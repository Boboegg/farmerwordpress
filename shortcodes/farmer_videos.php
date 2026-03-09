<?php
/*
 * Shortcode: [farmer_videos]
 * 自動從 YouTube 頻道 RSS 抓取最新影片，以卡片網格呈現。
 *
 * 【參數】
 *   channels : YouTube 頻道 ID（UCxxx），逗號分隔，可放多個頻道
 *   count    : 顯示筆數（預設 6）
 *   cache_hr : 快取小時數（預設 12）
 *
 * 【範例】
 *   [farmer_videos]
 *   [farmer_videos count="4"]
 *   [farmer_videos channels="UCRgk3ryTcY8Wcvvv_ulZgmA,UC_OTHER_ID" count="8"]
 *
 * 【預設頻道】
 *   - UCRgk3ryTcY8Wcvvv_ulZgmA : NIOSH U.S. Agricultural Safety and Health Centers
 *
 * 【資料來源】
 *   YouTube RSS：https://www.youtube.com/feeds/videos.xml?channel_id=XXX
 */

add_shortcode('farmer_videos', function ($atts) {

    $atts = shortcode_atts([
        'channels' => 'UCRgk3ryTcY8Wcvvv_ulZgmA',
        'count'    => 6,
        'cache_hr' => 12,
    ], $atts);

    $channel_ids = array_filter(array_map('trim', explode(',', $atts['channels'])));
    $count       = max(1, intval($atts['count']));
    $cache_hr    = max(1, intval($atts['cache_hr']));

    // ── 快取 ──
    $cache_key = 'farmer_videos_' . md5(serialize($atts));
    $cached    = get_transient($cache_key);
    if ($cached !== false) {
        return $cached;
    }

    // ── 抓取 RSS（合併多頻道）──
    include_once ABSPATH . WPINC . '/feed.php';
    $all_videos = [];

    foreach ($channel_ids as $ch_id) {
        $feed_url = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . urlencode($ch_id);
        $rss = fetch_feed($feed_url);
        if (is_wp_error($rss)) continue;

        $items = $rss->get_items(0, $rss->get_item_quantity(20));
        foreach ($items as $item) {
            $video_id = '';
            // YouTube RSS 的 <yt:videoId> 會被 SimplePie 解析
            // 從 link 解析 video ID
            $link = $item->get_link();
            if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $link, $m)) {
                $video_id = $m[1];
            }
            if (!$video_id) continue;

            $all_videos[] = [
                'title'     => $item->get_title(),
                'video_id'  => $video_id,
                'link'      => $link,
                'date'      => $item->get_date('U'),
                'desc'      => wp_strip_all_tags($item->get_description()),
                'thumbnail' => "https://img.youtube.com/vi/{$video_id}/mqdefault.jpg",
            ];
        }
    }

    // 依日期排序（最新優先）
    usort($all_videos, function ($a, $b) { return $b['date'] - $a['date']; });
    $all_videos = array_slice($all_videos, 0, $count);

    if (empty($all_videos)) {
        return '<p class="edu-fetch-error">⚠️ 目前無法載入影片資料，請稍後再試。</p>';
    }

    // ── 組 HTML ──
    $html  = '<div class="edu-video-grid">';

    foreach ($all_videos as $v) {
        $esc_title = esc_attr($v['title']);
        $esc_link  = esc_url($v['link']);
        $esc_thumb = esc_url($v['thumbnail']);
        $esc_vid   = esc_attr($v['video_id']);
        $title_h   = esc_html($v['title']);
        $desc_h    = esc_html(mb_strimwidth($v['desc'], 0, 120, '…'));
        $date_h    = esc_html(date_i18n('Y/m/d', $v['date']));

        $html .= <<<CARD
<div class="edu-video-card" data-video-id="{$esc_vid}">
  <div class="edu-video-thumb">
    <img src="{$esc_thumb}" alt="{$esc_title}" loading="lazy">
    <div class="edu-video-play"><i class="fas fa-play"></i></div>
  </div>
  <div class="edu-video-info">
    <div class="edu-video-tags">
      <span class="edu-tag-topic">{$date_h}</span>
    </div>
    <h3 class="edu-video-title">{$title_h}</h3>
    <p class="edu-video-desc">{$desc_h}</p>
    <a href="{$esc_link}" class="edu-video-link" target="_blank" rel="noopener"><i class="fas fa-play-circle"></i> 觀看影片</a>
  </div>
</div>
CARD;
    }

    $html .= '</div>';

    // 燈箱播放 JS（點擊卡片直接播放）
    $html .= <<<'JS'
<div id="edu-video-lightbox" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.85);z-index:99999;align-items:center;justify-content:center;cursor:pointer;" onclick="this.style.display='none';this.innerHTML='';">
</div>
<script>
(function(){
  document.querySelectorAll('.edu-video-card[data-video-id]').forEach(function(card){
    card.querySelector('.edu-video-thumb').style.cursor='pointer';
    card.querySelector('.edu-video-thumb').addEventListener('click',function(e){
      e.preventDefault();
      var vid=card.getAttribute('data-video-id');
      var lb=document.getElementById('edu-video-lightbox');
      lb.innerHTML='<div style="position:relative;width:90%;max-width:900px;aspect-ratio:16/9;" onclick="event.stopPropagation()"><iframe src="https://www.youtube-nocookie.com/embed/'+vid+'?autoplay=1&rel=0" style="width:100%;height:100%;border:none;border-radius:12px;" allow="autoplay;encrypted-media" allowfullscreen></iframe><button onclick="this.parentElement.parentElement.style.display=\'none\';this.parentElement.parentElement.innerHTML=\'\';" style="position:absolute;top:-12px;right:-12px;background:#fff;border:none;border-radius:50%;width:32px;height:32px;font-size:18px;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.3);">&times;</button></div>';
      lb.style.display='flex';
    });
  });
})();
</script>
JS;

    // NIOSH 頻道提示
    $html .= '<div class="edu-video-more">';
    $html .= '<i class="fas fa-circle-info"></i>';
    $html .= '<span>以上影片自動抓取自 NIOSH 美國農業安全中心 YouTube 頻道，涵蓋農藥、感染、農機、穀倉、心理健康等主題。</span>';
    $html .= '<a href="https://www.youtube.com/@USagCenters" target="_blank" rel="noopener">前往頻道 <i class="fas fa-arrow-right"></i></a>';
    $html .= '</div>';

    // ── 存入快取 ──
    set_transient($cache_key, $html, $cache_hr * HOUR_IN_SECONDS);

    return $html;
});
