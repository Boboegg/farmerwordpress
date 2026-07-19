document.addEventListener('DOMContentLoaded', () => {
  const FEATURE_INTERVAL = 7000;
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.documentElement.classList.add('js-media-hub');

  const seriesNames = (item) => (item.series || []).map((term) => term.name).filter(Boolean).join('、');
  const audioPlayers = new Set();

  const formatAudioTime = (seconds) => {
    if (!Number.isFinite(seconds) || seconds < 0) return '--:--';
    const rounded = Math.floor(seconds);
    const hours = Math.floor(rounded / 3600);
    const minutes = Math.floor((rounded % 3600) / 60);
    const remainingSeconds = rounded % 60;
    if (hours > 0) {
      return `${hours}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
    }
    return `${minutes}:${String(remainingSeconds).padStart(2, '0')}`;
  };

  const createAudioPlayer = (item, options = {}) => {
    const player = document.createElement('div');
    player.className = `fw-audio-player${options.compact ? ' fw-audio-player--compact' : ''}`;
    player.setAttribute('data-fw-audio-player', '');
    player.dataset.audioTitle = item.title || 'Podcast';
    if (options.transient) player.setAttribute('data-media-transient-player', '');

    const toggle = document.createElement('button');
    toggle.className = 'fw-audio-player__toggle';
    toggle.type = 'button';
    toggle.setAttribute('data-fw-audio-toggle', '');
    toggle.setAttribute('aria-label', `播放：${player.dataset.audioTitle}`);
    const icon = document.createElement('i');
    icon.className = 'fa-solid fa-play';
    icon.setAttribute('data-fw-audio-icon', '');
    icon.setAttribute('aria-hidden', 'true');
    toggle.append(icon);

    const body = document.createElement('div');
    body.className = 'fw-audio-player__body';
    const statusRow = document.createElement('div');
    statusRow.className = 'fw-audio-player__status-row';
    const state = document.createElement('span');
    state.setAttribute('data-fw-audio-state', '');
    state.textContent = '準備播放';
    const times = document.createElement('span');
    times.className = 'fw-audio-player__times';
    const current = document.createElement('span');
    current.setAttribute('data-fw-audio-current', '');
    current.textContent = '00:00';
    const divider = document.createElement('span');
    divider.setAttribute('aria-hidden', 'true');
    divider.textContent = ' / ';
    const duration = document.createElement('span');
    duration.setAttribute('data-fw-audio-duration', '');
    duration.textContent = item.duration || '--:--';
    times.append(current, divider, duration);
    statusRow.append(state, times);

    const timeline = document.createElement('div');
    timeline.className = 'fw-audio-player__timeline';
    const progress = document.createElement('span');
    progress.className = 'fw-audio-player__progress';
    progress.setAttribute('data-fw-audio-progress', '');
    progress.setAttribute('aria-hidden', 'true');
    const seek = document.createElement('input');
    seek.type = 'range';
    seek.min = '0';
    seek.max = '100';
    seek.value = '0';
    seek.step = '0.1';
    seek.setAttribute('data-fw-audio-seek', '');
    seek.setAttribute('aria-label', `調整播放進度：${player.dataset.audioTitle}`);
    timeline.append(progress, seek);

    const message = document.createElement('p');
    message.className = 'fw-audio-player__message';
    message.setAttribute('data-fw-audio-message', '');
    message.setAttribute('role', 'status');
    message.hidden = true;
    body.append(statusRow, timeline, message);

    const audio = document.createElement('audio');
    audio.src = item.embed?.url || '';
    audio.preload = 'none';
    audio.hidden = true;
    audio.setAttribute('data-fw-audio-element', '');
    player.append(toggle, body, audio);
    return player;
  };

  const initAudioPlayer = (player, options = {}) => {
    if (!player) return;
    const audio = player.querySelector('[data-fw-audio-element]');
    const toggle = player.querySelector('[data-fw-audio-toggle]');
    const icon = player.querySelector('[data-fw-audio-icon]');
    const state = player.querySelector('[data-fw-audio-state]');
    const seek = player.querySelector('[data-fw-audio-seek]');
    const progress = player.querySelector('[data-fw-audio-progress]');
    const current = player.querySelector('[data-fw-audio-current]');
    const duration = player.querySelector('[data-fw-audio-duration]');
    const message = player.querySelector('[data-fw-audio-message]');
    const title = player.dataset.audioTitle || toggle?.getAttribute('aria-label')?.replace(/^播放：/, '') || 'Podcast';
    if (!audio || !toggle || !seek || !progress || !current || !duration) return;

    const showMessage = (text = '') => {
      if (!message) return;
      message.textContent = text;
      message.hidden = !text;
    };
    const updateToggle = (playing) => {
      if (icon) icon.className = `fa-solid ${playing ? 'fa-pause' : 'fa-play'}`;
      toggle.setAttribute('aria-label', `${playing ? '暫停' : '播放'}：${title}`);
      toggle.setAttribute('aria-pressed', playing ? 'true' : 'false');
    };
    const updateProgress = () => {
      const total = Number.isFinite(audio.duration) ? audio.duration : 0;
      const elapsed = Number.isFinite(audio.currentTime) ? audio.currentTime : 0;
      const percentage = total > 0 ? Math.min(100, Math.max(0, (elapsed / total) * 100)) : 0;
      seek.value = String(percentage);
      progress.style.width = `${percentage}%`;
      current.textContent = formatAudioTime(elapsed).replace('--:--', '00:00');
      seek.setAttribute('aria-valuetext', `${current.textContent}，共 ${duration.textContent}`);
    };
    const playAudio = () => {
      audioPlayers.forEach((otherAudio) => {
        if (otherAudio !== audio && !otherAudio.paused) otherAudio.pause();
      });
      showMessage('');
      audio.play().catch(() => showMessage('音訊暫時無法播放，請稍後再試。'));
    };

    if (!player.dataset.audioInitialized) {
      player.dataset.audioInitialized = 'true';
      audioPlayers.add(audio);
      updateToggle(false);
      audio.addEventListener('loadedmetadata', () => {
        if (Number.isFinite(audio.duration)) duration.textContent = formatAudioTime(audio.duration);
        updateProgress();
      });
      audio.addEventListener('durationchange', () => {
        if (Number.isFinite(audio.duration)) duration.textContent = formatAudioTime(audio.duration);
      });
      audio.addEventListener('timeupdate', updateProgress);
      audio.addEventListener('play', () => {
        audioPlayers.forEach((otherAudio) => {
          if (otherAudio !== audio && !otherAudio.paused) otherAudio.pause();
        });
        updateToggle(true);
        if (state) state.textContent = '正在播放';
      });
      audio.addEventListener('pause', () => {
        updateToggle(false);
        if (state && !audio.ended) state.textContent = '已暫停';
      });
      audio.addEventListener('ended', () => {
        updateToggle(false);
        if (state) state.textContent = '播放完畢';
      });
      audio.addEventListener('waiting', () => {
        if (state) state.textContent = '載入中';
      });
      audio.addEventListener('playing', () => {
        updateToggle(true);
        if (state) state.textContent = '正在播放';
      });
      audio.addEventListener('error', () => {
        updateToggle(false);
        if (state) state.textContent = '無法播放';
        showMessage('音訊暫時無法播放，請稍後再試。');
      });
      toggle.addEventListener('click', () => {
        if (audio.paused) playAudio();
        else audio.pause();
      });
      seek.addEventListener('input', () => {
        if (!Number.isFinite(audio.duration) || audio.duration <= 0) return;
        audio.currentTime = (Number(seek.value) / 100) * audio.duration;
        updateProgress();
      });
    }

    if (options.autoplay) playAudio();
  };

  const initAudioPlayers = (scope = document) => {
    const players = [];
    if (scope.matches?.('[data-fw-audio-player]')) players.push(scope);
    players.push(...scope.querySelectorAll('[data-fw-audio-player]'));
    players.forEach((player) => initAudioPlayer(player));
  };

  const createVisual = (item, className) => {
    const visual = document.createElement('div');
    visual.className = `${className}${item.thumb ? '' : ' no-image'}`;
    if (item.thumb) {
      const image = document.createElement('img');
      image.src = item.thumb;
      image.alt = '';
      image.loading = 'eager';
      visual.append(image);
    } else {
      const fallback = document.createElement('span');
      fallback.className = 'mh-visual-fallback';
      fallback.setAttribute('aria-hidden', 'true');
      const icon = document.createElement('i');
      icon.className = `fa-solid ${item.format === 'podcast' ? 'fa-wave-square' : 'fa-play'}`;
      fallback.append(icon);
      visual.append(fallback);
    }
    return visual;
  };

  const createMediaAction = (item, label) => {
    if (item.embed && item.embed.url) {
      if (item.embed.type === 'audio') {
        return createAudioPlayer(item);
      }
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'mh-action-button';
      button.setAttribute('data-media-play', '');
      button.dataset.embedType = item.embed.type;
      button.dataset.embedUrl = item.embed.url;
      const icon = document.createElement('i');
      icon.className = 'fa-solid fa-play';
      icon.setAttribute('aria-hidden', 'true');
      button.append(icon, document.createTextNode(` ${label}`));
      return button;
    }

    const link = document.createElement('a');
    link.className = 'mh-action-button';
    link.href = item.link;
    link.textContent = '閱讀完整內容';
    return link;
  };

  const resetPlayer = (container) => {
    if (!container) return;
    container.querySelectorAll('audio[data-fw-audio-element]').forEach((audio) => audio.pause());
    container.querySelectorAll('[data-media-transient-player]').forEach((player) => player.remove());
    container.querySelectorAll('iframe[data-media-iframe]').forEach((iframe) => iframe.remove());
    container.querySelectorAll('audio[data-media-audio]').forEach((audio) => audio.remove());
    container.querySelectorAll('[data-media-player-visual]').forEach((visual) => { visual.hidden = false; });
    container.querySelectorAll('[data-media-play]').forEach((button) => { button.hidden = false; });
  };

  const featureRoot = document.querySelector('[data-media-feature]');
  let featurePlaying = false;

  const initFeature = () => {
    if (!featureRoot) return;
    const slides = Array.from(featureRoot.querySelectorAll('[data-media-feature-slide]'));
    const dots = Array.from(featureRoot.querySelectorAll('[data-media-feature-dot]'));
    const previous = featureRoot.querySelector('[data-media-feature-prev]');
    const next = featureRoot.querySelector('[data-media-feature-next]');
    const toggle = featureRoot.querySelector('[data-media-feature-toggle]');
    let current = 0;
    let timer = null;
    let manualPause = false;
    let hoverPause = false;
    let focusPause = false;

    const isPaused = () => manualPause || hoverPause || focusPause || featurePlaying || reduceMotion;

    const updateToggle = () => {
      if (!toggle) return;
      const paused = manualPause || featurePlaying || reduceMotion;
      toggle.setAttribute('aria-pressed', paused ? 'true' : 'false');
      toggle.setAttribute('aria-label', paused ? '繼續自動輪播' : '暫停自動輪播');
      toggle.title = paused ? '繼續自動輪播' : '暫停自動輪播';
      const icon = toggle.querySelector('i');
      if (icon) icon.className = `fa-solid ${paused ? 'fa-play' : 'fa-pause'}`;
    };

    const stopTimer = () => {
      window.clearTimeout(timer);
      timer = null;
    };

    const schedule = () => {
      stopTimer();
      if (slides.length < 2 || isPaused()) return;
      timer = window.setTimeout(() => show(current + 1), FEATURE_INTERVAL);
    };

    const show = (index) => {
      if (!slides.length) return;
      current = (index + slides.length) % slides.length;
      slides.forEach((slide, slideIndex) => {
        const active = slideIndex === current;
        if (!active) resetPlayer(slide);
        slide.hidden = !active;
        slide.setAttribute('aria-hidden', active ? 'false' : 'true');
      });
      dots.forEach((dot, dotIndex) => dot.setAttribute('aria-current', dotIndex === current ? 'true' : 'false'));
      featurePlaying = false;
      updateToggle();
      schedule();
    };

    previous?.addEventListener('click', () => show(current - 1));
    next?.addEventListener('click', () => show(current + 1));
    dots.forEach((dot, index) => dot.addEventListener('click', () => show(index)));
    toggle?.addEventListener('click', () => {
      if (featurePlaying) {
        resetPlayer(slides[current]);
        featurePlaying = false;
        manualPause = false;
      } else {
        manualPause = !manualPause;
      }
      updateToggle();
      schedule();
    });

    featureRoot.addEventListener('mouseenter', () => { hoverPause = true; stopTimer(); });
    featureRoot.addEventListener('mouseleave', () => { hoverPause = false; schedule(); });
    featureRoot.addEventListener('focusin', () => { focusPause = true; stopTimer(); });
    featureRoot.addEventListener('focusout', (event) => {
      if (!featureRoot.contains(event.relatedTarget)) {
        focusPause = false;
        schedule();
      }
    });
    featureRoot.addEventListener('mediahub:play', () => {
      featurePlaying = true;
      stopTimer();
      updateToggle();
    });

    updateToggle();
    schedule();
  };

  document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-media-play]');
    if (!button) return;
    const container = button.closest('[data-media-player], [data-media-podcast-main], [data-media-video-main]');
    if (!container) return;

    const surface = container.matches('[data-media-player]')
      ? container
      : container.querySelector(container.matches('[data-media-podcast-main]') ? '.mh-podcast-cover' : '[data-media-video-player]');
    if (!surface || !button.dataset.embedUrl) return;

    event.preventDefault();
    resetPlayer(container);

    if (button.dataset.embedType === 'audio') {
      const player = createAudioPlayer(
        {
          title: button.dataset.audioTitle || button.getAttribute('aria-label')?.replace(/^播放：/, '') || 'Podcast',
          duration: button.dataset.audioDuration || '',
          embed: { type: 'audio', url: button.dataset.embedUrl },
        },
        { transient: true }
      );
      surface.append(player);
      button.hidden = true;
      initAudioPlayer(player, { autoplay: true });
      if (featureRoot && featureRoot.contains(button)) {
        featureRoot.dispatchEvent(new CustomEvent('mediahub:play'));
      }
      return;
    }

    const currentVisual = surface.firstElementChild;
    if (currentVisual) {
      currentVisual.hidden = true;
      currentVisual.setAttribute('data-media-player-visual', '');
    }

    const iframe = document.createElement('iframe');
    const separator = button.dataset.embedUrl.includes('?') ? '&' : '?';
    iframe.className = `mh-media-iframe mh-media-iframe--${button.dataset.embedType || 'default'}`;
    iframe.src = button.dataset.embedType === 'youtube'
      ? `${button.dataset.embedUrl}${separator}autoplay=1&rel=0`
      : button.dataset.embedUrl;
    iframe.title = button.getAttribute('aria-label') || '媒體播放器';
    iframe.allow = 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture';
    iframe.setAttribute('data-media-iframe', '');
    surface.append(iframe);
    button.hidden = true;

    if (featureRoot && featureRoot.contains(button)) {
      featureRoot.dispatchEvent(new CustomEvent('mediahub:play'));
    }
  });

  const parsePayload = (root, selector) => {
    const node = root?.querySelector(selector);
    if (!node) return [];
    try {
      return JSON.parse(node.textContent || '[]');
    } catch (error) {
      return [];
    }
  };

  const initPodcast = () => {
    const root = document.querySelector('[data-media-podcast]');
    if (!root) return;
    const main = root.querySelector('[data-media-podcast-main]');
    const items = parsePayload(root, '[data-media-podcast-data]');
    const links = Array.from(root.querySelectorAll('[data-media-podcast-item]'));

    links.forEach((link, index) => link.addEventListener('click', (event) => {
      const item = items[index];
      if (!item || !main) return;
      event.preventDefault();
      resetPlayer(main);

      const oldCover = main.querySelector('.mh-podcast-cover');
      oldCover?.replaceWith(createVisual(item, 'mh-podcast-cover'));
      const meta = main.querySelector('[data-media-main-meta]');
      const titleLink = main.querySelector('[data-media-main-title] a');
      const excerpt = main.querySelector('[data-media-main-excerpt]');
      const action = main.querySelector('[data-media-main-action]');
      if (meta) meta.textContent = `${item.date}${seriesNames(item) ? `｜${seriesNames(item)}` : ''}`;
      if (titleLink) { titleLink.textContent = item.title; titleLink.href = item.link; }
      if (excerpt) excerpt.textContent = item.excerpt;
      if (action) {
        action.replaceChildren(createMediaAction(item, '播放本集'));
        initAudioPlayers(action);
      }
      links.forEach((queueItem, queueIndex) => queueItem.setAttribute('aria-current', queueIndex === index ? 'true' : 'false'));
    }));
  };

  const initVideo = () => {
    const root = document.querySelector('[data-media-video]');
    if (!root) return;
    const main = root.querySelector('[data-media-video-main]');
    const items = parsePayload(root, '[data-media-video-data]');
    const links = Array.from(root.querySelectorAll('[data-media-video-item]'));

    links.forEach((link, index) => link.addEventListener('click', (event) => {
      const item = items[index];
      if (!item || !main) return;
      event.preventDefault();
      resetPlayer(main);

      const player = main.querySelector('[data-media-video-player]');
      const meta = main.querySelector('[data-media-main-meta]');
      const titleLink = main.querySelector('[data-media-main-title] a');
      const action = main.querySelector('[data-media-main-action]');
      if (player) player.replaceChildren(createVisual(item, 'mh-video-visual'));
      if (meta) meta.textContent = item.date;
      if (titleLink) { titleLink.textContent = item.title; titleLink.href = item.link; }
      if (action) action.replaceChildren(createMediaAction(item, '播放影片'));
      links.forEach((videoItem, videoIndex) => videoItem.setAttribute('aria-current', videoIndex === index ? 'true' : 'false'));
    }));
  };

  const initFilters = () => {
    const form = document.querySelector('[data-media-filter-form]');
    const drawer = form?.querySelector('[data-media-filter-drawer]');
    const toggle = form?.querySelector('[data-media-filter-toggle]');
    const close = form?.querySelector('[data-media-filter-close]');
    if (!form || !drawer || !toggle) return;

    drawer.hidden = true;
    const setOpen = (open) => {
      drawer.hidden = !open;
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) close?.focus();
    };

    toggle.addEventListener('click', () => setOpen(drawer.hidden));
    close?.addEventListener('click', () => { setOpen(false); toggle.focus(); });
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !drawer.hidden) {
        setOpen(false);
        toggle.focus();
      }
    });

    form.querySelectorAll('input[name="media_format"]').forEach((input) => {
      input.addEventListener('change', () => form.requestSubmit());
    });
  };

  initAudioPlayers();
  initFeature();
  initPodcast();
  initVideo();
  initFilters();
});
