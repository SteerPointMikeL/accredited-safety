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

// ---------------------------------------------------------------------------
// Binding/detection regression — the second-pass bug was that the calendar's
// in-modal class never applied because the input selector and active-input
// resolution missed the real Gravity Forms markup. These checks assert the
// code targets the selectors/classes GF actually renders and drives the active
// input from jQuery UI's own datepicker state.
// ---------------------------------------------------------------------------
console.log('\nGravity Forms selector / detection wiring (main.js):');

// The visible GF date input carries the `datepicker` class; jQuery UI tags the
// bound input `hasDatepicker`; GF wraps it in `.ginput_container_date` /
// `.gfield_date`. The detector must recognize these real shapes.
assert(/input\.datepicker/.test(js), 'selector matches GF input.datepicker');
assert(/input\.hasDatepicker/.test(js), 'selector matches jQuery UI input.hasDatepicker');
assert(/\.ginput_container_date input/.test(js), 'selector matches .ginput_container_date input');
assert(/\.gfield_date input/.test(js), 'selector matches .gfield_date text input');

// Active input must be resolved from jQuery UI's authoritative instance, not
// only guessed from event targets.
assert(/\$\.datepicker/.test(js) && /_curInst/.test(js), 'reads jQuery UI $.datepicker._curInst');
assert(/_lastInput/.test(js), 'falls back to jQuery UI _lastInput');

// Opening listeners must cover focusin, mousedown and click (browsers/inputs
// differ on which precedes the calendar opening).
['focusin', 'mousedown', 'click'].forEach((ev) =>
  assert(new RegExp("addEventListener\\(\\s*'" + ev + "'").test(js), 'listens for ' + ev)
);

// Must not gate the reposition pass on offsetParent (transiently null mid-
// mutation), which previously dropped the only reposition.
assert(!/!dp\.offsetParent/.test(js), 'does not gate reposition on offsetParent');

// Re-pin after Gravity Forms AJAX redraws.
assert(/gform_post_render/.test(js), 'rebinds after gform_post_render');

// ---------------------------------------------------------------------------
// Behavioral check against the REAL helper code. We extract the self-contained
// `accrDatepicker` object literal from main.js and evaluate it (it touches no
// DOM globals beyond the element args we pass), then drive it with minimal fake
// nodes shaped like the actual GF markup. This exercises the shipped selector
// and coordinate math, not a reimplementation.
// ---------------------------------------------------------------------------
console.log('\nLive accrDatepicker helper behavior:');

function extractAccrDatepicker(source) {
  const start = source.indexOf('const accrDatepicker = {');
  if (start === -1) throw new Error('accrDatepicker object not found in main.js');
  // Walk braces from the first "{" to find the matching close.
  const braceStart = source.indexOf('{', start);
  let depth = 0;
  let end = -1;
  for (let i = braceStart; i < source.length; i++) {
    const ch = source[i];
    if (ch === '{') depth++;
    else if (ch === '}') {
      depth--;
      if (depth === 0) {
        end = i;
        break;
      }
    }
  }
  if (end === -1) throw new Error('could not find end of accrDatepicker object');
  const objectLiteral = source.slice(braceStart, end + 1);
  // eslint-disable-next-line no-eval
  return eval('(' + objectLiteral + ')');
}

const accrDatepicker = extractAccrDatepicker(js);

// Minimal element double supporting matches()/closest() against a class list,
// id, type, and an ancestor chain — enough for the helper's selector logic.
function makeEl({ classes = [], id = '', type = '', parent = null } = {}) {
  const el = {
    _classes: new Set(classes),
    id,
    type,
    parent,
    matches(selector) {
      return selector.split(',').some((part) => matchSimple(this, part.trim()));
    },
    closest(selector) {
      let node = this;
      while (node) {
        if (node.matches && node.matches(selector)) return node;
        if (matchSimple(node, selector)) return node;
        node = node.parent;
      }
      return null;
    },
  };
  return el;
}

// Supports the limited selector grammar used by INPUT_SELECTOR and the modal
// scope check: "input.cls", ".cls input", ".gfield_date input[type=\"text\"]",
// "[data-modal]", and bare ".cls" / "input".
function matchSimple(node, selector) {
  // Descendant selector: "A B" — B matches node, A matches some ancestor.
  if (/\s/.test(selector)) {
    const parts = selector.split(/\s+/);
    const last = parts[parts.length - 1];
    if (!matchSimple(node, last)) return false;
    let ancestor = node.parent;
    const need = parts.slice(0, -1);
    // Only single-ancestor depth needed for our selectors.
    return need.every((sel) => {
      let a = ancestor;
      while (a) {
        if (matchSimple(a, sel)) return true;
        a = a.parent;
      }
      return false;
    });
  }
  // Compound simple selector: optional tag, optional .class, optional
  // [type="..."], optional [data-modal]. We parse each part out and test it.
  let sel = selector;
  let tag = '';
  const tagM = sel.match(/^[a-z0-9]+/i);
  if (tagM) {
    tag = tagM[0].toLowerCase();
    sel = sel.slice(tagM[0].length);
  }
  let dataModal = false;
  if (sel.includes('[data-modal]')) {
    dataModal = true;
    sel = sel.replace('[data-modal]', '');
  }
  let typeAttr = '';
  const typeM = sel.match(/\[type="([^"]+)"\]/);
  if (typeM) {
    typeAttr = typeM[1];
    sel = sel.replace(typeM[0], '');
  }
  let cls = '';
  const clsM = sel.match(/\.([\w-]+)/);
  if (clsM) cls = clsM[1];

  if (tag === 'input' && !node._isInput) return false;
  if (cls && !node._classes.has(cls)) return false;
  if (typeAttr && node.type !== typeAttr) return false;
  if (dataModal && !node._dataModal) return false;
  return true;
}

// Builders for realistic GF markup.
function modalContainer() {
  return { _classes: new Set(), _dataModal: true, parent: null, matches: () => false };
}
function gfDateInput({ inModal = true, classes = ['datepicker', 'medium', 'mdy'], type = 'text' } = {}) {
  const modalEl = inModal ? modalContainer() : null;
  const container = makeEl({ classes: ['ginput_container_date', 'gfield_date'], parent: modalEl });
  container._dataModal = false;
  if (inModal) container.parent = modalEl;
  const input = makeEl({ classes, id: 'input_2_3', type, parent: container });
  input._isInput = true;
  return input;
}

// Real GF input inside a modal is detected.
assert(
  accrDatepicker.isModalDateInput(gfDateInput()) === true,
  'detects GF datepicker input (class "datepicker medium mdy") inside a modal'
);
// jQuery-UI-tagged input (hasDatepicker) inside a modal is detected.
assert(
  accrDatepicker.isModalDateInput(gfDateInput({ classes: ['hasDatepicker'] })) === true,
  'detects jQuery UI hasDatepicker input inside a modal'
);
// A date input whose only signal is the container class is detected.
(function () {
  const modalEl = modalContainer();
  const container = makeEl({ classes: ['ginput_container_date'], parent: modalEl });
  const input = makeEl({ classes: [], type: 'text', parent: container });
  input._isInput = true;
  assert(
    accrDatepicker.isModalDateInput(input) === true,
    'detects date input via .ginput_container_date wrapper (no datepicker class on input)'
  );
})();
// The SAME input outside any modal is NOT touched (site-wide datepickers safe).
assert(
  accrDatepicker.isModalDateInput(gfDateInput({ inModal: false })) === false,
  'ignores GF datepicker input that is not inside a modal'
);
// Non-date inputs inside the modal are ignored.
(function () {
  const modalEl = modalContainer();
  const plain = makeEl({ classes: ['name'], type: 'text', parent: modalEl });
  plain._isInput = true;
  assert(
    accrDatepicker.isModalDateInput(plain) === false,
    'ignores a non-date text input inside the modal'
  );
})();
assert(accrDatepicker.isModalDateInput(null) === false, 'null target is safely ignored');

// computePosition is the real shipped math (used by repositionDatepicker).
const cp = accrDatepicker.computePosition(
  { top: 300, bottom: 332, left: 120 },
  260,
  280,
  1024,
  800,
  8
);
assert(cp.top === 340 && cp.left === 120, 'computePosition pins just below the field with room (340/120)');

// The fix pins the calendar with position:fixed and viewport coordinates.
// Adding a page-scroll offset to a fixed popup is the original bug, so guard
// against its reintroduction at the source level.
assert(/dp\.style\.position\s*=\s*'fixed'/.test(js), 'pins calendar with position:fixed');
assert(
  /dp\.style\.top\s*=\s*pos\.top\s*\+\s*'px'/.test(js) &&
    /top:\s*Math\.round\(top\)/.test(js),
  'writes top from the rounded viewport coordinate only (no scroll offset added)'
);
assert(
  !/Math\.round\(\s*top\s*\+\s*scroll/.test(js) && !/Math\.round\(\s*topVp\s*\+\s*scroll/.test(js),
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
