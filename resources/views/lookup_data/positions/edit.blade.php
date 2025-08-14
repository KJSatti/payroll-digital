<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Position') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <form action="{{ route('positions.update', $position->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="bg-white border border-gray-200 shadow rounded-lg p-6">
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Title</label>
                    <input name="name" value="{{ $position->title }}" class="w-full border px-3 py-2 rounded" required />
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Departments</label>
                    <select name="department_id" class="w-full border px-3 py-2 rounded">
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ $position->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Additional Text</label>
                    <input name="description" value="{{ $position->description }}" class="w-full border px-3 py-2 rounded" required />
                </div>
                
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>