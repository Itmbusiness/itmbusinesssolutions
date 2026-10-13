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
