<?php
/*
 * Plugin Name: 知識轉譯精選 - 影音動態櫥窗 (輪播+燈箱播放版)
 * Description: 自動抓取 Podcast 與 Video，支援首頁輪播展示，點擊封面可直接彈出視窗播放 YouTube。
 * Author: AI Assistant
 * Version: 3.0 (Carousel & Lightbox)
 *
 * 【操作說明書 / Maintenance Guide】
 * 1. 功能：
 * - 自動輪播文章 (預設顯示 3 欄，手機 1 欄)。
 * - 點擊「封面圖」或「播放圖示」：直接彈出視窗播放影片 (僅限 YouTube)。
 * - 點擊「標題」或「按鈕」：跳轉至文章內頁看詳細資訊。
 *
 * 2. Elementor 短碼用法：
 * - 基本用法：[knowledge_podcast_grid]
 * - 增加數量 (輪播建議多一點)：[knowledge_podcast_grid count="9"]
 * - 修改標題：[knowledge_podcast_grid title="農民影音專區"]
 *
 * 【參數說明】
 * - category ：分類 slug，逗號分隔（預設 podcast,video,youtube）
 * - count    ：抓取篇數（輪播建議至少 6 篇）
 * - title    ：區塊標題
 * - more_text：右上角連結文字
 * - more_url ：右上角連結網址
 */

add_shortcode('knowledge_podcast_grid', function($atts) {

    // 1. 設定區域
    $atts = shortcode_atts([
        'category' => 'podcast,video,youtube',
        'count'    => 6,
        'title'    => '影音專區',
        'more_text'=> '查看更多',
        'more_url' => '/media/'
    ], $atts);

    // 2. 建立查詢
    $args = [
        'category_name'  => $atts['category'],
        'posts_per_page' => $atts['count'],
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ];
    $query = new WP_Query($args);

    // 3. 組裝 HTML
    $output = '<div class="knowledge-podcast-wrapper" id="video-carousel-widget">';

    // 3.1 標題欄
    $output .= '
    <div class="section-head">
        <div class="head-title">'. esc_html($atts['title']) .'</div>
        <div class="head-controls">
            <button class="nav-arrow prev-slide"><i class="fas fa-chevron-left"></i></button>
            <button class="nav-arrow next-slide"><i class="fas fa-chevron-right"></i></button>
            <a href="'. esc_url($atts['more_url']) .'" class="head-link">'. esc_html($atts['more_text']) .' <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>';

    if ($query->have_posts()) {
        $output .= '<div class="carousel-viewport"><div class="carousel-track">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $title = get_the_title();
            $link  = get_permalink();
            $date  = get_the_date('Y.m.d');

            // --- 圖片與影片 ID 抓取邏輯 ---
            $img      = get_the_post_thumbnail_url($post_id, 'large');
            $video_id = '';

            // 掃描內容找 YouTube ID
            $content    = get_post_field('post_content', $post_id);
            $meta_data  = get_post_meta($post_id, '_elementor_data', true);
            $search_content = stripslashes($content . ' ' . (is_string($meta_data) ? $meta_data : json_encode($meta_data)));

            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $search_content, $matches)) {
                $video_id = $matches[1];
                if (!$img) {
                    $img = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
                }
            }

            // 保底圖片
            if (!$img) {
                $img = 'https://images.unsplash.com/photo-1478737270239-2f02b77ac6d5?q=80&w=600&auto=format&fit=crop';
            }

            $raw_excerpt = strip_tags(get_the_excerpt());
            $desc        = mb_strimwidth($raw_excerpt, 0, 60, '');
            $cats        = get_the_category();
            $cat_name    = !empty($cats) ? $cats[0]->name : 'Media';
            $cat_slug    = !empty($cats) ? $cats[0]->slug : '';

            // 判斷類型（影片 vs Podcast）
            if (strpos($cat_slug, 'video') !== false || strpos($cat_slug, 'youtube') !== false || !empty($video_id)) {
                $btn_text        = '前往觀看';
                $btn_icon        = 'fas fa-play-circle';
                $overlay_icon    = 'fas fa-play';
                $label_color     = '#c0392b';
                $lightbox_attr   = !empty($video_id) ? 'data-video-id="'.$video_id.'"' : '';
                $thumb_link_class = !empty($video_id) ? 'k-thumb-link lightbox-trigger' : 'k-thumb-link';
            } else {
                $btn_text        = '前往收聽';
                $btn_icon        = 'fas fa-headphones-alt';
                $overlay_icon    = 'fas fa-headphones';
                $label_color     = '#1976d2';
                $lightbox_attr   = '';
                $thumb_link_class = 'k-thumb-link';
            }

            $output .= '
            <div class="carousel-item">
                <div class="k-card">
                    <a href="'.$link.'" class="'.$thumb_link_class.'" '.$lightbox_attr.'>
                        <div class="k-thumb">
                            <span class="k-label" style="background: '.$label_color.';">'. esc_html($cat_name) .'</span>
                            <img src="'. esc_url($img) .'" alt="'. esc_attr($title) .'">
                            <div class="play-icon"><i class="'.$overlay_icon.'"></i></div>
                        </div>
                    </a>
                    <div class="k-info">
                        <h3 class="k-title"><a href="'.$link.'">'. $title .'</a></h3>
                        <p class="k-desc">'. $desc .'</p>
                        <div class="k-stats">
                            <span class="k-date"><i class="far fa-calendar-alt"></i> '. $date .'</span>
                            <a href="'.$link.'" class="k-listen-btn">
                                <i class="'.$btn_icon.'"></i> '.$btn_text.'
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
        }
        $output .= '</div></div>'; // 結束輪播容器
        wp_reset_postdata();
    } else {
        $output .= '<p style="color:#666; padding:20px;">目前尚無影音內容。</p>';
    }

    // 燈箱 HTML 結構（隱藏）
    $output .= '
    <div id="video-lightbox" class="lightbox-overlay">
        <div class="lightbox-content">
            <button class="lightbox-close"><i class="fas fa-times"></i></button>
            <div class="lightbox-video-container">
                <iframe id="lightbox-iframe" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </div>
    </div>';

    $output .= '</div>'; // 結束外層容器

    // =================================================================
    // 4. CSS 樣式
    // =================================================================
    $output .= '
    <style>
    .knowledge-podcast-wrapper {
        font-family: "Noto Sans TC", sans-serif; color: #333; width: 100%; margin-bottom: 40px; position: relative;
    }
    .section-head {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;
    }
    .head-title {
        font-size: 1.5rem; font-weight: 800; color: #2c5e2e;
        border-left: 5px solid #d4a017; padding-left: 15px; margin: 0;
    }
    .head-controls { display: flex; align-items: center; gap: 15px; }
    .head-link {
        font-size: 0.9rem; color: #555; font-weight: 600;
        display: flex; align-items: center; gap: 5px; text-decoration: none;
    }
    .head-link:hover { color: #2c5e2e; }
    .nav-arrow {
        background: #fff; border: 1px solid #ddd; width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; transition: 0.3s;
    }
    .nav-arrow:hover { background: #2c5e2e; color: #fff; border-color: #2c5e2e; }
    .carousel-viewport { overflow: hidden; width: 100%; padding: 10px 5px 20px 5px; }
    .carousel-track { display: flex; transition: transform 0.5s ease-in-out; gap: 20px; }
    .carousel-item {
        min-width: calc(33.333% - 14px);
        flex-shrink: 0;
    }
    .k-card {
        background: #ffffff; border-radius: 12px; overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eee;
        transition: all 0.3s ease; display: flex; flex-direction: column; height: 100%;
    }
    .k-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(44, 94, 46, 0.15); border-color: #2c5e2e;
    }
    .k-thumb { height: 180px; position: relative; overflow: hidden; background: #000; }
    .k-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; opacity: 0.9; }
    .k-card:hover .k-thumb img { transform: scale(1.1); opacity: 1; }
    .k-label {
        position: absolute; top: 10px; left: 10px;
        color: white; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; z-index: 2;
    }
    .play-icon {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        width: 50px; height: 50px; background: rgba(0, 0, 0, 0.6);
        color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 20px; opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        padding-left: 2px; z-index: 3;
    }
    .k-card:hover .play-icon { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    .k-info { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .k-title { font-size: 1.1rem; font-weight: 700; margin: 0 0 10px 0; line-height: 1.4; height: 3em; overflow: hidden; }
    .k-title a { text-decoration: none; color: #222; transition: 0.2s; }
    .k-title a:hover { color: #2c5e2e; }
    .k-desc { font-size: 0.9rem; color: #666; margin: 0 0 15px 0; line-height: 1.5; flex-grow: 1; }
    .k-stats {
        margin-top: auto; padding-top: 10px; border-top: 1px solid #f0f0f0;
        display: flex; justify-content: space-between; align-items: center;
        font-size: 0.85rem; color: #888;
    }
    .k-listen-btn {
        background: #5d6d3d; color: #ffffff !important;
        font-weight: 700; text-decoration: none;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 8px 15px; border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(93, 109, 61, 0.2);
    }
    .k-listen-btn:hover {
        background: #4a5a31; transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(93, 109, 61, 0.3);
    }
    /* 燈箱樣式 */
    .lightbox-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.9); z-index: 9999;
        display: none; justify-content: center; align-items: center;
        opacity: 0; transition: opacity 0.3s ease;
    }
    .lightbox-overlay.active { display: flex; opacity: 1; }
    .lightbox-content {
        position: relative; width: 90%; max-width: 800px;
        background: black; border-radius: 8px; overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    .lightbox-video-container { position: relative; padding-bottom: 56.25%; height: 0; }
    .lightbox-video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
    .lightbox-close {
        position: absolute; top: -40px; right: 0;
        background: none; border: none; color: white; font-size: 30px; cursor: pointer;
    }
    @media (max-width: 992px) {
        .carousel-item { min-width: calc(50% - 10px); }
    }
    @media (max-width: 768px) {
        .carousel-item { min-width: 100%; }
        .k-thumb { height: 200px; }
    }
    </style>';

    // =================================================================
    // 5. JavaScript 互動邏輯（輪播 + 燈箱）
    // =================================================================
    $output .= "
    <script>
    (function() {
        const widget = document.getElementById('video-carousel-widget');
        if(!widget) return;

        // --- 輪播邏輯 ---
        const track = widget.querySelector('.carousel-track');
        const items = widget.querySelectorAll('.carousel-item');
        const nextBtn = widget.querySelector('.next-slide');
        const prevBtn = widget.querySelector('.prev-slide');
        let currentIndex = 0;
        const gap = 20;

        const getItemsPerSlide = () => window.innerWidth > 992 ? 3 : (window.innerWidth > 768 ? 2 : 1);

        const updateCarousel = () => {
            const itemsPerSlide = getItemsPerSlide();
            const maxIndex = items.length - itemsPerSlide;
            if(currentIndex > maxIndex) currentIndex = 0;
            if(currentIndex < 0) currentIndex = maxIndex;
            const slideWidth = items[0].getBoundingClientRect().width;
            const moveX = (slideWidth + gap) * currentIndex;
            track.style.transform = \`translateX(-\${moveX}px)\`;
        };

        let autoTimer = setInterval(() => {
            const itemsPerSlide = getItemsPerSlide();
            if(currentIndex < items.length - itemsPerSlide) currentIndex++;
            else currentIndex = 0;
            updateCarousel();
        }, 5000);

        const resetTimer = () => {
            clearInterval(autoTimer);
            autoTimer = setInterval(() => {
                const itemsPerSlide = getItemsPerSlide();
                if(currentIndex < items.length - itemsPerSlide) currentIndex++;
                else currentIndex = 0;
                updateCarousel();
            }, 5000);
        };

        if(nextBtn) nextBtn.addEventListener('click', () => {
            const itemsPerSlide = getItemsPerSlide();
            if(currentIndex < items.length - itemsPerSlide) currentIndex++;
            else currentIndex = 0;
            updateCarousel();
            resetTimer();
        });
        if(prevBtn) prevBtn.addEventListener('click', () => {
            const itemsPerSlide = getItemsPerSlide();
            if(currentIndex > 0) currentIndex--;
            else currentIndex = items.length - itemsPerSlide;
            updateCarousel();
            resetTimer();
        });
        window.addEventListener('resize', updateCarousel);

        // --- 燈箱邏輯 ---
        const lightbox = document.getElementById('video-lightbox');
        const iframe   = document.getElementById('lightbox-iframe');
        const closeBtn = document.querySelector('.lightbox-close');
        const triggers = widget.querySelectorAll('.lightbox-trigger');

        triggers.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const videoId = btn.dataset.videoId;
                if(videoId) {
                    iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
                    lightbox.classList.add('active');
                }
            });
        });

        const closeLightbox = () => {
            lightbox.classList.remove('active');
            iframe.src = '';
        };
        if(closeBtn) closeBtn.addEventListener('click', closeLightbox);
        if(lightbox) lightbox.addEventListener('click', (e) => {
            if(e.target === lightbox) closeLightbox();
        });
    })();
    </script>
    ";

    return $output;
});
