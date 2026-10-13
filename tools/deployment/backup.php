<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require '/home/itmcvepp/laravel-itm/vendor/autoload.php';
$app = require '/home/itmcvepp/laravel-itm/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$directory = '/home/itmcvepp/itm-deployment-backups/'.gmdate('Ymd-His');
if (! mkdir($directory, 0700, true)) {
    throw new RuntimeException('Cannot create private backup directory.');
}

function runBackup(array $command, string $destination, ?array $environment = null): void
{
    $output = fopen($destination, 'wb');
    chmod($destination, 0600);
    $process = proc_open($command, [0 => ['file', '/dev/null', 'r'], 1 => $output, 2 => ['pipe', 'w']], $pipes, null, $environment);
    if (! is_resource($process)) {
        throw new RuntimeException('Could not launch backup.');
    }
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    fclose($output);
    if (proc_close($process) !== 0 || filesize($destination) === 0) {
        throw new RuntimeException('Backup failed; deployment must stop. '.preg_replace('/password[^\s]*/i', '[redacted]', $errors));
    }
}

$connection = DB::connection()->getConfig();
if (DB::connection()->getDriverName() !== 'mysql') {
    throw new RuntimeException('Unexpected database driver.');
}
$environment = getenv();
$environment['MYSQL_PWD'] = $connection['password'];
runBackup([
    'mysqldump', '--single-transaction', '--quick', '--no-tablespaces',
    '--host='.$connection['host'], '--port='.($connection['port'] ?? 3306),
    '--user='.$connection['username'], $connection['database'],
], $directory.'/database.sql', $environment);
runBackup(['tar', '-czf', '-', '-C', '/home/itmcvepp', 'laravel-itm', 'itmbusinesssolutions.co.zw'], $directory.'/files.tar.gz');
echo json_encode(['backup' => $directory, 'database_bytes' => filesize($directory.'/database.sql'), 'archive_bytes' => filesize($directory.'/files.tar.gz')], JSON_PRETTY_PRINT).PHP_EOL;
