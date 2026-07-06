<?php
/**
 * fwdignity theme — header.php
 * Topbar + Navbar 合併版
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'fw-page-v15' ); ?> data-page="<?php echo esc_attr( fwdignity_get_data_page() ); ?>">
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#page">跳至主內容</a>

<nav class="fw-navbar" id="fw-navbar" aria-label="主導覽">
  <div class="fw-navbar-inner">

    <!-- Logo + 站名 -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fw-logo" aria-label="尊嚴農業 首頁">
      <img src="<?php echo esc_url( fwdignity_get_logo_url() ); ?>" alt="尊嚴農業" class="fw-logo-img">
      <span class="fw-logo-text">
        <span class="fw-logo-zh">尊嚴農業</span>
        <span class="fw-logo-en">DIGNITY · Farmer Worker</span>
      </span>
    </a>

    <!-- 主選單：優先用 WP 後台「外觀 → 選單 → 主要導覽」,沒設定則 fallback 硬寫 -->
    <?php if ( has_nav_menu( 'primary' ) ) : ?>
      <?php wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class'     => 'fw-nav-menu',
        'container'      => false,
        'fallback_cb'    => false,
        'walker'         => class_exists( 'Fwdignity_Nav_Walker' ) ? new Fwdignity_Nav_Walker() : '',
      ) ); ?>
    <?php else : ?>
    <ul class="fw-nav-menu" role="menubar">
      <li class="fw-nav-item<?php echo is_front_page() ? ' is-active' : ''; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fw-nav-link">首頁</a></li>

      <li class="fw-nav-item has-dropdown">
        <a href="/about/" class="fw-nav-link">關於 <i class="fas fa-chevron-down fw-caret"></i></a>
        <ul class="fw-dropdown">
          <li><a href="/about/">關於計畫</a></li>
          <li><a href="/about/researchteam/">研究團隊</a></li>
          <li><a href="/about/contact/">聯繫我們</a></li>
          <li><a href="/about/day/">行事曆</a></li>
        </ul>
      </li>

      <li class="fw-nav-item has-dropdown">
        <a href="/occupational-safety/" class="fw-nav-link">職業安全 <i class="fas fa-chevron-down fw-caret"></i></a>
        <ul class="fw-dropdown">
          <li><a href="/occupational-safety/personal-protective-equipment/">個人防護具 (PPE)</a></li>
          <li><a href="/occupational-safety/labor-saving-machinery/">省工農機</a></li>
          <li><a href="/occupational-safety/greenhouse-zone/">溫室專區</a></li>
        </ul>
      </li>

      <li class="fw-nav-item has-dropdown">
        <a href="/healthgood/" class="fw-nav-link">健康促進 <i class="fas fa-chevron-down fw-caret"></i></a>
        <ul class="fw-dropdown">
          <li><a href="/healthgood/subpage-heat/">熱傷害</a></li>
          <li><a href="/healthgood/subpage-msd/">肌肉骨骼</a></li>
          <li><a href="/healthgood/subpage-pesticide/">農藥安全</a></li>
          <li><a href="/healthgood/subpage-mental/">心理健康</a></li>
        </ul>
      </li>

      <li class="fw-nav-item has-dropdown">
        <a href="/economic-insurance/" class="fw-nav-link">經濟與保險 <i class="fas fa-chevron-down fw-caret"></i></a>
        <ul class="fw-dropdown">
          <li><a href="/economic-insurance/occupational-accident-insurance/">職業災害保險</a></li>
          <li><a href="/economic-insurance/crop-insurance/">農業保險</a></li>
          <li><a href="/economic-insurance/agricultural-subsidy-resources/">農業補助資源</a></li>
          <li><a href="/economic-insurance/worker-occupational-accident-calculator/">職災試算表</a></li>
          <li><a href="/economic-insurance/farmer-worker-retirement-calculator/">退休金試算表</a></li>
        </ul>
      </li>

      <li class="fw-nav-item"><a href="/farmer-study/" class="fw-nav-link">培力課程</a></li>
      <li class="fw-nav-item"><a href="/dignity-farming-initiative/" class="fw-nav-link">倡議</a></li>

      <li class="fw-nav-item has-dropdown">
        <a href="/research-result/" class="fw-nav-link">研究成果 <i class="fas fa-chevron-down fw-caret"></i></a>
        <ul class="fw-dropdown">
          <li><a href="/research-result/research-publication/">研究出版</a></li>
          <li><a href="/research-result/infographic/">圖解懶人包</a></li>
          <li><a href="/research-result/download-zone/">下載專區</a></li>
          <li><a href="/research-result/podcast/">Podcast</a></li>
          <li><a href="/research-result/related-resources/">相關資源</a></li>
        </ul>
      </li>

      <li class="fw-nav-item"><a href="/thenews/" class="fw-nav-link">最新消息</a></li>
    </ul>
    <?php endif; ?>

    <!-- 右側工具區：社群（Customizer 可改） + 搜尋 + CTA -->
    <?php
    $fb = fwdignity_get( 'social_facebook' );
    $ig = fwdignity_get( 'social_instagram' );
    $yt = fwdignity_get( 'social_youtube' );
    $ln = fwdignity_get( 'social_line' );
    ?>
    <div class="fw-nav-right">
      <div class="fw-social">
        <?php if ( $fb ) : ?><a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener" aria-label="Facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
        <?php if ( $ig ) : ?><a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
        <?php if ( $yt ) : ?><a href="<?php echo esc_url( $yt ); ?>" target="_blank" rel="noopener" aria-label="YouTube" title="YouTube"><i class="fab fa-youtube"></i></a><?php endif; ?>
        <?php if ( $ln ) : ?><a href="<?php echo esc_url( $ln ); ?>" target="_blank" rel="noopener" aria-label="LINE" title="LINE"><i class="fab fa-line"></i></a><?php endif; ?>
      </div>
      <button class="fw-search-btn" aria-label="搜尋全站"><i class="fas fa-search"></i></button>
      <a href="/dignity-farming-initiative/" class="fw-cta"><i class="fas fa-bullhorn"></i> 加入倡議</a>
    </div>

    <!-- 手機漢堡 -->
    <button class="fw-hamburger" aria-label="選單" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<?php get_template_part( 'template-parts/ticker' ); ?>
