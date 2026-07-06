<?php
/**
 * fwdignity theme — page-beginner-farmers.php
 * 新手務農 分眾入口頁(slug=beginner-farmers)
 *
 * 🔴 內容紅線:所有文字、數字、引用來源、連結 URL 1:1 復用原頁內容。
 *               僅重新設計 UI / 版面 / 顏色 / 特效。
 */

get_header();
?>

<div class="fw-page-layout">

<main class="bf-main" id="main" role="main">

  <!-- ① Hero -->
  <section class="bf-hero">
    <div class="bf-hero-deco" aria-hidden="true">
      <i class="fas fa-leaf bf-deco-1"></i>
      <i class="fas fa-seedling bf-deco-2"></i>
      <i class="fas fa-sun bf-deco-3"></i>
    </div>
    <div class="bf-hero-inner">
      <span class="bf-hero-badge"><i class="fas fa-seedling"></i> 新手務農入口</span>
      <h1 data-hook-title class="bf-hero-title">先把風險看清楚，<br><span class="accent">再安心下田</span></h1>
      <p class="bf-hero-tagline">農業是全行業致死率最高的產業之一。<br>但只要做對幾件事，你就能安全地開始。</p>
      <div class="bf-hero-intro">
        <p>你可能正在考慮務農，或剛踏入這個行業。農業是一份與土地緊密連結的工作，但它的職業風險遠超過多數人的想像——高溫、農藥、重複性勞動、搬運重物，每一項都在悄悄傷害你的身體。這一頁從「保護自己」出發，幫你用最短時間建立正確觀念，帶著安全意識下田。</p>
        <p class="bf-hero-note"><i class="fas fa-info-circle"></i> <strong>本頁適用對象：</strong>農業部「新農民培育方案」定義之新農民，指從農未滿 2 年、經評估具從農潛力之學員，或仍在考慮跨入農業之有意從農者（農業部，n.d.）。</p>
      </div>
    </div>
  </section>

  <!-- ② 數據震撼 -->
  <section class="bf-sec bf-stats">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-chart-bar"></i> DATA · 關鍵數據</span>
      <h2 class="bf-sec-title">你需要知道的<span class="accent">數字</span></h2>
    </div>
    <div class="bf-stats-grid">
      <div class="bf-stat-card">
        <div class="bf-stat-number" data-count="18.6">18.6</div>
        <p class="bf-stat-label">美國農業職災致死率為每 10 萬名全時當量工作者 18.6 人，是全產業平均（3.7）的 5 倍（National Institute for Occupational Safety and Health [NIOSH], 2024；U.S. Bureau of Labor Statistics [BLS], 2023）。</p>
      </div>
      <div class="bf-stat-card">
        <div class="bf-stat-number" data-count="29">29<span class="bf-stat-unit">%</span></div>
        <p class="bf-stat-label">美國非致命農業傷害中，約三成為滑跌墜傷（slip, trip, fall）造成（NIOSH, 2024）。</p>
      </div>
      <div class="bf-stat-card">
        <div class="bf-stat-number" data-count="3.85">3.85<span class="bf-stat-unit">億</span></div>
        <p class="bf-stat-label">全球每年非故意急性農藥中毒案例，約 1.1 萬人致死（Boedeker et al., 2020）。</p>
      </div>
    </div>
    <p class="bf-stats-note"><i class="fas fa-circle-info"></i> 這些不是遙遠的統計數字——它們發生在你未來的每一天工作裡。好消息是，多數傷害都可以預防。</p>
  </section>

  <!-- ③ 新手最需要的四件事 -->
  <section class="bf-sec bf-scenarios">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-compass"></i> WHAT YOU NEED · 新手必備</span>
      <h2 class="bf-sec-title">新手最需要的<span class="accent">四件事</span></h2>
    </div>

    <div class="bf-scenario-card" style="--accent:#EA580C; --accent-light:#FFEDD5;">
      <div class="bf-scenario-num">01</div>
      <div class="bf-scenario-icon"><i class="fas fa-temperature-high"></i></div>
      <div class="bf-scenario-body">
        <h3>太陽底下的隱形殺手</h3>
        <p>台灣夏季田間溫度經常超過 35°C。2024 年一名 39 歲農民在田間因熱傷害猝死。美國職業安全衛生署指出，濕球黑球溫度（WBGT）超過 28°C 即進入警戒區——你需要知道怎麼辨識中暑警訊、何時該停下來（Occupational Safety and Health Administration [OSHA], n.d.）。</p>
        <a href="/healthgood/subpage-heat/" class="bf-scenario-link">了解熱傷害防護 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card" style="--accent:#E11D48; --accent-light:#FFE4E6;">
      <div class="bf-scenario-num">02</div>
      <div class="bf-scenario-icon"><i class="fas fa-mask-face"></i></div>
      <div class="bf-scenario-body">
        <h3>噴藥只戴布口罩?那等於沒戴</h3>
        <p>全球使用超過 1,000 種農藥，慢性暴露可能導致癌症與生殖系統影響（World Health Organization [WHO], 2022）；另有研究估計全球每年約 3.85 億件非故意急性農藥中毒、約 1.1 萬人死亡（Boedeker et al., 2020）。一般口罩<strong>擋不住農藥蒸氣</strong>——你需要的是有機蒸氣濾罐的防毒面具。從頭到腳，每個部位該穿戴什麼，這裡有完整指南。</p>
        <a href="/occupational-safety/personal-protective-equipment/" class="bf-scenario-link">查看防護具選用指南 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card" style="--accent:#1E40AF; --accent-light:#DBEAFE;">
      <div class="bf-scenario-num">03</div>
      <div class="bf-scenario-icon"><i class="fas fa-person-falling-burst"></i></div>
      <div class="bf-scenario-body">
        <h3>彎腰搬重物，你的腰會記住</h3>
        <p>NIOSH 建議單次人工抬舉上限為 23 公斤，但農民經常搬運遠超此限的重物（NIOSH, 2024）。「過度勞累」是農業非致命傷害的首因。好消息是:很多省力農機有政府補助，依年度公告採定額制（農業部農糧署，2025）。</p>
        <a href="/occupational-safety/labor-saving-machinery/" class="bf-scenario-link">看省工農機具與補助 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card" style="--accent:#15803D; --accent-light:#DCFCE7;">
      <div class="bf-scenario-num">04</div>
      <div class="bf-scenario-icon"><i class="fas fa-shield-halved"></i></div>
      <div class="bf-scenario-body">
        <h3>受傷了，你有保障嗎?</h3>
        <p>農職保投保率僅約三成，很多新手農民根本不知道自己有這些權益（勞動部勞工保險局，2024）。農民退休儲金、農保、職災保險——這些制度設計讓你受傷、退休時不至於沒有收入。先用試算器算一次，花 3 分鐘就能看清楚。</p>
        <a href="/economic-insurance/worker-occupational-accident-calculator/" class="bf-scenario-link">立即做保障試算 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </section>

  <!-- ④ 認識尊嚴農業 -->
  <section class="bf-sec bf-about">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-hand-holding-heart"></i> ABOUT · 計畫介紹</span>
      <h2 class="bf-sec-title">認識<span class="accent">尊嚴農業計畫</span></h2>
    </div>
    <div class="bf-about-card">
      <p class="bf-about-desc">
        「尊嚴農業」由<strong>國立中正大學</strong>與<strong>臺大醫院雲林分院</strong>共同推動，以嘉義縣民雄鄉為實踐場域，致力建構農業工作者的職業健康社會安全網。我們相信:<strong>每一位農民都應該在安全與尊嚴中勞動</strong>。
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

  <!-- ⑤ 相關資源 -->
  <section class="bf-sec bf-resources">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-bookmark"></i> RESOURCES · 相關資源</span>
      <h2 class="bf-sec-title">相關<span class="accent">資源</span></h2>
    </div>
    <div class="bf-cta">
      <a class="bf-cta-btn bf-cta-primary" href="/occupational-safety/">
        <i class="fas fa-helmet-safety"></i> 進入職業安全總覽
      </a>
      <a class="bf-cta-btn bf-cta-secondary" href="/research-result/related-resources/">
        <i class="fas fa-link"></i> 查看相關資源
      </a>
    </div>
  </section>

  <!-- ⑥ 緊急資源 -->
  <section class="bf-sec bf-emergency">
    <div class="bf-emergency-head">
      <h2><i class="fas fa-phone-volume"></i> 緊急時刻</h2>
      <p>出現持續頭暈、胸悶、呼吸不順，請先停止作業並就醫。不要硬撐。</p>
    </div>
    <div class="bf-emergency-grid">
      <a class="bf-emergency-item" href="tel:119">
        <strong>緊急救護</strong><span class="bf-emergency-num">119</span>
      </a>
      <a class="bf-emergency-item" href="tel:1925">
        <strong>安心專線</strong><span class="bf-emergency-num">1925</span>
      </a>
      <a class="bf-emergency-item" href="tel:113">
        <strong>保護專線</strong><span class="bf-emergency-num">113</span>
      </a>
    </div>
  </section>

  <!-- ⑦ 探索更多 -->
  <section class="bf-sec bf-explore">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-link"></i> EXPLORE · 探索更多</span>
      <h2 class="bf-sec-title">探索<span class="accent">更多</span></h2>
    </div>
    <div class="bf-explore-grid">
      <a href="/healthgood/" class="bf-explore-card c-health">
        <i class="fas fa-heart-pulse"></i>
        <span>健康促進</span>
      </a>
      <a href="/occupational-safety/" class="bf-explore-card c-safety">
        <i class="fas fa-helmet-safety"></i>
        <span>職業安全</span>
      </a>
      <a href="/economic-insurance/" class="bf-explore-card c-economy">
        <i class="fas fa-shield-cat"></i>
        <span>經濟與保險</span>
      </a>
      <a href="/research-result/podcast/" class="bf-explore-card c-podcast">
        <i class="fas fa-podcast"></i>
        <span>聽 Podcast</span>
      </a>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<!-- 底部快捷導覽(保留原功能) -->
<nav class="bf-bottom-nav" aria-label="新手務農快捷導覽">
  <div class="bf-bottom-wrap">
    <a class="bf-bottom-link" href="/occupational-safety/"><i class="fas fa-helmet-safety"></i><span>看防護</span></a>
    <a class="bf-bottom-link" href="/healthgood/subpage-heat/"><i class="fas fa-temperature-high"></i><span>防中暑</span></a>
    <a class="bf-bottom-link" href="/economic-insurance/worker-occupational-accident-calculator/"><i class="fas fa-calculator"></i><span>算保障</span></a>
  </div>
</nav>

<?php get_footer(); ?>
