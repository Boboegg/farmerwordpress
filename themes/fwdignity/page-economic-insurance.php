<?php
/**
 * Template Name: 經濟與保險 Hub
 * Slug: economic-insurance
 */
get_header(); ?>

<div class="fw-page-layout">

<main class="ei-main" id="main" role="main">

  <!-- Hero -->
  <section class="ei-hero reveal">
    <div class="ei-hero-grid">
      <div class="ei-hero-content">
        <span class="ei-hero-badge"><i class="fas fa-shield-halved"></i> ECONOMIC SAFETY NET</span>
        <h1 data-hook-title>經濟<span class="accent">與保險</span></h1>
        <p class="ei-hero-subtitle">務農受傷有給付、天災有理賠、資源有管道——搞懂你的經濟安全網。</p>
        <ul class="ei-hero-list">
          <li><i class="fas fa-check-circle"></i><span><strong>職業災害保險</strong>——務農受傷的收入補償與醫療津貼</span></li>
          <li><i class="fas fa-check-circle"></i><span><strong>農作物保險</strong>——天災造成農損的理賠機制</span></li>
          <li><i class="fas fa-check-circle"></i><span><strong>補助資源</strong>——農機補助、天災救助、政策貸款一次看</span></li>
        </ul>
        <div class="ei-hero-note">
          <i class="fas fa-lightbulb"></i>
          <div><strong>不確定自己的權益？</strong>本站提供兩個自製試算工具，輸入基本資料即可試算你的職災權益與退休儲金。</div>
        </div>
        <div class="ei-hero-actions">
          <a href="#ei-topics" class="ei-btn-primary" data-magnetic><i class="fas fa-layer-group"></i> 瀏覽主題</a>
          <a href="#ei-tools" class="ei-btn-secondary" data-magnetic><i class="fas fa-calculator"></i> 試算我的權益</a>
        </div>
      </div>
      <div class="ei-hero-visual">
        <div class="ei-hero-stats">
          <div class="ei-stat-card">
            <div class="ei-stat-icon"><i class="fas fa-coins"></i></div>
            <div>
              <span class="ei-stat-number"><span data-count="15">15</span> 元/月</span>
              <span class="ei-stat-label">農職保農民實繳保費</span>
              <span class="ei-stat-source">（勞動部勞工保險局 [BLI], 2024a，112/2/10 起）</span>
            </div>
          </div>
          <div class="ei-stat-card">
            <div class="ei-stat-icon"><i class="fas fa-bed-pulse"></i></div>
            <div>
              <span class="ei-stat-number"><span data-count="680">680</span> 元/日</span>
              <span class="ei-stat-label">農職保傷病給付最高日額</span>
              <span class="ei-stat-source">（BLI, 2024b，113/9/1 起）</span>
            </div>
          </div>
          <div class="ei-stat-card">
            <div class="ei-stat-icon"><i class="fas fa-seedling"></i></div>
            <div>
              <span class="ei-stat-number"><span data-count="27">27</span> 品項</span>
              <span class="ei-stat-label">農作物保險可投保品項</span>
              <span class="ei-stat-source">（農業部農業金融署 [BOAF], n.d.）</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3 主題入口 -->
  <section class="ei-sec reveal" id="ei-topics">
    <div class="ei-section-head">
      <span class="kicker">Topics · 主題分類</span>
      <h2>三大<span class="accent">經濟安全網</span></h2>
      <p>點擊進入各主題子頁面，查看完整說明與申請流程。</p>
    </div>
    <div class="ei-topic-grid">
      <a href="/occupational-accident-insurance/" class="ei-topic">
        <div class="ei-topic-icon"><i class="fas fa-user-injured"></i></div>
        <h3>農民職業災害保險</h3>
        <p class="ei-topic-desc">務農受傷、就醫、失能、身故的收入補償制度。</p>
        <div class="ei-topic-highlights">月繳僅 <strong>15 元</strong>（BLI, 2024a） · 傷病給付最高 <strong>680 元/日</strong>（BLI, 2024b） · 身心障礙一次金</div>
        <span class="ei-topic-cta">了解職災保險 <i class="fas fa-arrow-right"></i></span>
      </a>
      <a href="/crop-insurance/" class="ei-topic">
        <div class="ei-topic-icon"><i class="fas fa-cloud-bolt"></i></div>
        <h3>農作物保險</h3>
        <p class="ei-topic-desc">颱風、豪雨、低溫、病蟲害造成農損的理賠。</p>
        <div class="ei-topic-highlights">目前 <strong>27 品項</strong>可投保（BOAF, n.d.） · 政府補助保費 <strong>最高 60%</strong>（來源待確認） · 金融署主管</div>
        <span class="ei-topic-cta">了解農作物保險 <i class="fas fa-arrow-right"></i></span>
      </a>
      <a href="/agricultural-subsidy-resources/" class="ei-topic">
        <div class="ei-topic-icon"><i class="fas fa-hand-holding-dollar"></i></div>
        <h3>補助資源</h3>
        <p class="ei-topic-desc">農機補助、天災救助、政策貸款、農民學費補助一次看。</p>
        <div class="ei-topic-highlights">省力農機補助 · 天災現金救助 · 農貸低利 · 訓練補助</div>
        <span class="ei-topic-cta">了解補助資源 <i class="fas fa-arrow-right"></i></span>
      </a>
    </div>
  </section>

  <!-- 保險速覽表 -->
  <section class="ei-sec reveal">
    <div class="ei-section-head">
      <span class="kicker">Snapshot · 保險速覽</span>
      <h2>台灣農民<span class="accent">可用保險</span>對照表</h2>
      <p>快速掌握「誰保什麼」。資料以 2025 年官方公告為準，實際以官方網站最新說明為主。</p>
    </div>
    <div class="ei-snap-wrap">
      <div class="ei-snap-header">
        <h3><i class="fas fa-table-list"></i> 農民保險一覽</h3>
        <p>整理自勞保局、農業部農業金融署公開資料（BLI, 2024a；BOAF, n.d.）。</p>
      </div>
      <div class="ei-snap-table-wrap">
        <table class="ei-snap-table">
          <thead>
            <tr>
              <th>保險類型</th>
              <th>保障對象</th>
              <th>保障範圍</th>
              <th>農民每月繳</th>
              <th>說明</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><div class="insurance-name">農民健康保險<small>（農保）</small></div></td>
              <td>農民本人</td>
              <td>生育、身心障礙、喪葬</td>
              <td><span class="ei-snap-highlight">78 元</span><br><small>（BLI, 2024a）</small></td>
              <td><span class="ei-snap-note">基礎保障，不含傷病給付</span></td>
            </tr>
            <tr>
              <td><div class="insurance-name">農民職業災害保險<small>（農職保）</small></div></td>
              <td>農民本人</td>
              <td>傷病、就醫津貼、身心障礙、喪葬</td>
              <td><span class="ei-snap-highlight">15 元</span><br><small>（BLI, 2024a）</small></td>
              <td><a href="/occupational-accident-insurance/" class="ei-snap-link">查看詳情 →</a></td>
            </tr>
            <tr>
              <td><div class="insurance-name">農作物保險</div></td>
              <td>農作物</td>
              <td>天災、病蟲害造成的農損</td>
              <td>依品項不同<br><small>（BOAF, n.d.）</small></td>
              <td><a href="/crop-insurance/" class="ei-snap-link">查看詳情 →</a></td>
            </tr>
            <tr>
              <td><div class="insurance-name">全民健康保險<small>（健保）</small></div></td>
              <td>農民本人</td>
              <td>就醫費用</td>
              <td>依投保金額</td>
              <td><span class="ei-snap-wip">另行投保，非本專區範圍</span></td>
            </tr>
            <tr>
              <td><div class="insurance-name">農民退休儲金</div></td>
              <td>農民退休</td>
              <td>退休年金</td>
              <td>自選提繳</td>
              <td><a href="#ei-tools" class="ei-snap-link">試算工具 →</a></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="ei-snap-footnote">
        <i class="fas fa-info-circle"></i> 資料來源：BLI (2024a)、BOAF (n.d.)。本表為簡化對照，實際申請條件與給付金額以官方最新規定為準。
      </div>
    </div>
  </section>

  <!-- 試算工具（自製 + 官方） -->
  <section class="ei-sec reveal" id="ei-tools">
    <div class="ei-tools">
      <div class="ei-tools-header">
        <h3><i class="fas fa-calculator"></i> 試算工具</h3>
        <p>本站自製互動工具 + 官方試算直達連結，快速估算你的權益。</p>
      </div>
      <div class="ei-tools-grid">
        <div class="ei-tool-card internal">
          <div class="ei-tool-info">
            <h4><i class="fas fa-user-injured"></i> 勞工職業災害權益試算 <span class="tool-badge">本站</span></h4>
            <p>整合農民、勞工不同身分之職災給付試算，輸入基本資料即可估算傷病給付、醫療津貼、身心障礙給付。</p>
            <a href="/worker-occupational-accident-calculator/" class="ei-tool-link accent">開始試算 <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
        <div class="ei-tool-card internal">
          <div class="ei-tool-info">
            <h4><i class="fas fa-piggy-bank"></i> 農民、勞工退休金試算 <span class="tool-badge">本站</span></h4>
            <p>整合農民退休儲金、勞退新制、勞保年金，試算你未來每月可領取的退休給付概況。</p>
            <a href="/farmer-worker-retirement-calculator/" class="ei-tool-link accent">開始試算 <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
        <div class="ei-tool-card">
          <div class="ei-tool-info">
            <h4><i class="fas fa-building-columns"></i> 農職保・傷病給付試算 <span class="tool-badge external">官方</span></h4>
            <p>勞保局官方試算系統，輸入不能工作天數，自動算出傷病給付 + 就醫津貼金額。</p>
            <a href="https://www.bli.gov.tw/0105770.html" target="_blank" rel="noopener" class="ei-tool-link">前往勞保局 <i class="fas fa-arrow-up-right-from-square"></i></a>
          </div>
        </div>
        <div class="ei-tool-card">
          <div class="ei-tool-info">
            <h4><i class="fas fa-piggy-bank"></i> 農民退休儲金試算 <span class="tool-badge external">官方</span></h4>
            <p>勞保局官方試算系統，輸入年齡和提繳比例，試算未來每月可領取的退休年金。</p>
            <a href="https://www.bli.gov.tw/0105770.html" target="_blank" rel="noopener" class="ei-tool-link">前往勞保局 <i class="fas fa-arrow-up-right-from-square"></i></a>
          </div>
        </div>
        <div class="ei-tool-card">
          <div class="ei-tool-info">
            <h4><i class="fas fa-seedling"></i> 農作物保險・品項查詢 <span class="tool-badge external">官方</span></h4>
            <p>查看目前可投保的農作物品項、承保公司及保費補助比例。</p>
            <a href="https://www.coaf.gov.tw/" target="_blank" rel="noopener" class="ei-tool-link">前往農業金融署 <i class="fas fa-arrow-up-right-from-square"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 聯絡窗口 -->
  <section class="ei-sec reveal">
    <div class="ei-section-head">
      <span class="kicker">Contacts · 聯絡窗口</span>
      <h2>我要<span class="accent">申請或諮詢</span></h2>
      <p>不同保險由不同機關主管，以下為加保、申請、諮詢的主要窗口。</p>
    </div>
    <div class="ei-contact-grid">
      <a href="https://www.bli.gov.tw/" target="_blank" rel="noopener" class="ei-contact-card">
        <div class="ei-contact-icon"><i class="fas fa-building-columns"></i></div>
        <div>
          <h4>勞保局（農職保）</h4>
          <p>職災保險加保、給付申請 · 勞動部勞工保險局<br><strong style="color:var(--ei-main);">☎ 02-2396-1266 轉 2800</strong></p>
        </div>
      </a>
      <a href="https://www.coaf.gov.tw/" target="_blank" rel="noopener" class="ei-contact-card">
        <div class="ei-contact-icon"><i class="fas fa-tractor"></i></div>
        <div>
          <h4>農業金融署（農作物保險）</h4>
          <p>農業保險品項、保費查詢 · 農業部農業金融署<br><strong style="color:var(--ei-main);">☎ 02-3393-5835</strong></p>
        </div>
      </a>
      <div class="ei-contact-card" style="cursor:default;">
        <div class="ei-contact-icon"><i class="fas fa-people-roof"></i></div>
        <div>
          <h4>戶籍所在地農會</h4>
          <p>各地不同 · 加保與申請的第一線窗口</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 免責聲明 -->
  <section class="ei-sec reveal">
    <div class="ei-disclaimer">
      <strong><i class="fas fa-triangle-exclamation"></i> 免責聲明：</strong>本站資訊整理自勞動部勞工保險局（BLI, 2024a）、農業部農業金融署（BOAF, n.d.）及相關主管機關公開資料。實際申請條件、給付金額、補助比例依官方最新公告為準。本站試算工具為估算參考，不具法律效力；正式申請請洽各主管機關或所在地農會。
    </div>
  </section>

  <!-- 參考資料（APA 7，全展開） -->
  <style>
    .ei-refs-apa7 { list-style: none; padding: 0; margin: 0; }
    .ei-refs-apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.7; font-size: 13.5px; }
    .ei-refs-apa7 li em { font-style: italic; }
    .ei-refs-apa7 li a { word-break: break-all; }
    .ei-refs-wrap { background:#F9FAFB;border:1px solid var(--ei-line,#E5E7EB);border-radius:12px;padding:22px 26px;margin-top:8px; }
    .ei-refs-wrap h3 { font-size:18px;font-weight:900;margin:0 0 8px;color:var(--ei-deep,#0F172A); }
    .ei-refs-note { font-size:13px;color:#555;margin:0 0 14px; }
  </style>
  <section class="ei-sec reveal">
    <div class="ei-section-head">
      <span class="kicker">References · 參考資料</span>
      <h2>參考<span class="accent">資料</span></h2>
      <p>本頁依 APA 第 7 版格式引用以下台灣政府公開資料。中文在前、英文在後。</p>
    </div>
    <div class="ei-refs-wrap">
      <ul class="ei-refs-apa7">
        <li>勞動部勞工保險局（2024a）。<em>農民保險・保費業務</em>。https://www.bli.gov.tw/0016983.html</li>
        <li>勞動部勞工保險局（2024b）。<em>農民職業災害保險傷病給付（113 年 9 月 1 日起適用）</em>。https://www.bli.gov.tw/0100668.html</li>
        <li>農業部農業金融署（n.d.）。<em>農產業保險</em>。https://www.coaf.gov.tw/</li>
        <li>Bureau of Agricultural Finance. (n.d.). <em>Agricultural insurance</em>. Ministry of Agriculture, Taiwan. https://www.coaf.gov.tw/</li>
        <li>Bureau of Labor Insurance. (2024a). <em>Farmer insurance: Premium administration</em>. Ministry of Labor, Taiwan. https://www.bli.gov.tw/0016983.html</li>
        <li>Bureau of Labor Insurance. (2024b). <em>Farmer occupational accident insurance: Injury and sickness benefits (effective 2024/9/1)</em>. Ministry of Labor, Taiwan. https://www.bli.gov.tw/0100668.html</li>
      </ul>
    </div>
  </section>

  <!-- 交叉連結 -->
  <section class="ei-sec reveal">
    <div class="ei-cross">
      <h3><i class="fas fa-compass"></i> 延伸閱讀</h3>
      <div class="ei-cross-grid">
        <a href="/healthgood/" class="ei-cross-card">
          <div class="ei-cross-icon"><i class="fas fa-heart-pulse"></i></div>
          <div>
            <div style="font-weight:900;color:var(--ei-deep);">健康促進</div>
            <div style="font-size:12px;color:var(--c-text-muted);margin-top:2px;">中暑、農藥中毒屬農職保涵蓋的職業傷病——先學會預防</div>
          </div>
        </a>
        <a href="/occupational-safety/" class="ei-cross-card">
          <div class="ei-cross-icon"><i class="fas fa-helmet-safety"></i></div>
          <div>
            <div style="font-weight:900;color:var(--ei-deep);">職業安全</div>
            <div style="font-size:12px;color:var(--c-text-muted);margin-top:2px;">農機事故是最常見的職災類型——了解安全操作與防護具</div>
          </div>
        </a>
      </div>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer();
