/* Accredited Safety Solutions — main.js */

(function () {
  // ---------- Shared modal focus helpers ----------
  const FOCUSABLE =
    'a[href], button:not([disabled]), textarea, input:not([type="hidden"]), select, [tabindex]:not([tabindex="-1"])';

  function getFocusable(container) {
    return Array.from(container.querySelectorAll(FOCUSABLE)).filter(
      (el) => el.offsetParent !== null || el === document.activeElement
    );
  }

  // Trap Tab focus within a container for a keydown event. Returns true if handled.
  function trapTab(e, container) {
    if (e.key !== 'Tab') return false;
    const focusables = getFocusable(container);
    if (!focusables.length) {
      e.preventDefault();
      return true;
    }
    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
    return true;
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

  // The chevron toggle is rendered (display:none -> inline-flex) by the mobile
  // media query. Treat "toggle is visible" as the signal that we're in the
  // accordion/mobile mode, rather than trusting only the viewport width — the
  // two should agree, but offsetParent is what actually reflects the DOM state.
  const toggleIsActive = (toggle) => !!toggle && toggle.offsetParent !== null;

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

    // Parent link behavior:
    //  - Mobile accordion (toggle visible): tapping the label toggles the
    //    submenu instead of following the placeholder "#" link, so the parent
    //    is expandable even if the tap misses the chevron. Submenu links stay
    //    reachable once expanded.
    //  - Touch on wider/desktop layouts (coarse pointer, no toggle shown):
    //    first tap opens, second tap follows the link.
    //  - Mouse/desktop: no-op; CSS hover/focus-within handles it.
    if (trigger) {
      trigger.addEventListener('click', (e) => {
        if (isMobileViewport() || toggleIsActive(toggle)) {
          // Only intercept placeholder parents; real destination links still
          // navigate (their submenu opens via the chevron).
          const href = trigger.getAttribute('href');
          if (!href || href === '#') {
            e.preventDefault();
            if (parent.classList.contains('is-open')) closeDropdown(parent);
            else openDropdown(parent);
          }
          return;
        }
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
      if (e.target === modal || (e.target.closest && e.target.closest('[data-modal-close]'))) closeModal();
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

  // ---------- Staff detail modals ----------
  (function () {
    const triggers = Array.from(document.querySelectorAll('.staff-card__trigger[aria-controls]'));
    if (!triggers.length) return;

    let activeModal = null;
    let lastTrigger = null;

    function openStaffModal(trigger) {
      const id = trigger.getAttribute('aria-controls');
      const modal = id && document.getElementById(id);
      if (!modal) return;

      lastTrigger = trigger;
      activeModal = modal;
      modal.hidden = false;
      trigger.setAttribute('aria-expanded', 'true');
      document.body.classList.add('staff-modal-open');

      const focusables = getFocusable(modal);
      const target = modal.querySelector('[data-staff-modal-close]') || focusables[0] || modal;
      window.setTimeout(() => target.focus(), 0);
    }

    function closeStaffModal() {
      if (!activeModal) return;
      activeModal.hidden = true;
      document.body.classList.remove('staff-modal-open');
      if (lastTrigger) {
        lastTrigger.setAttribute('aria-expanded', 'false');
        lastTrigger.focus();
      }
      activeModal = null;
      lastTrigger = null;
    }

    triggers.forEach((trigger) => {
      trigger.addEventListener('click', () => openStaffModal(trigger));
    });

    document.querySelectorAll('[data-staff-modal]').forEach((modal) => {
      modal.addEventListener('click', (e) => {
        if (e.target === modal || (e.target.closest && e.target.closest('[data-staff-modal-close]'))) {
          closeStaffModal();
        }
      });
    });

    document.addEventListener('keydown', (e) => {
      if (!activeModal) return;
      if (e.key === 'Escape') {
        e.preventDefault();
        closeStaffModal();
        return;
      }
      trapTab(e, activeModal);
    });
  })();

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

  // ---------- Footer newsletter modal ----------
  (function () {
    const newsletterModal = document.querySelector('[data-modal="newsletter"]');
    const triggers = Array.from(document.querySelectorAll('[data-newsletter-open]'));
    if (!newsletterModal || !triggers.length) return;

    let lastTrigger = null;

    function openNewsletter(trigger) {
      lastTrigger = trigger;
      trigger.setAttribute('aria-expanded', 'true');
      newsletterModal.classList.add('is-open');
      newsletterModal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('no-scroll');
      window.setTimeout(() => {
        const focusables = getFocusable(newsletterModal);
        const target =
          newsletterModal.querySelector('[data-modal-close]') || focusables[0] || newsletterModal;
        target.focus();
      }, 100);
    }

    function closeNewsletter() {
      newsletterModal.classList.remove('is-open');
      newsletterModal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('no-scroll');
      if (lastTrigger) {
        lastTrigger.setAttribute('aria-expanded', 'false');
        lastTrigger.focus();
        lastTrigger = null;
      }
    }

    triggers.forEach((trigger) => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        openNewsletter(trigger);
      });
    });

    newsletterModal.addEventListener('click', (e) => {
      if (e.target === newsletterModal || e.target.closest('[data-modal-close]')) {
        closeNewsletter();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (!newsletterModal.classList.contains('is-open')) return;
      if (e.key === 'Escape') {
        e.preventDefault();
        closeNewsletter();
        return;
      }
      trapTab(e, newsletterModal);
    });
  })();

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
