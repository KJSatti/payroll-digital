<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Taxes') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-2">
        <div class="text-right mb-4">
            <a href="{{ route('taxes.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Tax
            </a>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-800 rounded flex justify-between items-center">
                <div>
                    <span class="font-semibold">Success:</span> {{ session('success') }}
                </div>
                <button @click="show = false"
                    class="text-green-800 hover:text-green-900 font-bold text-lg leading-none">
                    &times;
                </button>
            </div>
        @elseif (session('error'))
            <div x-data="{ show: true }" x-show="show"
                class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-800 rounded flex justify-between items-center">
                <div>
                    <span class="font-semibold">Error:</span> {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-800 hover:text-red-900 font-bold text-lg leading-none">
                    &times;
                </button>
            </div>
        @endif

        {{-- Filters --}}
        <form method="GET" action="{{ route('taxes.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Employee</label>
                <select name="employee_id" class="w-full border px-3 py-2 rounded">
                    <option value="">All Employees</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                <a href="{{ route('taxes.index') }}"
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white border border-gray-200 shadow rounded-lg p-4">
            <div class="overflow-x-auto text-left">
                <table class="w-full table-auto text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">Employee</th>
                            <th class="px-4 py-2">Federal Tax</th>
                            <th class="px-4 py-2">State Tax</th>
                            <th class="px-4 py-2">Local Tax</th>
                            <th class="px-4 py-2">Social Security Tax</th>
                            <th class="px-4 py-2">Medicare Tax</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($taxes as $tax)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    {{ optional($tax->employee)->first_name }}
                                    {{ optional($tax->employee)->last_name }}
                                </td>
                                <td class="px-4 py-2">{{ $tax->federal_tax }}</td>
                                <td class="px-4 py-2">{{ $tax->state_tax }}</td>
                                <td class="px-4 py-2">{{ $tax->local_tax }}</td>
                                <td class="px-4 py-2">{{ $tax->social_security_tax }}</td>
                                <td class="px-4 py-2">{{ $tax->medicare_tax }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('taxes.edit', $tax->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('taxes.destroy', $tax->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-center text-gray-500">No tax records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
