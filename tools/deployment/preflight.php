<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require '/home/itmcvepp/laravel-itm/vendor/autoload.php';
$app = require '/home/itmcvepp/laravel-itm/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$counts = [];
foreach (['users', 'leads', 'pages', 'articles', 'settings'] as $table) {
    $counts[$table] = DB::table($table)->count();
}
echo json_encode([
    'php' => PHP_VERSION, 'laravel' => $app->version(), 'environment' => $app->environment(),
    'debug' => config('app.debug'), 'url' => config('app.url'), 'public_path' => public_path(),
    'database_driver' => DB::connection()->getDriverName(),
    'counts' => $counts, 'free_bytes' => disk_free_space('/home/itmcvepp'),
    'gd' => extension_loaded('gd'), 'webp' => function_exists('imagewebp'),
], JSON_PRETTY_PRINT).PHP_EOL;
