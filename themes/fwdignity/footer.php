<?php
/**
 * fwdignity theme — footer.php v2（Scope B · Customizer 動態化）
 * 所有單位/地址/聯絡/社群/連結都可在 WP 後台改:
 *   - 外觀 → 自訂 → 尊嚴農業 · 單位資訊 / 聯絡資訊 / 社群媒體
 *   - 外觀 → 選單 → 指定「頁尾 · 快速連結」「頁尾 · 友善連結」
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
        <p class="fw-footer-desc">
          <strong><?php echo esc_html( fwdignity_get( 'tagline', '尊嚴農業：共構雲嘉農業工作者職業健康的社會安全網' ) ); ?></strong>
        </p>
        <ul class="fw-footer-units">
          <?php
          $units = array(
            'guidance_unit' => '指導單位',
            'host_unit'     => '計畫主持單位',
            'partners'      => '合作夥伴',
          );
          foreach ( $units as $key => $label ) :
            $val = fwdignity_get( $key );
            if ( ! $val ) continue;
          ?>
          <li><span class="fw-footer-unit-label"><?php echo esc_html( $label ); ?></span><?php echo wp_kses_post( $val ); ?></li>
          <?php endforeach; ?>
        </ul>
        <ul class="fw-footer-contact">
          <?php $addr = fwdignity_get( 'address' ); if ( $addr ) : ?>
          <li><i class="fas fa-location-dot"></i><span><?php echo wp_kses_post( nl2br( esc_html( $addr ) ) ); ?></span></li>
          <?php endif; ?>
          <?php $phone = fwdignity_get( 'phone' ); if ( $phone ) : ?>
          <li><i class="fas fa-phone"></i><span><?php echo esc_html( $phone ); ?></span></li>
          <?php endif; ?>
          <?php $email = fwdignity_get( 'email' ); if ( $email ) : ?>
          <li><i class="fas fa-envelope"></i><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
          <?php endif; ?>
        </ul>
        <div class="fw-footer-social">
          <?php
          $socials = array(
            'facebook'  => array( 'fa-facebook-f', 'Facebook' ),
            'instagram' => array( 'fa-instagram', 'Instagram' ),
            'youtube'   => array( 'fa-youtube', 'YouTube' ),
            'line'      => array( 'fa-line', 'LINE' ),
          );
          foreach ( $socials as $key => $v ) :
            $url = fwdignity_get( 'social_' . $key );
            if ( ! $url ) continue;
          ?>
          <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $v[1] ); ?>"><i class="fab <?php echo esc_attr( $v[0] ); ?>"></i></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="fw-footer-col">
        <h4>熱門關鍵字</h4>
        <div class="fw-footer-tags">
          <a href="/healthgood/subpage-heat/">#熱傷害</a>
          <a href="/healthgood/subpage-msd/">#肌肉骨骼</a>
          <a href="/healthgood/subpage-pesticide/">#農藥安全</a>
          <a href="/dignity-farming-initiative/">#民雄</a>
          <a href="/economic-insurance/crop-insurance/">#職災保險</a>
          <a href="/young-farmers/">#青農</a>
          <a href="/farmer-study/">#食農教育</a>
          <a href="/experienced-farmers/">#高齡農民</a>
          <a href="/occupational-safety/">#職業安全</a>
          <a href="/healthgood/subpage-mental/">#心理健康</a>
          <a href="/occupational-safety/labor-saving-machinery/">#省工農機</a>
          <a href="/research-result/podcast/">#Podcast</a>
        </div>
      </div>

      <div class="fw-footer-col">
        <h4>友善連結</h4>
        <?php if ( has_nav_menu( 'footer_friendly' ) ) : ?>
          <?php wp_nav_menu( array(
            'theme_location' => 'footer_friendly',
            'menu_class'     => 'fw-footer-links',
            'container'      => false,
            'fallback_cb'    => false,
            'link_before'    => '',
            'link_after'     => '<i class="fas fa-external-link-alt"></i>',
          ) ); ?>
        <?php else : ?>
        <ul class="fw-footer-links">
          <li><a href="https://www.moa.gov.tw/" target="_blank" rel="noopener">農業部<i class="fas fa-external-link-alt"></i></a></li>
          <li><a href="https://www.osha.gov.tw/" target="_blank" rel="noopener">職安署<i class="fas fa-external-link-alt"></i></a></li>
          <li><a href="https://www.ccu.edu.tw/" target="_blank" rel="noopener">中正大學<i class="fas fa-external-link-alt"></i></a></li>
          <li><a href="https://www.ylh.gov.tw/" target="_blank" rel="noopener">臺大雲林分院<i class="fas fa-external-link-alt"></i></a></li>
          <li><a href="https://www.hisp.ntu.edu.tw/" target="_blank" rel="noopener">新作坊<i class="fas fa-external-link-alt"></i></a></li>
          <li><a href="https://www.bli.gov.tw/" target="_blank" rel="noopener">勞保局<i class="fas fa-external-link-alt"></i></a></li>
          <li><a href="https://www.ilosh.gov.tw/" target="_blank" rel="noopener">勞動研究所<i class="fas fa-external-link-alt"></i></a></li>
        </ul>
        <?php endif; ?>
      </div>

      <div class="fw-footer-col">
        <h4>快速連結</h4>
        <?php if ( has_nav_menu( 'footer_quick' ) ) : ?>
          <?php wp_nav_menu( array(
            'theme_location' => 'footer_quick',
            'menu_class'     => 'fw-footer-links',
            'container'      => false,
            'fallback_cb'    => false,
          ) ); ?>
        <?php else : ?>
        <ul class="fw-footer-links">
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-home"></i>回到首頁</a></li>
          <li><a href="/thenews/"><i class="fas fa-calendar-alt"></i>掌握最新動態</a></li>
          <li><a href="/about/"><i class="fas fa-users"></i>關於我們</a></li>
          <li><a href="/about/contact/"><i class="fas fa-envelope"></i>聯繫我們</a></li>
          <li><a href="/research-result/"><i class="fas fa-flask"></i>研究成果</a></li>
          <li><a href="/dignity-farming-initiative/"><i class="fas fa-bullhorn"></i>尊嚴倡議</a></li>
        </ul>
        <?php endif; ?>
      </div>

    </div>

    <div class="fw-footer-bottom">
      <div>© <?php echo date( 'Y' ); ?> 尊嚴農業 · Farmer Worker Dignity · 國立中正大學 × 臺大醫院雲林分院</div>
      <div>Design System MASTER FINAL · 2026-04-20</div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
