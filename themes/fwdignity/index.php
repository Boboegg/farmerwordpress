<?php
/**
 * fwdignity theme — index.php
 * Fallback template。WordPress 找不到專屬 template 時用這份。
 *
 * @package fwdignity
 */
?>
<?php get_header(); ?>

<main id="main" class="site-main fw-page-main" role="main">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p>找不到內容。</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
