import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { spawn } from 'node:child_process';

const chromeExecutable = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const baseUrl = 'https://fwdignity-local.local';
const projectRoot = '/Users/boboegg/farmerwordpress';
const artifactDir = path.join(projectRoot, 'output/playwright/clear-field-design-system-330/audit');
const resultPath = path.join(projectRoot, 'worklogs/clear-field-design-system-330/results.json');
fs.mkdirSync(artifactDir, { recursive: true });

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
        if (message.error) pending.reject(new Error(`${pending.method}: ${message.error.message}`));
        else pending.resolve(message.result);
        return;
      }
      (this.listeners.get(message.method) || []).forEach((handler) => handler(message.params));
    });
  }

  on(method, handler) {
    const handlers = this.listeners.get(method) || [];
    handlers.push(handler);
    this.listeners.set(method, handlers);
  }

  once(method, timeout = 25000) {
    return new Promise((resolve, reject) => {
      const timer = setTimeout(() => reject(new Error(`Timeout waiting for ${method}`)), timeout);
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
    if (process.exitCode !== null) throw new Error(`Chrome exited early with ${process.exitCode}`);
    try {
      const response = await fetch(`http://127.0.0.1:${port}/json/version`);
      if (response.ok) return;
    } catch {}
    await delay(120);
  }
  throw new Error('Chrome DevTools endpoint did not become ready');
}

async function createTarget(port) {
  const response = await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' });
  if (!response.ok) throw new Error(`Unable to create target: ${response.status}`);
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
    throw new Error(`Runtime.evaluate failed: ${detail}`);
  }
  return response.result.value;
}

const waitForVisualReady = `(async () => {
  if (document.fonts?.ready) await document.fonts.ready;
  const pendingImages = Array.from(document.images).filter((image) => !image.complete);
  await Promise.race([
    Promise.all(pendingImages.map((image) => new Promise((resolve) => {
      image.addEventListener('load', resolve, { once:true });
      image.addEventListener('error', resolve, { once:true });
    }))),
    new Promise((resolve) => setTimeout(resolve, 3000))
  ]);
  await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
  return true;
})()`;

const readerFunctionalAudit = `(async () => {
  const trigger = document.querySelector('.fw-reader-trigger');
  const panel = document.querySelector('.fw-reader-panel');
  if (!trigger || !panel) return { missing:true };
  localStorage.removeItem('fwdignity-reader-size');
  document.documentElement.dataset.fwReaderSize = 'standard';

  trigger.click();
  const opened = !panel.hidden && trigger.getAttribute('aria-expanded') === 'true';
  panel.querySelector('[data-reader-size="large"]').click();
  const largeApplied = document.documentElement.dataset.fwReaderSize === 'large';
  const stayedOpen = !panel.hidden;
  const largeStored = localStorage.getItem('fwdignity-reader-size') === 'large';

  document.dispatchEvent(new KeyboardEvent('keydown', { key:'Escape', bubbles:true }));
  const escapeClosed = panel.hidden && trigger.getAttribute('aria-expanded') === 'false';
  const escapeRestoredFocus = document.activeElement === trigger;

  trigger.click();
  document.body.dispatchEvent(new MouseEvent('click', { bubbles:true }));
  const outsideClosed = panel.hidden;

  trigger.click();
  panel.querySelector('[data-reader-size="xlarge"]').click();
  const xlargeApplied = document.documentElement.dataset.fwReaderSize === 'xlarge';
  const xlargeStored = localStorage.getItem('fwdignity-reader-size') === 'xlarge';
  return { opened, largeApplied, stayedOpen, largeStored, escapeClosed, escapeRestoredFocus, outsideClosed, xlargeApplied, xlargeStored, panelOpen:!panel.hidden };
})()`;

const pageAudit = `(() => {
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
  const textSizes = textElements.map((element) => ({
    selector: element.className ? '.' + String(element.className).trim().split(/\\s+/).join('.') : element.tagName.toLowerCase(),
    text: element.textContent.trim().slice(0, 50),
    size: px(css(element).fontSize)
  })).filter((item) => item.size > 0).sort((a, b) => a.size - b.size);
  const controls = Array.from(document.querySelectorAll('button, input, select, textarea, [role="button"]')).filter(visible);
  const controlHeights = controls.map((element) => ({ label:(element.getAttribute('aria-label') || element.textContent || element.tagName).trim().slice(0, 50), height:rect(element).height })).sort((a, b) => a.height - b.height);
  const allowedColors = new Set([
    'rgba(0, 0, 0, 0)',
    'rgb(253, 251, 247)', 'rgb(245, 245, 242)', 'rgb(255, 255, 255)', 'rgb(240, 240, 236)',
    'rgb(67, 88, 50)', 'rgb(49, 66, 37)', 'rgb(52, 69, 45)', 'rgb(40, 49, 33)',
    'rgb(86, 97, 85)', 'rgb(26, 26, 26)', 'rgb(74, 74, 74)',
    'rgb(165, 126, 38)', 'rgb(123, 93, 18)', 'rgb(224, 224, 224)',
    'rgb(247, 246, 241)', 'rgb(162, 74, 61)', 'rgb(37, 51, 30)', 'rgb(220, 228, 216)',
    'rgb(55, 110, 124)', 'rgb(232, 236, 227)', 'rgb(247, 238, 217)',
    'rgb(247, 231, 227)', 'rgb(115, 121, 112)'
  ]);
  const colorProperties = ['color', 'backgroundColor', 'borderTopColor', 'borderRightColor', 'borderBottomColor', 'borderLeftColor'];
  const unexpectedColorMap = new Map();
  Array.from(document.body.querySelectorAll('*')).filter(visible).forEach((element) => {
    const style = css(element);
    colorProperties.forEach((property) => {
      const value = style[property];
      if (!value || allowedColors.has(value) || value.startsWith('rgba(0, 0, 0, ')) return;
      const key = property + ':' + value;
      if (!unexpectedColorMap.has(key)) unexpectedColorMap.set(key, {
        element:element.tagName.toLowerCase() + (element.className ? '.' + String(element.className).trim().split(/\\s+/).slice(0, 2).join('.') : ''),
        property,
        value
      });
    });
  });
  const unexpectedWeights = textElements.filter((element) => !['400', '500', '700', '900'].includes(css(element).fontWeight)).slice(0, 20).map((element) => ({
    element:element.tagName.toLowerCase() + (element.className ? '.' + String(element.className).trim().split(/\\s+/).slice(0, 2).join('.') : ''),
    weight:css(element).fontWeight,
    text:element.textContent.trim().slice(0, 50)
  }));
  const nonBrand900 = textElements.filter((element) => css(element).fontWeight === '900' && !element.closest('.fw-logo, .fw-footer-logo')).slice(0, 20).map((element) => ({
    element:element.tagName.toLowerCase() + (element.className ? '.' + String(element.className).trim().split(/\\s+/).slice(0, 2).join('.') : ''),
    text:element.textContent.trim().slice(0, 50)
  }));
  const unexpectedFamilies = textElements.filter((element) => {
    const family = css(element).fontFamily;
    return !family.includes('Noto Sans TC') && !family.includes('Noto Serif TC');
  }).slice(0, 20).map((element) => ({
    element:element.tagName.toLowerCase() + (element.className ? '.' + String(element.className).trim().split(/\\s+/).slice(0, 2).join('.') : ''),
    family:css(element).fontFamily,
    text:element.textContent.trim().slice(0, 50)
  }));
  const h1 = document.querySelector('main h1');
  const h2 = document.querySelector('main h2');
  const articleContent = document.querySelector('.article-content');
  const trigger = document.querySelector('.fw-reader-trigger');
  const panel = document.querySelector('.fw-reader-panel');
  const bottomNav = Array.from(document.querySelectorAll('.bf-bottom-nav, [data-fixed-bottom-bar]')).find(visible);
  const triggerRect = rect(trigger);
  const panelRect = rect(panel);
  const navRect = rect(bottomNav);
  const overlaps = (one, two) => Boolean(one && two && !(one.right <= two.left || one.left >= two.right || one.bottom <= two.top || one.top >= two.bottom));
  const firstImage = Array.from(document.querySelectorAll('main img')).find(visible);
  const firstCard = document.querySelector('.tnews-card, .fw-home-v06-media-card, .mh-video-item, .pf-topic-preview, .fg-guide__task, .field-note, .rs-pub, .article-entry');
  const filterTool = document.querySelector('.tnews-filter-panel, .field-notes-filter-bar, .mh-filter-bar');
  const firstTag = document.querySelector('.tnews-card-tags span, .field-note-terms span, .article-topics a, .rs-pub-type');
  const firstControl = document.querySelector('main button, main input, main select, main textarea, main [class*="-btn"]');
  const homeBand = document.querySelector('.fw-home-v04-audio');
  const homeMediaCard = document.querySelector('.fw-home-v06-media-card');
  const navbar = document.querySelector('.fw-navbar');
  const footer = document.querySelector('.fw-footer');
  const stylesheetUrls = Array.from(document.styleSheets).map((sheet) => sheet.href).filter(Boolean);
  const scriptUrls = Array.from(document.scripts).map((script) => script.src).filter(Boolean);
  return {
    url:location.href,
    viewport:{ width:innerWidth, height:innerHeight },
    scrollY:window.scrollY,
    documentWidth:document.documentElement.scrollWidth,
    overflow:document.documentElement.scrollWidth > document.documentElement.clientWidth,
    pageBackground:css(document.body).backgroundColor,
    footerBackground:css(footer)?.backgroundColor || null,
    navbarHeight:rect(navbar)?.height || null,
    themeAssets:{
      tokens:stylesheetUrls.find((url) => url.includes('site-v2-tokens.css')) || null,
      foundation:stylesheetUrls.find((url) => url.includes('site-v2-page-foundation.css')) || null,
      reader:scriptUrls.find((url) => url.includes('reader-size-control.js')) || null
    },
    readerSize:document.documentElement.dataset.fwReaderSize || null,
    storedReaderSize:localStorage.getItem('fwdignity-reader-size'),
    h1:h1 ? { size:css(h1).fontSize, lineHeight:css(h1).lineHeight, weight:css(h1).fontWeight, family:css(h1).fontFamily } : null,
    h2:h2 ? { size:css(h2).fontSize, lineHeight:css(h2).lineHeight, weight:css(h2).fontWeight, family:css(h2).fontFamily } : null,
    article:articleContent ? { color:css(articleContent).color, size:css(articleContent).fontSize, lineHeight:css(articleContent).lineHeight, family:css(articleContent).fontFamily } : null,
    reader:trigger ? { width:css(trigger).width, height:css(trigger).height, radius:css(trigger).borderRadius, shadow:css(trigger).boxShadow, expanded:trigger.getAttribute('aria-expanded') } : null,
    panel:panel ? { hidden:panel.hidden, width:css(panel).width, radius:css(panel).borderRadius, shadow:css(panel).boxShadow } : null,
    bottomNav:bottomNav ? { height:navRect.height, gap:navRect.top - triggerRect.bottom, overlapsTrigger:overlaps(navRect, triggerRect), overlapsPanel:overlaps(navRect, panelRect) } : null,
    firstCard:firstCard ? { radius:css(firstCard).borderRadius, shadow:css(firstCard).boxShadow, transition:css(firstCard).transitionProperty } : null,
    filterTool:filterTool ? { radius:css(filterTool).borderRadius, shadow:css(filterTool).boxShadow } : null,
    firstTag:firstTag ? { radius:css(firstTag).borderRadius, background:css(firstTag).backgroundColor, borderLeft:css(firstTag).borderLeftColor + ' ' + css(firstTag).borderLeftWidth } : null,
    firstControl:firstControl ? { radius:css(firstControl).borderRadius, height:rect(firstControl).height } : null,
    firstImage:firstImage ? { filter:css(firstImage).filter, transform:css(firstImage).transform, transition:css(firstImage).transitionProperty } : null,
    homeBand:homeBand ? { background:css(homeBand).backgroundColor, marginTop:css(homeBand).marginTop, marginBottom:css(homeBand).marginBottom, paddingTop:css(homeBand).paddingTop, paddingBottom:css(homeBand).paddingBottom } : null,
    homeMediaCard:homeMediaCard ? { background:css(homeMediaCard).backgroundColor, radius:css(homeMediaCard).borderRadius, shadow:css(homeMediaCard).boxShadow } : null,
    minimumText:textSizes[0] || null,
    minimumControls:controlHeights.slice(0, 5),
    unexpectedColors:Array.from(unexpectedColorMap.values()),
    unexpectedWeights,
    nonBrand900,
    unexpectedFamilies,
    brokenImages:Array.from(document.images).filter((image) => image.complete && image.naturalWidth === 0).map((image) => image.currentSrc || image.src),
    revealHidden:Array.from(document.querySelectorAll('.reveal, [class*="reveal"]')).filter((element) => visible(element) && Number(css(element).opacity) < 1).length
  };
})()`;

const cases = [
  { name:'home-hero-desktop', path:'/', viewport:{ width:1440, height:1000 }, scroll:null },
  { name:'home-media-desktop', path:'/', viewport:{ width:1440, height:1000 }, scroll:'.fw-home-v04-audio', offset:-28 },
  { name:'home-footer-desktop', path:'/', viewport:{ width:1440, height:1000 }, scroll:'.fw-footer', offset:0 },
  { name:'news-desktop', path:'/thenews/', viewport:{ width:1440, height:1000 }, scroll:'.tnews-workspace', offset:-90 },
  { name:'podcast-desktop', path:'/podcast/', viewport:{ width:1440, height:1000 }, scroll:'.mh-feature-stage', offset:-90 },
  { name:'people-desktop', path:'/people-who-farm/', viewport:{ width:1440, height:1000 }, scroll:'.pf-hub', offset:0 },
  { name:'guide-desktop', path:'/farming-guide/', viewport:{ width:1440, height:1000 }, scroll:'.fg-guide__tasks', offset:-90 },
  { name:'field-notes-desktop', path:'/field-notes/', viewport:{ width:1440, height:1000 }, scroll:'.field-notes-page', offset:0 },
  { name:'research-tablet', path:'/research-results/', viewport:{ width:1025, height:1000 }, scroll:'.ra-page', offset:0 },
  { name:'article-desktop-reader', path:'/?p=1829', viewport:{ width:1440, height:1000 }, scroll:'.article-entry', offset:-100, functionalReader:true },
  { name:'article-mobile-reader', path:'/?p=1829', viewport:{ width:390, height:844 }, scroll:'.article-entry', offset:-72, functionalReader:true },
  { name:'condition-mobile-bottom-nav', path:'/thenews/', viewport:{ width:390, height:844 }, scroll:'.tnews-page', offset:0, openReader:true, injectBottomNav:true }
];

let counter = 0;

async function runCase(testCase) {
  counter += 1;
  const port = 9700 + ((process.pid + counter) % 250);
  const profileDir = path.join(os.tmpdir(), `fwdignity-330-audit-${Date.now()}-${counter}`);
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
  ], { stdio:['ignore', 'ignore', 'pipe'] });
  let stderr = '';
  chrome.stderr.on('data', (chunk) => { stderr += chunk.toString(); });
  let client;
  const runtimeErrors = [];
  const consoleErrors = [];
  let documentStatus = null;
  try {
    await waitForChrome(port, chrome);
    const target = await createTarget(port);
    client = new CdpClient(target.webSocketDebuggerUrl);
    await client.ready;
    client.on('Runtime.exceptionThrown', (params) => runtimeErrors.push(params.exceptionDetails?.exception?.description || params.exceptionDetails?.text || 'Unknown exception'));
    client.on('Runtime.consoleAPICalled', (params) => {
      if (params.type === 'error') consoleErrors.push(params.args.map((arg) => arg.value || arg.description || '').join(' '));
    });
    client.on('Network.responseReceived', (params) => {
      if (params.type === 'Document' && params.response.url.startsWith(baseUrl)) documentStatus = params.response.status;
    });
    await Promise.all([client.send('Page.enable'), client.send('Runtime.enable'), client.send('Network.enable')]);
    await client.send('Emulation.setDeviceMetricsOverride', {
      width:testCase.viewport.width,
      height:testCase.viewport.height,
      deviceScaleFactor:1,
      mobile:testCase.viewport.width < 600,
      screenWidth:testCase.viewport.width,
      screenHeight:testCase.viewport.height
    });
    const loaded = client.once('Page.loadEventFired');
    const navigation = await client.send('Page.navigate', { url:`${baseUrl}${testCase.path}` });
    if (navigation.errorText) throw new Error(navigation.errorText);
    await loaded;
    await evaluate(client, waitForVisualReady, true);

    if (testCase.injectBottomNav) {
      await evaluate(client, `(() => {
        const nav = document.createElement('nav');
        nav.className = 'bf-bottom-nav';
        nav.setAttribute('aria-label', '固定底部快捷列條件預演');
        Object.assign(nav.style, {
          position:'fixed', left:'0', right:'0', bottom:'0', height:'79px',
          display:'flex', background:'#FFFFFF', borderTop:'1px solid #E0E0E0', zIndex:'900'
        });
        nav.textContent = '固定底部快捷列條件預演';
        document.body.appendChild(nav);
        window.dispatchEvent(new Event('resize'));
        return true;
      })()`);
      await delay(100);
    }

    let readerFunctional = null;
    if (testCase.functionalReader) readerFunctional = await evaluate(client, readerFunctionalAudit, true);
    if (testCase.openReader) await evaluate(client, `document.querySelector('.fw-reader-trigger')?.click()`);

    if (testCase.scroll) {
      await evaluate(client, `(() => {
        const target = document.querySelector(${JSON.stringify(testCase.scroll)});
        if (!target) throw new Error('Missing audit selector: ${testCase.scroll}');
        document.documentElement.style.scrollBehavior = 'auto';
        const top = target.getBoundingClientRect().top + window.scrollY + ${testCase.offset || 0};
        window.scrollTo({ top, left:0, behavior:'instant' });
        return scrollY;
      })()`);
      await delay(350);
    }

    const audit = await evaluate(client, pageAudit);
    const screenshot = await client.send('Page.captureScreenshot', { format:'png', fromSurface:true, captureBeyondViewport:false });
    const screenshotPath = path.join(artifactDir, `${testCase.name}.png`);
    fs.writeFileSync(screenshotPath, Buffer.from(screenshot.data, 'base64'));
    return {
      name:testCase.name,
      path:testCase.path,
      viewport:testCase.viewport,
      documentStatus,
      screenshot:screenshotPath,
      profileDir,
      audit,
      readerFunctional,
      runtimeErrors,
      consoleErrors,
      chromeStderr:stderr.split('\n').filter(Boolean).slice(-8)
    };
  } finally {
    try { client?.close(); } catch {}
    chrome.kill('SIGTERM');
    await Promise.race([new Promise((resolve) => chrome.once('exit', resolve)), delay(2500)]);
    if (chrome.exitCode === null) chrome.kill('SIGKILL');
  }
}

const results = [];
for (const testCase of cases) {
  process.stdout.write(`RUN ${testCase.name}\n`);
  const result = await runCase(testCase);
  results.push(result);
  process.stdout.write(`OK  ${testCase.name}\n`);
}

const failures = [];
const expect = (condition, message) => { if (!condition) failures.push(message); };
for (const result of results) {
  const { audit } = result;
  expect(result.documentStatus === 200, `${result.name}: HTTP ${result.documentStatus}`);
  expect(!audit.overflow, `${result.name}: horizontal overflow ${audit.documentWidth} > ${audit.viewport.width}`);
  expect(audit.brokenImages.length === 0, `${result.name}: ${audit.brokenImages.length} broken image(s)`);
  expect(result.runtimeErrors.length === 0, `${result.name}: runtime ${result.runtimeErrors.join(' | ')}`);
  expect(result.consoleErrors.length === 0, `${result.name}: console ${result.consoleErrors.join(' | ')}`);
  expect(audit.themeAssets.tokens?.includes('ver=3.3.0'), `${result.name}: token asset is not 3.3.0`);
  expect(audit.themeAssets.foundation?.includes('ver=3.3.0'), `${result.name}: foundation asset is not 3.3.0`);
  expect(audit.themeAssets.reader?.includes('ver=3.3.0'), `${result.name}: reader asset is not 3.3.0`);
  expect(audit.pageBackground === 'rgb(253, 251, 247)', `${result.name}: page background ${audit.pageBackground}`);
  expect(audit.footerBackground === 'rgb(37, 51, 30)', `${result.name}: footer background ${audit.footerBackground}`);
  if (audit.h1) {
    expect(audit.h1.size === '48px', `${result.name}: H1 ${audit.h1.size}`);
    expect(audit.h1.weight === '700', `${result.name}: H1 weight ${audit.h1.weight}`);
    expect(audit.h1.family.includes('Noto Serif TC'), `${result.name}: H1 family ${audit.h1.family}`);
  }
  expect(audit.reader?.width === '56px' && audit.reader?.height === '56px', `${result.name}: reader size ${audit.reader?.width}/${audit.reader?.height}`);
  expect(audit.reader?.radius === '50%', `${result.name}: reader radius ${audit.reader?.radius}`);
  expect(audit.reader?.shadow !== 'none', `${result.name}: reader shadow missing`);
  expect((audit.minimumText?.size || 0) >= 15, `${result.name}: minimum text ${audit.minimumText?.size}px ${audit.minimumText?.text}`);
  expect(audit.revealHidden === 0, `${result.name}: ${audit.revealHidden} reveal element(s) hidden`);
  expect(audit.unexpectedColors.length === 0, `${result.name}: unexpected colors ${JSON.stringify(audit.unexpectedColors.slice(0, 6))}`);
  expect(audit.unexpectedWeights.length === 0, `${result.name}: unexpected weights ${JSON.stringify(audit.unexpectedWeights.slice(0, 6))}`);
  expect(audit.nonBrand900.length === 0, `${result.name}: non-brand 900 ${JSON.stringify(audit.nonBrand900.slice(0, 6))}`);
  expect(audit.unexpectedFamilies.length === 0, `${result.name}: unexpected font families ${JSON.stringify(audit.unexpectedFamilies.slice(0, 6))}`);
  if (audit.firstImage) {
    expect(audit.firstImage.filter === 'none', `${result.name}: image filter ${audit.firstImage.filter}`);
    expect(audit.firstImage.transition !== 'transform', `${result.name}: image transition ${audit.firstImage.transition}`);
  }
  if (audit.firstCard) {
    expect(audit.firstCard.radius === '14px', `${result.name}: card radius ${audit.firstCard.radius}`);
    expect(audit.firstCard.shadow === 'none', `${result.name}: content card shadow ${audit.firstCard.shadow}`);
    expect(!audit.firstCard.transition.includes('transform'), `${result.name}: card transition ${audit.firstCard.transition}`);
  }
  if (audit.firstTag) expect(audit.firstTag.radius === '2px', `${result.name}: tag radius ${audit.firstTag.radius}`);
  if (audit.firstControl) expect(audit.firstControl.radius === '4px', `${result.name}: control radius ${audit.firstControl.radius}`);
  if (audit.bottomNav) {
    expect(audit.bottomNav.gap >= 15, `${result.name}: reader/bottom-nav gap ${audit.bottomNav.gap}`);
    expect(!audit.bottomNav.overlapsTrigger, `${result.name}: reader overlaps bottom nav`);
    expect(!audit.bottomNav.overlapsPanel, `${result.name}: reader panel overlaps bottom nav`);
  }
  if (result.viewport.width === 1440) expect(audit.navbarHeight <= 92, `${result.name}: 1440 navbar ${audit.navbarHeight}px`);
  if (result.viewport.width === 1025) expect(audit.navbarHeight <= 140, `${result.name}: 1025 navbar ${audit.navbarHeight}px`);
  if (result.readerFunctional) {
    Object.entries(result.readerFunctional).filter(([key]) => key !== 'missing').forEach(([key, value]) => expect(value === true, `${result.name}: reader ${key}=${value}`));
    expect(audit.readerSize === 'xlarge', `${result.name}: final reader size ${audit.readerSize}`);
    expect(audit.h1?.size === '48px', `${result.name}: xlarge H1 changed to ${audit.h1?.size}`);
    if (audit.h2) expect(audit.h2.size === '38px', `${result.name}: xlarge H2 ${audit.h2.size}`);
    if (audit.article) {
      expect(audit.article.color === 'rgb(26, 26, 26)', `${result.name}: article color ${audit.article.color}`);
      expect(audit.article.size === '24px', `${result.name}: xlarge article ${audit.article.size}`);
    }
    expect(audit.panel?.hidden === false, `${result.name}: reader panel did not remain open after selection`);
    expect(audit.panel?.radius === '14px', `${result.name}: panel radius ${audit.panel?.radius}`);
  }
}

const homeMedia = results.find((result) => result.name === 'home-media-desktop')?.audit;
if (homeMedia) {
  expect(homeMedia.homeBand?.background === 'rgb(67, 88, 50)', `home media band ${homeMedia.homeBand?.background}`);
  expect(homeMedia.homeBand?.marginTop === '28px' && homeMedia.homeBand?.marginBottom === '28px', `home media margins ${homeMedia.homeBand?.marginTop}/${homeMedia.homeBand?.marginBottom}`);
  expect(homeMedia.homeBand?.paddingTop === '46px' && homeMedia.homeBand?.paddingBottom === '46px', `home media padding ${homeMedia.homeBand?.paddingTop}/${homeMedia.homeBand?.paddingBottom}`);
  expect(homeMedia.homeMediaCard?.background === 'rgb(52, 69, 45)', `home media card ${homeMedia.homeMediaCard?.background}`);
  expect(homeMedia.homeMediaCard?.radius === '14px', `home media radius ${homeMedia.homeMediaCard?.radius}`);
}

const news = results.find((result) => result.name === 'news-desktop')?.audit;
if (news?.filterTool) expect(news.filterTool.shadow !== 'none', 'news filter tool has no B-role shadow');

const report = {
  generatedAt:new Date().toISOString(),
  themeVersion:'3.3.0',
  cases:results,
  failures,
  passed:failures.length === 0
};
fs.writeFileSync(resultPath, `${JSON.stringify(report, null, 2)}\n`);
process.stdout.write(`RESULT ${report.passed ? 'PASS' : 'FAIL'} ${failures.length} failure(s)\n`);
process.stdout.write(`${resultPath}\n`);
failures.forEach((failure) => process.stderr.write(`- ${failure}\n`));
if (failures.length) process.exitCode = 1;
