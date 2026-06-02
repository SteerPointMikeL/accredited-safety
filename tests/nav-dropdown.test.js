/**
 * Static, no-browser regression guards for the primary-nav mobile dropdown.
 *
 * Run: `node tests/nav-dropdown.test.js`
 *
 * These are intentionally dependency-free string/structure assertions. The
 * original bug ("tapping a parent menu item does not add `.is-open` on mobile")
 * was a CSS stacking issue: the absolutely-positioned `.nav-submenu-toggle`
 * sat *below* the parent `<a>` (which has `z-index:1`), so taps on the chevron
 * hit the link instead of the button and the toggle's click handler never ran.
 *
 * A headless DOM (jsdom) cannot compute paint/hit-test stacking order, so the
 * meaningful guard for the root cause is asserting the CSS contract directly.
 * We also assert the JS toggle handler still flips `is-open`.
 */

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
// Strip CSS comments so commentary that mentions values (e.g. "z-index:1")
// can't be mistaken for a real declaration.
const css = fs
  .readFileSync(path.join(root, 'assets/css/style.css'), 'utf8')
  .replace(/\/\*[\s\S]*?\*\//g, '');
const js = fs.readFileSync(path.join(root, 'assets/js/main.js'), 'utf8');

let failures = 0;
function assert(cond, msg) {
  if (cond) {
    console.log('  ok  - ' + msg);
  } else {
    failures++;
    console.error('  FAIL- ' + msg);
  }
}

// Extract the `@media (max-width: 980px)` mobile block that owns the toggle.
function mobileNavBlock() {
  const idx = css.indexOf('@media (max-width: 980px)');
  if (idx === -1) return '';
  // Grab a generous slice that contains the nav rules.
  return css.slice(idx, idx + 4000);
}

console.log('CSS: mobile toggle stacking contract');
const block = mobileNavBlock();
const toggleRuleIdx = block.indexOf('.nav-submenu-toggle {');
assert(toggleRuleIdx !== -1, 'mobile block defines .nav-submenu-toggle');
const toggleRule = block.slice(toggleRuleIdx, block.indexOf('}', toggleRuleIdx));
assert(/position:\s*absolute/.test(toggleRule), 'mobile toggle is position:absolute');
assert(/z-index:\s*[1-9]/.test(toggleRule), 'mobile toggle has a positive z-index (above parent link)');

// The parent link uses z-index:1; the toggle must exceed it.
const linkZ = (css.match(/\.nav-links\s*>\s*li\s*>\s*a\s*\{[^}]*z-index:\s*(\d+)/) || [])[1];
const toggleZ = (toggleRule.match(/z-index:\s*(\d+)/) || [])[1];
assert(
  linkZ !== undefined && toggleZ !== undefined && Number(toggleZ) > Number(linkZ),
  `toggle z-index (${toggleZ}) > parent link z-index (${linkZ})`
);

console.log('JS: toggle/click adds .is-open');
assert(/classList\.add\('is-open'\)/.test(js), 'openDropdown adds is-open class');
assert(/classList\.remove\('is-open'\)/.test(js), 'closeDropdown removes is-open class');
assert(
  /\.nav-submenu-toggle'\)/.test(js) && /addEventListener\('click'/.test(js),
  'a click handler is bound to the submenu toggle'
);
assert(/aria-expanded/.test(js), 'aria-expanded is synced in JS');

console.log('');
if (failures) {
  console.error(`${failures} assertion(s) failed`);
  process.exit(1);
}
console.log('All nav-dropdown guards passed.');
