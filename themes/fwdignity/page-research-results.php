<?php
/**
 * fwdignity · page-research-result.php
 * 研究成果 Hub · Research Blue #1E40AF
 * 🔴 紅線:文字 / 數字 / 引用 1:1 復用原頁。
 */
get_header();
?>

<div class="fw-page-layout">

<main class="rr-main" id="main" role="main">

  <!-- Hero -->
  <section class="rr-hero">
    <div class="rr-hero-deco" aria-hidden="true">
      <i class="fas fa-flask rr-deco-1"></i>
      <i class="fas fa-book-open-reader rr-deco-2"></i>
      <i class="fas fa-chart-column rr-deco-3"></i>
    </div>
    <div class="rr-hero-inner">
      <span class="rr-hero-badge"><i class="fas fa-flask"></i> RESEARCH</span>
      <h1 class="rr-hero-title" data-hook-title>研究<span class="accent">成果</span></h1>
      <p class="rr-hero-tagline">以台灣本土實證數據為基石,系統性建構雲嘉農業工作者職業健康與社會安全網知識體系。</p>
      <div class="rr-hero-intro">
        <p>本計畫立基於國立中正大學與臺大醫院雲林分院的跨領域合作,透過人文創新與社會實踐(HISP)計畫的支持,以嘉義縣民雄鄉為核心場域,深入探究農業工作者的職業健康困境。</p>
        <p>研究成果涵蓋:</p>
        <ul class="rr-hero-list">
          <li><i class="fas fa-check-circle"></i> <strong>HISP 季會發表</strong>——計畫階段性研究成果與學術發表</li>
          <li><i class="fas fa-check-circle"></i> <strong>影音 Podcast</strong>——以聲音記錄農業職業健康的真實故事</li>
          <li><i class="fas fa-check-circle"></i> <strong>圖文資訊</strong>——將複雜數據轉化為易懂的視覺化資訊</li>
          <li><i class="fas fa-check-circle"></i> <strong>相關資源</strong>——國內外政府、學術、國際組織資源彙整</li>
        </ul>
      </div>
      <div class="rr-hero-actions">
        <a href="#rr-featured" class="rr-btn-primary" data-magnetic><i class="fas fa-star"></i> 最新成果</a>
        <a href="#rr-categories" class="rr-btn-secondary"><i class="fas fa-folder-tree"></i> 成果分類</a>
      </div>
      <div class="rr-hero-stats">
        <div class="rr-stat">
          <div class="rr-stat-icon" style="--sc:#0D9488;--scl:#CCFBF1;"><i class="fas fa-person-chalkboard"></i></div>
          <div class="rr-stat-body">
            <span class="rr-stat-num" data-count="3">3</span>
            <span class="rr-stat-label">篇 HISP 季會研究發表</span>
          </div>
        </div>
        <div class="rr-stat">
          <div class="rr-stat-icon" style="--sc:#1E40AF;--scl:#DBEAFE;"><i class="fas fa-university"></i></div>
          <div class="rr-stat-body">
            <span class="rr-stat-num" data-count="2">2</span>
            <span class="rr-stat-label">跨領域合作單位</span>
          </div>
        </div>
        <div class="rr-stat">
          <div class="rr-stat-icon" style="--sc:#DC2626;--scl:#FEE2E2;"><i class="fas fa-calendar-check"></i></div>
          <div class="rr-stat-body">
            <span class="rr-stat-num">2025<small>–</small></span>
            <span class="rr-stat-label">持續研究中</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ① 成果分類 -->
  <section class="rr-sec reveal" id="rr-categories">
    <div class="rr-sec-head">
      <div class="rr-sec-icon"><i class="fas fa-folder-tree"></i></div>
      <div>
        <h2>成果<span class="accent">分類</span></h2>
        <p>點擊進入各分類瀏覽完整列表。</p>
      </div>
    </div>
    <div class="rr-cat-grid">
      <a href="/research-result/research-publication/" class="rr-cat" style="--cc:#1565C0;--ccl:#DBEAFE;">
        <div class="rr-cat-icon"><i class="fas fa-book-open"></i></div>
        <h3>研究出版</h3>
        <p>HISP 季會發表、計畫報告</p>
        <span class="rr-cat-count">3 篇</span>
      </a>
      <a href="/research-result/podcast/" class="rr-cat" style="--cc:#C62828;--ccl:#FEE2E2;">
        <div class="rr-cat-icon"><i class="fas fa-podcast"></i></div>
        <h3>影音 Podcast</h3>
        <p>訪談錄音、知識轉譯影片</p>
        <span class="rr-cat-count">持續更新</span>
      </a>
      <a href="/research-result/infographic/" class="rr-cat" style="--cc:#D97706;--ccl:#FEF3C7;">
        <div class="rr-cat-icon"><i class="fas fa-chart-pie"></i></div>
        <h3>圖文資訊</h3>
        <p>Infographics、數據視覺化</p>
        <span class="rr-cat-count">即將推出</span>
      </a>
      <a href="/research-result/download-zone/" class="rr-cat" style="--cc:#0D9488;--ccl:#CCFBF1;">
        <div class="rr-cat-icon"><i class="fas fa-download"></i></div>
        <h3>下載專區</h3>
        <p>簡報、手冊、教材檔案下載</p>
        <span class="rr-cat-count">即將推出</span>
      </a>
      <a href="/research-result/related-resources/" class="rr-cat" style="--cc:#7C3AED;--ccl:#EDE9FE;">
        <div class="rr-cat-icon"><i class="fas fa-link"></i></div>
        <h3>相關資源</h3>
        <p>政府資料庫、相關計畫</p>
        <span class="rr-cat-count">12+ 筆</span>
      </a>
    </div>
  </section>

  <!-- ② 最新成果精選 -->
  <section class="rr-sec reveal" id="rr-featured">
    <div class="rr-sec-head">
      <div class="rr-sec-icon"><i class="fas fa-star"></i></div>
      <div>
        <h2>最新<span class="accent">成果精選</span></h2>
        <p>本計畫於 HISP(人文創新與社會實踐計畫)季會之研究發表。</p>
      </div>
    </div>
    <div class="rr-feat-list">
      <a href="https://www.hisp.ntu.edu.tw/teams/ccu#achievements" target="_blank" rel="noopener" class="rr-feat">
        <div class="rr-feat-type"><i class="fas fa-person-chalkboard"></i> HISP 季會</div>
        <div class="rr-feat-body">
          <h3>地方知識到地方敘事</h3>
          <p class="rr-feat-date"><i class="far fa-calendar"></i> 2026.01.20</p>
          <p class="rr-feat-abstract">115 年 1 月 HISP 季會發表。探討如何將在地農業工作者的實務經驗與生活智慧,轉化為具有傳播力與政策參考價值的地方敘事。</p>
          <div class="rr-feat-tags">
            <span>地方知識</span><span>敘事研究</span><span>社會實踐</span>
          </div>
        </div>
        <div class="rr-feat-cta"><i class="fas fa-arrow-up-right-from-square"></i> 前往新作坊</div>
      </a>
      <a href="https://www.hisp.ntu.edu.tw/teams/ccu#achievements" target="_blank" rel="noopener" class="rr-feat">
        <div class="rr-feat-type"><i class="fas fa-person-chalkboard"></i> HISP 季會</div>
        <div class="rr-feat-body">
          <h3>災後重建與韌性地方社會</h3>
          <p class="rr-feat-date"><i class="far fa-calendar"></i> 2025.10.20</p>
          <p class="rr-feat-abstract">114 年 10 月 HISP 季會發表。聚焦災後農村社區的重建歷程,探討如何透過社會支持網絡與在地組織力量,建構具有韌性的地方社會。</p>
          <div class="rr-feat-tags">
            <span>災後重建</span><span>社區韌性</span><span>農村社會</span>
          </div>
        </div>
        <div class="rr-feat-cta"><i class="fas fa-arrow-up-right-from-square"></i> 前往新作坊</div>
      </a>
      <a href="https://www.hisp.ntu.edu.tw/teams/ccu#achievements" target="_blank" rel="noopener" class="rr-feat">
        <div class="rr-feat-type"><i class="fas fa-person-chalkboard"></i> HISP 季會</div>
        <div class="rr-feat-body">
          <h3>社會實踐的行動評估</h3>
          <p class="rr-feat-date"><i class="far fa-calendar"></i> 2025.07.21</p>
          <p class="rr-feat-abstract">114 年 7 月 HISP 季會發表。回顧與評估計畫的社會實踐行動成效,檢視農業工作者職業健康介入方案的實際影響與改進方向。</p>
          <div class="rr-feat-tags">
            <span>行動評估</span><span>社會實踐</span><span>成效檢視</span>
          </div>
        </div>
        <div class="rr-feat-cta"><i class="fas fa-arrow-up-right-from-square"></i> 前往新作坊</div>
      </a>
    </div>
  </section>

  <!-- ③ 研究取徑 -->
  <section class="rr-sec reveal" id="rr-approach">
    <div class="rr-sec-head">
      <div class="rr-sec-icon"><i class="fas fa-microscope"></i></div>
      <div>
        <h2>研究<span class="accent">取徑</span></h2>
        <p>跨領域合作,從田間到政策的知識建構路徑。</p>
      </div>
    </div>
    <div class="rr-approach-grid">
      <div class="rr-approach" style="--ac:#15803D;--acl:#DCFCE7;">
        <div class="rr-approach-icon"><i class="fas fa-users-rectangle"></i></div>
        <h3>田野調查與質性研究</h3>
        <p>深入雲嘉地區農村社區,透過參與觀察、深度訪談與焦點團體,理解農業工作者的日常勞動經驗與健康困境。</p>
      </div>
      <div class="rr-approach" style="--ac:#1565C0;--acl:#DBEAFE;">
        <div class="rr-approach-icon"><i class="fas fa-chart-column"></i></div>
        <h3>量化數據與實證分析</h3>
        <p>結合農保資料、職災統計與健康調查數據,進行系統性的量化分析,為政策建議提供實證基礎。</p>
      </div>
      <div class="rr-approach" style="--ac:#C62828;--acl:#FEE2E2;">
        <div class="rr-approach-icon"><i class="fas fa-stethoscope"></i></div>
        <h3>醫學專業與職業健康</h3>
        <p>聯合臺大醫院雲林分院職業醫學科,針對農業勞動的特殊職業暴露與健康風險進行專業評估。</p>
      </div>
      <div class="rr-approach" style="--ac:#7C3AED;--acl:#EDE9FE;">
        <div class="rr-approach-icon"><i class="fas fa-bullhorn"></i></div>
        <h3>社會倡議與政策轉譯</h3>
        <p>將學術研究成果轉化為可操作的政策建議與社會倡議行動,推動農業工作者職業健康制度的改善。</p>
      </div>
    </div>
  </section>

  <!-- ④ 合作夥伴 -->
  <section class="rr-sec rr-partners reveal">
    <div class="rr-partner-row">
      <span class="rr-partner-label"><i class="fas fa-handshake"></i> 合作單位</span>
      <div class="rr-partner-list">
        <span class="rr-partner">國立中正大學</span>
        <span class="rr-partner-divider"></span>
        <span class="rr-partner">臺大醫院雲林分院</span>
        <span class="rr-partner-divider"></span>
        <span class="rr-partner">HISP 人文創新與社會實踐計畫</span>
      </div>
    </div>
  </section>

  <!-- ⑤ 交叉連結 -->
  <section class="rr-sec reveal">
    <h3 class="rr-cross-title"><i class="fas fa-compass"></i> 延伸探索</h3>
    <div class="rr-cross-grid">
      <a href="/occupational-safety/" class="rr-cross" style="--cc:#EA580C;"><i class="fas fa-helmet-safety"></i><span>職業安全</span></a>
      <a href="/healthgood/" class="rr-cross" style="--cc:#0891B2;"><i class="fas fa-heart-pulse"></i><span>健康促進</span></a>
      <a href="/economic-insurance/" class="rr-cross" style="--cc:#D97706;"><i class="fas fa-shield-halved"></i><span>經濟與保險</span></a>
      <a href="/dignity-farming-initiative/" class="rr-cross" style="--cc:#E11D48;"><i class="fas fa-bullhorn"></i><span>尊嚴農業倡議</span></a>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer(); ?>
