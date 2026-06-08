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
assert(/scrollY|pageYOffset/.test(js), 'accounts for scroll offset');

if (failures) {
  console.error('\n' + failures + ' check(s) failed.');
  process.exit(1);
}
console.log('\nAll datepicker checks passed.');
