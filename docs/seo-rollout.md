# Search visibility rollout

## Implemented and deployed

Deployed to the ITM Business Solutions production domain on 8 October 2026.
Connection details, verification and private backup paths are recorded in
`docs/deployment-cpanel.md`.

- Home, services and resources metadata use CMS page fields and respect indexability.
- Titles avoid repeating ITM Business Solutions; descriptions fall back to meaningful summaries.
- Published services require a published parent category throughout public queries, related links and the sitemap.
- Sitemap URLs are deduplicated, exclude non-indexable CMS hubs and use the production origin consistently with canonical and structured-data URLs.
- Organization data uses current owner-managed contact settings; service and article schema remain present. JSON-LD is safely encoded. FAQ markup is not a promise of a Google search enhancement.
- A new registration guide links registration, requirements, tax, employer, procurement and licensing services, explains quotation costs without inventing fees, and directs readers to official authorities.
- Non-production public pages are noindex. Social previews have an image and canonical URL.

Run `php artisan db:seed --class=SeoContentSeeder` to refresh only homepage title/SEO fields and the new guide. Existing homepage body, unrelated records and publication flags are preserved. Subsequent edits belong in the CMS. Do not repeatedly rerun this importer over owner edits to the guide.

## Target pages and intent

| Search intent | Preferred page |
| --- | --- |
| Company registration in Zimbabwe / registration support | Homepage and company-registration category, with distinct business overview versus service comparison |
| Private limited company registration | Existing private limited company service |
| PBC registration | Existing PBC service |
| Company registration requirements, costs and next steps | New practical guide and Requirements Centre |
| Tax compliance services Zimbabwe | Tax and fiscal compliance category |
| TIN, tax clearance, tax returns | Corresponding existing individual service |
| NSSA employer registration | Existing employer service/category |

## Deployment and measurement still required

1. Deployment is complete and the HTTPS production pages were checked successfully from the hosting server. Earlier failures to access the domain through the web research tool did not establish a DNS or hosting problem. Continue monitoring public reachability and application errors.
2. Use `APP_ENV=production`, disable debugging and set `APP_URL` to the actual origin. `SEO_PUBLIC_URL` defaults to `https://itmbusinesssolutions.co.zw`, matching the existing robots.txt. If the owner selects www instead, update this setting and robots.txt together, and configure a single permanent HTTPS/host redirect at the hosting layer. Do not redirect local development traffic.
3. Verify Google Search Console domain ownership, submit `/sitemap.xml` and inspect the homepage, registration category and core services. DNS verification must be performed by someone with account access. No verification token or search data was available during this work.
4. Check rendered production robots directives, canonical URLs, sitemap, statuses and assets after deployment. Review search impressions, clicks, queries and quotation conversions by landing page; use actual evidence to identify the next change.
5. Confirm the real business entity, contact/address accuracy, author qualifications and review status. Replace template guidance articles with genuinely useful, reviewed answers over time. Never invent a review date or credential to satisfy an SEO signal.
6. Create or verify the genuine Google Business Profile where eligible; maintain consistent business details and seek honest customer reviews without incentives or fabricated ratings. Earn relevant local links through real relationships, not purchased link schemes.
7. Measure deployed mobile field performance and diagnose with lab tools. Prioritize LCP, INP and CLS issues observed in real data. Browser screenshot checks and real-user performance data were unavailable in this run.

There is no guarantee of first position or indexing. Search results depend on query, competitors, location and search systems. Avoid keyword stuffing, mass doorway pages, fabricated testimonials and unsupported outcome claims.

References checked:
- https://developers.google.com/search/docs/fundamentals/seo-starter-guide
- https://developers.google.com/search/docs/appearance/ranking-systems-guide
- https://developers.google.com/search/updates
- https://www.justice.gov.zw/?service=business-consulting
- https://www.zimra.co.zw/
