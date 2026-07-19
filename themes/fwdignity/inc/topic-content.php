<?php
/**
 * Shared data model for the People Who Farm page group.
 *
 * @package fwdignity
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return the fixed editorial topic registry.
 *
 * @return array<string, array<string, string>>
 */
function fwdignity_topic_registry() {
    return array(
        'body-workload' => array(
            'label'     => '身體如何承受農務？',
            'page_slug' => 'body-and-farm-work',
            'term_slug' => 'body-workload',
            'core'      => '農務的負荷不只發生在受傷當下，也累積在姿勢、工時、暴露、疲勞與心理壓力裡。',
        ),
        'changing-work' => array(
            'label'     => '農業工作正在怎麼改變？',
            'page_slug' => 'changing-farm-work',
            'term_slug' => 'changing-work',
            'core'      => '高齡、缺工、代耕、有機生產與新技術，正在重新安排誰做什麼、怎麼做，以及風險轉到哪裡。',
        ),
        'rural-support' => array(
            'label'     => '誰在撐起農村？',
            'page_slug' => 'rural-support',
            'term_slug' => 'rural-support',
            'core'      => '農村由田間勞動、家務照顧、社群協調與地方支持共同撐住，其中許多工作長期不被看見。',
        ),
        'risk-responsibility' => array(
            'label'     => '風險由誰承擔？',
            'page_slug' => 'risk-and-responsibility',
            'term_slug' => 'risk-responsibility',
            'core'      => '職傷、災損、收入中斷與申請成本不只由個人承擔，也反映制度如何接住或漏接。',
        ),
        'knowledge-to-field' => array(
            'label'     => '知識如何走進田間？',
            'page_slug' => 'knowledge-in-the-field',
            'term_slug' => 'knowledge-to-field',
            'core'      => '知識要能進入田間，必須被取得、理解、信任並轉成可做的行動，而不只是被放上網站。',
        ),
    );
}

/**
 * Register the internal topic taxonomy and Page meta.
 */
function fwdignity_register_topic_content() {
    register_taxonomy(
        'fwd_topic',
        array( 'post', 'page' ),
        array(
            'labels' => array(
                'name'          => '田野主題',
                'singular_name' => '田野主題',
                'search_items'  => '搜尋主題',
                'all_items'     => '所有主題',
                'edit_item'     => '編輯主題',
                'update_item'   => '更新主題',
                'add_new_item'  => '新增主題',
                'new_item_name' => '新主題名稱',
                'menu_name'     => '田野主題',
            ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_admin_column'  => true,
            'show_in_rest'       => true,
            'hierarchical'       => false,
            'rewrite'            => false,
            'query_var'          => false,
        )
    );

    register_post_meta(
        'page',
        '_fwd_topic_featured_post_id',
        array(
            'type'              => 'integer',
            'single'            => true,
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'auth_callback'     => static function() {
                return current_user_can( 'edit_pages' );
            },
            'show_in_rest'      => true,
        )
    );

    add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'fwdignity_register_topic_content', 5 );

/**
 * Keep the five fixed terms available without removing editor-created terms.
 */
function fwdignity_ensure_topic_terms() {
    foreach ( fwdignity_topic_registry() as $topic ) {
        $term = term_exists( $topic['term_slug'], 'fwd_topic' );
        if ( ! $term ) {
            wp_insert_term(
                $topic['label'],
                'fwd_topic',
                array( 'slug' => $topic['term_slug'] )
            );
            continue;
        }

        $term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
        $current = get_term( $term_id, 'fwd_topic' );
        if ( $current && ! is_wp_error( $current ) && $topic['label'] !== $current->name ) {
            wp_update_term(
                $term_id,
                'fwd_topic',
                array( 'name' => $topic['label'] )
            );
        }
    }
}
add_action( 'init', 'fwdignity_ensure_topic_terms', 20 );

/**
 * Resolve the public Page for a topic.
 *
 * @param string $term_slug Topic term slug.
 * @return WP_Post|null
 */
function fwdignity_get_topic_page( $term_slug ) {
    $registry = fwdignity_topic_registry();
    if ( ! isset( $registry[ $term_slug ] ) ) {
        return null;
    }

    $page = get_page_by_path(
        'people-who-farm/' . $registry[ $term_slug ]['page_slug'],
        OBJECT,
        'page'
    );

    return $page instanceof WP_Post ? $page : null;
}

/**
 * Resolve the public Page URL for a topic.
 *
 * @param string $term_slug Topic term slug.
 * @return string
 */
function fwdignity_get_topic_page_url( $term_slug ) {
    $page = fwdignity_get_topic_page( $term_slug );
    if ( ! $page || 'publish' !== $page->post_status ) {
        return '';
    }

    return (string) get_permalink( $page );
}

/**
 * Resolve a topic slug from a registered child Page.
 *
 * @param int $page_id Page ID.
 * @return string
 */
function fwdignity_topic_term_slug_for_page( $page_id ) {
    $page = get_post( $page_id );
    if ( ! $page instanceof WP_Post || 'page' !== $page->post_type ) {
        return '';
    }

    foreach ( fwdignity_topic_registry() as $term_slug => $topic ) {
        if ( $topic['page_slug'] === $page->post_name ) {
            return $term_slug;
        }
    }

    return '';
}

/**
 * Return a publish-only topic query.
 *
 * @param string $term_slug Topic term slug.
 * @param array  $args      WP_Query overrides.
 * @return WP_Query
 */
function fwdignity_topic_query( $term_slug, $args = array() ) {
    $query_args = wp_parse_args(
        $args,
        array(
            'posts_per_page'      => 7,
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
        )
    );

    $query_args['post_type']   = 'post';
    $query_args['post_status'] = 'publish';
    $query_args['orderby']     = array(
        'date' => 'DESC',
        'ID'   => 'DESC',
    );
    $query_args['tax_query']   = array(
        array(
            'taxonomy' => 'fwd_topic',
            'field'    => 'slug',
            'terms'    => array( sanitize_title( $term_slug ) ),
            'operator' => 'IN',
        ),
    );

    return new WP_Query( $query_args );
}

/**
 * Return deterministic, presentation-limited labels for an article card.
 *
 * @param int    $post_id       Post ID.
 * @param string $current_topic Topic already represented by the Page.
 * @return array<int, array<string, string|int>>
 */
function fwdignity_topic_card_labels( $post_id, $current_topic = '' ) {
    $labels = array();
    $format_registry = array(
        'news'             => '消息',
        'field-note'       => '田野紀錄',
        'agri-school'      => '培力與活動',
        'education-farmer' => '培力與活動',
        'event'            => '培力與活動',
        'podcast'          => 'Podcast',
        'youtube'          => '影片',
        'video'            => '影片',
        'knowledge'        => '知識文章',
        'research-action'  => '研究與倡議',
    );

    $category_slugs = wp_list_pluck( get_the_category( $post_id ), 'slug' );
    foreach ( $format_registry as $slug => $label ) {
        if ( in_array( $slug, $category_slugs, true ) && ! in_array( $label, wp_list_pluck( $labels, 'label' ), true ) ) {
            $labels[] = array(
                'type'  => 'format',
                'label' => $label,
            );
        }
        if ( 2 <= count( $labels ) ) {
            break;
        }
    }

    $topic_terms = get_the_terms( $post_id, 'fwd_topic' );
    $topic_slugs = $topic_terms && ! is_wp_error( $topic_terms )
        ? wp_list_pluck( $topic_terms, 'slug' )
        : array();
    foreach ( fwdignity_topic_registry() as $term_slug => $topic ) {
        if ( $term_slug === $current_topic || ! in_array( $term_slug, $topic_slugs, true ) ) {
            continue;
        }
        $labels[] = array(
            'type'  => 'topic',
            'label' => $topic['label'],
        );
        break;
    }

    return array_slice( $labels, 0, 3 );
}

/**
 * Render one topic content card with a single focusable destination.
 *
 * @param int    $post_id       Post ID.
 * @param string $current_topic Topic represented by the current Page.
 * @param string $class_name    Additional card class.
 */
function fwdignity_render_topic_card( $post_id, $current_topic = '', $class_name = '' ) {
    $post = get_post( $post_id );
    if ( ! $post instanceof WP_Post || 'publish' !== $post->post_status ) {
        return;
    }

    $labels  = fwdignity_topic_card_labels( $post_id, $current_topic );
    $excerpt = get_the_excerpt( $post );
    if ( '' === trim( $excerpt ) ) {
        $excerpt = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 32, '…' );
    }
    $thumbnail_id  = get_post_thumbnail_id( $post_id );
    $thumbnail_alt = $thumbnail_id ? trim( (string) get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) ) : '';
    if ( '' === $thumbnail_alt ) {
        $thumbnail_alt = $post->post_title;
    }
    ?>
    <article class="pf-content-card <?php echo esc_attr( $class_name ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>">
        <a class="pf-content-card__link" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
            <?php if ( $thumbnail_id ) : ?>
                <figure class="pf-content-card__media">
                    <?php
                    echo wp_get_attachment_image(
                        $thumbnail_id,
                        'medium_large',
                        false,
                        array(
                            'loading' => 'lazy',
                            'alt'     => $thumbnail_alt,
                        )
                    );
                    ?>
                </figure>
            <?php endif; ?>
            <div class="pf-content-card__body">
                <?php if ( $labels ) : ?>
                    <div class="pf-content-card__labels" aria-label="內容標籤">
                        <?php foreach ( $labels as $label ) : ?>
                            <span data-label-type="<?php echo esc_attr( $label['type'] ); ?>"><?php echo esc_html( $label['label'] ); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $post_id ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d', $post_id ) ); ?></time>
                <h3><?php echo esc_html( $post->post_title ); ?></h3>
                <?php if ( $excerpt ) : ?>
                    <p><?php echo esc_html( $excerpt ); ?></p>
                <?php endif; ?>
                <span class="pf-content-card__action">閱讀內容 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
            </div>
        </a>
    </article>
    <?php
}

/**
 * Add the topic Page featured-post metabox.
 */
function fwdignity_add_topic_page_metabox() {
    $screen = get_current_screen();
    if ( ! $screen || 'page' !== $screen->post_type ) {
        return;
    }

    $post_id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0;
    if ( ! $post_id || ! fwdignity_topic_term_slug_for_page( $post_id ) ) {
        return;
    }

    add_meta_box(
        'fwd-topic-featured-post',
        '主題起讀內容',
        'fwdignity_render_topic_page_metabox',
        'page',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes_page', 'fwdignity_add_topic_page_metabox' );

/**
 * Render the featured-post selector.
 *
 * @param WP_Post $post Current Page.
 */
function fwdignity_render_topic_page_metabox( $post ) {
    $term_slug = fwdignity_topic_term_slug_for_page( $post->ID );
    $selected  = (int) get_post_meta( $post->ID, '_fwd_topic_featured_post_id', true );
    $query     = fwdignity_topic_query(
        $term_slug,
        array( 'posts_per_page' => 100 )
    );

    wp_nonce_field( 'fwdignity_save_topic_page_meta', 'fwdignity_topic_page_nonce' );
    ?>
    <label for="fwd-topic-featured-post-select">選擇一篇已發布且屬於本題的內容</label>
    <select id="fwd-topic-featured-post-select" name="fwd_topic_featured_post_id" style="width:100%;margin-top:8px">
        <option value="0">自動使用最新內容</option>
        <?php foreach ( $query->posts as $candidate ) : ?>
            <option value="<?php echo esc_attr( $candidate->ID ); ?>" <?php selected( $selected, $candidate->ID ); ?>>
                <?php echo esc_html( $candidate->post_title ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

/**
 * Save the topic Page featured-post selection.
 *
 * @param int $post_id Page ID.
 */
function fwdignity_save_topic_page_meta( $post_id ) {
    if ( ! isset( $_POST['fwdignity_topic_page_nonce'] ) ) {
        return;
    }

    $nonce = sanitize_text_field( wp_unslash( $_POST['fwdignity_topic_page_nonce'] ) );
    if ( ! wp_verify_nonce( $nonce, 'fwdignity_save_topic_page_meta' ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $term_slug = fwdignity_topic_term_slug_for_page( $post_id );
    if ( ! $term_slug ) {
        return;
    }

    $featured_post_id = isset( $_POST['fwd_topic_featured_post_id'] )
        ? absint( wp_unslash( $_POST['fwd_topic_featured_post_id'] ) )
        : 0;
    if ( $featured_post_id && ( 'publish' !== get_post_status( $featured_post_id ) || ! has_term( $term_slug, 'fwd_topic', $featured_post_id ) ) ) {
        $featured_post_id = 0;
    }

    update_post_meta( $post_id, '_fwd_topic_featured_post_id', $featured_post_id );
}
add_action( 'save_post_page', 'fwdignity_save_topic_page_meta' );

/**
 * Route the five child Pages to one shared template.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function fwdignity_topic_page_template( $template ) {
    if ( ! is_page() || ! fwdignity_topic_term_slug_for_page( get_queried_object_id() ) ) {
        return $template;
    }

    $topic_template = FWDIGNITY_THEME_DIR . '/page-farm-work-topic.php';
    return file_exists( $topic_template ) ? $topic_template : $template;
}
add_filter( 'template_include', 'fwdignity_topic_page_template', 20 );
