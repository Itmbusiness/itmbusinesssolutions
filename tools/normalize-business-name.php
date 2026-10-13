<?php

use App\Models\Article;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function fullBusinessName(mixed $value): mixed
{
    if (is_array($value)) {
        return array_map('fullBusinessName', $value);
    }

    return is_string($value)
        ? preg_replace('/\bITM\b(?!\s+Business\s+Solutions)/', 'ITM Business Solutions', $value)
        : $value;
}

$fields = ['title', 'name', 'summary', 'body', 'intro', 'excerpt', 'author', 'reviewer',
    'answer', 'question', 'seo_title', 'meta_description', 'includes', 'requirements',
    'steps', 'suited_to', 'audience', 'cta_label'];
$changed = 0;
DB::transaction(function () use ($fields, &$changed): void {
    foreach ([Page::class, Article::class, Service::class, ServiceCategory::class, Package::class, Faq::class] as $modelClass) {
        foreach ($modelClass::all() as $model) {
            foreach ($fields as $field) {
                if (array_key_exists($field, $model->getAttributes())) {
                    $model->setAttribute($field, fullBusinessName($model->getAttribute($field)));
                }
            }
            if ($model->isDirty()) {
                $model->save();
                $changed++;
            }
        }
    }
    foreach (['site.tagline', 'site.safety_note'] as $key) {
        $value = Setting::get($key);
        $updated = fullBusinessName($value);
        if ($value !== $updated) {
            Setting::put($key, $updated, 'site');
            $changed++;
        }
    }
});

$contentDirectory = dirname(__DIR__).'/docs/ITM_Website_PRD_and_Content_Pack/ITM_Website_PRD_Content_Pack/content';
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($contentDirectory, FilesystemIterator::SKIP_DOTS)) as $file) {
    if ($file->getExtension() === 'md') {
        $original = file_get_contents($file->getPathname());
        $updated = fullBusinessName($original);
        if ($original !== $updated) {
            file_put_contents($file->getPathname(), $updated);
        }
    }
}

echo 'Updated '.$changed." saved content records.\n";
