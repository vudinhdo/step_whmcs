<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dịch vụ #{{ $service->id }} - {{ $service->product->name ?? 'Dịch vụ' }}
            </h2>
            <a href="{{ route('client.services.index') }}" class="text-sm text-indigo-600">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Thông tin cơ bản --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Thông tin dịch vụ</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Sản phẩm</dt>
                        <dd class="font-medium">
                            {{ $service->product->name ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">Trạng thái</dt>
                        <dd class="font-medium capitalize">
                            {{ $service->status }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">Chu kỳ thanh toán</dt>
                        <dd class="font-medium">
                            {{ $service->billing_cycle }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">Ngày bắt đầu</dt>
                        <dd class="font-medium">
                            {{ optional($service->start_date)->format('d/m/Y') ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">Ngày đến hạn</dt>
                        <dd class="font-medium">
                            {{ optional($service->next_due_date)->format('d/m/Y') ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">Đơn hàng gốc</dt>
                        <dd class="font-medium">
                            @if ($service->order)
                                #{{ $service->order->id }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($service->notes)
                    <div class="mt-4">
                        <dt class="text-gray-500 text-sm mb-1">Ghi chú</dt>
                        <dd class="text-sm whitespace-pre-line">
                            {{ $service->notes }}
                        </dd>
                    </div>
                @endif
            </div>

            {{-- Custom fields (domain, IP, username, ...) --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Thông tin kỹ thuật (Custom fields)</h3>

                @php $fields = $service->custom_fields ?? []; @endphp

                @if (empty($fields))
                    <p class="text-sm text-gray-500">Chưa có thông tin kỹ thuật.</p>
                @else
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        @foreach ($fields as $key => $value)
                            <div>
                                <dt class="text-gray-500">{{ $key }}</dt>
                                <dd class="font-medium">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>

            {{-- Liên quan đến hoá đơn --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Hoá đơn liên quan</h3>

                @php
                    $invoiceItems = $service->invoiceItems;
                @endphp

                @if ($invoiceItems->isEmpty())
                    <p class="text-sm text-gray-500">Chưa có hoá đơn nào cho dịch vụ này.</p>
                @else
                    <table class="min-w-full text-sm text-left">
                        <thead>
                        <tr>
                            <th class="px-3 py-2">ID hoá đơn</th>
                            <th class="px-3 py-2">Mô tả</th>
                            <th class="px-3 py-2">Thành tiền</th>
                            <th class="px-3 py-2">Trạng thái</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($invoiceItems as $item)
                            @php $invoice = $item->invoice; @endphp
                            @if ($invoice)
                                <tr class="border-t">
                                    <td class="px-3 py-2">#{{ $invoice->id }}</td>
                                    <td class="px-3 py-2">{{ $item->description }}</td>
                                    <td class="px-3 py-2">
                                        {{ number_format($item->line_total, 0, ',', '.') }} {{ $invoice->currency }}
                                    </td>
                                    <td class="px-3 py-2 capitalize">{{ $invoice->status }}</td>
                                    <td class="px-3 py-2 text-right">
                                        <a href="{{ route('client.invoices.show', $invoice) }}"
                                           class="text-indigo-600 text-sm">
                                            Xem hoá đơn →
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Gợi ý tạo ticket liên quan --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-2">Cần hỗ trợ?</h3>
                <p class="text-sm text-gray-600 mb-3">
                    Nếu bạn gặp vấn đề với dịch vụ này, hãy tạo ticket hỗ trợ và ghi rõ ID dịch vụ #{{ $service->id }}.
                </p>
                <a href="{{ route('client.tickets.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500">
                    Tạo ticket hỗ trợ
                </a>
            </div>
        </div>
    </div>
</x-client-layout>
