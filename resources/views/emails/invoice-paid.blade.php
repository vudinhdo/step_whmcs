<x-mail::layout subject="Thanh toán thành công">
    <h2>Thanh toán thành công!</h2>

    <p>Bạn đã thanh toán thành công hóa đơn #{{ $invoice->id }}.</p>

    <p><strong>Số tiền:</strong> {{ number_format($invoice->total, 0, ',', '.') }} {{ $invoice->currency }}</p>

    @if($invoice->items->first()?->service)
        <p>Dịch vụ <strong>{{ $invoice->items->first()->service->product->name }}</strong> đã được kích hoạt.</p>
    @endif

    <p>Cảm ơn bạn đã sử dụng dịch vụ của {{ setting('company_name') }}!</p>
</x-mail::layout>
