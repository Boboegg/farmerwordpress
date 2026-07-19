(function() {
  'use strict';
  var root = document.getElementById('page');
  if (!root) return;

  /* Hero entrance — stagger 每個 char */
  requestAnimationFrame(function() {
    var heroChars = root.querySelectorAll('.hook-title .char');
    heroChars.forEach(function(c, i) { c.style.transitionDelay = (0.2 + i * 0.04) + 's'; });
    root.classList.add('js-ready');
  });

  /* Scroll progress + scrolled state */
  var progress = root.querySelector('.v15-progress');
  window.addEventListener('scroll', function() {
    var h = document.documentElement;
    var pct = (h.scrollTop / (h.scrollHeight - h.clientHeight)) * 100;
    if (progress) progress.style.width = pct + '%';
    root.classList.toggle('scrolled', h.scrollTop > 10);
  }, { passive: true });

  /* Page switcher */
  var switcher = document.getElementById('pageSwitcher');
  if (switcher) {
    switcher.addEventListener('change', function(e) {
      root.setAttribute('data-page', e.target.value);
    });
  }
  /* topic-card 點擊切色（沿用 method card 的 data-goto 行為） */
  root.querySelectorAll('[data-goto]').forEach(function(card) {
    card.addEventListener('click', function(e) {
      e.preventDefault();
      root.setAttribute('data-page', card.dataset.goto);
      if (switcher) switcher.value = card.dataset.goto;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  /* Method 卡 stagger reveal delay（每張 +80ms） */
  var topicCards = root.querySelectorAll('.topic-grid .topic-card');
  topicCards.forEach(function(c, i) { c.style.transitionDelay = (i * 0.08) + 's'; });

  /* News 卡 stagger */
  var newsCards = root.querySelectorAll('.news-grid .news-card');
  newsCards.forEach(function(c, i) { c.style.transitionDelay = (i * 0.08) + 's'; });

  /* Media 卡 stagger */
  var mediaCards = root.querySelectorAll('.media-grid .media-card');
  mediaCards.forEach(function(c, i) { c.style.transitionDelay = (i * 0.06) + 's'; });

  /* Audience 卡 stagger */
  var audienceCards = root.querySelectorAll('.audience-grid .audience-card');
  audienceCards.forEach(function(c, i) { c.style.transitionDelay = (i * 0.1) + 's'; });

  if ('IntersectionObserver' in window) {
    /* Reveal */
    var revealIo = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          revealIo.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    root.querySelectorAll('.reveal, [data-proof]').forEach(function(el) { revealIo.observe(el); });

    /* Scramble — proof 3 數字 */
    var scrambleIo = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var target = el.dataset.scramble;
        var chars = '0123456789./';
        var iter = 0;
        var finalArr = target.split('');
        var len = finalArr.length;
        var interval = setInterval(function() {
          var out = '';
          for (var i = 0; i < len; i++) {
            if (i < iter) out += finalArr[i];
            else out += chars[Math.floor(Math.random() * chars.length)];
          }
          el.textContent = out;
          iter += 1 / 3;
          if (iter >= len) {
            clearInterval(interval);
            el.textContent = target;
          }
        }, 60);
        scrambleIo.unobserve(el);
      });
    }, { threshold: 0.4 });
    root.querySelectorAll('[data-scramble]').forEach(function(el) { scrambleIo.observe(el); });

    /* Scramble — 4 分眾大號碼（0 → 01/02/03/04） */
    var scrambleAcIo = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var target = el.dataset.scrambleAc;
        var chars = '0123456789';
        var iter = 0;
        var finalArr = target.split('');
        var len = finalArr.length;
        var interval = setInterval(function() {
          var out = '';
          for (var i = 0; i < len; i++) {
            if (i < iter) out += finalArr[i];
            else out += chars[Math.floor(Math.random() * chars.length)];
          }
          el.textContent = out;
          iter += 1 / 2;
          if (iter >= len) {
            clearInterval(interval);
            el.textContent = target;
          }
        }, 70);
        scrambleAcIo.unobserve(el);
      });
    }, { threshold: 0.5 });
    root.querySelectorAll('[data-scramble-ac]').forEach(function(el) { scrambleAcIo.observe(el); });

    /* Count-up — statband 4 格 */
    var countIo = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var target = parseFloat(el.dataset.count);
        var dec = parseInt(el.dataset.dec || '0');
        var start = performance.now();
        var duration = 1400;
        function step(now) {
          var t = Math.min(1, (now - start) / duration);
          var eased = 1 - Math.pow(1 - t, 4);
          el.textContent = (target * eased).toFixed(dec);
          if (t < 1) requestAnimationFrame(step);
          else el.textContent = dec ? target.toFixed(dec) : target.toString();
        }
        requestAnimationFrame(step);
        countIo.unobserve(el);
      });
    }, { threshold: 0.5 });
    root.querySelectorAll('.statband-cell [data-count]').forEach(function(el) { countIo.observe(el); });
  } else {
    /* Fallback: 全部直接顯示 */
    root.querySelectorAll('.reveal, [data-proof]').forEach(function(el) { el.classList.add('in'); });
    root.querySelectorAll('[data-scramble]').forEach(function(el) { el.textContent = el.dataset.scramble; });
    root.querySelectorAll('[data-scramble-ac]').forEach(function(el) { el.textContent = el.dataset.scrambleAc; });
    root.querySelectorAll('.statband-cell [data-count]').forEach(function(el) { el.textContent = el.dataset.count; });
  }
})();
