@php use Illuminate\Support\Str; @endphp
<x-public-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $group->name }}
            </h2>
            @if($group->description)
                <p class="text-sm text-gray-500 mt-1">{{ $group->description }}</p>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($group->products->isEmpty())
                <div class="bg-white shadow-sm rounded-lg p-6">
                    Chưa có sản phẩm trong danh mục này.
                </div>
            @else
                
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($group->products as $product)
                        @php
                            $minPrice = $product->pricing->min('price');
                            $currency = $product->pricing->first()->currency ?? setting('default_currency', 'VND');
                        @endphp

                        <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col">
                            <a href="{{ route('products.show', $product->slug) }}"
                               class="text-lg font-semibold text-indigo-600">
                                {{ $product->name }}
                            </a>

                            <p class="text-sm text-gray-600 mt-2">
                                {{ Str::limit($product->description, 110) }}
                            </p>

                            <div class="mt-4 text-sm">
                                @if(!is_null($minPrice))
                                    <span class="text-gray-500">Giá từ</span>
                                    <span class="font-semibold">
                                        {{ number_format($minPrice, 0, ',', '.') }} {{ $currency }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Liên hệ để báo giá</span>
                                @endif
                            </div>

                            <div class="mt-auto pt-4 flex gap-2">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="inline-flex px-4 py-2 border rounded-md text-sm">
                                    Xem chi tiết
                                </a>
                                <a href="{{ route('products.index') }}"
                                   class="inline-flex px-4 py-2 border rounded-md text-sm">
                                    Xem thêm
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
