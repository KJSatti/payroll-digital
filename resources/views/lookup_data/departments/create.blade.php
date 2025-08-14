<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Department') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <div class="bg-white border border-gray-200 shadow rounded-lg p-6 w-full">
            <form action="{{ route('departments.store') }}" method="POST" class="w-full">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Name</label>
                    <input name="name"
                        class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300"
                        required />
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Additional Text</label>
                    <input name="description" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300" required />
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded">Save</button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
