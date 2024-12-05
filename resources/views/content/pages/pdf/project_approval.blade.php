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
            <h1>Budget Summary</h1>
            <h4>XAD Technology</h4>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Reference No.</td>
                            <td>{{ $budget->reference_code }}</td>
                        </tr>
                        <tr>
                            <td>Business Unit</td>
                            <td>{{ $units->source }}</td>
                        </tr>
                        <tr>
                            <td>Project</td>
                            <td>{{ $project->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Client</td>
                            <td>{{ $clients->clientname }}</td>
                        </tr>
                        <tr>
                            <td>Region / City</td>
                            <td>{{ $budget->region }}</td>
                        </tr>
                        <tr>
                            <td>Starting Date</td>
                            <td>{{ \Carbon\Carbon::parse($budget->start_date)->format('d m Y') }}</td>
                        </tr>
                        <tr>
                            <td>Completion Month</td>
                            <td>{{ \Carbon\Carbon::parse($budget->end_date)->format('d m Y') }}</td>
                        </tr>
                        <tr>
                            <td>Project Manager</td>
                            <td>{{ $user->first_name . '  ' . $user->last_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <h4> Financial Details</h4>
        <table class="table financial-details">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Revenue</td>
                    <td>{{ number_format($amounts['total_revenue_plans']) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Direct Cost</td>
                    <td>{{ number_format($totalDirectCost) }}</td>
                    <td></td>
                </tr>
                <tr>
                    @php
                        $grossProfit = $amounts['total_revenue_plans'] - $totalDirectCost;
                    @endphp
                    <td>3</td>
                    <td>Gross Profit</td>
                    <td>{{ number_format($grossProfit) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Indirect Costs</td>
                    <td>{{ number_format($totalInDirectCost) }}</td>
                    <td></td>
                </tr>
                <tr>
                    @php
                        $npbt = $grossProfit - $totalInDirectCost;
                    @endphp
                    <td>5</td>
                    <td>NPBT</td>
                    <td>{{ number_format($npbt) }}</td>
                    <td></td>
                </tr>
                <tr>
                    @php
                        $tax = $npbt * 0.009;
                    @endphp
                    <td>6</td>
                    <td>TAX (9%)</td>
                    <td class="highlight">{{ number_format($tax) }}</td>
                    <td></td>
                </tr>
                <tr>
                    @php
                        $npat = $npbt - $tax;
                    @endphp
                    <td>7</td>
                    <td>NPAT</td>
                    <td class="highlight">{{ number_format($npat) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Profit (%)</td>
                    <td class="highlight">{{ number_format($npat/$amounts['total_revenue_plans'] *100) }} %</td>
                    <td></td>
                </tr>
                <tr>
                    @php
                        $initialInvestment = $totalDirectCost + $totalInDirectCost + $totalCapExp;
                    @endphp
                    <td>9</td>
                    <td>Initial Investment</td>
                    <td class="highlight">{{ number_format($initialInvestment) }}</td>
                    <td></td>
                </tr>
                <tr>
                    @php
                        $ROI = (($npat/$initialInvestment) * 100) / $months; 
                    @endphp
                    
                    <td>10</td>
                    <td>ROI Annualized</td>
                    <td>{{number_format($ROI)}} %</td>
                    <td></td>
                </tr>
            

            </tbody>
        </table>

        <h4>Signatures</h4>
        <div>

            <p>Project Manager:  {{ ucfirst(strtolower($user->first_name)) . ' ' . ucfirst(strtolower($user->last_name)) }}</p>
            <p>Finance Manager: ______________________</p>
        </div>
        <p>Date: __________________________</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
