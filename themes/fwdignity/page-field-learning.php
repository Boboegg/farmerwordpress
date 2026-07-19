<?php
/**
 * Template Name: 田野誌
 * Slug: field-learning
 * 田野誌入口頁。
 */

get_header();

$featured       = fwdignity_get_featured_field_post();
$featured_id    = $featured ? (int) $featured->ID : 0;
$recent_records = fwdignity_field_query(
    array(
        'posts_per_page' => 3,
        'post__not_in'   => $featured_id ? array( $featured_id ) : array(),
    )
);
$training_posts = fwdignity_field_query(
    array(
        'posts_per_page' => 3,
        'tax_query'      => array( fwdignity_field_tax_clause( array( 'agri-school', 'education-farmer' ) ) ),
    )
);
$topic_terms = array_values(
    array_filter(
        array_map( 'get_category_by_slug', fwdignity_field_topic_slugs() ),
        static function ( $term ) {
            return $term instanceof WP_Term && $term->count > 0;
        }
    )
);

$render_record_card = static function ( $post_id, $class = '' ) {
    $location = fwdignity_get_field_location( $post_id );
    $context  = fwdignity_get_field_context( $post_id );
    $terms    = get_the_category( $post_id );
    $has_image = has_post_thumbnail( $post_id );
    $classes   = trim( 'field-record-card ' . ( $has_image ? '' : 'field-record-card--text-only ' ) . $class );
    ?>
    <article class="<?php echo esc_attr( $classes ); ?>">
      <?php if ( $has_image ) : ?>
        <a class="field-record-media" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-label="閱讀<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
          <?php echo get_the_post_thumbnail( $post_id, 'medium_large', array( 'loading' => 'lazy' ) ); ?>
        </a>
      <?php endif; ?>
      <div class="field-record-copy field-record-body">
        <div class="field-record-meta">
          <time datetime="<?php echo esc_attr( fwdignity_get_field_date_raw( $post_id ) ); ?>"><?php echo esc_html( fwdignity_get_field_date( $post_id ) ); ?></time>
          <?php if ( $location ) : ?><span><?php echo esc_html( $location ); ?></span><?php endif; ?>
          <?php if ( ! $location && $context ) : ?><span><?php echo esc_html( $context ); ?></span><?php endif; ?>
        </div>
        <h3><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
        <p><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 32, '…' ) ); ?></p>
        <?php if ( $terms ) : ?>
          <div class="field-record-terms" aria-label="文章分類">
            <?php foreach ( array_slice( $terms, 0, 3 ) as $term ) : ?><span><?php echo esc_html( $term->name ); ?></span><?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </article>
    <?php
};
?>

<main class="field-home" id="main">
  <section class="field-home-intro">
    <div class="field-home-intro-inner">
      <p class="field-eyebrow">Field Journal</p>
      <div class="field-home-intro-grid">
        <h1><?php the_title(); ?></h1>
        <p>把田野觀察、活動事後整理、人物與農業工作經驗轉成可閱讀的公共紀錄，集中呈現田野紀錄、活動成果與做農的人。</p>
      </div>
    </div>
  </section>

  <?php if ( $featured ) : ?>
    <section class="field-featured<?php echo has_post_thumbnail( $featured_id ) ? '' : ' field-featured--text-only'; ?>" aria-labelledby="field-featured-title">
      <?php if ( has_post_thumbnail( $featured_id ) ) : ?>
        <a class="field-featured-media" href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>">
          <?php echo get_the_post_thumbnail( $featured_id, 'large' ); ?>
        </a>
      <?php endif; ?>
      <div class="field-featured-copy">
        <p class="field-eyebrow">本期田野</p>
        <div class="field-featured-meta">
          <time datetime="<?php echo esc_attr( fwdignity_get_field_date_raw( $featured_id ) ); ?>"><?php echo esc_html( fwdignity_get_field_date( $featured_id ) ); ?></time>
          <?php if ( fwdignity_get_field_location( $featured_id ) ) : ?><span><?php echo esc_html( fwdignity_get_field_location( $featured_id ) ); ?></span><?php endif; ?>
        </div>
        <h2 id="field-featured-title"><a href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>"><?php echo esc_html( get_the_title( $featured_id ) ); ?></a></h2>
        <p><?php echo esc_html( wp_trim_words( get_the_excerpt( $featured_id ), 52, '…' ) ); ?></p>
        <a class="field-text-link" href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>">閱讀完整紀錄 <span aria-hidden="true">→</span></a>
      </div>
    </section>
  <?php endif; ?>

  <section class="field-section" aria-labelledby="field-recent-title">
    <div class="field-section-head">
      <div>
        <p class="field-eyebrow">Recent Notes</p>
        <h2 id="field-recent-title">近期田野紀錄</h2>
      </div>
      <a class="field-section-link" href="<?php echo esc_url( home_url( '/field-notes/' ) ); ?>">查看全部紀錄 <span aria-hidden="true">→</span></a>
    </div>
    <?php if ( $recent_records->have_posts() ) : ?>
      <div class="field-record-grid">
        <?php while ( $recent_records->have_posts() ) : $recent_records->the_post(); ?>
          <?php $render_record_card( get_the_ID() ); ?>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p class="field-empty">目前沒有其他田野紀錄。</p>
    <?php endif; ?>
  </section>

  <?php if ( $topic_terms ) : ?>
    <section class="field-index-band" aria-labelledby="field-index-title">
      <div class="field-section-head">
        <div>
          <p class="field-eyebrow">Field Index</p>
          <h2 id="field-index-title">依田野脈絡閱讀</h2>
        </div>
        <p>分類會隨文章增加，自動擴充。</p>
      </div>
      <div class="field-topic-list">
        <?php foreach ( $topic_terms as $index => $term ) : ?>
          <a href="<?php echo esc_url( add_query_arg( 'topic[]', $term->slug, home_url( '/field-notes/' ) ) ); ?>">
            <span class="field-topic-number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
            <strong><?php echo esc_html( $term->name ); ?></strong>
            <span><?php echo esc_html( (string) $term->count ); ?> 篇</span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="field-section field-training" aria-labelledby="field-training-title">
    <div class="field-section-head">
      <div>
        <p class="field-eyebrow">Learning in Practice</p>
        <h2 id="field-training-title">農學堂</h2>
      </div>
      <a class="field-section-link" href="<?php echo esc_url( home_url( '/farmer-study/' ) ); ?>">進入農學堂 <span aria-hidden="true">→</span></a>
    </div>
    <p class="field-section-lead">目前以活動完成後的整理與知識沉澱為主，不刊登未公開的邀約資訊。</p>
    <?php if ( $training_posts->have_posts() ) : ?>
      <div class="field-training-list">
        <?php while ( $training_posts->have_posts() ) : $training_posts->the_post(); ?>
          <article>
            <time datetime="<?php echo esc_attr( fwdignity_get_field_date_raw( get_the_ID() ) ); ?>"><?php echo esc_html( fwdignity_get_field_date( get_the_ID(), 'Y.m.d' ) ); ?></time>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <a href="<?php the_permalink(); ?>" aria-label="閱讀<?php the_title_attribute(); ?>">→</a>
          </article>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p class="field-empty">目前尚無可顯示的農學堂成果。</p>
    <?php endif; ?>
  </section>

  <nav class="field-extension" aria-label="田野誌延伸閱讀">
    <a href="<?php echo esc_url( home_url( '/farming-guide/#fg-task-learning-support' ) ); ?>"><span>田間知識與資源</span><strong>學習、健康與工作支持</strong></a>
    <a href="<?php echo esc_url( home_url( '/research-results/research-publications/' ) ); ?>"><span>研究倡議</span><strong>研究成果與公共討論</strong></a>
  </nav>
</main>

<?php get_footer(); ?>
