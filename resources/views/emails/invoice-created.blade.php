<x-mail::layout subject="Hóa đơn mới #{{ $invoice->id }}">
    <h2>Xin chào {{ $invoice->user->name }},</h2>

    <p>Bạn có một hóa đơn mới cần thanh toán.</p>

    <p><strong>Mã hóa đơn:</strong> #{{ $invoice->id }}</p>
    <p><strong>Số tiền:</strong> {{ number_format($invoice->total, 0, ',', '.') }} {{ $invoice->currency }}</p>
    <p><strong>Hạn thanh toán:</strong> {{ $invoice->due_date->format('d/m/Y') }}</p>

    <p>
        Bạn có thể xem và thanh toán tại đây:<br>
        <a href="{{ route('client.invoices.show', $invoice) }}">
            Xem hóa đơn
        </a>
    </p>

    <p>Cảm ơn bạn đã sử dụng dịch vụ!</p>
</x-mail::layout>
