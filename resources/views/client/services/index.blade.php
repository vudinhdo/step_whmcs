<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dịch vụ của tôi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                @if ($services->isEmpty())
                    <p>Bạn chưa có dịch vụ nào.</p>
                @else
                    <table class="min-w-full text-sm text-left">
                        <thead>
                        <tr>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Sản phẩm</th>
                            <th class="px-3 py-2">Trạng thái</th>
                            <th class="px-3 py-2">Chu kỳ</th>
                            <th class="px-3 py-2">Ngày bắt đầu</th>
                            <th class="px-3 py-2">Đến hạn</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($services as $service)
                            <tr class="border-t">
                                <td class="px-3 py-2">#{{ $service->id }}</td>
                                <td class="px-3 py-2">
                                    {{ $service->product->name ?? '-' }}
                                </td>
                                <td class="px-3 py-2 capitalize">
                                    {{ $service->status }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $service->billing_cycle }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ optional($service->start_date)->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ optional($service->next_due_date)->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <a href="{{ route('client.services.show', $service) }}"
                                       class="text-indigo-600 text-sm">
                                        Xem chi tiết →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $services->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>
