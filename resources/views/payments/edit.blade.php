<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Payment') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-12">
        <div class="bg-white p-6 rounded-lg shadow">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show"
                    class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-800 rounded flex justify-between items-center">
                    <div>
                        <span class="font-semibold">Please fix the following:</span>
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="show = false"
                        class="text-red-800 hover:text-red-900 font-bold text-lg leading-none">&times;</button>
                </div>
            @endif

            <form method="POST" action="{{ route('payments.update', $payment->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Employee (Alpine searchable, pre-selected) --}}
                    <div>
                        <label class="block mb-1">Employee</label>
                        @php
                            $selectedEmployee = $employees->firstWhere('id', old('employee_id', $payment->employee_id));
                        @endphp
                        <div x-data="{
                            employees: {{ Js::from(
                                $employees->map(
                                    fn($e) => [
                                        'id' => $e->id,
                                        'name' => $e->first_name . ' ' . $e->last_name,
                                    ],
                                ),
                            ) }},
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
                        }" x-cloak class="relative w-full">
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

                            <!-- Hidden Input -->
                            <input type="hidden" name="employee_id" :value="selected ? selected.id : ''">
                        </div>
                        @error('employee_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Method (stores ID in payments.payment_method) --}}
                    <div>
                        <label class="block mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full border px-3 py-2 rounded" required>
                            <option value="">Select Method</option>
                            @foreach ($methods as $method)
                                <option value="{{ $method->id }}"
                                    {{ (int) old('payment_method', $payment->payment_method) === (int) $method->id ? 'selected' : '' }}>
                                    {{ $method->title ?? $method->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gross Pay --}}
                    <div>
                        <label class="block mb-1">Gross Pay</label>
                        <input type="number" step="0.01" name="gross_pay"
                            value="{{ old('gross_pay', $payment->gross_pay) }}" class="w-full border px-3 py-2 rounded"
                            required>
                        @error('gross_pay')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Total Deductions --}}
                    <div>
                        <label class="block mb-1">Total Deductions</label>
                        <input type="number" step="0.01" name="total_deductions"
                            value="{{ old('total_deductions', $payment->total_deductions) }}"
                            class="w-full border px-3 py-2 rounded" required>
                        @error('total_deductions')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Net Pay --}}
                    <div>
                        <label class="block mb-1">Net Pay</label>
                        <input type="number" step="0.01" name="net_pay"
                            value="{{ old('net_pay', $payment->net_pay) }}" class="w-full border px-3 py-2 rounded"
                            required>
                        @error('net_pay')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label class="block mb-1">Payment Date</label>
                        <input type="date" name="payment_date"
                            value="{{ old('payment_date', \Illuminate\Support\Str::of($payment->payment_date)->substr(0, 10)) }}"
                            class="w-full border px-3 py-2 rounded" required>
                        @error('payment_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
                    <a href="{{ route('payments.index') }}"
                        class="ml-2 text-gray-600 hover:text-gray-900 underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
