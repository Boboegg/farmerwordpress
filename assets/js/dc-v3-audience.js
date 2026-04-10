/*!
 * dc-v3-audience.js — L3 Director Cut 互動層（青農 / 資深 / 公眾）
 * 依賴：gsap.min.js + ScrollTrigger.min.js
 * 原則：內容預設可見，JS 失敗時不影響閱讀
 */
(function () {
  'use strict';

  function init() {
    var root = document.querySelector('.fw-page.dc-v3.aud-young, .fw-page.dc-v3.aud-senior, .fw-page.dc-v3.aud-public');
    if (!root) return;

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var canHover = window.matchMedia('(hover: hover)').matches && window.matchMedia('(pointer: fine)').matches;
    var progress = root.querySelector('.dc-progress');

    function updateProgress() {
      var h = document.documentElement;
      var max = h.scrollHeight - h.clientHeight;
      if (progress) progress.style.width = (max > 0 ? (h.scrollTop / max) * 100 : 0) + '%';
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    if (reduce) return;

    // 互動保留最小必要回饋：僅在可 hover 裝置加上可選 class
    if (canHover) {
      root.classList.add('dc-hover-ready');
    }

    if (!(window.gsap && window.ScrollTrigger)) return;

    try {
      gsap.registerPlugin(ScrollTrigger);
      root.classList.add('has-gsap');

      // Section reveal
      root.querySelectorAll('.dc-reveal').forEach(function (el) {
        ScrollTrigger.create({
          trigger: el,
          start: 'top 85%',
          once: true,
          onEnter: function () {
            el.classList.add('dc-in');
          }
        });
      });

      // Pillar stagger
      root.querySelectorAll('.young-about-pillars, .senior-about-pillars, .public-about-pillars').forEach(function (wrap) {
        ScrollTrigger.create({
          trigger: wrap,
          start: 'top 86%',
          once: true,
          onEnter: function () {
            wrap.querySelectorAll('.dc-pillar-stagger').forEach(function (el, i) {
              setTimeout(function () { el.classList.add('dc-in'); }, i * 90);
            });
          }
        });
      });

      // 移除場景閃屏，避免干擾閱讀
    } catch (e) {
      console.warn('[dc-v3-audience] GSAP init failed:', e);
      root.classList.remove('has-gsap');
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
