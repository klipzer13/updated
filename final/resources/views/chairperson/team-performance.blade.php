@extends('chairperson.genchair')
@section('title', 'Team Performance')

@section('content')
    <div class="main-content">
        <div class="top-nav d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0">Team Performance</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Team Performance</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown me-2">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="timePeriodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        This Quarter
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="timePeriodDropdown">
                        <li><a class="dropdown-item" href="#">This Week</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item active" href="#">This Quarter</a></li>
                    </ul>
                </div>
                <button class="btn btn-sm btn-primary">
                    <i class="fas fa-download me-1"></i> Export
                </button>
            </div>
        </div>

        <div class="dashboard-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex">
                    <div class="input-group input-group-sm me-2" style="width: 200px;">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search team members...">
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> Filters
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="#">All Departments</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Development</a></li>
                            <li><a class="dropdown-item" href="#">Marketing</a></li>
                            <li><a class="dropdown-item" href="#">Operations</a></li>
                        </ul>
                    </div>
                </div>
                <div class="text-muted small">
                    Showing {{ $teamMembers->firstItem() }}-{{ $teamMembers->lastItem() }} of {{ $teamMembers->total() }} members
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Team Member</th>
                            <th>Department</th>
                            <th class="text-center">Completed</th>
                            <th class="text-center">Overdue</th>
                            <th class="text-center">Total</th>
                            <th class="pe-4">Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teamMembers as $member)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ $member->avatar_url ?? asset('images/default-avatar.png') }}" 
                                             alt="{{ $member->name }}" 
                                             class="rounded-circle" width="36" height="36">
                                        @if($member->completed_tasks > 0 && $member->completed_tasks == $member->total_tasks)
                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-2 border-white">
                                            <i class="fas fa-check text-white" style="font-size: 8px;"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $member->name }}</strong>
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $member->department->name ?? 'N/A' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $member->completed_tasks }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger bg-opacity-10 text-danger">{{ $member->overdue_tasks }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $member->total_tasks }}</span>
                            </td>
                            <td class="pe-4">
                                @if($member->total_tasks > 0)
                                    @php
                                        $percentage = ($member->completed_tasks / $member->total_tasks) * 100;
                                        $progressClass = $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar {{ $progressClass }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ round($percentage) }}%</small>
                                    </div>
                                @else
                                    <span class="text-muted small">No tasks</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $teamMembers->firstItem() }}-{{ $teamMembers->lastItem() }} of {{ $teamMembers->total() }} members
                </div>
                <nav aria-label="Page navigation">
                    {{ $teamMembers->links() }}
                </nav>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
        }
        
        .assignee {
            width: 36px;
            height: 36px;
            object-fit: cover;
        }
        
        .progress {
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        
        .badge.bg-light {
            border: 1px solid #dee2e6;
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom-width: 1px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 1rem 0.5rem;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
        }
    </style>
@endsection