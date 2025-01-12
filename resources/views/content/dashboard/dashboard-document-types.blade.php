@extends('layouts/contentNavbarLayout')

@section('title', 'Document Types - ' . $subcategory->name)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
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

        .folder-title {
            font-size: 16px;
            color: #374151;
            font-weight: 600;
        }

        .document-count {
            font-size: 14px;
            color: #007bff;
            margin-top: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <a href="{{ route('departments.subcategories', $subcategory->department_id) }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back to Subcategories
        </a>

        <div class="header-with-icon text-center">
            <i class="fas fa-folder-open header-icon"></i>
            <h4 class="header-title mt-2">{{ $subcategory->name }} - Document Types</h4>
        </div>

        <div class="folder-grid">
            @foreach ($documentTypes as $documentType)
                <a href="{{ route('document-types.documents', $documentType->id) }}" class="folder-card">
                    <div class="folder-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="folder-title">{{ $documentType->name }}</div>
                    <p class="document-count">{{ $documentType->total_files }} document(s)</p>
                </a>
            @endforeach
        </div>
        
    </div>
@endsection
