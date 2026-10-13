#!/usr/bin/env bash
set -euo pipefail

APP=/home/itmcvepp/laravel-itm
WEB=/home/itmcvepp/itmbusinesssolutions.co.zw
RELEASE=/home/itmcvepp/itm-release-20261008-153232
BACKUP=/home/itmcvepp/itm-deployment-backups/20261008-153232

test -s "$BACKUP/database.sql"
gzip -t "$BACKUP/files.tar.gz"
gzip -t "$RELEASE/application.tar.gz"
gzip -t "$RELEASE/public-assets.tar.gz"
test "$(realpath "$APP")" = /home/itmcvepp/laravel-itm
test "$(realpath "$WEB")" = /home/itmcvepp/itmbusinesssolutions.co.zw

cd "$APP"
php artisan down --retry=60
tar -xzf "$RELEASE/application.tar.gz" -C "$APP"
tar -xzf "$RELEASE/public-assets.tar.gz" -C "$WEB"

# Match Laravel's public_path to the separate cPanel document root.
if [ ! -L "$APP/public" ]; then
    test ! -e "$APP/public-before-20261008-153232"
    mv "$APP/public" "$APP/public-before-20261008-153232"
    ln -s "$WEB" "$APP/public"
fi
test "$(realpath "$APP/public")" = "$WEB"
mkdir -p "$WEB/images/uploads"
chmod 755 "$WEB/images/uploads"

php artisan optimize:clear
php artisan migrate --force
php artisan db:seed --class=LegalPagesSeeder --force
php artisan db:seed --class=SeoContentSeeder --force
php "$RELEASE/finalize.php"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
php artisan up
printf 'DEPLOYMENT_COMPLETE\n'
