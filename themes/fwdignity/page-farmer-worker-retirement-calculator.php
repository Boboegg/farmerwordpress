<?php
/**
 * Template Name: 農民、勞工職涯退休金試算
 * Slug: farmer-worker-retirement-calculator
 *
 * C 方案 iframe-self：
 *   ?embed=1 → 只輸出原 post_content
 *   否則    → 套 fwdignity wrapper + iframe 指回自己
 */

// ====== Raw Mode（避開 WP 內建 ?embed=1 oEmbed） ======
if ( isset( $_GET['fw_raw'] ) && $_GET['fw_raw'] === '1' ) {
    nocache_headers();
    header( 'Content-Type: text/html; charset=UTF-8' );
    while ( have_posts() ) : the_post();
        // 此工具 post_content 沒有 <!DOCTYPE>，包一層最小 HTML 確保 viewport 正常
        $raw = do_blocks( $post->post_content );
        if ( stripos( $raw, '<!doctype' ) === false && stripos( $raw, '<html' ) === false ) {
            echo '<!DOCTYPE html><html lang="zh-TW"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>退休金試算</title><style>body{margin:0;background:#f9fafb;font-family:"Noto Sans TC",system-ui,sans-serif;}</style></head><body>';
            echo $raw;
            echo '</body></html>';
        } else {
            echo $raw;
        }
    endwhile;
    exit;
}

get_header(); ?>

<div class="fw-page-layout">

<main class="cc-main" id="main" role="main">

  <!-- Hero -->
  <section class="cc-hero reveal">
    <div class="cc-hero-inner">
      <span class="cc-hero-badge"><i class="fas fa-piggy-bank"></i> RETIREMENT CALCULATOR</span>
      <h1 data-hook-title>農民、勞工<span class="accent">職涯退休金</span>試算</h1>
      <p class="cc-hero-lead">整合農民退休儲金、勞保老年年金、勞退新制三大退休制度。輸入你的年齡、投保年資、提繳比例，試算未來每月可領取的退休給付概況，幫你規劃退休後的經濟安全網。</p>
      <div class="cc-hero-meta">
        <span class="cc-hero-meta-item"><i class="fas fa-users"></i> 適用：農民 / 勞工 / 混合身分</span>
        <span class="cc-hero-meta-item"><i class="fas fa-layer-group"></i> 整合：農退 + 勞保 + 勞退</span>
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
        <p class="cc-warn-desc">試算結果不具法律效力，實際退休給付金額依投保年資、平均月投保薪資、制度修正與法令變更而定，以勞動部勞工保險局官方為準。</p>
      </div>
    </div>
  </section>

  <!-- 試算工具 iframe -->
  <section class="cc-sec reveal">
    <div class="cc-tool-wrap">
      <div class="cc-tool-header">
        <span class="title"><i class="fas fa-piggy-bank"></i> 勞農職涯退休金試算 5.0</span>
        <div class="actions">
          <a href="<?php echo esc_url( add_query_arg( 'fw_raw', '1', get_permalink() ) ); ?>" target="_blank" rel="noopener" class="cc-tool-btn" data-magnetic><i class="fas fa-up-right-from-square"></i> 新視窗開啟</a>
        </div>
      </div>
      <iframe
        class="cc-tool-iframe short"
        src="<?php echo esc_url( add_query_arg( 'fw_raw', '1', get_permalink() ) ); ?>"
        title="農民、勞工職涯退休金試算"
        loading="lazy"
        referrerpolicy="no-referrer"></iframe>
    </div>
  </section>

  <!-- 免責 -->
  <section class="cc-sec reveal">
    <div class="cc-disclaimer">
      <strong><i class="fas fa-triangle-exclamation"></i> 免責聲明：</strong>本試算工具僅為估算參考，不具法律效力。實際退休給付金額依投保年資、平均月投保薪資、退休年齡、制度修正及法令變更而定。農民退休儲金條例、勞工保險條例、勞工退休金條例之正式試算請洽勞保局或使用官方試算系統。本工具試算結果僅供規劃參考，不作為法律依據。
    </div>
  </section>

  <!-- 官方連結 -->
  <section class="cc-sec reveal">
    <div class="cc-official">
      <h3><i class="fas fa-building-columns"></i> 官方試算與諮詢</h3>
      <div class="cc-official-list">
        <a href="https://www.bli.gov.tw/0105770.html" target="_blank" rel="noopener" class="cc-official-item">
          <i class="fas fa-seedling"></i>
          <span>勞保局・農民退休儲金</span>
        </a>
        <a href="https://edesk.bli.gov.tw/aa06/" target="_blank" rel="noopener" class="cc-official-item">
          <i class="fas fa-calculator"></i>
          <span>勞保局・老年年金試算</span>
        </a>
        <a href="https://edesk.bli.gov.tw/aa10/" target="_blank" rel="noopener" class="cc-official-item">
          <i class="fas fa-piggy-bank"></i>
          <span>勞保局・勞退試算</span>
        </a>
        <a href="tel:0412589988" class="cc-official-item">
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
        <a href="/worker-occupational-accident-calculator/"><i class="fas fa-user-injured"></i>職災權益試算</a>
        <a href="/occupational-accident-insurance/"><i class="fas fa-bed-pulse"></i>職災保險說明</a>
        <a href="/agricultural-subsidy-resources/"><i class="fas fa-hand-holding-dollar"></i>補助資源</a>
      </div>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer();
