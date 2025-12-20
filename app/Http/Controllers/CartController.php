<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $cart = session('cart', [
            'items' => [],
            'subtotal' => 0,
            'currency' => setting('default_currency', 'VND'),
        ]);

        return view('public.cart.index', compact('cart'));
    }

    public function add(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'billing_cycle' => ['required', 'string'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
            'config_json' => ['nullable', 'string'],
            'config_price' => ['nullable', 'numeric', 'min:0'],

        ]);

        $qty = $data['qty'] ?? 1;

        $product = Product::with(['pricing', 'group'])
            ->where('is_active', true)
            ->findOrFail($data['product_id']);

        $pricing = $product->pricing
            ->firstWhere('billing_cycle', $data['billing_cycle']);

        if (!$pricing) {
            return back()->withErrors(['billing_cycle' => 'Chu kỳ thanh toán không hợp lệ.']);
        }
        $config = [];
        if (!empty($data['config_json'])) {
            $decoded = json_decode($data['config_json'], true);
            if (is_array($decoded)) $config = $decoded;
        }

        $configPrice = (float)($data['config_price'] ?? 0);

        // Cart item (dự phòng cho configurator sau này)
        $item = [
            'key' => $this->makeCartKey($product->id, $data['billing_cycle'], $config),
            'product_id' => $product->id,
            'name' => $product->name,
            'group' => $product->group?->name,
            'slug' => $product->slug,
            'billing_cycle' => $data['billing_cycle'],
            'qty' => $qty,
            'base_price' => (float)$pricing->price,
            'setup_fee' => (float)($pricing->setup_fee ?? 0),
            'currency' => $pricing->currency ?? setting('default_currency', 'VND'),
            'config' => $config,
            'config_price' => $configPrice,
        ];

        $cart = session('cart', ['items' => [], 'subtotal' => 0, 'currency' => $item['currency']]);

        // Nếu item trùng key -> cộng qty
        $found = false;
        foreach ($cart['items'] as &$existing) {
            if ($existing['key'] === $item['key']) {
                $existing['qty'] += $item['qty'];
                $found = true;
                break;
            }
        }
        unset($existing);

        if (!$found) {
            $cart['items'][] = $item;
        }

        $cart = $this->recalc($cart);

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('status', 'Đã thêm vào giỏ hàng.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', ['items' => [], 'subtotal' => 0, 'currency' => setting('default_currency', 'VND')]);

        foreach ($cart['items'] as &$item) {
            if ($item['key'] === $data['key']) {
                $item['qty'] = $data['qty'];
                break;
            }
        }
        unset($item);

        $cart = $this->recalc($cart);
        session(['cart' => $cart]);

        return back()->with('status', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
        ]);

        $cart = session('cart', ['items' => [], 'subtotal' => 0, 'currency' => setting('default_currency', 'VND')]);

        $cart['items'] = array_values(array_filter($cart['items'], fn($i) => $i['key'] !== $data['key']));

        $cart = $this->recalc($cart);
        session(['cart' => $cart]);

        return back()->with('status', 'Đã xoá sản phẩm khỏi giỏ.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('status', 'Đã xoá toàn bộ giỏ hàng.');
    }

    protected function recalc(array $cart): array
    {
        $subtotal = 0;
        $currency = $cart['currency'] ?? setting('default_currency', 'VND');

        foreach ($cart['items'] as &$item) {
            $currency = $item['currency'] ?? $currency;

            $unit = ($item['base_price'] ?? 0) + ($item['setup_fee'] ?? 0) + ($item['config_price'] ?? 0);
            $item['unit_total'] = $unit;
            $item['line_total'] = $unit * ($item['qty'] ?? 1);

            $subtotal += $item['line_total'];
        }
        unset($item);

        $cart['subtotal'] = $subtotal;
        $cart['currency'] = $currency;

        return $cart;
    }

    protected function makeCartKey(int $productId, string $cycle, array $config): string
    {
        // Key ổn định để sau này config khác nhau sẽ tạo item khác nhau
        return sha1($productId . '|' . $cycle . '|' . json_encode($config));
    }
}
