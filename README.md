# Accredited Safety Solutions — WordPress Theme

A production-ready WordPress theme for Accredited Safety Solutions, Indiana's NCCCO crane operator certification training company. Page sections are built as **reusable ACF flexible-content layouts** shared across pages and custom post types. **Gravity Forms** is the assumed forms platform; **WooCommerce is not supported**.

---

## Contents

```
accredited-safety-solutions/
├── style.css                        WordPress theme manifest
├── functions.php                    Theme bootstrap (enqueues, includes, ACF JSON, helpers)
├── header.php / footer.php          Header, footer, request-pricing modal
├── front-page.php                   Delegates to page.php
├── page.php                         Default page — renders ACF flexible content
├── single-certification.php         Single Certification CPT
├── single-class.php                 Single Class CPT (date / time / tuition card)
├── 404.php
├── index.php
├── inc/
│   ├── cpt.php                      Registers `certification` & `class` CPTs + `class_category` taxonomy
│   ├── acf-fields.php               Defines all ACF field groups in PHP (authoritative)
│   ├── acf-bootstrap.php            No-op fallbacks so theme never fatals if ACF is missing
│   ├── helpers.php                  Icon library, button rendering, image-URL fallback filter, section open/close
│   └── gravity-forms.php            GF body class + container .field hooks + submit button class
├── assets/
│   ├── css/base.css                 Design tokens + base styles (copied verbatim from the supplied design)
│   ├── css/style.css                Component styles (copied verbatim)
│   ├── js/main.js                   Theme JS (copied verbatim)
│   └── images/                      hero-crane.png, classroom-training.png, hands-on-training.png, rigger.png
├── template-parts/flexible/         One PHP partial per ACF flex layout
│   ├── page_hero.php / hero_image.php
│   ├── trust_bar.php / section_intro.php
│   ├── split.php                    Reusable two-column text+image (covers every split section in the design)
│   ├── cards_grid.php               Variants: cert / feature / feature_num / testimonial
│   ├── stats_band.php / logos_band.php / cta_banner.php
│   ├── anchor_nav.php / notice_bar.php
│   ├── classes_table.php            Auto-renders the Class CPT in a table
│   ├── openings_list.php
│   ├── contact_split.php            Info blocks + Gravity Forms slot
│   ├── gravity_form.php             Standalone Gravity Form section
│   └── rich_content.php             WYSIWYG escape hatch
└── acf-json/                        Field-group JSON for ACF Pro local-JSON sync
```

The companion archive contains:

- `accredited-safety-solutions.zip` — this theme (drop into `wp-content/themes/`)
- `accredited-safety-solutions-import.wxr.xml` — WordPress importer file with **all design copy verbatim**, plus the nine scheduled classes from the design's Classes table

---

## Requirements

- WordPress 6.0+
- PHP 7.4+
- [Advanced Custom Fields **Pro**](https://www.advancedcustomfields.com/pro/) (required — flexible content & repeater fields)
- [Gravity Forms](https://www.gravityforms.com/) (recommended; the theme styles it out of the box and exposes "Gravity Form ID" fields wherever a form belongs)
- [WordPress Importer](https://wordpress.org/plugins/wordpress-importer/) (only needed once, to load the WXR file)

WooCommerce is **not** supported by this theme and no WC hooks are present.

---

## Installation & import order

> Follow this order exactly. If pages render with empty sections, you almost certainly imported the WXR before activating the theme (so ACF didn't know about the field groups yet).

1. **Install plugins**
   - Activate **Advanced Custom Fields Pro**.
   - Activate **Gravity Forms** (or skip if you'll add it later — the theme will show inline placeholders where forms should appear).
   - Install **WordPress Importer** (`Tools → Import → WordPress`).

2. **Install the theme**
   - Upload `accredited-safety-solutions.zip` at *Appearance → Themes → Add New → Upload*, then **Activate**.
   - On activation, the theme registers the two CPTs (`certification`, `class`), the `class_category` taxonomy, and three ACF field groups (`Page Sections`, `Class details`, `Certification details`). You'll see them immediately under the admin sidebar.

3. **Sync ACF JSON (optional but recommended)**
   - Visit *Custom Fields → Field Groups*. Three "Sync available" rows should appear (they live in `/wp-content/themes/accredited-safety-solutions/acf-json/`). Click **Sync** for each. The field groups are *also* registered in PHP, so the site works either way — sync just lets admins edit them from the UI.

4. **Import content**
   - *Tools → Import → WordPress → Run Importer*.
   - Upload `accredited-safety-solutions-import.wxr.xml`.
   - When prompted, assign all content to the existing admin user.
   - **Do NOT check "Download and import file attachments"** — the WXR references theme-bundled images via `/wp-content/themes/accredited-safety-solutions/assets/images/…`, so they resolve immediately without uploading to the media library.

5. **Set the homepage**
   - *Settings → Reading → Your homepage displays → A static page*.
   - **Homepage:** Home. **Posts page:** leave blank (the theme is not a blog).

6. **Set permalinks**
   - *Settings → Permalinks → Post name*. Save. This ensures `/certifications/`, `/classes/`, and `/class/{slug}/` resolve correctly.

7. **Configure the primary menu**
   - *Appearance → Menus → Create new menu → "Primary"*.
   - Add Home, About, Certifications, Classes, Careers, Contact in that order.
   - Assign it to the **Primary navigation** location. (If you skip this, the theme falls back to a hardcoded list using the same labels.)
   - Optionally also create "Footer — Training" and "Footer — Company" menus.

8. **(Recommended) Wire up Gravity Forms**
   - Create a "Request Pricing" form (first name / last name / email / phone / company / operator count / notes).
   - Create a "Contact" form (first name / last name / email / phone / topic / message).
   - In the Customizer, set:
     - **Request-Pricing form ID** (powers the legacy modal) under *Appearance → Customize → Site Identity* (we expose it via `get_theme_mod('accr_pricing_form_id')`; until the Customizer is wired up, this is settable via a one-line `set_theme_mod` snippet, see "Customizer keys" below).
   - On the **Contact** page, edit the "Contact info + form split" section and set "Gravity Form ID" to your contact form's ID.

9. **Verify**
   - Visit each page — Home, About, Certifications, Classes, Careers, Contact — and confirm content matches the design.
   - Visit `/classes/` and confirm the table lists the nine pre-imported class entries.
   - In *Classes → All Classes* (admin), open one and confirm Date / Time / Tuition are populated in the side panel.

---

## How the page builder works

Every page and CPT entry exposes a single **Page Sections** field — a flexible-content field whose layouts are the building blocks of the entire site:

| Layout | Reused for |
|---|---|
| `page_hero` | About / Certifications / Classes / Careers / Contact page heroes (eyebrow + title + lead) |
| `hero_image` | Home full-bleed hero |
| `trust_bar` | Home trust strip |
| `section_intro` | Standalone section heading blocks |
| `split` | **Every** two-column text+image: Home "Hands-on", About "Mission", Certifications Mobile/Articulating/Lattice splits. Supports reverse, bullets (check / arrow / 2-col arrow), buttons, anchor IDs, and tinted background. |
| `cards_grid` | Cert cards, feature cards, **numbered** step cards (Classes "How it works"), and testimonials — one layout, four `variant` choices |
| `stats_band` | Home + About stat strips |
| `logos_band` | Home clients band |
| `cta_banner` | The dark navy CTA at the bottom of every page |
| `anchor_nav` | Sticky in-page link bar (Certifications) |
| `notice_bar` | "Pricing on request" warning band (Classes) |
| `classes_table` | The schedule table — auto-renders Class CPT entries |
| `openings_list` | Careers openings rows |
| `contact_split` | Contact page (left info + right Gravity Form) |
| `gravity_form` | Standalone form section anywhere |
| `rich_content` | WYSIWYG escape hatch |

The two-column **split** layout in particular is reused 5+ times in the imported content (every cert detail + mission + hands-on section).

---

## Custom post types

### Certifications (`certification`)
Slug: `/certifications/{slug}/`. Supports the same `Page Sections` field — so a future "deep-dive certification page" is just an arrangement of layouts. Side panel adds: `short_name`, `card_image`, `badge`.

### Classes (`class`)
Slug: `/class/{slug}/`. Side-panel "Class details" fields:

- `hero_title` (override for the hero heading; falls back to the post title)
- `subtitle` (deprecated — no longer displayed; retained for data compatibility)
- `class_date` (date picker; stored `YYYY-MM-DD`)
- `class_end_date` (optional date picker; multi-day classes render a range, e.g. `Apr 28 – 30, 2026`)
- `class_date_display` (override, e.g. `Apr&nbsp;28, 2026`; wins over the derived range)
- `class_time` (free-form, e.g. `7:30 AM – 6:30 PM`)
- `price` (numeric) and/or `tuition_display` (string override, e.g. `Request pricing`)
- `show_in_schedule` toggle
- `request_class_label` (override for the modal's `data-class` attribute)
- `schedule_rows` repeater (badge / day label, date display, time, description)
- `detail_columns` repeater (icon + title + content) — two-column detail list inside the panel; falls back to the legacy `topics_covered` / `designations` / `what_to_bring` / `accommodations` fields

The single-class template renders the post featured image beside the panel title when set, and the "We Will Travel to You" CTA is authored via the `travel_cta` flexible **Page Sections** layout (no longer hardcoded).

Categorize each class with one or more **Class Categories** terms (`Mobile`, `Articulating`, `Rigger`, `Signal Person`, `Telehandler`, …). The schedule table's filter buttons are auto-populated from the terms that have classes attached, and `assets/js/main.js` filters visible rows client-side using each row's `data-class-categories` value.

---

## Gravity Forms compatibility

- The theme **disables Gravity Forms' built-in CSS** via `gform_disable_css` so form styling is fully driven by `assets/css/style.css` (`.field`, `.form-row`, `.btn` etc.).
- Field containers get an additional `.field` class via `gform_field_container`.
- Submit buttons get `btn btn--primary btn--lg btn--block`.
- `class_exists('GFForms')` adds `has-gravityforms` to `<body>` so CSS can target it.
- A `accr_render_gravity_form( $form_id )` helper renders a form with a graceful "Gravity Forms is not active" fallback box. The flexible layouts `contact_split` and `gravity_form` use it.
- **No hard-coded forms** appear in templates except the legacy "Request Pricing" modal in `footer.php`, which is shown only when no Gravity Form ID has been set in the Customizer (`accr_pricing_form_id`). Replace it by setting that ID and the modal will render the Gravity Form instead.

---

## Customizer keys (theme_mods)

Until a custom-tailored Customizer panel is built, these `theme_mod` keys are read by the templates and can be set in `wp-admin/customize.php`'s "Additional CSS" preview or via a small mu-plugin:

| Key | Default | Used by |
|---|---|---|
| `accr_phone_display` | `844-717-3665` | Header / footer / announcement bar |
| `accr_phone_link` | `tel:8447173665` | Header / footer / announcement bar |
| `accr_email` | `info@accredited-safety.com` | Header / footer |
| `accr_footer_blurb` | Indiana's trusted NCCCO… | Footer left column |
| `accr_service_area` | Serving Indiana & the Midwest | Footer |
| `accr_copyright` | © {year} Accredited Safety Solutions… | Footer bottom |
| `accr_legal_line` | NCCCO is a registered trademark… | Footer bottom |
| `accr_pricing_form_id` | `0` | Replaces the legacy Request-Pricing modal form with a Gravity Form |

---

## Assumptions & limitations

1. **Image attachments are referenced by URL, not WP attachment ID.** The WXR doesn't ship attachment posts; it embeds image URLs pointing at `/wp-content/themes/accredited-safety-solutions/assets/images/…`. A small filter on `acf/format_value/type=image` (in `inc/helpers.php`) reads companion `__url` / `__alt` meta and synthesizes the array shape ACF would return, so templates render unchanged. **Side effect:** these images aren't selectable via the Media Library until an admin uploads them. To "promote" them, upload the same four files in *Media → Add New* and re-select them on each image field.
2. **No hardcoded copy in PHP templates.** All headlines, paragraphs, eyebrows, buttons, stats, testimonials, and class names live in the WXR file → postmeta. Templates are pure presentation. The exception is the **legacy Request-Pricing modal in `footer.php`** (form fields and the "We'll never share your info" microcopy), which exists as a graceful fallback for when Gravity Forms is not yet wired up. Replacing it with a Gravity Form via `accr_pricing_form_id` is the supported path.
3. **The schedule table is data-driven from the Class CPT.** The nine dated classes in the design's table were imported as Class CPT entries. To change a date, edit the class, not the page.
4. **No WooCommerce hooks.** No WC bootstrap, no shop URLs, no product loops.
5. **`class_category` filter buttons** are rendered as accessible HTML buttons and progressively enhanced by `assets/js/main.js`; if JavaScript is disabled, all class rows remain visible.
6. **The static design includes a small "inline-edit" iframe script** in every HTML page. That script is intentionally **not** carried over into the theme — it's a preview-only helper from the design tool.
7. **PHP 8.4 was used for syntax verification.** Code follows WordPress core conventions and was confirmed clean with `php -l` on every PHP file.
8. **No multilingual setup.** Strings are translation-ready (`__()` / text domain `accr-theme`) but no `.mo`/`.po` files are bundled.
9. **No block editor (Gutenberg) blocks** are registered. The flexible content layouts in ACF are the canonical authoring surface; `the_content` is intentionally hidden on pages with `page_sections` populated.

---

## Tests run

- `php -l` against every PHP file in the theme → **0 errors**
- WXR XML validated with `simplexml_load_string()` → **parse OK**
- WXR content inventory: **6 pages, 9 class CPT entries, 5 class_category terms, 10 companion image URL meta** (confirmed by regex).
- Layout serialization spot-checked: `page_sections` meta for "Home" deserializes to `[hero_image, trust_bar, cards_grid, stats_band, split, cards_grid, logos_band, cta_banner]`.

---

## Re-generating the import file

If you change content in `generate_wxr.php`, regenerate the WXR with:

```bash
php /path/to/build/generate_wxr.php
```

If you change the ACF PHP definitions in `inc/acf-fields.php`, regenerate the JSON exports with:

```bash
php /path/to/build/dump_acf_json.php
```

Both helper scripts live next to the theme directory (outside the theme bundle) and are included in the working build only — not shipped to production.
