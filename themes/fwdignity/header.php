<?php
/**
 * fwdignity theme — header.php
 * 新版全站導覽
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>document.documentElement.classList.add('js');</script>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'fw-page-v15' ); ?> data-page="<?php echo esc_attr( fwdignity_get_data_page() ); ?>">
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">跳至主內容</a>

<nav class="fw-navbar" id="fw-navbar" aria-label="主導覽">
  <div class="fw-navbar-inner">

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fw-logo" aria-label="尊嚴農業 首頁">
      <img src="<?php echo esc_url( fwdignity_get_logo_url() ); ?>" alt="尊嚴農業" class="fw-logo-img">
      <span class="fw-logo-text">
        <span class="fw-logo-zh">尊嚴農業</span>
        <span class="fw-logo-en">DIGNITY FARMING</span>
      </span>
    </a>

    <ul class="fw-nav-menu" role="menubar">
      <li class="fw-nav-item has-dropdown has-landing-link">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fw-nav-link">首頁</a>
        <button class="fw-nav-link fw-nav-trigger fw-nav-submenu-toggle" type="button" aria-label="開啟首頁子選單" aria-expanded="false"><i class="fas fa-chevron-down fw-caret" aria-hidden="true"></i></button>
        <ul class="fw-dropdown">
          <li><a href="<?php echo esc_url( home_url( '/thenews/' ) ); ?>">全部內容</a></li>
        </ul>
      </li>

      <li class="fw-nav-item has-dropdown has-landing-link">
        <a href="<?php echo esc_url( home_url( '/field-learning/' ) ); ?>" class="fw-nav-link">田野誌</a>
        <button class="fw-nav-link fw-nav-trigger fw-nav-submenu-toggle" type="button" aria-label="開啟田野誌子選單" aria-expanded="false"><i class="fas fa-chevron-down fw-caret" aria-hidden="true"></i></button>
        <ul class="fw-dropdown">
          <li><a href="<?php echo esc_url( home_url( '/field-notes/' ) ); ?>">田野紀錄</a></li>
          <li><a href="<?php echo esc_url( home_url( '/people-who-farm/' ) ); ?>">做農的人</a></li>
        </ul>
      </li>

      <li class="fw-nav-item"><a href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>" class="fw-nav-link">聽見田裡的聲音</a></li>
      <li class="fw-nav-item"><a href="<?php echo esc_url( home_url( '/farming-guide/' ) ); ?>" class="fw-nav-link">田間知識與資源</a></li>

      <li class="fw-nav-item has-dropdown has-landing-link">
        <a href="<?php echo esc_url( home_url( '/research-results/' ) ); ?>" class="fw-nav-link">研究與行動</a>
        <button class="fw-nav-link fw-nav-trigger fw-nav-submenu-toggle" type="button" aria-label="開啟研究與行動子選單" aria-expanded="false"><i class="fas fa-chevron-down fw-caret" aria-hidden="true"></i></button>
        <ul class="fw-dropdown">
          <li><a href="<?php echo esc_url( home_url( '/research-results/research-publications/' ) ); ?>">研究出版</a></li>
          <li><a href="<?php echo esc_url( home_url( '/research-results/related-resources/' ) ); ?>">相關資源</a></li>
        </ul>
      </li>

      <li class="fw-nav-item has-dropdown has-landing-link">
        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="fw-nav-link">認識尊嚴農業</a>
        <button class="fw-nav-link fw-nav-trigger fw-nav-submenu-toggle" type="button" aria-label="開啟認識尊嚴農業子選單" aria-expanded="false"><i class="fas fa-chevron-down fw-caret" aria-hidden="true"></i></button>
        <ul class="fw-dropdown fw-dropdown-align-right">
          <li><a href="<?php echo esc_url( home_url( '/about/researchteam/' ) ); ?>">研究團隊</a></li>
        </ul>
      </li>
    </ul>

    <div class="fw-nav-right" aria-label="社群媒體">
      <div class="fw-social">
        <?php
        $header_socials = array(
            'facebook'  => array( 'fa-facebook-f', 'Facebook' ),
            'instagram' => array( 'fa-instagram', 'Instagram' ),
        );
        foreach ( $header_socials as $key => $social ) :
            $url = fwdignity_get_social_url( $key );
            if ( ! $url ) {
                continue;
            }
        ?>
        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $social[1] ); ?>"><i class="fab <?php echo esc_attr( $social[0] ); ?>" aria-hidden="true"></i></a>
        <?php endforeach; ?>
      </div>
    </div>

    <button class="fw-hamburger" aria-label="選單" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>
