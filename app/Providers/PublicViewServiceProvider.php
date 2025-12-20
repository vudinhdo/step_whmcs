<?php

namespace App\Providers;

use App\Models\ProductGroup;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class PublicViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('public.*', function ($view) {
            $publicGroups = ProductGroup::orderBy('name')->get(['id', 'name', 'slug']);
            $view->with('publicGroups', $publicGroups);
        });
    }
}
