<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hoá đơn
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                @if ($invoices->isEmpty())
                    <p>Chưa có hoá đơn nào.</p>
                @else
                    <table class="min-w-full text-sm text-left">
                        <thead>
                        <tr>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Ngày xuất</th>
                            <th class="px-3 py-2">Đến hạn</th>
                            <th class="px-3 py-2">Tổng</th>
                            <th class="px-3 py-2">Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($invoices as $invoice)
                            <tr class="border-t">
                                <td class="px-3 py-2">
                                    <a href="{{ route('client.invoices.show', $invoice) }}"
                                       class="text-indigo-600">
                                        #{{ $invoice->id }}
                                    </a>
                                </td>
                                <td class="px-3 py-2">{{ $invoice->issue_date?->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">{{ $invoice->due_date?->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">
                                    {{ number_format($invoice->total, 0, ',', '.') }} {{ $invoice->currency }}
                                </td>
                                <td class="px-3 py-2 capitalize">{{ $invoice->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>
