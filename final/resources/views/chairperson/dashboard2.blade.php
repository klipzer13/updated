@extends('chairperson.genchair')
@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 30px;
            margin-bottom: 30px;
            border: none;
        }

        .stat-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .stat-card h3 {
            font-weight: 700;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .stat-card p {
            color: #7f8c8d;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .section-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
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

        .task-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            border-left: 3px solid #eee;
        }

        .task-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .task-item.high-priority {
            border-left-color: #ff6b6b;
        }

        .task-item.medium-priority {
            border-left-color: #ffd166;
        }

        .task-item.low-priority {
            border-left-color: #06d6a0;
        }

        .task-item .task-info {
            flex: 1;
            margin-left: 15px;
        }

        .task-item .task-meta {
            display: flex;
            align-items: center;
            margin-top: 5px;
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        .task-item .task-meta span {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }

        .task-item .task-meta i {
            margin-right: 5px;
            font-size: 0.9rem;
        }

        .task-item .assignee {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 5px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .employee-card {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .employee-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .employee-card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        .employee-card .employee-info {
            flex: 1;
        }

        .employee-card .employee-stats {
            display: flex;
            margin-top: 5px;
        }

        .employee-card .stat {
            margin-right: 15px;
            font-size: 0.8rem;
            color: #7f8c8d;
        }

        .employee-card .stat i {
            margin-right: 3px;
        }

        .priority-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .priority-high {
            background-color: #ffebee;
            color: #ff6b6b;
        }

        .priority-medium {
            background-color: #fff8e1;
            color: #ffb74d;
        }

        .priority-low {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .status-approved {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        .status-rejected {
            background-color: #ffebee;
            color: #d32f2f;
        }
        .status-pending_approval {
            background-color: #f3e5f5;
            color: #8e24aa;
        }

        .status-in-progress {
            background-color: #fff3e0;
            color: #fb8c00;
        }

        .status-completed {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .status-overdue {
            background-color: #ffebee;
            color: #f44336;
        }

        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
        }
    </style>

    <div class="main-content">
        @if(session('success'))
            <div class="notification-toast alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="top-nav d-flex justify-content-between align-items-center mb-4">
            <button class="sidebar-collapse-btn d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex align-items-center">
                <div class="position-relative me-3">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-profile">
                    <img src="{{ Auth::user()->avatar_url ?? 'https://via.placeholder.com/40' }}" alt="User"
                        class="rounded-circle">
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <!-- Dashboard Overview -->
        <div class="dashboard-container mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3>{{ $totalTasks }}</h3>
                        <p>Total Tasks</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h3>{{ $overdueTasks }}</h3>
                        <p>Overdue Tasks</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3>{{ $completedTasks }}</h3>
                        <p>Completed Tasks</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>{{ $teamMembersCount }}</h3>
                        <p>Team Members</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Pending Approvals Section -->
            <div class="col-lg-6">
                <div class="dashboard-container">
                    <div class="section-header">
                        <h4><i class="fas fa-clock"></i> Pending Approvals</h4>
                    </div>

                    @foreach($pendingApprovals as $task)
                        <div class="task-item">
                            <div class="task-check">
                                <i class="fas fa-file-alt text-primary"></i>
                            </div>
                            <div class="task-info">
                                <h5>{{ $task->title }}
                                    <span class="priority-badge priority-{{ strtolower($task->priority->name) }}">
                                        {{ $task->priority->name }}
                                    </span>
                                </h5>
                                <div class="task-meta">
                                    <span><i class="fas fa-user"></i> {{ $task->creator->name }}</span>
                                    <span><i class="fas fa-calendar-alt"></i> Due: {{ $task->due_date->diffForHumans() }}</span>
                                    <span
                                        class="status-badge status-{{ str_replace(' ', '-', strtolower($task->current_status->name ?? 'No status')) }}">
                                        {{  $task->current_status->name ?? 'No status'  }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('tasks.review', $task->id) }}" class="btn btn-sm btn-outline-primary">Review</a>
                        </div>
                    @endforeach

                    <div class="text-center mt-3">
                        <a href="{{ route('tasks.pending') }}" class="btn btn-link">View All Pending Approvals</a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="col-lg-6">
                <div class="dashboard-container">
                    <div class="section-header">
                        <h4><i class="fas fa-history"></i> Recent Activity</h4>
                    </div>

                    @foreach($recentActivity as $task)
                        <div class="task-item">
                            <div class="task-check">
                                <img src="{{ $task->creator->avatar_url }}" alt="{{ $task->creator->name }}" class="assignee">
                            </div>
                            <div class="task-info">
                                <h5>{{ $task->creator->name }} updated "{{ $task->title }}"</h5>
                                <div class="task-meta">
                                    <span><i class="fas fa-clock"></i> {{ $task->updated_at->diffForHumans() }}</span>
                                    <span
                                        class="status-badge status-{{ str_replace(' ', '-', strtolower($task->current_status->name ?? 'No status')) }}">
                                        {{  $task->current_status->name ?? 'No status'  }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center mt-3">
                        <a href="{{ route('activity.index') }}" class="btn btn-link">View All Activity</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Performance Section -->
        <div class="dashboard-container mt-4">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-users"></i> Team Performance</h4>
                <div>
                    <select class="form-select form-select-sm" style="width: auto; display: inline-block;">
                        <option>This Week</option>
                        <option>This Month</option>
                        <option selected>This Quarter</option>
                    </select>
                </div>
            </div>

            <div class="row">
                @foreach($teamMembers as $member)
                    <div class="col-md-3">
                        <div class="employee-card">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}">
                            <div class="employee-info">
                                <h5>{{ $member->name }}</h5>
                                <small>{{ $member->department->name ?? 'No Department' }}</small>
                                <div class="employee-stats">
                                    <div class="stat"><i class="fas fa-check-circle text-success"></i>
                                        {{ $member->completed_tasks }}</div>
                                    <div class="stat"><i class="fas fa-exclamation-circle text-danger"></i>
                                        {{ $member->overdue_tasks }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('team.performance') }}" class="btn btn-link">View Full Team Report</a>
            </div>
        </div>

        <!-- Upcoming Deadlines Section -->
        <div class="dashboard-container mt-4">
            <div class="section-header">
                <h4><i class="fas fa-calendar-alt"></i> Upcoming Deadlines</h4>
            </div>

            @foreach($upcomingDeadlines as $task)
                <div class="task-item {{ strtolower($task->priority->name) }}-priority">
                    <div class="task-check">
                        <i class="fas fa-exclamation text-danger"></i>
                    </div>
                    <div class="task-info">
                        <h5>{{ $task->title }}</h5>
                        <div class="task-meta">
                            <span><i class="fas fa-users"></i>
                                @if($task->assignees->count() > 1)
                                    Team ({{ $task->assignees->count() }})
                                @else
                                    {{ $task->assignees->first()->name ?? 'Unassigned' }}
                                @endif
                            </span>
                            <span><i class="fas fa-calendar-alt"></i> Due: {{ $task->due_date->diffForHumans() }}</span>
                            <span
                                class="status-badge status-{{ str_replace(' ', '-', strtolower($task->current_status->name ?? 'No status')) }}">
                                {{  $task->current_status->name ?? 'No status'  }}
                            </span>
                        </div>
                    </div>
                    <button
                        class="btn btn-sm btn-outline-{{ $task->priority->name == 'High' ? 'danger' : ($task->priority->name == 'Medium' ? 'warning' : 'secondary') }}">
                        {{ $task->priority->name == 'High' ? 'Urgent' : ($task->priority->name == 'Medium' ? 'Review' : 'Schedule') }}
                    </button>
                </div>
            @endforeach

            <div class="text-center mt-3">
                <a href="{{ route('tasks.upcoming') }}" class="btn btn-link">View All Upcoming Tasks</a>
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
        });
    </script>
@endsection