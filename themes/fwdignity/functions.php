<?php
/**
 * fwdignity theme — functions.php
 * 現行版本：清朗田野 3.3.0 × v4 資訊架構。
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'FWDIGNITY_VERSION', '3.3.0' );
define( 'FWDIGNITY_THEME_DIR', get_template_directory() );
define( 'FWDIGNITY_THEME_URI', get_template_directory_uri() );

function fwdignity_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'script', 'style',
    ) );
    add_theme_support( 'editor-styles' );

    // 2026-07-17 W3-P：導覽為 header.php／footer.php 硬編碼（v4 規格鎖定），
    // wp_nav_menu 全 theme 零呼叫——選單註冊與 Nav Walker 屬死抽象，退役歸檔 retired-20260716/。
}
add_action( 'after_setup_theme', 'fwdignity_setup' );

function fwdignity_body_slug_class( $classes ) {
    if ( is_singular() ) {
        $post = get_post();
        if ( $post && ! empty( $post->post_name ) ) {
            $classes[] = 'page-slug-' . sanitize_html_class( $post->post_name );
        }
    }
    return $classes;
}
add_filter( 'body_class', 'fwdignity_body_slug_class' );

function fwdignity_redirect_legacy_podcast_url() {
    $request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
    $request_path = wp_parse_url( $request_uri, PHP_URL_PATH );

    if ( '/research-results/podcast' === untrailingslashit( (string) $request_path ) ) {
        wp_safe_redirect( home_url( '/podcast/' ), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'fwdignity_redirect_legacy_podcast_url', 1 );

if ( ! isset( $content_width ) ) { $content_width = 1440; }

require_once FWDIGNITY_THEME_DIR . '/inc/field-content.php';
require_once FWDIGNITY_THEME_DIR . '/inc/media-hub.php';
require_once FWDIGNITY_THEME_DIR . '/inc/topic-content.php';
require_once FWDIGNITY_THEME_DIR . '/inc/guide-content.php';
require_once FWDIGNITY_THEME_DIR . '/inc/content-taxonomies.php';

// 農學堂 shortcode(Code Snippets 若無啟用時的 theme 內建 fallback)
// farmer_videos.php / farmer_courses.php 使用 anonymous function + add_shortcode
// 即使 Code Snippets 也註冊相同 shortcode,add_shortcode 會被覆蓋(安全)
// 其他 shortcode(home_highlight_slider / news_magazine_layout 等)
// 有命名 function,仍由 Code Snippets 獨占避免 Fatal Error。
$fw_inc_sc = FWDIGNITY_THEME_DIR . '/inc/shortcodes/';
foreach ( array( 'farmer_videos.php', 'farmer_courses.php' ) as $fw_sc ) {
    if ( file_exists( $fw_inc_sc . $fw_sc ) ) {
        require_once $fw_inc_sc . $fw_sc;
    }
}

function fwdignity_enqueue_assets() {
    $layout_css_file    = FWDIGNITY_THEME_DIR . '/assets/css/layout.css';
    $layout_css_version = file_exists( $layout_css_file ) ? (string) filemtime( $layout_css_file ) : FWDIGNITY_VERSION;

    wp_enqueue_style(
        'fwdignity-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700;900&family=Noto+Serif+TC:wght@700&display=swap',
        array(), null
    );
    // 2026-07-17 W2-C：Font Awesome 自託管（assets/vendor/），去 cdnjs 依賴。
    wp_enqueue_style(
        'fwdignity-fontawesome',
        FWDIGNITY_THEME_URI . '/assets/vendor/fontawesome/css/all.min.css',
        array(), '6.4.2'
    );
    wp_enqueue_style( 'fwdignity-main', get_stylesheet_uri(),
        array( 'fwdignity-google-fonts', 'fwdignity-fontawesome' ), FWDIGNITY_VERSION );
    wp_enqueue_style( 'fwdignity-v2-tokens',
        FWDIGNITY_THEME_URI . '/assets/css/site-v2-tokens.css',
        array( 'fwdignity-main' ), FWDIGNITY_VERSION );
    wp_enqueue_style( 'fwdignity-layout',
        FWDIGNITY_THEME_URI . '/assets/css/layout.css',
        array( 'fwdignity-v2-tokens' ), $layout_css_version );
    wp_enqueue_style( 'fwdignity-site-parts',
        FWDIGNITY_THEME_URI . '/assets/css/site-parts.css',
        array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
    wp_enqueue_script( 'fwdignity-layout',
        FWDIGNITY_THEME_URI . '/assets/js/layout.js',
        array(), FWDIGNITY_VERSION, true );
    wp_enqueue_script( 'fwdignity-reader-size',
        FWDIGNITY_THEME_URI . '/assets/js/reader-size-control.js',
        array( 'fwdignity-layout' ), FWDIGNITY_VERSION, true );

    if ( is_front_page() ) {
        wp_enqueue_style( 'fwdignity-home',
            FWDIGNITY_THEME_URI . '/assets/css/home.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_style( 'fwdignity-home-polish',
            FWDIGNITY_THEME_URI . '/assets/css/home-polish.css',
            array( 'fwdignity-home' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-home',
            FWDIGNITY_THEME_URI . '/assets/js/home.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 頁面資產對照表（2026-07-17 W2-C 資料驅動化）：slug → 專屬 CSS/JS。
    // 迭代順序＝載入順序；research-about-refresh 疊在 about／research 家族之後，必須留在表尾。
    // 版號一律 FWDIGNITY_VERSION，實際輸出由 fwdignity_bust_asset_version() 以 filemtime 覆寫。
    $fw_page_assets = array(
        'fwdignity-page-thenews'            => array( 'slugs' => array( 'thenews' ), 'css' => 'page-thenews.css', 'js' => 'page-thenews.js' ),
        'fwdignity-page-people-who-farm'    => array( 'slugs' => array( 'people-who-farm', 'body-and-farm-work', 'changing-farm-work', 'rural-support', 'risk-and-responsibility', 'knowledge-in-the-field' ), 'css' => 'page-people-who-farm.css', 'js' => 'page-people-who-farm.js', 'js_slugs' => array( 'people-who-farm' ) ),
        'fwdignity-page-field-learning'     => array( 'slugs' => array( 'field-learning' ), 'css' => 'page-field-learning.css' ),
        'fwdignity-page-field-notes'        => array( 'slugs' => array( 'field-notes' ), 'css' => 'page-field-notes.css', 'js' => 'page-field-notes.js' ),
        'fwdignity-page-field-events'       => array( 'slugs' => array( 'field-events' ), 'css' => 'page-field-events.css' ),
        'fwdignity-page-health-sub'         => array( 'slugs' => array( 'subpage-heat', 'subpage-pesticide', 'subpage-msd', 'subpage-mental' ), 'css' => 'page-health-sub.css' ),
        'fwdignity-page-research-result'    => array( 'slugs' => array( 'research-result', 'research-results' ), 'css' => 'page-research-result.css' ),
        'fwdignity-page-about'              => array( 'slugs' => array( 'about' ), 'css' => 'page-about.css' ),
        'fwdignity-page-economic-insurance' => array( 'slugs' => array( 'economic-insurance' ), 'css' => 'page-economic-insurance.css' ),
        'fwdignity-page-economic-sub'       => array( 'slugs' => array( 'occupational-accident-insurance', 'crop-insurance', 'agricultural-subsidy-resources' ), 'css' => 'page-economic-sub.css' ),
        'fwdignity-page-calc'               => array( 'slugs' => array( 'worker-occupational-accident-calculator', 'farmer-worker-retirement-calculator' ), 'css' => 'page-calc.css', 'skip_raw' => true ),
        'fwdignity-page-about-sub'          => array( 'slugs' => array( 'researchteam', 'contact', 'day' ), 'css' => 'page-about-sub.css' ),
        'fwdignity-page-research-sub'       => array( 'slugs' => array( 'research-publication', 'research-publications', 'infographic', 'download-zone', 'related-resources' ), 'css' => 'page-research-sub.css' ),
        'fwdignity-page-farming-guide'      => array( 'slugs' => array( 'farming-guide' ), 'css' => 'page-farming-guide.css' ),
        'fwdignity-page-farmer-study'       => array( 'slugs' => array( 'farmer-study', 'farming-guide' ), 'css' => 'page-farmer-study.css' ),
        'fwdignity-page-advocacy'           => array( 'slugs' => array( 'dignity-farming-initiative' ), 'css' => 'page-advocacy.css' ),
        'fwdignity-page-healthgood'         => array( 'slugs' => array( 'healthgood' ), 'css' => 'page-healthgood.css', 'js' => 'page-healthgood.js' ),
        'fwdignity-page-safety'             => array( 'slugs' => array( 'occupational-safety' ), 'css' => 'page-safety.css' ),
        'fwdignity-page-safety-sub'         => array( 'slugs' => array( 'personal-protective-equipment', 'labor-saving-machinery', 'greenhouse-zone' ), 'css' => 'page-safety-sub.css', 'js' => 'page-safety-sub.js', 'js_slugs' => array( 'personal-protective-equipment' ), 'js_handle' => 'fwdignity-safety-sub-risk' ),
        'fwdignity-page-audience'           => array( 'slugs' => array( 'beginner-farmers', 'young-farmers', 'experienced-farmers', 'Public', 'public' ), 'css' => 'page-audience.css' ),
        'fwdignity-research-about-refresh'  => array( 'slugs' => array( 'research-results', 'research-publications', 'related-resources', 'dignity-farming-initiative', 'about', 'researchteam' ), 'css' => 'page-research-about-refresh.css' ),
    );

    foreach ( $fw_page_assets as $fw_handle => $fw_asset ) {
        if ( ! is_page( $fw_asset['slugs'] ) ) {
            continue;
        }
        if ( ! empty( $fw_asset['skip_raw'] ) && isset( $_GET['fw_raw'] ) ) {
            continue;
        }
        if ( ! empty( $fw_asset['css'] ) ) {
            wp_enqueue_style( $fw_handle,
                FWDIGNITY_THEME_URI . '/assets/css/' . $fw_asset['css'],
                array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        }
        if ( ! empty( $fw_asset['js'] ) && ( empty( $fw_asset['js_slugs'] ) || is_page( $fw_asset['js_slugs'] ) ) ) {
            wp_enqueue_script( empty( $fw_asset['js_handle'] ) ? $fw_handle : $fw_asset['js_handle'],
                FWDIGNITY_THEME_URI . '/assets/js/' . $fw_asset['js'],
                array(), FWDIGNITY_VERSION, true );
        }
    }

    // 影音中樞：hub 頁、節目系列 taxonomy 與 Podcast 單篇共用（非單純 page slug，保留獨立條件）。
    if ( is_page( 'podcast' ) || is_tax( 'media_series' ) || ( is_singular( 'post' ) && has_category( 'podcast', get_queried_object_id() ) ) ) {
        wp_enqueue_style( 'fwdignity-page-media-hub',
            FWDIGNITY_THEME_URI . '/assets/css/page-media-hub.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-page-media-hub',
            FWDIGNITY_THEME_URI . '/assets/js/page-media-hub.js',
            array(), FWDIGNITY_VERSION, true );
    }

    if ( is_single() ) {
        wp_enqueue_style( 'fwdignity-single-post',
            FWDIGNITY_THEME_URI . '/assets/css/single-post.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
    }

    wp_enqueue_style(
        'fwdignity-v2-page-foundation',
        FWDIGNITY_THEME_URI . '/assets/css/site-v2-page-foundation.css',
        array( 'fwdignity-layout' ),
        FWDIGNITY_VERSION
    );

    $final_layout_file = FWDIGNITY_THEME_DIR . '/assets/css/site-v3-layout-final.css';
    wp_enqueue_style(
        'fwdignity-site-v3-final-layout',
        FWDIGNITY_THEME_URI . '/assets/css/site-v3-layout-final.css',
        array( 'fwdignity-v2-page-foundation' ),
        file_exists( $final_layout_file ) ? (string) filemtime( $final_layout_file ) : FWDIGNITY_VERSION
    );

}
add_action( 'wp_enqueue_scripts', 'fwdignity_enqueue_assets' );

/**
 * theme 資產統一以 filemtime 做 cache-busting：
 * 輸出端覆蓋 enqueue 傳入的固定版號，改檔即失效快取；外部 CDN 資產不動。
 */
function fwdignity_bust_asset_version( $src ) {
    if ( ! is_string( $src ) || false === strpos( $src, FWDIGNITY_THEME_URI ) ) {
        return $src;
    }
    $relative = wp_parse_url( str_replace( FWDIGNITY_THEME_URI, '', $src ), PHP_URL_PATH );
    if ( ! $relative ) {
        return $src;
    }
    $file = FWDIGNITY_THEME_DIR . $relative;
    if ( file_exists( $file ) ) {
        $src = add_query_arg( 'ver', (string) filemtime( $file ), $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'fwdignity_bust_asset_version' );
add_filter( 'script_loader_src', 'fwdignity_bust_asset_version' );

/**
 * 對外部字體與 icon CDN 先建連線，縮短首訪請求瀑布。
 */
function fwdignity_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array( 'href' => 'https://fonts.googleapis.com' );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'fwdignity_resource_hints', 10, 2 );

/**
 * Code Snippets 端仍註冊 cdnjs 版 Font Awesome（handle: fontawesome-6）；
 * theme 已自託管同版本，晚點位 dequeue 避免雙載與外部依賴。
 */
function fwdignity_dedupe_external_fontawesome() {
    wp_dequeue_style( 'fontawesome-6' );
    wp_deregister_style( 'fontawesome-6' );
}
add_action( 'wp_enqueue_scripts', 'fwdignity_dedupe_external_fontawesome', 99 );

// WP emoji 偵測腳本連 s.w.org 外連；站台不依賴，移除。
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * 最新消息分類數量包含子分類，文章或分類更新後需清除聚合快取。
 */
function fwdignity_flush_thenews_facet_counts() {
    delete_transient( 'fwdignity_thenews_facet_counts_v1' );
}
add_action( 'save_post_post', 'fwdignity_flush_thenews_facet_counts' );
add_action( 'created_category', 'fwdignity_flush_thenews_facet_counts' );
add_action( 'edited_category', 'fwdignity_flush_thenews_facet_counts' );
add_action( 'delete_category', 'fwdignity_flush_thenews_facet_counts' );
add_action(
    'set_object_terms',
    function ( $object_id, $terms, $tt_ids, $taxonomy ) {
        if ( 'category' === $taxonomy && 'post' === get_post_type( $object_id ) ) {
            fwdignity_flush_thenews_facet_counts();
        }
    },
    10,
    4
);

function fwdignity_get_data_page() {
    if ( is_front_page() ) { return 'home'; }
    if ( is_singular() ) {
        $post = get_post();
        if ( $post && ! empty( $post->post_name ) ) {
            return sanitize_html_class( $post->post_name );
        }
    }
    if ( is_home() ) { return 'news'; }
    return 'default';
}

/**
 * Resolve a Page URL only when the target is publicly published.
 *
 * @param string $page_path Page path relative to the site root.
 * @return string
 */
function fwdignity_get_public_page_url( $page_path ) {
    $page = get_page_by_path( trim( (string) $page_path, '/' ), OBJECT, 'page' );
    if ( ! $page instanceof WP_Post || 'publish' !== $page->post_status ) {
        return '';
    }

    return (string) get_permalink( $page );
}

function fwdignity_get_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $img = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $img ) { return $img[0]; }
    }
    return content_url( 'uploads/2025/12/Logo_final-1-1.png' );
}

/**
 * Customizer 設定 — Bobo 可在 WP 後台「外觀 → 自訂」改
 */
function fwdignity_customize_register( $wp_customize ) {

    // ---- Section: 單位資訊 ----
    $wp_customize->add_section( 'fwdignity_units', array(
        'title'    => __( '尊嚴農業 · 單位資訊', 'fwdignity' ),
        'priority' => 30,
    ) );

    $units = array(
        'guidance_unit' => array( '指導單位', '國家科學委員會' ),
        'host_unit'     => array( '計畫主持單位', '國立中正大學' ),
        'partners'      => array( '合作夥伴', '臺大醫院雲林分院農業環境與職業健康中心、民雄鄉農會' ),
    );
    foreach ( $units as $key => $v ) {
        $wp_customize->add_setting( 'fwdignity_' . $key, array(
            'default'           => $v[1],
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( 'fwdignity_' . $key, array(
            'label'   => $v[0],
            'section' => 'fwdignity_units',
            'type'    => 'textarea',
        ) );
    }

    // ---- Section: 聯絡資訊 ----
    $wp_customize->add_section( 'fwdignity_contact', array(
        'title'    => __( '尊嚴農業 · 聯絡資訊', 'fwdignity' ),
        'priority' => 31,
    ) );

    $contacts = array(
        'address'  => array( '地址', '嘉義縣民雄鄉大學路一段 168 號 社會科學院一館 323 研究室', 'textarea' ),
        'phone'    => array( '電話', '05-2720411 # 22316', 'text' ),
        'email'    => array( 'Email', 'fwdignity@alum.ccu.edu.tw', 'text' ),
        'tagline'  => array( '計畫標語', '尊嚴農業：共構雲嘉農業工作者職業健康的社會安全網', 'text' ),
    );
    foreach ( $contacts as $key => $v ) {
        $wp_customize->add_setting( 'fwdignity_' . $key, array(
            'default'           => $v[1],
            'sanitize_callback' => ( $key === 'email' ) ? 'sanitize_email' : 'wp_kses_post',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( 'fwdignity_' . $key, array(
            'label'   => $v[0],
            'section' => 'fwdignity_contact',
            'type'    => $v[2],
        ) );
    }

    // ---- Section: 社群媒體 ----
    $wp_customize->add_section( 'fwdignity_social', array(
        'title'    => __( '尊嚴農業 · 社群媒體', 'fwdignity' ),
        'priority' => 32,
    ) );

    $socials = array(
        'facebook'  => array( 'Facebook', 'https://www.facebook.com/profile.php?id=61575048498498' ),
        'instagram' => array( 'Instagram', 'https://www.instagram.com/fwdignity' ),
        'youtube'   => array( 'YouTube', '' ),
        'line'      => array( 'LINE', '' ),
    );
    foreach ( $socials as $key => $v ) {
        $wp_customize->add_setting( 'fwdignity_social_' . $key, array(
            'default'           => $v[1],
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( 'fwdignity_social_' . $key, array(
            'label'   => $v[0] . ' URL',
            'section' => 'fwdignity_social',
            'type'    => 'url',
        ) );
    }
}
add_action( 'customize_register', 'fwdignity_customize_register' );

/**
 * Customizer defaults 集中表（前台與後台 default 同步用）
 */
function fwdignity_customizer_defaults() {
    return array(
        'guidance_unit'   => '國家科學委員會',
        'host_unit'       => '國立中正大學',
        'partners'        => '臺大醫院雲林分院農業環境與職業健康中心、民雄鄉農會',
        'address'         => '嘉義縣民雄鄉大學路一段 168 號 社會科學院一館 323 研究室',
        'phone'           => '05-2720411 # 22316',
        'email'           => 'fwdignity@alum.ccu.edu.tw',
        'tagline'         => '尊嚴農業：共構雲嘉農業工作者職業健康的社會安全網',
        'social_facebook' => 'https://www.facebook.com/profile.php?id=61575048498498',
        'social_instagram'=> 'https://www.instagram.com/fwdignity',
        'social_youtube'  => '',
        'social_line'     => '',
    );
}

/**
 * 最新消息頁 — category slug → 顏色對照
 * 用於 .tnews-card-cat badge 的 --cc 色
 */
function fwdignity_tnews_cat_color( $slug ) {
    $map = array(
        'news'     => '#15803D', // 主綠
        'event'    => '#EA580C', // 橙
        'press'    => '#1E40AF', // 深藍
        'media'    => '#7C3AED', // 紫
        'podcast'  => '#D97706', // 金棕
        'video'    => '#E11D48', // 深紅
        'youtube'  => '#E11D48',
        'notices'  => '#0891B2', // 青
        '最新消息' => '#15803D',
        '活動'     => '#EA580C',
        '媒體'     => '#1E40AF',
    );
    return isset( $map[ $slug ] ) ? $map[ $slug ] : '#64748B';
}

/**
 * 取 Customizer 值 helper
 * 優先：Bobo 在後台設的值 → 設定 default → 傳入 fallback
 */
function fwdignity_get( $key, $fallback = '' ) {
    $defaults = fwdignity_customizer_defaults();
    $default = isset( $defaults[ $key ] ) ? $defaults[ $key ] : $fallback;
    $value = get_theme_mod( 'fwdignity_' . $key, $default );
    return $value;
}

/**
 * 取社群連結；裸網域佔位值（如 https://www.instagram.com/）視同未設定，
 * 頁首頁尾據此自動隱藏該 icon，避免輸出假連結。
 */
function fwdignity_get_social_url( $key ) {
    $url = trim( (string) fwdignity_get( 'social_' . $key ) );
    if ( '' === $url ) {
        return '';
    }
    $path  = trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
    $query = wp_parse_url( $url, PHP_URL_QUERY );
    if ( '' === $path && empty( $query ) ) {
        return '';
    }
    return $url;
}

/**
 * 分類 chip 的抵達網址：導向「全部內容」對應分面（v4「策展與查詢集中於全部內容」）。
 * news 與 media 家族＝內容形式分面；其餘分類＝內容主題分面。
 * thenews 頁不存在時退回原生 category archive 連結。
 */
function fwdignity_thenews_filter_url( $category ) {
    if ( ! $category instanceof WP_Term ) {
        return '';
    }
    $thenews = fwdignity_get_public_page_url( 'thenews' );
    if ( ! $thenews ) {
        return (string) get_category_link( $category );
    }
    $media_root = get_category_by_slug( 'media' );
    $is_format  = 'news' === $category->slug || (
        $media_root && (
            (int) $category->term_id === (int) $media_root->term_id
            || term_is_ancestor_of( $media_root->term_id, $category->term_id, 'category' )
        )
    );
    $param = $is_format ? 'format' : 'category_topic';

    return add_query_arg( array( $param => array( $category->slug ) ), $thenews );
}

/**
 * category／tag archive 無核准頁型（index.php 傾印死路），一律導向「全部內容」
 * 對應分面或關鍵字查詢。開發期用 302，正式上線前評估改 301。
 */
function fwdignity_redirect_taxonomy_archives() {
    if ( ! is_category() && ! is_tag() ) {
        return;
    }
    $thenews = fwdignity_get_public_page_url( 'thenews' );
    if ( ! $thenews ) {
        return;
    }
    $target = $thenews;
    $term   = get_queried_object();
    if ( $term instanceof WP_Term ) {
        $target = is_category()
            ? fwdignity_thenews_filter_url( $term )
            : add_query_arg( array( 'keyword' => $term->name ), $thenews );
    }
    wp_safe_redirect( $target, 302 );
    exit;
}
add_action( 'template_redirect', 'fwdignity_redirect_taxonomy_archives' );

/**
 * 取得民雄鄉農事氣象摘要。
 *
 * API key 必須由 wp-config.php 的 FWDIGNITY_CWA_API_KEY 或主機環境變數提供，
 * 不寫入 theme repo。若既有 Code Snippets 已註冊同名函式，沿用既有版本。
 */
if ( ! function_exists( 'get_minxiong_weather_status' ) ) {
    function get_minxiong_weather_status() {
        $fallback = array(
            'available'    => false,
            'status_class' => 'maintenance-alert',
            'icon_class'   => 'fa-cloud-sun',
            'label'        => '氣象資料更新中',
            'message'      => '民雄鄉氣象資料暫時無法取得，請稍後再試。',
            'temperature'  => null,
            'rain'         => null,
            'weather'      => '',
            'updated'      => '',
        );

        $cached = get_transient( 'fwdignity_minxiong_weather_v1' );
        if ( is_array( $cached ) ) {
            return wp_parse_args( $cached, $fallback );
        }

        $api_key = defined( 'FWDIGNITY_CWA_API_KEY' )
            ? FWDIGNITY_CWA_API_KEY
            : getenv( 'FWDIGNITY_CWA_API_KEY' );
        $api_key = is_string( $api_key ) ? trim( $api_key ) : '';

        if ( '' === $api_key ) {
            return $fallback;
        }

        $api_url = add_query_arg(
            array( 'Authorization' => $api_key ),
            'https://opendata.cwa.gov.tw/api/v1/rest/datastore/F-D0047-029'
        );
        $response = wp_remote_get( $api_url, array(
            'timeout'     => 10,
            'redirection' => 3,
            'sslverify'   => true,
        ) );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            set_transient( 'fwdignity_minxiong_weather_v1', $fallback, 5 * MINUTE_IN_SECONDS );
            return $fallback;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        $locations = isset( $data['records']['Locations'][0]['Location'] )
            && is_array( $data['records']['Locations'][0]['Location'] )
            ? $data['records']['Locations'][0]['Location']
            : array();
        $location_data = null;

        foreach ( $locations as $location ) {
            if ( isset( $location['LocationName'] ) && '民雄鄉' === $location['LocationName'] ) {
                $location_data = $location;
                break;
            }
        }

        if ( ! is_array( $location_data ) || empty( $location_data['WeatherElement'] ) ) {
            set_transient( 'fwdignity_minxiong_weather_v1', $fallback, 5 * MINUTE_IN_SECONDS );
            return $fallback;
        }

        $elements = $location_data['WeatherElement'];
        $get_value = static function( $element_name, $value_key ) use ( $elements ) {
            foreach ( $elements as $element ) {
                if ( ! isset( $element['ElementName'] ) || $element_name !== $element['ElementName'] ) {
                    continue;
                }

                $value = isset( $element['Time'][0]['ElementValue'][0] )
                    ? $element['Time'][0]['ElementValue'][0]
                    : array();

                return array_key_exists( $value_key, $value ) ? $value[ $value_key ] : null;
            }

            return null;
        };

        $temperature = $get_value( '溫度', 'Temperature' );
        $rain = $get_value( '3小時降雨機率', 'ProbabilityOfPrecipitation' );
        $weather = $get_value( '天氣現象', 'Weather' );

        if ( ! is_numeric( $temperature ) ) {
            set_transient( 'fwdignity_minxiong_weather_v1', $fallback, 5 * MINUTE_IN_SECONDS );
            return $fallback;
        }

        $temperature_number = (float) $temperature;
        $rain_number = is_numeric( $rain ) ? (float) $rain : 0;
        $result = array(
            'available'    => true,
            'status_class' => 'weather-normal',
            'icon_class'   => 'fa-seedling',
            'label'        => '今日天氣：' . ( $weather ? $weather : '多雲時晴' ),
            'message'      => '氣候適宜 (' . $temperature_number . '°C)：請持續注意田間作業安全，操作農機時請穿著防護裝備。',
            'temperature'  => $temperature_number,
            'rain'         => $rain_number,
            'weather'      => $weather ? $weather : '',
            'updated'      => current_time( 'H:i' ),
        );

        if ( $rain_number >= 70 ) {
            $result['status_class'] = 'rain-alert';
            $result['icon_class'] = 'fa-umbrella';
            $result['label'] = '降雨機率高';
            $result['message'] = '降雨預警 (' . $rain_number . '%)：民雄地區降雨機率高，請農友注意田間排水與防滑，避免雨中作業。';
        } elseif ( $temperature_number >= 30 ) {
            $result['status_class'] = 'hot-alert';
            $result['icon_class'] = 'fa-temperature-high';
            $result['label'] = '高溫熱危害預警';
            $result['message'] = '高溫特報 (' . $temperature_number . '°C)：請務必多喝水、每工作30分鐘至陰涼處休息，慎防熱衰竭！';
        } elseif ( $temperature_number <= 15 ) {
            $result['status_class'] = 'cold-alert';
            $result['icon_class'] = 'fa-snowflake';
            $result['label'] = '低溫寒害預警';
            $result['message'] = '低溫特報 (' . $temperature_number . '°C)：請患有心血管疾病之農友注意保暖，採洋蔥式穿搭。';
        }

        set_transient( 'fwdignity_minxiong_weather_v1', $result, 30 * MINUTE_IN_SECONDS );
        return $result;
    }
}

/**
 * 抓農業部 RSS（WS Feed：https://www.moa.gov.tw/ws.php?id=9817）
 * 回傳 array 結構：[['title'=>,'link'=>,'date'=>,'excerpt'=>], ...]
 * 失敗時回空 array，不噴錯。
 */
function fwdignity_fetch_moa_rss( $count = 4 ) {
    if ( ! function_exists( 'fetch_feed' ) ) {
        include_once ABSPATH . WPINC . '/feed.php';
    }
    $items = array();
    // 快取時長須在 fetch 前以 filter 指定（fetch 後呼叫 set_cache_duration 無效）。
    $fw_rss_lifetime = static function () { return 6 * HOUR_IN_SECONDS; };
    add_filter( 'wp_feed_cache_transient_lifetime', $fw_rss_lifetime );
    $feed = fetch_feed( 'https://www.moa.gov.tw/ws.php?id=9817' );
    remove_filter( 'wp_feed_cache_transient_lifetime', $fw_rss_lifetime );
    if ( is_wp_error( $feed ) ) { return $items; }
    $max = $feed->get_item_quantity( $count );
    $rss_items = $feed->get_items( 0, $max );
    foreach ( $rss_items as $item ) {
        $items[] = array(
            'title'   => wp_strip_all_tags( $item->get_title() ),
            'link'    => esc_url( $item->get_permalink() ),
            'date'    => $item->get_date( 'Y-m-d' ),
            'excerpt' => wp_trim_words( wp_strip_all_tags( $item->get_description() ), 26, '…' ),
            'source'  => '農業部',
        );
    }
    return $items;
}

/**
 * 抓計畫最新 posts
 */
function fwdignity_fetch_site_posts( $count = 4 ) {
    $items = array();
    $q = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ) );
    if ( ! $q->have_posts() ) { return $items; }
    while ( $q->have_posts() ) {
        $q->the_post();
        $cats = get_the_category();
        $cat_name = ( $cats && isset( $cats[0] ) ) ? $cats[0]->name : '計畫動態';
        $items[] = array(
            'title'   => get_the_title(),
            'link'    => get_permalink(),
            'date'    => get_the_date( 'Y-m-d' ),
            'excerpt' => wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 26, '…' ),
            'source'  => $cat_name,
            'thumb'   => get_the_post_thumbnail_url( null, 'medium_large' ),
        );
    }
    wp_reset_postdata();
    return $items;
}

/**
 * 混合：WP posts 在前，MOA RSS 在後，依日期倒序
 */
function fwdignity_get_mixed_news( $total = 7 ) {
    $site = fwdignity_fetch_site_posts( $total );
    $moa  = fwdignity_fetch_moa_rss( $total );
    $all = array_merge( $site, $moa );
    usort( $all, function( $a, $b ) {
        return strcmp( $b['date'], $a['date'] );
    } );
    return array_slice( $all, 0, $total );
}

/**
 * 抓影音內容（podcast / video / youtube 三個 category）
 * 回傳結構：[['title','link','date','thumb','video_id','cat_name','cat_slug','ep_num','type'], ...]
 */
function fwdignity_fetch_media( $count = 6 ) {
    $items = array();
    $q = new WP_Query( array(
        'post_type'      => 'post',
        'category_name'  => 'podcast,video,youtube',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ) );
    if ( ! $q->have_posts() ) { return $items; }
    while ( $q->have_posts() ) {
        $q->the_post();
        $pid = get_the_ID();
        $thumb = get_the_post_thumbnail_url( $pid, 'medium_large' );
        $video_id = '';
        $content = get_post_field( 'post_content', $pid );
        $meta = get_post_meta( $pid, '_elementor_data', true );
        $search = stripslashes( $content . ' ' . ( is_string( $meta ) ? $meta : wp_json_encode( $meta ) ) );
        if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $search, $m ) ) {
            $video_id = $m[1];
            if ( ! $thumb ) { $thumb = "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg"; }
        }
        $cats = get_the_category();
        $cat_name = ( $cats && isset( $cats[0] ) ) ? $cats[0]->name : 'Media';
        $cat_slug = ( $cats && isset( $cats[0] ) ) ? $cats[0]->slug : '';
        $ep = get_post_meta( $pid, 'episode_number', true );
        $is_video = ( strpos( $cat_slug, 'video' ) !== false || strpos( $cat_slug, 'youtube' ) !== false || ! empty( $video_id ) );
        $items[] = array(
            'title'    => get_the_title(),
            'link'     => get_permalink(),
            'date'     => get_the_date( 'Y.m.d' ),
            'thumb'    => $thumb,
            'video_id' => $video_id,
            'cat_name' => $cat_name,
            'cat_slug' => $cat_slug,
            'ep'       => $ep,
            'type'     => $is_video ? 'VIDEO' : 'PODCAST',
        );
    }
    wp_reset_postdata();
    return $items;
}
