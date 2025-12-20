<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Client Portal' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
<div class="min-h-screen">

    {{-- Topbar --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="font-bold text-lg">
                {{ config('app.name') }} <span class="text-indigo-600">Portal</span>
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Sản phẩm</a>
                <a href="{{ route('cart.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Giỏ hàng</a>

                <div class="h-6 w-px bg-gray-200"></div>

                <div class="text-sm text-gray-600 hidden sm:block">
                    {{ auth()->user()->name }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-600 hover:text-red-700 font-semibold">Đăng xuất</button>
                </form>
            </div>
        </div>
    </header>

    {{-- Main --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid lg:grid-cols-12 gap-6">

        {{-- Sidebar --}}
        <aside class="lg:col-span-3">
            <div class="bg-white border rounded-2xl p-4 sticky top-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <nav class="space-y-1 text-sm">
                    <a href="{{ route('dashboard') }}"
                       class="block px-3 py-2 rounded-xl hover:bg-gray-50 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}">
                        Tổng quan
                    </a>

                    <a href="{{ route('client.services.index') }}"
                       class="block px-3 py-2 rounded-xl hover:bg-gray-50 {{ request()->routeIs('client.services.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}">
                        Dịch vụ
                    </a>

                    <a href="{{ route('client.invoices.index') }}"
                       class="block px-3 py-2 rounded-xl hover:bg-gray-50 {{ request()->routeIs('client.invoices.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}">
                        Hóa đơn
                    </a>

                    <a href="{{ route('client.orders.index') }}"
                       class="block px-3 py-2 rounded-xl hover:bg-gray-50 {{ request()->routeIs('client.orders.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}">
                        Đơn hàng
                    </a>

                    <a href="{{ route('client.tickets.index') }}"
                       class="block px-3 py-2 rounded-xl hover:bg-gray-50 {{ request()->routeIs('client.tickets.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}">
                        Ticket hỗ trợ
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="block px-3 py-2 rounded-xl hover:bg-gray-50 {{ request()->routeIs('profile.edit') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700' }}">
                        Tài khoản
                    </a>
                </nav>

                <div class="mt-4 pt-4 border-t text-xs text-gray-500">
                    Cần hỗ trợ? <a class="text-indigo-600 font-semibold" href="{{ route('client.tickets.create') }}">Tạo ticket</a>
                </div>
            </div>
        </aside>

        {{-- Content --}}
        <main class="lg:col-span-9">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{ $slot }}
        </main>

    </div>

</div>
</body>
</html>
