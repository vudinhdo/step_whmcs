<?php

namespace App\Console\Commands;

use App\Models\AutomationLog;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessBilling extends Command
{
    protected $signature = 'billing:process';
    protected $description = 'Tạo hoá đơn gia hạn & xử lý dịch vụ quá hạn';

    public function handle(): int
    {
        $this->info('Bắt đầu xử lý billing...');

        $today = Carbon::today();

        $this->generateRenewalInvoices($today);
        $this->suspendOverdueServices($today);

        $this->info('Hoàn thành billing.');

        return Command::SUCCESS;
    }

    protected function generateRenewalInvoices(Carbon $today): void
    {
        // sắp đến hạn trong 3 ngày
        $dueFrom = $today;
        $dueTo   = $today->copy()->addDays(3);

        $services = Service::with('user', 'product')
            ->where('status', 'active')
            ->whereBetween('next_due_date', [$dueFrom, $dueTo])
            ->get();

        $count = 0;

        foreach ($services as $service) {
            // kiểm tra đã có invoice chưa (unpaid) cho kỳ này
            $existing = Invoice::where('user_id', $service->user_id)
                ->where('status', 'unpaid')
                ->whereHas('items', function ($q) use ($service) {
                    $q->where('service_id', $service->id);
                })
                ->exists();

            if ($existing) {
                continue;
            }

            // lấy giá theo billing_cycle
            $pricing = $service->product
                ? $service->product->pricing()
                    ->where('billing_cycle', $service->billing_cycle)
                    ->first()
                : null;

            if (! $pricing) {
                continue;
            }

            $subtotal = $pricing->price; // gia hạn không tính setup

            $invoice = Invoice::create([
                'user_id'    => $service->user_id,
                'status'     => 'unpaid',
                'issue_date' => $today,
                'due_date'   => $service->next_due_date,
                'subtotal'   => $subtotal,
                'tax'        => 0,
                'total'      => $subtotal,
                'currency'   => $pricing->currency,
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $service->id,
                'description'=> 'Gia hạn ' . ($service->product->name ?? 'dịch vụ') .
                    ' - ' . ucfirst($service->billing_cycle),
                'quantity'   => 1,
                'unit_price' => $pricing->price,
                'line_total' => $subtotal,
            ]);

            $count++;
        }

        AutomationLog::create([
            'type'        => 'invoice_generation',
            'description' => "Đã tạo {$count} hoá đơn gia hạn.",
            'status'      => 'success',
            'run_at'      => Carbon::now(),
        ]);

        $this->info("Tạo {$count} hoá đơn gia hạn.");
    }

    protected function suspendOverdueServices(Carbon $today): void
    {
        // quá hạn > 7 ngày
        $overdueDate = $today->copy()->subDays(7);

        $services = Service::with('user')
            ->where('status', 'active')
            ->where('next_due_date', '<', $overdueDate)
            ->get();

        $count = 0;

        foreach ($services as $service) {
            // nếu vẫn còn invoice unpaid, thì suspend
            $hasUnpaidInvoice = Invoice::where('user_id', $service->user_id)
                ->where('status', 'unpaid')
                ->whereHas('items', function ($q) use ($service) {
                    $q->where('service_id', $service->id);
                })
                ->exists();

            if (! $hasUnpaidInvoice) {
                continue;
            }

            $service->update(['status' => 'suspended']);
            $count++;
        }

        AutomationLog::create([
            'type'        => 'service_suspension',
            'description' => "Đã suspend {$count} dịch vụ quá hạn thanh toán.",
            'status'      => 'success',
            'run_at'      => Carbon::now(),
        ]);

        $this->info("Suspend {$count} dịch vụ quá hạn.");
    }
}
