<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Attendance') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10">
        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST" action="{{ route('attendences.update', $attendence->id) }}" x-data="attendanceCalc({
                timeIn: '{{ \Illuminate\Support\Str::of($attendence->time_in)->substr(0, 5) }}',
                timeOut: '{{ \Illuminate\Support\Str::of($attendence->time_out)->substr(0, 5) }}'
            })"
                x-init="init(true)">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Employee (preselected) --}}
                    <div>
                        <label class="block mb-1">Employee</label>
                        @php $selectedEmployee = $employees->firstWhere('id', $attendence->employee_id); @endphp
                        <div x-data="{
                            employees: {{ Js::from($employees->map(fn($e) => ['id' => $e->id, 'name' => $e->first_name . ' ' . $e->last_name])) }},
                            search: '{{ $selectedEmployee ? $selectedEmployee->first_name . ' ' . $selectedEmployee->last_name : '' }}',
                            selected: {{ Js::from([
                                'id' => $selectedEmployee?->id,
                                'name' => $selectedEmployee?->first_name . ' ' . $selectedEmployee?->last_name,
                            ]) }},
                            open: false,
                            get filtered() {
                                return this.search === '' ? this.employees :
                                    this.employees.filter(e => e.name.toLowerCase().includes(this.search.toLowerCase()));
                            },
                            select(emp) { this.selected = emp;
                                this.search = emp.name;
                                this.open = false; }
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

                    {{-- Date --}}
                    <div>
                        <label class="block mb-1">Date</label>
                        <input type="date" name="date" value="{{ old('date', $attendence->date ?? now()->toDateString()) }}"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>

                    {{-- Time In --}}
                    <div>
                        <label class="block mb-1">Time In</label>
                        <input type="time" name="time_in" x-model="timeIn" @input="recalc()"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>

                    {{-- Time Out --}}
                    <div>
                        <label class="block mb-1">Time Out</label>
                        <input type="time" name="time_out" x-model="timeOut" @input="recalc()"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>

                    {{-- Total Hours Worked (auto) --}}
                    <div>
                        <label class="block mb-1">Total Hours Worked</label>
                        <input type="number" name="total_hours_worked" x-model="totalHoursWorked" @focus="recalc()"
                            step="0.01" class="w-full border px-3 py-2 rounded bg-gray-50"
                            placeholder="Auto-calculated" readonly>
                    </div>

                    {{-- Overtime Hours (auto) --}}
                    <div>
                        <label class="block mb-1">Overtime Hours</label>
                        <input type="number" name="overtime_hours" x-model="overtimeHours" @focus="recalc()"
                            step="0.01" class="w-full border px-3 py-2 rounded bg-gray-50" readonly>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
                    <a href="{{ route('attendences.index') }}"
                        class="ml-2 text-gray-600 hover:text-gray-900 underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Alpine helper (include once per layout or page) --}}
    <script>
        function attendanceCalc(seed = {}) {
            return {
                timeIn: seed.timeIn || '',
                timeOut: seed.timeOut || '',
                totalHoursWorked: '',
                overtimeHours: '',
                standardHours: 8, // SOP: 8 hours

                init(isEdit = false) {
                    if (isEdit) this.recalc();
                },

                recalc() {
                    const minutes = this.diffInMinutes(this.timeIn, this.timeOut);
                    if (minutes === null) {
                        this.totalHoursWorked = '';
                        this.overtimeHours = '';
                        return;
                    }

                    const worked = minutes / 60;
                    this.totalHoursWorked = worked.toFixed(2);

                    const ot = worked > this.standardHours ? (worked - this.standardHours) : 0;
                    this.overtimeHours = ot.toFixed(2);
                },

                // Accepts 'HH:MM' or 'HH:MM:SS' (24h). Handles overnight.
                diffInMinutes(t1, t2) {
                    if (!t1 || !t2) return null;
                    const parts = t => t.split(':').map(Number);
                    const [h1, m1, s1 = 0] = parts(t1);
                    const [h2, m2, s2 = 0] = parts(t2);
                    const toMin = (h, m, s) => h * 60 + m + Math.floor(s / 60);
                    let a = toMin(h1, m1, s1),
                        b = toMin(h2, m2, s2);
                    if (b < a) b += 24 * 60; // overnight shift
                    return b - a;
                },
            }
        }
    </script>
</x-app-layout>
