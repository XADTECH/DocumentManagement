@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Pages')

@section('content')

    <style>
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #c8d1da !important;
        }

        .table-striped tbody tr.cash-outflow {
            background-color: tomato;
        }

        .dropdown-section {
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 20px;
            cursor: pointer;
        }

        .dropdown-header {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .dropdown-content {
            display: none;
            margin-top: 15px;
        }

        .dropdown-section.active .dropdown-content {
            display: block;
        }

        .limited-scroll {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>

    <script>
        function toggleDropdown(event) {
            const section = event.target.closest('.dropdown-section');
            section.classList.toggle('active');
        }
    </script>

    <!-- Cash Flow Filter Form -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Allocated Budget Lists</h5>
            <div class="d-flex">
                <form class="d-flex" method="GET" action="{{ route('show-allocated-budgets') }}">
                    <input type="text" name="reference_code" class="form-control me-2" placeholder="Budget Reference Code"
                        aria-label="Search" value="{{ request('reference_code') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
        </div>

        <form class="container mt-3" method="GET" action="{{ route('show-allocated-budgets') }}">
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
            <div class="row mb-4">
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('show-allocated-budgets') }}" class="btn btn-secondary">Clear Filter</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Cash Flow Table -->
    @if (request('reference_code') || request('budget_project_id'))
        @if ($allocatedBudgets->isNotEmpty())
            <div class="card mt-4">
                <div class="table-responsive text-nowrap limited-scroll">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Approved Budget</th>
                                <th>Initial Fund Allocated</th>
                                <th>Consumed Fund</th>
                                <th>Received Fund</th>
                                <th>Remaining Fund</th>
                                <th>Remain Budget</th>
                                <th>Salary</th>
                                <th>Facility</th>
                                <th>Material</th>
                                <th>Overhead</th>
                                <th>Financial Cost</th>
                                <th>Capital Expenditure</th>
                                <th>Total DPM</th>
                                <th>Total LPO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allocatedBudgets as $budgetProject)
                                @php
                                    $remaining =
                                        $budgetProject->allocated_budget -
                                        $budgetProject->total_dpm -
                                        $budgetProject->total_lpo;
                                @endphp
                                <tr>
                                    <td>{{ number_format($approvedBudget->approved_budget, 0) }}</td>
                                    <td>{{ number_format($budgetProject->initial_allocation_budget, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_dpm + $budgetProject->total_lpo, 0) }}</td>
                                    <td>{{ number_format($total_amount, 0) }}</td>
                                    <td>{{ number_format($budgetProject->allocated_budget, 0) }}</td>
                                    <td>{{ number_format($approvedBudget->approved_budget - $budgetProject->allocated_budget, 0) }}
                                    </td>
                                    <td>{{ number_format($budgetProject->total_salary, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_facility_cost, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_material_cost, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_cost_overhead, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_financial_cost, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_capital_expenditure, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_dpm, 0) }}</td>
                                    <td>{{ number_format($budgetProject->total_lpo, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-warning mt-4">
                No budgets found for the given filters.
            </div>
        @endif
    @else
        <div class="alert alert-info mt-4">
            Please apply filters to view the allocated budgets.
        </div>
    @endif

    <!-- Invoices Dropdown Section -->
    @if ($invoices && $invoices->isNotEmpty())
        <div class="card mt-4">
            <div class="card-body">
                <div class="dropdown-section">
                    <h3 class="dropdown-header" onclick="toggleDropdown(event)">Invoices ▼ {{ $invoice_count }}</h3>
                    <div class="dropdown-content">
                        <h5>Total Invoices: {{ $invoice_count }}</h5>
                        <h5>Total Amount Received: {{ number_format($total_amount, 0) }}</h5>

                        <div class="table-responsive text-nowrap limited-scroll mt-2">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Invoice Number</th>
                                        <th>Fund Category</th>
                                        <th>Amount Received</th>
                                        <th>Bank</th>
                                        <th>Invoice File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $index => $invoice)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $invoice->date }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->invoice_fund_category }}</td>
                                            <td>{{ number_format($invoice->invoice_dr_amount_received, 0) }}</td>
                                            @php
                                                $bank = $banks
                                                    ->where('id', $invoice->invoice_destination_account)
                                                    ->first();
                                            @endphp
                                            <td>
                                                <a
                                                    href="{{ route('banks.projectledger', ['bank_id' => $bank->id, 'budget_project_id' => $invoice->invoice_budget_project_id]) }}">
                                                    {{ $bank->bank_name }}
                                                </a>
                                            </td>

                                            <td>
                                                @if ($invoice->invoice_file)
                                                    <a href="{{ asset('/' . $invoice->invoice_file) }}"
                                                        target="_blank">View</a>
                                                @else
                                                    No file
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Sender Dropdown Section -->
    @if ($sndr && $sndr->isNotEmpty())
        <div class="card mt-4">
            <div class="card-body">
                <div class="dropdown-section">
                    <h3 class="dropdown-header" onclick="toggleDropdown(event)">Senders ▼ {{ $sndr->count() }}</h3>
                    <div class="dropdown-content">
                        <h5>Total Senders: {{ $sndr->count() }}</h5>

                        <div class="table-responsive text-nowrap limited-scroll mt-2">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Tracking #</th>
                                        <th>Sender Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Sender Bank Name</th>
                                        <th>Sender Bank Account</th>
                                        <th>Destination Account</th>
                                        <th>Fund Type</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sndr as $index => $sender)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $sender->date }}</td>
                                            <td>{{ $sender->tracking_number }}</td>
                                            <td>{{ $sender->sender_name }}</td>
                                            <td>{{ number_format($sender->amount, 0) }}</td>
                                            <td>{{ $sender->sender_for }}</td>
                                            <td>{{ $sender->sender_bank_name }}</td>
                                            <td>{{ $sender->sender_bank_account }}</td>
                                            @php
                                                $bank = $banks->where('id', $sender->destination_account)->first();
                                            @endphp
                                            <td>
                                                <a
                                                    href="{{ route('banks.projectledger', ['bank_id' => $bank->id, 'budget_project_id' => $sender->budget_project_id]) }}">
                                                    {{ $bank->bank_name }}
                                                </a>
                                            </td>
                                            <td>{{ $sender->fund_type }}</td>


                                            <td>{!! nl2br(e($sender->sender_detail)) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Transfer From Management Dropdown Section -->
    @if ($transfers && $transfers->isNotEmpty())
        <div class="card mt-4">
            <div class="card-body">
                <div class="dropdown-section">
                    <h3 class="dropdown-header" onclick="toggleDropdown(event)">Management Transfers ▼
                        {{ $transfers->count() }}</h3>
                    <div class="dropdown-content">
                        <h5>Total Transfers Amount: {{ number_format($total_transfer_amount, 0) }}</h5>

                        <div class="table-responsive text-nowrap limited-scroll mt-2">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date Received</th>
                                        <th>Transfer Designation</th>
                                        <th>Transfer Reference</th>
                                        <th>Fund Category</th>
                                        <th>Amount</th>
                                        <th>Source Account</th>
                                        <th>Destination Account</th>
                                        <th>Amount</th>
                                        <th>Sender Bank Name</th>
                                        <th>Transfer Date</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transfers as $index => $transfer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transfer->date_received }}</td>
                                            <td>{{ $transfer->transfer_designation }}</td>
                                            <td>{{ $transfer->transfer_reference }}</td>
                                            <td>{{ $transfer->fund_category }}</td>
                                            <td>{{ number_format($transfer->transfer_amount, 0) }}</td>

                                            <td>{{ $transfer->source_account }}</td>
                                            @php
                                                $bank = $banks
                                                    ->where('id', $transfer->transfer_destination_account)
                                                    ->first();
                                            @endphp
                                            <td>
                                                <a
                                                    href="{{ route('banks.projectledger', ['bank_id' => $transfer->transfer_destination_account, 'budget_project_id' => $transfer->budget_project_id]) }}">
                                                    {{ $bank->bank_name }}
                                                </a>
                                            </td>
                                            <td>{{ $transfer->sender_bank_name }}</td>
                                            <td>{{ $transfer->transfer_date }}</td>
                                            <td>{!! nl2br(e($transfer->transfer_description)) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Remittance Transfer Dropdown Section -->
    @if ($remittances && $remittances->isNotEmpty())
        <div class="card mt-4">
            <div class="card-body">
                <div class="dropdown-section">
                    <h3 class="dropdown-header" onclick="toggleDropdown(event)">Remittance Transfers ▼
                        {{ $remittances->count() }}</h3>
                    <div class="dropdown-content">
                        <h5>Total Remittance Amount: {{ number_format($remittances->sum('remittance_amount'), 0) }}</h5>

                        <div class="table-responsive text-nowrap limited-scroll mt-2">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reference</th>
                                        <th>Payer Name</th>
                                        <th>Sender Bank</th>
                                        <th>Account Number</th>
                                        <th>Destination Account</th>
                                        <th>Fund Category</th>
                                        <th>Amount</th>
                                        <th>Date Received</th>
                                        <th>Currency</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($remittances as $index => $remittance)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $remittance->remittance_reference }}</td>
                                            <td>{{ $remittance->remittance_payer_name }}</td>
                                            <td>{{ $remittance->remittance_sender_bank }}</td>
                                            <td>{{ $remittance->remittance_account_number }}</td>
                                            @php
                                                $destinationBank = $banks
                                                    ->where('id', $remittance->remittance_destination_account)
                                                    ->first();
                                            @endphp
                                            <td>
                                                <a
                                                    href="{{ route('banks.projectledger', ['bank_id' => $remittance->remittance_destination_account, 'budget_project_id' => $remittance->budget_project_id]) }}">
                                                    {{ $destinationBank->bank_name ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>{{ $remittance->fund_category }}</td>
                                            <td>{{ number_format($remittance->remittance_amount, 0) }}</td>
                                            <td>{{ $remittance->remittance_date_received }}</td>
                                            <td>{{ $remittance->remittance_currency }}</td>
                                            <td>{!! nl2br(e($remittance->remittance_description)) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loan Dropdown Section -->
    @if ($loans && $loans->isNotEmpty())
        <div class="card mt-4">
            <div class="card-body">
                <div class="dropdown-section">
                    <h3 class="dropdown-header" onclick="toggleDropdown(event)">Loans ▼
                        {{ $loans->count() }}</h3>
                    <div class="dropdown-content">
                        <h5>Total Loan Amount: {{ number_format($loans->sum('loan_amount'), 0) }}</h5>

                        <div class="table-responsive text-nowrap limited-scroll mt-2">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Loan Reference</th>
                                        <th>Provider Name</th>
                                        <th>Loan Amount</th>
                                        <th>Interest Rate</th>
                                        <th>Destination Account</th>
                                        <th>Loan Type</th>
                                        <th>Loan Start Date</th>
                                        <th>Loan Date</th>
                                        <th>Loan Repayment Frequency</th>
                                        <th>Fund Category</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loans as $index => $loan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $loan->loan_reference }}</td>
                                            <td>{{ $loan->loan_provider_name }}</td>
                                            <td>{{ number_format($loan->loan_amount, 0) }}</td>
                                            <td>{{ number_format($loan->loan_interest_rate, 0) }}</td>
                                            @php
                                            $destinationBank = $banks
                                                ->where('id', $loan->loan_destination_account)
                                                ->first();
                                        @endphp
                                        <td>
                                            <a
                                                href="{{ route('banks.projectledger', ['bank_id' => $loan->loan_destination_account, 'budget_project_id' => $loan->budget_project_id]) }}">
                                                {{ $destinationBank->bank_name ?? 'N/A' }}
                                            </a>
                                        </td>
                                            <td>{{ $loan->loan_provider_type }}</td>
                                            <td>{{ $loan->loan_repayment_start_date }}</td>
                                            <td>{{ $loan->loan_date }}</td>
                                            <td>{{ $loan->loan_repayment_frequency }}</td>
                                            <td>{{ $loan->fund_category }}</td>
                                            <td>{!! nl2br(e($loan->loan_description)) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif




@endsection
