<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class LandingController extends Controller
{
    public function home(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $groups = ProductGroup::query()
            ->with(['products' => function ($q) {
                $q->where('is_active', true)
                    ->with('pricing')        // cần relation pricing trong Product
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('landing.home', compact('groups'));
    }
}
