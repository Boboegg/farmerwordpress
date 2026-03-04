<?php
/*
 * Plugin Name: 通用新聞卡片網格 (篇數自適應版)
 * Description: 圖片+標題+日期卡片網格，依篇數自動調整版型。
 *
 * 使用範例：
 *   [my_newss_loop cat="news" count="6" title="最新消息"]
 *   [my_newss_loop cat="podcast" count="4" title="Podcast 精選"]
 *
 * 【這個短碼在做什麼？】
 * - 自動抓某個「文章分類」最新的文章，做成卡片網格。
 * - 每張卡片：精選圖片 + 分類標籤 + 日期 + 標題 + 閱讀全文按鈕。
 * - 會依「實際文章篇數」自動調整版型：
 *   ・1 篇：置中、中等寬度卡片。
 *   ・2 篇：兩欄並排。
 *   ・3 篇以上：自動排成多欄網格，超過會自動換行。
 *
 * 【基本用法】
 * - 顯示某分類最新文章：
 *   [my_newss_loop cat="news" count="6" title="最新消息"]
 *
 * 【常用範例】
 * 1）新聞與公告
 *   - 最新消息：
 *     [my_newss_loop cat="news" count="6" title="最新消息"]
 *   - 活動快訊：
 *     [my_newss_loop cat="event" count="4" title="活動快訊"]
 *   - 媒體報導：
 *     [my_newss_loop cat="press" count="4" title="媒體報導"]
 *   - 網站公告：
 *     [my_newss_loop cat="notices" count="4" title="網站公告"]
 *
 * 2）保險相關
 *   - 經濟保險：
 *     [my_newss_loop cat="insurance" count="4" title="經濟保險"]
 *   - 職災保險：
 *     [my_newss_loop cat="occupation-ins" count="4" title="職災保險"]
 *   - 農作物保險：
 *     [my_newss_loop cat="crop-ins" count="4" title="農作物保險"]
 *   - 補助資源：
 *     [my_newss_loop cat="ins-subsidy-insurance" count="4" title="補助資源"]
 *
 * 3）健康與危害
 *   - 職業健康：
 *     [my_newss_loop cat="health" count="4" title="職業健康"]
 *   - 熱危害：
 *     [my_newss_loop cat="heat-stress" count="4" title="熱危害"]
 *   - 農藥安全：
 *     [my_newss_loop cat="chemical-safety" count="4" title="農藥安全"]
 *   - 骨骼肌肉：
 *     [my_newss_loop cat="musculoskeletal" count="4" title="骨骼肌肉"]
 *
 * 4）教育與農學堂
 *   - 農學堂文章：
 *     [my_newss_loop cat="agri-school" count="4" title="農學堂"]
 *   - 課程活動：
 *     [my_newss_loop cat="education-farmer" count="4" title="課程活動"]
 *
 * 5）影音與教材
 *   - Podcast 精選：
 *     [my_newss_loop cat="podcast" count="4" title="Podcast 精選"]
 *   - Youtube 影片：
 *     [my_newss_loop cat="youtube" count="4" title="Youtube 影片"]
 *   - 影片教學：
 *     [my_newss_loop cat="video" count="4" title="影片教學"]
 *   - 圖解懶人包：
 *     [my_newss_loop cat="infographic" count="4" title="圖解懶人包"]
 *
 * 6）下載與其他
 *   - 下載中心：
 *     [my_newss_loop cat="downloads" count="4" title="下載中心"]
 *
 * 【參數說明】
 * - cat   ：文章分類的 slug（後台右邊英文字）。
 * - count ：最多顯示幾篇文章（整體卡片數，不是一排幾個）。
 * - title ：區塊上方標題文字，可留空。
 *
 * 【版型說明】
 * - 一行幾張卡片是由 CSS 自動決定（視窗寬度），不是 count 決定。
 * - count 控制的是「總共幾張卡片」。
 */

add_shortcode('my_newss_loop', function($atts) {
    // 1. 參數：分類 slug、篇數、區塊標題
    $atts = shortcode_atts([
        'cat'   => '',
        'count' => 6,
        'title' => ''
    ], $atts);

    // 2. 查詢條件
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => (int)$atts['count'],
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ];
    if (!empty($atts['cat'])) {
        $args['category_name'] = sanitize_text_field($atts['cat']);
    }
    $query = new WP_Query($args);
    $post_count = (int)$query->found_posts;

    // 依篇數決定 layout class
    if ($post_count >= 3) {
        $layout_class = 'layout-multi';   // 3 篇以上
    } elseif ($post_count == 2) {
        $layout_class = 'layout-two';     // 2 篇
    } else {
        $layout_class = 'layout-single';  // 1 篇
    }

    // 標題區塊（可選）
    $title_html = !empty($atts['title'])
        ? "<h3 class='news-section-title'>" . esc_html($atts['title']) . "</h3>"
        : '';

    $output  = "<div class='news-loop-wrapper {$layout_class}'>";
    $output .= $title_html;
    $output .= "<div class='news-grid-container'>";

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $link       = get_permalink();
            $title      = get_the_title();
            $date       = get_the_date('Y.m.d');
            $categories = get_the_category();
            $cat_name   = !empty($categories) ? $categories[0]->name : '一般消息';
            $cat_slug   = !empty($categories) ? $categories[0]->slug : 'general';

            // 精選圖片（無圖時用預設）
            $thumb_id  = get_post_thumbnail_id();
            $thumb_url = wp_get_attachment_image_src($thumb_id, 'medium');
            $image     = ($thumb_url) ? $thumb_url[0]
                                      : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400';

            $safe_link  = esc_url($link);
            $safe_image = esc_url($image);
            $safe_title = esc_html($title);
            $safe_date  = esc_html($date);
            $safe_cat   = esc_html($cat_name);
            $safe_slug  = esc_attr($cat_slug);

            $output .= "
            <div class='news-grid-card'>
                <a href='{$safe_link}' class='card-image-link'>
                    <div class='card-image' style='background-image: url({$safe_image});'>
                        <span class='card-grid-label cat-{$safe_slug}'>{$safe_cat}</span>
                    </div>
                </a>
                <div class='card-grid-body'>
                    <span class='card-grid-date'>{$safe_date}</span>
                    <h3 class='card-grid-title'><a href='{$safe_link}'>{$safe_title}</a></h3>
                    <a href='{$safe_link}' class='card-grid-more'>閱讀全文 →</a>
                </div>
            </div>";
        }
        wp_reset_postdata();
    } else {
        $output .= '<div class="no-posts">目前尚無消息更新</div>';
    }

    $output .= '</div>'; // .news-grid-container
    $output .= '</div>'; // .news-loop-wrapper

    // 3. 內嵌 CSS
    $output .= "
    <style>
    .news-loop-wrapper { margin-bottom: 40px; }
    .news-section-title {
        font-size: 1.4rem; font-weight: 800; color: var(--brand-dark); margin: 0 0 25px 0;
        border-left: 5px solid var(--accent); padding-left: 15px;
    }
    .news-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
        padding: 0;
    }
    /* 篇數自適應：1 篇時不要太寬、居中顯示 */
    .layout-single .news-grid-container {
        display: flex;
        justify-content: center;
    }
    .layout-single .news-grid-card {
        max-width: 700px;
        width: 100%;
    }
    /* 2 篇時固定兩欄 */
    .layout-two .news-grid-container {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .news-grid-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .news-grid-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    .card-image-link {
        display: block;
        position: relative;
        height: 220px;
    }
    .card-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        position: relative;
        overflow: hidden;
    }
    .card-grid-label {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255,255,255,0.9);
        color: var(--brand);
        padding: 6px 12px;
        border-radius: var(--radius-badge);
        font-size: 0.8rem;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
        white-space: nowrap;
    }
    /* ===== 分類配色已移至 global.css 統一管理 ===== */
    .card-grid-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    .card-grid-date {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 500;
    }
    .card-grid-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 12px 0;
        line-height: 1.4;
        flex-grow: 1;
    }
    .card-grid-title a {
        color: inherit;
        text-decoration: none;
    }
    .card-grid-title a:hover { color: var(--brand); }
    .card-grid-more {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--brand);
        font-weight: 700;
        font-size: 0.9rem;
        padding: 8px 18px;
        border: 1.5px solid var(--brand);
        border-radius: var(--radius-btn);
        transition: all 0.3s ease;
        text-decoration: none;
        align-self: flex-start;
        margin-top: auto;
    }
    .card-grid-more:hover {
        background: var(--brand);
        color: white;
        transform: translateY(-2px);
    }
    .no-posts {
        grid-column: 1/-1;
        text-align: center;
        padding: 40px;
        color: #6b7280;
        font-size: 1rem;
    }
    @media (max-width: 768px) {
        .news-grid-container {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .card-image-link { height: 200px; }
        .layout-single .news-grid-card { max-width: 100%; }
    }
    </style>
    ";
    return $output;
});
