<?php
/**
 * fwdignity theme — front-page.php
 * 首頁 B v0.6 WordPress staged template（2026-07-11）
 *
 * 視覺基準：2026-07-11 v0.6 密度與輪播確認稿。
 * 內容底線：不把靜態稿文案硬塞進 PHP；文章、頁面摘要與連結由 WordPress 動態來源產生。
 */
?>
<?php get_header(); ?>

<?php
$home_tagline = fwdignity_get( 'tagline', get_bloginfo( 'name' ) );
$hero_title   = preg_replace( '/^尊嚴農業[：:]\s*/u', '', $home_tagline );

$about_pages = get_posts( array(
    'post_type'      => 'page',
    'name'           => 'about',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'no_found_rows'  => true,
) );
$about_page = ! empty( $about_pages ) ? $about_pages[0] : null;

$make_summary = static function( $post_obj, $words = 42 ) {
    if ( ! $post_obj instanceof WP_Post ) {
        return '';
    }

    $raw = has_excerpt( $post_obj ) ? $post_obj->post_excerpt : $post_obj->post_content;
    $raw = strip_shortcodes( $raw );
    $raw = wp_strip_all_tags( $raw );

    return wp_trim_words( $raw, $words, '...' );
};

$collect_post_item = static function( $post_id ) {
    $cats      = get_the_category( $post_id );
    $cat_names = array();

    if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
        foreach ( array_slice( $cats, 0, 3 ) as $cat ) {
            $cat_names[] = $cat->name;
        }
    }

    $thumb_id  = get_post_thumbnail_id( $post_id );
    $thumb_alt = $thumb_id ? get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) : '';

    return array(
        'id'        => $post_id,
        'title'     => get_the_title( $post_id ),
        'link'      => get_permalink( $post_id ),
        'date'      => get_the_date( 'Y.m.d', $post_id ),
        'date_attr' => get_the_date( 'Y-m-d', $post_id ),
        'excerpt'   => wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post_id ) ), 40, '...' ),
        'thumb'     => get_the_post_thumbnail_url( $post_id, 'medium_large' ),
        'thumb_alt' => $thumb_alt ? $thumb_alt : get_the_title( $post_id ),
        'cats'      => $cat_names,
    );
};

$latest_query = new WP_Query( array(
    'post_type'           => 'post',
    'posts_per_page'      => 5,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => false,
    'no_found_rows'       => true,
) );

$latest_posts = array();
if ( $latest_query->have_posts() ) {
    while ( $latest_query->have_posts() ) {
        $latest_query->the_post();
        $latest_posts[] = $collect_post_item( get_the_ID() );
    }
    wp_reset_postdata();
}

// 2026-07-17 W3-P（健檢 F18）：影音選稿改分類查詢，不再以標題字串猜測（'ep' 誤中一般英文字）。
$media_candidates = get_posts( array(
    'post_type'           => 'post',
    'posts_per_page'      => 12,
    'post_status'         => 'publish',
    'category_name'       => 'podcast,video,youtube,media',
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
) );

$media_posts = array();
foreach ( $media_candidates as $candidate ) {
    $candidate_item             = $collect_post_item( $candidate->ID );
    $candidate_item['is_video'] = has_category( array( 'youtube', 'video' ), $candidate->ID );
    $media_posts[]              = $candidate_item;
}

if ( empty( $media_posts ) ) {
    $media_posts = array_slice( $latest_posts, 0, 4 );
}

$knowledge_topics = array(
    '身體與健康',
    '農藥與防護',
    '農機與工作環境',
    '經濟、保險與保障',
    '學習與支持',
    '工具與正式資源',
);

$about_summary = $about_page ? wp_strip_all_tags( strip_shortcodes( $about_page->post_content ) ) : get_bloginfo( 'description' );
$about_summary = preg_replace( '/^尊嚴農業[：:]\s*共構雲嘉農業工作者職業健康的社會安全網[。\s]*/u', '', trim( $about_summary ) );
if ( preg_match( '/^.+?。/u', $about_summary, $about_intro_match ) ) {
    $about_summary = $about_intro_match[0];
} else {
    $about_summary = wp_trim_words( $about_summary, 56, '...' );
}
$hero_image        = FWDIGNITY_THEME_URI . '/assets/images/homepage-field-equipment-20260702-1600w.jpg';
$hero_image_srcset = FWDIGNITY_THEME_URI . '/assets/images/homepage-field-equipment-20260702-1600w.jpg 1600w, '
    . FWDIGNITY_THEME_URI . '/assets/images/homepage-field-equipment-20260702-2400w.jpg 2400w';
$hero_alt          = '田間設備與作物近景';
$weather       = function_exists( 'get_minxiong_weather_status' )
    ? get_minxiong_weather_status()
    : array();
$weather       = wp_parse_args( $weather, array(
    'available'    => false,
    'status_class' => 'maintenance-alert',
    'label'        => '氣象資料更新中',
    'message'      => '民雄鄉氣象資料暫時無法取得，請稍後再試。',
    'temperature'  => null,
    'rain'         => null,
    'weather'      => '',
    'updated'      => '',
) );
$weather_is_alert = in_array( $weather['status_class'], array( 'rain-alert', 'hot-alert', 'cold-alert' ), true );
$weather_message  = preg_replace( '/^\s*⚠(?:\x{FE0F})?\s*/u', '', (string) $weather['message'] );

$news_fallback_image = fwdignity_get_logo_url();
$latest_posts_payload = array_map(
    static function( $post_item ) use ( $news_fallback_image ) {
        $category = ! empty( $post_item['cats'][0] ) ? $post_item['cats'][0] : '最新消息';

        return array(
            'meta'    => $category . '｜' . $post_item['date'],
            'title'   => $post_item['title'],
            'excerpt' => $post_item['excerpt'],
            'image'   => $post_item['thumb'] ? $post_item['thumb'] : $news_fallback_image,
            'alt'     => $post_item['thumb'] ? $post_item['thumb_alt'] : '尊嚴農業',
            'link'    => $post_item['link'],
            'action'  => '閱讀消息',
        );
    },
    $latest_posts
);

$framework_intro = $about_page ? $make_summary( $about_page, 76 ) : '';
$framework_intro = preg_replace( '/^尊嚴農業[：:]\s*共構雲嘉農業工作者職業健康的社會安全網[。\s]*/u', '', trim( $framework_intro ) );
$framework_cards = array(
    array(
        'no'    => '01',
        'title' => '基礎調查',
        'desc'  => '以基礎調查掌握農業勞動現場的傷病樣態。',
    ),
    array(
        'no'    => '02',
        'title' => '政策研究',
        'desc'  => '進行政策研究與跨國比較，檢視現行社會保障不足。',
    ),
    array(
        'no'    => '03',
        'title' => '健康教育',
        'desc'  => '發展具在地適切性的健康促進教育模組，提升農民自我保護能力。',
    ),
    array(
        'no'    => '04',
        'title' => '社會倡議',
        'desc'  => '透過社會倡議，凝聚農會、社區組織與學術單位共同行動。',
    ),
);
?>

<main class="fw-home-v04 fw-home-v06" id="main" role="main">
  <section class="fw-home-v06-weather <?php echo esc_attr( $weather['status_class'] ); ?>" aria-labelledby="home-weather-title">
    <div class="fw-home-v04-wrap fw-home-v06-weather-layout">
      <div class="fw-home-v06-weather-place">
        <p class="fw-home-v06-weather-title" id="home-weather-title">今天民雄鄉天氣預報</p>
      </div>
      <div class="fw-home-v06-weather-status" role="status">
        <span aria-hidden="true"></span>
        <div>
          <strong><?php echo esc_html( $weather['label'] ); ?></strong>
          <small><?php if ( $weather_is_alert ) : ?>⚠️ <?php endif; ?><?php echo esc_html( $weather_message ); ?></small>
        </div>
      </div>
    </div>
  </section>

  <section class="fw-home-v04-hero" aria-labelledby="fw-home-title">
    <img class="fw-home-v04-hero-image" src="<?php echo esc_url( $hero_image ); ?>" srcset="<?php echo esc_attr( $hero_image_srcset ); ?>" sizes="(min-width: 1440px) 1320px, calc(100vw - 56px)" width="1600" height="1204" fetchpriority="high" decoding="async" alt="<?php echo esc_attr( $hero_alt ); ?>">
    <div class="fw-home-v04-hero-shade" aria-hidden="true"></div>
    <div class="fw-home-v04-wrap fw-home-v04-hero-content">
      <p class="fw-home-v04-eyebrow">尊嚴農業</p>
      <h1 id="fw-home-title"><?php echo esc_html( $hero_title ); ?></h1>
      <?php if ( $about_summary ) : ?>
        <p><?php echo esc_html( $about_summary ); ?></p>
      <?php endif; ?>
      <div class="fw-home-v04-actions" aria-label="首頁主要行動">
        <a class="fw-home-v04-button primary" href="#home-latest">看最新消息</a>
        <a class="fw-home-v04-button secondary" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">認識計畫</a>
      </div>
    </div>
  </section>

  <section class="fw-home-v04-section fw-home-v06-news-section" id="home-latest" aria-labelledby="home-latest-title">
    <div class="fw-home-v04-wrap fw-home-v04-section-head split">
      <div>
        <p class="fw-home-v04-eyebrow">News & Updates</p>
        <h2 id="home-latest-title">最新消息</h2>
      </div>
      <a class="fw-home-v04-text-link" href="<?php echo esc_url( home_url( '/thenews/' ) ); ?>">看全部最新消息</a>
    </div>

    <?php if ( ! empty( $latest_posts_payload ) ) : ?>
    <div class="fw-home-v04-wrap fw-home-v06-editorial-news" data-home-news aria-label="最新消息輪播">
      <?php $featured = $latest_posts[0]; ?>
      <article class="fw-home-v06-feature" data-home-news-feature aria-live="polite">
        <img data-home-news-image src="<?php echo esc_url( $latest_posts_payload[0]['image'] ); ?>" alt="<?php echo esc_attr( $latest_posts_payload[0]['alt'] ); ?>">
        <div class="fw-home-v06-feature-copy">
          <p class="fw-home-v04-meta" data-home-news-meta><?php echo esc_html( $latest_posts_payload[0]['meta'] ); ?></p>
          <h3 data-home-news-title><?php echo esc_html( $latest_posts_payload[0]['title'] ); ?></h3>
          <p data-home-news-excerpt><?php echo esc_html( $latest_posts_payload[0]['excerpt'] ); ?></p>
          <a class="fw-home-v06-news-read" data-home-news-link href="<?php echo esc_url( $latest_posts_payload[0]['link'] ); ?>">閱讀消息 <span aria-hidden="true">→</span></a>
        </div>
      </article>

      <aside class="fw-home-v06-news-picker-panel" aria-label="其他最新消息">
        <div class="fw-home-v06-news-picker-head">
          <p><span data-home-news-current>01</span> / <?php echo esc_html( str_pad( (string) count( $latest_posts_payload ), 2, '0', STR_PAD_LEFT ) ); ?></p>
          <div class="fw-home-v06-compact-controls">
            <button type="button" data-home-news-toggle aria-pressed="false" aria-label="暫停自動輪播" title="暫停自動輪播"><span data-home-news-toggle-icon aria-hidden="true">Ⅱ</span></button>
            <button type="button" data-home-news-prev aria-label="上一則消息" title="上一則消息"><span aria-hidden="true">←</span></button>
            <button type="button" data-home-news-next aria-label="下一則消息" title="下一則消息"><span aria-hidden="true">→</span></button>
          </div>
        </div>
        <div class="fw-home-v06-news-picker" data-home-news-picker>
          <?php foreach ( array_slice( $latest_posts_payload, 1 ) as $index => $post_item ) : ?>
          <button class="fw-home-v06-news-picker-item" type="button" data-home-news-index="<?php echo esc_attr( (string) ( $index + 1 ) ); ?>">
            <img src="<?php echo esc_url( $post_item['image'] ); ?>" alt="">
            <span><span><?php echo esc_html( $post_item['meta'] ); ?></span><strong><?php echo esc_html( $post_item['title'] ); ?></strong></span>
          </button>
          <?php endforeach; ?>
        </div>
      </aside>
    </div>
    <script type="application/json" id="fw-home-news-data"><?php echo wp_json_encode( $latest_posts_payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
    <?php endif; ?>
  </section>

  <?php if ( ! empty( $media_posts ) ) : ?>
  <section class="fw-home-v04-section fw-home-v04-audio fw-home-v06-audio" id="home-media" aria-labelledby="home-media-title">
    <div class="fw-home-v04-wrap fw-home-v04-section-head split">
      <div>
        <p class="fw-home-v04-eyebrow">Audio & Video</p>
        <h2 id="home-media-title">語音／影音</h2>
      </div>
      <div class="fw-home-v06-media-head-actions">
        <div class="fw-home-v06-media-controls" aria-label="聲音與影像輪播控制">
          <button type="button" data-home-media-toggle aria-pressed="false" aria-label="暫停聲音與影像自動輪播" title="暫停聲音與影像自動輪播"><span aria-hidden="true">Ⅱ</span></button>
          <button type="button" data-home-media-prev aria-label="上一組聲音與影像" title="上一組聲音與影像"><span aria-hidden="true">←</span></button>
          <button type="button" data-home-media-next aria-label="下一組聲音與影像" title="下一組聲音與影像"><span aria-hidden="true">→</span></button>
        </div>
        <a class="fw-home-v06-audio-all" href="<?php echo esc_url( home_url( '/podcast/' ) ); ?>">看全部內容</a>
      </div>
    </div>

    <div class="fw-home-v04-wrap fw-home-v06-media-carousel" data-home-media>
      <div class="fw-home-v06-media-viewport" data-home-media-viewport tabindex="0" aria-label="聲音與影像，可左右滑動">
        <div class="fw-home-v06-media-track">
      <?php foreach ( $media_posts as $post_item ) : ?>
        <?php
        $media_category = ! empty( $post_item['cats'][0] ) ? $post_item['cats'][0] : 'Podcast';
        $media_is_video = ! empty( $post_item['is_video'] );
        ?>
        <article class="fw-home-v06-media-card">
          <a class="fw-home-v06-media-visual <?php echo $post_item['thumb'] ? '' : 'no-image'; ?>" href="<?php echo esc_url( $post_item['link'] ); ?>" tabindex="-1" aria-hidden="true">
            <?php if ( $post_item['thumb'] ) : ?><img src="<?php echo esc_url( $post_item['thumb'] ); ?>" alt=""><?php endif; ?>
            <span><?php echo esc_html( $media_category ); ?></span>
          </a>
          <div class="fw-home-v06-media-body">
            <p class="fw-home-v06-media-meta"><?php echo esc_html( $media_category ); ?>｜<?php echo esc_html( $post_item['date'] ); ?></p>
            <h3><a href="<?php echo esc_url( $post_item['link'] ); ?>"><?php echo esc_html( $post_item['title'] ); ?></a></h3>
            <?php if ( $post_item['excerpt'] ) : ?><p><?php echo esc_html( $post_item['excerpt'] ); ?></p><?php endif; ?>
            <a class="fw-home-v06-media-action" href="<?php echo esc_url( $post_item['link'] ); ?>"><?php echo esc_html( $media_is_video ? '觀看影片' : '立即聆聽' ); ?> <span aria-hidden="true">→</span></a>
          </div>
        </article>
      <?php endforeach; ?>
        </div>
      </div>
      <div class="fw-home-v06-media-progress" aria-hidden="true"><span data-home-media-progress></span></div>
    </div>
  </section>
  <?php endif; ?>

  <section class="fw-home-v04-section fw-home-v04-resources fw-home-v07-guide" id="home-resources" aria-labelledby="home-resources-title">
    <div class="fw-home-v04-wrap fw-home-v04-section-head split">
      <div>
        <p class="fw-home-v04-eyebrow">Knowledge &amp; Resources</p>
        <h2 id="home-resources-title">田間知識與資源</h2>
      </div>
      <a class="fw-home-v04-text-link fw-home-v07-guide-link" href="<?php echo esc_url( home_url( '/farming-guide/' ) ); ?>">查看全部資源 <span aria-hidden="true">→</span></a>
    </div>

    <ol class="fw-home-v04-wrap fw-home-v07-guide-index" aria-label="田間知識與資源主題索引">
      <?php foreach ( $knowledge_topics as $index => $task ) : ?>
        <li class="fw-home-v07-guide-task">
          <span aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
          <strong><?php echo esc_html( $task ); ?></strong>
        </li>
      <?php endforeach; ?>
    </ol>
  </section>

  <section class="fw-home-v04-section fw-home-v04-framework" id="home-framework" aria-labelledby="home-framework-title">
    <div class="fw-home-v04-wrap fw-home-v04-framework-layout">
      <div class="fw-home-v04-section-head">
        <p class="fw-home-v04-eyebrow">Project Framework</p>
        <h2 id="home-framework-title">計畫如何推進</h2>
        <?php if ( $framework_intro ) : ?><p><?php echo esc_html( $framework_intro ); ?></p><?php endif; ?>
      </div>

      <div class="fw-home-v04-framework-grid" aria-label="計畫四大面向">
        <?php foreach ( $framework_cards as $card ) : ?>
        <article class="fw-home-v04-framework-card">
          <span><?php echo esc_html( $card['no'] ); ?></span>
          <h3><?php echo esc_html( $card['title'] ); ?></h3>
          <p><?php echo esc_html( $card['desc'] ); ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
