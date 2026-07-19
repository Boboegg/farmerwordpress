<?php
/**
 * Data model and Page query helpers for the field knowledge hub.
 *
 * @package fwdignity
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return the farming guide information architecture.
 *
 * Slugs are the only content references kept in code. Titles, URLs, and
 * publication states always come from their WordPress Page records.
 *
 * @return array<string, array<string, array<string, string>>>
 */
function fwdignity_farming_guide_registry() {
    return array(
        'task_groups' => array(
            'body-health' => array(
                'label' => '身體與健康',
                'icon'  => 'fa-solid fa-heart-pulse',
            ),
            'pesticide-protection' => array(
                'label' => '農藥與防護',
                'icon'  => 'fa-solid fa-shield-halved',
            ),
            'machinery-environment' => array(
                'label' => '農機與工作環境',
                'icon'  => 'fa-solid fa-tractor',
            ),
            'economy-security' => array(
                'label' => '經濟、保險與保障',
                'icon'  => 'fa-solid fa-file-shield',
            ),
            'learning-support' => array(
                'label' => '學習與支持',
                'icon'  => 'fa-solid fa-people-group',
            ),
            'tools-resources' => array(
                'label' => '工具與正式資源',
                'icon'  => 'fa-solid fa-screwdriver-wrench',
            ),
        ),
        'resource_groups' => array(
            'material' => array(
                'label' => '教材',
                'icon'  => 'fa-solid fa-book-open',
            ),
            'checklist' => array(
                'label' => '檢核表',
                'icon'  => 'fa-solid fa-list-check',
            ),
            'calculator' => array(
                'label' => '試算工具',
                'icon'  => 'fa-solid fa-calculator',
            ),
        ),
        'items' => array(
            'farmer-study' => array(
                'task_group'     => 'learning-support',
                'resource_group' => '',
            ),
            'subpage-heat' => array(
                'task_group'     => 'body-health',
                'resource_group' => 'checklist',
            ),
            'personal-protective-equipment' => array(
                'task_group'     => 'pesticide-protection',
                'resource_group' => 'checklist',
            ),
            'labor-saving-machinery' => array(
                'task_group'     => 'machinery-environment',
                'resource_group' => 'checklist',
            ),
            'greenhouse-zone' => array(
                'task_group'     => 'machinery-environment',
                'resource_group' => 'material',
            ),
            'subpage-pesticide' => array(
                'task_group'     => 'pesticide-protection',
                'resource_group' => 'material',
            ),
            'subpage-msd' => array(
                'task_group'     => 'body-health',
                'resource_group' => 'material',
            ),
            'subpage-mental' => array(
                'task_group'     => 'body-health',
                'resource_group' => 'material',
            ),
            'occupational-accident-insurance' => array(
                'task_group'     => 'economy-security',
                'resource_group' => 'material',
            ),
            'crop-insurance' => array(
                'task_group'     => 'economy-security',
                'resource_group' => 'material',
            ),
            'agricultural-subsidy-resources' => array(
                'task_group'     => 'economy-security',
                'resource_group' => 'material',
            ),
            'worker-occupational-accident-calculator' => array(
                'task_group'     => 'tools-resources',
                'resource_group' => 'calculator',
            ),
            'farmer-worker-retirement-calculator' => array(
                'task_group'     => 'tools-resources',
                'resource_group' => 'calculator',
            ),
        ),
    );
}

/**
 * Query configured Pages and merge their current state into the registry.
 *
 * Drafts remain available to the data model for editorial planning. Public
 * collection helpers below are responsible for excluding them from output.
 *
 * @return array<string, mixed>
 */
function fwdignity_get_farming_guide_catalog() {
    $catalog = fwdignity_farming_guide_registry();
    $slugs   = array_keys( $catalog['items'] );
    $pages   = get_posts(
        array(
            'post_type'              => 'page',
            'post_status'            => array( 'publish', 'draft' ),
            'post_name__in'          => $slugs,
            'posts_per_page'         => -1,
            'orderby'                => 'post_name__in',
            'order'                  => 'ASC',
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'suppress_filters'       => false,
        )
    );

    $pages_by_slug = array();
    foreach ( $pages as $page ) {
        if ( $page instanceof WP_Post && in_array( $page->post_name, $slugs, true ) ) {
            $pages_by_slug[ $page->post_name ] = $page;
        }
    }

    foreach ( $catalog['items'] as $slug => &$item ) {
        $page                = isset( $pages_by_slug[ $slug ] ) ? $pages_by_slug[ $slug ] : null;
        $item['page']        = $page;
        $item['post_status'] = $page instanceof WP_Post ? $page->post_status : '';
    }
    unset( $item );

    return $catalog;
}

/**
 * Build publish-only collections for one catalog facet.
 *
 * @param array<string, mixed> $catalog       Farming guide catalog.
 * @param string               $groups_key    Registry group key.
 * @param string               $item_group_key Item classification key.
 * @return array<int, array<string, mixed>>
 */
function fwdignity_collect_public_farming_guide_groups( $catalog, $groups_key, $item_group_key ) {
    if ( empty( $catalog[ $groups_key ] ) || empty( $catalog['items'] ) ) {
        return array();
    }

    $public_groups = array();
    foreach ( $catalog[ $groups_key ] as $group_key => $group ) {
        $public_items = array();

        foreach ( $catalog['items'] as $slug => $item ) {
            if ( $group_key !== $item[ $item_group_key ] ) {
                continue;
            }

            $page = $item['page'];
            if ( ! $page instanceof WP_Post || 'publish' !== $item['post_status'] ) {
                continue;
            }

            $title = trim( (string) get_the_title( $page ) );
            $url   = (string) get_permalink( $page );
            if ( '' === $title || '' === $url ) {
                continue;
            }

            $public_items[] = array(
                'slug'  => $slug,
                'title' => $title,
                'url'   => $url,
            );
        }

        if ( ! $public_items ) {
            continue;
        }

        $public_groups[] = array(
            'key'   => $group_key,
            'label' => $group['label'],
            'icon'  => $group['icon'],
            'items' => $public_items,
        );
    }

    return $public_groups;
}

/**
 * Return publish-only task collections.
 *
 * @param array<string, mixed> $catalog Farming guide catalog.
 * @return array<int, array<string, mixed>>
 */
function fwdignity_get_public_farming_guide_groups( $catalog ) {
    return fwdignity_collect_public_farming_guide_groups( $catalog, 'task_groups', 'task_group' );
}

/**
 * Return all six approved task groups while keeping unpublished items hidden.
 *
 * Empty groups are structural placeholders only: they expose no draft title,
 * URL, summary, or professional guidance.
 *
 * @param array<string, mixed> $catalog Farming guide catalog.
 * @return array<int, array<string, mixed>>
 */
function fwdignity_get_farming_guide_task_index( $catalog ) {
    $public_groups = fwdignity_get_public_farming_guide_groups( $catalog );
    $public_by_key = array();

    foreach ( $public_groups as $group ) {
        $public_by_key[ $group['key'] ] = $group['items'];
    }

    $task_index = array();
    foreach ( $catalog['task_groups'] as $key => $group ) {
        $task_index[] = array(
            'key'   => $key,
            'label' => $group['label'],
            'icon'  => $group['icon'],
            'items' => isset( $public_by_key[ $key ] ) ? $public_by_key[ $key ] : array(),
        );
    }

    return $task_index;
}

/**
 * Return publish-only resource collections.
 *
 * @param array<string, mixed> $catalog Farming guide catalog.
 * @return array<int, array<string, mixed>>
 */
function fwdignity_get_public_farming_guide_resources( $catalog ) {
    return fwdignity_collect_public_farming_guide_groups( $catalog, 'resource_groups', 'resource_group' );
}
