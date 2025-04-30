@extends('chairperson.genchair')
@section('title', 'Upcoming Tasks')

@section('content')
    <div class="main-content">
        <div class="top-nav d-flex justify-content-between align-items-center mb-4">
            <h2>Upcoming Tasks</h2>
            <div>
                <select class="form-select form-select-sm" style="width: auto; display: inline-block;">
                    <option>Next 7 Days</option>
                    <option>Next 14 Days</option>
                    <option selected>Next 30 Days</option>
                </select>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="dashboard-container">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Priority</th>
                            <th>Assigned To</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingDeadlines as $task)
                        @if($task->current_status && $task->current_status->name === 'Pending Approval')
                        <tr>
                            <td>
                                <strong>{{ $task->title }}</strong>
                                <div class="text-muted small">{{ Str::limit($task->description, 50) }}</div>
                            </td>
                            <td>
                                <span class="priority-badge priority-{{ strtolower($task->priority->name) }}">
                                    {{ $task->priority->name }}
                                </span>
                            </td>
                            <td>
                                @if($task->assignees->count() > 1)
                                    Team ({{ $task->assignees->count() }})
                                @else
                                    {{ $task->assignees->first()->name ?? 'Unassigned' }}
                                @endif
                            </td>
                            <td>
                                {{ $task->due_date->format('M j, Y') }}
                                <div class="text-muted small">{{ $task->due_date->diffForHumans() }}</div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ str_replace(' ', '-', strtolower( $task->current_status->name ?? 'No status' )) }}">
                                    {{  $task->current_status->name ?? 'No status'  }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('tasks.review', $task->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Review
                                </a>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $upcomingDeadlines->links() }}
            </div>
        </div>
    </div>
@endsection