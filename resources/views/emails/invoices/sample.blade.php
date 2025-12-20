@component('mail::message')
    # Hóa đơn mẫu (tham khảo) 🧾

    Xin chào {{ $order->user->name ?? 'Quý khách' }},

    Đây là hóa đơn mẫu cho đơn hàng **#{{ $order->id }}**.

    @component('mail::panel')
        **Invoice #{{ $invoice->id }}**
        **Tổng tiền:** {{ number_format($invoice->total,0,',','.') }} {{ $invoice->currency ?? 'VND' }}
        **Trạng thái:** {{ strtoupper($invoice->status) }}
    @endcomponent

    @component('mail::button', ['url' => route('client.invoices.show', $invoice)])
        Xem hóa đơn trong Portal
    @endcomponent

    Cảm ơn bạn,
    {{ config('app.name') }}
@endcomponent
