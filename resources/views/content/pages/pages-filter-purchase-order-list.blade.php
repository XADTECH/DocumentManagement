@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Pages')

@section('content')

    <style>
        .limited-scroll {
            max-height: 200px;
            overflow-y: auto;
            display: block;
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
        <span class="text-muted fw-light">Budget Management /</span> Filter Purchase Orders
    </h4>

    <div class="row">
        <div class="col-md-12">

            <!-- Alert box HTML -->
            <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert"
                style="display: none; width:80%; margin:10px auto">
                <span id="alertMessage"></span>
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>

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

            <!-- Filter Form -->
            <div class="card">
                <div class="card-body">
                    <h6>Filter Purchase Orders</h6>
                    <form id="filterForm" action="{{ route('filter-purchase-orders') }}" method="GET">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="project_id" class="form-label">Choose Project</label>
                                <select class="form-select" name="project_id" id="project_id">
                                    <option disabled selected value>Choose</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->reference_code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="project_person_id" class="form-label">Choose Project Manager</label>
                                <select class="form-select" name="project_person_id" id="project_person_id">
                                    <option disabled selected value>Choose</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('project_person_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" id="date" class="form-control" name="date"
                                    value="{{ request('date') }}" placeholder="Enter Date" />
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-4">
                                <label for="PO" class="form-label">Reference</label>
                                <input type="text" id="po_number" class="form-control" name="po_number"
                                    value="{{ request('po_number') }}" placeholder="Enter PO Number" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <button type="button" class="btn btn-secondary" id="clearFilters">Clear Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Purchase Orders Table -->
            <div class="card mt-4">
                <h5 class="card-header">PO List</h5>
                <div class="table-responsive text-nowrap limited-scroll">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>PO #</th>
                                <th>Project #</th>
                                {{-- <th>Verified</th> --}}
                                <th>Description</th>
                                <th>Prepared By</th>
                                <th>Requested By</th>
                                <th>Budget Allocated</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="project-table-body" class="table-border-bottom-0">
                            @if($purchaseOrders->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">No Data</td> <!-- Adjust colspan based on the number of columns -->
                                </tr>
                            @else
                                @foreach ($purchaseOrders as $po)
                                    @php
                                        $requestPerson = $userList->firstWhere('id', $po->requested_by);
                                        $preparedPerson = $userList->firstWhere('id', $po->prepared_by);
                                        $budget = $budgetList->firstWhere('id', $po->project_id);
                                        $totalBudget = $totalBudgetAllocated->firstWhere('budget_project_id', $po->project_id); // Assuming 2 is for an example, adjust accordingly
                                    @endphp
                                    <tr>
                                        <!-- PO Number with Budget Check -->
                                        <td style="color:#0067aa">
                                            @if ($totalBudget && $totalBudget->allocated_budget)
                                                <a href="{{ route('purchaseOrder.edit', ['POID' => $po->po_number]) }}">{{ $po->po_number ?? 'N/A' }}</a>
                                            @else
                                                <span style="cursor: default;" onclick="alert('Total budget is not allocated for this project PO.');">
                                                    {{ $po->po_number ?? 'N/A' }}
                                                </span>
                                            @endif
                                        </td>
                        
                                        <!-- Budget Reference Code -->
                                        <td style="color:#0067aa">
                                            @if($budget)
                                                <a href="{{ route('edit-project-budget', ['project_id' => $budget->id]) }}">
                                                    {{ $budget->reference_code ?? 'N/A' }}
                                                </a>
                                            @else
                                                <span style="color: red;">No Budget</span>
                                            @endif
                                        </td>
                        
                                        <!-- Verification Status -->
                                        {{-- <td>
                                            <span style="color: red;font-weight:700">
                                                {{ $po->is_verified ? 'Verified' : 'Not Verified' }}
                                            </span>
                                        </td> --}}
                        
                                        <!-- Description -->
                                        <td>{{ $po->description ?? 'N/A' }}</td>
                        
                                        <!-- Prepared By -->
                                        <td>{{ optional($preparedPerson)->first_name ?? 'N/A' }}</td>
                        
                                        <!-- Requested By -->
                                        <td>{{ optional($requestPerson)->first_name ?? 'N/A' }}</td>
                        
                                        <!-- Total Budget Allocated -->
                                        <td>
                                            @if (is_null(optional($budget)->total_budget_allocated) || $budget->total_budget_allocated <= 0)
                                                <span style="color: red;font-weight:700">Budget Not Allocated</span>
                                            @else
                                                {{ number_format($budget->total_budget_allocated, 2) }}
                                            @endif
                                        </td>
                        
                                        <!-- PO Status -->
                                        <td class="text-success">{{ $po->status ?? 'N/A' }}</td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('clearFilters').addEventListener('click', function() {
            const form = document.getElementById('filterForm');
            form.reset(); // Reset the form fields

            // Redirect to the same page without query parameters
            window.location.href = "{{ route('filter-purchase-orders') }}";
        });
    </script>

@endsection
