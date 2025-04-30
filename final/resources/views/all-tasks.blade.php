@extends('chairperson.genchair')
@section('title', 'All Tasks')

@section('content')
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
                    <img src="{{ Auth::user()->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" alt="User Profile" class="rounded-circle" width="40">
                    <span>{{ ucwords(strtolower(Auth::user()->name)) }}</span>
                </div>
            </div>
        </div>

        <div class="task-management-container">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-tasks"></i> All Tasks</h4>
                <button class="btn create-task-btn" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                    <i class="fas fa-plus me-2"></i> Create New Task
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Assignees</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#taskDetailModal{{ $task->id }}">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="priority-badge priority-{{ $task->priority->name }}">
                                        {{ ucfirst($task->priority->name) }} Priority
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ str_replace('_', '-', $task->current_status->name ?? 'pending') }}">
                                        {{ ucwords(str_replace('_', ' ', $task->current_status->name ?? 'pending')) }}
                                    </span>
                                </td>
                                <td>{{ $task->due_date->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex">
                                        @foreach($task->users as $user)
                                            <img src="{{ $user->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="assignee" 
                                                 title="{{ $user->name }}"
                                                 data-bs-toggle="tooltip">
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $task->department->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#taskDetailModal{{ $task->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($task->creator_id == Auth::id())
                                            <form action="{{ route('chairperson.tasks.delete', $task->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Task Detail Modal for each task -->
                            <div class="modal fade task-detail-modal" id="taskDetailModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <span>{{ $task->title }}</span>
                                                <span class="priority-badge priority-{{ $task->priority->name }} ms-2">
                                                    {{ ucfirst($task->priority->name) }} Priority
                                                </span>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="mb-4">
                                                        <h6>Description</h6>
                                                        <p>{{ $task->description ?? 'No description provided.' }}</p>
                                                    </div>

                                                    @if($task->attachments->count() > 0)
                                                        <div class="mb-4">
                                                            <h6>Attachments ({{ $task->attachments->count() }})</h6>
                                                            <div class="d-flex flex-wrap">
                                                                @foreach($task->attachments as $attachment)
                                                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                                                        <img src="{{ Storage::url($attachment->file_path) }}" 
                                                                             class="attachment-thumbnail" 
                                                                             alt="{{ $attachment->file_name }}"
                                                                             title="{{ $attachment->file_name }}">
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mb-4">
                                                        <h6>Comments ({{ $task->comments->count() }})</h6>
                                                        
                                                        @foreach($task->comments as $comment)
                                                            <div class="comment-box">
                                                                <div class="comment-header">
                                                                    <div class="comment-user">
                                                                        <img src="{{ $comment->user->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" 
                                                                             alt="{{ $comment->user->name }}">
                                                                        {{ $comment->user->name }}
                                                                    </div>
                                                                    <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                                                                </div>
                                                                <div class="comment-text">
                                                                    {{ $comment->content }}
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <div class="mt-3">
                                                            <form action="{{ route('chairperson.tasks.add-comment', $task->id) }}" method="POST">
                                                                @csrf
                                                                <textarea class="form-control" name="comment" rows="3" placeholder="Add a comment..." required></textarea>
                                                                <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-4">
                                                        <h6>Task Details</h6>
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><i class="fas fa-calendar-alt me-2"></i> Due Date</span>
                                                                <span class="fw-bold">{{ $task->due_date->format('M d, Y') }}</span>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <span><i class="fas fa-users me-2"></i> Assigned To</span>
                                                                <div class="d-flex flex-wrap mt-2">
                                                                    @foreach($task->users as $user)
                                                                        <div class="me-2 mb-2 d-flex align-items-center">
                                                                            <img src="{{ $user->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" 
                                                                                 alt="{{ $user->name }}" 
                                                                                 class="assignee me-1">
                                                                            <span>{{ $user->name }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><i class="fas fa-building me-2"></i> Department</span>
                                                                <span>{{ $task->department->name ?? 'N/A' }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><i class="fas fa-user me-2"></i> Created By</span>
                                                                <span>{{ $task->creator->name }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><i class="fas fa-clock me-2"></i> Created On</span>
                                                                <span>{{ $task->created_at->format('M d, Y') }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    @if($task->users->contains(Auth::id()))
                                                        <div class="mb-4">
                                                            <h6>Update Status</h6>
                                                            <form action="{{ route('chairperson.tasks.update-status', $task->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                                <select class="form-select task-status-select" name="status_id" required>
                                                                    @foreach($status::all() as $status)
                                                                        <option value="{{ $status->id }}" 
                                                                                {{ $task->users->find(Auth::id())->pivot->status_id == $status->id ? 'selected' : '' }}>
                                                                            {{ ucwords(str_replace('_', ' ', $status->name)) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                                                            </form>
                                                        </div>
                                                    @endif

                                                    @if($task->current_status && $task->current_status->name == 'pending_approval' && Auth::user()->hasRole('chairperson'))
                                                        <div class="mb-4">
                                                            <h6>Task Approval</h6>
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                                This task is awaiting your approval
                                                            </div>
                                                            <div class="d-grid gap-2">
                                                                <form action="{{ route('chairperson.approve-task', $task->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success">
                                                                        <i class="fas fa-check me-2"></i> Approve Task
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('chairperson.reject-task', $task->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-times me-2"></i> Reject Submission
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mb-4">
                                                        <h6>Add Attachment</h6>
                                                        <form action="{{ route('chairperson.tasks.add-attachment', $task->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="file" name="attachment" class="form-control mb-2" required>
                                                            <button type="submit" class="btn btn-primary w-100">Upload</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-center">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel"><i class="fas fa-plus-circle me-2"></i> Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('chairperson.tasks.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Task Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter task title" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Provide detailed description..."></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="due_date" class="form-label">Due Date *</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="priority_id" class="form-label">Priority *</label>
                                        <select class="form-select" id="priority_id" name="priority_id" required>
                                            <option value="">Select Priority</option>
                                            @foreach($priority::all() as $priority)
                                                <option value="{{ $priority->id }}">{{ ucfirst($priority->name) }} Priority</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Attachments</label>
                                    <div class="file-upload-area border rounded p-4 text-center">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h5>Drag and drop files here</h5>
                                        <p class="text-muted mb-3">or</p>
                                        <input type="file" id="attachment" name="attachment" style="display: none;">
                                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('attachment').click()">Browse Files</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Assign To *</label>
                                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="selectAllAssignees">
                                            <label class="form-check-label fw-bold" for="selectAllAssignees">Select All</label>
                                        </div>
                                        <hr>
                                        @foreach($user::where('id', '!=', Auth::id())->get() as $user)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input assignee-checkbox" type="checkbox" 
                                                       id="assignee_{{ $user->id }}" 
                                                       name="assignees[]" 
                                                       value="{{ $user->id }}">
                                                <label class="form-check-label" for="assignee_{{ $user->id }}">
                                                    {{ $user->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-select" id="department_id" name="department_id">
                                        <option value="">All Departments</option>
                                        @foreach($department::all() as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide notification after 5 seconds
            const notification = document.querySelector('.notification-toast');
            if (notification) {
                setTimeout(() => {
                    notification.classList.remove('show');
                    notification.classList.add('hide');
                }, 5000);
            }

            // Select all assignees functionality
            const selectAllCheckbox = document.getElementById('selectAllAssignees');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.assignee-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection