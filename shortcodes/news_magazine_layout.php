<?php
/*
 * Plugin Name: 圖文雜誌風格最新動態 (PHP 自動輪播 + 預覽優先版 - 終極詳解版)
 * Description: 自動抓取文章，左側大圖輪播，右側列表點擊切換預覽，需按「閱讀全文」才跳轉。
 * Author: AI Assistant
 * Version: 3.0 (Auto-Route + Full Category Colors)
 *
 * 【操作說明書 / Maintenance Guide】
 * 1. 功能：此程式會自動抓取指定分類的最新文章 (預設 5 篇)。
 * - 第 1 篇：顯示在左側大圖 (輪播焦點)。
 * - 第 2~5 篇：顯示在右側列表。
 * - 互動：點擊右側列表標題，左側大圖會切換預覽；點擊左側「閱讀全文」才會跳轉。
 *
 * 2. 如何使用 (Elementor 短碼)：
 * - 基本用法：[news_magazine_layout]
 * - 指定分類：[news_magazine_layout category="news"]
 * - 修改標題：[news_magazine_layout title="近期活動"]
 * - 修改數量：[news_magazine_layout count="6"]
 * - 指定連結：[news_magazine_layout more_url="/custom-page/"]
 *   （若不指定 more_url，會自動依 category 參數導向對應分類頁面）
 *
 * 3. 常見維護修改：
 * - 修改「閱讀全文」按鈕文字：搜尋 "閱讀全文"
 * - 修改摘要字數：搜尋 "wp_trim_words"
 * - 修改顏色樣式：捲動至下方的 <style> 區塊
 *
 * 【參數說明】
 * - category ：分類 slug，留空則抓全站文章
 * - count    ：抓取篇數（建議 5 篇，太多列表會太長）
 * - title    ：區塊左上角標題文字
 * - more_url ：右上角「查看全部」連結網址
 */

add_shortcode('news_magazine_layout', function($atts) {

    // =================================================================
    // 1. 設定區域：接收短碼參數 (預設值設定)
    // =================================================================
    $atts = shortcode_atts([
        'category' => '',
        'count'    => 5,
        'title'    => '最新動態',
        'more_url' => ''   // 留空 = 自動依 category 決定
    ], $atts);

    // =================================================================
    // 1.1 分類 → 對應頁面 URL 對照表
    //     ★ 新增分類時請同步更新此表 & docs/article-category-slugs.md
    // =================================================================
    $category_url_map = [
        // 最新消息系列
        'news'                 => '/thenews/',
        'press'                => '/thenews/',
        'event'                => '/thenews/',
        'notices'              => '/thenews/',
        // 安全裝備
        'equipment'            => '/occupational-safety/',
        'ppe'                  => '/occupational-safety/personal-protective-equipment/',
        'assistive-tech'       => '/occupational-safety/labor-saving-machinery/',
        'media'                => '/occupational-safety/',
        // 環境設施
        'environment'          => '/occupational-safety/',
        'greenhouse'           => '/occupational-safety/greenhouse-zone/',
        // 研究出版
        'research'             => '/research-result/research-publication/',
        'annual-reports'       => '/research-result/research-publication/',
        'policy-papers'        => '/research-result/research-publication/',
        'data-viz'             => '/research-result/infographic/',
        // 影音
        'podcast'              => '/research-result/podcast/',
        'youtube'              => '/research-result/podcast/',
        // 下載中心
        'downloads'            => '/research-result/download-zone/',
        'infographic'          => '/research-result/download-zone/',
        'video'                => '/research-result/download-zone/',
        // 職業健康
        'health'               => '/healthgood/',
        'heat-stress'          => '/healthgood/subpage-heat/',
        'chemical-safety'      => '/healthgood/subpage-pesticide/',
        'musculoskeletal'      => '/healthgood/subpage-msd/',
        // 經濟保險
        'insurance'            => '/economic-insurance/',
        'occupation-ins'       => '/economic-insurance/occupational-accident-insurance/',
        'ins-subsidy-insurance'=> '/economic-insurance/agricultural-subsidy-resources/',
        'crop-ins'             => '/economic-insurance/crop-insurance/',
        // 農學堂
        'agri-school'          => '/farmer-study/',
        'education-farmer'     => '/farmer-study/',
    ];

    // 如果使用者沒有手動指定 more_url，就自動從對照表查找
    if (empty($atts['more_url'])) {
        $cat_key = sanitize_text_field($atts['category']);
        $atts['more_url'] = isset($category_url_map[$cat_key])
            ? $category_url_map[$cat_key]
            : '/thenews/';  // 最終預設
    }

    // =================================================================
    // 2. 建立查詢：向 WordPress 資料庫請求文章
    // =================================================================
    $args = [
        'posts_per_page' => (int)$atts['count'],   // 強制整數，防止型別錯誤
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ];
    if (!empty($atts['category'])) {
        $args['category_name'] = sanitize_text_field($atts['category']); // 同 my_news_loop 做法
    }
    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p style="color:#666; padding:20px;">目前尚無最新動態 (分類: ' . esc_html($atts['category']) . ')。</p>';
    }

    // =================================================================
    // 3. 資料預處理：將資料存入陣列，方便後續 HTML 組裝
    // =================================================================
    $posts_data = [];
    while ($query->have_posts()) {
        $query->the_post();

        $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$img) {
            // ★ 維護提醒：若要更換預設圖片，請修改下方網址
            $img = 'https://images.unsplash.com/photo-1625246333195-5519a49d75f0?q=80&w=800';
        }

        $cats     = get_the_category();
        $cat_name = !empty($cats) ? $cats[0]->name : '公告';
        $cat_slug = !empty($cats) ? $cats[0]->slug : 'general';

        $posts_data[] = [
            'title'    => get_the_title(),
            'link'     => get_permalink(),
            'date'     => get_the_date('Y.m.d'),
            // ★ wp_trim_words(內容, 字數, 結尾符號) -> '' 代表不顯示點點點
            'desc'     => wp_trim_words(get_the_excerpt(), 45, ''),
            'img'      => $img,
            'cat_name' => $cat_name,
            'cat_slug' => $cat_slug,
        ];
    }
    wp_reset_postdata();

    // =================================================================
    // 4. 組裝 HTML 結構
    // =================================================================
    // 產生唯一 ID，避免同一頁多個短碼時 JS 衝突
    static $instance_count = 0;
    $instance_count++;
    $widget_id = 'news-mag-widget-' . $instance_count;

    $output = '<div class="news-widget-wrapper" id="' . esc_attr($widget_id) . '">';

    // 4.1 區塊標題欄
    $output .= '
    <div class="section-head">
        <div class="head-title">' . esc_html($atts['title']) . '</div>
        <a href="' . esc_url($atts['more_url']) . '" class="head-link">查看全部 <i class="fas fa-arrow-right"></i></a>
    </div>';

    // 4.2 內容容器開始
    $output .= '<div class="news-container-v2">';

    // --- 左側：大圖幻燈片區 ---
    $output .= '<div class="news-featured-container">';
    foreach ($posts_data as $index => $post) {
        $active_class = ($index === 0) ? 'active' : '';
        $output .= '
        <div class="news-featured-slide ' . $active_class . '" data-index="' . $index . '">
            <a href="' . esc_url($post['link']) . '" class="nf-image-link">
                <div class="nf-image">
                    <img src="' . esc_url($post['img']) . '" alt="' . esc_attr($post['title']) . '">
                </div>
            </a>
            <div class="nf-content">
                <span class="nf-tag cat-' . esc_attr($post['cat_slug']) . '">' . esc_html($post['cat_name']) . '</span>
                <h3 class="nf-title"><a href="' . esc_url($post['link']) . '">' . esc_html($post['title']) . '</a></h3>
                <p class="nf-desc">' . esc_html($post['desc']) . '</p>
                <a href="' . esc_url($post['link']) . '" class="nf-link">閱讀全文 →</a>
            </div>
        </div>';
    }
    $output .= '</div>'; // 左側結束

    // --- 右側：新聞列表選單 ---
    $output .= '<div class="news-list-side">';
    foreach ($posts_data as $index => $post) {
        $active_class = ($index === 0) ? 'active' : '';
        $output .= '
        <div class="news-item-side ' . $active_class . '" data-index="' . $index . '">
            <div class="ni-meta">
                <span class="ni-badge cat-' . esc_attr($post['cat_slug']) . '">' . esc_html($post['cat_name']) . '</span>
                <span>' . esc_html($post['date']) . '</span>
            </div>
            <div class="ni-title" style="cursor: pointer;">' . esc_html($post['title']) . '</div>
            <div class="ni-desc-mobile">' . esc_html($post['desc']) . '</div>
        </div>';
    }
    $output .= '</div>'; // 右側結束
    $output .= '</div></div>'; // 主容器結束

    // =================================================================
    // 5. CSS 樣式表
    // =================================================================
    $output .= '
    <style>
    .news-widget-wrapper {
        font-family: "Noto Sans TC", sans-serif; color: #333; margin: 0 auto; width: 100%;
    }
    .news-widget-wrapper a { text-decoration: none; color: inherit; transition: 0.3s; }
    .news-widget-wrapper img { max-width: 100%; display: block; }
    /* .section-head 已移至 global.css */
    /* .head-title 和 .head-link 已移至 global.css */
    .news-container-v2 {
        display: flex; gap: 30px;
        background: #ffffff; border-radius: 16px; padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eee;
        min-height: 400px;
    }
    .news-featured-container { flex: 1; position: relative; overflow: hidden; }
    .news-featured-slide { display: none; flex-direction: column; animation: fadeIn 0.5s ease; }
    .news-featured-slide.active { display: flex; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .nf-image { height: 250px; overflow: hidden; position: relative; border-radius: 12px; margin-bottom: 15px; }
    .nf-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .news-featured-slide:hover .nf-image img { transform: scale(1.05); }
    .nf-content { padding-top: 5px; }
    .nf-tag {
        background: #d4a017; color: white; font-size: 0.8rem; font-weight: 700;
        padding: 4px 10px; border-radius: 4px; display: inline-block; margin-bottom: 10px;
    }
    .nf-title { font-size: 1.3rem; font-weight: 800; color: #222; margin: 0 0 10px 0; line-height: 1.4; }
    .nf-title a:hover { color: var(--brand); }
    .nf-desc {
        font-size: 0.95rem; color: #555; line-height: 1.6; margin: 0 0 15px 0;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .nf-link { font-weight: 700; color: var(--brand); font-size: 0.95rem; }
    .nf-link:hover { text-decoration: underline; }
    .news-list-side {
        flex: 1; display: flex; flex-direction: column;
        border-left: 1px solid #eee; padding-left: 30px;
    }
    .news-item-side {
        padding: 15px; border-bottom: 1px dashed #eee;
        display: flex; flex-direction: column; cursor: pointer;
        border-radius: 8px; transition: all 0.3s; margin-bottom: 5px;
        border-left: 3px solid transparent;
    }
    .news-item-side:hover { background-color: #f9f9f9; }
    .news-item-side.active {
        background-color: var(--brand-light);
        border-left-color: var(--brand);
    }
    .ni-meta {
        font-size: 0.85rem; color: #888; margin-bottom: 6px;
        display: flex; align-items: center; gap: 10px;
    }
    .ni-badge {
        background: #eef2f5; color: #555; padding: 2px 8px;
        border-radius: 4px; font-weight: 600; font-size: 0.8rem;
    }
    /* ===== 分類配色已移至 global.css 統一管理 ===== */
    .ni-title { font-size: 1.05rem; font-weight: 500; color: #222; line-height: 1.5; transition: 0.2s; }
    .ni-title:hover { color: var(--brand); }
    .news-item-side.active .ni-title { color: var(--brand); font-weight: 700; }
    .ni-desc-mobile { display: none; }
    @media (max-width: 768px) {
        .news-container-v2 { flex-direction: column; gap: 0; padding: 0; overflow: hidden; }
        .nf-image { height: 200px; border-radius: 0; margin-bottom: 15px; }
        .nf-content { padding: 0 20px 20px 20px; }
        .news-list-side { border-left: none; padding: 0 20px 20px 20px; border-top: 5px solid #eee; }
        .news-item-side { padding: 15px 0; border-radius: 0; border-left: none; }
        .news-item-side.active { background: transparent; color: var(--brand); }
    }
    </style>';

    // =================================================================
    // 6. JavaScript 互動邏輯（自動輪播 + 點擊預覽）
    // =================================================================
    $output .= "
    <script>
    (function() {
        const widget = document.getElementById('" . esc_js($widget_id) . "');
        if(!widget) return;

        const slides    = widget.querySelectorAll('.news-featured-slide');
        const listItems = widget.querySelectorAll('.news-item-side');
        let currentIndex = 0;
        let timer;
        const interval = 5000; // 自動輪播間隔（毫秒）

        function switchTab(index) {
            slides.forEach(s => s.classList.remove('active'));
            listItems.forEach(l => l.classList.remove('active'));
            if(slides[index])    slides[index].classList.add('active');
            if(listItems[index]) listItems[index].classList.add('active');
            currentIndex = index;
        }

        function startAutoPlay() {
            timer = setInterval(() => {
                let nextIndex = currentIndex + 1;
                if(nextIndex >= slides.length) nextIndex = 0;
                switchTab(nextIndex);
            }, interval);
        }

        function stopAutoPlay() { clearInterval(timer); }

        listItems.forEach((item, idx) => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                switchTab(idx);
                stopAutoPlay(); // 手動操作後暫停自動輪播
            });
        });

        widget.addEventListener('mouseenter', stopAutoPlay);
        widget.addEventListener('mouseleave', startAutoPlay);

        startAutoPlay();
    })();
    </script>
    ";

    return $output;
});
