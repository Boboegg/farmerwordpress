<?php
/*
 * Plugin Name: Facebook 粉專貼文顯示 [fb_feed_widget]
 * Description: 從 Facebook Graph API 抓取粉絲專頁最新貼文，顯示在頁面或側邊欄。
 *
 * 使用範例：
 *   [fb_feed_widget count="5"]
 *   [fb_feed_widget count="3" show_image="true"]
 *
 * 【參數說明】
 * - count      ：顯示篇數（預設 5，最多 10）
 * - show_image ：是否顯示縮圖（預設 false）
 *
 * 【需要設定的 WordPress 選項（在後台 設定 > FB 整合 設定，或直接用 wp_options）】
 * - fb_page_id          ：粉絲專頁的 Page ID（數字）
 * - fb_page_access_token：長期 Page Access Token
 *
 * 【如何取得 Access Token】
 * 1. 前往 https://developers.facebook.com/ 建立 App
 * 2. 在 Graph API Explorer 取得 Page Access Token
 * 3. 用 Token Debugger 轉成長期 Token（永久不過期）
 * 4. 將 Token 存到 WordPress 設定
 */

add_shortcode('fb_feed_widget', function($atts) {
    $atts = shortcode_atts([
        'count'      => 5,
        'show_image' => 'false',
    ], $atts);

    $count      = min((int)$atts['count'], 10);
    $show_image = filter_var($atts['show_image'], FILTER_VALIDATE_BOOLEAN);

    // 從 WordPress 選項讀取設定
    $page_id      = get_option('fb_page_id', '');
    $access_token = get_option('fb_page_access_token', '');

    if (empty($page_id) || empty($access_token)) {
        if (current_user_can('manage_options')) {
            return '<p style="color:red;">⚠️ 請先在 WordPress 後台設定 <strong>fb_page_id</strong> 與 <strong>fb_page_access_token</strong>。</p>';
        }
        return '';
    }

    // 嘗試從快取取得（避免每次頁面載入都打 API）
    $cache_key  = 'fb_feed_cache_' . $page_id . '_' . $count;
    $posts      = get_transient($cache_key);

    if (false === $posts) {
        $fields   = 'id,message,story,created_time,full_picture,permalink_url';
        $api_url  = add_query_arg([
            'fields'       => $fields,
            'limit'        => $count,
            'access_token' => $access_token,
        ], "https://graph.facebook.com/v19.0/{$page_id}/posts");

        $response = wp_remote_get($api_url, ['timeout' => 10]);

        if (is_wp_error($response)) {
            return '<p>無法取得 Facebook 資料，請稍後再試。</p>';
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!empty($body['error'])) {
            if (current_user_can('manage_options')) {
                return '<p style="color:red;">FB API 錯誤：' . esc_html($body['error']['message']) . '</p>';
            }
            return '';
        }

        $posts = $body['data'] ?? [];

        // 快取 30 分鐘
        set_transient($cache_key, $posts, 30 * MINUTE_IN_SECONDS);
    }

    if (empty($posts)) {
        return '<p class="fb-no-posts">目前沒有最新貼文。</p>';
    }

    $output = '<div class="fb-feed-widget">';
    $output .= '<div class="fb-feed-header"><span class="fb-icon">f</span> 粉專最新動態</div>';
    $output .= '<ul class="fb-feed-list">';

    foreach ($posts as $post) {
        $message      = $post['message'] ?? ($post['story'] ?? '');
        $link         = $post['permalink_url'] ?? '#';
        $image        = $post['full_picture'] ?? '';
        $created_time = $post['created_time'] ?? '';
        $date_display = '';

        if (!empty($created_time)) {
            $timestamp    = strtotime($created_time);
            $date_display = date_i18n('Y/m/d', $timestamp);
        }

        // 截斷過長文字
        $short_message = mb_strlen($message) > 100
            ? mb_substr($message, 0, 100) . '…'
            : $message;

        $output .= '<li class="fb-feed-item">';

        if ($show_image && !empty($image)) {
            $output .= '<a href="' . esc_url($link) . '" target="_blank" rel="noopener">';
            $output .= '<img src="' . esc_url($image) . '" alt="" class="fb-feed-thumb" loading="lazy">';
            $output .= '</a>';
        }

        $output .= '<div class="fb-feed-content">';
        if (!empty($date_display)) {
            $output .= '<span class="fb-feed-date">' . esc_html($date_display) . '</span>';
        }
        if (!empty($short_message)) {
            $output .= '<p class="fb-feed-text">' . esc_html($short_message) . '</p>';
        }
        $output .= '<a href="' . esc_url($link) . '" target="_blank" rel="noopener" class="fb-feed-link">在 Facebook 查看 →</a>';
        $output .= '</div>';
        $output .= '</li>';
    }

    $output .= '</ul></div>';

    // 內嵌樣式
    $output .= '<style>
    .fb-feed-widget { font-family: inherit; }
    .fb-feed-header { background:#1877f2; color:#fff; padding:8px 12px; font-weight:bold; border-radius:6px 6px 0 0; display:flex; align-items:center; gap:6px; }
    .fb-icon { background:#fff; color:#1877f2; border-radius:4px; padding:0 5px; font-weight:900; font-size:14px; }
    .fb-feed-list { list-style:none; margin:0; padding:0; border:1px solid #e0e0e0; border-top:none; border-radius:0 0 6px 6px; }
    .fb-feed-item { padding:10px 12px; border-bottom:1px solid #f0f0f0; }
    .fb-feed-item:last-child { border-bottom:none; }
    .fb-feed-thumb { width:100%; height:120px; object-fit:cover; border-radius:4px; margin-bottom:6px; display:block; }
    .fb-feed-date { font-size:11px; color:#888; display:block; margin-bottom:3px; }
    .fb-feed-text { font-size:13px; color:#333; margin:0 0 6px; line-height:1.5; }
    .fb-feed-link { font-size:12px; color:#1877f2; text-decoration:none; }
    .fb-feed-link:hover { text-decoration:underline; }
    </style>';

    return $output;
});
