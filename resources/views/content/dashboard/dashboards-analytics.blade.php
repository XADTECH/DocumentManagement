@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Document Management')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .folder-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .folder-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .folder-card:hover {
            background-color: #e3f2fd;
            border-color: #007bff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .folder-icon {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 10px;
        }


        .document-count {
            font-size: 14px;
            margin-top: 5px;
        }

        .no-documents {
            font-size: 14px;
            color: #6b7280;
        }

        .card-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            border-radius: 5px;
        }

        /* Specific styles for CEO cards */
        .card-pending {
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        .card-pending .card-badge {
            background-color: #ffc107;
        }

        .card-pending .folder-icon {
            color: #856404;
        }

        .card-approved {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .card-approved .card-badge {
            background-color: #28a745;
        }

        .card-approved .folder-icon {
            color: #155724;
        }

        .card-rejected {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .card-rejected .card-badge {
            background-color: #dc3545;
        }

        .card-rejected .folder-icon {
            color: #721c24;
        }
    </style>
@endsection

@section('content')

<div class="col-md-12">
    <!-- Alert box HTML -->
    @if ($errors->any())
    <div class="alert alert-danger" id="error-alert">
        <!-- <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button> -->
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success" id="success-alert">
        <!-- <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
        {{ session('success') }}
    </div>
    @endif
</div>

<div class="container mt-5">
    <div class="header-with-icon text-center">
        <i class="fas fa-folder-open header-icon"></i>
        <h4 class="header-title mt-2">DOCUMENT MANAGEMENT</h4>
    </div>

    <div class="folder-grid">
        {{-- CEO Approval Folder --}}
        @if (isset($ceoApprovalCount) && $ceoApprovalCount > 0)
            <a href="{{ route('documents.pending') }}" class="folder-card card-pending">
                <div class="card-badge">Pending</div>
                <div class="folder-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="folder-title">CEO Approvals</div>
                <p class="document-count">{{ $ceoApprovalCount }} document(s) pending approval</p>
            </a>
        @endif

        {{-- CEO Approved Folder --}}
        @if (isset($ceoApprovedCount) && $ceoApprovedCount > 0)
            <a href="{{ route('documents.approved') }}" class="folder-card card-approved">
                <div class="card-badge">Approved</div>
                <div class="folder-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="folder-title">CEO Approved</div>
                <p class="document-count">{{ $ceoApprovedCount }} document(s)</p>
            </a>
        @endif

        {{-- CEO Rejected Folder --}}
        @if (isset($ceoRejectedCount) && $ceoRejectedCount > 0)
            <a href="{{ route('documents.rejected') }}" class="folder-card card-rejected">
                <div class="card-badge">Rejected</div>
                <div class="folder-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="folder-title">CEO Rejected</div>
                <p class="document-count">{{ $ceoRejectedCount }} document(s)</p>
            </a>
        @endif

        {{-- Department Folders --}}
        @foreach ($departments as $department)
            <a href="{{ route('departments.subcategories', $department->id) }}" class="folder-card">
                <div class="folder-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="folder-title">{{ $department->name }}</div>
                <p class="document-count">
                    @php
                        $totalDocuments = $department->documents->sum(function ($doc) {
                            return is_array(json_decode($doc->file_paths)) ? count(json_decode($doc->file_paths)) : 0;
                        });
                    @endphp
                    {{ $totalDocuments }} document(s)
                </p>
            </a>
        @endforeach
    </div>

    {{-- No Departments Message --}}
    @if ($departments->isEmpty())
        <p class="no-documents text-center mt-4">No departments or documents available.</p>
    @endif
</div>
@endsection
