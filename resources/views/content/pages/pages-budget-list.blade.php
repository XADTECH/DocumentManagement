@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Pages')

@section('content')

<style>
    .limited-scroll {
        max-height: 200px;
        /* Set the maximum height as needed */
        overflow-y: auto;
        /* Adds a vertical scrollbar when content overflows */
        display: block;
        /* Ensures the scrollbar is visible on the tbody */
    }

    .font_style {
        font-weight: bold;
    }

    #error-alert,
    #success-alert {
        transition: opacity 0.5s ease-out;
    }
</style>
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Report /</span> Filter Report
</h4>
<div class="row">
    <div class="col-md-12"> @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
        @endif
    </div>
</div>

<!-- Projects Table -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">PROJECT BUDGET LIST</h5>
        <div class="d-flex">
            <form class="d-flex" method="GET" action="{{ route('budgets.list') }}">
                <input type="text" name="reference_code" class="form-control me-2" placeholder="Please enter Budget Reference Code" aria-label="Search">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
    </div>

    <form class="container" method="GET" action="{{ route('budgets.list') }}">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="startdate" class="form-label">Projects</label>
                <select class="form-select" name="project_id">
                    <option disabled selected value>Choose</option>
                    @foreach ($projects as $project)
                    <option {{isset($fields['project_id']) && !is_null($fields['project_id'])  && $fields['project_id'] ==$project->id ? 'selected' : '' }} value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="startdate" class="form-label">Status</label>
                <select class="form-select" name="approval_status">
                    <option disabled selected value>Choose Status</option>
                    <option {{isset($fields['approval_status']) && !is_null($fields['approval_status'])  && $fields['approval_status'] === "Approve" ? 'selected' : '' }} value="Approve">Approve</option>
                    <option {{isset($fields['approval_status']) && !is_null($fields['approval_status'])  && $fields['approval_status'] === "Pending" ? 'selected' : '' }} value="Pending">Pending</option>
                    <option {{isset($fields['approval_status']) && !is_null($fields['approval_status'])  && $fields['approval_status'] === "Cancelled" ? 'selected' : '' }} value="Cancelled">Cancelled</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="startdate" class="form-label">Choose Client </label>

                <select class="form-select" name="client_id">
                    <option disabled selected value>Choose</option>
                    @foreach ($clients as $client)
                    <option {{isset($fields['client_id']) && !is_null($fields['client_id'])  && $fields['client_id'] ==$client->id ? 'selected' : '' }} value="{{$client->id}}">{{$client->clientname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="startdate" class="form-label">Project Manager / Client Manager </label>
                <select class="form-select" name="manager_id">
                    <option disabled selected value>Choose</option>
                    @foreach ($users as $user)
                    <option {{isset($fields['manager_id']) && !is_null($fields['manager_id'])  && $fields['manager_id'] ==$user->id ? 'selected' : '' }} value="{{$user->id}}">{{$user->first_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
        </div>
        <div class="row" style="margin-bottom:20px">
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('budgets.list') }}" class="btn btn-secondary">Clear Filter</a>
            </div>
        </div>
    </form>
</div>

<div class="card mt-4">
    <div class="table-responsive text-nowrap  limited-scroll">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>RefCode</th>
                    <th>Month</th>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Project Manager</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Budget Allocate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="project-table-body" class="table-border-bottom-0">
                @foreach($budgets as $budget)

                @php
                // Find the client name from the $clients collection
                $client = $clients->firstWhere('id', $budget->client_id);
                $clientName = $client ? $client->clientname : 'N/A'; // Handle cases where client is not found

                $user = $users->firstWhere('id', $budget->manager_id);
                $userName = $user ? $user->first_name : 'ADMIN'; // Handle cases where client is not found

                $project = $projects->firstWhere('id', $budget->project_id);
                $projectName = $project ? $project->name : 'N/A'; // Handle cases where client is not found

                $unit = $units->firstWhere('id', $budget->unit_id);
                $unitName = $unit ? $unit->source : 'N/A'; // Handle cases where client is not found

                // Parse the month using Carbon
                $month = \Carbon\Carbon::parse($budget->month);

                // Format month and year
                $formattedMonth = $month->format('F'); // Full month name (e.g., August)
                $formattedYear = $month->format('Y'); // Year (e.g., 2024)
                @endphp

                <tr>
                    <td style="color:#0067aa">{{ $budget->reference_code }}</td>
                    <td class="font_style">{{ $formattedMonth }} {{ $formattedYear }}</td>
                    <td class="font_style">{{ $projectName }}</td>
                    <td class="font_style">{{ $clientName }}</td>
                    <td class="font_style">{{ $userName}}</td>
                    <td class="font_style">{{ $budget->start_date }}</td>
                    <td class="font_style">{{ $budget->end_date }}</td>
                    <td class="font_style">
                    @if (is_null($budget->total_budget_allocated) || $budget->total_budget_allocated <= 0)
                                <span style="color: red;">Budget Not Allocated</span>
                            @else
                                {{ number_format($budget->total_budget_allocated, 2) }}
                            @endif
                    </td>
                    <td class="font_style">
                        @if (strtolower($budget->approval_status)=='approved'|| strtolower($budget->approval_status)=='approve' )
                        <span class="badge bg-success">{{ $budget->approval_status}}</span>
                        @elseif(strtolower($budget->approval_status)=='cancelled' || strtolower($budget->approval_status)=='cancel' || strtolower($budget->approval_status)=='rejected' || strtolower($budget->approval_status)=='reject')
                        <span class="badge bg-danger">{{ $budget->approval_status}}</span>
                        @else
                        <span class="badge bg-warning">{{ $budget->approval_status}}</span>
                        @endif
                    </td>
                    <td>
                        <span class="btn btn-primary btn-sm">
                            <a href="{{ route('budget-project-report-summary', ['id' => $budget->id]) }}">
                                <i class="bx bx-file" style="color:white"></i>
                            </a>
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div style="margin-bottom:50px"></div>

@endsection