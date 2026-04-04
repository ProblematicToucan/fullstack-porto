<?php

namespace App\Ai\Knowledge;

use App\Models\Post;
use App\Models\Project;

final class PortfolioContentExtractor
{
    /**
     * Plain text for indexing: Filament Builder blocks; image blocks are omitted.
     *
     * @param  array<int, array<string, mixed>>|string|null  $blocksOrString
     */
    public static function plainTextFromBuilderContent(array|string|null $blocksOrString): string
    {
        if ($blocksOrString === null || $blocksOrString === '') {
            return '';
        }

        if (is_string($blocksOrString)) {
            return trim($blocksOrString);
        }

        $parts = [];

        foreach ($blocksOrString as $block) {
            if (! is_array($block)) {
                continue;
            }

            $type = $block['type'] ?? null;
            $data = is_array($block['data'] ?? null) ? $block['data'] : [];

            if ($type === 'image') {
                continue;
            }

            if ($type === 'paragraph') {
                $html = (string) ($data['text'] ?? '');
                $plain = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                if ($plain !== '') {
                    $parts[] = $plain;
                }
            } elseif ($type === 'code') {
                $code = trim((string) ($data['text'] ?? ''));
                if ($code !== '') {
                    $parts[] = $code;
                }
            }
        }

        return trim(implode("\n\n", $parts));
    }

    public static function canonicalTextForPost(Post $post): string
    {
        $body = self::plainTextFromBuilderContent($post->content);

        return trim(implode("\n", array_filter([
            'Title: '.(string) $post->title,
            'Slug: '.(string) $post->slug,
            '',
            $body,
        ])));
    }

    public static function canonicalTextForProject(Project $project): string
    {
        $body = self::plainTextFromBuilderContent($project->description);
        $lines = [
            'Title: '.(string) $project->title,
            'Slug: '.(string) $project->slug,
        ];

        if (filled($project->project_url)) {
            $lines[] = 'Project URL: '.(string) $project->project_url;
        }

        if (filled($project->repo_url)) {
            $lines[] = 'Repository URL: '.(string) $project->repo_url;
        }

        $lines[] = '';
        $lines[] = $body;

        return trim(implode("\n", $lines));
    }
}
