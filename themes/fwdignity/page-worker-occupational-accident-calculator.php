<?php
/**
 * Template Name: 勞工職業災害權益試算
 * Slug: worker-occupational-accident-calculator
 *
 * C 方案 iframe-self：
 *   ?embed=1 → 只輸出原 post_content（讓 iframe 使用）
 *   否則    → 套 fwdignity wrapper + iframe 指回自己帶 embed=1
 */

// ====== Raw Mode：只輸出工具本身的 HTML（避開 WP 內建 ?embed=1 oEmbed） ======
if ( isset( $_GET['fw_raw'] ) && $_GET['fw_raw'] === '1' ) {
    nocache_headers();
    header( 'Content-Type: text/html; charset=UTF-8' );
    while ( have_posts() ) : the_post();
        echo do_blocks( $post->post_content );
    endwhile;
    exit;
}

get_header(); ?>

<div class="fw-page-layout">

<main class="cc-main" id="main" role="main">

  <!-- Hero -->
  <section class="cc-hero reveal">
    <div class="cc-hero-inner">
      <span class="cc-hero-badge"><i class="fas fa-calculator"></i> CALCULATOR</span>
      <h1 data-hook-title>勞工職業災害<span class="accent">權益試算</span></h1>
      <p class="cc-hero-lead">針對農民、受僱勞工不同身分之職業災害保險給付進行試算。輸入你的基本資料、傷病情況、失能狀態，系統會估算你可申請的傷病給付、醫療津貼、身心障礙給付金額。</p>
      <div class="cc-hero-meta">
        <span class="cc-hero-meta-item"><i class="fas fa-users"></i> 適用：農民 / 受僱勞工</span>
        <span class="cc-hero-meta-item"><i class="fas fa-chart-line"></i> 依據：勞保局官方公式</span>
        <span class="cc-hero-meta-item"><i class="fas fa-shield"></i> 免費使用・不儲存資料</span>
      </div>
    </div>
  </section>

  <!-- ⚠️ 工具警示 banner（強制醒目，置於工具前） -->
  <section class="cc-sec reveal">
    <div class="cc-warn">
      <div class="cc-warn-icon"><i class="fas fa-triangle-exclamation"></i></div>
      <div class="cc-warn-body">
        <p class="cc-warn-title">本工具僅供試算參考</p>
        <p class="cc-warn-desc">試算結果不具法律效力，實際給付金額以勞動部勞工保險局官方認定為準。</p>
      </div>
    </div>
  </section>

  <!-- 試算工具 iframe -->
  <section class="cc-sec reveal">
    <div class="cc-tool-wrap">
      <div class="cc-tool-header">
        <span class="title"><i class="fas fa-calculator"></i> 農勞職災權益試算神器 v2.0</span>
        <div class="actions">
          <a href="<?php echo esc_url( add_query_arg( 'fw_raw', '1', get_permalink() ) ); ?>" target="_blank" rel="noopener" class="cc-tool-btn" data-magnetic><i class="fas fa-up-right-from-square"></i> 新視窗開啟</a>
        </div>
      </div>
      <iframe
        class="cc-tool-iframe"
        src="<?php echo esc_url( add_query_arg( 'fw_raw', '1', get_permalink() ) ); ?>"
        title="勞工職業災害權益試算"
        loading="lazy"
        referrerpolicy="no-referrer"></iframe>
    </div>
  </section>

  <!-- 免責 -->
  <section class="cc-sec reveal">
    <div class="cc-disclaimer">
      <strong><i class="fas fa-triangle-exclamation"></i> 免責聲明：</strong>本試算工具僅為估算參考，不具法律效力。實際給付金額依勞動部勞工保險局認定之投保薪資、加保年資、醫療證明及傷病等級為準。正式申請請備齊相關文件洽詢勞保局或所在地農會。試算結果僅供規劃參考，請勿作為法律文件使用。
    </div>
  </section>

  <!-- 官方連結 -->
  <section class="cc-sec reveal">
    <div class="cc-official">
      <h3><i class="fas fa-building-columns"></i> 正式申請與諮詢窗口</h3>
      <div class="cc-official-list">
        <a href="https://www.bli.gov.tw/0105770.html" target="_blank" rel="noopener" class="cc-official-item">
          <i class="fas fa-building-columns"></i>
          <span>勞保局・農職保專區</span>
        </a>
        <a href="https://www.bli.gov.tw/0105707.html" target="_blank" rel="noopener" class="cc-official-item">
          <i class="fas fa-user-injured"></i>
          <span>勞保局・勞工職災保險</span>
        </a>
        <a href="https://www.bli.gov.tw/0105823.html" target="_blank" rel="noopener" class="cc-official-item">
          <i class="fas fa-file-invoice-dollar"></i>
          <span>職災給付申請書下載</span>
        </a>
        <a href="tel:+880412589988" class="cc-official-item">
          <i class="fas fa-phone"></i>
          <span>勞保局客服 0412-58-9988</span>
        </a>
      </div>
    </div>
  </section>

  <!-- 交叉連結 -->
  <section class="cc-sec reveal">
    <div class="cc-cross">
      <h3><i class="fas fa-compass"></i> 延伸閱讀</h3>
      <div class="cc-cross-links">
        <a href="/economic-insurance/"><i class="fas fa-shield-halved"></i>經濟與保險總覽</a>
        <a href="/occupational-accident-insurance/"><i class="fas fa-user-injured"></i>職業災害保險說明</a>
        <a href="/farmer-worker-retirement-calculator/"><i class="fas fa-piggy-bank"></i>退休金試算</a>
        <a href="/occupational-safety/"><i class="fas fa-helmet-safety"></i>職業安全預防</a>
      </div>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer();
