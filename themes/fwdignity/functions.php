<?php
/**
 * fwdignity theme — functions.php
 *
 * Phase 1a skeleton — minimum viable theme functions.
 * 實際 enqueue / data-page / Gutenberg block 註冊在 S2-S4 階段擴充。
 *
 * @package fwdignity
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'FWDIGNITY_VERSION', '1.0-skeleton' );
define( 'FWDIGNITY_THEME_DIR', get_template_directory() );
define( 'FWDIGNITY_THEME_URI', get_template_directory_uri() );

/**
 * Theme setup — 註冊 WP 功能支援。
 */
function fwdignity_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'script', 'style',
    ) );
    add_theme_support( 'editor-styles' );

    register_nav_menus( array(
        'primary' => __( '主要導覽', 'fwdignity' ),
        'footer'  => __( '頁尾連結', 'fwdignity' ),
    ) );
}
add_action( 'after_setup_theme', 'fwdignity_setup' );

/**
 * body_class filter — 自動注入 slug class 方便 debug。
 */
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

if ( ! isset( $content_width ) ) {
    $content_width = 1280;
}

/**
 * Placeholder：enqueue — S2 補入 v15-master.css / v15-master.js。
 */
function fwdignity_enqueue_assets() {
    wp_enqueue_style(
        'fwdignity-main',
        get_stylesheet_uri(),
        array(),
        FWDIGNITY_VERSION
    );
    // S2 補 Google Fonts + v15-master.css + v15-master.js
}
add_action( 'wp_enqueue_scripts', 'fwdignity_enqueue_assets' );

/**
 * Helper：取得當前頁面對應的 data-page slug（28 頁 OKLCH 切色用）。
 */
function fwdignity_get_data_page() {
    if ( is_front_page() ) {
        return 'home';
    }
    if ( is_singular() ) {
        $post = get_post();
        if ( $post && ! empty( $post->post_name ) ) {
            return sanitize_html_class( $post->post_name );
        }
    }
    if ( is_home() ) {
        return 'news';
    }
    return 'default';
}
