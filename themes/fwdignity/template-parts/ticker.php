<?php
/**
 * Template part: 走馬燈 + 嘉義民雄氣象
 * 用法:<?php get_template_part( 'template-parts/ticker' ); ?>
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$fw_weather = null;
if ( function_exists( 'get_minxiong_weather_status' ) ) {
    $fw_weather = get_minxiong_weather_status();
}
$fw_w_icon  = $fw_weather && isset( $fw_weather['icon_class'] ) ? $fw_weather['icon_class'] : 'fa-cloud-sun';
$fw_w_msg   = $fw_weather && isset( $fw_weather['message'] ) ? wp_strip_all_tags( $fw_weather['message'] ) : '即時氣象連線中 · 為您分析農事建議';
?>
<div class="fw-ticker-row">
  <div class="fw-ticker-words">
    <div class="fw-ticker-track">
      <span>看見 <span class="en">SEE</span></span>
      <span>論證 <span class="en">EVIDENCE</span></span>
      <span>改變 <span class="en">CHANGE</span></span>
      <span>尊嚴 <span class="en">DIGNITY</span></span>
      <span>農民是工作者</span>
      <span>不是補助對象</span>
      <span>看見 <span class="en">SEE</span></span>
      <span>論證 <span class="en">EVIDENCE</span></span>
      <span>改變 <span class="en">CHANGE</span></span>
      <span>尊嚴 <span class="en">DIGNITY</span></span>
      <span>農民是工作者</span>
      <span>不是補助對象</span>
    </div>
  </div>
  <div class="fw-ticker-weather">
    <div class="fw-w-icon"><i class="fas <?php echo esc_attr( $fw_w_icon ); ?>"></i></div>
    <span class="fw-w-badge">嘉義民雄</span>
    <div class="fw-w-msg-vp">
      <span class="fw-w-message"><?php echo esc_html( $fw_w_msg ); ?></span>
    </div>
    <a href="/occupational-safety/" class="fw-w-cta">防護指引 <i class="fas fa-arrow-right"></i></a>
  </div>
</div>
