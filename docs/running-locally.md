# Running locally (Windows)

## The short version

Two terminals:

```powershell
php artisan serve          # http://localhost:8000
npm run dev                # Vite, for hot reload
```

For a one-off check without Vite running, `npm run build` first and `php artisan serve`
will serve the built assets.

If `php artisan serve` fails with `Failed opening required '...resources/server.php'`,
see quirk #4 below (Avast) — `php -S 127.0.0.1:8000 -t public` is the immediate
workaround.

Useful routes: `/` (public homepage), `/login`, `/register`, `/dashboard`,
and `/design-system` (the component reference board, non-production only).

Lead-notification emails (`App\Mail\NewLeadReceived`, `LeadConfirmation`) are
queued (`QUEUE_CONNECTION=database`), so submitting the quote form or a
workspace request only inserts a row into the `jobs` table — nothing actually
sends until a worker processes it:

```powershell
php artisan queue:work
```

With `MAIL_MAILER=log` (the default), a processed job writes the rendered
email to `storage/logs/laravel.log` instead of delivering it — set real SMTP
credentials in `.env` to send for real.

## This machine's quirks

Six things had to be worked around to get a first install through. They are
environmental, not project bugs — but they will bite again on a fresh clone.

### 1. The PHP on PATH needs a `php.ini`

PATH points at the WinGet build:

```
C:\Users\methu\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\
```

It ships `php.ini-development` and `php.ini-production` but **no `php.ini`**, so PHP
starts with zero optional extensions. That is what produces:

```
could not find driver (Connection: sqlite, …)
```

`PDO` is built in, but `pdo_sqlite` is a DLL that only loads if an ini enables it.

A `php.ini` now exists in that directory, copied from `php.ini-development` with
`extension_dir` set and these enabled: `curl`, `fileinfo`, `gd`, `intl`, `mbstring`,
`openssl`, `pdo_sqlite`, `sqlite3`, `sodium`, `exif`, `zip`. Verify with:

```powershell
php -r "echo implode(',', PDO::getAvailableDrivers());"   # expect: sqlite
```

Caveat: a WinGet upgrade of PHP may replace that directory and drop the file. If the
"could not find driver" error returns after an update, re-create it. There is also an
unrelated standalone PHP at `C:\tmp\ovanest-php84\php.exe` which already has its own
working ini.

Installing [Laravel Herd](https://herd.laravel.com/windows) would replace all of this
with a managed PHP and is the smoother long-term answer.

### 1b. Restart the server after changing `php.ini`

`php artisan serve` reads the ini once at boot. If an old server is still bound to
port 8000, a newly started one silently fails to bind and you keep hitting the old
process with the old config. Kill it first:

```powershell
Get-Process php | Stop-Process -Force
php artisan serve
```

### 2. Avast intercepts TLS, so Composer cannot verify GitHub

Avast Web/Mail Shield re-signs HTTPS traffic with its own root, which PHP's curl
does not trust out of the box — every download fails with
`curl error 60 … unable to get local issuer certificate`.

A combined bundle (the official cacert.pem plus the Windows certificate stores,
which include the Avast root) is at `C:\tmp\ovanest-php84\cacert-combined.pem`.
Point PHP at it, ideally by setting it in `php.ini`:

```ini
curl.cainfo = "C:\tmp\ovanest-php84\cacert-combined.pem"
openssl.cafile = "C:\tmp\ovanest-php84\cacert-combined.pem"
```

Regenerate it when Avast rotates its root, or exclude this project from Avast's
HTTPS scanning.

### 3. OneDrive/Avast lock Composer's temp archives

The project lives in OneDrive, and Composer extracts through `vendor/composer/tmp-*.zip`.
The indexer and the AV scanner hold those files open, so installs fail intermittently
with `Access is denied` or `Could not delete`. Re-running `composer install` clears it
(it took two attempts here). Moving the project outside OneDrive would remove the
problem entirely.

Related: `--prefer-source` cannot be used on Windows — `vlucas/phpdotenv`'s git tree
contains `tests/fixtures/env/nul.env`, and `nul` is a reserved device name. Stay on
`--prefer-dist` (the default).

### 4. Avast quarantines Laravel's dev-server router script

`vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php` — the tiny
router `php artisan serve` uses to emulate `mod_rewrite` — reads the request URI and
conditionally `require`s a file based on it. That is also the generic shape of a PHP
webshell, and Avast's heuristics flag it. On this machine it has been silently deleted
from `vendor/` more than once, which turns every request into:

```
Fatal error: Failed opening required '...\Foundation\Console/../resources/server.php'
```

first intermittently under concurrent connections (a browser loading the page fires
several requests at once; on Windows `php -S` has no worker/fork support, so it
serialises them — the crash was Avast's on-access scan catching the file mid-`include`
under that load), then, once quarantined, on every single request, concurrent or not.

**Confirm it's this**, not something else, by checking the file exists:

```powershell
Test-Path vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php
```

**Immediate workaround**, no admin access needed — skip the router entirely. PHP's
built-in server falls back to `index.php` for any path that isn't a real file even
without a router script, which is all Laravel's router does differently for local
dev, so this serves the app identically:

```powershell
php -S 127.0.0.1:8000 -t public
```

**Permanent fix** — restore the file, then stop Avast from taking it again:

1. Open Avast → Protection → Virus Chest and restore `server.php` if it's sitting
   there quarantined (fastest path — no reinstall needed).
2. If it's gone from the chest too, extract it fresh from Composer's own cache
   without touching the network:
   ```powershell
   Add-Type -AssemblyName System.IO.Compression.FileSystem
   $zip = [System.IO.Compression.ZipFile]::OpenRead(
       (Get-ChildItem "$env:LOCALAPPDATA\Composer\files\laravel\framework\*.zip" | Select -First 1)
   )
   $entry = $zip.Entries | Where-Object { $_.FullName -match 'Foundation[\\/]resources[\\/]server\.php$' }
   [System.IO.Compression.ZipFileExtensions]::ExtractToFile(
       $entry, 'vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php', $true
   )
   $zip.Dispose()
   ```
3. Add an Avast exclusion for the project folder (or at least `vendor\laravel\framework`)
   so it stops recurring — Avast → Settings → Exceptions.

Until step 3 is done, expect this to come back after any `composer install` that
re-extracts `laravel/framework`.

### 5. SQLite cannot be written inside OneDrive

With the database at its default `database/database.sqlite`, every write fails:

```
SQLSTATE[HY000]: General error: 8 attempt to write a readonly database
```

It is not a permissions problem, and the usual checks all mislead you —
`is_writable()` returns true for both the file and its directory, PHP can create
and delete other files in `database/` (including a `.sqlite` one), and raw PDO can
*read* the database fine. Only writes fail, because SQLite's locking and journal
handling do not work through OneDrive's sync layer once it has touched the file.
Migrations typically succeed right after the file is created, then writes start
failing a few minutes later once OneDrive syncs it.

The fix is to keep the database outside the synced tree. `.env` now points at:

```ini
DB_DATABASE=C:\itm-data\database.sqlite
```

That directory is not synced by anything, and writes work normally there. If you
clone fresh or reset the database, recreate it in the same place rather than
moving it back under `database/`:

```powershell
New-Item -ItemType Directory -Force C:\itm-data
New-Item -ItemType File C:\itm-data\database.sqlite
php artisan migrate
```

`database/database.sqlite` is still in the repo tree and still gitignored; it is
simply no longer the database the app uses on this machine. Moving the whole
project out of OneDrive would remove this and quirks #3 and #4 at once.

### 6. `laravel/boost` does not support Laravel 13

This one is a real project issue, not environmental. `composer.json` requires
`laravel/boost ^1.0`, whose newest release caps at `illuminate/support ^12.41`.
**`composer install` cannot resolve against the committed `composer.json`.**

The repo already carries a workaround manifest — `composer.verify.json`, identical
to `composer.json` minus Boost — and its `composer.verify.lock`. That is what the
current `vendor/` was installed from:

```powershell
$env:COMPOSER = "composer.verify.json"
composer install
```

Pick one of these to make the normal command work again:

- drop `laravel/boost` from `composer.json` (and the Boost section of `AGENTS.md`), or
- pin it to a release that supports Laravel 13 once one ships, or
- keep the split manifest and document it as the intended workflow.

Until then note that `vendor/` was installed with `--no-dev`, so **PHPUnit, Pint and
PHPStan are not present** and `composer test` / `composer lint` will not run. Install
dev dependencies once the Boost constraint is resolved.
