<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --info-color: #43aa8b;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            /* REMOVED overflow-x: hidden */
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
            padding-bottom: 60px;
            /* ADDED: proper width calculation */
            width: calc(100% - var(--sidebar-width));
        }
        
        .main-content.expanded {
            margin-left: 80px;
            width: calc(100% - 80px);
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            /* CHANGED: better footer positioning */
            left: var(--sidebar-width);
            right: 0;
            background: white;
            border-top: 1px solid #e1e5eb;
            padding: 15px 0;
            transition: all 0.3s;
            z-index: 999;
        }
        
        .footer.expanded {
            left: 80px;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Custom select2 styles for Tailwind */
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        
        /* DataTables Tailwind overrides */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none !important;
            ring: 2px !important;
            border-color: var(--primary-color) !important;
        }
        
        /* ADDED: Responsive table container */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }
            
            .sidebar.collapsed {
                margin-left: 0;
                width: 280px;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .footer {
                left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
                width: 100%;
            }
            
            .footer.expanded {
                left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased">
    <div class="flex">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Header -->
            @include('admin.layouts.header')
            
            <!-- Page Content -->
            <div class="px-4 sm:px-6 lg:px-8 py-8">
                <!-- Page Title -->
                <div class="sm:flex sm:items-center sm:justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">@yield('title')</h3>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-2 text-sm">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Dashboard</a>
                                </li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                    @hasSection('actions')
                        <div class="mt-4 sm:mt-0 flex gap-2">
                            @yield('actions')
                        </div>
                    @endif
                </div>
                
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                            <button type="button" class="ml-auto text-green-400 hover:text-green-600" onclick="this.closest('div[role=alert]').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                            <button type="button" class="ml-auto text-red-400 hover:text-red-600" onclick="this.closest('div[role=alert]').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg" role="alert">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-2 mt-1"></i>
                            <div>
                                <p class="text-sm text-red-700 font-medium">Please fix the following errors:</p>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="ml-auto text-red-400 hover:text-red-600" onclick="this.closest('div[role=alert]').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                <!-- Main Content -->
                <main>
                    @yield('content')
                </main>
            </div>
            
            <!-- Footer -->
            @include('admin.layouts.footer')
        </div>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js (if you're using it) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const footer = document.querySelector('.footer');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            footer.classList.toggle('expanded');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            // Trigger resize event for DataTables
            window.dispatchEvent(new Event('resize'));
        }
        
        // Load sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const footer = document.querySelector('.footer');
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                footer.classList.add('expanded');
            }
            
            // Initialize Select2
            if (typeof $ !== 'undefined') {
                $('.select2').select2({
                    theme: 'classic',
                    width: '100%'
                });
            }
            
            // Initialize DataTables with responsive option
            if (typeof $.fn.DataTable !== 'undefined') {
                $('.data-table').DataTable({
                    pageLength: 25,
                    responsive: true,
                    scrollX: true, // Enable horizontal scroll for DataTables
                    language: {
                        search: "",
                        searchPlaceholder: "Search...",
                        lengthMenu: "_MENU_ records per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ records",
                        infoEmpty: "No records available",
                        infoFiltered: "(filtered from _MAX_ total records)"
                    }
                });
            }
        });
        
        // Confirm delete
        function confirmDelete(event, formId) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                document.getElementById(formId).submit();
            }
        }
        
        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        }
    </script>
    
    @stack('scripts')
</body>
</html>