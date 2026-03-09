<?php
/*
 * Shortcode: [farmer_courses]
 * 自動從農民學院 RSS 抓取最新課程，以卡片清單呈現。
 *
 * 【參數】
 *   count    : 顯示筆數（預設 6）
 *   keyword  : 篩選關鍵字，逗號分隔（留空=顯示全部）
 *   cache_hr : 快取小時數（預設 12）
 *
 * 【範例】
 *   [farmer_courses]
 *   [farmer_courses count="4" keyword="安全,有機"]
 *
 * 【資料來源】
 *   農民學院 RSS：https://academy.moa.gov.tw/rss.php?func=course
 */

add_shortcode('farmer_courses', function ($atts) {

    $atts = shortcode_atts([
        'count'    => 6,
        'keyword'  => '',
        'cache_hr' => 12,
    ], $atts);

    $count    = intval($atts['count']);
    $cache_hr = max(1, intval($atts['cache_hr']));
    $keywords = array_filter(array_map('trim', explode(',', $atts['keyword'])));

    // ── 快取 key ──
    $cache_key = 'farmer_courses_' . md5(serialize($atts));
    $cached    = get_transient($cache_key);
    if ($cached !== false) {
        return $cached;
    }

    // ── 抓取 RSS ──
    include_once ABSPATH . WPINC . '/feed.php';
    $rss = fetch_feed('https://academy.moa.gov.tw/rss.php?func=course');

    if (is_wp_error($rss)) {
        return '<p class="edu-fetch-error">⚠️ 目前無法載入農民學院課程資料，請稍後再試。</p>';
    }

    $max   = $rss->get_item_quantity(50); // 先抓多一點，再篩選
    $items = $rss->get_items(0, $max);

    if (empty($items)) {
        return '<p class="edu-fetch-empty">目前沒有近期課程。</p>';
    }

    // ── 篩選 ──
    $filtered = [];
    foreach ($items as $item) {
        if (count($filtered) >= $count) break;

        $title = $item->get_title();
        $desc  = wp_strip_all_tags($item->get_description());

        // 關鍵字篩選（若有設定）
        if (!empty($keywords)) {
            $match = false;
            foreach ($keywords as $kw) {
                if (mb_stripos($title, $kw) !== false || mb_stripos($desc, $kw) !== false) {
                    $match = true;
                    break;
                }
            }
            if (!$match) continue;
        }

        $filtered[] = $item;
    }

    if (empty($filtered)) {
        return '<p class="edu-fetch-empty">目前沒有符合條件的課程。<a href="https://academy.moa.gov.tw/" target="_blank" rel="noopener">前往農民學院查看所有課程 →</a></p>';
    }

    // ── 解析標題中的日期（格式：115-04-08 課程名稱）──
    // 民國年轉西元年
    function farmer_courses_parse_title($title) {
        $result = ['date_str' => '', 'month' => '', 'day' => '', 'name' => $title];
        if (preg_match('/^(\d{2,3})-(\d{2})-(\d{2})\s+(.+)$/', $title, $m)) {
            $roc_year = intval($m[1]);
            $year     = $roc_year + 1911;
            $month    = $m[2];
            $day      = $m[3];
            $result['date_str'] = "{$year}/{$month}/{$day}";
            $result['month']    = intval($month) . '月';
            $result['day']      = ltrim($day, '0');
            $result['name']     = trim($m[4]);
        }
        return $result;
    }

    // ── 組 HTML ──
    $html = '<div class="edu-ws-list">';

    foreach ($filtered as $item) {
        $parsed = farmer_courses_parse_title($item->get_title());
        $link   = esc_url($item->get_link());
        $desc   = wp_trim_words(wp_strip_all_tags($item->get_description()), 40, '…');

        $html .= '<div class="edu-ws-item">';

        // 日期方塊
        if ($parsed['month'] && $parsed['day']) {
            $html .= '<div class="edu-ws-date-box">';
            $html .= '<span class="edu-ws-month">' . esc_html($parsed['month']) . '</span>';
            $html .= '<span class="edu-ws-day">' . esc_html($parsed['day']) . '</span>';
            $html .= '</div>';
        }

        // 內容
        $html .= '<div class="edu-ws-content">';
        $html .= '<div class="edu-ws-meta">';
        $html .= '<span class="edu-ws-status edu-ws-open"><i class="fas fa-circle"></i> 開放報名</span>';
        $html .= '<span class="edu-ws-category">農民學院</span>';
        $html .= '</div>';
        $html .= '<h3 class="edu-ws-title">' . esc_html($parsed['name']) . '</h3>';
        if ($desc) {
            $html .= '<p class="edu-ws-desc">' . esc_html($desc) . '</p>';
        }
        $html .= '</div>';

        // 報名按鈕
        $html .= '<div class="edu-ws-action">';
        $html .= '<a href="' . $link . '" class="edu-ws-register" target="_blank" rel="noopener"><i class="fas fa-pen-to-square"></i> 查看詳情</a>';
        $html .= '</div>';

        $html .= '</div>'; // .edu-ws-item
    }

    $html .= '</div>'; // .edu-ws-list

    // 更多連結
    $html .= '<div style="text-align:center;margin-top:16px;">';
    $html .= '<a href="https://academy.moa.gov.tw/list.php?id=19" target="_blank" rel="noopener" '
           . 'style="color:var(--brand,#5C8607);font-weight:600;">'
           . '前往農民學院查看所有課程 <i class="fas fa-arrow-right"></i></a>';
    $html .= '</div>';

    // ── 存入快取 ──
    set_transient($cache_key, $html, $cache_hr * HOUR_IN_SECONDS);

    return $html;
});
