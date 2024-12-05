@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Pages')

@section('content')

    <style>

    </style>

    <!-- Cash Flow Filter Form -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">CASH FLOW LIST</h5>
            <div class="d-flex">
                <form class="d-flex" method="GET" action="{{ route('budgets.cashflowLists') }}">
                    <input type="text" name="reference_code" class="form-control me-2" placeholder="Budget Reference Code"
                        aria-label="Search" value="{{ request('reference_code') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
        </div>

        <form class="container" method="GET" action="{{ route('budgets.cashflowLists') }}">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="budget_project_id" class="form-label">Budget Project</label>
                    <select class="form-select" name="budget_project_id">
                        <option disabled selected value>Choose</option>
                        @foreach ($budgetProjects as $budgetProject)
                            <option {{ request('budget_project_id') == $budgetProject->id ? 'selected' : '' }}
                                value="{{ $budgetProject->id }}">{{ $budgetProject->reference_code }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row" style="margin-bottom:20px">
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('budgets.cashflowLists') }}" class="btn btn-secondary">Clear Filter</a>
                </div>
            </div>
        </form>
    </div>


    <!-- Cash Flow Table -->
    @if (request('budget_project_id') || request('reference_code'))
        @if ($cashFlows->count() > 0)
            <div class="text-right mt-3" style="display: flex; justify-content: flex-end;">
                <a href="{{ route('download.cashflow', ['POID' => request('budget_project_id')]) }}"
                    class="btn btn-primary">
                    <i class="fa fa-download"></i> Download
                </a>
            </div>
        @endif

        <div class="card mt-4">
            <div class="table-responsive text-nowrap limited-scroll">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Reference</th>
                            <th>Cash Inflow</th>
                            <th>Cash Outflow</th>
                            {{-- <th>Committed Budget</th> --}}
                            <th>Total DPM</th>
                            <th>Total LPO</th>
                            <th>Balance</th>
                            <th>Project Manager</th>

                            {{-- <th>Total Budget Allocated</th> --}}
                        </tr>
                    </thead>
                    <tbody id="cashflow-table-body" class="table-border-bottom-0">
                        @foreach ($cashFlows as $index => $cashFlow)
                            <!-- Add $index to get the current iteration -->
                            <tr>
                                @php
                                    $project = $budgetProjects->firstWhere('id', $cashFlow->budget_project_id);
                                    $user = $users->firstWhere('id', $project->manager_id);
                                    $dpm = $allocatedBudgets->firstWhere(
                                        'budget_project_id',
                                        $cashFlow->budget_project_id,
                                    );
                                @endphp
                                <td>{{ $cashFlow->date }}</td>
                                <td>{{ $cashFlow->description }}</td>
                                <td>{{ $cashFlow->category }}</td>
                                <td>
                                    @if (str_contains($cashFlow->reference_code, 'INV-'))
                                        @php
                                            $inv = $invoice
                                                ->where('invoice_number', $cashFlow->reference_code)
                                                ->first(); // Get the first matching invoice
                                        @endphp

                                        @if ($inv)
                                        <a href="{{ asset('storage/' . $inv->invoice_file) }}" target="_blank">{{ $cashFlow->reference_code }}</a>

                                        @else
                                            {{ $cashFlow->reference_code }}
                                        @endif
                                    @else
                                        {{ $cashFlow->reference_code }}
                                    @endif
                                </td>
                                <td class="{{ $index >= 6 && $cashFlow->cash_inflow > 0 ? 'text-primary' : '' }}">
                                    {{ number_format($cashFlow->cash_inflow, 0) }}
                                </td>
                                <td class="{{ $index >= 6 && $cashFlow->cash_outflow > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($cashFlow->cash_outflow, 0) }}
                                </td>
                                {{-- <td>{{ number_format($cashFlow->committed_budget, 0) }}</td> --}}

                                <td>{{ number_format($dpm->total_dpm, 0) }}</td>
                                <td>{{ number_format($dpm->total_lpo, 0) }}</td>
                                <td>{{ number_format($cashFlow->balance, 0) }}</td>
                                {{-- <td>{{ $cashFlow->reference_code }}</td> --}}

                                <td>{{ $user->first_name ?? 'N/A' }}</td>

                                {{-- <td>{{ number_format($allocatedBudgets[0]->allocated_budget) }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @endif


    @if (!request('budget_project_id') && !request('reference_code'))
        <div class="alert alert-info mt-4">
            Please apply filters to view the Cash Flow List.
        </div>
    @endif

@endsection
