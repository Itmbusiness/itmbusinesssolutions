# cPanel deployment

## Connection and paths

The local OpenSSH config at `C:\Users\methu\.ssh\config` defines alias `itm-cpanel`:

- Host: `premium125.web-hosting.com`
- Port: `21098`
- User: `itmcvepp`
- Identity file: `~/.ssh/itm_cpanel`
- Connection: `ssh itm-cpanel`

The key is already authorised and was successfully used on 8 October 2026. Do not put private key contents or passwords in this repository. Keep host-key checking enabled.

Confirmed ITM Business Solutions paths:

- Application: `/home/itmcvepp/laravel-itm`
- Domain web root: `/home/itmcvepp/itmbusinesssolutions.co.zw`
- Domain: `https://itmbusinesssolutions.co.zw`

`public_html` belongs to a different site. The cPanel account hosts several unrelated domains; never deploy this app to the account-wide web root or alter another domain.

Laravel's `public` path now links to the ITM domain web root, so compiled assets and dashboard image uploads use the directory actually served by cPanel. The domain's existing customised `index.php`, `.htaccess` and storage link were preserved.

## Deployed on 22 October 2026: scroll sequence

Commit `f65dc22` added the scroll-linked frame sequence to the homepage process section. The existing editable process image remains the still-image fallback. The release changed only the homepage view, its CSS and JavaScript source, one feature test, and compiled assets. It ran no migrations or seeders and did not replace `.env`, uploads or live records.

Before deployment, 151 local tests (617 assertions) and the frontend build passed. A fresh private backup was created at `/home/itmcvepp/itm-deployment-backups/20261022-161409`. The staged archive is `/home/itmcvepp/itm-release-f65dc22-20261022/release.tar.gz`; its SHA-256 was verified before extraction. The application and web root were checked before the update, then Laravel caches were rebuilt and maintenance mode was disabled.

After deployment, the HTTPS homepage returned 200 with the sequence marker and new asset references. The compiled CSS and JavaScript and frames 001 and 160 each returned 200. Production remains in non-debug mode, and the counts remain 1 user, 3 leads, 11 pages, 13 articles and 7 settings. A visual browser check of the scrolling animation has not yet been performed.

## Deployed on 22 October 2026

The release brought the GitHub code through `83a72bb` to the ITM domain. It included
the marketing-consent and lead-response features, the public editorial repairs, and
the compiled frontend assets. A fresh private backup was created at
`/home/itmcvepp/itm-deployment-backups/20261022-154246` before the site changed.
The private release staging directory is
`/home/itmcvepp/itm-release-83a72bb-20261022`.

Local verification passed 150 tests (613 assertions) and the frontend build. The
three release archives matched their SHA-256 hashes after transfer. The first
migration attempt stopped because MySQL rejected an automatically generated index
name longer than its identifier limit. The two partial marketing tables were
confirmed empty, removed, and recreated by the corrected migration with an
explicit shorter index name. All four new migrations are recorded as run. The
site was then brought out of maintenance mode.

No content-pack reseed or database reset was run. The live `.env`, customised
domain `index.php` and `.htaccess` retained their original checksums. The app's
`public` symlink still points to the dedicated ITM web root, and uploads remain
writable. Post-deployment checks returned 200 for the homepage, contact, legal,
service category, article, sitemap, login, images, CSS and JavaScript; guest
`/admin` redirected to login. The editorial prompts and generic article
descriptions targeted by the migration were absent from the live CMS. Counts
remained 1 user, 3 leads, 11 pages, 13 articles and 7 settings.

The staged application archive predates the one-line migration index fix. Do not
rerun that archive or its deployment script; build a fresh release from GitHub for
any future deployment.

## Deployed on 8 October 2026

The release included the public redesign and generated WebP assets, contact form/settings integration, dashboard image management, sidebar visibility fixes, full business-name wording, legal pages and SEO improvements.

Before deployment:

- Local verification: 130 tests passed, PHPStan passed, Pint passed, frontend build passed.
- Confirmed remote PHP 8.3.35, Laravel 13.32.0, MySQL, production mode and debugging disabled.
- Created private database and file backups before modifying the application.
- Compared transferred archive hashes with local SHA-256 hashes.

Deployment preserved `.env`, the existing server dependencies, client records, live uploads and contact settings. No full content-pack seed or database reset was run. Pending migrations were checked; none remained. Only legal pages and the new SEO guide/home metadata were imported, with business-name expansion in existing client-facing CMS copy. Configuration, route and view caches were rebuilt, and a queue restart signal was sent. This does not prove that a queue worker is continuously running; verify hosting cron/worker configuration separately.

Post-deployment checks:

- Homepage, contact, privacy, terms, registration/compliance categories, guide, sitemap and login returned 200.
- Admin returned 302 to login for a guest.
- CSS, JavaScript and both registration image variants returned 200.
- Canonical URLs point to the HTTPS production origin; public content is not noindex.
- The image upload directory is writable and resolves inside the served web root.
- Users remained 1, leads remained 0, pages remained 11 and settings remained 7. Articles increased from 12 to 13 for the new guide.

No authenticated dashboard upload or end-to-end email submission was performed against production. Browser visual checks were unavailable.

## Backup and rollback

Private backup directory:

`/home/itmcvepp/itm-deployment-backups/20261008-153232`

- `database.sql`: pre-deployment MySQL dump.
- `files.tar.gz`: pre-deployment Laravel application and ITM web root, including configuration and uploads.

Release staging directory: `/home/itmcvepp/itm-release-20261008-153232`.

These files must remain outside all served web roots. They contain sensitive data; do not publish or commit them. The original separate app public directory also remains at `/home/itmcvepp/laravel-itm/public-before-20261008-153232`.

The scripts in `tools/deployment/` record this specific deployment and use fixed confirmed paths. Do not rerun `deploy.sh` unchanged: prepare a fresh release, fresh backups and a reviewed plan for each subsequent deployment. Do not reuse a local lockfile on the server without checking PHP compatibility; this machine's local lock includes PHP 8.4-oriented dependencies while the server runs PHP 8.3.

For rollback, first determine whether live data has changed since this backup. Restore only the required code/assets for a code failure, preserve the current `.env` and uploads, account for the public-directory symlink, and rebuild caches. Restoring the database requires explicit approval and a fresh current backup because it could discard new users, enquiries or dashboard edits. Test the restored site before ending maintenance mode.

Google Search Console verification, sitemap submission, field performance measurement and legal review remain separate owner tasks. Deployment does not guarantee search rankings.
