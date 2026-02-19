<?php
/*
 * 【首頁重點輪播 shortocode： [home_highlight_slider] 】
 *
 * 結構說明：
 *  - 第一張：嘉義民雄鄉農事氣象（從氣象署 F-D0047-029 讀取）
 *  - 第二張：最新活動（post_type = event，抓最新一篇）
 *  - 第三張：最新 Podcast（post_type = podcast，抓最新一篇）
 *  - 第四張：範例示範卡片（預設隱藏，只給你以後複製用）
 *
 * 操作手冊：
 *  1）使用方式：
 *     - 在任何頁面／文章／Elementor 短碼小工具中輸入：
 *         [home_highlight_slider]
 *
 *  2）修改氣象 API Key：
 *     - 找函數 get_minxiong_weather_status() 中的：
 *         $apiKey = 'CWA-4B0BCA13-7C84-4ED5-BA80-F090AD64048C';
 *       把字串改成你新的 key。
 *
 *  3）修改目標地點（例如改成太保市）：
 *     - 同一個函數裡，把：
 *         $targetLocation = '民雄鄉';
 *       改成：
 *         $targetLocation = '太保市';
 *
 *  4）修改氣象提示文案：
 *     - 在 get_minxiong_weather_status() 裡，搜尋「降雨預警」「高溫特報」「低溫特報」，
 *       直接改成你想要的文字，HTML <strong> 等標籤也可以一起改。
 *
 *  5）活動卡片資料來源：
 *     - 函數 get_latest_event() 裡：
 *         'post_type' => 'event'
 *       如要改成別的文章類型（例如 campaign），把 event 換成對應 slug。
 *     - 卡片顯示內容在 home_highlight_slider_shortcode() 中，
 *       搜尋「最新活動」那段 HTML，就能改標題文字和按鈕文字。
 *
 *  6）Podcast 卡片資料來源：
 *     - 函數 get_latest_podcast() 裡：
 *         'post_type' => 'podcast'
 *       如要改 slug，同樣改這裡。
 *     - 顯示內容在 home_highlight_slider_shortcode() 中，
 *       搜尋「最新 Podcast」那段 HTML 可改字樣。
 *
 *  7）輪播速度：
 *     - 在 home_highlight_slider_shortcode() 輸出的 HTML 中：
 *         <div class="home-highlight-slider" data-interval="7000">
 *       7000 代表 7 秒，可改成 5000（5 秒）等。
 *
 *  8）新增一張新卡片（自己定義）：
 *     - 在 home_highlight_slider_shortcode() 裡，照「示範卡片」那段程式複製一份，
 *       改掉裡面的 label / message / 連結後，再把最下面那段「示範卡片 slides[]」解除註解即可。
 *
 *  9）這份操作手冊就寫在本檔案開頭（你現在看的這一段多行註解），
 *     之後要查設定可以直接打開 functions.php / Snippet 看到。
 */

/* ==============================
 *  載入 Font Awesome 圖示
 * ============================== */
function mytheme_enqueue_fontawesome_for_slider() {
    wp_enqueue_style(
        'fontawesome-5',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        array(),
        '5.15.4'
    );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_fontawesome_for_slider' );

/* ==============================
 *  最新活動
 * ============================== */
function get_latest_event() {
    $query = new WP_Query(array(
        'post_type'      => 'event',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $data = array(
                'title' => get_the_title(),
                'date'  => get_post_meta( get_the_ID(), 'event_date', true ) ?: get_the_date( 'Y.m.d' ),
                'link'  => get_post_meta( get_the_ID(), 'signup_link', true ) ?: get_permalink(),
            );
            wp_reset_postdata();
            return $data;
        }
        wp_reset_postdata();
    }
    return false;
}

/* ==============================
 *  最新 Podcast
 * ============================== */
function get_latest_podcast() {
    $query = new WP_Query(array(
        'post_type'      => 'podcast',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $ep = get_post_meta( get_the_ID(), 'episode_number', true );
            $data = array(
                'title'  => get_the_title(),
                'ep_tag' => $ep ? "EP.{$ep}" : "最新上線",
                'link'   => get_permalink(),
            );
            wp_reset_postdata();
            return $data;
        }
        wp_reset_postdata();
    }
    return false;
}

/* ==============================
 *  嘉義民雄氣象（F-D0047-029 全嘉義縣 → 篩民雄鄉）
 * ============================== */
function get_minxiong_weather_status() {
    $apiKey  = 'CWA-4B0BCA13-7C84-4ED5-BA80-F090AD64048C';
    $apiUrl  = 'https://opendata.cwa.gov.tw/api/v1/rest/datastore/F-D0047-029'
             . '?Authorization=' . urlencode($apiKey);
    $result = array(
        'status_class' => 'maintenance-alert',
        'icon_class'   => 'fa-tools',
        'label'        => 'API暫不可用',
        'message'      => '⚠️ 無法連線氣象署，請稍後再試。',
    );
    $response = wp_remote_get($apiUrl, array(
        'timeout'   => 10,
        'sslverify' => false,
    ));
    if (is_wp_error($response)) {
        return $result;
    }
    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    if ($code !== 200 || empty($body)) {
        return $result;
    }
    $data = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $result;
    }
    if (
        !isset($data['records']['Locations'][0]['Location']) ||
        !is_array($data['records']['Locations'][0]['Location'])
    ) {
        return $result;
    }
    $locations = $data['records']['Locations'][0]['Location'];
    $targetLocation = '民雄鄉';
    $locationData   = null;
    foreach ($locations as $loc) {
        if (isset($loc['LocationName']) && $loc['LocationName'] === $targetLocation) {
            $locationData = $loc;
            break;
        }
    }
    if (!$locationData || !isset($locationData['WeatherElement'])) {
        return $result;
    }
    $elements = $locationData['WeatherElement'];
    $getVal = function ($elName, $valKey) use ($elements) {
        foreach ($elements as $el) {
            if (isset($el['ElementName']) && $el['ElementName'] === $elName) {
                if (!empty($el['Time'][0]['ElementValue'][0][$valKey])) {
                    return $el['Time'][0]['ElementValue'][0][$valKey];
                }
            }
        }
        return null;
    };
    $temp = $getVal('溫度', 'Temperature');
    $pop  = $getVal('3小時降雨機率', 'ProbabilityOfPrecipitation');
    $wx   = $getVal('天氣現象', 'Weather');
    if ($temp === null) {
        return $result;
    }
    $tempNum = floatval($temp);
    $popNum  = $pop !== null ? floatval($pop) : 0;
    $status_class = '';
    $icon_class   = 'fa-seedling';
    $label        = '今日天氣：' . ($wx ? $wx : '多雲時晴');
    $message      = '⛅ 氣候適宜 (' . $tempNum . '°C)：請持續注意田間作業安全，操作農機時請穿著防護裝備。';
    if ($popNum >= 70) {
        $status_class = 'rain-alert';
        $icon_class   = 'fa-umbrella';
        $label        = '降雨機率高';
        $message      = '🌧️ 降雨預警 (' . $popNum . '%)：民雄地區降雨機率高，請農友注意田間排水與防滑，避免雨中作業。';
    } elseif ($tempNum >= 30) {
        $status_class = 'hot-alert';
        $icon_class   = 'fa-temperature-high';
        $label        = '高溫熱危害預警';
        $message      = '⚠️ 高溫特報 (' . $tempNum . '°C)：請務必多喝水、每工作30分鐘至陰涼處休息，慎防熱衰竭！';
    } elseif ($tempNum <= 15) {
        $status_class = 'cold-alert';
        $icon_class   = 'fa-snowflake';
        $label        = '低溫寒害預警';
        $message      = '❄️ 低溫特報 (' . $tempNum . '°C)：請患有心血管疾病之農友注意保暖，採洋蔥式穿搭。';
    }
    $result['status_class'] = $status_class;
    $result['icon_class']   = $icon_class;
    $result['label']        = $label;
    $result['message']      = $message;
    return $result;
}

/* ==============================
 *  短碼：首頁三卡輪播 + 一張示範卡 [home_highlight_slider]
 * ============================== */
function home_highlight_slider_shortcode() {
    $weather        = get_minxiong_weather_status();
    $latest_event   = get_latest_event();
    $latest_podcast = get_latest_podcast();
    $slides = array();
    // 1. 氣象
    if ( $weather ) {
        $slides[] = array(
            'type'         => 'weather',
            'status_class' => $weather['status_class'],
            'icon_class'   => $weather['icon_class'],
            'label'        => $weather['label'],
            'message'      => $weather['message'],
            'btn_text'     => '務農防護指引',
            'btn_link'     => '/Safety/',
        );
    }
    // 2. 活動
    if ( $latest_event ) {
        $slides[] = array(
            'type'       => 'event',
            'icon_class' => 'fa-calendar-check',
            'label'      => '最新活動',
            'message'    => '<strong>' . esc_html( $latest_event['date'] ) . '</strong>　' . esc_html( $latest_event['title'] ),
            'btn_text'   => '立即報名',
            'btn_link'   => $latest_event['link'],
        );
    }
    // 3. Podcast
    if ( $latest_podcast ) {
        $slides[] = array(
            'type'       => 'podcast',
            'icon_class' => 'fa-podcast',
            'label'      => $latest_podcast['ep_tag'],
            'message'    => esc_html( $latest_podcast['title'] ),
            'btn_text'   => '前往收聽',
            'btn_link'   => $latest_podcast['link'],
        );
    }
    // 4. 示範卡片（預設不加入輪播；要用時把這段註解解除即可）
    /*
    $slides[] = array(
        'type'       => 'demo',
        'icon_class' => 'fa-info-circle',
        'label'      => '示範卡片（複製用）',
        'message'    => '這是一張示範卡片，未來要新增新卡片時可以複製這個結構，改圖示、文字和連結即可。',
        'btn_text'   => '示範按鈕',
        'btn_link'   => '#',
    );
    */
    if ( empty( $slides ) ) {
        return '';
    }
    ob_start();
    ?>
    <div class="home-highlight-outer">
      <div class="home-highlight-slider" data-interval="7000">
        <?php foreach ( $slides as $index => $s ) : ?>
          <div class="season-alert-bar <?php echo isset( $s['status_class'] ) ? esc_attr( $s['status_class'] ) : ''; ?><?php echo $index === 0 ? ' is-active' : ' is-hidden'; ?>">
            <div class="alert-icon-wrapper">
              <i class="fas <?php echo esc_attr( $s['icon_class'] ); ?>"></i>
            </div>
            <div class="alert-content-wrapper">
              <div class="alert-badge"><?php echo esc_html( $s['label'] ); ?></div>
              <div class="alert-text">
                <?php echo ( $s['type'] === 'weather' ) ? wp_kses_post( $s['message'] ) : $s['message']; ?>
              </div>
            </div>
            <a href="<?php echo esc_url( $s['btn_link'] ); ?>" class="alert-btn">
              <?php echo esc_html( $s['btn_text'] ); ?> <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="home-highlight-dots">
        <?php foreach ( $slides as $index => $s ) : ?>
          <span class="dot<?php echo $index === 0 ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>"></span>
        <?php endforeach; ?>
      </div>
    </div>
    <style>
    .home-highlight-outer { position: relative; }
    .home-highlight-slider { position: relative; min-height: 100px; }
    .home-highlight-slider .season-alert-bar {
        background: linear-gradient(135deg, #f1f8e9 0%, #ffffff 100%);
        border-left: 6px solid #66bb6a;
        border-radius: 16px;
        padding: 20px 30px;
        display: flex;
        align-items: center;
        gap: 25px;
        margin-bottom: 12px;
        font-family: "Noto Sans TC", sans-serif;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: opacity 0.4s ease, transform 0.4s ease;
        position: absolute;
        inset: 0;
    }
    .home-highlight-slider .season-alert-bar.is-hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateY(10px);
    }
    .home-highlight-slider .season-alert-bar.is-active {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
    }
    .alert-icon-wrapper {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #66bb6a;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        flex-shrink: 0;
        animation: pulse 3s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .alert-content-wrapper { flex-grow: 1; }
    .alert-badge {
        display: inline-block;
        font-size: 0.85rem;
        font-weight: 800;
        color: #2e7d32;
        background: rgba(46,125,50,0.1);
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    .alert-text { color: #444; font-size: 1.05rem; line-height: 1.5; }
    .alert-btn {
        text-decoration: none;
        color: #2e7d32;
        background: white;
        border: 1px solid #a5d6a7;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        white-space: nowrap;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        flex-shrink: 0;
    }
    .alert-btn:hover {
        background: #2e7d32;
        color: white;
        border-color: #2e7d32;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(46,125,50,0.2);
    }
    .alert-btn i { transition: transform 0.3s; }
    .alert-btn:hover i { transform: translateX(5px); }
    .season-alert-bar.rain-alert {
        background: linear-gradient(135deg, #e0f2f1 0%, #ffffff 100%);
        border-left-color: #00897b;
    }
    .season-alert-bar.hot-alert {
        background: linear-gradient(135deg, #fff3e0 0%, #ffffff 100%);
        border-left-color: #ff7043;
    }
    .season-alert-bar.cold-alert {
        background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);
        border-left-color: #42a5f5;
    }
    .season-alert-bar.maintenance-alert {
        background: #f5f5f5;
        border-left-color: #bdbdbd;
        filter: grayscale(100%);
    }
    .home-highlight-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
    }
    .home-highlight-dots .dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #c8e6c9;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .home-highlight-dots .dot.is-active {
        width: 20px;
        background: #2e7d32;
    }
    @media (max-width: 768px) {
        .home-highlight-slider .season-alert-bar {
            position: relative;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
        }
        .home-highlight-slider .season-alert-bar.is-hidden {
            display: none;
        }
        .alert-icon-wrapper {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
        }
        .alert-btn {
            width: 100%;
            justify-content: center;
            margin-top: 10px;
        }
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var slider = document.querySelector('.home-highlight-slider');
        if (!slider) return;
        var slides = slider.querySelectorAll('.season-alert-bar');
        if (!slides.length) return;
        var dotsContainer = document.querySelector('.home-highlight-dots');
        var dots = dotsContainer ? dotsContainer.querySelectorAll('.dot') : [];
        var current = 0;
        var interval = parseInt(slider.getAttribute('data-interval')) || 7000;
        function showSlide(index) {
            slides[current].classList.remove('is-active');
            slides[current].classList.add('is-hidden');
            current = index;
            slides[current].classList.remove('is-hidden');
            slides[current].classList.add('is-active');
            if (dots.length) {
                dots.forEach(function (d) { d.classList.remove('is-active'); });
                dots[current].classList.add('is-active');
            }
        }
        var timer = setInterval(function () {
            var next = (current + 1) % slides.length;
            showSlide(next);
        }, interval);
        if (dots.length) {
            dots.forEach(function (dot, idx) {
                dot.setAttribute('data-index', idx);
                dot.addEventListener('click', function () {
                    var index = parseInt(this.getAttribute('data-index'));
                    clearInterval(timer);
                    showSlide(index);
                    timer = setInterval(function () {
                        var next = (current + 1) % slides.length;
                        showSlide(next);
                    }, interval);
                });
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'home_highlight_slider', 'home_highlight_slider_shortcode' );
