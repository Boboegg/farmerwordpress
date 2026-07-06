<?php
/**
 * fwdignity · Custom Nav Walker
 * 讓 wp_nav_menu 輸出符合 .fw-nav-item / .has-dropdown / .fw-dropdown 的結構
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Fwdignity_Nav_Walker extends Walker_Nav_Menu {

    // 上層 <ul class="fw-dropdown">
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= "\n<ul class=\"fw-dropdown\">\n";
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= "</ul>\n";
    }

    // <li> 節點
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $has_children = in_array( 'menu-item-has-children', $classes, true );

        $li_classes = array( 'fw-nav-item' );
        if ( $depth === 0 && $has_children ) { $li_classes[] = 'has-dropdown'; }
        if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current_page_item', $classes, true ) ) {
            $li_classes[] = 'is-active';
        }

        // 只有頂層 li 套 fw-nav-item;dropdown 內的 li 保持乾淨
        if ( $depth > 0 ) { $li_classes = array(); }

        $class_attr = $li_classes ? ' class="' . esc_attr( implode( ' ', $li_classes ) ) . '"' : '';
        $output .= '<li' . $class_attr . '>';

        $url    = ! empty( $item->url ) ? $item->url : '#';
        $target = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $xfn    = ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
        $title  = esc_html( $item->title );
        $caret  = ( $depth === 0 && $has_children ) ? ' <i class="fas fa-chevron-down fw-caret"></i>' : '';

        $a_class = ( $depth === 0 ) ? ' class="fw-nav-link"' : '';
        $output .= '<a href="' . esc_url( $url ) . '"' . $target . $xfn . $a_class . '>' . $title . $caret . '</a>';
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}
