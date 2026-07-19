<?php
/**
 * Template Name: 研究出版（期刊與季會發表）
 * Slug: research-publication
 */
get_header();

$research_material_ids = array_values(
    array_filter(
        array_map( 'absint', (array) get_option( 'fwdignity_research_material_ids', array() ) )
    )
);

$research_materials = array();
foreach ( $research_material_ids as $research_material_id ) {
    $research_material = get_post( $research_material_id );
    if (
        $research_material instanceof WP_Post
        && 'attachment' === $research_material->post_type
        && 'application/pdf' === get_post_mime_type( $research_material )
    ) {
        $research_materials[] = $research_material;
    }
}
?>

<div class="fw-page-layout fw-page-layout--full">

<main class="rs-main" id="main" role="main">

      <!-- Hero -->
      <section class="rs-hero">
        <div class="rs-hero-inner">
          <span class="rs-hero-badge"><i class="fas fa-book-open"></i> PUBLICATION</span>
          <h1>研究出版</h1>
          <p class="rs-hero-lead">本計畫團隊於 HISP 人文創新與社會實踐計畫季會、國內外期刊與研討會之學術發表紀錄，呈現從田野到論述的研究軌跡。</p>
          <p class="rs-refresh-note">正式書目、發表資訊與外部連結將逐筆核對後持續更新。</p>
        </div>
      </section>

      <?php if ( $research_materials ) : ?>
      <section class="rs-sec rs-materials" aria-labelledby="rs-materials-title">
        <div class="rs-sec-head">
          <div>
            <h2 id="rs-materials-title">本計畫研究資料</h2>
            <p>工作坊與季會簡報 PDF。</p>
          </div>
        </div>
        <div class="rs-pub-list">
          <?php foreach ( $research_materials as $research_material ) : ?>
            <a href="<?php echo esc_url( wp_get_attachment_url( $research_material->ID ) ); ?>" target="_blank" rel="noopener" class="rs-pub">
              <span class="rs-pub-type">PDF</span>
              <div class="rs-pub-body">
                <h3><?php echo esc_html( get_the_title( $research_material ) ); ?></h3>
                <p class="rs-pub-meta">尊嚴農業 · <em>研究資料</em></p>
              </div>
              <span class="rs-pub-cta">開啟 PDF <i class="fas fa-arrow-up-right-from-square"></i></span>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- 類型說明 -->
      <section class="rs-sec">
        <div class="rs-sec-head">
          <div class="rs-sec-icon"><i class="fas fa-list-check"></i></div>
          <div>
            <h2>發表類型</h2>
            <p>依學術產出形式整理現有公開紀錄。</p>
          </div>
        </div>
        <div class="rs-legend">
          <div class="rs-legend-item"><b>HISP</b>人文創新與社會實踐計畫季會</div>
          <div class="rs-legend-item"><b>期刊</b>學術期刊論文</div>
          <div class="rs-legend-item"><b>研討會</b>國內外研討會論文</div>
        </div>
      </section>

      <!-- 2026 發表 -->
      <section class="rs-sec">
        <div class="rs-pub-year"><i class="fas fa-calendar-days"></i> 2026</div>
        <div class="rs-pub-list">
          <a href="https://www.hisp.ntu.edu.tw/teams/ccu#achievements" target="_blank" rel="noopener" class="rs-pub">
            <span class="rs-pub-type">HISP</span>
            <div class="rs-pub-body">
              <h3>地方知識到地方敘事：農業工作者經驗的再現路徑</h3>
              <p class="rs-pub-meta">2026 年 1 月 20 日 · <em>HISP 季會</em> · 國立中正大學團隊</p>
              <p class="rs-pub-authors">探討如何將在地農業工作者的實務經驗與生活智慧，轉化為具有傳播力與政策參考價值的地方敘事。</p>
            </div>
            <span class="rs-pub-cta">前往新作坊 <i class="fas fa-arrow-up-right-from-square"></i></span>
          </a>
        </div>
      </section>

      <!-- 2025 發表 -->
      <section class="rs-sec">
        <div class="rs-pub-year"><i class="fas fa-calendar-days"></i> 2025</div>
        <div class="rs-pub-list">
          <a href="https://www.hisp.ntu.edu.tw/teams/ccu#achievements" target="_blank" rel="noopener" class="rs-pub">
            <span class="rs-pub-type">HISP</span>
            <div class="rs-pub-body">
              <h3>災後重建與韌性地方社會</h3>
              <p class="rs-pub-meta">2025 年 10 月 20 日 · <em>HISP 季會</em> · 國立中正大學團隊</p>
              <p class="rs-pub-authors">聚焦災後農村社區的重建歷程，探討如何透過社會支持網絡與在地組織力量，建構具有韌性的地方社會。</p>
            </div>
            <span class="rs-pub-cta">前往新作坊 <i class="fas fa-arrow-up-right-from-square"></i></span>
          </a>
          <a href="https://www.hisp.ntu.edu.tw/teams/ccu#achievements" target="_blank" rel="noopener" class="rs-pub">
            <span class="rs-pub-type">HISP</span>
            <div class="rs-pub-body">
              <h3>社會實踐的行動評估：農業工作者職業健康介入方案</h3>
              <p class="rs-pub-meta">2025 年 7 月 21 日 · <em>HISP 季會</em> · 國立中正大學團隊</p>
              <p class="rs-pub-authors">回顧與評估計畫的社會實踐行動成效，檢視農業工作者職業健康介入方案的實際影響與改進方向。</p>
            </div>
            <span class="rs-pub-cta">前往新作坊 <i class="fas fa-arrow-up-right-from-square"></i></span>
          </a>
        </div>
      </section>

      <!-- 撰寫中 -->
      <section class="rs-sec">
        <div class="rs-sec-head">
          <div class="rs-sec-icon"><i class="fas fa-pen-ruler"></i></div>
          <div>
            <h2>撰寫中</h2>
            <p>規劃投稿學術期刊之論文主題。</p>
          </div>
        </div>
        <div class="rs-topic-grid">
          <div class="rs-topic">
            <div class="rs-topic-icon"><i class="fas fa-temperature-high"></i></div>
            <h3>臺灣農業工作者熱傷害研究</h3>
            <p>結合田野調查與流行病學資料，系統性整理熱傷害風險因子與在地預防策略。</p>
          </div>
          <div class="rs-topic">
            <div class="rs-topic-icon"><i class="fas fa-person-cane"></i></div>
            <h3>高齡農業工作者尊嚴勞動</h3>
            <p>從勞動尊嚴與社會保障視角，分析高齡農民面對的結構性困境與制度缺口。</p>
          </div>
        </div>
      </section>

      <!-- 交叉連結 -->
      <section class="rs-sec">
        <div class="rs-cross">
          <h3>延伸閱讀</h3>
          <div class="rs-cross-links">
            <a href="/research-results/"><i class="fas fa-circle-nodes"></i>研究成果總覽</a>
            <a href="/podcast/"><i class="fas fa-podcast"></i>Podcast 節目</a>
            <a href="<?php echo esc_url( home_url( '/research-results/related-resources/' ) ); ?>"><i class="fas fa-link"></i>相關資源</a>
          </div>
        </div>
      </section>

    </main>

</div>

<?php get_footer();
