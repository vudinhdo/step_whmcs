<x-client-layout>
    <x-slot name="title">Tổng quan</x-slot>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-white border rounded-2xl p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Xin chào, {{ auth()->user()->name }} 👋</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Đây là khu vực quản lý dịch vụ, hóa đơn và ticket hỗ trợ của bạn.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2 border rounded-xl text-sm font-semibold hover:border-gray-400">
                        Mua thêm dịch vụ
                    </a>
                    <a href="{{ route('client.tickets.create') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-500">
                        Tạo ticket
                    </a>
                </div>
            </div>
        </div>

        {{-- KPI Cards (tạm thời show số 0; lát mình nối query) --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border rounded-2xl p-5">
                <div class="text-xs text-gray-500">Dịch vụ đang hoạt động</div>
                <div class="text-2xl font-bold mt-2">{{ $stats['active_services'] ?? 0 }}</div>
            </div>
            <div class="bg-white border rounded-2xl p-5">
                <div class="text-xs text-gray-500">Hóa đơn chưa thanh toán</div>
                <div class="text-2xl font-bold mt-2">{{ $stats['unpaid_invoices'] ?? 0 }}</div>
            </div>
            <div class="bg-white border rounded-2xl p-5">
                <div class="text-xs text-gray-500">Đơn hàng chờ xử lý</div>
                <div class="text-2xl font-bold mt-2">{{ $stats['pending_orders'] ?? 0 }}</div>
            </div>
            <div class="bg-white border rounded-2xl p-5">
                <div class="text-xs text-gray-500">Ticket đang mở</div>
                <div class="text-2xl font-bold mt-2">{{ $stats['open_tickets'] ?? 0 }}</div>
            </div>
        </div>

        {{-- Recent tables --}}
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white border rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-lg">Hóa đơn gần đây</h2>
                    <a class="text-sm text-indigo-600 font-semibold" href="{{ route('client.invoices.index') }}">Xem tất cả</a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse(($recent['invoices'] ?? []) as $inv)
                        <a href="{{ route('client.invoices.show', $inv) }}"
                           class="block border rounded-xl p-4 hover:bg-gray-50">
                            <div class="flex justify-between">
                                <div class="font-semibold">#{{ $inv->id }}</div>
                                <div class="text-sm">{{ strtoupper($inv->status) }}</div>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ number_format($inv->total,0,',','.') }} {{ $inv->currency ?? 'VND' }}
                            </div>
                        </a>
                    @empty
                        <div class="text-sm text-gray-500">Chưa có hóa đơn.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-lg">Ticket gần đây</h2>
                    <a class="text-sm text-indigo-600 font-semibold" href="{{ route('client.tickets.index') }}">Xem tất cả</a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse(($recent['tickets'] ?? []) as $t)
                        <a href="{{ route('client.tickets.show', $t) }}"
                           class="block border rounded-xl p-4 hover:bg-gray-50">
                            <div class="flex justify-between">
                                <div class="font-semibold">#{{ $t->id }} — {{ \Illuminate\Support\Str::limit($t->subject, 40) }}</div>
                                <div class="text-sm">{{ strtoupper($t->status) }}</div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $t->updated_at?->diffForHumans() }}
                            </div>
                        </a>
                    @empty
                        <div class="text-sm text-gray-500">Chưa có ticket.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
