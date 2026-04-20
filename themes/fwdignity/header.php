<?php
/**
 * fwdignity theme — header.php
 * Phase 1a skeleton。S3 擴充：nav、Google Fonts、完整 head meta。
 *
 * @package fwdignity
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'fw-page-v15' ); ?> data-page="<?php echo esc_attr( fwdignity_get_data_page() ); ?>" id="page">
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main">跳至主內容</a>

<header class="site-header" role="banner">
    <nav class="site-nav" aria-label="主導覽">
        <a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php bloginfo( 'name' ); ?>
        </a>
    </nav>
</header>
