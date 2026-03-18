<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Link extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  string  $href  The URL the link points to. Required when as="a".
     * @param  string  $variant  Link style: default, ghost, subtle. Default: default.
     * @param  bool|null  $external  If true, open in new tab. If null, inferred from href (same-origin = internal).
     * @param  string  $as  HTML tag: a or button. Default: a.
     */
    public function __construct(
        public string $href = '#',
        public string $variant = 'default',
        public ?bool $external = null,
        public string $as = 'a',
    ) {
        if (! in_array($this->as, ['a', 'button'], true)) {
            $this->as = 'a';
        }
    }

    public function isExternal(): bool
    {
        if ($this->external !== null) {
            return $this->external;
        }

        return ! $this->isInternalUrl($this->href);
    }

    /**
     * Href to render. Internal links are normalized to relative paths so wire:navigate intercepts them.
     */
    public function renderedHref(): string
    {
        if (! $this->isExternal() && Str::startsWith($this->href, ['http://', 'https://'])) {
            $parsed = parse_url($this->href);
            $path = $parsed['path'] ?? '/';
            $query = isset($parsed['query']) ? '?'.$parsed['query'] : '';
            $fragment = isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';

            return $path.$query.$fragment;
        }

        return $this->href;
    }

    protected function isInternalUrl(string $href): bool
    {
        if ($href === '' || $href === '#') {
            return true;
        }

        if (! Str::startsWith($href, ['http://', 'https://'])) {
            return true;
        }

        $appUrl = rtrim(config('app.url'), '/');
        $parsed = parse_url($href);

        $schemePart = $parsed['scheme'] ?? '';
        $scheme = "{$schemePart}://";
        $host = $parsed['host'] ?? '';
        $port = isset($parsed['port']) ? ":{$parsed['port']}" : '';
        $origin = "{$scheme}{$host}{$port}";

        $appScheme = parse_url($appUrl, PHP_URL_SCHEME);
        $appHost = parse_url($appUrl, PHP_URL_HOST);
        $appOrigin = "{$appScheme}://{$appHost}";
        $appPort = parse_url($appUrl, PHP_URL_PORT);
        if ($appPort !== null) {
            $appOrigin .= ":{$appPort}";
        }

        return Str::lower($origin) === Str::lower($appOrigin);
    }

    public function isButton(): bool
    {
        return $this->as === 'button';
    }

    public function baseClasses(): string
    {
        return 'cursor-pointer font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-offset-1 rounded-sm';
    }

    public function variantClasses(): string
    {
        return match ($this->variant) {
            'ghost' => 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 hover:underline dark:hover:text-zinc-100',
            'subtle' => 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 hover:underline dark:hover:text-zinc-300',
            default => 'text-blue-600 underline hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.link');
    }
}
