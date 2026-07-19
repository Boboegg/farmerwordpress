(function () {
  'use strict';

  var page = document.querySelector('.field-notes-page');
  if (!page) return;

  page.classList.add('has-js');

  var filterToggle = page.querySelector('.field-notes-filter-toggle');
  var filterDrawer = page.querySelector('.field-notes-filter-drawer');
  var filterClose = page.querySelector('.field-notes-filter-close');
  var viewButtons = page.querySelectorAll('[data-field-view]');
  var viewPanels = page.querySelectorAll('[data-field-panel]');

  function setFilter(open) {
    if (!filterToggle || !filterDrawer) return;
    filterDrawer.hidden = !open;
    filterToggle.setAttribute('aria-expanded', String(open));
    if (open) {
      var firstInput = filterDrawer.querySelector('input, button, a');
      if (firstInput) firstInput.focus({ preventScroll: true });
    }
  }

  function setView(view) {
    var selected = view === 'timeline' ? 'timeline' : 'grid';
    viewButtons.forEach(function (button) {
      var active = button.dataset.fieldView === selected;
      button.classList.toggle('is-active', active);
      button.setAttribute('aria-pressed', String(active));
    });
    viewPanels.forEach(function (panel) {
      panel.hidden = panel.dataset.fieldPanel !== selected;
    });
    try { localStorage.setItem('fwdignityFieldView', selected); } catch (error) {}
  }

  if (filterToggle && filterDrawer) {
    filterToggle.addEventListener('click', function () {
      setFilter(filterDrawer.hidden);
    });
    if (filterClose) {
      filterClose.addEventListener('click', function () {
        setFilter(false);
        filterToggle.focus();
      });
    }
    setFilter(false);
  }

  viewButtons.forEach(function (button) {
    button.addEventListener('click', function () { setView(button.dataset.fieldView); });
  });

  page.querySelectorAll('.field-notes-filter-option input').forEach(function (input) {
    input.addEventListener('change', function () {
      input.closest('.field-notes-filter-option').classList.toggle('is-selected', input.checked);
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && filterDrawer && !filterDrawer.hidden) {
      setFilter(false);
      filterToggle.focus();
    }
  });

  var savedView = 'grid';
  try { savedView = localStorage.getItem('fwdignityFieldView') || 'grid'; } catch (error) {}
  setView(savedView);
}());
