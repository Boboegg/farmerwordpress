<?php
/**
 * Template Name: 聽見田裡的聲音
 * Slug: podcast
 */

get_header();

$page_url = get_permalink();
$paged    = max( 1, (int) ( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ) );

$read_array_query = static function ( $key ) {
    if ( ! isset( $_GET[ $key ] ) ) {
        return array();
    }
    $values = array_map( 'sanitize_title', (array) wp_unslash( $_GET[ $key ] ) );
    return array_values( array_unique( array_filter( $values ) ) );
};

$raw_format = isset( $_GET['media_format'] ) ? sanitize_title( wp_unslash( $_GET['media_format'] ) ) : '';
$format     = 'podcast' === $raw_format ? 'podcast' : ( in_array( $raw_format, array( 'youtube', 'video' ), true ) ? 'video' : '' );
$query_formats = 'podcast' === $format ? array( 'podcast' ) : ( 'video' === $format ? array( 'youtube', 'video' ) : array() );
$selected_series = $read_array_query( 'media_series' );
$selected_topics = $read_array_query( 'topic' );
$selected_year   = isset( $_GET['media_year'] ) ? sanitize_text_field( wp_unslash( $_GET['media_year'] ) ) : '';
$selected_year   = preg_match( '/^20\d{2}$/', $selected_year ) ? $selected_year : '';
$keyword         = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';
$sort_order      = isset( $_GET['order'] ) && 'oldest' === sanitize_key( wp_unslash( $_GET['order'] ) ) ? 'oldest' : 'newest';

$featured_items = fwdignity_get_featured_media_items( 3 );
$series_items   = fwdignity_get_media_series_terms();
$podcast_items  = fwdignity_get_media_items(
    array(
        'posts_per_page' => 6,
        'formats'        => array( 'podcast' ),
    )
);
$video_items    = fwdignity_get_media_items(
    array(
        'posts_per_page' => 5,
        'formats'        => array( 'youtube', 'video' ),
    )
);
$topic_items    = fwdignity_get_media_topic_terms();
$media_years    = fwdignity_get_media_years();

$allowed_series = array();
foreach ( $series_items as $series_item ) {
    $allowed_series[] = $series_item['term']->slug;
}
$allowed_topics = array();
foreach ( $topic_items as $topic_item ) {
    $allowed_topics[] = $topic_item['term']->slug;
}
$selected_series = array_values( array_intersect( $selected_series, $allowed_series ) );
$selected_topics = array_values( array_intersect( $selected_topics, $allowed_topics ) );

$archive_result = fwdignity_query_media_items(
    array(
        // 2026-07-16 W2-B：現行影音僅 17 筆，放寬單頁上限讓全部集數一頁可見（舊集不再藏第 2 頁）。
        'posts_per_page' => 24,
        'paged'          => $paged,
        'order'          => 'oldest' === $sort_order ? 'ASC' : 'DESC',
        'keyword'        => $keyword,
        'formats'        => $query_formats,
        'series'         => $selected_series,
        'topics'         => $selected_topics,
        'year'           => $selected_year,
    )
);

$series_names = static function ( $item ) {
    return implode( '、', wp_list_pluck( (array) $item['series'], 'name' ) );
};

$current_args = array();
if ( $format ) {
    $current_args['media_format'] = $format;
}
if ( $selected_series ) {
    $current_args['media_series'] = $selected_series;
}
if ( $selected_topics ) {
    $current_args['topic'] = $selected_topics;
}
if ( $selected_year ) {
    $current_args['media_year'] = $selected_year;
}
if ( $keyword ) {
    $current_args['keyword'] = $keyword;
}
if ( 'oldest' === $sort_order ) {
    $current_args['order'] = 'oldest';
}

$remove_filter_url = static function ( $group, $value ) use ( $page_url, $current_args ) {
    $args = $current_args;
    if ( in_array( $group, array( 'media_series', 'topic' ), true ) ) {
        $args[ $group ] = array_values( array_diff( isset( $args[ $group ] ) ? (array) $args[ $group ] : array(), array( $value ) ) );
        if ( ! $args[ $group ] ) {
            unset( $args[ $group ] );
        }
    } else {
        unset( $args[ $group ] );
    }
    return $args ? add_query_arg( $args, $page_url ) : $page_url;
};

$render_media_visual = static function ( $item, $class_name, $loading = 'lazy' ) {
    ?>
    <div class="<?php echo esc_attr( $class_name ); ?><?php echo $item['thumb'] ? '' : ' no-image'; ?>">
        <?php if ( $item['thumb'] ) : ?>
            <img src="<?php echo esc_url( $item['thumb'] ); ?>" alt="" loading="<?php echo esc_attr( $loading ); ?>">
        <?php else : ?>
            <span class="mh-visual-fallback" aria-hidden="true"><i class="fa-solid <?php echo 'podcast' === $item['format'] ? 'fa-wave-square' : 'fa-play'; ?>"></i></span>
        <?php endif; ?>
    </div>
    <?php
};

$active_count = ( $format ? 1 : 0 ) + count( $selected_series ) + count( $selected_topics ) + ( $selected_year ? 1 : 0 ) + ( $keyword ? 1 : 0 );
?>

<main class="mh-main" id="main">
    <header class="mh-intro">
        <div class="mh-wrap">
            <p class="mh-eyebrow">Audio &amp; Video</p>
            <h1><?php the_title(); ?></h1>
            <p>從田間到餐桌，從勞動到尊嚴。透過 Podcast 與影音內容，我們記錄雲嘉農業工作者的實踐故事、健康困境與在地知識，讓第一線的聲音被聽見。</p>
        </div>
    </header>

    <section class="mh-section mh-feature" aria-labelledby="mh-feature-title" data-media-feature>
        <div class="mh-wrap">
            <div class="mh-section-head mh-section-head-split">
                <div>
                    <p class="mh-eyebrow">Featured</p>
                    <h2 id="mh-feature-title">本期精選</h2>
                </div>
                <?php if ( count( $featured_items ) > 1 ) : ?>
                <div class="mh-feature-controls" aria-label="精選內容輪播控制">
                    <button type="button" data-media-feature-toggle aria-pressed="false" aria-label="暫停自動輪播" title="暫停自動輪播"><i class="fa-solid fa-pause" aria-hidden="true"></i></button>
                    <button type="button" data-media-feature-prev aria-label="上一則精選" title="上一則精選"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></button>
                    <button type="button" data-media-feature-next aria-label="下一則精選" title="下一則精選"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
                </div>
                <?php endif; ?>
            </div>

            <div class="mh-feature-stage">
                <?php foreach ( $featured_items as $index => $item ) : ?>
                <article class="mh-feature-slide" data-media-feature-slide data-index="<?php echo esc_attr( (string) $index ); ?>"<?php echo 0 === $index ? '' : ' hidden'; ?>>
                    <div class="mh-feature-player" data-media-player>
                        <?php $render_media_visual( $item, 'mh-feature-visual', 0 === $index ? 'eager' : 'lazy' ); ?>
                        <?php if ( $item['embed']['url'] ) : ?>
                        <button class="mh-play-button" type="button" data-media-play data-embed-type="<?php echo esc_attr( $item['embed']['type'] ); ?>" data-embed-url="<?php echo esc_url( $item['embed']['url'] ); ?>" data-audio-title="<?php echo esc_attr( $item['title'] ); ?>" data-audio-duration="<?php echo esc_attr( $item['duration'] ); ?>" aria-label="播放：<?php echo esc_attr( $item['title'] ); ?>">
                            <i class="fa-solid fa-play" aria-hidden="true"></i><span><?php echo esc_html( 'podcast' === $item['format'] ? '播放本集' : '播放影片' ); ?></span>
                        </button>
                        <?php else : ?>
                        <a class="mh-play-button" href="<?php echo esc_url( $item['link'] ); ?>"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i><span>閱讀完整內容</span></a>
                        <?php endif; ?>
                    </div>
                    <div class="mh-feature-copy">
                        <p class="mh-meta"><?php echo esc_html( $item['format_label'] ); ?><?php echo $series_names( $item ) ? '｜' . esc_html( $series_names( $item ) ) : ''; ?>｜<?php echo esc_html( $item['date'] ); ?></p>
                        <h3><a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
                        <p class="mh-feature-excerpt"><?php echo esc_html( $item['excerpt'] ); ?></p>
                        <?php if ( $item['key_points'] ) : ?>
                        <ul class="mh-key-points">
                            <?php foreach ( $item['key_points'] as $point ) : ?><li><?php echo esc_html( $point ); ?></li><?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <a class="mh-text-link" href="<?php echo esc_url( $item['link'] ); ?>">查看完整內容 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <?php if ( count( $featured_items ) > 1 ) : ?>
            <div class="mh-feature-dots" role="group" aria-label="指定精選內容">
                <?php foreach ( $featured_items as $index => $item ) : ?>
                <button type="button" data-media-feature-dot="<?php echo esc_attr( (string) $index ); ?>" aria-label="第 <?php echo esc_attr( (string) ( $index + 1 ) ); ?> 則：<?php echo esc_attr( $item['title'] ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"><span><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="mh-section mh-series" aria-labelledby="mh-series-title">
        <div class="mh-wrap">
            <div class="mh-section-head mh-section-head-split">
                <div><p class="mh-eyebrow">Programs</p><h2 id="mh-series-title">節目系列</h2></div>
                <p>沿著一個節目持續收聽，不必從全部文章重新尋找。</p>
            </div>
            <?php if ( $series_items ) : ?>
            <div class="mh-series-list">
                <?php foreach ( $series_items as $index => $series_item ) : ?>
                <a class="mh-series-item" href="<?php echo esc_url( $series_item['link'] ); ?>">
                    <span class="mh-series-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
                    <span class="mh-series-name"><strong><?php echo esc_html( $series_item['term']->name ); ?></strong><?php if ( $series_item['term']->description ) : ?><small><?php echo esc_html( $series_item['term']->description ); ?></small><?php endif; ?></span>
                    <span class="mh-series-facts"><?php echo esc_html( (string) $series_item['count'] ); ?> 集<br><small>更新至 <?php echo esc_html( $series_item['latest_date'] ); ?></small></span>
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <p class="mh-honest-empty">系列資料正在依既有節目整理；目前仍可從下方瀏覽全部 Podcast 與影片。</p>
            <?php endif; ?>
        </div>
    </section>

    <?php if ( $podcast_items ) : $podcast_main = $podcast_items[0]; ?>
    <section class="mh-section mh-podcast" aria-labelledby="mh-podcast-title" data-media-podcast>
        <div class="mh-wrap">
            <div class="mh-section-head mh-section-head-split">
                <div><p class="mh-eyebrow">Listen</p><h2 id="mh-podcast-title">最新 Podcast</h2></div>
                <a class="mh-text-link" href="<?php echo esc_url( add_query_arg( 'media_format', 'podcast', $page_url ) . '#mh-archive' ); ?>">查看全部 Podcast <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            <div class="mh-podcast-desk">
                <article class="mh-podcast-main" data-media-podcast-main>
                    <?php $render_media_visual( $podcast_main, 'mh-podcast-cover', 'eager' ); ?>
                    <div>
                        <p class="mh-meta" data-media-main-meta><?php echo esc_html( $podcast_main['date'] ); ?><?php echo $series_names( $podcast_main ) ? '｜' . esc_html( $series_names( $podcast_main ) ) : ''; ?></p>
                        <h3 data-media-main-title><a href="<?php echo esc_url( $podcast_main['link'] ); ?>"><?php echo esc_html( $podcast_main['title'] ); ?></a></h3>
                        <p data-media-main-excerpt><?php echo esc_html( $podcast_main['excerpt'] ); ?></p>
                        <div data-media-main-action>
                            <?php if ( 'audio' === $podcast_main['embed']['type'] && $podcast_main['embed']['url'] ) : ?>
                            <?php fwdignity_render_audio_player( $podcast_main, array( 'class' => 'fw-audio-player--hub' ) ); ?>
                            <?php elseif ( $podcast_main['embed']['url'] ) : ?>
                            <button class="mh-action-button" type="button" data-media-play data-embed-type="<?php echo esc_attr( $podcast_main['embed']['type'] ); ?>" data-embed-url="<?php echo esc_url( $podcast_main['embed']['url'] ); ?>"><i class="fa-solid fa-play" aria-hidden="true"></i> 播放本集</button>
                            <?php else : ?>
                            <a class="mh-action-button" href="<?php echo esc_url( $podcast_main['link'] ); ?>">閱讀完整內容</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <div class="mh-podcast-queue" aria-label="Podcast 待播清單">
                    <p class="mh-queue-label">待播清單</p>
                    <?php foreach ( array_slice( $podcast_items, 0, 5 ) as $index => $item ) : ?>
                    <a href="<?php echo esc_url( $item['link'] ); ?>" class="mh-queue-item" data-media-podcast-item="<?php echo esc_attr( (string) $index ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>">
                        <span><?php echo esc_html( $item['episode'] ? $item['episode'] : sprintf( '%02d', $index + 1 ) ); ?></span>
                        <strong><?php echo esc_html( $item['title'] ); ?></strong>
                        <small><?php echo esc_html( $item['duration'] ? $item['duration'] : $item['date'] ); ?></small>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <script type="application/json" data-media-podcast-data><?php echo wp_json_encode( array_slice( $podcast_items, 0, 5 ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
        </div>
    </section>
    <?php endif; ?>

    <?php if ( $video_items ) : $video_main = $video_items[0]; ?>
    <section class="mh-section mh-videos" aria-labelledby="mh-video-title" data-media-video>
        <div class="mh-wrap">
            <div class="mh-section-head mh-section-head-split">
                <div><p class="mh-eyebrow">Watch</p><h2 id="mh-video-title">最新影片</h2></div>
                <a class="mh-text-link" href="<?php echo esc_url( add_query_arg( 'media_format', 'video', $page_url ) . '#mh-archive' ); ?>">查看全部影片 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            <div class="mh-video-screen">
                <article class="mh-video-main" data-media-video-main>
                    <div data-media-video-player><?php $render_media_visual( $video_main, 'mh-video-visual', 'eager' ); ?></div>
                    <div class="mh-video-copy">
                        <p class="mh-meta" data-media-main-meta><?php echo esc_html( $video_main['date'] ); ?></p>
                        <h3 data-media-main-title><a href="<?php echo esc_url( $video_main['link'] ); ?>"><?php echo esc_html( $video_main['title'] ); ?></a></h3>
                        <div data-media-main-action>
                            <?php if ( $video_main['embed']['url'] ) : ?>
                            <button class="mh-action-button" type="button" data-media-play data-embed-type="<?php echo esc_attr( $video_main['embed']['type'] ); ?>" data-embed-url="<?php echo esc_url( $video_main['embed']['url'] ); ?>"><i class="fa-solid fa-play" aria-hidden="true"></i> 播放影片</button>
                            <?php else : ?>
                            <a class="mh-action-button" href="<?php echo esc_url( $video_main['link'] ); ?>">閱讀完整內容</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <div class="mh-video-list" aria-label="其他影片">
                    <?php foreach ( array_slice( $video_items, 0, 4 ) as $index => $item ) : ?>
                    <a href="<?php echo esc_url( $item['link'] ); ?>" class="mh-video-item<?php echo $item['thumb'] ? '' : ' mh-video-item--text-only'; ?>" data-media-video-item="<?php echo esc_attr( (string) $index ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>">
                        <?php if ( $item['thumb'] ) : ?>
                            <?php $render_media_visual( $item, 'mh-video-thumb' ); ?>
                        <?php endif; ?>
                        <span class="mh-video-item-copy"><small><?php echo esc_html( $item['date'] ); ?></small><strong><?php echo esc_html( $item['title'] ); ?></strong></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <script type="application/json" data-media-video-data><?php echo wp_json_encode( array_slice( $video_items, 0, 4 ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
        </div>
    </section>
    <?php endif; ?>

    <section class="mh-section mh-archive" id="mh-archive" aria-labelledby="mh-archive-title">
        <div class="mh-wrap">
            <div class="mh-section-head mh-section-head-split">
                <div><p class="mh-eyebrow">Archive</p><h2 id="mh-archive-title">全部聲音與影像</h2></div>
                <strong class="mh-result-count"><?php echo esc_html( (string) $archive_result['total'] ); ?> 篇內容</strong>
            </div>

            <form class="mh-filter-form" method="get" action="<?php echo esc_url( $page_url ); ?>" data-media-filter-form>
                <div class="mh-filter-bar">
                    <fieldset class="mh-format-control">
                        <legend>形式</legend>
                        <label><input type="radio" name="media_format" value="" <?php checked( '', $format ); ?>><span>全部</span></label>
                        <label><input type="radio" name="media_format" value="podcast" <?php checked( 'podcast', $format ); ?>><span>Podcast</span></label>
                        <label><input type="radio" name="media_format" value="video" <?php checked( 'video', $format ); ?>><span>影片</span></label>
                    </fieldset>
                    <label class="mh-search"><span>搜尋</span><input type="search" name="keyword" value="<?php echo esc_attr( $keyword ); ?>" placeholder="搜尋標題或內容"><button type="submit" aria-label="送出搜尋"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button></label>
                    <label><span>年份</span><select name="media_year"><option value="">全部年份</option><?php foreach ( $media_years as $year ) : ?><option value="<?php echo esc_attr( $year ); ?>" <?php selected( $selected_year, $year ); ?>><?php echo esc_html( $year ); ?></option><?php endforeach; ?></select></label>
                    <label><span>排序</span><select name="order"><option value="newest" <?php selected( $sort_order, 'newest' ); ?>>最新優先</option><option value="oldest" <?php selected( $sort_order, 'oldest' ); ?>>最早優先</option></select></label>
                    <button class="mh-filter-toggle" type="button" data-media-filter-toggle aria-expanded="false"><i class="fa-solid fa-sliders" aria-hidden="true"></i> 篩選<?php if ( $active_count ) : ?><span><?php echo esc_html( (string) $active_count ); ?></span><?php endif; ?></button>
                </div>

                <div class="mh-filter-drawer" data-media-filter-drawer>
                    <div class="mh-filter-drawer-head"><strong>進一步篩選</strong><button type="button" data-media-filter-close aria-label="關閉篩選"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button></div>
                    <fieldset><legend>節目系列</legend><?php if ( $series_items ) : foreach ( $series_items as $series_item ) : $term = $series_item['term']; ?><label><input type="checkbox" name="media_series[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, $selected_series, true ) ); ?>><span><?php echo esc_html( $term->name ); ?></span><small><?php echo esc_html( (string) $series_item['count'] ); ?></small></label><?php endforeach; else : ?><p>尚未建立系列分類。</p><?php endif; ?></fieldset>
                    <fieldset><legend>內容主題</legend><?php if ( $topic_items ) : foreach ( $topic_items as $topic_item ) : $term = $topic_item['term']; ?><label><input type="checkbox" name="topic[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, $selected_topics, true ) ); ?>><span><?php echo esc_html( $term->name ); ?></span><small><?php echo esc_html( (string) $topic_item['count'] ); ?></small></label><?php endforeach; else : ?><p>目前沒有可用主題。</p><?php endif; ?></fieldset>
                    <div class="mh-filter-actions"><button class="mh-action-button" type="submit">套用條件</button><a href="<?php echo esc_url( $page_url . '#mh-archive' ); ?>">清除全部</a></div>
                </div>
            </form>

            <?php if ( $active_count ) : ?>
            <div class="mh-active-filters" aria-label="已選條件">
                <span>已選條件</span>
                <?php if ( $format ) : ?><a href="<?php echo esc_url( $remove_filter_url( 'media_format', $format ) . '#mh-archive' ); ?>"><?php echo esc_html( fwdignity_media_format_label( $format ) ); ?> <i class="fa-solid fa-xmark" aria-hidden="true"></i></a><?php endif; ?>
                <?php foreach ( $selected_series as $slug ) : $term = get_term_by( 'slug', $slug, 'media_series' ); if ( $term ) : ?><a href="<?php echo esc_url( $remove_filter_url( 'media_series', $slug ) . '#mh-archive' ); ?>"><?php echo esc_html( $term->name ); ?> <i class="fa-solid fa-xmark" aria-hidden="true"></i></a><?php endif; endforeach; ?>
                <?php foreach ( $selected_topics as $slug ) : $term = get_category_by_slug( $slug ); if ( $term ) : ?><a href="<?php echo esc_url( $remove_filter_url( 'topic', $slug ) . '#mh-archive' ); ?>"><?php echo esc_html( $term->name ); ?> <i class="fa-solid fa-xmark" aria-hidden="true"></i></a><?php endif; endforeach; ?>
                <?php if ( $selected_year ) : ?><a href="<?php echo esc_url( $remove_filter_url( 'media_year', $selected_year ) . '#mh-archive' ); ?>"><?php echo esc_html( $selected_year ); ?> <i class="fa-solid fa-xmark" aria-hidden="true"></i></a><?php endif; ?>
                <?php if ( $keyword ) : ?><a href="<?php echo esc_url( $remove_filter_url( 'keyword', $keyword ) . '#mh-archive' ); ?>">搜尋：<?php echo esc_html( $keyword ); ?> <i class="fa-solid fa-xmark" aria-hidden="true"></i></a><?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ( $archive_result['items'] ) : ?>
            <div class="mh-archive-list">
                <?php foreach ( $archive_result['items'] as $item ) : ?>
                <article class="mh-archive-item<?php echo $item['thumb'] ? '' : ' mh-archive-item--text-only'; ?>">
                    <?php if ( $item['thumb'] ) : ?>
                    <a class="mh-archive-visual" href="<?php echo esc_url( $item['link'] ); ?>" tabindex="-1" aria-hidden="true"><?php $render_media_visual( $item, 'mh-archive-thumb' ); ?></a>
                    <?php endif; ?>
                    <div class="mh-archive-copy">
                        <p class="mh-meta"><?php echo esc_html( $item['format_label'] ); ?><?php echo $series_names( $item ) ? '｜' . esc_html( $series_names( $item ) ) : ''; ?>｜<?php echo esc_html( $item['date'] ); ?></p>
                        <h3><a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
                        <p><?php echo esc_html( $item['excerpt'] ); ?></p>
                    </div>
                    <?php if ( 'podcast' === $item['format'] && 'audio' === $item['embed']['type'] ) : ?>
                    <div class="mh-row-player">
                        <?php fwdignity_render_audio_player( $item, array( 'compact' => true ) ); ?>
                        <a class="mh-row-detail" href="<?php echo esc_url( $item['link'] ); ?>">查看本集內容 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <?php else : ?>
                    <a class="mh-row-action" href="<?php echo esc_url( $item['link'] ); ?>">觀看影片 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    <?php endif; ?>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <div class="mh-empty"><h3>目前沒有符合條件的內容</h3><p>可移除部分條件，或查看全部聲音與影像。</p><a class="mh-action-button" href="<?php echo esc_url( $page_url . '#mh-archive' ); ?>">清除全部條件</a></div>
            <?php endif; ?>

            <?php if ( $archive_result['pages'] > 1 ) : ?>
            <nav class="mh-pagination" aria-label="聲音與影像分頁">
                <?php echo wp_kses_post( paginate_links( array( 'base' => trailingslashit( $page_url ) . 'page/%#%/', 'format' => '', 'current' => $paged, 'total' => $archive_result['pages'], 'add_args' => $current_args, 'prev_text' => '上一頁', 'next_text' => '下一頁' ) ) ); ?>
            </nav>
            <?php endif; ?>
        </div>
    </section>

    <section class="mh-section mh-related" aria-labelledby="mh-related-title">
        <div class="mh-wrap mh-related-grid">
            <div><p class="mh-eyebrow">Explore</p><h2 id="mh-related-title">延伸入口</h2></div>
            <a href="<?php echo esc_url( home_url( '/field-notes/' ) ); ?>"><span>田野誌</span><strong>從現場照片與紀錄繼續理解農業工作。</strong><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            <a href="<?php echo esc_url( home_url( '/research-results/related-resources/' ) ); ?>"><span>專題資源</span><strong>依職業安全、健康與制度議題找資料。</strong><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
