<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Support\MarkdownDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Imports the PRD content pack in docs/ into the database.
 *
 * Idempotent: every record is matched on its slug and updated, so this can be
 * re-run after the pack is edited without duplicating anything or discarding
 * unrelated rows the owner has since created in the admin.
 */
class ContentPackSeeder extends Seeder
{
    private string $root;

    /** Category slug → display order, matching the PRD's navigation order. */
    private const CATEGORY_ORDER = [
        'company-registration',
        'tax-fiscal-compliance',
        'branding-business-documents',
        'tender-supplier-registration',
        'nssa-employer-compliance',
        'trusts-organisations',
        'business-operator-licences',
    ];

    private const CATEGORY_ICONS = [
        'company-registration' => 'buildings',
        'tax-fiscal-compliance' => 'receipt',
        'branding-business-documents' => 'palette',
        'tender-supplier-registration' => 'gavel',
        'nssa-employer-compliance' => 'users-three',
        'trusts-organisations' => 'hand-heart',
        'business-operator-licences' => 'certificate',
    ];

    /** Services surfaced on the homepage, per content/public/home.md. */
    private const FEATURED = [
        'private-limited-company-registration',
        'tin-tax-clearance',
        'tax-returns-submission',
        'praz-registration',
        'nssa-employer-registration',
        'company-profile-design',
    ];

    public function run(): void
    {
        $this->root = base_path('docs/ITM_Website_PRD_and_Content_Pack/ITM_Website_PRD_Content_Pack');

        if (! is_dir($this->root)) {
            throw new RuntimeException("Content pack not found at {$this->root}");
        }

        $this->seedSettings();
        $this->seedCategories();
        $this->seedServices();
        $this->seedRelatedServices();
        $this->seedPackages();
        $this->seedArticles();
        $this->seedPages();
    }

    private function document(string $relativePath): MarkdownDocument
    {
        $path = $this->root.'/'.$relativePath;

        if (! is_file($path)) {
            throw new RuntimeException("Missing content file: {$relativePath}");
        }

        return MarkdownDocument::parse((string) file_get_contents($path));
    }

    /** @return array<int, string> */
    private function files(string $directory): array
    {
        $paths = glob($this->root.'/content/'.$directory.'/*.md') ?: [];

        return array_values(array_filter(
            $paths,
            fn (string $path) => ! in_array(basename($path), ['index.md', 'ARTICLE-TEMPLATE.md', 'EDITORIAL-CALENDAR.md'], true),
        ));
    }

    private function seedSettings(): void
    {
        // Placeholders until the business confirms them — the PRD lists these
        // as configuration inputs, and the board forbids inventing details.
        $defaults = [
            'contact.phone' => ['+263 ·· ··', 'contact'],
            'contact.whatsapp' => ['+263 ·· ··', 'contact'],
            'contact.email' => ['hello@itmbusinesssolutions.co.zw', 'contact'],
            'contact.hours' => ['Mon–Fri, 08:00–17:00', 'contact'],
            'contact.address' => ['Harare, Zimbabwe', 'contact'],
            'site.tagline' => ['Ideas today. A brighter tomorrow.', 'site'],
            'site.safety_note' => ['ITM will never ask for your password, banking PIN or a one-time code.', 'site'],
        ];

        foreach ($defaults as $key => [$value, $group]) {
            if (Setting::query()->whereKey($key)->doesntExist()) {
                Setting::put($key, $value, $group);
            }
        }
    }

    private function seedCategories(): void
    {
        foreach ($this->files('categories') as $path) {
            $slug = basename($path, '.md');
            $doc = MarkdownDocument::parse((string) file_get_contents($path));
            $order = array_search($slug, self::CATEGORY_ORDER, true);

            ServiceCategory::updateOrCreate(['slug' => $slug], [
                'name' => $doc->title() ?? Str::headline($slug),
                'summary' => $doc->intro(),
                'body' => $doc->section('How the quotation process works'),
                'audience' => $doc->listItems('Who this category helps'),
                'seo_title' => $doc->frontMatter('seo_title'),
                'meta_description' => $doc->frontMatter('meta_description'),
                'primary_keyword' => $doc->frontMatter('primary_keyword'),
                'icon' => self::CATEGORY_ICONS[$slug] ?? 'folder',
                'position' => $order === false ? 99 : $order,
                'is_published' => $doc->frontMatter('indexable') !== 'false',
            ]);
        }
    }

    private function seedServices(): void
    {
        $categories = ServiceCategory::pluck('id', 'name');
        $position = 0;

        foreach ($this->files('services') as $path) {
            $slug = basename($path, '.md');
            $doc = MarkdownDocument::parse((string) file_get_contents($path));
            $categoryName = $doc->frontMatter('category');

            $categoryId = $categoryName !== null && $categories->has($categoryName)
                ? $categories[$categoryName]
                : ServiceCategory::query()->orderBy('position')->value('id');

            $service = Service::updateOrCreate(['slug' => $slug], [
                'service_category_id' => $categoryId,
                'name' => $doc->title() ?? Str::headline($slug),
                'summary' => $doc->frontMatter('meta_description'),
                'intro' => $doc->intro(),
                'suited_to' => $doc->listItems('Is this service right for you?'),
                'includes' => $doc->listItems('What the service can include'),
                'requirements' => $doc->listItems('Starting requirements'),
                'steps' => $doc->listItems('How it works'),
                'charges_note' => $doc->section('Charges and payment'),
                'processing_note' => $doc->section('Processing and progress'),
                'source_note' => $doc->section('Source and review note'),
                'seo_title' => $doc->frontMatter('seo_title'),
                'meta_description' => $doc->frontMatter('meta_description'),
                'primary_keyword' => $doc->frontMatter('primary_keyword'),
                'is_featured' => in_array($slug, self::FEATURED, true),
                'is_published' => $doc->frontMatter('indexable') !== 'false',
                'position' => $position++,
                'reviewer' => $doc->frontMatter('reviewer'),
            ]);

            $service->faqs()->delete();

            foreach ($doc->questionAnswers('Frequently asked questions') as $index => $faq) {
                $service->faqs()->create([
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'position' => $index,
                ]);
            }
        }
    }

    /** Wires the "Related services" links once every service row exists. */
    private function seedRelatedServices(): void
    {
        $ids = Service::pluck('id', 'slug');

        foreach ($this->files('services') as $path) {
            $slug = basename($path, '.md');
            $doc = MarkdownDocument::parse((string) file_get_contents($path));
            $section = $doc->section('Related services') ?? '';

            preg_match_all('/\]\(\/services\/([a-z0-9-]+)\)/', $section, $matches);

            $related = [];

            foreach (array_values(array_unique($matches[1])) as $position => $relatedSlug) {
                if ($relatedSlug !== $slug && $ids->has($relatedSlug)) {
                    $related[$ids[$relatedSlug]] = ['position' => $position];
                }
            }

            if ($ids->has($slug)) {
                Service::query()->whereKey($ids[$slug])->first()?->relatedServices()->sync($related);
            }
        }
    }

    private function seedPackages(): void
    {
        $doc = $this->document('content/public/packages.md');
        $position = 0;

        // Each package is a `## Name` block, excluding the summary sections.
        preg_match_all('/^##\s+(.+?)\s*$(.*?)(?=^##\s|\z)/ms', $doc->body, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $name = MarkdownDocument::plain($match[1]);

            if (in_array($name, ['Your quotation will show'], true)) {
                continue;
            }

            $body = trim($match[2]);
            $body = preg_replace('/^\*\*CTA:\*\*.*$/m', '', $body) ?? $body;

            Package::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'summary' => MarkdownDocument::plain(trim($body)),
                'is_published' => true,
                'position' => $position++,
            ]);
        }

        // The "what a quotation shows" list belongs to the packages page itself.
        Page::updateOrCreate(['slug' => 'packages'], [
            'title' => $doc->title() ?? 'Packages',
            'seo_title' => $doc->frontMatter('seo_title'),
            'meta_description' => $doc->frontMatter('meta_description'),
            'body' => $doc->intro(),
            'is_system' => true,
        ]);
    }

    private function seedArticles(): void
    {
        $categories = ServiceCategory::pluck('id', 'slug');
        $position = 0;

        foreach ($this->files('articles') as $path) {
            $slug = basename($path, '.md');
            $doc = MarkdownDocument::parse((string) file_get_contents($path));

            $categorySlug = $doc->frontMatter('category');
            $categorySlug = $categorySlug !== null ? Str::slug($categorySlug) : null;
            // The pack's free-text "category:" front matter (e.g. "Starting a
            // Business") doesn't always match one of the site's real service
            // categories — only trust it as a link target once it does.
            $resolvedCategorySlug = $categorySlug !== null && $categories->has($categorySlug) ? $categorySlug : null;

            // Same "**CTA:** Label" convention as static pages — pulled into
            // its own field so it renders as a real button, not bold text.
            $ctaLabel = null;
            $ctaUrl = null;
            $body = $doc->body;

            if (preg_match('/^\*\*CTA:\*\*\s*(.+?)\s*$/mi', $body, $m) === 1) {
                $body = trim(preg_replace('/^\*\*CTA:\*\*.*$/mi', '', $body) ?? $body);
                $ctaLabel = MarkdownDocument::plain($m[1]);
                $ctaUrl = $this->resolveArticleCtaUrl($ctaLabel, $resolvedCategorySlug);
            }

            Article::updateOrCreate(['slug' => $slug], [
                'service_category_id' => $resolvedCategorySlug !== null ? $categories[$resolvedCategorySlug] : null,
                'title' => $doc->title() ?? Str::headline($slug),
                'excerpt' => $doc->frontMatter('meta_description') ?? $doc->intro(),
                'body' => $body,
                'cta_label' => $ctaLabel,
                'cta_url' => $ctaUrl,
                'seo_title' => $doc->frontMatter('seo_title'),
                'meta_description' => $doc->frontMatter('meta_description'),
                'primary_keyword' => $doc->frontMatter('primary_keyword'),
                'author' => $doc->frontMatter('author'),
                'reviewer' => $doc->frontMatter('reviewer'),
                'is_published' => $doc->frontMatter('indexable') !== 'false',
                'published_at' => now(),
                'position' => $position++,
            ]);
        }
    }

    /**
     * An article's CTA almost always points at requesting an assessment, the
     * same intent behind every generic label the pack uses ("Request a Tax
     * Readiness Assessment", "Compare Structures", …). The one exception is
     * a label that names "Services" directly, which browses services instead
     * — the article's own category if it resolved to a real one, else the
     * full services index.
     */
    private function resolveArticleCtaUrl(string $label, ?string $categorySlug): string
    {
        if (str_contains($label, 'Services')) {
            return $categorySlug !== null ? route('services.category', $categorySlug) : route('services.index');
        }

        return route('quote.create');
    }

    /**
     * Static pages that have their own route but whose copy the owner edits.
     */
    private function seedPages(): void
    {
        $pages = [
            'about' => 'content/public/about.md',
            'contact' => 'content/public/contact.md',
            'privacy' => 'content/public/privacy-policy.md',
            'terms' => 'content/public/terms-of-service.md',
            'document-handling' => 'content/public/document-handling.md',
            'requirements' => 'content/public/requirements-centre.md',
            'resources' => 'content/public/resources.md',
            'faq' => 'content/public/faq.md',
            'home' => 'content/public/home.md',
            'services' => 'content/public/services.md',
        ];

        foreach ($pages as $slug => $relativePath) {
            $doc = $this->document($relativePath);

            // The pack marks an in-body call to action as "**CTA:** Label" —
            // pull it into its own field so it renders as a real button
            // component instead of being dumped into the prose as bold text.
            $ctaLabel = null;
            $ctaUrl = null;
            $body = $doc->body;

            if (preg_match('/^\*\*CTA:\*\*\s*(.+?)\s*$/mi', $body, $m) === 1) {
                $body = trim(preg_replace('/^\*\*CTA:\*\*.*$/mi', '', $body) ?? $body);
                $ctaUrl = $this->resolvePageCtaUrl(MarkdownDocument::plain($m[1]));
                $ctaLabel = $ctaUrl !== null ? MarkdownDocument::plain($m[1]) : null;
            }

            Page::updateOrCreate(['slug' => $slug], [
                'title' => $doc->title() ?? Str::headline($slug),
                'seo_title' => $doc->frontMatter('seo_title'),
                'meta_description' => $doc->frontMatter('meta_description'),
                'body' => $body,
                'cta_label' => $ctaLabel,
                'cta_url' => $ctaUrl,
                'is_indexable' => $doc->frontMatter('indexable') !== 'false',
                'is_system' => true,
            ]);
        }

        // Site-wide FAQs for /faq. This page states each question as its own
        // `##` heading, unlike service pages which nest them under `###`.
        $faqDoc = $this->document('content/public/faq.md');
        Faq::query()->global()->delete();

        preg_match_all('/^##\s+(.+?)\s*$(.*?)(?=^##\s|\z)/ms', $faqDoc->body, $matches, PREG_SET_ORDER);

        $position = 0;

        foreach ($matches as $match) {
            $answer = MarkdownDocument::plain(trim($match[2]));

            if ($answer === '') {
                continue;
            }

            Faq::create([
                'question' => MarkdownDocument::plain($match[1]),
                'answer' => $answer,
                'position' => $position++,
            ]);
        }
    }

    /**
     * Maps a content pack CTA label to the route it should link to. Only
     * labels the pack actually uses are recognised — an unresolved label
     * leaves the page without an in-body CTA rather than linking nowhere.
     */
    private function resolvePageCtaUrl(string $label): ?string
    {
        return match ($label) {
            'View Our Services', 'Choose a Service Checklist' => route('services.index'),
            'Browse All Articles' => route('articles.index'),
            default => null,
        };
    }
}
