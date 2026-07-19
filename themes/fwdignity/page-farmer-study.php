<?php
/**
 * Template Name: 農學堂
 * Slug: farmer-study
 * 農學堂。
 */

get_header();

$training_posts = fwdignity_field_query(
    array(
        'posts_per_page' => 6,
        'tax_query'      => array( fwdignity_field_tax_clause( array( 'agri-school', 'education-farmer' ) ) ),
    )
);

$learning_topics = array(
    array( 'number' => '01', 'title' => '熱危害防護', 'copy' => '辨識高溫風險、安排作業節奏、補充水分與處理熱傷害。' ),
    array( 'number' => '02', 'title' => '農藥安全', 'copy' => '從標示閱讀、防護裝備到施作後清潔，降低接觸與中毒風險。' ),
    array( 'number' => '03', 'title' => '肌肉骨骼健康', 'copy' => '理解重複性勞動與搬運負荷，調整工具、姿勢與工作流程。' ),
    array( 'number' => '04', 'title' => '農機與作業安全', 'copy' => '把設備操作、保養與場域風險納入每日工作判斷。' ),
    array( 'number' => '05', 'title' => '個人防護', 'copy' => '依工作情境選擇合適的呼吸、皮膚、眼睛與身體防護。' ),
    array( 'number' => '06', 'title' => '保險與權益', 'copy' => '認識農保、職災給付與申請路徑，讓保障真正接得上需要。' ),
);

?>

<main class="training-page" id="main">
  <header class="training-hero">
    <div>
      <p class="training-eyebrow">Learning in Practice</p>
      <h1><?php the_title(); ?></h1>
      <p>從農民日常工作情境出發，把職業健康、安全與保障議題轉成可以理解、討論與帶回現場使用的內容。現階段以直接邀約的培力為主，網站呈現活動完成後的整理與延伸資源。</p>
      <nav aria-label="本頁段落">
        <a href="#training-records">農學堂紀錄</a>
        <a href="<?php echo esc_url( home_url( '/farming-guide/#fg-learning-support' ) ); ?>">前往學習與支持</a>
      </nav>
    </div>
  </header>

  <section class="training-section training-topics" aria-labelledby="training-topics-title">
    <div class="training-section-head">
      <div><p class="training-eyebrow">Learning Themes</p><h2 id="training-topics-title">農學堂主題</h2></div>
      <p>主題不是固定課綱，而是依每次參與者、產業與場域需要重新組合。</p>
    </div>
    <div class="training-topic-grid">
      <?php foreach ( $learning_topics as $topic ) : ?>
        <article><span><?php echo esc_html( $topic['number'] ); ?></span><h3><?php echo esc_html( $topic['title'] ); ?></h3><p><?php echo esc_html( $topic['copy'] ); ?></p></article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="training-section" id="training-records" aria-labelledby="training-records-title">
    <div class="training-section-head">
      <div><p class="training-eyebrow">Completed Sessions</p><h2 id="training-records-title">農學堂紀錄</h2></div>
      <p>活動後整理會保留討論脈絡、現場觀察與可延伸使用的知識。</p>
    </div>
    <?php if ( $training_posts->have_posts() ) : ?>
      <div class="training-record-list">
        <?php while ( $training_posts->have_posts() ) : $training_posts->the_post(); ?>
          <article class="training-record<?php echo has_post_thumbnail() ? '' : ' training-record--text-only'; ?>">
            <?php if ( has_post_thumbnail() ) : ?>
              <a class="training-record-media training-record__media" href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
              </a>
            <?php endif; ?>
            <div class="training-record__body">
              <time datetime="<?php echo esc_attr( fwdignity_get_field_date_raw( get_the_ID() ) ); ?>"><?php echo esc_html( fwdignity_get_field_date( get_the_ID() ) ); ?></time>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34, '…' ) ); ?></p>
              <a class="training-text-link" href="<?php the_permalink(); ?>">閱讀成果整理 <span aria-hidden="true">→</span></a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <div class="training-empty">目前尚無可公開的農學堂成果。</div>
    <?php endif; ?>
  </section>

</main>

<?php get_footer(); ?>
