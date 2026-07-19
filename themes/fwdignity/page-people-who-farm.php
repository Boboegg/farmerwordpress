<?php
/**
 * Template Name: 做農的人
 * Slug: people-who-farm
 * People Who Farm editorial hub.
 *
 * @package fwdignity
 */

get_header();

while ( have_posts() ) :
    the_post();

    $registry      = fwdignity_topic_registry();
    $topic_items   = array();
    $used_post_ids = array();

    foreach ( $registry as $term_slug => $topic ) {
        $topic_page = fwdignity_get_topic_page( $term_slug );
        if ( ! $topic_page || 'publish' !== $topic_page->post_status ) {
            continue;
        }

        $featured_id = (int) get_post_meta( $topic_page->ID, '_fwd_topic_featured_post_id', true );
        if (
            ! $featured_id ||
            in_array( $featured_id, $used_post_ids, true ) ||
            'publish' !== get_post_status( $featured_id ) ||
            ! has_term( $term_slug, 'fwd_topic', $featured_id )
        ) {
            $representative_query = fwdignity_topic_query(
                $term_slug,
                array(
                    'posts_per_page' => 1,
                    'post__not_in'   => $used_post_ids,
                )
            );
            $featured_id = $representative_query->have_posts()
                ? (int) $representative_query->posts[0]->ID
                : 0;
        }

        if ( $featured_id ) {
            $used_post_ids[] = $featured_id;
        }

        $thumbnail_id = get_post_thumbnail_id( $topic_page->ID );
        if ( ! $thumbnail_id && $featured_id ) {
            $thumbnail_id = get_post_thumbnail_id( $featured_id );
        }

        $topic_items[] = array(
            'term_slug'   => $term_slug,
            'topic'       => $topic,
            'page'        => $topic_page,
            'url'         => get_permalink( $topic_page ),
            'featured_id' => $featured_id,
            'thumbnail'   => $thumbnail_id,
            'summary'     => $topic_page->post_excerpt ? $topic_page->post_excerpt : $topic['core'],
        );
    }

    $recent_query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 4,
            'post__not_in'        => $used_post_ids,
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
            'orderby'             => array(
                'date' => 'DESC',
                'ID'   => 'DESC',
            ),
            'tax_query'           => array(
                array(
                    'taxonomy' => 'fwd_topic',
                    'operator' => 'EXISTS',
                ),
            ),
        )
    );

    $research_page = get_page_by_path( 'research-results', OBJECT, 'page' );
    $research_url  = $research_page && 'publish' === $research_page->post_status
        ? get_permalink( $research_page )
        : '';
    ?>
    <main class="pf-hub" id="main" data-layout-version="v3">
        <nav class="pf-breadcrumb" aria-label="麵包屑">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">首頁</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">做農的人</span>
        </nav>

        <header class="pf-page-intro">
            <p class="pf-eyebrow">People Who Farm</p>
            <h1><?php the_title(); ?></h1>
            <div class="pf-page-intro__lead">
                <?php the_content(); ?>
            </div>
        </header>

        <?php if ( $topic_items ) : ?>
            <section class="pf-topic-desk" aria-labelledby="pf-topic-desk-title" data-topic-map>
                <div class="pf-section-heading">
                    <p class="pf-eyebrow">Five Questions</p>
                    <h2 id="pf-topic-desk-title">五個值得理解的問題</h2>
                </div>

                <div class="pf-question-grid" aria-label="做農的人五個主題">
                    <?php foreach ( $topic_items as $index => $item ) : ?>
                        <article
                            class="pf-question-entry pf-question-entry--<?php echo esc_attr( (string) ( $index + 1 ) ); ?>"
                            data-topic-entry
                            data-topic="<?php echo esc_attr( $item['term_slug'] ); ?>"
                        >
                            <?php if ( $item['thumbnail'] ) : ?>
                                <a class="pf-question-entry__media" href="<?php echo esc_url( $item['url'] ); ?>" tabindex="-1" aria-hidden="true">
                                    <?php
                                    echo wp_get_attachment_image(
                                        $item['thumbnail'],
                                        'medium_large',
                                        false,
                                        array(
                                            'loading' => 0 === $index ? 'eager' : 'lazy',
                                            'alt'     => '',
                                        )
                                    );
                                    ?>
                                </a>
                            <?php endif; ?>
                            <div class="pf-question-entry__body">
                                <p class="pf-question-entry__number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></p>
                                <h3><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['topic']['label'] ); ?></a></h3>
                                <p class="pf-question-entry__summary"><?php echo esc_html( $item['summary'] ); ?></p>
                                <?php if ( $item['featured_id'] ) : ?>
                                    <p class="pf-question-entry__start">
                                        <span>從這篇開始</span>
                                        <a href="<?php echo esc_url( get_permalink( $item['featured_id'] ) ); ?>"><?php echo esc_html( get_the_title( $item['featured_id'] ) ); ?></a>
                                    </p>
                                <?php endif; ?>
                                <a class="pf-text-link" href="<?php echo esc_url( $item['url'] ); ?>">進入主題 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ( $recent_query->have_posts() ) : ?>
            <section class="pf-recent" aria-labelledby="pf-recent-title">
                <div class="pf-section-heading pf-section-heading--row">
                    <div>
                        <p class="pf-eyebrow">Recent Stories</p>
                        <h2 id="pf-recent-title">最近更新</h2>
                    </div>
                    <a class="pf-text-link" href="<?php echo esc_url( home_url( '/thenews/' ) ); ?>">查看全部內容 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="pf-content-list pf-content-list--four">
                    <?php
                    foreach ( $recent_query->posts as $recent_post ) {
                        fwdignity_render_topic_card( $recent_post->ID, '', 'pf-content-card--compact' );
                    }
                    ?>
                </div>
            </section>
        <?php endif; ?>

        <section class="pf-research-bridge" aria-labelledby="pf-research-bridge-title">
            <div>
                <p class="pf-eyebrow">From Questions To Action</p>
                <h2 id="pf-research-bridge-title">從問題走向行動</h2>
                <p>這些現場問題如何進入調查、培力、公共討論與政策建議，並持續回到田間接受檢驗。</p>
            </div>
            <?php if ( $research_url ) : ?>
                <a class="pf-text-link" href="<?php echo esc_url( $research_url ); ?>">研究與行動 <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            <?php else : ?>
                <span class="pf-link-status">內容整理中</span>
            <?php endif; ?>
        </section>
    </main>
    <?php
endwhile;

get_footer();
