---
name: laravel-ux-review
description: Review and improve complete user journeys in Laravel Blade and Livewire interfaces, focusing on accessibility, forms, navigation, states and responsive behavior. Use for usability fixes rather than new branding or SEO strategy.
---

# Laravel UX Review

Start from the user's goal and current route/component flow. Walk the journey from entry through submission, feedback and return navigation. Review the existing design system first; do not run nonexistent design-search scripts or install tools merely because a generic guide mentions them.

Prioritize blocked tasks, inaccessible interactions and data loss before decorative polish. Examine guest, signed-in and unauthorized states. Distinguish intended future functionality from working features; do not invent document vaults, payments or team permissions to fill a screen.

Forms must have labels, suitable input types, understandable required/optional fields, grouped choices and a visible consent explanation when relevant. Preserve input after server validation, connect errors to controls, prevent duplicate submissions and show a truthful success state only after persistence succeeds. Do not collect sensitive documents or credentials in a general enquiry form.

Navigation should identify the current page without color alone and remain readable on its actual background. Verify brand, inactive items, active items, icons, profile text and focus states independently. Mobile menus need keyboard-safe dismissal and focus handling. Destructive actions need a proportionate confirmation and a clear recovery/default path where available.

Use the project's touch-target and typography requirements. Check 320px-class narrow layouts, ordinary mobile and desktop, long content, zoom and reduced motion. Avoid hover-only critical actions, shifting control sizes and sticky elements that obscure fields. Charts need a readable alternative and states need text as well as color.

For owner image controls, test upload/replace preview, validation failures, removal, restore-default, shared-file safety and unauthorized requests. For dashboards, test record ownership and empty/loading/error states, not just attractive fixtures.

Give evidence-based findings ordered by severity with the affected route/component and a concrete correction. Implement only the requested scope, then run relevant HTTP/Livewire tests, frontend build and browser interaction checks when available. State visual checks that could not be performed.
