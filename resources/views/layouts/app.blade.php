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

        <!-- DataTables -->
        <link href="https://cdn.datatables.net/3.0.1/css/dataTables.dataTables.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/3.0.1/js/dataTables.min.js"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            
            <!-- Search Bar + Cart Button (Global) -->
            <div class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-end gap-3">
                    <!-- Search Box (no functionality yet) -->
                    <form action="{{ route('dashboard') }}" method="GET" class="w-2/3">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search items..."
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </form>

                    <!-- Cart Button -->
                    <a href="/cart"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md whitespace-nowrap">
                        🛒 Cart
                    </a>
                </div>
            </div>

            <!-- Page Heading -->

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- SweetAlert2: flash toasts + confirm forms -->
        <script>
            // Flash toasts
            @if(session('success'))
                Swal.fire({ toast: true, position: 'top-end', icon: 'success',
                    title: @json(session('success')), showConfirmButton: false, timer: 3000, timerProgressBar: true });
            @endif
            @if(session('error'))
                Swal.fire({ toast: true, position: 'top-end', icon: 'error',
                    title: @json(session('error')), showConfirmButton: false, timer: 4000, timerProgressBar: true });
            @endif

            // Intercept any form with data-confirm attribute
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('form[data-confirm]').forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const message = form.dataset.confirm || 'Are you sure?';
                        const danger  = form.dataset.danger === 'true';
                        Swal.fire({
                            title: 'Confirm',
                            text: message,
                            icon: danger ? 'warning' : 'question',
                            showCancelButton: true,
                            confirmButtonColor: danger ? '#dc2626' : '#2563eb',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, proceed',
                        }).then(function (result) {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });
            });
        </script>
    </body>
</html>
