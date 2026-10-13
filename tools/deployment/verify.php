<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require '/home/itmcvepp/laravel-itm/vendor/autoload.php';
$app = require '/home/itmcvepp/laravel-itm/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$origin = 'https://itmbusinesssolutions.co.zw';
$paths = ['/', '/contact', '/privacy', '/terms', '/services/category/company-registration',
    '/services/category/tax-fiscal-compliance', '/articles/company-registration-and-compliance-zimbabwe',
    '/sitemap.xml', '/login', '/admin', '/images/public/registration-v1.webp', '/images/public/registration-v1-640.webp'];
$manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true, 512, JSON_THROW_ON_ERROR);
foreach (['resources/css/app.css', 'resources/js/app.js'] as $entry) {
    $paths[] = '/build/'.$manifest[$entry]['file'];
}
$results = [];
foreach ($paths as $path) {
    $curl = curl_init($origin.$path);
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30]);
    $body = curl_exec($curl);
    if ($body === false) {
        throw new RuntimeException('HTTP check failed: '.$path.' '.curl_error($curl));
    }
    $result = ['path' => $path, 'status' => curl_getinfo($curl, CURLINFO_HTTP_CODE)];
    curl_close($curl);
    if (preg_match('/<title>\s*(.*?)\s*<\/title>/s', $body, $match)) {
        $result['title'] = html_entity_decode($match[1]);
        $result['noindex'] = str_contains($body, 'content="noindex, follow"');
        $result['placeholder'] = str_contains($body, '[Insert verified') || str_contains($body, 'The final policy must') || str_contains($body, 'The final terms must');
        preg_match('/rel="canonical" href="([^"]+)"/', $body, $canonical);
        $result['canonical'] = $canonical[1] ?? null;
    }
    $results[] = $result;
}
$counts = [];
foreach (['users', 'leads', 'pages', 'articles', 'settings'] as $table) {
    $counts[$table] = DB::table($table)->count();
}
echo json_encode([
    'http' => $results, 'counts' => $counts,
    'public_directory' => realpath(public_path()),
    'upload_directory_writable' => is_writable(config('filesystems.disks.public_images.root')),
    'production' => $app->isProduction(), 'debug' => config('app.debug'),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;
