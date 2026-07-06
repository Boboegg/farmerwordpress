/**
 * fwdignity · page-healthgood.js
 * 主頁互動工具:健康風險速篩 + 心跳計算
 */
(function() {
  'use strict';

  // ========== 心跳警戒值 (180 − 年齡) ==========
  const hrBtn = document.getElementById('hp-hr-calc');
  const hrAge = document.getElementById('hp-hr-age');
  const hrResult = document.getElementById('hp-hr-result');
  const hrBpm = document.getElementById('hp-hr-bpm');
  if (hrBtn && hrAge) {
    function calc() {
      const age = parseInt(hrAge.value, 10);
      if (isNaN(age) || age < 15 || age > 100) {
        alert('請輸入合理的年齡 (15-100 歲)');
        return;
      }
      if (hrBpm) hrBpm.textContent = String(180 - age);
      if (hrResult) hrResult.hidden = false;
    }
    hrBtn.addEventListener('click', calc);
    hrAge.addEventListener('keypress', (e) => { if (e.key === 'Enter') calc(); });
  }

  // ========== 健康風險速篩 ==========
  const ageBtns = document.querySelectorAll('.hp-quiz-age');
  const checks = document.querySelectorAll('.hp-quiz-item input');
  const submitBtn = document.getElementById('hp-quiz-submit');
  const resetBtn = document.getElementById('hp-quiz-reset');
  const result = document.getElementById('hp-quiz-result');
  const levelEl = document.getElementById('hp-quiz-result-level');
  const msgEl = document.getElementById('hp-quiz-result-msg');
  const iconEl = document.getElementById('hp-quiz-result-icon');
  const topicsEl = document.getElementById('hp-quiz-topics');

  let selectedAge = -1;

  ageBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      ageBtns.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      selectedAge = parseInt(btn.dataset.age, 10);
    });
  });

  const topicMeta = {
    heat:      { name: '熱危害預防', icon: 'fa-temperature-arrow-up', url: '/healthgood/subpage-heat/',      color: '#DC2626', desc: '建議閱讀熱危害預防指南,學會辨識、預防與急救' },
    pesticide: { name: '農藥安全',   icon: 'fa-flask-vial',           url: '/healthgood/subpage-pesticide/', color: '#7C3AED', desc: '建議閱讀農藥安全指南,補強防護具與急救知識' },
    msd:       { name: '骨骼肌肉',   icon: 'fa-bone',                 url: '/healthgood/subpage-msd/',       color: '#1E40AF', desc: '建議閱讀骨骼肌肉保健指南,學會暖身伸展與就醫時機' },
    mental:    { name: '心靈紓壓',   icon: 'fa-brain',                url: '/healthgood/subpage-mental/',    color: '#15803D', desc: '建議閱讀心靈紓壓指南,認識求助管道,1925 有人聽你說' },
  };

  function buildTopicLink(meta) {
    const a = document.createElement('a');
    a.href = meta.url;
    a.className = 'hp-quiz-topic';
    a.style.setProperty('--tt', meta.color);

    const iconLead = document.createElement('i');
    iconLead.className = 'fas ' + meta.icon;
    a.appendChild(iconLead);

    const body = document.createElement('div');
    body.className = 'hp-quiz-topic-body';
    const strong = document.createElement('strong');
    strong.textContent = meta.name;
    const small = document.createElement('small');
    small.textContent = meta.desc;
    body.appendChild(strong);
    body.appendChild(small);
    a.appendChild(body);

    const iconTrail = document.createElement('i');
    iconTrail.className = 'fas fa-arrow-right hp-quiz-topic-arrow';
    a.appendChild(iconTrail);

    return a;
  }

  function setIcon(el, faClass) {
    while (el.firstChild) el.removeChild(el.firstChild);
    const i = document.createElement('i');
    i.className = 'fas ' + faClass;
    el.appendChild(i);
  }

  function submit() {
    if (selectedAge < 0) {
      alert('請先選擇年齡區間');
      return;
    }
    const topicScore = { heat: 0, pesticide: 0, msd: 0, mental: 0 };
    let total = 0;
    checks.forEach(cb => {
      if (cb.checked) {
        const topic = cb.closest('.hp-quiz-item').dataset.topic;
        topicScore[topic] = (topicScore[topic] || 0) + 1;
        total++;
      }
    });
    const weighted = total + (selectedAge === 2 ? 2 : selectedAge === 1 ? 1 : 0);

    let level, msg, iconClass, iconBg;
    if (weighted >= 6) {
      level = '高風險 · 建議立即採取行動';
      msg = '目前有多項風險因子同時存在,建議儘快改善防護,並考慮就醫做完整健康檢查。';
      iconClass = 'fa-triangle-exclamation';
      iconBg = '#DC2626';
    } else if (weighted >= 3) {
      level = '中風險 · 需要關注幾個面向';
      msg = '有幾項風險因子需要關注,請參考下方主題連結了解預防方法。';
      iconClass = 'fa-circle-exclamation';
      iconBg = '#EA580C';
    } else {
      level = '低風險 · 繼續保持好習慣';
      msg = '你目前的防護狀況不錯,繼續保持。仍建議定期健檢,有症狀及早就醫。';
      iconClass = 'fa-circle-check';
      iconBg = '#15803D';
    }

    levelEl.textContent = level;
    msgEl.textContent = msg;
    iconEl.style.background = iconBg;
    setIcon(iconEl, iconClass);

    // 列出需要關注的主題
    while (topicsEl.firstChild) topicsEl.removeChild(topicsEl.firstChild);
    Object.entries(topicScore)
      .filter(([, n]) => n > 0)
      .sort((a, b) => b[1] - a[1])
      .forEach(([t]) => topicsEl.appendChild(buildTopicLink(topicMeta[t])));

    result.hidden = false;
    setTimeout(() => result.scrollIntoView({ behavior: 'smooth', block: 'center' }), 50);
  }

  function reset() {
    ageBtns.forEach(b => b.classList.remove('is-active'));
    checks.forEach(cb => cb.checked = false);
    selectedAge = -1;
    result.hidden = true;
  }

  if (submitBtn) submitBtn.addEventListener('click', submit);
  if (resetBtn) resetBtn.addEventListener('click', reset);
})();
