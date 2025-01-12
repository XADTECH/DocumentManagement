@extends('layouts/contentNavbarLayout')

@section('title', 'Document Management - Add Document Type')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Document Type Management /</span> Add Document Type
</h4>

<style>
    .alert {
        opacity: 1;
        transition: opacity 0.6s ease-out;
        background-color: #0067ab;
        color: white;
        text-align: left;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <!-- Alert box HTML -->
        @if ($errors->any())
        <div class="alert alert-danger" id="error-alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="card mb-4">
            <h5 class="card-header">Add Document Type</h5>

            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('document-types.store') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Document Type Name</label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Enter document type name..." required autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-control" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="subcategory_id" class="form-label">Subcategory</label>
                            <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                <option value="">Select Subcategory</option>
                                <!-- Options dynamically populated -->
                            </select>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const departmentDropdown = document.getElementById('department_id');
        const subcategoryDropdown = document.getElementById('subcategory_id');

        // Update subcategories when a department is selected
        departmentDropdown.addEventListener('change', function () {
            const departmentId = this.value;

            // Clear previous options
            subcategoryDropdown.innerHTML = '<option value="">Select Subcategory</option>';

            if (departmentId) {
                fetch(`/get-subcategories/${departmentId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(subcategories => {
                        subcategories.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategoryDropdown.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching subcategories:', error));
            }
        });
    });
</script>

@endsection
