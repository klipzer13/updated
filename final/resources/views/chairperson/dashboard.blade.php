@extends('chairperson.genchair')
@section('tittle', 'Dashboard')
@section('content')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <!-- <div class="top-nav d-flex justify-content-between align-items-center mb-4">
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
        </div> -->

        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-calendar me-2"></i> Task Calendar</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-2"></i> Add Task
            </button>
        </div>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card">
                    <div class="stat-number">{{ $activeTasks }}</div>
                    <div class="stat-label">Active Tasks</div>
                    <div class="progress mt-2" style="height: 8px;">
                        <!-- <div class="progress-bar" style="width: {{ $activeTasks > 0 ? ($completedPercentage) : 0 }}%"></div> -->
                    </div>
                    <small class="text-muted">{{ $completedPercentage }}% completed</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card">
                    <div class="stat-number">{{ $overdueTasks }}</div>
                    <div class="stat-label">Overdue Tasks</div>
                    <div class="progress mt-2" style="height: 8px;">
                        <!-- <div class="progress-bar bg-danger" style="width: {{ $overdueTasks > 0 ? 100 : 0 }}%"></div> -->
                    </div>
                    <small class="text-muted">Need attention</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card">
                    <div class="stat-number">{{ $completedTasks }}</div>
                    <div class="stat-label">Completed</div>
                    <div class="progress mt-2" style="height: 8px;">
                        <!-- <div class="progress-bar bg-success" style="width: {{ $completedPercentage }}%"></div> -->
                    </div>
                    <small class="text-muted">This month</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card">
                    <div class="stat-number">{{ $hoursLogged }}</div>
                    <div class="stat-label">Hours Logged</div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: 60%"></div>
                    </div>
                    <small class="text-muted">This week</small>
                </div>
            </div>
        </div>

        <!-- Task Calendar -->
        <div class="dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">My Task Calendar</h5>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary" id="prevWeek">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="today">
                        Today
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="nextWeek">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div id="taskCalendar"></div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="taskTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="taskDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="dueDate" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="dueDate" name="due_date" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="taskPriority" class="form-label">Priority</label>
                            <select class="form-select" id="taskPriority" name="priority_id" required>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskAssignees" class="form-label">Assignees</label>
                            <select class="form-select" id="taskAssignees" name="assignees[]" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Detail Modal -->
    <div class="modal fade task-modal" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskDetailModalLabel">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 id="detailTaskTitle"></h4>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="priority-badge" id="detailPriority"></span>
                                <span class="badge bg-secondary" id="detailStatus"></span>
                            </div>
                            <p id="detailDescription" class="mb-4"></p>

                            <div class="task-meta mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong><i class="fas fa-user me-2"></i>Assigned by:</strong>
                                            <span id="detailCreator"></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong><i class="fas fa-calendar-plus me-2"></i>Start Date:</strong>
                                            <span id="detailStartDate"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong><i class="fas fa-calendar-check me-2"></i>Due Date:</strong>
                                            <span id="detailDueDate"></span>
                                        </div>
                                        <div class="mb-3">
                                            <strong><i class="fas fa-users me-2"></i>Assignees:</strong>
                                            <span id="detailAssignees"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Section -->
                            <div class="progress-section mb-4">
                                <div class="progress mb-2">
                                    <div class="progress-bar" id="detailProgress" role="progressbar" style="width: 0%">
                                    </div>
                                </div>
                                <small class="text-muted" id="progressText">Task progress</small>
                            </div>

                            <!-- Completion Section -->
                            <div class="completion-section mb-4" id="completionSection" style="display: none;">
                                <hr>
                                <h5><i class="fas fa-check-circle me-2"></i>Submit Completion</h5>
                                <div class="mb-3">
                                    <label for="completionComments" class="form-label">Completion Notes</label>
                                    <textarea class="form-control" id="completionComments" rows="3"
                                        placeholder="Describe what you've completed and any important details"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="completionAttachments" class="form-label">Supporting Documents</label>
                                    <input type="file" class="form-control" id="completionAttachments"
                                        name="completion_attachments[]" multiple>
                                    <small class="text-muted">Upload any files that prove task completion</small>
                                </div>
                            </div>

                            <!-- Comments Section - Fixed Implementation -->
                            <div class="comments-section mb-4">
                                <hr>
                                <h5><i class="fas fa-comments me-2"></i>Task Comments</h5>
                                <div id="commentsList" class="mb-3">
                                    <!-- Comments will be loaded here -->
                                </div>
                                <div class="add-comment">
                                    <form id="commentForm">
                                        @csrf
                                        <input type="hidden" id="commentTaskId" name="task_id">
                                        <textarea class="form-control mb-2" id="newComment" name="comment"
                                            placeholder="Add a comment..." required></textarea>
                                        <button type="submit" class="btn btn-sm btn-primary">Add Comment</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Attachments Section -->
                            <div class="attachments-section">
                                <h5><i class="fas fa-paperclip me-2"></i>Attachments</h5>
                                <div id="attachmentsList" class="list-group">
                                    <!-- Attachments will be loaded here -->
                                </div>

                                <!-- Add Attachment Button -->
                                <div class="add-attachment mt-3" id="addAttachmentSection">
                                    <form id="attachmentForm">
                                        @csrf
                                        <input type="hidden" id="attachmentTaskId" name="task_id">
                                        <input type="file" class="form-control mb-2" id="newAttachment" name="attachments[]"
                                            multiple>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Upload File</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div id="completionControls">
                        <!-- Completion checkbox will be placed here dynamically -->
                    </div>
                    <button type="button" class="btn btn-primary" id="updateTask">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get tasks from server via AJAX
            fetch('{{ route("api.tasks") }}')
                .then(response => response.json())
                .then(data => {
                    initializeCalendar(data);
                })
                .catch(error => {
                    console.error('Error loading tasks:', error);
                    alert('Error loading tasks. Please refresh the page.');
                });

            function initializeCalendar(taskData) {
                const calendarEl = document.getElementById('taskCalendar');
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: taskData.map(task => ({
                        id: task.id,
                        title: task.title,
                        start: task.start_date || task.created_at,
                        end: task.due_date,
                        className: `fc-event-${task.priority.name.toLowerCase()}-priority`,
                        extendedProps: {
                            description: task.description,
                            priority: task.priority,
                            status: task.user_status || 'pending',
                            creator: task.creator,
                            assignees: task.users || [],
                            attachments: task.attachments || [],
                            comments: task.comments || [],
                            task_id: task.id,
                            is_participant: task.is_participant
                        }
                    })),
                    eventClick: function (info) {
                        const task = info.event;
                        const props = task.extendedProps;

                        // Set the task ID for forms
                        document.getElementById('commentTaskId').value = props.task_id;
                        document.getElementById('attachmentTaskId').value = props.task_id;

                        // Populate task details (same as before)
                        document.getElementById('detailTaskTitle').textContent = task.title;
                        document.getElementById('detailDescription').textContent = props.description || 'No description provided';
                        document.getElementById('detailCreator').textContent = props.creator ? props.creator.name : 'Unknown';

                        // Set priority badge
                        const priorityBadge = document.getElementById('detailPriority');
                        const priorityName = props.priority.name || 'Medium';
                        priorityBadge.textContent = priorityName;
                        priorityBadge.className = 'priority-badge ';
                        if (priorityName.toLowerCase() === 'high') {
                            priorityBadge.className += 'priority-high';
                        } else if (priorityName.toLowerCase() === 'medium') {
                            priorityBadge.className += 'priority-medium';
                        } else {
                            priorityBadge.className += 'priority-low';
                        }

                        // Set status
                        const statusElement = document.getElementById('detailStatus');
                        statusElement.textContent = props.status;

                        // Format dates
                        const startDate = task.start ? new Date(task.start) : new Date();
                        const dueDate = task.end ? new Date(task.end) : new Date(task.start);

                        document.getElementById('detailStartDate').textContent = startDate.toLocaleDateString();
                        document.getElementById('detailDueDate').textContent = dueDate.toLocaleDateString();

                        // Set assignees
                        const assigneesElement = document.getElementById('detailAssignees');
                        assigneesElement.innerHTML = '';

                        if (props.assignees && props.assignees.length > 0) {
                            const assigneeNames = props.assignees.map(user => user.name).join(', ');
                            assigneesElement.textContent = assigneeNames;
                        } else {
                            assigneesElement.textContent = 'No assignees';
                        }

                        // Set progress
                        const progressBar = document.getElementById('detailProgress');
                        const progressText = document.getElementById('progressText');
                        if (props.status && props.status.toLowerCase() === 'completed') {
                            progressBar.style.width = '100%';
                            progressBar.textContent = 'Completed';
                            progressBar.className = 'progress-bar bg-success';
                            progressText.textContent = 'Task completed';
                        } else if (props.status && props.status.toLowerCase() === 'in_progress') {
                            progressBar.style.width = '60%';
                            progressBar.textContent = 'In Progress';
                            progressBar.className = 'progress-bar bg-warning';
                            progressText.textContent = 'Task in progress - completion submitted';
                        } else {
                            progressBar.style.width = '30%';
                            progressBar.textContent = 'Pending';
                            progressBar.className = 'progress-bar bg-info';
                            progressText.textContent = 'Task pending';
                        }

                        // Load attachments
                        const attachmentsList = document.getElementById('attachmentsList');
                        attachmentsList.innerHTML = '';

                        if (props.attachments && props.attachments.length > 0) {
                            props.attachments.forEach(attachment => {
                                const fileLink = document.createElement('a');
                                fileLink.href = `/storage/${attachment.path}`;
                                fileLink.target = '_blank';
                                fileLink.className = 'list-group-item list-group-item-action';

                                const fileIcon = getFileIcon(attachment.type);
                                const uploadDate = new Date(attachment.created_at).toLocaleDateString();

                                fileLink.innerHTML = `
                                <div class="d-flex w-100 justify-content-between">
                                    <div>
                                        <i class="${fileIcon} me-2"></i>
                                        ${attachment.filename}
                                    </div>
                                    <small>${uploadDate}</small>
                                </div>
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="text-muted">${formatFileSize(attachment.size)}</small>
                                    <small>Uploaded by: ${attachment.uploaded_by}</small>
                                </div>
                            `;

                                attachmentsList.appendChild(fileLink);
                            });
                        } else {
                            const noAttachments = document.createElement('div');
                            noAttachments.className = 'list-group-item';
                            noAttachments.textContent = 'No attachments yet';
                            attachmentsList.appendChild(noAttachments);
                        }

                        // Load comments - Fixed Implementation
                        const commentsList = document.getElementById('commentsList');
                        commentsList.innerHTML = '';

                        if (props.comments && props.comments.length > 0) {
                            props.comments.forEach(comment => {
                                const commentElement = document.createElement('div');
                                commentElement.className = 'card mb-2';
                                commentElement.innerHTML = `
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between">
                                        <strong>${comment.user.name}</strong>
                                        <small class="text-muted">${new Date(comment.created_at).toLocaleString()}</small>
                                    </div>
                                    <p class="mb-0 mt-1">${comment.comment}</p>
                                </div>
                            `;
                                commentsList.appendChild(commentElement);
                            });
                        } else {
                            const noComments = document.createElement('div');
                            noComments.className = 'alert alert-info';
                            noComments.textContent = 'No comments yet';
                            commentsList.appendChild(noComments);
                        }

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
                        modal.show();
                    }
                });

                calendar.render();

                // Navigation buttons
                document.getElementById('prevWeek').addEventListener('click', function () {
                    calendar.prev();
                });

                document.getElementById('today').addEventListener('click', function () {
                    calendar.today();
                });

                document.getElementById('nextWeek').addEventListener('click', function () {
                    calendar.next();
                });
            }

            // Fixed Comment Submission
            document.getElementById('commentForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const taskId = document.getElementById('commentTaskId').value;

                fetch(`/tasks/${taskId}/comments`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Clear the comment field
                            document.getElementById('newComment').value = '';

                            // Refresh the calendar to show new comment
                            const calendarEl = document.getElementById('taskCalendar');
                            const calendar = FullCalendar.getCalendar(calendarEl);
                            calendar.refetchEvents();

                            // Show success message
                            alert('Comment added successfully');
                        }
                    })
                    .catch(error => {
                        console.error('Error adding comment:', error);
                        alert('Error adding comment. Please try again.');
                    });
            });

            // File Attachment Submission
            document.getElementById('attachmentForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const taskId = document.getElementById('attachmentTaskId').value;

                fetch(`/tasks/${taskId}/attachments`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Clear the file input
                            document.getElementById('newAttachment').value = '';

                            // Refresh the calendar to show new attachment
                            const calendarEl = document.getElementById('taskCalendar');
                            const calendar = FullCalendar.getCalendar(calendarEl);
                            calendar.refetchEvents();

                            // Show success message
                            alert('Files uploaded successfully');
                        }
                    })
                    .catch(error => {
                        console.error('Error uploading files:', error);
                        alert('Error uploading files. Please try again.');
                    });
            });

            // Toggle sidebar on mobile
            document.getElementById('sidebarToggle').addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('mainContent').classList.toggle('active');
            });

            // Helper functions
            function getFileIcon(mimeType) {
                if (!mimeType) return 'fas fa-file';

                if (mimeType.startsWith('image/')) {
                    return 'fas fa-file-image';
                } else if (mimeType === 'application/pdf') {
                    return 'fas fa-file-pdf';
                } else if (mimeType.startsWith('video/')) {
                    return 'fas fa-file-video';
                } else if (mimeType.startsWith('audio/')) {
                    return 'fas fa-file-audio';
                } else if (mimeType.startsWith('application/vnd.ms-excel') || mimeType.includes('spreadsheetml')) {
                    return 'fas fa-file-excel';
                } else if (mimeType.startsWith('application/msword') || mimeType.includes('wordprocessingml')) {
                    return 'fas fa-file-word';
                } else if (mimeType.startsWith('application/zip') || mimeType.includes('compressed')) {
                    return 'fas fa-file-archive';
                } else {
                    return 'fas fa-file';
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
@endsection