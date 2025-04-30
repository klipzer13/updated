@extends('genview')
@section('title', 'Assign Task')

@section('content')
    <style>
        .assign-task-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 40px;
            margin-bottom: 30px;
            border: none;
            transition: transform 0.3s ease;
        }

        .form-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .form-header h4 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            display: block;
            font-size: 0.95rem;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 12px 18px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
            height: auto;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        .priority-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .priority-high {
            background-color: #ff6b6b;
        }

        .priority-medium {
            background-color: #ffd166;
        }

        .priority-low {
            background-color: #06d6a0;
        }

        .assign-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 14px 32px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border-radius: 10px;
            font-size: 1rem;
            width: 100%;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        .assign-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(67, 97, 238, 0.3);
        }

        .user-select-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 8px;
            border: 1px solid #f0f0f0;
            background-color: white;
        }

        .user-select-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .user-select-item.active {
            border-color: var(--primary-color);
            background-color: #f0f7ff;
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.1);
        }

        .user-select-item img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            margin-right: 14px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background-color: #f0f7ff;
        }

        .file-upload-icon {
            font-size: 2.8rem;
            color: var(--primary-color);
            margin-bottom: 15px;
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
                {{ session(key: 'success') }}
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
                    <img src="{{ Auth::user()->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" alt="User Profile" class="rounded-circle"
                        width="40">
                    <span>{{ ucwords(strtolower(Auth::user()->name)) }}</span>
                </div>
            </div>
        </div>

        <!-- Assign Task Content -->
        <div class="assign-task-container">
            <div class="form-header">
                <h4><i class="fas fa-tasks me-2"></i> Assign New Task</h4>
                <p>Delegate work efficiently to your team members with clear instructions</p>
            </div>

            <form id="taskAssignmentForm" method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <label for="taskTitle" class="form-label">Task Title *</label>
                            <input type="text" class="form-control" id="taskTitle" name="title"
                                placeholder="e.g., Design new dashboard interface" required>
                        </div>

                        <div class="mb-4">
                            <label for="taskDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="taskDescription" name="description" rows="6"
                                placeholder="Provide clear instructions, objectives, and any relevant details..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="dueDate" class="form-label">Due Date *</label>
                                <input type="date" class="form-control" id="dueDate" name="due_date"
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="taskPriority" class="form-label">Priority *</label>
                                <select class="form-select" id="taskPriority" name="priority_id" required>
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority->id }}">
                                            <span class="priority-indicator priority-{{ $priority->name }}"></span>
                                            {{ ucfirst($priority->name) }} Priority
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Attachments</label>
                            <div class="file-upload-area" onclick="document.getElementById('attachments').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h5>Drag & drop files here</h5>
                                <p class="text-muted mb-3">Supports PDF, DOCX, JPG, PNG up to 10MB</p>
                                <button type="button" class="btn btn-outline-primary btn-sm px-4">Browse Files</button>
                                <input type="file" id="attachments" name="attachments[]" multiple style="display: none;"
                                    onchange="updateFilePreview(this)">
                            </div>
                            <div id="filePreview" class="mt-3 d-none">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paperclip me-2"></i>
                                    <span id="fileName" class="small"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-4">
                            <label class="form-label">Assign To *</label>
                            <div class="border rounded-3 p-3 bg-white" style="max-height: 600px; overflow-y: auto;">
                                @foreach($departments as $department)
                                    <div class="department-group">
                                        <div class="department-header d-flex justify-content-between align-items-center mb-3">
                                            <span class="department-title fw-bold text-primary">
                                                <i class="fas fa-building me-2"></i>{{ $department->name }}
                                                <small class="text-muted d-block">{{ $department->description }}</small>
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-primary select-all-btn"
                                                onclick="selectAllFromDepartment('department-{{ $department->id }}')">
                                                <i class="fas fa-check-double me-1"></i> Select All
                                            </button>
                                        </div>
                                        @foreach($department->users as $user)
                                            @if($user->id !== Auth::id())
                                                <div class="user-select-item" onclick="toggleUserSelection(this, '{{ $user->id }}')"
                                                    data-department="department-{{ $department->id }}">
                                                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                                        alt="{{ $user->name }}" width="44" height="44">
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small>{{ $user->role->name ?? 'Team Member' }}</small>
                                                    </div>
                                                    <input type="checkbox" name="assignees[]" value="{{ $user->id }}"
                                                        style="display: none;">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary assign-btn">
                                <i class="fas fa-paper-plane me-2"></i> Assign Task
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Track selected users
        let selectedUsers = [];

        function toggleUserSelection(element, userId) {
            element.classList.toggle('active');
            const checkbox = element.querySelector('input[type="checkbox"]');

            if (element.classList.contains('active')) {
                checkbox.checked = true;
                if (!selectedUsers.includes(userId)) {
                    selectedUsers.push(userId);
                }
            } else {
                checkbox.checked = false;
                selectedUsers = selectedUsers.filter(id => id !== userId);
            }
        }

        function selectAllFromDepartment(departmentClass) {
            const departmentUsers = document.querySelectorAll(`[data-department="${departmentClass}"]`);
            let allSelected = true;

            // Check if all users are already selected
            departmentUsers.forEach(user => {
                if (!user.classList.contains('active')) {
                    allSelected = false;
                }
            });

            // Toggle selection based on current state
            departmentUsers.forEach(user => {
                const checkbox = user.querySelector('input[type="checkbox"]');
                if (allSelected) {
                    user.classList.remove('active');
                    checkbox.checked = false;
                    selectedUsers = selectedUsers.filter(id => id !== checkbox.value);
                } else {
                    user.classList.add('active');
                    checkbox.checked = true;
                    if (!selectedUsers.includes(checkbox.value)) {
                        selectedUsers.push(checkbox.value);
                    }
                }
            });

            // Update button text
            const button = document.querySelector(`.department-header button[data-department="${departmentClass}"]`);
            if (button) {
                button.textContent = allSelected ? "Deselect All" : "Select All";
            }
        }

        function updateFilePreview(input) {
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');

            if (input.files.length > 0) {
                filePreview.classList.remove('d-none');
                fileName.textContent = `${input.files.length} file(s) selected`;
            } else {
                filePreview.classList.add('d-none');
            }
        }

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