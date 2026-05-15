<?php
/**
 * fwdignity theme — front-page.php
 * 知識平台 HUB 版 v3（2026-04-21 第三輪修正）
 *
 * 區塊順序：
 *   Marquee + 氣象(真實CWA) → 最新動態(大卡輪播+列表) → 分眾4大卡 → 7章節 → 影音(真實WP資料)
 *
 * 🔴 文案紅線：影音/最新動態全抓 WP 真實 post，禁止硬寫假文案。
 */
?>
<?php get_header(); ?>

<!-- 走馬燈 + 氣象已由 header.php 的 ticker partial 渲染 -->

<!-- ========== MAIN ========== -->
<div class="fw-main-wrap">

  <main class="fw-main-col" id="main" role="main">

    <!-- ① 最新動態 · 大卡輪播 + 列表（WP posts + MOA RSS） -->
    <section class="fw-sec fw-sec-news reveal">
      <div class="fw-sec-head fw-sec-head-row">
        <div>
          <span class="fw-kicker"><i class="fas fa-star"></i> LATEST · 持續更新</span>
          <h2 class="fw-sec-title">最新<span class="accent">動態</span></h2>
          <p class="fw-sec-subtitle">研究進展、田野紀錄、政策倡議、工作坊與活動公告。</p>
        </div>
        <a href="/thenews/" class="fw-sec-more">所有動態 <i class="fas fa-arrow-right"></i></a>
      </div>

      <?php
      $news_items = fwdignity_get_mixed_news( 5 );
      if ( ! empty( $news_items ) ) :
      ?>
      <div class="fw-news-layout">
        <!-- 左：輪播大卡（5 筆,共用資料源） -->
        <div class="fw-news-feature-slider" data-interval="6000">
          <?php foreach ( $news_items as $idx => $feature ) :
            $has_thumb = ! empty( $feature['thumb'] );
            $is_moa = ( isset( $feature['source'] ) && $feature['source'] === '農業部' );
            $fallback_class = $is_moa ? 'nothumb-moa' : 'nothumb-site';
          ?>
          <a href="<?php echo esc_url( $feature['link'] ); ?>"
             <?php echo $is_moa ? 'target="_blank" rel="noopener"' : ''; ?>
             class="fw-news-feature <?php echo $idx === 0 ? 'is-active' : 'is-hidden'; ?> <?php echo $has_thumb ? 'has-thumb' : $fallback_class; ?>"
             data-slide="<?php echo esc_attr( $idx ); ?>"
             <?php echo $has_thumb ? 'style="background-image:url(' . esc_url( $feature['thumb'] ) . ');"' : ''; ?>>
            <?php if ( ! $has_thumb ) : ?>
            <div class="fw-news-feature-bgicon">
              <i class="fas <?php echo $is_moa ? 'fa-landmark' : 'fa-seedling'; ?>"></i>
            </div>
            <?php endif; ?>
            <div class="fw-news-feature-inner">
              <div class="fw-news-meta">
                <span class="fw-news-date"><?php echo esc_html( $feature['date'] ); ?></span>
                <span class="fw-news-cat"><?php echo esc_html( $feature['source'] ); ?></span>
              </div>
              <h3 class="fw-news-feature-title"><?php echo esc_html( $feature['title'] ); ?></h3>
              <p class="fw-news-feature-excerpt"><?php echo esc_html( $feature['excerpt'] ); ?></p>
              <span class="fw-news-read">閱讀全文 <i class="fas fa-arrow-right"></i></span>
            </div>
          </a>
          <?php endforeach; ?>
          <!-- dots -->
          <div class="fw-news-dots">
            <?php foreach ( $news_items as $idx => $_s ) : ?>
              <span class="fw-news-dot <?php echo $idx === 0 ? 'is-active' : ''; ?>" data-idx="<?php echo esc_attr( $idx ); ?>"></span>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- 右：5 筆列表（點擊 → 切換左側預覽；再點/閱讀全文 才跳轉） -->
        <ul class="fw-news-list">
          <?php foreach ( $news_items as $idx => $item ) : ?>
          <li class="fw-news-li <?php echo $idx === 0 ? 'is-active' : ''; ?>" data-idx="<?php echo esc_attr( $idx ); ?>">
            <div class="fw-news-meta">
              <span class="fw-news-cat"><?php echo esc_html( $item['source'] ); ?></span>
              <span class="fw-news-date"><?php echo esc_html( $item['date'] ); ?></span>
            </div>
            <h4 class="fw-news-list-title"><?php echo esc_html( $item['title'] ); ?></h4>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- 所有動態按鈕移到表格下方 -->
      <div class="fw-news-more-wrap">
        <a href="/thenews/" class="fw-sec-more">所有動態 <i class="fas fa-arrow-right"></i></a>
      </div>

      <?php else : ?>
      <div class="fw-news-empty">
        <i class="fas fa-newspaper"></i>
        <p>尚無動態內容,稍後再回來看看。</p>
      </div>
      <?php endif; ?>
    </section>

    <!-- ④ 影音專區（真實 WP query，Image #32 漸層卡） -->
    <section class="fw-sec fw-sec-media reveal">
      <div class="fw-sec-head">
        <span class="fw-kicker"><i class="fas fa-play"></i> AUDIO &amp; VIDEO</span>
        <h2 class="fw-sec-title">用聲音與影像,<br>聽見<span class="accent">田間的故事</span>。</h2>
        <p class="fw-sec-subtitle">Podcast、紀實影片、課程錄影——每一則都是數字背後的真實場景。</p>
      </div>

      <?php
      $media_items = fwdignity_fetch_media( 6 );
      $media_colors = array( 'm-safety', 'm-intl', 'm-health', 'm-economy', 'm-empower', 'm-research' );
      if ( ! empty( $media_items ) ) :
      ?>
      <div class="fw-media-grid">
        <?php foreach ( $media_items as $i => $m ) :
          $color_class = $media_colors[ $i % count( $media_colors ) ];
          $ep_text = $m['ep'] ? 'EP.' . $m['ep'] : $m['date'];
        ?>
        <a href="<?php echo esc_url( $m['link'] ); ?>" class="fw-media-card <?php echo esc_attr( $color_class ); ?> <?php echo $m['thumb'] ? 'has-thumb' : ''; ?>"
           <?php echo $m['thumb'] ? 'style="--mg-img:url(' . esc_url( $m['thumb'] ) . ');"' : ''; ?>>
          <div class="fw-media-vis">
            <span class="fw-media-ep"><?php echo esc_html( $ep_text ); ?> · <?php echo esc_html( $m['cat_name'] ); ?></span>
            <div class="fw-media-play"><i class="fas fa-play"></i></div>
          </div>
          <div class="fw-media-body">
            <span class="fw-media-tag"><?php echo esc_html( $m['type'] ); ?></span>
            <h3 class="fw-media-title"><?php echo esc_html( $m['title'] ); ?></h3>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <div class="fw-media-more-wrap">
        <a href="/research-result/podcast/" class="fw-sec-more">全部影音 <i class="fas fa-arrow-right"></i></a>
      </div>
      <?php else : ?>
      <div class="fw-news-empty">
        <i class="fas fa-podcast"></i>
        <p>影音內容準備中。</p>
      </div>
      <?php endif; ?>
    </section>

  </main>

  <?php get_template_part( 'template-parts/sidebar' ); ?>

</div>

<?php get_footer(); ?>
