@extends('employee.genemp')
@section('title', 'My Tasks')
@section('content')



    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <div class="top-nav d-flex justify-content-between align-items-center">
            <button class="sidebar-collapse-btn d-lg-none btn btn-light rounded-circle p-2" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex align-items-center">
                <div class="position-relative me-3">
                    <button class="btn btn-light rounded-circle p-2 position-relative">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                </div>
                <div class="user-profile">
                    <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="User">
                    <span>Sarah Johnson</span>
                    <i class="fas fa-chevron-down ms-2" style="font-size: 0.8rem;"></i>
                </div>
            </div>
        </div>
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="fas fa-list-check text-primary me-2"></i> My Tasks</h3>
                <p class="text-muted mb-0">Manage your current workload and track progress</p>
            </div>
            <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-2"></i> Add Task
            </button>
        </div>
        
        <!-- Filter Controls -->
        <div class="filter-controls glass-card mb-4">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="taskFilter" class="form-label">Filter by Status</label>
                    <select class="form-select" id="taskFilter">
                        <option value="all">All Tasks</option>
                        <option value="active">Active Tasks</option>
                        <option value="completed">Completed Tasks</option>
                        <option value="overdue">Overdue Tasks</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="priorityFilter" class="form-label">Filter by Priority</label>
                    <select class="form-select" id="priorityFilter">
                        <option value="all">All Priorities</option>
                        <option value="high">High Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="low">Low Priority</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="projectFilter" class="form-label">Filter by Project</label>
                    <select class="form-select" id="projectFilter">
                        <option value="all">All Projects</option>
                        <option value="website">Website Redesign</option>
                        <option value="mobile">Mobile App</option>
                        <option value="marketing">Marketing</option>
                        <option value="api">API Integration</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Task List -->
        <div id="taskList">
            <!-- High Priority Task -->
            <div class="task-card glass-card high-priority mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="task-title">
                            Complete project proposal
                            <span class="task-badge bg-danger ms-2">High Priority</span>
                        </h5>
                        <div class="task-meta">
                            <span><i class="fas fa-project-diagram me-1"></i> Website Redesign</span> • 
                            <span><i class="fas fa-calendar-day me-1"></i> Due: Today, 2:00 PM</span>
                        </div>
                        <div class="task-description">
                            Prepare the final draft of the project proposal document for client review and approval.
                        </div>
                        <div class="task-progress">
                            <div class="task-progress-bar bg-danger" style="width: 30%"></div>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success complete-task">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Medium Priority Task -->
            <div class="task-card glass-card medium-priority mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="task-title">
                            Team sync meeting
                            <span class="task-badge bg-warning text-dark ms-2">Medium Priority</span>
                        </h5>
                        <div class="task-meta">
                            <span><i class="fas fa-project-diagram me-1"></i> General</span> • 
                            <span><i class="fas fa-calendar-day me-1"></i> Due: Tomorrow, 9:30 AM</span>
                        </div>
                        <div class="task-description">
                            Weekly team sync meeting to discuss progress, blockers, and upcoming tasks.
                        </div>
                        <div class="task-progress">
                            <div class="task-progress-bar bg-warning" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success complete-task">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Low Priority Task (Completed) -->
            <div class="task-card glass-card low-priority completed mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="task-title">
                            Update API documentation
                            <span class="task-badge bg-success text-white ms-2">Completed</span>
                        </h5>
                        <div class="task-meta">
                            <span><i class="fas fa-project-diagram me-1"></i> API Integration</span> • 
                            <span><i class="fas fa-calendar-day me-1"></i> Completed: Jun 12</span>
                        </div>
                        <div class="task-description">
                            Document new endpoints and update existing documentation for version 2.1.
                        </div>
                        <div class="task-progress">
                            <div class="task-progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary undo-complete">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Medium Priority Task -->
            <div class="task-card glass-card medium-priority mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="task-title">
                            Code review session
                            <span class="task-badge bg-warning text-dark ms-2">Medium Priority</span>
                        </h5>
                        <div class="task-meta">
                            <span><i class="fas fa-project-diagram me-1"></i> Mobile App</span> • 
                            <span><i class="fas fa-calendar-day me-1"></i> Due: Jun 15, 3:00 PM</span>
                        </div>
                        <div class="task-description">
                            Review pull requests and provide feedback to team members on the new authentication flow.
                        </div>
                        <div class="task-progress">
                            <div class="task-progress-bar bg-warning" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success complete-task">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- High Priority Task (Overdue) -->
            <div class="task-card glass-card high-priority mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="task-title">
                            Client presentation
                            <span class="task-badge bg-danger ms-2">Overdue</span>
                        </h5>
                        <div class="task-meta">
                            <span><i class="fas fa-project-diagram me-1"></i> Marketing</span> • 
                            <span><i class="fas fa-calendar-day me-1"></i> Due: Jun 10, 11:00 AM</span>
                        </div>
                        <div class="task-description">
                            Prepare and deliver presentation to key stakeholders about Q2 results.
                        </div>
                        <div class="task-progress">
                            <div class="task-progress-bar bg-danger" style="width: 15%"></div>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success complete-task">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Empty State (hidden by default) -->
        <div class="empty-state glass-card" id="emptyState" style="display: none;">
            <i class="fas fa-tasks"></i>
            <h4>No tasks found</h4>
            <p class="text-muted">Try adjusting your filters or create a new task</p>
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-2"></i> Create Task
            </button>
        </div>
        
        <!-- Pagination -->
        <nav aria-label="Task pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
    
    <!-- Add Task Modal -->
    <div class="modal fade task-modal" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Create New Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="taskTitle" placeholder="Enter task name" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="taskDescription" rows="3" placeholder="Add details about the task"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" id="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="dueDate" class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control" id="dueDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="taskPriority" class="form-label">Priority</label>
                            <select class="form-select" id="taskPriority" required>
                                <option value="low">Low Priority</option>
                                <option value="medium" selected>Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskProject" class="form-label">Project</label>
                            <select class="form-select" id="taskProject">
                                <option value="">Select Project</option>
                                <option value="website">Website Redesign</option>
                                <option value="mobile">Mobile App Development</option>
                                <option value="marketing">Marketing Campaign</option>
                                <option value="api">API Integration</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTask">
                        <i class="fas fa-save me-2"></i>Save Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('mainContent').classList.toggle('active');
        });
        
        // Task completion functionality
        document.querySelectorAll('.complete-task').forEach(button => {
            button.addEventListener('click', function() {
                const taskCard = this.closest('.task-card');
                taskCard.classList.add('completed');
                
                // Update progress bar
                const progressBar = taskCard.querySelector('.task-progress-bar');
                progressBar.style.width = '100%';
                progressBar.classList.remove('bg-danger', 'bg-warning');
                progressBar.classList.add('bg-success');
                
                // Update badge
                const badge = taskCard.querySelector('.task-badge');
                badge.textContent = 'Completed';
                badge.className = 'task-badge bg-success text-white ms-2';
                
                // Update button
                this.innerHTML = '<i class="fas fa-undo"></i>';
                this.classList.remove('btn-outline-success', 'complete-task');
                this.classList.add('btn-outline-secondary', 'undo-complete');
                
                // Add event listener for undo
                this.addEventListener('click', undoComplete);
            });
        });
        
        // Undo completion functionality
        function undoComplete() {
            const taskCard = this.closest('.task-card');
            taskCard.classList.remove('completed');
            
            // Update progress bar
            const progressBar = taskCard.querySelector('.task-progress-bar');
            progressBar.style.width = '30%';
            progressBar.classList.remove('bg-success');
            
            // Determine original priority
            if(taskCard.classList.contains('high-priority')) {
                progressBar.classList.add('bg-danger');
                var priorityText = 'High Priority';
                var priorityClass = 'bg-danger';
            } else if(taskCard.classList.contains('medium-priority')) {
                progressBar.classList.add('bg-warning');
                var priorityText = 'Medium Priority';
                var priorityClass = 'bg-warning text-dark';
            } else {
                progressBar.classList.add('bg-success');
                var priorityText = 'Low Priority';
                var priorityClass = 'bg-success text-white';
            }
            
            // Update badge
            const badge = taskCard.querySelector('.task-badge');
            badge.textContent = priorityText;
            badge.className = `task-badge ${priorityClass} ms-2`;
            
            // Update button
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.classList.remove('btn-outline-secondary', 'undo-complete');
            this.classList.add('btn-outline-success', 'complete-task');
            
            // Add event listener for complete
            this.addEventListener('click', function() {
                const taskCard = this.closest('.task-card');
                taskCard.classList.add('completed');
                
                // Update progress bar
                const progressBar = taskCard.querySelector('.task-progress-bar');
                progressBar.style.width = '100%';
                progressBar.classList.remove('bg-danger', 'bg-warning');
                progressBar.classList.add('bg-success');
                
                // Update badge
                const badge = taskCard.querySelector('.task-badge');
                badge.textContent = 'Completed';
                badge.className = 'task-badge bg-success text-white ms-2';
                
                // Update button
                this.innerHTML = '<i class="fas fa-undo"></i>';
                this.classList.remove('btn-outline-success', 'complete-task');
                this.classList.add('btn-outline-secondary', 'undo-complete');
                
                // Add event listener for undo
                this.addEventListener('click', undoComplete);
            });
        }
        
        // Filter functionality
        document.getElementById('taskFilter').addEventListener('change', filterTasks);
        document.getElementById('priorityFilter').addEventListener('change', filterTasks);
        document.getElementById('projectFilter').addEventListener('change', filterTasks);
        
        function filterTasks() {
            const statusFilter = document.getElementById('taskFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const projectFilter = document.getElementById('projectFilter').value;
            
            const taskCards = document.querySelectorAll('.task-card');
            let visibleCount = 0;
            
            taskCards.forEach(card => {
                const isCompleted = card.classList.contains('completed');
                const priority = card.classList.contains('high-priority') ? 'high' : 
                                card.classList.contains('medium-priority') ? 'medium' : 'low';
                
                // Get project from card content
                const projectElement = card.querySelector('.task-meta span:first-child');
                const projectText = projectElement.textContent.trim();
                let project = '';
                if(projectText.includes('Website')) project = 'website';
                else if(projectText.includes('Mobile')) project = 'mobile';
                else if(projectText.includes('Marketing')) project = 'marketing';
                else if(projectText.includes('API')) project = 'api';
                
                // Check status filter
                let statusMatch = true;
                if(statusFilter === 'active' && isCompleted) statusMatch = false;
                if(statusFilter === 'completed' && !isCompleted) statusMatch = false;
                if(statusFilter === 'overdue') {
                    // In a real app, you would check actual due dates
                    statusMatch = card.querySelector('.task-badge').textContent === 'Overdue';
                }
                
                // Check priority filter
                const priorityMatch = priorityFilter === 'all' || priority === priorityFilter;
                
                // Check project filter
                const projectMatch = projectFilter === 'all' || project === projectFilter;
                
                // Show/hide card based on filters
                if(statusMatch && priorityMatch && projectMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show empty state if no tasks match filters
            const emptyState = document.getElementById('emptyState');
            if(visibleCount === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }
        
        // Save new task
        document.getElementById('saveTask').addEventListener('click', function() {
            const title = document.getElementById('taskTitle').value;
            const description = document.getElementById('taskDescription').value;
            const startDate = document.getElementById('startDate').value;
            const dueDate = document.getElementById('dueDate').value;
            const priority = document.getElementById('taskPriority').value;
            const project = document.getElementById('taskProject').value;
            
            if(title && startDate && dueDate) {
                // In a real app, you would add this to your tasks array and possibly save to a database
                // For this demo, we'll just show an alert
                alert('Task created successfully!');
                
                // Reset form
                document.getElementById('taskForm').reset();
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
                modal.hide();
            }
        });
    </script>
@endsection