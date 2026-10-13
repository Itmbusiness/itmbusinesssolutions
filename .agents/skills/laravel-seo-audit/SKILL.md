---
name: laravel-seo-audit
description: Diagnose technical and content SEO problems in Laravel websites using rendered HTTP evidence, routes, CMS records and search data when available. Use for audits, not automatic redesigns or bulk content generation.
---

# Laravel SEO Audit

Establish the requested pages, intended production origin and business goal from the repository and user context. Distinguish local render checks from live indexing evidence. Search Console access is useful but not a prerequisite; state when traffic, indexing or query data is unavailable. Do not infer indexing from a site search alone.

Inspect routes and catch-all ordering, controllers, publication scopes, CMS metadata, shared head components, robots.txt and sitemap generation. Fetch rendered responses: status, redirects, canonical, title, description, robots directives, heading hierarchy and internal links. Record a reproducible URL and relevant code location for each finding. A robots block prevents crawling, so it can also prevent a crawler from reading noindex; neither mechanism secures private records.

Check that sitemaps contain only published, canonical, indexable URLs on the intended host. Exclude admin/auth/workspace routes, preview URLs and drafts. Test missing/unpublished records return the intended status, and redirects do not loop. Evaluate pagination and filters in context rather than canonicalizing all pages to page one.

Inspect responsive image dimensions, srcset, above-fold loading, fonts, Vite output and Livewire interaction costs. Core Web Vitals are LCP, INP and CLS; distinguish field measurements at the 75th percentile from lab diagnostics. Do not claim a Lighthouse run proves real-user INP or ranking improvements.

Review whether content answers the customer's actual question, whether category and service pages compete unnecessarily, and whether authors, review dates and local contact details are verifiable. Titles and descriptions should be useful and distinct, not enforced by rigid character counts or keyword density. Flag template placeholders and broken policy links. Check JSON-LD agrees with visible, verified business facts and current search-feature eligibility.

Prioritize findings by demonstrated impact and confidence. Deliver issue, evidence, affected URL/file, practical fix and verification. Separate confirmed defects from hypotheses. An audit alone is not authorization to change production settings or publish pages.

Use current primary guidance when checking search rules:
- https://developers.google.com/search/docs/fundamentals/seo-starter-guide
- https://developers.google.com/search/docs/appearance/core-web-vitals
- https://developers.google.com/search/docs/crawling-indexing/robots/intro
