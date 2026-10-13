---
name: laravel-ui-design
description: Design and implement visual layouts and reusable components in Laravel Blade or Livewire applications while respecting the existing design system. Use for visual redesign and component work, not SEO audits.
---

# Laravel UI Design

Read the project's design system, CSS tokens, layouts and neighboring Blade components before proposing a visual language. Use the existing palette, typography, icon library and controls. Do not introduce another framework or regenerate a design system unless requested.

Identify the audience and task. Public service pages need clear service identity, trusted facts and a direct enquiry path; dashboards need organized working information and predictable navigation. Preserve database-driven copy and settings. Do not replace a contact form with a list of field names or add decorative controls without behavior.

Use semantic Blade components, labels, focus states and named routes. Keep page sections unframed where possible; reserve cards for repeated items or genuinely framed tools. Use appropriate icons with accessible names, real radio/checkbox semantics and stable control dimensions. Test long names and validation text rather than designing only for short samples.

Choose images for a concrete informational or visual purpose. Use real or generated bitmap assets when appropriate, not fake photographs built as SVG. Generated people are illustrative, never presented as actual staff or clients. Avoid generated legal seals, credentials or authority logos. Give informative images meaningful alt text and decorative images empty alt text. Make replacement, preview, removal and fallback behavior understandable if owners manage assets.

Retain Livewire's Alpine integration. Consider scoped CSS and cascade layers when framework component styles override sidebar text or icons; verify computed contrast instead of assuming a class worked.

Check desktop and narrow mobile layouts, text wrapping, keyboard navigation, focus, error/success/empty states and image load failures. Use screenshots and interaction tests when browser tooling is available; otherwise explicitly distinguish code/HTTP checks from visual verification. Build changed assets and run relevant feature tests.

For ITM Business Solutions, docs/design-system.md and resources/css/app.css are authoritative. Use the full business name; keep contact facts settings-driven and public copy CMS-editable.
