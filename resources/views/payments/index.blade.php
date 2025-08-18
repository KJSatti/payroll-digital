<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-2">
        <div class="text-right mb-4">
            <a href="{{ route('payments.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Add Payment
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
        <form method="GET" action="{{ route('payments.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Employee --}}
            @php
                $selectedEmployee = $employees->firstWhere('id', (int) request('employee_id'));
            @endphp

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
                    // seed from the current query string (?employee_id=...)
                    selected: {{ Js::from(
                        $selectedEmployee
                            ? ['id' => $selectedEmployee->id, 'name' => $selectedEmployee->first_name . ' ' . $selectedEmployee->last_name]
                            : null,
                    ) }},
                    search: '{{ $selectedEmployee ? $selectedEmployee->first_name . ' ' . $selectedEmployee->last_name : '' }}',
                    open: false,
                
                    get filtered() {
                        if (!this.search) return this.employees;
                        const s = this.search.toLowerCase();
                        return this.employees.filter(e => e.name.toLowerCase().includes(s));
                    },
                    select(emp) {
                        this.selected = emp;
                        this.search = emp.name;
                        this.open = false;
                    }
                }" class="relative w-full" x-cloak>
                    <!-- Visible Input -->
                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                        @keydown.escape.window="open = false" class="w-full border px-3 py-2 rounded"
                        placeholder="Select employee">

                    <!-- Dropdown List -->
                    <div x-show="open"
                        class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-60 overflow-y-auto">
                        <template x-for="emp in filtered" :key="emp.id">
                            <div @click="select(emp)"
                                class="px-3 py-2 hover:bg-blue-100 cursor-pointer flex justify-between">
                                <span x-text="emp.name"></span>
                                <span x-show="selected && selected.id === emp.id"
                                    class="text-xs text-blue-600">Selected</span>
                            </div>
                        </template>
                        <div x-show="filtered.length === 0" class="px-3 py-2 text-gray-500">No results</div>
                    </div>

                    <!-- Hidden Input (what the server receives) -->
                    <input type="hidden" name="employee_id" :value="selected ? selected.id : ''">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="payment_method" class="w-full border px-3 py-2 rounded">
                    <option value="">All Methods</option>
                    @foreach ($methods as $method)
                        <option value="{{ $method->id }}"
                            {{ request('payment_method') == $method->id ? 'selected' : '' }}>
                            {{ $method->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                <a href="{{ route('payments.index') }}"
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
                            <th class="px-4 py-2">Gross Pay</th>
                            <th class="px-4 py-2">Total Deductions</th>
                            <th class="px-4 py-2">Net Pay</th>
                            <th class="px-4 py-2">Payment Date</th>
                            <th class="px-4 py-2">Payment Method</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    {{ optional($payment->employee)->first_name }}
                                    {{ optional($payment->employee)->last_name }}
                                </td>
                                <td class="px-4 py-2">{{ $payment->gross_pay }}</td>
                                <td class="px-4 py-2">{{ $payment->total_deductions }}</td>
                                <td class="px-4 py-2">{{ $payment->net_pay }}</td>
                                <td class="px-4 py-2">{{ $payment->payment_date }}</td>
                                <td class="px-4 py-2">
                                    {{ optional($payment->paymentMethod)->name }}
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('payments.edit', $payment->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-center text-gray-500">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
