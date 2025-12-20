<x-client-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ $ticket->id }} - {{ $ticket->subject }}
            </h2>
            <a href="{{ route('client.tickets.index') }}" class="text-sm text-indigo-600">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p><strong>Phòng ban:</strong> {{ $ticket->department->name ?? '-' }}</p>
                <p><strong>Trạng thái:</strong> <span class="capitalize">{{ $ticket->status }}</span></p>
                <p><strong>Ưu tiên:</strong> <span class="capitalize">{{ $ticket->priority }}</span></p>
                <p><strong>Tạo lúc:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>

            {{-- Danh sách trao đổi --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold mb-2">Trao đổi</h3>

                @foreach ($ticket->replies as $reply)
                    <div class="border rounded-md p-3 {{ $reply->is_staff ? 'bg-slate-50' : '' }}">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>
                                {{ $reply->user->email ?? 'Người dùng' }}
                                @if($reply->is_staff)
                                    <span class="ml-1 px-1 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px]">
                                        Staff
                                    </span>
                                @endif
                            </span>
                            <span>{{ $reply->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-sm whitespace-pre-line">{{ $reply->message }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Form trả lời --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('status'))
                    <div class="mb-4 text-green-600 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('client.tickets.reply', $ticket) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Trả lời
                        </label>
                        <textarea name="message" rows="4"
                                  class="border-gray-300 rounded-md shadow-sm w-full">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500">
                            Gửi trả lời
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-client-layout>
