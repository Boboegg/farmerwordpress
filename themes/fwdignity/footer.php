<?php
/**
 * fwdignity theme — 新版全站頁尾
 */
?>
<footer class="fw-footer" role="contentinfo">
  <div class="fw-footer-inner">
    <div class="fw-footer-grid">
      <div class="fw-footer-col fw-footer-brand">
        <div class="fw-footer-logo">
          <img src="<?php echo esc_url( fwdignity_get_logo_url() ); ?>" alt="尊嚴農業">
          <span>尊嚴農業</span>
        </div>
        <p class="fw-footer-desc"><?php echo esc_html( fwdignity_get( 'tagline', '尊嚴農業：共構雲嘉農業工作者職業健康的社會安全網' ) ); ?></p>
        <ul class="fw-footer-units">
          <?php
          $units = array(
              'guidance_unit' => '指導單位',
              'host_unit'     => '計畫主持單位',
              'partners'      => '合作夥伴',
          );
          foreach ( $units as $key => $label ) :
              $value = fwdignity_get( $key );
              if ( ! $value ) {
                  continue;
              }
          ?>
          <li><span class="fw-footer-unit-label"><?php echo esc_html( $label ); ?></span><span class="fw-footer-unit-value"><?php echo wp_kses_post( $value ); ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="fw-footer-col" id="footer-contact">
        <h2>聯繫與查找</h2>
        <ul class="fw-footer-contact">
          <?php $address = fwdignity_get( 'address' ); if ( $address ) : ?>
          <li><i class="fas fa-location-dot" aria-hidden="true"></i><span><?php echo wp_kses_post( nl2br( esc_html( $address ) ) ); ?></span></li>
          <?php endif; ?>
          <?php $phone = fwdignity_get( 'phone' ); if ( $phone ) : ?>
          <li><i class="fas fa-phone" aria-hidden="true"></i><span><?php echo esc_html( $phone ); ?></span></li>
          <?php endif; ?>
          <?php $email = fwdignity_get( 'email' ); if ( $email ) : ?>
          <li><i class="fas fa-envelope" aria-hidden="true"></i><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
          <?php endif; ?>
        </ul>
        <form class="fw-footer-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
          <label for="fw-footer-search-input">搜尋網站</label>
          <div>
            <input id="fw-footer-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>">
            <button type="submit" aria-label="送出搜尋"><i class="fas fa-search" aria-hidden="true"></i></button>
          </div>
        </form>
        <div class="fw-footer-social">
          <?php
          $socials = array(
              'facebook'  => array( 'fa-facebook-f', 'Facebook' ),
              'instagram' => array( 'fa-instagram', 'Instagram' ),
              'youtube'   => array( 'fa-youtube', 'YouTube' ),
              'line'      => array( 'fa-line', 'LINE' ),
          );
          foreach ( $socials as $key => $social ) :
              $url = fwdignity_get_social_url( $key );
              if ( ! $url ) {
                  continue;
              }
          ?>
          <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $social[1] ); ?>"><i class="fab <?php echo esc_attr( $social[0] ); ?>" aria-hidden="true"></i></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="fw-footer-bottom">
      <div>© <?php echo esc_html( date( 'Y' ) ); ?> 尊嚴農業 · 國立中正大學 × 臺大醫院雲林分院</div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
