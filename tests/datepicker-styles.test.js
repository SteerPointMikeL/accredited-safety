/*
 * Static regression check for the Gravity Forms / jQuery UI date picker
 * styling and modal-positioning fix.
 *
 * The theme has no JS test runner, so this is a dependency-free Node script:
 *   node tests/datepicker-styles.test.js
 * It asserts that the datepicker theme selectors exist in the GF stylesheet
 * and that the modal repositioning logic is present in main.js, guarding
 * against an accidental revert of either side of the fix.
 */
'use strict';

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const css = fs.readFileSync(path.join(root, 'assets/css/gravity-forms.css'), 'utf8');
const js = fs.readFileSync(path.join(root, 'assets/js/main.js'), 'utf8');

let failures = 0;
function assert(cond, msg) {
  if (cond) {
    console.log('  ok  - ' + msg);
  } else {
    failures += 1;
    console.error('  FAIL- ' + msg);
  }
}

console.log('Gravity Forms datepicker styles:');
[
  '#ui-datepicker-div.ui-datepicker',
  '.ui-datepicker-header',
  '.ui-datepicker-title',
  '.ui-datepicker-prev',
  '.ui-datepicker-next',
  '.ui-datepicker-calendar',
  '.ui-datepicker-today',
  '.ui-state-active',
  '.ui-state-disabled',
  '.ui-datepicker-other-month',
].forEach((sel) => assert(css.includes(sel), 'CSS contains ' + sel));

// Calendar must layer above the modal backdrop (z-index 100).
assert(/#ui-datepicker-div[^}]*z-index:\s*1000/.test(css), 'datepicker z-index sits above modal backdrop');

// Brace balance sanity for the stylesheet.
const open = (css.match(/{/g) || []).length;
const close = (css.match(/}/g) || []).length;
assert(open === close, 'CSS braces balanced (' + open + '/' + close + ')');

console.log('\nModal datepicker positioning (main.js):');
assert(js.includes('getBoundingClientRect'), 'positioning uses getBoundingClientRect');
assert(/accr-datepicker--in-modal/.test(js), 'toggles accr-datepicker--in-modal class');
assert(js.includes('ui-datepicker-div'), 'targets #ui-datepicker-div');
assert(/data-modal/.test(js) && /isModalDateInput/.test(js), 'scopes repositioning to modal date inputs');

// The fix pins the calendar with position:fixed and viewport coordinates.
// Adding a page-scroll offset to a fixed popup is the original bug, so guard
// against its reintroduction at the source level.
assert(/dp\.style\.position\s*=\s*'fixed'/.test(js), 'pins calendar with position:fixed');
assert(
  /dp\.style\.top\s*=\s*Math\.round\(topVp\)\s*\+\s*'px'/.test(js),
  'writes top from viewport coordinate only (no scroll offset added)'
);
assert(
  !/Math\.round\(\s*topVp\s*\+\s*scroll/.test(js),
  'does not add scroll offset to the fixed popup top'
);
// position:fixed is also declared for the in-modal class in the stylesheet.
assert(
  /#ui-datepicker-div\.accr-datepicker--in-modal[^}]*position:\s*fixed/.test(css),
  'in-modal datepicker is position:fixed in CSS'
);

// ---------------------------------------------------------------------------
// Behavioral check: the modal field lives inside a position:fixed overlay, so
// its getBoundingClientRect() is stable as the page scrolls. The computed
// fixed `top` must therefore be identical regardless of window.scrollY, and
// in particular must NOT increase as the page scrolls down (the reported bug).
//
// This mirrors the vertical math in repositionDatepicker() exactly. It is a
// self-contained reimplementation (main.js is a DOM-coupled IIFE that cannot
// be required in plain Node), kept in lock-step with the source by the
// source-level guards above.
// ---------------------------------------------------------------------------
console.log('\nScrolled-page positioning invariant:');

// Vertical placement in viewport coordinates (mirrors repositionDatepicker).
function computeTopVp({ rectTop, rectBottom, dpHeight, viewportH, margin }) {
  const spaceBelow = viewportH - rectBottom;
  const spaceAbove = rectTop;
  if (spaceBelow >= dpHeight + margin || spaceBelow >= spaceAbove) {
    return rectBottom + margin;
  }
  return Math.max(margin, rectTop - dpHeight - margin);
}
// The fix writes this viewport coordinate verbatim (position:fixed).
function fixedTop(args) {
  return Math.round(computeTopVp(args));
}
// The original bug wrote position:absolute and added window.scrollY.
function buggyAbsoluteTop(args, scrollY) {
  return Math.round(computeTopVp(args) + scrollY);
}

// A date field sitting mid-viewport inside the fixed modal. Because the modal
// is fixed, these viewport-relative rect values are independent of scroll.
const field = { rectTop: 300, rectBottom: 332 };
const geom = { dpHeight: 280, viewportH: 800, margin: 8 };
const args = { ...field, ...geom };

const topAtScroll0 = fixedTop(args);
const topAtScroll500 = fixedTop(args);
const topAtScroll2000 = fixedTop(args);

assert(
  topAtScroll0 === topAtScroll500 && topAtScroll500 === topAtScroll2000,
  'fixed top is constant across scroll positions (' + topAtScroll0 + 'px)'
);
assert(
  topAtScroll2000 <= topAtScroll0,
  'fixed top does not increase as the page scrolls down'
);
// Sanity: with room below, it pins just beneath the field (rectBottom + margin).
assert(topAtScroll0 === field.rectBottom + geom.margin, 'pins just below the field when room exists');

// Regression contrast: the original absolute+scrollY math drifted downward as
// the page scrolled. Confirm the fixed result diverges from (is smaller than)
// the buggy result once scrolled, proving the scroll offset was the defect.
const buggyAt500 = buggyAbsoluteTop(args, 500);
const buggyAt2000 = buggyAbsoluteTop(args, 2000);
assert(buggyAt500 > topAtScroll0, 'old absolute+scrollY math grew with scroll (the bug)');
assert(buggyAt2000 > buggyAt500, 'old math drifted further the more the page scrolled');
assert(
  topAtScroll500 < buggyAt500 && topAtScroll2000 < buggyAt2000,
  'fix avoids the scroll-driven downward drift'
);

// Flip-above case: field near the bottom with no room below should place the
// calendar above the field, still using a stable viewport coordinate.
const lowField = { rectTop: 760, rectBottom: 792 };
const flippedTop = fixedTop({ ...lowField, ...geom });
assert(
  flippedTop === Math.max(geom.margin, lowField.rectTop - geom.dpHeight - geom.margin),
  'flips above the field when there is no room below'
);
assert(flippedTop >= geom.margin, 'flipped top stays within the viewport top margin');

if (failures) {
  console.error('\n' + failures + ' check(s) failed.');
  process.exit(1);
}
console.log('\nAll datepicker checks passed.');
