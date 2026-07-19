(function () {
  'use strict';

  var shell = document.querySelector('[data-news-shell]');
  if (!shell) return;

  shell.classList.add('has-js');

  var collapseButton = shell.querySelector('[data-filter-collapse]');
  var filterForm = shell.querySelector('[data-auto-submit]');
  var storageKey = 'fwdignityThenewsFiltersCollapsed';

  function readCollapsedState() {
    try {
      return window.localStorage.getItem(storageKey) === '1';
    } catch (error) {
      return false;
    }
  }

  function writeCollapsedState(isCollapsed) {
    try {
      window.localStorage.setItem(storageKey, isCollapsed ? '1' : '0');
    } catch (error) {
      // Storage can be unavailable in privacy modes; the control still works.
    }
  }

  function setCollapsed(isCollapsed) {
    shell.classList.toggle('is-filter-collapsed', isCollapsed);
    if (!collapseButton) return;

    collapseButton.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
    collapseButton.setAttribute('title', isCollapsed ? '展開篩選欄' : '收合篩選欄');
    var label = collapseButton.querySelector('.screen-reader-text');
    if (label) label.textContent = isCollapsed ? '展開篩選欄' : '收合篩選欄';
    var icon = collapseButton.querySelector('i');
    if (icon) {
      icon.classList.toggle('fa-chevron-left', !isCollapsed);
      icon.classList.toggle('fa-chevron-right', isCollapsed);
    }
  }

  setCollapsed(readCollapsedState());

  if (collapseButton) {
    collapseButton.addEventListener('click', function () {
      var nextState = !shell.classList.contains('is-filter-collapsed');
      setCollapsed(nextState);
      writeCollapsedState(nextState);
    });
  }

  if (filterForm) {
    filterForm.addEventListener('change', function (event) {
      var control = event.target;
      if (!control.matches('input[type="checkbox"], select[name="order"]')) return;

      if (typeof filterForm.requestSubmit === 'function') {
        filterForm.requestSubmit();
      } else {
        filterForm.submit();
      }
    });
  }

  shell.querySelectorAll('[data-facet-group]').forEach(function (group) {
    var moreButton = group.querySelector('[data-facet-more]');
    var list = group.querySelector('.tnews-facet-list');
    if (!moreButton || !list) return;

    moreButton.addEventListener('click', function () {
      var isExpanded = list.classList.toggle('is-expanded');
      moreButton.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
      moreButton.textContent = isExpanded
        ? '收起部分分類'
        : moreButton.textContent.replace('收起部分分類', '顯示更多分類');
      if (!isExpanded) moreButton.textContent = '顯示更多分類';
    });
  });
})();
