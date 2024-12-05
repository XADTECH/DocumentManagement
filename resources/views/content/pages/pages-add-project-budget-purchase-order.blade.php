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
    <span class="text-muted fw-light">Budget Management /</span> Create Purchase Order
</h4>

<div class="row">
    <div class="col-md-12">

        <!-- Alert box HTML -->
        <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
            <span id="alertMessage"></span>
            <button type="button" class="btn-close" aria-label="Close"></button>
        </div>

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

        <!-- Project Form -->
        <div class="card">
            <div class="card-body">
                <h6> (PO) Create Purchase Order</h6>
                <form action="{{ route('add-budget-project-purchase-order') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="startdate" class="form-label"> Date</label>
                            <input type="date" id="startdate" class="form-control" name="startdate" placeholder="Enter Start Date" required />
                        </div>
                        <div class="col-sm-4">
                            <label for="payment_term" class="form-label">Payment Term</label>
                            <select id="payment_term" name="payment_term" class="form-select">
                                <option value="cash">cash</option>
                                <option value="online transaction">online transaction</option>
                                <option value="cheque 30 days">cheque 30 days</option>
                                <option value="cheque 60 days">cheque 60 days</option>
                                <option value="cheque 90 days">cheque 90 days</option>
                                <option value="cheque as per invoice">cheque as per invoice</option>
                                <option value="cash on delivery">cash on delivery</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="startdate" class="form-label">Supplier Name</label>
                            <!-- <input type="text" class="form-control" name="supplier_name" placeholder="eg: Frontier Innovation" /> -->
                            <select class="form-select" name="supplier_name">
                                <option disabled selected value>Choose</option>
                                @foreach ($supplierlist as $budget)
                                <option value="{{$budget->supplier_name}}">{{$budget->supplier_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-4">
                            <label for="supplier_address" class="form-label">Supplier Address</label>
                            <input type="text" class="form-control" name="supplier_address" placeholder="eg: Abu Hail Dubai, UAE" />
                        </div>

                        <div class="col-sm-4">
                            <label for="project_name" class="form-label">Choose Project </label>
                            <select class="form-select" name="project_name">
                                <option disabled selected value>Choose</option>
                                @foreach ($budgetList as $budget)
                                <option value="{{$budget->id}}">{{$budget->reference_code}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="project_name" class="form-label">Choose Project Manager </label>
                            <select class="form-select" name="project_person_id">
                                <option disabled selected value>Choose</option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->first_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 mt-4">
                            <label for="description" class="form-label"> Description </label>
                            <input type="text" class="form-control" name="description" placeholder="description" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Projects Table -->
<div class="card mt-4">
    <h5 class="card-header">PO List</h5>
    <div class="table-responsive text-nowrap limited-scroll">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>PO #</th>
                    <th>Project #</th>
                    <th>Supplier Name</th>
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
                    <td colspan="8" class="text-center">No Data</td> <!-- Adjust colspan based on the number of columns in the table -->
                </tr>
                @else
                @foreach($purchaseOrders as $po)

                @php
                $requestPerson = $userList->firstWhere('id', $po->requested_by);
                $preparedPerson = $userList->firstWhere('id', $po->prepared_by);
                $budget = $budgetList->firstWhere('id', $po->project_id);
                @endphp

                <tr>
                    <!-- PO Number -->
                    <td style="color:#0067aa">
                        <a href="{{ route('purchaseOrder.edit', ['POID' => $po->po_number]) }}">
                            {{ $po->po_number ?? 'N/A' }}
                        </a>
                    </td>

                    <!-- Budget Reference Code -->
                    <td style="color:#0067aa">
                        @if($budget)
                        <a href="{{ route('budget-project-report-summary', ['id' => $budget->id]) }}">
                            {{ $budget->reference_code ?? 'N/A' }}
                        </a>
                        @else
                        <span style="color: red;">No Budget</span>
                        @endif
                    </td>

                    <!-- Supplier Name -->
                    <td>{{ $po->supplier_name ?? 'N/A' }}</td>

                    <!-- Description -->
                    <td>{{ $po->description ?? 'N/A' }}</td>

                    <!-- Prepared By -->
                    <td>{{ optional($preparedPerson)->first_name ?? 'N/A' }}</td>

                    <!-- Requested By -->
                    <td>{{ optional($requestPerson)->first_name ?? 'N/A' }}</td>

                    <!-- Total Budget Allocated -->
                    <td>
                        @if (is_null(optional($budget)->total_budget_allocated) || $budget->total_budget_allocated <= 0)
                            <span style="color: red;">Budget Not Allocated</span>
                            @else
                            {{ number_format($budget->total_budget_allocated, 2) }}
                            @endif
                    </td>

                    <!-- PO Status -->
                    <td>{{ $po->status ?? 'N/A' }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
    </div>
</div>


<!--Model-->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit Project Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProjectForm">
                    <input type="hidden" id="projectId" name="project_id">
                    <div class="mb-3">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="projectName" name="projectName" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectDetails" class="form-label">Project Details</label>
                        <input type="text" class="form-control" id="projectDetails" name="projectDetails">
                    </div>
                    <div class="mb-3">
                        <label for="projectRemarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" id="projectRemarks" name="projectRemarks">
                    </div>
                    <div class="mb-3">
                        <label for="projectStatus" class="form-label">Status</label>
                        <select class="form-select" id="projectStatus" name="projectStatus">
                            <option value="Active">Active</option>
                            <option value="Non Active">Non Active</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        function hideAlertAfterDelay(alertId, delay) {
            console.log('Trying to hide', alertId);
            var alertElement = document.getElementById(alertId);
            if (alertElement) {
                setTimeout(function() {
                    console.log('Hiding', alertId);
                    alertElement.style.opacity = 0; // Fade out effect
                    setTimeout(function() {
                        alertElement.style.display = 'none'; // Hide element after fading out
                    }, 500); // Match the duration of the fade-out effect
                }, delay);
            } else {
                console.log('Element not found:', alertId);
            }
        }

        // Hide alerts after 3000 ms
        hideAlertAfterDelay('error-alert', 3000);
        hideAlertAfterDelay('success-alert', 3000);
    });
</script>

@endsection