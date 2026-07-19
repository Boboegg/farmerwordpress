<?php
/**
 * Template Name: 全部內容
 * Slug: thenews
 * 全部內容：多條件文章索引。
 *
 * 全部內容來自 WordPress posts、既有 category 與內部 fwd_topic taxonomy；不建立重複文章。
 * 同一分面內採 OR，不同分面之間採 AND。
 */

$set_content_library_document_title = static function ( $title_parts ) {
    $title_parts['title'] = '全部內容';

    return $title_parts;
};

add_filter( 'document_title_parts', $set_content_library_document_title, 20 );
get_header();
remove_filter( 'document_title_parts', $set_content_library_document_title, 20 );

$page_url = get_permalink();
$paged    = max( 1, (int) ( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ) );

$read_array_query = static function ( $key ) {
    if ( ! isset( $_GET[ $key ] ) ) {
        return array();
    }

    $raw = (array) wp_unslash( $_GET[ $key ] );
    $raw = array_map( 'sanitize_title', $raw );

    return array_values( array_unique( array_filter( $raw ) ) );
};

$selected_formats         = $read_array_query( 'format' );
$requested_people_topics  = $read_array_query( 'topic' );
$selected_category_topics = $read_array_query( 'category_topic' );
$keyword                  = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';
$sort_order               = isset( $_GET['order'] ) && 'oldest' === sanitize_key( wp_unslash( $_GET['order'] ) ) ? 'oldest' : 'newest';
$legacy_tab               = isset( $_GET['tab'] ) ? sanitize_title( wp_unslash( $_GET['tab'] ) ) : '';

$all_categories = get_categories(
    array(
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    )
);

$categories_by_id   = array();
$categories_by_slug = array();
foreach ( $all_categories as $category ) {
    $categories_by_id[ $category->term_id ]     = $category;
    $categories_by_slug[ $category->slug ]      = $category;
}

// 舊站以 media 為影音與教材的父分類；其子分類均視為內容形式。
$format_priority = array( 'news', 'media', 'podcast', 'youtube', 'video', 'infographic', 'downloads' );
$format_ids      = array();
$media_root      = isset( $categories_by_slug['media'] ) ? $categories_by_slug['media'] : null;

if ( $media_root ) {
    $format_ids[] = (int) $media_root->term_id;
    foreach ( get_term_children( $media_root->term_id, 'category' ) as $child_id ) {
        $format_ids[] = (int) $child_id;
    }
}
if ( isset( $categories_by_slug['news'] ) ) {
    $format_ids[] = (int) $categories_by_slug['news']->term_id;
}
$format_ids = array_values( array_unique( $format_ids ) );

$format_categories = array();
foreach ( $format_priority as $slug ) {
    if ( isset( $categories_by_slug[ $slug ] ) ) {
        $format_categories[] = $categories_by_slug[ $slug ];
    }
}
foreach ( $format_ids as $format_id ) {
    if ( ! isset( $categories_by_id[ $format_id ] ) ) {
        continue;
    }
    $candidate = $categories_by_id[ $format_id ];
    if ( ! in_array( $candidate, $format_categories, true ) ) {
        $format_categories[] = $candidate;
    }
}

// 消息文章的子分類仍是內容主題；其餘非 media 分類樹也全部自動進入主題分面。
$topic_categories = array();
foreach ( $all_categories as $category ) {
    $is_media_family = $media_root && (
        (int) $category->term_id === (int) $media_root->term_id ||
        term_is_ancestor_of( $media_root->term_id, $category->term_id, 'category' )
    );
    $is_news_root = 'news' === $category->slug;

    if ( ! $is_media_family && ! $is_news_root ) {
        $topic_categories[] = $category;
    }
}

// 用文章 ID 集合計算父分類含子分類後的真實數量，避免一篇多分類被重複計數。
$term_post_ids = get_transient( 'fwdignity_thenews_facet_counts_v1' );
if ( false === $term_post_ids ) {
    $term_post_ids = array();
    $published_ids = get_posts(
        array(
            'post_type'              => 'post',
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            'orderby'                => 'none',
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => true,
        )
    );

    foreach ( $published_ids as $post_id ) {
        foreach ( wp_get_post_categories( $post_id ) as $term_id ) {
            $related_ids = array_merge( array( (int) $term_id ), array_map( 'intval', get_ancestors( $term_id, 'category', 'taxonomy' ) ) );
            foreach ( array_unique( $related_ids ) as $related_id ) {
                $term_post_ids[ $related_id ][ $post_id ] = true;
            }
        }
    }

    set_transient( 'fwdignity_thenews_facet_counts_v1', $term_post_ids, DAY_IN_SECONDS );
}

$term_count = static function ( $term_id ) use ( $term_post_ids ) {
    return isset( $term_post_ids[ $term_id ] ) ? count( $term_post_ids[ $term_id ] ) : 0;
};

// 沒有內容的舊規劃分類暫不佔版面；未來有文章後會自動出現。
$format_categories = array_values(
    array_filter(
        $format_categories,
        static function ( $category ) use ( $term_count ) {
            return $term_count( $category->term_id ) > 0;
        }
    )
);
$topic_categories = array_values(
    array_filter(
        $topic_categories,
        static function ( $category ) use ( $term_count ) {
            return $term_count( $category->term_id ) > 0;
        }
    )
);

$sort_categories_by_tree = static function ( $categories, $priority_slugs = array() ) {
    $by_id    = array();
    $children = array();
    foreach ( $categories as $category ) {
        $by_id[ $category->term_id ] = $category;
    }
    foreach ( $categories as $category ) {
        $parent_id = isset( $by_id[ $category->parent ] ) ? (int) $category->parent : 0;
        $children[ $parent_id ][] = $category;
    }

    $sort_terms = static function ( &$terms ) use ( $priority_slugs ) {
        usort(
            $terms,
            static function ( $left, $right ) use ( $priority_slugs ) {
                $left_rank  = array_search( $left->slug, $priority_slugs, true );
                $right_rank = array_search( $right->slug, $priority_slugs, true );
                $left_rank  = false === $left_rank ? PHP_INT_MAX : $left_rank;
                $right_rank = false === $right_rank ? PHP_INT_MAX : $right_rank;
                return $left_rank === $right_rank
                    ? strnatcasecmp( $left->name, $right->name )
                    : $left_rank <=> $right_rank;
            }
        );
    };

    $ordered = array();
    $walk    = static function ( $parent_id ) use ( &$walk, &$ordered, &$children, $sort_terms ) {
        if ( empty( $children[ $parent_id ] ) ) {
            return;
        }
        $sort_terms( $children[ $parent_id ] );
        foreach ( $children[ $parent_id ] as $category ) {
            $ordered[] = $category;
            $walk( $category->term_id );
        }
    };
    $walk( 0 );

    return $ordered;
};

$topic_categories = $sort_categories_by_tree(
    $topic_categories,
    array( 'agri-school', 'environment', 'equipment', 'health', 'insurance', 'research', 'event', 'press', 'notices' )
);

$allowed_format_slugs       = wp_list_pluck( $format_categories, 'slug' );
$allowed_category_slugs     = wp_list_pluck( $topic_categories, 'slug' );
$people_topic_registry      = function_exists( 'fwdignity_topic_registry' ) ? fwdignity_topic_registry() : array();
$allowed_people_topic_slugs = array_keys( $people_topic_registry );
$selected_formats           = array_values( array_intersect( $selected_formats, $allowed_format_slugs ) );
$selected_people_topics     = array_values( array_intersect( $requested_people_topics, $allowed_people_topic_slugs ) );

// 2026-07-12 前的最新消息曾以 topic[] 表示 category；舊網址仍可使用。
$legacy_category_topics    = array_diff( $requested_people_topics, $allowed_people_topic_slugs );
$selected_category_topics = array_values(
    array_intersect(
        array_unique( array_merge( $selected_category_topics, $legacy_category_topics ) ),
        $allowed_category_slugs
    )
);

// 舊站使用 ?tab=slug；保留舊書籤，但新操作一律輸出可複選分面。
if ( $legacy_tab && ! $selected_formats && ! $selected_category_topics && ! $selected_people_topics ) {
    if ( in_array( $legacy_tab, $allowed_format_slugs, true ) ) {
        $selected_formats[] = $legacy_tab;
    } elseif ( in_array( $legacy_tab, $allowed_category_slugs, true ) ) {
        $selected_category_topics[] = $legacy_tab;
    }
}

$tax_query = array( 'relation' => 'AND' );
if ( $selected_formats ) {
    $tax_query[] = array(
        'taxonomy'         => 'category',
        'field'            => 'slug',
        'terms'            => $selected_formats,
        'operator'         => 'IN',
        'include_children' => true,
    );
}
if ( $selected_category_topics ) {
    $tax_query[] = array(
        'taxonomy'         => 'category',
        'field'            => 'slug',
        'terms'            => $selected_category_topics,
        'operator'         => 'IN',
        'include_children' => true,
    );
}
if ( $selected_people_topics ) {
    $tax_query[] = array(
        'taxonomy' => 'fwd_topic',
        'field'    => 'slug',
        'terms'    => $selected_people_topics,
        'operator' => 'IN',
    );
}

$query_args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'orderby'             => 'date',
    'order'               => 'oldest' === $sort_order ? 'ASC' : 'DESC',
    'ignore_sticky_posts' => true,
);
if ( $keyword ) {
    $query_args['s'] = $keyword;
}
if ( count( $tax_query ) > 1 ) {
    $query_args['tax_query'] = $tax_query;
}

$news_query = new WP_Query( $query_args );

$format_label_overrides = array(
    'news'    => '消息文章',
    'media'   => '影音與教材（全部）',
    'youtube' => 'YouTube',
);

$get_term_label = static function ( $category ) use ( $format_label_overrides ) {
    return isset( $format_label_overrides[ $category->slug ] ) ? $format_label_overrides[ $category->slug ] : $category->name;
};

$active_filters = array();
foreach ( $selected_formats as $slug ) {
    if ( isset( $categories_by_slug[ $slug ] ) ) {
        $active_filters[] = array( 'group' => 'format', 'slug' => $slug, 'label' => $get_term_label( $categories_by_slug[ $slug ] ) );
    }
}
foreach ( $selected_category_topics as $slug ) {
    if ( isset( $categories_by_slug[ $slug ] ) ) {
        $active_filters[] = array( 'group' => 'category_topic', 'slug' => $slug, 'label' => $categories_by_slug[ $slug ]->name );
    }
}
foreach ( $selected_people_topics as $slug ) {
    if ( isset( $people_topic_registry[ $slug ] ) ) {
        $active_filters[] = array( 'group' => 'topic', 'slug' => $slug, 'label' => $people_topic_registry[ $slug ]['label'] );
    }
}

$current_query_args = array();
if ( $selected_formats ) {
    $current_query_args['format'] = $selected_formats;
}
if ( $selected_category_topics ) {
    $current_query_args['category_topic'] = $selected_category_topics;
}
if ( $selected_people_topics ) {
    $current_query_args['topic'] = $selected_people_topics;
}
if ( $keyword ) {
    $current_query_args['keyword'] = $keyword;
}
if ( 'oldest' === $sort_order ) {
    $current_query_args['order'] = 'oldest';
}

$remove_filter_url = static function ( $group, $slug ) use ( $page_url, $current_query_args ) {
    $args = $current_query_args;
    if ( isset( $args[ $group ] ) ) {
        $args[ $group ] = array_values( array_diff( (array) $args[ $group ], array( $slug ) ) );
        if ( ! $args[ $group ] ) {
            unset( $args[ $group ] );
        }
    }
    return $args ? add_query_arg( $args, $page_url ) : $page_url;
};

$render_facet = static function ( $categories, $selected, $group, $term_count, $get_term_label = null ) {
    foreach ( $categories as $index => $category ) {
        $is_selected = in_array( $category->slug, $selected, true );
        $is_extra    = $index >= 7 && ! $is_selected;
        $label       = $get_term_label ? $get_term_label( $category ) : $category->name;
        $depth       = count( get_ancestors( $category->term_id, 'category', 'taxonomy' ) );
        ?>
        <label class="tnews-facet-option<?php echo $is_selected ? ' is-selected' : ''; ?><?php echo $is_extra ? ' is-extra' : ''; ?>" style="--facet-depth:<?php echo esc_attr( (string) min( $depth, 2 ) ); ?>;">
          <input type="checkbox" name="<?php echo esc_attr( $group ); ?>[]" value="<?php echo esc_attr( $category->slug ); ?>" <?php checked( $is_selected ); ?>>
          <span class="tnews-facet-check" aria-hidden="true"></span>
          <span class="tnews-facet-label"><?php echo esc_html( $label ); ?></span>
          <span class="tnews-facet-count"><?php echo esc_html( (string) $term_count( $category->term_id ) ); ?></span>
        </label>
        <?php
    }
};

$people_topic_counts = array();
foreach ( $people_topic_registry as $slug => $topic ) {
    $people_topic_counts[ $slug ] = (int) ( new WP_Query(
        array(
            'post_type'              => 'post',
            'post_status'            => 'publish',
            'posts_per_page'         => 1,
            'fields'                 => 'ids',
            'no_found_rows'          => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'tax_query'              => array(
                array(
                    'taxonomy' => 'fwd_topic',
                    'field'    => 'slug',
                    'terms'    => array( $slug ),
                ),
            ),
        )
    ) )->found_posts;
}

$render_people_topics = static function ( $registry, $selected, $counts ) {
    foreach ( $registry as $slug => $topic ) {
        $is_selected = in_array( $slug, $selected, true );
        ?>
        <label class="tnews-facet-option<?php echo $is_selected ? ' is-selected' : ''; ?>" style="--facet-depth:0;">
          <input type="checkbox" name="topic[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( $is_selected ); ?>>
          <span class="tnews-facet-check" aria-hidden="true"></span>
          <span class="tnews-facet-label"><?php echo esc_html( $topic['label'] ); ?></span>
          <span class="tnews-facet-count"><?php echo esc_html( (string) ( $counts[ $slug ] ?? 0 ) ); ?></span>
        </label>
        <?php
    }
};
?>

<main class="tnews-page" id="main" data-news-shell>
  <header class="tnews-intro">
    <div class="tnews-intro-inner">
      <p class="tnews-kicker">全站內容索引</p>
      <h1>全部內容</h1>
      <p>收錄尊嚴農業的消息文章、田野紀錄、Podcast、YouTube、影音與教材；可依內容形式與主題交叉查找。</p>
    </div>
  </header>

  <div class="tnews-workspace">
    <aside class="tnews-filter-panel" data-news-filter-panel aria-label="內容篩選">
      <div class="tnews-filter-heading">
        <span class="tnews-filter-heading-icon" aria-hidden="true"><i class="fas fa-sliders"></i></span>
        <div class="tnews-filter-heading-copy">
          <strong>篩選條件</strong>
          <span>可複選與交叉篩選</span>
        </div>
        <span class="tnews-filter-active-count" aria-label="已選條件數"><?php echo esc_html( (string) count( $active_filters ) ); ?></span>
        <button class="tnews-filter-collapse" type="button" data-filter-collapse aria-expanded="true" aria-controls="tnews-filter-form" title="收合篩選欄">
          <i class="fas fa-chevron-left" aria-hidden="true"></i>
          <span class="screen-reader-text">收合篩選欄</span>
        </button>
      </div>

      <form class="tnews-filter-form" id="tnews-filter-form" action="<?php echo esc_url( $page_url ); ?>" method="get" data-auto-submit>
        <fieldset class="tnews-facet-group" data-facet-group>
          <legend>內容形式</legend>
          <div class="tnews-facet-list">
            <?php $render_facet( $format_categories, $selected_formats, 'format', $term_count, $get_term_label ); ?>
          </div>
          <?php if ( count( $format_categories ) > 7 ) : ?>
            <button class="tnews-facet-more" type="button" data-facet-more aria-expanded="false">顯示更多內容形式</button>
          <?php endif; ?>
        </fieldset>

        <fieldset class="tnews-facet-group" data-facet-group>
          <legend>內容主題</legend>
          <div class="tnews-facet-list">
            <?php $render_facet( $topic_categories, $selected_category_topics, 'category_topic', $term_count ); ?>
          </div>
          <?php if ( count( $topic_categories ) > 7 ) : ?>
            <button class="tnews-facet-more" type="button" data-facet-more aria-expanded="false">顯示更多內容主題</button>
          <?php endif; ?>
        </fieldset>

        <fieldset class="tnews-facet-group tnews-facet-group--people" data-facet-group>
          <legend>做農的人</legend>
          <div class="tnews-facet-list">
            <?php $render_people_topics( $people_topic_registry, $selected_people_topics, $people_topic_counts ); ?>
          </div>
        </fieldset>

        <div class="tnews-filter-actions">
          <button class="tnews-apply" type="submit"><i class="fas fa-check" aria-hidden="true"></i>套用篩選</button>
          <a class="tnews-clear" href="<?php echo esc_url( $page_url ); ?>">清除全部</a>
        </div>
      </form>
    </aside>

    <section class="tnews-results" aria-labelledby="tnews-results-title">
      <div class="tnews-toolbar">
        <label class="tnews-search">
          <span class="screen-reader-text">搜尋內容</span>
          <i class="fas fa-magnifying-glass" aria-hidden="true"></i>
          <input type="search" name="keyword" value="<?php echo esc_attr( $keyword ); ?>" placeholder="搜尋標題或內容" form="tnews-filter-form">
        </label>
        <label class="tnews-sort">
          <span>排序</span>
          <select name="order" form="tnews-filter-form">
            <option value="newest" <?php selected( $sort_order, 'newest' ); ?>>最新發布</option>
            <option value="oldest" <?php selected( $sort_order, 'oldest' ); ?>>最早發布</option>
          </select>
        </label>
        <button class="tnews-search-submit" type="submit" form="tnews-filter-form">搜尋</button>
      </div>

      <div class="tnews-result-summary">
        <div>
          <p class="tnews-result-eyebrow">Result</p>
          <h2 id="tnews-results-title">找到 <?php echo esc_html( number_format_i18n( $news_query->found_posts ) ); ?> 篇內容</h2>
        </div>
        <?php if ( $keyword ) : ?>
          <p class="tnews-keyword-note">關鍵字：<strong><?php echo esc_html( $keyword ); ?></strong></p>
        <?php endif; ?>
      </div>

      <?php if ( $active_filters ) : ?>
        <div class="tnews-active-filters" aria-label="已選篩選條件">
          <span>已選</span>
          <?php foreach ( $active_filters as $active_filter ) : ?>
            <a href="<?php echo esc_url( $remove_filter_url( $active_filter['group'], $active_filter['slug'] ) ); ?>">
              <?php echo esc_html( $active_filter['label'] ); ?><i class="fas fa-xmark" aria-hidden="true"></i>
            </a>
          <?php endforeach; ?>
          <a class="tnews-active-clear" href="<?php echo esc_url( $page_url ); ?>">清除全部</a>
        </div>
      <?php endif; ?>

      <?php if ( $news_query->have_posts() ) : ?>
        <div class="tnews-grid">
          <?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
            <?php
            $post_id    = get_the_ID();
            $categories = get_the_category( $post_id );
            $people_topics = get_the_terms( $post_id, 'fwd_topic' );
            if ( ! is_array( $people_topics ) ) {
                $people_topics = array();
            }
            $thumbnail  = get_the_post_thumbnail_url( $post_id, 'medium_large' );
            $excerpt    = get_the_excerpt( $post_id );
            if ( ! $excerpt ) {
                $excerpt = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 30, '…' );
            }
            ?>
            <article <?php post_class( 'tnews-card', $post_id ); ?>>
              <a class="tnews-card-media<?php echo $thumbnail ? '' : ' is-empty'; ?>" href="<?php the_permalink(); ?>">
                <?php if ( $thumbnail ) : ?>
                  <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" loading="lazy">
                <?php else : ?>
                  <i class="fas fa-file-lines" aria-hidden="true"></i>
                <?php endif; ?>
              </a>
              <div class="tnews-card-body">
                <div class="tnews-card-topline">
                  <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d', $post_id ) ); ?></time>
                </div>
                <?php if ( $categories || $people_topics ) : ?>
                  <div class="tnews-card-tags" aria-label="文章分類">
                    <?php foreach ( array_slice( $categories, 0, 3 ) as $category ) : ?>
                      <?php $is_format = in_array( $category->slug, $allowed_format_slugs, true ); ?>
                      <span class="<?php echo $is_format ? 'is-format' : 'is-topic'; ?>"><?php echo esc_html( $get_term_label( $category ) ); ?></span>
                    <?php endforeach; ?>
                    <?php if ( $people_topics ) : ?>
                      <?php $people_topic = reset( $people_topics ); ?>
                      <span class="is-people-topic"><?php echo esc_html( $people_topic->name ); ?></span>
                    <?php endif; ?>
                    <?php $remaining_labels = max( 0, count( $categories ) - 3 ) + max( 0, count( $people_topics ) - 1 ); ?>
                    <?php if ( $remaining_labels > 0 ) : ?>
                      <span class="is-more">+<?php echo esc_html( (string) $remaining_labels ); ?></span>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $excerpt ), 32, '…' ) ); ?></p>
                <a class="tnews-card-link" href="<?php the_permalink(); ?>">閱讀內容<i class="fas fa-arrow-right" aria-hidden="true"></i></a>
              </div>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php
        $pagination = paginate_links(
            array(
                'base'      => str_replace( 999999999, '%#%', esc_url_raw( add_query_arg( 'paged', 999999999, $page_url ) ) ),
                'format'    => '',
                'current'   => $paged,
                'total'     => $news_query->max_num_pages,
                'prev_text' => '<i class="fas fa-chevron-left" aria-hidden="true"></i><span>上一頁</span>',
                'next_text' => '<span>下一頁</span><i class="fas fa-chevron-right" aria-hidden="true"></i>',
                'type'      => 'array',
                'add_args'  => $current_query_args,
            )
        );
        if ( $pagination ) :
            ?>
            <nav class="tnews-pagination" aria-label="內容分頁">
              <?php foreach ( $pagination as $page_link ) : ?>
                <?php echo wp_kses_post( str_replace( 'page-numbers', 'tnews-page-link', $page_link ) ); ?>
              <?php endforeach; ?>
            </nav>
          <?php endif; ?>
      <?php else : ?>
        <div class="tnews-empty">
          <i class="fas fa-magnifying-glass" aria-hidden="true"></i>
          <h2>找不到符合條件的內容</h2>
          <p>可以移除部分篩選條件，或改用其他關鍵字搜尋。</p>
          <a href="<?php echo esc_url( $page_url ); ?>">查看全部內容</a>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php get_footer();
