@extends('layouts/contentNavbarLayout')

@section('title', $title)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .timestamp-label {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #ffffff;


            border-radius: 4px;
            z-index: 10;
        }

        /* Default border style */
.document-card {
    border: 1px solid #d1d5db;
}

/* Approved border color */
.border-approved {
    border-color: #28a745 !important; /* Green */
}

/* Rejected border color */
.border-rejected {
    border-color: #dc3545 !important; /* Red */
}

/* Pending border color */
.border-pending {
    border-color: #ffc107 !important; /* Yellow */
}

        .document-card {
            position: relative;
        }

        .document-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .document-card {
            width: 200px;
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            padding: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .document-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .document-thumbnail {
            width: 100%;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .document-title {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .document-department {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .document-uploader {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            font-size: 12px;
            color: #ffffff;
            border: none;
            padding: 6px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-action i {
            margin-right: 5px;
        }

        .btn-download {
            background-color: #007bff;
        }

        .btn-download:hover {
            background-color: #0056b3;
        }


        .approval-label {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
            padding: 3px 6px;
            border-radius: 4px;
            z-index: 10;
        }

        .approval-approved {
            background-color: #28a745;
            /* Green for Approved */
        }

        .approval-rejected {
            background-color: #dc3545;
            /* Red for Rejected */
        }

        .approval-pending {
            background-color: #ffc107;
            /* Yellow for Pending */
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        {{-- Back Button --}}
        <a href="{{ route('dashboard-analytics') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        {{-- Page Header --}}
        <div class="header-with-icon text-center">
            <i class="fas fa-file-alt header-icon"></i>
            <h4 class="header-title mt-2">{{ $title }}</h4>
        </div>


        {{-- Document Display --}}
        @if ($documents->count() > 0)
        <div class="document-container">
            @foreach ($documents as $document)
                @php
                    $filePaths = json_decode($document->file_paths, true);
                @endphp
        
                @if (is_array($filePaths))
                    @foreach ($filePaths as $filePath)
                        <a href="{{ route('documents.show', $document->id) }}"
                            class="document-card
                                {{ $document->approval_status === 'Approved' ? 'border-approved' : '' }}
                                {{ $document->approval_status === 'Rejected' ? 'border-rejected' : '' }}
                                {{ $document->approval_status === 'Pending' ? 'border-pending' : '' }}"
                            title="Document: {{ $document->name }} | Department: {{ $document->department->name ?? 'N/A' }}">
                            
                            {{-- Timestamp Label --}}
                            <div class="timestamp-label">
                                {{ $document->updated_at->format('d M Y, h:i A') }}
                            </div>
        
                            {{-- Thumbnail for Image Files --}}
                            @if (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset($filePath) }}" alt="{{ $document->name }}" class="document-thumbnail"
                                    onerror="this.src='{{ asset('images/default-thumbnail.png') }}';">
                            @elseif (pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf')
                                <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png"
                                    alt="PDF File" class="document-thumbnail">
                            @elseif (pathinfo($filePath, PATHINFO_EXTENSION) === 'doc' || pathinfo($filePath, PATHINFO_EXTENSION) === 'docx')
                                <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png"
                                    alt="Word File" class="document-thumbnail">
                            @else
                                <img src="{{ asset('images/default-thumbnail.png') }}" alt="File Icon"
                                    class="document-thumbnail">
                            @endif
        
                            {{-- Document Title --}}
                            <p class="document-title">
                                {{ pathinfo($filePath, PATHINFO_FILENAME) }}.{{ pathinfo($filePath, PATHINFO_EXTENSION) }}
                            </p>
        
                            {{-- Department Name --}}
                            <p class="document-department">
                                Department: {{ $document->department->name ?? 'N/A' }}
                            </p>
        
                            {{-- Uploader Role --}}
                            <p class="document-uploader">
                                Uploaded By: {{ $document->user->email ?? 'Unknown' }}
                            </p>
        
                            {{-- Approval Status Label --}}
                            <div class="approval-label 
                                {{ $document->approval_status === 'Approved' ? 'approval-approved' : '' }}
                                {{ $document->approval_status === 'Rejected' ? 'approval-rejected' : '' }}
                                {{ $document->approval_status === 'Pending' ? 'approval-pending' : '' }}">
                                {{ $document->approval_status }}
                            </div>
        
                            {{-- Download Button --}}
                            <form action="{{ route('documents.download') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="filePath" value="{{ $filePath }}">
                                <button type="submit" class="btn-action btn-download">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </form>
                        </a>
                    @endforeach
                @else
                    <p class="text-danger">File paths are not properly stored as an array.</p>
                @endif
            @endforeach
        </div>
        
        @else
            <p class="text-center">No documents available.</p>
        @endif
    </div>


@endsection
