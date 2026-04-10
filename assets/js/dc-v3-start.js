/*!
 * dc-v3-start.js — L3 Director Cut 互動層（新手務農分眾頁）
 * 依賴：gsap.min.js + ScrollTrigger.min.js
 * 設計原則：
 *   1. 所有文字預設可見（CSS default），JS 失敗不影響閱讀
 *   2. 只有在 GSAP 成功載入後才加 .has-gsap class 啟用 reveal
 *   3. 移除 parallax / scene-cut / 3D 動效，保留可讀性優先
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

    // GSAP animations — 只有 GSAP 真的載入才執行
    if (window.gsap && window.ScrollTrigger) {
      try {
        gsap.registerPlugin(ScrollTrigger);

        // 啟用 has-gsap class：CSS 把 .dc-reveal 元素切換到「隱藏等進場」狀態
        root.classList.add('has-gsap');

        // 不使用 parallax 與閃屏，避免視覺噱頭干擾閱讀

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
