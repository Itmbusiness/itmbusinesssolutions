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

require '/home/itmcvepp/laravel-itm/vendor/autoload.php';
$app = require '/home/itmcvepp/laravel-itm/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function expandBusinessName(mixed $value): mixed
{
    return is_array($value) ? array_map('expandBusinessName', $value)
        : (is_string($value) ? preg_replace('/\bITM\b(?!\s+Business\s+Solutions)/', 'ITM Business Solutions', $value) : $value);
}

DB::transaction(function (): void {
    $fields = ['title', 'name', 'summary', 'body', 'intro', 'excerpt', 'author', 'reviewer',
        'answer', 'question', 'seo_title', 'meta_description', 'includes', 'requirements',
        'steps', 'suited_to', 'audience', 'cta_label'];
    foreach ([Page::class, Article::class, Service::class, ServiceCategory::class, Package::class, Faq::class] as $modelClass) {
        foreach ($modelClass::all() as $model) {
            foreach ($fields as $field) {
                if (array_key_exists($field, $model->getAttributes())) {
                    $model->setAttribute($field, expandBusinessName($model->getAttribute($field)));
                }
            }
            if ($model->isDirty()) {
                $model->save();
            }
        }
    }
    foreach (['site.tagline', 'site.safety_note'] as $key) {
        $value = Setting::get($key);
        $updated = expandBusinessName($value);
        if ($value !== $updated) {
            Setting::put($key, $updated, 'site');
        }
    }
});
echo "Business name normalization completed without replacing contact settings.\n";
