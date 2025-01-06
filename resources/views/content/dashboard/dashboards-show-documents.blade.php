@extends('layouts/contentNavbarLayout')

@section('title', 'Documents - ' . $subcategory->name)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
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
            margin-bottom: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        .btn-delete {
            background-color: #ff4d4d;
        }

        .btn-delete:hover {
            background-color: #d93030;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        {{-- Back Button --}}
        <a href="{{ route('departments.subcategories', $subcategory->department_id) }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back to Subcategories
        </a>

        {{-- Page Header --}}
        <div class="header-with-icon text-center">
            <i class="fas fa-file-alt header-icon"></i>
            <h4 class="header-title mt-2">{{ $subcategory->name }} - Documents</h4>
        </div>

        {{-- Document Display --}}
        @if ($subcategory->documents->count() > 0)
            <div class="document-container">
                @foreach ($subcategory->documents as $document)
                    @php
                        $filePaths = json_decode($document->file_paths, true);
                    @endphp

                    @foreach ($filePaths as $filePath)
                        <div class="document-card">
                            {{-- Thumbnail for Image Files --}}
                            @if (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <img 
                                    src="{{ asset($filePath) }}" 
                                    alt="{{ $document->name }}" 
                                    class="document-thumbnail"
                                    onerror="this.src='{{ asset('images/default-thumbnail.png') }}';" {{-- Fallback Thumbnail --}}
                                >
                            @elseif (pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf')
                                {{-- Show a static PDF icon for PDF files --}}
                                <img 
                                    src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" 
                                    alt="PDF File" 
                                    class="document-thumbnail">
                            @elseif (pathinfo($filePath, PATHINFO_EXTENSION) === 'doc' || pathinfo($filePath, PATHINFO_EXTENSION) === 'docx')
                                {{-- Show a static Word icon for DOC/DOCX files --}}
                                <img 
                                    src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" 
                                    alt="Word File" 
                                    class="document-thumbnail">
                            @else
                                {{-- Fallback for Other File Types --}}
                                <img 
                                    src="{{ asset('images/default-thumbnail.png') }}" 
                                    alt="File Icon" 
                                    class="document-thumbnail">
                            @endif

                            {{-- Document Title with Extension --}}
                            <p class="document-title">
                                {{ pathinfo($filePath, PATHINFO_FILENAME) }}.{{ pathinfo($filePath, PATHINFO_EXTENSION) }}
                            </p>

                            {{-- Action Buttons --}}
                            <div class="action-buttons">
                                {{-- Download Button --}}
                                <a href="{{ asset($filePath) }}" class="btn-action btn-download" download>
                                    <i class="fas fa-download"></i> Download
                                </a>

                                {{-- Delete Button --}}
                                <form method="POST" action="{{ route('documents.destroy', $document->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-action btn-delete" 
                                            @if (!auth()->user()->hasRole(['Admin', 'CEO', 'Secretary'])) disabled @endif>
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <p class="text-center">No documents available in this subcategory.</p>
        @endif
    </div>
@endsection
