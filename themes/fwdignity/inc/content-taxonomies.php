<?php
/**
 * Shared classification axes for the three-layer content architecture.
 *
 * These taxonomies are editorial facets. Public collection routes are enabled
 * separately only when they contain content that passes the publication gate.
 *
 * @package fwdignity
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return the controlled dictionaries for the five shared classification axes.
 *
 * @return array<string, array<string, mixed>>
 */
function fwdignity_content_taxonomy_registry() {
    return array(
        'content_type' => array(
            'label' => '內容型態',
            'terms' => array(
                'news'       => '消息文章',
                'field-note' => '田野紀錄',
                'training'   => '農學堂',
                'podcast'    => 'Podcast',
                'video'      => '影片',
                'guide'      => '田間知識',
                'research'   => '研究與倡議',
                'resource'   => '外部資源',
                'tool'       => '互動工具',
            ),
        ),
        'domain' => array(
            'label' => '田間知識主題',
            'terms' => array(
                'body-health'                  => '身體與健康',
                'pesticide-protection'         => '農藥與防護',
                'machinery-work-environment'   => '農機與工作環境',
                'economy-insurance-security'   => '經濟、保險與保障',
                'learning-support'             => '學習與支持',
                'tools-official-resources'     => '工具與正式資源',
            ),
        ),
        'task' => array(
            'label' => '查找需求',
            'terms' => array(
                'identify-risk'               => '辨識風險',
                'prepare-work'                => '作業前準備',
                'choose-protection-equipment' => '選擇防護或設備',
                'respond-warning-incident'    => '處理警訊或事故',
                'understand-rights-coverage'  => '了解保障與權益',
                'apply-resources'             => '申請資源',
                'use-tools'                   => '使用工具',
                'find-learning-support'       => '找課程與支持',
            ),
        ),
        'audience' => array(
            'label' => '推廣對象',
            'terms' => array(
                'general-public'         => '一般民眾',
                'agricultural-workers'   => '農業工作者',
                'academic-professionals' => '學術與專業者',
            ),
        ),
        'context' => array(
            'label' => '工作情境',
            'terms' => array(
                'heat-outdoor-work'            => '高溫與戶外作業',
                'greenhouse-work'               => '溫室作業',
                'machinery-operation'           => '農機操作',
                'pesticide-application'         => '農藥施作',
                'lifting-repetitive-work'       => '搬運與重複動作',
                'protective-equipment'          => '防護裝備選用',
                'incident-occupational-injury'  => '事故與職災處理',
                'insurance-subsidy-application' => '保險或補助申請',
            ),
        ),
    );
}

/**
 * Register shared, multi-select facets without exposing empty archives.
 */
function fwdignity_register_content_taxonomies() {
    foreach ( fwdignity_content_taxonomy_registry() as $taxonomy => $definition ) {
        register_taxonomy(
            $taxonomy,
            array( 'post', 'page' ),
            array(
                'labels' => array(
                    'name'          => $definition['label'],
                    'singular_name' => $definition['label'],
                    'search_items'  => '搜尋' . $definition['label'],
                    'all_items'     => '所有' . $definition['label'],
                    'edit_item'     => '編輯' . $definition['label'],
                    'update_item'   => '更新' . $definition['label'],
                    'add_new_item'  => '新增' . $definition['label'],
                    'new_item_name' => '新' . $definition['label'] . '名稱',
                    'menu_name'     => $definition['label'],
                ),
                'public'             => false,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_admin_column'  => true,
                'show_in_rest'       => true,
                'show_tagcloud'      => false,
                'hierarchical'       => false,
                'rewrite'            => false,
                'query_var'          => false,
            )
        );
    }
}
add_action( 'init', 'fwdignity_register_content_taxonomies', 5 );

/**
 * Keep the controlled dictionaries available to editors.
 */
function fwdignity_ensure_content_taxonomy_terms() {
    $legacy_public = get_term_by( 'slug', 'public', 'audience' );
    $general_public = get_term_by( 'slug', 'general-public', 'audience' );

    if ( $legacy_public && ! is_wp_error( $legacy_public ) && ! $general_public ) {
        wp_update_term(
            $legacy_public->term_id,
            'audience',
            array(
                'name' => '關心農業的一般民眾',
                'slug' => 'general-public',
            )
        );
    }

    foreach ( fwdignity_content_taxonomy_registry() as $taxonomy => $definition ) {
        foreach ( $definition['terms'] as $slug => $name ) {
            $term = get_term_by( 'slug', $slug, $taxonomy );

            if ( ! $term || is_wp_error( $term ) ) {
                wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
                continue;
            }

            if ( $name !== $term->name ) {
                wp_update_term( $term->term_id, $taxonomy, array( 'name' => $name ) );
            }
        }
    }
}
add_action( 'init', 'fwdignity_ensure_content_taxonomy_terms', 20 );
