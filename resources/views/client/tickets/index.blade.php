<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket hỗ trợ
            </h2>
            <a href="{{ route('client.tickets.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                Tạo ticket mới
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('status'))
                    <div class="mb-4 text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($tickets->isEmpty())
                    <p>Bạn chưa có ticket nào.</p>
                @else
                    <table class="min-w-full text-sm text-left">
                        <thead>
                        <tr>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">Chủ đề</th>
                            <th class="px-3 py-2">Phòng ban</th>
                            <th class="px-3 py-2">Ưu tiên</th>
                            <th class="px-3 py-2">Trạng thái</th>
                            <th class="px-3 py-2">Cập nhật</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tickets as $ticket)
                            <tr class="border-t">
                                <td class="px-3 py-2">
                                    <a href="{{ route('client.tickets.show', $ticket) }}" class="text-indigo-600">
                                        #{{ $ticket->id }}
                                    </a>
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('client.tickets.show', $ticket) }}" class="text-indigo-600">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>
                                <td class="px-3 py-2">{{ $ticket->department->name ?? '-' }}</td>
                                <td class="px-3 py-2 capitalize">{{ $ticket->priority }}</td>
                                <td class="px-3 py-2 capitalize">{{ $ticket->status }}</td>
                                <td class="px-3 py-2 text-gray-500">
                                    {{ $ticket->updated_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>
