<x-public-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">Giỏ hàng</h1>
                <p class="text-sm text-gray-500 mt-1">Xem và chỉnh số lượng trước khi đặt hàng.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm text-indigo-600">← Tiếp tục xem sản phẩm</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if(empty($cart['items']))
                <div class="bg-white shadow-sm rounded-lg p-6">
                    Giỏ hàng của bạn đang trống.
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                        <tr>
                            <th class="px-3 py-2">Sản phẩm</th>
                            <th class="px-3 py-2">Chu kỳ</th>
                            <th class="px-3 py-2">Đơn giá</th>
                            <th class="px-3 py-2">Số lượng</th>
                            <th class="px-3 py-2">Thành tiền</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cart['items'] as $item)
                            <tr class="border-t">
                                <td class="px-3 py-2">
                                    <a class="text-indigo-600 font-medium"
                                       href="{{ route('products.show', $item['slug']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                    @if(!empty($item['group']))
                                        <div class="text-xs text-gray-500">{{ $item['group'] }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-2 capitalize">{{ $item['billing_cycle'] }}</td>
                                <td class="px-3 py-2">
                                    {{ number_format($item['unit_total'], 0, ',', '.') }} {{ $cart['currency'] }}
                                </td>
                                <td class="px-3 py-2">
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                        <input type="number" name="qty" min="1" max="99"
                                               value="{{ $item['qty'] }}"
                                               class="w-20 border-gray-300 rounded-md shadow-sm text-sm">
                                        <button class="px-3 py-2 border rounded-md text-xs">Cập nhật</button>
                                    </form>
                                </td>
                                <td class="px-3 py-2 font-semibold">
                                    {{ number_format($item['line_total'], 0, ',', '.') }} {{ $cart['currency'] }}
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                        <button class="text-red-600 text-sm">Xoá</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 flex items-center justify-between">
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            <button class="px-4 py-2 border rounded-md text-sm">
                                Xoá tất cả
                            </button>
                        </form>

                        <div class="text-right">
                            <div class="text-sm text-gray-500">Tạm tính</div>
                            <div class="text-xl font-semibold">
                                {{ number_format($cart['subtotal'], 0, ',', '.') }} {{ $cart['currency'] }}
                            </div>

                            {{-- Checkout sẽ làm ở bước kế tiếp --}}
                            <a href="{{ route('checkout.show') }}"
                               class="inline-flex mt-3 px-5 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold">
                                Tiến hành đặt hàng
                            </a>

                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-public-layout>
