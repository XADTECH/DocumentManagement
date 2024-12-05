@extends('layouts/contentNavbarLayout')

@section('title', 'Fund Management - Record Transaction')

@section('content')

    <style>
        .hidden {
            display: none;
        }
    </style>

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Fund Management /</span> Record Transaction
    </h4>

    <div class="row">
        <div class="col-md-12">
            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Fund Form -->
            <div class="card">
                <div class="card-body">
                    <h6>Record Transaction</h6>
                    <form action="{{ route('cashflow.storeDPM') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Date -->
                            <div class="col-sm-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" id="date" class="form-control" name="date"
                                    value="{{ old('date') }}" />
                            </div>

                            <!-- Fund Type -->
                            <div class="col-sm-4">
                                <label for="fund_type" class="form-label">Fund Type</label>
                                <select class="form-select" id="fund_type" name="fund_type" onchange="toggleFundTypeUI()">
                                    <option disabled selected value>Choose Fund Type</option>
                                    <option value="Inflow" {{ old('fund_type') == 'Inflow' ? 'selected' : '' }}>Inflow
                                    </option>
                                    <option value="Outflow" {{ old('fund_type') == 'Outflow' ? 'selected' : '' }}>Outflow
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Inflow Fields - Visible only when "Inflow" is selected -->
                        <div id="inflowFields" class="hidden mt-4">
                            <h6>Inflow Details</h6>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="main_category"
                                        onchange="toggleCategoryUI()">
                                        <option disabled selected value>Choose Category</option>
                                        {{-- <option value="Salary" {{ old('category') == 'Salary' ? 'selected' : '' }}>Salary
                                        </option>
                                        <option value="Facility" {{ old('category') == 'Facility' ? 'selected' : '' }}>
                                            Facility</option>
                                        <option value="Material" {{ old('category') == 'Material' ? 'selected' : '' }}>
                                            Material</option>
                                        <option value="Overhead" {{ old('category') == 'Overhead' ? 'selected' : '' }}>
                                            Overhead</option>
                                        <option value="Financial" {{ old('category') == 'Financial' ? 'selected' : '' }}>
                                            Financial</option>
                                        <option value="Capital Expenditure"
                                            {{ old('category') == 'Capital Expenditure' ? 'selected' : '' }}>Capital
                                            Expenditure</option> --}}
                                        <option value="Invoice" {{ old('category') == 'Invoice' ? 'selected' : '' }}>Invoice
                                        </option>
                                        <option value="Funds Transfer from Management"
                                            {{ old('category') == 'Funds Transfer from Management' ? 'selected' : '' }}>
                                            Funds Transfer from Management</option>
                                        <option value="Account Remittance"
                                            {{ old('category') == 'Account Remittance' ? 'selected' : '' }}>Account
                                            Remittance</option>
                                        <option value="Bank Loan" {{ old('category') == 'Bank Loan' ? 'selected' : '' }}>
                                            Bank Loan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Shared Fields for All Inflow Categories Except Invoice -->
                        <div id="sharedFields" class="hidden mt-4">
                            <h6>Transaction Details</h6>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="cash_inflow" class="form-label">Cash Inflow</label>
                                    <input type="number" id="cash_inflow" class="form-control" name="cash_inflow"
                                        value="{{ old('cash_inflow') }}" placeholder="Enter Cash Inflow" />
                                </div>
                                <div class="col-sm-4">
                                    <label for="budget_project" class="form-label">Budget Project</label>
                                    <select class="form-select" id="budget_project" name="budget_project">
                                        <option disabled selected value>Choose Budget Project</option>
                                        @foreach ($budgetProjects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('budget_project') == $project->id ? 'selected' : '' }}>
                                                {{ $project->reference_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" id="description" class="form-control" name="description"
                                        value="{{ old('description') }}" placeholder="Enter Description" />
                                </div>
                            </div>
                        </div>

                        <!-- Outflow Fields - Visible only when "Outflow" is selected -->
                        <div id="outflowFields" class="hidden mt-4">
                            <h6>Outflow Details</h6>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="cash_outflow" class="form-label">Cash Outflow</label>
                                    <input type="number" id="cash_outflow" class="form-control" name="cash_outflow"
                                        value="{{ old('cash_outflow') }}" placeholder="Enter Cash Outflow" />
                                </div>
                                <div class="col-sm-4">
                                    <label for="budget_project_outflow" class="form-label">Budget Project</label>
                                    <select class="form-select" id="budget_project_outflow" name="budget_project_outflow">
                                        <option disabled selected value>Choose Budget Project</option>
                                        @foreach ($budgetProjects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('budget_project_outflow') == $project->id ? 'selected' : '' }}>
                                                {{ $project->reference_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="description_outflow" class="form-label">Description</label>
                                    <input type="text" id="description_outflow" class="form-control"
                                        name="description_outflow" value="{{ old('description_outflow') }}"
                                        placeholder="Enter Description" />
                                </div>
                            </div>
                        </div>
                        <!-- Invoice Fields - Visible only when "Invoice" is selected -->
                        <div id="invoiceFields" class="hidden mt-4">
                            <h6>Invoice Details</h6>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="invoice_number" class="form-label">Invoice Number</label>
                                    <input type="text" id="invoice_number" class="form-control" name="invoice_number"
                                        value="{{ old('invoice_number') }}" placeholder="Enter Invoice Number" />
                                </div>
                                <div class="col-sm-4">
                                    <label for="dr_amount_received" class="form-label">DR Amount Received</label>
                                    <input type="number" id="invoice_dr_amount_received" class="form-control"
                                        name="invoice_dr_amount_received" value="{{ old('dr_amount_received') }}"
                                        placeholder="Enter DR Amount Received" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="budget_project_id" class="form-label">Budget Project</label>
                                    <select class="form-select" name="invoice_budget_project_id">
                                        <option disabled selected value>Choose Project</option>
                                        @foreach ($budgetProjects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('budget_project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->reference_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Fund Category Dropdown -->
                            <div class="row mt-3">
                                <div class="col-sm-4">
                                    <label for="fund_category" class="form-label">Fund Category</label>
                                    <select id="fund_category" name="invoice_fund_category" class="form-select">
                                        <option disabled selected value>Choose Fund Category</option>
                                        <option value="Salary" {{ old('fund_category') == 'Salary' ? 'selected' : '' }}>
                                            Salary</option>
                                        <option value="Facility"
                                            {{ old('fund_category') == 'Facility' ? 'selected' : '' }}>Facility</option>
                                        <option value="Material"
                                            {{ old('fund_category') == 'Material' ? 'selected' : '' }}>Material</option>
                                        <option value="Overhead"
                                            {{ old('fund_category') == 'Overhead' ? 'selected' : '' }}>Overhead</option>
                                        <option value="Financial"
                                            {{ old('fund_category') == 'Financial' ? 'selected' : '' }}>
                                            Financial</option>
                                        <option value="Capital Expenditure"
                                            {{ old('fund_category') == 'Capital Expenditure' ? 'selected' : '' }}>
                                            Capital Expenditure</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="bank" class="form-label">Bank</label>
                                    <select id="invoice_destination_account" name="invoice_destination_account"
                                        class="form-control">
                                        <option value="">Select Receiving Account</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} -
                                                {{ $bank->country }} - {{ $bank->region }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="invoice_file" class="form-label">Upload Invoice</label>
                                    <input type="file" id="invoice_file" class="form-control" name="invoice_file" />
                                </div>
                            </div>

                            <!-- Sender Details -->
                            <div class="row mt-3">
                                <div class="col-sm-4">
                                    <label for="invoice_sender_name" class="form-label">Sender Name</label>
                                    <input type="text" id="invoice_sender_name" class="form-control"
                                        name="invoice_sender_name" value="{{ old('invoice_sender_name') }}"
                                        placeholder="Enter Sender Name" />
                                </div>

                                <div class="col-sm-8">
                                    <label for="invoice_sender_bank_name" class="form-label">Sender Bank Name</label>
                                    <input type="text" id="invoice_sender_bank_name" class="form-control"
                                        name="invoice_sender_bank_name" value="{{ old('invoice_sender_bank_name') }}"
                                        placeholder="Enter Sender Bank Name" />
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <label for="invoice_sender_bank_account" class="form-label">Sender Bank Account
                                        Number</label>
                                    <input type="text" id="invoice_sender_bank_account" class="form-control"
                                        name="invoice_sender_bank_account"
                                        value="{{ old('invoice_sender_bank_account') }}"
                                        placeholder="Enter Sender Bank Account Number" />
                                </div>

                                <div class="col-sm-8">
                                    <label for="sender_detail" class="form-label">Sender Details</label>
                                    <textarea id="sender_detail" name="sender_detail" class="form-control" rows="4" cols="50"
                                        placeholder="Enter details here...">{{ old('sender_detail') }}</textarea>
                                </div>

                            </div>

                            <!-- Invoice Items -->
                            <div class="mt-4">
                                <h6>Invoice Items</h6>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Description</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceItems">
                                        <!-- Dynamic Rows Will Be Added Here -->
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-secondary mt-2" onclick="addInvoiceItem()">Add
                                    Item</button>
                            </div>
                        </div>


                        <!-- Account Remittance Fields -->
                        <div id="accountRemittanceFields" class="hidden mt-4">


                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="remittance_reference" class="form-label">Remittance Reference
                                        Number</label>
                                    <input type="text" id="remittance_reference" class="form-control"
                                        name="remittance_reference" placeholder="Enter Reference Number" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="remittance_payer_name" class="form-label">Payer Name</label>
                                    <input type="text" id="remittance_payer_name" class="form-control"
                                        name="remittance_payer_name" placeholder="Enter Payer Name" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="remittance_amount" class="form-label">Amount Received</label>
                                    <input type="text" id="remittance_amount" class="form-control"
                                        name="remittance_amount" placeholder="Enter Amount"
                                        oninput="formatNumber(this)" />
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <label for="remittance_sender_bank" class="form-label">Sender Bank</label>
                                    <input type="text" id="remittance_sender_bank" class="form-control"
                                        name="remittance_sender_bank" placeholder="Enter Sender's Bank Name" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="remittance_account_number" class="form-label">Sender Bank Account
                                        Number</label>
                                    <input type="text" id="remittance_account_number" class="form-control"
                                        name="remittance_account_number" placeholder="Enter Sender Account Number" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="remittance_destination_account" class="form-label">Bank Receiving</label>
                                    <select id="remittance_destination_account" name="remittance_destination_account"
                                        class="form-select">
                                        <option value="">Select Receiving Account</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} -
                                                {{ $bank->country }} - {{ $bank->region }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>

                            <!-- Third Row with Fund Category -->
                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <label for="fund_category" class="form-label">Fund Category</label>
                                    <select id="fund_category" name="fund_category" class="form-select">
                                        <option disabled selected value>Choose Fund Category</option>
                                        <option value="Salary" {{ old('fund_category') == 'Salary' ? 'selected' : '' }}>
                                            Salary</option>
                                        <option value="Facility"
                                            {{ old('fund_category') == 'Facility' ? 'selected' : '' }}>Facility</option>
                                        <option value="Material"
                                            {{ old('fund_category') == 'Material' ? 'selected' : '' }}>Material</option>
                                        <option value="Overhead"
                                            {{ old('fund_category') == 'Overhead' ? 'selected' : '' }}>Overhead</option>
                                        <option value="Finance" {{ old('fund_category') == 'Finance' ? 'selected' : '' }}>
                                            Finance</option>
                                        <option value="Capital Expenditure"
                                            {{ old('fund_category') == 'Capital Expenditure' ? 'selected' : '' }}>Capital
                                            Expenditure</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="budget_project_id" class="form-label">Budget Project</label>
                                    <select class="form-select" name="budget_project_id">
                                        <option disabled selected value>Choose Project</option>
                                        @foreach ($budgetProjects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('budget_project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->reference_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="remittance_date_received" class="form-label">Date Received</label>
                                    <input type="date" id="remittance_date_received" class="form-control"
                                        name="remittance_date_received" />
                                </div>
                            </div>

                            <!-- Fourth Row -->
                            <div class="row mt-3">

                                <div class="col-sm-4">
                                    <label for="remittance_currency" class="form-label">remittance_currency</label>
                                    <input type="text" id="remittance_currency" class="form-control"
                                        name="remittance_currency" placeholder="e.g., USD, EUR" />
                                </div>
                                <div class="col-sm-8">
                                    <label for="remittance_description" class="form-label">Purpose/Description</label>
                                    <textarea id="remittance_description" class="form-control" name="remittance_description"
                                        placeholder="Enter Description" rows="4"></textarea>
                                </div>

                            </div>
                        </div>


                        <!-- Bank Transfer Fields -->
                        <div id="bankTransferFields" class="hidden mt-4">
                            <h6>Bank Transfer Details</h6>

                            <!-- First Row -->
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="transfer_reference" class="form-label">Transfer Reference Number</label>
                                    <input type="text" id="transfer_reference" class="form-control"
                                        name="transfer_reference" placeholder="Enter Transfer Reference Number" />
                                </div>

                                <div class="col-md-4">
                                    <label for="fund_category" class="form-label">Fund Category</label>
                                    <select id="fund_category" name="fund_category" class="form-select">
                                        <option disabled selected value>Choose Fund Category</option>
                                        <option value="Salary" {{ old('fund_category') == 'Salary' ? 'selected' : '' }}>
                                            Salary</option>
                                        <option value="Facility"
                                            {{ old('fund_category') == 'Facility' ? 'selected' : '' }}>Facility</option>
                                        <option value="Material"
                                            {{ old('fund_category') == 'Material' ? 'selected' : '' }}>Material</option>
                                        <option value="Overhead"
                                            {{ old('fund_category') == 'Overhead' ? 'selected' : '' }}>Overhead</option>
                                        <option value="Financial"
                                            {{ old('fund_category') == 'Financial' ? 'selected' : '' }}>
                                            Financial</option>
                                        <option value="Capital Expenditure"
                                            {{ old('fund_category') == 'Capital Expenditure' ? 'selected' : '' }}>Capital
                                            Expenditure</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="source_account" class="form-label">Source Account (Sender)</label>
                                    <input type="text" id="source_account" class="form-control" name="source_account"
                                        placeholder="Enter Source Account" />
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="transfer_amount" class="form-label">Transfer Amount</label>
                                    <input type="number" id="transfer_amount" class="form-control"
                                        name="transfer_amount" placeholder="Enter Amount" />
                                </div>

                                <div class="col-md-4">
                                    <label for="sender_bank_name" class="form-label">Sender Bank Name</label>
                                    <input type="text" id="sender_bank_name" class="form-control"
                                        name="sender_bank_name" placeholder="Enter Sender Bank Name" />
                                </div>

                                <div class="col-md-4">
                                    <label for="transfer_destination_account" class="form-label">Destination Account
                                        (Receiving)</label>
                                    <select id="transfer_destination_account" name="transfer_destination_account"
                                        class="form-select">
                                        <option value="">Select Receiving Account</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} -
                                                {{ $bank->country }} - {{ $bank->region }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Third Row -->
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="transfer_date" class="form-label">Transfer Date</label>
                                    <input type="date" id="transfer_date" class="form-control"
                                        name="transfer_date" />
                                </div>

                                <div class="col-md-4">
                                    <label for="budget_project_id" class="form-label">Budget Project</label>
                                    <select id="budget_project_id" class="form-select" name="budget_project_id">
                                        <option disabled selected value>Choose Project</option>
                                        @foreach ($budgetProjects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('budget_project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->reference_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="date_received" class="form-label">Date Received</label>
                                    <input type="date" id="date_received" class="form-control"
                                        name="date_received" />
                                </div>


                                <!-- Fourth Row -->
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label for="transfer_designation" class="form-label">Designation</label>
                                        <input type="input" id="transfer_designation" class="form-control"
                                            name="transfer_designation" placeholder="From CEO ... Finance" />
                                    </div>

                                    <div class="col-md-8">
                                        <label for="transfer_description" class="form-label">Purpose/Description</label>
                                        <textarea id="transfer_description" class="form-control" name="transfer_description" placeholder="Enter Description"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- Loan Source of Funds Fields -->
                        <div id="loanFields" class="hidden mt-4">
                            <h6>Loan Details</h6>

                            <!-- First Row -->
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="loan_reference" class="form-label">Loan Reference Number</label>
                                    <input type="text" id="loan_reference" class="form-control" name="loan_reference"
                                        placeholder="Enter Loan Reference Number" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="loan_provider_type" class="form-label">Provider Type</label>
                                    <select id="loan_provider_type" name="loan_provider_type" class="form-control">
                                        <option value="">Select Provider Type</option>
                                        <option value="financial instituion">Financial Institution</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="loan_provider_name" class="form-label">Provider Name</label>
                                    <input type="text" id="loan_provider_name" class="form-control"
                                        name="loan_provider_name" placeholder="Enter Provider's Name" />
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <label for="loan_amount" class="form-label">Amount of Loan</label>
                                    <input type="number" id="loan_amount" class="form-control" name="loan_amount"
                                        placeholder="Enter Loan Amount" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="loan_interest_rate" class="form-label">Interest Rate (%)</label>
                                    <input type="number" id="loan_interest_rate" class="form-control"
                                        name="loan_interest_rate" placeholder="Enter Interest Rate" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="loan_bank_account" class="form-label">Loan Bank Account Number</label>
                                    <input type="text" id="loan_bank_account" class="form-control"
                                        name="loan_bank_account" placeholder="Enter Loan Account No" />
                                </div>
                            </div>

                            <!-- Third Row with Fund Category -->
                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <label for="fund_category" class="form-label">Fund Category</label>
                                    <select id="fund_category" name="fund_category" class="form-select">
                                        <option disabled selected value>Choose Fund Category</option>
                                        <option value="Salary" {{ old('fund_category') == 'Salary' ? 'selected' : '' }}>
                                            Salary</option>
                                        <option value="Facility"
                                            {{ old('fund_category') == 'Facility' ? 'selected' : '' }}>Facility
                                        </option>
                                        <option value="Material"
                                            {{ old('fund_category') == 'Material' ? 'selected' : '' }}>Material
                                        </option>
                                        <option value="Overhead"
                                            {{ old('fund_category') == 'Overhead' ? 'selected' : '' }}>Overhead
                                        </option>
                                        <option value="Financial" {{ old('fund_category') == 'Finance' ? 'selected' : '' }}>
                                            Finance</option>
                                        <option value="Capital Expenditure"
                                            {{ old('fund_category') == 'Capital Expenditure' ? 'selected' : '' }}>
                                            Capital
                                            Expenditure</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="loan_repayment_start_date" class="form-label">Repayment Start Date</label>
                                    <input type="date" id="loan_repayment_start_date" class="form-control"
                                        name="loan_repayment_start_date" />
                                </div>

                                <div class="col-sm-4">
                                    <label for="loan_repayment_frequency" class="form-label">Repayment Frequency</label>
                                    <select id="loan_repayment_frequency" name="loan_repayment_frequency"
                                        class="form-control">
                                        <option value="">Select Frequency</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Quarterly">Quarterly</option>
                                        <option value="Annually">Annually</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fourth Row -->
                            <div class="row mt-4">
                                <div class="col-sm-4">
                                    <label for="receiving_bank" class="form-label">Receiving Bank Account</label>
                                    <select id="loan_destination_account" name="loan_destination_account"
                                        class="form-control">
                                        <option value="">Select Receiving Account</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} -
                                                {{ $bank->country }} - {{ $bank->region }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="budget_project_id" class="form-label">Budget Project</label>
                                    <select class="form-select" name="budget_project_id">
                                        <option disabled selected value>Choose Project</option>
                                        @foreach ($budgetProjects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('budget_project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->reference_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="loan_date" class="form-label">Loan Date</label>
                                    <input type="date" id="loan_date" class="form-control" name="loan_date" />
                                </div>
                            </div>

                            <!-- Fifth Row -->
                            <div class="row mt-4">
                                <div class="col-sm-12">
                                    <label for="loan_description" class="form-label">Purpose/Description</label>
                                    <textarea id="loan_description" class="form-control" name="loan_description" placeholder="Enter Purpose of Loan"
                                        rows="4"></textarea>
                                </div>
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

    <script>
        // Toggle Fund Type UI
        function toggleFundTypeUI() {
            const fundType = document.getElementById('fund_type').value;
            document.getElementById('inflowFields').classList.toggle('hidden', fundType !== 'Inflow');
            document.getElementById('outflowFields').classList.toggle('hidden', fundType !== 'Outflow');
            document.getElementById('sharedFields').classList.add('hidden');
            document.getElementById('invoiceFields').classList.add('hidden');
        }

        function toggleCategoryUI() {
            const category = document.getElementById('category').value;

            // Hide all sections by default
            document.getElementById('invoiceFields').classList.add('hidden');
            document.getElementById('bankTransferFields').classList.add('hidden');
            document.getElementById('accountRemittanceFields').classList.add('hidden');
            document.getElementById('loanFields').classList.add('hidden'); // Ensure loanFields is hidden initially

            // Show the appropriate section based on the selected category
            if (category === 'Invoice') {
                document.getElementById('invoiceFields').classList.remove('hidden');
            } else if (category === 'Funds Transfer from Management') {
                document.getElementById('bankTransferFields').classList.remove('hidden');
            } else if (category === 'Account Remittance') {
                document.getElementById('accountRemittanceFields').classList.remove('hidden');
            } else if (category === 'Bank Loan') {
                document.getElementById('loanFields').classList.remove('hidden');
            }
        }

        // Call the function on page load to set the correct section if there's an old value
        document.addEventListener('DOMContentLoaded', function() {
            toggleCategoryUI();
        });


        // Add Invoice Item
        function addInvoiceItem() {
            const tableBody = document.getElementById('invoiceItems');
            const row = document.createElement('tr');

            row.innerHTML = `
            <td><input type="text" name="item_description[]" class="form-control" placeholder="Enter Item Description" /></td>
            <td><input type="number" name="amount[]" class="form-control" placeholder="Enter Amount" /></td>
            <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
        `;

            tableBody.appendChild(row);
        }

        // Remove Row
        function removeRow(button) {
            button.closest('tr').remove();
        }

        function formatNumber(input) {
            // Remove any existing commas
            let value = input.value.replace(/,/g, '');

            // Ensure it is a number
            if (!isNaN(value) && value !== '') {
                // Add commas for thousands separator
                input.value = parseFloat(value).toLocaleString('en-US');
            } else {
                input.value = ''; // Reset if not a valid number
            }
        }
    </script>

@endsection
