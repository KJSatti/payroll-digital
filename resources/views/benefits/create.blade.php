<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Benefit') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-white p-6 rounded-lg shadow">
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
            <form method="POST" action="{{ route('benefits.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    {{-- Benefit Type --}}
                    <div>
                        <label class="block mb-1">Benefit Type</label>
                        <select name="benefit_type_id" class="w-full border px-3 py-2 rounded" required>
                            <option value="">Select Benefit Type</option>
                            @foreach ($benefit_types as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('benefit_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Enrollment Date --}}
                    <div>
                        <label class="block mb-1">Enrollment Date</label>
                        <input type="date" name="enrollment_date"
                            value="{{ old('enrollment_date', now()->toDateString()) }}" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    {{-- Monthly Contribution --}}
                    <div>
                        <label class="block mb-1">Monthly Contribution</label>
                        <input type="number" name="monthly_contribution" step="0.01"
                            value="{{ old('monthly_contribution') }}" required class="w-full border px-3 py-2 rounded">
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                    <a href="{{ route('benefits.index') }}"
                        class="ml-2 text-gray-600 hover:text-gray-900 underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
