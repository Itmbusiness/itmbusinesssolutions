# Project Agent Guide

## Start here

**`docs/PROJECT-MAP.md` is the navigation index for this codebase** — a
"to change X, edit Y" table, the route → controller → view map, the database
schema, and the non-obvious gotchas. Read it instead of exploring the tree;
it is written to answer "where does this live?" in one file.

This file holds the conventions. The map holds the layout.

## Stack

- Laravel 13 on PHP 8.3+.
- Blade views with Livewire 4 components.
- Alpine.js is provided by Livewire; do not install or initialize a second Alpine instance.
- Tailwind CSS 4 is compiled through Vite.
- SQLite is the default local database.

## Working Agreements

- Follow existing Laravel conventions and prefer framework features over custom infrastructure.
- Inspect neighboring files before changing architecture or naming.
- Keep controllers thin. Put validation in Form Requests when it is reused or non-trivial.
- Use Eloquent relationships and query scopes; prevent N+1 queries with eager loading.
- Author interactive server-driven UI as Livewire components and small client-only interactions with Alpine.
- Keep Blade components accessible: labels for controls, keyboard-safe interactions, visible focus states, and semantic HTML.
- Never commit secrets or edit `.env.example` with real credentials.
- Do not introduce new production dependencies without explaining the need.

## Design System

- The ITM design system is documented in `docs/design-system.md`; its source board is
  `docs/Waiting on screen selections/ITM Design System.dc.html`.
- Tokens live in `resources/css/app.css` (`@theme`); components live in
  `resources/views/components/ui/`. Build screens from those, not ad-hoc colour utilities.
- The Nocturne system under `docs/.../_ds/` is a reference for method only — ITM's
  light navy/teal/gold palette is the source of truth, not Nocturne's dark blurple.
- `/design-system` renders the living reference board outside production.

## Content architecture

The public site is database-driven. Nothing in `resources/views/pages/public/`
hard-codes copy — it all renders from these tables:

- `service_categories` → `services` (7 categories, 20 services)
- `articles`, `packages`, `pages` (editable static pages), `faqs` (polymorphic:
  attached to a service, or site-wide for `/faq`)
- `settings` — owner-editable contact details and site-wide notices
- `leads` — every quotation request, guest or signed-in (PRD Journey A and B)

Seed or re-import the PRD content pack with `php artisan db:seed`. The importer
(`database/seeders/ContentPackSeeder.php`) parses the markdown in
`docs/ITM_Website_PRD_and_Content_Pack/` and is idempotent — it matches on slug
and updates, so it is safe to re-run after editing the pack.

Admin lives at `/admin`, guarded by the `admin` middleware alias and
`UserRole::Admin`. Administrator is granted in the admin, never self-selected at
registration.

### Scope built so far

Release 1 of the PRD (public site, SEO, lead capture) plus the full admin CMS.
Deliberately **not** built yet: organisations and teams, versioned quotations,
payments, the document vault, the applications workflow and the compliance
calendar. Those are Releases 2–4 and need a CRM spine first — do not half-build
them into the lead flow.

## Commands

Local setup on this machine needs four workarounds (PHP not on PATH, Avast TLS
interception, OneDrive file locks, and a `laravel/boost` conflict with Laravel 13).
See `docs/running-locally.md` before running any of these.

```bash
composer setup
composer run dev
composer test
composer lint
npm run build
```

For a focused test, run `php artisan test --filter=TestName` or pass a test file path.

## Verification

- Add or update tests for every behavior change.
- Prefer feature tests for HTTP and Livewire behavior; use unit tests for isolated domain logic.
- Before handing off, run the narrowest relevant tests, then `composer test` when practical.
- Run `npm run build` after changing frontend assets or Tailwind classes.
- Run `vendor/bin/pint --dirty` after changing PHP files.

## Agentic Laravel Support

- Laravel Boost is a development dependency. Use its version-aware documentation search and application tools when available.
- If Boost has not been initialized on a fresh checkout, run `php artisan boost:install` and enable Codex and Claude Code integrations.
- Prefer Boost documentation results for Laravel ecosystem questions because they match installed package versions.
