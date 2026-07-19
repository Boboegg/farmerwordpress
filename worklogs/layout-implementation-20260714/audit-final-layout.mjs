import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { spawn } from 'node:child_process';

const baseUrl = 'https://fwdignity-local.local';
const projectRoot = '/Users/boboegg/farmerwordpress';
const artifactDir = path.join(projectRoot, 'output/playwright/layout-final-20260714');
const screenshotDir = path.join(artifactDir, 'screenshots');
const resultPath = path.join(artifactDir, 'results.json');
const chromeExecutable = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';

const pages = [
  { family: 'home', id: 'home', path: '/' },
  { family: 'news', id: 'news', path: '/thenews/' },
  { family: 'guide', id: 'guide', path: '/farming-guide/' },
  { family: 'field', id: 'field-learning', path: '/field-learning/' },
  { family: 'field', id: 'field-notes', path: '/field-notes/' },
  { family: 'field', id: 'people', path: '/people-who-farm/' },
  { family: 'field', id: 'farmer-study', path: '/farmer-study/' },
  { family: 'topics', id: 'topic-body', path: '/people-who-farm/body-and-farm-work/' },
  { family: 'topics', id: 'topic-change', path: '/people-who-farm/changing-farm-work/' },
  { family: 'topics', id: 'topic-support', path: '/people-who-farm/rural-support/' },
  { family: 'topics', id: 'topic-risk', path: '/people-who-farm/risk-and-responsibility/' },
  { family: 'topics', id: 'topic-knowledge', path: '/people-who-farm/knowledge-in-the-field/' },
  { family: 'media', id: 'podcast', path: '/podcast/' },
  { family: 'research', id: 'research', path: '/research-results/' },
  { family: 'research', id: 'publications', path: '/research-results/research-publications/' },
  { family: 'research', id: 'resources', path: '/research-results/related-resources/' },
  { family: 'research', id: 'initiative', path: '/dignity-farming-initiative/' },
  { family: 'about', id: 'about', path: '/about/' },
  { family: 'about', id: 'team', path: '/about/researchteam/' },
  { family: 'single', id: 'single-news', path: '/?p=1633' },
  { family: 'single', id: 'single-field', path: '/?p=1829' },
  { family: 'single', id: 'single-media', path: '/?p=2092' }
];

const cases = pages.flatMap((page) => [
  { ...page, mobile: false, viewport: { width: 1440, height: 1100 } },
  { ...page, mobile: true, viewport: { width: 390, height: 844 } }
]);

const delay = (milliseconds) => new Promise((resolve) => setTimeout(resolve, milliseconds));
const caseKey = (testCase) => `${testCase.family}--${testCase.id}--${testCase.mobile ? 'mobile' : 'desktop'}`;

fs.mkdirSync(screenshotDir, { recursive: true });

class CdpClient {
  constructor(url) {
    this.nextId = 1;
    this.pending = new Map();
    this.listeners = new Map();
    this.socket = new WebSocket(url);
    this.ready = new Promise((resolve, reject) => {
      this.socket.addEventListener('open', resolve, { once: true });
      this.socket.addEventListener('error', reject, { once: true });
    });
    this.socket.addEventListener('message', (event) => {
      const message = JSON.parse(event.data);
      if (message.id) {
        const pending = this.pending.get(message.id);
        if (!pending) return;
        this.pending.delete(message.id);
        if (message.error) pending.reject(new Error(`${pending.method}: ${message.error.message}`));
        else pending.resolve(message.result);
        return;
      }
      for (const handler of this.listeners.get(message.method) || []) handler(message.params);
    });
  }

  on(method, handler) {
    const handlers = this.listeners.get(method) || [];
    handlers.push(handler);
    this.listeners.set(method, handlers);
  }

  once(method, timeout = 30000) {
    return new Promise((resolve, reject) => {
      const timer = setTimeout(() => reject(new Error(`等待 ${method} 逾時`)), timeout);
      const handler = (params) => {
        clearTimeout(timer);
        this.listeners.set(method, (this.listeners.get(method) || []).filter((item) => item !== handler));
        resolve(params);
      };
      this.on(method, handler);
    });
  }

  async send(method, params = {}) {
    await this.ready;
    const id = this.nextId++;
    return new Promise((resolve, reject) => {
      this.pending.set(id, { resolve, reject, method });
      this.socket.send(JSON.stringify({ id, method, params }));
    });
  }

  close() {
    this.socket.close();
  }
}

async function waitForChrome(port, chrome) {
  const deadline = Date.now() + 20000;
  while (Date.now() < deadline) {
    if (chrome.exitCode !== null) throw new Error(`Chrome 提前結束：${chrome.exitCode}`);
    try {
      const response = await fetch(`http://127.0.0.1:${port}/json/version`);
      if (response.ok) return;
    } catch {}
    await delay(120);
  }
  throw new Error('Chrome DevTools endpoint 未就緒');
}

async function createTarget(port) {
  const response = await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' });
  if (!response.ok) throw new Error(`無法建立 Chrome target：${response.status}`);
  return response.json();
}

async function evaluate(client, expression, awaitPromise = false) {
  const response = await client.send('Runtime.evaluate', {
    expression,
    awaitPromise,
    returnByValue: true,
    userGesture: true
  });
  if (response.exceptionDetails) {
    const detail = response.exceptionDetails.exception?.description || response.exceptionDetails.text;
    throw new Error(`Runtime.evaluate 失敗：${detail}`);
  }
  return response.result.value;
}

const waitForVisualReady = `(async () => {
  if (document.fonts?.ready) await document.fonts.ready;
  document.querySelectorAll('img[loading="lazy"]').forEach((image) => { image.loading = 'eager'; });
  const fullHeight = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
  for (const ratio of [0, .3, .6, .9, 1]) {
    scrollTo(0, Math.max(0, Math.round((fullHeight - innerHeight) * ratio)));
    await new Promise((resolve) => setTimeout(resolve, 90));
  }
  scrollTo(0, 0);
  const pending = Array.from(document.images).filter((image) => !image.complete);
  await Promise.race([
    Promise.all(pending.map((image) => new Promise((resolve) => {
      image.addEventListener('load', resolve, { once: true });
      image.addEventListener('error', resolve, { once: true });
    }))),
    new Promise((resolve) => setTimeout(resolve, 4000))
  ]);
  await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
  return true;
})()`;

function auditExpression(testCase) {
  return `(() => {
    const css = (selector) => {
      const element = document.querySelector(selector);
      return element ? getComputedStyle(element) : null;
    };
    const rect = (selector) => {
      const element = document.querySelector(selector);
      if (!element) return null;
      const box = element.getBoundingClientRect();
      return { x:Math.round(box.x), y:Math.round(box.y), width:Math.round(box.width), height:Math.round(box.height) };
    };
    const columns = (selector) => {
      const value = css(selector)?.gridTemplateColumns || '';
      return { value, count:value && value !== 'none' ? value.trim().split(/\\s+/).length : 0 };
    };
    const stylesheetUrls = Array.from(document.styleSheets).map((sheet) => sheet.href).filter(Boolean);
    const brokenImages = Array.from(document.images)
      .filter((image) => image.complete && image.naturalWidth === 0)
      .map((image) => image.currentSrc || image.src);
    const overflowElements = Array.from(document.body.querySelectorAll('*')).map((element) => {
      const box = element.getBoundingClientRect();
      const style = getComputedStyle(element);
      if (style.display === 'none' || style.position === 'fixed') return null;
      const left = Math.max(0, -box.left);
      const right = Math.max(0, box.right - document.documentElement.clientWidth);
      if (left <= 1 && right <= 1) return null;
      return {
        element:element.tagName.toLowerCase() + (element.id ? '#' + element.id : '') + (typeof element.className === 'string' && element.className.trim() ? '.' + element.className.trim().split(/\\s+/).slice(0, 2).join('.') : ''),
        left:Math.round(left),
        right:Math.round(right),
        width:Math.round(box.width)
      };
    }).filter(Boolean).slice(0, 20);
    return {
      url:location.href,
      title:document.title,
      pageData:document.body.dataset.page || null,
      viewport:{ width:innerWidth, height:innerHeight },
      documentWidth:document.documentElement.scrollWidth,
      clientWidth:document.documentElement.clientWidth,
      documentHeight:Math.max(document.documentElement.scrollHeight, document.body.scrollHeight),
      overflow:document.documentElement.scrollWidth > document.documentElement.clientWidth,
      overflowElements,
      h1:document.querySelector('main h1')?.textContent.trim() || null,
      finalStylesheet:stylesheetUrls.find((url) => url.includes('site-v3-layout-final.css')) || null,
      footerBackground:css('.fw-footer')?.backgroundColor || null,
      brokenImages,
      home:{
        hero:rect('.fw-home-v04-hero'),
        heroRadius:css('.fw-home-v04-hero')?.borderRadius || null,
        weatherRadius:css('.fw-home-v06-weather')?.borderRadius || null,
        editorialDisplay:css('.fw-home-v06-editorial-news')?.display || null,
        editorial:columns('.fw-home-v06-editorial-news'),
        mediaBackground:css('.fw-home-v04-audio')?.backgroundColor || null
      },
      news:{
        workspaceDisplay:css('.tnews-workspace')?.display || null,
        workspace:columns('.tnews-workspace'),
        filterPosition:css('.tnews-filter-panel')?.position || null,
        gridDisplay:css('.tnews-grid')?.display || null,
        grid:columns('.tnews-grid'),
        firstCard:rect('.tnews-grid > .tnews-card:first-child')
      },
      guide:{
        quickNavDisplay:css('.fg-guide__quick-nav')?.display || null,
        taskGrid:columns('.fg-guide__task-grid'),
        taskCount:document.querySelectorAll('.fg-guide__task').length
      },
      field:{
        pathwaysDisplay:css('.field-family-pathways')?.display || null,
        featured:columns('.field-featured'),
        records:columns('.field-record-grid'),
        notes:columns('.field-notes-grid'),
        questions:columns('.pf-question-grid'),
        training:columns('.training-record-list')
      },
      topics:{
        heroDisplay:css('.pf-topic-hero')?.display || null,
        hero:columns('.pf-topic-hero'),
        feature:columns('.pf-topic-feature'),
        content:columns('.pf-content-list')
      },
      media:{
        featureDisplay:css('.mh-feature-slide')?.display || null,
        feature:columns('.mh-feature-slide'),
        podcast:columns('.mh-podcast-desk'),
        archiveDisplay:css('.mh-archive-list')?.display || null,
        archive:columns('.mh-archive-list')
      },
      research:{
        actions:columns('.ra-action-grid'),
        outputs:columns('.ra-output-grid'),
        mainWidth:rect('.rs-main')?.width || rect('.adv-main')?.width || null,
        timelineWidth:rect('.adv-timeline')?.width || null
      },
      about:{
        field:columns('.ab-field'),
        actions:columns('.ab-action-grid'),
        team:columns('.ab-team-grid'),
        subteam:columns('.as-team-grid')
      },
      single:{
        hasFeatured:Boolean(document.querySelector('.article-featured-image')),
        entryDisplay:css('.article-entry')?.display || null,
        entry:columns('.article-entry'),
        entryRect:rect('.article-entry'),
        contentRect:rect('.article-content')
      },
      expected:{ family:${JSON.stringify(testCase.family)}, id:${JSON.stringify(testCase.id)}, mobile:${JSON.stringify(testCase.mobile)} }
    };
  })()`;
}

function layoutFailures(testCase, audit) {
  const failures = [];
  if (!audit.finalStylesheet) failures.push('正式版型 CSS 未載入');
  if (!audit.h1) failures.push('缺少 H1');
  if (audit.overflow) failures.push('頁面水平溢出');
  if (audit.brokenImages.length) failures.push(`壞圖 ${audit.brokenImages.length}`);
  if (audit.footerBackground !== 'rgb(37, 51, 30)') failures.push(`頁尾色異常 ${audit.footerBackground}`);

  if (testCase.family === 'home') {
    if (audit.home.heroRadius !== '14px' || audit.home.weatherRadius !== '14px') failures.push('首頁圓潤外框未生效');
    if (!testCase.mobile && audit.home.editorial.count !== 2) failures.push('首頁桌機頭版不是雙欄');
    if (testCase.mobile && audit.home.editorialDisplay !== 'block') failures.push('首頁手機頭版未直排');
    if (audit.home.mediaBackground !== 'rgb(67, 88, 50)') failures.push('首頁深綠影音帶被改動');
  }

  if (testCase.family === 'news') {
    if (!testCase.mobile && audit.news.workspace.count !== 2) failures.push('最新消息桌機左側索引未生效');
    if (!testCase.mobile && audit.news.grid.count !== 12) failures.push('最新消息桌機頭條網格未生效');
    if (testCase.mobile && audit.news.workspaceDisplay !== 'block') failures.push('最新消息手機未直排');
  }

  if (testCase.family === 'guide') {
    if (audit.guide.quickNavDisplay !== 'none') failures.push('指南重複六入口仍顯示');
    if (!testCase.mobile && audit.guide.taskGrid.count !== 3) failures.push('指南桌機不是三欄六卡');
    if (testCase.mobile && audit.guide.taskGrid.count !== 1) failures.push('指南手機不是單欄');
  }

  if (testCase.family === 'field' && testCase.id === 'field-learning' && audit.field.pathwaysDisplay !== 'none') {
    failures.push('田野誌頂端三入口仍顯示');
  }

  if (testCase.family === 'topics') {
    if (!testCase.mobile && audit.topics.hero.count !== 2) failures.push('主題頁桌機問題專刊首屏未生效');
    if (testCase.mobile && audit.topics.heroDisplay !== 'block') failures.push('主題頁手機未直排');
  }

  if (testCase.family === 'media') {
    if (!testCase.mobile && audit.media.feature.count !== 2) failures.push('影音桌機主打單集未生效');
    if (!testCase.mobile && audit.media.archive.count !== 2) failures.push('影音桌機節目架未成雙欄');
    if (testCase.mobile && audit.media.archiveDisplay !== 'block') failures.push('影音手機節目架未直排');
  }

  if (testCase.family === 'research' && testCase.id === 'research') {
    if (!testCase.mobile && audit.research.actions.count !== 2) failures.push('研究到行動桌機路徑未生效');
    if (testCase.mobile && audit.research.actions.count !== 1) failures.push('研究到行動手機未直排');
  }

  if (testCase.family === 'about' && testCase.id === 'about') {
    if (!testCase.mobile && audit.about.field.count !== 2) failures.push('計畫敘事桌機雙欄未生效');
    if (testCase.mobile && audit.about.field.count !== 1) failures.push('計畫敘事手機未直排');
  }

  if (testCase.family === 'single' && audit.single.hasFeatured) {
    if (!testCase.mobile && audit.single.entryDisplay !== 'grid') failures.push('有真實圖片的單篇未套雜誌開場');
    if (!testCase.mobile && audit.single.entry.count !== 2) failures.push('單篇桌機雜誌開場不是雙欄');
    if (testCase.mobile && audit.single.entryDisplay !== 'block') failures.push('單篇手機未回到自然閱讀流');
  }
  return failures;
}

const port = 9550 + (process.pid % 200);
const profileDir = path.join(os.tmpdir(), `fwdignity-final-layout-${Date.now()}`);
fs.mkdirSync(profileDir, { recursive: true });
const chrome = spawn(chromeExecutable, [
  '--headless=new',
  `--remote-debugging-port=${port}`,
  `--user-data-dir=${profileDir}`,
  '--ignore-certificate-errors',
  '--allow-insecure-localhost',
  '--disable-background-networking',
  '--disable-component-update',
  '--disable-default-apps',
  '--disable-features=TranslateUI',
  '--disable-gpu',
  '--disable-sync',
  '--hide-scrollbars',
  '--no-first-run',
  '--no-default-browser-check',
  'about:blank'
], { stdio: ['ignore', 'ignore', 'pipe'] });

let stderr = '';
chrome.stderr.on('data', (chunk) => { stderr += chunk.toString(); });

let client;
const results = [];

try {
  await waitForChrome(port, chrome);
  const target = await createTarget(port);
  client = new CdpClient(target.webSocketDebuggerUrl);
  await client.ready;
  await Promise.all([
    client.send('Page.enable'),
    client.send('Runtime.enable'),
    client.send('Network.enable')
  ]);

  let activeErrors = [];
  let activeConsoleErrors = [];
  let documentStatus = null;
  client.on('Runtime.exceptionThrown', (params) => {
    activeErrors.push(params.exceptionDetails?.exception?.description || params.exceptionDetails?.text || '未知例外');
  });
  client.on('Runtime.consoleAPICalled', (params) => {
    if (params.type === 'error') activeConsoleErrors.push(params.args.map((arg) => arg.value || arg.description || '').join(' '));
  });
  client.on('Network.responseReceived', (params) => {
    if (params.type === 'Document' && params.response.url.startsWith(baseUrl)) documentStatus = params.response.status;
  });

  console.log(`全站版型驗收開始：${cases.length} 案`);
  for (let index = 0; index < cases.length; index += 1) {
    const testCase = cases[index];
    const key = caseKey(testCase);
    const startedAt = Date.now();
    activeErrors = [];
    activeConsoleErrors = [];
    documentStatus = null;
    try {
      await client.send('Emulation.setDeviceMetricsOverride', {
        width: testCase.viewport.width,
        height: testCase.viewport.height,
        deviceScaleFactor: 1,
        mobile: testCase.mobile,
        screenWidth: testCase.viewport.width,
        screenHeight: testCase.viewport.height
      });
      const loaded = client.once('Page.loadEventFired');
      const navigation = await client.send('Page.navigate', { url: baseUrl + testCase.path });
      if (navigation.errorText) throw new Error(navigation.errorText);
      await loaded;
      await evaluate(client, waitForVisualReady, true);
      const audit = await evaluate(client, auditExpression(testCase));
      const failures = [];
      if (documentStatus !== 200) failures.push(`HTTP ${documentStatus}`);
      if (activeErrors.length) failures.push(`runtime error ${activeErrors.length}`);
      failures.push(...layoutFailures(testCase, audit));

      const metrics = await client.send('Page.getLayoutMetrics');
      const fullHeight = Math.ceil(metrics.cssContentSize?.height || audit.documentHeight || testCase.viewport.height);
      const captureHeight = Math.min(fullHeight, 30000);
      const screenshotPath = path.join(screenshotDir, `${key}.jpg`);
      const screenshot = await client.send('Page.captureScreenshot', {
        format: 'jpeg',
        quality: 88,
        fromSurface: true,
        captureBeyondViewport: true,
        clip: { x: 0, y: 0, width: testCase.viewport.width, height: captureHeight, scale: 1 }
      });
      fs.writeFileSync(screenshotPath, Buffer.from(screenshot.data, 'base64'));
      if (fullHeight > captureHeight) failures.push('長頁超過 30000px，截圖已裁切');

      const result = {
        key,
        ...testCase,
        documentStatus,
        fullHeight,
        captureHeight,
        screenshot:path.relative(artifactDir, screenshotPath),
        audit,
        runtimeErrors:activeErrors,
        consoleErrors:activeConsoleErrors,
        failures,
        passed:failures.length === 0,
        durationMs:Date.now() - startedAt
      };
      results.push(result);
      console.log(`${result.passed ? 'PASS' : 'FAIL'} ${index + 1}/${cases.length} ${key}${failures.length ? `｜${failures.join('；')}` : ''}`);
    } catch (error) {
      const result = {
        key,
        ...testCase,
        documentStatus,
        runtimeErrors:activeErrors,
        consoleErrors:activeConsoleErrors,
        failures:[String(error.stack || error)],
        passed:false,
        durationMs:Date.now() - startedAt
      };
      results.push(result);
      console.log(`FAIL ${index + 1}/${cases.length} ${key}｜${result.failures[0]}`);
    }
    fs.writeFileSync(resultPath, JSON.stringify({
      generatedAt:new Date().toISOString(),
      totalExpected:cases.length,
      completed:results.length,
      failureCount:results.filter((item) => !item.passed).length,
      passed:results.length === cases.length && results.every((item) => item.passed),
      cases:results
    }, null, 2) + '\n');
  }
} finally {
  if (client) client.close();
  if (chrome.exitCode === null) chrome.kill('SIGTERM');
  await Promise.race([new Promise((resolve) => chrome.once('exit', resolve)), delay(1500)]);
  if (chrome.exitCode === null) chrome.kill('SIGKILL');
  fs.rmSync(profileDir, { recursive: true, force: true });
}

const failures = results.filter((item) => !item.passed);
console.log(`SUMMARY ${JSON.stringify({ total:cases.length, completed:results.length, passed:failures.length === 0, failures:failures.length })}`);
if (failures.length) {
  console.log(`FAILED_KEYS ${failures.map((item) => item.key).join(',')}`);
  process.exitCode = 1;
}
