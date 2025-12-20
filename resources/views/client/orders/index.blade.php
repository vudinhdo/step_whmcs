<x-client-layout title="Đơn hàng">
    <div class="bg-white border rounded-2xl p-6">
        <h1 class="text-xl font-bold mb-4">Đơn hàng của tôi</h1>

        @forelse($orders as $order)
            <a href="{{ route('client.orders.show', $order) }}"
               class="block border rounded-xl p-4 mb-3 hover:bg-gray-50">
                <div class="flex justify-between">
                    <div>#{{ $order->id }}</div>
                    <div class="text-sm">{{ strtoupper($order->status) }}</div>
                </div>
                <div class="text-sm text-gray-500 mt-1">
                    {{ number_format($order->total,0,',','.') }} VND
                </div>
            </a>
        @empty
            <p class="text-gray-500">Chưa có đơn hàng.</p>
        @endforelse

        <div class="mt-4">{{ $orders->links() }}</div>
    </div>
</x-client-layout>
