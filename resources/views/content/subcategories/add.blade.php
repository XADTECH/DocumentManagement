@extends('layouts/contentNavbarLayout')

@section('title', 'Subcategory Management - Add Subcategory')

@section('page-script')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Subcategory Management /</span> Add Subcategory
    </h4>

    <style>
        .alert {
            opacity: 1;
            transition: opacity 0.6s ease-out;
            background-color: #0067ab;
            color: white;
            text-align: left;
        }

        .alert-danger {
            background-color: #dc3545;
        }

        .alert-success {
            background-color: #28a745;
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
                <h5 class="card-header">Add Subcategory</h5>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('subcategories.store') }}">
                        @csrf
                        <div class="row">
                            <!-- Subcategory Name -->
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Subcategory Name</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    placeholder="Enter subcategory name..." value="{{ old('name') }}" required />
                            </div>

                            <!-- Department Selection -->
                            <div class="mb-3 col-md-6">
                                <label for="department_id" class="form-label">Department</label>
                                <select id="department_id" name="department_id" class="form-control" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
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
        // Automatically hide alerts after a delay
        document.addEventListener('DOMContentLoaded', () => {
            const errorAlert = document.getElementById('error-alert');
            const successAlert = document.getElementById('success-alert');

            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.opacity = 0;
                    setTimeout(() => errorAlert.remove(), 600);
                }, 3000);
            }

            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = 0;
                    setTimeout(() => successAlert.remove(), 600);
                }, 3000);
            }
        });
    </script>
@endsection
