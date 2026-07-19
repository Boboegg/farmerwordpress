<?php
/**
 * Template Name: 聯繫我們
 * Slug: contact
 */
get_header(); ?>

<div class="fw-page-layout fw-page-layout--full">

<main class="as-main" id="main" role="main">

  <!-- Hero -->
  <section class="as-hero reveal">
    <div class="as-hero-inner">
      <span class="as-hero-badge"><i class="fas fa-envelope"></i> CONTACT US</span>
      <h1 data-hook-title>聯繫<span class="accent">研究團隊</span></h1>
      <p class="as-hero-lead">歡迎農會、社區組織、媒體、學術夥伴與關心農業職業健康的公民聯繫我們。您的每一個訊息都是推動農業工作者社會安全網的重要力量。</p>
    </div>
  </section>

  <!-- Contact grid：資訊 + 留言表單 -->
  <section class="as-sec reveal">
    <div class="as-contact-grid">

      <!-- 聯絡資訊 -->
      <div class="as-contact-info">
        <h3>尊嚴農業研究團隊</h3>
        <p class="subtitle">Dignity Farmer Worker Research Team</p>
        <div class="as-contact-list">
          <a href="tel:+88652720411,22316" class="as-contact-item">
            <div class="icon"><i class="fas fa-phone"></i></div>
            <div>
              <p class="label">電話 Phone</p>
              <p class="val">05-2720411 轉分機 22316</p>
            </div>
          </a>
          <a href="mailto:fwdignity@alum.ccu.edu.tw" class="as-contact-item">
            <div class="icon"><i class="fas fa-envelope"></i></div>
            <div>
              <p class="label">電子郵件 Email</p>
              <p class="val">fwdignity@alum.ccu.edu.tw</p>
            </div>
          </a>
          <div class="as-contact-item" style="cursor:default;">
            <div class="icon"><i class="fas fa-location-dot"></i></div>
            <div>
              <p class="label">地址 Address</p>
              <p class="val">621301 嘉義縣民雄鄉大學路一段 168 號<br>國立中正大學 社科院一館 323 研究室</p>
            </div>
          </div>
        </div>
      </div>

      <!-- 留言表單 -->
      <div class="as-contact-form">
        <h3>留言給我們</h3>
        <p class="subtitle">Leave a Message</p>
        <p class="note">若您有合作提案、媒體採訪、受訪邀請、題材建議或計畫相關諮詢，請於下方表單留言。我們會於收到後盡快回覆。</p>
        <?php echo do_shortcode( '[sureforms id="959"]' ); ?>
      </div>

    </div>
  </section>

  <!-- Google Map -->
  <section class="as-sec reveal">
    <div class="as-sec-head">
      <span class="kicker">Location · 研究室位置</span>
      <h2>國立中正大學<span class="accent">社科院一館</span></h2>
      <p>歡迎預約來訪，請先透過電話或 Email 聯繫。</p>
    </div>
    <div class="as-map-card">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3642.5!2d120.4615!3d23.5565!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z5ZyL56uL5Lit5q2j5aSn5a24IOekvuenkeWtuOmZouS4gOmkqA!5e0!3m2!1szh-TW!2stw!4v1700000000000!5m2!1szh-TW!2stw"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen></iframe>
    </div>
  </section>

  <!-- 交叉連結 -->
  <section class="as-sec reveal">
    <div class="as-cross">
      <h3>延伸閱讀</h3>
      <div class="as-cross-links">
        <a href="/about/"><i class="fas fa-info-circle"></i>關於我們</a>
        <a href="<?php echo esc_url( home_url( '/about/researchteam/' ) ); ?>"><i class="fas fa-users"></i>研究團隊</a>
        <a href="/dignity-farming-initiative/"><i class="fas fa-bullhorn"></i>加入倡議</a>
      </div>
    </div>
  </section>

</main>

</div>

<?php get_footer();
