<?php
/**
 * Template Name: 研究團隊
 * Slug: researchteam
 */
get_header();

$team = array(
  '國立中正大學' => array(
    'icon' => 'fa-landmark',
    'roles' => array(
      '計畫主持人' => array(
        array( 'name'=>'詹盛如', 'role'=>'計畫主持人', 'org'=>'國立中正大學', 'desc'=>'教育學研究所 特聘教授暨副校長', 'img'=>'uploads/2025/12/詹勝如-1-scaled-e1766588165755.jpg' ),
      ),
      '共同主持人' => array(
        array( 'name'=>'管中祥', 'role'=>'共同主持人兼執行長', 'org'=>'國立中正大學', 'desc'=>'傳播學系 教授兼系主任', 'img'=>'uploads/2025/12/管中祥-1-scaled.jpg' ),
        array( 'name'=>'陳毓璟', 'role'=>'共同主持人', 'org'=>'國立中正大學', 'desc'=>'成人及繼續教育 教授', 'img'=>'uploads/2025/12/117640342-scaled.jpg' ),
        array( 'name'=>'劉黃麗娟', 'role'=>'共同主持人', 'org'=>'國立中正大學', 'desc'=>'勞工關係學系 副教授', 'img'=>'uploads/2025/12/劉黃麗娟.png' ),
        array( 'name'=>'李翠萍', 'role'=>'共同主持人', 'org'=>'國立中正大學', 'desc'=>'政治學系 教授兼教務長', 'img'=>'uploads/2025/12/7C471D2A-22A2-473F-9793-71005024EE3F-1.jpg' ),
        array( 'name'=>'蔡崇隆', 'role'=>'協同主持人', 'org'=>'國立中正大學', 'desc'=>'傳播學系 副教授級專技人員', 'img'=>'uploads/2025/12/190_img_10-scaled.webp' ),
        array( 'name'=>'丁文卿', 'role'=>'協同主持人', 'org'=>'國立中正大學', 'desc'=>'勞工關係學系 助理教授', 'img'=>'uploads/2025/12/mczh-tw500x500_large25571_496711490427.webp' ),
        array( 'name'=>'王秀華', 'role'=>'協同主持人', 'org'=>'國立中正大學', 'desc'=>'運動競技學系 教授兼系主任', 'img'=>'uploads/2025/12/mczh-tw700x700_large1396_989789945179.webp' ),
      ),
      '研究團隊' => array(
        array( 'name'=>'陳丹怡', 'role'=>'博士後研究員', 'org'=>'高齡社會勞動與福祉研究中心', 'desc'=>'國立中正大學', 'img'=>'uploads/2025/12/485657-scaled.jpg' ),
        array( 'name'=>'顏孟芬', 'role'=>'博士後研究員', 'org'=>'高齡社會勞動與福祉研究中心', 'desc'=>'國立中正大學', 'img'=>'' ),
        array( 'name'=>'曾柏鴻', 'role'=>'專案助理', 'org'=>'高齡社會勞動與福祉研究中心', 'desc'=>'國立中正大學', 'img'=>'' ),
        array( 'name'=>'曾志桓', 'role'=>'專案助理', 'org'=>'高齡社會勞動與福祉研究中心', 'desc'=>'國立中正大學', 'img'=>'uploads/2025/12/S__10928178.jpg' ),
        array( 'name'=>'陳俊榮', 'role'=>'專任助理', 'org'=>'', 'desc'=>'', 'img'=>'uploads/2025/12/485657-portrait2-20260716.jpg' ),
      ),
    ),
  ),
  '臺大醫院雲林分院' => array(
    'icon' => 'fa-stethoscope',
    'roles' => array(
      '共同主持人' => array(
        array( 'name'=>'李念偉', 'role'=>'共同主持人', 'org'=>'臺大醫院雲林分院', 'desc'=>'環境及職業醫學部 主任', 'img'=>'uploads/2025/12/李廿偉.webp' ),
        array( 'name'=>'陳宗延', 'role'=>'共同主持人', 'org'=>'臺大醫院雲林分院', 'desc'=>'環境及職業醫學部 專任主治醫師', 'img'=>'uploads/2025/12/陳宗延-scaled-e1766846916931.webp' ),
        array( 'name'=>'謝秉儒', 'role'=>'共同主持人', 'org'=>'臺大醫院雲林分院', 'desc'=>'環境及職業醫學部 主治醫師', 'img'=>'uploads/2025/12/謝秉儒-scaled.webp' ),
        array( 'name'=>'魏志阜', 'role'=>'共同主持人', 'org'=>'臺大醫院雲林分院', 'desc'=>'環境及職業醫學部 主治醫師', 'img'=>'uploads/2025/12/魏志阜.webp' ),
      ),
    ),
  ),
  '跨校合作' => array(
    'icon' => 'fa-network-wired',
    'roles' => array(
      '國立中山大學' => array(
        array( 'name'=>'林韋至', 'role'=>'共同主持人', 'org'=>'國立中山大學', 'desc'=>'機械與機電工程學系 特聘教授', 'img'=>'uploads/2025/12/林韋至-1.webp' ),
      ),
    ),
  ),
);
?>

<style>
/* 研究團隊 — 機構為主軸 + 角色分層（2026-04-24 v2 排版） */
.as-inst-section { margin: 64px 0; }
.as-inst-header {
  display: flex; align-items: center; gap: 14px;
  padding: 16px 24px; margin-bottom: 28px;
  border-left: 6px solid var(--fw-color-primary);
  background: var(--fw-color-paper);
  border-radius: 6px;
}
.as-inst-header i {
  font-size: 28px; color: var(--fw-color-primary);
}
.as-inst-header h2 {
  margin: 0; font-size: var(--fw-font-size-h3); font-weight: 700;
  letter-spacing: 0; color: var(--fw-color-ink);
}
.as-role-subsection { margin: 32px 0; padding-left: 28px; border-left: 2px dashed var(--fw-color-line); }
.as-role-label {
  display: inline-block; font-size: var(--fw-font-size-meta); font-weight: 700;
  letter-spacing: 0.08em; color: var(--fw-color-primary);
  padding: 4px 12px; margin-bottom: 16px;
  background: var(--fw-color-paper); border-radius: 4px;
  text-transform: uppercase;
}
@media (max-width: 768px) {
  .as-inst-header h2 { font-size: var(--fw-font-size-h4); }
  .as-role-subsection { padding-left: 16px; }
}
</style>

<div class="fw-page-layout fw-page-layout--full">

<main class="as-main" id="main" role="main">

  <!-- Hero -->
  <section class="as-hero reveal">
    <div class="as-hero-inner">
      <span class="as-hero-badge"><i class="fas fa-users"></i> RESEARCH TEAM</span>
      <h1>研究團隊</h1>
      <p class="as-hero-lead">本計畫由國立中正大學與臺大醫院雲林分院共同主持，結合教育、傳播、勞工關係、政治、職業醫學、機械工程、運動科學等領域專業。</p>
    </div>
  </section>

  <!-- 團隊：機構 → 角色 → 成員 三層 -->
  <?php foreach ( $team as $inst_name => $inst ) : ?>
    <section class="as-inst-section reveal">
      <div class="as-inst-header">
        <i class="fas <?php echo esc_attr( $inst['icon'] ); ?>"></i>
        <h2><?php echo esc_html( $inst_name ); ?></h2>
      </div>

      <?php foreach ( $inst['roles'] as $role_name => $members ) : ?>
        <div class="as-role-subsection">
          <div class="as-role-label"><?php echo esc_html( $role_name ); ?></div>
          <div class="as-team-grid">
            <?php foreach ( $members as $m ) :
              $img = ! empty( $m['img'] ) ? content_url( $m['img'] ) : '';
            ?>
            <div class="as-team-card<?php echo $img ? ' as-team-card--with-photo' : ' as-team-card--text-only'; ?>">
              <?php if ( $img ) : ?>
              <div class="as-team-photo">
                <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $m['name'] ); ?>" loading="lazy">
              </div>
              <?php endif; ?>
              <div class="as-team-info">
                <?php if ( ! empty( $m['org'] ) ) : ?>
                  <span class="org"><?php echo esc_html( $m['org'] ); ?></span>
                <?php endif; ?>
                <h3 class="name"><?php echo esc_html( $m['name'] ); ?></h3>
                <p class="role"><?php echo esc_html( $m['role'] ); ?></p>
                <?php if ( ! empty( $m['desc'] ) ) : ?>
                  <p class="desc"><?php echo esc_html( $m['desc'] ); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  <?php endforeach; ?>

  <!-- 交叉連結 -->
  <section class="as-sec reveal">
    <div class="as-cross">
      <h3>延伸閱讀</h3>
      <div class="as-cross-links">
        <a href="/about/"><i class="fas fa-info-circle"></i>認識尊嚴農業</a>
        <a href="<?php echo esc_url( home_url( '/research-results/' ) ); ?>"><i class="fas fa-circle-nodes"></i>研究成果</a>
      </div>
    </div>
  </section>

</main>

</div>

<?php get_footer();
