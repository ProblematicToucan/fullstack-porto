<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'about';

    protected $fillable = [
        'heading',
        'body',
        'avatar',
        'links',
    ];

    protected $casts = [
        'links' => 'array',
    ];

    /**
     * Text block for AI agent system instructions: heading, bio, links, and avatar from the About page.
     */
    public static function agentInstructionsContext(): string
    {
        $about = static::query()->first(['heading', 'body', 'avatar', 'links']);

        if ($about === null) {
            return '';
        }

        $sections = [];

        if (filled($about->heading)) {
            $sections[] = "About section heading: {$about->heading}";
        }

        if (filled($about->body)) {
            $sections[] = "Bio:\n{$about->body}";
        }

        $links = $about->links;

        if (\is_array($links) && $links !== []) {
            $lines = [];

            foreach ($links as $label => $url) {
                if ($url === null || $url === '') {
                    continue;
                }

                $lines[] = \is_string($label) ? "{$label}: {$url}" : (string) $url;
            }

            if ($lines !== []) {
                $linesText = implode("\n", $lines);
                $sections[] = "Social / profile links:\n{$linesText}";
            }
        }

        if (filled($about->avatar)) {
            $sections[] = "Avatar image (public path on this site): {$about->avatar}";
        }

        if ($sections === []) {
            return '';
        }

        $sectionsText = implode("\n\n", $sections);

        return "=== Site owner (About page)\n{$sectionsText}\n===\n";
    }
}
