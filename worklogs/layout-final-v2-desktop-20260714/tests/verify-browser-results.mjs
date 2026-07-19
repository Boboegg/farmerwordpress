import { createHash } from 'node:crypto';
import { readFile, stat } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const testDir = dirname(fileURLToPath(import.meta.url));
const projectRoot = resolve(testDir, '../../..');
const worklogDir = resolve(testDir, '..');
const resultsPath = resolve(projectRoot, 'output/playwright/layout-final-v2-desktop-20260714/results.json');
const htmlPath = resolve(worklogDir, 'desktop-layout-room-v2.html');
const screenshotPath = resolve(projectRoot, 'output/playwright/layout-final-v2-desktop-20260714/screenshots/desktop-layout-room-v2.png');

const [resultsText, html, screenshot] = await Promise.all([
  readFile(resultsPath, 'utf8'),
  readFile(htmlPath),
  readFile(screenshotPath)
]);
const results = JSON.parse(resultsText);
const failures = [];
const assert = (condition, message) => { if (!condition) failures.push(message); };
const sha256 = (buffer) => createHash('sha256').update(buffer).digest('hex');

assert(results.passed === true, '瀏覽器結果 passed 必須為 true');
assert(results.viewport?.width === 1440 && results.viewport?.height === 1100, 'viewport 必須是 1440×1100');
assert(results.document?.horizontalOverflow === 0, '不得有水平溢位');
assert(results.document?.scrollWidth === results.document?.clientWidth, 'scrollWidth 必須等於 clientWidth');
assert(Array.isArray(results.families) && results.families.length === 9, '必須有九類頁面結果');
for (const family of results.families || []) {
  assert(family.canvasWidth === 1320, `${family.id} 內容寬度必須是 1320px`);
  assert(family.h1Count === 1, `${family.id} 必須恰有一個頁內 H1`);
}
assert(results.images?.broken === 0, '不得有破圖');
assert(results.images?.withoutAlt === 0, '圖片不得缺少 alt');
assert(results.topicPage?.summaryFontSize === '20px', '主題副標必須為 20px');
assert(results.topicPage?.summaryWidth === 1100, '主題副標容器必須為 1100px');
assert(results.topicPage?.switcherCount === 0, '主題頁不得有 01–05 切換列');
assert(results.topicPage?.removedMarkerCount === 1, '主題選項移除標記必須恰有一個');
assert(results.articleMediaHeight <= 480, '文章媒體不得超過 480px');
assert(results.homeAlertHeight <= 64, '首頁警示不得高於 64px');
assert(results.typography?.h1 === '48px', 'H1 實算字級必須是 48px');
assert(results.typography?.body === '18px', '一般正文實算字級必須是 18px');
assert(results.typography?.longform === '20px', '長文實算字級必須是 20px');
assert(results.typography?.longformFamily?.includes('Noto Sans TC'), '長文必須使用 Noto Sans TC');
assert(results.geometry?.cardRadius === '14px', '內容卡圓角必須是 14px');
assert(results.geometry?.readingControl === '56px', 'Aa 必須是 56×56px');
assert(results.console?.errors === 0 && results.console?.warnings === 0, 'Console 必須零錯誤、零警告');
assert(results.artifacts?.htmlSha256 === sha256(html), 'HTML SHA256 與現行輸出不一致');
assert(results.artifacts?.fullScreenshotSha256 === sha256(screenshot), '完整截圖 SHA256 與現行輸出不一致');

const screenshotInfo = await stat(screenshotPath);
assert(screenshotInfo.size > 100_000, '完整截圖檔案異常過小');

if (failures.length) {
  console.error(`FAIL：瀏覽器結果契約有 ${failures.length} 項未通過`);
  for (const failure of failures) console.error(`- ${failure}`);
  process.exit(1);
}

console.log('PASS：1440px 瀏覽器結果與現行輸出一致');
console.log(`- 頁面家族：${results.families.length}/9`);
console.log(`- 圖片：${results.images.total} 張，破圖 ${results.images.broken}`);
console.log('- 溢位、字級、圓角、Aa、主題頁與文章媒體尺寸均通過');
