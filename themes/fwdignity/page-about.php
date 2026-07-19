<?php
/**
 * Template Name: 關於我們
 * Slug: about
 */
get_header();

$advocacy_url = fwdignity_get_public_page_url( 'dignity-farming-initiative' );
?>

<div class="fw-page-layout fw-page-layout--full">

<main class="ab-main" id="main" role="main">

  <!-- Hero -->
  <section class="ab-hero">
    <div class="ab-hero-inner">
      <span class="ab-hero-badge"><i class="fas fa-leaf"></i> ABOUT US</span>
      <h1>尊嚴農業：<br>共構雲嘉農業工作者<br>職業健康的社會安全網</h1>
      <p class="ab-hero-lead">本計畫聚焦農業工作者之<strong>職業健康</strong>與<strong>社會安全網</strong>建構，回應農民長期面臨之高溫曝露、重複性勞動、農藥使用及政策保障不足等風險。雖已有農保與農職保制度，然在認定、預防與制度落差方面仍存缺口，難以全面維護農民健康（勞動部勞工保險局，2024）。</p>
      <p class="ab-hero-quote">農民是工作者，不是補助對象。</p>
    </div>
  </section>

  <!-- 計畫緣起（4 面向） -->
  <section class="ab-sec">
    <div class="ab-sec-head">
      <span class="kicker">Origin · 計畫緣起</span>
      <h2>研究的四大面向</h2>
      <p>從風險掌握到政策倡議，建構完整的農業職業健康社會安全網。</p>
    </div>
    <div class="ab-origin">
      <p class="ab-origin-intro">為建構農業工作者之社會安全網，本研究透過四大面向展開：以<strong>基礎調查</strong>掌握農業勞動現場的傷病樣態；進行<strong>政策研究與跨國比較</strong>，檢視現行社會保障不足；發展具在地適切性的<strong>健康促進教育模組</strong>，提升農民自我保護能力；透過<strong>社會倡議</strong>，凝聚農會、社區組織與學術單位共同行動，推動政策修正。</p>
      <div class="ab-four">
        <div class="ab-four-item">
          <span class="ab-four-num">01</span>
          <h3>基礎調查</h3>
          <p>掌握農業勞動現場的傷病樣態與健康風險。</p>
        </div>
        <div class="ab-four-item">
          <span class="ab-four-num">02</span>
          <h3>政策研究</h3>
          <p>進行跨國比較，檢視現行社會保障不足。</p>
        </div>
        <div class="ab-four-item">
          <span class="ab-four-num">03</span>
          <h3>健康教育</h3>
          <p>發展在地適切的健康促進教育模組。</p>
        </div>
        <div class="ab-four-item">
          <span class="ab-four-num">04</span>
          <h3>社會倡議</h3>
          <p>凝聚多方共識，推動政策修正與制度改革。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 實踐場域 -->
  <section class="ab-sec">
    <div class="ab-sec-head">
      <span class="kicker">Field · 實踐場域</span>
      <h2>嘉義縣民雄鄉</h2>
      <p>以典型臺灣農村的產業結構為研究基地，承載「農業即生活」的地方認同。</p>
    </div>
    <div class="ab-field">
      <div class="ab-field-img">
        <img src="<?php echo esc_url( content_url( 'uploads/2025/12/image-4.webp' ) ); ?>" alt="嘉義縣民雄鄉實踐場域" loading="lazy">
        <span class="ab-field-tag"><i class="fas fa-location-dot"></i> 嘉義縣民雄鄉</span>
      </div>
      <div class="ab-field-text">
        <h3>農業即生活的地方認同</h3>
        <p>嘉義縣民雄鄉位於嘉南平原中部，海拔約 25 公尺，地勢平坦，屬亞熱帶至熱帶季風氣候，具備優良的農業生產條件，尤其適合<strong>鳳梨、熱帶水果</strong>之栽培。</p>
        <p>全鄉面積約 85.5 平方公里，人口約七萬人，為嘉義縣人口最多且人口密度最高之鄉鎮。民雄擁有嘉義縣面積最大的耕地（5,070 公頃），農民數約七千人，主要作物涵蓋稻米、雜糧、蔬果、鳳梨與蓮藕，展現典型臺灣農村的產業結構（民雄鄉公所，n.d.；以上在地數據來源待確認）。</p>
        <p>稻米耕作形塑了平原農業景觀，並承載地方居民之日常生活與歲時循環，形成「<strong>農業即生活</strong>」的地方認同，兼具社會、經濟與文化之代表性。</p>
      </div>
    </div>
  </section>

  <!-- 行動設計 4 卡 -->
  <section class="ab-sec">
    <div class="ab-sec-head">
      <span class="kicker">Action · 行動設計</span>
      <h2>四個行動面向</h2>
      <p>從科學實證到政策推動，建構以農民為中心的系統性行動。</p>
    </div>
    <div class="ab-action-grid">
      <div class="ab-action">
        <span class="ab-action-num">01</span>
        <div class="ab-action-icon"><i class="fas fa-magnifying-glass-chart"></i></div>
        <h3>揭示農業勞動的<br>健康風險</h3>
        <p>釐清農業工作者常見職業傷病的類型、成因與其與勞動史、社會史的關聯。透過問卷、訪談、田野調查與臨床檢測，掌握農業勞動者在高溫、農藥暴露、重複性勞動下的健康樣態，建構農民職業健康的科學性資料基礎（Osborne et al., 2012）。</p>
      </div>
      <div class="ab-action">
        <span class="ab-action-num">02</span>
        <div class="ab-action-icon"><i class="fas fa-scale-balanced"></i></div>
        <h3>檢視制度落差與<br>跨國比較</h3>
        <p>剖析臺灣現行農民健康保險、農職災保等制度在認定與保障上的困境，並參照日本、韓國、澳洲等國經驗。以比較政策的視角，檢視國內外在職災認定、補償制度、預防教育、醫療支持等面向的差異，提出具在地適切性與可行性的改進建議。</p>
      </div>
      <div class="ab-action">
        <span class="ab-action-num">03</span>
        <div class="ab-action-icon"><i class="fas fa-graduation-cap"></i></div>
        <h3>建立以農民為中心<br>的教育模組</h3>
        <p>發展健康促進教育課程，涵蓋正確工作姿勢、農藥安全、肌肉骨骼疾病預防等主題。結合大學社會責任（USR）與社區陪伴學習，強調農民自我健康識能與社群互助機制。</p>
      </div>
      <div class="ab-action">
        <span class="ab-action-num">04</span>
        <div class="ab-action-icon"><i class="fas fa-handshake"></i></div>
        <h3>連結多元行動者<br>以推動政策變革</h3>
        <p>整合大學、醫院、農會、青農組織與社區發展協會，形成跨部門協作平台。透過論壇、媒體報導、策展與公共對話，提升社會對農民職業健康的認識，促使相關部會修正政策並投入資源。</p>
      </div>
    </div>
  </section>

  <!-- 研究團隊 preview -->
  <section class="ab-sec">
    <div class="ab-sec-head">
      <span class="kicker">Team · 研究團隊</span>
      <h2>研究團隊</h2>
      <p>由國立中正大學與臺大醫院雲林分院共同組成，結合教育、傳播、勞工、政治、職業醫學等專業。</p>
    </div>
    <div class="ab-team">
      <div class="ab-team-intro">
        <div>
          <h3>主持團隊</h3>
          <p>計畫由國立中正大學與臺大醫院雲林分院共同主持，整合跨校與跨領域之協同研究者，共同推動農業工作者職業健康的社會安全網建構。</p>
        </div>
        <a href="<?php echo esc_url( home_url( '/about/researchteam/' ) ); ?>" class="ab-team-cta">查看全部團隊成員 <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="ab-team-grid">
        <div class="ab-team-card">
          <div class="ab-team-photo"><img src="<?php echo esc_url( content_url( 'uploads/2025/12/詹勝如-1-scaled-e1766588165755.jpg' ) ); ?>" alt="詹盛如" loading="lazy"></div>
          <div class="ab-team-info">
            <span class="org">國立中正大學</span>
            <p class="name">詹盛如</p>
            <p class="title">計畫主持人</p>
          </div>
        </div>
        <div class="ab-team-card">
          <div class="ab-team-photo"><img src="<?php echo esc_url( content_url( 'uploads/2025/12/管中祥-1-scaled.jpg' ) ); ?>" alt="管中祥" loading="lazy"></div>
          <div class="ab-team-info">
            <span class="org">國立中正大學</span>
            <p class="name">管中祥</p>
            <p class="title">共同主持人兼執行長</p>
          </div>
        </div>
        <div class="ab-team-card">
          <div class="ab-team-photo"><img src="<?php echo esc_url( content_url( 'uploads/2025/12/117640342-scaled.jpg' ) ); ?>" alt="陳毓璟" loading="lazy"></div>
          <div class="ab-team-info">
            <span class="org">國立中正大學</span>
            <p class="name">陳毓璟</p>
            <p class="title">共同主持人</p>
          </div>
        </div>
        <div class="ab-team-card">
          <div class="ab-team-photo"><img src="<?php echo esc_url( content_url( 'uploads/2025/12/李廿偉.webp' ) ); ?>" alt="李念偉" loading="lazy"></div>
          <div class="ab-team-info">
            <span class="org">臺大醫院雲林分院</span>
            <p class="name">李念偉</p>
            <p class="title">環境及職業醫學部 主任</p>
          </div>
        </div>
        <div class="ab-team-card">
          <div class="ab-team-photo"><img src="<?php echo esc_url( content_url( 'uploads/2025/12/陳宗延-scaled-e1766846916931.webp' ) ); ?>" alt="陳宗延" loading="lazy"></div>
          <div class="ab-team-info">
            <span class="org">臺大醫院雲林分院</span>
            <p class="name">陳宗延</p>
            <p class="title">專任主治醫師</p>
          </div>
        </div>
        <div class="ab-team-card">
          <div class="ab-team-photo"><img src="<?php echo esc_url( content_url( 'uploads/2025/12/林韋至-1.webp' ) ); ?>" alt="林韋至" loading="lazy"></div>
          <div class="ab-team-info">
            <span class="org">國立中山大學</span>
            <p class="name">林韋至</p>
            <p class="title">機械與機電工程 特聘教授</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 最終 CTA -->
  <section class="ab-sec">
    <div class="ab-final-cta">
      <h3>讓農業勞動者的<br>健康與尊嚴被看見</h3>
      <p>本計畫邀請農會、社區組織、青農夥伴、衛教人員與關心農業的每一位公民，一起共構雲嘉農業工作者的社會安全網。</p>
      <?php if ( $advocacy_url ) : ?>
        <div class="ab-final-btns">
          <a href="<?php echo esc_url( $advocacy_url ); ?>" class="btn-primary"><i class="fas fa-bullhorn"></i> 加入尊嚴農業倡議</a>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- References 全展開（APA 7） -->
  <style>
    .ab-refs-list.apa7 { list-style: none; padding: 0; }
    .ab-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .ab-refs-list.apa7 li em { font-style: italic; }
    .ab-refs-list.apa7 li a { word-break: break-all; }
    .ab-refs-open { padding: 1.5em 0; }
    .ab-refs-note { font-size: .9em; color: var(--fw-color-muted); margin-bottom: 1em; }
  </style>
  <section class="ab-sec ab-refs">
    <div class="ab-refs-open">
      <div class="ab-sec-head">
        <span class="kicker"><i class="fas fa-book-open"></i> References · 參考資料</span>
        <h2>參考資料</h2>
        <p class="ab-refs-note">本頁引用依 APA 第 7 版格式整理；在地數據（耕地面積、農民人數等）標示「來源待確認」者將於後續版本補齊原始出處。</p>
      </div>
      <ul class="ab-refs-list apa7">
        <li>行政院主計總處（2023）。<em>112 年農家戶口及經濟調查統計結果</em>。https://www.stat.gov.tw/</li>
        <li>勞動部勞工保險局（2024 年 11 月）。<em>農民職業災害保險就醫津貼調整公告（113/11/6 起適用）</em>。https://www.bli.gov.tw/0100668.html</li>
        <li>Osborne, A., Blake, C., Fullen, B. M., Meredith, D., Phelan, J., McNamara, J., &amp; Cunningham, C. (2012). Prevalence of musculoskeletal disorders among farmers: A systematic review. <em>American Journal of Industrial Medicine, 55</em>(2), 143–158. <a href="https://doi.org/10.1002/ajim.21033" target="_blank" rel="noopener">https://doi.org/10.1002/ajim.21033</a></li>
      </ul>
    </div>
  </section>

</main>

</div>

<?php get_footer();
