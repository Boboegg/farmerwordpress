<?php
/**
 * Template Name: 田間活動
 * Slug: field-events
 * 未來公開活動介面。暫不加入主選單。
 */

get_header();

$event_query = new WP_Query(
    array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 12,
        'meta_key'            => '_fwdignity_event_start',
        'orderby'             => 'meta_value',
        'order'               => 'ASC',
        'ignore_sticky_posts' => true,
        'meta_query'          => array(
            array( 'key' => '_fwdignity_event_public', 'value' => '1' ),
        ),
    )
);

?>

<main class="field-events-page" id="main">
  <header class="field-events-hero">
    <div>
      <p class="field-events-eyebrow">Events</p>
      <h1><?php the_title(); ?></h1>
      <p>目前計畫活動以直接邀約為主，沒有對外公開的報名資訊。這個頁面已保留完整活動日期、地點、狀態與報名連結介面；未來有公開活動時，可直接啟用。</p>
    </div>
  </header>

  <section class="field-events-section" aria-labelledby="field-events-current-title">
    <div class="field-events-section-head"><div><p class="field-events-eyebrow">Public Events</p><h2 id="field-events-current-title">公開活動</h2></div></div>
    <?php if ( $event_query->have_posts() ) : ?>
      <div class="field-event-grid">
        <?php while ( $event_query->have_posts() ) : $event_query->the_post();
            $status       = fwdignity_get_event_status( get_the_ID() );
            $start        = get_post_meta( get_the_ID(), '_fwdignity_event_start', true );
            $end          = get_post_meta( get_the_ID(), '_fwdignity_event_end', true );
            $location     = fwdignity_get_field_location( get_the_ID() );
            $registration = esc_url( get_post_meta( get_the_ID(), '_fwdignity_event_registration_url', true ) );
            ?>
          <article class="field-event-card">
            <div class="field-event-status field-event-status--<?php echo esc_attr( $status['slug'] ); ?>"><?php echo esc_html( $status['label'] ); ?></div>
            <p class="field-event-date"><?php echo esc_html( $start ? wp_date( 'Y.m.d H:i', strtotime( $start ) ) : get_the_date( 'Y.m.d' ) ); ?><?php if ( $end ) : ?> 至 <?php echo esc_html( wp_date( 'm.d H:i', strtotime( $end ) ) ); ?><?php endif; ?></p>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php if ( $location ) : ?><p class="field-event-location"><?php echo esc_html( $location ); ?></p><?php endif; ?>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '…' ) ); ?></p>
            <div class="field-event-actions"><a href="<?php the_permalink(); ?>">查看資訊</a><?php if ( $registration && ! in_array( $status['slug'], array( 'ended', 'cancelled', 'full' ), true ) ) : ?><a class="is-primary" href="<?php echo esc_url( $registration ); ?>" target="_blank" rel="noopener">前往報名</a><?php endif; ?></div>
          </article>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <div class="field-events-empty"><strong>目前沒有公開活動</strong><p>活動採直接邀約；完成後的內容會整理至農學堂與田野紀錄。</p></div>
    <?php endif; ?>
  </section>

</main>

<?php get_footer(); ?>
