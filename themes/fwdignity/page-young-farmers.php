<?php
/**
 * fwdignity theme — page-young-farmers.php
 * 青農 分眾入口頁(slug=young-farmers)· ROYAL BLUE
 *
 * 🔴 紅線:文字/數字/引用來源/連結 1:1 復用原頁。
 */
get_header();
?>

<div class="fw-page-layout">

<main class="bf-main" id="main" role="main">

  <!-- ① Hero -->
  <section class="bf-hero">
    <div class="bf-hero-deco" aria-hidden="true">
      <i class="fas fa-tractor bf-deco-1"></i>
      <i class="fas fa-gears bf-deco-2"></i>
      <i class="fas fa-chart-line bf-deco-3"></i>
    </div>
    <div class="bf-hero-inner">
      <span class="bf-hero-badge"><i class="fas fa-tractor"></i> 青農入口</span>
      <h1 class="bf-hero-title" data-hook-title>你已經在田裡了。<br><span class="accent">十年後你的身體還撐得住嗎?</span></h1>
      <p class="bf-hero-tagline">聰明務農不只是提升產量，更是讓你的農業生涯走得更長更遠。</p>
      <div class="bf-hero-intro">
        <p>青農是台灣農業的未來，但也面臨雙重壓力：一方面要讓農場經營穩定、一方面要避免身體在最精華的年紀就因為高強度勞動而提早耗損。這一頁聚焦在「用技術取代蠻力」和「用制度保障未來」，幫你做出更聰明的決策——投資省工設備、搞懂保險制度、規劃長期的職業健康策略（National Institute for Occupational Safety and Health [NIOSH], 2024）。</p>
        <p class="bf-hero-note"><i class="fas fa-info-circle"></i> <strong>本頁適用對象：</strong>農業部「百大青農輔導」以 18–45 歲實際從農者為資格門檻；「青年從農創業貸款」最高貸款額度 500 萬元，由農業部農業金融署辦理（農業部農業金融署，n.d.）。</p>
      </div>
    </div>
  </section>

  <!-- ② Stats -->
  <section class="bf-sec bf-stats reveal">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-chart-line"></i> DATA · 讓數字說話</span>
      <h2 class="bf-sec-title">讓<span class="accent">數字說話</span></h2>
    </div>
    <div class="bf-stats-grid">
      <div class="bf-stat-card">
        <div class="bf-stat-number"><i class="fas fa-person-cane" style="font-size:.72em;"></i></div>
        <p class="bf-stat-label">台灣農民平均年齡約 64 歲，務農人口結構高齡化為國安議題（行政院主計總處，2023）。</p>
        <p class="bf-stat-source">資料來源：行政院主計總處（2023）</p>
      </div>
      <div class="bf-stat-card">
        <div class="bf-stat-number">定額<span class="bf-stat-unit">補助</span></div>
        <p class="bf-stat-label">農糧署省工機械補助依年度公告採定額制，補助額度與品項逐年調整（農業部農糧署，2025）。</p>
        <p class="bf-stat-source">資料來源：農業部農糧署（2025，實際補助金額與比例依年度公告為準）</p>
      </div>
      <div class="bf-stat-card">
        <div class="bf-stat-number">500<span class="bf-stat-unit">萬</span></div>
        <p class="bf-stat-label">青年從農創業貸款最高額度 500 萬元，18–45 歲實際從農者可申請，低利分期（農業部農業金融署，n.d.）。</p>
        <p class="bf-stat-source">資料來源：農業部農業金融署</p>
      </div>
    </div>
    <p class="bf-stats-note"><i class="fas fa-circle-info"></i> 青農有機會用科技與制度做出不同選擇：以機器取代蠻力保護身體、以低利貸款與保險保障未來（NIOSH, 2024；農業部，n.d.）。</p>
  </section>

  <!-- ③ Scenarios -->
  <section class="bf-sec bf-scenarios">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-rocket"></i> STRATEGY · 四個策略支點</span>
      <h2 class="bf-sec-title">青農的<span class="accent">四個策略支點</span></h2>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#2563EB; --accent-light:#DBEAFE;">
      <div class="bf-scenario-num">01</div>
      <div class="bf-scenario-icon"><i class="fas fa-gears"></i></div>
      <div class="bf-scenario-body">
        <h3>機器 vs. 人力:為你的身體多爭取 20 年</h3>
        <p>NIOSH 安全控制層級指出「消除」與「取代」（第一、二層）的保護效果遠優於僅靠穿戴防護具（第五層）（NIOSH, 2021）。省工農機可以直接消除下背痛、肩頸損傷和農藥直接暴露等風險，而且多數機具可申請政府補助（農業部農糧署，2025）。<strong>聰明的投資不是花錢，是在保護你的生產力。</strong></p>
        <a href="/occupational-safety/labor-saving-machinery/" class="bf-scenario-link">看省工機具與補助方案 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#16A34A; --accent-light:#DCFCE7;">
      <div class="bf-scenario-num">02</div>
      <div class="bf-scenario-icon"><i class="fas fa-calculator"></i></div>
      <div class="bf-scenario-body">
        <h3>你的退休金夠嗎?現在就算清楚</h3>
        <p>農民退休儲金制度 2021 年才上路，很多青農還沒搞清楚跟勞保退休金差在哪裡。愈早開始提繳，複利效果愈大。我們做了試算器，輸入年齡和年資就能比較不同方案的長期差異（勞動部勞工保險局，2024）。</p>
        <a href="/economic-insurance/farmer-worker-retirement-calculator/" class="bf-scenario-link">做退休金試算 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#E11D48; --accent-light:#FFE4E6;">
      <div class="bf-scenario-num">03</div>
      <div class="bf-scenario-icon"><i class="fas fa-shield-halved"></i></div>
      <div class="bf-scenario-body">
        <h3>受傷了怎麼辦?NIOSH 告訴你風險有多高</h3>
        <p>2022 年美國農林漁獵業致死率為每 10 萬全時當量工作者（FTE）18.6 人，是全產業平均 3.7 的 5 倍，交通運輸事故（含拖拉機翻覆）為首要死因（NIOSH, 2024；U.S. Bureau of Labor Statistics [BLS], 2023）。職災保險在關鍵時刻能提供醫療給付和傷病津貼——但前提是你有投保。花 3 分鐘用試算器確認你目前的保障範圍（勞動部勞工保險局，2024）。</p>
        <a href="/economic-insurance/worker-occupational-accident-calculator/" class="bf-scenario-link">做職災保障試算 <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="bf-scenario-card reveal" style="--accent:#7C3AED; --accent-light:#EDE9FE;">
      <div class="bf-scenario-num">04</div>
      <div class="bf-scenario-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="bf-scenario-body">
        <h3>從做中學:真實案例與在地經驗</h3>
        <p>《我的民雄朋友們》Podcast 紀錄了鄭富賢從斜槓到全職有機蓮藕農的真實歷程，以及女農在農務與家庭間的多重拉扯。農學堂也有工作坊和短影片，把前輩的經驗轉化為你可以立即應用的知識。</p>
        <a href="/research-result/podcast/" class="bf-scenario-link">聽 Podcast 與學習資源 <i class="fas fa-arrow-right"></i></a>
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
        「尊嚴農業」由<strong>國立中正大學</strong>與<strong>臺大醫院雲林分院</strong>共同推動，以嘉義縣民雄鄉為實踐場域，致力建構農業工作者的職業健康社會安全網。我們特別關注如何借鏡日韓澳經驗，推動台灣農業<strong>從勞力密集型轉向技術與知識密集型</strong>。
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
      <a class="bf-cta-btn bf-cta-primary" href="/occupational-safety/labor-saving-machinery/" data-magnetic>
        <i class="fas fa-tractor"></i> 先看機具與補助
      </a>
      <a class="bf-cta-btn bf-cta-secondary" href="/economic-insurance/worker-occupational-accident-calculator/">
        <i class="fas fa-calculator"></i> 做保障試算
      </a>
    </div>
  </section>

  <!-- ⑥ Emergency -->
  <section class="bf-sec bf-emergency">
    <div class="bf-emergency-head">
      <h2><i class="fas fa-phone-volume"></i> 緊急時刻</h2>
      <p>農業工作高風險。受傷、中暑或身體不適，先停下來，先求助。</p>
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
    .bf-refs-note { color: #6b7280; font-size: .92em; }
  </style>
  <section class="bf-sec bf-refs">
    <div class="bf-sec-head">
      <span class="bf-kicker"><i class="fas fa-book-open"></i> REFERENCES · 參考資料</span>
      <h2 class="bf-sec-title">參考<span class="accent">資料</span></h2>
      <p class="bf-refs-note">本頁內容依 APA 第 7 版格式引用以下台灣政府與國際職業安全機構公開資料。</p>
    </div>
    <ul class="bf-refs-list apa7">
      <li>行政院主計總處（2023）。<em>112 年農家戶口及經濟調查統計結果</em>。https://www.stat.gov.tw/</li>
      <li>勞動部勞工保險局（2024）。<em>農民職業災害保險與農民退休儲金制度介紹</em>。https://www.bli.gov.tw/</li>
      <li>農業部（n.d.）。<em>百大青農輔導計畫</em>。https://www.moa.gov.tw/</li>
      <li>農業部農業金融署（n.d.）。<em>青年從農創業貸款</em>。https://www.afa.gov.tw/</li>
      <li>農業部農糧署（2025）。<em>農機補助專區</em>。https://www.afa.gov.tw/cht/index.php?code=list&amp;ids=3314</li>
      <li>National Institute for Occupational Safety and Health. (2021). <em>Hierarchy of controls</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/hierarchy-of-controls/about/index.html</li>
      <li>National Institute for Occupational Safety and Health. (2024). <em>Agricultural safety</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/agriculture/about/index.html</li>
      <li>U.S. Bureau of Labor Statistics. (2023). <em>Census of fatal occupational injuries, 2022</em> [Data set]. U.S. Department of Labor. https://www.bls.gov/iif/oshcfoi1.htm</li>
    </ul>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<nav class="bf-bottom-nav" aria-label="青農快捷導覽">
  <div class="bf-bottom-wrap">
    <a class="bf-bottom-link" href="/occupational-safety/labor-saving-machinery/"><i class="fas fa-tractor"></i><span>看機具</span></a>
    <a class="bf-bottom-link" href="/economic-insurance/worker-occupational-accident-calculator/"><i class="fas fa-calculator"></i><span>算保障</span></a>
    <a class="bf-bottom-link" href="/thenews/"><i class="fas fa-newspaper"></i><span>看動態</span></a>
  </div>
</nav>

<?php get_footer(); ?>
