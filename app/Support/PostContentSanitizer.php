<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class PostContentSanitizer
{
    private static ?HtmlSanitizer $sanitizer = null;

    /**
     * Return safe HTML for display from post content.
     * Supports: Filament Builder blocks (array of { type, data }), TipTap JSON doc, or HTML string.
     */
    public static function sanitize(array|string|null $content): string
    {
        if ($content === null || $content === '') {
            return '';
        }

        $html = \is_array($content)
            ? self::arrayContentToHtml($content)
            : (string) $content;

        return self::sanitizer()->sanitize($html);
    }

    /**
     * Detect format and convert array content to HTML (builder blocks or TipTap doc).
     */
    private static function arrayContentToHtml(array $content): string
    {
        if (self::isBuilderBlocks($content)) {
            return self::builderBlocksToHtml($content);
        }

        return self::tiptapJsonToHtml($content);
    }

    /**
     * Check if array is Filament Builder format: list of items with 'type' and 'data' keys.
     */
    private static function isBuilderBlocks(array $content): bool
    {
        if ($content === []) {
            return false;
        }
        $first = reset($content);
        if (!\is_array($first)) {
            return false;
        }
        return \array_key_exists('type', $first) && \array_key_exists('data', $first);
    }

    /**
     * Render Filament Builder blocks (paragraph, image) to HTML.
     */
    private static function builderBlocksToHtml(array $blocks): string
    {
        $out = '';
        foreach ($blocks as $item) {
            if (!\is_array($item)) {
                continue;
            }
            $type = $item['type'] ?? '';
            $data = \is_array($item['data'] ?? null) ? $item['data'] : [];
            $out .= self::builderBlockToHtml($type, $data);
        }

        return $out;
    }

    private static function builderBlockToHtml(string $type, array $data): string
    {
        return match ($type) {
            'paragraph' => self::builderParagraphToHtml($data),
            'image' => self::builderImageToHtml($data),
            default => '',
        };
    }

    private static function builderParagraphToHtml(array $data): string
    {
        $text = $data['text'] ?? null;
        if ($text === null || $text === '') {
            return '';
        }
        if (\is_string($text)) {
            return self::sanitizer()->sanitize('<p>' . $text . '</p>');
        }
        if (\is_array($text)) {
            $inner = self::tiptapJsonToHtml($text);
            if ($inner === '') {
                return '';
            }
            return self::sanitizer()->sanitize($inner);
        }

        return '';
    }

    private static function builderImageToHtml(array $data): string
    {
        $image = $data['image'] ?? null;
        if ($image === null || $image === '') {
            return '';
        }
        $url = \is_array($image) ? ($image[0] ?? '') : (string) $image;
        if ($url === '') {
            return '';
        }
        if (!str_starts_with($url, 'http') && !str_starts_with($url, '/')) {
            $url = \Illuminate\Support\Facades\Storage::url($url);
        }
        $alt = \is_string($data['alt'] ?? null) ? $data['alt'] : '';

        return '<figure class="my-4"><img src="' . \e($url) . '" alt="' . \e($alt) . '" class="max-w-full h-auto rounded-lg" loading="lazy">' . ($alt !== '' ? '<figcaption class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">' . \e($alt) . '</figcaption>' : '') . '</figure>';
    }

    private static function sanitizer(): HtmlSanitizer
    {
        if (self::$sanitizer === null) {
            self::$sanitizer = new HtmlSanitizer(
                (new HtmlSanitizerConfig())
                    ->allowSafeElements()
                    ->allowRelativeLinks()
            );
        }

        return self::$sanitizer;
    }

    /**
     * Convert TipTap/ProseMirror JSON document to HTML. Only emits whitelisted tags; text is escaped.
     * Defensively validates node shape so malformed payloads degrade to empty output instead of throwing.
     */
    private static function tiptapJsonToHtml(array $node): string
    {
        $type = $node['type'] ?? '';
        $content = \is_array($node['content'] ?? null) ? $node['content'] : [];
        $text = $node['text'] ?? null;

        if ($text !== null && \is_scalar($text)) {
            $html = e((string) $text);
            $marks = \is_array($node['marks'] ?? null) ? $node['marks'] : [];
            foreach ($marks as $mark) {
                if (!\is_array($mark)) {
                    continue;
                }
                $markType = $mark['type'] ?? '';
                $html = match ($markType) {
                    'bold' => "<strong>{$html}</strong>",
                    'italic' => "<em>{$html}</em>",
                    'link' => '<a href="' . \e(self::linkHrefFromMark($mark)) . '">' . $html . '</a>',
                    'code' => "<code>{$html}</code>",
                    'strike' => "<s>{$html}</s>",
                    'underline' => "<u>{$html}</u>",
                    default => $html,
                };
            }

            return $html;
        }

        $children = implode('', array_map(
            fn (mixed $child): string => \is_array($child) ? self::tiptapJsonToHtml($child) : '',
            $content
        ));

        return match ($type) {
            'doc' => $children,
            'paragraph' => "<p>{$children}</p>",
            'heading' => self::headingOpenTag($node).$children.self::headingCloseTag($node),
            'bulletList' => "<ul>{$children}</ul>",
            'orderedList' => "<ol>{$children}</ol>",
            'listItem' => "<li>{$children}</li>",
            'blockquote' => "<blockquote>{$children}</blockquote>",
            'codeBlock' => "<pre><code>{$children}</code></pre>",
            'horizontalRule' => '<hr>',
            'hardBreak' => '<br>',
            default => $children,
        };
    }

    /**
     * Safe href from link mark: attrs must be array and href must be string; otherwise '#'.
     */
    private static function linkHrefFromMark(array $mark): string
    {
        $attrs = $mark['attrs'] ?? null;
        if (!\is_array($attrs)) {
            return '#';
        }
        $href = $attrs['href'] ?? null;

        return \is_string($href) ? $href : '#';
    }

    private static function headingOpenTag(array $node): string
    {
        $attrs = \is_array($node['attrs'] ?? null) ? $node['attrs'] : [];
        $level = (int) ($attrs['level'] ?? 1);
        $level = max(1, min(6, $level));

        return "<h{$level}>";
    }

    private static function headingCloseTag(array $node): string
    {
        $attrs = \is_array($node['attrs'] ?? null) ? $node['attrs'] : [];
        $level = (int) ($attrs['level'] ?? 1);
        $level = max(1, min(6, $level));

        return "</h{$level}>";
    }
}
