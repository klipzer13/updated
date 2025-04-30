@extends('chairperson.genchair')
@section('title', 'Task Details')

@section('content')
    <style>
        .task-details-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 30px;
            margin-bottom: 30px;
            border: none;
        }

        .task-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .task-title {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .task-meta-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .task-meta-item i {
            margin-right: 8px;
            color: #6c757d;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.65em;
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

        .description-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .comment-section {
            max-height: 400px;
            overflow-y: auto;
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .comment-item {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .comment-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .comment-user {
            font-weight: 600;
            color: #212529;
        }

        .comment-time {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
        }

        .attachment-thumbnail {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            transition: transform 0.2s;
        }

        .attachment-thumbnail:hover {
            transform: scale(1.05);
        }

        .attachment-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 2rem;
        }

        .back-btn {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .btn-submit {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
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

        <div class="task-details-container">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary back-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Tasks
            </a>

            <div class="task-header">
                <h1 class="task-title">{{ $task->title }}</h1>
                <div class="task-meta">
                    <span class="task-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <strong>Due:</strong> {{ $task->due_date->format('M d, Y') }}
                    </span>
                    <span class="task-meta-item">
                        <i class="fas fa-user"></i>
                        <strong>Assigned by:</strong> {{ $task->creator->name }}
                    </span>
                    <span class="task-meta-item">
                        <i class="fas fa-clock"></i>
                        <strong>Assigned on:</strong> {{ $task->created_at->format('M d, Y') }}
                    </span>
                    @if($task->priority)
                        <span class="task-meta-item">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Priority:</strong>
                            <span class="badge priority-{{ strtolower($task->priority->name) }}">
                                {{ $task->priority->name }}
                            </span>
                        </span>
                    @endif
                    <span class="task-meta-item">
                        <i class="fas fa-tasks"></i>
                        <strong>Status:</strong>
                        <span class="badge status-{{ str_replace(' ', '-', strtolower($status->name)) }}">
                            {{ $status->name }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="description-box">
                <h6 class="section-title">Description</h6>
                <p>{{ $task->description }}</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="section-title">Attachments</h6>
                    <div class="attachment-container">
                        @forelse($task->attachments as $attachment)
                            @if($attachment->mime_type && str_starts_with($attachment->mime_type, 'image/'))
                                <div>
                                    <a href="{{ Storage::url($attachment->url) }}" target="_blank" class="d-block">
                                        <img src="{{ Storage::url($attachment->url) }}" class="attachment-thumbnail" 
                                             alt="{{ $attachment->original_name }}" title="{{ $attachment->original_name }}">
                                    </a>
                                    <small class="d-block text-muted text-truncate" style="max-width: 120px">
                                        {{ $attachment->original_name }}
                                    </small>
                                </div>
                            @else
                                <div>
                                    <a href="{{ Storage::url($attachment->url) }}" target="_blank" class="d-block text-center">
                                        <div class="attachment-thumbnail d-flex align-items-center justify-content-center bg-white">
                                            <i class="fas fa-file-alt fa-2x text-secondary"></i>
                                        </div>
                                        <small class="d-block text-muted text-truncate" style="max-width: 120px">
                                            {{ $attachment->original_name }}
                                        </small>
                                    </a>
                                </div>
                            @endif
                        @empty
                            <p class="text-muted">No attachments available</p>
                        @endforelse
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="section-title">Comments</h6>
                    <div class="comment-section">
                        @forelse($task->comments as $comment)
                            <div class="comment-item">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $comment->user->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" 
                                         class="user-avatar" alt="{{ $comment->user->name }}">
                                    <div>
                                        <span class="comment-user">{{ $comment->user->name }}</span>
                                        <span class="comment-time">{{ $comment->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                                <p class="ms-5 mb-0">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <p class="text-muted">No comments yet</p>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($status->name !== 'completed')
                <div class="mt-5">
                    <h6 class="section-title">Submit Task</h6>
                    <form id="taskSubmissionForm" action="{{ route('tasks.submit', $task->id) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="comment" class="form-label">Add Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3"
                                      placeholder="Add your comments here..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="attachments" class="form-label">Add Attachments</label>
                            <input class="form-control" type="file" id="attachments" name="attachments[]" multiple>
                            <div class="form-text">Upload relevant files (max 5MB each)</div>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-paper-plane me-2"></i> Submit Task
                        </button>
                    </form>
                </div>
            @endif
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
        });
    </script>
@endsection