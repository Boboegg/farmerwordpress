/*!
 * dc-v3-sections.js — L3 互動層（7 主章節頁）
 * 依賴：gsap.min.js + ScrollTrigger.min.js
 * 策略：內容預設可見；JS 失敗自動降級；僅保留必要互動
 */
(function () {
  'use strict';

  var PAGE_PREFIX = ['prev', 'hp', 'money', 'adv', 'res', 'edu', 'content'];

  function hasAnyPrefixClass(el, suffixes) {
    if (!el || !el.classList) return false;
    for (var i = 0; i < PAGE_PREFIX.length; i++) {
      for (var j = 0; j < suffixes.length; j++) {
        if (el.classList.contains(PAGE_PREFIX[i] + '-' + suffixes[j])) return true;
      }
    }
    return false;
  }

  function markRevealTargets(root) {
    var children = root.children;
    for (var i = 0; i < children.length; i++) {
      var el = children[i];
      if (!(el instanceof HTMLElement)) continue;
      if (hasAnyPrefixClass(el, ['hero', 'sections', 'about', 'cta', 'emergency', 'explore'])) {
        el.classList.add('dc-s-reveal');
      }
    }

    var cardSelectors = [
      '.prev-card', '.prevS-subpage-card', '.prevS-stat-card',
      '.hp-tab-card', '.hp-card', '.hpS-subpage-card',
      '.money-topic-card', '.money-tool-card', '.money-cross-card',
      '.adv-problem-card', '.adv-pillar-card', '.adv-tl-card', '.adv-fg-card', '.adv-media-card', '.adv-cross-card',
      '.res-cat-card', '.res-feat-card', '.res-approach-card', '.res-cross-card', '.res-stat-item',
      '.edu-path-card', '.edu-platform-card', '.edu-video-card', '.edu-ws-item', '.edu-dl-item', '.edu-cross-card',
      '.content-card'
    ];

    root.querySelectorAll(cardSelectors.join(',')).forEach(function (el) {
      el.classList.add('dc-hover-card');
      if (!el.classList.contains('dc-tilt-lite')) {
        el.classList.add('dc-tilt-lite');
      }
    });

    var ctaSelectors = [
      '.prev-btn-primary', '.prev-btn-secondary',
      '.hp-btn-primary', '.hp-btn-secondary',
      '.money-btn-primary', '.money-btn-secondary',
      '.adv-btn-primary', '.adv-btn-secondary',
      '.res-btn-primary', '.res-btn-secondary', '.res-hero-btn',
      '.edu-hero-chip', '.edu-btn-primary', '.edu-btn-secondary',
      '.content-card a'
    ];

    root.querySelectorAll(ctaSelectors.join(',')).forEach(function (el) {
      el.classList.add('dc-magnetic');
    });
  }

  function bindProgress(root) {
    var progress = root.querySelector('.dc-progress');
    if (!progress) return;

    function update() {
      var h = document.documentElement;
      var max = h.scrollHeight - h.clientHeight;
      progress.style.width = (max > 0 ? (h.scrollTop / max) * 100 : 0) + '%';
    }

    window.addEventListener('scroll', update, { passive: true });
    update();
  }

  function initGsap(root) {
    if (!(window.gsap && window.ScrollTrigger)) return;

    try {
      gsap.registerPlugin(ScrollTrigger);
      root.classList.add('has-gsap');

      root.querySelectorAll('.dc-s-reveal').forEach(function (el) {
        ScrollTrigger.create({
          trigger: el,
          start: 'top 85%',
          once: true,
          onEnter: function () {
            el.classList.add('dc-in');
          }
        });
      });

      // 不使用閃屏效果，避免章節長文閱讀被打斷
    } catch (e) {
      console.warn('[dc-v3-sections] gsap failed:', e);
      root.classList.remove('has-gsap');
    }
  }

  function init() {
    var root = document.querySelector('.fw-page.dc-v3.ch-safety, .fw-page.dc-v3.ch-health, .fw-page.dc-v3.ch-economic, .fw-page.dc-v3.ch-advocacy, .fw-page.dc-v3.ch-research, .fw-page.dc-v3.ch-about, .fw-page.dc-v3.ch-empower');
    if (!root) return;

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    markRevealTargets(root);
    bindProgress(root);

    if (reduce) return;

    initGsap(root);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
