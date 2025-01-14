@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Document Management')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f5f7;
            color: #1d1d1f;
        }

        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            padding: 20px;
        }

        .folder-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: #1d1d1f;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .folder-card:hover {
            background-color: #e3f2fd;
            border-color: #007bff;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .folder-icon {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 12px;
        }

        .folder-title {
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            color: #374151;
        }

        .document-count {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .no-documents {
            font-size: 14px;
            color: #6b7280;
        }

        .card-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            border-radius: 12px;
            text-transform: uppercase;
        }

        /* Specific styles for CEO cards */
        .card-pending {
            background-color: #fff9e6;
        }

        .card-pending .card-badge {
            background-color: #ffc107;
        }

        .card-pending .folder-icon {
            color: #856404;
        }

        .card-approved {
            background-color: #e6f7f1;
        }

        .card-approved .card-badge {
            background-color: #28a745;
        }

        .card-approved .folder-icon {
            color: #155724;
        }

        .card-rejected {
            background-color: #fcebea;
        }

        .card-rejected .card-badge {
            background-color: #dc3545;
        }

        .card-rejected .folder-icon {
            color: #721c24;
        }

        .alert {
            border-radius: 12px;
            padding: 16px;
            font-size: 14px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .alert-danger {
            background-color: #fcebea;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #e6f7f1;
            border-color: #c3e6cb;
            color: #155724;
        }
    </style>
@endsection

@section('content')

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
</div>

<div class="container mt-5">
    <div class="header-with-icon text-center">
        <i class="fas fa-folder-open header-icon" style="font-size: 36px; color: #007bff;"></i>
        <h4 class="header-title mt-2" style="font-weight: 600; color: #1d1d1f;">DOCUMENT MANAGEMENT</h4>
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
