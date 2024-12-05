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
    </style>
@endsection

@section('content')
    <div class="container mt-5">
      <div class="header-with-icon text-center">
        <i class="fas fa-folder-open header-icon"></i>
        <h4 class="header-title mt-2">DOCUMENT MANAGEMENT</h4>
    </div>
        <div class="folder-grid">
            <!-- Full Department List with Icons -->
            @php
                $departments = [
                    ['name' => '1- CEO Approval Registry', 'icon' => 'fas fa-briefcase'],
                    ['name' => '2- HR Appraisal', 'icon' => 'fas fa-users'],
                    ['name' => '3- HR Department', 'icon' => 'fas fa-user-tie'],
                    ['name' => '4- Admin Department', 'icon' => 'fas fa-cogs'],
                    ['name' => '5- Cash & LPO Request', 'icon' => 'fas fa-money-check-alt'],
                    ['name' => '6- Finance & Accounts Department', 'icon' => 'fas fa-calculator'],
                    ['name' => '7- PMO Department', 'icon' => 'fas fa-project-diagram'],
                    ['name' => '8- Ismail', 'icon' => 'fas fa-user-circle'],
                    ['name' => '9- WR Project', 'icon' => 'fas fa-tasks'],
                    ['name' => '10- Contract Department', 'icon' => 'fas fa-file-contract'],
                    ['name' => '11- GM Emails', 'icon' => 'fas fa-envelope'],
                    ['name' => '12- Bike & Car Rental Approval', 'icon' => 'fas fa-motorcycle'],
                    ['name' => '13- XAD Pakistan Approval', 'icon' => 'fas fa-flag'],
                    ['name' => '14- SOP’s Approval', 'icon' => 'fas fa-book'],
                    ['name' => '15- CEO Office', 'icon' => 'fas fa-building'],
                    ['name' => '16- Fleet Department', 'icon' => 'fas fa-truck'],
                    ['name' => '17- Training & Development', 'icon' => 'fas fa-chalkboard-teacher'],
                    ['name' => '18- General Docs for Signature', 'icon' => 'fas fa-pen-alt'],
                ];
            @endphp

            @foreach ($departments as $department)
                <div class="folder-card">
                    <div class="folder-icon">
                        <i class="{{ $department['icon'] }}"></i>
                    </div>
                    <div class="folder-title">{{ $department['name'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
