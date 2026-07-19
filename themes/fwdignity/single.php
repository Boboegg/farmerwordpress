<?php
/**
 * Single post template shared by news, field notes, and media entries.
 *
 * @package fwdignity
 */

get_header();

while ( have_posts() ) :
    the_post();

    $post_id    = get_the_ID();
    $categories = get_the_category( $post_id );
    $category_ids = wp_list_pluck( $categories, 'term_id' );
    $topic_terms = taxonomy_exists( 'fwd_topic' ) ? get_the_terms( $post_id, 'fwd_topic' ) : array();
    if ( is_wp_error( $topic_terms ) ) {
        $topic_terms = array();
    }
    $is_media   = has_category( array( 'podcast', 'youtube', 'video', 'media' ), $post_id );
    $is_field   = has_category( array( 'agri-school', 'event', 'greenhouse', 'environment', 'education-farmer' ), $post_id );
    $media_item = $is_media && function_exists( 'fwdignity_get_media_item' )
        ? fwdignity_get_media_item( $post_id )
        : array();

    if ( $is_media ) {
        $back_url   = home_url( '/podcast/' );
        $back_label = '聽見田裡的聲音';
        $eyebrow    = 'Audio & Video';
    } elseif ( $is_field ) {
        $back_url   = home_url( '/field-notes/' );
        $back_label = '田野誌';
        $eyebrow    = 'Field Journal';
    } else {
        $back_url   = home_url( '/thenews/' );
        $back_label = '最新消息';
        $eyebrow    = 'News & Updates';
    }

    $display_date_raw = get_the_date( 'Y-m-d' );
    $display_date     = get_the_date( 'Y.m.d' );
    if ( $is_field && function_exists( 'fwdignity_get_field_date_raw' ) ) {
        $display_date_raw = fwdignity_get_field_date_raw( $post_id );
        $display_date     = fwdignity_get_field_date( $post_id );
    }

    $related = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 3,
            'post__not_in'        => array( $post_id ),
            'category__in'        => array_map( 'intval', $category_ids ),
            'ignore_sticky_posts' => true,
        )
    );
    ?>
    <main class="article-shell" id="main">
        <nav class="article-breadcrumb" aria-label="麵包屑">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">首頁</a>
            <span aria-hidden="true">/</span>
            <a href="<?php echo esc_url( $back_url ); ?>"><?php echo esc_html( $back_label ); ?></a>
        </nav>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'article-entry' ); ?>>
            <header class="article-header">
                <p class="article-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
                <?php the_title( '<h1>', '</h1>' ); ?>
                <div class="article-meta">
                    <time datetime="<?php echo esc_attr( $display_date_raw ); ?>"><?php echo esc_html( $display_date ); ?></time>
                    <?php if ( $categories ) : ?>
                    <span aria-hidden="true">/</span>
                    <div class="article-categories" aria-label="內容分類">
                        <?php foreach ( $categories as $category ) : ?>
                        <a href="<?php echo esc_url( fwdignity_thenews_filter_url( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ( $topic_terms ) : ?>
                    <nav class="article-topics" aria-label="相關主題">
                        <span>相關主題</span>
                        <?php foreach ( fwdignity_topic_registry() as $topic_slug => $topic ) : ?>
                            <?php if ( in_array( $topic_slug, wp_list_pluck( $topic_terms, 'slug' ), true ) && fwdignity_get_topic_page_url( $topic_slug ) ) : ?>
                                <a href="<?php echo esc_url( fwdignity_get_topic_page_url( $topic_slug ) ); ?>"><?php echo esc_html( $topic['label'] ); ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </nav>
                <?php endif; ?>
            </header>

            <?php if ( $media_item && 'podcast' === $media_item['format'] && 'audio' === $media_item['embed']['type'] ) : ?>
            <section class="article-audio" aria-label="Podcast 播放器">
                <p class="article-audio__label">收聽本集</p>
                <?php fwdignity_render_audio_player( $media_item, array( 'class' => 'fw-audio-player--article' ) ); ?>
            </section>
            <?php endif; ?>

            <?php if ( has_post_thumbnail() ) : ?>
            <figure class="article-featured-image">
                <?php
                $thumbnail_id  = get_post_thumbnail_id();
                $thumbnail_alt = trim( (string) get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) );
                if ( '' === $thumbnail_alt ) {
                    $thumbnail_alt = get_the_title();
                }
                echo wp_get_attachment_image(
                    $thumbnail_id,
                    'large',
                    false,
                    array(
                        'loading' => 'eager',
                        'alt'     => $thumbnail_alt,
                    )
                );
                ?>
                <?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
                <figcaption><?php echo esc_html( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
                <?php endif; ?>
            </figure>
            <?php endif; ?>

            <div class="article-content">
                <?php the_content(); ?>
            </div>

            <?php if ( has_tag() ) : ?>
            <footer class="article-tags" aria-label="文章標籤">
                <span>標籤</span>
                <?php the_tags( '', '', '' ); ?>
            </footer>
            <?php endif; ?>
        </article>

        <?php if ( $related->have_posts() ) : ?>
        <section class="article-related" aria-labelledby="article-related-title">
            <div class="article-section-head">
                <p class="article-eyebrow">Continue Reading</p>
                <h2 id="article-related-title">相關內容</h2>
            </div>
            <div class="article-related-list">
                <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                <article>
                    <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <a class="article-related-link" href="<?php the_permalink(); ?>">閱讀內容 <span aria-hidden="true">→</span></a>
                </article>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; wp_reset_postdata(); ?>

        <div class="article-return">
            <a href="<?php echo esc_url( $back_url ); ?>"><span aria-hidden="true">←</span> 返回<?php echo esc_html( $back_label ); ?></a>
        </div>
    </main>
    <?php
endwhile;

get_footer();
