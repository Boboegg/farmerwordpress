<?php
/**
 * fwdignity · page-farmer-study.php
 * 農學堂 培力課程 · Empower Purple #7C3AED
 * 🔴 紅線:文字 / 數字 / 引用 1:1 復用原頁。
 */
get_header();
?>

<div class="fw-page-layout">

<main class="fs-main" id="main" role="main">

  <!-- Hero -->
  <section class="fs-hero">
    <div class="fs-hero-deco" aria-hidden="true">
      <i class="fas fa-graduation-cap fs-deco-1"></i>
      <i class="fas fa-book-open-reader fs-deco-2"></i>
      <i class="fas fa-lightbulb fs-deco-3"></i>
    </div>
    <div class="fs-hero-inner">
      <span class="fs-hero-badge"><i class="fas fa-graduation-cap"></i> 培力與實踐</span>
      <h1 class="fs-hero-title" data-hook-title>農學堂 <span class="accent">學習園區</span></h1>
      <p class="fs-hero-subtitle">從 3 分鐘微學習到整日工作坊,從線上影片到田間實作——為雲嘉農友打造最務實的職業安全與健康學習資源,讓每位農民都能「安全做、健康活」（農業部，n.d.；National Institute for Occupational Safety and Health [NIOSH], 2024）。</p>
      <div class="fs-hero-nav">
        <a href="#fs-videos" class="fs-chip"><i class="fas fa-play-circle"></i> 微學習影片</a>
        <a href="#fs-workshops" class="fs-chip"><i class="fas fa-chalkboard-user"></i> 工作坊課程</a>
        <a href="#fs-platforms" class="fs-chip"><i class="fas fa-globe"></i> 學習平台</a>
        <a href="#fs-resources" class="fs-chip"><i class="fas fa-download"></i> 教材下載</a>
      </div>
      <div class="fs-hero-stats">
        <div class="fs-hero-stat">
          <span class="fs-hero-stat-num" data-count="6">6</span>
          <span class="fs-hero-stat-label">大學習主題</span>
        </div>
        <div class="fs-hero-stat">
          <span class="fs-hero-stat-num">170<small>+</small></span>
          <span class="fs-hero-stat-label">支國際安全影片（NIOSH, 2024）</span>
        </div>
        <div class="fs-hero-stat">
          <span class="fs-hero-stat-num">近百</span>
          <span class="fs-hero-stat-label">門農民學院數位課程（截至 2024 年）</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ① 六大核心議題 -->
  <section class="fs-sec reveal" id="fs-paths">
    <div class="fs-sec-head">
      <div class="fs-sec-icon"><i class="fas fa-route"></i></div>
      <div>
        <h2>六大<span class="accent">核心議題</span></h2>
        <p>農業工作者最常面對的六大風險議題。專屬教材持續開發中,可先參考各議題對應的深度專區與外部學習平台（Occupational Safety and Health Administration [OSHA], n.d.；World Health Organization [WHO], 2022）。</p>
      </div>
    </div>
    <div class="fs-path-grid">
      <div class="fs-path" style="--pc:#DC2626;--pcl:#FEE2E2;"><div class="fs-path-icon"><i class="fas fa-temperature-high"></i></div><h4>熱危害防護</h4><p>中暑預防、熱指數監測、急救處置</p></div>
      <div class="fs-path" style="--pc:#CA8A04;--pcl:#FEFCE8;"><div class="fs-path-icon"><i class="fas fa-skull-crossbones"></i></div><h4>農藥安全</h4><p>安全用藥、防護裝備、中毒急救</p></div>
      <div class="fs-path" style="--pc:#2563EB;--pcl:#EFF6FF;"><div class="fs-path-icon"><i class="fas fa-person-walking"></i></div><h4>人因工程</h4><p>搬運姿勢、肌骨保健、腰背保護</p></div>
      <div class="fs-path" style="--pc:#16A34A;--pcl:#F0FDF4;"><div class="fs-path-icon"><i class="fas fa-tractor"></i></div><h4>農機安全</h4><p>操作規範、保養維修、翻覆防護</p></div>
      <div class="fs-path" style="--pc:#9333EA;--pcl:#FAF5FF;"><div class="fs-path-icon"><i class="fas fa-helmet-safety"></i></div><h4>個人防護</h4><p>PPE 穿戴、呼吸防護、紫外線防護</p></div>
      <div class="fs-path" style="--pc:#EA580C;--pcl:#FFF7ED;"><div class="fs-path-icon"><i class="fas fa-shield-halved"></i></div><h4>保險權益</h4><p>農保、職災保險、退休儲金</p></div>
    </div>
  </section>

  <!-- ② 影音專區(shortcode) -->
  <section class="fs-sec reveal" id="fs-videos">
    <div class="fs-sec-head">
      <div class="fs-sec-icon"><i class="fas fa-film"></i></div>
      <div>
        <h2>農業<span class="accent">影音專區</span></h2>
        <p>自動抓取農業部官方 YouTube 頻道最新影片,涵蓋農業技術、食農教育、綠色照顧等主題,點擊可直接播放（農業部，n.d.）。</p>
      </div>
    </div>
    <div class="fs-shortcode-wrap">
      <?php echo do_shortcode( '[farmer_videos count="6"]' ); ?>
    </div>
  </section>

  <!-- ③ 學習平台導覽 -->
  <section class="fs-sec reveal" id="fs-platforms">
    <div class="fs-sec-head">
      <div class="fs-sec-icon"><i class="fas fa-globe"></i></div>
      <div>
        <h2>學習<span class="accent">平台導覽</span></h2>
        <p>嚴選台灣政府與國際權威機構提供的免費線上學習資源,為農友打造完整的知識補給站。</p>
      </div>
    </div>

    <h3 class="fs-group-title"><i class="fas fa-flag"></i> 台灣官方學習平台</h3>
    <div class="fs-platform-grid">
      <a href="https://academy.moa.gov.tw/" target="_blank" rel="noopener" class="fs-platform" style="--pc:#16A34A;--pcl:#F0FDF4;">
        <span class="fs-platform-badge tw">官方</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fas fa-seedling"></i></div>
          <div><h4>農民學院</h4><span class="fs-platform-org">農業部</span></div>
        </div>
        <p>台灣最大農業學習平台,近百門數位課程（截至 2024 年）涵蓋農糧、畜產、水產、經營管理等 7 大類，訓練採入門班、初階班、進階班、高階班 4 級分流；入門班已全面數位化,隨時隨地可學（農業部，n.d.）。</p>
        <div class="fs-platform-tags"><span>入門班</span><span>初階班</span><span>進階班</span><span>高階班</span><span>農場見習</span></div>
      </a>
      <a href="https://agdigischool.atri.org.tw/" target="_blank" rel="noopener" class="fs-platform" style="--pc:#2563EB;--pcl:#EFF6FF;">
        <span class="fs-platform-badge tw">官方</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fas fa-laptop-code"></i></div>
          <div><h4>農業數位學堂</h4><span class="fs-platform-org">農業科技研究院</span></div>
        </div>
        <p>提供線上學習與直播課程,設有學習歷程證明系統。涵蓋智慧農業、數位行銷、農業創新等前瞻主題（農業科技研究院，n.d.）。</p>
        <div class="fs-platform-tags"><span>數位課程</span><span>直播教學</span><span>學習證明</span></div>
      </a>
      <a href="https://isafe.osha.gov.tw/mooc/index.php" target="_blank" rel="noopener" class="fs-platform" style="--pc:#DC2626;--pcl:#FEF2F2;">
        <span class="fs-platform-badge tw">官方</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fas fa-hard-hat"></i></div>
          <div><h4>職業安全衛生數位學習平台</h4><span class="fs-platform-org">勞動部職安署</span></div>
        </div>
        <p>免費職安衛線上課程,包含一般安全衛生教育訓練教材。適合所有勞動者,農業工作者也適用（勞動部職業安全衛生署，n.d.）。</p>
        <div class="fs-platform-tags"><span>安全衛生</span><span>教育訓練</span><span>免費</span></div>
      </a>
      <a href="https://www.hdares.gov.tw/ws.php?id=1484" target="_blank" rel="noopener" class="fs-platform" style="--pc:#CA8A04;--pcl:#FEFCE8;">
        <span class="fs-platform-badge tw">官方</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fas fa-video"></i></div>
          <div><h4>農業改良場數位學習專區</h4><span class="fs-platform-org">花蓮區農業改良場</span></div>
        </div>
        <p>各地農業改良場製作的種植技術教學影片,含作物栽培、病蟲害防治、有機農業實務等在地化內容（花蓮區農業改良場，n.d.）。</p>
        <div class="fs-platform-tags"><span>栽培技術</span><span>病蟲害</span><span>影片</span></div>
      </a>
    </div>

    <h3 class="fs-group-title"><i class="fas fa-earth-americas"></i> 國際農業安全學習資源</h3>
    <div class="fs-platform-grid">
      <a href="https://www.youtube.com/@USagCenters" target="_blank" rel="noopener" class="fs-platform" style="--pc:#DC2626;--pcl:#FEF2F2;">
        <span class="fs-platform-badge intl">國際</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fab fa-youtube"></i></div>
          <div><h4>NIOSH 農業安全影片頻道</h4><span class="fs-platform-org">CDC / NIOSH</span></div>
        </div>
        <p>美國國家職業安全衛生研究所農業安全中心 YouTube 頻道,170+ 支影片,多語字幕,涵蓋農藥、感染、穀倉、農機安全等主題（NIOSH, 2024）。</p>
        <div class="fs-platform-tags"><span>YouTube</span><span>多語字幕</span><span>170+ 影片</span></div>
      </a>
      <a href="https://www.osha.gov/agricultural-operations/resources" target="_blank" rel="noopener" class="fs-platform" style="--pc:#16A34A;--pcl:#F0FDF4;">
        <span class="fs-platform-badge intl">國際</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fas fa-book-open"></i></div>
          <div><h4>OSHA 農業作業資源中心</h4><span class="fs-platform-org">美國勞動部 OSHA</span></div>
        </div>
        <p>農業危害識別與控制完整指引,含熱傷害預防、農藥安全、PPE 標準、青少年農業安全互動工具等免費教材（OSHA, n.d.）。</p>
        <div class="fs-platform-tags"><span>教材</span><span>互動工具</span><span>指引</span></div>
      </a>
      <a href="https://www.necasag.org/safetytraining/" target="_blank" rel="noopener" class="fs-platform" style="--pc:#2563EB;--pcl:#EFF6FF;">
        <span class="fs-platform-badge intl">國際</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fas fa-user-shield"></i></div>
          <div><h4>NECAS 農業安全訓練</h4><span class="fs-platform-org">美國國家農業安全教育中心</span></div>
        </div>
        <p>穀倉密閉空間安全、糞池安全、跌落防護、穀物粉塵火災預防等專業訓練課程,適合農場管理者進修（National Education Center for Agricultural Safety [NECAS], n.d.）。</p>
        <div class="fs-platform-tags"><span>密閉空間</span><span>跌落防護</span><span>專業訓練</span></div>
      </a>
      <a href="https://www.sh168.org.tw/shortFilm.aspx" target="_blank" rel="noopener" class="fs-platform" style="--pc:#9333EA;--pcl:#FAF5FF;">
        <span class="fs-platform-badge tw">官方</span>
        <div class="fs-platform-head">
          <div class="fs-platform-icon"><i class="fas fa-clapperboard"></i></div>
          <div><h4>職安衛宣導短片</h4><span class="fs-platform-org">中小企業安全衛生資訊網</span></div>
        </div>
        <p>勞動部職安署委託製作的安全衛生宣導影片合集,含火災爆炸預防、濕熱環境危害、外籍移工多語版本等（勞動部職業安全衛生署，n.d.）。</p>
        <div class="fs-platform-tags"><span>宣導影片</span><span>多語版</span><span>免費</span></div>
      </a>
    </div>
  </section>

  <!-- ④ 近期課程(shortcode) -->
  <section class="fs-sec reveal" id="fs-workshops">
    <div class="fs-sec-head">
      <div class="fs-sec-icon"><i class="fas fa-chalkboard-user"></i></div>
      <div>
        <h2>農民學院<span class="accent">近期課程</span></h2>
        <p>自動抓取農民學院最新訓練課程,資料每 12 小時更新。點擊可前往官網查看詳情與報名（農業部，n.d.）。</p>
      </div>
    </div>
    <div class="fs-shortcode-wrap">
      <?php echo do_shortcode( '[farmer_courses count="6"]' ); ?>
    </div>
  </section>

  <!-- ⑤ 教材下載 -->
  <section class="fs-sec reveal" id="fs-resources">
    <div class="fs-sec-head">
      <div class="fs-sec-icon"><i class="fas fa-folder-open"></i></div>
      <div>
        <h2>教材與<span class="accent">資源下載</span></h2>
        <p>實用圖解懶人包、急救卡片、檢核表等,列印帶到田間隨時查閱。</p>
      </div>
    </div>
    <div class="fs-dl-grid">
      <a href="https://www.osha.gov.tw/media/kp2pvhn1/%E4%BA%BA%E5%9B%A0%E6%80%A7%E5%8D%B1%E5%AE%B3%E9%A0%90%E9%98%B2%E8%A8%88%E7%95%AB%E6%8C%87%E5%BC%95.pdf?mediaDL=true" target="_blank" rel="noopener" class="fs-dl" style="--dc:#DC2626;">
        <div class="fs-dl-icon"><i class="fas fa-file-pdf"></i></div>
        <div class="fs-dl-info"><h4>人因性危害預防計畫指引</h4><span>PDF・勞動部職安署・含肌骨傷病評估檢核表</span></div>
        <div class="fs-dl-arrow"><i class="fas fa-download"></i></div>
      </a>
      <a href="https://www.coapre.org.tw/files/20240528140047760.pdf" target="_blank" rel="noopener" class="fs-dl" style="--dc:#F59E0B;">
        <div class="fs-dl-icon"><i class="fas fa-file-pdf"></i></div>
        <div class="fs-dl-info"><h4>職場人因性危害預防</h4><span>PDF・職業災害預防及重建中心・教育訓練教材</span></div>
        <div class="fs-dl-arrow"><i class="fas fa-download"></i></div>
      </a>
      <a href="https://www.osha.gov.tw/media/pocp0fbr/%E8%81%B7%E5%A0%B4%E4%BA%BA%E5%9B%A0%E5%8D%B1%E5%AE%B3%E9%A0%90%E9%98%B2%E5%84%AA%E8%89%AF%E6%A1%88%E4%BE%8B%E4%BD%9C%E5%93%81%E9%9B%86.pdf" target="_blank" rel="noopener" class="fs-dl" style="--dc:#7C3AED;">
        <div class="fs-dl-icon"><i class="fas fa-file-pdf"></i></div>
        <div class="fs-dl-info"><h4>職場人因危害預防優良案例作品集</h4><span>PDF・勞動部職安署・實際改善案例與成效</span></div>
        <div class="fs-dl-arrow"><i class="fas fa-download"></i></div>
      </a>
      <a href="https://www.cdc.gov/niosh/agriculture/about/index.html" target="_blank" rel="noopener" class="fs-dl" style="--dc:#2563EB;">
        <div class="fs-dl-icon"><i class="fas fa-file-lines"></i></div>
        <div class="fs-dl-info"><h4>NIOSH Simple Solutions: Ergonomics for Farmworkers</h4><span>英文・NIOSH・農業人因工程實用指南</span></div>
        <div class="fs-dl-arrow"><i class="fas fa-external-link-alt"></i></div>
      </a>
      <a href="https://www.bli.gov.tw/" target="_blank" rel="noopener" class="fs-dl" style="--dc:#16A34A;">
        <div class="fs-dl-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="fs-dl-info"><h4>農民職業災害保險申請指南</h4><span>勞保局・投保資格、給付項目與申請流程</span></div>
        <div class="fs-dl-arrow"><i class="fas fa-external-link-alt"></i></div>
      </a>
    </div>
  </section>

  <!-- ⑥ 學習園區概念 -->
  <section class="fs-sec reveal" id="fs-concept">
    <div class="fs-sec-head">
      <div class="fs-sec-icon"><i class="fas fa-lightbulb"></i></div>
      <div>
        <h2>學習<span class="accent">園區概念</span></h2>
        <p>農學堂以「線上自學 → 實體實作 → 持續精進」三階段循環,打造農民終身學習生態系。</p>
      </div>
    </div>
    <div class="fs-flow">
      <div class="fs-flow-step" style="--fc:#2563EB;">
        <div class="fs-flow-num">1</div>
        <div class="fs-flow-icon"><i class="fas fa-mobile-screen-button"></i></div>
        <h4>線上自學</h4>
        <p>3 分鐘微學習影片<br>隨時隨地、田間休息就能看<br>農民學院近百門數位課程（農業部，n.d.）</p>
      </div>
      <div class="fs-flow-arrow"><i class="fas fa-arrow-right-long"></i></div>
      <div class="fs-flow-step" style="--fc:#7C3AED;">
        <div class="fs-flow-num">2</div>
        <div class="fs-flow-icon"><i class="fas fa-hands-holding"></i></div>
        <h4>實體實作</h4>
        <p>工作坊動手操作<br>田間實地演練<br>專家現場指導</p>
      </div>
      <div class="fs-flow-arrow"><i class="fas fa-arrow-right-long"></i></div>
      <div class="fs-flow-step" style="--fc:#16A34A;">
        <div class="fs-flow-num">3</div>
        <div class="fs-flow-icon"><i class="fas fa-arrows-rotate"></i></div>
        <h4>持續精進</h4>
        <p>進階課程與資源<br>國際研究新知<br>HISP 社群交流</p>
      </div>
    </div>

    <div class="fs-partners">
      <h4><i class="fas fa-handshake"></i> 合作夥伴與資源來源</h4>
      <div class="fs-partner-tags">
        <span>農業部農民學院</span>
        <span>農業科技研究院</span>
        <span>勞動部職安署</span>
        <span>CDC / NIOSH</span>
        <span>OSHA</span>
        <span>NECAS</span>
        <span>WHO 農藥安全</span>
        <span>ILO 農業安全</span>
        <span>嘉義大學農推中心</span>
        <span>HISP 計畫</span>
      </div>
    </div>
  </section>

  <!-- ⑦ 交叉連結 -->
  <section class="fs-sec reveal">
    <h3 class="fs-cross-title"><i class="fas fa-link"></i> 延伸探索</h3>
    <div class="fs-cross-grid">
      <a href="/occupational-safety/" class="fs-cross" style="--cc:#EA580C;"><i class="fas fa-helmet-safety"></i><span>職業安全</span><small>防護具型錄與風險評估工具</small></a>
      <a href="/healthgood/" class="fs-cross" style="--cc:#0891B2;"><i class="fas fa-heart-pulse"></i><span>健康促進</span><small>人體互動圖與復健運動處方</small></a>
      <a href="/economic-insurance/" class="fs-cross" style="--cc:#D97706;"><i class="fas fa-shield-cat"></i><span>經濟與保險</span><small>職災保險、農作物保險、補助資源</small></a>
      <a href="/research-result/" class="fs-cross" style="--cc:#1E40AF;"><i class="fas fa-flask"></i><span>研究成果</span><small>最新研究出版與 Podcast</small></a>
    </div>
  </section>

  <!-- ⑧ References 全展開（APA 7） -->
  <style>
    .fs-refs-list.apa7 { list-style: none; padding: 0; }
    .fs-refs-list.apa7 li { padding-left: 2em; text-indent: -2em; margin-bottom: .8em; line-height: 1.6; }
    .fs-refs-list.apa7 li em { font-style: italic; }
    .fs-refs-list.apa7 li a { word-break: break-all; }
    .fs-refs-note { color: #6b7280; font-size: .92em; }
  </style>
  <section class="fs-sec reveal fs-refs">
    <div class="fs-sec-head">
      <div class="fs-sec-icon"><i class="fas fa-book-open"></i></div>
      <div>
        <h2>參考<span class="accent">資料</span></h2>
        <p class="fs-refs-note">本頁內容依 APA 第 7 版格式引用以下台灣政府與國際職業安全機構公開資料。數位課程數量為截至 2024 年之約略統計，實際開課數以各平台當年度公告為準。</p>
      </div>
    </div>
    <ul class="fs-refs-list apa7">
      <li>行政院主計總處（2023）。<em>112 年農家戶口及經濟調查統計結果</em>。https://www.stat.gov.tw/</li>
      <li>花蓮區農業改良場（n.d.）。<em>農業改良場數位學習專區</em>。https://www.hdares.gov.tw/ws.php?id=1484</li>
      <li>勞動部職業安全衛生署（n.d.）。<em>職業安全衛生數位學習平台</em>。https://isafe.osha.gov.tw/mooc/index.php</li>
      <li>農業科技研究院（n.d.）。<em>農業數位學堂</em>。https://agdigischool.atri.org.tw/</li>
      <li>農業部（n.d.）。<em>農民學院</em>。https://academy.moa.gov.tw/</li>
      <li>National Education Center for Agricultural Safety. (n.d.). <em>Agricultural safety training</em>. https://www.necasag.org/safetytraining/</li>
      <li>National Institute for Occupational Safety and Health. (2024). <em>Agricultural safety</em>. Centers for Disease Control and Prevention. https://www.cdc.gov/niosh/agriculture/about/index.html</li>
      <li>Occupational Safety and Health Administration. (n.d.). <em>Agricultural operations: Resources</em>. U.S. Department of Labor. https://www.osha.gov/agricultural-operations/resources</li>
      <li>World Health Organization. (2022). <em>Pesticide residues in food</em>. https://www.who.int/news-room/fact-sheets/detail/pesticide-residues-in-food</li>
    </ul>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
