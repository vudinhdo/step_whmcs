<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceivedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', ['items' => [], 'subtotal' => 0, 'currency' => setting('default_currency', 'VND')]);

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('status', 'Giỏ hàng đang trống.');
        }

        return view('public.checkout.show', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session('cart', ['items' => [], 'subtotal' => 0, 'currency' => setting('default_currency', 'VND')]);

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('status', 'Giỏ hàng đang trống.');
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'notes'   => ['nullable', 'string', 'max:2000'],
        ]);

        // =========================================================
        // 1) XÁC ĐỊNH USER (login thì dùng auth, guest thì tự tạo/gán)
        // =========================================================
        $createdNewUser = false;
        $defaultPassword = null;

        if (auth()->check()) {
            $user = auth()->user();
        } else {
            $email = strtolower(trim($data['email']));

            $user = User::where('email', $email)->first();

            if (! $user) {
                $createdNewUser = true;
                $defaultPassword = '123@123a';

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $email,
                    'password' => Hash::make($defaultPassword),
                    'must_change_password' => true, // BẮT ĐỔI MẬT KHẨU LẦN ĐẦU
                ]);
            }
        }

        // =========================================================
        // 2) TẠO ORDER (luôn có user_id)
        // =========================================================
        $token = (string) Str::uuid();

        $order = Order::create([
            'user_id'         => $user?->id, // luôn có (trừ trường hợp lỗi tạo user)
            'is_guest'        => auth()->check() ? false : true,
            'guest_name'      => auth()->check() ? null : $data['name'],
            'guest_email'     => auth()->check() ? null : $data['email'],
            'guest_phone'     => auth()->check() ? null : ($data['phone'] ?? null),
            'guest_company'   => auth()->check() ? null : ($data['company'] ?? null),
            'public_token'    => $token,

            'status'          => 'pending',
            'total'           => (float) ($cart['subtotal'] ?? 0),
            'currency'        => $cart['currency'] ?? setting('default_currency', 'VND'),
            'payment_gateway' => null,
            'notes'           => $data['notes'] ?? null,
        ]);

        // =========================================================
        // 3) TẠO ORDER ITEMS
        // =========================================================
        foreach ($cart['items'] as $item) {
            $unitPrice   = (float) ($item['base_price'] ?? 0);
            $setupFee    = (float) ($item['setup_fee'] ?? 0);
            $config      = $item['config'] ?? [];
            $configPrice = (float) ($item['config_price'] ?? 0);

            $quantity     = (int) ($item['qty'] ?? 1);
            $lineSubtotal = ($unitPrice + $setupFee + $configPrice) * $quantity;

            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item['product_id'],
                'description'   => ($item['name'] ?? 'Sản phẩm') . ' - ' . ucfirst($item['billing_cycle']),
                'quantity'      => $quantity,
                'billing_cycle' => $item['billing_cycle'],
                'config'        => empty($config) ? null : $config,
                'unit_price'    => $unitPrice,
                'setup_fee'     => $setupFee,
                'config_price'  => $configPrice,
                'subtotal'      => $lineSubtotal,
            ]);
        }

        // =========================================================
        // 4) GỬI MAIL (dùng cấu hình DB)
        // =========================================================
        applyMailSettings();

        $orderFresh = $order->fresh('items');

        // gửi email cho khách: nếu tạo user mới -> gửi kèm mật khẩu mặc định
        Mail::to($data['email'])->send(
            new OrderReceivedMail(
                order: $orderFresh,
                isForSupport: false,
                createdNewUser: $createdNewUser,
                defaultPassword: $defaultPassword
            )
        );

        // (tuỳ chọn) gửi cho support
        if (setting('support_email')) {
            Mail::to(setting('support_email'))->send(
                new OrderReceivedMail(
                    order: $orderFresh,
                    isForSupport: true
                )
            );
        }

        // clear cart
        session()->forget('cart');

        return redirect()->route('checkout.thankyou', ['token' => $order->public_token]);
    }
    public function thankYou(string $token): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $order = Order::with('items.product')
            ->where('public_token', $token)
            ->firstOrFail();

        return view('public.checkout.thankyou', compact('order'));
    }
}
