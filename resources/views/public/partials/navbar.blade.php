@php
    $logo = setting('logo');
@endphp

<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Left: Brand --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" class="h-8" alt="Logo">
                @else
                    <span class="font-bold text-lg">{{ setting('company_name', config('app.name')) }}</span>
                @endif
            </a>

            {{-- Middle: Links --}}
            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('landing') }}" class="hover:text-indigo-600">Trang chủ</a>
                <a href="{{ route('products.index') }}" class="hover:text-indigo-600">Sản phẩm</a>

                {{-- Danh mục: đơn giản (sau sẽ làm dropdown) --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            @keydown.escape="open = false"
                            class="hover:text-indigo-600 text-sm inline-flex items-center gap-1">
                        Danh mục
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition
                         class="absolute z-50 mt-2 w-56 bg-white border rounded-lg shadow-sm overflow-hidden">
                        <a href="{{ route('products.index') }}"
                           class="block px-4 py-2 text-sm hover:bg-gray-50">
                            Tất cả sản phẩm
                        </a>

                        <div class="border-t"></div>

                        @foreach(($publicGroups ?? []) as $g)
                            <a href="{{ route('categories.show', $g->slug) }}"
                               class="block px-4 py-2 text-sm hover:bg-gray-50">
                                {{ $g->name }}
                            </a>
                        @endforeach
                    </div>
                </div>


                <a href="{{ route('contact') }}" class="hover:text-indigo-600">Liên hệ</a>

            </div>

            {{-- Right: auth buttons --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('cart.index') }}" class="text-sm px-3 py-2 border rounded-md">
                    Giỏ hàng
                    @php $count = count(session('cart.items', [])); @endphp
                    @if($count)
                        <span class="ml-1 text-xs bg-gray-900 text-white rounded-full px-2 py-0.5">{{ $count }}</span>
                    @endif
                </a>

            @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        Vào Portal
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 border rounded-md text-sm">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        Đăng ký
                    </a>
                @endauth
            </div>

        </div>
    </div>
</div>
