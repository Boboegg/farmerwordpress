<?php
/**
 * Template Name: Podcast · 聽見田裡的聲音
 * Slug: podcast
 */
get_header(); ?>

<div class="fw-page-layout">

<main class="rs-main" id="main" role="main">

  <!-- Hero -->
  <section class="rs-hero reveal">
    <div class="rs-hero-inner">
      <span class="rs-hero-badge"><i class="fas fa-podcast"></i> PODCAST</span>
      <h1 data-hook-title>聽見田裡的聲音</h1>
      <p class="rs-hero-lead">從田間到餐桌，從勞動到尊嚴。透過 Podcast 節目，我們記錄雲嘉農業工作者的實踐故事、健康困境、與在地知識，讓第一線的聲音被聽見。</p>
      <?php
      $podcast_count = (int) wp_count_posts()->publish;
      $podcast_q_count = new WP_Query( array(
        'category_name' => 'podcast',
        'post_status'   => 'publish',
        'posts_per_page'=> -1,
        'fields'        => 'ids',
      ) );
      $ep_count = $podcast_q_count->found_posts;
      wp_reset_postdata();
      ?>
      <div class="rs-hero-stats">
        <div class="rs-hero-stat"><b><?php echo esc_html( $ep_count ); ?></b><span>已發布集數</span></div>
        <div class="rs-hero-stat"><b>持續更新</b><span>定期新增節目</span></div>
        <div class="rs-hero-stat"><b>多平台</b><span>收聽管道多元</span></div>
      </div>
    </div>
  </section>

  <!-- 最新節目 -->
  <section class="rs-sec reveal">
    <div class="rs-sec-head">
      <div class="rs-sec-icon"><i class="fas fa-headphones"></i></div>
      <div>
        <h2>最新<span class="accent">節目</span></h2>
        <p>本計畫與地方夥伴合作之 Podcast 節目集數。</p>
      </div>
    </div>
    <div class="rs-pub-list">
      <?php
      $podcast_q = new WP_Query( array(
        'category_name' => 'podcast',
        'post_status'   => 'publish',
        'posts_per_page'=> 10,
        'orderby'       => 'date',
        'order'         => 'DESC',
      ) );
      if ( $podcast_q->have_posts() ) :
        while ( $podcast_q->have_posts() ) : $podcast_q->the_post();
          $date_str = get_the_date( 'Y.m.d' );
          $excerpt  = get_the_excerpt();
          if ( ! $excerpt ) {
            $excerpt = wp_trim_words( wp_strip_all_tags( get_the_content() ), 45, '…' );
          }
      ?>
      <a href="<?php the_permalink(); ?>" class="rs-pub">
        <span class="rs-pub-type"><i class="fas fa-podcast"></i> EP</span>
        <div class="rs-pub-body">
          <h3><?php the_title(); ?></h3>
          <p class="rs-pub-meta"><i class="far fa-calendar"></i> <?php echo esc_html( $date_str ); ?> · <em>Podcast</em></p>
          <p class="rs-pub-authors"><?php echo esc_html( $excerpt ); ?></p>
        </div>
        <span class="rs-pub-cta">收聽本集 <i class="fas fa-arrow-right"></i></span>
      </a>
      <?php
        endwhile;
        wp_reset_postdata();
      else :
      ?>
      <p style="padding:24px;background:#FAFAFA;border-radius:12px;color:var(--c-text-muted);">尚無已發布節目，敬請期待。</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- 節目主題規劃 -->
  <section class="rs-sec reveal">
    <div class="rs-sec-head">
      <div class="rs-sec-icon"><i class="fas fa-microphone-lines"></i></div>
      <div>
        <h2>節目<span class="accent">主題規劃</span></h2>
        <p>四大主題軸線，呼應本計畫研究重點。</p>
      </div>
    </div>
    <div class="rs-topic-grid">
      <div class="rs-topic">
        <div class="rs-topic-icon"><i class="fas fa-tractor"></i></div>
        <h3>田間實踐</h3>
        <p>訪談第一線農業工作者，聽他們談每日勞動、作物、與土地的故事。</p>
      </div>
      <div class="rs-topic">
        <div class="rs-topic-icon"><i class="fas fa-stethoscope"></i></div>
        <h3>健康與安全</h3>
        <p>邀請職業醫學專家，用白話解析農業職業健康風險與預防策略。</p>
      </div>
      <div class="rs-topic">
        <div class="rs-topic-icon"><i class="fas fa-handshake-angle"></i></div>
        <h3>制度與權益</h3>
        <p>解析農保、職災、退休制度，協助農業工作者知道自己的權益。</p>
      </div>
      <div class="rs-topic">
        <div class="rs-topic-icon"><i class="fas fa-seedling"></i></div>
        <h3>地方敘事</h3>
        <p>記錄雲嘉地方農業社群的轉型、合作、與韌性實踐案例。</p>
      </div>
    </div>
  </section>

  <!-- 收聽平台（待上線 placeholder） -->
  <section class="rs-sec reveal">
    <div class="rs-sec-head">
      <div class="rs-sec-icon"><i class="fas fa-broadcast-tower"></i></div>
      <div>
        <h2>收聽<span class="accent">平台</span></h2>
        <p>節目上線後將於以下主要平台同步發布。</p>
      </div>
    </div>
    <div class="rs-legend">
      <div class="rs-legend-item"><b>Spotify</b>音樂與 Podcast 串流</div>
      <div class="rs-legend-item"><b>Apple Podcast</b>iOS 使用者首選</div>
      <div class="rs-legend-item"><b>YouTube Music</b>Android 平台（原 Google Podcasts，2024 年轉移）</div>
      <div class="rs-legend-item"><b>SoundOn</b>台灣本土</div>
      <div class="rs-legend-item"><b>KKBOX</b>華語音樂平台</div>
    </div>
  </section>

  <!-- 參與合作 -->
  <section class="rs-sec reveal">
    <div class="rs-sec-head">
      <div class="rs-sec-icon"><i class="fas fa-user-plus"></i></div>
      <div>
        <h2>參與<span class="accent">合作</span></h2>
        <p>邀請您與我們一起，讓農業勞動者的聲音被聽見。</p>
      </div>
    </div>
    <div class="rs-topic-grid">
      <div class="rs-topic">
        <div class="rs-topic-icon"><i class="fas fa-comment-dots"></i></div>
        <h3>受訪者招募</h3>
        <p>若您是雲嘉地區農業工作者，願意分享您的故事與經驗，歡迎聯繫我們。</p>
      </div>
      <div class="rs-topic">
        <div class="rs-topic-icon"><i class="fas fa-lightbulb"></i></div>
        <h3>題材建議</h3>
        <p>對農業健康、制度議題有想深入了解的主題？歡迎提供節目主題建議。</p>
      </div>
    </div>
  </section>

  <!-- 交叉連結 -->
  <section class="rs-sec reveal">
    <div class="rs-cross">
      <h3>延伸閱讀</h3>
      <div class="rs-cross-links">
        <a href="/research-results/"><i class="fas fa-circle-nodes"></i>研究成果總覽</a>
        <a href="/research-publications/"><i class="fas fa-book-open"></i>研究出版</a>
        <a href="/download-zone/"><i class="fas fa-download"></i>下載專區</a>
        <a href="/related-resources/"><i class="fas fa-link"></i>相關資源</a>
      </div>
    </div>
  </section>

</main>

<?php get_template_part( 'template-parts/sidebar' ); ?>
</div>

<?php get_footer();
