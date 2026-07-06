<?php
/**
 * fwdignity theme — page-thenews.php
 * 最新消息 archive-style template
 *   WP 會自動套用到 slug = 'thenews' 的 Page
 *
 * 功能:
 *   - Hero 小版(~280px)
 *   - Filter chips(WP category 動態抓,sticky)
 *   - Magazine grid: 第 1 筆大 feature 卡 + 其餘 3 欄 grid
 *   - Pagination
 *
 * 🔴 文案紅線: 全部從 WP posts 真實資料拉,禁止假卡。
 */

get_header();

// ---- 讀 URL: 當前分類 & 頁碼 ----
$current_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'all';
$paged       = max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' ) );

// ---- 抓所有 WP category（拿來組 filter chips）----
$all_cats = get_categories( array(
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 10,
) );

// ---- 組 query ----
$per_page = 13; // 1 feature + 12 grid
$q_args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
);
if ( $current_tab !== 'all' ) {
    $q_args['category_name'] = $current_tab;
}
$news_q = new WP_Query( $q_args );
$posts  = $news_q->posts;
?>

<!-- ================== Hero ================== -->
<section class="tnews-hero">
  <div class="tnews-hero-inner">
    <span class="tnews-hero-badge"><i class="fas fa-newspaper"></i> NEWS &amp; UPDATES</span>
    <h1 data-hook-title class="tnews-hero-title">最新<span class="accent">消息</span></h1>
    <p class="tnews-hero-subtitle">計畫動態、活動快訊、媒體報導、Podcast——一站掌握尊嚴農業的所有更新。</p>
  </div>
</section>

<!-- ================== Filter chips(sticky) ================== -->
<div class="tnews-filter-bar" id="tnews-filter">
  <div class="tnews-filter-inner">
    <a href="<?php echo esc_url( get_permalink() ); ?>"
       class="tnews-chip <?php echo $current_tab === 'all' ? 'is-active' : ''; ?>"
       data-tab="all">
      <i class="fas fa-layer-group"></i> 全部
      <span class="tnews-chip-count"><?php echo intval( wp_count_posts()->publish ); ?></span>
    </a>
    <?php foreach ( $all_cats as $cat ) : ?>
    <a href="<?php echo esc_url( add_query_arg( 'tab', $cat->slug, get_permalink() ) ); ?>"
       class="tnews-chip <?php echo $current_tab === $cat->slug ? 'is-active' : ''; ?>"
       data-tab="<?php echo esc_attr( $cat->slug ); ?>">
      <?php echo esc_html( $cat->name ); ?>
      <span class="tnews-chip-count"><?php echo intval( $cat->count ); ?></span>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- ================== Main + Aside ================== -->
<div class="tnews-layout">
<main class="tnews-main" id="main" role="main">
  <?php if ( ! empty( $posts ) ) : ?>
  <div class="tnews-grid">

    <?php foreach ( $posts as $p ) :
      setup_postdata( $p );
      $pcats = get_the_category( $p->ID );
      $pcat  = ( $pcats && isset( $pcats[0] ) ) ? $pcats[0] : null;
      $thumb = get_the_post_thumbnail_url( $p->ID, 'medium_large' );
    ?>
    <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>" class="tnews-card">
      <div class="tnews-card-thumb <?php echo $thumb ? '' : 'nothumb'; ?>"
           <?php if ( $thumb ) : ?>style="background-image:url(<?php echo esc_url( $thumb ); ?>);"<?php endif; ?>>
        <?php if ( ! $thumb ) : ?>
        <i class="fas fa-file-lines"></i>
        <?php endif; ?>
      </div>
      <div class="tnews-card-inner">
        <div class="tnews-card-meta">
          <?php if ( $pcat ) : ?>
            <span class="tnews-card-cat" style="--cc:<?php echo esc_attr( fwdignity_tnews_cat_color( $pcat->slug ) ); ?>;"><?php echo esc_html( $pcat->name ); ?></span>
          <?php endif; ?>
          <span class="tnews-card-date"><?php echo esc_html( get_the_date( 'Y.m.d', $p->ID ) ); ?></span>
        </div>
        <h3 class="tnews-card-title"><?php echo esc_html( get_the_title( $p->ID ) ); ?></h3>
        <p class="tnews-card-excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt( $p->ID ) ), 24, '…' ) ); ?></p>
      </div>
    </a>
    <?php endforeach; wp_reset_postdata(); ?>

  </div>

  <!-- Pagination -->
  <?php
  $pagination = paginate_links( array(
      'base'      => add_query_arg( 'paged', '%#%', get_permalink() ) . ( $current_tab !== 'all' ? '' : '' ),
      'format'    => '',
      'current'   => $paged,
      'total'     => $news_q->max_num_pages,
      'prev_text' => '<i class="fas fa-chevron-left"></i> 上一頁',
      'next_text' => '下一頁 <i class="fas fa-chevron-right"></i>',
      'type'      => 'array',
      'add_args'  => $current_tab !== 'all' ? array( 'tab' => $current_tab ) : false,
  ) );
  if ( $pagination ) : ?>
  <nav class="tnews-pagination" aria-label="分頁">
    <?php foreach ( $pagination as $link ) : ?>
      <?php echo str_replace( array( 'page-numbers', "class='" ), array( 'tnews-page', "class='tnews-page " ), $link ); ?>
    <?php endforeach; ?>
  </nav>
  <?php endif; ?>

  <?php else : ?>
  <div class="tnews-empty">
    <i class="fas fa-inbox"></i>
    <h2>此分類下尚無文章</h2>
    <p>回<a href="<?php echo esc_url( get_permalink() ); ?>">所有消息</a>看看。</p>
  </div>
  <?php endif; ?>
</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
