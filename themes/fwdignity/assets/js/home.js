/**
 * fwdignity - home.js
 * 首頁 v0.6：最新消息主消息輪替＋聲音與影像換組
 */
(function() {
  'use strict';

  var motionQuery = window.matchMedia
    ? window.matchMedia('(prefers-reduced-motion: reduce)')
    : null;

  function prefersReducedMotion() {
    return Boolean(motionQuery && motionQuery.matches);
  }

  function setupEditorialNews() {
    var root = document.querySelector('[data-home-news]');
    var dataNode = document.querySelector('#fw-home-news-data');

    if (!root || !dataNode) {
      return;
    }

    var items;
    try {
      items = JSON.parse(dataNode.textContent);
    } catch (error) {
      return;
    }

    if (!Array.isArray(items) || items.length === 0) {
      return;
    }

    var image = root.querySelector('[data-home-news-image]');
    var meta = root.querySelector('[data-home-news-meta]');
    var title = root.querySelector('[data-home-news-title]');
    var excerpt = root.querySelector('[data-home-news-excerpt]');
    var link = root.querySelector('[data-home-news-link]');
    var picker = root.querySelector('[data-home-news-picker]');
    var currentLabel = root.querySelector('[data-home-news-current]');
    var toggleButton = root.querySelector('[data-home-news-toggle]');
    var toggleIcon = root.querySelector('[data-home-news-toggle-icon]');
    var previousButton = root.querySelector('[data-home-news-prev]');
    var nextButton = root.querySelector('[data-home-news-next]');

    if (!image || !meta || !title || !excerpt || !link || !picker || !currentLabel || !toggleButton || !toggleIcon || !previousButton || !nextButton) {
      return;
    }

    var currentIndex = 0;
    var timerId = null;
    var manuallyPaused = prefersReducedMotion();
    var pointerPaused = false;
    var focusPaused = false;

    function isPaused() {
      return manuallyPaused || pointerPaused || focusPaused || document.hidden;
    }

    function updateToggle() {
      var label = manuallyPaused ? '開始自動輪播' : '暫停自動輪播';
      toggleButton.setAttribute('aria-pressed', String(manuallyPaused));
      toggleButton.setAttribute('aria-label', label);
      toggleButton.title = label;
      toggleIcon.textContent = manuallyPaused ? '▶' : 'Ⅱ';
      toggleButton.disabled = items.length < 2;
      previousButton.disabled = items.length < 2;
      nextButton.disabled = items.length < 2;
    }

    function stopTimer() {
      if (timerId !== null) {
        window.clearInterval(timerId);
        timerId = null;
      }
    }

    function startTimer() {
      stopTimer();
      if (items.length > 1 && !isPaused()) {
        timerId = window.setInterval(function() {
          renderNews((currentIndex + 1) % items.length);
        }, 5600);
      }
    }

    function createPickerButton(item, index) {
      var button = document.createElement('button');
      var thumbnail = document.createElement('img');
      var copy = document.createElement('span');
      var itemMeta = document.createElement('span');
      var itemTitle = document.createElement('strong');

      button.type = 'button';
      button.className = 'fw-home-v06-news-picker-item';
      button.setAttribute('aria-label', '顯示消息：' + item.title);
      thumbnail.src = item.image;
      thumbnail.alt = '';
      itemMeta.textContent = item.meta;
      itemTitle.textContent = item.title;
      copy.append(itemMeta, itemTitle);
      button.append(thumbnail, copy);
      button.addEventListener('click', function() {
        renderNews(index);
        startTimer();
      });

      return button;
    }

    function renderNews(index) {
      var item = items[index];
      var arrow = document.createElement('span');
      var pickerItems = items
        .map(function(candidate, candidateIndex) {
          return { candidate: candidate, candidateIndex: candidateIndex };
        })
        .filter(function(candidate) {
          return candidate.candidateIndex !== index;
        })
        .map(function(candidate) {
          return createPickerButton(candidate.candidate, candidate.candidateIndex);
        });

      currentIndex = index;
      image.src = item.image;
      image.alt = item.alt;
      meta.textContent = item.meta;
      title.textContent = item.title;
      excerpt.textContent = item.excerpt || '';
      link.href = item.link;
      link.textContent = (item.action || '閱讀消息') + ' ';
      arrow.setAttribute('aria-hidden', 'true');
      arrow.textContent = '→';
      link.append(arrow);
      currentLabel.textContent = String(index + 1).padStart(2, '0');
      picker.style.gridTemplateRows = 'repeat(' + Math.max(1, pickerItems.length) + ', minmax(0, 1fr))';
      picker.replaceChildren.apply(picker, pickerItems);
    }

    previousButton.addEventListener('click', function() {
      renderNews((currentIndex - 1 + items.length) % items.length);
      startTimer();
    });

    nextButton.addEventListener('click', function() {
      renderNews((currentIndex + 1) % items.length);
      startTimer();
    });

    toggleButton.addEventListener('click', function() {
      manuallyPaused = !manuallyPaused;
      updateToggle();
      startTimer();
    });

    root.addEventListener('pointerenter', function() {
      pointerPaused = true;
      stopTimer();
    });

    root.addEventListener('pointerleave', function() {
      pointerPaused = false;
      startTimer();
    });

    root.addEventListener('focusin', function() {
      focusPaused = true;
      stopTimer();
    });

    root.addEventListener('focusout', function(event) {
      if (!root.contains(event.relatedTarget)) {
        focusPaused = false;
        startTimer();
      }
    });

    document.addEventListener('visibilitychange', startTimer);
    renderNews(0);
    updateToggle();
    startTimer();
  }

  function setupMediaCarousel() {
    var root = document.querySelector('[data-home-media]');
    var viewport = root ? root.querySelector('[data-home-media-viewport]') : null;
    var progress = root ? root.querySelector('[data-home-media-progress]') : null;
    var section = root ? root.closest('.fw-home-v06-audio') : null;
    var toggleButton = document.querySelector('[data-home-media-toggle]');
    var previousButton = document.querySelector('[data-home-media-prev]');
    var nextButton = document.querySelector('[data-home-media-next]');

    if (!root || !viewport || !progress || !section || !toggleButton || !previousButton || !nextButton) {
      return;
    }

    var timerId = null;
    var manualPaused = false;
    var pointerPaused = false;
    var focusPaused = false;
    var sectionVisible = false;
    var scrollFrame = null;

    function hasOverflow() {
      return viewport.scrollWidth - viewport.clientWidth > 2;
    }

    function atStart() {
      return viewport.scrollLeft <= 2;
    }

    function atEnd() {
      return viewport.scrollLeft + viewport.clientWidth >= viewport.scrollWidth - 2;
    }

    function isPaused() {
      return prefersReducedMotion() || manualPaused || pointerPaused || focusPaused || !sectionVisible || document.hidden;
    }

    function updateControls() {
      var overflow = hasOverflow();
      var ratio = overflow
        ? Math.min(1, (viewport.scrollLeft + viewport.clientWidth) / viewport.scrollWidth)
        : 1;

      previousButton.disabled = !overflow || atStart();
      nextButton.disabled = !overflow || atEnd();
      toggleButton.disabled = !overflow;
      progress.style.transform = 'scaleX(' + ratio + ')';
    }

    function updateToggle() {
      var paused = manualPaused || prefersReducedMotion();
      toggleButton.setAttribute('aria-pressed', paused ? 'true' : 'false');
      toggleButton.setAttribute('aria-label', paused ? '繼續聲音與影像自動輪播' : '暫停聲音與影像自動輪播');
      toggleButton.title = paused ? '繼續聲音與影像自動輪播' : '暫停聲音與影像自動輪播';
      var icon = toggleButton.querySelector('span');
      if (icon) icon.textContent = paused ? '▶' : 'Ⅱ';
    }

    function stopTimer() {
      if (timerId !== null) {
        window.clearInterval(timerId);
        timerId = null;
      }
    }

    function moveMedia(direction) {
      var track = viewport.querySelector('.fw-home-v06-media-track');
      var gap = track ? parseFloat(window.getComputedStyle(track).columnGap) || 0 : 0;
      var distance = viewport.clientWidth + gap;

      viewport.scrollBy({
        left: direction * distance,
        behavior: prefersReducedMotion() ? 'auto' : 'smooth'
      });
    }

    function startTimer() {
      stopTimer();
      if (hasOverflow() && !isPaused()) {
        timerId = window.setInterval(function() {
          if (atEnd()) {
            viewport.scrollTo({ left: 0, behavior: 'smooth' });
          } else {
            moveMedia(1);
          }
        }, 8000);
      }
    }

    toggleButton.addEventListener('click', function() {
      manualPaused = !manualPaused;
      updateToggle();
      startTimer();
    });

    previousButton.addEventListener('click', function() {
      moveMedia(-1);
      startTimer();
    });

    nextButton.addEventListener('click', function() {
      moveMedia(1);
      startTimer();
    });

    viewport.addEventListener('scroll', function() {
      if (scrollFrame !== null) {
        window.cancelAnimationFrame(scrollFrame);
      }
      scrollFrame = window.requestAnimationFrame(updateControls);
    });

    section.addEventListener('pointerenter', function() {
      pointerPaused = true;
      stopTimer();
    });

    section.addEventListener('pointerleave', function() {
      pointerPaused = false;
      startTimer();
    });

    section.addEventListener('focusin', function() {
      focusPaused = true;
      stopTimer();
    });

    section.addEventListener('focusout', function(event) {
      if (!section.contains(event.relatedTarget)) {
        focusPaused = false;
        startTimer();
      }
    });

    document.addEventListener('visibilitychange', startTimer);

    if ('ResizeObserver' in window) {
      new ResizeObserver(function() {
        updateControls();
        startTimer();
      }).observe(viewport);
    } else {
      window.addEventListener('resize', function() {
        updateControls();
        startTimer();
      });
    }

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function(entries) {
        sectionVisible = entries[0].isIntersecting;
        if (sectionVisible) {
          startTimer();
        } else {
          stopTimer();
        }
      }, { threshold: 0.2 }).observe(section);
    } else {
      sectionVisible = true;
    }

    updateControls();
    updateToggle();
    startTimer();
  }

  setupEditorialNews();
  setupMediaCarousel();
})();
