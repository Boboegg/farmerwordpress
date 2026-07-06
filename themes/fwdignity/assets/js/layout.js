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
    });
  }

  // dropdown keyboard a11y
  nav.querySelectorAll('.fw-nav-item.has-dropdown > .fw-nav-link').forEach(function(link) {
    link.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        const parent = link.parentElement;
        parent.classList.toggle('focus-open');
      }
    });
  });
})();
