<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceCreatedMail;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'billing_cycle' => ['required', 'string'],
            'notes'         => ['nullable', 'string'],
        ]);

        // Tìm pricing theo chu kỳ
        $pricing = $product->pricing()
            ->where('billing_cycle', $data['billing_cycle'])
            ->first();

        if (! $pricing) {
            return back()->withErrors([
                'billing_cycle' => 'Chu kỳ thanh toán không hợp lệ.',
            ]);
        }

        $subtotal = $pricing->price + $pricing->setup_fee;

        // 1. Tạo Order
        $order = Order::create([
            'user_id'         => $user->id,
            'status'          => 'pending',
            'total'           => $subtotal,
            'currency'        => $pricing->currency,
            'payment_gateway' => null,
            'notes'           => $data['notes'] ?? null,
        ]);

        // 2. Tạo OrderItem
        $description = $product->name . ' - ' . ucfirst($pricing->billing_cycle);

        $orderItem = OrderItem::create([
            'order_id'      => $order->id,
            'product_id'    => $product->id,
            'description'   => $description,
            'quantity'      => 1,
            'billing_cycle' => $pricing->billing_cycle,
            'unit_price'    => $pricing->price,
            'setup_fee'     => $pricing->setup_fee,
            'subtotal'      => $subtotal,
        ]);

        // 3. Tạo Service (pending, CHƯA set start_date/next_due_date – chờ thanh toán)
        $service = Service::create([
            'user_id'       => $user->id,
            'product_id'    => $product->id,
            'order_id'      => $order->id,
            'status'        => 'pending',
            'billing_cycle' => $pricing->billing_cycle,
            'start_date'    => null,
            'next_due_date' => null,
            'custom_fields' => null,
            'notes'         => $data['notes'] ?? null,
        ]);

        // 4. Tạo Invoice + InvoiceItem (unpaid)
        $issueDate = Carbon::now();
        $dueDate   = Carbon::now()->addDays(7); // hạn thanh toán 7 ngày

        $invoice = Invoice::create([
            'user_id'         => $user->id,
            'status'          => 'unpaid',
            'issue_date'      => $issueDate,
            'due_date'        => $dueDate,
            'subtotal'        => $subtotal,
            'tax'             => 0,
            'total'           => $subtotal,
            'currency'        => $pricing->currency,
            'payment_gateway' => null,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'service_id' => $service->id,
            'description'=> $description,
            'quantity'   => 1,
            'unit_price' => $pricing->price + $pricing->setup_fee,
            'line_total' => $subtotal,
        ]);
        Mail::to($user->email)->send(new InvoiceCreatedMail($invoice));

        return redirect()
            ->route('client.invoices.show', $invoice)
            ->with('status', 'Đã tạo đơn hàng & hoá đơn. Vui lòng thanh toán để kích hoạt dịch vụ.');
    }

    // Nếu sau này muốn dùng lại:
    protected function calculateNextDueDate(Carbon $from, string $cycle): Carbon
    {
        return match ($cycle) {
            'monthly'      => $from->copy()->addMonth(),
            'quarterly'    => $from->copy()->addMonths(3),
            'semiannually' => $from->copy()->addMonths(6),
            'annually'     => $from->copy()->addYear(),
            default        => $from->copy()->addMonth(),
        };
    }
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items','invoice');

        return view('client.orders.show', compact('order'));
    }

}
