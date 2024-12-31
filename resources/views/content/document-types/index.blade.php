@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Document Types')

@section('content')

<style>
    .limited-scroll {
        max-height: 200px;
        overflow-y: auto;
        display: block;
    }

    .font_style {
        font-weight: bold;
    }

    #error-alert,
    #success-alert {
        transition: opacity 0.5s ease-out;
    }

    .btn-action {
        margin-right: 5px;
    }

    .table-container {
        margin-top: 20px;
    }

    .alert {
        margin-bottom: 20px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        color: white;
        background-color: #28a745;
        border: none;
        border-radius: 5px;
    }

    .alert.alert-danger {
        background-color: #dc3545;
    }

    .table th,
    .table td {
        vertical-align: middle;
        text-align: center;
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-container .btn {
        margin-top: 0;
    }
</style>

<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Document Type Management /</span> Document Types
</h4>

<!-- Success and Error Alerts -->
@if (session('success'))
<div class="alert alert-success" id="success-alert">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger" id="error-alert">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Search Filter -->
<div class="card mb-4">
    <h5 class="card-header">Search Document Types</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('document-types.index') }}">
            <div class="search-container">
                <input type="text" name="search" id="documentTypeSearch" class="form-control" placeholder="Search by Document Type Name" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('document-types.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Document Types Table -->
<div class="card mb-4">
    <h5 class="card-header">Manage Document Types</h5>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Document Type Name</th>
                    <th>Department</th>
                    <th>Subcategory</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="documentTypeTable">
                @forelse ($documentTypes as $index => $documentType)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font_style">{{ $documentType->name }}</td>
                    <td>{{ $documentType->department->name ?? 'N/A' }}</td>
                    <td>{{ $documentType->subcategory->name ?? 'N/A' }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-sm btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editDocumentTypeModal" 
                            data-id="{{ $documentType->id }}" 
                            data-name="{{ $documentType->name }}">
                            Edit
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('document-types.destroy', $documentType->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this document type?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No document types found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<!-- Edit Document Type Modal -->
<div class="modal fade" id="editDocumentTypeModal" tabindex="-1" aria-labelledby="editDocumentTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDocumentTypeModalLabel">Edit Document Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form with Dynamic Action -->
            <form id="editDocumentTypeForm" method="POST" action="">
                @csrf
                @method('PUT') <!-- This ensures the form uses the PUT method -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editDocumentTypeName" class="form-label">Document Type Name</label>
                        <input type="text" class="form-control" id="editDocumentTypeName" name="name" required>
                    </div>
                    <!-- Hidden Input to Store Document Type ID -->
                    <input type="hidden" id="editDocumentTypeId" name="id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Document Type</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editDocumentTypeModal = document.getElementById('editDocumentTypeModal');
        const editDocumentTypeForm = document.getElementById('editDocumentTypeForm');
        const editDocumentTypeName = document.getElementById('editDocumentTypeName');
        const editDocumentTypeId = document.getElementById('editDocumentTypeId');

        // Pre-fill modal for edit
        editDocumentTypeModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const documentTypeId = button.getAttribute('data-id'); // Extract document type ID
            const documentTypeName = button.getAttribute('data-name'); // Extract document type Name

            // Set the action for the form dynamically
            editDocumentTypeForm.action = `/document-types/update/${documentTypeId}`;
            // Pre-fill the name field
            editDocumentTypeName.value = documentTypeName;
            // Set the hidden ID field
            editDocumentTypeId.value = documentTypeId;
        });
    });
</script>

