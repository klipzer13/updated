@extends('genview')
@section('title', 'Notifications')

@section('content')
    <style>
        .notifications-container {
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

        .notification-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .notification-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
        }

        .notification-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.1);
            border-bottom: 3px solid var(--primary-color);
        }

        .notification-item {
            display: flex;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .notification-item.unread {
            background-color: #f0f7ff;
            border-left-color: var(--primary-color);
        }

        .notification-item:hover {
            background-color: #f8f9fa;
            transform: translateX(3px);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .notification-text {
            color: #6c757d;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #adb5bd;
        }

        .notification-actions {
            display: flex;
            align-items: center;
        }

        .notification-badge {
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            margin-left: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 15px;
        }

        .empty-state h5 {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #adb5bd;
            max-width: 400px;
            margin: 0 auto;
        }

        .btn-mark-all {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .btn-mark-all:hover {
            background-color: #f0f7ff;
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

        <!-- Notifications Content -->
        <div class="notifications-container">
            <div class="section-header">
                <h4><i class="fas fa-bell"></i> Notifications</h4>
                <button class="btn btn-mark-all">
                    <i class="fas fa-check-double me-1"></i> Mark All as Read
                </button>
            </div>

            <ul class="nav nav-tabs notification-tabs" id="notificationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        All
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab">
                        Unread
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab">
                        Tasks
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="updates-tab" data-bs-toggle="tab" data-bs-target="#updates" type="button" role="tab">
                        Updates
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="notificationTabsContent">
                <!-- All Notifications Tab -->
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New Task Assigned</div>
                            <div class="notification-text">"Annual Report Preparation" has been assigned to you by Sarah Johnson. Due date: May 15, 2023.</div>
                            <div class="notification-time">10 minutes ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>

                    <div class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New Comment on Task</div>
                            <div class="notification-text">Sarah Johnson commented on "Website Redesign": "Please prioritize the homepage updates first."</div>
                            <div class="notification-time">1 hour ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>

                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Task Approved</div>
                            <div class="notification-text">Your completion of "IT Infrastructure Upgrade" has been approved by Robert Kim.</div>
                            <div class="notification-time">Yesterday</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>

                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-calendar-exclamation"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Deadline Reminder</div>
                            <div class="notification-text">"Q3 Financial Analysis" is due tomorrow. Don't forget to submit your work.</div>
                            <div class="notification-time">2 days ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>
                </div>

                <!-- Unread Notifications Tab -->
                <div class="tab-pane fade" id="unread" role="tabpanel">
                    <!-- Content would be loaded dynamically -->
                    <div class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New Task Assigned</div>
                            <div class="notification-text">"Annual Report Preparation" has been assigned to you by Sarah Johnson. Due date: May 15, 2023.</div>
                            <div class="notification-time">10 minutes ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>

                    <div class="notification-item unread">
                        <div class="notification-icon">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New Comment on Task</div>
                            <div class="notification-text">Sarah Johnson commented on "Website Redesign": "Please prioritize the homepage updates first."</div>
                            <div class="notification-time">1 hour ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>
                </div>

                <!-- Task Notifications Tab -->
                <div class="tab-pane fade" id="tasks" role="tabpanel">
                    <!-- Content would be loaded dynamically -->
                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New Task Assigned</div>
                            <div class="notification-text">"Annual Report Preparation" has been assigned to you by Sarah Johnson. Due date: May 15, 2023.</div>
                            <div class="notification-time">10 minutes ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>

                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Task Approved</div>
                            <div class="notification-text">Your completion of "IT Infrastructure Upgrade" has been approved by Robert Kim.</div>
                            <div class="notification-time">Yesterday</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>
                </div>

                <!-- Updates Tab -->
                <div class="tab-pane fade" id="updates" role="tabpanel">
                    <!-- Content would be loaded dynamically -->
                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New Comment on Task</div>
                            <div class="notification-text">Sarah Johnson commented on "Website Redesign": "Please prioritize the homepage updates first."</div>
                            <div class="notification-time">1 hour ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>

                    <div class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-calendar-exclamation"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Deadline Reminder</div>
                            <div class="notification-text">"Q3 Financial Analysis" is due tomorrow. Don't forget to submit your work.</div>
                            <div class="notification-time">2 days ago</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-secondary">View</button>
                        </div>
                    </div>
                </div>
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

            // Mark as read functionality
            const notificationItems = document.querySelectorAll('.notification-item.unread');
            notificationItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                    // In a real app, you would send an AJAX request to mark as read
                    updateUnreadCount();
                });
            });

            // Mark all as read
            const markAllBtn = document.querySelector('.btn-mark-all');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function() {
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                    });
                    // In a real app, you would send an AJAX request to mark all as read
                    updateUnreadCount();
                });
            }
        });

        function updateUnreadCount() {
            // This would be connected to your backend in a real app
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = unreadCount;
                if (unreadCount === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.style.display = 'flex';
                }
            }
        }
    </script>
@endsection