/**
 * fwdignity · audience-fx.js
 * MASTER FINAL 09 動畫清單實作(安全 DOM 操作,不用 innerHTML)
 */
(function() {
  'use strict';

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduced) return;

  // ===== 1. Hero 逐字顯現 [data-hook-title] =====
  document.querySelectorAll('[data-hook-title]').forEach(el => {
    if (el.dataset.hookProcessed) return;
    el.dataset.hookProcessed = '1';
    const wrap = (node) => {
      if (node.nodeType === Node.TEXT_NODE) {
        const frag = document.createDocumentFragment();
        [...node.textContent].forEach(ch => {
          if (ch === ' ') { frag.appendChild(document.createTextNode(' ')); return; }
          const s = document.createElement('span');
          s.className = 'char';
          s.textContent = ch;
          s.style.display = 'inline-block';
          s.style.opacity = '0';
          s.style.transform = 'translateY(10px)';
          s.style.transition = 'opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1)';
          frag.appendChild(s);
        });
        node.replaceWith(frag);
      } else if (node.nodeType === Node.ELEMENT_NODE) {
        [...node.childNodes].forEach(wrap);
      }
    };
    wrap(el);
    el.querySelectorAll('.char').forEach((c, i) => {
      setTimeout(() => {
        c.style.opacity = '1';
        c.style.transform = 'translateY(0)';
      }, 200 + i * 40);
    });
  });

  // ===== 2. Scroll Progress Bar =====
  if (!document.querySelector('.v15-progress')) {
    const bar = document.createElement('div');
    bar.className = 'v15-progress';
    document.body.appendChild(bar);
  }
  const progress = document.querySelector('.v15-progress');
  function updateProgress() {
    const h = document.documentElement;
    const total = h.scrollHeight - h.clientHeight;
    const pct = total > 0 ? (h.scrollTop / total) * 100 : 0;
    progress.style.width = pct + '%';
  }
  updateProgress();
  window.addEventListener('scroll', updateProgress, { passive: true });

  // ===== 3. IntersectionObserver reveal stagger =====
  const revealEls = document.querySelectorAll('.reveal, .bf-scenario-card, .bf-stat-card, .bf-pillar, .bf-explore-card, .bf-emergency-item');
  revealEls.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1)';
  });
  const io = new IntersectionObserver(entries => {
    const visible = entries.filter(e => e.isIntersecting);
    visible.forEach((e, idx) => {
      setTimeout(() => {
        e.target.style.opacity = '1';
        e.target.style.transform = 'translateY(0)';
      }, idx * 80);
      io.unobserve(e.target);
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
  revealEls.forEach(el => io.observe(el));

  // ===== 4. Stat Count-Up [data-count] — 用 DOM 節點重組,不碰 innerHTML =====
  const nfZh = new Intl.NumberFormat('zh-Hant', { maximumFractionDigits: 1 });
  function animateCount(el, target) {
    const unitNode = el.querySelector('.bf-stat-unit');
    const unitClone = unitNode ? unitNode.cloneNode(true) : null;
    const duration = 1500;
    const start = performance.now();
    const ease = t => 1 - Math.pow(1 - t, 3);
    function writeNum(num) {
      while (el.firstChild) el.removeChild(el.firstChild);
      el.appendChild(document.createTextNode(num));
      if (unitClone) el.appendChild(unitClone.cloneNode(true));
    }
    function frame(now) {
      const t = Math.min(1, (now - start) / duration);
      const v = target * ease(t);
      const formatted = (target >= 10) ? Math.round(v).toString() : nfZh.format(v);
      writeNum(formatted);
      if (t < 1) {
        requestAnimationFrame(frame);
      } else {
        const finalNum = target.toString().indexOf('.') > -1 ? target.toString() : Math.round(target).toString();
        writeNum(finalNum);
      }
    }
    requestAnimationFrame(frame);
  }
  const countIO = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const target = parseFloat(e.target.dataset.count);
      if (!isNaN(target)) animateCount(e.target, target);
      countIO.unobserve(e.target);
    });
  }, { threshold: 0.4 });
  document.querySelectorAll('[data-count]').forEach(el => countIO.observe(el));

  // ===== 5. Magnetic CTA [data-magnetic] =====
  document.querySelectorAll('[data-magnetic]').forEach(btn => {
    btn.addEventListener('mousemove', e => {
      const r = btn.getBoundingClientRect();
      const x = (e.clientX - r.left - r.width / 2) / r.width;
      const y = (e.clientY - r.top - r.height / 2) / r.height;
      btn.style.transform = `translate(${x * 8}px, ${y * 8}px)`;
    });
    btn.addEventListener('mouseleave', () => {
      btn.style.transition = 'transform 0.5s cubic-bezier(0.34,1.56,0.64,1)';
      btn.style.transform = '';
      setTimeout(() => { btn.style.transition = ''; }, 500);
    });
    btn.addEventListener('mouseenter', () => { btn.style.transition = ''; });
  });

  // ===== 6. Tilt Card [data-tilt] =====
  document.querySelectorAll('[data-tilt]').forEach(card => {
    card.style.transformStyle = 'preserve-3d';
    card.addEventListener('mousemove', e => {
      const r = card.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - 0.5;
      const y = (e.clientY - r.top) / r.height - 0.5;
      card.style.transform = `perspective(1000px) rotateX(${-y * 5}deg) rotateY(${x * 5}deg)`;
    });
    card.addEventListener('mouseleave', () => {
      card.style.transition = 'transform 0.5s cubic-bezier(0.16,1,0.3,1)';
      card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
      setTimeout(() => { card.style.transition = ''; }, 500);
    });
    card.addEventListener('mouseenter', () => { card.style.transition = ''; });
  });
})();
