/*!
 * dc-v3-sections.js — L3 互動層（7 主章節頁）
 * 依賴：gsap.min.js + ScrollTrigger.min.js
 * 策略：桌機優先；內容預設可見；JS 失敗自動降級
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

  function bindMagnetic(root) {
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
  }

  function bindTilt(root, selector, sx, sy, lift) {
    root.querySelectorAll(selector).forEach(function (card) {
      card.addEventListener('pointermove', function (e) {
        var r = card.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - 0.5;
        var y = (e.clientY - r.top) / r.height - 0.5;
        card.style.transform = 'perspective(900px) rotateX(' + (-y * sy).toFixed(2) + 'deg) rotateY(' + (x * sx).toFixed(2) + 'deg) translateY(' + lift + 'px)';
      });
      card.addEventListener('pointerleave', function () {
        card.style.transform = '';
      });
    });
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

      var cut = root.querySelector('.dc-scene-cut');
      if (cut) {
        var flashTargets = root.querySelectorAll('.dc-hover-card');
        if (flashTargets.length) {
          ScrollTrigger.create({
            trigger: flashTargets[0],
            start: 'top 84%',
            once: true,
            onEnter: function () {
              gsap.fromTo(cut, { opacity: 0 }, { opacity: 0.34, duration: 0.12, yoyo: true, repeat: 1 });
            }
          });
        }
      }
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
    if (window.matchMedia('(hover: hover)').matches) {
      bindMagnetic(root);
      bindTilt(root, '.dc-tilt', 8, 6, -3);
      bindTilt(root, '.dc-tilt-lite', 4, 3, -2);
    }

    initGsap(root);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
