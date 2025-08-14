<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendances') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <div class="text-right mb-4">
            <a href="{{ route('attendences.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Attendance
            </a>
        </div>

        {{-- Dismissible Alerts --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-800 rounded flex justify-between items-center">
                <div><span class="font-semibold">Success:</span> {{ session('success') }}</div>
                <button @click="show = false"
                    class="text-green-800 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
            </div>
        @elseif (session('error'))
            <div x-data="{ show: true }" x-show="show"
                class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-800 rounded flex justify-between items-center">
                <div><span class="font-semibold">Error:</span> {{ session('error') }}</div>
                <button @click="show = false"
                    class="text-red-800 hover:text-red-900 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        {{-- Filters --}}
        <form method="GET" action="{{ route('attendences.index') }}" class="mb-6 space-y-4">
            {{-- Row 1: Employee + Single Date --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                    <select name="employee_id" class="w-full border px-3 py-2 rounded">
                        <option value="">All Employees</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date (exact)</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full border px-3 py-2 rounded">
                </div>
            </div>

            {{-- Row 2: Date Range + Buttons --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full border px-3 py-2 rounded">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                    <a href="{{ route('attendences.index') }}"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white border border-gray-200 shadow rounded-lg p-4">
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">Employee</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Time In</th>
                            <th class="px-4 py-2">Time Out</th>
                            <th class="px-4 py-2">Total Hours</th>
                            <th class="px-4 py-2">Overtime</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendences as $att)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    {{ optional($att->employee)->first_name }}
                                    {{ optional($att->employee)->last_name }}
                                </td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                                <td class="px-4 py-2">
                                    {{ \Illuminate\Support\Str::of($att->time_in)->substr(0, 5) }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ \Illuminate\Support\Str::of($att->time_out)->substr(0, 5) }}
                                </td>
                                <td class="px-4 py-2">{{ $att->total_hours_worked }}</td>
                                <td class="px-4 py-2">{{ $att->overtime_hours }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('attendences.edit', $att->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('attendences.destroy', $att->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure?')"
                                            class="text-red-600 hover:underline ml-2">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                                    No attendance records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- If you switch to paginate() in the model, render links here --}}
                {{-- <div class="mt-4">{{ $attendences->appends(request()->query())->links() }}</div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
