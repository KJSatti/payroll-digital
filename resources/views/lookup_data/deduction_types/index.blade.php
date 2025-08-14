<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Deduction Types') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-2">
        <div class="text-right mb-4">
            <a href="{{ route('deduction-types.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Deduction Type
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 shadow rounded-lg p-4">
            <table class="w-full table-auto text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($benefitTypes as $benefitType)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $benefitType->name }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('deduction-types.edit', $benefitType->id) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('deduction-types.destroy', $benefitType->id) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline ml-2"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
