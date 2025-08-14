<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <div class="bg-white border border-gray-200 shadow rounded-lg p-4">
            <div class="overflow-x-auto text-left"> {{-- forces left text in scroll area --}}
                <table class="w-full table-auto text-sm text-left"> {{-- enforce left alignment globally --}}
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Full Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Department</th>
                            <th class="px-4 py-2 text-left">Position</th>
                            <th class="px-4 py-2 text-left">Roles</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse ($users as $user)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">{{ $user->department->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $user->position->title ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @foreach ($user->getRoleNames() as $role)
                                        <span>{{ $role }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2">
                                    <span
                                        class="inline-block px-2 py-1 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ URL::to('profile') }}" class="text-blue-600 hover:underline">Edit</a>
                                    <a href="#" class="text-blue-600 hover:underline ml-2"
                                        onclick="openModal({{ $user->id }})">Assign Role</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal start -->
    <div id="assignRoleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">

        <div class="bg-white rounded-lg shadow-lg max-w-md mx-4 p-6 relative">

            <h2 class="text-lg font-bold mb-4 text-center">Assign Role to {{ $user->name }}</h2>

            <form id="assignRoleForm" method="POST">
                @csrf
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700">Select Role:</label>
                    <select name="role" id="role" class="w-full border border-gray-300 rounded px-3 py-2 mt-1"
                        required>
                        <option value="">-- Select Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="submit" class="px-4 py-2 rounded bg-black text-white hover:bg-gray-900 mt-2 mr-2">
                        Assign
                    </button>
                    <button type="button" onclick="document.getElementById('assignRoleModal').classList.add('hidden')"
                        class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 mt-2">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal end -->
</x-app-layout>
<script>
    function openModal(userId) {
        const modal = document.getElementById('assignRoleModal');
        const form = document.getElementById('assignRoleForm');

        const routeTemplate = "{{ route('users.assign.role', ':id') }}";
        const newAction = routeTemplate.replace(':id', userId);

        form.action = newAction;

        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('assignRoleModal').classList.add('hidden');
    }
</script>
