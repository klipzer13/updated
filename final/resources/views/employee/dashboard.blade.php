@extends('employee.genemp')
@section('tittle', 'Dashboard')
@section('content')
    <!-- Main Content -->
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

        <!-- Dashboard Header -->


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
            <div class="card-header d-flex justify-content-between align-items-center">
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
                                    <!-- Attachments will be loaded here -->
                                    <div class="text-center py-3 w-100">
                                        <p class="text-muted">No attachments available</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <h6 class="fw-semibold mb-3 text-uppercase small text-muted">Comments</h6>
                                <div id="task-modal-comments" class="comment-section bg-light rounded p-3">
                                    <!-- Comments will be loaded here -->
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

                    <div id="task-error-container" style="display: none;">
                        <!-- Error content here -->
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <style>
        /* Calendar Event Styling */
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            border-left: 4px solid #3a87ad;
            padding: 2px 5px;
            margin-bottom: 2px;
            font-size: 0.85em;
        }

        .priority-badge {
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-weight: 600;
        }

        .priority-high {
            background-color: #f8d7da;
            color: #721c24;
        }

        .priority-medium {
            background-color: #fff3cd;
            color: #856404;
        }

        .priority-low {
            background-color: #d4edda;
            color: #155724;
        }

        /* Status-specific event styling */
        .fc-event-completed {
            opacity: 0.7;
            border-left: 4px solid #28a745 !important;
        }

        .fc-event-overdue {
            border-left: 4px solid #dc3545 !important;
        }

        .fc-event-in-progress {
            border-left: 4px solid #ffc107 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the task modal
            const taskModal = new TaskModal();

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
                        start: task.due_date,
                        end: task.due_date,
                        allDay: true,
                        className: `fc-event-${task.priority.name.toLowerCase()}-priority ${getStatusClass(task.status)}`,
                        extendedProps: {
                            description: task.description,
                            priority: task.priority,
                            status: task.status || 'pending',
                            creator: task.creator,
                            assignees: task.users || [],
                            attachments: task.attachments || [],
                            comments: task.comments || [],
                            task_id: task.id,
                            is_participant: task.is_participant
                        }
                    })),
                    dateClick: function (info) {
                        // Get all tasks for the clicked date
                        const tasksOnDate = taskData.filter(task => {
                            const taskDate = new Date(task.due_date).toDateString();
                            const clickedDate = info.date.toDateString();
                            return taskDate === clickedDate;
                        });

                        if (tasksOnDate.length > 0) {
                            // Show the first task in the modal (you could implement a list view if needed)
                            taskModal.showTaskLoadingState();
                            taskModal.loadTaskDetails(tasksOnDate[0].id);
                            document.getElementById('submission_task_id').value = tasksOnDate[0].id;
                            taskModal.modal.show();
                        } else {
                            // No tasks on this date
                            alert('No tasks scheduled for ' + info.dateStr);
                        }
                    },
                    eventClick: function (info) {
                        // Show the clicked task in the modal
                        const task = info.event;
                        taskModal.showTaskLoadingState();
                        taskModal.loadTaskDetails(task.extendedProps.task_id);
                        document.getElementById('submission_task_id').value = task.extendedProps.task_id;
                        taskModal.modal.show();
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

            // Helper function to get status class
            function getStatusClass(status) {
                if (!status) return 'fc-event-pending';

                switch (status.toLowerCase()) {
                    case 'completed': return 'fc-event-completed';
                    case 'overdue': return 'fc-event-overdue';
                    case 'in_progress':
                    case 'in progress':
                        return 'fc-event-in-progress';
                    default: return 'fc-event-pending';
                }
            }

            // Toggle sidebar on mobile
            document.getElementById('sidebarToggle').addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('mainContent').classList.toggle('active');
            });

        });
    </script>
    <script>
        class TaskModal {
            constructor() {
                this.modal = new bootstrap.Modal(document.getElementById('taskViewModal'));
                this.initializeEventListeners();
            }

            initializeEventListeners() {
                // Event delegation for view task buttons
                document.addEventListener('click', (e) => {
                    if (e.target.closest('.view-task-btn')) {
                        const button = e.target.closest('.view-task-btn');
                        const taskId = button.getAttribute('data-task-id');
                        this.showTaskLoadingState();
                        this.loadTaskDetails(taskId);
                        document.getElementById('submission_task_id').value = taskId;
                        this.modal.show();
                    }
                });

                // Sort functionality
                const sortSelect = document.getElementById('sortSelect');
                if (sortSelect) {
                    sortSelect.addEventListener('change', function () {
                        TaskModal.sortTasks(this.value);
                    });
                }
            }

            showTaskLoadingState() {
                document.getElementById('task-loading-indicator').style.display = 'block';
                document.getElementById('task-details-container').style.display = 'none';
                document.getElementById('task-error-container').style.display = 'none';
            }

            showTaskDetails() {
                document.getElementById('task-loading-indicator').style.display = 'none';
                document.getElementById('task-details-container').style.display = 'block';
                document.getElementById('task-error-container').style.display = 'none';
            }

            showTaskError(errorMessage) {
                document.getElementById('task-loading-indicator').style.display = 'none';
                document.getElementById('task-details-container').style.display = 'none';
                document.getElementById('task-error-container').style.display = 'block';
                document.getElementById('task-error-message').textContent = errorMessage || 'Failed to load task details.';
            }

            loadTaskDetails(taskId) {
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
                        document.getElementById('task-modal-status').className = `badge rounded-pill bg-${this.getStatusClass(task.status)}`;

                        // Hide submission form if task is completed
                        const submissionForm = document.getElementById('task-submission-form-container');
                        if (task.status.toLowerCase() === 'completed') {
                            submissionForm.style.display = 'none';
                        } else {
                            submissionForm.style.display = 'block';
                        }

                        if (task.priority) {
                            document.getElementById('task-modal-priority').textContent = task.priority.name;
                            document.getElementById('task-modal-priority').className = `badge rounded-pill bg-${this.getPriorityClass(task.priority.name)}`;
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

                        this.showTaskDetails();
                    })
                    .catch(error => {
                        console.error('Error loading task details:', error);
                        this.showTaskError(error.message || 'Failed to load task details. Please try again.');
                    });
            }

            getStatusClass(status) {
                const statusClasses = {
                    'pending': 'info',
                    'in_progress': 'warning',
                    'completed': 'success',
                    'overdue': 'danger',
                    'rejected': 'danger',
                    'pending_approval': 'primary'
                };
                return statusClasses[status.toLowerCase()] || 'secondary';
            }

            getPriorityClass(priority) {
                const priorityClasses = {
                    'high': 'danger',
                    'medium': 'warning',
                    'low': 'success'
                };
                return priorityClasses[priority.toLowerCase()] || 'secondary';
            }

            static sortTasks(criteria) {
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
        }

        // Initialize the TaskModal when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Auto-hide notification
            const notification = document.querySelector('.notification-toast');
            if (notification) {
                setTimeout(() => {
                    notification.classList.remove('show');
                    notification.classList.add('hide');
                }, 5000);
            }

            // Initialize task modal if it exists on the page
            if (document.getElementById('taskViewModal')) {
                new TaskModal();
            }
        });
    </script>
@endsection