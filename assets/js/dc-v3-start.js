/*!
 * dc-v3-start.js — L3 Director Cut 互動層（新手務農分眾頁）
 * 依賴：gsap.min.js + ScrollTrigger.min.js
 * 設計原則：
 *   1. 所有文字預設可見（CSS default），JS 失敗不影響閱讀
 *   2. 只有在 GSAP 成功載入後才加 .has-gsap class 啟用隱藏+動畫
 *   3. Hero 永遠立即可見（不 scrub opacity），parallax 才做 scrub
 *   4. 全部 try/catch 包住，一個 bug 不連動整個 init
 */
(function () {
  'use strict';

  function init() {
    var root = document.querySelector('.fw-page.dc-v3.aud-start');
    if (!root) return;

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Scroll progress bar — 永遠可用，不依賴 GSAP
    var progress = root.querySelector('.dc-progress');
    function updateProgress() {
      var h = document.documentElement;
      var max = h.scrollHeight - h.clientHeight;
      if (progress) progress.style.width = (max > 0 ? h.scrollTop / max * 100 : 0) + '%';
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    if (reduce) return; // 尊重 reduced-motion，不開任何動畫

    // Noise canvas — 背景顆粒動畫
    try {
      var canvas = root.querySelector('.dc-noise');
      if (canvas) {
        var ctx = canvas.getContext('2d');
        var fit = function () {
          canvas.width = canvas.clientWidth;
          canvas.height = canvas.clientHeight;
        };
        fit();
        window.addEventListener('resize', fit);
        (function drawNoise() {
          var w = canvas.width;
          var h = canvas.height;
          if (!w || !h) {
            requestAnimationFrame(drawNoise);
            return;
          }
          var img = ctx.createImageData(w, h);
          var d = img.data;
          for (var i = 0; i < d.length; i += 4) {
            var v = (Math.random() * 255) | 0;
            d[i] = d[i + 1] = d[i + 2] = v;
            d[i + 3] = 18;
          }
          ctx.putImageData(img, 0, 0);
          requestAnimationFrame(drawNoise);
        })();
      }
    } catch (e) { console.warn('[dc-v3] noise canvas failed:', e); }

    // Magnetic CTA
    try {
      root.querySelectorAll('.dc-magnetic').forEach(function (btn) {
        btn.addEventListener('pointermove', function (e) {
          var r = btn.getBoundingClientRect();
          var dx = (e.clientX - (r.left + r.width / 2)) / (r.width / 2);
          var dy = (e.clientY - (r.top + r.height / 2)) / (r.height / 2);
          btn.style.transform = 'translate(' + (dx * 6).toFixed(1) + 'px,' + (dy * 4).toFixed(1) + 'px)';
        });
        btn.addEventListener('pointerleave', function () { btn.style.transform = ''; });
      });
    } catch (e) { console.warn('[dc-v3] magnetic failed:', e); }

    // 3D Tilt cards
    try {
      root.querySelectorAll('.dc-tilt').forEach(function (card) {
        card.addEventListener('pointermove', function (e) {
          var r = card.getBoundingClientRect();
          var x = (e.clientX - r.left) / r.width - 0.5;
          var y = (e.clientY - r.top) / r.height - 0.5;
          card.style.transform = 'perspective(900px) rotateX(' + (-y * 6).toFixed(2) + 'deg) rotateY(' + (x * 8).toFixed(2) + 'deg) translateY(-3px)';
        });
        card.addEventListener('pointerleave', function () { card.style.transform = ''; });
      });
    } catch (e) { console.warn('[dc-v3] tilt failed:', e); }

    // GSAP animations — 只有 GSAP 真的載入才執行
    if (window.gsap && window.ScrollTrigger) {
      try {
        gsap.registerPlugin(ScrollTrigger);

        // 啟用 has-gsap class：CSS 把 .dc-reveal 元素切換到「隱藏等進場」狀態
        root.classList.add('has-gsap');

        // Hero parallax only — 絕不 scrub hero 文字（文字永遠可見）
        var heroWrap = root.querySelector('.start-hero-wrap');
        if (heroWrap) {
          gsap.to('.start-hero-wrap .dc-bg1', {
            scale: 1.08, yPercent: -6,
            scrollTrigger: { trigger: heroWrap, start: 'top top', end: 'bottom bottom', scrub: 1.1 }
          });
          gsap.to('.start-hero-wrap .dc-bg2', {
            xPercent: 6, yPercent: -3, opacity: 0.7,
            scrollTrigger: { trigger: heroWrap, start: 'top top', end: 'bottom bottom', scrub: 1.1 }
          });
          gsap.to('.start-hero-wrap .dc-fog', {
            xPercent: -8, yPercent: 5, opacity: 0.55,
            scrollTrigger: { trigger: heroWrap, start: 'top top', end: 'bottom bottom', scrub: 1.1 }
          });
          gsap.to('.start-hero-wrap .dc-beam', {
            xPercent: 120, ease: 'none',
            scrollTrigger: { trigger: heroWrap, start: 'top top', end: 'bottom bottom', scrub: 1.1 }
          });
        }

        // Scene cut flash
        ScrollTrigger.create({
          trigger: '.start-stats',
          start: 'top 80%',
          onEnter: function () {
            gsap.fromTo('.dc-scene-cut', { opacity: 0 }, { opacity: 0.35, duration: 0.12, yoyo: true, repeat: 1 });
          }
        });

        // Reveal sections
        root.querySelectorAll('.dc-reveal').forEach(function (el) {
          ScrollTrigger.create({
            trigger: el,
            start: 'top 85%',
            once: true,
            onEnter: function () { el.classList.add('dc-in'); }
          });
        });

        // Pillar stagger
        var pillarsWrap = root.querySelector('.start-about-pillars');
        if (pillarsWrap) {
          ScrollTrigger.create({
            trigger: pillarsWrap,
            start: 'top 85%',
            once: true,
            onEnter: function () {
              root.querySelectorAll('.start-pillar').forEach(function (el, i) {
                setTimeout(function () { el.classList.add('dc-in'); }, i * 100);
              });
            }
          });
        }

        // Counter animation
        root.querySelectorAll('.dc-count').forEach(function (el) {
          ScrollTrigger.create({
            trigger: el,
            start: 'top 85%',
            once: true,
            onEnter: function () {
              var target = parseFloat(el.dataset.count);
              var decimals = parseInt(el.dataset.decimals || '0', 10);
              var obj = { v: 0 };
              gsap.to(obj, {
                v: target,
                duration: 1.2,
                ease: 'power2.out',
                onUpdate: function () { el.textContent = obj.v.toFixed(decimals); }
              });
            }
          });
        });
      } catch (e) {
        console.warn('[dc-v3] GSAP init failed:', e);
        // GSAP 初始化失敗時移除 has-gsap 讓所有 .dc-reveal 恢復可見
        root.classList.remove('has-gsap');
      }
    }
    // 如果 GSAP 沒載入，root 沒有 has-gsap class，.dc-reveal 預設可見 — 自動 degrade
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
