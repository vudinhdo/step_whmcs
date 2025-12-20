<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, ProductGroup $group): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $page = category_page($group->slug);

        $q        = trim((string) $request->query('q', ''));
        $cycle    = $request->query('cycle'); // monthly/annually...
        $priceMin = $request->query('price_min');
        $priceMax = $request->query('price_max');
        $sort     = $request->query('sort', 'relevance'); // relevance|price_asc|price_desc|name_asc|name_desc|newest


        $priceMin = $priceMin !== null ? (int) preg_replace('/\D+/', '', $priceMin) : null;
        $priceMax = $priceMax !== null ? (int) preg_replace('/\D+/', '', $priceMax) : null;

        $productsQuery = Product::query()
            ->where('product_group_id', $group->id)
            ->where('is_active', true);

        // Search
        if ($q !== '') {
            $productsQuery->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter cycle (chỉ lấy product có pricing theo cycle)
        if (!empty($cycle)) {
            $productsQuery->whereHas('pricing', function ($p) use ($cycle) {
                $p->where('billing_cycle', $cycle);
            });
        }

        // Filter price range dựa theo pricing (theo cycle nếu có)
        if ($priceMin !== null || $priceMax !== null) {
            $productsQuery->whereHas('pricing', function ($p) use ($cycle, $priceMin, $priceMax) {
                if (!empty($cycle)) {
                    $p->where('billing_cycle', $cycle);
                }
                if ($priceMin !== null && $priceMin !== '') {
                    $p->where('price', '>=', (float) $priceMin);
                }
                if ($priceMax !== null && $priceMax !== '') {
                    $p->where('price', '<=', (float) $priceMax);
                }
            });
        }

        // Eager load pricing (lọc theo cycle nếu có)
        $productsQuery->with(['pricing' => function ($p) use ($cycle) {
            if (!empty($cycle)) {
                $p->where('billing_cycle', $cycle);
            }
            $p->orderBy('price', 'asc');
        }]);

        // Lấy min price để sort theo giá (Laravel 10 hỗ trợ withMin)
        $productsQuery->withMin(
            ['pricing as min_price' => function ($p) use ($cycle) {
                if (!empty($cycle)) {
                    $p->where('billing_cycle', $cycle);
                }
            }],
            'price'
        );

        // Sort
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderByRaw('min_price IS NULL'); // đẩy null xuống cuối
                $productsQuery->orderBy('min_price', 'asc');
                break;

            case 'price_desc':
                $productsQuery->orderByRaw('min_price IS NULL');
                $productsQuery->orderBy('min_price', 'desc');
                break;

            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;

            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;

            default:
                // relevance: nếu có search thì ưu tiên name match nhẹ, không search thì newest
                if ($q !== '') {
                    $productsQuery->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END", ["%{$q}%"]);
                    $productsQuery->orderBy('created_at', 'desc');
                } else {
                    $productsQuery->orderBy('created_at', 'desc');
                }
                break;
        }

        $products = $productsQuery
            ->paginate(12)
            ->withQueryString();

        // Lấy danh sách billing_cycle để render select (từ pricing của group)
        // Cách đơn giản: lấy từ $products + fallback lấy từ group (nếu muốn đầy đủ hơn sẽ query riêng)
        $cycleOptions = collect(['monthly', 'annually'])->unique()->values(); // fallback
        $seen = collect();
        foreach ($products as $p) {
            foreach ($p->pricing as $pr) $seen->push($pr->billing_cycle);
        }
        if ($seen->isNotEmpty()) {
            $cycleOptions = $seen->unique()->values();
        }

        return view('public.categories.template', [
            'group'        => $group,
            'page'         => $page,
            'products'     => $products,
            'cycleOptions' => $cycleOptions,
            'filters'      => compact('q', 'cycle', 'priceMin', 'priceMax', 'sort'),
        ]);
    }


}

