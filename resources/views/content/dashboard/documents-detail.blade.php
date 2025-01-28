@extends('layouts/contentNavbarLayout')

@section('title', $title)

@section('content')
    <style>
          /* Prevent text selection across the entire document */
    body {
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; /* Standard syntax */
    }

    /* Change cursor to pointer for hover effect */
    body, .card, .file-container, .file-item, table, table th, table td {
        cursor: pointer;
    }

    /* Optional: Allow text selection inside inputs, textareas */
    input, textarea {
        -webkit-user-select: text;
        -moz-user-select: text;
        -ms-user-select: text;
        user-select: text;
        cursor: text; /* Show text selection cursor */
    }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f5f7;
            color: #1d1d1f;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            margin-bottom: 20px;
        }

        .card-header {
            background: border-box;
            font-size: 18px;
            font-weight: 600;
            color: #1d1d1f;
            padding: 16px 24px;
            border-bottom: 1px solid #e0e0e0;
            text-transform: capitalize;
        }

        .card-body {
            padding: 24px;
        }

        .table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            text-align: left;
            padding: 12px 16px;
            font-size: 14px;
        }

        .table th {
            font-weight: bold;
            color: #6b7280;
            background-color: #f9f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .table td {
            border-bottom: 1px solid #e0e0e0;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .file-container {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .file-item {
            text-align: center;
            width: 120px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9fa;
            padding: 12px;
            transition: box-shadow 0.3s ease;
        }

        .file-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .file-item img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .file-item p {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #1d1d1f;
        }
    </style>

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Document Management /</span> {{ $title }}
    </h4>

    <!-- Document Details Card -->
    <div class="card">
        <div class="card-header">Document Details</div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Document Name</th>
                    <td>{{ $document->name }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $document->department->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Sub Department</th>
                    <td>{{ $document->subcategory->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $document->documentType->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Uploaded By</th>
                    <td>{{ $document->user->first_name . ' ' . $document->user->last_name ?? 'N/A' }} | {{ $document->user->email?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($document->approval_status === 'Approved')
                            <span class="badge bg-success text-white">{{ $document->approval_status }}</span>
                        @elseif ($document->approval_status === 'Pending')
                            <span class="badge bg-warning text-white">{{ $document->approval_status }}</span>
                        @elseif ($document->approval_status === 'Rejected')
                            <span class="badge bg-danger text-white">{{ $document->approval_status }}</span>
                        @endif
                    </td>
                    
                </tr>
                <tr>
                    <th>Remarks</th>
                    <td>{{ $document->remarks ?? 'No remarks available' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Attached Files Card -->
    <div class="card">
        <div class="card-header">Attached Files</div>
        <div class="card-body">
            @php
                $filePaths = json_decode($document->file_paths, true);
            @endphp

            @if (is_array($filePaths) && count($filePaths) > 0)
                <div class="file-container">
                    @foreach ($filePaths as $filePath)
                        @php
                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                            $fileName = pathinfo($filePath, PATHINFO_BASENAME);
                        @endphp
                        <div class="file-item text-center">
                            @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                <a href="{{ asset($filePath) }}" target="_blank">
                                    <img src="{{ asset($filePath) }}" alt="{{ $fileName }}" style="width: 100px; height: auto; border: 1px solid #ddd;">
                                </a>
                            @elseif ($fileExtension === 'pdf')
                                <a href="{{ asset($filePath) }}" target="_blank">
                                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" alt="PDF File" style="width: 100px; height: auto;">
                                </a>
                            @elseif (in_array($fileExtension, ['doc', 'docx']))
                                <a href="{{ asset($filePath) }}" target="_blank">
                                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" alt="Word File" style="width: 100px; height: auto;">
                                </a>
                            @else
                                <a href="{{ asset($filePath) }}" target="_blank" class="btn btn-primary">
                                    View File
                                </a>
                            @endif
                            <p class="file-name">{{ $fileName }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No files attached to this document.</p>
            @endif
        </div>
    </div>
    <script>
        // Remove focus from any active element after the page loads
        window.addEventListener('load', () => {
            const activeElement = document.activeElement;
    
            // Check if the focused element is not the body and blur it
            if (activeElement && activeElement !== document.body) {
                activeElement.blur();
            }
        });
    </script>
@endsection
