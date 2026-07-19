import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { pathToFileURL } from 'node:url';
import { spawn } from 'node:child_process';

const projectRoot = '/Users/boboegg/farmerwordpress';
const artifactDir = path.join(projectRoot, 'output/playwright/layout-selection-20260714');
const indexPath = path.join(artifactDir, 'index.html');
const auditDir = path.join(artifactDir, 'selection-room-audit');
const resultPath = path.join(auditDir, 'results.json');
const chromeExecutable = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const pageUrl = pathToFileURL(indexPath).href;
const delay = (milliseconds) => new Promise((resolve) => setTimeout(resolve, milliseconds));

fs.mkdirSync(auditDir, { recursive: true });
if (!fs.existsSync(indexPath)) throw new Error('找不到選擇室：' + indexPath);
if (!fs.existsSync(chromeExecutable)) throw new Error('找不到系統 Chrome：' + chromeExecutable);

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

async function waitForChrome(port, chrome) {
  const deadline = Date.now() + 20000;
  while (Date.now() < deadline) {
    if (chrome.exitCode !== null) throw new Error('Chrome 提前結束：' + chrome.exitCode);
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

async function navigate(client, url) {
  const loaded = client.once('Page.loadEventFired');
  const result = await client.send('Page.navigate', { url });
  if (result.errorText) throw new Error(result.errorText);
  await loaded;
  await evaluate(client, `(async () => {
    if (document.fonts?.ready) await document.fonts.ready;
    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
    return true;
  })()`, true);
}

const loadAllImages = `(async () => {
  document.querySelectorAll('details.all-pages').forEach((item) => { item.open = true; });
  document.querySelectorAll('img').forEach((image) => { image.loading = 'eager'; });
  let previousHeight = 0;
  for (let round = 0; round < 3; round += 1) {
    const height = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
    const step = Math.max(600, Math.floor(innerHeight * .85));
    for (let y = 0; y < height; y += step) {
      scrollTo(0, y);
      await new Promise((resolve) => setTimeout(resolve, 24));
    }
    if (height === previousHeight) break;
    previousHeight = height;
  }
  const pending = Array.from(document.images).filter((image) => !image.complete);
  await Promise.race([
    Promise.all(pending.map((image) => new Promise((resolve) => {
      image.addEventListener('load', resolve, { once: true });
      image.addEventListener('error', resolve, { once: true });
    }))),
    new Promise((resolve) => setTimeout(resolve, 20000))
  ]);
  scrollTo(0, 0);
  await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
  return { height: document.documentElement.scrollHeight, images: document.images.length };
})()`;

const auditExpression = `(() => {
  const visible = (element) => {
    const style = getComputedStyle(element);
    const box = element.getBoundingClientRect();
    return style.display !== 'none' && style.visibility !== 'hidden' && Number(style.opacity) > 0 && box.width > 0 && box.height > 0;
  };
  const controls = Array.from(document.querySelectorAll('button, a.button, .decision-nav a, .all-pages summary, .option-card')).filter(visible);
  const smallControls = controls.map((element) => {
    const box = element.getBoundingClientRect();
    return { label: (element.textContent || element.getAttribute('aria-label') || '').trim().slice(0, 80), width: Math.round(box.width), height: Math.round(box.height) };
  }).filter((item) => item.width < 44 || item.height < 44);
  const duplicateIds = Array.from(document.querySelectorAll('[id]')).map((element) => element.id).filter((id, index, ids) => ids.indexOf(id) !== index);
  const unnamedControls = Array.from(document.querySelectorAll('button, textarea, input')).filter((element) => {
    if (element.type === 'radio') return !element.closest('label');
    return !(element.getAttribute('aria-label') || element.getAttribute('aria-labelledby') || element.id && document.querySelector('label[for="' + CSS.escape(element.id) + '"]') || element.textContent.trim());
  }).map((element) => element.outerHTML.slice(0, 160));
  return {
    title: document.title,
    lang: document.documentElement.lang,
    viewport: { width: innerWidth, height: innerHeight },
    documentWidth: document.documentElement.scrollWidth,
    clientWidth: document.documentElement.clientWidth,
    documentHeight: document.documentElement.scrollHeight,
    overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth,
    sections: document.querySelectorAll('[data-family-section]').length,
    fieldsets: document.querySelectorAll('.decision-fieldset').length,
    radios: document.querySelectorAll('input[type="radio"][data-family]').length,
    checked: document.querySelectorAll('input[type="radio"][data-family]:checked').length,
    notes: document.querySelectorAll('[data-note-family]').length,
    contactImages: document.querySelectorAll('.sheet-grid img').length,
    sourceImages: document.querySelectorAll('.shot-card img').length,
    totalImages: document.images.length,
    brokenImages: Array.from(document.images).filter((image) => image.complete && image.naturalWidth === 0).map((image) => image.src),
    duplicateIds,
    unnamedControls,
    smallControls,
    h1Count: document.querySelectorAll('h1').length,
    summaryText: document.getElementById('summary-output')?.textContent || '',
    storage: localStorage.getItem('fwdignity-layout-selection-v1')
  };
})()`;

async function captureViewport(client, filename) {
  const metrics = await client.send('Page.getLayoutMetrics');
  const viewport = metrics.cssVisualViewport || metrics.visualViewport;
  const response = await client.send('Page.captureScreenshot', {
    format: 'jpeg',
    quality: 90,
    fromSurface: true,
    captureBeyondViewport: true,
    clip: {
      x: viewport.pageX,
      y: viewport.pageY,
      width: viewport.clientWidth,
      height: viewport.clientHeight,
      scale: 1
    }
  });
  const outputPath = path.join(auditDir, filename);
  fs.writeFileSync(outputPath, Buffer.from(response.data, 'base64'));
  return path.relative(artifactDir, outputPath);
}

async function stopChrome(chrome, profileDir) {
  if (chrome.exitCode === null) chrome.kill('SIGTERM');
  await Promise.race([new Promise((resolve) => chrome.once('exit', resolve)), delay(1500)]);
  if (chrome.exitCode === null) chrome.kill('SIGKILL');
  fs.rmSync(profileDir, { recursive: true, force: true });
}

async function runViewport(name, viewport) {
  const port = 9700 + ((process.pid + viewport.width) % 200);
  const profileDir = path.join(os.tmpdir(), 'fwdignity-selection-room-' + name + '-' + Date.now());
  fs.mkdirSync(profileDir, { recursive: true });
  const chrome = spawn(chromeExecutable, [
    '--headless=new',
    '--remote-debugging-port=' + port,
    '--user-data-dir=' + profileDir,
    '--allow-file-access-from-files',
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

  const runtimeErrors = [];
  const consoleErrors = [];
  let client;
  try {
    await waitForChrome(port, chrome);
    const target = await createTarget(port);
    client = new CdpClient(target.webSocketDebuggerUrl);
    await client.ready;
    client.on('Runtime.exceptionThrown', (params) => runtimeErrors.push(params.exceptionDetails?.exception?.description || params.exceptionDetails?.text || '未知例外'));
    client.on('Runtime.consoleAPICalled', (params) => {
      if (params.type === 'error') consoleErrors.push(params.args.map((arg) => arg.value || arg.description || '').join(' '));
    });
    await Promise.all([client.send('Page.enable'), client.send('Runtime.enable'), client.send('Network.enable')]);
    await client.send('Emulation.setDeviceMetricsOverride', {
      width: viewport.width,
      height: viewport.height,
      deviceScaleFactor: 1,
      mobile: viewport.mobile,
      screenWidth: viewport.width,
      screenHeight: viewport.height
    });

    await navigate(client, pageUrl);
    await evaluate(client, `localStorage.removeItem('fwdignity-layout-selection-v1'); location.reload()`);
    await client.once('Page.loadEventFired');
    const initial = await evaluate(client, auditExpression);
    const loadedImages = await evaluate(client, loadAllImages, true);
    const expanded = await evaluate(client, auditExpression);
    const topScreenshot = await captureViewport(client, 'selection-room-' + name + '-top.jpg');

    await evaluate(client, `(() => {
      const radio = document.querySelector('input[data-family="home"][value="B"]');
      radio.click();
      const note = document.querySelector('[data-note-family="home"]');
      note.value = '驗證保存測試';
      note.dispatchEvent(new Event('input', { bubbles: true }));
      return true;
    })()`);
    const afterInput = await evaluate(client, auditExpression);

    await navigate(client, pageUrl);
    const restored = await evaluate(client, `(() => ({
      choice: document.querySelector('input[data-family="home"]:checked')?.value || null,
      note: document.querySelector('[data-note-family="home"]')?.value || '',
      selectedCount: document.getElementById('selected-count')?.textContent || '',
      summary: document.getElementById('summary-output')?.textContent || ''
    }))()`);

    await client.send('Input.dispatchKeyEvent', { type: 'keyDown', key: 'Tab', code: 'Tab', windowsVirtualKeyCode: 9 });
    await client.send('Input.dispatchKeyEvent', { type: 'keyUp', key: 'Tab', code: 'Tab', windowsVirtualKeyCode: 9 });
    const focus = await evaluate(client, `(() => {
      const element = document.activeElement;
      const style = getComputedStyle(element);
      return { tag: element.tagName, text: element.textContent.trim().slice(0, 60), outlineWidth: style.outlineWidth, outlineStyle: style.outlineStyle };
    })()`);

    await evaluate(client, `document.getElementById('copy-summary').click()`);
    await delay(200);
    const copyStatus = await evaluate(client, `document.getElementById('copy-status').textContent`);
    await evaluate(client, `document.getElementById('selection-summary').scrollIntoView()`);
    await delay(100);
    const summaryScreenshot = await captureViewport(client, 'selection-room-' + name + '-summary.jpg');

    const failures = [];
    if (initial.checked !== 0) failures.push('初始狀態有預選');
    if (initial.sections !== 9 || initial.fieldsets !== 9) failures.push('九組結構數量錯誤');
    if (initial.radios !== 27 || initial.notes !== 9) failures.push('選擇控制數量錯誤');
    if (expanded.contactImages !== 18 || expanded.sourceImages !== 93 || expanded.totalImages !== 111) failures.push('圖片案例數量錯誤');
    if (expanded.brokenImages.length) failures.push('壞圖 ' + expanded.brokenImages.length);
    if (expanded.overflow) failures.push('水平溢出');
    if (expanded.duplicateIds.length) failures.push('重複 ID ' + expanded.duplicateIds.length);
    if (expanded.unnamedControls.length) failures.push('控制缺可存取名稱 ' + expanded.unnamedControls.length);
    if (expanded.smallControls.length) failures.push('主要控制小於 44px ' + expanded.smallControls.length);
    if (expanded.h1Count !== 1 || expanded.lang !== 'zh-Hant-TW') failures.push('文件語意錯誤');
    if (!afterInput.storage) failures.push('輸入後未寫入 localStorage');
    if (restored.choice !== 'B' || restored.note !== '驗證保存測試' || restored.selectedCount !== '1') failures.push('重新載入後未恢復選擇');
    if (!restored.summary.includes('01 首頁：B｜編輯頭版（驗證保存測試）')) failures.push('摘要內容錯誤');
    if (focus.tag === 'BODY' || Number.parseFloat(focus.outlineWidth) < 3 || focus.outlineStyle === 'none') failures.push('鍵盤焦點不可見');
    if (!copyStatus.includes('摘要已複製') && !copyStatus.includes('請直接選取')) failures.push('複製摘要沒有結果提示');
    if (runtimeErrors.length) failures.push('runtime error ' + runtimeErrors.length);
    if (consoleErrors.length) failures.push('console error ' + consoleErrors.length);

    return {
      name,
      viewport,
      initial,
      loadedImages,
      expanded,
      afterInput: { storage: afterInput.storage, checked: afterInput.checked },
      restored,
      focus,
      copyStatus,
      runtimeErrors,
      consoleErrors,
      screenshots: [topScreenshot, summaryScreenshot],
      failures,
      passed: failures.length === 0
    };
  } finally {
    if (client) client.close();
    await stopChrome(chrome, profileDir);
  }
}

const startedAt = new Date().toISOString();
const cases = [];
for (const [name, viewport] of [
  ['desktop', { width: 1440, height: 1000, mobile: false }],
  ['mobile', { width: 390, height: 844, mobile: true }]
]) {
  const result = await runViewport(name, viewport);
  cases.push(result);
  console.log((result.passed ? 'PASS ' : 'FAIL ') + name + (result.failures.length ? '｜' + result.failures.join('；') : ''));
}

const failures = cases.flatMap((item) => item.failures.map((failure) => item.name + '：' + failure));
const output = {
  generatedAt: new Date().toISOString(),
  startedAt,
  pageUrl,
  passed: failures.length === 0,
  failureCount: failures.length,
  failures,
  cases
};
fs.writeFileSync(resultPath, JSON.stringify(output, null, 2) + '\n');
console.log('SUMMARY ' + JSON.stringify({ passed: output.passed, failures: output.failureCount }));
process.exitCode = output.passed ? 0 : 1;
