@extends('chairperson.genchair')
@section('title', 'Review Task')

@section('content')
    <div class="main-content">
        <div class="top-nav d-flex justify-content-between align-items-center mb-3 mb-md-4">
            <h2 class="h4 mb-0">Review Task</h2>
            <div>
                <a href="{{ route('tasks.pending') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="dashboard-container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8 order-2 order-lg-1">
                    <div class="card mb-3 mb-md-4">
                        <div class="card-header bg-white p-3">
                            <h4 class="h5 mb-0">{{ $task->title ?? 'Untitled Task' }}</h4>
                            <div class="d-flex align-items-center mt-2">
                                @if($task->priority)
                                <span class="priority-badge priority-{{ strtolower($task->priority->name) }} me-2">
                                    {{ $task->priority->name }}
                                </span>
                                @endif
                                <span class="status-badge status-{{ $task->current_status ? str_replace(' ', '-', strtolower($task->current_status->name)) : 'no-status' }}">
                                    {{ $task->current_status->name ?? 'No status' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <h5 class="h6">Description</h5>
                                <p class="mb-0">{{ $task->description ?? 'No description provided' }}</p>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <div class="mb-2">
                                        <h6 class="small text-muted mb-1">Created By</h6>
                                        <p class="mb-0">{{ $task->creator->name ?? 'System' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <h6 class="small text-muted mb-1">Due Date</h6>
                                        <p class="mb-0">
                                            @if($task->due_date)
                                                {{ $task->due_date->format('M j, Y') }} <small class="text-muted">({{ $task->due_date->diffForHumans() }})</small>
                                            @else
                                                No due date set
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5 class="h6">Assigned To</h5>
                                <div class="d-flex flex-wrap">
                                    @forelse($task->assignees ?? [] as $assignee)
                                    <div class="me-2 mb-2 d-flex align-items-center">
                                        <img src="{{ $assignee->avatar_url ?? asset('images/default-avatar.png') }}" 
                                             alt="{{ $assignee->name }}" 
                                             class="assignee me-1" style="width: 24px; height: 24px;">
                                        <span class="small">{{ $assignee->name }}</span>
                                    </div>
                                    @empty
                                    <div class="text-muted small">No assignees</div>
                                    @endforelse
                                </div>
                            </div>

                            @if($task->attachments && $task->attachments->count() > 0)
                            <div class="mb-3">
                                <h5 class="h6">Attachments</h5>
                                <div class="list-group">
                                    @foreach($task->attachments as $attachment)
                                    <a href="{{ Storage::url($attachment->path) }}" 
                                       target="_blank" 
                                       class="list-group-item list-group-item-action py-2 px-3">
                                        <i class="fas fa-paperclip me-2"></i>
                                        <span class="small">{{ $attachment->filename }}</span>
                                        <span class="text-muted small ms-2">
                                            ({{ method_exists($attachment, 'sizeForHumans') ? $attachment->sizeForHumans() : 'N/A' }})
                                        </span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mb-3 mb-md-4">
                        <div class="card-header bg-white p-3">
                            <h5 class="h6 mb-0">Comments</h5>
                        </div>
                        <div class="card-body p-3">
                            @if($task->comments && $task->comments->count() > 0)
                                @foreach($task->comments as $comment)
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $comment->user->avatar_url ?? asset('images/default-avatar.png') }}" 
                                                 alt="{{ $comment->user->name }}" 
                                                 class="assignee me-2" style="width: 24px; height: 24px;">
                                            <strong class="small">{{ $comment->user->name ?? 'Unknown User' }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="mt-2 ps-4 small">
                                        {{ $comment->comment }}
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted small">No comments yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-4 order-1 order-lg-2 mb-3 mb-lg-0">
                    <div class="card mb-3">
                        <div class="card-header bg-white p-3">
                            <h5 class="h6 mb-0">Review Actions</h5>
                        </div>
                        <div class="card-body p-3">
                            <form action="{{ route('tasks.approve', $task->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="approvalComment" class="form-label small">Add Comment (Optional)</label>
                                    <textarea class="form-control form-control-sm" id="approvalComment" name="comment" rows="3"></textarea>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check-circle me-1"></i> Approve
                                    </button>
                                    <a href="{{ route('tasks.reject', $task->id) }}" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times-circle me-1"></i> Reject
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($task->activities && $task->activities->count() > 0)
                    <div class="card">
                        <div class="card-header bg-white p-3">
                            <h5 class="h6 mb-0">Task History</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline">
                                @foreach($task->activities as $activity)
                                <div class="timeline-item mb-2">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-indicator bg-{{ $activity->event == 'created' ? 'primary' : ($activity->event == 'updated' ? 'info' : 'warning') }}"></div>
                                    </div>
                                    <div class="timeline-item-content">
                                        <div class="d-flex justify-content-between">
                                            <strong class="small">{{ $activity->description ?? 'Activity' }}</strong>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if($activity->causer)
                                        <div class="small text-muted">
                                            By {{ $activity->causer->name }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Base styles */
        .assignee {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Timeline styles */
        .timeline {
            position: relative;
            padding-left: 1rem;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 1rem;
        }
        .timeline-item-marker {
            position: absolute;
            left: -0.5rem;
            width: 1.5rem;
            text-align: center;
        }
        .timeline-item-marker-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #dee2e6;
        }
        .timeline-item-content {
            padding-left: 1rem;
        }
        
        /* Priority and status badges */
        .priority-badge, .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        .status-badge.status-no-status {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .card-header, .card-body {
                padding: 1rem;
            }
            .dashboard-container {
                padding: 1rem;
            }
            .top-nav h2 {
                font-size: 1.25rem;
            }
        }
        
        @media (min-width: 768px) {
            .assignee {
                width: 40px;
                height: 40px;
            }
        }
    </style>
@endsection