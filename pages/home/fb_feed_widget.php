<?php
/*
 * Plugin Name: Facebook 粉專動態牆 [fb_feed_widget]
 * Description: 用 Facebook 官方 Page Plugin 嵌入粉絲專頁動態，不需要 API Token。
 *
 * 使用範例：
 *   [fb_feed_widget]
 *   [fb_feed_widget width="380" height="600"]
 *   [fb_feed_widget page="https://www.facebook.com/fwdignity" width="340" height="500"]
 *
 * 【參數說明】
 * - page   ：粉絲專頁完整網址（預設已設為 fwdignity）
 * - width  ：元件寬度，px（預設 340，最小 180，最大 500）
 * - height ：元件高度，px（預設 500，最小 130）
 * - cover  ：是否顯示封面照片 true/false（預設 true）
 * - faces  ：是否顯示按讚的朋友頭貼 true/false（預設 false）
 */

add_shortcode('fb_feed_widget', function($atts) {
    $atts = shortcode_atts([
        'page'   => 'https://www.facebook.com/fwdignity',
        'width'  => 340,
        'height' => 500,
        'cover'  => 'true',
        'faces'  => 'false',
    ], $atts);

    $page_url   = esc_url_raw($atts['page']);
    $width      = max(180, min(500, (int)$atts['width']));
    $height     = max(130, (int)$atts['height']);
    $hide_cover = filter_var($atts['cover'], FILTER_VALIDATE_BOOLEAN) ? 'false' : 'true';
    $show_faces = filter_var($atts['faces'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

    // Facebook Page Plugin 的 iframe 網址
    $embed_url = add_query_arg([
        'href'                  => $page_url,
        'tabs'                  => 'timeline',
        'width'                 => $width,
        'height'                => $height,
        'small_header'          => 'false',
        'adapt_container_width' => 'true',
        'hide_cover'            => $hide_cover,
        'show_facepile'         => $show_faces,
        'locale'                => 'zh_TW',
    ], 'https://www.facebook.com/plugins/page.php');

    $output = '<div class="fb-page-plugin-wrap" style="max-width:' . $width . 'px;">';

    // 載入 Facebook JS SDK（只載入一次）
    $output .= '<div id="fb-root"></div>';
    $output .= '<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v19.0">
    </script>';

    // 用 iframe 嵌入（不依賴 JS SDK，相容性更好）
    $output .= '<iframe
        src="' . esc_url($embed_url) . '"
        width="' . $width . '"
        height="' . $height . '"
        style="border:none;overflow:hidden;border-radius:8px;"
        scrolling="no"
        frameborder="0"
        allowfullscreen="true"
        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
        loading="lazy">
    </iframe>';

    $output .= '</div>';

    // 響應式樣式
    $output .= '<style>
    .fb-page-plugin-wrap {
        width: 100%;
        overflow: hidden;
    }
    .fb-page-plugin-wrap iframe {
        width: 100% !important;
        max-width: 100%;
    }
    </style>';

    return $output;
});
