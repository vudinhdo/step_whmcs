@php use Illuminate\Support\Str; @endphp
<x-public-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dịch vụ & Bảng giá
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Chọn danh mục, tìm kiếm, xem chi tiết và đặt mua khi cần.
                </p>
            </div>

            {{-- Search + Filter --}}
            <form method="GET" action="{{ route('products.index') }}" class="flex gap-2 w-full md:w-auto">
                <select name="group" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">Tất cả danh mục</option>
                    @foreach($groups as $g)
                        <option value="{{ $g->slug }}" @selected($groupSlug === $g->slug)>
                            {{ $g->name }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="q" value="{{ $q }}"
                       placeholder="Tìm dịch vụ..."
                       class="border-gray-300 rounded-md shadow-sm text-sm w-full md:w-64">

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-500">
                    Lọc
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Quick breadcrumb --}}
            <div class="text-sm text-gray-500 mb-4">
                <a href="{{ route('landing') }}" class="text-indigo-600">Trang chủ</a>
                <span class="mx-1">/</span>
                <span>Sản phẩm</span>
            </div>

            @if($products->isEmpty())
                <div class="bg-white shadow-sm rounded-lg p-6">
                    Không tìm thấy sản phẩm phù hợp.
                </div>
            @else
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @php
                            $minPrice = $product->pricing->min('price');
                            $currency = $product->pricing->first()->currency ?? setting('default_currency', 'VND');
                        @endphp

                        <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col">
                            <div class="flex items-start justify-between gap-3">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="text-lg font-semibold text-indigo-600">
                                    {{ $product->name }}
                                </a>

                                @if($product->group)
                                    <a href="{{ route('products.index', ['group' => $product->group->slug]) }}"
                                       class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700 whitespace-nowrap">
                                        {{ $product->group->name }}
                                    </a>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 mt-2">
                                {{ Str::limit($product->description, 110) }}
                            </p>

                            <div class="mt-4 text-sm">
                                @if(!is_null($minPrice))
                                    <span class="text-gray-500">Giá từ</span>
                                    <span class="font-semibold">
                                        {{ number_format($minPrice, 0, ',', '.') }} {{ $currency }}
                                    </span>
                                    <span class="text-gray-500">/ kỳ</span>
                                @else
                                    <span class="text-gray-500">Liên hệ để báo giá</span>
                                @endif
                            </div>

                            <div class="mt-auto pt-4 flex gap-2">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="inline-flex px-4 py-2 border rounded-md text-sm">
                                    Xem chi tiết
                                </a>


                                {{-- CTA: nếu login thì đặt mua luôn, guest thì mời đăng nhập/đăng ký --}}
                                @auth
                                    <a href="{{ route('products.show', $product->slug) }}#order"
                                       class="inline-flex px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold">
                                        Đặt mua
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="inline-flex px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold">
                                        Đăng nhập để đặt
                                    </a>
                                @endauth

                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
