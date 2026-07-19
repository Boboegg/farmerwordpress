import { readFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const testDir = dirname(fileURLToPath(import.meta.url));
const worklogDir = resolve(testDir, '..');
const outputPath = resolve(worklogDir, 'desktop-layout-room-v2.html');

const failures = [];

function assert(condition, message) {
  if (!condition) failures.push(message);
}

let html = '';
try {
  html = await readFile(outputPath, 'utf8');
} catch (error) {
  console.error(`FAIL：找不到輸出檔 ${outputPath}`);
  console.error(error.message);
  process.exit(1);
}

const requiredFamilies = Array.from({ length: 9 }, (_, index) => String(index + 1).padStart(2, '0'));
for (const family of requiredFamilies) {
  const matches = html.match(new RegExp(`data-page-family=["']${family}["']`, 'g')) || [];
  assert(matches.length === 1, `頁面家族 ${family} 應恰好出現一次，目前 ${matches.length} 次`);
}

const requiredMarkers = [
  '高溫熱危害預警',
  '⚠️ 高溫特報 (35°C)：請務必多喝水、每工作30分鐘至陰涼處休息，慎防熱衰竭！',
  '共構雲嘉農業工作者職業健康的社會安全網',
  'EP218：愛上種田的父女檔',
  '身體如何承受農務？',
  '問題 → 研究 → 共作 → 公開成果',
  '研究團隊'
];

for (const marker of requiredMarkers) {
  assert(html.includes(marker), `缺少真實內容標記：${marker}`);
}

const requiredTokens = [
  '--fw-paper: #FDFBF7',
  '--fw-surface: #FFFFFF',
  '--fw-brand: #435832',
  '--fw-ink: #283121',
  '--fw-body: #1A1A1A',
  '--fw-gold: #7B5D12',
  '--fw-footer: #25331E'
];

for (const token of requiredTokens) {
  assert(html.includes(token), `缺少清朗田野 token：${token}`);
}

const forbiddenPatterns = [
  { pattern: /@media\s*\(/i, label: '本輪不應包含手機或響應式 media query' },
  { pattern: /01\s*\/\s*05/, label: '田野主題頁不應保留 01 / 05' },
  { pattern: /data-topic-switcher/, label: '田野主題頁不應保留五題切換列' },
  { pattern: /\.article-entry\s*\{[^}]*border\s*:/is, label: '文章單篇不應有包住整篇的外框' },
  { pattern: /nth-(?:child|of-type)\s*\(/i, label: '最新消息不應靠 nth-child 排版' },
  { pattern: /thenews\/\?m=/i, label: '年份月份預覽不得連到目前會 404 的日期網址' },
  { pattern: /lorem ipsum/i, label: '不得出現 lorem ipsum' }
];

for (const { pattern, label } of forbiddenPatterns) {
  assert(!pattern.test(html), label);
}

const familyHeadings = html.match(/<h2\b[^>]*class=["'][^"']*page-preview__title[^"']*["'][^>]*>/g) || [];
assert(familyHeadings.length === 9, `九類頁面應各有一個主要 h2，目前 ${familyHeadings.length} 個`);

assert(html.includes('data-desktop-only="true"'), '輸出應標示為 desktop-only');
assert(html.includes('max-width: 1320px'), '主要內容寬度應為 1320px');
assert(html.includes('max-width: 780px'), '長文正文寬度應為 780px');
assert(html.includes('font-size: 20px'), '田野主題副標應包含 20px 桌機規格');
assert(html.includes('data-topic-options-removed="true"'), '田野主題頁應留下移除選項列的可驗證標記');

const lockedVisualRules = [
  'font: 700 48px/1.32 var(--fw-serif)',
  'font: 700 34px/1.38 var(--fw-serif)',
  'font: 700 24px/1.45 var(--fw-serif)',
  'font: 400 20px/1.85 var(--fw-sans)',
  'font-size: 15px !important',
  'border-radius: 14px',
  'width: 56px',
  'right: 18px',
  'bottom: 18px',
  'box-shadow: 0 6px 18px rgba(37, 51, 30, .18)'
];

for (const rule of lockedVisualRules) {
  assert(html.includes(rule), `缺少 3.3.0 鎖定規則：${rule}`);
}

if (failures.length) {
  console.error(`FAIL：桌機版型契約有 ${failures.length} 項未通過`);
  for (const failure of failures) console.error(`- ${failure}`);
  process.exit(1);
}

console.log('PASS：桌機版型契約全數通過');
console.log(`- 九類頁面：${requiredFamilies.length}/9`);
console.log(`- 真實內容標記：${requiredMarkers.length}/${requiredMarkers.length}`);
console.log(`- 禁止模式：${forbiddenPatterns.length}/${forbiddenPatterns.length}`);
