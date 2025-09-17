<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('shops.index')" :active="request()->routeIs('shops.*')">
                        {{ __('Kedai') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        {{ __('Acara') }}
                    </x-nav-link>
                    <x-nav-link :href="route('forum-posts.index')" :active="request()->routeIs('forum-posts.*')">
                        {{ __('Forum') }}
                    </x-nav-link>
                    <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                        {{ __('Notifikasi') }}
                    </x-nav-link>
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        {{ __('Laporan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('promotions.public')" :active="request()->routeIs('promotions.public')">
                        {{ __('Promosi Umum') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Notifications & Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications Dropdown -->
                <div x-data="notificationDropdown()">
                    <div class="relative">
                        <button @click="toggleDropdown()" class="relative inline-flex items-center p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19H6.5a2.5 2.5 0 01-2.5-2.5v-8a6 6 0 1112 0v8a2.5 2.5 0 01-2.5 2.5z"/>
                            </svg>
                            <!-- Notification Badge -->
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" @click.away="close()" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 border border-gray-200 dark:border-gray-700">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notifikasi</h3>
                                    <button @click="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                        Tandai Semua Dibaca
                                    </button>
                                </div>
                                
                                <div class="max-h-96 overflow-y-auto">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div :class="{'bg-blue-50 dark:bg-blue-900/20': notification.read_at === null}" class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" @click="markAsRead(notification.id)">
                                            <p class="text-sm text-gray-900 dark:text-gray-100" x-text="notification.data.title"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="notification.data.message"></p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="formatDate(notification.created_at)"></p>
                                        </div>
                                    </template>
                                    
                                    <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada notifikasi
                                    </div>
                                </div>
                                
                                <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                        Lihat Semua Notifikasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')">
                                <i class="fas fa-tachometer-alt mr-1"></i>
                                {{ __('Admin Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.users.index')">
                                <i class="fas fa-users mr-1"></i>
                                {{ __('User Management') }}
                            </x-dropdown-link>
                        @endif

                        @if(Auth::user()->role === 'vendor')
                            <x-dropdown-link :href="route('vendor.dashboard')">
                                <i class="fas fa-store mr-1"></i>
                                {{ __('Vendor Dashboard') }}
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('notification-preferences.index')">
                            <i class="fas fa-bell mr-1"></i>
                            {{ __('Tetapan Notifikasi') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('shops.index')" :active="request()->routeIs('shops.*')">
                {{ __('Kedai') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                {{ __('Acara') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('forum-posts.index')" :active="request()->routeIs('forum-posts.*')">
                {{ __('Forum') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                {{ __('Notifikasi') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('promotions.public')" :active="request()->routeIs('promotions.public')">
                {{ __('Promosi Umum') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
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
