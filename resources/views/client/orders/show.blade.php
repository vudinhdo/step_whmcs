<x-client-layout title="Chi tiết đơn hàng">
    <div class="space-y-6">

        <div class="bg-white border rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Đơn hàng #{{ $order->id }}</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Tạo lúc: {{ $order->created_at?->format('d/m/Y H:i') }}
                    </p>
                </div>

                @php
                    $status = strtoupper($order->status);
                    $badgeClass = match($order->status) {
                        'active' => 'bg-green-50 text-green-700 border-green-200',
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                    };
                @endphp

                <div class="inline-flex items-center px-3 py-2 rounded-xl border text-sm font-semibold {{ $badgeClass }}">
                    {{ $status }}
                </div>
            </div>

            <div class="mt-6 grid sm:grid-cols-3 gap-4">
                <div class="border rounded-xl p-4">
                    <div class="text-xs text-gray-500">Tổng tiền</div>
                    <div class="text-lg font-bold mt-1">
                        {{ number_format($order->total,0,',','.') }} {{ $order->currency ?? 'VND' }}
                    </div>
                </div>

                <div class="border rounded-xl p-4">
                    <div class="text-xs text-gray-500">Số hạng mục</div>
                    <div class="text-lg font-bold mt-1">
                        {{ $order->items?->count() ?? 0 }}
                    </div>
                </div>

                <div class="border rounded-xl p-4">
                    <div class="text-xs text-gray-500">Hóa đơn</div>
                    <div class="text-lg font-bold mt-1">
                        @if($order->invoice ?? null)
                            <a class="text-indigo-600 hover:underline"
                               href="{{ route('client.invoices.show', $order->invoice) }}">
                                #{{ $order->invoice->id }}
                            </a>
                        @else
                            <span class="text-gray-500">—</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Hạng mục trong đơn</h2>
                <a href="{{ route('client.orders.index') }}" class="text-sm text-indigo-600 font-semibold">
                    ← Quay lại danh sách
                </a>
            </div>

            <div class="mt-4 space-y-4">
                @forelse($order->items as $item)
                    @php
                        $config = $item->config_json ? json_decode($item->config_json, true) : [];
                    @endphp

                    <div class="border rounded-xl p-5">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $item->name }}</div>
                                <div class="text-sm text-gray-500 mt-1">
                                    Chu kỳ: {{ ucfirst($item->billing_cycle ?? '-') }}
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-sm text-gray-500">Giá</div>
                                <div class="text-lg font-bold">
                                    {{ number_format($item->price ?? 0,0,',','.') }} {{ $order->currency ?? 'VND' }}
                                </div>
                            </div>
                        </div>

                        @if(!empty($config))
                            <div class="mt-4 bg-gray-50 border rounded-xl p-4">
                                <div class="text-sm font-semibold text-gray-800 mb-2">Cấu hình</div>
                                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 text-sm text-gray-700">
                                    @foreach($config as $k => $v)
                                        <div class="flex items-center justify-between gap-3 border rounded-lg bg-white px-3 py-2">
                                            <span class="text-gray-500">{{ strtoupper($k) }}</span>
                                            <span class="font-semibold">{{ is_array($v) ? json_encode($v) : $v }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">Đơn hàng chưa có hạng mục.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-client-layout>
