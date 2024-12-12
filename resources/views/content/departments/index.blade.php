@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Departments')

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
    <span class="text-muted fw-light">Department Management /</span> Departments
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
    <h5 class="card-header">Search Departments</h5>
    <div class="card-body">
        <div class="search-container">
            <input type="text" name="search" id="departmentSearch" class="form-control" placeholder="Search by Department Name" value="{{ request('search') }}">
            <button type="button" class="btn btn-primary" id="searchButton">Search</button>
            <a href="{{ route('departments-list') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</div>

<!-- Departments Table -->
<div class="card mb-4">
    <h5 class="card-header">Manage Departments</h5>
    <div class="table-responsive table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="departmentTable">
                @forelse ($departments as $index => $department)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font_style">{{ $department->name }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-sm btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editDepartmentModal" 
                            data-id="{{ $department->id }}" 
                            data-name="{{ $department->name }}">
                            Edit
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('delete-department', $department->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">No departments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDepartmentForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editDepartmentName" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="editDepartmentName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Department</button>
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
        const departmentSearch = document.getElementById('departmentSearch');
        const departmentTable = document.getElementById('departmentTable');

        const editDepartmentModal = document.getElementById('editDepartmentModal');
        const editDepartmentForm = document.getElementById('editDepartmentForm');
        const editDepartmentName = document.getElementById('editDepartmentName');

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
        editDepartmentModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const departmentId = button.getAttribute('data-id');
        const departmentName = button.getAttribute('data-name');

        editDepartmentName.value = departmentName;
        editDepartmentForm.action = `/update-department/${departmentId}`;
    });

        // Filter as you type
        departmentSearch.addEventListener('input', () => {
            const query = departmentSearch.value.toLowerCase();
            const rows = departmentTable.querySelectorAll('tr');
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
