<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $q = $request->string('q')->toString();
        $groupSlug = $request->string('group')->toString();

        $groups = ProductGroup::orderBy('name')->get();

        $productsQuery = Product::query()
            ->with(['group', 'pricing'])
            ->where('is_active', true);

        if ($groupSlug) {
            $productsQuery->whereHas('group', fn ($qq) => $qq->where('slug', $groupSlug));
        }

        if ($q) {
            $productsQuery->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $products = $productsQuery
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'groups', 'q', 'groupSlug'));
    }


    public function show(Product $product): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $product->load(['group','pricing','options.values','pricingRules']);

        return view('products.show', compact('product'));
    }
}
