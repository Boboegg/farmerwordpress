<?php
/*
 * Plugin Name: Podcast 完整列表 — 網格排列版
 * Description: 用於 Podcast 專屬頁面，以網格排列所有 podcast 文章（非輪播）。
 *              自動抓取特色圖片或 YouTube 縮圖，支援燈箱播放。
 * Author: AI Assistant
 * Version: 1.0
 *
 * 【操作說明書 / Maintenance Guide】
 * 1. 功能：
 *    - 網格排列所有 podcast 分類的文章。
 *    - 點擊封面圖：有 YouTube → 燈箱播放；無 YouTube → 跳轉文章。
 *    - 點擊標題/按鈕：跳轉至文章內頁。
 *
 * 2. 短碼用法：
 *    - 基本：[podcast_full_archive]
 *    - 指定分類：[podcast_full_archive category="podcast,video"]
 *    - 限制數量：[podcast_full_archive count="12"]
 *    - 修改標題：[podcast_full_archive title="影音紀錄"]
 *
 * 【參數說明】
 * - category：分類 slug，逗號分隔（預設 podcast）
 * - count   ：抓取篇數（預設 99，全部顯示）
 * - title   ：區塊標題（預設 全部集數）
 */

add_shortcode('podcast_full_archive', function ($atts) {

    $atts = shortcode_atts([
        'category' => 'podcast',
        'count'    => 99,
        'title'    => '全部集數',
    ], $atts);

    // ─── 查詢 ───
    $query = new WP_Query([
        'category_name'  => $atts['category'],
        'posts_per_page' => intval($atts['count']),
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    $total = $query->found_posts;

    // ─── 開始輸出 ───
    $uid = 'pfa-' . wp_rand(1000, 9999);
    $o   = '<div class="pfa-wrapper" id="' . $uid . '">';

    // 統計 + 標題
    $o .= '<div class="pfa-toolbar">';
    $o .= '  <h2 class="pfa-section-title"><i class="fas fa-list"></i> ' . esc_html($atts['title']) . '</h2>';
    $o .= '  <span class="pfa-total-badge"><i class="fas fa-podcast"></i> 共 ' . $total . ' 集</span>';
    $o .= '</div>';

    if ($query->have_posts()) {
        $o .= '<div class="pfa-grid">';
        $ep = $total;
        while ($query->have_posts()) {
            $query->the_post();
            $pid = get_the_ID();

            $title = get_the_title();
            $link  = get_permalink();
            $date  = get_the_date('Y.m.d');
            $img   = get_the_post_thumbnail_url($pid, 'large');

            // ─── YouTube ID 偵測 ───
            $video_id = '';
            $content  = get_post_field('post_content', $pid);
            $meta     = get_post_meta($pid, '_elementor_data', true);
            $haystack = stripslashes($content . ' ' . (is_string($meta) ? $meta : json_encode($meta)));

            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $haystack, $m)) {
                $video_id = $m[1];
                if (!$img) {
                    $img = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
                }
            }
            if (!$img) {
                $img = 'https://images.unsplash.com/photo-1478737270239-2f02b77ac6d5?q=80&w=600&auto=format&fit=crop';
            }

            $raw     = strip_tags(get_the_excerpt());
            $excerpt = mb_strimwidth($raw, 0, 80, '…');

            // ─── 判斷類型 ───
            $has_video = !empty($video_id);
            $cats      = get_the_category();
            $cat_name  = !empty($cats) ? $cats[0]->name : 'Podcast';
            $cat_slug  = !empty($cats) ? $cats[0]->slug : '';

            $is_video = (strpos($cat_slug, 'video') !== false || strpos($cat_slug, 'youtube') !== false || $has_video);
            $type_icon  = $is_video ? 'fas fa-video' : 'fas fa-microphone';
            $type_color = $is_video ? '#8B2D14' : '#4A6932';
            $btn_text   = $is_video ? '觀看影片' : '收聽節目';
            $btn_icon   = $is_video ? 'fas fa-play' : 'fas fa-headphones';
            $overlay_icon = $is_video ? 'fas fa-play' : 'fas fa-headphones';

            $lightbox = $has_video ? ' data-video-id="' . esc_attr($video_id) . '"' : '';
            $thumb_cls = $has_video ? 'pfa-thumb-link pfa-lightbox-trigger' : 'pfa-thumb-link';

            $o .= '
            <div class="pfa-card">
                <a href="' . esc_url($link) . '" class="' . $thumb_cls . '"' . $lightbox . '>
                    <div class="pfa-thumb">
                        <span class="pfa-ep-badge">EP.' . $ep . '</span>
                        <img src="' . esc_url($img) . '" alt="' . esc_attr($title) . '" loading="lazy">
                        <div class="pfa-play-overlay"><i class="' . $overlay_icon . '"></i></div>
                    </div>
                </a>
                <div class="pfa-body">
                    <div class="pfa-meta">
                        <span class="pfa-type-badge" style="background:' . $type_color . '"><i class="' . $type_icon . '"></i> ' . esc_html($cat_name) . '</span>
                        <span class="pfa-date"><i class="far fa-calendar-alt"></i> ' . $date . '</span>
                    </div>
                    <h3 class="pfa-title"><a href="' . esc_url($link) . '">' . esc_html($title) . '</a></h3>
                    <p class="pfa-excerpt">' . esc_html($excerpt) . '</p>
                    <a href="' . esc_url($link) . '" class="pfa-cta"><i class="' . $btn_icon . '"></i> ' . $btn_text . '</a>
                </div>
            </div>';
            $ep--;
        }
        $o .= '</div>'; // grid
        wp_reset_postdata();
    } else {
        $o .= '
        <div class="pfa-empty">
            <i class="fas fa-seedling"></i>
            <p>目前尚無影音內容，敬請期待！</p>
        </div>';
    }

    // ─── 燈箱 ───
    $o .= '
    <div class="pfa-lightbox-overlay" id="' . $uid . '-lightbox">
        <div class="pfa-lightbox-box">
            <button class="pfa-lightbox-close"><i class="fas fa-times"></i></button>
            <div class="pfa-lightbox-video">
                <iframe class="pfa-lightbox-iframe" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </div>
    </div>';

    $o .= '</div>'; // wrapper

    // =================================================================
    // CSS
    // =================================================================
    $o .= '
    <style>
    .pfa-wrapper {
        font-family: "Noto Sans TC", "Lato", sans-serif;
        color: #1f2937;
        width: 100%;
    }

    /* ── Toolbar ── */
    .pfa-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--brand-light);
    }
    .pfa-section-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--brand-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pfa-total-badge {
        font-size: .85rem;
        font-weight: 700;
        color: var(--brand);
        background: var(--brand-light);
        padding: 6px 16px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Grid ── */
    .pfa-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    /* ── Card ── */
    .pfa-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(0,0,0,.04);
        transition: all .35s cubic-bezier(.25,.8,.25,1);
        display: flex;
        flex-direction: column;
    }
    .pfa-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(92,134,7,.12);
        border-color: var(--brand);
    }

    /* ── Thumbnail ── */
    .pfa-thumb-link { display: block; text-decoration: none; }
    .pfa-thumb {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #111;
    }
    .pfa-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .6s ease, opacity .4s;
        opacity: .88;
    }
    .pfa-card:hover .pfa-thumb img {
        transform: scale(1.08);
        opacity: 1;
    }
    .pfa-ep-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0,0,0,.65);
        color: #fff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 800;
        font-family: "Lato", sans-serif;
        letter-spacing: 1px;
        z-index: 3;
        backdrop-filter: blur(4px);
    }
    .pfa-play-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(.7);
        width: 60px;
        height: 60px;
        background: rgba(92,134,7,.85);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        opacity: 0;
        transition: all .4s cubic-bezier(.175,.885,.32,1.275);
        z-index: 3;
        padding-left: 3px;
    }
    .pfa-card:hover .pfa-play-overlay {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

    /* ── Body ── */
    .pfa-body {
        padding: 20px 22px 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .pfa-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    .pfa-type-badge {
        color: #fff;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .pfa-date {
        font-size: .8rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .pfa-title {
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.5;
        margin: 0 0 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .pfa-title a {
        text-decoration: none;
        color: #1f2937;
        transition: color .3s;
    }
    .pfa-title a:hover { color: var(--brand); }
    .pfa-excerpt {
        font-size: .88rem;
        color: #6b7280;
        line-height: 1.6;
        margin: 0 0 16px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .pfa-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 50px;
        font-weight: 700;
        font-size: .88rem;
        text-decoration: none;
        background: var(--brand-light);
        color: var(--brand);
        border: 1px solid rgba(92,134,7,.15);
        transition: all .3s;
        align-self: flex-start;
    }
    .pfa-cta:hover {
        background: var(--brand);
        color: #fff;
        border-color: var(--brand);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(92,134,7,.25);
    }

    /* ── Empty ── */
    .pfa-empty {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
    .pfa-empty i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    .pfa-empty p {
        font-size: 1.05rem;
        margin: 0;
    }

    /* ── Lightbox ── */
    .pfa-lightbox-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,.92);
        z-index: 99999;
        display: none;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity .35s ease;
    }
    .pfa-lightbox-overlay.pfa-active { display: flex; opacity: 1; }
    .pfa-lightbox-box {
        position: relative;
        width: 92%;
        max-width: 860px;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0,0,0,.6);
    }
    .pfa-lightbox-video {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
    }
    .pfa-lightbox-video iframe {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
    }
    .pfa-lightbox-close {
        position: absolute;
        top: -44px;
        right: 0;
        background: none;
        border: none;
        color: #fff;
        font-size: 28px;
        cursor: pointer;
        opacity: .7;
        transition: opacity .3s;
    }
    .pfa-lightbox-close:hover { opacity: 1; }

    /* ── RWD ── */
    @media (max-width: 992px) {
        .pfa-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
    }
    @media (max-width: 600px) {
        .pfa-grid { grid-template-columns: 1fr; gap: 16px; }
        .pfa-toolbar { flex-direction: column; align-items: flex-start; gap: 10px; }
        .pfa-thumb { height: 220px; }
    }
    </style>';

    // =================================================================
    // JavaScript
    // =================================================================
    $o .= '
    <script>
    (function(){
        var w = document.getElementById("' . $uid . '");
        if(!w) return;
        var overlay  = document.getElementById("' . $uid . '-lightbox");
        var iframe   = overlay ? overlay.querySelector(".pfa-lightbox-iframe") : null;
        var closeBtn = overlay ? overlay.querySelector(".pfa-lightbox-close") : null;
        var triggers = w.querySelectorAll(".pfa-lightbox-trigger");

        triggers.forEach(function(t){
            t.addEventListener("click", function(e){
                e.preventDefault();
                var vid = t.getAttribute("data-video-id");
                if(vid && iframe){
                    iframe.src = "https://www.youtube.com/embed/" + vid + "?autoplay=1&rel=0";
                    overlay.classList.add("pfa-active");
                }
            });
        });

        function closeLB(){
            if(overlay) overlay.classList.remove("pfa-active");
            if(iframe) iframe.src = "";
        }
        if(closeBtn) closeBtn.addEventListener("click", closeLB);
        if(overlay) overlay.addEventListener("click", function(e){ if(e.target===overlay) closeLB(); });
        document.addEventListener("keydown", function(e){ if(e.key==="Escape") closeLB(); });
    })();
    </script>';

    return $o;
});
