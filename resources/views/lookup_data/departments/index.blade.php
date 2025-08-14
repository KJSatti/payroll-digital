<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Departments') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <div class="text-right mb-4">
            <a href="{{ route('departments.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Department
            </a>
        </div>
        <div class="bg-white border border-gray-200 shadow rounded-lg p-4">
            <div class="overflow-x-auto text-left">
                <table class="w-full table-auto text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Additional Text</th>
                            <th class="px-4 py-2 text-left">Created At</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $department->name }}</td>
                                <td class="px-4 py-2">{{ $department->description }} </td>
                                <td class="px-4 py-2">{{ date('F j, Y', strtotime($department->created_at)) }} </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('departments.edit', $department->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
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
