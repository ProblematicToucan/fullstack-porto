<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class PostContentSanitizer
{
    private static ?HtmlSanitizer $sanitizer = null;

    /**
     * Return safe HTML for display from post content (TipTap JSON array or HTML string).
     */
    public static function sanitize(array|string|null $content): string
    {
        if ($content === null || $content === '') {
            return '';
        }

        $html = \is_array($content)
            ? self::tiptapJsonToHtml($content)
            : (string) $content;

        return self::sanitizer()->sanitize($html);
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
     */
    private static function tiptapJsonToHtml(array $node): string
    {
        $type = $node['type'] ?? '';
        $content = $node['content'] ?? [];
        $text = $node['text'] ?? null;

        if ($text !== null) {
            $html = e($text);
            $marks = $node['marks'] ?? [];
            foreach ($marks as $mark) {
                $markType = $mark['type'] ?? '';
                $html = match ($markType) {
                    'bold' => "<strong>{$html}</strong>",
                    'italic' => "<em>{$html}</em>",
                    'link' => '<a href="' . \e($mark['attrs']['href'] ?? '#') . '">' . $html . '</a>',
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

    private static function headingOpenTag(array $node): string
    {
        $level = (int) ($node['attrs']['level'] ?? 1);
        $level = max(1, min(6, $level));

        return "<h{$level}>";
    }

    private static function headingCloseTag(array $node): string
    {
        $level = (int) ($node['attrs']['level'] ?? 1);
        $level = max(1, min(6, $level));

        return "</h{$level}>";
    }
}
