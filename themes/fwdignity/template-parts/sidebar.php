<?php
/**
 * Template part: 通用側邊欄(分眾 + 倡議 + 工具箱 + 快速連結)
 * 用法:<?php get_template_part( 'template-parts/sidebar' ); ?>
 *
 * 所有頁面共用,保持一致的使用者進入路徑。
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// 參數預設值:只有首頁不顯示(主區已有大數字卡),其他頁一律顯示
$fw_side_args = wp_parse_args( $args ?? array(), array(
    'show_audience' => false /* DISABLED 2026-05-15 待老師確認 */,
) );
?>
<aside class="fw-aside" aria-label="側邊工具">
  <div class="fw-aside-inner">

    <?php if ( $fw_side_args['show_audience'] ) : ?>
    <!-- 分眾入口(2x2) -->
    <div class="fw-side-card">
      <div class="fw-side-title"><i class="fas fa-users-viewfinder"></i> 請選擇您的身分</div>
      <div class="fw-side-audience-grid">
        <a href="/beginner-farmers/" class="fw-aud a-beginner">
          <div class="fw-aud-icon"><i class="fas fa-seedling"></i></div>
          <div class="fw-aud-name">想務農與新手農友</div>
          <div class="fw-aud-desc">入門資源、風險介紹</div>
        </a>
        <a href="/young-farmers/" class="fw-aud a-young">
          <div class="fw-aud-icon"><i class="fas fa-tractor"></i></div>
          <div class="fw-aud-name">青農與創業</div>
          <div class="fw-aud-desc">技術與保險</div>
        </a>
        <a href="/experienced-farmers/" class="fw-aud a-senior">
          <div class="fw-aud-icon"><i class="fas fa-heart-pulse"></i></div>
          <div class="fw-aud-name">資深農友</div>
          <div class="fw-aud-desc">慢性病、自身權益</div>
        </a>
        <a href="/Public/" class="fw-aud a-public">
          <div class="fw-aud-icon"><i class="fas fa-hand-holding-heart"></i></div>
          <div class="fw-aud-name">一般民眾</div>
          <div class="fw-aud-desc">尊農倡議與支持</div>
        </a>
      </div>
    </div>
    <?php endif; ?>

    <!-- 倡議 CTA -->
    <a href="/dignity-farming-initiative/" class="fw-side-cta-btn-solo">
      <i class="fas fa-bullhorn"></i>
      <span>尊嚴農業倡議</span>
    </a>

    <!-- 工具箱 -->
    <div class="fw-side-card">
      <div class="fw-side-title"><i class="fas fa-toolbox"></i> 實用工具箱</div>
      <ul class="fw-side-tool-list">
        <li><a href="https://www.osha.gov.tw/48110/48363/133456/48395/nodelist" target="_blank" rel="noopener">
          <span class="fw-tool-ic"><i class="fas fa-file-medical-alt"></i></span>
          <span class="fw-tool-txt">職業病認定標準</span>
          <i class="fas fa-download fw-tool-arr"></i>
        </a></li>
        <li><a href="/downloads/checklist.pdf" target="_blank" rel="noopener">
          <span class="fw-tool-ic"><i class="fas fa-heartbeat"></i></span>
          <span class="fw-tool-txt">自我健康檢核表</span>
          <i class="fas fa-download fw-tool-arr"></i>
        </a></li>
        <li><a href="https://www.hpa.gov.tw/Video/RecommendVideo.aspx?nodeid=851&vid=627" target="_blank" rel="noopener">
          <span class="fw-tool-ic"><i class="fas fa-running"></i></span>
          <span class="fw-tool-txt">我家也是健身房</span>
          <i class="fas fa-external-link-alt fw-tool-arr"></i>
        </a></li>
        <li><a href="https://agrstat.moa.gov.tw/sdweb/public/maintenance/Announce.aspx" target="_blank" rel="noopener">
          <span class="fw-tool-ic"><i class="fas fa-chart-pie"></i></span>
          <span class="fw-tool-txt">農業統計數據庫</span>
          <i class="fas fa-arrow-right fw-tool-arr"></i>
        </a></li>
      </ul>
    </div>

    <!-- 快速連結 -->
    <div class="fw-side-card">
      <div class="fw-side-title"><i class="fas fa-compass"></i> 快速連結</div>
      <ul class="fw-side-tool-list">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <span class="fw-tool-ic"><i class="fas fa-home"></i></span>
          <span class="fw-tool-txt">回到首頁</span>
          <i class="fas fa-arrow-right fw-tool-arr"></i>
        </a></li>
        <li><a href="/thenews/">
          <span class="fw-tool-ic"><i class="fas fa-calendar-alt"></i></span>
          <span class="fw-tool-txt">掌握最新動態</span>
          <i class="fas fa-arrow-right fw-tool-arr"></i>
        </a></li>
        <li><a href="/about/">
          <span class="fw-tool-ic"><i class="fas fa-users"></i></span>
          <span class="fw-tool-txt">關於我們</span>
          <i class="fas fa-arrow-right fw-tool-arr"></i>
        </a></li>
      </ul>
    </div>

  </div>
</aside>
