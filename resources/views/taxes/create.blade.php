<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Tax') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST" action="{{ route('taxes.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    <div>
                        <label class="block mb-1">Federal Tax</label>
                        <input type="number" name="federal_tax" step="0.01" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">State Tax</label>
                        <input type="number" name="state_tax" step="0.01" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">Local Tax</label>
                        <input type="number" name="local_tax" step="0.01" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">Social Security Tax</label>
                        <input type="number" name="social_security_tax" step="0.01" required
                            class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block mb-1">Medicare Tax</label>
                        <input type="number" name="medicare_tax" step="0.01" required
                            class="w-full border px-3 py-2 rounded">
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                    <a href="{{ route('taxes.index') }}"
                        class="ml-2 text-gray-600 hover:text-gray-900 underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
