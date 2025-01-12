@extends('layouts/contentNavbarLayout')

@section('title', 'Documents - Table Style')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .table-container {
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
        }

        table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .top-actions {
            display: flex;
            /* background-color: aqua; */
            align-items: center;

            margin-bottom: 20px;
        }

        .action-form {
            margin: 0;
        }

        .status-dropdown {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        .status-dropdown:hover {
            border-color: #ccc;
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
        <div class="text-center mb-4">
            <h4 class="header-title">Documents - Table Style</h4>
        </div>

        {{-- Top Actions --}}
        <div class="top-actions">
            @if (auth()->check() && in_array(auth()->user()->role, ['Admin', 'CEO', 'Secretary']))
                {{-- Update Status Form --}}
                <form action="{{ route('documents.updateStatus') }}" method="POST" class="action-form" id="statusForm">
                    @csrf
                    <select name="status" class="form-select form-select-sm status-dropdown" id="statusDropdown">
                        <option selected>select status</option>
                        <option value="Pending">Change Status to Pending</option>
                        <option value="Approved">Change Status to Approved</option>
                        <option value="Rejected">Change Status to Rejected</option>
                    </select>
                    <input type="text" name="document_id" value="{{ $document->id }}" hidden>
                </form>

                {{-- Delete Button --}}
                <form method="POST" action="{{ route('documents.destroy', $document->id) }}" style="display:inline;margin-left:10px" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            class="btn-action btn btn-sm btn-danger btn-delete" 
                            @if (!auth()->user()->hasRole(['Admin', 'CEO', 'Secretary'])) disabled @endif>
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            @endif
        </div>

        {{-- Table --}}
        @php
            $filePaths = json_decode($document->file_paths, true); // Decode file paths to an array
        @endphp

        @if (is_array($filePaths) && count($filePaths) > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th>Document Name</th>
                            <th>Department</th>
                            <th>Subcategory</th>
                            <th>Type</th>
                            <th>Uploaded By</th>
                            <th>Approval Status</th>
                            <th>CEO Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filePaths as $filePath)
                            <tr>
                                {{-- Thumbnail --}}
                                <td>
                                    @if (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                        <a href="{{ asset($filePath) }}" target="_blank">
                                            <img src="{{ asset($filePath) }}" alt="{{ $document->name }}" class="thumbnail"
                                                onerror="this.src='{{ asset('images/default-thumbnail.png') }}';">
                                        </a>
                                    @elseif (pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf')
                                        <a href="{{ asset($filePath) }}" target="_blank">
                                            <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" alt="PDF File" class="thumbnail">
                                        </a>
                                    @elseif (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['doc', 'docx']))
                                        <a href="{{ asset($filePath) }}" target="_blank">
                                            <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" alt="Word File" class="thumbnail">
                                        </a>
                                    @else
                                        <a href="{{ asset($filePath) }}" target="_blank">
                                            <img src="{{ asset('images/default-thumbnail.png') }}" alt="File Icon" class="thumbnail">
                                        </a>
                                    @endif
                                </td>
                                

                                {{-- Document Name --}}
                                <td>{{ pathinfo($filePath, PATHINFO_FILENAME) }}</td>

                                {{-- Department --}}
                                <td>{{ $document->department->name ?? 'N/A' }}</td>

                                {{-- Subcategory --}}
                                <td>{{ $document->subcategory->name ?? 'N/A' }}</td>

                                {{-- Type --}}
                                <td>{{ $document->documentType->name ?? 'N/A' }}</td>

                                {{-- Uploaded By --}}
                                <td>{{ $document->user->email ?? 'Unknown' }}</td>

                                {{-- Approval Status --}}
                                <td>{{ $document->approval_status }}</td>

                                {{-- CEO Approval --}}
                                <td>{{ $document->ceo_approval ? 'Required' : 'Not Required' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted">No documents available for this category.</p>
        @endif
    </div>

    <script>
        document.getElementById('statusDropdown').addEventListener('change', function() {
            const selectedValue = this.value;
            const confirmation = confirm(
                `Are you sure you want to change the document status to "${selectedValue}"?`);

            if (confirmation) {
                document.getElementById('statusForm').submit();
            } else {
                // Reset the dropdown to its previous value
                this.value = "Pending"; // Adjust as needed if you want a different default option
            }
        });

        // Add event listener to all delete buttons
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Find the form associated with this button
                const form = this.closest('.delete-form');

                // Show confirmation alert
                if (confirm(
                    'Are you sure you want to delete this document? This action cannot be undone.')) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });
    </script>
@endsection
