<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Approval Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add any custom styles here */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            /* Full-width table */
            border: 1px solid #dee2e6;
            /* Border around table */
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            /* Border around table cells */
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
            /* Light gray for odd rows */
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
            /* White for even rows */
        }

        .financial-details th,
        .financial-details td {
            text-align: right;
        }

        .financial-details th {
            background-color: #f2f2f2;
        }

        .highlight {
            background-color: yellow;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Cash Flow Report</h1>
            <h4>XAD Technology</h4>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Reference No.</td>
                            <td>{{$budget->reference_code}}</td>
                        </tr>
                        <tr>
                            <td>Business Unit</td>
                            <td>{{$units->source}}</td>
                        </tr>
                        <tr>
                            <td>Project</td>
                            <td>{{$project->name}}</td>
                        </tr>
                        <tr>
                            <td>Client</td>
                            <td>{{$clients->clientname}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Project Manager</td>
                            <td>{{$user->first_name}}</td>
                        </tr>
                        <tr>
                            <td>Total DPM</td>
                            <td id="total_dpm">{{number_format($dpm) ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                            <td>Total LPO</td>
                            <td id="total_lpo">{{number_format($lpo) ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                            <td>Total Budget Allocated</td>
                            <td id="total_lpo">{{number_format($allocatedBudget) ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                            <td>Remaining Budget</td>
                            <td id="total_lpo">{{number_format($remainingBudget) ?? 'N/A'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <h4>Cash Flow Report</h4>
        <div class="card mt-4">
            <div class="table-responsive text-nowrap limited-scroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Cash Inflow</th>
                            <th>Cash Outflow</th>
                            <th>Committed Budget</th>
                            <th>Balance</th>
                            <th>Total DPM</th>
                            <th>Total LPO</th>
                            <th>Total Budget Allocated</th>
                        </tr>
                    </thead>
                    <tbody id="cashflow-table-body" class="table-border-bottom-0">
                        @foreach ($cashFlows as $index => $cashFlow)
                            <!-- Add $index to get the current iteration -->
                            <tr>
                                @php
                                    $dpm = $allocatedBudgets->firstWhere(
                                        'budget_project_id',
                                        $cashFlow->budget_project_id,
                                    );
                                @endphp
                                <td>{{ $cashFlow->date }}</td>
                                <td>{{ $cashFlow->description }}</td>
                                <td>{{ $cashFlow->category }}</td>
                                <td class="{{ $index >= 6 && $cashFlow->cash_inflow > 0 ? 'text-primary' : '' }}">
                                    {{ number_format($cashFlow->cash_inflow, 0) }}
                                </td>
                                <td class="{{ $index >= 6 && $cashFlow->cash_outflow > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($cashFlow->cash_outflow, 0) }}
                                </td>
                                <td>{{ number_format($cashFlow->committed_budget, 0) }}</td>
                                <td>{{ number_format($cashFlow->balance, 0) }}</td>
                                <td>{{ number_format($dpm->total_dpm, 0) }}</td>
                                <td>{{ number_format($dpm->total_lpo, 0) }}</td>
                               
                                <td>{{ number_format($allocatedBudgets->allocated_budget) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
