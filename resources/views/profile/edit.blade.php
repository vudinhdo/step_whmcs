<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">



            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @if(session('warning'))
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800 text-sm">
                        {{ session('warning') }}
                    </div>
                @endif

                @if(auth()->user()->must_change_password)
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 text-sm">
                        Bạn đang đăng nhập lần đầu. Vui lòng đổi mật khẩu để tiếp tục sử dụng Client Portal.
                    </div>
                @endif
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>
</x-client-layout>
