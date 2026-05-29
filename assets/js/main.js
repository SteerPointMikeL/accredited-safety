/* Accredited Safety Solutions — main.js */

(function () {
  // ---------- Theme toggle ----------
  const root = document.documentElement;
  const toggle = document.querySelector('[data-theme-toggle]');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)');
  let theme = systemPrefersDark.matches ? 'dark' : 'light';
  setTheme(theme);

  function setTheme(mode) {
    theme = mode;
    root.setAttribute('data-theme', mode);
    if (toggle) {
      toggle.setAttribute('aria-label', 'Switch to ' + (mode === 'dark' ? 'light' : 'dark') + ' mode');
      toggle.innerHTML =
        mode === 'dark'
          ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>'
          : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
    }
  }

  if (toggle) {
    toggle.addEventListener('click', () => setTheme(theme === 'dark' ? 'light' : 'dark'));
  }

  // ---------- Mobile nav ----------
  const navToggle = document.querySelector('[data-nav-toggle]');
  const navLinks = document.querySelector('[data-nav-links]');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      const open = navLinks.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', String(open));
      if (!open) {
        navLinks.querySelectorAll('.has-dropdown.is-open').forEach((item) => closeDropdown(item));
      }
    });
  }

  // ---------- Primary nav dropdowns ----------
  const dropdownParents = navLinks
    ? Array.from(navLinks.querySelectorAll('.has-dropdown'))
    : [];

  function openDropdown(parent) {
    parent.classList.add('is-open');
    const trigger = parent.querySelector(':scope > a');
    const toggle = parent.querySelector(':scope > .nav-submenu-toggle');
    if (trigger) trigger.setAttribute('aria-expanded', 'true');
    if (toggle) toggle.setAttribute('aria-expanded', 'true');
  }

  function closeDropdown(parent) {
    parent.classList.remove('is-open');
    const trigger = parent.querySelector(':scope > a');
    const toggle = parent.querySelector(':scope > .nav-submenu-toggle');
    if (trigger) trigger.setAttribute('aria-expanded', 'false');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
  }

  function closeAllDropdowns(except) {
    dropdownParents.forEach((parent) => {
      if (parent !== except) closeDropdown(parent);
    });
  }

  const isMobileViewport = () => window.matchMedia('(max-width: 980px)').matches;
  const isCoarsePointer = () => window.matchMedia('(hover: none), (pointer: coarse)').matches;

  dropdownParents.forEach((parent) => {
    const toggle = parent.querySelector(':scope > .nav-submenu-toggle');
    const trigger = parent.querySelector(':scope > a');

    if (toggle) {
      toggle.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const willOpen = !parent.classList.contains('is-open');
        if (!isMobileViewport()) closeAllDropdowns(parent);
        if (willOpen) openDropdown(parent);
        else closeDropdown(parent);
      });
    }

    // Touch / coarse-pointer: first tap on parent link opens the dropdown
    // instead of navigating; second tap follows the link. On desktop this is
    // a no-op (CSS hover/focus-within handles it).
    if (trigger) {
      trigger.addEventListener('click', (e) => {
        if (isMobileViewport()) return; // handled by toggle button
        if (!isCoarsePointer()) return;
        if (!parent.classList.contains('is-open')) {
          e.preventDefault();
          closeAllDropdowns(parent);
          openDropdown(parent);
        }
      });
    }
  });

  if (dropdownParents.length) {
    document.addEventListener('click', (e) => {
      if (!e.target.closest || e.target.closest('.nav-links .has-dropdown')) return;
      closeAllDropdowns();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      const openParent = dropdownParents.find((p) => p.classList.contains('is-open'));
      if (!openParent) return;
      closeDropdown(openParent);
      const trigger = openParent.querySelector(':scope > a');
      if (trigger) trigger.focus();
    });
  }

  // ---------- Sticky header shadow on scroll ----------
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => {
      if (window.scrollY > 8) header.classList.add('is-scrolled');
      else header.classList.remove('is-scrolled');
    };
    document.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ---------- Request Pricing Modal ----------
  const modal = document.querySelector('[data-modal="pricing"]');
  const modalTitle = modal && modal.querySelector('[data-modal-title]');
  const modalSubtitle = modal && modal.querySelector('[data-modal-subtitle]');
  const modalForm = modal && modal.querySelector('form');
  const modalSuccess = modal && modal.querySelector('.modal__success');
  const classHidden = modal && modal.querySelector('input[name="class_name"]');

  function openModal(trigger) {
    if (!modal) return;
    const className = trigger?.dataset.class || '';
    const classDate = trigger?.dataset.date || '';
    if (className && modalTitle) modalTitle.textContent = 'Request pricing · ' + className;
    if (classDate && modalSubtitle)
      modalSubtitle.textContent =
        'Class date: ' + classDate + ' — we’ll reply within one business day with pricing and availability.';
    if (classHidden) classHidden.value = className + (classDate ? ' — ' + classDate : '');
    modalForm && (modalForm.style.display = '');
    modalSuccess && (modalSuccess.style.display = 'none');
    modal.classList.add('is-open');
    document.body.classList.add('no-scroll');
    setTimeout(() => {
      const firstInput = modal.querySelector('input, textarea, select');
      firstInput && firstInput.focus();
    }, 100);
  }

  function closeModal() {
    if (!modal) return;
    modal.classList.remove('is-open');
    document.body.classList.remove('no-scroll');
  }

  document.querySelectorAll('[data-request-pricing]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openModal(btn);
    });
  });

  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal || e.target.hasAttribute('data-modal-close')) closeModal();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });
  }

  if (modalForm) {
    modalForm.addEventListener('submit', (e) => {
      e.preventDefault();
      // In production this POSTs to the CRM webhook (GoHighLevel / Pipedrive).
      // For the static demo we just show the success state.
      modalForm.style.display = 'none';
      if (modalSuccess) modalSuccess.style.display = 'block';
    });
  }

  // ---------- Classes table category filters ----------
  document.querySelectorAll('[data-classes-filter]').forEach((filterBar) => {
    const section = filterBar.closest('section');
    const rows = section ? Array.from(section.querySelectorAll('tr[data-class-categories]')) : [];
    const buttons = Array.from(filterBar.querySelectorAll('[data-filter]'));

    if (!rows.length || !buttons.length) return;

    buttons.forEach((button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('aria-pressed', button.classList.contains('is-active') ? 'true' : 'false');

      button.addEventListener('click', () => {
        const activeFilter = button.dataset.filter || '';

        buttons.forEach((btn) => {
          const isActive = btn === button;
          btn.classList.toggle('is-active', isActive);
          btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });

        rows.forEach((row) => {
          const categories = (row.dataset.classCategories || '').split(/\s+/).filter(Boolean);
          const shouldShow = !activeFilter || categories.includes(activeFilter);
          row.hidden = !shouldShow;
        });
      });
    });
  });

  // ---------- Smooth anchor for any #class-schedule link (Classes page) ----------
  document.querySelectorAll('a[href^="#"]').forEach((a) => {
    a.addEventListener('click', (e) => {
      const id = a.getAttribute('href');
      if (id && id.length > 1) {
        const el = document.querySelector(id);
        if (el) {
          e.preventDefault();
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  });
})();
