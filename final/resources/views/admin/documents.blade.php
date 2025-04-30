@php
    if (!function_exists('formatFileSize')) {
        function formatFileSize($bytes, $decimals = 2) {
            if ($bytes === 0) return '0 Bytes';
            $k = 1024;
            $sizes = ['Bytes', 'KB', 'MB', 'GB'];
            $i = floor(log($bytes) / log($k));
            return round($bytes / pow($k, $i), $decimals) . ' ' . $sizes[$i];
        }
    }
@endphp
@extends('genview')

@section('title', 'Document Management')

@section('content')
    <style>

        /* Main Layout */
        .documents-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 30px;
            margin-bottom: 30px;
            border: none;
        }

        .documents-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Upload Area */
        .upload-area {
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: var(--light-bg);
        }

        .upload-area:hover {
            border-color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
            transform: translateY(-2px);
        }

        .upload-icon {
            font-size: 3.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            opacity: 0.8;
        }

        /* File Cards */
        .file-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background-color: white;
        }

        .file-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow);
            border-color: transparent;
        }

        /* File Type Badges */
        .file-type-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .file-type-badge.pdf { background-color: #ffeeee; color: #f44336; }
        .file-type-badge.doc { background-color: #e3f2fd; color: #2196f3; }
        .file-type-badge.xls { background-color: #e8f5e9; color: #4caf50; }
        .file-type-badge.img { background-color: #fff3e0; color: #ff9800; }
        .file-type-badge.zip { background-color: #f3e5f5; color: #9c27b0; }
        .file-type-badge.other { background-color: #f5f5f5; color: #607d8b; }

        /* Buttons */
        .btn-soft {
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-soft-primary {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        .btn-soft-primary:hover {
            background-color: rgba(67, 97, 238, 0.2);
        }

        /* Table Styles */
        .file-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .file-table thead th {
            background-color: var(--light-bg);
            padding: 14px 20px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid rgba(0, 0, 0, 0.03);
        }

        .file-table tbody td {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            vertical-align: middle;
        }

        .file-table tbody tr:last-child td {
            border-bottom: none;
        }

        .file-table tbody tr:hover {
            background-color: var(--light-bg);
        }

        /* Modal Styles */
        .preview-modal .modal-content {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .preview-modal .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }

        .preview-modal .modal-body {
            padding: 0;
            height: 70vh;
        }

        .preview-modal .modal-footer {
            background-color: #f8f9fa;
            border-top: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .documents-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .file-actions {
                margin-top: 10px;
                justify-content: flex-start;
            }
            
            .upload-area {
                padding: 30px 15px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .file-card {
            animation: fadeIn 0.3s ease forwards;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

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
                    <img src="{{ Auth::user()->avatar_url ?? asset('storage/profile/avatars/profile.png') }}" 
                         alt="User Profile" class="rounded-circle" width="40">
                    <span>{{ ucwords(strtolower(Auth::user()->name)) }}</span>
                </div>
            </div>
        </div>

        <!-- Documents Container -->
        <div class="documents-container">
            <!-- Documents Header -->
            <div class="documents-header">
                <h4 class="mb-0">
                    <i class="fas fa-folder-open me-2" style="color: var(--primary-color);"></i> 
                    Document Management
                </h4>
                <button hidden="true" "btn btn-primary" id="uploadTrigger">
                    <i class="fas fa-plus me-2"></i> Upload Files
                </button>
            </div>

            <!-- Upload Area -->
            <div class="upload-area" id="uploadArea" style="display: none;">
                <form id="uploadForm" action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <h5 class="mb-2">Drag & Drop Files Here</h5>
                    <p class="text-muted mb-4">or click to browse files</p>
                    <input type="file" id="fileUpload" name="files[]" multiple style="display: none;">
                    <input type="hidden" name="task_id" value="0">
                    <button type="button" class="btn btn-soft btn-soft-primary"
                        onclick="document.getElementById('fileUpload').click()">
                        <i class="fas fa-folder-open me-2"></i> Select Files
                    </button>
                    <div id="fileList" class="mt-4"></div>
                    <button type="submit" class="btn btn-primary mt-3" id="uploadSubmit" style="display: none;">
                        <i class="fas fa-upload me-2"></i> Upload Now
                    </button>
                </form>
            </div>

            <!-- View Toggle -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary active" id="listViewBtn">
                        <i class="fas fa-list me-2"></i> List View
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="gridViewBtn">
                        <i class="fas fa-th-large me-2"></i> Grid View
                    </button>
                </div>
                <div class="search-box">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search documents..." id="searchInput">
                    </div>
                </div>
            </div>

            <!-- Files Table (List View) -->
            <div class="table-responsive" id="listView">
                <table class="file-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Uploaded</th>
                            <th>Uploaded by</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attachments as $attachment)
                        <tr>
                            <td>
                                @php
                                    $icon = match (true) {
                                        str_contains($attachment->type, 'pdf') => 'fa-file-pdf text-danger',
                                        str_contains($attachment->type, 'word') => 'fa-file-word text-primary',
                                        str_contains($attachment->type, 'excel') => 'fa-file-excel text-success',
                                        str_contains($attachment->type, 'image') => 'fa-file-image text-warning',
                                        str_contains($attachment->type, 'zip') => 'fa-file-archive text-secondary',
                                        default => 'fa-file-alt text-muted'
                                    };
                                    $badgeClass = match (true) {
                                        str_contains($attachment->type, 'pdf') => 'pdf',
                                        str_contains($attachment->type, 'word') => 'doc',
                                        str_contains($attachment->type, 'excel') => 'xls',
                                        str_contains($attachment->type, 'image') => 'img',
                                        str_contains($attachment->type, 'zip') => 'zip',
                                        default => 'other'
                                    };
                                @endphp
                                <i class="fas {{ $icon }} me-2"></i>
                                <span class="file-name">{{ $attachment->filename }}</span>
                            </td>
                            <td>
                                <span class="file-type-badge {{ $badgeClass }}">
                                    {{ strtoupper(pathinfo($attachment->filename, PATHINFO_EXTENSION)) }}
                                </span>
                            </td>
                            <td>{{ formatFileSize($attachment->size) }}</td>
                            <td>{{ $attachment->created_at->format('M d, Y') }}</td>
                            <td>{{ $attachment->uploader->name }}</td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-sm btn-soft btn-soft-primary view-btn me-2"
                                        data-file-url="{{ Storage::url($attachment->path) }}"
                                        data-file-type="{{ $attachment->type }}"
                                        data-file-name="{{ $attachment->filename }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('attachments.download', $attachment->id) }}"
                                        class="btn btn-sm btn-soft btn-soft-primary me-2">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('attachments.destroy', $attachment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft btn-soft-primary me-2"
                                            onclick="return confirm('Are you sure you want to delete this file?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Files Grid (Hidden by default) -->
            <div class="row" id="gridView" style="display: none;">
                @foreach($attachments as $attachment)
                @php
                    $icon = match (true) {
                        str_contains($attachment->type, 'pdf') => 'fa-file-pdf text-danger',
                        str_contains($attachment->type, 'word') => 'fa-file-word text-primary',
                        str_contains($attachment->type, 'excel') => 'fa-file-excel text-success',
                        str_contains($attachment->type, 'image') => 'fa-file-image text-warning',
                        str_contains($attachment->type, 'zip') => 'fa-file-archive text-secondary',
                        default => 'fa-file-alt text-muted'
                    };
                    $badgeClass = match (true) {
                        str_contains($attachment->type, 'pdf') => 'pdf',
                        str_contains($attachment->type, 'word') => 'doc',
                        str_contains($attachment->type, 'excel') => 'xls',
                        str_contains($attachment->type, 'image') => 'img',
                        str_contains($attachment->type, 'zip') => 'zip',
                        default => 'other'
                    };
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="file-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <i class="fas {{ $icon }}" style="font-size: 2.5rem;"></i>
                            <span class="file-type-badge {{ $badgeClass }}">
                                {{ strtoupper(pathinfo($attachment->filename, PATHINFO_EXTENSION)) }}
                            </span>
                        </div>
                        <h6 class="mb-2 text-truncate" title="{{ $attachment->filename }}">{{ $attachment->filename }}</h6>
                        <p class="small text-muted mb-3">{{ formatFileSize($attachment->size) }} • {{ $attachment->created_at->format('M d, Y') }}</p>
                        <div class="file-actions d-flex justify-content-between">
                            <button class="btn btn-sm btn-soft btn-soft-primary view-btn"
                                data-file-url="{{ Storage::url($attachment->path) }}"
                                data-file-type="{{ $attachment->type }}"
                                data-file-name="{{ $attachment->filename }}">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
                            <a href="{{ route('attachments.download', $attachment->id) }}"
                                class="btn btn-sm btn-soft btn-soft-primary">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($attachments->hasPages())
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    @if ($attachments->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link"><i class="fas fa-angle-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $attachments->previousPageUrl() }}"><i class="fas fa-angle-left"></i></a>
                        </li>
                    @endif

                    @foreach ($attachments->getUrlRange(1, $attachments->lastPage()) as $page => $url)
                        @if ($page == $attachments->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if ($attachments->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $attachments->nextPageUrl() }}"><i class="fas fa-angle-right"></i></a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link"><i class="fas fa-angle-right"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
            @endif
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade preview-modal" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pdfPreview" style="display: none; height: 100%;">
                        <iframe src="" width="100%" height="100%" style="border: none;"></iframe>
                    </div>
                    <div id="imagePreview" style="display: none; height: 100%; text-align: center;">
                        <img src="" alt="Preview" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <div id="officePreview" style="display: none; height: 100%;">
                        <iframe src="" width="100%" height="100%" style="border: none;"></iframe>
                    </div>
                    <div id="unsupportedPreview" style="display: none; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px;">
                        <i class="fas fa-file-alt fa-5x text-muted mb-4"></i>
                        <h4 class="mb-3">Preview Not Available</h4>
                        <p class="text-muted mb-4">This file type cannot be previewed in the browser.</p>
                        <a href="#" id="downloadInstead" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i> Download File
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="downloadBtn" class="btn btn-primary me-auto">
                        <i class="fas fa-download me-2"></i> Download
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Document ready
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle upload area
            document.getElementById('uploadTrigger').addEventListener('click', function() {
                const uploadArea = document.getElementById('uploadArea');
                uploadArea.style.display = uploadArea.style.display === 'none' ? 'block' : 'none';
            });

            // File upload handling
            document.getElementById('fileUpload').addEventListener('change', function(e) {
                const files = e.target.files;
                const fileList = document.getElementById('fileList');
                const uploadSubmit = document.getElementById('uploadSubmit');

                fileList.innerHTML = '';

                if (files.length > 0) {
                    uploadSubmit.style.display = 'inline-block';
                    
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const fileItem = document.createElement('div');
                        fileItem.className = 'd-flex justify-content-between align-items-center p-3 mb-2 bg-light rounded';
                        fileItem.innerHTML = `
                            <div>
                                <i class="fas fa-file-alt me-2"></i>
                                <span>${file.name}</span>
                            </div>
                            <small class="text-muted">${formatFileSize(file.size)}</small>
                        `;
                        fileList.appendChild(fileItem);
                    }
                } else {
                    uploadSubmit.style.display = 'none';
                }
            });

            // Drag and drop functionality
            const uploadArea = document.getElementById('uploadArea');
            
            ['dragover', 'dragenter'].forEach(event => {
                uploadArea.addEventListener(event, (e) => {
                    e.preventDefault();
                    uploadArea.style.borderColor = 'var(--primary-color)';
                    uploadArea.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
                });
            });

            ['dragleave', 'dragend', 'drop'].forEach(event => {
                uploadArea.addEventListener(event, (e) => {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#e0e0e0';
                    uploadArea.style.backgroundColor = 'var(--light-bg)';
                });
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('fileUpload').files = files;
                    const event = new Event('change');
                    document.getElementById('fileUpload').dispatchEvent(event);
                }
            });

            // View toggle
            document.getElementById('listViewBtn').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('gridViewBtn').classList.remove('active');
                document.getElementById('listView').style.display = 'block';
                document.getElementById('gridView').style.display = 'none';
            });

            document.getElementById('gridViewBtn').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('listViewBtn').classList.remove('active');
                document.getElementById('listView').style.display = 'none';
                document.getElementById('gridView').style.display = 'flex';
            });

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const viewType = document.getElementById('listViewBtn').classList.contains('active') ? 'list' : 'grid';
                
                if (viewType === 'list') {
                    const rows = document.querySelectorAll('.file-table tbody tr');
                    rows.forEach(row => {
                        const fileName = row.querySelector('.file-name').textContent.toLowerCase();
                        row.style.display = fileName.includes(searchTerm) ? '' : 'none';
                    });
                } else {
                    const cards = document.querySelectorAll('#gridView .file-card');
                    cards.forEach(card => {
                        const fileName = card.querySelector('h6').textContent.toLowerCase();
                        card.parentElement.style.display = fileName.includes(searchTerm) ? '' : 'none';
                    });
                }
            });

            // File preview functionality
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fileUrl = this.getAttribute('data-file-url');
                    const fileType = this.getAttribute('data-file-type');
                    const fileName = this.getAttribute('data-file-name');
                    
                    previewFile(fileUrl, fileType, fileName);
                });
            });

            // Form submission with progress
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const uploadArea = document.getElementById('uploadArea');

                axios.post(this.action, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function(progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        // You could add a progress bar here
                        console.log(percentCompleted + '% uploaded');
                    }
                })
                .then(response => {
                    toastr.success('Files uploaded successfully!');
                    uploadArea.style.display = 'none';
                    window.location.reload();
                })
                .catch(error => {
                    toastr.error('Error uploading files: ' + error.response.data.message);
                });
            });
        });

        // File preview function
        function previewFile(fileUrl, fileType, fileName) {
            const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
            const pdfPreview = document.getElementById('pdfPreview');
            const imagePreview = document.getElementById('imagePreview');
            const officePreview = document.getElementById('officePreview');
            const unsupportedPreview = document.getElementById('unsupportedPreview');
            const downloadBtn = document.getElementById('downloadBtn');
            const downloadInstead = document.getElementById('downloadInstead');
            const modalTitle = document.getElementById('filePreviewModalLabel');

            // Reset all previews
            pdfPreview.style.display = 'none';
            imagePreview.style.display = 'none';
            officePreview.style.display = 'none';
            unsupportedPreview.style.display = 'none';

            // Set modal title
            modalTitle.textContent = fileName;
            
            // Set download links
            downloadBtn.href = fileUrl;
            downloadInstead.href = fileUrl;

            // Show appropriate preview based on file type
            if (fileType.includes('pdf')) {
                pdfPreview.style.display = 'block';
                pdfPreview.querySelector('iframe').src = fileUrl;
            } 
            else if (fileType.includes('image')) {
                imagePreview.style.display = 'block';
                imagePreview.querySelector('img').src = fileUrl;
            }
            else if (fileType.includes('word') || fileType.includes('excel') || fileType.includes('powerpoint')) {
                officePreview.style.display = 'block';
                officePreview.querySelector('iframe').src = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fileUrl)}`;
            }
            else {
                unsupportedPreview.style.display = 'flex';
            }

            modal.show();
        }

        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }
    </script>
@endsection

