/**
 * fwdignity · home.js
 * 首頁互動：reveal on scroll + 氣象即時 (CWA API placeholder)
 */
(function() {
  'use strict';

  // Reveal on scroll
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting) {
          e.target.classList.add('in');
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.reveal').forEach(function(el) { io.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function(el) { el.classList.add('in'); });
  }

  // 氣象已由 PHP get_minxiong_weather_status() server-side 渲染,前端不需 fetch

  // 最新動態 大卡輪播
  const slider = document.querySelector('.fw-news-feature-slider');
  if (slider) {
    const slides = slider.querySelectorAll('.fw-news-feature');
    const dots = slider.querySelectorAll('.fw-news-dot');
    const interval = parseInt(slider.getAttribute('data-interval'), 10) || 5000;
    let current = 0;
    let timer;

    function show(idx) {
      slides.forEach((s, i) => {
        s.classList.toggle('is-active', i === idx);
        s.classList.toggle('is-hidden', i !== idx);
      });
      dots.forEach((d, i) => d.classList.toggle('is-active', i === idx));
      current = idx;
    }
    function next() { show((current + 1) % slides.length); }
    function start() { timer = setInterval(next, interval); }
    function stop() { clearInterval(timer); }

    if (slides.length > 1) {
      start();
      dots.forEach((d, i) => {
        d.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          stop();
          show(i);
          updateList(i);
          start();
        });
      });
      slider.addEventListener('mouseenter', stop);
      slider.addEventListener('mouseleave', start);
    }

    // 右側列表：點擊只切換左側預覽,不跳轉（還原 news_magazine 原版 UX）
    const listItems = document.querySelectorAll('.fw-news-li');
    function updateList(idx) {
      listItems.forEach((li, i) => li.classList.toggle('is-active', i === idx));
    }
    listItems.forEach((li, i) => {
      li.addEventListener('click', (e) => {
        e.preventDefault();
        stop();
        show(i);
        updateList(i);
      });
    });
  }
})();
