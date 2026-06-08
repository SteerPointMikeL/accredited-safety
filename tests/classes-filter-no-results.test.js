/*
 * Dependency-free regression test for the classes-table category filter
 * no-results behavior in assets/js/main.js.
 *
 * Runs the real main.js inside a minimal DOM stub that implements only the
 * APIs the classes-filter block touches, then asserts that the
 * `.classes-table__no-results` row shows/hides correctly across the default
 * state, manual clicks, and query-string preselection.
 *
 * Run with: node tests/classes-filter-no-results.test.js
 */
'use strict';

const fs = require('fs');
const path = require('path');
const vm = require('vm');

let testsRun = 0;
let failures = 0;
function assert(cond, msg) {
  testsRun += 1;
  if (!cond) {
    failures += 1;
    console.error('  FAIL: ' + msg);
  } else {
    console.log('  ok: ' + msg);
  }
}

const HIDDEN_CLASS = 'classes-table__no-results--hidden';

// ---- Minimal DOM stub --------------------------------------------------

class ClassList {
  constructor(el) {
    this.el = el;
  }
  _set() {
    this.el._classNames = Array.from(this._tokens);
  }
  get _tokens() {
    return new Set(this.el._classNames);
  }
  contains(c) {
    return this.el._classNames.includes(c);
  }
  add(c) {
    if (!this.contains(c)) this.el._classNames.push(c);
  }
  remove(c) {
    this.el._classNames = this.el._classNames.filter((x) => x !== c);
  }
  toggle(c, force) {
    const want = force === undefined ? !this.contains(c) : !!force;
    if (want) this.add(c);
    else this.remove(c);
    return want;
  }
}

class El {
  constructor(tag, opts = {}) {
    this.tag = tag;
    this._classNames = opts.classNames ? opts.classNames.slice() : [];
    this.dataset = opts.dataset ? Object.assign({}, opts.dataset) : {};
    this.attrs = {};
    this.children = [];
    this.parent = null;
    this.hidden = false;
    this.classList = new ClassList(this);
  }
  append(child) {
    child.parent = this;
    this.children.push(child);
    return child;
  }
  setAttribute(name, value) {
    this.attrs[name] = String(value);
  }
  addEventListener() {}
  getAttribute(name) {
    return name in this.attrs ? this.attrs[name] : null;
  }
  closest(selector) {
    let node = this;
    while (node) {
      if (node._matches(selector)) return node;
      node = node.parent;
    }
    return null;
  }
  _descendants() {
    const out = [];
    const walk = (node) => {
      node.children.forEach((c) => {
        out.push(c);
        walk(c);
      });
    };
    walk(this);
    return out;
  }
  _matches(selector) {
    // Supports: 'tag', '.class', '[data-attr]', 'tag.class', and the few
    // composite selectors main.js uses.
    return selector.split(',').some((part) => this._matchesSimple(part.trim()));
  }
  _matchesSimple(sel) {
    // tr[data-class-categories]
    let m = sel.match(/^([a-z0-9-]+)?(\[[^\]]+\])?(\.[^\s]+)?$/i);
    if (!m) return false;
    const [, tag, attr, cls] = m;
    if (tag && this.tag !== tag) return false;
    if (attr) {
      const name = attr.slice(1, -1);
      if (name.startsWith('data-')) {
        const key = name
          .slice(5)
          .replace(/-([a-z])/g, (_, c) => c.toUpperCase());
        if (!(key in this.dataset)) return false;
      } else if (!(name in this.attrs)) {
        return false;
      }
    }
    if (cls) {
      const classes = cls.split('.').filter(Boolean);
      if (!classes.every((c) => this.classList.contains(c))) return false;
    }
    return true;
  }
  querySelectorAll(selector) {
    return this._descendants().filter((n) => n._matches(selector));
  }
  querySelector(selector) {
    return this.querySelectorAll(selector)[0] || null;
  }
}

function buildDocument(rootSection) {
  const all = [rootSection, ...rootSection._descendants()];
  return {
    querySelectorAll(selector) {
      return all.filter((n) => n._matches(selector));
    },
    querySelector(selector) {
      return this.querySelectorAll(selector)[0] || null;
    },
    addEventListener() {},
    activeElement: null,
    body: new El('body'),
  };
}

// ---- Fixture: mirrors template-parts/flexible/classes_table.php --------

function buildFixture() {
  const section = new El('section', { classNames: ['section'] });

  const filterBar = new El('div', {
    classNames: ['classes-table-filter'],
    dataset: { classesFilter: '' },
  });
  const btnAll = new El('button', {
    classNames: ['btn', 'btn--outline', 'is-active'],
    dataset: { filter: '' },
  });
  const btnMobile = new El('button', {
    classNames: ['btn', 'btn--outline'],
    dataset: { filter: 'mobile' },
  });
  const btnOnsite = new El('button', {
    classNames: ['btn', 'btn--outline'],
    dataset: { filter: 'onsite' },
  });
  filterBar.append(btnAll);
  filterBar.append(btnMobile);
  filterBar.append(btnOnsite);
  section.append(filterBar);

  const table = new El('table', { classNames: ['classes-table'] });
  const tbody = new El('tbody');
  table.append(tbody);
  section.append(table);

  const rowMobile = new El('tr', { dataset: { classCategories: 'mobile' } });
  const rowOnsite = new El('tr', { dataset: { classCategories: 'onsite' } });
  tbody.append(rowMobile);
  tbody.append(rowOnsite);

  const noResults = new El('tr', {
    classNames: ['classes-table__no-results', HIDDEN_CLASS],
  });
  tbody.append(noResults);

  return { section, btnAll, btnMobile, btnOnsite, rowMobile, rowOnsite, noResults };
}

// ---- Test runner -------------------------------------------------------

function runMainJs(doc, search) {
  const code = fs.readFileSync(
    path.join(__dirname, '..', 'assets', 'js', 'main.js'),
    'utf8'
  );
  const sandbox = {
    document: doc,
    window: { location: { search: search || '' } },
    URLSearchParams,
    console,
  };
  vm.createContext(sandbox);
  vm.runInContext(code, sandbox);
}

function isVisible(noResults) {
  return !noResults.classList.contains(HIDDEN_CLASS) && noResults.hidden !== true;
}

console.log('Default (all) state hides the no-results row:');
{
  const f = buildFixture();
  runMainJs(buildDocument(f.section), '');
  assert(!isVisible(f.noResults), 'no-results hidden on initial all-classes state');
  assert(!f.rowMobile.hidden && !f.rowOnsite.hidden, 'all rows visible by default');
}

// Manual click and query-string preselection share the same applyFilter path.
// The stub does not dispatch DOM events, so query-string preselection is used
// to drive applyFilter for the matching/non-matching category cases below.
console.log('Filter with matching rows keeps no-results hidden (via query string):');
{
  const f = buildFixture();
  runMainJs(buildDocument(f.section), '?class_category_filter=mobile');
  assert(!isVisible(f.noResults), 'no-results hidden when a filter has matches');
  assert(!f.rowMobile.hidden, 'matching mobile row stays visible');
  assert(f.rowOnsite.hidden, 'non-matching onsite row is hidden');
}

console.log('Filter with no matching rows shows the no-results row:');
{
  const f = buildFixture();
  // Remove the onsite row category so no row matches 'onsite' but the button
  // still exists; reuse mobile-only rows.
  f.rowOnsite.dataset.classCategories = 'mobile';
  runMainJs(buildDocument(f.section), '?class_category_filter=onsite');
  assert(isVisible(f.noResults), 'no-results shown when filter matches zero rows');
  assert(f.rowMobile.hidden && f.rowOnsite.hidden, 'all rows hidden for empty category');
}

console.log('No-results row is never counted as a result row:');
{
  const f = buildFixture();
  // Give the no-results row a category attr to prove it is still excluded.
  f.noResults.dataset.classCategories = 'onsite';
  f.rowMobile.dataset.classCategories = 'mobile';
  f.rowOnsite.dataset.classCategories = 'mobile';
  runMainJs(buildDocument(f.section), '?class_category_filter=onsite');
  assert(isVisible(f.noResults), 'no-results shows even though it carries a matching category');
}

console.log('\n' + (failures ? failures + ' FAILURE(S)' : 'All ' + testsRun + ' assertions passed'));
process.exit(failures ? 1 : 0);
