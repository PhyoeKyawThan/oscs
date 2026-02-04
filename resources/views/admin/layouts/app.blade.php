<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
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
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            overflow-x: hidden;
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
            width: 70px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
            padding-bottom: 60px;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 999;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-radius: 12px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .stat-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
        
        .btn {
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            border-bottom: 2px solid #dee2e6;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .pagination .page-link {
            border-radius: 6px;
            margin: 0 3px;
        }
        
        .alert {
            border: none;
            border-radius: 10px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e1e5eb;
            padding: 10px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        
        .nav-tabs .nav-link {
            border-radius: 8px 8px 0 0;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: white;
            border-top: 1px solid #e1e5eb;
            padding: 15px 0;
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        .footer.expanded {
            margin-left: 70px;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            border-radius: 6px;
            margin: 2px 10px;
            width: auto;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.collapsed {
                margin-left: 0;
                width: 250px;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .footer {
                margin-left: 0;
            }
            
            .navbar-toggler {
                order: -1;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        
        <!-- Main Content -->
        <div class="main-content w-100" id="mainContent">
            <!-- Header -->
            @include('admin.layouts.header')
            
            <!-- Page Content -->
            <div class="container-fluid p-4">
                <!-- Page Title -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="mb-1 fw-bold">@yield('title')</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                    @hasSection('actions')
                        <div class="d-flex gap-2">
                            @yield('actions')
                        </div>
                    @endif
                </div>
                
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
            
            <!-- Footer -->
            @include('admin.layouts.footer')
        </div>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
            
            // Initialize DataTables
            $('.data-table').DataTable({
                pageLength: 25,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "_MENU_ records per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "No records available",
                    infoFiltered: "(filtered from _MAX_ total records)"
                }
            });
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