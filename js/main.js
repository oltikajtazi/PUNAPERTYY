(function () {
  'use strict';
  var root = document.documentElement;
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var store = {
    get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
    set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
  };

  /* ---------- Theme ---------- */
  var themeBtn = $('#theme');
  function setTheme(t) {
    root.setAttribute('data-theme', t);
    store.set('theme', t);
    var meta = $('meta[name="theme-color"]');
    if (meta) meta.setAttribute('content', t === 'dark' ? '#0d0f12' : '#f6f4ef');
  }
  themeBtn.addEventListener('click', function () {
    setTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
  });

  /* ---------- Language ---------- */
  var langSel = $('#lang');
  function applyLang(lang, persist) {
    var dict = (window.I18N || {})[lang];
    if (!dict) return;
    root.lang = lang;
    $$('[data-i18n]').forEach(function (el) {
      var v = dict[el.getAttribute('data-i18n')];
      if (v != null) el.textContent = v;
    });
    $$('[data-i18n-html]').forEach(function (el) {
      var v = dict[el.getAttribute('data-i18n-html')];
      if (v != null) el.innerHTML = v;
    });
    langSel.value = lang;
    if (persist) store.set('lang', lang); // only a manual choice is remembered
  }
  langSel.addEventListener('change', function () { applyLang(langSel.value, true); });

  // Pick the starting language:
  // 1) the visitor's own earlier choice
  // 2) their browser language(s), if we have a translation for it
  // 3) their time zone as a location hint (Germany/Austria/Switzerland -> German, Albania -> Albanian)
  // 4) English (the default written in the HTML)
  var TZ_LANG = {
    'Europe/Berlin': 'de', 'Europe/Busingen': 'de', 'Europe/Vienna': 'de',
    'Europe/Zurich': 'de', 'Europe/Vaduz': 'de', 'Europe/Tirane': 'sq'
  };
  function detectLang() {
    var supported = window.I18N || {};
    var saved = store.get('lang');
    if (saved && supported[saved]) return saved;

    var prefs = (navigator.languages && navigator.languages.length) ? navigator.languages : [navigator.language || ''];
    for (var i = 0; i < prefs.length; i++) {
      var code = String(prefs[i]).toLowerCase().split('-')[0];
      if (supported[code]) return code;
    }

    try {
      var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
      if (TZ_LANG[tz] && supported[TZ_LANG[tz]]) return TZ_LANG[tz];
    } catch (e) {}

    return 'en';
  }
  var startLang = detectLang();
  if (startLang !== 'en') applyLang(startLang, false);
  else langSel.value = 'en';

  /* ---------- Mobile menu ---------- */
  var menuBtn = $('#menu'), nav = $('#nav');
  menuBtn.addEventListener('click', function () {
    var open = nav.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  $$('#nav a').forEach(function (a) {
    a.addEventListener('click', function () {
      nav.classList.remove('open');
      menuBtn.setAttribute('aria-expanded', 'false');
    });
  });

  /* ---------- Reveal on scroll ---------- */
  var items = $$('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    items.forEach(function (el) { io.observe(el); });
  } else {
    items.forEach(function (el) { el.classList.add('in'); });
  }

  /* ---------- Project screenshots: local file first, then live site image, then gradient ---------- */
  $$('.shot img').forEach(function (img) {
    function fail() {
      var fb = img.getAttribute('data-fallback');
      if (fb && img.getAttribute('src') !== fb) {
        img.setAttribute('src', fb);
      } else {
        var bar = img.parentElement.querySelector('.shot-bar span');
        img.parentElement.setAttribute('data-label', bar ? bar.textContent : '');
        img.parentElement.classList.add('noimg');
        img.remove();
      }
    }
    img.addEventListener('error', fail);
    if (img.complete && img.naturalWidth === 0) fail();
  });

  /* ---------- Live preview modal ---------- */
  var dlg = $('#preview'), frame = $('#preview-frame');
  $$('.js-preview').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url = btn.getAttribute('data-url');
      $('#preview-title').textContent = btn.getAttribute('data-title');
      $('#preview-open').setAttribute('href', url);
      frame.src = url;
      if (typeof dlg.showModal === 'function') dlg.showModal();
      else window.open(url, '_blank', 'noopener');
    });
  });
  function closePreview() { if (dlg.open) dlg.close(); }
  $('#preview-close').addEventListener('click', closePreview);
  dlg.addEventListener('close', function () { frame.src = 'about:blank'; });
  dlg.addEventListener('click', function (e) { if (e.target === dlg) closePreview(); });

  /* ---------- Footer year ---------- */
  var y = $('#year'); if (y) y.textContent = new Date().getFullYear();
})();
