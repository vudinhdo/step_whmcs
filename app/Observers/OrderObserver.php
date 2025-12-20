<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Invoice;
use App\Mail\ServiceActivatedMail;
use App\Mail\InvoiceSampleMail;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function updated(Order $order): void
    {
        // chỉ chạy khi status đổi
        if (! $order->wasChanged('status')) return;

        // chỉ chạy khi active
        if ($order->status !== 'active') return;

        $order->loadMissing(['items', 'user']);

        // 1) Tạo services (mỗi item 1 service), tránh tạo trùng
        $createdServices = [];

        foreach ($order->items as $item) {
            $exists = Service::where('order_id', $order->id)
                ->where('order_item_id', $item->id)
                ->exists();

            if ($exists) continue;

            $service = Service::create([
                'user_id'       => $order->user_id,
                'order_id'      => $order->id,
                'order_item_id' => $item->id,
                'product_id'    => $item->product_id,
                'name'          => $item->description ?? 'Service',
                'billing_cycle' => $item->billing_cycle ?? 'monthly',
                'status'        => 'active',
                // nếu OrderItem của bạn dùng field config/config_json khác, chỉnh lại cho đúng:
                'config_json'   => is_array($item->config ?? null) ? json_encode($item->config) : ($item->config ?? null),
                'activated_at'  => now(),
            ]);

            $createdServices[] = $service;
        }

        // 2) Tạo invoice mẫu (nếu chưa có invoice cho order)
        // Bạn có thể dùng cột invoice_id trên orders, hoặc bảng invoices có order_id.
        // Mình dùng cách tìm theo order_id (phổ biến nhất).
        $invoice = Invoice::where('order_id', $order->id)->first();

        if (! $invoice) {
            $invoice = Invoice::create([
                'user_id'   => $order->user_id,
                'order_id'  => $order->id,
                'status'    => 'unpaid', // mẫu thì để unpaid
                'total'     => $order->total,
                'currency'  => $order->currency ?? setting('default_currency','VND'),
                'issue_date' => now()->toDateString(),
                'due_date'   => now()->addDays((int) setting('invoice_due_days', 7))->toDateString(),
                'notes'     => 'Invoice mẫu (tự tạo khi Activate).',
            ]);
        }

        // 3) Gửi mail (apply mail config từ DB)
        if ($order->user?->email) {
            applyMailSettings();

            // gửi service activated (gửi 1 mail / 1 service) hoặc gộp — hiện tại gửi từng cái cho dễ
            foreach ($createdServices as $service) {
                $service->loadMissing('user');
                Mail::to($order->user->email)->send(new ServiceActivatedMail($service));
            }

            // gửi invoice mẫu (1 mail)
            Mail::to($order->user->email)->send(new InvoiceSampleMail($invoice, $order));
        }
    }
}
