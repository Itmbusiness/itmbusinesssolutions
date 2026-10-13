---
name: laravel-engineering
description: Implement or review Laravel application behavior, including Eloquent, Blade, Livewire, authorization, CMS content and tests. Use for Laravel code work rather than framework-independent design or SEO audits.
---

# Laravel Engineering

Read the repository's AGENTS.md, project map and local setup guide first. Determine installed PHP, Laravel, Livewire and frontend versions from manifests and lockfiles; use version-aware Laravel Boost documentation when available, otherwise official documentation. Do not upgrade packages as an incidental fix.

Trace the route, middleware, controller/component, model and view before editing. Check neighboring patterns and dirty changes. Prefer framework features and existing abstractions; introduce services only when orchestration, reuse or testability justifies them. Use Form Requests for non-trivial or reused validation, policies for ownership, eager loading for relationships and transactions for coupled writes.

For CMS sites, identify the source of truth: database, settings, import pack or view. Fix the producer and consumer together. A saved setting should reach every intended surface without reseeding. Use targeted, transactional content updates; a global idempotent importer may still overwrite owner-edited records.

For Livewire/Blade applications, retain server-rendered semantics and named routes. Use Livewire for server-backed interactions and Alpine for small local interactions; do not initialize Alpine again if Livewire supplies it. Keep catch-all routes last. Enforce publication scopes on parent and child records.

Guard admin actions and record ownership on the server, including uploads and downloads. Never rely on hidden buttons or noindex for authorization. Validate upload content, size and dimensions; select public or private storage deliberately. Delete only owned managed files, account for shared references and preserve defaults. Keep credentials out of output and fixtures.

Test successful and rejected requests, authorization, ownership and relevant persistence. Use framework fakes for mail/storage/queues and isolated test databases. Verify narrow tests, formatting and the project's analysis/build checks. Never use destructive database resets against a working database. Report checks not run and actual limitations, not presumed success.

For this workspace, read docs/PROJECT-MAP.md, docs/design-system.md and docs/running-locally.md. Public content is database-driven; releases beyond the documented implemented scope must not be implied. Use the full business name ITM Business Solutions in client-facing copy.
