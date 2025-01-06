@extends('layouts/contentNavbarLayout')

@section('title', 'Document Management - Upload Document')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Document Management /</span> Upload Document
    </h4>

    <style>
        .file-preview {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .file-preview-item {
            width: 150px;
            height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .file-preview-item img,
        .file-preview-item iframe {
            max-width: 100%;
            max-height: 100px;
            border-radius: 5px;
        }

        .file-preview-item .file-icon {
            font-size: 50px;
            color: #6c757d;
        }

        .file-preview-item .file-name {
            font-size: 12px;
            word-break: break-word;
            margin-top: 5px;
        }

        .file-preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            padding: 5px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mb-4">
                <h5 class="card-header">Upload Document</h5>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('documents.store') }}"
                        id="uploadForm">
                        @csrf
                        <div class="row">
                            <!-- Document Name -->
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Document Name</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    placeholder="Enter document name..." required />
                            </div>

                            <!-- Department -->
                            <div class="mb-3 col-md-6">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-control" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subcategory -->
                            <div class="mb-3 col-md-6">
                                <label for="subcategory_id" class="form-label">Subcategory</label>
                                <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                    <option value="">Select Subcategory</option>
                                    @foreach ($subcategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Document Type -->
                            <div class="mb-3 col-md-6">
                                <label for="document_type_id" class="form-label">Document Type</label>
                                <select class="form-control" id="document_type_id" name="document_type_id" required>
                                    <option value="">Select Document Type</option>
                                    @foreach ($documentTypes as $document)
                                        <option value="{{ $document->id }}">{{ $document->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                                                    
                            <!-- Upload Files -->
                            <div class="mb-3 col-md-6">
                                <label for="files" class="form-label">Upload Files</label>
                                <input class="form-control" type="file" id="files" name="files[]" multiple
                                    required />
                            </div>

                             <!-- CEO Approval -->
                             <div class="mb-2 col-md-12">
                                <label for="ceo_approval" class="form-label">Require CEO Approval</label>
                                <input class="form-check-input" type="checkbox" id="ceo_approval" name="ceo_approval"
                                    value="1" />
                                <label class="form-check-label" for="ceo_approval">Check if this document requires CEO
                                    approval</label>
                            </div>

   
                            <!-- File Preview Section -->
                            <div class="file-preview mb-2" id="file-preview">
                                <p class="text-muted">No files selected yet.</p>
                            </div>

                        </div>

                        <!-- Submit Button -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById("files");
        const filePreview = document.getElementById("file-preview");

        fileInput.addEventListener("change", function() {
            filePreview.innerHTML = ""; // Clear previous previews

            if (this.files.length === 0) {
                filePreview.innerHTML = "<p class='text-muted'>No files selected yet.</p>";
                return;
            }

            Array.from(this.files).forEach((file, index) => {
                const div = document.createElement("div");
                div.classList.add("file-preview-item");

                const removeBtn = document.createElement("button");
                removeBtn.classList.add("remove-btn");
                removeBtn.textContent = "×";
                removeBtn.addEventListener("click", function() {
                    removeFile(index);
                });

                // Check file type
                if (file.type.startsWith("image/")) {
                    const img = document.createElement("img");
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    div.appendChild(img);
                } else if (file.type === "application/pdf") {
                    const iframe = document.createElement("iframe");
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        iframe.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    div.appendChild(iframe);
                } else {
                    const icon = document.createElement("div");
                    icon.classList.add("file-icon");
                    icon.innerHTML = "📁"; // General icon for other files
                    div.appendChild(icon);
                }

                const fileName = document.createElement("span");
                fileName.classList.add("file-name");
                fileName.textContent = file.name;

                div.appendChild(fileName);
                div.appendChild(removeBtn);

                filePreview.appendChild(div);
            });
        });

        function removeFile(index) {
            const dt = new DataTransfer();
            const files = fileInput.files;

            Array.from(files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });

            fileInput.files = dt.files;
            fileInput.dispatchEvent(new Event("change")); // Trigger change event
        }
    });
</script>
