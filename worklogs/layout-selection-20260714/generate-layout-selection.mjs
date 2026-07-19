import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { spawn } from 'node:child_process';
import { BASE_URL, allCases, caseKey } from './layout-config.mjs';

const projectRoot = '/Users/boboegg/farmerwordpress';
const worklogDir = path.join(projectRoot, 'worklogs/layout-selection-20260714');
const artifactDir = path.join(projectRoot, 'output/playwright/layout-selection-20260714');
const screenshotDir = path.join(artifactDir, 'screenshots');
const smoke = process.argv.includes('--smoke');
const onlyArg = process.argv.find((argument) => argument.startsWith('--only='));
const onlyKey = onlyArg ? onlyArg.slice('--only='.length) : '';
const resultPath = path.join(artifactDir, smoke ? 'smoke-results.json' : onlyKey ? 'diagnostic-' + onlyKey + '.json' : 'results.json');
const cssPath = path.join(worklogDir, 'layout-variants.css');
const chromeExecutable = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const resume = process.argv.includes('--resume');
const casesToRun = smoke ? [allCases[0]] : onlyKey ? allCases.filter((item) => caseKey(item) === onlyKey) : allCases;

if (!casesToRun.length) {
  throw new Error('找不到指定案例：' + onlyKey);
}

fs.mkdirSync(screenshotDir, { recursive: true });

if (!fs.existsSync(chromeExecutable)) {
  throw new Error('找不到系統 Chrome：' + chromeExecutable);
}
if (!fs.existsSync(cssPath)) {
  throw new Error('找不到候選 CSS：' + cssPath);
}

const cssText = fs.readFileSync(cssPath, 'utf8');
const delay = (milliseconds) => new Promise((resolve) => setTimeout(resolve, milliseconds));

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
        if (message.error) pending.reject(new Error(pending.method + ': ' + message.error.message));
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
      const timer = setTimeout(() => reject(new Error('等待 ' + method + ' 逾時')), timeout);
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

async function waitForChrome(port, process) {
  const deadline = Date.now() + 20000;
  while (Date.now() < deadline) {
    if (process.exitCode !== null) throw new Error('Chrome 提前結束：' + process.exitCode);
    try {
      const response = await fetch('http://127.0.0.1:' + port + '/json/version');
      if (response.ok) return;
    } catch {}
    await delay(120);
  }
  throw new Error('Chrome DevTools endpoint 未就緒');
}

async function createTarget(port) {
  const response = await fetch('http://127.0.0.1:' + port + '/json/new?about:blank', { method: 'PUT' });
  if (!response.ok) throw new Error('無法建立 Chrome target：' + response.status);
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
    throw new Error('Runtime.evaluate 失敗：' + detail);
  }
  return response.result.value;
}

function previewSetupExpression(testCase) {
  const previewKey = testCase.family + '-' + testCase.option.toLowerCase();
  return `(() => {
    document.documentElement.dataset.layoutPreview = ${JSON.stringify(previewKey)};
    let style = document.getElementById('layout-preview-css');
    if (!style) {
      style = document.createElement('style');
      style.id = 'layout-preview-css';
      document.head.appendChild(style);
    }
    style.textContent = ${JSON.stringify(cssText)};

    document.querySelectorAll('img[loading="lazy"]').forEach((image) => {
      image.loading = 'eager';
    });

    if (${JSON.stringify(previewKey)} === 'single-b') {
      const article = document.querySelector('.article-entry');
      const content = article?.querySelector('.article-content');
      const headings = content ? Array.from(content.querySelectorAll('h2, h3')).filter((heading) => heading.textContent.trim()) : [];
      if (article && content && headings.length) {
        const nav = document.createElement('nav');
        nav.className = 'layout-preview-toc';
        nav.setAttribute('aria-label', '本頁目錄');
        const title = document.createElement('strong');
        title.textContent = '本頁目錄';
        nav.appendChild(title);
        headings.forEach((heading, index) => {
          if (!heading.id) heading.id = 'layout-preview-heading-' + (index + 1);
          const link = document.createElement('a');
          link.href = '#' + heading.id;
          link.textContent = heading.textContent.trim();
          if (heading.tagName === 'H3') link.style.paddingInlineStart = '14px';
          nav.appendChild(link);
        });
        article.insertBefore(nav, content);
        article.classList.add('has-layout-preview-toc');
      }
    }

    return {
      previewKey: document.documentElement.dataset.layoutPreview,
      hasStyle: Boolean(document.getElementById('layout-preview-css')),
      tocLinks: document.querySelectorAll('.layout-preview-toc a').length
    };
  })()`;
}

const waitForVisualReady = `(async () => {
  if (document.fonts?.ready) await document.fonts.ready;
  const scrollHeight = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
  const step = Math.max(500, Math.floor(innerHeight * .8));
  for (let y = 0; y < scrollHeight; y += step) {
    scrollTo(0, y);
    await new Promise((resolve) => setTimeout(resolve, 70));
  }
  scrollTo(0, 0);
  const pending = Array.from(document.images).filter((image) => !image.complete);
  await Promise.race([
    Promise.all(pending.map((image) => new Promise((resolve) => {
      image.addEventListener('load', resolve, { once: true });
      image.addEventListener('error', resolve, { once: true });
    }))),
    new Promise((resolve) => setTimeout(resolve, 5000))
  ]);
  await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
  return true;
})()`;

function auditExpression(testCase) {
  const previewKey = testCase.family + '-' + testCase.option.toLowerCase();
  return `(() => {
    const css = (element) => element ? getComputedStyle(element) : null;
    const rect = (element) => element ? element.getBoundingClientRect() : null;
    const px = (value) => Number.parseFloat(value || '0');
    const visible = (element) => {
      const style = css(element);
      const box = rect(element);
      return Boolean(style && box && style.display !== 'none' && style.visibility !== 'hidden' && Number(style.opacity) > 0 && box.width > 0 && box.height > 0);
    };
    const directText = (element) => Array.from(element.childNodes).some((node) => node.nodeType === Node.TEXT_NODE && node.textContent.trim());
    const textElements = Array.from(document.body.querySelectorAll('*')).filter((element) => visible(element) && directText(element));
    const textSizes = textElements
      .map((element) => ({ text: element.textContent.trim().slice(0, 60), size: px(css(element).fontSize) }))
      .filter((item) => item.size > 0)
      .sort((a, b) => a.size - b.size);
    const controls = Array.from(document.querySelectorAll('button, select, textarea, input:not([type="checkbox"]):not([type="radio"]), a[class*="button"], a[class*="btn"]')).filter(visible);
    const smallControls = controls
      .map((element) => ({
        label: (element.getAttribute('aria-label') || element.textContent || element.getAttribute('placeholder') || element.tagName).trim().slice(0, 70),
        width: rect(element).width,
        height: rect(element).height,
        tag: element.tagName.toLowerCase(),
        classes: typeof element.className === 'string' ? element.className : ''
      }))
      .filter((item) => item.height > 0 && item.height < 43.5 && item.width > 20)
      .slice(0, 30);
    const brokenImages = Array.from(document.images)
      .filter((image) => image.complete && image.naturalWidth === 0)
      .map((image) => image.currentSrc || image.src);
    const imagesWithoutAlt = Array.from(document.querySelectorAll('main img'))
      .filter((image) => !image.hasAttribute('alt'))
      .map((image) => image.currentSrc || image.src);
    const overflowElements = Array.from(document.body.querySelectorAll('*'))
      .filter(visible)
      .map((element) => {
        const box = rect(element);
        const overflowLeft = Math.max(0, -box.left);
        const overflowRight = Math.max(0, box.right - document.documentElement.clientWidth);
        return {
          element: element.tagName.toLowerCase() + (element.id ? '#' + element.id : '') + (typeof element.className === 'string' && element.className.trim() ? '.' + element.className.trim().split(/\s+/).slice(0, 3).join('.') : ''),
          left: Math.round(box.left),
          right: Math.round(box.right),
          width: Math.round(box.width),
          overflowLeft: Math.round(overflowLeft),
          overflowRight: Math.round(overflowRight),
          display: css(element).display,
          position: css(element).position,
          minWidth: css(element).minWidth,
          maxWidth: css(element).maxWidth
        };
      })
      .filter((item) => item.overflowLeft > 1 || item.overflowRight > 1 || item.width > document.documentElement.clientWidth + 1)
      .sort((a, b) => (b.overflowLeft + b.overflowRight) - (a.overflowLeft + a.overflowRight))
      .slice(0, 40);
    const main = document.querySelector('main');
    const h1 = document.querySelector('main h1');
    const signatureCandidates = {
      home: ['.fw-home-v04', '.fw-home-v06-editorial-news', '.fw-home-v04-hero'],
      news: ['.tnews-grid', '.tnews-workspace', '.tnews-card'],
      guide: ['.fg-guide', '.fg-guide__task-grid', '.fg-guide__task'],
      field: ['.field-featured', '.field-notes-grid', '.pf-question-grid', '.training-record-list'],
      topics: ['.pf-topic-page', '.pf-topic-hero', '.pf-content-list'],
      media: ['.mh-feature-stage', '.mh-archive-list', '.mh-archive-item'],
      research: ['.ra-page', '.rs-main', '.adv-main'],
      about: ['.ab-main', '.as-main', '.ab-team-grid', '.as-team-grid'],
      single: ['.article-entry', '.article-content', '.layout-preview-toc']
    };
    const signature = (signatureCandidates[${JSON.stringify(testCase.family)}] || [])
      .map((selector) => {
        const element = document.querySelector(selector);
        if (!element) return null;
        const style = css(element);
        const box = rect(element);
        return {
          selector,
          display: style.display,
          gridTemplateColumns: style.gridTemplateColumns,
          position: style.position,
          width: Math.round(box.width),
          height: Math.round(box.height)
        };
      })
      .filter(Boolean);
    return {
      expectedPreviewKey: ${JSON.stringify(previewKey)},
      previewKey: document.documentElement.dataset.layoutPreview || null,
      stylePresent: Boolean(document.getElementById('layout-preview-css')),
      url: location.href,
      title: document.title,
      viewport: { width: innerWidth, height: innerHeight },
      documentWidth: document.documentElement.scrollWidth,
      clientWidth: document.documentElement.clientWidth,
      documentHeight: Math.max(document.documentElement.scrollHeight, document.body.scrollHeight),
      overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth,
      bodyBackground: css(document.body)?.backgroundColor || null,
      main: main ? { width: Math.round(rect(main).width), height: Math.round(rect(main).height) } : null,
      h1: h1 ? { text: h1.textContent.trim(), size: css(h1).fontSize, lineHeight: css(h1).lineHeight, family: css(h1).fontFamily } : null,
      minimumText: textSizes[0] || null,
      smallControls,
      brokenImages,
      imagesWithoutAlt,
      overflowElements,
      tocLinks: document.querySelectorAll('.layout-preview-toc a').length,
      signature
    };
  })()`;
}

async function stopChrome(chrome, profileDir) {
  if (chrome.exitCode === null) chrome.kill('SIGTERM');
  await Promise.race([
    new Promise((resolve) => chrome.once('exit', resolve)),
    delay(1500)
  ]);
  if (chrome.exitCode === null) chrome.kill('SIGKILL');
  fs.rmSync(profileDir, { recursive: true, force: true });
}

let counter = 0;

async function runCase(testCase) {
  counter += 1;
  const key = caseKey(testCase);
  const previewKey = testCase.family + '-' + testCase.option.toLowerCase();
  const screenshotPath = path.join(screenshotDir, key + '.jpg');
  const port = 9400 + ((process.pid + counter * 7) % 400);
  const profileDir = path.join(os.tmpdir(), 'fwdignity-layout-' + Date.now() + '-' + counter);
  fs.mkdirSync(profileDir, { recursive: true });

  const chrome = spawn(chromeExecutable, [
    '--headless=new',
    '--remote-debugging-port=' + port,
    '--user-data-dir=' + profileDir,
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
  let documentStatus = null;
  const runtimeErrors = [];
  const consoleErrors = [];
  const startedAt = Date.now();

  try {
    await waitForChrome(port, chrome);
    const target = await createTarget(port);
    client = new CdpClient(target.webSocketDebuggerUrl);
    await client.ready;

    client.on('Runtime.exceptionThrown', (params) => {
      runtimeErrors.push(params.exceptionDetails?.exception?.description || params.exceptionDetails?.text || '未知例外');
    });
    client.on('Runtime.consoleAPICalled', (params) => {
      if (params.type === 'error') {
        consoleErrors.push(params.args.map((arg) => arg.value || arg.description || '').join(' '));
      }
    });
    client.on('Network.responseReceived', (params) => {
      if (params.type === 'Document' && params.response.url.startsWith(BASE_URL)) {
        documentStatus = params.response.status;
      }
    });

    await Promise.all([
      client.send('Page.enable'),
      client.send('Runtime.enable'),
      client.send('Network.enable')
    ]);

    await client.send('Emulation.setDeviceMetricsOverride', {
      width: testCase.viewport.width,
      height: testCase.viewport.height,
      deviceScaleFactor: 1,
      mobile: testCase.mobile,
      screenWidth: testCase.viewport.width,
      screenHeight: testCase.viewport.height
    });

    const loaded = client.once('Page.loadEventFired');
    const navigation = await client.send('Page.navigate', { url: BASE_URL + testCase.path });
    if (navigation.errorText) throw new Error(navigation.errorText);
    await loaded;

    const setup = await evaluate(client, previewSetupExpression(testCase));
    await evaluate(client, waitForVisualReady, true);
    const audit = await evaluate(client, auditExpression(testCase));

    const metrics = await client.send('Page.getLayoutMetrics');
    const fullHeight = Math.ceil(metrics.cssContentSize?.height || audit.documentHeight || testCase.viewport.height);
    const captureHeight = Math.min(fullHeight, 30000);
    const screenshot = await client.send('Page.captureScreenshot', {
      format: 'jpeg',
      quality: 88,
      fromSurface: true,
      captureBeyondViewport: true,
      clip: {
        x: 0,
        y: 0,
        width: testCase.viewport.width,
        height: captureHeight,
        scale: 1
      }
    });
    fs.writeFileSync(screenshotPath, Buffer.from(screenshot.data, 'base64'));

    const failures = [];
    if (documentStatus !== 200) failures.push('HTTP ' + documentStatus);
    if (audit.previewKey !== previewKey || !audit.stylePresent || !setup.hasStyle) failures.push('候選 CSS 未生效');
    if (audit.overflow) failures.push('水平溢出');
    if (audit.brokenImages.length) failures.push('壞圖 ' + audit.brokenImages.length);
    if (runtimeErrors.length) failures.push('runtime error ' + runtimeErrors.length);
    if (consoleErrors.length) failures.push('console error ' + consoleErrors.length);
    if (audit.minimumText && audit.minimumText.size < 14.9) failures.push('可見文字小於 15px');
    if (audit.smallControls.length) failures.push('主要控制小於 44px：' + audit.smallControls.length);
    if (!audit.h1) failures.push('缺少 H1');
    if (!audit.signature.length) failures.push('找不到頁型 signature');
    if (fullHeight > captureHeight) failures.push('長頁超過 30000px，截圖已裁切');

    return {
      key,
      family: testCase.family,
      option: testCase.option,
      pageId: testCase.pageId,
      pageTitle: testCase.title,
      path: testCase.path,
      mobile: testCase.mobile,
      viewport: testCase.viewport,
      screenshot: path.relative(artifactDir, screenshotPath),
      screenshotBytes: fs.statSync(screenshotPath).size,
      fullHeight,
      captureHeight,
      documentStatus,
      setup,
      audit,
      runtimeErrors,
      consoleErrors,
      failures,
      passed: failures.length === 0,
      durationMs: Date.now() - startedAt
    };
  } catch (error) {
    return {
      key,
      family: testCase.family,
      option: testCase.option,
      pageId: testCase.pageId,
      pageTitle: testCase.title,
      path: testCase.path,
      mobile: testCase.mobile,
      viewport: testCase.viewport,
      screenshot: null,
      documentStatus,
      runtimeErrors,
      consoleErrors,
      failures: [String(error.stack || error)],
      passed: false,
      chromeStderr: stderr.slice(-4000),
      durationMs: Date.now() - startedAt
    };
  } finally {
    if (client) client.close();
    await stopChrome(chrome, profileDir);
  }
}

const previous = resume && fs.existsSync(resultPath)
  ? JSON.parse(fs.readFileSync(resultPath, 'utf8')).cases || []
  : [];
const resultMap = new Map(previous.map((item) => [item.key, item]));
const startedAt = new Date().toISOString();

console.log('版型擷取開始：' + casesToRun.length + ' 案；resume=' + resume + '；smoke=' + smoke);

for (let index = 0; index < casesToRun.length; index += 1) {
  const testCase = casesToRun[index];
  const key = caseKey(testCase);
  if (resume && resultMap.get(key)?.passed && resultMap.get(key)?.screenshot && fs.existsSync(path.join(artifactDir, resultMap.get(key).screenshot))) {
    console.log('SKIP ' + (index + 1) + '/' + casesToRun.length + ' ' + key);
    continue;
  }
  const result = await runCase(testCase);
  resultMap.set(key, result);
  const cases = casesToRun.map((item) => resultMap.get(caseKey(item))).filter(Boolean);
  const failures = cases.filter((item) => !item.passed);
  fs.writeFileSync(resultPath, JSON.stringify({
    generatedAt: new Date().toISOString(),
    startedAt,
    totalExpected: casesToRun.length,
    completed: cases.length,
    passed: failures.length === 0 && cases.length === casesToRun.length,
    failureCount: failures.length,
    cases
  }, null, 2) + '\n');
  console.log((result.passed ? 'PASS ' : 'FAIL ') + (index + 1) + '/' + casesToRun.length + ' ' + key + (result.failures.length ? '｜' + result.failures.join('；') : ''));
}

const finalCases = casesToRun.map((item) => resultMap.get(caseKey(item))).filter(Boolean);
const finalFailures = finalCases.filter((item) => !item.passed);
const summary = {
  generatedAt: new Date().toISOString(),
  startedAt,
  totalExpected: casesToRun.length,
  completed: finalCases.length,
  passed: finalFailures.length === 0 && finalCases.length === casesToRun.length,
  failureCount: finalFailures.length,
  cases: finalCases
};
fs.writeFileSync(resultPath, JSON.stringify(summary, null, 2) + '\n');
console.log('SUMMARY ' + JSON.stringify({ total: summary.totalExpected, completed: summary.completed, passed: summary.passed, failures: summary.failureCount }));
process.exitCode = summary.passed ? 0 : 1;
