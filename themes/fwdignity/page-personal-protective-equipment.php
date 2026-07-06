<?php
/**
 * fwdignity · page-personal-protective-equipment.php
 * PPE 子頁(slug=personal-protective-equipment)
 *
 * 🔴 紅線:全部文字 / 數字 / 引用 1:1 復用原頁。
 * 章節色 PPE · 桃紅 #F43F5E
 */
get_header();
?>

<div class="fw-page-layout">

<main class="os-sub" id="main" role="main">

  <!-- Back to parent -->
  <a href="/occupational-safety/" class="os-back"><i class="fas fa-arrow-left"></i> 回到職業安全總覽</a>

  <!-- Hero -->
  <section class="os-sub-hero">
    <div class="os-sub-hero-deco" aria-hidden="true">
      <i class="fas fa-mask-face os-sub-deco-1"></i>
      <i class="fas fa-shield os-sub-deco-2"></i>
    </div>
    <div class="os-sub-hero-inner">
      <span class="os-sub-badge"><i class="fas fa-mask-face"></i> 個人防護具專區</span>
      <h1 class="os-sub-title" data-hook-title>個人防護具（PPE）<span class="accent">選用指南</span></h1>
      <p class="os-sub-tagline">從頭到腳,選對、戴對,才真正有保護效果。</p>
      <div class="os-sub-intro">
        <p>個人防護具（personal protective equipment, PPE）是保護身體的<strong>最後一道防線</strong>。但很多農友常見的問題是:噴農藥只戴一般口罩（擋不住藥氣）、用棉手套碰農藥（藥會滲透進去）、操作鏈鋸不戴耳塞（聽力會慢慢損壞）。</p>
        <p>這一頁按照<strong>身體部位</strong>分類,告訴您每個部位該穿戴什麼、怎麼選、怎麼穿脫。最後面有完整的穿脫順序和法規依據。</p>
      </div>
      <div class="os-sub-data">
        <h4><i class="fas fa-chart-line"></i> 為什麼 PPE 是最後一道防線?</h4>
        <ul>
          <li>美國國家職業安全衛生研究所（National Institute for Occupational Safety and Health [NIOSH]）安全控制層級將 PPE 列於第 5 層（最低），但對自營農民而言往往是<strong>最實際的第一道保護</strong>（NIOSH, 2021）</li>
          <li>農業僅佔全球勞動人口約 27%,卻佔職業致死事故的<strong>近半數</strong>（International Labour Organization [ILO], 2023）</li>
          <li>正確選用並確實佩戴 PPE,可有效降低農藥暴露、噪音性聽損與眼部傷害的發生率（Occupational Safety and Health Administration [OSHA], n.d.-a）</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- 7 身體部位 -->
  <section class="os-sec os-ppe-cats">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-person"></i> BY BODY PART · 按身體部位</span>
      <h2 class="os-sec-title">7 個身體部位<span class="accent">完整指南</span></h2>
    </div>

    <!-- 1. 頭部 -->
    <article class="ppe-cat reveal" style="--cc:#F57F17; --ccl:#FFF3E0;">
      <header class="ppe-cat-head">
        <span class="ppe-cat-num">01</span>
        <div class="ppe-cat-icon"><i class="fas fa-hard-hat"></i></div>
        <div>
          <h3>頭部防護</h3>
          <p>果園高處作業有墜落物和跌落風險;露天長時間工作有紫外線傷害。</p>
        </div>
      </header>
      <div class="ppe-equip-grid">
        <div class="ppe-equip">
          <div class="ppe-photo" style="background-image:url(https://images.pexels.com/photos/4161604/pexels-photo-4161604.jpeg?w=600&auto=compress)"></div>
          <i class="fas fa-helmet-safety"></i>
          <h4>工業安全帽</h4>
          <span class="ppe-when">果園高處作業、農機操作</span>
          <p>需符合 CNS 1336 工業用安全帽國家標準（經濟部標準檢驗局,n.d.-a）。使用前檢查外殼有沒有裂痕,帽帶要扣緊。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-hat-cowboy-side"></i></div>
          <i class="fas fa-hat-cowboy-side"></i>
          <h4>寬沿遮陽帽</h4>
          <span class="ppe-when">一般田間作業</span>
          <p>選 UPF 50+ 材質,帽沿至少 7 公分以上才能有效遮擋臉部和脖子的紫外線。</p>
        </div>
      </div>
    </article>

    <!-- 2. 眼部 -->
    <article class="ppe-cat reveal" style="--cc:#283593; --ccl:#E8EAF6;">
      <header class="ppe-cat-head">
        <span class="ppe-cat-num">02</span>
        <div class="ppe-cat-icon"><i class="fas fa-glasses"></i></div>
        <div>
          <h3>眼部防護</h3>
          <p>割草碎枝時碎石和樹屑會飛濺傷眼;噴藥時藥液會飄到臉上。美國每年約有<strong>20,000 件</strong>工作場所眼部傷害,其中農林漁牧業佔比顯著（U.S. Bureau of Labor Statistics [BLS], 2023; OSHA, n.d.-a）。</p>
        </div>
      </header>
      <div class="ppe-equip-grid">
        <div class="ppe-equip">
          <div class="ppe-photo" style="background-image:url(https://images.pexels.com/photos/7723357/pexels-photo-7723357.jpeg?w=600&auto=compress)"></div>
          <i class="fas fa-glasses"></i>
          <h4>防飛濺護目鏡</h4>
          <span class="ppe-when">割草、碎枝、打石</span>
          <p>鏡片要通過耐衝擊測試。側面也要有防護,不然碎石從旁邊飛進來一樣會受傷。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-head-side-mask"></i></div>
          <i class="fas fa-head-side-mask"></i>
          <h4>密閉式護目鏡</h4>
          <span class="ppe-when">噴灑農藥</span>
          <p>施藥時要用密閉式的,藥液才不會從縫隙滲進去。選有防霧設計的款式比較舒服。</p>
        </div>
      </div>
    </article>

    <!-- 3. 呼吸 -->
    <article class="ppe-cat reveal" style="--cc:#C62828; --ccl:#FFEBEE;">
      <header class="ppe-cat-head">
        <span class="ppe-cat-num">03</span>
        <div class="ppe-cat-icon"><i class="fas fa-lungs"></i></div>
        <div>
          <h3>呼吸防護</h3>
          <p>農藥微粒和蒸氣經口鼻吸入會傷害神經、肝臟和腎臟;翻土碎土的粉塵刺激呼吸道。全球使用超過 <strong>1,000 種農藥</strong>,慢性暴露可能導致癌症與生殖系統影響,直接接觸者面臨最高健康風險（World Health Organization [WHO], 2022）。</p>
        </div>
      </header>
      <div class="ppe-equip-grid">
        <div class="ppe-equip">
          <div class="ppe-photo" style="background-image:url(https://images.pexels.com/photos/4052176/pexels-photo-4052176.jpeg?w=600&auto=compress)"></div>
          <i class="fas fa-mask-face"></i>
          <h4>N95 防塵口罩</h4>
          <span class="ppe-when">翻土、碎土等粉塵環境</span>
          <p>N95 等級可過濾至少 95% 的非油性懸浮微粒（粉塵、黴菌孢子），<strong>但不能過濾農藥蒸氣等氣態化學物</strong>——蒸氣會直接穿透口罩（NIOSH, 2020; U.S. Environmental Protection Agency [EPA], 2023）。粉塵作業時每次使用前壓緊鼻樑條做密合測試,用完即丟。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-head-side-mask"></i></div>
          <i class="fas fa-head-side-mask"></i>
          <h4>半面式防毒面具（有機蒸氣濾罐）</h4>
          <span class="ppe-when">噴灑農藥（一般露天環境）</span>
          <p>要搭配<strong>有機蒸氣濾罐</strong>,面具需符合國家呼吸防護具標準（標準號待國家標準局查證）。濾罐聞到異味就要換新。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-mask-ventilator"></i></div>
          <i class="fas fa-mask-ventilator"></i>
          <h4>全面式防毒面具</h4>
          <span class="ppe-when">溫室內施藥、高濃度藥劑環境</span>
          <p>同時保護呼吸和眼睛。密閉空間施藥一定要用全面式,半面式不夠。</p>
        </div>
      </div>
      <div class="ppe-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <div><strong>常見錯誤：</strong>很多農友噴藥時只戴一般布口罩或醫療口罩,甚至只戴 N95,這些都<strong>擋不住農藥蒸氣</strong>。N95 只能擋懸浮微粒（粉塵），氣態的有機溶劑與農藥揮發物會直接穿透濾材（EPA, 2023; NIOSH, 2020）。噴藥一定要使用含<strong>有機蒸氣（OV）濾罐</strong>的防毒面具才有效。</div>
      </div>
    </article>

    <!-- 4. 手部 -->
    <article class="ppe-cat reveal" style="--cc:#7B1FA2; --ccl:#F3E5F5;">
      <header class="ppe-cat-head">
        <span class="ppe-cat-num">04</span>
        <div class="ppe-cat-icon"><i class="fas fa-hand"></i></div>
        <div>
          <h3>手部防護</h3>
          <p>農藥會經皮膚吸收進入人體（手是最常接觸農藥的部位）;修剪和採收時有割傷風險。職業性農藥暴露以皮膚接觸為主要途徑,手部為最常見接觸部位（EPA, 2023; WHO, 2022）。</p>
        </div>
      </header>
      <div class="ppe-equip-grid">
        <div class="ppe-equip">
          <div class="ppe-photo" style="background-image:url(https://images.pexels.com/photos/8488007/pexels-photo-8488007.jpeg?w=600&auto=compress)"></div>
          <i class="fas fa-hand-sparkles"></i>
          <h4>耐化學手套（丁腈橡膠）</h4>
          <span class="ppe-when">調配農藥、噴灑農藥、接觸肥料</span>
          <p>一般棉手套和乳膠手套擋不住農藥滲透。一定要用<strong>丁腈橡膠（nitrile rubber）材質</strong>,EPA 將其列為符合 Worker Protection Standard 的耐化學手套材質之一（EPA, 2023）。脫手套前先把外面用肥皂水洗乾淨,避免農藥沾到手。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-mitten"></i></div>
          <i class="fas fa-mitten"></i>
          <h4>防割手套（EN 388 認證）</h4>
          <span class="ppe-when">修剪枝條、採收、操作刀具</span>
          <p>剪枝和採收時刀片割傷手很常見。選有 EN 388 防割認證的手套,等級越高防護越好。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-hands"></i></div>
          <i class="fas fa-hands"></i>
          <h4>一般工作手套</h4>
          <span class="ppe-when">搬運、整地</span>
          <p>搬運重物和整地用的基本手套。選防滑材質,手掌面有橡膠顆粒的抓握力較好。</p>
        </div>
      </div>
    </article>

    <!-- 5. 軀幹 -->
    <article class="ppe-cat reveal" style="--cc:#2E7D32; --ccl:#E8F5E9;">
      <header class="ppe-cat-head">
        <span class="ppe-cat-num">05</span>
        <div class="ppe-cat-icon"><i class="fas fa-shirt"></i></div>
        <div>
          <h3>軀幹防護</h3>
          <p>長時間露天作業紫外線會累積傷害皮膚;施藥時農藥會沾附在衣物上被皮膚吸收。</p>
        </div>
      </header>
      <div class="ppe-equip-grid">
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-shirt"></i></div>
          <i class="fas fa-shirt"></i>
          <h4>UPF 50+ 長袖防曬工作服</h4>
          <span class="ppe-when">一般田間露天作業</span>
          <p>選透氣排汗的材質,比擦防曬乳持久有效。每次洗完確認 UPF 防護效果是否還在(破損或褪色就該換)。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-vest"></i></div>
          <i class="fas fa-vest"></i>
          <h4>防水圍裙 / 全身防護衣</h4>
          <span class="ppe-when">施藥、調配農藥</span>
          <p>施藥時穿的外層防護衣需要有抗滲透功能,避免農藥沾到衣服後被皮膚吸收（EPA, 2023）。用完後外層先沖水再脫。</p>
        </div>
      </div>
    </article>

    <!-- 6. 下肢 -->
    <article class="ppe-cat reveal" style="--cc:#1565C0; --ccl:#E3F2FD;">
      <header class="ppe-cat-head">
        <span class="ppe-cat-num">06</span>
        <div class="ppe-cat-icon"><i class="fas fa-socks"></i></div>
        <div>
          <h3>下肢防護</h3>
          <p>長時間蹲姿會磨損膝蓋軟骨;田間地面潮濕容易滑倒;重物掉落會砸傷腳。</p>
        </div>
      </header>
      <div class="ppe-equip-grid">
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-shield-halved"></i></div>
          <i class="fas fa-shield-halved"></i>
          <h4>緩衝護膝</h4>
          <span class="ppe-when">蹲著採收、移苗</span>
          <p>長時間蹲姿是膝蓋退化的主因之一。選有厚緩衝墊的款式,綁帶式比套筒式穩固。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo" style="background-image:url(https://images.pexels.com/photos/1529426/pexels-photo-1529426.jpeg?w=600&auto=compress)"></div>
          <i class="fas fa-shoe-prints"></i>
          <h4>安全鞋 / 防滑雨鞋</h4>
          <span class="ppe-when">田間作業、操作農機</span>
          <p>鋼頭防止重物砸傷腳趾,防滑底避免在濕泥地滑倒。有防穿刺鞋底更好（防止釘子或尖銳物刺穿）。</p>
        </div>
      </div>
    </article>

    <!-- 7. 聽力 -->
    <article class="ppe-cat reveal" style="--cc:#E65100; --ccl:#FFF3E0;">
      <header class="ppe-cat-head">
        <span class="ppe-cat-num">07</span>
        <div class="ppe-cat-icon"><i class="fas fa-ear-listen"></i></div>
        <div>
          <h3>聽力防護</h3>
          <p>割草機、鏈鋸、耕耘機運轉噪音超過 85 分貝,長期不防護會造成不可逆的聽力損失。美國約有 <strong>2,200 萬勞工</strong>暴露於有害噪音中,農業工作者因缺乏聽力防護,噪音性聽力損失發生率特別高（NIOSH, 2024）。</p>
        </div>
      </header>
      <div class="ppe-equip-grid">
        <div class="ppe-equip">
          <div class="ppe-photo nophoto"><i class="fas fa-volume-xmark"></i></div>
          <i class="fas fa-volume-xmark"></i>
          <h4>泡棉耳塞</h4>
          <span class="ppe-when">輕度噪音環境（一般動力農機）</span>
          <p>搓揉壓縮後放入耳道,等它膨脹後才能隔音。便宜、用完即丟。</p>
        </div>
        <div class="ppe-equip">
          <div class="ppe-photo" style="background-image:url(https://images.pexels.com/photos/744922/pexels-photo-744922.jpeg?w=600&auto=compress)"></div>
          <i class="fas fa-headphones"></i>
          <h4>防噪耳罩</h4>
          <span class="ppe-when">重度噪音環境（鏈鋸、大型農機）</span>
          <p>整個罩住耳朵,隔音效果比耳塞好。確認 NRR（Noise Reduction Rating,噪音降低等級）至少 25 以上。</p>
        </div>
      </div>
      <div class="ppe-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <div><strong>重要提醒:</strong>噪音造成的聽力損失是慢慢累積的,初期不會有感覺,等到發現聽不清楚的時候已經無法恢復（NIOSH, 2024）。只要操作動力農機就應該戴耳塞或耳罩。</div>
      </div>
    </article>
  </section>

  <!-- 快速風險檢查 -->
  <section class="os-sec os-risk reveal">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-clipboard-check"></i> QUICK CHECK · 快速風險檢查</span>
      <h2 class="os-sec-title">你目前的防護狀況<span class="accent">安全嗎?</span></h2>
      <p class="os-sec-sub">勾選下列符合的項目,立即看到你的風險等級與改善方向。不勾表示該項目你目前沒做。</p>
    </div>

    <div class="os-risk-card">
      <div class="os-risk-q">
        <label><input type="checkbox" class="os-risk-cb" data-risk="ppe-hat"> <span>我在果園高處或日曬下作業時會戴安全帽或 UPF 50+ 遮陽帽</span></label>
        <label><input type="checkbox" class="os-risk-cb" data-risk="ppe-eye"> <span>割草碎枝或噴藥時我會戴護目鏡（不是普通眼鏡）</span></label>
        <label><input type="checkbox" class="os-risk-cb" data-risk="ppe-resp"> <span>噴藥時我會戴含有機蒸氣濾罐的防毒面具（非一般布口罩或 N95）</span></label>
        <label><input type="checkbox" class="os-risk-cb" data-risk="ppe-hand"> <span>接觸農藥時我會戴丁腈橡膠手套（非棉手套或乳膠手套）</span></label>
        <label><input type="checkbox" class="os-risk-cb" data-risk="ppe-ear"> <span>操作割草機、鏈鋸等動力農機時我會戴耳塞或耳罩</span></label>
        <label><input type="checkbox" class="os-risk-cb" data-risk="ppe-foot"> <span>田間作業我會穿防滑鞋或安全鞋（鋼頭款更佳）</span></label>
        <label><input type="checkbox" class="os-risk-cb" data-risk="ppe-sop"> <span>我知道防護具穿戴順序（由內到外）和脫除順序（由外到內）</span></label>
      </div>
      <div class="os-risk-result" id="os-risk-result">
        <div class="os-risk-meter">
          <div class="os-risk-meter-fill" id="os-risk-fill"></div>
        </div>
        <div class="os-risk-feedback">
          <span class="os-risk-score" id="os-risk-score">0</span>
          <span class="os-risk-score-divider">/</span>
          <span class="os-risk-score-total">7</span>
          <p class="os-risk-msg" id="os-risk-msg">勾選上方項目開始檢查你的防護狀況</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 穿脫 SOP -->
  <section class="os-sec os-sop reveal">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-list-ol"></i> SOP · 穿脫順序</span>
      <h2 class="os-sec-title">穿脫順序 — <span class="accent">順序對了才安全</span></h2>
      <p class="os-sec-sub">穿戴和脫除防護具有固定的順序。順序錯了,反而會讓農藥沾到皮膚上（EPA, 2023）。</p>
    </div>
    <div class="os-sop-grid">
      <div class="os-sop-card os-sop-on">
        <h3><i class="fas fa-arrow-down"></i> 穿戴順序（由內到外）</h3>
        <ol>
          <li><strong>工作服</strong> — 先穿上長袖長褲工作服或防護衣</li>
          <li><strong>安全鞋</strong> — 褲管放在鞋筒外面,避免農藥流入鞋內</li>
          <li><strong>護膝</strong> — 需要蹲姿作業時穿上</li>
          <li><strong>手套</strong> — 袖口塞進手套裡,不留縫隙</li>
          <li><strong>護目鏡</strong> — 調整鬆緊確保密合</li>
          <li><strong>口罩 / 防毒面具</strong> — 壓緊鼻樑條做密合測試</li>
          <li><strong>安全帽 / 遮陽帽</strong> — 最後戴帽子</li>
        </ol>
      </div>
      <div class="os-sop-card os-sop-off">
        <h3><i class="fas fa-arrow-up"></i> 脫除順序（由外到內）</h3>
        <ol>
          <li><strong>安全帽</strong> — 先脫帽子</li>
          <li><strong>護目鏡</strong> — 從後方摘下,避免碰觸鏡面</li>
          <li><strong>口罩 / 防毒面具</strong> — 從後方鬆開,向前取下</li>
          <li><strong>手套</strong> — <mark>外層先用肥皂水清洗後再脫</mark>,用另一隻手套的手指從手腕處翻面脫除</li>
          <li><strong>護膝</strong></li>
          <li><strong>安全鞋</strong></li>
          <li><strong>工作服</strong> — 避免內面碰觸外面（污染面）</li>
        </ol>
        <p class="os-sop-note"><i class="fas fa-hand-sparkles"></i> 全部脫完後用肥皂徹底洗手,並盡快沐浴更換乾淨衣物。</p>
      </div>
    </div>
  </section>

  <!-- 法規依據 -->
  <section class="os-sec os-legal reveal">
    <div class="os-sec-head">
      <span class="os-kicker"><i class="fas fa-gavel"></i> LEGAL · 法規依據</span>
      <h2 class="os-sec-title">法規<span class="accent">依據</span></h2>
      <p class="os-sec-sub">台灣的職業安全衛生法規對防護具有明確規範,以下是與農業作業最相關的條文（勞動部,n.d.；勞動部職業安全衛生署,2025）:</p>
    </div>
    <div class="os-legal-grid">
      <div class="os-legal-item">
        <h4><i class="fas fa-book-open-reader"></i> 《職業安全衛生法》第 6 條</h4>
        <p>雇主對於防止有害物質引起之危害,應有符合規定之必要安全衛生設備及措施。</p>
      </div>
      <div class="os-legal-item">
        <h4><i class="fas fa-book-open-reader"></i> 《職業安全衛生設施規則》第 277–280 條</h4>
        <p>暴露於有害物、高溫、噪音或粉塵環境之勞工,雇主應置備適當的個人防護具,並使勞工確實使用。</p>
      </div>
      <div class="os-legal-item">
        <h4><i class="fas fa-info-circle"></i> 適用說明</h4>
        <p>自營農民雖非法律規定的「勞工」身分,但這些規範可作為自我保護的參考標準。保護自己不需要等法律要求。</p>
      </div>
    </div>
  </section>

  <!-- References (APA 7 全展開) -->
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
        <p class="os-refs-note">本頁內容依 APA 第 7 版格式引用下列公開資料。中文文獻在前,英文文獻在後。標準號部分項目待國家標準局核實。</p>
      </div>
      <ul class="os-refs-list apa7">
        <li>勞動部（n.d.）。<em>職業安全衛生設施規則</em>。勞動部勞動法令查詢系統。https://laws.mol.gov.tw/FLAW/FLAWDAT0201.aspx?id=FL015021</li>
        <li>勞動部職業安全衛生署（2025）。<em>職業安全衛生法</em>。https://www.osha.gov.tw/</li>
        <li>經濟部標準檢驗局（n.d.-a）。<em>CNS 1336 工業用安全帽</em>。經濟部標準檢驗局 CNS 線上服務系統。https://www.cnsonline.com.tw/</li>
        <li>經濟部標準檢驗局（n.d.-b）。<em>呼吸防護具國家標準</em>（標準號待國家標準局查證）。經濟部標準檢驗局 CNS 線上服務系統。https://www.cnsonline.com.tw/</li>
        <li>International Labour Organization. (2023). <em>Safety and health in agriculture</em>. https://www.ilo.org/safety-and-health/agriculture</li>
        <li>National Institute for Occupational Safety and Health. (2020). <em>Respirators: Understanding N95 filtering facepiece respirators and their limitations with organic vapors</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/npptl/topics/respirators/</li>
        <li>National Institute for Occupational Safety and Health. (2021). <em>Hierarchy of controls</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/hierarchy-of-controls/about/index.html</li>
        <li>National Institute for Occupational Safety and Health. (2024). <em>Agricultural safety</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/agricultural-safety/about/index.html</li>
        <li>Occupational Safety and Health Administration. (n.d.-a). <em>Personal protective equipment</em>. U.S. Department of Labor. https://www.osha.gov/personal-protective-equipment</li>
        <li>U.S. Bureau of Labor Statistics. (2023). <em>Census of fatal occupational injuries, 2022</em> [Data set]. U.S. Department of Labor. https://www.bls.gov/iif/fatal-injuries.htm</li>
        <li>U.S. Environmental Protection Agency. (2023). <em>Agricultural Worker Protection Standard (WPS)</em>. https://www.epa.gov/pesticide-worker-safety/agricultural-worker-protection-standard-wps</li>
        <li>World Health Organization. (2022). <em>Pesticide residues in food</em>. https://www.who.int/news-room/fact-sheets/detail/pesticide-residues-in-food</li>
      </ul>
      <h4 class="os-refs-subhead"><i class="fas fa-camera"></i> 圖片來源</h4>
      <p class="os-refs-note">本頁各部位示意圖取自 <a href="https://www.pexels.com/license/" target="_blank" rel="noopener">Pexels</a>,依 Pexels License 可免費商用。軀幹防護暫無合適圖片,以主題色塊代替。</p>
      <ul class="os-refs-list">
        <li>Pexels photo 4161604（頭部）· 7723357（眼部）· 4052176（呼吸）· 8488007（手部）· 1529426（下肢）· 744922（聽力）。<a href="https://www.pexels.com/" target="_blank" rel="noopener">Pexels 首頁</a></li>
      </ul>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
