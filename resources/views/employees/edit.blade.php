<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-2">
        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name"
                            value="{{ old('first_name', $employee->first_name) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2" required>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name"
                            value="{{ old('last_name', $employee->last_name) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2" required>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            value="{{ old('date_of_birth', $employee->date_of_birth) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2">
                    </div>

                    <!-- Hire Date -->
                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700">Hire Date</label>
                        <input type="date" name="hire_date" id="hire_date"
                            value="{{ old('hire_date', $employee->hire_date) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2" required>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                        <select name="department_id" id="department_id"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2" required>
                            <option value="">Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position_id" class="block text-sm font-medium text-gray-700">Position</label>
                        <select name="position_id" id="position_id"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2" required>
                            <option value="">Select Position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2">
                            <option value="active"
                                {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="terminated"
                                {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Terminated
                            </option>
                            <option value="inactive"
                                {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
                    <a href="{{ route('employees.index') }}"
                        class="ml-2 text-gray-600 hover:text-gray-900 underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
