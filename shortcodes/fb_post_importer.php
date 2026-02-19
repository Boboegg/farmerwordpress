<?php
/*
 * Plugin Name: Facebook 貼文自動匯入 (WP Cron)
 * Description: 定時從 Facebook 粉絲專頁抓取新貼文，自動建立 WordPress 草稿文章，供管理員審核後發佈。
 *
 * 【運作方式】
 * - 每小時執行一次（透過 WP-Cron）
 * - 只匯入「之前未匯入過」的貼文（用 fb_post_id meta 去重複）
 * - 匯入為「草稿（draft）」，不會自動公開，需管理員手動審查後發佈
 *
 * 【匯入的文章資訊】
 * - 標題：取貼文前 50 字
 * - 內容：完整貼文文字 + 原始 FB 連結
 * - 分類：可在下方 IMPORT_CATEGORY 設定（分類 slug）
 * - 特色圖片：若貼文有圖片則自動下載並設定
 *
 * 【需要設定的 WordPress 選項】
 * - fb_page_id          ：粉絲專頁 Page ID
 * - fb_page_access_token：長期 Page Access Token
 * - fb_import_category  ：匯入文章的分類 slug（預設 'fb-posts'）
 *
 * 【手動觸發測試（在 functions.php 貼入）】
 * do_action('fb_import_posts_event');
 */

// ────────────────────────────────────────────────
// 1. 排程：每小時執行一次匯入
// ────────────────────────────────────────────────
add_action('wp', 'fb_importer_schedule_cron');
function fb_importer_schedule_cron() {
    if (!wp_next_scheduled('fb_import_posts_event')) {
        wp_schedule_event(time(), 'hourly', 'fb_import_posts_event');
    }
}

// 外掛停用時清除排程（若未來轉成正式外掛用）
register_deactivation_hook(__FILE__, function() {
    wp_clear_scheduled_hook('fb_import_posts_event');
});

// ────────────────────────────────────────────────
// 2. 主要匯入邏輯
// ────────────────────────────────────────────────
add_action('fb_import_posts_event', 'fb_importer_run');
function fb_importer_run() {
    $page_id      = get_option('fb_page_id', '');
    $access_token = get_option('fb_page_access_token', '');
    $category     = get_option('fb_import_category', 'fb-posts');

    if (empty($page_id) || empty($access_token)) {
        error_log('[FB Importer] 尚未設定 fb_page_id 或 fb_page_access_token。');
        return;
    }

    // 抓取最近 10 則貼文
    $fields  = 'id,message,story,created_time,full_picture,permalink_url';
    $api_url = add_query_arg([
        'fields'       => $fields,
        'limit'        => 10,
        'access_token' => $access_token,
    ], "https://graph.facebook.com/v19.0/{$page_id}/posts");

    $response = wp_remote_get($api_url, ['timeout' => 15]);

    if (is_wp_error($response)) {
        error_log('[FB Importer] API 請求失敗：' . $response->get_error_message());
        return;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($body['error'])) {
        error_log('[FB Importer] FB API 錯誤：' . $body['error']['message']);
        return;
    }

    $posts = $body['data'] ?? [];

    if (empty($posts)) {
        error_log('[FB Importer] 沒有取得任何貼文。');
        return;
    }

    $imported = 0;

    foreach ($posts as $fb_post) {
        $fb_id  = sanitize_text_field($fb_post['id'] ?? '');
        $message = trim($fb_post['message'] ?? ($fb_post['story'] ?? ''));
        $link    = $fb_post['permalink_url'] ?? '';
        $image   = $fb_post['full_picture'] ?? '';
        $time    = $fb_post['created_time'] ?? '';

        // 略過沒有文字的貼文（例如純分享）
        if (empty($message) || empty($fb_id)) {
            continue;
        }

        // 去重複：檢查是否已有相同 fb_post_id 的文章
        $existing = get_posts([
            'post_type'   => 'post',
            'post_status' => 'any',
            'meta_key'    => 'fb_post_id',
            'meta_value'  => $fb_id,
            'numberposts' => 1,
            'fields'      => 'ids',
        ]);

        if (!empty($existing)) {
            continue; // 已匯入過，跳過
        }

        // 產生標題（取前 50 字）
        $title = mb_strlen($message) > 50
            ? mb_substr($message, 0, 50) . '…'
            : $message;

        // 組合文章內容
        $content  = wp_kses_post(nl2br($message));
        $content .= "\n\n";
        $content .= '<p class="fb-source-link">📌 <a href="' . esc_url($link) . '" target="_blank" rel="noopener">查看原始 Facebook 貼文</a></p>';

        // 取得或建立分類
        $cat_obj = get_category_by_slug($category);
        $cat_id  = $cat_obj ? $cat_obj->term_id : 0;

        if (!$cat_id && !empty($category)) {
            $new_cat = wp_create_category($category);
            $cat_id  = is_wp_error($new_cat) ? 0 : $new_cat;
        }

        // 建立 WordPress 草稿文章
        $post_date = !empty($time) ? date('Y-m-d H:i:s', strtotime($time)) : current_time('mysql');

        $post_data = [
            'post_title'    => sanitize_text_field($title),
            'post_content'  => $content,
            'post_status'   => 'draft',      // 草稿，需人工審查
            'post_type'     => 'post',
            'post_date'     => $post_date,
            'post_date_gmt' => get_gmt_from_date($post_date),
        ];

        if ($cat_id) {
            $post_data['post_category'] = [$cat_id];
        }

        $post_id = wp_insert_post($post_data, true);

        if (is_wp_error($post_id)) {
            error_log('[FB Importer] 建立文章失敗：' . $post_id->get_error_message());
            continue;
        }

        // 儲存 FB Post ID 避免重複匯入
        update_post_meta($post_id, 'fb_post_id', $fb_id);
        update_post_meta($post_id, 'fb_post_url', esc_url_raw($link));
        update_post_meta($post_id, 'fb_imported_at', current_time('mysql'));

        // 下載並設定特色圖片
        if (!empty($image)) {
            fb_importer_set_featured_image($post_id, $image, $title);
        }

        $imported++;
    }

    if ($imported > 0) {
        error_log("[FB Importer] 成功匯入 {$imported} 則新貼文（草稿）。");
    }
}

// ────────────────────────────────────────────────
// 3. 下載圖片並設為特色圖片
// ────────────────────────────────────────────────
function fb_importer_set_featured_image($post_id, $image_url, $title = '') {
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $tmp = download_url(esc_url_raw($image_url));

    if (is_wp_error($tmp)) {
        error_log('[FB Importer] 圖片下載失敗：' . $tmp->get_error_message());
        return;
    }

    $file_array = [
        'name'     => sanitize_file_name('fb-image-' . $post_id . '.jpg'),
        'tmp_name' => $tmp,
    ];

    $attachment_id = media_handle_sideload($file_array, $post_id, sanitize_text_field($title));

    // 清理暫存
    @unlink($tmp);

    if (is_wp_error($attachment_id)) {
        error_log('[FB Importer] 圖片上傳失敗：' . $attachment_id->get_error_message());
        return;
    }

    set_post_thumbnail($post_id, $attachment_id);
}

// ────────────────────────────────────────────────
// 4. 後台設定頁（wp-admin > 設定 > FB 整合）
// ────────────────────────────────────────────────
add_action('admin_menu', function() {
    add_options_page(
        'Facebook 整合設定',
        'FB 整合設定',
        'manage_options',
        'fb-integration',
        'fb_importer_settings_page'
    );
});

function fb_importer_settings_page() {
    if (isset($_POST['fb_save_settings']) && check_admin_referer('fb_settings_nonce')) {
        update_option('fb_page_id',           sanitize_text_field($_POST['fb_page_id'] ?? ''));
        update_option('fb_page_access_token', sanitize_text_field($_POST['fb_page_access_token'] ?? ''));
        update_option('fb_import_category',   sanitize_text_field($_POST['fb_import_category'] ?? 'fb-posts'));
        echo '<div class="notice notice-success"><p>設定已儲存。</p></div>';
    }

    $page_id      = get_option('fb_page_id', '');
    $access_token = get_option('fb_page_access_token', '');
    $category     = get_option('fb_import_category', 'fb-posts');
    ?>
    <div class="wrap">
        <h1>Facebook 整合設定</h1>

        <form method="post">
            <?php wp_nonce_field('fb_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="fb_page_id">粉絲專頁 Page ID</label></th>
                    <td>
                        <input type="text" id="fb_page_id" name="fb_page_id"
                               value="<?php echo esc_attr($page_id); ?>" class="regular-text">
                        <p class="description">數字 ID，例如：<code>123456789012345</code></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="fb_page_access_token">Page Access Token（長期）</label></th>
                    <td>
                        <textarea id="fb_page_access_token" name="fb_page_access_token"
                                  rows="3" class="large-text"><?php echo esc_textarea($access_token); ?></textarea>
                        <p class="description">請使用<strong>長期 Token</strong>，避免過期。取得方式見下方說明。</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="fb_import_category">匯入文章分類</label></th>
                    <td>
                        <input type="text" id="fb_import_category" name="fb_import_category"
                               value="<?php echo esc_attr($category); ?>" class="regular-text">
                        <p class="description">分類 slug，例如：<code>fb-posts</code>。若不存在會自動建立。</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('儲存設定', 'primary', 'fb_save_settings'); ?>
        </form>

        <hr>
        <h2>手動觸發匯入</h2>
        <p>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('options-general.php?page=fb-integration&run_import=1'), 'fb_run_import')); ?>"
               class="button button-secondary">立即執行匯入</a>
        </p>
        <?php
        if (isset($_GET['run_import']) && check_admin_referer('fb_run_import')) {
            fb_importer_run();
            echo '<div class="notice notice-info"><p>匯入完成，請至文章列表查看草稿。</p></div>';
        }
        ?>

        <hr>
        <h2>如何取得長期 Page Access Token</h2>
        <ol>
            <li>前往 <strong>developers.facebook.com</strong> 建立 App</li>
            <li>在 <strong>Graph API Explorer</strong> 選擇你的 App 與粉絲專頁</li>
            <li>勾選權限：<code>pages_read_engagement</code>、<code>pages_show_list</code></li>
            <li>取得短期 Token 後，用 <strong>Access Token Debugger</strong> → 「延長 Token 有效期限」</li>
            <li>將長期 Token 貼到上方欄位</li>
        </ol>
    </div>
    <?php
}
