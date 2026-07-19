import { readFile, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { corePages } from './src/pages/core.mjs';
import { fieldMediaPages } from './src/pages/field-media.mjs';
import { researchSinglePages } from './src/pages/research-single.mjs';

const worklogDir = dirname(fileURLToPath(import.meta.url));
const styles = await readFile(resolve(worklogDir, 'src/styles.css'), 'utf8');
const pages = [...corePages, ...fieldMediaPages, ...researchSinglePages]
  .sort((left, right) => left.id.localeCompare(right.id));

if (pages.length !== 9) {
  throw new Error(`預期九類頁面，實際收到 ${pages.length} 類`);
}

const navigation = pages.map((page) => `
  <li><a href="#page-${page.id}" aria-label="前往 ${page.title}">${page.id}</a></li>`).join('');

function renderSiteHeader() {
  return `
    <header class="mock-site-header">
      <div class="mock-brand" aria-label="尊嚴農業">
        <span class="mock-brand__mark" aria-hidden="true">禾</span>
        <strong class="mock-brand__name">尊嚴農業<span>DIGNITY FARMING</span></strong>
      </div>
      <nav aria-label="網站主要導覽">
        <ul>
          <li><a href="#">首頁</a></li>
          <li><a href="#">最新消息</a></li>
          <li><a href="#">田野誌</a></li>
          <li><a href="#">聽見田裡的聲音</a></li>
          <li><a href="#">田間知識與資源</a></li>
          <li><a href="#">研究與行動</a></li>
          <li><a href="#">認識尊嚴農業</a></li>
        </ul>
      </nav>
    </header>`;
}

function renderSiteFooter() {
  return `
    <footer class="mock-site-footer">
      <div><h3>尊嚴農業</h3><p>共構雲嘉農業工作者職業健康的社會安全網</p></div>
      <div><h3>快速連結</h3><ul><li>田間知識與資源</li><li>研究與行動</li></ul></div>
      <div><h3>研究與共作</h3><p>國立中正大學團隊與地方夥伴共同整理、研究與公開。</p></div>
    </footer>`;
}

function renderPage(page) {
  return `
  <section class="page-preview" id="page-${page.id}" data-page-family="${page.id}">
    <header class="page-preview__head">
      <span class="page-preview__number">${page.id}</span>
      <div>
        <h2 class="page-preview__title">${page.title}</h2>
        <p class="page-preview__decision">${page.decision}</p>
      </div>
      <span class="page-preview__badge">桌機 1440px</span>
    </header>
    <div class="site-canvas">
      ${renderSiteHeader()}
      <main class="mock-main mock-page mock-page--${page.slug}">
        ${page.body}
      </main>
      ${renderSiteFooter()}
    </div>
  </section>`;
}

const html = `<!doctype html>
<html lang="zh-Hant-TW" data-desktop-only="true">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=1440">
  <meta name="color-scheme" content="light">
  <title>尊嚴農業｜電腦版最終版型選擇室 v2</title>
  <style>${styles}</style>
</head>
<body>
  <header class="room-masthead">
    <div class="room-masthead__inner">
      <div>
        <p class="room-kicker">清朗田野 3.3.0・桌機骨架定案</p>
        <h1>九類頁面最終版型選擇室 v2</h1>
        <p>這次直接看完整的桌機頁面骨架：真實內容、實際密度、真正的區塊關係。手機與特效延後，不讓兩種問題混在一起。</p>
      </div>
      <aside class="room-status">
        <strong>本輪只看三件事</strong>
        <p>資訊先後、留白密度、區塊是否合理。色彩、字體、幾何與深綠頁尾沿用既定規格。</p>
      </aside>
    </div>
  </header>
  <nav class="room-nav" aria-label="九類頁面快速跳轉">
    <div class="room-nav__inner"><strong>逐頁檢視</strong><ol>${navigation}</ol></div>
  </nav>
  <main class="room-main">
    <section class="room-guidance" aria-label="檢視說明">
      <article><h2>平衡緊湊，不是全面壓縮</h2><p>主章節 48px、標題到內容 24px、欄距 20–24px；不讓父層與子層間距重複相加。</p></article>
      <article><h2>區塊靠內容模式分，不靠滿頁框線</h2><p>大章節用留白或單一底色面；細線只給時間流、清單與表格；卡片只包獨立可操作內容。</p></article>
    </section>
    ${pages.map(renderPage).join('\n')}
  </main>
  <button class="reading-control" type="button" aria-label="調整閱讀文字大小">Aa</button>
</body>
</html>`;

const outputPath = resolve(worklogDir, 'desktop-layout-room-v2.html');
await writeFile(outputPath, html, 'utf8');
console.log(`已建立 ${outputPath}`);
