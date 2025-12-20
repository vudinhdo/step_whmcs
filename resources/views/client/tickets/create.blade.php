<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tạo ticket mới
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('client.tickets.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phòng ban
                        </label>
                        <select name="department_id"
                                class="border-gray-300 rounded-md shadow-sm w-full">
                            @foreach ($departments as $id => $name)
                                <option value="{{ $id }}" @selected(old('department_id') == $id)>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Chủ đề
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="border-gray-300 rounded-md shadow-sm w-full">
                        @error('subject')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mức độ ưu tiên
                        </label>
                        <select name="priority"
                                class="border-gray-300 rounded-md shadow-sm w-full">
                            <option value="low">Thấp</option>
                            <option value="medium" selected>Trung bình</option>
                            <option value="high">Cao</option>
                            <option value="urgent">Khẩn cấp</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nội dung
                        </label>
                        <textarea name="message" rows="6"
                                  class="border-gray-300 rounded-md shadow-sm w-full">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('client.tickets.index') }}"
                           class="px-4 py-2 mr-2 border border-gray-300 rounded-md text-sm text-gray-700">
                            Huỷ
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500">
                            Gửi ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-client-layout>
