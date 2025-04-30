@extends('genview')
@section('title', 'Reports')

@section('content')
    <style>
        :root {
            --primary-color: #4b6cb7;
            --secondary-color: #182848;
        }

        .reports-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .report-filters {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        .report-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            height: 100%;
        }

        .report-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .export-btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 8px 20px;
            font-weight: 600;
            color: white;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 15px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .report-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .report-table thead th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #eee;
        }

        .report-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .report-table tbody tr:last-child td {
            border-bottom: none;
        }

        .report-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .completion-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .completion-badge.high {
            background-color: #e6f7ee;
            color: #28a745;
        }

        .completion-badge.medium {
            background-color: #fff8e1;
            color: #ffc107;
        }

        .completion-badge.low {
            background-color: #ffebee;
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .report-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .report-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>

    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav d-flex justify-content-between align-items-center mb-4">
            <button class="sidebar-collapse-btn" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex align-items-center">
                <div class="position-relative me-3">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-profile">
                    <img src="{{ Auth::user()->avatar_url ?? asset('storage/profile/avatars/profile.png') }}"
                        alt="User Profile" class="rounded-circle" width="40">
                    <span>{{ ucwords(strtolower(Auth::user()->name)) }}</span>
                </div>
            </div>
        </div>

        <!-- Reports Container -->
        <div class="reports-container">
            <!-- Reports Header -->
            <div class="report-header">
                <h4><i class="fas fa-chart-line me-2"></i> Project Reports</h4>
                <button class="btn export-btn">
                    <i class="fas fa-file-export me-2"></i> Export Report
                </button>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">48</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Pending Tasks</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">24</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">75%</div>
                    <div class="stat-label">Completion Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">5</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="report-filters">
                <div class="row">
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label>Date Range</label>
                            <select class="form-select">
                                <option>Last 7 Days</option>
                                <option selected>Last 30 Days</option>
                                <option>Last Quarter</option>
                                <option>Custom Range</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label>Project</label>
                            <select class="form-select">
                                <option>All Projects</option>
                                <option>Website Redesign</option>
                                <option selected>Mobile App</option>
                                <option>API Integration</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label>Team Member</label>
                            <select class="form-select">
                                <option>All Members</option>
                                <option>Sarah Johnson</option>
                                <option>Mike Chen</option>
                                <option>Emma Wilson</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label>Status</label>
                            <select class="form-select">
                                <option>All Statuses</option>
                                <option>Pending</option>
                                <option selected>In Progress</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="report-card">
                        <div class="report-card-header">
                            <h5>Task Completion</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Export as PNG</a></li>
                                    <li><a class="dropdown-item" href="#">Export as CSV</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="chart-container">
                            <!-- This would be replaced with an actual chart (Chart.js, etc.) -->
                            <div
                                style="height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 5px;">
                                <p class="text-muted">Task Completion Chart</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <div class="report-card-header">
                            <h5>Task Distribution</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Export as PNG</a></li>
                                    <li><a class="dropdown-item" href="#">Export as CSV</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="chart-container">
                            <!-- This would be replaced with an actual chart (Chart.js, etc.) -->
                            <div
                                style="height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 5px;">
                                <p class="text-muted">Task Distribution Chart</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Report Table -->
            <div class="report-card">
                <div class="report-card-header">
                    <h5>Task Performance Details</h5>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assignee</th>
                                <th>Project</th>
                                <th>Priority</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th>Completion</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Implement new notification system</td>
                                <td>Mike Chen</td>
                                <td>Mobile App</td>
                                <td>High</td>
                                <td>Jun 1, 2023</td>
                                <td>Jun 20, 2023</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                    </div>
                                </td>
                                <td><span class="completion-badge high">On Track</span></td>
                            </tr>
                            <tr>
                                <td>Update documentation</td>
                                <td>Emma Wilson</td>
                                <td>API Integration</td>
                                <td>Medium</td>
                                <td>May 25, 2023</td>
                                <td>Jun 15, 2023</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                                    </div>
                                </td>
                                <td><span class="completion-badge high">Completed</span></td>
                            </tr>
                            <tr>
                                <td>Fix critical security vulnerability</td>
                                <td>Sarah Johnson</td>
                                <td>Website Redesign</td>
                                <td>High</td>
                                <td>Jun 10, 2023</td>
                                <td>Jun 12, 2023</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 25%"></div>
                                    </div>
                                </td>
                                <td><span class="completion-badge low">Behind</span></td>
                            </tr>
                            <tr>
                                <td>Create user dashboard</td>
                                <td>Sarah Johnson</td>
                                <td>Mobile App</td>
                                <td>Medium</td>
                                <td>Jun 5, 2023</td>
                                <td>Jun 25, 2023</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 50%"></div>
                                    </div>
                                </td>
                                <td><span class="completion-badge medium">At Risk</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Chart.js for actual charts (would be included in your layout) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // This is where you would initialize your actual charts
        document.addEventListener('DOMContentLoaded', function () {
            // Example for Task Completion chart
            const completionCtx = document.createElement('canvas');
            completionCtx.style.width = '100%';
            completionCtx.style.height = '100%';
            const completionContainer = document.querySelector('.chart-container:first-child div');
            completionContainer.innerHTML = '';
            completionContainer.appendChild(completionCtx);

            new Chart(completionCtx, {
                type: 'bar',
                data: {
                    labels: ['Completed', 'In Progress', 'Pending', 'Overdue'],
                    datasets: [{
                        label: 'Tasks',
                        data: [12, 24, 12, 5],
                        backgroundColor: [
                            '#28a745',
                            '#17a2b8',
                            '#6c757d',
                            '#dc3545'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Example for Task Distribution chart
            const distributionCtx = document.createElement('canvas');
            distributionCtx.style.width = '100%';
            distributionCtx.style.height = '100%';
            const distributionContainer = document.querySelector('.chart-container:nth-child(2) div');
            distributionContainer.innerHTML = '';
            distributionContainer.appendChild(distributionCtx);

            new Chart(distributionCtx, {
                type: 'pie',
                data: {
                    labels: ['Website Redesign', 'Mobile App', 'API Integration', 'Marketing'],
                    datasets: [{
                        data: [15, 20, 10, 5],
                        backgroundColor: [
                            '#4b6cb7',
                            '#6c757d',
                            '#17a2b8',
                            '#ffc107'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        });

        // Export functionality
        document.querySelector('.export-btn').addEventListener('click', function () {
            // This would typically generate and download a report
            console.log('Exporting report...');
            alert('Report exported successfully!');
        });
    </script>

@endsection