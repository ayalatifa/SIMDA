/* SIMDA — interaksi UI bersama (tanpa mengubah logika sistem) */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  /* ---- Sidebar mobile ---- */
  var burger = $('#burger'), overlay = $('#overlay');
  function closeSide() { document.body.classList.remove('side-open'); }
  if (burger) burger.addEventListener('click', function () { document.body.classList.toggle('side-open'); });
  if (overlay) overlay.addEventListener('click', closeSide);

  /* ---- Navbar publik mobile ---- */
  var navToggle = $('#navToggle'), mobilePanel = $('#mobilePanel');
  if (navToggle && mobilePanel) {
    navToggle.addEventListener('click', function () {
      var open = mobilePanel.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ---- Dropdown profil ---- */
  var profile = $('#profile'), profileBtn = $('#profileBtn');
  function closeProfile() {
    if (!profile) return;
    profile.classList.remove('open');
    if (profileBtn) profileBtn.setAttribute('aria-expanded', 'false');
  }
  if (profile && profileBtn) {
    profileBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = profile.classList.toggle('open');
      profileBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('click', function (e) { if (!profile.contains(e.target)) closeProfile(); });
  }

  /* ---- Tampil/sembunyi password ---- */
  $$('.toggle-pass').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.getElementById(btn.getAttribute('data-target'));
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
      btn.querySelector('.eye-on').hidden = show;
      btn.querySelector('.eye-off').hidden = !show;
    });
  });

  /* ---- Popup / dialog generik ---- */
  function openM(m) {
    if (!m) return;
    m.classList.add('show');
    document.body.classList.add('modal-open');
  }
  function closeM(m) {
    if (!m) return;
    m.classList.remove('show');
    if (!$('.modal.show')) document.body.classList.remove('modal-open');
  }
  document.addEventListener('click', function (e) {
    var o = e.target.closest('[data-open]');
    if (o) { e.preventDefault(); closeSide(); openM($(o.getAttribute('data-open'))); return; }
    var c = e.target.closest('[data-close]');
    if (c) { e.preventDefault(); closeM(c.closest('.modal')); return; }
    if (e.target.classList && e.target.classList.contains('modal')) closeM(e.target);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    closeSide(); closeProfile(); closeKebab();
    var open = $$('.modal.show'); if (open.length) closeM(open[open.length - 1]);
  });
  $$('.modal[data-autoopen]').forEach(openM);

  /* ---- Konfirmasi hapus (menggantikan confirm() bawaan browser) ---- */
  var cModal = $('#confirmModal'), pendingForm = null;
  if (cModal) {
    $('#confirmNo', cModal).addEventListener('click', function () { pendingForm = null; });
    $('#confirmYes', cModal).addEventListener('click', function () {
      var f = pendingForm; closeM(cModal); pendingForm = null; if (f) f.submit();
    });
  }
  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form.matches || !form.matches('form[data-confirm]') || !cModal) return;
    e.preventDefault();
    pendingForm = form;
    $('#confirmText', cModal).textContent = form.getAttribute('data-confirm');
    closeKebab();
    openM(cModal);
    $('#confirmYes', cModal).focus();
  });

  /* ---- Menu aksi titik tiga ---- */
  var openKebab = null;
  function closeKebab() {
    if (!openKebab) return;
    openKebab.menu.classList.remove('show');
    openKebab.btn.setAttribute('aria-expanded', 'false');
    openKebab = null;
  }
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.kebab-btn');
    if (!btn) { if (!e.target.closest('.kebab-menu')) closeKebab(); return; }
    e.stopPropagation();
    var menu = btn.parentNode.querySelector('.kebab-menu');
    var same = openKebab && openKebab.menu === menu;
    closeKebab();
    if (same) return;
    menu.classList.add('show');
    var r = btn.getBoundingClientRect(), mw = menu.offsetWidth, mh = menu.offsetHeight;
    var left = Math.min(Math.max(8, r.right - mw), window.innerWidth - mw - 8);
    var top = r.bottom + 6;
    if (top + mh > window.innerHeight - 8) top = Math.max(8, r.top - mh - 6);
    menu.style.left = left + 'px'; menu.style.top = top + 'px';
    btn.setAttribute('aria-expanded', 'true');
    openKebab = { btn: btn, menu: menu };
  });
  window.addEventListener('scroll', closeKebab, true);
  window.addEventListener('resize', closeKebab);

  /* ---- Popup konfirmasi pengajuan: tolak -> wajib catatan ---- */
  document.addEventListener('click', function (e) {
    var t = e.target.closest('[data-reject-open]');
    var c = e.target.closest('[data-reject-cancel]');
    if (!t && !c) return;
    var item = (t || c).closest('.notif-item');
    var box = $('.reject-box', item), acts = $('.notif-actions', item), ta = $('textarea', box);
    if (t) { box.hidden = false; acts.hidden = true; ta.disabled = false; ta.required = true; ta.focus(); }
    else { box.hidden = true; acts.hidden = false; ta.disabled = true; ta.required = false; ta.value = ''; }
  });

  /* ---- Form pengajuan: blok informasi sesuai layanan terpilih ---- */
  function kategoriLayanan(v) {
    v = (v || '').toLowerCase();
    if (!v) return '';
    if (v.indexOf('rapor') > -1) return 'rapor';
    if (v.indexOf('legalisir') === 0) return 'legalisir';
    if (v.indexOf('ijazah') > -1) return 'ijazah';
    return 'lainnya';
  }
  function tambahHari(iso, n) {
    var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso || '');
    if (!m) return '';
    var d = new Date(Date.UTC(+m[1], +m[2] - 1, +m[3] + n));
    var p = function (x) { return (x < 10 ? '0' : '') + x; };
    return p(d.getUTCDate()) + '/' + p(d.getUTCMonth() + 1) + '/' + d.getUTCFullYear();
  }
  $$('[data-service-form]').forEach(function (form) {
    var sel = $('[data-service-select]', form);
    var pinjam = $('[data-pinjam]', form), batas = $('[data-batas]', form);
    function apply() {
      var k = kategoriLayanan(sel.value);
      $$('[data-block]', form).forEach(function (b) { b.disabled = b.getAttribute('data-block') !== k; });
    }
    function hitung() {
      if (pinjam && batas && !batas.hasAttribute('data-fixed')) batas.value = tambahHari(pinjam.value, 7);
    }
    if (sel) { sel.addEventListener('change', apply); apply(); }
    if (pinjam) { pinjam.addEventListener('input', hitung); pinjam.addEventListener('change', hitung); if (pinjam.value) hitung(); }
  });

  /* ---- Pencarian & filter tabel/daftar sisi klien ---- */
  $$('[data-live-search]').forEach(function (input) {
    var scope = document.querySelector(input.getAttribute('data-live-search'));
    if (!scope) return;
    var items = $$('[data-search-item]', scope);
    var info = $('[data-result-info]');
    var emptyBox = $('[data-search-empty]');
    var chipFilter = '';
    function apply() {
      var q = input.value.trim().toLowerCase(), shown = 0;
      items.forEach(function (it) {
        var okText = !q || it.textContent.toLowerCase().indexOf(q) > -1;
        var okChip = !chipFilter || it.getAttribute('data-status') === chipFilter;
        var ok = okText && okChip;
        it.hidden = !ok;
        if (ok) shown++;
      });
      if (info) info.textContent = shown + ' dari ' + items.length + ' data';
      if (emptyBox) emptyBox.hidden = shown !== 0 || items.length === 0;
    }
    input.addEventListener('input', apply);
    $$('[data-chip]').forEach(function (chip) {
      chip.addEventListener('click', function () {
        $$('[data-chip]').forEach(function (c) { c.classList.remove('active'); });
        chip.classList.add('active');
        chipFilter = chip.getAttribute('data-chip');
        apply();
      });
    });
    apply();
  });

  /* ---- Notifikasi pengajuan (admin) ---- */
  var notif = $('#notificationLink');
  if (notif && notif.dataset.endpoint) {
    var badge = $('#notificationBadge');
    var setCount = function (n) {
      var t = n > 99 ? '99+' : String(n);
      badge.textContent = t; badge.hidden = n === 0;
      notif.classList.toggle('has-notif', n > 0);
      var c = $('#notifCount'); if (c) c.textContent = n;
    };
    var update = function () {
      fetch(notif.dataset.endpoint, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (d) { if (d) setCount(Number(d.jumlah) || 0); })
        .catch(function () {});
    };
    update(); setInterval(update, 5000);

    /* segarkan isi popup setiap kali dibuka */
    notif.addEventListener('click', function () {
      if (!notif.dataset.refresh) return;
      fetch(notif.dataset.refresh, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store', credentials: 'same-origin' })
        .then(function (r) { return r.ok ? r.text() : null; })
        .then(function (html) {
          if (!html) return;
          var doc = new DOMParser().parseFromString(html, 'text/html');
          var fresh = doc.getElementById('notifBody'), cur = document.getElementById('notifBody');
          if (fresh && cur && !cur.querySelector('.reject-box:not([hidden])')) cur.innerHTML = fresh.innerHTML;
        }).catch(function () {});
    });
  }

  /* ---- Animasi bar laporan ---- */
  $$('.bar-fill').forEach(function (b) {
    var w = b.getAttribute('data-w') || '0';
    requestAnimationFrame(function () { setTimeout(function () { b.style.width = w + '%'; }, 80); });
  });

  /* ---- Toast sukses otomatis hilang ---- */
  var toast = $('.toast');
  if (toast) setTimeout(function () { toast.classList.add('out'); setTimeout(function () { toast.remove(); }, 400); }, 4200);

  /* ---- Cegah kirim ganda ---- */
  window.addEventListener('pageshow', function (e) {
    if (!e.persisted) return;
    $$('form[data-once] button[type=submit]').forEach(function (b) { b.disabled = false; if (b.dataset.label) b.innerHTML = b.dataset.label; });
  });
  $$('form[data-once]').forEach(function (f) {
    f.addEventListener('submit', function () {
      var b = f.querySelector('button[type=submit]');
      if (b) { b.dataset.label = b.innerHTML; setTimeout(function () { b.disabled = true; b.textContent = 'Memproses…'; }, 0); }
    });
  });
})();
