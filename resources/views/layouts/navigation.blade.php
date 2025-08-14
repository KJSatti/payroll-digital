<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex sm:space-x-8 sm:ml-10 items-center">
                    <!-- Dashboard Link -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="mr-[50px]">
                        Dashboard
                    </x-nav-link>

                    <!-- Dropdowns -->
                    @foreach ([
                        'Users' => [
                            ['label' => 'Users', 'route' => 'users.index'],
                            ['label' => 'Roles', 'route' => 'roles.index'],
                            ['label' => 'Permissions', 'route' => 'permissions.index'],
                        ],
                        'Lookup' => [
                            ['label' => 'Departments', 'route' => 'departments.index'],
                            ['label' => 'Positions', 'route' => 'positions.index'],
                            ['label' => 'Benefit Types', 'route' => 'benefit-types.index'],
                            ['label' => 'Deduction Types', 'route' => 'deduction-types.index'],
                            ['label' => 'Leave Types', 'route' => 'leave-types.index'],
                            ['label' => 'Payment Methods', 'route' => 'payment-methods.index'],
                        ],
                        'Employees' => [
                            ['label' => 'Employees', 'route' => 'employees.index'],
                            ['label' => 'Benefits', 'route' => 'benefits.index'],
                            ['label' => 'Deductions', 'route' => 'deductions.index'],
                        ],
                        'Payroll' => [
                            ['label' => 'Salaries', 'route' => 'salaries.index'],
                            ['label' => 'Attendance', 'route' => 'attendences.index'],
                            ['label' => 'Taxes', 'route' => 'taxes.index'],
                            ['label' => 'Payments', 'route' => 'payments.index'],
                        ]
                    ] as $title => $items)
                        <div x-data="{ open: false }" class="relative mr-2">
                            <button @click="open = !open" type="button"
                                class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ __($title) }}
                                <x-heroicon-o-chevron-down class="w-3 h-3 mt-1 ml-1" />
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute left-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <ul class="py-2 text-sm text-gray-700">
                                    @foreach ($items as $item)
                                        <li>
                                            <a href="{{ route($item['route']) }}"
                                                class="block px-4 py-2 hover:bg-gray-100">
                                                {{ $item['label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-500" />
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none rounded-md">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="open" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Mobile Dropdowns -->
            @foreach ([
                'User Management' => [
                    ['label' => 'Users', 'route' => 'users.index'],
                    ['label' => 'Roles', 'route' => 'roles.index'],
                    ['label' => 'Permissions', 'route' => 'permissions.index'],
                ],
                'Lookup Data' => [
                    ['label' => 'Departments', 'route' => 'departments.index'],
                    ['label' => 'Positions', 'route' => 'positions.index'],
                    ['label' => 'Benefit Types', 'route' => 'benefit-types.index'],
                    ['label' => 'Deduction Types', 'route' => 'deduction-types.index'],
                    ['label' => 'Leave Types', 'route' => 'leave-types.index'],
                    ['label' => 'Payment Methods', 'route' => 'payment-methods.index'],
                ],
                'Employee Management' => [
                    ['label' => 'Employees', 'route' => 'employees.index'],
                    ['label' => 'Benefits', 'route' => 'benefits.index'],
                    ['label' => 'Deductions', 'route' => 'deductions.index'],
                ],
                'Payroll' => [
                    ['label' => 'Salaries', 'route' => 'salaries.index'],
                    ['label' => 'Attendance', 'route' => 'attendences.index'],
                    ['label' => 'Taxes', 'route' => 'taxes.index'],
                    ['label' => 'Payments', 'route' => 'payments.index'],
                ]
            ] as $title => $items)
                <div x-data="{ open: false }" class="border-t border-gray-200">
                    <button @click="open = !open"
                        class="w-full text-left px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                        {{ __($title) }}
                    </button>
                    <div x-show="open" class="pl-4">
                        @foreach ($items as $item)
                            <x-responsive-nav-link :href="route($item['route'])">
                                {{ $item['label'] }}
                            </x-responsive-nav-link>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>