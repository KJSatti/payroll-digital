<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Leave Type') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST" action="{{ route('leave-types.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border px-3 py-2 rounded">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                    <a href="{{ route('leave-types.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
