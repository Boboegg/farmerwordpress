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
    var progress = root.querySelector('.dc-progress');

    function updateProgress() {
      var h = document.documentElement;
      var max = h.scrollHeight - h.clientHeight;
      if (progress) progress.style.width = (max > 0 ? (h.scrollTop / max) * 100 : 0) + '%';
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    if (reduce) return;

    // Magnetic CTA
    try {
      root.querySelectorAll('.dc-magnetic').forEach(function (el) {
        el.addEventListener('pointermove', function (e) {
          var r = el.getBoundingClientRect();
          var dx = (e.clientX - (r.left + r.width / 2)) / (r.width / 2);
          var dy = (e.clientY - (r.top + r.height / 2)) / (r.height / 2);
          el.style.transform = 'translate(' + (dx * 6).toFixed(1) + 'px,' + (dy * 4).toFixed(1) + 'px)';
        });
        el.addEventListener('pointerleave', function () {
          el.style.transform = '';
        });
      });
    } catch (e) {
      console.warn('[dc-v3-audience] magnetic failed:', e);
    }

    // 3D tilt cards
    function bindTilt(selector, strengthX, strengthY, lift) {
      root.querySelectorAll(selector).forEach(function (card) {
        card.addEventListener('pointermove', function (e) {
          var r = card.getBoundingClientRect();
          var x = (e.clientX - r.left) / r.width - 0.5;
          var y = (e.clientY - r.top) / r.height - 0.5;
          card.style.transform = 'perspective(900px) rotateX(' + (-y * strengthY).toFixed(2) + 'deg) rotateY(' + (x * strengthX).toFixed(2) + 'deg) translateY(' + lift + 'px)';
        });
        card.addEventListener('pointerleave', function () {
          card.style.transform = '';
        });
      });
    }

    try {
      bindTilt('.dc-tilt', 8, 6, -3);
      bindTilt('.dc-tilt-lite', 4, 3, -2);
    } catch (e) {
      console.warn('[dc-v3-audience] tilt failed:', e);
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

      // Scene cut flash on stats and scenarios entrance
      ['.young-stats', '.senior-stats', '.public-stats', '.young-scenarios', '.senior-scenarios', '.public-scenarios'].forEach(function (selector) {
        var target = root.querySelector(selector);
        if (!target) return;
        ScrollTrigger.create({
          trigger: target,
          start: 'top 82%',
          once: true,
          onEnter: function () {
            gsap.fromTo('.dc-scene-cut', { opacity: 0 }, { opacity: 0.34, duration: 0.12, yoyo: true, repeat: 1 });
          }
        });
      });
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
