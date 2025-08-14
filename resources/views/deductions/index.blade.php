<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Deductions') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-2">
        <div class="text-right mb-4">
            <a href="{{ route('deductions.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Deduction
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
        <form method="GET" action="{{ route('deductions.index') }}"
            class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Employee --}}
            <div>
                <label class="block mb-1">Employee</label>
                <div x-data="{
                    employees: {{ Js::from(
                        $employees->map(
                            fn($e) => [
                                'id' => $e->id,
                                'name' => $e->first_name . ' ' . $e->last_name,
                            ],
                        ),
                    ) }},
                    search: '',
                    open: false,
                    selected: null,
                    get filtered() {
                        return this.search === '' ?
                            this.employees :
                            this.employees.filter(e =>
                                e.name.toLowerCase().includes(this.search.toLowerCase())
                            );
                    },
                    select(emp) {
                        this.selected = emp;
                        this.search = emp.name;
                        this.open = false;
                    }
                }" class="relative w-full">
                    <!-- Visible Input -->
                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                        @keydown.escape.window="open = false" class="w-full border px-3 py-2 rounded"
                        placeholder="Select employee">

                    <!-- Dropdown List -->
                    <div x-show="open"
                        class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-60 overflow-y-auto">
                        <template x-for="emp in filtered" :key="emp.id">
                            <div @click="select(emp)" class="px-3 py-2 hover:bg-blue-100 cursor-pointer"
                                x-text="emp.name"></div>
                        </template>
                        <div x-show="filtered.length === 0" class="px-3 py-2 text-gray-500">No results</div>
                    </div>

                    <!-- Hidden Input -->
                    <input type="hidden" name="employee_id" :value="selected?.id">
                </div>
            </div>

            {{-- Optional: Deduction Type filter (show if $deduction_types provided) --}}
            @isset($deduction_types)
                <div>
                    <label class="block mb-1">Deduction Type</label>
                    <select name="deduction_type_id" class="w-full border px-3 py-2 rounded" required>
                        <option value="">All Types</option>
                        @foreach ($deduction_types as $type)
                            <option value="{{ $type->id }}"
                                {{ request('deduction_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endisset

            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                <a href="{{ route('deductions.index') }}"
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
                            <th class="px-4 py-2">Deduction Type</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Start Date</th>
                            <th class="px-4 py-2">End Date</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deductions as $deduction)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    {{ optional($deduction->employee)->first_name }}
                                    {{ optional($deduction->employee)->last_name }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ optional($deduction->deductionType)->name ?? '—' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ number_format($deduction->deduction_amount, 2) }}
                                </td>
                                <td class="px-4 py-2">{{ $deduction->start_date }}</td>
                                <td class="px-4 py-2">{{ $deduction->end_date ?? '—' }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('deductions.edit', $deduction->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('deductions.destroy', $deduction->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center text-gray-500">No deductions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- For pagination, replace ->get() with ->paginate() in controller and enable: --}}
                {{-- <div class="mt-4">{{ $deductions->appends(request()->query())->links() }}</div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
