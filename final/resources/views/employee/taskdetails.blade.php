@extends('genview')
@section('title', 'Task Details')

@section('content')
    <style>
        .task-detail-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 30px;
            margin-bottom: 30px;
            border: none;
        }

        .section-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h4 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-header h4 i {
            margin-right: 10px;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .task-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .task-meta {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .task-meta-item {
            margin-right: 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .task-meta-item i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        .priority-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 10px;
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

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-in-progress {
            background-color: #fff3e0;
            color: #fb8c00;
        }

        .status-completed {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .status-overdue {
            background-color: #ffebee;
            color: #f44336;
        }

        .task-description {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            line-height: 1.6;
            color: #495057;
        }

        .task-section {
            margin-bottom: 30px;
        }

        .task-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .task-section-title i {
            margin-right: 10px;
        }

        .attachment-thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #eee;
            cursor: pointer;
            transition: all 0.3s;
        }

        .attachment-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .comment-box {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: white;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .comment-user {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #2c3e50;
        }

        .comment-user img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-time {
            font-size: 0.8rem;
            color: #7f8c8d;
        }

        .comment-text {
            color: #495057;
            line-height: 1.5;
        }

        .reply-form {
            margin-top: 20px;
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f8f9fa;
            margin-bottom: 15px;
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background-color: #f0f7ff;
        }

        .file-upload-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .task-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-complete {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border-radius: 10px;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        .btn-complete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(67, 97, 238, 0.3);
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

        <!-- Task Detail Content -->
        <div class="task-detail-container">
            <div class="section-header">
                <h4><i class="fas fa-tasks"></i> Task Details</h4>
                <a href="#" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Tasks
                </a>
            </div>

            <div class="task-header">
                <div>
                    <h1 class="task-title">Annual Report Preparation <span class="priority-badge priority-high">High Priority</span></h1>
                    <div class="task-meta">
                        <div class="task-meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Due: May 15, 2023</span>
                        </div>
                        <div class="task-meta-item">
                            <i class="fas fa-user"></i>
                            <span>Assigned by: Sarah Johnson</span>
                        </div>
                        <div class="task-meta-item">
                            <i class="fas fa-building"></i>
                            <span>Finance Department</span>
                        </div>
                        <div class="task-meta-item">
                            <i class="fas fa-clock"></i>
                            <span>Created: April 25, 2023</span>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="status-badge status-in-progress">In Progress</span>
                </div>
            </div>

            <div class="task-description">
                <h5>Task Description:</h5>
                <p>Prepare the annual financial report for board review. Include all Q4 figures and year-over-year comparisons. The report should be comprehensive with executive summary, financial statements, and analysis sections. Please ensure all data is verified with the finance team before submission.</p>
                <p>Additional notes: The CEO has requested special emphasis on the growth metrics for our new product line. Include at least 3 comparative charts showing quarter-over-quarter performance.</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="task-section">
                        <h5 class="task-section-title"><i class="fas fa-comments"></i> Comments & Updates</h5>
                        
                        <div class="comment-box">
                            <div class="comment-header">
                                <div class="comment-user">
                                    <img src="{{ Auth::user()->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" alt="You">
                                    You
                                </div>
                                <div class="comment-time">2 hours ago</div>
                            </div>
                            <div class="comment-text">
                                I've completed the financial statements section. Attached is the draft for review. Please let me know if you'd like any changes before I proceed with the analysis section.
                            </div>
                        </div>

                        <div class="comment-box">
                            <div class="comment-header">
                                <div class="comment-user">
                                    <img src="https://ui-avatars.com/api/?name=Sarah+J" alt="Sarah Johnson">
                                    Sarah Johnson
                                </div>
                                <div class="comment-time">1 hour ago</div>
                            </div>
                            <div class="comment-text">
                                The draft looks good overall. Please make sure to include the comparative analysis with last year's figures in the next version. Also, don't forget to add the footnotes about the accounting method changes we discussed.
                            </div>
                        </div>

                        <div class="reply-form">
                            <textarea class="form-control" rows="3" placeholder="Add a comment or update..."></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-paperclip"></i> Attach File
                                    </button>
                                </div>
                                <button class="btn btn-primary">Post Comment</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="task-section">
                        <h5 class="task-section-title"><i class="fas fa-paperclip"></i> Attachments (3)</h5>
                        
                        <div class="d-flex flex-wrap">
                            <img src="https://via.placeholder.com/200x150?text=Report+Draft" class="attachment-thumbnail" alt="Report Draft">
                            <img src="https://via.placeholder.com/200x150?text=Financial+Data" class="attachment-thumbnail" alt="Financial Data">
                            <img src="https://via.placeholder.com/200x150?text=Guidelines" class="attachment-thumbnail" alt="Guidelines">
                        </div>
                        
                        <div class="file-upload-area">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h5>Drag & drop files here</h5>
                            <p class="text-muted mb-3">Supports PDF, DOCX, JPG, PNG up to 10MB</p>
                            <button type="button" class="btn btn-outline-primary btn-sm px-4">Browse Files</button>
                        </div>
                    </div>

                    <div class="task-section">
                        <h5 class="task-section-title"><i class="fas fa-tasks"></i> Task Actions</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
                            <select class="form-select mb-3">
                                <option>In Progress</option>
                                <option>Pending Review</option>
                                <option>Completed</option>
                                <option>On Hold</option>
                            </select>
                            <button class="btn btn-outline-primary w-100">Update Status</button>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            This task cannot be marked as complete until all required attachments are submitted.
                        </div>
                    </div>
                </div>
            </div>

            <div class="task-actions">
                <button class="btn btn-complete">
                    <i class="fas fa-check-circle me-2"></i> Mark as Complete
                </button>
            </div>
        </div>
    </div>

    <script>
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