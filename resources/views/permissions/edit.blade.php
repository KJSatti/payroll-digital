<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Permission') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="bg-white border border-gray-200 shadow rounded-lg p-6">
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Name</label>
                    <input name="name" value="{{ $permission->name }}" class="w-full border px-3 py-2 rounded" required />
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>