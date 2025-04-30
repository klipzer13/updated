<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4b6cb7;
            --secondary-color: #182848;
            --sidebar-width: 250px;
        }

        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .sidebar-menu {
            padding: 0;
            list-style: none;
        }

        .sidebar-menu li {
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .sidebar-menu li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar-menu li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu li.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .stat-card {
            text-align: center;
            padding: 20px;
        }

        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .stat-card .stat-label {
            color: #6c757d;
        }

        /* Task Styles */
        .task-item {
            padding: 15px;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 10px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .task-item.high-priority {
            border-left-color: #dc3545;
        }

        .task-item.medium-priority {
            border-left-color: #ffc107;
        }

        .task-item.low-priority {
            border-left-color: #28a745;
        }

        .task-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .task-actions {
            margin-top: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Top Navigation */
        .top-nav {
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                /* left: -var(--sidebar-width); */
                left: -250px;

            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.active {
                margin-left: var(--sidebar-width);
            }

            .sidebar-collapse-btn {
                display: block !important;
            }
        }



        .sidebar-collapse-btn {
            display: none;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        /* Task Status Badges */
        .badge-pending {
            background-color: #6c757d;
            color: white;
        }

        .badge-in-progress {
            background-color: #17a2b8;
            color: white;
        }

        .badge-completed {
            background-color: #28a745;
            color: white;
        }

        :root {
            --primary-color: #4b6cb7;
            --secondary-color: #182848;
        }

        .members-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .members-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .members-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .members-table thead th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #eee;
        }

        .members-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .members-table tbody tr:last-child td {
            border-bottom: none;
        }

        .members-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .member-info {
            display: flex;
            align-items: center;
        }

        .status-active {
            color: #28a745;
            background-color: #e6f7ee;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .status-inactive {
            color: #dc3545;
            background-color: #ffebee;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            margin-right: 5px;
        }

        .action-btn.edit {
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .action-btn.delete {
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .action-btn.view {
            color: #6c757d;
            border: 1px solid #6c757d;
        }

        .add-member-btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }

        .add-member-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-member {
            position: relative;
            width: 300px;
        }

        .search-member input {
            padding-left: 35px;
        }

        .search-member i {
            position: absolute;
            left: 12px;
            top: 10px;
            color: #6c757d;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .modal-footer {
            border-top: 1px solid #eee;
        }

        /* View Modal Styles */
        .member-avatar-lg {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .member-detail-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .member-detail-row:last-child {
            border-bottom: none;
        }

        .member-detail-label {
            font-weight: 600;
            color: #6c757d;
            min-width: 150px;
            display: inline-block;
        }

        .member-detail-value {
            color: #495057;
        }

        .member-status {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .status-active {
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }

        .status-inactive {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }

        @media (max-width: 768px) {
            .members-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-member {
                width: 100%;
                margin-top: 15px;
            }

            .members-table {
                display: block;
                overflow-x: auto;
            }

            .member-avatar-lg {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-tasks me-2"></i>TaskNotify</h4>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('task.dashboard') ? 'active' : '' }}">
            <a href="{{ route('task.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li class="{{ request()->routeIs('task.index') ? 'active' : '' }}">
            <a href="{{ route('task.index') }}"><i class="fas fa-tasks"></i> All Tasks</a>
            </li>
            <li class="{{ request()->routeIs('assign.task') ? 'active' : '' }}">
            <a href="{{ route('assign.task') }}"><i class="fas fa-tasks"></i> Assign Tasks</a>
            </li>
            <li class="{{ request()->routeIs('task.members') ? 'active' : '' }}">
            <a href="{{ route('task.members') }}"><i class="fas fa-users"></i> Members</a>
            </li>
            <li class="{{ request()->routeIs('task.report') ? 'active' : '' }}">
            <a href="#"><i class="fas fa-cog"></i> Reports</a>
            </li>
            <li class="{{ request()->routeIs('task.document') ? 'active' : '' }}">
            <a href="{{ route('task.document') }}"><i class="fas fa-file-alt"></i> Documents</a>
            </li>
            <li class="">
            <a href="#"><i class="fas fa-history"></i> Audit Logs</a>
            </li>
            <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="text-white text-decoration-none">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
            </li>
        </ul>
    </div>


    @yield('content')
    <!-- Main Content -->

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
    </script>
</body>

</html>