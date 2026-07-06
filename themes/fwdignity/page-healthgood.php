<?php
/**
 * fwdignity · page-healthgood.php v2
 * 健康促進 章節 Hub(slug=healthgood) — 擴充版
 *
 * 🔴 紅線:文字 / 數字 / 引用 1:1 復用原頁。
 * 章節色 HEALTH · Cyan 青色
 *   Main #0891B2 · Deep #164E63 · BG #CFFAFE · Contrast #F59E0B
 */
get_header();
?>

<div class="fw-page-layout">

<main class="hp-main" id="main" role="main">

  <!-- ① Hero -->
  <section class="hp-hero" style="background-image:linear-gradient(135deg,rgba(207,250,254,0.95) 0%,rgba(254,252,247,0.88) 50%,rgba(8,145,178,0.22) 100%),url(https://images.pexels.com/photos/2480070/pexels-photo-2480070.jpeg?w=1400&auto=compress);background-size:cover;background-position:center;">
    <div class="hp-hero-deco" aria-hidden="true">
      <i class="fas fa-heart-pulse hp-deco-1"></i>
      <i class="fas fa-leaf hp-deco-2"></i>
      <i class="fas fa-hand-holding-heart hp-deco-3"></i>
    </div>
    <div class="hp-hero-inner">
      <span class="hp-hero-badge"><i class="fas fa-heart-pulse"></i> 健康促進專區</span>
      <h1 class="hp-hero-title" data-hook-title>農民<span class="accent">健康促進</span></h1>
      <p class="hp-hero-tagline">保護身體、管理風險、照顧心理——從田間到生活的全方位健康指南。</p>
      <div class="hp-hero-intro">
        <p>務農是高體力、高暴露的工作。台灣農民平均年齡超過 62 歲,超過半數已逾 65 歲（行政院主計總處，2023）。在這個年紀還要面對烈日、農藥、重複搬運和不確定的收入,身體和心理都承受很大的壓力。</p>
        <p>健康促進不是「生病了才看醫生」,而是在還沒出問題之前就開始保護自己。這個專區整理了跟農民最直接相關的四大健康主題:</p>
        <ul class="hp-hero-list">
          <li><i class="fas fa-check-circle"></i> <strong>熱危害預防</strong>——了解熱中暑怎麼辨識、怎麼急救、怎麼安排作息</li>
          <li><i class="fas fa-check-circle"></i> <strong>農藥安全</strong>——掌握正確的防護方法和中毒急救步驟</li>
          <li><i class="fas fa-check-circle"></i> <strong>骨骼肌肉保健</strong>——學會暖身伸展,知道什麼時候該就醫</li>
          <li><i class="fas fa-check-circle"></i> <strong>心靈紓壓</strong>——壓力調適方法和求助管道,照顧心理也是照顧健康</li>
        </ul>
      </div>
      <div class="hp-hero-actions">
        <a href="#hp-topics" class="hp-btn-primary" data-magnetic>
          <i class="fas fa-layer-group"></i> 瀏覽四大主題
        </a>
        <a href="#hp-rights" class="hp-btn-secondary">
          <i class="fas fa-hand-holding-heart"></i> 保障你的權益
        </a>
      </div>
    </div>
  </section>

  <!-- ② 關鍵數據 3 卡 -->
  <section class="hp-sec hp-stats reveal">
    <div class="hp-sec-head">
      <span class="hp-kicker"><i class="fas fa-chart-column"></i> DATA · 關鍵數據</span>
      <h2 class="hp-sec-title">農民健康<span class="accent">關鍵數據</span></h2>
    </div>
    <div class="hp-stats-grid">
      <div class="hp-stat-card" style="--sc:#EC4899; --scl:#FCE7F3;">
        <div class="hp-stat-icon"><i class="fas fa-user-clock"></i></div>
        <div class="hp-stat-num" data-count="62">62+<small>歲</small></div>
        <h4>台灣農民平均年齡</h4>
        <p class="hp-stat-source">（行政院主計總處，2023）</p>
      </div>
      <div class="hp-stat-card" style="--sc:#1E40AF; --scl:#DBEAFE;">
        <div class="hp-stat-icon"><i class="fas fa-bone"></i></div>
        <div class="hp-stat-num" data-count="91">91<small>%</small></div>
        <h4>農民曾有肌肉骨骼不適</h4>
        <p class="hp-stat-source">（Osborne et al., 2012）</p>
      </div>
      <div class="hp-stat-card" style="--sc:#DC2626; --scl:#FEE2E2;">
        <div class="hp-stat-icon"><i class="fas fa-temperature-arrow-up"></i></div>
        <div class="hp-stat-num" data-count="71.3">71.3<small>%</small></div>
        <h4>農漁民感知高溫風險</h4>
        <p class="hp-stat-source">（國家衛生研究院 [國衛院], 2024；來源待確認）</p>
      </div>
    </div>
  </section>

  <!-- ③ 4 主題大卡 + 6 重點 icon -->
  <section class="hp-sec hp-topics" id="hp-topics">
    <div class="hp-sec-head">
      <span class="hp-kicker"><i class="fas fa-layer-group"></i> FOUR TOPICS · 四大健康主題</span>
      <h2 class="hp-sec-title">農民最常面對的<span class="accent">四種健康風險</span></h2>
      <p class="hp-sec-sub">每一種都有對應的預防方法。點進任一主題進入深度專區,獲得完整預防知識、操作步驟與就醫建議。</p>
    </div>

    <!-- 01 熱危害 -->
    <article class="hp-topic reveal" style="--tc:#DC2626; --tcl:#FEE2E2;">
      <header class="hp-topic-head">
        <div class="hp-topic-num">01</div>
        <div class="hp-topic-icon"><i class="fas fa-temperature-arrow-up"></i></div>
        <div class="hp-topic-heading">
          <h3>熱危害預防</h3>
          <p class="hp-topic-intro">雲嘉地區夏季高溫經常超過 35°C,溫室內部更可能突破 40°C。農民長時間在烈日下作業,是熱中暑和熱衰竭的高風險族群。更值得注意的是,長期熱暴露與慢性腎臟病風險顯著相關——中暑患者日後罹患慢性腎臟病的風險為一般人的 <strong>4.3 倍</strong>（Tseng et al., 2020）。</p>
        </div>
        <a href="/healthgood/subpage-heat/" class="hp-topic-cta"><i class="fas fa-arrow-right"></i></a>
      </header>
      <div class="hp-topic-icons">
        <div class="hp-topic-mini"><i class="fas fa-head-side-virus"></i><h5>熱中暑 vs 熱衰竭</h5><p>體溫 ≥40°C 且意識改變 = 中暑,是急診狀況,死亡率超過 30%（來源待確認）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-droplet"></i><h5>喝水規則</h5><p>每 15-20 分鐘喝 150-200 mL,不要等到渴了才喝（勞動部，2025）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-clock"></i><h5>避開高溫時段</h5><p>建議:清晨 5:00-10:00 作業 → 中午休息 → 下午 15:00-18:00 作業</p></div>
        <div class="hp-topic-mini"><i class="fas fa-heart-pulse"></i><h5>熱應變症狀監測</h5><p>出現頭痛、頭暈、噁心、心悸、皮膚發燙任一項，就要立刻停工到陰涼處補水</p></div>
        <div class="hp-topic-mini"><i class="fas fa-disease"></i><h5>熱與腎臟的關係</h5><p>長期熱暴露會傷腎,中暑患者日後的慢性腎臟病風險比一般人高出數倍（Tseng et al., 2020）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-kit-medical"></i><h5>中暑急救</h5><p>移到陰涼處 → 冰敷脖子腋下鼠蹊部 → 意識不清立刻打 119</p></div>
      </div>
    </article>

    <!-- 02 農藥安全 -->
    <article class="hp-topic reveal" style="--tc:#7C3AED; --tcl:#EDE9FE;">
      <header class="hp-topic-head">
        <div class="hp-topic-num">02</div>
        <div class="hp-topic-icon"><i class="fas fa-flask-vial"></i></div>
        <div class="hp-topic-heading">
          <h3>農藥安全</h3>
          <p class="hp-topic-intro">農藥是農業的必要工具,但使用不當就是毒物。台灣每年仍有約 390 人因非自殺性農藥中毒住院（來源待確認）。最大的風險不是吸入,而是皮膚接觸——超過 <strong>95%</strong> 的職業性農藥暴露發生在皮膚,尤其是手部（來源待確認）。很多農友噴藥時戴了口罩,卻沒戴手套,等於最大的破口完全沒有防護。</p>
        </div>
        <a href="/healthgood/subpage-pesticide/" class="hp-topic-cta"><i class="fas fa-arrow-right"></i></a>
      </header>
      <div class="hp-topic-icons">
        <div class="hp-topic-mini"><i class="fas fa-hand"></i><h5>皮膚是最大破口</h5><p>超過 95% 的農藥暴露經由皮膚吸收,手部和前臂是最常接觸的部位（來源待確認）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-triangle-exclamation"></i><h5>急性中毒症狀</h5><p>流口水、瞳孔縮小、肌肉抽搐、呼吸困難 → 立刻打 119</p></div>
        <div class="hp-topic-mini"><i class="fas fa-clock-rotate-left"></i><h5>安全採收期</h5><p>最後一次噴藥到採收的最短天數,標示在農藥瓶標籤上,務必遵守</p></div>
        <div class="hp-topic-mini"><i class="fas fa-wind"></i><h5>再進入間隔(REI)</h5><p>農藥標籤標示再進入間隔，視農藥種類由 4 小時至 72 小時不等（EPA, n.d.）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-phone-volume"></i><h5>中毒急救電話</h5><p>毒藥物防治諮詢中心 24 小時專線:<strong>02-2871-7121</strong></p></div>
        <div class="hp-topic-mini"><i class="fas fa-ban"></i><h5>巴拉刈已禁用</h5><p>2020 年全面禁用,農藥中毒自殺率下降 37%,證實管制有效（來源待確認）</p></div>
      </div>
    </article>

    <!-- 03 骨骼肌肉 -->
    <article class="hp-topic reveal" style="--tc:#1E40AF; --tcl:#DBEAFE;">
      <header class="hp-topic-head">
        <div class="hp-topic-num">03</div>
        <div class="hp-topic-icon"><i class="fas fa-bone"></i></div>
        <div class="hp-topic-heading">
          <h3>骨骼肌肉保健</h3>
          <p class="hp-topic-intro">國際研究顯示,超過 <strong>90%</strong> 的農民一生中會經歷肌肉骨骼疼痛（Osborne et al., 2012）。最常見的部位是腰部、膝蓋和肩膀,跟農務中的蹲姿、搬運和仰頭動作直接相關。國衛院的台灣農民健康世代研究也發現,農民的髖關節退化、膝關節退化和膝蓋滑囊炎風險顯著高於一般人（國衛院, 2024；來源待確認）。</p>
        </div>
        <a href="/healthgood/subpage-msd/" class="hp-topic-cta"><i class="fas fa-arrow-right"></i></a>
      </header>
      <div class="hp-topic-icons">
        <div class="hp-topic-mini"><i class="fas fa-person-walking-arrow-loop-left"></i><h5>腰痛</h5><p>農民下背痛盛行率 63-87%,長時間彎腰和搬重物是主因（Osborne et al., 2012）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-person-falling"></i><h5>膝蓋問題</h5><p>稻農膝關節疼痛盛行率 54-80%,長時間蹲姿是最大元兇（Osborne et al., 2012）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-hand-holding-medical"></i><h5>肩膀傷害</h5><p>果農旋轉肌撕裂盛行率 60%,長時間仰頭採摘修剪造成（來源待確認）</p></div>
        <div class="hp-topic-mini"><i class="fas fa-dumbbell"></i><h5>工前暖身</h5><p>5-10 分鐘暖身可顯著降低傷害風險,深蹲、手臂伸展、軀幹旋轉</p></div>
        <div class="hp-topic-mini"><i class="fas fa-stethoscope"></i><h5>什麼時候該看醫生</h5><p>疼痛持續超過 2 週、愈來愈嚴重、出現麻木感 → 盡早就醫</p></div>
        <div class="hp-topic-mini"><i class="fas fa-hospital"></i><h5>雲嘉復健資源</h5><p>台大雲林分院、雲林長庚、嘉義長庚、嘉基、大林慈濟均設有復健科</p></div>
      </div>
    </article>

    <!-- 04 心靈紓壓 -->
    <article class="hp-topic reveal" style="--tc:#15803D; --tcl:#DCFCE7;">
      <header class="hp-topic-head">
        <div class="hp-topic-num">04</div>
        <div class="hp-topic-icon"><i class="fas fa-brain"></i></div>
        <div class="hp-topic-heading">
          <h3>心靈紓壓</h3>
          <p class="hp-topic-intro">農民面對的壓力是多重的：天候不確定、收入不穩定、身體慢性疼痛、人口外移後的孤獨感。世界衛生組織指出，社會孤立與慢性病痛是高齡人口憂鬱風險的重要因子（WHO, 2023）。針對台灣民眾的研究顯示，民眾對精神疾病的汙名仍顯著影響求助意願（Zhuang et al., 2017）——這意味著很多需要幫助的農友從來沒有尋求過幫助。2020 年全面禁用巴拉刈後，整體農藥自殺率下降 37%、巴拉刈自殺下降 58%（Chang et al., 2022）。</p>
        </div>
        <a href="/healthgood/subpage-mental/" class="hp-topic-cta"><i class="fas fa-arrow-right"></i></a>
      </header>
      <div class="hp-topic-icons">
        <div class="hp-topic-mini"><i class="fas fa-cloud-rain"></i><h5>農民特有壓力源</h5><p>看天吃飯、收入波動、獨居孤立、身體慢性疼痛</p></div>
        <div class="hp-topic-mini"><i class="fas fa-lungs"></i><h5>腹式呼吸</h5><p>最簡單的紓壓法:吸氣 4 秒、吐氣 8 秒,每天 10 分鐘,田邊就能做</p></div>
        <div class="hp-topic-mini"><i class="fas fa-phone"></i><h5>安心專線 1925</h5><p>24 小時免費,衛福部設置的心理諮詢專線,有人聽你說</p></div>
        <div class="hp-topic-mini"><i class="fas fa-people-group"></i><h5>社群連結很重要</h5><p>參加農會活動、廟口聚會、鄰里互動,人際連結是最好的心理疫苗</p></div>
        <div class="hp-topic-mini"><i class="fas fa-moon"></i><h5>睡眠品質</h5><p>固定作息、睡前避免咖啡和酒精、中午可短睡 15-20 分鐘</p></div>
        <div class="hp-topic-mini"><i class="fas fa-heart"></i><h5>求助是勇敢</h5><p>尋求幫助不是軟弱,是對自己和家人負責任的表現</p></div>
      </div>
    </article>
  </section>

  <!-- ④ 健康風險速篩 -->
  <section class="hp-sec hp-quiz reveal" id="hp-quiz">
    <div class="hp-sec-head">
      <span class="hp-kicker"><i class="fas fa-clipboard-check"></i> QUICK CHECK · 健康風險速篩</span>
      <h2 class="hp-sec-title">兩步驟了解<span class="accent">你目前的健康風險</span></h2>
      <p class="hp-sec-sub">不需要任何專業知識,選擇年齡 + 勾選作業狀況,馬上看到結果與最需要關注的主題。</p>
    </div>

    <div class="hp-quiz-card">
      <div class="hp-quiz-disc">
        <i class="fas fa-info-circle"></i>
        <div>
          <strong>關於這個工具</strong>
          <p>本速篩依據台灣職安署《高氣溫作業熱危害預防指引》、農委會農藥安全使用規範,以及國際農民肌肉骨骼疾病流行病學文獻（Osborne et al., 2012）中確認的主要風險因子編製。年齡加權依據台灣農民健康世代研究。本工具為<strong>自我覺察用途</strong>,非臨床診斷工具。</p>
        </div>
      </div>

      <div class="hp-quiz-step">
        <label class="hp-quiz-q"><span class="hp-quiz-n">1</span>您的年齡區間是?</label>
        <div class="hp-quiz-ages" id="hp-quiz-ages">
          <button class="hp-quiz-age" data-age="0" type="button">50 歲以下</button>
          <button class="hp-quiz-age" data-age="1" type="button">50-64 歲</button>
          <button class="hp-quiz-age" data-age="2" type="button">65 歲以上</button>
        </div>
      </div>

      <div class="hp-quiz-step">
        <label class="hp-quiz-q"><span class="hp-quiz-n">2</span>以下哪些狀況符合您目前的情形?(可複選)</label>
        <div class="hp-quiz-checks">
          <label class="hp-quiz-item" data-topic="heat"><input type="checkbox"><span>每天在戶外作業超過 4 小時</span><span class="hp-quiz-tag" style="--tt:#DC2626;--ttb:#FEE2E2">熱危害</span></label>
          <label class="hp-quiz-item" data-topic="heat"><input type="checkbox"><span>夏天幾乎不避開中午高溫時段(10:00-15:00)</span><span class="hp-quiz-tag" style="--tt:#DC2626;--ttb:#FEE2E2">熱危害</span></label>
          <label class="hp-quiz-item" data-topic="heat"><input type="checkbox"><span>作業中很少主動喝水(通常等到渴了才喝)</span><span class="hp-quiz-tag" style="--tt:#DC2626;--ttb:#FEE2E2">熱危害</span></label>
          <label class="hp-quiz-item" data-topic="pesticide"><input type="checkbox"><span>噴灑農藥時沒有戴耐化學手套</span><span class="hp-quiz-tag" style="--tt:#7C3AED;--ttb:#EDE9FE">農藥</span></label>
          <label class="hp-quiz-item" data-topic="pesticide"><input type="checkbox"><span>噴灑農藥時只戴一般口罩(非防毒面具)</span><span class="hp-quiz-tag" style="--tt:#7C3AED;--ttb:#EDE9FE">農藥</span></label>
          <label class="hp-quiz-item" data-topic="pesticide"><input type="checkbox"><span>操作農機時沒有戴耳塞或耳罩</span><span class="hp-quiz-tag" style="--tt:#7C3AED;--ttb:#EDE9FE">安全</span></label>
          <label class="hp-quiz-item" data-topic="msd"><input type="checkbox"><span>每天需搬運超過 15 公斤重物</span><span class="hp-quiz-tag" style="--tt:#1E40AF;--ttb:#DBEAFE">骨骼肌肉</span></label>
          <label class="hp-quiz-item" data-topic="msd"><input type="checkbox"><span>經常需要蹲著或彎腰超過 1 小時</span><span class="hp-quiz-tag" style="--tt:#1E40AF;--ttb:#DBEAFE">骨骼肌肉</span></label>
          <label class="hp-quiz-item" data-topic="msd"><input type="checkbox"><span>最近經常覺得腰痠背痛或膝蓋痛</span><span class="hp-quiz-tag" style="--tt:#1E40AF;--ttb:#DBEAFE">骨骼肌肉</span></label>
          <label class="hp-quiz-item" data-topic="mental"><input type="checkbox"><span>最近心情低落、睡不好、或對事情失去興趣</span><span class="hp-quiz-tag" style="--tt:#15803D;--ttb:#DCFCE7">心靈</span></label>
        </div>
      </div>

      <button class="hp-quiz-submit" id="hp-quiz-submit" type="button"><i class="fas fa-magnifying-glass-chart"></i> 查看結果</button>

      <div class="hp-quiz-result" id="hp-quiz-result" hidden>
        <div class="hp-quiz-result-head">
          <div class="hp-quiz-result-icon" id="hp-quiz-result-icon"><i class="fas fa-circle-check"></i></div>
          <div>
            <h4 id="hp-quiz-result-level">低風險</h4>
            <p id="hp-quiz-result-msg">你目前的防護狀況不錯,繼續保持。</p>
          </div>
        </div>
        <div class="hp-quiz-topics" id="hp-quiz-topics"></div>
        <button class="hp-quiz-reset" id="hp-quiz-reset" type="button"><i class="fas fa-rotate-left"></i> 重新評估</button>
      </div>
    </div>
  </section>

  <!-- ⑤ 今天能下田嗎(熱指數表 + 心跳計算) -->
  <section class="hp-sec hp-heat-tool reveal" id="hp-heat-tool">
    <div class="hp-sec-head">
      <span class="hp-kicker"><i class="fas fa-sun"></i> HEAT TOOL · 今天能下田嗎</span>
      <h2 class="hp-sec-title">對照氣溫濕度,查看<span class="accent">農作建議與補水頻率</span></h2>
      <p class="hp-sec-sub"><strong>直射陽光下或穿不透氣防護衣時,風險等級要再往上升一級。</strong></p>
    </div>
    <div class="hp-heat-row">

      <div class="hp-heat-table-card">
        <h3><i class="fas fa-temperature-half"></i> 熱指數風險對照表</h3>
        <p class="hp-heat-note">熱指數 = 結合氣溫和相對濕度的體感溫度。可用中央氣象署「生活氣象」APP 查詢。</p>
        <div class="hp-heat-table-wrap">
          <table class="hp-heat-table">
            <thead><tr><th>熱指數</th><th>風險等級</th><th>農作建議</th></tr></thead>
            <tbody>
              <tr><td><strong>&lt; 27°C</strong></td><td><span class="hp-heat-badge" style="background:#81C784;color:#fff;">正常</span></td><td>可正常作業,注意補水</td></tr>
              <tr><td><strong>27-32°C</strong></td><td><span class="hp-heat-badge" style="background:#FFD54F;color:#333;">第一級</span></td><td>重體力活增加休息頻率,每 30 分鐘補水</td></tr>
              <tr><td><strong>32-41°C</strong></td><td><span class="hp-heat-badge" style="background:#FFB74D;color:#fff;">第二級</span></td><td>減少戶外作業時間,避開 10:00-15:00,每 15-20 分鐘補水</td></tr>
              <tr><td><strong>41-54°C</strong></td><td><span class="hp-heat-badge" style="background:#EF5350;color:#fff;">第三級</span></td><td>避免戶外作業,改清晨或傍晚,穿防護衣再升一級防護</td></tr>
              <tr><td><strong>≥ 54°C</strong></td><td><span class="hp-heat-badge" style="background:#424242;color:#fff;">第四級</span></td><td>停止所有非緊急戶外作業</td></tr>
            </tbody>
          </table>
        </div>
        <p class="hp-heat-src"><i class="fas fa-info-circle"></i> 依據勞動部《高氣溫作業熱危害預防指引》第三版（勞動部，2025）。查詢熱指數:<a href="https://hiosha.osha.gov.tw" target="_blank" rel="noopener">hiosha.osha.gov.tw</a></p>
      </div>

      <div class="hp-hr-card">
        <h3><i class="fas fa-heart-pulse"></i> 心跳警戒值計算</h3>
        <p class="hp-hr-desc">高溫下作業時,心跳是最簡單的身體警報器。輸入你的年齡,馬上知道心跳上限。</p>
        <div class="hp-hr-input-row">
          <input type="number" id="hp-hr-age" min="15" max="100" placeholder="例如 65" class="hp-hr-input">
          <button type="button" id="hp-hr-calc" class="hp-hr-btn">計算</button>
        </div>
        <div class="hp-hr-result" id="hp-hr-result" hidden>
          <div class="hp-hr-big"><span id="hp-hr-bpm">115</span><small>下/分鐘</small></div>
          <p>工作中心跳一旦超過這個數字,<strong>立刻停下來到陰涼處休息</strong>,補充水分,等心跳恢復正常再繼續。</p>
          <div class="hp-hr-how">
            <strong><i class="fas fa-hand"></i> 怎麼量心跳?</strong>
            <p>把食指和中指輕壓在手腕內側(靠近拇指那一側),數 15 秒的跳動次數,再乘以 4。</p>
          </div>
        </div>
        <div class="hp-hr-examples">
          <p><strong>常見年齡對照:</strong></p>
          <div class="hp-hr-ex">
            <span>50 歲 → <strong>130</strong></span>
            <span>55 歲 → <strong>125</strong></span>
            <span>60 歲 → <strong>120</strong></span>
            <span>65 歲 → <strong>115</strong></span>
            <span>70 歲 → <strong>110</strong></span>
            <span>75 歲 → <strong>105</strong></span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ⑥ 保障你的權益 3 欄 -->
  <section class="hp-sec hp-rights" id="hp-rights">
    <div class="hp-sec-head">
      <span class="hp-kicker"><i class="fas fa-hand-holding-heart"></i> YOUR RIGHTS · 保障權益</span>
      <h2 class="hp-sec-title">保障你的<span class="accent">權益</span></h2>
      <p class="hp-sec-sub">保險怎麼保?生病去哪裡看?緊急電話是什麼?一次整理清楚。</p>
    </div>

    <div class="hp-rights-grid">

      <!-- 欄 1:農職保 -->
      <div class="hp-rights-col reveal">
        <div class="hp-rights-icon" style="--rc:#15803D; --rcl:#DCFCE7;">
          <i class="fas fa-shield-halved"></i>
        </div>
        <h3>農民職業災害保險</h3>
        <p class="hp-rights-desc">務農受傷也有保障。自願加保,農會辦理。</p>

        <dl class="hp-info-list">
          <div><dt>月繳保費</dt><dd><strong>NT$25</strong><br><small>雲林縣政府全額補助,農友免繳</small></dd></div>
          <div><dt>傷病給付</dt><dd>第 1-2 月:NT$680/日<br>第 3 月起:NT$476/日<br>最長給付 2 年</dd></div>
          <div><dt>就醫津貼</dt><dd>門診 NT$100/日<br>住院 NT$1,200/日（勞動部勞工保險局，2024）</dd></div>
          <div><dt>身心障礙</dt><dd>依等級最高 NT$612,000</dd></div>
          <div><dt>加保方式</dt><dd>攜帶身分證到<strong>戶籍所在地農會</strong>辦理</dd></div>
          <div><dt>涵蓋範圍</dt><dd>農機傷害、農藥中毒、蛇咬、中暑、墜落等</dd></div>
        </dl>

        <div class="hp-rights-highlight">
          <i class="fas fa-chart-line"></i>
          <span>雲林縣投保率 <strong>50.42%</strong>(全國最高)（來源待確認）</span>
        </div>

        <div class="hp-rights-disclaimer">
          <i class="fas fa-exclamation-triangle"></i>
          <div>
            <strong>資料僅供參考</strong>
            <p>以上農職保資訊依據勞動部勞工保險局公開資料整理（勞動部勞工保險局，2024）。保費、給付金額與補助政策可能隨法規調整而變動。<strong>最新資訊請洽戶籍所在地農會,或撥打勞保局服務專線 02-2396-1266。</strong></p>
            <p>
              <a href="https://www.bli.gov.tw/0100395.html" target="_blank" rel="noopener">勞保局・農職保專區</a>
              <a href="https://www.yunlin.gov.tw/" target="_blank" rel="noopener">雲林縣政府</a>
            </p>
          </div>
        </div>
      </div>

      <!-- 欄 2:就醫資源 -->
      <div class="hp-rights-col reveal">
        <div class="hp-rights-icon" style="--rc:#1E40AF; --rcl:#DBEAFE;">
          <i class="fas fa-hospital"></i>
        </div>
        <h3>雲嘉地區就醫資源</h3>
        <p class="hp-rights-desc">以下醫院距離雲嘉農業區較近,設有相關科別。</p>

        <div class="hp-hosp-list">
          <div class="hp-hosp">
            <h4>台大醫院雲林分院</h4>
            <div class="hp-hosp-tags"><span>職業醫學科</span><span>復健科</span><span>農業健康中心</span></div>
            <p class="hp-hosp-note">台灣首座農業環境與職業健康中心所在地</p>
          </div>
          <div class="hp-hosp">
            <h4>雲林長庚醫院</h4>
            <div class="hp-hosp-tags"><span>復健科</span><span>骨科</span><span>慢性病管理</span></div>
          </div>
          <div class="hp-hosp">
            <h4>嘉義長庚醫院</h4>
            <div class="hp-hosp-tags"><span>復健科 8 位醫師</span><span>精神科</span></div>
            <p class="hp-hosp-note">69 床復健病房,雲嘉地區規模最大（來源待確認）</p>
          </div>
          <div class="hp-hosp">
            <h4>嘉義基督教醫院</h4>
            <div class="hp-hosp-tags"><span>復健科</span><span>職業病評估</span></div>
          </div>
          <div class="hp-hosp">
            <h4>大林慈濟醫院</h4>
            <div class="hp-hosp-tags"><span>復健科</span><span>腎臟內科</span><span>社區照護</span></div>
          </div>
        </div>
      </div>

      <!-- 欄 3:緊急聯絡 -->
      <div class="hp-rights-col reveal">
        <div class="hp-rights-icon" style="--rc:#DC2626; --rcl:#FEE2E2;">
          <i class="fas fa-phone-flip"></i>
        </div>
        <h3>緊急聯絡資訊</h3>
        <p class="hp-rights-desc">存在手機裡,需要的時候不用找。</p>

        <div class="hp-phone-list">
          <a href="tel:119" class="hp-phone is-emergency">
            <span class="hp-phone-num">119</span>
            <span class="hp-phone-desc">火災、急救、緊急醫療</span>
          </a>
          <a href="tel:1925" class="hp-phone is-mental">
            <span class="hp-phone-num">1925</span>
            <span class="hp-phone-desc">安心專線(24hr 心理諮詢)</span>
          </a>
          <a href="tel:1995" class="hp-phone">
            <span class="hp-phone-num">1995</span>
            <span class="hp-phone-desc">生命線</span>
          </a>
          <a href="tel:1980" class="hp-phone">
            <span class="hp-phone-num">1980</span>
            <span class="hp-phone-desc">張老師專線</span>
          </a>
          <a href="tel:0228717121" class="hp-phone is-poison">
            <span class="hp-phone-num">02-2871-7121</span>
            <span class="hp-phone-desc">毒藥物防治諮詢中心(24hr)</span>
          </a>
          <a href="tel:0800022228" class="hp-phone">
            <span class="hp-phone-num">0800-022-228</span>
            <span class="hp-phone-desc">植物病蟲害諮詢(免費)</span>
          </a>
        </div>

        <div class="hp-local-contacts">
          <h4><i class="fas fa-map-marker-alt"></i> 地方心理衛生中心</h4>
          <p>雲林縣:05-5523338</p>
          <p>嘉義縣:05-2949193</p>
        </div>
      </div>

    </div>
  </section>

  <!-- ⑤ 交叉連結 -->
  <section class="hp-sec hp-cross reveal">
    <div class="hp-cross-card">
      <div class="hp-cross-icon"><i class="fas fa-link"></i></div>
      <div class="hp-cross-body">
        <h3>延伸閱讀</h3>
        <p>也推薦你看 <a href="/occupational-safety/">職業安全專區</a>——了解個人防護具穿戴、省力農機補助、溫室作業安全。健康與安全是一體兩面。</p>
      </div>
    </div>
  </section>

  <!-- ⑥ References 全展開（APA 7） -->
  <style>
    .hp-refs-list.apa7 { list-style: none; padding: 0; }
    .hp-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .hp-refs-list.apa7 li em { font-style: italic; }
    .hp-refs-list.apa7 li a { word-break: break-all; }
  </style>
  <section class="hp-sec hp-refs">
    <div class="hp-refs-open">
      <div class="hp-sec-head">
        <span class="hp-kicker"><i class="fas fa-book-open"></i> REFERENCES · 參考資料</span>
        <h2 class="hp-sec-title" style="margin:0;">參考<span class="accent">資料</span></h2>
        <p class="hp-refs-note">本頁內容依 APA 第 7 版格式引用以下台灣政府、國際職業安全與農業健康機構公開資料與學術文獻。部分內文數據標示「來源待確認」者，將於後續版本補齊或刪除。</p>
      </div>
      <ul class="hp-refs-list apa7">
        <li>行政院主計總處（2023）。<em>112 年農家戶口及經濟調查統計結果</em>。https://www.stat.gov.tw/</li>
        <li>勞動部（2025）。<em>高氣溫戶外作業勞工熱危害預防指引</em>（第三版）。勞動部職業安全衛生署。https://www.osha.gov.tw/</li>
        <li>勞動部勞工保險局（2024 年 11 月）。<em>農民職業災害保險就醫津貼調整公告（113/11/6 起適用）</em>。https://www.bli.gov.tw/0100668.html</li>
        <li>國家衛生研究院（2024）。<em>台灣農民健康世代研究數據</em>。國家衛生研究院農業與環境健康中心。</li>
        <li>Centers for Disease Control and Prevention, National Institute for Occupational Safety and Health. (2024). <em>Agricultural safety</em>. https://www.cdc.gov/niosh/agriculture/about/index.html</li>
        <li>Chang, S.-S., Lin, C.-Y., Lee, M.-B., Shen, L.-J., Gunnell, D., &amp; Eddleston, M. (2022). The early impact of paraquat ban on suicide in Taiwan. <em>Clinical Toxicology, 60</em>(1), 131–135. https://doi.org/10.1080/15563650.2021.1937642</li>
        <li>Liu, S. I., Lu, R. B., &amp; Lee, M. B. (2008). Community mental health services in Taiwan. <em>Taiwanese Journal of Psychiatry, 22</em>(4), 263–274.</li>
        <li>U.S. Bureau of Labor Statistics. (2023). <em>National census of fatal occupational injuries in 2022</em> (USDL-23-2615). https://www.bls.gov/news.release/archives/cfoi_12192023.pdf</li>
        <li>U.S. Environmental Protection Agency. (n.d.). <em>Restrictions to protect workers after pesticide applications</em>. Retrieved April 24, 2026, from https://www.epa.gov/pesticide-worker-safety/restrictions-protect-workers-after-pesticide-applications</li>
        <li>Wang, W.-Y., Chen, C.-M., Wu, W.-T., &amp; et al. (2023). Occupational health surveillance and detection of emerging occupational diseases among Taiwan farmers, through analysis of national-based farmers' and medico-administrative databases. <em>American Journal of Industrial Medicine, 66</em>(1), 85–93. https://doi.org/10.1002/ajim.23443</li>
        <li>Zhuang, X. Y., Wong, D. F. K., Cheng, C.-W., &amp; Pan, S.-M. (2017). Mental health literacy, stigma and perception of causation of mental illness among Chinese people in Taiwan. <em>International Journal of Social Psychiatry, 63</em>(6), 498–507. https://doi.org/10.1177/0020764017719303</li>
        <li>Occupational Safety and Health Administration. (n.d.). <em>Heat — Overview</em>. U.S. Department of Labor. https://www.osha.gov/heat</li>
        <li>Osborne, A., Blake, C., Fullen, B. M., Meredith, D., Phelan, J., McNamara, J., &amp; Cunningham, C. (2012). Prevalence of musculoskeletal disorders among farmers: A systematic review. <em>American Journal of Industrial Medicine, 55</em>(2), 143–158. <a href="https://doi.org/10.1002/ajim.21033" target="_blank" rel="noopener">https://doi.org/10.1002/ajim.21033</a></li>
        <li>Tseng, M. F., Chou, C. L., Chung, C. H., Chen, Y. K., Chien, W. C., Feng, C. H., &amp; Chu, P. (2020). Risk of chronic kidney disease in patients with heat injury: A nationwide longitudinal cohort study in Taiwan. <em>PLOS ONE, 15</em>(7), e0235607. <a href="https://doi.org/10.1371/journal.pone.0235607" target="_blank" rel="noopener">https://doi.org/10.1371/journal.pone.0235607</a></li>
        <li>World Health Organization. (2022). <em>Pesticide residues in food</em>. https://www.who.int/news-room/fact-sheets/detail/pesticide-residues-in-food</li>
      </ul>
      <h4 class="hp-refs-subhead"><i class="fas fa-camera"></i> 圖片來源</h4>
      <p class="hp-refs-note">Hero 背景取自 <a href="https://www.pexels.com/license/" target="_blank" rel="noopener">Pexels</a>（photo 2480070），依 Pexels License 可免費商用。</p>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
