<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hoá đơn #{{ $invoice->id }}
            </h2>
            <a href="{{ route('client.invoices.index') }}" class="text-sm text-indigo-600">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm rounded-lg p-6">
                <p><strong>Trạng thái:</strong> <span class="capitalize">{{ $invoice->status }}</span></p>
                <p><strong>Ngày xuất:</strong> {{ $invoice->issue_date?->format('d/m/Y') }}</p>
                <p><strong>Đến hạn:</strong> {{ $invoice->due_date?->format('d/m/Y') }}</p>
                <p><strong>Tổng:</strong>
                    {{ number_format($invoice->total, 0, ',', '.') }} {{ $invoice->currency }}
                </p>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Chi tiết</h3>
                <table class="min-w-full text-sm text-left">
                    <thead>
                    <tr>
                        <th class="px-3 py-2">Mô tả</th>
                        <th class="px-3 py-2">Số lượng</th>
                        <th class="px-3 py-2">Đơn giá</th>
                        <th class="px-3 py-2">Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($invoice->items as $item)
                        <tr class="border-t">
                            <td class="px-3 py-2">
                                {{ $item->description }}
                                @if ($item->service && $item->service->product)
                                    <div class="text-xs text-gray-500">
                                        Dịch vụ: {{ $item->service->product->name }} (ID dịch vụ #{{ $item->service->id }})
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-2">{{ $item->quantity }}</td>
                            <td class="px-3 py-2">
                                {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2">
                                {{ number_format($item->line_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right">
                    <p>Subtotal: {{ number_format($invoice->subtotal, 0, ',', '.') }}</p>
                    <p>Thuế: {{ number_format($invoice->tax, 0, ',', '.') }}</p>
                    <p class="font-semibold">
                        Tổng: {{ number_format($invoice->total, 0, ',', '.') }} {{ $invoice->currency }}
                    </p>
                </div>
            </div>
            @if ($invoice->status !== 'paid')
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold mb-3">Thanh toán</h3>

                    <p class="text-sm text-gray-600 mb-3">
                        Hiện tại đang dùng chế độ thanh toán thử nghiệm. Bấm nút dưới đây để đánh dấu hoá đơn đã thanh toán
                        và kích hoạt dịch vụ.
                    </p>

                    <form method="POST" action="{{ route('client.invoices.pay.test', $invoice) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-500">
                            Thanh toán test (đánh dấu PAID)
                        </button>
                    </form>
                </div>
            @endif

            {{-- Chỗ này sau sẽ thêm nút Thanh toán (VNPay/MoMo/Chuyển khoản) --}}
        </div>
    </div>
</x-client-layout>
