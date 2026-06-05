/**
 * Dependency-free regression test for the classes-table query-string filter.
 *
 * The production logic lives in assets/js/main.js inside a browser-only IIFE
 * that touches `document` and `window` globals, so we can't import it directly
 * under plain Node. Instead this test builds a tiny DOM/URL stub, loads the
 * relevant block out of main.js, and runs it against the stub — verifying the
 * actual shipped code rather than a copy.
 *
 * Run: node tests/classes-filter.test.js
 */

'use strict';

const fs = require('fs');
const path = require('path');
const assert = require('assert');

const MAIN_JS = path.join(__dirname, '..', 'assets', 'js', 'main.js');

// ---- Minimal DOM stub --------------------------------------------------------

function makeClassList(initial) {
  const set = new Set(initial || []);
  return {
    contains: (c) => set.has(c),
    add: (c) => set.add(c),
    remove: (c) => set.delete(c),
    toggle: (c, force) => {
      const want = force === undefined ? !set.has(c) : !!force;
      if (want) set.add(c);
      else set.delete(c);
      return want;
    },
  };
}

function makeButton(filterValue, isActive) {
  return {
    dataset: { filter: filterValue },
    attrs: {},
    _listeners: {},
    classList: makeClassList(isActive ? ['is-active'] : []),
    setAttribute(name, value) {
      this.attrs[name] = value;
    },
    getAttribute(name) {
      return this.attrs[name];
    },
    addEventListener(type, fn) {
      (this._listeners[type] = this._listeners[type] || []).push(fn);
    },
    click() {
      (this._listeners.click || []).forEach((fn) => fn());
    },
  };
}

function makeRow(categories) {
  return {
    dataset: { classCategories: categories },
    hidden: false,
  };
}

// Build one classes-table section: a filter bar with buttons + rows.
function makeSection(buttons, rows) {
  const filterBar = {
    _kind: 'filter-bar',
    closest: () => section,
    querySelectorAll: (sel) => (sel === '[data-filter]' ? buttons : []),
  };
  const section = {
    _kind: 'section',
    querySelectorAll: (sel) => (sel === 'tr[data-class-categories]' ? rows : []),
  };
  return { filterBar, section };
}

function makeDocument(filterBars) {
  return {
    querySelectorAll: (sel) => (sel === '[data-classes-filter]' ? filterBars : []),
  };
}

// ---- Harness: run the main.js filter block against the stub -----------------

function extractFilterBlock(src) {
  const start = src.indexOf('// ---------- Classes table category filters ----------');
  const end = src.indexOf('// ---------- Footer newsletter modal ----------');
  assert.ok(start !== -1 && end !== -1 && end > start, 'could not locate filter block in main.js');
  return src.slice(start, end);
}

function runScenario({ filterBars, search }) {
  const src = fs.readFileSync(MAIN_JS, 'utf8');
  const block = extractFilterBlock(src);

  const documentStub = makeDocument(filterBars);
  const windowStub = { location: { search } };

  // Provide URLSearchParams from Node's global (available in modern Node).
  const fn = new Function('document', 'window', 'URLSearchParams', block);
  fn(documentStub, windowStub, URLSearchParams);
}

// ---- Test scenarios ----------------------------------------------------------

let passed = 0;
function test(name, body) {
  body();
  passed += 1;
  console.log('ok - ' + name);
}

function buildBars() {
  const all = makeButton('', true);
  const mobile = makeButton('mobile', false);
  const articulating = makeButton('articulating', false);
  const signal = makeButton('signal-person', false);
  const buttons = [all, mobile, articulating, signal];

  const rowMobile = makeRow('mobile');
  const rowArticulating = makeRow('articulating');
  const rowSignal = makeRow('signal-person');
  const rowMulti = makeRow('mobile articulating');
  const rows = [rowMobile, rowArticulating, rowSignal, rowMulti];

  const { filterBar } = makeSection(buttons, rows);
  return {
    bars: [filterBar],
    buttons: { all, mobile, articulating, signal },
    rows: { rowMobile, rowArticulating, rowSignal, rowMulti },
  };
}

test('mobile slug preselects Mobile filter and hides others', () => {
  const ctx = buildBars();
  runScenario({ filterBars: ctx.bars, search: '?class_category_filter=mobile' });

  assert.strictEqual(ctx.buttons.mobile.classList.contains('is-active'), true);
  assert.strictEqual(ctx.buttons.mobile.getAttribute('aria-pressed'), 'true');
  assert.strictEqual(ctx.buttons.all.classList.contains('is-active'), false);
  assert.strictEqual(ctx.buttons.all.getAttribute('aria-pressed'), 'false');

  assert.strictEqual(ctx.rows.rowMobile.hidden, false);
  assert.strictEqual(ctx.rows.rowArticulating.hidden, true);
  assert.strictEqual(ctx.rows.rowSignal.hidden, true);
  assert.strictEqual(ctx.rows.rowMulti.hidden, false, 'multi-category row containing mobile stays visible');
});

test('hyphenated slug signal-person preselects correctly', () => {
  const ctx = buildBars();
  runScenario({ filterBars: ctx.bars, search: '?class_category_filter=signal-person' });

  assert.strictEqual(ctx.buttons.signal.classList.contains('is-active'), true);
  assert.strictEqual(ctx.rows.rowSignal.hidden, false);
  assert.strictEqual(ctx.rows.rowMobile.hidden, true);
  assert.strictEqual(ctx.rows.rowMulti.hidden, true);
});

test('articulating slug preselects correctly', () => {
  const ctx = buildBars();
  runScenario({ filterBars: ctx.bars, search: '?class_category_filter=articulating' });

  assert.strictEqual(ctx.buttons.articulating.classList.contains('is-active'), true);
  assert.strictEqual(ctx.rows.rowArticulating.hidden, false);
  assert.strictEqual(ctx.rows.rowMulti.hidden, false);
  assert.strictEqual(ctx.rows.rowSignal.hidden, true);
});

test('no query param leaves default All-classes state untouched', () => {
  const ctx = buildBars();
  runScenario({ filterBars: ctx.bars, search: '' });

  assert.strictEqual(ctx.buttons.all.classList.contains('is-active'), true);
  assert.strictEqual(ctx.buttons.mobile.classList.contains('is-active'), false);
  // Rows are not touched on load when no param is present.
  assert.strictEqual(ctx.rows.rowMobile.hidden, false);
  assert.strictEqual(ctx.rows.rowSignal.hidden, false);
});

test('unknown/invalid value is ignored (no matching button)', () => {
  const ctx = buildBars();
  runScenario({ filterBars: ctx.bars, search: '?class_category_filter=does-not-exist' });

  assert.strictEqual(ctx.buttons.all.classList.contains('is-active'), true);
  assert.strictEqual(ctx.buttons.mobile.classList.contains('is-active'), false);
  assert.strictEqual(ctx.rows.rowSignal.hidden, false);
});

test('dirty value with extra characters normalizes to a valid slug', () => {
  const ctx = buildBars();
  // Uppercase + surrounding junk should normalize to "mobile".
  runScenario({ filterBars: ctx.bars, search: '?class_category_filter=%20MOBILE%21' });

  assert.strictEqual(ctx.buttons.mobile.classList.contains('is-active'), true);
  assert.strictEqual(ctx.rows.rowMobile.hidden, false);
  assert.strictEqual(ctx.rows.rowArticulating.hidden, true);
});

test('manual clicking still works after load', () => {
  const ctx = buildBars();
  runScenario({ filterBars: ctx.bars, search: '?class_category_filter=mobile' });

  // Now simulate a user clicking "Articulating".
  ctx.buttons.articulating.click();

  assert.strictEqual(ctx.buttons.articulating.classList.contains('is-active'), true);
  assert.strictEqual(ctx.buttons.mobile.classList.contains('is-active'), false);
  assert.strictEqual(ctx.rows.rowArticulating.hidden, false);
  assert.strictEqual(ctx.rows.rowMobile.hidden, true);

  // And clicking "All classes" restores everything.
  ctx.buttons.all.click();
  assert.strictEqual(ctx.buttons.all.classList.contains('is-active'), true);
  assert.strictEqual(ctx.rows.rowMobile.hidden, false);
  assert.strictEqual(ctx.rows.rowSignal.hidden, false);
});

test('multiple class tables are scoped independently', () => {
  // Bar A gets the URL filter; bar B (different page section) must NOT change,
  // because the param only matches a button that exists in each bar's own set.
  const aAll = makeButton('', true);
  const aMobile = makeButton('mobile', false);
  const aRowMobile = makeRow('mobile');
  const aRowOther = makeRow('articulating');
  const barA = makeSection([aAll, aMobile], [aRowMobile, aRowOther]).filterBar;

  const bAll = makeButton('', true);
  const bSignal = makeButton('signal-person', false);
  const bRowSignal = makeRow('signal-person');
  const bRowOther = makeRow('articulating');
  const barB = makeSection([bAll, bSignal], [bRowSignal, bRowOther]).filterBar;

  runScenario({ filterBars: [barA, barB], search: '?class_category_filter=mobile' });

  // Bar A reacts.
  assert.strictEqual(aMobile.classList.contains('is-active'), true);
  assert.strictEqual(aRowMobile.hidden, false);
  assert.strictEqual(aRowOther.hidden, true);

  // Bar B has no "mobile" button, so it stays in its default state.
  assert.strictEqual(bAll.classList.contains('is-active'), true);
  assert.strictEqual(bSignal.classList.contains('is-active'), false);
  assert.strictEqual(bRowSignal.hidden, false);
  assert.strictEqual(bRowOther.hidden, false);
});

console.log('\n' + passed + ' passing');
