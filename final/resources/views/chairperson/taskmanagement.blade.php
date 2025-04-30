@extends('chairperson.genchair')
@section('title', 'Task Management')

@section('content')
    <style>
        .task-management-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 30px;
            margin-bottom: 30px;
            border: none;
        }

        .task-management-container {
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

        .task-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .task-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
        }

        .task-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.1);
            border-bottom: 3px solid var(--primary-color);
        }

        .task-card {
            border-radius: 12px;
            border: 1px solid #eee;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .task-card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-card-body {
            padding: 20px;
        }

        .task-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .task-meta {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        .task-meta span {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }

        .task-meta i {
            margin-right: 5px;
            font-size: 0.9rem;
        }

        .task-description {
            color: #495057;
            margin-bottom: 15px;
        }

        .task-assignees {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .assignee {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 5px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .task-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
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

        .status-pending-approval {
            background-color: #f3e5f5;
            color: #9c27b0;
        }

        .status-rejected {
            background-color: #efebe9;
            color: #795548;
        }

        .attachment-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #eee;
            cursor: pointer;
            transition: all 0.3s;
        }

        .attachment-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .comment-box {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .comment-user {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #2c3e50;
        }

        .comment-user img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-time {
            font-size: 0.8rem;
            color: #7f8c8d;
        }

        .comment-text {
            color: #495057;
            line-height: 1.5;
        }

        .create-task-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border-radius: 10px;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
            margin-bottom: 20px;
        }

        .create-task-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(67, 97, 238, 0.3);
        }

        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
        }

        .task-detail-modal .modal-header {
            border-bottom: 1px solid #eee;
            padding: 20px;
        }

        .task-detail-modal .modal-body {
            padding: 25px;
        }

        .task-detail-modal .modal-footer {
            border-top: 1px solid #eee;
            padding: 15px 25px;
        }

        .task-status-select {
            width: 200px;
            display: inline-block;
            margin-right: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: #495057;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #6c757d;
        }

        .assignee-section {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .assignee-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .assignee-tasks-count {
            margin-left: auto;
            font-weight: bold;
            color: var(--primary-color);
        }

        .assignee-section {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .assignee-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .assignee-tasks-count {
            margin-left: auto;
            font-weight: bold;
            color: var(--primary-color);
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

        <div class="task-management-container">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-tasks"></i> Task Management</h4>
                <a href="{{ route('chairassign.task') }}" class="btn create-task-btn">
                    <i class="fas fa-plus me-2"></i> Create New Task
                </a>
            </div>

            <!-- Assignee Filter -->
            <div class="mb-4">
                <label for="assigneeFilter" class="form-label">Filter by Assignee:</label>
                <select class="form-select" id="assigneeFilter">
                    <option value="all">All Assignees</option>
                    @foreach($assignees as $assignee)
                        <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
                    @endforeach
                </select>
            </div>

            <ul class="nav nav-tabs task-tabs" id="taskTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tasks-tab" data-bs-toggle="tab" data-bs-target="#all-tasks"
                        type="button" role="tab">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list-check me-2"></i>
                            <span>All Tasks</span>
                            <span class="task-count-badge bg-primary-soft ms-2">{{ $tasks->count() }}</span>
                        </div>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-tasks"
                        type="button" role="tab">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock me-2"></i>
                            <span>Pending</span>
                            <span class="task-count-badge bg-warning-soft ms-2">{{ $pendingApprovals->count() }}</span>
                        </div>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="in-progress-tab" data-bs-toggle="tab" data-bs-target="#in-progress-tasks"
                        type="button" role="tab">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-spinner me-2"></i>
                            <span>In Progress</span>
                            <span class="task-count-badge bg-info-soft ms-2">{{ $inProgressTasks->count() }}</span>
                        </div>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-tasks"
                        type="button" role="tab">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>Completed</span>
                            <span class="task-count-badge bg-success-soft ms-2">{{ $completedTasks->count() }}</span>
                        </div>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="taskTabsContent">
                <!-- All Tasks Tab -->
                <div class="tab-pane fade show active" id="all-tasks" role="tabpanel">
                    @forelse($tasks as $task)
                        <div class="task-card">
                            <div class="task-card-header">
                                <div>
                                    <span class="task-title">{{ $task->title }}</span>
                                    <span class="priority-badge priority-{{ $task->priority->name ?? 'medium' }}">
                                        {{ ucfirst($task->priority->name ?? 'Medium') }} Priority
                                    </span>
                                </div>
                                <span
                                    class="status-badge status-{{ str_replace(' ', '-', strtolower($task->current_status->name ?? 'pending')) }}">
                                    {{ $task->current_status->name ?? 'Pending' }}
                                </span>
                            </div>
                            <div class="task-card-body">
                                <div class="task-meta">
                                    <span><i class="fas fa-calendar-alt"></i> Due:
                                        {{ $task->due_date->format('M d, Y') }}</span>
                                    <span><i class="fas fa-user"></i> Assigned to:
                                        @foreach($task->assignees as $assignee)
                                            {{ $assignee->name }}@if(!$loop->last), @endif
                                        @endforeach
                                    </span>
                                    <span><i class="fas fa-building"></i> {{ $task->department ?? 'General' }}</span>
                                </div>
                                <p class="task-description">
                                    {{ Str::limit($task->description, 200) }}
                                </p>
                                <div class="task-actions">
                                    <a href="{{ route('chairperson.tasks.review', $task->id) }}"
                                        class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                    @if($task->current_status && $task->current_status->name === 'pending_approval')
                                        <form action="{{ route('chairperson.tasks.approve', $task->id) }}" method="POST"
                                            class="d-inline me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('chairperson.tasks.reject', $task->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times me-1"></i> Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-tasks"></i>
                            <h5>No tasks found</h5>
                            <p>There are currently no tasks assigned to you or your team.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pending Approval Tab -->
                <div class="tab-pane fade" id="pending-tasks" role="tabpanel">
                    @if($pendingApprovals->count() > 0)
                                    @foreach($assignees as $assignee)
                                                @php

                                                    $assigneeTasks = $pendingApprovals->filter(function ($task) use ($assignee) {
                                                        return $task->assignees->contains('id', $assignee->id) &&
                                                            optional($task->current_status)->name === 'pending_approval';
                                                    });

                                                @endphp


                                                @if($assigneeTasks->count() > 0)


                                                    <div class="assignee-section" data-assignee="{{ $assignee->id }}">
                                                        <div class="assignee-header">
                                                            <img src="{{ $assignee->avatar_url ?? asset('storage/profile/avatars/profile.png') }}"
                                                                class="assignee me-3" alt="{{ $assignee->name }}">
                                                            <h5 class="mb-0">{{ $assignee->name }}</h5>
                                                            <span class="assignee-tasks-count">{{ $assigneeTasks->count() }} tasks</span>
                                                        </div>

                                                        @foreach($assigneeTasks as $task)

                                                            <div class="task-card mb-3">
                                                                <div class="task-card-header">
                                                                    <div>
                                                                        <span class="task-title">{{ $task->title }}</span>
                                                                        <span class="priority-badge priority-{{ $task->priority->name ?? 'medium' }}">
                                                                            {{ ucfirst($task->priority->name ?? 'Medium') }} Priority
                                                                        </span>
                                                                    </div>
                                                                    <span class="status-badge status-pending-approval">
                                                                        Pending Approval
                                                                    </span>
                                                                </div>
                                                                <div class="task-card-body">
                                                                    <div class="task-meta">
                                                                        <span><i class="fas fa-calendar-alt"></i> Due:
                                                                            {{ $task->due_date->format('M d, Y') }}</span>
                                                                        <span><i class="fas fa-building"></i> {{ $task->department ?? 'General' }}</span>
                                                                    </div>
                                                                    <p class="task-description">
                                                                        {{ Str::limit($task->description, 200) }}
                                                                    </p>
                                                                    <div class="task-actions">
                                                                        <a href="{{ route('chairperson.tasks.review', $task->id) }}"
                                                                            class="btn btn-sm btn-outline-primary me-2">
                                                                            <i class="fas fa-eye me-1"></i> View Details
                                                                        </a>
                                                                        <form
                                                                            action="{{ route('chairperson.tasks.approve-assignee', ['task' => $task->id, 'user' => $assignee->id]) }}"
                                                                            method="POST" class="d-inline me-2">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                                <i class="fas fa-check me-1"></i> Approve
                                                                            </button>
                                                                        </form>
                                                                        <form
                                                                            action="{{ route('chairperson.tasks.reject-assignee', ['task' => $task->id, 'user' => $assignee->id]) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                                <i class="fas fa-times me-1"></i> Reject
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                    @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-clock"></i>
                            <h5>No tasks pending approval</h5>
                            <p>There are currently no tasks awaiting your approval.</p>
                        </div>
                    @endif

                </div>

                <!-- In Progress Tab -->
                <div class="tab-pane fade" id="in-progress-tasks" role="tabpanel">
                    @if($inProgressTasks->count() > 0)
                                @foreach($assignees as $assignee)
                                            @php
                                                $assigneeTasks = $inProgressTasks->filter(function ($task) use ($assignee) {
                                                    return $task->assignees->contains('id', $assignee->id);
                                                });
                                            @endphp

                                            @if($assigneeTasks->count() > 0)
                                                <div class="assignee-section" data-assignee="{{ $assignee->id }}">
                                                    <div class="assignee-header">
                                                        <img src="{{ $assignee->avatar_url ?? asset('storage/profile/avatars/profile.png') }}"
                                                            class="assignee me-3" alt="{{ $assignee->name }}">
                                                        <h5 class="mb-0">{{ $assignee->name }}</h5>
                                                        <span class="assignee-tasks-count">{{ $assigneeTasks->count() }} tasks</span>
                                                    </div>

                                                    @foreach($assigneeTasks as $task)
                                                        <div class="task-card mb-3">
                                                            <div class="task-card-header">
                                                                <div>
                                                                    <span class="task-title">{{ $task->title }}</span>
                                                                    <span class="priority-badge priority-{{ $task->priority->name ?? 'medium' }}">
                                                                        {{ ucfirst($task->priority->name ?? 'Medium') }} Priority
                                                                    </span>
                                                                </div>
                                                                <span class="status-badge status-in-progress">
                                                                    In Progress
                                                                </span>
                                                            </div>
                                                            <div class="task-card-body">
                                                                <div class="task-meta">
                                                                    <span><i class="fas fa-calendar-alt"></i> Due:
                                                                        {{ $task->due_date->format('M d, Y') }}</span>
                                                                    <span><i class="fas fa-building"></i> {{ $task->department ?? 'General' }}</span>
                                                                </div>
                                                                <p class="task-description">
                                                                    {{ Str::limit($task->description, 200) }}
                                                                </p>
                                                                <div class="task-actions">
                                                                    <a href="{{ route('chairperson.tasks.review', $task->id) }}"
                                                                        class="btn btn-sm btn-outline-primary me-2">
                                                                        <i class="fas fa-eye me-1"></i> View Details
                                                                    </a>
                                                                    @if($task->current_status && $task->current_status->name === 'pending_approval')
                                                                        <form action="{{ route('chairperson.tasks.approve', $task->id) }}" method="POST"
                                                                            class="d-inline me-2">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                                <i class="fas fa-check me-1"></i> Approve
                                                                            </button>
                                                                        </form>
                                                                        <form action="{{ route('chairperson.tasks.reject', $task->id) }}" method="POST"
                                                                            class="d-inline">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                                <i class="fas fa-times me-1"></i> Reject
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-spinner"></i>
                            <h5>No tasks in progress</h5>
                            <p>There are currently no tasks being worked on by your team.</p>
                        </div>
                    @endif
                </div>

                <!-- Completed Tab -->
                <div class="tab-pane fade" id="completed-tasks" role="tabpanel">
                    @if($completedTasks->count() > 0)
                                @foreach($assignees as $assignee)
                                            @php
                                                $assigneeTasks = $completedTasks->filter(function ($task) use ($assignee) {
                                                    return $task->assignees->contains('id', $assignee->id);
                                                });
                                            @endphp

                                            @if($assigneeTasks->count() > 0)
                                                <div class="assignee-section" data-assignee="{{ $assignee->id }}">
                                                    <div class="assignee-header">
                                                        <img src="{{ $assignee->avatar_url ?? asset('storage/profile/avatars/profile.png') }}"
                                                            class="assignee me-3" alt="{{ $assignee->name }}">
                                                        <h5 class="mb-0">{{ $assignee->name }}</h5>
                                                        <span class="assignee-tasks-count">{{ $assigneeTasks->count() }} tasks</span>
                                                    </div>

                                                    @foreach($assigneeTasks as $task)
                                                        <div class="task-card mb-3">
                                                            <div class="task-card-header">
                                                                <div>
                                                                    <span class="task-title">{{ $task->title }}</span>
                                                                    <span class="priority-badge priority-{{ $task->priority->name ?? 'medium' }}">
                                                                        {{ ucfirst($task->priority->name ?? 'Medium') }} Priority
                                                                    </span>
                                                                </div>
                                                                <span class="status-badge status-completed">
                                                                    Completed
                                                                </span>
                                                            </div>
                                                            <div class="task-card-body">
                                                                <div class="task-meta">
                                                                    <span><i class="fas fa-calendar-alt"></i> Due:
                                                                        {{ $task->due_date->format('M d, Y') }}</span>
                                                                    <span><i class="fas fa-building"></i> {{ $task->department ?? 'General' }}</span>
                                                                </div>
                                                                <p class="task-description">
                                                                    {{ Str::limit($task->description, 200) }}
                                                                </p>
                                                                <div class="task-actions">
                                                                    <a href="{{ route('chairperson.tasks.review', $task->id) }}"
                                                                        class="btn btn-sm btn-outline-primary me-2">
                                                                        <i class="fas fa-eye me-1"></i> View Details
                                                                    </a>
                                                                    <button class="btn btn-sm btn-outline-secondary">
                                                                        <i class="fas fa-file-export me-1"></i> Export Report
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <h5>No completed tasks</h5>
                            <p>There are currently no completed tasks in your team.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide notification
            const notification = document.querySelector('.notification-toast');
            if (notification) {
                setTimeout(() => {
                    notification.classList.remove('show');
                    notification.classList.add('hide');
                }, 5000);
            }

            // Tab persistence
            if (localStorage.getItem('lastActiveTab')) {
                const lastActiveTab = localStorage.getItem('lastActiveTab');
                const tabTrigger = document.querySelector(`#${lastActiveTab}-tab`);
                if (tabTrigger) {
                    new bootstrap.Tab(tabTrigger).show();
                }
            }

            // Save active tab when changed
            const tabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabTriggers.forEach(tabTrigger => {
                tabTrigger.addEventListener('click', function () {
                    localStorage.setItem('lastActiveTab', this.id);
                });
            });

            // Assignee filter functionality
            const assigneeFilter = document.getElementById('assigneeFilter');
            if (assigneeFilter) {
                assigneeFilter.addEventListener('change', function () {
                    const assigneeId = this.value;
                    const assigneeSections = document.querySelectorAll('.assignee-section');

                    assigneeSections.forEach(section => {
                        if (assigneeId === 'all' || section.dataset.assignee === assigneeId) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endsection