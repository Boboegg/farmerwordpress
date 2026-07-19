<?php
/**
 * 田間知識與資源：學習與支持。
 */

$platforms = array(
    array( 'title' => '農民學院', 'org' => '農業部', 'url' => 'https://academy.moa.gov.tw/', 'copy' => '農業專業訓練、數位課程與農場見習資訊。' ),
    array( 'title' => '農業數位學堂', 'org' => '農業科技研究院', 'url' => 'https://agdigischool.atri.org.tw/', 'copy' => '農業科技、數位應用與線上學習資源。' ),
    array( 'title' => '職業安全衛生數位學習平台', 'org' => '勞動部職業安全衛生署', 'url' => 'https://isafe.osha.gov.tw/mooc/index.php', 'copy' => '職業安全衛生教育訓練與免費數位課程。' ),
    array( 'title' => '農業改良場數位學習專區', 'org' => '花蓮區農業改良場', 'url' => 'https://www.hdares.gov.tw/ws.php?id=1484', 'copy' => '作物栽培、病蟲害管理與在地農業技術影片。' ),
);

$resources = array(
    array( 'title' => '人因性危害預防計畫指引', 'source' => '勞動部職業安全衛生署', 'url' => 'https://www.osha.gov.tw/media/kp2pvhn1/%E4%BA%BA%E5%9B%A0%E6%80%A7%E5%8D%B1%E5%AE%B3%E9%A0%90%E9%98%B2%E8%A8%88%E7%95%AB%E6%8C%87%E5%BC%95.pdf?mediaDL=true' ),
    array( 'title' => '職場人因性危害預防', 'source' => '職業災害預防及重建中心', 'url' => 'https://www.coapre.org.tw/files/20240528140047760.pdf' ),
    array( 'title' => '職場人因危害預防優良案例作品集', 'source' => '勞動部職業安全衛生署', 'url' => 'https://www.osha.gov.tw/media/pocp0fbr/%E8%81%B7%E5%A0%B4%E4%BA%BA%E5%9B%A0%E6%80%A7%E5%8D%B1%E5%AE%B3%E9%A0%90%E9%98%B2%E5%84%AA%E8%89%AF%E6%A1%88%E4%BE%8B%E4%BD%9C%E5%93%81%E9%9B%86.pdf' ),
    array( 'title' => '農民職業災害保險資訊', 'source' => '勞動部勞工保險局', 'url' => 'https://www.bli.gov.tw/' ),
);
?>

<section class="fg-guide__learning-support" id="fg-learning-support" aria-labelledby="fg-learning-support-title">
  <div class="fg-guide__section-heading">
    <p>02</p>
    <h2 id="fg-learning-support-title">學習與支持</h2>
  </div>
  <p class="fg-guide__learning-lead">影音、近期課程、官方平台與教材集中在此，與農學堂的活動成果分開整理。</p>

  <section class="training-section training-media" id="training-videos" aria-labelledby="training-videos-title">
    <div class="training-section-head">
      <div><p class="training-eyebrow">Official Videos</p><h2 id="training-videos-title">影音學習</h2></div>
      <p>自動讀取農業部官方 YouTube 頻道；內容由來源平台更新。</p>
    </div>
    <?php echo do_shortcode( '[farmer_videos count="6"]' ); ?>
  </section>

  <section class="training-section" id="training-courses" aria-labelledby="training-courses-title">
    <div class="training-section-head">
      <div><p class="training-eyebrow">External Courses</p><h2 id="training-courses-title">農民學院近期課程</h2></div>
      <p>自動讀取農民學院公開課程資訊；報名與異動以來源網站公告為準。</p>
    </div>
    <?php echo do_shortcode( '[farmer_courses count="6"]' ); ?>
  </section>

  <section class="training-platform-band" aria-labelledby="training-platform-title">
    <div class="training-section training-platform-inner">
      <div class="training-section-head"><div><p class="training-eyebrow">Learning Platforms</p><h2 id="training-platform-title">官方學習平台</h2></div></div>
      <div class="training-platform-grid">
        <?php foreach ( $platforms as $platform ) : ?>
          <a href="<?php echo esc_url( $platform['url'] ); ?>" target="_blank" rel="noopener"><span><?php echo esc_html( $platform['org'] ); ?></span><h3><?php echo esc_html( $platform['title'] ); ?></h3><p><?php echo esc_html( $platform['copy'] ); ?></p><strong>前往平台 →</strong></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="training-section" id="training-resources" aria-labelledby="training-resources-title">
    <div class="training-section-head">
      <div><p class="training-eyebrow">Materials</p><h2 id="training-resources-title">教材與延伸資源</h2></div>
      <p>連結至原發布單位，方便確認版本與最新資訊。</p>
    </div>
    <div class="training-resource-list">
      <?php foreach ( $resources as $resource ) : ?>
        <a href="<?php echo esc_url( $resource['url'] ); ?>" target="_blank" rel="noopener"><span><?php echo esc_html( $resource['source'] ); ?></span><strong><?php echo esc_html( $resource['title'] ); ?></strong><b aria-hidden="true">↗</b></a>
      <?php endforeach; ?>
    </div>
  </section>
</section>
