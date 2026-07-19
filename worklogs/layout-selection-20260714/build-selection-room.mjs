import { readFile, stat, writeFile } from 'node:fs/promises';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { families } from './layout-config.mjs';

const scriptDir = dirname(fileURLToPath(import.meta.url));
const projectRoot = resolve(scriptDir, '../..');
const outputDir = join(projectRoot, 'output/playwright/layout-selection-20260714');
const resultsPath = join(outputDir, 'results.json');
const snapshotPath = join(outputDir, 'source-snapshot.json');

const [results, snapshot] = await Promise.all([
  readFile(resultsPath, 'utf8').then(JSON.parse),
  readFile(snapshotPath, 'utf8').then(JSON.parse)
]);

if (!results.passed || results.failureCount !== 0 || results.completed !== results.totalExpected) {
  throw new Error(`瀏覽器驗證未全數通過：${results.completed}/${results.totalExpected}，失敗 ${results.failureCount}`);
}

const caseMap = new Map(results.cases.map((item) => [item.key, item]));
const options = ['A', 'B', 'C'];

function escapeHtml(value) {
  return String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function screenshotCase(familyId, option, pageId, mobile) {
  const key = [familyId, option.toLowerCase(), pageId, mobile ? 'mobile' : 'desktop'].join('--');
  const item = caseMap.get(key);
  if (!item) throw new Error(`缺少案例：${key}`);
  return item;
}

for (const item of results.cases) {
  const screenshotPath = join(outputDir, item.screenshot);
  const info = await stat(screenshotPath);
  if (!info.isFile() || info.size === 0) throw new Error(`截圖不存在或為空：${item.screenshot}`);
}

for (const family of families) {
  for (const mode of ['desktop', 'mobile']) {
    const sheetPath = join(outputDir, 'contact-sheets', `${family.id}--${mode}.jpg`);
    const info = await stat(sheetPath);
    if (!info.isFile() || info.size === 0) throw new Error(`比較表不存在或為空：${sheetPath}`);
  }
}

function renderOptionCard(family, option) {
  const detail = family.options[option];
  const recommended = family.recommendation === option
    ? '<span class="recommendation">設計建議</span>'
    : '';
  return `
    <label class="option-card" data-option="${option}">
      <span class="option-head">
        <input type="radio" name="family-${family.id}" value="${option}" data-family="${family.id}">
        <span class="option-letter" aria-hidden="true">${option}</span>
        <span class="option-name">${escapeHtml(detail.name)}</span>
        ${recommended}
      </span>
      <span class="option-summary">${escapeHtml(detail.summary)}</span>
      <span class="option-tradeoff"><strong>取捨：</strong>${escapeHtml(detail.tradeoff)}</span>
    </label>`;
}

function renderDesktopPage(family, page) {
  const shots = options.map((option) => {
    const item = screenshotCase(family.id, option, page.id, false);
    return `
      <figure class="shot-card">
        <a href="${escapeHtml(item.screenshot)}" target="_blank" rel="noopener" aria-label="開啟 ${escapeHtml(page.title)} ${option} 桌機完整截圖">
          <img class="shot-preview" src="${escapeHtml(item.screenshot)}" loading="lazy" decoding="async" alt="${escapeHtml(page.title)}版型 ${option} 桌機完整頁面預覽">
        </a>
        <figcaption><strong>${option}｜${escapeHtml(family.options[option].name)}</strong><span>${item.audit.documentWidth}px × ${item.captureHeight}px・驗證通過</span></figcaption>
      </figure>`;
  }).join('');

  return `
    <article class="page-case">
      <header class="page-case-head">
        <div><h4>${escapeHtml(page.title)}</h4><code>${escapeHtml(page.path)}</code></div>
        <span>桌機 1440px</span>
      </header>
      <div class="shot-grid">${shots}</div>
    </article>`;
}

function renderMobileCase(family) {
  const page = family.pages.find((item) => item.id === family.mobileRepresentative);
  const shots = options.map((option) => {
    const item = screenshotCase(family.id, option, page.id, true);
    return `
      <figure class="shot-card mobile-shot-card">
        <a href="${escapeHtml(item.screenshot)}" target="_blank" rel="noopener" aria-label="開啟 ${escapeHtml(page.title)} ${option} 手機完整截圖">
          <img class="shot-preview mobile-shot" src="${escapeHtml(item.screenshot)}" loading="lazy" decoding="async" alt="${escapeHtml(page.title)}版型 ${option} 手機完整頁面預覽">
        </a>
        <figcaption><strong>${option}｜${escapeHtml(family.options[option].name)}</strong><span>390px 代表案例・驗證通過</span></figcaption>
      </figure>`;
  }).join('');

  return `
    <article class="page-case mobile-case">
      <header class="page-case-head">
        <div><h4>手機代表案例｜${escapeHtml(page.title)}</h4><code>${escapeHtml(page.path)}</code></div>
        <span>手機 390px</span>
      </header>
      <div class="shot-grid">${shots}</div>
    </article>`;
}

function renderFamily(family) {
  const pageCount = family.pages.length;
  const pageLabel = pageCount === 1 ? '1 個實頁' : `${pageCount} 個實頁`;
  return `
  <section class="family-section" id="family-${family.id}" data-family-section="${family.id}">
    <header class="family-head">
      <div class="family-number">${family.number}</div>
      <div>
        <p class="eyebrow">版型拍板題・${pageLabel}</p>
        <h2>${escapeHtml(family.title)}</h2>
        <p class="mission">${escapeHtml(family.mission)}</p>
      </div>
    </header>

    <div class="comparison-block" aria-labelledby="compare-${family.id}">
      <div class="subhead-row">
        <div><p class="eyebrow">先看全頁節奏</p><h3 id="compare-${family.id}">A／B／C 同列比較</h3></div>
        <p>欄由左至右是 A、B、C；列由上至下是頁首、中段、頁尾。點圖可看原尺寸。</p>
      </div>
      <div class="sheet-grid">
        <figure>
          <a href="contact-sheets/${family.id}--desktop.jpg" target="_blank" rel="noopener">
            <img src="contact-sheets/${family.id}--desktop.jpg" loading="lazy" decoding="async" alt="${escapeHtml(family.title)} A B C 桌機全頁比較表">
          </a>
          <figcaption>桌機版・家族內每個實頁</figcaption>
        </figure>
        <figure>
          <a href="contact-sheets/${family.id}--mobile.jpg" target="_blank" rel="noopener">
            <img src="contact-sheets/${family.id}--mobile.jpg" loading="lazy" decoding="async" alt="${escapeHtml(family.title)} A B C 手機全頁比較表">
          </a>
          <figcaption>手機版・代表實頁（手機會刻意收斂為單欄）</figcaption>
        </figure>
      </div>
    </div>

    <fieldset class="decision-fieldset">
      <legend>選擇 ${family.number} ${escapeHtml(family.title)} 的骨架</legend>
      <p class="legend-help">「設計建議」只是判斷依據，沒有預先勾選；你可以自由選，也能在下方寫混合方式。</p>
      <div class="option-grid">${options.map((option) => renderOptionCard(family, option)).join('')}</div>
      <label class="note-label" for="note-${family.id}">混合備註／保留條件</label>
      <textarea id="note-${family.id}" data-note-family="${family.id}" rows="3" placeholder="例：整體選 A，但長文第一屏採 C；請說明外框、第一屏、內容流或手機要取哪一案。"></textarea>
      <p class="save-note" id="saved-${family.id}" aria-live="polite">尚未選擇</p>
    </fieldset>

    <details class="all-pages">
      <summary>展開 ${pageLabel}的 1440px 原始比較與 390px 手機代表案例</summary>
      <div class="all-pages-body">
        ${family.pages.map((page) => renderDesktopPage(family, page)).join('')}
        ${renderMobileCase(family)}
      </div>
    </details>
  </section>`;
}

const navItems = families.map((family) => `
  <li><a href="#family-${family.id}" data-nav-family="${family.id}"><span>${family.number}</span>${escapeHtml(family.title)}<b aria-hidden="true">未選</b></a></li>`).join('');

const familySections = families.map(renderFamily).join('\n');
const capturedAt = snapshot.captured_at.replace('T', ' ').replace('+0800', '（台北時間）');

const html = `<!doctype html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light">
  <title>尊嚴農業｜全站版型選擇室</title>
  <style>
    :root {
      --paper: #FDFBF7;
      --paper-2: #F5F5F2;
      --surface: #FFFFFF;
      --surface-2: #F0F0EC;
      --brand: #435832;
      --brand-hover: #314225;
      --ink: #283121;
      --muted: #566155;
      --body: #1A1A1A;
      --gold: #7B5D12;
      --line: #E0E0E0;
      --footer: #25331E;
      --footer-text: #F7F6F1;
      --warning: #A24A3D;
      --focus-gap: #F7F6F1;
      --max: 1480px;
      --serif: "Noto Serif TC", "Songti TC", serif;
      --sans: "Noto Sans TC", "PingFang TC", sans-serif;
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { margin: 0; background: var(--paper); color: var(--body); font-family: var(--sans); line-height: 1.65; }
    img { display: block; max-width: 100%; }
    button, input, textarea { font: inherit; }
    a { color: var(--brand); text-underline-offset: .2em; }
    a:hover { color: var(--brand-hover); }
    :focus-visible { outline: 3px solid var(--ink); outline-offset: 3px; box-shadow: 0 0 0 2px var(--focus-gap); }
    .skip-link { position: fixed; z-index: 20; left: 18px; top: 12px; transform: translateY(-160%); background: var(--ink); color: white; padding: 10px 14px; border-radius: 4px; }
    .skip-link:focus { transform: none; }

    .masthead { background: var(--footer); color: var(--footer-text); border-bottom: 4px solid var(--brand); }
    .masthead-inner { max-width: var(--max); margin: 0 auto; padding: 64px 32px 56px; display: grid; grid-template-columns: minmax(0, 1fr) minmax(330px, .42fr); gap: 64px; align-items: end; }
    .brandline { margin: 0 0 18px; color: #DCE4D8; font-size: 15px; letter-spacing: .08em; }
    .masthead h1 { margin: 0; max-width: 900px; font: 700 clamp(38px, 5vw, 72px)/1.16 var(--serif); letter-spacing: -.025em; }
    .lede { max-width: 760px; margin: 24px 0 0; color: #DCE4D8; font-size: 18px; }
    .masthead-note { border: 1px solid rgba(247,246,241,.26); border-radius: 14px; padding: 24px; background: rgba(255,255,255,.035); }
    .masthead-note strong { display: block; margin-bottom: 8px; color: white; font-family: var(--serif); font-size: 22px; }
    .masthead-note p { margin: 0; color: #DCE4D8; }

    .evidence-strip { max-width: var(--max); margin: 0 auto; padding: 28px 32px; display: grid; grid-template-columns: repeat(3, 1fr) minmax(280px, 2fr); gap: 1px; background: var(--line); border-inline: 1px solid var(--line); }
    .evidence-item { min-height: 112px; padding: 20px; background: var(--surface); }
    .evidence-item strong { display: block; font: 700 30px/1.2 var(--serif); color: var(--ink); }
    .evidence-item span { color: var(--muted); font-size: 14px; }
    .evidence-warning { border-left: 4px solid var(--warning); }
    .evidence-warning strong { font: 700 16px/1.4 var(--sans); }

    .workspace { max-width: var(--max); margin: 0 auto; padding: 48px 32px 96px; display: grid; grid-template-columns: 260px minmax(0, 1fr); gap: 48px; }
    .side { align-self: start; position: sticky; top: 18px; }
    .progress-card { padding: 20px; border: 1px solid var(--line); border-radius: 14px; background: var(--surface); box-shadow: 0 4px 14px rgba(37,51,30,.06); }
    .progress-card h2 { margin: 0; font: 700 22px/1.35 var(--serif); color: var(--ink); }
    .progress-card p { margin: 8px 0 16px; color: var(--muted); }
    .progress-track { height: 4px; background: var(--surface-2); }
    .progress-bar { height: 100%; width: 0; background: var(--brand); transition: width 160ms ease; }
    .decision-nav { margin-top: 18px; }
    .decision-nav ol { list-style: none; margin: 0; padding: 0; border-top: 1px solid var(--line); }
    .decision-nav a { min-height: 48px; padding: 9px 4px; display: grid; grid-template-columns: 30px 1fr auto; gap: 8px; align-items: center; border-bottom: 1px solid var(--line); color: var(--ink); text-decoration: none; font-size: 14px; }
    .decision-nav a:hover { background: var(--paper-2); }
    .decision-nav a span { color: var(--gold); font-variant-numeric: tabular-nums; }
    .decision-nav a b { color: var(--muted); font-size: 12px; font-weight: 500; }
    .decision-nav a[data-selected="true"] b { color: var(--brand); font-weight: 700; }
    .side-actions { display: grid; gap: 8px; margin-top: 18px; }

    .content { min-width: 0; }
    .intro-panel { margin-bottom: 56px; padding: 28px 32px; border-left: 4px solid var(--brand); background: var(--paper-2); }
    .intro-panel h2 { margin: 0 0 8px; font: 700 28px/1.35 var(--serif); color: var(--ink); }
    .intro-panel p { margin: 8px 0 0; max-width: 78ch; }

    .family-section { scroll-margin-top: 18px; padding: 0 0 72px; margin: 0 0 72px; border-bottom: 2px solid var(--ink); }
    .family-head { display: grid; grid-template-columns: 68px minmax(0, 1fr); gap: 20px; align-items: start; margin-bottom: 32px; }
    .family-number { color: var(--gold); font: 600 30px/1 var(--serif); padding-top: 8px; border-top: 4px solid var(--gold); }
    .eyebrow { margin: 0 0 6px; color: var(--gold); font-size: 13px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
    .family-head h2 { margin: 0; color: var(--ink); font: 700 clamp(32px, 4vw, 48px)/1.25 var(--serif); }
    .mission { margin: 12px 0 0; color: var(--muted); font-size: 18px; }

    .comparison-block { padding: 28px; border: 1px solid var(--line); border-radius: 14px; background: var(--surface); }
    .subhead-row { display: grid; grid-template-columns: minmax(260px, .8fr) 1.2fr; gap: 32px; align-items: end; margin-bottom: 24px; }
    .subhead-row h3 { margin: 0; color: var(--ink); font: 700 26px/1.35 var(--serif); }
    .subhead-row > p { margin: 0; color: var(--muted); }
    .sheet-grid { display: grid; grid-template-columns: minmax(0, 1.8fr) minmax(240px, .72fr); gap: 20px; align-items: start; }
    figure { margin: 0; }
    .sheet-grid a { display: block; border: 1px solid var(--line); background: var(--paper-2); overflow: hidden; border-radius: 14px; }
    .sheet-grid img { width: 100%; height: auto; }
    figcaption { margin-top: 9px; color: var(--muted); font-size: 13px; }

    .decision-fieldset { margin: 28px 0 0; padding: 28px; border: 1px solid var(--line); border-radius: 14px; background: var(--surface); }
    .decision-fieldset legend { padding: 0 10px; color: var(--ink); font: 700 24px/1.35 var(--serif); }
    .legend-help { margin: 0 0 20px; color: var(--muted); }
    .option-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .option-card { display: block; min-width: 0; padding: 20px; border: 2px solid var(--line); border-radius: 14px; background: var(--paper); cursor: pointer; transition: border-color 160ms ease, background-color 160ms ease; }
    .option-card:hover { border-color: var(--brand); }
    .option-card:has(input:checked) { border-color: var(--brand); background: #E8ECE3; }
    .option-head { min-height: 44px; display: grid; grid-template-columns: 24px 28px minmax(0, 1fr) auto; gap: 8px; align-items: center; }
    .option-head input { width: 22px; height: 22px; margin: 0; accent-color: var(--brand); }
    .option-letter { color: var(--gold); font-weight: 800; }
    .option-name { color: var(--ink); font: 700 19px/1.35 var(--serif); }
    .recommendation { padding: 3px 7px; border: 1px solid var(--brand); border-radius: 2px; color: var(--brand); font-size: 12px; font-weight: 700; }
    .option-summary, .option-tradeoff { display: block; margin-top: 12px; }
    .option-tradeoff { color: var(--muted); font-size: 14px; }
    .option-tradeoff strong { color: var(--ink); }
    .note-label { display: block; margin: 24px 0 8px; color: var(--ink); font-weight: 700; }
    textarea { width: 100%; min-height: 96px; padding: 13px 14px; border: 1px solid #92988E; border-radius: 4px; background: white; color: var(--body); resize: vertical; }
    .save-note { margin: 8px 0 0; color: var(--muted); font-size: 13px; }

    .all-pages { margin-top: 20px; border: 1px solid var(--line); border-radius: 14px; background: var(--surface); overflow: hidden; }
    .all-pages summary { min-height: 52px; padding: 15px 20px; color: var(--ink); font-weight: 700; cursor: pointer; }
    .all-pages summary:hover { background: var(--paper-2); }
    .all-pages-body { padding: 0 20px 24px; }
    .page-case { padding: 28px 0; border-top: 1px solid var(--line); }
    .page-case-head { margin-bottom: 16px; display: flex; justify-content: space-between; gap: 20px; align-items: end; }
    .page-case-head h4 { margin: 0 0 4px; color: var(--ink); font: 700 21px/1.35 var(--serif); }
    .page-case-head code { color: var(--muted); overflow-wrap: anywhere; }
    .page-case-head > span { color: var(--muted); font-size: 13px; white-space: nowrap; }
    .shot-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .shot-card a { display: block; height: 360px; overflow: hidden; border: 1px solid var(--line); border-radius: 14px; background: var(--paper-2); }
    .shot-preview { width: 100%; height: 100%; object-fit: cover; object-position: top center; }
    .shot-card figcaption { display: flex; flex-direction: column; }
    .shot-card figcaption strong { color: var(--ink); }
    .mobile-shot-card a { height: 520px; }
    .mobile-shot { object-fit: cover; object-position: top center; }
    .mobile-case { background: var(--paper-2); margin-inline: -20px; padding-inline: 20px; }

    .button { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; padding: 9px 14px; border: 1px solid var(--brand); border-radius: 4px; background: var(--brand); color: white; font-weight: 700; cursor: pointer; text-decoration: none; }
    .button:hover { background: var(--brand-hover); color: white; }
    .button.secondary { background: var(--surface); color: var(--brand); }
    .button.secondary:hover { background: var(--paper-2); color: var(--brand-hover); }

    .summary-panel { scroll-margin-top: 18px; padding: 32px; border: 2px solid var(--brand); border-radius: 14px; background: var(--surface); }
    .summary-panel h2 { margin: 0; color: var(--ink); font: 700 34px/1.3 var(--serif); }
    .summary-panel > p { color: var(--muted); }
    .summary-actions { display: flex; flex-wrap: wrap; gap: 10px; margin: 18px 0; }
    .summary-output { margin: 0; padding: 20px; border-left: 4px solid var(--brand); background: var(--paper-2); white-space: pre-wrap; overflow-wrap: anywhere; font: 15px/1.75 var(--sans); }
    .copy-status { min-height: 1.6em; margin: 8px 0 0; color: var(--brand); font-weight: 700; }

    .footer { padding: 32px; background: var(--footer); color: #DCE4D8; }
    .footer-inner { max-width: var(--max); margin: 0 auto; }
    .footer strong { color: var(--footer-text); }

    @media (max-width: 1100px) {
      .masthead-inner { grid-template-columns: 1fr; gap: 32px; }
      .evidence-strip { grid-template-columns: repeat(3, 1fr); }
      .evidence-warning { grid-column: 1 / -1; }
      .workspace { grid-template-columns: 1fr; }
      .side { position: static; }
      .decision-nav ol { display: grid; grid-template-columns: repeat(3, 1fr); }
      .sheet-grid { grid-template-columns: 1fr; }
      .sheet-grid figure:last-child { max-width: 560px; }
    }

    @media (max-width: 760px) {
      html { scroll-behavior: auto; }
      .masthead-inner { padding: 44px 18px 36px; }
      .masthead h1 { font-size: 40px; }
      .lede { font-size: 16px; }
      .evidence-strip { padding: 0 18px; grid-template-columns: 1fr 1fr; }
      .evidence-warning { grid-column: 1 / -1; }
      .workspace { padding: 32px 18px 64px; gap: 32px; }
      .decision-nav ol { grid-template-columns: 1fr; }
      .intro-panel { padding: 22px 20px; }
      .family-section { margin-bottom: 52px; padding-bottom: 52px; }
      .family-head { grid-template-columns: 46px minmax(0, 1fr); gap: 12px; }
      .family-head h2 { font-size: 34px; overflow-wrap: anywhere; }
      .mission { font-size: 16px; }
      .comparison-block, .decision-fieldset, .summary-panel { padding: 20px; }
      .subhead-row { grid-template-columns: 1fr; gap: 12px; }
      .option-grid, .shot-grid { grid-template-columns: 1fr; }
      .option-head { grid-template-columns: 24px 28px minmax(0, 1fr); }
      .recommendation { grid-column: 3; justify-self: start; }
      .page-case-head { align-items: start; flex-direction: column; gap: 6px; }
      .shot-card a { height: 420px; }
      .mobile-shot-card a { height: 540px; }
      .summary-actions { display: grid; }
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
    }
  </style>
</head>
<body>
  <a class="skip-link" href="#main">跳到版型選擇</a>
  <header class="masthead">
    <div class="masthead-inner">
      <div>
        <p class="brandline">尊嚴農業・清朗田野 3.3.0・唯讀比較成果</p>
        <h1>全站版型選擇室</h1>
        <p class="lede">九組拍板題一次攤開。顏色、字體、字級、圓角、陰影與頁尾都已固定；這次只比較每頁的第一屏任務、資訊順序、網格與導覽關係。</p>
      </div>
      <div class="masthead-note">
        <strong>明早只需要做 9 個選擇</strong>
        <p>先看每組的桌機／手機同列比較，再勾 A、B 或 C。若要混搭，直接寫在該組備註；所有選擇只存在這個瀏覽器，不會寫入 WordPress。</p>
      </div>
    </div>
  </header>

  <section class="evidence-strip" aria-label="驗證摘要">
    <div class="evidence-item"><strong>9</strong><span>組版型拍板題</span></div>
    <div class="evidence-item"><strong>22</strong><span>個真實頁面／單篇案例</span></div>
    <div class="evidence-item"><strong>93／93</strong><span>瀏覽器案例通過</span></div>
    <div class="evidence-item evidence-warning"><strong>這是內容快照，不是永久筆數規格</strong><span>擷取 ${escapeHtml(capturedAt)}；當時為 ${snapshot.counts_at_capture.pages} Pages、${snapshot.counts_at_capture.posts} posts。影音匯入仍可能由另一個 Codex 更新。</span></div>
  </section>

  <div class="workspace">
    <aside class="side" aria-label="選擇進度與章節">
      <div class="progress-card">
        <h2><span id="selected-count">0</span>／${families.length} 已選</h2>
        <p id="remaining-text">還有 ${families.length} 組待選</p>
        <div class="progress-track" aria-hidden="true"><div class="progress-bar" id="progress-bar"></div></div>
      </div>
      <nav class="decision-nav" aria-label="九組版型選擇">
        <ol>${navItems}</ol>
      </nav>
      <div class="side-actions">
        <a class="button" href="#selection-summary">查看選擇摘要</a>
        <button class="button secondary" type="button" id="expand-all">展開全部實頁</button>
      </div>
    </aside>

    <main class="content" id="main">
      <section class="intro-panel">
        <h2>怎麼看，才不會又變成選色票</h2>
        <p>每組先比較「全頁節奏」：桌機最能看出三案差異，手機為了可讀性會共同收斂成單欄。需要核對某一頁時，再展開原始截圖；所有畫面都來自 LocalWP 真實內容，沒有補假文案或假圖片。</p>
        <p><strong>設計建議不是預設答案。</strong>沒有任何選項被預先勾選；重新開啟這個檔案時，已選內容會從本機儲存恢復。</p>
      </section>

      ${familySections}

      <section class="summary-panel" id="selection-summary">
        <p class="eyebrow">最後一步</p>
        <h2>選擇摘要</h2>
        <p>選完後按「複製摘要」，貼回對話就能接續實作。沒有選完也可以先複製，未選題目會清楚標示。</p>
        <div class="summary-actions">
          <button class="button" type="button" id="copy-summary">複製摘要</button>
          <button class="button secondary" type="button" id="clear-selection">清除本機選擇</button>
        </div>
        <pre class="summary-output" id="summary-output" tabindex="0"></pre>
        <p class="copy-status" id="copy-status" aria-live="polite"></p>
      </section>
    </main>
  </div>

  <footer class="footer">
    <div class="footer-inner"><strong>本檔是候選版型選擇工具，不是現行網站規格。</strong> 本次產出沒有修改佈景主題、WordPress 資料庫或媒體，也沒有執行 reset、clean、commit 或 push。</div>
  </footer>

  <script>
    (function () {
      'use strict';
      var storageKey = 'fwdignity-layout-selection-v1';
      var families = ${JSON.stringify(families.map((family) => ({ id: family.id, number: family.number, title: family.title, options: Object.fromEntries(options.map((option) => [option, family.options[option].name])) })))};
      var state = { choices: {}, notes: {} };

      function loadState() {
        try {
          var saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
          if (saved && typeof saved === 'object') {
            state.choices = saved.choices && typeof saved.choices === 'object' ? saved.choices : {};
            state.notes = saved.notes && typeof saved.notes === 'object' ? saved.notes : {};
          }
        } catch (error) {
          state = { choices: {}, notes: {} };
        }
      }

      function saveState() {
        localStorage.setItem(storageKey, JSON.stringify(state));
      }

      function buildSummary() {
        var lines = ['尊嚴農業全站版型拍板'];
        families.forEach(function (family) {
          var choice = state.choices[family.id];
          var note = (state.notes[family.id] || '').trim();
          var answer = choice ? choice + '｜' + family.options[choice] : '尚未選擇';
          if (note) answer += '（' + note + '）';
          lines.push(family.number + ' ' + family.title + '：' + answer);
        });
        lines.push('');
        lines.push('依清朗田野 3.3.0 固定色彩、字體、字級、幾何、陰影與頁尾；只拍板版面骨架。');
        return lines.join('\\n');
      }

      function updateUI() {
        var selected = 0;
        families.forEach(function (family) {
          var choice = state.choices[family.id] || '';
          var note = state.notes[family.id] || '';
          var radio = document.querySelector('input[data-family="' + family.id + '"][value="' + choice + '"]');
          if (radio) radio.checked = true;
          var textarea = document.querySelector('[data-note-family="' + family.id + '"]');
          if (textarea && textarea.value !== note) textarea.value = note;
          var nav = document.querySelector('[data-nav-family="' + family.id + '"]');
          var saved = document.getElementById('saved-' + family.id);
          if (choice) {
            selected += 1;
            nav.dataset.selected = 'true';
            nav.querySelector('b').textContent = choice;
            saved.textContent = '已自動保存在此瀏覽器：' + choice + (note.trim() ? '，含混合備註' : '');
          } else {
            nav.dataset.selected = 'false';
            nav.querySelector('b').textContent = note.trim() ? '有備註' : '未選';
            saved.textContent = note.trim() ? '備註已保存，尚未勾選骨架' : '尚未選擇';
          }
        });
        document.getElementById('selected-count').textContent = String(selected);
        document.getElementById('remaining-text').textContent = selected === families.length ? '九組都已選完' : '還有 ' + (families.length - selected) + ' 組待選';
        document.getElementById('progress-bar').style.width = Math.round(selected / families.length * 100) + '%';
        document.getElementById('summary-output').textContent = buildSummary();
      }

      loadState();
      updateUI();

      document.querySelectorAll('input[data-family]').forEach(function (input) {
        input.addEventListener('change', function () {
          state.choices[input.dataset.family] = input.value;
          saveState();
          updateUI();
        });
      });

      document.querySelectorAll('[data-note-family]').forEach(function (textarea) {
        textarea.addEventListener('input', function () {
          state.notes[textarea.dataset.noteFamily] = textarea.value;
          saveState();
          updateUI();
        });
      });

      document.getElementById('copy-summary').addEventListener('click', async function () {
        var text = buildSummary();
        var status = document.getElementById('copy-status');
        try {
          await navigator.clipboard.writeText(text);
          status.textContent = '摘要已複製，可以直接貼回對話。';
        } catch (error) {
          var helper = document.createElement('textarea');
          helper.value = text;
          helper.setAttribute('readonly', '');
          helper.style.position = 'fixed';
          helper.style.left = '-9999px';
          document.body.appendChild(helper);
          helper.select();
          var copied = document.execCommand('copy');
          helper.remove();
          status.textContent = copied ? '摘要已複製，可以直接貼回對話。' : '瀏覽器未允許自動複製；請直接選取下方摘要。';
        }
      });

      document.getElementById('clear-selection').addEventListener('click', function () {
        if (!window.confirm('只會清除這個瀏覽器內的九組選擇與備註，確定要清除嗎？')) return;
        localStorage.removeItem(storageKey);
        state = { choices: {}, notes: {} };
        document.querySelectorAll('input[data-family]').forEach(function (input) { input.checked = false; });
        document.querySelectorAll('[data-note-family]').forEach(function (textarea) { textarea.value = ''; });
        document.getElementById('copy-status').textContent = '本機選擇已清除。';
        updateUI();
      });

      document.getElementById('expand-all').addEventListener('click', function (event) {
        var details = Array.from(document.querySelectorAll('.all-pages'));
        var shouldOpen = details.some(function (item) { return !item.open; });
        details.forEach(function (item) { item.open = shouldOpen; });
        event.currentTarget.textContent = shouldOpen ? '收合全部實頁' : '展開全部實頁';
      });
    }());
  </script>
</body>
</html>`;

await writeFile(join(outputDir, 'index.html'), html, 'utf8');
console.log(`已建立：${join(outputDir, 'index.html')}`);
console.log(`家族：${families.length}；實頁：${families.reduce((sum, family) => sum + family.pages.length, 0)}；案例：${results.completed}/${results.totalExpected}`);
