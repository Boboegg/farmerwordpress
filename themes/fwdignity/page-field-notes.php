<?php
/**
 * Template Name: 田野紀錄
 * Slug: field-notes
 * 田野紀錄：可篩選的圖誌與時間軸。
 */

get_header();

$page_url = get_permalink();
$paged    = max( 1, (int) ( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ) );

$read_array = static function ( $key ) {
    if ( ! isset( $_GET[ $key ] ) ) {
        return array();
    }
    return array_values(
        array_unique(
            array_filter( array_map( 'sanitize_title', (array) wp_unslash( $_GET[ $key ] ) ) )
        )
    );
};

$keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';
$order   = isset( $_GET['order'] ) && 'oldest' === sanitize_key( wp_unslash( $_GET['order'] ) ) ? 'oldest' : 'newest';
$year    = isset( $_GET['year'] ) ? absint( $_GET['year'] ) : 0;

$all_field_ids = get_posts(
    array(
        'post_type'              => 'post',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'fields'                 => 'ids',
        'orderby'                => 'date',
        'order'                  => 'DESC',
        'no_found_rows'          => true,
        'update_post_meta_cache' => true,
        'tax_query'              => array( fwdignity_field_tax_clause() ),
    )
);

// 分面數量只計算田野誌集合，避免顯示全站有內容、田野誌卻為 0 的分類。
$field_term_posts = array();
foreach ( $all_field_ids as $post_id ) {
    foreach ( wp_get_post_categories( $post_id ) as $term_id ) {
        $related_ids = array_merge( array( (int) $term_id ), array_map( 'intval', get_ancestors( $term_id, 'category', 'taxonomy' ) ) );
        foreach ( array_unique( $related_ids ) as $related_id ) {
            $field_term_posts[ $related_id ][ $post_id ] = true;
        }
    }
}
$field_term_count = static function ( $term_id ) use ( $field_term_posts ) {
    return isset( $field_term_posts[ $term_id ] ) ? count( $field_term_posts[ $term_id ] ) : 0;
};

$topic_terms = array_values(
    array_filter(
        array_map( 'get_category_by_slug', fwdignity_field_topic_slugs() ),
        static function ( $term ) use ( $field_term_count ) {
            return $term instanceof WP_Term && $field_term_count( $term->term_id ) > 0;
        }
    )
);
$format_terms = array_values(
    array_filter(
        array_map( 'get_category_by_slug', array( 'podcast', 'youtube', 'video', 'infographic', 'downloads' ) ),
        static function ( $term ) use ( $field_term_count ) {
            return $term instanceof WP_Term && $field_term_count( $term->term_id ) > 0;
        }
    )
);

$allowed_topics   = wp_list_pluck( $topic_terms, 'slug' );
$allowed_formats  = wp_list_pluck( $format_terms, 'slug' );
$selected_topics  = array_values( array_intersect( $read_array( 'topic' ), $allowed_topics ) );
$selected_formats = array_values( array_intersect( $read_array( 'format' ), $allowed_formats ) );

$available_years = array_values(
    array_unique(
        array_map(
            static function ( $post_id ) {
                return (int) fwdignity_get_field_date( $post_id, 'Y' );
            },
            $all_field_ids
        )
    )
);
rsort( $available_years );
if ( $year && ! in_array( $year, $available_years, true ) ) {
    $year = 0;
}

$tax_query = array( 'relation' => 'AND', fwdignity_field_tax_clause() );
if ( $selected_topics ) {
    $tax_query[] = fwdignity_field_tax_clause( $selected_topics );
}
if ( $selected_formats ) {
    $tax_query[] = array(
        'taxonomy'         => 'category',
        'field'            => 'slug',
        'terms'            => $selected_formats,
        'operator'         => 'IN',
        'include_children' => true,
    );
}

$query_args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'orderby'             => 'date',
    'order'               => 'oldest' === $order ? 'ASC' : 'DESC',
    'ignore_sticky_posts' => true,
    'tax_query'           => $tax_query,
);
if ( $keyword ) {
    $query_args['s'] = $keyword;
}
if ( $year ) {
    $query_args['date_query'] = array( array( 'year' => $year ) );
}

$field_query = new WP_Query( $query_args );
$result_ids  = wp_list_pluck( $field_query->posts, 'ID' );
$trajectory_ids = array_slice( $all_field_ids, 0, 5 );

$active_filters = array();
foreach ( array_merge( $topic_terms, $format_terms ) as $term ) {
    if ( in_array( $term->slug, $selected_topics, true ) ) {
        $active_filters[] = array( 'group' => 'topic', 'slug' => $term->slug, 'label' => $term->name );
    }
    if ( in_array( $term->slug, $selected_formats, true ) ) {
        $active_filters[] = array( 'group' => 'format', 'slug' => $term->slug, 'label' => $term->name );
    }
}
$active_filter_count = count( $active_filters ) + ( $year ? 1 : 0 ) + ( $keyword ? 1 : 0 );

$current_args = array();
if ( $selected_topics ) { $current_args['topic'] = $selected_topics; }
if ( $selected_formats ) { $current_args['format'] = $selected_formats; }
if ( $keyword ) { $current_args['keyword'] = $keyword; }
if ( $year ) { $current_args['year'] = $year; }
if ( 'oldest' === $order ) { $current_args['order'] = 'oldest'; }

$remove_filter_url = static function ( $group, $slug ) use ( $current_args, $page_url ) {
    $args = $current_args;
    $args[ $group ] = array_values( array_diff( isset( $args[ $group ] ) ? (array) $args[ $group ] : array(), array( $slug ) ) );
    if ( ! $args[ $group ] ) {
        unset( $args[ $group ] );
    }
    return $args ? add_query_arg( $args, $page_url ) : $page_url;
};

$render_filter_group = static function ( $legend, $name, $terms, $selected ) use ( $field_term_count ) {
    if ( ! $terms ) {
        return;
    }
    ?>
    <fieldset class="field-notes-filter-group">
      <legend><?php echo esc_html( $legend ); ?></legend>
      <?php foreach ( $terms as $term ) : ?>
        <label class="field-notes-filter-option<?php echo in_array( $term->slug, $selected, true ) ? ' is-selected' : ''; ?>">
          <input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, $selected, true ) ); ?>>
          <span class="field-notes-checkbox" aria-hidden="true"></span>
          <span><?php echo esc_html( $term->name ); ?></span>
          <small><?php echo esc_html( (string) $field_term_count( $term->term_id ) ); ?></small>
        </label>
      <?php endforeach; ?>
    </fieldset>
    <?php
};

$render_note = static function ( $post_id, $timeline = false ) {
    $location = fwdignity_get_field_location( $post_id );
    $context  = fwdignity_get_field_context( $post_id );
    $terms    = get_the_category( $post_id );
    $has_image = has_post_thumbnail( $post_id );
    $classes   = 'field-note field-note-card';
    $classes  .= $timeline ? ' field-note--timeline' : '';
    $classes  .= $has_image ? '' : ' field-note-card--text-only';
    ?>
    <article class="<?php echo esc_attr( $classes ); ?>">
      <?php if ( $timeline ) : ?>
        <time class="field-note-timeline-date" datetime="<?php echo esc_attr( fwdignity_get_field_date_raw( $post_id ) ); ?>"><?php echo esc_html( fwdignity_get_field_date( $post_id, 'm.d' ) ); ?></time>
      <?php endif; ?>
      <?php if ( $has_image ) : ?>
        <a class="field-note-media field-note-card__media" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
          <?php echo get_the_post_thumbnail( $post_id, 'medium_large', array( 'loading' => $timeline ? 'lazy' : 'eager' ) ); ?>
        </a>
      <?php endif; ?>
      <div class="field-note-copy field-note-card__body">
        <?php if ( ! $timeline ) : ?>
          <time datetime="<?php echo esc_attr( fwdignity_get_field_date_raw( $post_id ) ); ?>"><?php echo esc_html( fwdignity_get_field_date( $post_id ) ); ?></time>
        <?php endif; ?>
        <?php if ( $location || $context ) : ?><p class="field-note-place"><?php echo esc_html( $location ? $location : $context ); ?></p><?php endif; ?>
        <h2><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h2>
        <p><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), $timeline ? 34 : 24, '…' ) ); ?></p>
        <?php if ( $terms ) : ?>
          <div class="field-note-terms">
            <?php foreach ( array_slice( $terms, 0, 3 ) as $term ) : ?><span><?php echo esc_html( $term->name ); ?></span><?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </article>
    <?php
};
?>

<main class="field-notes-page" id="main">
  <header class="field-notes-intro">
    <div>
      <p class="field-notes-eyebrow">Field Records</p>
      <h1><?php the_title(); ?></h1>
      <p>以照片、文字與影音保存每一次走入農村現場的觀察，記錄場域、人物、工作情境與可確認的田野發現。</p>
    </div>
  </header>

  <?php if ( $trajectory_ids ) : ?>
    <section class="field-trajectory" aria-labelledby="field-trajectory-title">
      <div class="field-trajectory-head">
        <div><p class="field-notes-eyebrow">Field Trail</p><h2 id="field-trajectory-title">田野軌跡</h2></div>
        <p>依田野日期排列，記錄從現場累積而來的理解。</p>
      </div>
      <ol class="field-trajectory-list">
        <?php foreach ( $trajectory_ids as $index => $post_id ) : ?>
          <li>
            <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
              <span class="field-trajectory-index"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
              <time datetime="<?php echo esc_attr( fwdignity_get_field_date_raw( $post_id ) ); ?>"><b><?php echo esc_html( fwdignity_get_field_date( $post_id, 'm.d' ) ); ?></b><small><?php echo esc_html( fwdignity_get_field_date( $post_id, 'Y' ) ); ?></small></time>
              <strong><?php echo esc_html( get_the_title( $post_id ) ); ?></strong>
            </a>
          </li>
        <?php endforeach; ?>
      </ol>
    </section>
  <?php endif; ?>

  <?php if ( $topic_terms ) : ?>
    <nav class="field-contexts" aria-labelledby="field-contexts-title">
      <div class="field-contexts-head">
        <p class="field-notes-eyebrow">Field Context</p>
        <h2 id="field-contexts-title">田野脈絡</h2>
      </div>
      <div class="field-context-list">
        <?php foreach ( $topic_terms as $term ) : ?>
          <a href="<?php echo esc_url( add_query_arg( 'topic[]', $term->slug, $page_url ) ); ?>">
            <strong><?php echo esc_html( $term->name ); ?></strong>
            <span><?php echo esc_html( (string) $field_term_count( $term->term_id ) ); ?> 篇</span>
          </a>
        <?php endforeach; ?>
      </div>
    </nav>
  <?php endif; ?>

  <section class="field-notes-results" aria-labelledby="field-notes-result-title">
    <div class="field-notes-result-head">
      <div><p class="field-notes-eyebrow">Visual Archive</p><h2 id="field-notes-result-title">田野圖誌</h2></div>
      <strong><?php echo esc_html( number_format_i18n( $field_query->found_posts ) ); ?> 篇紀錄</strong>
    </div>

    <div class="field-notes-controls">
      <button type="button" class="field-notes-filter-toggle" aria-controls="field-notes-filter-drawer" aria-expanded="false">
        <i class="fas fa-sliders" aria-hidden="true"></i> 篩選<?php if ( $active_filter_count ) : ?><b><?php echo esc_html( (string) $active_filter_count ); ?></b><?php endif; ?>
      </button>
      <form class="field-notes-toolbar" method="get" action="<?php echo esc_url( $page_url ); ?>">
        <?php foreach ( $selected_topics as $slug ) : ?><input type="hidden" name="topic[]" value="<?php echo esc_attr( $slug ); ?>"><?php endforeach; ?>
        <?php foreach ( $selected_formats as $slug ) : ?><input type="hidden" name="format[]" value="<?php echo esc_attr( $slug ); ?>"><?php endforeach; ?>
        <label class="field-notes-search"><span class="screen-reader-text">搜尋田野紀錄</span><input type="search" name="keyword" value="<?php echo esc_attr( $keyword ); ?>" placeholder="搜尋田野紀錄"><button type="submit">搜尋</button></label>
        <label><span>年份</span><select name="year" onchange="this.form.submit()"><option value="">全部</option><?php foreach ( $available_years as $available_year ) : ?><option value="<?php echo esc_attr( (string) $available_year ); ?>" <?php selected( $year, $available_year ); ?>><?php echo esc_html( (string) $available_year ); ?></option><?php endforeach; ?></select></label>
        <label><span>排序</span><select name="order" onchange="this.form.submit()"><option value="newest" <?php selected( $order, 'newest' ); ?>>最新優先</option><option value="oldest" <?php selected( $order, 'oldest' ); ?>>最早優先</option></select></label>
      </form>
      <div class="field-notes-view" role="group" aria-label="顯示方式">
        <button type="button" data-field-view="grid" class="is-active" aria-pressed="true">圖誌</button>
        <button type="button" data-field-view="timeline" aria-pressed="false">時間軸</button>
      </div>
    </div>

    <div class="field-notes-filter-drawer" id="field-notes-filter-drawer">
      <div class="field-notes-filter-drawer-head"><div><strong>篩選田野紀錄</strong><span>同一組內可複選，兩組條件交叉篩選。</span></div><button type="button" class="field-notes-filter-close" aria-label="關閉篩選">×</button></div>
      <form id="field-notes-filter-form" method="get" action="<?php echo esc_url( $page_url ); ?>">
        <div class="field-notes-filter-groups">
          <?php $render_filter_group( '田野脈絡', 'topic', $topic_terms, $selected_topics ); ?>
          <?php $render_filter_group( '內容形式', 'format', $format_terms, $selected_formats ); ?>
        </div>
        <?php if ( $keyword ) : ?><input type="hidden" name="keyword" value="<?php echo esc_attr( $keyword ); ?>"><?php endif; ?>
        <?php if ( $year ) : ?><input type="hidden" name="year" value="<?php echo esc_attr( (string) $year ); ?>"><?php endif; ?>
        <?php if ( 'oldest' === $order ) : ?><input type="hidden" name="order" value="oldest"><?php endif; ?>
        <div class="field-notes-filter-actions"><button type="submit">套用條件</button><a href="<?php echo esc_url( $page_url ); ?>">清除全部</a></div>
      </form>
    </div>

    <?php if ( $active_filters || $year || $keyword ) : ?>
      <div class="field-notes-active" aria-label="已套用條件">
        <?php foreach ( $active_filters as $filter ) : ?><a href="<?php echo esc_url( $remove_filter_url( $filter['group'], $filter['slug'] ) ); ?>"><?php echo esc_html( $filter['label'] ); ?> <span aria-hidden="true">×</span></a><?php endforeach; ?>
        <?php if ( $year ) : ?><a href="<?php echo esc_url( remove_query_arg( 'year' ) ); ?>"><?php echo esc_html( (string) $year ); ?> 年 <span aria-hidden="true">×</span></a><?php endif; ?>
        <?php if ( $keyword ) : ?><a href="<?php echo esc_url( remove_query_arg( 'keyword' ) ); ?>">搜尋：<?php echo esc_html( $keyword ); ?> <span aria-hidden="true">×</span></a><?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ( $result_ids ) : ?>
      <div class="field-notes-grid" data-field-panel="grid">
        <?php foreach ( $result_ids as $post_id ) : $render_note( $post_id ); endforeach; ?>
      </div>
      <div class="field-notes-timeline" data-field-panel="timeline" hidden>
        <?php
        $timeline_year = null;
        foreach ( $result_ids as $post_id ) :
            $post_year = fwdignity_get_field_date( $post_id, 'Y' );
            if ( $post_year !== $timeline_year ) :
                if ( null !== $timeline_year ) { echo '</div></section>'; }
                $timeline_year = $post_year;
                echo '<section class="field-timeline-year"><h3>' . esc_html( $timeline_year ) . '</h3><div>';
            endif;
            $render_note( $post_id, true );
        endforeach;
        if ( null !== $timeline_year ) { echo '</div></section>'; }
        ?>
      </div>
      <nav class="field-notes-pagination" aria-label="田野紀錄分頁">
        <?php echo wp_kses_post( paginate_links( array( 'total' => $field_query->max_num_pages, 'current' => $paged, 'add_args' => $current_args, 'prev_text' => '←', 'next_text' => '→' ) ) ); ?>
      </nav>
    <?php else : ?>
      <div class="field-notes-empty"><h2>找不到符合條件的紀錄</h2><p>請減少篩選條件，或清除搜尋字詞後再查看。</p><a href="<?php echo esc_url( $page_url ); ?>">清除全部條件</a></div>
    <?php endif; ?>
  </section>
</main>

<?php
wp_reset_postdata();
get_footer();
?>
