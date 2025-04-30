@extends('genview')
@section('title', 'Performance Reports')

@section('content')
    <style>
        .reports-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 30px;
            margin-bottom: 30px;
            border: none;
        }

        .section-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h4 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-header h4 i {
            margin-right: 10px;
        }

        .report-filters {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .filter-group {
            margin-right: 20px;
            margin-bottom: 10px;
        }

        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        .btn-apply {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-apply:hover {
            background-color: #3a56c4;
            transform: translateY(-2px);
        }

        .btn-export {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .btn-export:hover {
            background-color: #f0f5ff;
        }

        .report-card {
            border-radius: 12px;
            border: 1px solid #eee;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .report-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .chart-container {
            height: 300px;
            margin-bottom: 20px;
            position: relative;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary-color);
        }

        .stat-card h5 {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .stat-card h3 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0;
        }

        .stat-card.completed {
            border-left-color: #28a745;
        }

        .stat-card.overdue {
            border-left-color: #dc3545;
        }

        .stat-card.pending {
            border-left-color: #ffc107;
        }

        .stat-card.in-progress {
            border-left-color: #17a2b8;
        }

        .performance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .performance-table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }

        .performance-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 0.85rem;
        }

        .performance-table tr:hover {
            background-color: #f8f9fa;
        }

        .progress-cell {
            display: flex;
            align-items: center;
        }

        .progress-bar-container {
            flex-grow: 1;
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            margin-right: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 4px;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: #e8f5e9;
            color: #28a745;
        }

        .badge-warning {
            background-color: #fff8e1;
            color: #ffc107;
        }

        .badge-danger {
            background-color: #ffebee;
            color: #dc3545;
        }

        .badge-info {
            background-color: #e3f2fd;
            color: #17a2b8;
        }

        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 15px;
        }

        .empty-state h5 {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #adb5bd;
            max-width: 400px;
            margin: 0 auto;
        }
    </style>

    <div class="main-content">
        @if(session('success'))
            <div class="notification-toast alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session(key: 'success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
                    <img src="{{ Auth::user()->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" alt="User Profile" class="rounded-circle"
                        width="40">
                    <span>{{ ucwords(strtolower(Auth::user()->name)) }}</span>
                </div>
            </div>
        </div>

        <!-- Reports Content -->
        <div class="reports-container">
            <div class="section-header">
                <h4><i class="fas fa-chart-bar"></i> Performance Reports</h4>
                <div>
                    <button class="btn btn-export me-2">
                        <i class="fas fa-file-export me-2"></i> Export
                    </button>
                    <button class="btn btn-apply">
                        <i class="fas fa-filter me-2"></i> Apply Filters
                    </button>
                </div>
            </div>

            <div class="report-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <div class="filter-label">Date Range</div>
                        <select class="form-select">
                            <option>Last 7 Days</option>
                            <option selected>Last 30 Days</option>
                            <option>Last Quarter</option>
                            <option>Last Year</option>
                            <option>Custom Range</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Department</div>
                        <select class="form-select">
                            <option>All Departments</option>
                            <option>Finance</option>
                            <option>Marketing</option>
                            <option>IT</option>
                            <option>Operations</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Priority</div>
                        <select class="form-select">
                            <option>All Priorities</option>
                            <option>High</option>
                            <option>Medium</option>
                            <option>Low</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-label">Status</div>
                        <select class="form-select">
                            <option>All Statuses</option>
                            <option>Completed</option>
                            <option>In Progress</option>
                            <option>Pending</option>
                            <option>Overdue</option>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group" style="width: 300px;">
                        <div class="filter-label">Employee</div>
                        <select class="form-select">
                            <option>All Employees</option>
                            <option>Sarah Johnson</option>
                            <option>Michael Chen</option>
                            <option>Emily Wong</option>
                            <option>Robert Kim</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-title">Task Completion Overview</div>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-2">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-file-csv me-1"></i> CSV
                        </button>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card completed">
                        <h5>Completed Tasks</h5>
                        <h3>142</h3>
                        <small>+12% from last month</small>
                    </div>
                    <div class="stat-card overdue">
                        <h5>Overdue Tasks</h5>
                        <h3>18</h3>
                        <small>-3% from last month</small>
                    </div>
                    <div class="stat-card pending">
                        <h5>Pending Approval</h5>
                        <h3>7</h3>
                        <small>+2 from last week</small>
                    </div>
                    <div class="stat-card in-progress">
                        <h5>In Progress</h5>
                        <h3>34</h3>
                        <small>5 due this week</small>
                    </div>
                </div>
                <div class="chart-container">
                    <!-- Chart would be rendered here with Chart.js or similar -->
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; background-color: #f8f9fa; border-radius: 8px;">
                        <i class="fas fa-chart-bar fa-3x text-muted"></i>
                        <p class="ms-3 text-muted">Task completion chart would display here</p>
                    </div>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-title">Employee Performance</div>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-2">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-file-csv me-1"></i> CSV
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="performance-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Tasks Assigned</th>
                                <th>Completed</th>
                                <th>Overdue</th>
                                <th>Completion Rate</th>
                                <th>Avg. Time</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+J" alt="Sarah Johnson" class="rounded-circle me-2" width="32" height="32">
                                        Sarah Johnson
                                    </div>
                                </td>
                                <td>Finance</td>
                                <td>28</td>
                                <td>25</td>
                                <td>1</td>
                                <td>
                                    <div class="progress-cell">
                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: 89%"></div>
                                        </div>
                                        <span>89%</span>
                                    </div>
                                </td>
                                <td>2.3 days</td>
                                <td><span class="badge badge-success">Excellent</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Michael+C" alt="Michael Chen" class="rounded-circle me-2" width="32" height="32">
                                        Michael Chen
                                    </div>
                                </td>
                                <td>Marketing</td>
                                <td>32</td>
                                <td>28</td>
                                <td>2</td>
                                <td>
                                    <div class="progress-cell">
                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: 88%"></div>
                                        </div>
                                        <span>88%</span>
                                    </div>
                                </td>
                                <td>2.5 days</td>
                                <td><span class="badge badge-success">Excellent</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Emily+W" alt="Emily Wong" class="rounded-circle me-2" width="32" height="32">
                                        Emily Wong
                                    </div>
                                </td>
                                <td>Marketing</td>
                                <td>24</td>
                                <td>20</td>
                                <td>3</td>
                                <td>
                                    <div class="progress-cell">
                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: 83%"></div>
                                        </div>
                                        <span>83%</span>
                                    </div>
                                </td>
                                <td>3.1 days</td>
                                <td><span class="badge badge-info">Good</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Robert+K" alt="Robert Kim" class="rounded-circle me-2" width="32" height="32">
                                        Robert Kim
                                    </div>
                                </td>
                                <td>IT</td>
                                <td>18</td>
                                <td>15</td>
                                <td>2</td>
                                <td>
                                    <div class="progress-cell">
                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: 83%"></div>
                                        </div>
                                        <span>83%</span>
                                    </div>
                                </td>
                                <td>3.2 days</td>
                                <td><span class="badge badge-info">Good</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=David+M" alt="David Miller" class="rounded-circle me-2" width="32" height="32">
                                        David Miller
                                    </div>
                                </td>
                                <td>Operations</td>
                                <td>22</td>
                                <td>17</td>
                                <td>4</td>
                                <td>
                                    <div class="progress-cell">
                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: 77%"></div>
                                        </div>
                                        <span>77%</span>
                                    </div>
                                </td>
                                <td>3.8 days</td>
                                <td><span class="badge badge-warning">Average</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="report-card">
                <div class="report-header">
                    <div class="report-title">Task Status Distribution</div>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-2">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-file-csv me-1"></i> CSV
                        </button>
                    </div>
                </div>
                <div class="chart-container">
                    <!-- Chart would be rendered here with Chart.js or similar -->
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; background-color: #f8f9fa; border-radius: 8px;">
                        <i class="fas fa-chart-pie fa-3x text-muted"></i>
                        <p class="ms-3 text-muted">Task distribution pie chart would display here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide notification after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const notification = document.querySelector('.notification-toast');
            if (notification) {
                setTimeout(() => {
                    notification.classList.remove('show');
                    notification.classList.add('hide');
                }, 5000);
            }

            // In a real application, you would initialize charts here
            // Example with Chart.js:
        
            const ctx = document.getElementById('taskCompletionChart').getContext('2d');
            const taskCompletionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Completed Tasks',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: 'rgba(67, 97, 238, 0.8)'
                    }, {
                        label: 'Overdue Tasks',
                        data: [2, 3, 1, 4, 1, 2],
                        backgroundColor: 'rgba(220, 53, 69, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
    
        });
    </script>
@endsection