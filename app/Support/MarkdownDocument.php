<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * A minimal front-matter + section parser for the PRD content pack.
 *
 * The pack is hand-written Markdown with YAML-ish front matter and predictable
 * `##` section headings, so it is parsed structurally rather than rendered as
 * one blob — that is what lets each part land in its own editable column.
 */
class MarkdownDocument
{
    /** @param array<string, string> $frontMatter */
    private function __construct(
        public readonly array $frontMatter,
        public readonly string $body,
    ) {}

    public static function parse(string $raw): self
    {
        $raw = str_replace("\r\n", "\n", $raw);
        $frontMatter = [];
        $body = $raw;

        if (str_starts_with($raw, "---\n")) {
            $end = strpos($raw, "\n---", 4);

            if ($end !== false) {
                $frontMatter = self::parseFrontMatter(substr($raw, 4, $end - 4));
                $body = ltrim(substr($raw, $end + 4), "\n");
            }
        }

        return new self($frontMatter, $body);
    }

    /** @return array<string, string> */
    private static function parseFrontMatter(string $block): array
    {
        $values = [];

        foreach (explode("\n", $block) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$key, $value] = explode(':', $line, 2);
            $value = trim($value);
            $value = trim($value, '"\'');

            // Unfilled placeholders like "[YYYY-MM-DD]" are absent, not content.
            if ($value === '' || (str_starts_with($value, '[') && str_ends_with($value, ']'))) {
                continue;
            }

            $values[trim($key)] = $value;
        }

        return $values;
    }

    public function frontMatter(string $key, ?string $default = null): ?string
    {
        return $this->frontMatter[$key] ?? $default;
    }

    /** The first `# Heading` in the body. */
    public function title(): ?string
    {
        return preg_match('/^#\s+(.+)$/m', $this->body, $m) === 1 ? trim($m[1]) : null;
    }

    /**
     * The paragraph directly beneath the H1, which the pack uses as the lead.
     */
    public function intro(): ?string
    {
        $afterTitle = preg_split('/^#\s+.+$/m', $this->body, 2);

        if (! isset($afterTitle[1])) {
            return null;
        }

        foreach (explode("\n\n", trim($afterTitle[1])) as $block) {
            $block = trim($block);

            if ($block === '' || str_starts_with($block, '#') || str_starts_with($block, '**')) {
                continue;
            }

            return $block;
        }

        return null;
    }

    /** The raw text under a given `##` heading, up to the next `##`. */
    public function section(string $heading): ?string
    {
        $pattern = '/^##\s+'.preg_quote($heading, '/').'\s*$(.*?)(?=^##\s|\z)/ms';

        return preg_match($pattern, $this->body, $m) === 1 ? trim($m[1]) : null;
    }

    /**
     * Bullet items under a heading, with Markdown checkboxes and links stripped.
     *
     * @return array<int, string>
     */
    public function listItems(string $heading): array
    {
        $section = $this->section($heading);

        if ($section === null) {
            return [];
        }

        preg_match_all('/^\s*(?:[-*]|\d+\.)\s+(.+)$/m', $section, $matches);

        return array_values(array_filter(array_map(
            fn (string $item) => self::plain(preg_replace('/^\[[ x]\]\s*/', '', trim($item)) ?? ''),
            $matches[1],
        )));
    }

    /**
     * `### Question` / answer pairs under a heading — the pack's FAQ shape.
     *
     * @return array<int, array{question: string, answer: string}>
     */
    public function questionAnswers(string $heading): array
    {
        $section = $this->section($heading);

        if ($section === null) {
            return [];
        }

        preg_match_all('/^###\s+(.+?)\s*$(.*?)(?=^###\s|\z)/ms', $section, $matches, PREG_SET_ORDER);

        $pairs = [];

        foreach ($matches as $match) {
            $answer = self::plain(trim($match[2]));

            if ($answer === '') {
                continue;
            }

            $pairs[] = ['question' => self::plain(trim($match[1])), 'answer' => $answer];
        }

        return $pairs;
    }

    /**
     * `### Heading` + following paragraph pairs — used by category and package
     * pages to list their children.
     *
     * @return array<int, array{title: string, slug: ?string, body: string}>
     */
    public function subsections(string $heading): array
    {
        $section = $this->section($heading);

        if ($section === null) {
            return [];
        }

        preg_match_all('/^###\s+(.+?)\s*$(.*?)(?=^###\s|\z)/ms', $section, $matches, PREG_SET_ORDER);

        return array_map(fn (array $match) => [
            'title' => self::plain($match[1]),
            'slug' => preg_match('/\]\(\/services\/([a-z0-9-]+)\)/', $match[1], $m) === 1 ? $m[1] : null,
            'body' => self::plain(trim($match[2])),
        ], $matches);
    }

    /** Strip Markdown emphasis and links down to readable prose. */
    public static function plain(string $value): string
    {
        $value = preg_replace('/\[([^\]]+)\]\([^)]*\)/', '$1', $value) ?? $value;
        $value = str_replace(['**', '`'], '', $value);

        return trim($value);
    }

    public static function slug(string $value): string
    {
        return Str::slug($value);
    }
}
