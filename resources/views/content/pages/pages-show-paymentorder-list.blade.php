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

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Budget Management /</span> Filter Payment Orders
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
                    <h6>Filter Payment Orders</h6>
                    <form id="filterForm" action="{{ route('paymentOrders.list') }}" method="GET">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="project_id" class="form-label">Choose Project</label>
                                <select class="form-select" name="project_id" id="project_id">
                                    <option disabled selected value>Choose</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->reference_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="payment_order_id" class="form-label">Choose Payment Order</label>
                                <select class="form-select" name="payment_order_id" id="payment_order_id">
                                    <option disabled selected value>Choose</option>
                                    @foreach ($paymentOrders as $po)
                                        <option value="{{ $po->id }}"
                                            {{ request('payment_order_id') == $po->id ? 'selected' : '' }}>
                                            {{ $po->payment_order_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="po_number" class="form-label">Enter Payment Order</label>
                                <input type="text" id="po_number" class="form-control" name="po_number"
                                    value="{{ request('po_number') }}" placeholder="Enter PO Number" />
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-4">
                                <label for="project_reference" class="form-label">Enter Project Reference</label>
                                <input type="text" id="project_reference" class="form-control" name="project_reference"
                                    value="{{ request('project_reference') }}" placeholder="Enter Project Reference" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <button type="button" class="btn btn-secondary" id="clearFilters">Clear Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Orders Table -->
            <div class="card mt-4">
                <h5 class="card-header">PO List</h5>
                <div class="table-responsive text-nowrap limited-scroll">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>PO #</th>
                                <th>Project #</th>
                                <th>Payment Method</th>
                                <th>Prepared By</th>
                                <th>Status</th>
                                <th>Submit Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="project-table-body" class="table-border-bottom-0">
                            @if ($filteredPaymentOrders->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">No Data</td>
                                </tr>
                            @else
                                @foreach ($filteredPaymentOrders as $po)
                                    <tr>
                                        <td>
                                            <form
                                                action="{{ route('paymentOrders.show', ['id' => $po->payment_order_number]) }}"
                                                method="GET" style="display: inline;">
                                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline"
                                                    style="text-decoration: underline; background: none; border: none;">
                                                    {{ $po->payment_order_number }}
                                                </button>
                                            </form>
                                        </td>
                                        @php
                                            $project = $budgetList->where('id', $po->budget_project_id)->first();
                                        @endphp
                                        <td>{{ $project->reference_code ?? 'N/A' }}</td>
                                        <td>{{ $po->payment_method }}</td>
                                        @php
                                            $user = $userList->where('id', $po->user_id)->first();
                                        @endphp
                                        <td>{{ $user->email ?? 'N/A' }}</td>
                                        <td class="text-success" style="font-weight:bold">{{ $po->status }}</td>
                                        <td class="text-success" style="font-weight:bold">{{ $po->paid_status }}</td>
                                        <td>
                                            <form
                                                action="{{ route('paymentOrders.destroy', ['id' => $po->payment_order_number]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this payment order?');"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-link text-danger p-0 m-0 align-baseline"
                                                    style="background: none; border: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
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
            window.location.href = "{{ route('paymentOrders.list') }}";
        });
    </script>

@endsection
