# Project map

A navigation index for this codebase: **where things live and what to open to
change them**. Read this before exploring — it is meant to replace a search.

- Conventions and rules: `AGENTS.md` (loaded automatically each session)
- Local setup quirks on this machine: `docs/running-locally.md`
- Live cPanel/SSH connection, deployment paths and backups: `docs/deployment-cpanel.md`
- Design tokens and components: `docs/design-system.md`
- Client vs. consultant dashboard role definitions: `docs/dashboards-spec.md`

---

## 1. What this app is

A database-driven marketing site plus an admin CMS for ITM Business Solutions,
a Zimbabwean company-registration and compliance firm.

It does two jobs: turn visitors into **qualified quotation requests** (a `Lead`),
and give the owner an admin to manage everything without a deploy.

Source of truth for requirements: `docs/ITM_Website_PRD_and_Content_Pack/`.

**Built:** public site, SEO, lead capture, full admin CMS, client dashboard
(their own requests to ITM), and a consultant dashboard/CRM (an independent
consultant's own customers, notes and tasks — unrelated to ITM's leads; see
`docs/dashboards-spec.md`).
**Not built** (PRD Releases 2–4, needs a CRM spine first — do not half-build
these into the lead flow): organisations and teams, versioned quotations,
payments, document vault, applications workflow, compliance calendar.

---

## 2. "I want to change…" → open this

| Goal | Files |
| --- | --- |
| Public page copy (about, contact, privacy, terms…) | Not in code — **edit in `/admin` → Pages** (`pages` table) |
| Service/article/package/FAQ content | Not in code — **edit in `/admin`** (`services`, `articles`, `packages`, `faqs`) |
| Phone, WhatsApp, email, hours, address | Not in code — **`/admin` → Settings** (`settings` table) |
| Homepage *layout* (not its copy) | `resources/views/pages/public/home.blade.php` + `app/Http/Controllers/HomeController.php` |
| Service page *layout* | `resources/views/pages/public/service.blade.php` + `ServiceController@show` |
| Public header / footer / nav | `resources/views/layouts/public.blade.php` (data via `app/Support/SiteChrome.php`) |
| Colours, spacing, type, motion | `resources/css/app.css` (all tokens in `@theme`) |
| A reusable UI element (button, card, badge…) | `resources/views/components/ui/` |
| Scroll/reveal animations | `resources/js/motion.js` |
| Public page images / image management | Admin Settings previews and upload/replace/remove/restore controls; `Admin/PublicImageController`, `app/Support/PublicMedia.php`, `resources/views/components/admin/public-image.blade.php`; uploads use the `public_images` disk |
| Requirements checklist search | `resources/views/components/public/checklist-finder.blade.php` + `PageController@show` |
| Contact layout / live contact details | `resources/views/pages/public/contact.blade.php`, `components/public/contact-{form,channels}.blade.php` + `PageController@show`; details come from Admin Settings, enquiries use `QuoteRequestController@store` |
| Add a public route | `routes/web.php` — **keep the catch-all `{slug}` route last** |
| Add an admin screen | `routes/admin.php` + `app/Http/Controllers/Admin/` + `resources/views/pages/admin/` |
| Admin sidebar links | `resources/views/layouts/admin.blade.php` |
| Quotation request form / validation | `app/Http/Controllers/QuoteRequestController.php` + `resources/views/pages/public/quote.blade.php` |
| Optional email marketing consent and unsubscribe | `components/public/marketing-opt-in.blade.php`, `app/Support/MarketingConsent.php`, `MarketingPreferenceController`, `marketing_subscriptions` / `marketing_consent_events`; wording version in `config/marketing.php` |
| Lead notification / confirmation emails | `app/Mail/{NewLeadReceived,LeadConfirmation}.php` + `resources/views/emails/`. Sent from `QuoteRequestController@store` and `LeadController@store`, queued — see `docs/running-locally.md` for running a worker |
| Lead statuses | `app/Enums/LeadStatus.php` |
| ITM lead ownership and first-response queue | `app/Http/Controllers/Admin/LeadController.php`, `Admin/DashboardController.php`, `pages/admin/leads/`, `pages/admin/dashboard.blade.php`; assignees are admins only |
| Who can reach the admin | `app/Http/Middleware/EnsureUserIsAdmin.php`, `app/Enums/UserRole.php` |
| Registration fields / roles | `app/Actions/Fortify/CreateNewUser.php` + `resources/views/pages/auth/register.blade.php` |
| Client vs consultant dashboard | `app/Http/Controllers/DashboardController.php` prepares role-scoped data; `resources/views/dashboard.blade.php` dispatches to `pages/dashboard/{client,consultant}.blade.php` |
| Consultant's own customers/notes/tasks | `routes/consultant.php` + `app/Http/Controllers/Consultant/` + `resources/views/pages/consultant/customers/` |
| Workspace sidebar | `resources/views/layouts/app/sidebar.blade.php` |
| Re-import the PRD content pack | `database/seeders/ContentPackSeeder.php`, then `php artisan db:seed` |
| SEO metadata / JSON-LD | Per-page in the controller (`schema` key) + `layouts/public.blade.php` `<head>` |
| Sitemap | `app/Http/Controllers/SitemapController.php` + `resources/views/sitemap.blade.php` |

---

## 3. Routes → controller → view

Defined in `routes/web.php` (public) and `routes/admin.php` (admin).

### Public

| URL | Controller | View |
| --- | --- | --- |
| `/` | `HomeController` | `pages/public/home` |
| `/services` | `ServiceController@index` | `pages/public/services` |
| `/services/category/{category}` | `ServiceController@category` | `pages/public/category` |
| `/services/{service}` | `ServiceController@show` | `pages/public/service` |
| `/packages` | `PageController@packages` | `pages/public/packages` |
| `/articles`, `/articles/{article}` | `ArticleController` | `pages/public/articles`, `article` |
| `/request-a-quotation` (GET/POST) | `QuoteRequestController` | `pages/public/quote` |
| `/request-received` | `QuoteRequestController@received` | `pages/public/quote-received` |
| `/track` (GET/POST) | `TrackApplicationController` | `pages/public/track` |
| `/sitemap.xml` | `SitemapController` | `sitemap` |
| `/{slug}` **(catch-all, must stay last)** | `PageController@show` | `pages/public/page` |

### Authenticated

| URL | Notes |
| --- | --- |
| `/dashboard` | `DashboardController`; branches on role → `pages/dashboard/client` or `consultant` |
| `/service-requests/create`, POST `/service-requests` | `LeadController` (signed-in request; creates a `Lead` with `source=workspace`) |
| `/consultant/customers/*` | `Consultant\CustomerController` — a consultant's own customer CRUD, scoped to `consultant_id`. Middleware `auth,verified,consultant` |
| `/consultant/customers/{customer}/notes`, `/tasks` | `Consultant\CustomerNoteController`, `Consultant\TaskController` |
| `/settings/*` | `routes/settings.php`, Livewire pages |
| `/design-system` | Component reference board. Non-production only, unlinked |

### Admin — all under `/admin`, middleware `auth,verified,admin`

`DashboardController` (overview) · `LeadController` (inbox, status, assignment,
notes) · `ServiceController` · `ServiceCategoryController` · `ArticleController`
· `PackageController` · `FaqController` · `PageController` (edit only) ·
`SettingController` · `UserController` (role changes).

Views mirror these at `resources/views/pages/admin/<name>/{index,form}.blade.php`.

---

## 4. Database

Migrations in `database/migrations/`. Content tables are in
`2026_09_15_120000_create_content_tables.php`; leads in `..._120001_create_leads_table.php`.

| Table | Model | Purpose |
| --- | --- | --- |
| `service_categories` | `ServiceCategory` | 7 categories; `hasMany` services |
| `services` | `Service` | 20 services. List fields (`requirements`, `steps`, `suited_to`, `includes`) are **JSON arrays**, edited as one-per-line textareas |
| `related_services` | — | Self-referencing pivot for "Related services" |
| `articles` | `Article` | Guidance articles; `body` is Markdown |
| `packages` | `Package` | Bundled offerings |
| `pages` | `Page` | Editable static pages. `is_system` = has a fixed route, cannot be deleted |
| `faqs` | `Faq` | **Polymorphic**: attached to a service, or `faqable_type = null` for the site-wide `/faq` page |
| `settings` | `Setting` | Key/value, cached. Read via `Setting::get()` / `Setting::values()` |
| `leads` | `Lead` | Every quotation request, guest or signed-in — **ITM's own clients only**, never a consultant's |
| `marketing_subscriptions`, `marketing_consent_events` | `MarketingSubscription`, `MarketingConsentEvent` | Email opt-in state and append-only request/confirmation/revocation history; transactional lead consent remains separate |
| `users` | `User` | `role` is a `UserRole` enum: client, consultant, admin |
| `consultant_customers` | `ConsultantCustomer` | A consultant's own customer book, scoped by `consultant_id`. Deliberately separate from `leads` |
| `consultant_customer_notes` | `ConsultantCustomerNote` | Timestamped activity log per consultant customer |
| `consultant_tasks` | `ConsultantTask` | A consultant's own follow-up reminders, tied to one of their customers |

Enums: `app/Enums/` — `UserRole`, `LeadStatus` (the PRD's status model),
`ServiceGoal` (mirrors the homepage's five outcomes), `ConsultantCustomerStage`
(a consultant's own lightweight status — not `LeadStatus`).

Support classes: `app/Support/MarkdownDocument.php` (front-matter + section
parser used by the importer), `app/Support/SiteChrome.php` (header/footer data).

---

## 5. Gotchas that will cost you time

These are non-obvious and were each found the hard way.

**Blade / CSS**

1. `<x-layouts::public>` resolves to the view name **`layouts::public`**, not
   `layouts.public`. View composers do **not** fire for namespaced anonymous
   components — that is why shared data comes from `@inject` + `SiteChrome`.
2. Slot content compiles in the **child view's** scope, not the layout's. A
   variable bound to the layout is not available inside the slot.
3. **Unlayered CSS beats everything in a `@layer`**, regardless of specificity.
   The `html:root.dark { color-scheme: light }` rule at the bottom of
   `app.css` must stay unlayered — it overrides an inline style Flux injects.
4. The design system is **light-only**. Flux ships a dark-mode switcher that
   would produce a half-dark UI; the rule above pins it.
5. In `@theme`, `--color-teal-*: initial` also clears the bare `--color-teal`.
   Declare the role colour *after* the wildcard reset.

**PHP**

6. `$request->validate()` **omits nullable keys that were not submitted**. Use
   `$validated['x'] ?? null`, never `$validated['x']` directly.
7. An unchecked checkbox sends nothing — read it with `$request->boolean('x')`.
8. Eloquent does not read DB defaults back. `User` sets `$attributes['role']`
   so a user created without a role is not null in memory.
9. PHPStan needs `--memory-limit=1G` on this machine (`vendor/bin/phpstan analyse --configuration=phpstan-project.neon --memory-limit=1G`). The previous `phpstan.neon` filename was blocked by the local security software, so the same configuration lives in `phpstan-project.neon`.

**Environment** (full detail in `docs/running-locally.md`)

10. Serve with **`php -S 127.0.0.1:8000 -t public`**. `php artisan serve` fails
    because Avast quarantines Laravel's router script as a false-positive webshell.
11. The SQLite database lives **outside OneDrive** at `C:\itm-data\database.sqlite`
    (`DB_DATABASE` in `.env`). SQLite writes fail inside a synced folder.
12. `composer install` needs `$env:COMPOSER = "composer.verify.json"` — the
    committed `composer.json` cannot resolve (`laravel/boost` vs Laravel 13).

---

## 6. Tests

`tests/Feature/` — run with `php artisan test`, or `--filter=Name` for one.

| File | Covers |
| --- | --- |
| `PublicSiteTest` | Pages render from the DB; unpublished content 404s; JSON-LD; sitemap |
| `QuoteRequestTest` | Guest lead capture, consent, honeypot, reference format, tracking privacy, lead notification/confirmation emails |
| `AdminTest` | Access control, CRUD round-trips to the public site, last-admin protection |
| `LeadRequestTest` | Signed-in request flow |
| `DashboardTest` | Role-based dashboard branching |
| `ConsultantCrmTest` | Consultant customer/note/task CRUD, ownership isolation between consultants and from clients |
| `DesignSystemTest` | UI components render correctly |
| `Auth/`, `Settings/` | Starter-kit auth and settings |

---

## 7. Do not read these to get context

Large and mostly irrelevant to code changes:

- `vendor/`, `node_modules/`, `public/build/`
- `docs/Waiting on screen selections/` — design source boards (huge HTML)
- `docs/ITM_Website_PRD_and_Content_Pack/` — the requirements pack. Its content
  is **already imported into the database**; read a specific file only when you
  need the original requirement wording.
