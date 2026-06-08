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

  // Holds the values from the trigger that opened the modal, so a deferred /
  // AJAX Gravity Forms render can be re-filled with the correct data.
  let pricingPrefill = { name: '', date: '', operators: '' };

  // Set the underlying control's value for a Gravity Forms field container and
  // dispatch input/change so GF's conditional logic and validation see it.
  function setGfFieldValue(container, value) {
    if (!container) return;
    const control = container.querySelector('input, textarea, select');
    if (!control) return;
    control.value = value;
    control.dispatchEvent(new Event('input', { bubbles: true }));
    control.dispatchEvent(new Event('change', { bubbles: true }));
  }

  // Fill the GF hidden fields inside the modal from the stored prefill values.
  // Always writes (even empty strings) so stale values from a prior open are
  // cleared. Returns true if at least one target field was found.
  function fillPricingFields() {
    if (!modal) return false;
    const nameField = modal.querySelector('.gfield--input-name-class_name');
    const dateField = modal.querySelector('.gfield--input-name-class_date');
    const operatorsField = modal.querySelector('.gfield--input-name-operators');
    setGfFieldValue(nameField, pricingPrefill.name);
    setGfFieldValue(dateField, pricingPrefill.date);
    setGfFieldValue(operatorsField, pricingPrefill.operators);
    return !!(nameField || dateField || operatorsField);
  }

  function openModal(trigger) {
    if (!modal) return;
    const className = trigger?.dataset.class || '';
    const classDate = trigger?.dataset.date || '';
    const classOperators = trigger?.dataset.operators || 'Just me';
    pricingPrefill = { name: className, date: classDate, operators: classOperators };

    if (modalTitle)
      modalTitle.textContent = className ? 'Request pricing · ' + className : 'Request Pricing';
    if (modalSubtitle) {
      if (classDate) {
        modalSubtitle.textContent =
          'Class date: ' + classDate + ' — we’ll reply within one business day with pricing and availability.';
      } else {
         modalSubtitle.textContent =
          'We’ll reply within one business day with pricing and availability.';
      }
    }

    // Legacy static-fallback hidden input.
    if (classHidden) classHidden.value = className + (classDate ? ' — ' + classDate : '');

    // Gravity Forms hidden fields. If GF hasn't rendered yet (deferred / AJAX),
    // retry briefly so the values land once the fields appear.
    if (!fillPricingFields()) {
      let attempts = 0;
      const retry = window.setInterval(() => {
        attempts += 1;
        if (fillPricingFields() || attempts >= 20) {
          window.clearInterval(retry);
        }
      }, 100);
    }

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

  // Re-apply the prefill whenever Gravity Forms (re)renders its form — covers
  // AJAX submit/validation redraws that replace the field markup. Only acts
  // while the pricing modal is open so we don't clobber other forms.
  if (modal) {
    const reapplyOnGfRender = () => {
      if (modal.classList.contains('is-open')) fillPricingFields();
    };
    // Newer GF native event.
    document.addEventListener('gform/postRender', reapplyOnGfRender);
    // Legacy jQuery-triggered event, when jQuery is present.
    if (window.jQuery) {
      window.jQuery(document).on('gform_post_render', reapplyOnGfRender);
    }
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
  // Normalize an arbitrary value into a category slug: lowercase, trim, and
  // keep only [a-z0-9-] so query-string input maps to the same slugs the
  // server emits in data-filter / data-class-categories.
  function normalizeCategorySlug(value) {
    return String(value == null ? '' : value)
      .trim()
      .toLowerCase()
      .replace(/[^a-z0-9-]/g, '');
  }

  document.querySelectorAll('[data-classes-filter]').forEach((filterBar) => {
    const section = filterBar.closest('section');
    // Only true class rows count as results; the no-results row carries the
    // message and must never be treated as one.
    const rows = section
      ? Array.from(section.querySelectorAll('tr[data-class-categories]')).filter(
          (row) => !row.classList.contains('classes-table__no-results')
        )
      : [];
    const buttons = Array.from(filterBar.querySelectorAll('[data-filter]'));
    const noResults = section ? section.querySelector('.classes-table__no-results') : null;

    if (!rows.length || !buttons.length) return;

    // Apply a filter as if `button` were clicked. Scoped to this filter bar's
    // own buttons and rows so multiple class tables on a page stay independent.
    function applyFilter(button) {
      const activeFilter = button.dataset.filter || '';
      let visibleCount = 0;

      buttons.forEach((btn) => {
        const isActive = btn === button;
        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });

      rows.forEach((row) => {
        const categories = (row.dataset.classCategories || '').split(/\s+/).filter(Boolean);
        const shouldShow = !activeFilter || categories.includes(activeFilter);
        row.hidden = !shouldShow;
        if (shouldShow) {
          visibleCount += 1;
        }
      });

      // Show the no-results message only when an active filter hides every row.
      // The default/all state always hides it.
      if (noResults) {
        const showNoResults = Boolean(activeFilter) && visibleCount === 0;
        noResults.classList.toggle('classes-table__no-results--hidden', !showNoResults);
        noResults.hidden = !showNoResults;
      }
    }

    buttons.forEach((button) => {
      button.setAttribute('type', 'button');
      button.setAttribute('aria-pressed', button.classList.contains('is-active') ? 'true' : 'false');
      button.addEventListener('click', () => applyFilter(button));
    });

    // Preselect a category from the URL (e.g. ?class_category_filter=mobile).
    // Only acts when the value matches a real filter button in this bar;
    // otherwise the default (All classes) state is left untouched. Does not
    // scroll or change focus, so existing browser navigation behavior wins.
    const requested = normalizeCategorySlug(
      new URLSearchParams(window.location.search).get('class_category_filter')
    );
    if (requested) {
      const match = buttons.find((btn) => (btn.dataset.filter || '') === requested);
      if (match) {
        applyFilter(match);
      }
    }
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
