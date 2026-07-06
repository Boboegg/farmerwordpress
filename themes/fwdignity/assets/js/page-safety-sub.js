/**
 * fwdignity · page-safety-sub.js
 * PPE 快速風險檢查互動邏輯
 */
(function() {
  'use strict';
  const cbs = document.querySelectorAll('.os-risk-cb');
  if (!cbs.length) return;

  const fill = document.getElementById('os-risk-fill');
  const scoreEl = document.getElementById('os-risk-score');
  const msgEl = document.getElementById('os-risk-msg');
  const total = cbs.length;

  const messages = [
    '勾選上方項目開始檢查你的防護狀況',
    '⚠️ 高風險·建議全面補齊防護具,每一項都可能救你一命',
    '⚠️ 高風險·目前防護明顯不足,請儘快改善',
    '🔶 中風險·仍有重要項目未做,優先處理呼吸和手部',
    '🔶 中風險·距離完整防護還有一段距離',
    '🟡 低風險·核心防護大致到位,再補齊細節',
    '✅ 良好·只差一兩項,繼續保持',
    '🎉 完整防護·你的習慣已是業界標竿'
  ];
  const colors = ['#6b7280', '#DC2626', '#DC2626', '#EA580C', '#EA580C', '#F59E0B', '#16A34A', '#15803D'];

  function update() {
    let n = 0;
    cbs.forEach(cb => { if (cb.checked) n++; });
    if (fill) fill.style.width = ((n / total) * 100) + '%';
    if (scoreEl) {
      scoreEl.textContent = n;
      scoreEl.style.color = colors[n];
    }
    if (msgEl) {
      msgEl.textContent = messages[n];
      msgEl.style.color = colors[n];
    }
  }
  cbs.forEach(cb => cb.addEventListener('change', update));
  update();
})();
