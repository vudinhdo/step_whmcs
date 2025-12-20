<?php

use App\Support\ArrayDeepMerge;

if (!function_exists('category_page')) {
    function category_page(string $slug): array
    {
        $pages = config('category_pages', []);
        $default = $pages['default'] ?? [];
        $override = $pages[$slug] ?? [];

        return ArrayDeepMerge::merge($default, $override);
    }
}
