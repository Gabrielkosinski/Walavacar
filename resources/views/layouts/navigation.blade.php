<nav x-data="{ open: false }" class="bg-gray-900 border-b border-red-600">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <iconify-icon icon="lucide:droplets" class="text-2xl text-red-500 mr-2"></iconify-icon>
                        <span class="wa-brand-text font-bold text-lg">WA Auto Premier</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-red-400">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('atendimentos.index')" :active="request()->routeIs('atendimentos.*')" class="text-gray-300 hover:text-red-400">
                        {{ __('Atendimentos') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" class="text-gray-300 hover:text-red-400">
                        {{ __('Clientes') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('servicos.index')" :active="request()->routeIs('servicos.*')" class="text-gray-300 hover:text-red-400">
                        {{ __('Serviços') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('relatorios.index')" :active="request()->routeIs('relatorios.*')" class="text-gray-300 hover:text-red-400">
                        {{ __('Relatórios') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('despesas.index')" :active="request()->routeIs('despesas.*')" class="text-gray-300 hover:text-red-400">
                        {{ __('Despesas') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-white hover:bg-red-600 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-300 hover:text-red-400 hover:bg-gray-800">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-gray-300 hover:text-red-400 hover:bg-gray-800">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-red-400 hover:bg-gray-700">
                <iconify-icon icon="lucide:home" class="mr-2"></iconify-icon>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('atendimentos.index')" :active="request()->routeIs('atendimentos.*')" class="text-gray-300 hover:text-red-400 hover:bg-gray-700">
                <iconify-icon icon="lucide:clipboard-list" class="mr-2"></iconify-icon>
                {{ __('Atendimentos') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" class="text-gray-300 hover:text-red-400 hover:bg-gray-700">
                <iconify-icon icon="lucide:users" class="mr-2"></iconify-icon>
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('servicos.index')" :active="request()->routeIs('servicos.*')" class="text-gray-300 hover:text-red-400 hover:bg-gray-700">
                <iconify-icon icon="lucide:droplets" class="mr-2"></iconify-icon>
                {{ __('Serviços') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('relatorios.index')" :active="request()->routeIs('relatorios.*')" class="text-gray-300 hover:text-red-400 hover:bg-gray-700">
                <iconify-icon icon="lucide:bar-chart-3" class="mr-2"></iconify-icon>
                {{ __('Relatórios') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('despesas.index')" :active="request()->routeIs('despesas.*')" class="text-gray-300 hover:text-red-400 hover:bg-gray-700">
                <iconify-icon icon="lucide:credit-card" class="mr-2"></iconify-icon>
                {{ __('Despesas') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
