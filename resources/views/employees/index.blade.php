<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-2">
        <div class="text-right mb-4">
            <a href="{{ route('employees.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Employee
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="mb-4 text-red-600">{{ session('error') }}</div>
        @endif

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('employees.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- First Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ request('first_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="First Name">
                </div>

                {{-- Last Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ request('last_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Last Name">
                </div>

                {{-- Department --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded">
                        <option value="">All Departments</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Position --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <select name="position_id" class="w-full px-3 py-2 border border-gray-300 rounded">
                        <option value="">All Positions</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}"
                                {{ request('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hire Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                    <input type="date" name="hire_date" value="{{ request('hire_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                {{-- Buttons --}}
                <div class="flex items-end gap-3">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                    <a href="{{ route('employees.index') }}"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
                </div>

            </div>
        </form>

        <form method="GET" action="{{ route('employees.index') }}" class="flex justify-end items-center gap-2 mb-4">
            <label class="block text-sm font-medium text-gray-700">Per Page</label>
            <select name="per_page" class="border px-3 py-2 rounded w-48"
                onchange="this.form.submit()">
                @foreach ([10, 15, 25, 50, 100] as $n)
                    <option value="{{ $n }}"
                        {{ (int) request('per_page', 15) === $n ? 'selected' : '' }}>
                        {{ $n }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Table --}}
        <div class="bg-white border border-gray-200 shadow rounded-lg p-4">
            <div class="overflow-x-auto text-left">
                <table class="w-full table-auto text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Department</th>
                            <th class="px-4 py-2">Position</th>
                            <th class="px-4 py-2">Hire Date</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </td>
                                <td class="px-4 py-2">{{ $employee->email }}</td>
                                <td class="px-4 py-2">{{ optional($employee->department)->name }}</td>
                                <td class="px-4 py-2">{{ optional($employee->position)->title }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($employee->hire_date)->format('d M Y') }}</td>
                                <td class="px-4 py-2 capitalize">{{ $employee->status }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('employees.edit', $employee->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2"
                                            onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                                    No employees found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- Pagination --}}
                @if ($employees instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    <div class="mt-4">
                        {{ $employees->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
