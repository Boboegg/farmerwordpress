/**
 * fwdignity · layout.js
 * Navbar scrolled state + hamburger
 */
(function() {
  'use strict';

  const nav = document.getElementById('fw-navbar');
  if (!nav) return;

  // scrolled state
  function update() {
    nav.classList.toggle('scrolled', window.scrollY > 8);
  }
  update();
  window.addEventListener('scroll', update, { passive: true });

  // hamburger toggle
  const burger = nav.querySelector('.fw-hamburger');
  if (burger) {
    burger.addEventListener('click', function() {
      const open = nav.classList.toggle('open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) {
        const firstControl = nav.querySelector('.fw-nav-menu a, .fw-nav-menu button');
        if (firstControl) firstControl.focus();
      }
    });
  }

  const dropdowns = nav.querySelectorAll('.fw-nav-item.has-dropdown');

  function closeDropdowns(except) {
    dropdowns.forEach(function(item) {
      if (item === except) return;
      item.classList.remove('focus-open');
      const trigger = item.querySelector(':scope > .fw-nav-trigger');
      if (trigger) trigger.setAttribute('aria-expanded', 'false');
    });
  }

  nav.querySelectorAll('.fw-nav-trigger').forEach(function(trigger) {
    trigger.addEventListener('click', function() {
      const parent = trigger.parentElement;
      const willOpen = !parent.classList.contains('focus-open');
      closeDropdowns(parent);
      parent.classList.toggle('focus-open', willOpen);
      trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    });
  });

  document.addEventListener('click', function(event) {
    if (!nav.contains(event.target)) closeDropdowns();
  });

  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeDropdowns();
      nav.classList.remove('open');
      if (burger) burger.setAttribute('aria-expanded', 'false');
      if (burger) burger.focus();
    }
  });
})();
