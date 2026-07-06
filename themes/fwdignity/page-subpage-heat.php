<?php
/**
 * fwdignity · page-subpage-heat.php
 * 熱危害預防子頁(slug=subpage-heat)· 紅 #DC2626
 * 🔴 紅線:文字 / 數字 / 引用 1:1 復用原頁。
 */
get_header();
?>

<div class="fw-page-layout">

<main class="hps" id="main" role="main">

  <a href="/healthgood/" class="hps-back"><i class="fas fa-arrow-left"></i> 回到健康促進總覽</a>

  <!-- Hero -->
  <section class="hps-hero">
    <div class="hps-hero-deco" aria-hidden="true">
      <i class="fas fa-temperature-arrow-up hps-deco-1"></i>
      <i class="fas fa-sun hps-deco-2"></i>
    </div>
    <div class="hps-hero-inner">
      <span class="hps-badge"><i class="fas fa-temperature-arrow-up"></i> 熱危害預防</span>
      <h1 class="hps-title" data-hook-title>熱危害<span class="accent">預防指南</span></h1>
      <p class="hps-tagline">辨識、預防、急救——在烈日下保護自己的完整知識。</p>
      <div class="hps-intro">
        <p>台灣 19–64 歲工作年齡成人因熱傷害送急診的人數在近年明顯增加（衛生福利部國民健康署 [國健署]，2024；確切百分比來源待確認）。每年夏季，雲嘉地區高溫頻繁超過 35°C，溫室內更可突破 40°C。農民長時間戶外作業、年齡偏高、體力負荷大，是熱傷害的高風險族群（Flouris et al., 2018）。</p>
        <p>更令人擔憂的是：長期熱暴露不只會造成急性中暑，還會累積性地傷害腎臟。研究顯示，中暑患者日後罹患慢性腎臟病的風險為一般人的 <strong>4.3 倍</strong>（Tseng et al., 2020）。這一頁整理了從辨識症狀到急救處置的完整流程，並依據勞動部《高氣溫戶外作業勞工熱危害預防指引》（勞動部職業安全衛生署 [OSHA-TW]，2025）。</p>
      </div>
    </div>
  </section>

  <!-- ① 辨識:中暑 vs 熱衰竭 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-head-side-virus"></i></div>
      <div>
        <h2>熱中暑 <span class="accent">vs</span> 熱衰竭：怎麼分辨</h2>
        <p>兩者處理方式不同，分辨清楚才能正確急救（OSHA-TW, 2025；衛生福利部國民健康署 [HPA]，n.d.）。</p>
      </div>
    </div>
    <div class="hps-compare-grid">
      <div class="hps-compare danger">
        <h3><i class="fas fa-circle-xmark"></i> 熱中暑（致命緊急）</h3>
        <ul>
          <li>體溫 ≥ 40°C</li>
          <li>皮膚發紅、乾燥（古典型中暑常見）；<strong>勞力型中暑仍可能大量出汗</strong>，不可單以「不出汗」判定（OSHA-TW, 2025）</li>
          <li>意識模糊、抽搐、昏迷</li>
          <li>心跳快但血壓可能下降</li>
        </ul>
        <div class="hps-compare-action">
          <strong>處置：</strong>立刻打 119 → 移到陰涼處 → 積極降溫（冷水浸泡或冰敷脖子、腋下、鼠蹊部）→ 等待救護車（HPA, n.d.）
        </div>
        <p class="hps-compare-stat">重症中暑延遲降溫會顯著增加死亡率（Flouris et al., 2018）。</p>
      </div>
      <div class="hps-compare warning">
        <h3><i class="fas fa-triangle-exclamation"></i> 熱衰竭（嚴重但可現場處理）</h3>
        <ul>
          <li>體溫通常 &lt; 40°C</li>
          <li>皮膚濕冷、大量出汗</li>
          <li>頭暈、噁心、虛弱、肌肉痙攣</li>
          <li>意識清楚但可能站不穩</li>
        </ul>
        <div class="hps-compare-action">
          <strong>處置：</strong>移到陰涼處 → 鬆開衣物 → 慢慢補充含鹽分的飲料 → 扇風降溫（HPA, n.d.）
        </div>
        <p class="hps-compare-stat">如未正確處理，可能惡化為熱中暑。</p>
      </div>
    </div>
    <div class="hps-note">
      <i class="fas fa-exclamation-circle"></i>
      <span>判斷關鍵：<strong>有沒有意識改變</strong>。如果對方說話含糊、反應遲鈍、或叫不醒，請當作中暑處理，立刻叫救護車（HPA, n.d.）。</span>
    </div>
  </section>

  <!-- ② 預防措施 6 習慣 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-shield-halved"></i></div>
      <div>
        <h2>預防措施：<span class="accent">六個關鍵習慣</span></h2>
        <p>熱傷害並非不可避免。做到以下六件事，風險就能大幅降低（OSHA-TW, 2025）。</p>
      </div>
    </div>
    <div class="hps-habit-grid">
      <div class="hps-habit"><div class="hps-habit-num">1</div><div><h4>調整作息，避開高溫時段</h4><p>清晨 5:00–10:00 作業 → 中午 10:00–15:00 休息（室內或陰涼處）→ 下午 15:00–18:00 作業。台灣夏季最高溫通常出現在 13:00–14:00 之間（OSHA-TW, 2025）。</p></div></div>
      <div class="hps-habit"><div class="hps-habit-num">2</div><div><h4>主動補水，不等口渴</h4><p>每 15–20 分鐘喝 150–200 mL，一天至少 2–4 公升。口渴代表身體已經缺水 1–2%。可加少量鹽巴（每公升水加 1/4 茶匙）補充電解質。避免含糖飲料和酒精（OSHA-TW, 2025）。</p></div></div>
      <div class="hps-habit"><div class="hps-habit-num">3</div><div><h4>穿淺色、透氣、寬鬆衣物</h4><p>深色衣物吸熱更多。選擇吸濕排汗材質，搭配寬沿遮陽帽（帽沿 ≥ 7 公分）。若穿不透氣防護衣（如噴農藥），作業時間要縮短，休息頻率要增加（Occupational Safety and Health Administration [OSHA], n.d.）。</p></div></div>
      <div class="hps-habit"><div class="hps-habit-num">4</div><div><h4>熱適應訓練（acclimatization）</h4><p>夏季剛開始或休假後回到田間，前 5–7 天要逐步增加作業時間。第一天只做平常的 20%，之後每天增加 20%。身體需要時間調整排汗和散熱機制（OSHA, n.d.；OSHA-TW, 2025）。</p></div></div>
      <div class="hps-habit"><div class="hps-habit-num">5</div><div><h4>留意熱應變的身體訊號</h4><p>熱傷害判斷的重點不在心跳數，而是體感與症狀：<strong>頭痛、頭暈、噁心、心悸、皮膚發燙、肌肉抽筋</strong>任一出現就應立即停工、到陰涼處休息並補水。若出現意識混亂、體溫明顯升高，屬中暑急症須立即送醫（OSHA-TW, 2025；HPA, n.d.）。</p></div></div>
      <div class="hps-habit"><div class="hps-habit-num">6</div><div><h4>建立「夥伴制」互相看顧</h4><p>高溫天田間不要一個人作業。和鄰居、家人約好互相觀察。如果對方突然變得安靜、動作遲緩、說話含糊，這些都是熱傷害的早期徵兆（OSHA, n.d.）。</p></div></div>
    </div>
  </section>

  <!-- ③ 熱-腎連結 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-disease"></i></div>
      <div>
        <h2>為什麼熱<span class="accent">會傷腎</span>？</h2>
        <p>這不只是急性問題——長期累積的傷害更令人擔憂。</p>
      </div>
    </div>
    <p class="hps-p">近年國際研究逐漸揭示「熱與慢性腎臟病」（heat-CKD nexus）的關聯。台灣針對農業縣市的長期追蹤研究發現：</p>
    <div class="hps-kidney-stats">
      <div class="hps-kidney"><span class="hps-kidney-num">+8%</span><span>氣溫每升高 1°C，慢性腎臟病門診率增加（Tseng et al., 2020）</span></div>
      <div class="hps-kidney"><span class="hps-kidney-num">4.3 倍</span><span>中暑患者日後罹患慢性腎臟病的風險（HR = 4.346；Tseng et al., 2020）</span></div>
      <div class="hps-kidney"><span class="hps-kidney-num">9.1 倍</span><span>中暑患者日後進展到末期腎病的風險（HR = 9.078；Tseng et al., 2020）</span></div>
    </div>
    <p class="hps-p">可能機轉：反覆脫水 → 尿液濃縮 → 腎臟持續處於高壓狀態 → 腎小管損傷累積 → 慢性腎臟病。此現象在中美洲甘蔗工人已有大量流行病學證據，台灣的農業縣市也觀察到類似趨勢（Flouris et al., 2018；Tseng et al., 2020）。</p>
    <div class="hps-note">
      <i class="fas fa-lightbulb"></i>
      <span>最好的預防就是充分補水。上述「每 15–20 分鐘喝 150–200 mL」不只是防中暑，也是保護腎臟（OSHA-TW, 2025）。</span>
    </div>
  </section>

  <!-- ④ 中暑急救 SOP -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-kit-medical"></i></div>
      <div>
        <h2>中暑<span class="accent">急救 SOP</span></h2>
        <p>記住這個口訣：<strong>蔭涼 → 脫衣 → 散熱 → 喝水 → 送醫</strong>（HPA, n.d.）。</p>
      </div>
    </div>
    <div class="hps-sop-steps">
      <div class="hps-sop"><div class="hps-sop-num">1</div><div><h4>移到蔭涼處</h4><p>搬到樹蔭、工寮或有冷氣的地方。如果在田中央，先用任何可以遮陽的東西擋住直射陽光。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">2</div><div><h4>鬆開或脫掉多餘衣物</h4><p>解開鈕扣、鬆開腰帶、脫掉防護衣和鞋子，讓身體能散熱。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">3</div><div><h4>積極降溫</h4><p>以濕毛巾或冰塊敷在<strong>脖子兩側、腋下、鼠蹊部</strong>（大血管離皮膚最近，降溫效率最高）。有水管可直接以水沖，並同時搧風（HPA, n.d.）。重症可考慮冷水浸泡（cold water immersion）作為急救黃金方法（Flouris et al., 2018）。</p></div></div>
      <div class="hps-sop"><div class="hps-sop-num">4</div><div><h4>補充水分（僅限意識清醒者）</h4><p>小口小口喝水或含鹽飲料。<strong>意識不清的人絕對不可以灌水</strong>，會嗆到窒息。</p></div></div>
      <div class="hps-sop emergency"><div class="hps-sop-num">5</div><div><h4>撥打 119</h4><p>以下任一情況就要立刻叫救護車：體溫超過 40°C、意識不清或說話含糊、抽搐、降溫後 15 分鐘沒有改善。等待救護車期間持續降溫不要停。</p></div></div>
    </div>
  </section>

  <!-- ⑤ 氣象資源 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-cloud-sun"></i></div>
      <div>
        <h2>善用<span class="accent">氣象資源</span>，提前判斷風險</h2>
        <p>出門前看一眼天氣預報，就能決定今天的作業計畫（中央氣象署，n.d.）。</p>
      </div>
    </div>
    <div class="hps-res-grid">
      <div class="hps-res"><h4><i class="fas fa-mobile-screen"></i> 中央氣象署「生活氣象」APP</h4><p>可查詢鄉鎮級的體感溫度和紫外線指數，並可設定所在鄉鎮推播通知，即時收到高溫特報（中央氣象署，n.d.）。</p></div>
      <div class="hps-res"><h4><i class="fas fa-triangle-exclamation"></i> 三級高溫燈號</h4><p>氣象署依鄉鎮發布黃色（連續 3 天 ≥ 36°C）、橙色（連續 3 天 ≥ 38°C）、紅色（≥ 40°C 或連續 3 天 ≥ 38°C）高溫燈號。看到橙色以上建議取消中午戶外作業（中央氣象署，n.d.）。</p></div>
      <div class="hps-res"><h4><i class="fas fa-industry"></i> 職安署熱危害預警</h4><p>勞動部職安署提供工作場所熱指數預警，可於 <a href="https://hiosha.osha.gov.tw" target="_blank" rel="noopener">hiosha.osha.gov.tw</a> 查詢（OSHA-TW, 2025）。</p></div>
    </div>
  </section>

  <!-- 交叉連結 -->
  <section class="hps-sec hps-cross reveal">
    <h3><i class="fas fa-link"></i> 相關主題</h3>
    <div class="hps-cross-grid">
      <a href="/occupational-safety/greenhouse-zone/"><i class="fas fa-warehouse"></i><span>溫室作業安全</span><small>溫室內溫度更高，防護標準要升一級</small></a>
      <a href="/healthgood/subpage-pesticide/"><i class="fas fa-flask-vial"></i><span>農藥安全</span><small>高溫加速農藥揮發，穿防護衣時散熱更差</small></a>
      <a href="/occupational-safety/personal-protective-equipment/"><i class="fas fa-mask-face"></i><span>個人防護具</span><small>不透氣防護衣的穿著時間限制和替代方案</small></a>
    </div>
  </section>

  <!-- References 全展開（APA 7） -->
  <style>
    .hps-refs-list.apa7 { list-style: none; padding: 0; }
    .hps-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .hps-refs-list.apa7 li em { font-style: italic; }
    .hps-refs-list.apa7 li a { word-break: break-all; }
  </style>
  <section class="hps-sec hps-refs">
    <div class="hps-refs-open">
      <h2><i class="fas fa-book-open"></i> 參考<span class="accent">資料</span></h2>
      <p class="hps-refs-note">本頁依 APA 第 7 版格式引用以下政府機關與國際學術文獻。</p>
      <ul class="hps-refs-list apa7">
        <li>中央氣象署（n.d.）。<em>高溫資訊</em>。https://www.cwa.gov.tw/V8/C/W/OBS_Heat.html</li>
        <li>勞動部職業安全衛生署（2025）。<em>高氣溫戶外作業勞工熱危害預防指引</em>。https://www.osha.gov.tw/48110/48713/48735/60221/</li>
        <li>衛生福利部國民健康署（n.d.）。<em>預防熱傷害衛教專區</em>。https://www.hpa.gov.tw/Pages/List.aspx?nodeid=440</li>
        <li>Flouris, A. D., Dinas, P. C., Ioannou, L. G., Nybo, L., Havenith, G., Kenny, G. P., &amp; Kjellstrom, T. (2018). Workers’ health and productivity under occupational heat strain: A systematic review and meta-analysis. <em>The Lancet Planetary Health, 2</em>(12), e521–e531. https://doi.org/10.1016/S2542-5196(18)30237-7</li>
        <li>Occupational Safety and Health Administration. (n.d.). <em>Heat — Overview</em>. U.S. Department of Labor. https://www.osha.gov/heat</li>
        <li>Tseng, M. F., Chou, C. L., Chung, C. H., Chen, Y. K., Chien, W. C., Feng, C. H., &amp; Chu, P. (2020). Risk of chronic kidney disease in patients with heat injury: A nationwide longitudinal cohort study in Taiwan. <em>PLOS ONE, 15</em>(7), e0235607. https://doi.org/10.1371/journal.pone.0235607</li>
      </ul>
      <p class="hps-refs-note"><em>待核實項目</em>：Hero 內「急診送醫近年顯著增加（74.4% 的精確百分比來源）」需補國民健康署確切年度報告文號。</p>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
