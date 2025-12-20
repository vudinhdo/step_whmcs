<x-public-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-semibold">Đã nhận đơn hàng</h1>
            <p class="text-sm text-gray-500 mt-1">Mã đơn hàng: #{{ $order->id }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <p class="text-sm text-gray-700">
                    Cảm ơn bạn! Chúng tôi đã nhận được yêu cầu. Vui lòng kiểm tra email để theo dõi thông tin.
                </p>

                <div class="mt-4 text-sm">
                    <div><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</div>
                    <div><strong>Tổng tạm tính:</strong> {{ number_format($order->total, 0, ',', '.') }} {{ $order->currency }}</div>
                </div>

                <div class="mt-4 border-t pt-4">
                    <strong class="text-sm">Chi tiết:</strong>
                    <ul class="list-disc ml-5 text-sm text-gray-700 mt-2">
                        @foreach($order->items as $it)
                            <li>{{ $it->description }} (x{{ $it->quantity }})</li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6 flex gap-2">
                    <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded-md text-sm">Tiếp tục mua</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 border rounded-md text-sm">Liên hệ</a>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
