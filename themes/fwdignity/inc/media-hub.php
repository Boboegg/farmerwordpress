<?php
/**
 * 語音影音資料模型、後台欄位與查詢 helper。
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fwdignity_register_media_hub_data() {
    register_taxonomy(
        'media_series',
        array( 'post' ),
        array(
            'labels'            => array(
                'name'          => '節目系列',
                'singular_name' => '節目系列',
                'search_items'  => '搜尋節目系列',
                'all_items'     => '全部節目系列',
                'edit_item'     => '編輯節目系列',
                'update_item'   => '更新節目系列',
                'add_new_item'  => '新增節目系列',
                'new_item_name' => '新節目系列名稱',
                'menu_name'     => '節目系列',
            ),
            'public'            => true,
            'publicly_queryable'=> true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'hierarchical'      => false,
            'rewrite'           => array(
                'slug'       => 'media-series',
                'with_front' => false,
            ),
        )
    );

    $auth_callback = static function () {
        return current_user_can( 'edit_posts' );
    };

    register_post_meta(
        'post',
        '_fwd_media_embed_url',
        array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'fwdignity_sanitize_media_embed_url',
            'auth_callback'     => $auth_callback,
        )
    );

    register_post_meta(
        'post',
        '_fwd_media_audio_url',
        array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'fwdignity_sanitize_media_audio_url',
            'auth_callback'     => $auth_callback,
        )
    );

    foreach ( array( '_fwd_media_source_url', '_fwd_media_ccu_url' ) as $meta_key ) {
        register_post_meta(
            'post',
            $meta_key,
            array(
                'type'              => 'string',
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => 'esc_url_raw',
                'auth_callback'     => $auth_callback,
            )
        );
    }

    register_post_meta(
        'post',
        '_fwd_import_source_guid',
        array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => false,
            'sanitize_callback' => 'sanitize_key',
            'auth_callback'     => $auth_callback,
        )
    );

    foreach ( array( '_fwd_media_episode_number', '_fwd_media_duration' ) as $meta_key ) {
        register_post_meta(
            'post',
            $meta_key,
            array(
                'type'              => 'string',
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => $auth_callback,
            )
        );
    }

    register_post_meta(
        'post',
        '_fwd_media_key_points',
        array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'fwdignity_sanitize_media_key_points',
            'auth_callback'     => $auth_callback,
        )
    );
}
add_action( 'init', 'fwdignity_register_media_hub_data' );

function fwdignity_sanitize_media_embed_url( $value ) {
    $url = esc_url_raw( trim( (string) $value ) );
    if ( '' === $url ) {
        return '';
    }

    $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
    $allowed_hosts = array(
        'open.spotify.com',
        'youtube.com',
        'www.youtube.com',
        'youtu.be',
        'youtube-nocookie.com',
        'www.youtube-nocookie.com',
    );

    return in_array( $host, $allowed_hosts, true ) ? $url : '';
}

function fwdignity_sanitize_media_audio_url( $value ) {
    $url = esc_url_raw( trim( (string) $value ) );
    if ( '' === $url || 'https' !== strtolower( (string) wp_parse_url( $url, PHP_URL_SCHEME ) ) ) {
        return '';
    }

    $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
    return in_array( $host, array( 'm.cdn.firstory.me', 'file.cdn.firstory.me' ), true ) ? $url : '';
}

function fwdignity_sanitize_media_key_points( $value ) {
    $lines = preg_split( '/\R/u', (string) $value );
    $lines = array_map( 'sanitize_text_field', (array) $lines );
    $lines = array_values( array_filter( array_map( 'trim', $lines ) ) );

    return implode( "\n", array_slice( $lines, 0, 3 ) );
}

function fwdignity_add_media_meta_box() {
    add_meta_box(
        'fwdignity-media-details',
        '語音影音資料',
        'fwdignity_render_media_meta_box',
        'post',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes_post', 'fwdignity_add_media_meta_box' );

function fwdignity_render_media_meta_box( $post ) {
    wp_nonce_field( 'fwdignity_save_media_meta', 'fwdignity_media_meta_nonce' );

    $fields = array(
        '_fwd_media_embed_url'       => array( '播放器網址', 'url' ),
        '_fwd_media_audio_url'       => array( 'Podcast 音訊網址', 'url' ),
        '_fwd_media_source_url'      => array( '原始節目頁面', 'url' ),
        '_fwd_media_ccu_url'         => array( '中正大學介紹頁', 'url' ),
        '_fwd_media_episode_number'  => array( '集數', 'text' ),
        '_fwd_media_duration'        => array( '節目長度', 'text' ),
    );
    ?>
    <p>播放器網址只接受 Spotify、YouTube 與 YouTube NoCookie；未設定時可使用 Firstory 音訊網址播放。</p>
    <?php foreach ( $fields as $meta_key => $field ) : ?>
        <p>
            <label for="<?php echo esc_attr( $meta_key ); ?>"><strong><?php echo esc_html( $field[0] ); ?></strong></label><br>
            <input class="widefat" type="<?php echo esc_attr( $field[1] ); ?>" id="<?php echo esc_attr( $meta_key ); ?>" name="<?php echo esc_attr( $meta_key ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, $meta_key, true ) ); ?>">
        </p>
    <?php endforeach; ?>
    <p>
        <label for="_fwd_media_key_points"><strong>三行重點</strong></label><br>
        <textarea class="widefat" rows="4" id="_fwd_media_key_points" name="_fwd_media_key_points"><?php echo esc_textarea( get_post_meta( $post->ID, '_fwd_media_key_points', true ) ); ?></textarea>
        <span class="description">每行一點，最多保留三行；未填寫時前端不顯示此區。</span>
    </p>
    <?php
}

function fwdignity_save_media_meta( $post_id ) {
    if ( ! isset( $_POST['fwdignity_media_meta_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fwdignity_media_meta_nonce'] ) ), 'fwdignity_save_media_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $sanitizers = array(
        '_fwd_media_embed_url'      => 'fwdignity_sanitize_media_embed_url',
        '_fwd_media_audio_url'      => 'fwdignity_sanitize_media_audio_url',
        '_fwd_media_source_url'     => 'esc_url_raw',
        '_fwd_media_ccu_url'        => 'esc_url_raw',
        '_fwd_media_episode_number' => 'sanitize_text_field',
        '_fwd_media_duration'       => 'sanitize_text_field',
        '_fwd_media_key_points'     => 'fwdignity_sanitize_media_key_points',
    );

    foreach ( $sanitizers as $meta_key => $sanitizer ) {
        $value = isset( $_POST[ $meta_key ] ) ? call_user_func( $sanitizer, wp_unslash( $_POST[ $meta_key ] ) ) : '';
        if ( '' === $value ) {
            delete_post_meta( $post_id, $meta_key );
        } else {
            update_post_meta( $post_id, $meta_key, $value );
        }
    }

    fwdignity_clear_media_hub_cache();
}
add_action( 'save_post_post', 'fwdignity_save_media_meta' );

function fwdignity_clear_media_hub_cache() {
    delete_transient( 'fwdignity_media_series_index_v1' );
    delete_transient( 'fwdignity_media_topic_index_v1' );
    delete_transient( 'fwdignity_media_year_index_v1' );
}
add_action( 'created_media_series', 'fwdignity_clear_media_hub_cache' );
add_action( 'edited_media_series', 'fwdignity_clear_media_hub_cache' );
add_action( 'delete_media_series', 'fwdignity_clear_media_hub_cache' );
add_action( 'set_object_terms', 'fwdignity_clear_media_hub_cache' );

function fwdignity_media_format_slugs() {
    return array( 'podcast', 'youtube', 'video' );
}

function fwdignity_media_format_for_post( $post_id ) {
    if ( has_category( 'podcast', $post_id ) ) {
        return 'podcast';
    }
    if ( has_category( 'youtube', $post_id ) ) {
        return 'youtube';
    }

    return 'video';
}

function fwdignity_media_format_label( $format ) {
    return 'podcast' === $format ? 'Podcast' : ( 'youtube' === $format ? 'YouTube' : '影片' );
}

function fwdignity_get_media_embed( $url ) {
    $url = fwdignity_sanitize_media_embed_url( $url );
    if ( '' === $url ) {
        return array( 'type' => '', 'url' => '' );
    }

    $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
    $path = (string) wp_parse_url( $url, PHP_URL_PATH );

    if ( 'open.spotify.com' === $host && 0 === strpos( $path, '/embed/' ) ) {
        return array( 'type' => 'spotify', 'url' => $url );
    }

    $video_id = '';
    if ( 'youtu.be' === $host ) {
        $video_id = trim( $path, '/' );
    } elseif ( false !== strpos( $host, 'youtube' ) ) {
        parse_str( (string) wp_parse_url( $url, PHP_URL_QUERY ), $query );
        if ( ! empty( $query['v'] ) ) {
            $video_id = (string) $query['v'];
        } elseif ( preg_match( '#/(?:embed|shorts)/([A-Za-z0-9_-]{11})#', $path, $matches ) ) {
            $video_id = $matches[1];
        }
    }

    if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', $video_id ) ) {
        return array(
            'type' => 'youtube',
            'url'  => 'https://www.youtube-nocookie.com/embed/' . $video_id,
        );
    }

    return array( 'type' => '', 'url' => '' );
}

function fwdignity_get_media_item( $post_id ) {
    $post_id = (int) $post_id;
    $format  = fwdignity_media_format_for_post( $post_id );
    $series  = wp_get_post_terms( $post_id, 'media_series' );
    $series  = is_wp_error( $series ) ? array() : $series;
    $excerpt = get_the_excerpt( $post_id );
    if ( '' === trim( (string) $excerpt ) ) {
        $excerpt = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 34, '…' );
    }

    $key_points = fwdignity_sanitize_media_key_points( get_post_meta( $post_id, '_fwd_media_key_points', true ) );
    $key_points = '' === $key_points ? array() : preg_split( '/\R/u', $key_points );
    $episode    = get_post_meta( $post_id, '_fwd_media_episode_number', true );
    if ( '' === $episode ) {
        $episode = get_post_meta( $post_id, 'episode_number', true );
    }

    $audio_url = fwdignity_sanitize_media_audio_url( get_post_meta( $post_id, '_fwd_media_audio_url', true ) );
    if ( 'podcast' === $format && '' !== $audio_url ) {
        $embed = array( 'type' => 'audio', 'url' => $audio_url );
    } else {
        $embed = fwdignity_get_media_embed( get_post_meta( $post_id, '_fwd_media_embed_url', true ) );
        if ( '' === $embed['url'] && '' !== $audio_url ) {
            $embed = array( 'type' => 'audio', 'url' => $audio_url );
        }
    }
    $thumb = get_the_post_thumbnail_url( $post_id, 'medium_large' );
    if ( ! $thumb && 'youtube' === $embed['type'] && preg_match( '#/embed/([A-Za-z0-9_-]{11})#', $embed['url'], $matches ) ) {
        $thumb = 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg';
    }

    return array(
        'id'           => $post_id,
        'title'        => get_the_title( $post_id ),
        'link'         => get_permalink( $post_id ),
        'date'         => get_the_date( 'Y.m.d', $post_id ),
        'year'         => get_the_date( 'Y', $post_id ),
        'excerpt'      => $excerpt,
        'thumb'        => $thumb ? $thumb : '',
        'format'       => $format,
        'format_label' => fwdignity_media_format_label( $format ),
        'series'       => $series,
        'episode'      => (string) $episode,
        'duration'     => (string) get_post_meta( $post_id, '_fwd_media_duration', true ),
        'key_points'   => array_slice( array_values( array_filter( (array) $key_points ) ), 0, 3 ),
        'embed'        => $embed,
    );
}

/**
 * 輸出 Podcast 共用播放器。
 *
 * @param array $item     fwdignity_get_media_item() 回傳值。
 * @param array $settings 播放器顯示設定。
 */
function fwdignity_render_audio_player( $item, $settings = array() ) {
    if (
        ! is_array( $item )
        || empty( $item['embed']['url'] )
        || 'audio' !== $item['embed']['type']
    ) {
        return;
    }

    $settings = wp_parse_args(
        $settings,
        array(
            'class'   => '',
            'compact' => false,
        )
    );
    $classes = array( 'fw-audio-player' );
    if ( $settings['compact'] ) {
        $classes[] = 'fw-audio-player--compact';
    }
    if ( $settings['class'] ) {
        $classes[] = sanitize_html_class( $settings['class'] );
    }

    $title    = isset( $item['title'] ) ? (string) $item['title'] : 'Podcast';
    $duration = isset( $item['duration'] ) ? (string) $item['duration'] : '';
    ?>
    <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-fw-audio-player>
        <button
            class="fw-audio-player__toggle"
            type="button"
            data-fw-audio-toggle
            aria-label="播放：<?php echo esc_attr( $title ); ?>"
        >
            <i class="fa-solid fa-play" data-fw-audio-icon aria-hidden="true"></i>
        </button>
        <div class="fw-audio-player__body">
            <div class="fw-audio-player__status-row">
                <span data-fw-audio-state>準備播放</span>
                <span class="fw-audio-player__times"><span data-fw-audio-current>00:00</span><span aria-hidden="true"> / </span><span data-fw-audio-duration><?php echo esc_html( $duration ? $duration : '--:--' ); ?></span></span>
            </div>
            <div class="fw-audio-player__timeline">
                <span class="fw-audio-player__progress" data-fw-audio-progress aria-hidden="true"></span>
                <input type="range" min="0" max="100" value="0" step="0.1" data-fw-audio-seek aria-label="<?php echo esc_attr( '調整播放進度：' . $title ); ?>">
            </div>
            <p class="fw-audio-player__message" data-fw-audio-message role="status" hidden></p>
        </div>
        <audio src="<?php echo esc_url( $item['embed']['url'] ); ?>" preload="none" data-fw-audio-element hidden></audio>
    </div>
    <?php
}

function fwdignity_media_query_args( $args = array() ) {
    $defaults = array(
        'posts_per_page' => 12,
        'paged'          => 1,
        'order'          => 'DESC',
        'keyword'        => '',
        'formats'        => array(),
        'series'         => array(),
        'topics'         => array(),
        'year'           => '',
        'tag_slug'       => '',
    );
    $args = wp_parse_args( $args, $defaults );

    $formats = array_values( array_intersect( array_map( 'sanitize_title', (array) $args['formats'] ), fwdignity_media_format_slugs() ) );
    if ( ! $formats ) {
        $formats = fwdignity_media_format_slugs();
    }

    $tax_query = array( 'relation' => 'AND' );
    $tax_query[] = array(
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => $formats,
        'operator' => 'IN',
    );

    if ( ! empty( $args['series'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'media_series',
            'field'    => 'slug',
            'terms'    => array_map( 'sanitize_title', (array) $args['series'] ),
            'operator' => 'IN',
        );
    }

    if ( ! empty( $args['topics'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => array_map( 'sanitize_title', (array) $args['topics'] ),
            'operator' => 'IN',
        );
    }

    if ( ! empty( $args['tag_slug'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => sanitize_title( $args['tag_slug'] ),
        );
    }

    $query_args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => (int) $args['posts_per_page'],
        'paged'               => max( 1, (int) $args['paged'] ),
        'orderby'             => 'date',
        'order'               => 'ASC' === strtoupper( (string) $args['order'] ) ? 'ASC' : 'DESC',
        'ignore_sticky_posts' => true,
        'tax_query'           => $tax_query,
    );

    if ( '' !== trim( (string) $args['keyword'] ) ) {
        $query_args['s'] = sanitize_text_field( $args['keyword'] );
    }
    if ( preg_match( '/^20\d{2}$/', (string) $args['year'] ) ) {
        $query_args['date_query'] = array( array( 'year' => (int) $args['year'] ) );
    }

    return $query_args;
}

function fwdignity_query_media_items( $args = array() ) {
    $query = new WP_Query( fwdignity_media_query_args( $args ) );
    $items = array_map( 'fwdignity_get_media_item', wp_list_pluck( $query->posts, 'ID' ) );

    return array(
        'items' => $items,
        'total' => (int) $query->found_posts,
        'pages' => (int) $query->max_num_pages,
        'query' => $query,
    );
}

function fwdignity_get_media_items( $args = array() ) {
    $result = fwdignity_query_media_items( $args );
    return $result['items'];
}

function fwdignity_get_featured_media_items( $limit = 3 ) {
    $limit    = max( 1, (int) $limit );
    $featured = fwdignity_get_media_items(
        array(
            'posts_per_page' => $limit,
            'tag_slug'       => 'featured-media',
        )
    );
    $seen = array_fill_keys( array_map( 'intval', wp_list_pluck( $featured, 'id' ) ), true );

    if ( count( $featured ) < $limit ) {
        foreach ( fwdignity_get_media_items( array( 'posts_per_page' => $limit * 2 ) ) as $item ) {
            if ( isset( $seen[ $item['id'] ] ) ) {
                continue;
            }
            $featured[] = $item;
            $seen[ $item['id'] ] = true;
            if ( count( $featured ) >= $limit ) {
                break;
            }
        }
    }

    return array_slice( $featured, 0, $limit );
}

function fwdignity_get_media_series_terms() {
    $terms = get_terms(
        array(
            'taxonomy'   => 'media_series',
            'hide_empty' => true,
            'orderby'    => 'name',
        )
    );
    if ( is_wp_error( $terms ) ) {
        return array();
    }

    $items = array();
    foreach ( $terms as $term ) {
        $latest = fwdignity_get_media_items(
            array(
                'posts_per_page' => 1,
                'series'         => array( $term->slug ),
            )
        );
        if ( ! $latest ) {
            continue;
        }
        $items[] = array(
            'term'        => $term,
            'count'       => (int) $term->count,
            'latest_date' => $latest[0]['date'],
            'thumb'       => $latest[0]['thumb'],
            'link'        => get_term_link( $term ),
        );
    }

    return $items;
}

function fwdignity_get_media_topic_terms() {
    $media_ids = get_posts(
        array_merge(
            fwdignity_media_query_args( array( 'posts_per_page' => -1 ) ),
            array( 'fields' => 'ids', 'no_found_rows' => true )
        )
    );
    $format_slugs = array_merge( array( 'media' ), fwdignity_media_format_slugs() );
    $counts = array();
    $terms  = array();

    foreach ( $media_ids as $post_id ) {
        foreach ( wp_get_post_categories( $post_id, array( 'fields' => 'all' ) ) as $term ) {
            if ( in_array( $term->slug, $format_slugs, true ) ) {
                continue;
            }
            $terms[ $term->term_id ] = $term;
            $counts[ $term->term_id ] = isset( $counts[ $term->term_id ] ) ? $counts[ $term->term_id ] + 1 : 1;
        }
    }

    uasort(
        $terms,
        static function ( $left, $right ) {
            return strnatcasecmp( $left->name, $right->name );
        }
    );

    return array_map(
        static function ( $term ) use ( $counts ) {
            return array( 'term' => $term, 'count' => $counts[ $term->term_id ] );
        },
        array_values( $terms )
    );
}

function fwdignity_get_media_years() {
    $items = fwdignity_get_media_items( array( 'posts_per_page' => -1 ) );
    $years = array_values( array_unique( wp_list_pluck( $items, 'year' ) ) );
    rsort( $years, SORT_NUMERIC );

    return $years;
}
