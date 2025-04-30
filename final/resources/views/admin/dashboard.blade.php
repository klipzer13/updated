@extends('genview')
@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .assignees-dropdown .btn-sm.py-0 {
    padding-top: 0.15rem;
    padding-bottom: 0.15rem;
    font-size: 0.75rem;
}
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--secondary-color);
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .task-item {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #fff;
        border-left: 4px solid;
    }
    
    .high-priority {
        border-left-color: #dc3545;
    }
    
    .medium-priority {
        border-left-color: #ffc107;
    }
    
    .low-priority {
        border-left-color: #28a745;
    }
    
    .task-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .badge-pending {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    
    .badge-in-progress {
        background-color: #e7f6fd;
        color: #0d6efd;
    }
    
    .badge-completed {
        background-color: #e6f7ee;
        color: #198754;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .assigner-info {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.85rem;
    }

    .assignee-avatar {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: 5px;
        margin-right: 5px;
    }

    .assignees-dropdown .dropdown-menu {
        padding: 0;
        min-width: 250px;
    }

    .assignees-dropdown .dropdown-item {
        padding: 0.5rem 1rem;
    }

    .task-actions .btn {
        display: inline-flex;
        align-items: center;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar-collapse-btn {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #6c757d;
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
                    <img src="{{ Auth::user()->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" alt="User Profile" class="rounded-circle"
                        width="40">
                    <span>{{ ucwords(strtolower(Auth::user()->name)) }}</span>
                </div>
            </div>
        </div>

    <!-- Dashboard Content -->
    <h4 class="mb-4">Task Overview</h4>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <i class="fas fa-tasks"></i>
                <div class="stat-value">{{ $totalTasks }}</div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <i class="fas fa-user-clock"></i>
                <div class="stat-value">{{ $totalMembers }}</div>
                <div class="stat-label">Members</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <i class="fas fa-spinner"></i>
                <div class="stat-value">{{ $inProgressTasks }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <i class="fas fa-check-circle"></i>
                <div class="stat-value">{{ $completedTasks }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Tasks</h5>
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm" 
                       style="background: linear-gradient(to right, var(--primary-color), var(--secondary-color)); color: white;">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @forelse($recentTasks as $task)
                        @php
                            $priorityClass = strtolower($task->priority->name ?? 'medium') . '-priority';
                            $status = $task->users->first()->pivot->status_id ?? null;
                            $statusName = $statuses->firstWhere('id', $status)->name ?? 'Pending';
                            $statusClass = 'badge-' . strtolower(str_replace(' ', '-', $statusName));
                            $assigner = $task->creator;
                            $assignees = $task->users;
                        @endphp
                        
                        <div class="task-item {{ $priorityClass }}">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <h6 class="mb-0">{{ $task->title }}</h6>
        <div class="d-flex align-items-center">
            @if($assignees->count() > 0)
            <div class="assignees-dropdown me-2">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle py-0" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                    <i class="fas fa-users me-1"></i>
                    {{ $assignees->count() }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach($assignees as $assignee)
                        <li>
                            <div class="d-flex align-items-center px-3 py-2">
                                <img src="{{ $assignee->avatar_url ?? 'https://via.placeholder.com/30' }}" 
                                     class="assignee-avatar me-2" width="30" height="30">
                                <div>
                                    <div>{{ $assignee->name }}</div>
                                    @php
                                        $assigneeStatus = $statuses->firstWhere('id', $assignee->pivot->status_id ?? null);
                                        $assigneeStatusClass = 'badge-' . strtolower(str_replace(' ', '-', $assigneeStatus->name ?? 'pending'));
                                    @endphp
                                    <small class="text-muted">
                                        <span class="badge rounded-pill {{ $assigneeStatusClass }}">
                                            {{ $assigneeStatus->name ?? 'Pending' }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </li>
                        @if(!$loop->last)
                            <li><hr class="dropdown-divider"></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif
           
        </div>
    </div>
    
    <p class="task-meta mb-2">
        <span class="assigner-info">
            <i class="fas fa-user-edit me-1"></i> Assigned by: 
            <img src="{{ $assigner->avatar_url ?? 'https://via.placeholder.com/30' }}" 
                 class="assignee-avatar">
            {{ $assigner->name }}
        </span>
    </p>
    
    <p class="task-meta mb-2">
        @if($task->due_date)
            <i class="fas fa-calendar-day me-1"></i>
            Due: {{ $task->due_date->isToday() ? 'Today' : $task->due_date->format('M d, Y') }}
            @if($task->due_date->isPast() && $statusName !== 'Completed')
                <span class="text-danger ms-2"><i class="fas fa-exclamation-triangle"></i> Overdue</span>
            @endif
        @else
            <i class="fas fa-calendar-day me-1"></i> No due date
        @endif
    </p>
    
    <p class="mb-2">{{ Str::limit($task->description, 100) }}</p>
    
    <div class="task-actions">
        <a href="{{ route('tasks.show', $task->id) }}" 
           class="btn btn-sm btn-outline-primary me-2">
            <i class="fas fa-eye me-1"></i> View Details
        </a>
    </div>
</div>
                    @empty
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No recent tasks found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Members</h5>
                </div>
                <div class="card-body">
                    @foreach($teamMembers as $member)
                        @if($member->role_id !== 1)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $member->avatar_url ?? 'https://via.placeholder.com/40' }}" 
                                     alt="{{ $member->name }}" class="user-avatar me-3">
                                <div>
                                    <h6 class="mb-0">{{ $member->name }}</h6>
                                    <small class="text-muted">{{ $member->role->name ?? 'Team Member' }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill ms-auto">{{ $member->tasks_count }} Tasks</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary w-100 mb-2"
                        style="background: linear-gradient(to right, var(--primary-color), var(--secondary-color)); border: none;">
                        <i class="fas fa-plus me-2"></i>Create New Task
                    </a>
                    <a href="{{ route('task.members') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-user-plus me-2"></i>Add Team Member
                    </a>
                    <button class="btn btn-outline-primary w-100">
                        <i class="fas fa-project-diagram me-2"></i>New Project
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mark task as complete
    document.querySelectorAll('.mark-complete').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            // AJAX call to update task status
            fetch(`/tasks/${taskId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        });
    });

    // Reopen task
    document.querySelectorAll('.reopen-task').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            // AJAX call to reopen task
            fetch(`/tasks/${taskId}/reopen`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        });
    });
</script>

@endsection