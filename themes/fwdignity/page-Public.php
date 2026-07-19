<?php
/**
 * Template Name: 一般大眾
 * Slug: Public
 * fwdignity theme — page-Public.php
 * 一般民眾 分眾入口頁(slug=Public)· AMBER
 *
 * 🔴 紅線:文字/數字/引用來源/連結/引言 1:1 復用原頁。
 */
get_header();
?>

<div class="fw-page-layout">

<main class="bf-main" id="main" role="main">

  <!-- ① Hero -->
  <section class="bf-hero">
    <div class="bf-hero-deco" aria-hidden="true">
      <i class="fas fa-wheat-awn bf-deco-1"></i>
      <i class="fas fa-apple-whole bf-deco-2"></i>
      <i class="fas fa-hand-holding-heart bf-deco-3"></i>
    </div>
    <div class="bf-hero-inner">
      <span class="bf-hero-badge"><i class="fas fa-seedling"></i> 一般民眾</span>
      <h1 class="bf-hero-title" data-hook-title>你在超市拿起的平價蔬果，<br><span class="accent">種它的人正忍著腰痛。</span></h1>
      <p class="bf-hero-tagline">每一口食物背後，都有一雙受傷的手。了解農民的真實處境，是改變的第一步。</p>
      <div class="bf-hero-intro">
        <p>依主計總處農家戶口及經濟調查，台灣農民平均年齡約 64 歲、務農人口結構快速老化（行政院主計總處，2023）。他們每天頂著烈日、彎著腰，用身體換你餐桌上的食物——卻是所有職業中獲得最少勞動保護的族群。沒有勞基法保障、沒有雇主替他們繳保費，農民職業災害保險（以下簡稱農職保）自 2024 年 11 月保障再升級（勞動部勞工保險局，2024）。「尊嚴農業」計畫希望你知道這些事，因為改變需要整個社會的認同。</p>
      </div>
    </div>
  </section>

  <!-- ② Stats + Quote -->
  <section class="bf-sec bf-stats reveal">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-chart-bar"></i> DATA · 你可能不知道的數字</span>
      <h2 class="bf-sec-title">你可能<span class="accent">不知道的數字</span></h2>
    </div>
    <div class="bf-stats-grid">
      <div class="bf-stat-card">
        <div class="bf-stat-number" data-count="18.6">18.6</div>
        <p class="bf-stat-label">美國農業職災致死率（每 10 萬名全時當量工作者），是全產業平均（3.7）的 5 倍（National Institute for Occupational Safety and Health [NIOSH], 2024；U.S. Bureau of Labor Statistics [BLS], 2023）</p>
        <p class="bf-stat-source">資料來源：NIOSH, 2024；BLS, 2023</p>
      </div>
      <div class="bf-stat-card">
        <div class="bf-stat-number" data-count="3.85">3.85<span class="bf-stat-unit">億</span></div>
        <p class="bf-stat-label">全球每年非故意急性農藥中毒案例，約 1.1 萬人致死（Boedeker et al., 2020）</p>
        <p class="bf-stat-source">資料來源：Boedeker et al., 2020</p>
      </div>
      <div class="bf-stat-card">
        <div class="bf-stat-number" data-count="2024">2024</div>
        <p class="bf-stat-label">農職保自 2018 年 11 月試辦、2024 年 11 月保障升級（就醫津貼門診 100 元／住院 1,200 元每日）（勞動部勞工保險局，2024）</p>
        <p class="bf-stat-source">資料來源：勞動部勞工保險局，2024</p>
      </div>
    </div>

    <div class="bf-quote-card">
      <blockquote>
        「農民沒有慣老闆，但他們有兩個更殘酷的超級老闆:天氣和市場。你無法跟天氣談加班費，也無法向市場申請職災認定。」
      </blockquote>
      <cite>— 陳宗延 醫師，臺大醫院雲林分院職業醫學科（計畫訪談紀錄，2024）</cite>
    </div>
  </section>

  <!-- ③ Scenarios -->
  <section class="bf-sec bf-scenarios">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-eye"></i> ACTION · 看見、聽見、了解、行動</span>
      <h2 class="bf-sec-title">看見、聽見、<span class="accent">了解、行動</span></h2>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#EA580C; --accent-light:#FFEDD5;">
      <div class="bf-scenario-num">01</div>
      <div class="bf-scenario-icon"><i class="fas fa-images"></i></div>
      <div class="bf-scenario-body">
        <h3>看見農民的一天</h3>
        <p>圖文資訊帶你走進農田現場:清晨 5 點的採收、正午 38°C 的田間勞動、農藥噴灑時缺乏防護的日常。每張圖卡背後都有真實數據和國際研究佐證。<strong>當你看見他們的一天，你對「平價蔬果」的理解會徹底改變。</strong></p>
        <a href="/research-result/infographic/" class="bf-scenario-link">看圖文資訊 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#7C3AED; --accent-light:#EDE9FE;">
      <div class="bf-scenario-num">02</div>
      <div class="bf-scenario-icon"><i class="fas fa-podcast"></i></div>
      <div class="bf-scenario-body">
        <h3>聽見田裡的聲音</h3>
        <p>《我的民雄朋友們》Podcast 用第一人稱紀錄農民的真實生活——蓮藕農的斜槓轉全職故事、女農一邊帶小孩一邊務農的拉扯、噴農藥時的「能不戴就不戴」心態。<strong>這些聲音，是政策白皮書裡聽不到的。</strong></p>
        <a href="/research-result/podcast/" class="bf-scenario-link">聽 Podcast <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#1E40AF; --accent-light:#DBEAFE;">
      <div class="bf-scenario-num">03</div>
      <div class="bf-scenario-icon"><i class="fas fa-bullhorn"></i></div>
      <div class="bf-scenario-body">
        <h3>了解倡議行動:從研究到政策</h3>
        <p>「尊嚴農業」不只是學術研究，更推動實際制度改變——跨國比較日韓澳的農民職業安全體系、召開焦點團體聽取利害關係人的意見、記錄曾女士等農友成功申請職傷給付的案例。<strong>每一個改變，都從理解和發聲開始。</strong></p>
        <a href="/dignity-farming-initiative/" class="bf-scenario-link">了解倡議內容 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#16A34A; --accent-light:#DCFCE7;">
      <div class="bf-scenario-num">04</div>
      <div class="bf-scenario-icon"><i class="fas fa-hand-holding-heart"></i></div>
      <div class="bf-scenario-body">
        <h3>你可以做的事</h3>
        <p>你不需要會種田，也能支持農民的勞動尊嚴。分享這裡的資訊讓更多人知道、支持友善食材通路、關注農業政策議題——<strong>當社會開始在乎，農民的處境才會改變。把這一頁傳給一個朋友，就是行動的開始。</strong></p>
        <a href="/research-result/" class="bf-scenario-link">看更多研究成果 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </section>

  <!-- ④ About -->
  <section class="bf-sec bf-about reveal">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-hand-holding-heart"></i> ABOUT · 計畫介紹</span>
      <h2 class="bf-sec-title">認識<span class="accent">尊嚴農業計畫</span></h2>
    </div>
    <div class="bf-about-card">
      <p class="bf-about-desc">
        「尊嚴農業:共構雲嘉農業工作者職業健康社會安全網」由<strong>國立中正大學</strong>與<strong>臺大醫院雲林分院</strong>共同推動，以嘉義縣<strong>民雄鄉</strong>為實踐場域。計畫的核心理念是:<strong>農業不只撐起糧食安全，更應該撐起農民的生活尊嚴</strong>。我們透過社會倡議和多方利害關係人對話，讓農民的聲音被更多人聽見。
      </p>
      <div class="bf-about-pillars">
        <div class="bf-pillar"><i class="fas fa-microscope"></i><span>基礎調查</span></div>
        <div class="bf-pillar"><i class="fas fa-scale-balanced"></i><span>制度研究</span></div>
        <div class="bf-pillar"><i class="fas fa-chalkboard-user"></i><span>健康教育</span></div>
        <div class="bf-pillar"><i class="fas fa-bullhorn"></i><span>社會倡議</span></div>
      </div>
      <a href="/dignity-farming-initiative/" class="bf-about-link">了解更多倡議內容 <i class="fas fa-arrow-right"></i></a>
    </div>
  </section>

  <!-- ⑤ Resources -->
  <section class="bf-sec bf-resources">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-bookmark"></i> RESOURCES · 相關資源</span>
      <h2 class="bf-sec-title">相關<span class="accent">資源</span></h2>
    </div>
    <div class="bf-cta">
      <a class="bf-cta-btn bf-cta-primary" href="/research-result/infographic/" data-magnetic>
        <i class="fas fa-images"></i> 看見農民的世界
      </a>
      <a class="bf-cta-btn bf-cta-secondary" href="/research-result/podcast/">
        <i class="fas fa-headphones"></i> 聽 Podcast
      </a>
    </div>
  </section>

  <!-- ⑥ Emergency -->
  <section class="bf-sec bf-emergency">
    <div class="bf-emergency-head">
      <h2><i class="fas fa-phone-volume"></i> 你身邊有農友需要幫忙嗎?</h2>
      <p>如果你認識正在務農的朋友或家人，請把這些號碼傳給他們。有時候，一通電話就能改變結局。</p>
    </div>
    <div class="bf-emergency-grid">
      <a class="bf-emergency-item" href="tel:119"><strong>緊急救護</strong><span class="bf-emergency-num">119</span></a>
      <a class="bf-emergency-item" href="tel:1925"><strong>安心專線</strong><span class="bf-emergency-num">1925</span></a>
      <a class="bf-emergency-item" href="tel:113"><strong>保護專線</strong><span class="bf-emergency-num">113</span></a>
    </div>
  </section>

  <!-- ⑦ Explore -->
  <section class="bf-sec bf-explore">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-link"></i> EXPLORE · 探索更多</span>
      <h2 class="bf-sec-title">探索<span class="accent">更多</span></h2>
    </div>
    <div class="bf-explore-grid">
      <a href="/healthgood/" class="bf-explore-card c-health"><i class="fas fa-heart-pulse"></i><span>健康促進</span></a>
      <a href="/occupational-safety/" class="bf-explore-card c-safety"><i class="fas fa-helmet-safety"></i><span>職業安全</span></a>
      <a href="/economic-insurance/" class="bf-explore-card c-economy"><i class="fas fa-shield-cat"></i><span>經濟保險</span></a>
      <a href="/thenews/" class="bf-explore-card c-news"><i class="fas fa-newspaper"></i><span>最新消息</span></a>
    </div>
  </section>

  <!-- ⑧ References 全展開（APA 7） -->
  <style>
    .bf-refs-list.apa7 { list-style: none; padding: 0; }
    .bf-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .bf-refs-list.apa7 li em { font-style: italic; }
    .bf-refs-list.apa7 li a { word-break: break-all; }
    .bf-refs-subhead { margin-top: 1.2em; font-size: 1.05em; }
    .bf-refs-note { font-size: .92em; color: #555; }
  </style>
  <section class="bf-sec bf-refs">
    <div class="bf-refs-open">
      <div class="bf-sec-head">
        <span class="bf-kicker"><i class="fas fa-book-open"></i> REFERENCES · 參考資料</span>
        <h2 class="bf-sec-title" style="margin:0;">參考<span class="accent">資料</span></h2>
        <p class="bf-refs-note">本頁引用資料依 APA 第 7 版格式列出，以台灣政府與國際研究公開資料為主。</p>
      </div>
      <ul class="bf-refs-list apa7">
        <li>行政院主計總處（2023）。<em>112 年農家戶口及經濟調查統計結果</em>。https://www.stat.gov.tw/</li>
        <li>勞動部勞工保險局（2024 年 11 月）。<em>農民職業災害保險就醫津貼調整公告（113/11/6 起適用）</em>。https://www.bli.gov.tw/0100668.html</li>
        <li>Boedeker, W., Watts, M., Clausing, P., &amp; Marquez, E. (2020). The global distribution of acute unintentional pesticide poisoning: Estimations based on a systematic review. <em>BMC Public Health, 20</em>, 1875. https://doi.org/10.1186/s12889-020-09939-0</li>
        <li>National Institute for Occupational Safety and Health. (2024). <em>Agricultural safety</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/agriculture/about/index.html</li>
        <li>U.S. Bureau of Labor Statistics. (2023). <em>Census of fatal occupational injuries, 2022</em> [Data set]. U.S. Department of Labor. https://www.bls.gov/iif/oshcfoi1.htm</li>
      </ul>
      <h4 class="bf-refs-subhead"><i class="fas fa-comment-dots"></i> 訪談來源</h4>
      <p class="bf-refs-note">陳宗延醫師語錄取自本計畫訪談紀錄（2024），臺大醫院雲林分院職業醫學科。</p>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<nav class="bf-bottom-nav" aria-label="民眾快捷導覽">
  <div class="bf-bottom-wrap">
    <a class="bf-bottom-link" href="/research-result/infographic/"><i class="fas fa-images"></i><span>看圖文</span></a>
    <a class="bf-bottom-link" href="/research-result/podcast/"><i class="fas fa-podcast"></i><span>聽故事</span></a>
    <a class="bf-bottom-link" href="/dignity-farming-initiative/"><i class="fas fa-bullhorn"></i><span>倡議行動</span></a>
  </div>
</nav>

<?php get_footer(); ?>
