<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Positions') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <div class="text-right mb-4">
            <a href="{{ route('positions.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Position
            </a>
        </div>
        <div class="bg-white border border-gray-200 shadow rounded-lg p-4">
            <div class="overflow-x-auto text-left">
                <table class="w-full table-auto text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Department</th>
                            <th class="px-4 py-2 text-left">Additional Text</th>
                            <th class="px-4 py-2 text-left">Created At</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($positions as $position)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $position->title }}</td>
                                <td class="px-4 py-2">{{ $position->department_name }}</td>
                                <td class="px-4 py-2">{{ $position->description }} </td>
                                <td class="px-4 py-2">{{ date('F j, Y', strtotime($position->created_at)) }} </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('positions.edit', $position->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('positions.destroy', $position->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
