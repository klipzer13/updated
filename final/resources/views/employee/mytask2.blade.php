@extends('employee.genemp')
@section('title', 'My Tasks')

@section('content')
    <style>
        .tasks-container {
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
            line-height: 1.6;
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

        .status-pending-approval {
            background-color: #e8f5e9;
            color: #ff9800;
        }

        .status-in-progress {
            background-color: #fff3e0;
            color: #fb8c00;
        }

        .status-completed {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .status-rejected {
            background-color: #ffebee;
            color: #f44336;
        }

        .status-overdue {
            background-color: #ffebee;
            color: #f44336;
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

        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
        }

        /* Improved Modal Styles */
        .task-modal .modal-dialog {
            max-width: 800px;
        }

        .task-modal .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .task-modal .modal-title {
            font-weight: 600;
        }

        .task-modal .modal-body {
            padding: 2rem;
        }

        .task-modal .modal-footer {
            border-top: none;
            background-color: #f8f9fa;
            padding: 1rem 2rem;
        }

        .task-modal .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .task-modal .section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .task-modal .description-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .task-modal .comment-section {
            max-height: 300px;
            overflow-y: auto;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
        }

        .task-modal .comment-item {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .task-modal .comment-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .task-modal .comment-user {
            font-weight: 600;
            color: #212529;
        }

        .task-modal .comment-time {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .task-modal .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.75rem;
        }

        .task-modal .attachment-thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
            transition: transform 0.2s;
        }

        .task-modal .attachment-thumbnail:hover {
            transform: scale(1.05);
        }

        .task-modal .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .task-modal .btn-submit {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
    </style>


    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
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
        @if(session('success'))
            <div class="notification-toast alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        <!-- Tasks Content -->
        <div class="tasks-container">

            <div class="section-header">
                <h4><i class="fas fa-tasks"></i> My Tasks</h4>
                <div>
                    <select class="form-select form-select-sm me-2" id="sortSelect"
                        style="width: auto; display: inline-block;">
                        <option value="due_date">Sort by Due Date</option>
                        <option value="priority">Sort by Priority</option>
                        <option value="status">Sort by Status</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary" id="filterBtn">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>

            <ul class="nav nav-tabs task-tabs" id="taskTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tasks-tab" data-bs-toggle="tab" data-bs-target="#all-tasks"
                        type="button" role="tab">
                        <i class="fas fa-list-check me-2"></i>
                        <span>All Tasks</span>
                        <span class="task-count-badge bg-primary-soft ms-2">{{ $tasks->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="active-tasks-tab" data-bs-toggle="tab" data-bs-target="#active-tasks"
                        type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>
                        <span>Current Task</span>
                        <span class="task-count-badge bg-primary-soft ms-2">
                            {{ $tasks->filter(function ($task) use ($statuses) {
        $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
        $status = $statuses->firstWhere('id', $userStatus);
        return in_array($status->name, ['pending', 'in_progress', 'rejected']);
    })->count() }}
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-tasks"
                        type="button" role="tab">
                        <i class="fas fa-hourglass-half me-2"></i>
                        <span>Pending Approval</span>
                        <span class="task-count-badge bg-primary-soft ms-2">
                            {{ $tasks->filter(function ($task) use ($statuses) {
        $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
        $status = $statuses->firstWhere('id', $userStatus);
        return $status->name == 'pending_approval';
    })->count() }}
                        </span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-tasks"
                        type="button" role="tab">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>Completed</span>
                        <span class="task-count-badge bg-primary-soft ms-2">
                            {{ $tasks->filter(function ($task) use ($statuses) {
        $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
        $status = $statuses->firstWhere('id', $userStatus);
        return $status->name == 'completed';
    })->count() }}
                        </span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="taskTabsContent">
                <!-- All Tasks Tab -->
                <div class="tab-pane fade show active" id="all-tasks" role="tabpanel">
                    @if($tasks->count() > 0)
                                @foreach($tasks as $task)
                                            @php
                                                $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
                                                $status = $statuses->firstWhere('id', $userStatus);
                                            @endphp
                                            <div class="task-card" data-task-id="{{ $task->id }}"
                                                data-due-date="{{ $task->due_date->format('Y-m-d') }}"
                                                data-priority="{{ $task->priority ? $task->priority->name : '' }}"
                                                data-status="{{ $status->name }}">
                                                <div class="task-card-header">
                                                    <div>
                                                        <span class="task-title">{{ $task->title }}</span>
                                                        @if($task->priority)
                                                            <span class="priority-badge priority-{{ strtolower($task->priority->name) }}">
                                                                {{ $task->priority->name }} Priority
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="status-badge status-{{ str_replace(' ', '-', strtolower($status->name)) }}">
                                                        {{ $status->name }}
                                                    </span>
                                                </div>
                                                <div class="task-card-body">
                                                    <div class="task-meta">
                                                        <span><i class="fas fa-calendar-alt"></i> Due:
                                                            {{ $task->due_date->format('M d, Y') }}</span>
                                                        <span><i class="fas fa-user"></i> Assigned by: {{ $task->creator->name }}</span>
                                                    </div>
                                                    <p class="task-description">
                                                        {{ $task->description }}
                                                    </p>
                                                    <div class="task-actions">
                                                        <button class="btn btn-sm btn-outline-primary view-task-btn" data-task-id="{{ $task->id }}">
                                                            <i class="fas fa-eye me-1"></i> View Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5>No Tasks Assigned</h5>
                            <p>You currently have no tasks assigned to you.</p>
                        </div>
                    @endif
                </div>

                <!-- Active Tasks Tab (pending, In Progress, rejected) -->
                <div class="tab-pane fade" id="active-tasks" role="tabpanel">
                    @php
                        $activeTasksExist = false;
                        foreach ($tasks as $task) {
                            $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
                            $status = $statuses->firstWhere('id', $userStatus);
                            if (in_array($status->name, ['pending', 'in_progress', 'rejected'])) {
                                $activeTasksExist = true;
                                break;
                            }
                        }
                    @endphp

                    @if($activeTasksExist)
                                @foreach($tasks as $task)
                                            @php
                                                $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
                                                $status = $statuses->firstWhere('id', $userStatus);
                                            @endphp
                                            @if(in_array($status->name, ['pending', 'in_progress', 'rejected']))
                                                <div class="task-card" data-task-id="{{ $task->id }}"
                                                    data-due-date="{{ $task->due_date->format('Y-m-d') }}"
                                                    data-priority="{{ $task->priority ? $task->priority->name : '' }}"
                                                    data-status="{{ $status->name }}">
                                                    <div class="task-card-header">
                                                        <div>
                                                            <span class="task-title">{{ $task->title }}</span>
                                                            @if($task->priority)
                                                                <span class="priority-badge priority-{{ strtolower($task->priority->name) }}">
                                                                    {{ $task->priority->name }} Priority
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($status->name)) }}">
                                                            {{ $status->name }}
                                                        </span>
                                                    </div>
                                                    <div class="task-card-body">
                                                        <div class="task-meta">
                                                            <span><i class="fas fa-calendar-alt"></i> Due:
                                                                {{ $task->due_date->format('M d, Y') }}</span>
                                                            <span><i class="fas fa-user"></i> Assigned by: {{ $task->creator->name }}</span>
                                                        </div>
                                                        <p class="task-description">
                                                            {{ $task->description }}
                                                        </p>
                                                        <div class="task-actions">
                                                            <button class="btn btn-sm btn-outline-primary view-task-btn" data-task-id="{{ $task->id }}">
                                                                <i class="fas fa-eye me-1"></i> View Details
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5>No Active Tasks</h5>
                            <p>You currently have no active tasks.</p>
                        </div>
                    @endif
                </div>

                <!-- Pending Approval Tasks Tab -->
                <div class="tab-pane fade" id="pending-tasks" role="tabpanel">
                    @php
                        $pendingTasksExist = false;
                        foreach ($tasks as $task) {
                            $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
                            $status = $statuses->firstWhere('id', $userStatus);
                            if ($status->name == 'pending_approval') {
                                $pendingTasksExist = true;
                                break;
                            }
                        }
                    @endphp

                    @if($pendingTasksExist)
                                @foreach($tasks as $task)
                                            @php
                                                $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
                                                $status = $statuses->firstWhere('id', $userStatus);
                                            @endphp
                                            @if($status->name == 'pending_approval')
                                                <div class="task-card" data-task-id="{{ $task->id }}"
                                                    data-due-date="{{ $task->due_date->format('Y-m-d') }}"
                                                    data-priority="{{ $task->priority ? $task->priority->name : '' }}"
                                                    data-status="{{ $status->name }}">
                                                    <div class="task-card-header">
                                                        <div>
                                                            <span class="task-title">{{ $task->title }}</span>
                                                            @if($task->priority)
                                                                <span class="priority-badge priority-{{ strtolower($task->priority->name) }}">
                                                                    {{ $task->priority->name }} Priority
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($status->name)) }}">
                                                            {{ $status->name }}
                                                        </span>
                                                    </div>
                                                    <div class="task-card-body">
                                                        <div class="task-meta">
                                                            <span><i class="fas fa-calendar-alt"></i> Due:
                                                                {{ $task->due_date->format('M d, Y') }}</span>
                                                            <span><i class="fas fa-user"></i> Assigned by: {{ $task->creator->name }}</span>
                                                        </div>
                                                        <p class="task-description">
                                                            {{ $task->description }}
                                                        </p>
                                                        <div class="task-actions">
                                                            <button class="btn btn-sm btn-outline-primary view-task-btn" data-task-id="{{ $task->id }}">
                                                                <i class="fas fa-eye me-1"></i> View Details
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5>No Pending Approval Tasks</h5>
                            <p>You don't have any tasks waiting for approval.</p>
                        </div>
                    @endif
                </div>

                <!-- Completed Tasks Tab -->
                <div class="tab-pane fade" id="completed-tasks" role="tabpanel">
                    @php
                        $completedTasksExist = false;
                        foreach ($tasks as $task) {
                            $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
                            $status = $statuses->firstWhere('id', $userStatus);
                            if ($status->name == 'completed') {
                                $completedTasksExist = true;
                                break;
                            }
                        }
                    @endphp

                    @if($completedTasksExist)
                                @foreach($tasks as $task)
                                            @php
                                                $userStatus = $task->users->firstWhere('id', Auth::id())->pivot->status_id;
                                                $status = $statuses->firstWhere('id', $userStatus);
                                            @endphp
                                            @if($status->name == 'completed')
                                                <div class="task-card" data-task-id="{{ $task->id }}"
                                                    data-due-date="{{ $task->due_date->format('Y-m-d') }}"
                                                    data-priority="{{ $task->priority ? $task->priority->name : '' }}"
                                                    data-status="{{ $status->name }}">
                                                    <div class="task-card-header">
                                                        <div>
                                                            <span class="task-title">{{ $task->title }}</span>
                                                            @if($task->priority)
                                                                <span class="priority-badge priority-{{ strtolower($task->priority->name) }}">
                                                                    {{ $task->priority->name }} Priority
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($status->name)) }}">
                                                            {{ $status->name }}
                                                        </span>
                                                    </div>
                                                    <div class="task-card-body">
                                                        <div class="task-meta">
                                                            <span><i class="fas fa-calendar-alt"></i> Due:
                                                                {{ $task->due_date->format('M d, Y') }}</span>
                                                            <span><i class="fas fa-user"></i> Assigned by: {{ $task->creator->name }}</span>
                                                        </div>
                                                        <p class="task-description">
                                                            {{ $task->description }}
                                                        </p>
                                                        <div class="task-actions">
                                                            <button class="btn btn-sm btn-outline-primary view-task-btn" data-task-id="{{ $task->id }}">
                                                                <i class="fas fa-eye me-1"></i> View Details
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5>No Completed Tasks</h5>
                            <p>You don't have any completed tasks yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Task View Modal -->
    <!-- Task View Modal -->
    <div class="modal fade task-modal" id="taskViewModal" tabindex="-1" aria-labelledby="taskViewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-5" id="taskViewModalLabel">Task Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="task-loading-indicator" class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading task details...</p>
                    </div>

                    <div id="task-details-container" style="display: none;">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 id="task-modal-title" class="fw-bold mb-2"></h4>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span id="task-modal-status" class="badge rounded-pill"></span>
                                    <span id="task-modal-priority" class="badge rounded-pill"></span>
                                    <span id="task-modal-due-date" class="text-muted small"></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="text-muted small">Assigned by:</span>
                                    <span id="task-modal-creator" class="fw-semibold"></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted small">Assigned on:</span>
                                    <span id="task-modal-created-at" class="fw-semibold"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3 text-uppercase small text-muted">Description</h6>
                            <div id="task-modal-description" class="p-3 bg-light rounded"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="fw-semibold mb-3 text-uppercase small text-muted">Attachments</h6>
                                <div id="task-modal-attachments" class="d-flex flex-wrap gap-3">
                                    <div class="text-center py-3 w-100">
                                        <p class="text-muted">No attachments available</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <h6 class="fw-semibold mb-3 text-uppercase small text-muted">Comments</h6>
                                <div id="task-modal-comments" class="comment-section bg-light rounded p-3">
                                    <div class="text-center py-2">
                                        <p class="text-muted">No comments yet</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="task-submission-form-container">
                            <form id="taskSubmissionForm" action="/tasks/submit" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="task_id" id="submission_task_id">
                                <div class="mb-3">
                                    <label for="comment" class="form-label fw-semibold">Add Comment</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3"
                                        placeholder="Add your comments here..."></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="attachments" class="form-label fw-semibold">Add Attachments</label>
                                    <input class="form-control" type="file" id="attachments" name="attachments[]" multiple>
                                    <div class="form-text">Upload relevant files (max 5MB each)</div>
                                </div>
                                <button type="submit" class="btn btn-primary px-4 py-2" id="submitTaskBtn">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Task
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="task-error-container" class="d-flex align-items-center p-3 bg-light rounded"
                        style="display: none;">
                        <!-- <i class="fas fa-exclamation-circle me-3 fs-4 text-danger"></i>
                        <div>
                            <p class="mb-1" id="task-error-message">Failed to load task details.</p>
                            <div class="mt-2">
                                <button id="task-error-retry" class="btn btn-sm btn-outline-primary">Retry</button>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
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

            // Task view modal functionality
            const viewTaskButtons = document.querySelectorAll('.view-task-btn');
            const taskViewModal = new bootstrap.Modal(document.getElementById('taskViewModal'));

            viewTaskButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const taskId = this.getAttribute('data-task-id');
                    showTaskLoadingState();
                    loadTaskDetails(taskId);
                    document.getElementById('submission_task_id').value = taskId;
                    taskViewModal.show();
                });
            });

            // Retry button event listener
            document.getElementById('task-error-retry').addEventListener('click', function () {
                const taskId = document.getElementById('submission_task_id').value;
                showTaskLoadingState();
                loadTaskDetails(taskId);
            });

            // Sort functionality
            document.getElementById('sortSelect').addEventListener('change', function () {
                sortTasks(this.value);
            });

            // Sidebar toggle
            document.getElementById('sidebarToggle').addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('mainContent').classList.toggle('active');
            });
        });

        function showTaskLoadingState() {
            document.getElementById('task-loading-indicator').style.display = 'block';
            document.getElementById('task-details-container').style.display = 'none';
            document.getElementById('task-error-container').style.display = 'none';
        }

        function showTaskDetails() {
            document.getElementById('task-loading-indicator').style.display = 'none';
            document.getElementById('task-details-container').style.display = 'block';
            document.getElementById('task-error-container').style.display = 'none';
        }

        function showTaskError(errorMessage) {
            document.getElementById('task-loading-indicator').style.display = 'none';
            document.getElementById('task-details-container').style.display = 'none';
            document.getElementById('task-error-container').style.display = 'flex';
            document.getElementById('task-error-message').textContent = errorMessage || 'Failed to load task details.';
        }

        function loadTaskDetails(taskId) {
            fetch(`/tasks/${taskId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load task details');
                    }

                    const task = data.data;

                    // Populate basic task info
                    document.getElementById('task-modal-title').textContent = task.title;
                    document.getElementById('task-modal-description').textContent = task.description;
                    document.getElementById('task-modal-status').textContent = task.status;
                    document.getElementById('task-modal-status').className = `badge rounded-pill ${getStatusClass(task.status)}`;

                    // Hide submission form if task is completed
                    const submissionForm = document.getElementById('task-submission-form-container');
                    if (task.status.toLowerCase() === 'completed') {
                        submissionForm.style.display = 'none';
                    } else {
                        submissionForm.style.display = 'block';
                    }

                    if (task.priority) {
                        document.getElementById('task-modal-priority').textContent = task.priority.name;
                        document.getElementById('task-modal-priority').className = `badge rounded-pill ${getPriorityClass(task.priority.name)}`;
                    } else {
                        document.getElementById('task-modal-priority').textContent = 'No Priority';
                        document.getElementById('task-modal-priority').className = 'badge rounded-pill bg-secondary';
                    }

                    document.getElementById('task-modal-due-date').textContent = `Due: ${task.due_date_formatted}`;
                    document.getElementById('task-modal-creator').textContent = task.creator.name;
                    document.getElementById('task-modal-created-at').textContent = task.created_at_formatted;

                    // Load attachments
                    const attachmentsContainer = document.getElementById('task-modal-attachments');
                    attachmentsContainer.innerHTML = '';
                    if (task.attachments && task.attachments.length > 0) {
                        task.attachments.forEach(attachment => {
                            const attachmentElement = document.createElement('div');
                            attachmentElement.className = 'mb-2';

                            if (attachment.mime_type && attachment.mime_type.startsWith('image/')) {
                                attachmentElement.innerHTML = `
                                    <a href="/storage/${attachment.url}" target="_blank" class="d-block">
                                        <img src="/storage/${attachment.url}" class="attachment-thumbnail" 
                                             alt="${attachment.original_name}" title="${attachment.original_name}">
                                    </a>
                                    <small class="d-block text-muted text-truncate" style="max-width: 100px">${attachment.original_name}</small>
                                `;
                            } else {
                                attachmentElement.innerHTML = `
                                    <a href="/storage/${attachment.url}" target="_blank" class="d-block text-center">
                                        <div class="attachment-thumbnail d-flex align-items-center justify-content-center bg-white">
                                            <i class="fas fa-file-alt fa-2x text-secondary"></i>
                                        </div>
                                        <small class="d-block text-muted text-truncate" style="max-width: 100px">${attachment.original_name}</small>
                                    </a>
                                `;
                            }

                            attachmentsContainer.appendChild(attachmentElement);
                        });
                    } else {
                        attachmentsContainer.innerHTML = '<p class="text-muted">No attachments</p>';
                    }

                    // Load comments
                    const commentsContainer = document.getElementById('task-modal-comments');
                    commentsContainer.innerHTML = '';
                    if (task.comments && task.comments.length > 0) {
                        task.comments.forEach(comment => {
                            const commentElement = document.createElement('div');
                            commentElement.className = 'comment-item';
                            commentElement.innerHTML = `
                                <div class="d-flex align-items-center mb-2">
                                    <img src="${comment.user.avatar_url || '/storage/profile/avatars/profile.png'}" 
                                         class="user-avatar" alt="${comment.user.name}">
                                    <div>
                                        <span class="comment-user">${comment.user.name}</span>
                                        <span class="comment-time">${comment.created_at}</span>
                                    </div>
                                </div>
                                <p class="ms-4 mb-0">${comment.content}</p>
                            `;
                            commentsContainer.appendChild(commentElement);
                        });
                    } else {
                        commentsContainer.innerHTML = '<p class="text-muted">No comments yet</p>';
                    }

                    showTaskDetails();
                })
                .catch(error => {
                    console.error('Error loading task details:', error);
                    showTaskError(error.message || 'Failed to load task details. Please try again.');
                });
        }

        function getStatusClass(status) {
            const statusClasses = {
                'pending': 'bg-info',
                'in_progress': 'bg-warning text-dark',
                'completed': 'bg-success',
                'overdue': 'bg-danger',
                'rejected': 'bg-danger',
                'pending_approval': 'bg-primary'
            };
            return statusClasses[status.toLowerCase()] || 'bg-secondary';
        }

        function getPriorityClass(priority) {
            const priorityClasses = {
                'high': 'bg-danger',
                'medium': 'bg-warning text-dark',
                'low': 'bg-success'
            };
            return priorityClasses[priority.toLowerCase()] || 'bg-secondary';
        }

        function sortTasks(criteria) {
            // Get all tab panes
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabPanes.forEach(tabPane => {
                const taskContainer = tabPane;
                const tasks = Array.from(taskContainer.querySelectorAll('.task-card'));

                tasks.sort((a, b) => {
                    if (criteria === 'due_date') {
                        return new Date(a.getAttribute('data-due-date')) - new Date(b.getAttribute('data-due-date'));
                    } else if (criteria === 'priority') {
                        const priorityOrder = { 'High': 1, 'Medium': 2, 'Low': 3, '': 4 };
                        return priorityOrder[a.getAttribute('data-priority') || ''] - priorityOrder[b.getAttribute('data-priority') || ''];
                    } else if (criteria === 'status') {
                        return a.getAttribute('data-status').localeCompare(b.getAttribute('data-status'));
                    }
                    return 0;
                });

                // Re-append sorted tasks
                tasks.forEach(task => taskContainer.appendChild(task));
            });
        }
    </script>
@endsection