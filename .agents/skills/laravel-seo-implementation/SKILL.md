---
name: laravel-seo-implementation
description: Implement search metadata, canonical URLs, sitemaps, structured data and useful public content in Laravel CMS websites. Use for requested SEO changes rather than audits or ranking guarantees.
---

# Laravel SEO Implementation

Read the project map and trace existing SEO output before changing it. Preserve CMS ownership of titles, descriptions, body copy and publication flags. Use existing head components and metadata fields rather than duplicating tags in views. Keep fallback values meaningful, escaped and brand-consistent.

Confirm the production origin and canonical URL rules; never leak localhost into production metadata, JSON-LD or sitemaps. Keep route generation consistent across canonical, navigation, breadcrumbs and sitemap. Do not rename established slugs without an authorized redirect strategy. Handle unpublished records, sitemap exclusions and private-page noindex separately from access control.

Write content around service intent and verified business facts. Explain requirements, scope, uncertainties and next actions. Use natural wording and useful internal links; avoid keyword density targets, arbitrary word counts, invented credentials, fabricated reviews and doorway/location pages. Legal or regulatory guidance requires current authoritative sources and owner/legal confirmation where facts remain unknown.

Implement JSON-LD with structured serialization and only supported, truthful properties matching visible content. Choose the actual business/entity type, not a convenient rich-result type. Check Google's current eligibility rules before offering FAQ, HowTo or other search enhancements; schema does not guarantee eligibility or display.

Optimize actual bottlenecks: responsive bitmap assets with stable dimensions, prioritize the LCP asset, lazy-load below-fold media, reduce duplicate JavaScript, and retain semantic server-rendered links. In Livewire applications do not add a second Alpine instance. Cache only with a clear invalidation policy that respects publication and owner edits.

Add focused response tests for unique metadata, canonical host, escaping, publication filters and sitemap correctness as relevant. Build frontend assets when changed. Report completed changes and the evidence needed to assess search outcomes later; never promise rankings or immediate indexing.

Verify search guidance using official documentation:
- https://developers.google.com/search/docs/appearance/title-link
- https://developers.google.com/search/docs/appearance/snippet
- https://developers.google.com/search/docs/appearance/structured-data/intro-structured-data
