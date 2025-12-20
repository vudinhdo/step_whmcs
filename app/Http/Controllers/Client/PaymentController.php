<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\InvoicePaidMail;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ServiceActivatedMail;

class PaymentController extends Controller
{
    public function payTest(Invoice $invoice): RedirectResponse
    {
        // chỉ cho chủ của invoice thanh toán
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        if ($invoice->status === 'paid') {
            return back()->with('status', 'Hoá đơn này đã được thanh toán trước đó.');
        }

        // tạo transaction giả lập
        Transaction::create([
            'invoice_id' => $invoice->id,
            'amount' => $invoice->total,
            'currency' => $invoice->currency,
            'payment_gateway' => 'test',
            'transaction_id' => 'TEST-' . uniqid(),
            'status' => 'success',
            'paid_at' => Carbon::now(),
            'raw_response' => null,
        ]);

        // cập nhật invoice
        $invoice->update([
            'status' => 'paid',
            'payment_gateway' => 'test',
        ]);

        // kích hoạt các service liên quan
        foreach ($invoice->items as $item) {
            $service = $item->service;
            if (!$service) {
                continue;
            }

            $wasActive = $service->status === 'active'; // trạng thái cũ

            // Lấy pricing & tính ngày... (giống trước)
            $product = $service->product;
            $cycle = $service->billing_cycle;

            $pricing = $product?->pricing()
                ->where('billing_cycle', $cycle)
                ->first();

            if (!$pricing) {
                // Không tìm thấy pricing, chỉ kích hoạt đơn giản
                $service->update(['status' => 'active']);
            } else {
                if (is_null($service->start_date)) {
                    // Hóa đơn đầu tiên
                    $start = now();
                    $next = $this->calculateNextDueDate($start, $cycle);

                    $service->update([
                        'status' => 'active',
                        'start_date' => $start,
                        'next_due_date' => $next,
                    ]);
                } else {
                    // Gia hạn
                    $from = $service->next_due_date ?: now();
                    $next = $this->calculateNextDueDate($from, $cycle);

                    $service->update([
                        'status' => 'active',
                        'next_due_date' => $next,
                    ]);
                }
            }

            // Sau khi update, nếu trước đó chưa active -> giờ active -> gửi mail
            $service->refresh(); // lấy lại dữ liệu mới
            if (!$wasActive && $service->status === 'active') {
                Mail::to($service->user->email)
                    ->send(new ServiceActivatedMail($service));
            }
        }

        $startDate = Carbon::now();
        $nextDueDate = $this->calculateNextDueDate($startDate, $pricing->billing_cycle);
        // (tuỳ chọn) set đơn hàng là active nếu tất cả dịch vụ active
        $orderIds = $invoice->items
            ->filter(fn($i) => $i->service && $i->service->order_id)
            ->pluck('service.order_id')
            ->unique()
            ->toArray();

        if (!empty($orderIds)) {
            Order::whereIn('id', $orderIds)->update(['status' => 'active']);
        }
        Mail::to($invoice->user->email)->send(new InvoicePaidMail($invoice));

        return back()->with('status', 'Thanh toán test thành công. Dịch vụ đã được kích hoạt.');
    }

    protected function calculateNextDueDate(Carbon $from, string $cycle): Carbon
    {

        return match ($cycle) {
            'monthly' => $from->copy()->addMonth(),
            'quarterly' => $from->copy()->addMonths(3),
            'semiannually' => $from->copy()->addMonths(6),
            'annually' => $from->copy()->addYear(),
            default => $from->copy()->addMonth(),
        };
    }
}
