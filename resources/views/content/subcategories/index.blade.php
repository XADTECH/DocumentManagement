@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Subcategories')

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
    <span class="text-muted fw-light">Subcategory Management /</span> Subcategories
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
    <h5 class="card-header">Search Subcategories</h5>
    <div class="card-body">
        <div class="search-container">
            <input type="text" name="search" id="subcategorySearch" class="form-control" placeholder="Search by Subcategory Name" value="{{ request('search') }}">
            <button type="button" class="btn btn-primary" id="searchButton">Search</button>
            <a href="{{ route('subcategories.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</div>

<!-- Subcategories Table -->
<div class="card mb-4">
    <h5 class="card-header">Manage Subcategories</h5>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subcategory Name</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="subcategoryTable">
                @forelse ($subcategories as $index => $subcategory)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font_style">{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->department->name ?? 'N/A' }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-sm btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal" 
                            data-id="{{ $subcategory->id }}" 
                            data-name="{{ $subcategory->name }}" 
                            data-department-id="{{ $subcategory->department_id }}">
                            Edit
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this subcategory?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No subcategories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Subcategory Modal -->
<div class="modal fade" id="editSubcategoryModal" tabindex="-1" aria-labelledby="editSubcategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubcategoryModalLabel">Edit Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubcategoryForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editSubcategoryName" class="form-label">Subcategory Name</label>
                        <input type="text" class="form-control" id="editSubcategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDepartmentId" class="form-label">Department</label>
                        <select id="editDepartmentId" name="department_id" class="form-control" required>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hide alerts after a delay
    document.addEventListener('DOMContentLoaded', () => {
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');
        const subcategorySearch = document.getElementById('subcategorySearch');
        const subcategoryTable = document.getElementById('subcategoryTable');

        const editSubcategoryModal = document.getElementById('editSubcategoryModal');
        const editSubcategoryForm = document.getElementById('editSubcategoryForm');
        const editSubcategoryName = document.getElementById('editSubcategoryName');
        const editDepartmentId = document.getElementById('editDepartmentId');

        // Auto-hide alerts
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = 0;
                setTimeout(() => successAlert.remove(), 600);
            }, 3000);
        }

        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.opacity = 0;
                setTimeout(() => errorAlert.remove(), 600);
            }, 3000);
        }

        // Pre-fill modal for edit
        editSubcategoryModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            const subcategoryId = button.getAttribute('data-id');
            const subcategoryName = button.getAttribute('data-name');
            const departmentId = button.getAttribute('data-department-id');

            editSubcategoryName.value = subcategoryName;
            editDepartmentId.value = departmentId;
            editSubcategoryForm.action = `/update-subcategory/${subcategoryId}`;
        });

        // Filter as you type
        subcategorySearch.addEventListener('input', () => {
            const query = subcategorySearch.value.toLowerCase();
            const rows = subcategoryTable.querySelectorAll('tr');
            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(2)');
                if (nameCell && nameCell.textContent.toLowerCase().includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

@endsection
