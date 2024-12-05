@extends('layouts/contentNavbarLayout')

@section('title', 'Create Payment Order')

@section('content')

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 20px;
        }

        .container {
            max-width: 90%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h4 {
            color: #1a73e8;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .btn-primary {
            background-color: #1a73e8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #135ba3;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        .payment-order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .payment-order-table th,
        .payment-order-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .payment-order-table th {
            background-color: #1a73e8;
            color: white;
        }

        .add-item-btn {
            margin-top: 20px;
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-item-btn:hover {
            background-color: #218838;
        }

        #pdfPreviewContainer {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>


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

    <div class="container">
        <form action="{{ route('paymentOrders.update', ['id' => $po->payment_order_number]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Indicates a PUT request -->
            <!-- Payment Order Details -->
            <div class="form-section">
                <h4>Payment Order Details</h4>
                <div class="row">
                    <div class="col-md-4">
                        <label for="payment_order_number" class="form-label">Payment Order Number</label>
                        <input type="text" id="payment_order_number" name="payment_order_number" class="form-control"
                            value="{{ $po->payment_order_number ?? 'No Value' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="budget_reference_code" class="form-label">Budget Project</label>
                        <input type="text" id="budget_reference_code" name="budget_reference_code" class="form-control"
                            value="{{ $budget->reference_code ?? 'No Value' }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="project_name" class="form-label">Project Name</label>
                        <input type="text" id="project_name" name="project_name" class="form-control"
                            value="{{ $project->name ?? 'No Value' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Beneficiary Details -->

            @if ($po->payment_method === 'cash')
                <!-- Cash -->
                <div id="cashFields" class="form-section">
                    <h4>Cash Payment Details</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cashReceivedBy" class="form-label">Received By</label>
                            <input type="text" id="cashReceivedBy" name="cash_received_by" class="form-control"
                                placeholder="Enter Receiver's Name" value="{{ $po->cash_received_by ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="cashDate" class="form-label">Payment Date</label>
                            <input type="date" id="cashDate" name="cash_date" class="form-control"
                                value="{{ optional($po)->cash_date ? \Carbon\Carbon::parse($po->cash_date)->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="cashAmount" class="form-label">Cash Amount</label>
                            <input type="text" id="cashAmount" name="cash_amount" class="form-control"
                                value="{{ old('cash_amount', isset($po->cash_amount) ? number_format($po->cash_amount, 0) : '') }}"
                                placeholder="Enter cash amount" oninput="formatNumber(this)">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label for="cashDetail" class="form-label">Cash Detail</label>
                            <textarea id="cashDetail" name="cash_detail" class="form-control" rows="2"
                                placeholder="Enter cash details here...">{{ old('cash_detail', $po->cash_detail ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            @elseif($po->payment_method === 'online transaction')
                <!-- Online Transaction -->
                <div id="onlineTransactionFields" class="form-section">
                    <h4>Online Transaction Details</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="transaction_number" class="form-label">Transaction Number</label>
                            <input type="text" id="transaction_number" name="transaction_number" class="form-control"
                                placeholder="Enter Transaction ID" value="{{ $po->transaction_number ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="transactionAmount" class="form-label">Transaction Amount</label>
                            <input type="text" id="transactionAmount" name="transaction_amount" class="form-control"
                                value="{{ old('transaction_amount', isset($po->transaction_amount) ? number_format($po->transaction_amount, 0) : '') }}"
                                placeholder="Enter transaction amount" oninput="formatNumber(this)">
                        </div>
                        <div class="col-md-4">
                            <label for="transaction_detail" class="form-label">Transaction Detail</label>
                            <textarea id="transaction_detail" name="transaction_detail" class="form-control"
                                placeholder="Please enter detail..." rows="3">{{ $po->transaction_detail ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @elseif($po->payment_method === 'cheque')
                <!-- Cheque -->
                <div id="chequeFields" class="form-section">
                    <h4>Cheque Payment Details</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="chequeNumber" class="form-label">Cheque Number</label>
                            <input type="text" id="chequeNumber" name="cheque_number" class="form-control"
                                placeholder="Enter Cheque Number"
                                value="{{ old('cheque_number', $po->cheque_number ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="chequeDate" class="form-label">Cheque Date</label>
                            <input type="date" id="chequeDate" name="cheque_date" class="form-control"
                                value="{{ old('cheque_date', $po->cheque_date ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="totalChequeAmount" class="form-label">Total Cheque Amount</label>
                            <input type="text" id="totalChequeAmount" name="total_cheque_amount" class="form-control"
                                placeholder="Enter Cash Amount"
                                value="{{ old('total_cheque_amount', isset($po->total_cheque_amount) ? number_format($po->total_cheque_amount, 0) : '') }}"
                                oninput="formatNumber(this)">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4 d-flex flex-column justify-content-center">
                            <label for="chequeFile" class="form-label mb-1">Upload Cheque (PDF)</label>
                            @if (!empty($po->cheque_file))
                                <a href="{{ asset($po->cheque_file) }}" target="_blank" class="btn btn-link p-0 m-0">
                                    View Current File (PDF)
                                </a>
                            @else
                                <input type="file" id="chequeFile" name="cheque_file" class="form-control"
                                    accept="application/pdf">
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label for="chequePayee" class="form-label">Payee Name</label>
                            <input type="text" id="chequePayee" name="cheque_payee" class="form-control"
                                placeholder="Enter Payee Name"
                                value="{{ old('cheque_payee', $po->cheque_payee ?? '') }}">
                        </div>
                    </div>

                    <!-- PDF Preview Section -->
                    <div id="pdfPreviewContainer" class="mt-4 d-none">
                        <h5>PDF Preview:</h5>
                        <embed id="pdfPreview" src="" type="application/pdf" width="100%" height="400px" />
                    </div>
                </div>
                
            @elseif($po->payment_method === 'bank transfer')
                <!-- Bank Transfer -->
                <div id="bankTransferFields" class="form-section">
                    <h4>Bank Transfer Details</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="beneficiaryName" class="form-label">Beneficiary Name</label>
                            <input type="text" id="beneficiaryName" name="beneficiary_name" class="form-control"
                                placeholder="Enter Beneficiary Name" value="{{ $po->beneficiary_name ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="iban" class="form-label">IBAN/Account Number</label>
                            <input type="text" id="iban" name="iban" class="form-control"
                                placeholder="Enter IBAN/Account" value="{{ $po->iban ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="bankAmount" class="form-label">Total Bank Transfer Amount</label>
                            <input type="text" id="bankAmount" name="total_bank_transfer" class="form-control"
                                value="{{ old('total_bank_transfer', isset($po->total_bank_transfer) ? number_format($po->total_bank_transfer, 0) : '') }}"
                                placeholder="Enter total transfer amount" oninput="formatNumber(this)">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="bankName" class="form-label">Bank Name</label>
                            <input type="text" id="bankName" name="bank_name" class="form-control"
                                placeholder="Enter Bank Name" value="{{ $po->bank_name ?? '' }}">
                        </div>
                        <div class="col-md-8">
                            <label for="bankTransfer" class="form-label">Details</label>
                            <textarea id="bankTransfer" name="bank_transfer_details" class="form-control" placeholder="Enter Paid To"
                                rows="3">{{ $po->bank_transfer_details ?? '' }}</textarea>
                        </div>

                    </div>
                </div>
            @else
                <p class="text-muted">Please select a valid payment method to see relevant fields.</p>
            @endif


            <!-- Payment Items -->
            <div class="form-section">
                <h4>Payment Items</h4>
                <div class="table-container">
                    <table class="payment-order-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="paymentItems">
                            <!-- Check if item_description and item_amount exist -->
                            @if (isset($po->item_description) && isset($po->item_amount))
                                @php
                                    // Decode JSON strings into PHP arrays
                                    $itemDescriptions = json_decode($po->item_description, true);
                                    $itemAmounts = json_decode($po->item_amount, true);
                                @endphp

                                @foreach ($itemDescriptions as $index => $description)
                                    <tr>
                                        <td>
                                            <input type="text" name="item_description[]" class="form-control"
                                                value="{{ $description }}" placeholder="Enter Description"
                                                {{ isset($po) && $po->submit_status === 'Submitted' ? 'readonly' : '' }}>
                                        </td>
                                        <td>
                                            <input type="text" name="item_amount[]" class="form-control"
                                                value="{{ number_format((float) $itemAmounts[$index], 0) }}"
                                                placeholder="Enter Amount"
                                                {{ isset($po) && $po->submit_status === 'Submitted' ? 'readonly' : '' }}
                                                oninput="formatNumber(this)">
                                        </td>
                                        <td>
                                            @if (isset($po) && $po->submit_status !== 'Submitted')
                                                <button type="button" class="btn btn-danger"
                                                    onclick="removeItem(this)">Remove</button>
                                            @else
                                                <span class="text-muted">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>

                @if (isset($po) && $po->submit_status === 'Submitted')
                    <div class="add-item-btn disabled" style="opacity: 0.6;"
                        title="Cannot add items to a submitted order">
                        + Add Item
                    </div>
                @else
                    <div class="add-item-btn" onclick="addItem()">+ Add Item</div>
                @endif
            </div>


            <!-- Budget Summary -->
            <div class="form-section">
                <h4>Budget Summary</h4>
                <div class="row">
                    <div class="col-md-3">
                        <label for="totalBudget" class="form-label">Total Budget</label>
                        <input type="text" id="totalBudget" name="total_budget" class="form-control"
                            placeholder="Enter Total Budget"
                            value="{{ number_format($allocatedBudget->allocated_budget, 0) }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="utilization" class="form-label">Utilization</label>
                        <input type="text" id="utilization" name="utilization" class="form-control"
                            placeholder="Enter Utilization"
                            value="{{ number_format($allocatedBudget->total_dpm + $allocatedBudget->total_lpo, 0) }}"
                            readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="balance" class="form-label">Balance</label>
                        <input type="text" id="balance" name="balance" class="form-control"
                            placeholder="Enter Balance"
                            value="{{ number_format($allocatedBudget->allocated_budget - ($allocatedBudget->total_dpm + $allocatedBudget->total_lpo), 0) }}"
                            readonly>
                    </div>
                    <div class="col-sm-3">
                        <label for="bank_payment_id" class="form-label">Bank Receiving</label>
                        <select id="bank_payment_id" name="bank_payment_id" class="form-select">
                            <option value="">Select Payment Account</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}" @if (isset($po) && $po->bank_payment_id == $bank->id) selected @endif>
                                    {{ $bank->bank_name }} - {{ $bank->country }} - {{ $bank->region }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <input type="hidden" value="{{ $po->payment_method }}" name="payment_order_method" />

            <!-- Submit Button -->
            <div class="text-end mt-4">
                @if (isset($po) && $po->submit_status !== 'Submitted')
                    <button type="submit" class="btn btn-primary">
                        Submit Payment Order
                    </button>
                @else
                    <button type="submit" class="btn btn-primary" disabled>
                        Submitted
                    </button>
                @endif
            </div>
        </form>
    </div>

    <script>
        // Dynamically add items to the payment order
        function addItem() {
            const tableBody = document.getElementById('paymentItems');
            const row = document.createElement('tr');

            row.innerHTML = `
            <td><input type="text" name="item_description[]" class="form-control" placeholder="Enter Description" ></td>
            <td><input type="text" name="item_amount[]" class="form-control" placeholder="Enter Amount"  oninput="formatNumber(this)"></td>
      
            <td><button type="button" class="btn btn-danger" onclick="removeItem(this)">Remove</button></td>
        `;

            tableBody.appendChild(row);
        }

        function previewPDF(event) {
            const file = event.target.files[0];
            if (file && file.type === "application/pdf") {
                const fileURL = URL.createObjectURL(file); // Generate a URL for the uploaded file
                document.getElementById('pdfPreview').src = fileURL; // Set the src of the embed element
                document.getElementById('pdfPreviewContainer').classList.remove('d-none'); // Show the preview container
            } else {
                alert("Please upload a valid PDF file.");
                document.getElementById('chequeFile').value = ""; // Reset the input field if the file is invalid
                document.getElementById('pdfPreviewContainer').classList.add('d-none'); // Hide the preview container
            }
        }


        // Remove an item from the table
        function removeItem(button) {
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
