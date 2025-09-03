<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Notification Scripts -->
        <script>
            function notificationDropdown() {
                return {
                    open: false,
                    notifications: [],
                    unreadCount: 0,

                    init() {
                        this.loadNotifications();
                        this.loadUnreadCount();
                        
                        // Poll for new notifications every 30 seconds
                        setInterval(() => {
                            this.loadUnreadCount();
                        }, 30000);
                    },

                    toggleDropdown() {
                        this.open = !this.open;
                        if (this.open) {
                            this.loadNotifications();
                        }
                    },

                    close() {
                        this.open = false;
                    },

                    async loadNotifications() {
                        try {
                            const response = await fetch('/notifications/recent', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                this.notifications = data.notifications || [];
                            }
                        } catch (error) {
                            console.error('Error loading notifications:', error);
                        }
                    },

                    async loadUnreadCount() {
                        try {
                            const response = await fetch('/notifications/unread-count', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                this.unreadCount = data.count || 0;
                            }
                        } catch (error) {
                            console.error('Error loading unread count:', error);
                        }
                    },

                    async markAsRead(notificationId) {
                        try {
                            const response = await fetch(`/notifications/${notificationId}/read`, {
                                method: 'PATCH',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            
                            if (response.ok) {
                                // Update the notification in the list
                                const notification = this.notifications.find(n => n.id === notificationId);
                                if (notification) {
                                    notification.read_at = new Date().toISOString();
                                }
                                this.loadUnreadCount();
                            }
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                        }
                    },

                    async markAllAsRead() {
                        try {
                            const response = await fetch('/notifications/read-all', {
                                method: 'PATCH',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            
                            if (response.ok) {
                                // Mark all notifications as read
                                this.notifications.forEach(notification => {
                                    notification.read_at = new Date().toISOString();
                                });
                                this.unreadCount = 0;
                            }
                        } catch (error) {
                            console.error('Error marking all notifications as read:', error);
                        }
                    },

                    formatDate(dateString) {
                        const date = new Date(dateString);
                        const now = new Date();
                        const diffInMinutes = Math.floor((now - date) / (1000 * 60));
                        
                        if (diffInMinutes < 1) {
                            return 'Baru saja';
                        } else if (diffInMinutes < 60) {
                            return `${diffInMinutes} menit yang lalu`;
                        } else if (diffInMinutes < 1440) {
                            const hours = Math.floor(diffInMinutes / 60);
                            return `${hours} jam yang lalu`;
                        } else {
                            const days = Math.floor(diffInMinutes / 1440);
                            return `${days} hari yang lalu`;
                        }
                    }
                }
            }
        </script>
    </body>
</html>
