<?php
/**
 * Template Name: 行事曆
 * Slug: day
 */
get_header(); ?>

<div class="fw-page-layout">

<main class="as-main" id="main" role="main">

  <!-- Hero -->
  <section class="as-hero reveal">
    <div class="as-hero-inner">
      <span class="as-hero-badge"><i class="fas fa-calendar-days"></i> CALENDAR</span>
      <h1 data-hook-title>計畫<span class="accent">行事曆</span></h1>
      <p class="as-hero-lead">本計畫之活動、會議、田野行程、講座與培力課程，公開顯示於此行事曆。歡迎有興趣的夥伴查看並聯繫我們參與。</p>
    </div>
  </section>

  <!-- Calendar embed -->
  <section class="as-sec reveal">
    <div class="as-sec-head">
      <span class="kicker">Schedule · 行程總覽</span>
      <h2>活動<span class="accent">行程表</span></h2>
      <p>資料同步自計畫共用 Google 行事曆，即時更新。</p>
    </div>
    <div class="as-cal-wrap">
      <iframe
        src="https://calendar.google.com/calendar/embed?src=fwdignity%40alum.ccu.edu.tw&ctz=Asia%2FTaipei&showTitle=0&showPrint=0&showTabs=1&showCalendars=0&showTz=0&mode=MONTH"
        loading="lazy"
        title="尊嚴農業計畫行事曆"
        frameborder="0"
        scrolling="no"></iframe>
      <div class="as-cal-note">
        <strong><i class="fas fa-info-circle"></i> 活動參與說明：</strong>
        本行事曆公開顯示已確認之活動行程。若欲報名參加工作坊、培力課程或田野活動，請先至<a href="/contact/" style="color:var(--as-main);font-weight:800;">聯繫我們</a>確認名額與相關事宜。
      </div>
    </div>
  </section>

  <!-- 活動類型說明 -->
  <section class="as-sec reveal">
    <div class="as-sec-head">
      <span class="kicker">Categories · 活動類別</span>
      <h2>行事曆<span class="accent">內容</span></h2>
      <p>本計畫日常活動主要涵蓋以下四類。</p>
    </div>
    <div class="as-team-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));">
      <div class="as-team-card" style="text-align:left;">
        <div class="as-team-info" style="align-items:flex-start;padding:22px 20px;">
          <span class="org" style="background:rgba(21,128,61,0.1);color:var(--as-main);"><i class="fas fa-chalkboard-user"></i> 培力課程</span>
          <p class="name" style="font-size:16px;margin-top:12px;">衛教與防護工作坊</p>
          <p class="desc">針對雲嘉農業工作者之健康促進、安全防護主題培訓。</p>
        </div>
      </div>
      <div class="as-team-card" style="text-align:left;">
        <div class="as-team-info" style="align-items:flex-start;padding:22px 20px;">
          <span class="org" style="background:rgba(220,38,38,0.1);color:#DC2626;"><i class="fas fa-users"></i> 研究會議</span>
          <p class="name" style="font-size:16px;margin-top:12px;">團隊例會與季會</p>
          <p class="desc">計畫內部進度檢討、HISP 季會發表、跨單位協作會議。</p>
        </div>
      </div>
      <div class="as-team-card" style="text-align:left;">
        <div class="as-team-info" style="align-items:flex-start;padding:22px 20px;">
          <span class="org" style="background:rgba(245,158,11,0.12);color:var(--as-deep2);"><i class="fas fa-tractor"></i> 田野行程</span>
          <p class="name" style="font-size:16px;margin-top:12px;">訪談、調查、觀察</p>
          <p class="desc">計畫研究員赴雲嘉各鄉進行田野訪談與實地調查。</p>
        </div>
      </div>
      <div class="as-team-card" style="text-align:left;">
        <div class="as-team-info" style="align-items:flex-start;padding:22px 20px;">
          <span class="org" style="background:rgba(124,58,237,0.1);color:#7C3AED;"><i class="fas fa-microphone"></i> 公開講座</span>
          <p class="name" style="font-size:16px;margin-top:12px;">倡議論壇與媒體活動</p>
          <p class="desc">計畫成果公開發表、政策倡議論壇、社會對話活動。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 交叉連結 -->
  <section class="as-sec reveal">
    <div class="as-cross">
      <h3>延伸閱讀</h3>
      <div class="as-cross-links">
        <a href="/about/"><i class="fas fa-info-circle"></i>關於我們</a>
        <a href="/researchteam/"><i class="fas fa-users"></i>研究團隊</a>
        <a href="/contact/"><i class="fas fa-envelope"></i>聯繫我們</a>
        <a href="/farmer-study/"><i class="fas fa-chalkboard-user"></i>培力課程</a>
      </div>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer();
