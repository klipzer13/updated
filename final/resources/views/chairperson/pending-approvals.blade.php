@extends('chairperson.genchair')
@section('title', 'Pending Approvals')

@section('content')
    <div class="main-content">
        <div class="top-nav d-flex justify-content-between align-items-center mb-4">
            <h2>Pending Approvals</h2>
            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm me-2" style="width: 200px;">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search tasks..." id="taskSearch">
                </div>
            </div>
        </div>

        <div class="dashboard-container">
            @if($pendingApprovals->count() > 0)
                <div class="list-group">
                    @foreach($pendingApprovals as $task)
                        <div class="list-group-item mb-3 p-0 border-0">
                            <div class="card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">{{ $task->title }}</h5>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="priority-badge priority-{{ strtolower($task->priority->name) }}">
                                                {{ $task->priority->name }}
                                            </span>
                                            <span class="status-badge status-{{ str_replace(' ', '-', strtolower($task->current_status->name ?? 'no-status')) }}">
                                                {{ $task->current_status->name ?? 'No status' }}
                                            </span>
                                            <small class="text-muted">
                                                Due: {{ $task->due_date->format('M j, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('tasks.review', $task->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Full Details
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $task->description }}</p>
                                    
                                    <div class="mb-3">
                                        <h6 class="small text-muted mb-2">Assigned To:</h6>
                                        <div class="row g-2">
                                            @foreach($task->assignees as $assignee)
                                                <div class="col-md-6">
                                                    <div class="border p-3 rounded">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <img src="{{ $assignee->avatar_url ?? asset('images/default-avatar.png') }}" 
                                                                 alt="{{ $assignee->name }}" 
                                                                 class="rounded-circle me-3" width="40" height="40">
                                                            <div>
                                                                <strong>{{ $assignee->name }}</strong>
                                                                <div class="text-muted small">{{ $assignee->department->name ?? 'No department' }}</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <form action="{{ route('tasks.approve.assignee', ['task' => $task->id, 'user' => $assignee->id]) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    <i class="fas fa-check me-1"></i> Approve
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('tasks.reject.assignee', ['task' => $task->id, 'user' => $assignee->id]) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-times me-1"></i> Reject
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @if($task->attachments->count() > 0)
                                        <div class="mb-3">
                                            <h6 class="small text-muted mb-2">Attachments:</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($task->attachments as $attachment)
                                                    <a href="{{ Storage::url($attachment->path) }}" 
                                                       target="_blank" 
                                                       class="badge bg-light text-dark text-decoration-none">
                                                        <i class="fas fa-paperclip me-1"></i>
                                                        {{ $attachment->filename }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No pending approvals at this time.
                </div>
            @endif

            <div class="d-flex justify-content-center mt-4">
                {{ $pendingApprovals->links() }}
            </div>
        </div>
    </div>

    <style>
        .priority-badge, .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        
        .status-badge.status-no-status {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }
        
        .card {
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>

    <script>
        // Simple search functionality
        document.getElementById('taskSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.card');
            
            cards.forEach(card => {
                const title = card.querySelector('h5').textContent.toLowerCase();
                const description = card.querySelector('.card-text').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.closest('.list-group-item').style.display = 'block';
                } else {
                    card.closest('.list-group-item').style.display = 'none';
                }
            });
        });
    </script>
@endsection