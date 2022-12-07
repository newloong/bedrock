<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider as BaseSageServiceProvider;

class SageServiceProvider extends BaseSageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        add_filter('script_loader_tag', [$this, 'scriptLoaderTag'], 10, 2);
        add_filter('style_loader_tag', [$this, 'styleLoaderTag'], 999, 2);
    }

    /**
     * Load scripts asynchronously.
     */
    public function scriptLoaderTag(string $tag, string $handle): string
    {
        foreach (
            [
                'defer' => config('assets.deferred_scripts'),
                'async' => config('assets.async_scripts'),
            ] as $type => $scripts
        ) {
            if (in_array($handle, $scripts)) {
                $tag = str_replace(' src', " $type src", $tag);
            }
        }

        switch ($tag) {
            case 'sage/fontawesome.js':
                $tag = str_replace(' src', ' crossorigin="anonymous" src', $tag);
                break;
        }

        return $tag;
    }

    /**
     * Load styles asynchronously.
     */
    public function styleLoaderTag(string $html, string $handle): string
    {
        if (is_admin() || !in_array($handle, config('assets.async_styles'))) {
            return $html;
        }

        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        /** @var \DOMElement $tag */
        $tag = $dom->getElementsByTagName('link')->item(0);
        $tag->setAttribute('media', 'print');
        $tag->setAttribute('onload', "this.media='all'");
        $tag->removeAttribute('type');
        $tag->removeAttribute('id');
        $html = $dom->saveHTML($tag);

        return $html;
    }
}
