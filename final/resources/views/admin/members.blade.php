@extends('genview')
@section('title', 'Team Members')
<style>
    .modal-content {
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .modal-header {
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-footer {
        border-top: 1px solid #f0f0f0;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
    }

    .profile-picture-container {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    .border-light {
        border-color: #f0f0f0 !important;
    }

    .toggle-password {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .modal-content {
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .modal-header {
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-footer {
        border-top: 1px solid #f0f0f0;
    }

    .section-title {
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #6c757d;
    }

    .detail-item {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 12px 15px;
        height: 100%;
        transition: all 0.2s ease;
    }

    .detail-item:hover {
        background-color: #f0f0f0;
    }

    .detail-label {
        display: block;
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .detail-value {
        display: block;
        font-weight: 500;
        color: #212529;
    }

    .member-details-section {
        padding-left: 20px;
    }

    .edit-profile-btn {
        border-radius: 6px;
        padding: 8px 16px;
    }

    .border-light {
        border-color: #f0f0f0 !important;
    }

    .profile-picture-container {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .toggle-password {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .border-light {
        border-color: #f0f0f0 !important;
    }
</style>
@section('content')
    <div class="main-content">
        <!-- Top Navigation -->
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

        <!-- Members Container -->
        <div class="members-container">
            <!-- Members Header -->
            <div class="members-header">
                <h4><i class="fas fa-users me-2"></i> Team Members</h4>
                <div class="d-flex">
                    <div class="search-member me-3">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control" placeholder="Search members...">
                    </div>
                    <button class="btn add-member-btn" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="fas fa-user-plus me-2"></i> Add Member
                    </button>
                </div>
            </div>

            <!-- Members Table -->
            <div class="table-responsive">
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="member-info">
                                        <img src="{{ asset($user->avatar) }}" class="member-avatar">
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->role->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>{{ $user->role->name }}</td>
                                <td>{{ $user->department->name }}</td>
                                <td>
                                    <button class="btn btn-sm action-btn view" data-id="{{ $user->id }}" data-bs-toggle="modal"
                                        data-bs-target="#viewMemberModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm action-btn edit" data-id="{{ $user->id }}" data-bs-toggle="modal"
                                        data-bs-target="#editMemberModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm action-btn delete" data-id="{{ $user->id }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteMemberModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white">
                <div class="modal-header border-light">
                    <h5 class="modal-title d-flex align-items-center text-dark" id="addMemberModalLabel">
                        <i class="fas fa-user-plus me-2 text-primary"></i>Add New Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMemberForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Left Column - Profile Picture -->
                            <div class="col-md-4 text-center border-end border-light pe-4">
                                <div class="profile-picture-container mb-4">
                                    <img id="avatarPreview" src="{{ asset('storage/profile/avatars/profile.png') }}"
                                        class="rounded-circle shadow-sm mb-3" width="150" height="150"
                                        alt="Profile Preview">
                                    <div class="d-flex flex-column align-items-center">
                                        <label for="memberAvatar" class="btn btn-sm btn-outline-primary mb-1">
                                            <i class="fas fa-camera me-1"></i> Upload Photo
                                        </label>
                                        <input type="file" class="d-none" id="memberAvatar" name="avatar" accept="image/*">
                                        <small class="text-muted">JPG, PNG (Max 2MB)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Form Fields -->
                            <div class="col-md-8 ps-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memberName" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="memberName" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memberUsername" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="memberUsername" name="username"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memberEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="memberEmail" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memberPhone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="memberPhone" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memberPassword" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="memberPassword"
                                                    name="password" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memberRole" class="form-label">Role</label>
                                            <select class="form-select" id="memberRole" name="role_id" required>
                                                <option value="">Select Role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memberDepartment" class="form-label">Department</label>
                                            <select class="form-select" id="memberDepartment" name="department_id" required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="addMember()">
                        <i class="fas fa-save me-1"></i> Add Member
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Member Modal -->
    <div class="modal fade" id="viewMemberModal" tabindex="-1" aria-labelledby="viewMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white">
                <div class="modal-header border-light">
                    <h5 class="modal-title d-flex align-items-center text-dark" id="viewMemberModalLabel">
                        <i class="fas fa-user-circle me-2 text-primary"></i>Member Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Profile Picture Column -->
                        <div class="col-md-4 text-center border-end border-light">
                            <div class="position-relative mb-4">
                                <img id="viewAvatar" src="" class="rounded-circle shadow-sm" width="150" height="150"
                                    alt="Profile Picture">
                            </div>
                            <h4 id="viewName" class="mb-1 text-dark"></h4>
                            <p class="text-muted mb-4" id="viewRole"></p>
                        </div>

                        <!-- Details Column -->
                        <div class="col-md-8">
                            <div class="member-details-section">
                                <h6 class="section-title text-uppercase text-muted mb-3">Personal Information</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="detail-label">Username</span>
                                            <span class="detail-value text-dark" id="viewUsername"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="detail-label">Email</span>
                                            <span class="detail-value text-dark" id="viewEmail"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="detail-label">Phone</span>
                                            <span class="detail-value text-dark" id="viewPhone"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="detail-label">Department</span>
                                            <span class="detail-value text-dark" id="viewDepartment"></span>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="section-title text-uppercase text-muted mb-3">System Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="detail-label">Member Since</span>
                                            <span class="detail-value text-dark" id="viewCreatedAt"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="detail-label">Last Updated</span>
                                            <span class="detail-value text-dark" id="viewUpdatedAt"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary edit-profile-btn">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Edit Member Modal -->
    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white">
                <div class="modal-header border-light">
                    <h5 class="modal-title d-flex align-items-center text-dark" id="editMemberModalLabel">
                        <i class="fas fa-user-edit me-2 text-primary"></i>Edit Member Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMemberForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editMemberId" name="id">
                        <div class="row">
                            <!-- Left Column - Profile Picture -->
                            <div class="col-md-4 text-center border-end border-light pe-4">
                                <div class="profile-picture-container mb-4">
                                    <img id="currentAvatar" src="" class="rounded-circle shadow-sm mb-3" width="150"
                                        height="150" alt="Profile Picture">
                                    <div class="d-flex flex-column align-items-center">
                                        <label for="editAvatar" class="btn btn-sm btn-outline-primary mb-1">
                                            <i class="fas fa-camera me-1"></i> Change Photo
                                        </label>
                                        <input type="file" class="d-none" id="editAvatar" name="avatar" accept="image/*">
                                        <small class="text-muted">JPG, PNG (Max 2MB)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Form Fields -->
                            <div class="col-md-8 ps-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editName" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="editName" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editUsername" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="editUsername" name="username"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="editEmail" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editPhone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="editPhone" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editPassword" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="editPassword"
                                                    name="password" placeholder="Leave blank to keep current">
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editRole" class="form-label">Role</label>
                                            <select class="form-select" id="editRole" name="role_id" required>
                                                <option value="">Select Role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editDepartment" class="form-label">Department</label>
                                            <select class="form-select" id="editDepartment" name="department_id" required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="updateMember()">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Member Modal -->
    <div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white">
                <div class="modal-header border-light">
                    <h5 class="modal-title d-flex align-items-center text-dark" id="deleteMemberModalLabel">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column - Warning Icon -->
                        <div class="col-md-4 text-center border-end border-light pe-4">
                            <div class="delete-warning-container p-4">
                                <div
                                    class="delete-warning-icon bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-trash-alt text-danger" style="font-size: 2.5rem;"></i>
                                </div>
                                <h5 class="text-danger">Warning!</h5>
                                <p class="text-muted">This action cannot be undone</p>
                            </div>
                        </div>

                        <!-- Right Column - Member Info -->
                        <div class="col-md-8 ps-4">
                            <div class="delete-confirmation-content">
                                <p class="lead">Are you sure you want to delete this member?</p>
                                <div class="member-info-card p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <img id="deleteMemberAvatar" src="" class="rounded-circle me-4" width="80"
                                            height="80" alt="Member Avatar">
                                        <div>
                                            <h4 class="mb-1 text-dark" id="deleteMemberName"></h4>
                                            <p class="mb-2 text-muted" id="deleteMemberRole"></p>
                                            <p class="mb-0 text-muted small" id="deleteMemberEmail"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    All associated data will be permanently removed from the system.
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="deleteMemberId">
                </div>
                <div class="modal-footer border-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteMember()">
                        <i class="fas fa-trash-alt me-1"></i> Confirm Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .delete-warning-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .delete-warning-icon {
            width: 100px;
            height: 100px;
        }

        .member-info-card {
            transition: all 0.2s ease;
        }

        .member-info-card:hover {
            background-color: #f0f0f0 !important;
        }

        .modal-content {
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .modal-header {
            border-bottom: 1px solid #f0f0f0;
        }

        .modal-footer {
            border-top: 1px solid #f0f0f0;
        }

        .border-light {
            border-color: #f0f0f0 !important;
        }
    </style>
    <script>
        // Preview new avatar image before upload
        document.getElementById('editAvatar').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    document.getElementById('currentAvatar').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const passwordInput = this.previousElementSibling;
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        });
        // Preview avatar image before upload
        document.getElementById('memberAvatar').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    document.getElementById('avatarPreview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const passwordInput = this.previousElementSibling;
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        });
        // Search functionality
        document.querySelector('.search-member input').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.members-table tbody tr').forEach(row => {
                const name = row.querySelector('.member-info h6').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const username = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (name.includes(searchTerm) || email.includes(searchTerm) || username.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add member function
        function addMember() {
            const form = document.getElementById('addMemberForm');
            const formData = new FormData(form);

            fetch("{{ route('users.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Member added successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to add member'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the member: ' + (error.message || 'Unknown error'));
                });
        }
        // View member function - populate modal with data
        document.querySelectorAll('.view').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');

                fetch(`users/${userId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const user = data.data;
                            document.getElementById('viewName').textContent = user.name;
                            document.getElementById('viewUsername').textContent = user.username;
                            document.getElementById('viewEmail').textContent = user.email;
                            document.getElementById('viewPhone').textContent = user.phone || 'N/A';
                            document.getElementById('viewRole').textContent = user.role.name;
                            document.getElementById('viewDepartment').textContent = user.department.name;
                            document.getElementById('viewCreatedAt').textContent = new Date(user.created_at).toLocaleString();
                            document.getElementById('viewUpdatedAt').textContent = new Date(user.updated_at).toLocaleString();

                            document.getElementById('viewAvatar').src = data.avatar_url;
                        } else {
                            alert('Failed to fetch member details: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to fetch member details. Please check console for details.');
                    });
            });
        });


        // Update the edit member function
        document.querySelectorAll('.edit').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');

                fetch(`users/${userId}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const user = data.data;
                            document.getElementById('editMemberId').value = user.id;
                            document.getElementById('editName').value = user.name;
                            document.getElementById('editUsername').value = user.username;
                            document.getElementById('editEmail').value = user.email;
                            document.getElementById('editPhone').value = user.phone || '';
                            document.getElementById('editRole').value = user.role_id;
                            document.getElementById('editDepartment').value = user.department_id;

                            // Set avatar preview
                            const avatarUrl = user.avatar ? "{{ asset('') }}" + user.avatar : "{{ asset('storage/profile/avatars/profile.png') }}";
                            document.getElementById('currentAvatar').src = avatarUrl;
                        } else {
                            alert('Error: ' + (data.message || 'Failed to fetch member details'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to fetch member details for editing');
                    });
            });
        });

        // Update member function
        function updateMember() {
            const userId = document.getElementById('editMemberId').value;
            const form = document.getElementById('editMemberForm');
            const formData = new FormData(form);

            // Add _method field for Laravel to recognize as PUT request
            formData.append('_method', 'PUT');

            fetch(`users/${userId}`, {
                method: 'POST', // Using POST because we're sending FormData with _method=PUT
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Member updated successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to update member'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the member: ' + (error.message || 'Unknown error'));
                });
        }

        // Delete member function - populate confirmation modal
        document.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const row = this.closest('tr');

                const name = row.querySelector('.member-info h6').textContent;
                const role = row.querySelector('.member-info small').textContent;
                const avatar = row.querySelector('.member-avatar').src;

                document.getElementById('deleteMemberId').value = userId;
                document.getElementById('deleteMemberName').textContent = name;
                document.getElementById('deleteMemberRole').textContent = role;
                document.getElementById('deleteMemberAvatar').src = avatar;
            });
        });

        // Delete member function
        function deleteMember() {
            const userId = document.getElementById('deleteMemberId').value;

            fetch(`users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Member deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to delete member'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the member: ' + (error.message || 'Unknown error'));
                });
        }
    </script>
@endsection