<?php
/**
 * fwdignity · page-subpage-msd.php
 * 骨骼肌肉保健子頁(slug=subpage-msd)· 藍 #1E40AF
 */
get_header();
?>

<div class="fw-page-layout">

<main class="hps" id="main" role="main">

  <a href="/healthgood/" class="hps-back"><i class="fas fa-arrow-left"></i> 回到健康促進總覽</a>

  <section class="hps-hero">
    <div class="hps-hero-deco" aria-hidden="true">
      <i class="fas fa-bone hps-deco-1"></i>
      <i class="fas fa-person-walking-arrow-loop-left hps-deco-2"></i>
    </div>
    <div class="hps-hero-inner">
      <span class="hps-badge"><i class="fas fa-bone"></i> 骨骼肌肉保健</span>
      <h1 class="hps-title" data-hook-title>骨骼肌肉<span class="accent">保健指南</span></h1>
      <p class="hps-tagline">了解你的身體哪裡最容易受傷，學會保護和修復的方法。</p>
      <div class="hps-intro">
        <p>國際系統性回顧指出，農民肌肉骨骼疾病（musculoskeletal disorders, MSDs）的一年盛行率普遍高於一般工作者，最常見部位為下背、膝關節與肩膀（Osborne et al., 2012）。在台灣，國家衛生研究院（以下簡稱「國衛院」）的農民健康世代研究亦發現，農民族群的髖關節退化、膝關節退化與膝蓋滑囊炎風險顯著偏高（國家衛生研究院 [NHRI]，2024）。</p>
        <p>這些傷害並非突發事件，而是長年累月的重複動作、不良姿勢與過度負重累積。適當的暖身、中途伸展與使用省力工具可顯著降低傷害發生率（National Institute for Occupational Safety and Health [NIOSH]，2021；下文「70%」降幅為業界指引估計，精確比例來源待確認）。</p>
      </div>
    </div>
  </section>

  <!-- ① 作物別風險 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-seedling"></i></div>
      <div>
        <h2>你<span class="accent">種什麼</span>，就痛什麼</h2>
        <p>不同作物的農務動作不同，造成的傷害部位也不同（Osborne et al., 2012；NHRI, 2024）。</p>
      </div>
    </div>
    <div class="hps-crop-grid">
      <div class="hps-crop">
        <div class="hps-crop-head"><span class="hps-crop-emoji">🌾</span><h3>水稻農</h3></div>
        <div class="hps-pain-row"><span>下背部</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:82%;background:#C62828;"></div></div><span>63–87%</span></div>
        <div class="hps-pain-row"><span>膝蓋</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:70%;background:#E53935;"></div></div><span>54–80%</span></div>
        <div class="hps-pain-row"><span>肩膀</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:48%;background:#EF5350;"></div></div><span>35–48%</span></div>
        <p class="hps-crop-why">長時間彎腰插秧、蹲在田裡除草、搬運穀包（Osborne et al., 2012；盛行率區間來源待確認原始調查）。</p>
      </div>
      <div class="hps-crop">
        <div class="hps-crop-head"><span class="hps-crop-emoji">🍊</span><h3>果農</h3></div>
        <div class="hps-pain-row"><span>肩膀</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:60%;background:#1565C0;"></div></div><span>60%</span></div>
        <div class="hps-pain-row"><span>手肘</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:41%;background:#1976D2;"></div></div><span>41%</span></div>
        <div class="hps-pain-row"><span>手部</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:58%;background:#1E88E5;"></div></div><span>58%</span></div>
        <p class="hps-crop-why">仰頭修剪採摘、長時間使用剪枝工具、搬運果箱（盛行率百分比來源待確認）。</p>
      </div>
      <div class="hps-crop">
        <div class="hps-crop-head"><span class="hps-crop-emoji">🥬</span><h3>蔬菜農</h3></div>
        <div class="hps-pain-row"><span>下背部</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:72%;background:#2E7D32;"></div></div><span>60–77%</span></div>
        <div class="hps-pain-row"><span>膝蓋</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:50%;background:#43A047;"></div></div><span>40–55%</span></div>
        <div class="hps-pain-row"><span>手腕</span><div class="hps-pain-track"><div class="hps-pain-bar" style="width:38%;background:#66BB6A;"></div></div><span>25–42%</span></div>
        <p class="hps-crop-why">蹲姿採收、反覆手部動作、搬運菜籃（Osborne et al., 2012；百分比區間來源待確認）。</p>
      </div>
    </div>
  </section>

  <!-- ② 暖身伸展 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-dumbbell"></i></div>
      <div>
        <h2>工前暖身・<span class="accent">中間伸展</span>・工後收操</h2>
        <p>人因工程指引指出，適當的暖身與伸展可顯著降低 MSDs 風險（NIOSH, 2021；確切降幅比例來源待確認）。</p>
      </div>
    </div>
    <div class="hps-stretch-grid">
      <div class="hps-stretch before">
        <h3><i class="fas fa-sun"></i> 工前暖身（5–10 分鐘）</h3>
        <p class="hps-stretch-when">開始農務之前</p>
        <ul>
          <li><strong>頸部旋轉</strong>——頭慢慢左右轉各 5 次</li>
          <li><strong>手臂環繞</strong>——雙臂向前、向後各繞 10 圈</li>
          <li><strong>軀幹旋轉</strong>——雙腳站穩，上半身左右轉各 10 次</li>
          <li><strong>深蹲</strong>——雙腳與肩同寬，慢慢蹲下站起 10 次</li>
          <li><strong>小跑步或原地踏步</strong>——讓心率微微上升</li>
        </ul>
      </div>
      <div class="hps-stretch during">
        <h3><i class="fas fa-pause"></i> 中間伸展（每 1–2 小時，3–5 分鐘）</h3>
        <p class="hps-stretch-when">工作中間休息時</p>
        <ul>
          <li><strong>站立後仰</strong>——雙手撐腰，身體緩慢後彎（緩解久蹲/彎腰）</li>
          <li><strong>腿後側伸展</strong>——一腳踩在矮凳上，身體前傾 15 秒</li>
          <li><strong>手腕旋轉</strong>——雙手握拳順逆時針各轉 10 圈</li>
          <li><strong>肩膀聳放</strong>——聳肩 5 秒放下，重複 5 次</li>
        </ul>
      </div>
      <div class="hps-stretch after">
        <h3><i class="fas fa-moon"></i> 工後收操（5 分鐘）</h3>
        <p class="hps-stretch-when">結束農務後回家前</p>
        <ul>
          <li><strong>貓牛式</strong>——四肢撐地，背部弓起再下沉各 10 次</li>
          <li><strong>膝蓋抱胸</strong>——躺平或坐在椅子上，雙手抱膝靠近胸口 15 秒</li>
          <li><strong>全身抖鬆</strong>——站立全身甩動放鬆 30 秒</li>
        </ul>
      </div>
    </div>
    <div class="hps-note"><i class="fas fa-lightbulb"></i><span>不需要專門找時間。利用喝水、上廁所、換工具的空檔做幾個伸展就好。重點是<strong>打斷長時間維持同一個姿勢</strong>（NIOSH, 2021）。</span></div>
  </section>

  <!-- ③ 什麼時候該看醫生 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-stethoscope"></i></div>
      <div>
        <h2>什麼時候<span class="accent">該看醫生</span></h2>
        <p>不是所有的痠痛都能「忍一忍就好」。以下情況請儘早就醫。</p>
      </div>
    </div>
    <div class="hps-signs-grid">
      <div class="hps-sign red"><i class="fas fa-circle-exclamation"></i><div><h4>疼痛持續超過 2 週</h4><p>休息後沒有改善，或反覆發作</p></div></div>
      <div class="hps-sign red"><i class="fas fa-circle-exclamation"></i><div><h4>疼痛愈來愈嚴重</h4><p>從「不舒服」變成「很痛」，影響到日常生活或睡眠</p></div></div>
      <div class="hps-sign red"><i class="fas fa-circle-exclamation"></i><div><h4>出現麻木或刺痛感</h4><p>手指、腳趾麻木，或有觸電般的感覺沿手臂/腿部延伸</p></div></div>
      <div class="hps-sign orange"><i class="fas fa-triangle-exclamation"></i><div><h4>關節腫脹或變形</h4><p>膝蓋、手指關節明顯腫大、發熱，或外觀變形</p></div></div>
      <div class="hps-sign orange"><i class="fas fa-triangle-exclamation"></i><div><h4>活動範圍明顯受限</h4><p>手臂舉不起來、蹲不下去、轉頭困難</p></div></div>
      <div class="hps-sign orange"><i class="fas fa-triangle-exclamation"></i><div><h4>受傷後無法負重</h4><p>扭傷後無法站立或行走</p></div></div>
    </div>
  </section>

  <!-- ④ 省力工具 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-gears"></i></div>
      <div>
        <h2>省力工具：讓<span class="accent">機器代替身體</span>承受負擔</h2>
        <p>國際職業安全研究一致指出：以工程控制（engineering controls）取代人力，效果比伸展和護具更根本（NIOSH, 2021）。</p>
      </div>
    </div>
    <div class="hps-tool-grid">
      <div class="hps-tool"><i class="fas fa-dolly"></i><h4>搬運車</h4><p>取代肩扛重物，降低腰部和膝蓋負擔。農糧署提供省力農機具補助（農業部農糧署，2024；補助比例詳見農糧署公告）。</p></div>
      <div class="hps-tool"><i class="fas fa-scissors"></i><h4>電動剪枝機</h4><p>省去手動剪枝的反覆握力動作，降低手腕和手肘的傷害風險。</p></div>
      <div class="hps-tool"><i class="fas fa-chair"></i><h4>田間作業椅</h4><p>蹲姿改坐姿，膝蓋和腰部壓力大幅減少。適用於蔬菜採收。</p></div>
      <div class="hps-tool"><i class="fas fa-up-down-left-right"></i><h4>升降平台</h4><p>果園用升降台取代梯子，減少仰頭和高處墜落風險。</p></div>
    </div>
    <div class="hps-note"><i class="fas fa-link"></i><span>完整農機清單和補助申請方式 → <a href="/occupational-safety/labor-saving-machinery/">省力農機具專區</a></span></div>
  </section>

  <!-- ⑤ 雲嘉就醫資源 -->
  <section class="hps-sec reveal">
    <div class="hps-sec-head">
      <div class="hps-sec-icon"><i class="fas fa-hospital"></i></div>
      <div>
        <h2>雲嘉地區<span class="accent">復健科資源</span></h2>
        <p>痠痛拖久了只會更嚴重，趁早看復健科才是正確的。</p>
      </div>
    </div>
    <div class="hps-hosp-grid">
      <div class="hps-hosp-item"><h4>台大醫院雲林分院</h4><p>設有農業環境與職業健康中心，專門處理農民職業傷害。復健科、骨科、神經內科。</p></div>
      <div class="hps-hosp-item"><h4>雲林長庚醫院</h4><p>復健科、骨科。距離斗六、虎尾地區農友較近。</p></div>
      <div class="hps-hosp-item"><h4>嘉義長庚醫院</h4><p>雲嘉地區規模較大的復健部門（床位與醫師人數來源待確認原始統計）。</p></div>
      <div class="hps-hosp-item"><h4>嘉義基督教醫院</h4><p>復健科、骨科。嘉義市中心交通便利。</p></div>
      <div class="hps-hosp-item"><h4>大林慈濟醫院</h4><p>復健科、骨科。距離雲林南部和嘉義北部農友較近。社區照護資源豐富。</p></div>
    </div>
    <div class="hps-note"><i class="fas fa-shield-halved"></i><span><strong>農職保可以賠：</strong>因農務造成的肌肉骨骼傷害屬於農民職業災害保險的給付範圍。就醫時告知醫師你的工作內容，取得診斷證明後可向農會申請傷病給付（勞動部勞工保險局 [BLI]，2024）。<a href="/healthgood/#hp-rights">了解更多農職保</a></span></div>
  </section>

  <!-- 交叉連結 -->
  <section class="hps-sec hps-cross reveal">
    <h3><i class="fas fa-link"></i> 相關主題</h3>
    <div class="hps-cross-grid">
      <a href="/occupational-safety/labor-saving-machinery/"><i class="fas fa-tractor"></i><span>省力農機具</span><small>八大類農機和政府補助申請方式</small></a>
      <a href="/healthgood/subpage-heat/"><i class="fas fa-temperature-arrow-up"></i><span>熱危害預防</span><small>高溫下肌肉更容易疲勞和受傷</small></a>
      <a href="/healthgood/subpage-mental/"><i class="fas fa-brain"></i><span>心靈紓壓</span><small>慢性疼痛與憂鬱常共病，需一併關注</small></a>
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
      <p class="hps-refs-note">本頁依 APA 第 7 版格式引用以下文獻與政府資源。</p>
      <ul class="hps-refs-list apa7">
        <li>國家衛生研究院（2024）。<em>揭開農業環境健康之謎：透過全國農民和全民健康保險研究資料庫探索台灣農民健康</em>。國衛院電子報。https://enews.nhri.edu.tw/research/9879/</li>
        <li>勞動部勞工保險局（2024）。<em>農民職業災害保險</em>。https://www.bli.gov.tw/0100395.html</li>
        <li>勞動部職業安全衛生署（n.d.）。<em>人因性危害預防計畫指引</em>。https://www.osha.gov.tw/</li>
        <li>農業部農糧署（2024）。<em>省力農機具補助作業要點</em>。https://www.afa.gov.tw/</li>
        <li>National Institute for Occupational Safety and Health. (2021). <em>Revised NIOSH lifting equation</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/lifting/about/index.html</li>
        <li>Osborne, A., Blake, C., Fullen, B. M., Meredith, D., Phelan, J., McNamara, J., &amp; Cunningham, C. (2012). Prevalence of musculoskeletal disorders among farmers: A systematic review. <em>American Journal of Industrial Medicine, 55</em>(2), 143–158. https://doi.org/10.1002/ajim.21033</li>
      </ul>
      <p class="hps-refs-note"><em>待核實項目</em>：Hero「90% 盛行率」「降低 70% 風險」、水稻/果/蔬菜 MSD 百分比區間、嘉義長庚 69 床與 8 位復健科醫師等統計之原始資料來源。</p>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
