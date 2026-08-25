document.querySelectorAll('[data-slider]').forEach((slider) => {
  const sliderId = slider.getAttribute('id');
  if (!sliderId) return;

  const prevButton = document.querySelector(`[data-slider-prev="${sliderId}"]`);
  const nextButton = document.querySelector(`[data-slider-next="${sliderId}"]`);

  const getScrollAmount = () => {
    const firstSlide = slider.querySelector(':scope > *');
    if (!firstSlide) return slider.clientWidth * 0.9;

    const slideStyles = window.getComputedStyle(firstSlide);
    const marginRight = parseFloat(slideStyles.marginRight || '0');
    return firstSlide.getBoundingClientRect().width + marginRight + 24;
  };

  prevButton?.addEventListener('click', () => {
    slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
  });

  nextButton?.addEventListener('click', () => {
    slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
  });
});

const revealTitles = Array.from(document.querySelectorAll('h1, h2')).filter((title) => (
  !title.closest('header, footer, nav, [aria-hidden="true"]')
));

if (revealTitles.length) {
  if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    revealTitles.forEach((title) => {
      title.classList.add('title-reveal', 'is-visible');
    });
  } else {
    const revealTitle = (title) => {
      window.requestAnimationFrame(() => {
        window.requestAnimationFrame(() => {
          title.classList.add('is-visible');
        });
      });
    };

    const isTitleInViewport = (title) => {
      const rect = title.getBoundingClientRect();
      return rect.top < window.innerHeight && rect.bottom > 0;
    };

    const titleObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        revealTitle(entry.target);
        titleObserver.unobserve(entry.target);
      });
    }, {
      threshold: 0.2,
      rootMargin: '0px 0px -8% 0px',
    });

    revealTitles.forEach((title) => {
      title.classList.add('title-reveal');

      if (isTitleInViewport(title)) {
        revealTitle(title);
        return;
      }

      titleObserver.observe(title);
    });
  }
}

document.querySelectorAll('[data-gallery-modal]').forEach((modal) => {
  const openButtons = Array.from(document.querySelectorAll('[data-gallery-open]'));
  const closeButtons = Array.from(modal.querySelectorAll('[data-gallery-close]'));

  if (!openButtons.length) return;

  const setGalleryState = (isOpen) => {
    modal.classList.toggle('hidden', !isOpen);
    modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    document.body.classList.toggle('gallery-modal-open', isOpen);

    if (isOpen) {
      closeButtons[0]?.focus();
    }
  };

  openButtons.forEach((button) => {
    button.addEventListener('click', () => setGalleryState(true));
  });

  closeButtons.forEach((button) => {
    button.addEventListener('click', () => setGalleryState(false));
  });

  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      setGalleryState(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
      setGalleryState(false);
    }
  });
});

document.querySelectorAll('[data-tour-modal]').forEach((modal) => {
  const openButtons = Array.from(document.querySelectorAll('[data-tour-open]'));
  const closeButtons = Array.from(modal.querySelectorAll('[data-tour-close]'));
  const firstInput = modal.querySelector('input:not([type="hidden"])');

  if (!openButtons.length) return;

  const setTourState = (isOpen) => {
    modal.classList.toggle('hidden', !isOpen);
    modal.classList.toggle('flex', isOpen);
    modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    document.body.classList.toggle('tour-modal-open', isOpen);

    if (isOpen) {
      firstInput?.focus();
    }
  };

  openButtons.forEach((button) => {
    button.addEventListener('click', () => setTourState(true));
  });

  closeButtons.forEach((button) => {
    button.addEventListener('click', () => setTourState(false));
  });

  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      setTourState(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
      setTourState(false);
    }
  });
});

document.querySelectorAll('[data-location-search]').forEach((form) => {
  const input = form.querySelector('input[name="locatie"]');
  const results = form.querySelector('[data-location-results]');
  const emptyState = results?.querySelector('[data-location-empty]');
  const options = results ? Array.from(results.querySelectorAll('[data-location-option]')) : [];

  if (!input || !results || !options.length) return;

  let visibleOptions = [];
  let activeIndex = -1;

  const normalize = (value) => value
    .trim()
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, ' ')
    .trim();

  const getScore = (option, query) => {
    if (!query) return 10;

    const name = normalize(option.dataset.name || '');
    const slug = normalize(option.dataset.slug || '');

    if (name === query || slug === query) return 0;
    if (name.startsWith(query)) return 1;
    if (name.split(' ').some((word) => word.startsWith(query))) return 2;
    if (name.includes(query) || slug.includes(query)) return 3;

    let queryIndex = 0;
    for (const character of name) {
      if (character === query[queryIndex]) queryIndex += 1;
      if (queryIndex === query.length) return 4;
    }

    return Number.POSITIVE_INFINITY;
  };

  const setActiveOption = (index) => {
    activeIndex = index < 0 || !visibleOptions.length
      ? -1
      : index % visibleOptions.length;

    options.forEach((option) => {
      const isActive = option === visibleOptions[activeIndex];
      option.classList.toggle('bg-white/60', isActive);
      option.classList.toggle('text-orange-accent', isActive);
      option.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });

    visibleOptions[activeIndex]?.scrollIntoView({ block: 'nearest' });
  };

  const closeResults = () => {
    results.classList.add('hidden');
    input.setAttribute('aria-expanded', 'false');
    setActiveOption(-1);
  };

  const updateResults = () => {
    const query = normalize(input.value);
    const rankedOptions = options
      .map((option, originalIndex) => ({ option, originalIndex, score: getScore(option, query) }))
      .filter(({ score }) => Number.isFinite(score))
      .sort((first, second) => first.score - second.score || first.originalIndex - second.originalIndex);

    visibleOptions = rankedOptions.map(({ option }) => option);
    options.forEach((option) => option.classList.toggle('hidden', !visibleOptions.includes(option)));
    visibleOptions.forEach((option) => results.insertBefore(option, emptyState));
    emptyState?.classList.toggle('hidden', visibleOptions.length > 0);
    results.classList.remove('hidden');
    input.setAttribute('aria-expanded', 'true');
    setActiveOption(-1);
  };

  input.addEventListener('focus', updateResults);
  input.addEventListener('input', updateResults);

  input.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
      event.preventDefault();
      if (results.classList.contains('hidden')) updateResults();
      const nextIndex = activeIndex < 0
        ? (event.key === 'ArrowDown' ? 0 : visibleOptions.length - 1)
        : activeIndex + (event.key === 'ArrowDown' ? 1 : -1);
      setActiveOption(nextIndex < 0 ? visibleOptions.length - 1 : nextIndex);
    } else if (event.key === 'Enter' && activeIndex >= 0) {
      event.preventDefault();
      window.location.href = visibleOptions[activeIndex].href;
    } else if (event.key === 'Escape') {
      closeResults();
    }
  });

  document.addEventListener('click', (event) => {
    if (!form.contains(event.target)) closeResults();
  });

  form.addEventListener('submit', (event) => {
    const query = normalize(input.value);
    const match = options.find((option) => (
      normalize(option.dataset.name || '') === query || normalize(option.dataset.slug || '') === query
    ));

    if (!match?.href) return;

    event.preventDefault();
    window.location.href = match.href;
  });
});

document.querySelectorAll('[data-map-card]').forEach((card) => {
  const id = card.getAttribute('data-map-card');
  const pin = id ? document.querySelector(`[data-map-pin="${id}"]`) : null;

  if (!pin) return;

  card.addEventListener('mouseenter', () => {
    pin.classList.add('scale-105', 'bg-orange-500');
    pin.classList.remove('bg-slate-950');
  });

  card.addEventListener('mouseleave', () => {
    pin.classList.remove('scale-105', 'bg-orange-500');
    pin.classList.add('bg-slate-950');
  });
});

document.querySelectorAll('[data-location-map]').forEach((map) => {
  const pills = Array.from(map.querySelectorAll('[data-location-map-pill]'));
  const pins = Array.from(map.querySelectorAll('[data-location-map-pin]'));

  if (!pills.length || !pins.length) return;

  let activeId = pills.find((pill) => pill.getAttribute('aria-selected') === 'true')?.dataset.locationMapPill
    || pills[0].dataset.locationMapPill;

  const setPinImage = (pin, isActive) => {
    const image = pin.querySelector('img');
    const nextSrc = isActive ? pin.dataset.activeSrc : pin.dataset.inactiveSrc;

    if (image && nextSrc) {
      image.src = nextSrc;
    }
  };

  const setActiveLocation = (id, shouldPersist = true) => {
    if (shouldPersist) {
      activeId = id;
    }

    pills.forEach((pill) => {
      const isActive = pill.dataset.locationMapPill === id;
      pill.classList.toggle('bg-orange-accent', isActive);
      pill.classList.toggle('text-white', isActive);
      pill.classList.toggle('bg-surface-200', !isActive);
      pill.classList.toggle('text-slate-900', !isActive);
      pill.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });

    pins.forEach((pin) => {
      const isActive = pin.dataset.locationMapPin === id;
      pin.classList.toggle('scale-110', isActive);
      pin.classList.toggle('scale-100', !isActive);
      pin.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      setPinImage(pin, isActive);
    });
  };

  [...pills, ...pins].forEach((control) => {
    const id = control.dataset.locationMapPill || control.dataset.locationMapPin;

    control.addEventListener('click', () => setActiveLocation(id));
    control.addEventListener('mouseenter', () => setActiveLocation(id));
  });

  setActiveLocation(activeId);
});

document.querySelectorAll('[data-projects-slider]').forEach((slider) => {
  const slides = Array.from(slider.querySelectorAll('[data-projects-slide]'));
  const slideTrack = slider.querySelector('[data-projects-slides]');
  const prevButtons = Array.from(slider.querySelectorAll('[data-projects-prev]'));
  const nextButtons = Array.from(slider.querySelectorAll('[data-projects-next]'));
  const dots = Array.from(slider.querySelectorAll('[data-projects-dot]'));
  let activeIndex = 0;
  let dragStartX = 0;
  let dragStartY = 0;
  let isDragging = false;
  let hasDragged = false;

  if (!slides.length) return;

  const setActiveSlide = (nextIndex) => {
    activeIndex = Math.max(0, Math.min(nextIndex, slides.length - 1));

    slides.forEach((slide, index) => {
      const isActive = index === activeIndex;
      slide.classList.toggle('opacity-100', isActive);
      slide.classList.toggle('opacity-0', !isActive);
      slide.classList.toggle('pointer-events-none', !isActive);
      slide.classList.toggle('absolute', !isActive);
      slide.classList.toggle('inset-0', !isActive);
      slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
    });

    dots.forEach((dot) => {
      const isActive = Number.parseInt(dot.dataset.projectsDot, 10) === activeIndex;
      const isDark = dot.dataset.projectsDotTheme === 'dark';
      dot.classList.toggle('scale-125', isActive);
      dot.classList.toggle('opacity-100', isActive);
      dot.classList.toggle('opacity-70', !isActive);

      if (isDark) {
        dot.classList.toggle('bg-slate-900', isActive);
        dot.classList.toggle('bg-slate-300', !isActive);
      }

      dot.setAttribute('aria-current', isActive ? 'true' : 'false');
    });

    prevButtons.forEach((button) => {
      button.disabled = activeIndex === 0;
    });

    nextButtons.forEach((button) => {
      button.disabled = activeIndex === slides.length - 1;
    });
  };

  prevButtons.forEach((button) => {
    button.addEventListener('click', () => setActiveSlide(activeIndex - 1));
  });

  nextButtons.forEach((button) => {
    button.addEventListener('click', () => setActiveSlide(activeIndex + 1));
  });

  dots.forEach((dot) => {
    dot.addEventListener('click', () => {
      setActiveSlide(Number.parseInt(dot.dataset.projectsDot, 10) || 0);
    });
  });

  if (slideTrack && slides.length > 1) {
    slideTrack.addEventListener('dragstart', (event) => {
      event.preventDefault();
    });

    slideTrack.addEventListener('selectstart', (event) => {
      if (isDragging) {
        event.preventDefault();
      }
    });

    slideTrack.addEventListener('pointerdown', (event) => {
      if (event.button && event.button !== 0) return;
      if (event.target.closest('a, button, input, select, textarea')) return;

      dragStartX = event.clientX;
      dragStartY = event.clientY;
      isDragging = true;
      hasDragged = false;
      slideTrack.setPointerCapture?.(event.pointerId);
    });

    slideTrack.addEventListener('pointermove', (event) => {
      if (!isDragging) return;

      const deltaX = event.clientX - dragStartX;
      const deltaY = event.clientY - dragStartY;

      if (Math.abs(deltaX) > 12 && Math.abs(deltaX) > Math.abs(deltaY)) {
        hasDragged = true;
        window.getSelection()?.removeAllRanges();
      }
    });

    slideTrack.addEventListener('pointerup', (event) => {
      if (!isDragging) return;

      const deltaX = event.clientX - dragStartX;
      const deltaY = event.clientY - dragStartY;
      isDragging = false;
      slideTrack.releasePointerCapture?.(event.pointerId);

      if (Math.abs(deltaX) < 45 || Math.abs(deltaX) < Math.abs(deltaY)) return;

      setActiveSlide(activeIndex + (deltaX < 0 ? 1 : -1));
    });

    slideTrack.addEventListener('click', (event) => {
      if (!hasDragged) return;

      event.preventDefault();
      event.stopPropagation();
      hasDragged = false;
    }, true);

    slideTrack.addEventListener('pointercancel', () => {
      isDragging = false;
      hasDragged = false;
    });
  }

  setActiveSlide(0);
});

const initKantoorruimteMaps = () => {
  const mapElements = document.querySelectorAll('[data-kantoorruimte-map]');

  if (!mapElements.length || typeof window.google?.maps?.Map !== 'function') return;

  mapElements.forEach((mapElement) => {
    if (mapElement.dataset.mapReady === 'true') return;

    mapElement.dataset.mapReady = 'true';

    const centerQuery = mapElement.dataset.mapQuery || mapElement.dataset.mapCenter || 'Nederland';
    let items = [];

    try {
      items = JSON.parse(mapElement.dataset.mapItems || '[]');
    } catch {
      items = [];
    }

    const map = new window.google.maps.Map(mapElement, {
      center: { lat: 52.1326, lng: 5.2913 },
      zoom: 8,
      mapTypeControl: false,
      fullscreenControl: false,
      streetViewControl: false,
      clickableIcons: true,
      styles: [
        {
          featureType: 'poi.business',
          stylers: [{ visibility: 'off' }],
        },
      ],
    });

    mapElement.werkstekMap = map;

    const geocoder = new window.google.maps.Geocoder();
    const bounds = new window.google.maps.LatLngBounds();
    const infoWindow = new window.google.maps.InfoWindow({
      headerDisabled: true,
    });
    const markersById = new Map();
    let selectedMarker = null;
    const escapeHtml = (value) => String(value || '').replace(/[&<>"']/g, (character) => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;',
    })[character]);

    const setMarkerState = (marker, isActive) => {
      marker.setIcon({
        path: window.google.maps.SymbolPath.CIRCLE,
        fillColor: isActive ? '#f97316' : '#102335',
        fillOpacity: 1,
        strokeColor: '#FCF8F3',
        strokeWeight: 3,
        scale: isActive ? 11 : 8,
      });
      marker.setZIndex(isActive ? 20 : 1);
    };

    const createMarker = (item, position) => {
      const marker = new window.google.maps.Marker({
        map,
        position,
        title: item.title,
        cursor: 'pointer',
      });

      setMarkerState(marker, false);
      bounds.extend(position);
      markersById.set(String(item.id), marker);

      const showInfoWindow = () => {
        const image = item.image
          ? `<img src="${escapeHtml(item.image)}" alt="" style="display:block;width:220px;height:112px;object-fit:cover;">`
          : '';
        const address = item.address || item.title;
        const location = item.location && !String(address).toLowerCase().includes(String(item.location).toLowerCase())
          ? `<span style="display:block;margin-top:2px;color:#64748b;">${escapeHtml(item.location)}</span>`
          : '';
        const price = item.price
          ? `<span style="display:block;margin-top:8px;font-weight:700;color:#fc6321;">Vanaf ${escapeHtml(String(item.price).replace(/^vanaf\s*/i, ''))}</span>`
          : '';

        infoWindow.setContent(`
          <a href="${escapeHtml(item.url)}" style="display:block;width:220px;overflow:hidden;border-radius:16px;background:#FCF8F3;color:#0F293A;box-shadow:0 12px 30px rgba(15,41,58,.16);text-decoration:none;">
            ${image}
            <div style="padding:14px 16px 16px;line-height:1.35;">
              <strong style="display:block;font-size:14px;">${escapeHtml(address)}</strong>
              ${location}
              ${price}
            </div>
          </a>
        `);
        infoWindow.open({ map, anchor: marker, shouldFocus: false });
      };

      marker.addListener('click', () => {
        if (selectedMarker && selectedMarker !== marker) {
          setMarkerState(selectedMarker, false);
        }

        selectedMarker = marker;
        setMarkerState(marker, true);
        showInfoWindow();
      });

      return marker;
    };

    const fitVisibleMarkers = () => {
      if (!markersById.size) return;

      if (markersById.size === 1) {
        map.setCenter(markersById.values().next().value.getPosition());
        map.setZoom(14);
        return;
      }

      map.fitBounds(bounds, 80);
    };

    const geocodeCenter = () => {
      geocoder.geocode({ address: centerQuery }, (results, status) => {
        if (status !== 'OK' || !results?.[0]) return;

        map.setCenter(results[0].geometry.location);

        if (!markersById.size) {
          map.setZoom(12);
        }
      });
    };

    geocodeCenter();

    const resolvePosition = (item) => {
      if (Number.isFinite(item.lat) && Number.isFinite(item.lng)) {
        return Promise.resolve({ item, position: { lat: item.lat, lng: item.lng } });
      }

      return new Promise((resolve) => {
        geocoder.geocode({ address: item.query || item.title }, (results, status) => {
          if (status !== 'OK' || !results?.[0]) {
            console.warn(`Geen kaartpositie gevonden voor ${item.title}: ${status}`);
            resolve(null);
            return;
          }

          resolve({ item, position: results[0].geometry.location });
        });
      });
    };

    Promise.all(items.map(resolvePosition)).then((resolvedItems) => {
      resolvedItems.filter(Boolean).forEach(({ item, position }) => {
        createMarker(item, position);
      });

      fitVisibleMarkers();
    });

    map.addListener('click', () => {
      infoWindow.close();

      if (selectedMarker) {
        setMarkerState(selectedMarker, false);
        selectedMarker = null;
      }
    });

    document.querySelectorAll('[data-map-card]').forEach((card) => {
      const marker = markersById.get(card.getAttribute('data-map-card'));

      card.addEventListener('mouseenter', () => {
        const activeMarker = markersById.get(card.getAttribute('data-map-card'));
        if (!activeMarker) return;

        setMarkerState(activeMarker, true);
      });

      card.addEventListener('mouseleave', () => {
        const activeMarker = markersById.get(card.getAttribute('data-map-card'));
        if (!activeMarker) return;

        setMarkerState(activeMarker, false);
      });

      if (marker) {
        setMarkerState(marker, false);
      }
    });
  });
};

window.addEventListener('werkstek-google-maps-ready', initKantoorruimteMaps);
window.werkstekInitKantoorruimteMaps = initKantoorruimteMaps;
initKantoorruimteMaps();
if (window.werkstekGoogleMapsReady) initKantoorruimteMaps();
window.addEventListener('load', initKantoorruimteMaps);

document.querySelectorAll('[data-kantoorruimte-archive]').forEach((archive) => {
  const toggleButton = archive.querySelector('[data-archive-view-toggle]');
  const results = archive.querySelector('[data-archive-results]');
  const mapPanel = archive.querySelector('[data-archive-map-panel]');
  const content = archive.querySelector('[data-archive-content]');
  const sortToggles = archive.querySelectorAll('[data-archive-sort-toggle]');
  const mapIcon = archive.querySelector('[data-archive-view-icon="map"]');
  const listIcon = archive.querySelector('[data-archive-view-icon="list"]');
  const mapElement = archive.querySelector('[data-kantoorruimte-map]');

  if (!toggleButton || !results || !mapPanel) return;

  const setView = (view) => {
    const isMapView = view === 'map';

    archive.dataset.archiveView = view;
    results.classList.toggle('hidden', isMapView);
    mapPanel.classList.toggle('hidden', !isMapView);
    mapIcon?.classList.toggle('hidden', isMapView);
    listIcon?.classList.toggle('hidden', !isMapView);
    toggleButton.setAttribute('aria-label', isMapView ? 'Toon lijst' : 'Toon kaart');
    toggleButton.setAttribute('aria-pressed', isMapView ? 'true' : 'false');

    sortToggles.forEach((sortToggle) => {
      sortToggle.style.display = isMapView ? 'none' : '';
    });

    if (content && window.matchMedia('(max-width: 1279px)').matches) {
      content.style.paddingBottom = isMapView ? '1.25rem' : '';
    }

    if (isMapView) {
      initKantoorruimteMaps();

      window.setTimeout(() => {
        if (!mapElement?.werkstekMap || !window.google?.maps) return;

        window.google.maps.event.trigger(mapElement.werkstekMap, 'resize');
      }, 80);
    }
  };

  toggleButton.addEventListener('click', () => {
    setView(archive.dataset.archiveView === 'map' ? 'list' : 'map');
  });

  window.addEventListener('resize', () => {
    if (window.matchMedia('(min-width: 1280px)').matches) {
      results.classList.remove('hidden');
      mapPanel.classList.remove('hidden');
      sortToggles.forEach((sortToggle) => {
        sortToggle.style.display = '';
      });
      if (content) content.style.paddingBottom = '';
      return;
    }

    setView(archive.dataset.archiveView === 'map' ? 'map' : 'list');
  });
});

document.querySelectorAll('[data-community-video-tabs]').forEach((tabList) => {
  const tabs = Array.from(tabList.querySelectorAll('[data-community-video-tab]'));
  if (!tabs.length) return;

  const componentId = tabList.getAttribute('data-community-video-tabs');
  const panels = Array.from(
    document.querySelectorAll(`[data-community-video-panels="${componentId}"] [data-community-video-panel]`)
  );

  const setActiveTab = (activeTab) => {
    const activePanelId = activeTab.getAttribute('data-panel-id');

    tabs.forEach((tab) => {
      const isActive = tab === activeTab;
      const logo = tab.querySelector('img');
      tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
      tab.classList.toggle('border-orange-accent', isActive);
      tab.classList.toggle('shadow-[0_14px_30px_rgba(15,23,42,0.08)]', isActive);
      tab.classList.toggle('bg-[#FCF8F3]', isActive);
      tab.classList.toggle('border-surface', !isActive);
      tab.classList.toggle('bg-[#FCF8F3]/80', !isActive);

      if (logo) {
        logo.classList.toggle('opacity-100', isActive);
        logo.classList.toggle('grayscale-0', isActive);
        logo.classList.toggle('opacity-25', !isActive);
        logo.classList.toggle('grayscale', !isActive);
      }
    });

    panels.forEach((panel) => {
      const isActivePanel = panel.id === activePanelId;
      panel.classList.toggle('hidden', !isActivePanel);

      if (!isActivePanel) {
        panel.querySelectorAll('[data-community-video-player]').forEach((player) => {
          player.classList.remove('is-playing');

          player.querySelectorAll('video').forEach((video) => {
            video.pause();
            video.currentTime = 0;
          });

          player.querySelectorAll('iframe').forEach((iframe) => {
            const originalSrc = iframe.dataset.originalSrc || iframe.getAttribute('src');
            if (!originalSrc) return;

            iframe.src = 'about:blank';
            iframe.src = originalSrc;
          });
        });
      }
    });
  };

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => setActiveTab(tab));
  });
});

document.querySelectorAll('[data-community-video-player]').forEach((player) => {
  const playButton = player.querySelector('[data-community-video-play]');
  const video = player.querySelector('video');
  const iframe = player.querySelector('iframe');

  if (!playButton) return;

  player.querySelectorAll('iframe').forEach((playerIframe) => {
    const originalSrc = playerIframe.getAttribute('src');
    if (originalSrc) playerIframe.dataset.originalSrc = originalSrc;
  });

  const setPlaying = (isPlaying) => {
    player.classList.toggle('is-playing', isPlaying);
  };

  const addAutoplay = (src) => {
    if (!src) return src;

    try {
      const url = new URL(src, window.location.href);
      url.searchParams.set('autoplay', '1');
      return url.toString();
    } catch (error) {
      const separator = src.includes('?') ? '&' : '?';
      return `${src}${separator}autoplay=1`;
    }
  };

  playButton.addEventListener('click', () => {
    setPlaying(true);

    if (video) {
      video.play();
      return;
    }

    if (iframe) {
      iframe.src = addAutoplay(iframe.getAttribute('src'));
    }
  });

  if (video) {
    video.addEventListener('play', () => setPlaying(true));
    video.addEventListener('pause', () => setPlaying(false));
    video.addEventListener('ended', () => setPlaying(false));
  }
});

document.querySelectorAll('[data-counters]').forEach((section) => {
  const numbers = Array.from(section.querySelectorAll('[data-counter-number]'));
  if (!numbers.length) return;

  const animateCounters = () => {
    numbers.forEach((numberElement) => {
      const target = Number.parseInt(numberElement.getAttribute('data-counter-target'), 10) || 0;
      const duration = 1300;
      const startTime = window.performance.now();

      const update = (currentTime) => {
        const progress = Math.min((currentTime - startTime) / duration, 1);
        const easedProgress = 1 - Math.pow(1 - progress, 3);
        numberElement.textContent = Math.round(target * easedProgress).toString();

        if (progress < 1) {
          window.requestAnimationFrame(update);
        } else {
          numberElement.textContent = target.toString();
        }
      };

      window.requestAnimationFrame(update);
    });
  };

  if (!('IntersectionObserver' in window)) {
    animateCounters();
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      animateCounters();
      observer.disconnect();
    });
  }, {
    threshold: 0.35,
    rootMargin: '0px 0px -12% 0px',
  });

  observer.observe(section);
});

document.querySelectorAll('[data-testimonials]').forEach((slider) => {
  const slides = Array.from(slider.querySelectorAll('[data-testimonial-slide]'));
  const prevButtons = Array.from(slider.querySelectorAll('[data-testimonials-prev]'));
  const nextButtons = Array.from(slider.querySelectorAll('[data-testimonials-next]'));
  let activeIndex = 0;

  if (!slides.length || !prevButtons.length || !nextButtons.length) return;

  const setActiveSlide = (nextIndex) => {
    activeIndex = Math.max(0, Math.min(nextIndex, slides.length - 1));

    slides.forEach((slide, index) => {
      const isActive = index === activeIndex;
      slide.classList.toggle('hidden', !isActive);
      slide.classList.toggle('opacity-100', isActive);
      slide.classList.toggle('opacity-0', !isActive);
      slide.classList.toggle('pointer-events-none', !isActive);
      slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
    });

    prevButtons.forEach((button) => {
      button.disabled = activeIndex === 0;
    });

    nextButtons.forEach((button) => {
      button.disabled = activeIndex === slides.length - 1;
    });
  };

  prevButtons.forEach((button) => {
    button.addEventListener('click', () => setActiveSlide(activeIndex - 1));
  });

  nextButtons.forEach((button) => {
    button.addEventListener('click', () => setActiveSlide(activeIndex + 1));
  });

  setActiveSlide(0);
});

document.querySelectorAll('[data-faq]').forEach((faq) => {
  const items = Array.from(faq.querySelectorAll('details'));

  items.forEach((item) => {
    item.addEventListener('toggle', () => {
      if (!item.open) return;

      items.forEach((otherItem) => {
        if (otherItem !== item) {
          otherItem.open = false;
        }
      });
    });
  });
});

document.querySelectorAll('[data-accordion]').forEach((accordion) => {
  const items = Array.from(accordion.querySelectorAll('[data-accordion-item]'));
  if (!items.length) return;

  const setItemState = (item, isOpen) => {
    const trigger = item.querySelector('[data-accordion-trigger]');
    const panel = item.querySelector('[data-accordion-panel]');
    if (!trigger || !panel) return;

    item.classList.toggle('w-full', isOpen);
    item.classList.toggle('rounded-[0.75rem]', isOpen);
    item.classList.toggle('bg-dark-main', isOpen);
    item.classList.toggle('text-white', isOpen);
    item.classList.toggle('rounded-full', !isOpen);
    item.classList.toggle('bg-surface-200', !isOpen);
    item.classList.toggle('text-[#a4a4a4]', !isOpen);

    trigger.classList.toggle('w-full', isOpen);
    trigger.classList.toggle('px-5', isOpen);
    trigger.classList.toggle('pb-3', isOpen);
    trigger.classList.toggle('pt-4', isOpen);
    trigger.classList.toggle('text-left', isOpen);
    trigger.classList.toggle('py-3', !isOpen);
    trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

    panel.style.height = isOpen ? `${panel.scrollHeight}px` : '0px';
  };

  const openItem = (activeItem) => {
    items.forEach((item) => setItemState(item, item === activeItem));
  };

  items.forEach((item) => {
    item.querySelector('[data-accordion-trigger]')?.addEventListener('click', () => {
      openItem(item);
    });
  });

  const activeItem = items.find((item) => item.querySelector('[data-accordion-trigger]')?.getAttribute('aria-expanded') === 'true') || items[0];
  openItem(activeItem);

  window.addEventListener('resize', () => {
    items.forEach((item) => {
      const trigger = item.querySelector('[data-accordion-trigger]');
      const panel = item.querySelector('[data-accordion-panel]');
      if (trigger?.getAttribute('aria-expanded') === 'true' && panel) {
        panel.style.height = `${panel.scrollHeight}px`;
      }
    });
  });
});

const menuRoot = document.querySelector('[data-menu-root]');
const menuPanel = menuRoot?.querySelector('.mobile-menu__panel');
const menuOpenButton = document.querySelector('[data-menu-open]');
const menuCloseButtons = menuRoot ? Array.from(menuRoot.querySelectorAll('[data-menu-close]')) : [];

if (menuRoot && menuPanel && menuOpenButton) {
  const setMenuState = (isOpen) => {
    menuRoot.classList.toggle('is-open', isOpen);
    menuRoot.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    menuOpenButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    document.body.classList.toggle('mobile-menu-open', isOpen);

    if (isOpen) {
      menuPanel.focus();
    } else {
      menuOpenButton.focus();
    }
  };

  menuOpenButton.addEventListener('click', () => setMenuState(true));

  menuCloseButtons.forEach((button) => {
    button.addEventListener('click', () => setMenuState(false));
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menuRoot.classList.contains('is-open')) {
      setMenuState(false);
    }
  });
}

document.querySelectorAll('[data-filter-root]').forEach((filterRoot) => {
  const filterPanel = filterRoot.querySelector('.filter-overlay__panel');
  const openButtons = Array.from(document.querySelectorAll('[data-filter-open]'));
  const closeButtons = Array.from(filterRoot.querySelectorAll('[data-filter-close]'));
  const locationToggle = filterRoot.querySelector('[data-filter-locations-toggle]');
  const extraLocations = Array.from(filterRoot.querySelectorAll('[data-filter-location-extra="true"]'));
  const moreLabel = filterRoot.querySelector('[data-filter-more-label]');
  const lessLabel = filterRoot.querySelector('[data-filter-less-label]');

  if (!filterPanel || !openButtons.length) return;

  const setFilterState = (isOpen) => {
    filterRoot.classList.toggle('is-open', isOpen);
    filterRoot.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    document.body.classList.toggle('filter-overlay-open', isOpen);

    openButtons.forEach((button) => {
      button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    if (isOpen) {
      filterPanel.focus();
    } else {
      openButtons[0]?.focus();
    }
  };

  openButtons.forEach((button) => {
    button.addEventListener('click', () => setFilterState(true));
  });

  closeButtons.forEach((button) => {
    button.addEventListener('click', () => setFilterState(false));
  });

  locationToggle?.addEventListener('click', () => {
    const isExpanded = locationToggle.getAttribute('aria-expanded') === 'true';

    extraLocations.forEach((locationLink) => {
      locationLink.classList.toggle('hidden', isExpanded);
    });

    moreLabel?.classList.toggle('hidden', !isExpanded);
    lessLabel?.classList.toggle('hidden', isExpanded);
    locationToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && filterRoot.classList.contains('is-open')) {
      setFilterState(false);
    }
  });
});
