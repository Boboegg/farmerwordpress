<?php
/*
 * Plugin Name: 最新消息列表 shortcode [my_news_loop]
 * Description: 簡潔日期欄 + 分類標籤 + 標題的新聞列表。
 *
 * 使用範例：
 *   [my_news_loop cat="news" count="5"]
 *   [my_news_loop cat="event" count="3"]
 *
 * 【參數說明】
 * - cat   ：文章分類 slug，留空抓全站文章
 * - count ：顯示篇數（預設 5）
 *
 * 【卡片結構】
 * - 左側日期欄：日 / 月 / 年三行
 * - 中間：分類標籤 + 文章標題
 * - 右側：閱讀更多按鈕
 */

add_shortcode('my_news_loop', function($atts) {
    $atts = shortcode_atts([
        'cat'   => '',
        'count' => 5
    ], $atts);

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

    $query  = new WP_Query($args);
    $output = '<div class="modern-news-container">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $link       = get_permalink();
            $title      = get_the_title();
            $date_year  = get_the_date('Y');
            $date_day   = get_the_date('d');
            $date_month = get_the_date('m月');
            $categories = get_the_category();
            $cat_name   = !empty($categories) ? $categories[0]->name : '一般消息';

            $output .= "
            <article class='modern-news-card'>
                <div class='card-date-box'>
                    <span class='card-day'>" . esc_html($date_day) . "</span>
                    <span class='card-month'>" . esc_html($date_month) . "</span>
                    <span class='card-year'>" . esc_html($date_year) . "</span>
                </div>
                <div class='card-content-main'>
                    <div class='card-tag-row'>
                        <span class='card-label'>" . esc_html($cat_name) . "</span>
                    </div>
                    <h3 class='card-post-title'>
                        <a href='" . esc_url($link) . "'>" . esc_html($title) . "</a>
                    </h3>
                </div>
                <div class='card-action-btn'>
                    <a href='" . esc_url($link) . "' class='read-more-link'>閱讀更多 →</a>
                </div>
            </article>";
        }
        wp_reset_postdata();
    } else {
        $output .= '<p class="no-news-msg">此分類目前尚無消息更新</p>';
    }

    $output .= '</div>';
    return $output;
});
