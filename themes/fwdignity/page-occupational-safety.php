<?php
/**
 * Template Name: 職業安全
 * Slug: occupational-safety
 * fwdignity theme — page-occupational-safety.php
 * 職業安全 章節 Hub 頁(slug=occupational-safety)
 *
 * 🔴 紅線:所有文字、數字、引用來源 1:1 復用原頁。僅重組版面 + 新增動畫。
 * 互動工具 Tab / 風險評估 移至子頁(PPE / 省工農機)。
 *
 * 章節色 SAFETY:
 *   Main #EA580C · Deep #7C2D12 · BG #FFEDD5 · Contrast #15803D
 */

get_header();
?>

<div class="fw-page-layout">

<main class="os-main" id="main" role="main">

  <!-- ① Hero -->
  <section class="os-hero" style="background-image:linear-gradient(135deg,rgba(255,237,213,0.96) 0%,rgba(254,252,247,0.85) 45%,rgba(234,88,12,0.28) 100%),url(https://images.pexels.com/photos/32047262/pexels-photo-32047262.jpeg?w=1400&auto=compress);background-size:cover;background-position:center;">
    <div class="os-hero-deco" aria-hidden="true">
      <i class="fas fa-helmet-safety os-deco-1"></i>
      <i class="fas fa-shield-halved os-deco-2"></i>
      <i class="fas fa-tractor os-deco-3"></i>
    </div>
    <div class="os-hero-inner">
      <span class="os-hero-badge"><i class="fas fa-helmet-safety"></i> 職業安全專區</span>
      <h1 class="os-hero-title" data-hook-title>農業<span class="accent">職業安全</span></h1>
      <p class="os-hero-tagline">從裝備到制度,降低田間每一個風險因子。</p>
      <div class="os-hero-intro">
        <p>務農是一份辛苦的工作,每天面對日曬、農藥、重物搬運和機具操作,身體承受的風險其實比許多行業都高。但很多傷害是可以預防的。</p>
        <ul class="os-hero-list">
          <li><i class="fas fa-check-circle"></i> 了解田間常見的四大危害(物理、化學、生物、人因)</li>
          <li><i class="fas fa-check-circle"></i> 認識 NIOSH 安全控制層級的五個優先順序</li>
          <li><i class="fas fa-check-circle"></i> 查看該穿戴哪些防護具、哪些農機可以減輕負擔</li>
          <li><i class="fas fa-check-circle"></i> 了解政府的農機補助,省錢又省力</li>
        </ul>
      </div>
      <div class="os-hero-actions">
        <a href="#os-subpages" class="os-btn-primary" data-magnetic>
          <i class="fas fa-folder-open"></i> 瀏覽 3 大深度專區
        </a>
        <a href="#os-hierarchy" class="os-btn-secondary">
          <i class="fas fa-layer-group"></i> 看 NIOSH 控制層級
        </a>
      </div>
    </div>
  </section>

  <!-- ② Key Stats(精選 3 卡自原頁 6 卡) -->
  <section class="os-sec os-stats reveal">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-chart-bar"></i> DATA · 全球與台灣農業職災概況</span>
      <h2 class="os-sec-title">用數據說話——<span class="accent">農業是全球最危險的行業之一</span></h2>
    </div>
    <div class="os-stats-grid">
      <div class="os-stat-card" style="--sc:#EF4444; --scl:#FEE2E2;">
        <div class="os-stat-num" data-count="18.6">18.6<small>/10萬</small></div>
        <h4>農業致死率（美國）</h4>
        <p>2022 年美國農林漁獵業致死率為每 10 萬全時當量工作者（FTE）18.6 人，是全產業平均 3.7 的 <strong>5 倍</strong>，交通運輸事故為首要死因（National Institute for Occupational Safety and Health [NIOSH], 2024）。</p>
      </div>
      <div class="os-stat-card" style="--sc:#3B82F6; --scl:#DBEAFE;">
        <div class="os-stat-num">≈<span data-count="30">30</span><small>%</small></div>
        <h4>滑跌墜傷佔農業非致命傷害</h4>
        <p>美國 2021–2022 年非致命農業傷害中，約三成為滑跌墜（slip, trip, fall, STF）造成，且普遍存在低報現象（NIOSH, 2024）。</p>
      </div>
      <div class="os-stat-card" style="--sc:#7C3AED; --scl:#EDE9FE;">
        <div class="os-stat-num"><span data-count="64">64</span><small>歲+</small></div>
        <h4>台灣農民平均年齡</h4>
        <p>台灣農民平均年齡約 64 歲，務農人口快速老化；<strong>高齡勞動加上高強度反覆作業</strong>，慢性肌肉骨骼疾病盛行率升高（行政院主計總處，2023）。</p>
      </div>
      <div class="os-stat-card" style="--sc:#0D9488; --scl:#CCFBF1;">
        <div class="os-stat-num">2024<small>升級</small></div>
        <h4>農職保制度沿革</h4>
        <p>農民職業災害保險自 2018 年 11 月試辦、2023 年 12 月起申請農保者強制併加、<strong>2024 年 11 月就醫津貼升級</strong>（門診 100 元／住院 1,200 元每日）（勞動部勞工保險局，2024）。</p>
      </div>
      <div class="os-stat-card" style="--sc:#F59E0B; --scl:#FEF3C7;">
        <div class="os-stat-num" data-count="8.79">8.79<small>億人</small></div>
        <h4>全球農業就業人口</h4>
        <p>2023 年全球約 8.79 億人從事農業，占就業人口 28%；亞太地區最多（5.51 億），普遍面臨缺乏勞動與社會保護的困境（International Labour Organization [ILO], 2023）。</p>
      </div>
      <div class="os-stat-card" style="--sc:#E65100; --scl:#FFEDD5;">
        <div class="os-stat-num">1,000<small>+</small></div>
        <h4>全球農藥種類</h4>
        <p>全球使用超過 <strong>1,000 種農藥</strong>，慢性暴露可能導致癌症與生殖系統影響，直接接觸農藥的農業工作者面臨最高健康風險（World Health Organization [WHO], 2022）。</p>
      </div>
    </div>
  </section>

  <!-- ③ NIOSH 5 層控制層級 accordion -->
  <section class="os-sec os-niosh reveal" id="os-hierarchy">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-layer-group"></i> NIOSH · 安全控制層級</span>
      <h2 class="os-sec-title">保護自己的<span class="accent">五個優先順序</span></h2>
      <p class="os-sec-sub">國際職安研究機構 NIOSH 建議:<strong>最好的保護,是直接把危險去掉</strong>;穿防護具是最後的手段。從第一層到第五層,效果由強到弱。</p>
    </div>

    <div class="os-niosh-data">
      <i class="fas fa-chart-bar"></i>
      <div>
        <strong>為什麼這很重要？</strong>2021–2022 年美國農業共有約 <strong>21,020 件</strong>需缺勤的非致死傷害事件，其中約三成為滑跌墜（slip, trip, fall）所致（NIOSH, 2024）；致死事故方面，交通運輸事故（含拖拉機翻覆）為首要死因（NIOSH, 2024；U.S. Bureau of Labor Statistics [BLS], 2023）。<strong>越高層級的控制措施，越能從源頭避免這些傷害。</strong>
      </div>
    </div>

    <div class="os-hierarchy-list">
      <details class="os-h-level" data-level="1" style="--level-color:#10B981;">
        <summary>
          <span class="os-h-num">❶</span>
          <span class="os-h-label">消除 — 把危險直接去掉</span>
          <i class="fas fa-chevron-down os-h-arrow"></i>
        </summary>
        <div class="os-h-detail">
          <p><strong>效果最好。</strong>如果能不接觸危險,就不會受傷。</p>
          <p>🌾 <strong>農業例子:</strong></p>
          <p>• 台灣在 2019 年全面禁用巴拉刈(一種劇毒除草劑),農民從此不會再接觸到這種危險藥劑。</p>
          <p>• 改種不需要爬高採收的矮化果樹品種,從根本消除高處墜落的風險。</p>
        </div>
      </details>

      <details class="os-h-level" data-level="2" style="--level-color:#0EA5E9;">
        <summary>
          <span class="os-h-num">❷</span>
          <span class="os-h-label">取代 — 用比較安全的方式代替</span>
          <i class="fas fa-chevron-down os-h-arrow"></i>
        </summary>
        <div class="os-h-detail">
          <p><strong>把高風險的做法換成低風險的做法。</strong></p>
          <p>🌾 <strong>農業例子:</strong></p>
          <p>• 用生物防治(天敵、微生物製劑)取代化學農藥,減少農藥暴露。</p>
          <p>• 用電動剪枝機代替手動剪刀,手腕不用反覆出力。</p>
          <p>• 用農噴無人機代替人工背著藥桶噴藥,人完全不用進田裡。</p>
        </div>
      </details>

      <details class="os-h-level" data-level="3" style="--level-color:#3B82F6;">
        <summary>
          <span class="os-h-num">❸</span>
          <span class="os-h-label">工程控制 — 改善環境和設備</span>
          <i class="fas fa-chevron-down os-h-arrow"></i>
        </summary>
        <div class="os-h-detail">
          <p><strong>在工作環境裡加裝安全設施,讓危險不容易接觸到人。</strong></p>
          <p>🌾 <strong>農業例子:</strong></p>
          <p>• 在溫室裡安裝排風扇和通風系統,噴藥後加速排出殘留藥氣。</p>
          <p>• 農機加裝防護罩和安全連鎖開關,手一離開就自動停機。</p>
          <p>• 改善田間排水,減少地面積水造成的滑倒風險。</p>
        </div>
      </details>

      <details class="os-h-level" data-level="4" style="--level-color:#F59E0B;">
        <summary>
          <span class="os-h-num">❹</span>
          <span class="os-h-label">行政管理 — 調整工作方式和時間</span>
          <i class="fas fa-chevron-down os-h-arrow"></i>
        </summary>
        <div class="os-h-detail">
          <p><strong>透過排班、訓練和規則來降低接觸危險的時間和頻率。</strong></p>
          <p>🌾 <strong>農業例子:</strong></p>
          <p>• 中午最熱的時段(11:00-15:00)避免在戶外工作,改到清晨或傍晚。</p>
          <p>• 定期參加農安教育訓練,學習正確的搬運姿勢和農藥使用方法。</p>
          <p>• 在農機和儲藥區張貼安全操作步驟圖,隨時提醒自己。</p>
        </div>
      </details>

      <details class="os-h-level" data-level="5" style="--level-color:#F43F5E;">
        <summary>
          <span class="os-h-num">❺</span>
          <span class="os-h-label">個人防護具(PPE)— 最後一道防線</span>
          <i class="fas fa-chevron-down os-h-arrow"></i>
        </summary>
        <div class="os-h-detail">
          <p><strong>當上面四層都做了還是有殘餘風險時,才靠穿戴防護具來保護自己。</strong></p>
          <p>🌾 <strong>農業例子:</strong></p>
          <p>• 噴藥時戴防毒面具和耐化學手套。</p>
          <p>• 蹲著採收時戴護膝。</p>
          <p>• 操作割草機時戴耳塞和護目鏡。</p>
          <p>• 露天工作穿 UPF 50+ 的長袖工作服。</p>
          <p class="os-h-note"><i class="fas fa-info-circle"></i> <strong>為什麼 PPE 排在最後?</strong>因為防護具只在「選對」且「戴對」時才有效,而且戴久了會不舒服,容易被省略。所以能用前面四層解決的,盡量不要只依賴防護具。</p>
        </div>
      </details>
    </div>

    <p class="os-src">資料來源:NIOSH. (2021). <em>Hierarchy of Controls</em>. Centers for Disease Control and Prevention.</p>
  </section>

  <!-- ④ 四大危害類型 -->
  <section class="os-sec os-hazards">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-triangle-exclamation"></i> HAZARDS · 認識才能預防</span>
      <h2 class="os-sec-title">農業<span class="accent">四大危害類型</span></h2>
      <p class="os-sec-sub">田間工作的危害可以分成四大類。了解自己面對的是哪一種,才能選對保護方法。美國 OSHA 將農業危害分為 12 大類,本區整合為台灣農業現場最相關的四大類別。</p>
    </div>

    <div class="os-hazard-grid">
      <div class="os-hazard-card reveal" style="--hc:#E65100; --hcl:#FFF3E0;">
        <div class="os-hazard-icon"><i class="fas fa-bolt"></i></div>
        <h4>物理性危害</h4>
        <p class="os-hazard-sub">跟「力量、溫度、聲音」有關的傷害</p>
        <ul>
          <li><strong>噪音:</strong>割草機、鏈鋸、耕耘機的聲音超過 85 分貝,長期下來聽力會永久受損</li>
          <li><strong>振動:</strong>長期操作鏈鋸等振動工具,手指會變白、發麻(白指症)</li>
          <li><strong>跌倒與墜落:</strong>爬梯子採果、修剪或在濕滑地面行走時最容易發生。CDC/NIOSH 統計農業非致死傷害中 <strong>29% 為跌倒所致</strong>,是首要傷害原因</li>
          <li><strong>農機意外:</strong>被捲入旋轉零件、翻車</li>
          <li><strong>紫外線:</strong>長期日曬增加皮膚癌風險</li>
          <li><strong>高溫:</strong>中暑、熱衰竭,尤其夏天午間</li>
        </ul>
      </div>

      <div class="os-hazard-card reveal" style="--hc:#7B1FA2; --hcl:#F3E5F5;">
        <div class="os-hazard-icon"><i class="fas fa-flask"></i></div>
        <h4>化學性危害</h4>
        <p class="os-hazard-sub">跟「農藥、化學藥劑」有關的傷害</p>
        <ul>
          <li><strong>農藥暴露:</strong>藥液沾到皮膚會被吸收,吸到氣體更危險。WHO 指出全球使用超過 <strong>1,000 種農藥</strong>,慢性暴露可能導致癌症與生殖系統影響</li>
          <li><strong>肥料粉塵:</strong>撒肥料時粉塵刺激呼吸道和眼睛</li>
          <li><strong>有機溶劑:</strong>清洗農具時接觸到的油類和溶劑</li>
          <li><strong>REI 未遵守:</strong>噴完藥太早進入田間,殘留藥氣濃度還很高(REI = 再進入間隔時間)</li>
        </ul>
      </div>

      <div class="os-hazard-card reveal" style="--hc:#2E7D32; --hcl:#E8F5E9;">
        <div class="os-hazard-icon"><i class="fas fa-bug"></i></div>
        <h4>生物性危害</h4>
        <p class="os-hazard-sub">跟「動物、細菌、黴菌」有關的傷害</p>
        <ul>
          <li><strong>蛇咬:</strong>雲嘉農田常見龜殼花和赤尾青竹絲,草叢和田埂要特別注意</li>
          <li><strong>蜂螫:</strong>果園修剪時容易驚擾蜂巢</li>
          <li><strong>黴菌孢子:</strong>溫室內高溫高濕,黴菌孢子容易被吸入造成呼吸道不適</li>
          <li><strong>鉤端螺旋體:</strong>水田環境中經由傷口或黏膜感染</li>
          <li><strong>破傷風:</strong>被生鏽的農具刺傷時有感染風險</li>
        </ul>
      </div>

      <div class="os-hazard-card reveal" style="--hc:#1565C0; --hcl:#E3F2FD;">
        <div class="os-hazard-icon"><i class="fas fa-person-walking-arrow-loop-left"></i></div>
        <h4>人因性危害</h4>
        <p class="os-hazard-sub">跟「姿勢、負重、重複動作」有關的傷害</p>
        <ul>
          <li><strong>重複性動作:</strong>採收、修剪時同一個動作做幾百次,手腕和手指關節容易發炎(腱鞘炎、腕隧道症候群)</li>
          <li><strong>搬運重物:</strong>超過 15 公斤的東西經常搬,腰椎和膝蓋會慢慢受損</li>
          <li><strong>不良姿勢:</strong>長時間蹲著、彎腰、仰頭,脊椎和關節承受持續壓力</li>
          <li><strong>振動白指症:</strong>長期操作振動手工具累積的手部損傷</li>
        </ul>
      </div>
    </div>

    <p class="os-src">
      資料來源:OSHA. <em>Agricultural Operations: Hazards &amp; Controls</em>. U.S. DOL.;WHO. (2022). <em>Pesticide Residues in Food</em>.;CDC/NIOSH. (2024). <em>Agriculture Worker Safety and Health</em>.
    </p>
  </section>

  <!-- ⑤ 3 子頁大卡 -->
  <section class="os-sec os-subpages" id="os-subpages">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-folder-open"></i> DEEP DIVE · 深度專區</span>
      <h2 class="os-sec-title">進入<span class="accent">3 大子頁</span>看詳細說明</h2>
      <p class="os-sec-sub">每個主題都有獨立的完整頁面。點擊進入後可以看到更詳細的說明、選購建議和操作步驟。</p>
    </div>

    <div class="os-subpage-grid">
      <a href="/occupational-safety/personal-protective-equipment/" class="os-subpage-card" style="--spc:#F43F5E; --spcl:#FFE4E6;">
        <div class="os-subpage-num">01</div>
        <div class="os-subpage-icon"><i class="fas fa-mask-face"></i></div>
        <h3>個人防護具(PPE)</h3>
        <p>從頭到腳,每個身體部位該穿戴什麼防護具、怎麼選、怎麼穿脫,一次看清楚。</p>
        <span class="os-subpage-link">進入專區 <i class="fas fa-arrow-right"></i></span>
      </a>

      <a href="/occupational-safety/labor-saving-machinery/" class="os-subpage-card" style="--spc:#0EA5E9; --spcl:#E0F2FE;">
        <div class="os-subpage-num">02</div>
        <div class="os-subpage-icon"><i class="fas fa-tractor"></i></div>
        <h3>省力農機具</h3>
        <p>依照整地、管理、採收、搬運分類,看看哪些農機適合你,還有政府補助怎麼申請。</p>
        <span class="os-subpage-link">進入專區 <i class="fas fa-arrow-right"></i></span>
      </a>

      <a href="/occupational-safety/greenhouse-zone/" class="os-subpage-card" style="--spc:#F59E0B; --spcl:#FEF3C7;">
        <div class="os-subpage-num">03</div>
        <div class="os-subpage-icon"><i class="fas fa-warehouse"></i></div>
        <h3>溫室作業安全</h3>
        <p>溫室裡比外面更熱、農藥殘留更高、黴菌更多。了解溫室特有的風險和防護措施。</p>
        <span class="os-subpage-link">進入專區 <i class="fas fa-arrow-right"></i></span>
      </a>
    </div>
  </section>

  <!-- ⑥ References 全展開（APA 7） -->
  <style>
    .os-refs-list.apa7 { list-style: none; padding: 0; }
    .os-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .os-refs-list.apa7 li em { font-style: italic; }
    .os-refs-list.apa7 li a { word-break: break-all; }
  </style>
  <section class="os-sec os-refs">
    <div class="os-refs-open">
      <div class="os-sec-head">
        <span class="os-kicker"><i class="fas fa-book-open"></i> REFERENCES · 參考資料</span>
        <h2 class="os-sec-title" style="margin:0;">參考<span class="accent">資料</span></h2>
        <p class="os-refs-note">本頁內容依 APA 第 7 版格式引用以下台灣政府與國際職業安全機構公開資料。</p>
      </div>
      <ul class="os-refs-list apa7">
        <li>行政院主計總處（2023）。<em>112 年農家戶口及經濟調查統計結果</em>。https://www.stat.gov.tw/</li>
        <li>勞動部勞工保險局（2024 年 11 月）。<em>農民職業災害保險就醫津貼調整公告（113/11/6 起適用）</em>。https://www.bli.gov.tw/0100668.html</li>
        <li>勞動部（2024 年 9 月）。<em>農職保傷病給付基準提高，農民務農保障再升級</em>。https://www.mol.gov.tw/1607/1632/1633/76199/</li>
        <li>勞動部職業安全衛生署（2025）。<em>職業安全衛生法</em>。https://www.osha.gov.tw/</li>
        <li>勞動部（n.d.）。<em>職業安全衛生設施規則</em>。勞動部勞動法令查詢系統。https://laws.mol.gov.tw/FLAW/FLAWDAT0201.aspx?id=FL015021</li>
        <li>農業部農糧署（2025）。<em>農機補助專區</em>。https://www.afa.gov.tw/cht/index.php?code=list&amp;ids=3314</li>
        <li>臺大醫院雲林分院農業環境與職業健康中心（2024）。<em>農業工作者健康調查研究數據</em>。https://fwdignity.com/</li>
        <li>International Labour Organization. (2023). <em>Employment in agriculture</em>. https://www.ilo.org/topics/agriculture-and-food-services</li>
        <li>National Institute for Occupational Safety and Health. (2024). <em>Agricultural safety</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/agriculture/about/index.html</li>
        <li>National Institute for Occupational Safety and Health. (2021). <em>Hierarchy of controls</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/hierarchy-of-controls/about/index.html</li>
        <li>Occupational Safety and Health Administration. (n.d.). <em>Agricultural operations: Hazards &amp; controls</em>. U.S. Department of Labor. https://www.osha.gov/agricultural-operations/hazards</li>
        <li>U.S. Bureau of Labor Statistics. (2023). <em>Census of fatal occupational injuries, 2022</em> [Data set]. U.S. Department of Labor. https://www.bls.gov/iif/oshcfoi1.htm</li>
        <li>World Health Organization. (2022). <em>Pesticide residues in food</em>. https://www.who.int/news-room/fact-sheets/detail/pesticide-residues-in-food</li>
      </ul>
      <h4 class="os-refs-subhead"><i class="fas fa-camera"></i> 圖片來源</h4>
      <p class="os-refs-note">Hero 背景取自 <a href="https://www.pexels.com/license/" target="_blank" rel="noopener">Pexels</a>（photo 32047262，農田全景），依 Pexels License 可免費商用。</p>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
