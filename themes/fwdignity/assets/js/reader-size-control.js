/**
 * 尊嚴農業讀者字級控制
 * 偏好只保存在目前瀏覽器，不送回伺服器，也不攔截瀏覽器原生縮放。
 */
(function() {
  'use strict';

  const storageKey = 'fwdignity-reader-size';
  const allowedSizes = [ 'standard', 'large', 'xlarge' ];
  const root = document.documentElement;

  function readPreference() {
    try {
      const value = window.localStorage.getItem(storageKey);
      return allowedSizes.includes(value) ? value : 'standard';
    } catch (error) {
      return 'standard';
    }
  }

  function savePreference(value) {
    try {
      window.localStorage.setItem(storageKey, value);
    } catch (error) {
      // Storage may be disabled; the in-page choice still works for this view.
    }
  }

  function makeButton(value, label) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'fw-reader-option';
    button.dataset.readerSize = value;
    button.textContent = label;
    button.setAttribute('aria-pressed', 'false');
    return button;
  }

  const control = document.createElement('div');
  control.className = 'fw-reader-control';
  control.dataset.readerControl = '';

  const trigger = document.createElement('button');
  trigger.type = 'button';
  trigger.className = 'fw-reader-trigger';
  trigger.setAttribute('aria-label', '調整文字大小');
  trigger.setAttribute('aria-expanded', 'false');
  trigger.setAttribute('aria-controls', 'fw-reader-panel');
  trigger.innerHTML = '<span aria-hidden="true">Aa</span>';

  const panel = document.createElement('div');
  panel.className = 'fw-reader-panel';
  panel.id = 'fw-reader-panel';
  panel.hidden = true;
  panel.setAttribute('role', 'region');
  panel.setAttribute('aria-label', '文字大小選擇');

  const title = document.createElement('p');
  title.className = 'fw-reader-title';
  title.textContent = '文字大小';

  const note = document.createElement('p');
  note.className = 'fw-reader-note';
  note.textContent = '選擇適合閱讀的字級；瀏覽器縮放仍可照常使用。';

  const options = document.createElement('div');
  options.className = 'fw-reader-options';
  options.setAttribute('role', 'group');
  options.setAttribute('aria-label', '選擇文字大小');
  options.append(
    makeButton('standard', '標準'),
    makeButton('large', '大字'),
    makeButton('xlarge', '特大字')
  );

  panel.append(title, note, options);
  control.append(panel, trigger);
  document.body.append(control);

  const optionButtons = Array.from(options.querySelectorAll('.fw-reader-option'));

  function applySize(value, persist) {
    const nextValue = allowedSizes.includes(value) ? value : 'standard';
    root.dataset.fwReaderSize = nextValue;
    optionButtons.forEach(function(button) {
      button.setAttribute('aria-pressed', button.dataset.readerSize === nextValue ? 'true' : 'false');
    });
    if (persist) savePreference(nextValue);
  }

  function setOpen(open, restoreFocus) {
    panel.hidden = !open;
    trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    control.classList.toggle('is-open', open);
    if (!open && restoreFocus) trigger.focus();
  }

  function isVisibleFixedBottom(element) {
    if (!element) return false;
    const style = window.getComputedStyle(element);
    const rect = element.getBoundingClientRect();
    return style.display !== 'none' && style.visibility !== 'hidden' && style.position === 'fixed' && rect.height > 0 && rect.bottom > 0;
  }

  function updateBottomClearance() {
    const candidates = Array.from(document.querySelectorAll('.bf-bottom-nav, [data-fixed-bottom-bar]'));
    const fixedBottom = candidates.find(isVisibleFixedBottom);
    const clearance = fixedBottom ? Math.ceil(fixedBottom.getBoundingClientRect().height + 16) : 18;
    control.style.setProperty('--fw-reader-bottom-clearance', clearance + 'px');
  }

  trigger.addEventListener('click', function() {
    setOpen(panel.hidden, false);
  });

  optionButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      applySize(button.dataset.readerSize, true);
    });
  });

  document.addEventListener('click', function(event) {
    if (!panel.hidden && !control.contains(event.target)) setOpen(false, false);
  });

  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !panel.hidden) setOpen(false, true);
  });

  window.addEventListener('resize', updateBottomClearance, { passive: true });
  window.addEventListener('orientationchange', updateBottomClearance, { passive: true });

  if ('ResizeObserver' in window) {
    const bottomBar = document.querySelector('.bf-bottom-nav, [data-fixed-bottom-bar]');
    if (bottomBar) new ResizeObserver(updateBottomClearance).observe(bottomBar);
  }

  applySize(readPreference(), false);
  updateBottomClearance();
})();
