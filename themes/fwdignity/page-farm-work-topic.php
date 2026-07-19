<?php
/**
 * Template Name: 農務主題
 * Slug: farm-work-topic
 * Shared template for the five People Who Farm topic Pages.
 *
 * @package fwdignity
 */

get_header();

while ( have_posts() ) :
    the_post();

    $page_id       = get_the_ID();
    $term_slug     = fwdignity_topic_term_slug_for_page( $page_id );
    $registry      = fwdignity_topic_registry();
    $topic         = isset( $registry[ $term_slug ] ) ? $registry[ $term_slug ] : null;
    $topic_query   = fwdignity_topic_query( $term_slug, array( 'posts_per_page' => -1 ) );
    $topic_posts   = $topic_query->posts;
    $topic_count   = count( $topic_posts );
    $used_post_ids = array();
    $featured_id   = (int) get_post_meta( $page_id, '_fwd_topic_featured_post_id', true );

    if (
        ! $featured_id ||
        'publish' !== get_post_status( $featured_id ) ||
        ! has_term( $term_slug, 'fwd_topic', $featured_id )
    ) {
        $featured_id = $topic_count ? (int) $topic_posts[0]->ID : 0;
    }

    if ( $featured_id ) {
        $used_post_ids[] = $featured_id;
    }

    $remaining_posts = array_values(
        array_filter(
            $topic_posts,
            static function( $post ) use ( $used_post_ids ) {
                return ! in_array( (int) $post->ID, $used_post_ids, true );
            }
        )
    );

    $topic_keys    = array_keys( $registry );
    $current_index = array_search( $term_slug, $topic_keys, true );
    $previous_key  = false !== $current_index && 0 < $current_index ? $topic_keys[ $current_index - 1 ] : end( $topic_keys );
    reset( $topic_keys );
    $next_key = false !== $current_index && $current_index < count( $topic_keys ) - 1 ? $topic_keys[ $current_index + 1 ] : $topic_keys[0];

    $layout_profiles = array(
        'body-workload'       => 'workload',
        'changing-work'       => 'change-sequence',
        'rural-support'       => 'support-network',
        'risk-responsibility' => 'responsibility-ledger',
        'knowledge-to-field'  => 'knowledge-path',
    );
    $layout_profile = isset( $layout_profiles[ $term_slug ] ) ? $layout_profiles[ $term_slug ] : 'workload';
    $hero_thumbnail_id = get_post_thumbnail_id( $page_id );
    if ( $hero_thumbnail_id ) {
        foreach ( $topic_posts as $topic_post ) {
            if ( $hero_thumbnail_id === get_post_thumbnail_id( $topic_post->ID ) ) {
                $hero_thumbnail_id = 0;
                break;
            }
        }
    }

    $hub_page = get_page_by_path( 'people-who-farm', OBJECT, 'page' );
    $hub_url  = $hub_page && 'publish' === $hub_page->post_status ? get_permalink( $hub_page ) : home_url( '/' );
    ?>
    <main
        class="pf-topic-page pf-topic-page--<?php echo esc_attr( $layout_profile ); ?>"
        id="main"
        data-topic="<?php echo esc_attr( $term_slug ); ?>"
        data-layout-profile="<?php echo esc_attr( $layout_profile ); ?>"
    >
        <nav class="pf-breadcrumb" aria-label="麵包屑">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">首頁</a>
            <span aria-hidden="true">/</span>
            <a href="<?php echo esc_url( $hub_url ); ?>">做農的人</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page"><?php the_title(); ?></span>
        </nav>

        <header class="pf-topic-hero">
            <div class="pf-topic-hero__copy">
                <p class="pf-eyebrow">People Who Farm</p>
                <h1><?php the_title(); ?></h1>
                <p class="pf-topic-hero__core"><?php echo esc_html( $topic ? $topic['core'] : get_the_excerpt() ); ?></p>
                <div class="pf-topic-hero__lead"><?php the_content(); ?></div>
            </div>
            <?php if ( $hero_thumbnail_id ) : ?>
                <figure class="pf-topic-hero__media">
                    <?php
                    $thumbnail_alt = trim( (string) get_post_meta( $hero_thumbnail_id, '_wp_attachment_image_alt', true ) );
                    echo wp_get_attachment_image(
                        $hero_thumbnail_id,
                        'large',
                        false,
                        array(
                            'loading' => 'eager',
                            'alt'     => $thumbnail_alt ? $thumbnail_alt : get_the_title(),
                        )
                    );
                    ?>
                </figure>
            <?php endif; ?>
        </header>

        <?php if ( 0 === $topic_count ) : ?>
            <section class="pf-empty-state" aria-labelledby="pf-empty-title">
                <p class="pf-eyebrow">Current Status</p>
                <h2 id="pf-empty-title">目前沒有可公開的相關內容</h2>
                <p>主題架構已建立；文章完成內容與來源核對後，會自動出現在這裡。</p>
                <a class="pf-text-link" href="<?php echo esc_url( home_url( '/thenews/' ) ); ?>">查看全部最新消息 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </section>
        <?php elseif ( 3 > $topic_count ) : ?>
            <section class="pf-topic-content" aria-labelledby="pf-topic-content-title">
                <div class="pf-section-heading">
                    <p class="pf-eyebrow">In This Topic</p>
                    <h2 id="pf-topic-content-title">本題內容</h2>
                </div>
                <div class="pf-content-list pf-content-list--two">
                    <?php
                    foreach ( $topic_posts as $topic_post ) {
                        fwdignity_render_topic_card( $topic_post->ID, $term_slug );
                    }
                    ?>
                </div>
            </section>
        <?php else : ?>
            <section class="pf-topic-feature" aria-labelledby="pf-topic-feature-title">
                <div class="pf-section-heading">
                    <p class="pf-eyebrow">Start Here</p>
                    <h2 id="pf-topic-feature-title">從這裡開始</h2>
                </div>
                <?php fwdignity_render_topic_card( $featured_id, $term_slug, 'pf-content-card--featured' ); ?>
            </section>

            <?php if ( $remaining_posts ) : ?>
                <section class="pf-topic-content" aria-labelledby="pf-topic-content-title">
                    <div class="pf-section-heading pf-section-heading--row">
                        <div>
                            <p class="pf-eyebrow">Related Stories</p>
                            <h2 id="pf-topic-content-title">相關內容</h2>
                        </div>
                        <a class="pf-text-link" href="<?php echo esc_url( add_query_arg( 'topic[]', $term_slug, home_url( '/thenews/' ) ) ); ?>">查看本題全部內容 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="pf-content-list pf-content-list--three">
                        <?php
                        foreach ( array_slice( $remaining_posts, 0, 6 ) as $related_post ) {
                            fwdignity_render_topic_card( $related_post->ID, $term_slug, 'pf-content-card--compact' );
                        }
                        ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>

        <section class="pf-topic-research" aria-labelledby="pf-topic-research-title">
            <div>
                <p class="pf-eyebrow">Research And Action</p>
                <h2 id="pf-topic-research-title">我們正在做什麼</h2>
                <p>從田野觀察、研究整理到培力與公共討論，持續追問這個問題如何被理解與回應。</p>
            </div>
            <?php $research_page = get_page_by_path( 'research-results', OBJECT, 'page' ); ?>
            <?php if ( $research_page && 'publish' === $research_page->post_status ) : ?>
                <a class="pf-text-link" href="<?php echo esc_url( get_permalink( $research_page ) ); ?>">研究與行動 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            <?php else : ?>
                <span class="pf-link-status">內容整理中</span>
            <?php endif; ?>
        </section>

    </main>
    <?php
endwhile;

get_footer();
