<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tax') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10">
        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST" action="{{ route('taxes.update', $tax->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Employee --}}
                    <div>
                        <label class="block mb-1">Employee</label>
                        @php
                            $selectedEmployee = $employees->firstWhere('id', $tax->employee_id);
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
                            search: '{{ $selectedEmployee ? $selectedEmployee->first_name . ' ' . $selectedEmployee->last_name : '' }}',
                            selected: {{ Js::from([
                                'id' => $selectedEmployee?->id,
                                'name' => $selectedEmployee?->first_name . ' ' . $selectedEmployee?->last_name,
                            ]) }},
                            open: false,
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
                            <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                                @keydown.escape.window="open = false" class="w-full border px-3 py-2 rounded"
                                placeholder="Select employee">

                            <div x-show="open"
                                class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-60 overflow-y-auto">
                                <template x-for="emp in filtered" :key="emp.id">
                                    <div @click="select(emp)" class="px-3 py-2 hover:bg-blue-100 cursor-pointer"
                                        x-text="emp.name"></div>
                                </template>
                                <div x-show="filtered.length === 0" class="px-3 py-2 text-gray-500">No results</div>
                            </div>

                            <input type="hidden" name="employee_id" :value="selected?.id">
                        </div>
                    </div>

                    {{-- Federal Tax --}}
                    <div>
                        <label class="block mb-1">Federal Tax</label>
                        <input type="number" name="federal_tax" step="0.01"
                            value="{{ old('federal_tax', $tax->federal_tax) }}" class="w-full border px-3 py-2 rounded"
                            required>
                    </div>

                    {{-- State Tax --}}
                    <div>
                        <label class="block mb-1">State Tax</label>
                        <input type="number" name="state_tax" step="0.01"
                            value="{{ old('state_tax', $tax->state_tax) }}" class="w-full border px-3 py-2 rounded"
                            required>
                    </div>

                    {{-- Local Tax --}}
                    <div>
                        <label class="block mb-1">Local Tax</label>
                        <input type="number" name="local_tax" step="0.01"
                            value="{{ old('local_tax', $tax->local_tax) }}" class="w-full border px-3 py-2 rounded"
                            required>
                    </div>

                    {{-- Social Security Tax --}}
                    <div>
                        <label class="block mb-1">Social Security Tax</label>
                        <input type="number" name="social_security_tax" step="0.01"
                            value="{{ old('social_security_tax', $tax->social_security_tax) }}"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>

                    {{-- Medicare Tax --}}
                    <div>
                        <label class="block mb-1">Medicare Tax</label>
                        <input type="number" name="medicare_tax" step="0.01"
                            value="{{ old('medicare_tax', $tax->medicare_tax) }}"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
                    <a href="{{ route('taxes.index') }}"
                        class="ml-2 text-gray-600 hover:text-gray-900 underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
