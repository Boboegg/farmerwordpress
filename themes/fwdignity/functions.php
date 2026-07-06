<?php
/**
 * fwdignity theme — functions.php
 * Phase 2 — 首頁 HUB 實作
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'FWDIGNITY_VERSION', '1.1.0' );
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

    register_nav_menus( array(
        'primary'         => __( '主要導覽（Navbar）', 'fwdignity' ),
        'footer_quick'    => __( '頁尾 · 快速連結', 'fwdignity' ),
        'footer_friendly' => __( '頁尾 · 友善連結（外部單位）', 'fwdignity' ),
    ) );
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

if ( ! isset( $content_width ) ) { $content_width = 1440; }

// Nav Walker（讓 wp_nav_menu 輸出符合 .fw-nav-item/.has-dropdown 結構）
require_once FWDIGNITY_THEME_DIR . '/inc/class-nav-walker.php';

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
    wp_enqueue_style(
        'fwdignity-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter+Tight:wght@700;900&family=Noto+Sans+TC:wght@400;500;700;900&family=Inter:wght@400;500;600;700&display=swap',
        array(), null
    );
    wp_enqueue_style(
        'fwdignity-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
        array(), '6.4.2'
    );
    wp_enqueue_style( 'fwdignity-main', get_stylesheet_uri(),
        array( 'fwdignity-google-fonts', 'fwdignity-fontawesome' ), FWDIGNITY_VERSION );
    wp_enqueue_style( 'fwdignity-v15-master',
        FWDIGNITY_THEME_URI . '/assets/css/v15-master.css',
        array( 'fwdignity-main' ), FWDIGNITY_VERSION );
    wp_enqueue_style( 'fwdignity-layout',
        FWDIGNITY_THEME_URI . '/assets/css/layout.css',
        array( 'fwdignity-v15-master' ), FWDIGNITY_VERSION );
    wp_enqueue_style( 'fwdignity-site-parts',
        FWDIGNITY_THEME_URI . '/assets/css/site-parts.css',
        array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
    wp_enqueue_script( 'fwdignity-layout',
        FWDIGNITY_THEME_URI . '/assets/js/layout.js',
        array(), FWDIGNITY_VERSION, true );

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

    if ( is_page( 'thenews' ) ) {
        wp_enqueue_style( 'fwdignity-page-thenews',
            FWDIGNITY_THEME_URI . '/assets/css/page-thenews.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
    }

    // 健康促進 4 子頁共用 CSS + FX JS
    $fw_health_subs = array( 'subpage-heat', 'subpage-pesticide', 'subpage-msd', 'subpage-mental' );
    if ( is_page( $fw_health_subs ) ) {
        wp_enqueue_style( 'fwdignity-page-health-sub',
            FWDIGNITY_THEME_URI . '/assets/css/page-health-sub.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 研究成果 Hub(單複數都支援)
    if ( is_page( array( 'research-result', 'research-results' ) ) ) {
        wp_enqueue_style( 'fwdignity-page-research-result',
            FWDIGNITY_THEME_URI . '/assets/css/page-research-result.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 關於我們
    if ( is_page( 'about' ) ) {
        wp_enqueue_style( 'fwdignity-page-about',
            FWDIGNITY_THEME_URI . '/assets/css/page-about.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 經濟與保險 Hub
    if ( is_page( 'economic-insurance' ) ) {
        wp_enqueue_style( 'fwdignity-page-economic-insurance',
            FWDIGNITY_THEME_URI . '/assets/css/page-economic-insurance.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 經濟與保險 3 子頁共用樣式
    if ( is_page( array( 'occupational-accident-insurance', 'crop-insurance', 'agricultural-subsidy-resources' ) ) ) {
        wp_enqueue_style( 'fwdignity-page-economic-sub',
            FWDIGNITY_THEME_URI . '/assets/css/page-economic-sub.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 試算工具 2 頁共用樣式（iframe 包殼）
    if ( is_page( array( 'worker-occupational-accident-calculator', 'farmer-worker-retirement-calculator' ) ) && ! isset( $_GET['fw_raw'] ) ) {
        wp_enqueue_style( 'fwdignity-page-calc',
            FWDIGNITY_THEME_URI . '/assets/css/page-calc.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 關於我們 3 子頁共用樣式
    if ( is_page( array( 'researchteam', 'contact', 'day' ) ) ) {
        wp_enqueue_style( 'fwdignity-page-about-sub',
            FWDIGNITY_THEME_URI . '/assets/css/page-about-sub.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 研究成果 5 子頁共用樣式
    if ( is_page( array( 'research-publication', 'research-publications', 'podcast', 'infographic', 'download-zone', 'related-resources' ) ) ) {
        wp_enqueue_style( 'fwdignity-page-research-sub',
            FWDIGNITY_THEME_URI . '/assets/css/page-research-sub.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 農學堂 培力課程
    if ( is_page( 'farmer-study' ) ) {
        wp_enqueue_style( 'fwdignity-page-farmer-study',
            FWDIGNITY_THEME_URI . '/assets/css/page-farmer-study.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 尊嚴農業倡議頁
    if ( is_page( 'dignity-farming-initiative' ) ) {
        wp_enqueue_style( 'fwdignity-page-advocacy',
            FWDIGNITY_THEME_URI . '/assets/css/page-advocacy.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 健康促進 章節 Hub 頁
    if ( is_page( 'healthgood' ) ) {
        wp_enqueue_style( 'fwdignity-page-healthgood',
            FWDIGNITY_THEME_URI . '/assets/css/page-healthgood.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
        wp_enqueue_script( 'fwdignity-page-healthgood',
            FWDIGNITY_THEME_URI . '/assets/js/page-healthgood.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 職業安全 章節 Hub 頁
    if ( is_page( 'occupational-safety' ) ) {
        wp_enqueue_style( 'fwdignity-page-safety',
            FWDIGNITY_THEME_URI . '/assets/css/page-safety.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    // 職業安全 3 子頁(共用 CSS + FX JS + Risk JS for PPE)
    $fw_safety_subs = array( 'personal-protective-equipment', 'labor-saving-machinery', 'greenhouse-zone' );
    if ( is_page( $fw_safety_subs ) ) {
        wp_enqueue_style( 'fwdignity-page-safety-sub',
            FWDIGNITY_THEME_URI . '/assets/css/page-safety-sub.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
        if ( is_page( 'personal-protective-equipment' ) ) {
            wp_enqueue_script( 'fwdignity-safety-sub-risk',
                FWDIGNITY_THEME_URI . '/assets/js/page-safety-sub.js',
                array(), FWDIGNITY_VERSION, true );
        }
    }

    // 4 分眾入口頁共用 CSS + FX JS(大小寫皆容)
    $fw_audience_slugs = array( 'beginner-farmers', 'young-farmers', 'experienced-farmers', 'Public', 'public' );
    if ( is_page( $fw_audience_slugs ) ) {
        wp_enqueue_style( 'fwdignity-page-audience',
            FWDIGNITY_THEME_URI . '/assets/css/page-audience.css',
            array( 'fwdignity-layout' ), FWDIGNITY_VERSION );
        wp_enqueue_style( 'fwdignity-lora-font',
            'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400;1,500&display=swap',
            array(), null );
        wp_enqueue_script( 'fwdignity-audience-fx',
            FWDIGNITY_THEME_URI . '/assets/js/audience-fx.js',
            array(), FWDIGNITY_VERSION, true );
    }

    wp_enqueue_script( 'fwdignity-v15-master',
        FWDIGNITY_THEME_URI . '/assets/js/v15-master.js',
        array(), FWDIGNITY_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'fwdignity_enqueue_assets' );

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
        'instagram' => array( 'Instagram', 'https://www.instagram.com/' ),
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
        'social_instagram'=> 'https://www.instagram.com/',
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
 * 抓農業部 RSS（WS Feed：https://www.moa.gov.tw/ws.php?id=9817）
 * 回傳 array 結構：[['title'=>,'link'=>,'date'=>,'excerpt'=>], ...]
 * 失敗時回空 array，不噴錯。
 */
function fwdignity_fetch_moa_rss( $count = 4 ) {
    if ( ! function_exists( 'fetch_feed' ) ) {
        include_once ABSPATH . WPINC . '/feed.php';
    }
    $items = array();
    $feed = fetch_feed( 'https://www.moa.gov.tw/ws.php?id=9817' );
    if ( is_wp_error( $feed ) ) { return $items; }
    $feed->set_cache_duration( HOUR_IN_SECONDS * 6 );
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
        $thumb = get_the_post_thumbnail_url( $pid, 'large' );
        $video_id = '';
        $content = get_post_field( 'post_content', $pid );
        $meta = get_post_meta( $pid, '_elementor_data', true );
        $search = stripslashes( $content . ' ' . ( is_string( $meta ) ? $meta : wp_json_encode( $meta ) ) );
        if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $search, $m ) ) {
            $video_id = $m[1];
            if ( ! $thumb ) { $thumb = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg"; }
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
