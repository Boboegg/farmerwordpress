<?php
/**
 * Template Name: 田間知識與資源
 *
 * @package fwdignity
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_template_directory() . '/inc/guide-content.php';

// 2026-07-17 W2-C：本頁 CSS 改由 functions.php 頁面資產對照表統一 enqueue，
// 修正原本「模板頂部搶先載入」造成的 cascade 順序異常（健檢 F10）。

get_header();

$guide_catalog   = fwdignity_get_farming_guide_catalog();
$guide_tasks     = fwdignity_get_farming_guide_task_index( $guide_catalog );
$guide_resources = fwdignity_get_public_farming_guide_resources( $guide_catalog );

while ( have_posts() ) :
    the_post();
    ?>
    <main class="fg-guide" id="main">
        <header class="fg-guide__intro">
            <h1><?php the_title(); ?></h1>
            <p>把健康、農藥、農機、經濟、保險與制度等跨專業資訊整理成容易理解、可以查證，也知道下一步去哪裡確認的內容。</p>

            <?php if ( $guide_tasks ) : ?>
                <nav class="fg-guide__quick-nav" aria-label="六大知識入口">
                    <?php foreach ( $guide_tasks as $task ) : ?>
                        <a href="#fg-task-<?php echo esc_attr( $task['key'] ); ?>">
                            <?php echo esc_html( $task['label'] ); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </header>

        <?php if ( $guide_tasks ) : ?>
            <section class="fg-guide__tasks" aria-labelledby="fg-guide-tasks-title">
                <div class="fg-guide__section-heading">
                    <p>01</p>
                    <h2 id="fg-guide-tasks-title">六大知識入口</h2>
                </div>

                <div class="fg-guide__task-grid">
                    <?php foreach ( $guide_tasks as $index => $task ) : ?>
                        <article class="fg-guide__task" id="fg-task-<?php echo esc_attr( $task['key'] ); ?>">
                            <header class="fg-guide__task-heading">
                                <span class="fg-guide__task-icon" aria-hidden="true">
                                    <i class="<?php echo esc_attr( $task['icon'] ); ?>"></i>
                                </span>
                                <div>
                                    <span><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
                                    <h3><?php echo esc_html( $task['label'] ); ?></h3>
                                </div>
                            </header>

                            <ul class="fg-guide__link-list">
                                <?php if ( $task['items'] ) : ?>
                                    <?php foreach ( $task['items'] as $item ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $item['url'] ); ?>">
                                                <span><?php echo esc_html( $item['title'] ); ?></span>
                                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li class="fg-guide__pending">內容整理中</li>
                                <?php endif; ?>
                                <?php if ( 'learning-support' === $task['key'] ) : ?>
                                    <li><a href="#training-videos"><span>影音學習</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
                                    <li><a href="#training-courses"><span>近期課程與官方學習平台</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
                                    <li><a href="#training-resources"><span>教材與延伸資源</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php get_template_part( 'template-parts/farming-learning-support' ); ?>

        <?php if ( $guide_resources ) : ?>
            <section class="fg-guide__resources" aria-labelledby="fg-guide-resources-title">
                <div class="fg-guide__section-heading">
                    <p>03</p>
                    <h2 id="fg-guide-resources-title">教材、檢核表與試算工具</h2>
                </div>

                <div class="fg-guide__resource-grid">
                    <?php foreach ( $guide_resources as $resource ) : ?>
                        <section class="fg-guide__resource-group" aria-labelledby="fg-resource-<?php echo esc_attr( $resource['key'] ); ?>">
                            <h3 id="fg-resource-<?php echo esc_attr( $resource['key'] ); ?>">
                                <i class="<?php echo esc_attr( $resource['icon'] ); ?>" aria-hidden="true"></i>
                                <?php echo esc_html( $resource['label'] ); ?>
                            </h3>
                            <ul class="fg-guide__resource-list">
                                <?php foreach ( $resource['items'] as $item ) : ?>
                                    <li>
                                        <a href="<?php echo esc_url( $item['url'] ); ?>">
                                            <?php echo esc_html( $item['title'] ); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php
endwhile;

get_footer();
