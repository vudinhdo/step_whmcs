<x-public-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Checkout</h1>
                <p class="text-sm text-gray-500 mt-1">Nhập thông tin để gửi yêu cầu đặt hàng.</p>
            </div>
            <a href="{{ route('cart.index') }}" class="text-sm text-indigo-600">← Quay lại giỏ hàng</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-5 gap-6">

            {{-- Form --}}
            <div class="md:col-span-3 bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('checkout.place') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ tên *</label>
                        <input name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                            <input name="phone" value="{{ old('phone') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Công ty</label>
                            <input name="company" value="{{ old('company') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                        <textarea name="notes" rows="4" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-5 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold">
                            Gửi yêu cầu đặt hàng
                        </button>
                    </div>
                </form>
            </div>

            {{-- Summary --}}
            <div class="md:col-span-2 bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Tóm tắt đơn</h3>

                <div class="space-y-3 text-sm">
                    @foreach($cart['items'] as $item)
                        <div class="border rounded-md p-3">
                            <div class="font-medium">{{ $item['name'] }}</div>
                            <div class="text-gray-500 capitalize">
                                {{ $item['billing_cycle'] }} · x{{ $item['qty'] }}
                            </div>
                            <div class="mt-2 font-semibold">
                                {{ number_format($item['line_total'] ?? 0, 0, ',', '.') }} {{ $cart['currency'] }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Tạm tính</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format($cart['subtotal'], 0, ',', '.') }} {{ $cart['currency'] }}
                        </span>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-3">
                    Đây là yêu cầu đặt hàng. Chúng tôi sẽ liên hệ để xác nhận và hướng dẫn bước tiếp theo.
                </p>
            </div>

        </div>
    </div>
</x-public-layout>
