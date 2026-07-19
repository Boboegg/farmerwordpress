<?php
/**
 * 節目系列彙整頁。
 */

get_header();

$term     = get_queried_object();
$term_url = get_term_link( $term );
$paged    = max( 1, (int) ( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ) );
$keyword  = isset( $_GET['series_keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['series_keyword'] ) ) : '';
$result   = fwdignity_query_media_items(
    array(
        'posts_per_page' => 20,
        'paged'          => $paged,
        'series'         => array( $term->slug ),
        'keyword'        => $keyword,
    )
);
$latest = $result['items'] ? $result['items'][0] : null;

$render_visual = static function ( $item, $class_name, $loading = 'lazy' ) {
    ?>
    <div class="<?php echo esc_attr( $class_name ); ?><?php echo $item['thumb'] ? '' : ' no-image'; ?>">
        <?php if ( $item['thumb'] ) : ?>
            <img src="<?php echo esc_url( $item['thumb'] ); ?>" alt="" loading="<?php echo esc_attr( $loading ); ?>">
        <?php else : ?>
            <span class="mh-visual-fallback" aria-hidden="true"><i class="fa-solid fa-wave-square"></i></span>
        <?php endif; ?>
    </div>
    <?php
};

$items_by_year = array();
foreach ( $result['items'] as $item ) {
    $items_by_year[ $item['year'] ][] = $item;
}
?>

<main class="mh-series-archive mh-main" id="main">
    <header class="mh-series-hero">
        <div class="mh-wrap">
            <a class="mh-back-link" href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> 返回聽見田裡的聲音</a>
            <p class="mh-eyebrow">Program Series</p>
            <h1><?php echo esc_html( $term->name ); ?></h1>
            <?php if ( $term->description ) : ?><p class="mh-series-description"><?php echo esc_html( $term->description ); ?></p><?php endif; ?>
            <div class="mh-series-summary"><strong><?php echo esc_html( (string) $result['total'] ); ?></strong><span>集節目</span><?php if ( $latest ) : ?><span>最近更新 <?php echo esc_html( $latest['date'] ); ?></span><?php endif; ?></div>
        </div>
    </header>

    <?php if ( $latest ) : ?>
    <section class="mh-section mh-series-latest" aria-labelledby="mh-series-latest-title">
        <div class="mh-wrap">
            <div class="mh-section-head"><p class="mh-eyebrow">Latest Episode</p><h2 id="mh-series-latest-title">最新一集</h2></div>
            <article class="mh-series-latest-layout">
                <div class="mh-series-latest-player" data-media-player>
                    <?php $render_visual( $latest, 'mh-series-latest-visual', 'eager' ); ?>
                    <?php if ( $latest['embed']['url'] ) : ?>
                    <button class="mh-play-button" type="button" data-media-play data-embed-type="<?php echo esc_attr( $latest['embed']['type'] ); ?>" data-embed-url="<?php echo esc_url( $latest['embed']['url'] ); ?>" aria-label="播放：<?php echo esc_attr( $latest['title'] ); ?>"><i class="fa-solid fa-play" aria-hidden="true"></i><span>播放本集</span></button>
                    <?php else : ?>
                    <a class="mh-play-button" href="<?php echo esc_url( $latest['link'] ); ?>"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i><span>閱讀完整內容</span></a>
                    <?php endif; ?>
                </div>
                <div class="mh-series-latest-copy">
                    <p class="mh-meta"><?php echo esc_html( $latest['format_label'] ); ?>｜<?php echo esc_html( $latest['date'] ); ?><?php echo $latest['episode'] ? '｜' . esc_html( $latest['episode'] ) : ''; ?></p>
                    <h3><a href="<?php echo esc_url( $latest['link'] ); ?>"><?php echo esc_html( $latest['title'] ); ?></a></h3>
                    <p><?php echo esc_html( $latest['excerpt'] ); ?></p>
                    <?php if ( $latest['key_points'] ) : ?><ul class="mh-key-points"><?php foreach ( $latest['key_points'] as $point ) : ?><li><?php echo esc_html( $point ); ?></li><?php endforeach; ?></ul><?php endif; ?>
                    <a class="mh-text-link" href="<?php echo esc_url( $latest['link'] ); ?>">查看完整內容 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </article>
        </div>
    </section>
    <?php endif; ?>

    <section class="mh-section mh-series-episodes" aria-labelledby="mh-series-episodes-title">
        <div class="mh-wrap">
            <div class="mh-section-head mh-section-head-split">
                <div><p class="mh-eyebrow">Episodes</p><h2 id="mh-series-episodes-title">全部集數</h2></div>
                <?php if ( (int) $term->count > 20 ) : ?>
                <form class="mh-series-search" method="get" action="<?php echo esc_url( $term_url ); ?>"><label><span>搜尋本系列</span><input type="search" name="series_keyword" value="<?php echo esc_attr( $keyword ); ?>"><button type="submit" aria-label="搜尋本系列"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button></label></form>
                <?php endif; ?>
            </div>

            <?php if ( $items_by_year ) : ?>
                <?php foreach ( $items_by_year as $year => $items ) : ?>
                <div class="mh-series-year">
                    <h3><?php echo esc_html( $year ); ?></h3>
                    <div class="mh-series-episode-list">
                        <?php foreach ( $items as $index => $item ) : ?>
                        <article class="mh-series-episode">
                            <span class="mh-series-episode-number"><?php echo esc_html( $item['episode'] ? $item['episode'] : sprintf( '%02d', $index + 1 ) ); ?></span>
                            <div><p class="mh-meta"><?php echo esc_html( $item['date'] ); ?><?php echo $item['duration'] ? '｜' . esc_html( $item['duration'] ) : ''; ?></p><h4><a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h4><p><?php echo esc_html( $item['excerpt'] ); ?></p></div>
                            <a class="mh-row-action" href="<?php echo esc_url( $item['link'] ); ?>">收聽本集 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="mh-empty"><h3>這個系列目前沒有已發布集數</h3><p>可返回聽見田裡的聲音查看其他 Podcast 與影片。</p></div>
            <?php endif; ?>

            <?php if ( $result['pages'] > 1 ) : ?>
            <nav class="mh-pagination" aria-label="系列集數分頁"><?php echo wp_kses_post( paginate_links( array( 'base' => trailingslashit( $term_url ) . 'page/%#%/', 'format' => '', 'current' => $paged, 'total' => $result['pages'], 'add_args' => $keyword ? array( 'series_keyword' => $keyword ) : array(), 'prev_text' => '上一頁', 'next_text' => '下一頁' ) ) ); ?></nav>
            <?php endif; ?>
        </div>
    </section>

    <section class="mh-section mh-series-return">
        <div class="mh-wrap"><a class="mh-action-button" href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> 返回聽見田裡的聲音</a></div>
    </section>
</main>

<?php get_footer(); ?>
