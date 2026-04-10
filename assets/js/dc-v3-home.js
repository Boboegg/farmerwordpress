/*!
 * dc-v3-home.js — 首頁 L3 互動層（三大主題卡）
 * 鐵律：內容預設可見。JS 啟動成功才加 .has-gsap 切到動畫模式。
 * 取代 inline <script> 避免 wp:html block 處理破壞 JS 語法。
 */
(function () {
  'use strict';

  function init() {
    var section = document.querySelector('.dc-home');
    if (!section) return;

    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var reveals = section.querySelectorAll('.dc-reveal');

    if (prefersReduced || !('IntersectionObserver' in window)) {
      // 環境不支援動畫 → 全部立刻可見
      reveals.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }

    // JS 跑得起來 → 啟用動畫模式（CSS 此時才會隱藏未顯示的元素）
    section.classList.add('has-gsap');

    try {
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.2 });

      reveals.forEach(function (el, idx) {
        el.style.transitionDelay = (idx * 70) + 'ms';
        observer.observe(el);
      });
    } catch (e) {
      console.warn('[dc-v3-home] IO failed:', e);
      // Fallback: 移除 has-gsap 讓 CSS 回到預設可見
      section.classList.remove('has-gsap');
      reveals.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }

    // 3D tilt cards (only on hover-capable devices)
    if (!window.matchMedia('(hover: hover)').matches) return;

    try {
      section.querySelectorAll('.dc-card').forEach(function (card) {
        card.addEventListener('mousemove', function (event) {
          var rect = card.getBoundingClientRect();
          var px = ((event.clientX - rect.left) / rect.width - 0.5) * 8;
          var py = ((event.clientY - rect.top) / rect.height - 0.5) * -8;
          card.style.transform = 'translateY(-8px) rotateX(' + py + 'deg) rotateY(' + px + 'deg)';
        });
        card.addEventListener('mouseleave', function () {
          card.style.transform = '';
        });
      });
    } catch (e) {
      console.warn('[dc-v3-home] tilt failed:', e);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
