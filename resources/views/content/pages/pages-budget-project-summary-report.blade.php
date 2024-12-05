@extends('layouts/contentNavbarLayout')

@section('title', 'Project Summary Report')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Summary Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Playfair Display', serif;
            /* Professional serif font */
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }



        .status-dropdown {
            width: 120px;
            /* Increased width for more space */
            padding: 10px 15px;
            /* Increased padding for more room inside */
            font-size: 12px;
            /* Larger font for better readability */
            border-radius: 6px;
            border: 1px solid #0067aa;
            /* Border color */
            background-color: #f9f9f9;
            /* Light background */
            color: #333;
            /* Text color */
            transition: all 0.3s ease;
        }

        .status-dropdown:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 103, 170, 0.5);
            /* Focus effect */
        }

        .section-title {
            display: flex;
            align-items: center;
            color: black;
            font-weight: bolder;
        }

        .section-title .table-header-button {
            margin-left: 10px;
        }

        .status-dropdown {
            position: relative;
            display: inline-block;
        }




        .table-header-button {
            display: inline-block;
            margin-left: 10px;
            padding: 5px 10px;
            font-size: 0.9em;
            color: #fff;
            background-color: #0067aa;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 5rem;
            font-size: 0.8rem;
        }

        .table-header-button:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: 1px solid black;
        }

        h4 {
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid black;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .col-6 {
            width: 50%;
            padding: 10px;
        }

        .col-12 {
            width: 100%;
            padding: 10px;
        }

        .section-title {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .bordered {
            border: 1px solid black;
            padding: 5px;
            border-radius: 5px;
        }

        .overview-box {
            border: 1px solid #000;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #0067aa;
            color: white;
        }

        .budget-box {
            text-align: center;
            border: 2px solid #000;
            padding: 15px;
            background-color: #f1f9ff;
            border-radius: 5px;
            font-size: 1.5em;
        }

        .team-box {
            border: 1px solid #000;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .team-member {
            margin-bottom: 10px;
        }

        .team-member strong {
            display: inline-block;
            width: 150px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .footer ul {
            list-style: none;
            padding: 0;
        }

        .footer li {
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .col-6 {
                width: 100%;
            }
        }

        .flex-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .flex-container>div {
            flex: 1;
            margin: 5px;
        }

        .border-box {
            border: 1px solid black;
            padding: 10px;
            border-radius: 5px;
        }

        .dropdown {
            margin-bottom: 10px;
        }

        .limited-scroll {
            overflow-x: auto;
        }

        .border-box {
            border: 1px solid black;
            padding: 10px;
            border-radius: 5px;
        }

        .bordered {
            border: 1px solid black;
            padding: 5px;
            border-radius: 5px;
        }

        .overview-box p {
            margin: 0;
        }

        .overview-box span {
            font-weight: bold;
        }

        /* Modal Height */
        .modal-dialog {
            max-height: 550px;
            /* Set maximum height for the modal */
            height: 550px;
            display: flex;
            align-items: center;
        }

        .modal-content {
            height: 100%;
        }

        /* Custom Scrollbar Styles */
        .modal-body {
            /* Custom scrollbar for Webkit browsers (Chrome, Safari) */
            overflow-x: auto;
            scrollbar-width: thin;
            /* For Firefox */
            scrollbar-color: #0067aa #e0e0e0;
            /* For Firefox */
        }

        .modal-body::-webkit-scrollbar {
            width: 8px;
            /* Width of the scrollbar */
        }

        .modal-body::-webkit-scrollbar-thumb {
            background-color: #0067aa;
            /* Color of the scrollbar thumb */
            border-radius: 4px;
            /* Optional: Rounded corners for the scrollbar thumb */
        }

        .modal-body::-webkit-scrollbar-track {
            background-color: #e0e0e0;
            /* Color of the scrollbar track */
        }
    </style>
</head>

<body>

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
        <h4>{{ @$projects->name }} PROJECT SUMMARY REPORT</h4>

        <div class="flex-container">
            <div>
                <div class="section-title">Project Title: </div>
                <p class="border-box">{{ @$projects->name }}</p>
                <div class="section-title">Project Overview:</div>
                <div class="overview-box">
                    The project aimed to improve the overall customer experience by redesigning and enhancing the
                    functionality of our mobile application. The focus was on modernizing the user interface, optimizing
                    performance, and incorporating new features to meet evolving user expectations.
                </div>
            </div>

            <div>
                <div class="section-title">Project Duration:</div>
                <div class="border-box">Start Date: <strong>{{ @$budget->start_date }}</strong></div>
                <div class="border-box mt-2">End Date: <strong>{{ @$budget->end_date }}</strong></div>

                <div class="section-title mt-2">Project Team:</div>
                <div class="team-box">
                    <div class="team-member"><strong>Client</strong>{{ @$clients->clientname }}</div>
                    <div class="team-member"><strong>Business Unit</strong> {{ @$units->source }}</div>
                    <div class="team-member"><strong>Project Name</strong>{{ @$projects->name }}</div>
                    <div class="team-member"><strong>Reference Code</strong>{{ @$budget->reference_code }}</div>
                    <div class="team-member"><strong>Approval Status</strong><span
                            style="color:green; font-weight:bold">{{ @$budget->approval_status }}</span></div>
                    <div class="team-member">
                        <strong>Budget Summary</strong>
                        {{-- @if ($budget->approval_status == 'approve') --}}
                        <a href="{{ route('download.budgetSummary', ['POID' => $budget->id]) }}" target="_blank"
                            class="btn btn-sm" style="background-color:#1a73e8; color:white">
                            <i class="fas fa-print"></i> Download PDF
                        </a>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="section-title" style="color:red">Direct Cost</div>

        <!-- Salary Costs -->
        <div class="dropdown">
            <div class="section-title">
                Salary
                <button class="table-header-button">{{ number_format($totalSalary) }}</button>
            </div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>PROJECT</th>
                            <th>PO</th>
                            <th>EXPENSE</th>
                            <th>DESCRIPTION</th>
                            <th>COST PER MONTH</th>
                            <th>NO OF PERSON</th>
                            <th>MONTHS</th>
                            <th>AVERAGE COST</th>
                            <th>TOTAL COST</th>
                            <th>STATUS</th>
                            <th>VISA STATUS</th>
                            <th>%</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budget->salaries as $salary)
                        <tr>
                            <td>{{ $salary->type ?? 'no entry' }}</td>
                            <td>

                                @php
                                $project = $allProjects->where('id', $salary->project)->first();
                                @endphp
                                {{ $project->name }}
                            </td>
                            <td>{{ $salary->po ?? 'no entry' }}</td>
                            <td>{{ $salary->expenses ?? 'no entry' }}</td>
                            <td>{{ $salary->description ?? 'no entry' }}</td>
                            <td>{{ number_format($salary->cost_per_month) ?? 'no entry' }}</td>
                            <td>{{ $salary->no_of_staff ?? 'no entry' }}</td>
                            <td>{{ $salary->no_of_months ?? 'no entry' }}</td>
                            <td>{{ number_format($salary->average_cost) ?? 'no entry' }}</td>
                            <td>{{ number_format($salary->total_cost) ?? 'no entry' }}</td>
                            <td>{{ $salary->status ?? 'no entry' }}</td>
                            <td>{{ $salary->visa_status ?? 'no entry' }}</td>
                            <td>{{ $salary->percentage_cost ?? 'no entry' }}</td>
                            <td>
                                <!-- Delete Form -->
                                <form method="POST" action="{{ route('delete.salary', $salary->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name='isajax' value="false">
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>

                                <!-- Update Button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateModal-{{ $salary->id }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Facility Costs -->
        <div class="dropdown">
            <div class="section-title">
                Facility Cost
                <button class="table-header-button">{{ number_format($totalFacilityCost) }}</button>
            </div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>PROJECT</th>
                            <th>PO</th>
                            <th>EXPENSE</th>
                            <th>DESCRIPTION</th>
                            <th>COST PER MONTH</th>
                            <th>NO OF PERSON</th>
                            <th>MONTHS</th>
                            <th>AVERAGE COST</th>
                            <th>TOTAL COST</th>
                            <th>STATUS</th>
                            <th>%</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budget->facilityCosts as $facility)
                        <tr>
                            <td>{{ $facility->type ?? 'no entry' }}</td>
                            <td>
                                @php
                                $project = $allProjects->where('id', $facility->project)->first();
                                @endphp
                                {{ $project->name }}
                            </td>
                            <td>{{ $facility->po ?? 'no entry' }}</td>
                            <td>{{ $facility->expenses ?? 'no entry' }}</td>
                            <td>{{ $facility->description ?? 'no entry' }}</td>
                            <td>{{ $facility->cost_per_month ?? 'no entry' }}</td>
                            <td>{{ $facility->no_of_staff ?? 'no entry' }}</td>
                            <td>{{ $facility->no_of_months ?? 'no entry' }}</td>
                            <td>{{ $facility->average_cost ?? 'no entry' }}</td>
                            <td>{{ $facility->total_cost ?? 'no entry' }}</td>
                            <td>{{ $facility->status ?? 'no entry' }}</td>
                            <td>{{ $facility->percentage_cost ?? 'no entry' }}</td>
                            <td>
                                <!-- Delete Form -->
                                <form method="POST" action="{{ route('delete.facility', $facility->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <!-- Update Button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateModal-{{ $facility->id }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Material Costs -->
        <div class="dropdown">
            <div class="section-title">
                Material Cost
                <button class="table-header-button">{{ number_format($totalMaterialCost) }}</button>
            </div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>PROJECT</th>
                            <th>PO</th>
                            <th>EXPENSE HEAD</th>
                            <th>DESCRIPTION</th>
                            <th>QUANTITY</th>
                            <th>UNIT</th>
                            <th>UNIT COST</th>
                            <th>TOTAL COST</th>
                            <th>AVERAGE COST</th>
                            <th>STATUS</th>
                            <th>%</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budget->materialCosts as $material)
                        <tr>
                            <td>{{ $material->type ?? 'no entry' }}</td>
                            <td>
                                @php
                                $project = $allProjects->where('id', $material->project)->first();
                                @endphp
                                {{ $project->name }}
                            </td>
                            <td>{{ $material->po ?? 'no entry' }}</td>
                            <td>{{ $material->expenses ?? 'no entry' }}</td>
                            <td>{{ $material->description ?? 'no entry' }}</td>
                            <td>{{ number_format($material->quantity) ?? 'no entry' }}</td>
                            <td>{{ $material->unit ?? 'no entry' }}</td>
                            <td>{{ $material->unit_cost ?? 'no entry' }}</td>
                            <td>{{ $material->total_cost ?? 'no entry' }}</td>
                            <td>{{ $material->average_cost ?? 'no entry' }}</td>
                            <td>{{ $material->status ?? 'no entry' }}</td>
                            <td>{{ isset($material->percentage_cost) ? $material->percentage_cost : 'no entry' }}
                            <td>
                                <!-- Delete Form -->
                                <form method="POST" action="{{ route('delete.material', $material->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <!-- Update Button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateMaterialModal-{{ $material->id }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <div class="section-title" style="color:red">In Direct Cost</div>

        <!-- Overhead Costs -->
        <div class="dropdown">
            <div class="section-title">
                Overhead Cost
                <button class="table-header-button">{{ number_format($totalCostOverhead) }}</button>
            </div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>PROJECT</th>
                            <th>PO</th>
                            <th>EXPENSE</th>
                            <th>AMOUNT</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budget->costOverheads as $costOverhead)
                        <tr>
                            <td>{{ $costOverhead->type }}</td>
                            <td>
                                @php
                                $project = $allProjects->where('id', $costOverhead->project)->first();
                                @endphp
                                {{ @$project->name }}
                            </td>
                            <td>{{ $costOverhead->po }}</td>
                            <td>{{ $costOverhead->expenses }}</td>
                            <td>{{ $costOverhead->amount }}</td>
                            <td>
                                <!-- Delete Form -->
                                <form method="POST" action="{{ route('delete.costOverhead', $costOverhead->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <!-- Update Button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateOverHeadCostModal-{{ $costOverhead->id }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Financial Costs -->
        <div class="dropdown">
            <div class="section-title">
                Financial Cost
                <button class="table-header-button">{{ number_format($totalFinancialCost) }}</button>
            </div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>PROJECT</th>
                            <th>PO</th>
                            <th>EXPENSE</th>
                            <th>AMOUNT</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budget->financialCosts as $financialCost)
                        <tr>
                            <td>{{ $financialCost->type }}</td>
                            <td>
                                @php
                                $project = $allProjects->where('id', $financialCost->project)->first();
                                @endphp
                                {{ $project->name }}
                            </td>
                            <td>{{ $financialCost->po }}</td>
                            <td>{{ $financialCost->expenses }}</td>
                            <td>{{ $financialCost->total_cost }}</td>
                            <td>
                                <!-- Delete Form -->
                                <form method="POST"
                                    action="{{ route('delete.financialCost', $financialCost->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <!-- Update Button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateModal-{{ $financialCost->id }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Capital Expenditure -->
        <div class="section-title" style="color:red">Capital Expenditure</div>

        <div class="dropdown">
            <div class="section-title">
                Capital Expenditure
                <button class="table-header-button">{{ number_format($totalCapitalExpenditure) }}</button>
            </div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>PROJECT</th>
                            <th>PO</th>
                            <th>EXPENSE</th>
                            <th>DESCRIPTION</th>
                            <th> QUANTITY</th>
                            <th>COST</th>
                            <th>TOTAL COST</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th> <!-- Add an ACTIONS column -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budget->capitalExpenditures as $capital)
                        <tr>
                            <td>{{ $capital->type ?? 'no entry' }}</td>
                            <td>
                                @php
                                $project = $allProjects->where('id', $capital->project)->first();
                                @endphp
                                {{ $project->name }}
                            </td>
                            <td>{{ $capital->po ?? 'no entry' }}</td>
                            <td>{{ $capital->expenses ?? 'no entry' }}</td>
                            <td>{{ $capital->description ?? 'no entry' }}</td>
                            <td>{{ $capital->total_number ?? 'no entry' }}</td>
                            <td>{{ $capital->cost ?? 'no entry' }}</td>
                            <td>{{ $capital->total_cost ?? 'no entry' }}</td>
                            <td>{{ $capital->status ?? 'no entry' }}</td>
                            <td>
                                <!-- Delete Form -->
                                <form method="POST"
                                    action="{{ route('delete.capitalExpenditure', $capital->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <!-- Update Button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateCapitalModal-{{ $capital->id }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section-title" style="color:red">Revenue & Profit</div>

        <!-- Revenue Table -->
        <div class="dropdown">
            <div class="section-title">
                Revenue
                <button class="table-header-button">{{ number_format($totalNetProfitAfterTax) }}</button>
            </div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>PROJECT</th>
                            <th>DESCRIPTION</th>
                            <th>AMOUNT</th>
                            <th>TOTAL PROFIT</th>
                            <th>NET PROFIT BEFORE TAX</th>
                            <th>TAX</th>
                            <th>NET PROFIT AFTER TAX</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    @php
                    $total_profit_after_tax = 0;
                    @endphp
                    <tbody>
                        @foreach ($budget->revenuePlans as $revenuePlan)
                        <tr>
                            <td>{{ $revenuePlan->type }}</td>
                            <td>
                                @php
                                $project = $allProjects->where('id', $revenuePlan->project)->first();
                                @endphp
                                {{ $project->name }}
                            </td>
                            <td>{{ $revenuePlan->description }}</td>
                            <td>{{ $revenuePlan->amount }}</td>
                            <td>{{ $revenuePlan->total_profit }}</td>
                            <td>{{ $revenuePlan->net_profit_before_tax }}</td>
                            <td>{{ $revenuePlan->tax }}</td>
                            <td>{{ $revenuePlan->net_profit_after_tax }}</td>
                            <td>
                                <!-- Delete Form -->
                                <form method="POST"
                                    action="{{ route('delete.deleteRevenue', $revenuePlan->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <!-- Update Button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editRevenuePlan-{{ $revenuePlan->id }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Profit Table -->
        <div class="dropdown">
            <div class="section-title" style="color:red">Profit</div>

            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Profit Source</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Add rows here -->
                        <td>Net Profit After Tax</td>
                        <td>{{ number_format($totalNetProfitAfterTax) }} AED</td>
                    </tbody>
                </table>
            </div>
        </div>
        @php
        $totalOPEX = $totalDirectCost + $totalInDirectCost + $totalNetProfitBeforeTax;
        @endphp
        <!-- Cash Management Table -->
        <div class="dropdown">
            <div class="section-title" style="color:red">Cash Management</div>
            <div class="table-responsive text-nowrap limited-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>HEAD</th>
                            <th>AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Add rows here -->
                        <tr>
                            <td>1</td>
                            <td>Cash Requirement For CAPEX</td>
                            <td>{{ number_format($totalCapitalExpenditure) }} AED</td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>Cash Requirement For OPEX</td>
                            <td>{{ number_format($totalOPEX) }} AED</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total CAPEX, OPEX, and Total Cost -->
        <div class="section-title" style="color:red">Summary:</div>
        <div class="flex-container">
            <div class="bordered">
                <div class="section-title">Total CAPEX:</div>
                <p>{{ number_format($totalCapitalExpenditure) }} AED</p>
            </div>
            <div class="bordered">
                <div class="section-title">Total OPEX:</div>
                <p>{{ number_format($totalOPEX) }} AED</p>
            </div>
            <div class="bordered">
                <div class="section-title">Total Cost:</div>
                <p> {{ number_format($totalOPEX + $totalCapitalExpenditure) }} AED</p>
            </div>
        </div>


        <!-- Total CAPEX, OPEX, and Total Cost -->
        <div class="section-title mt-2" style="color:red">Budget Allocated</div>
        <div class="flex-container">
            <div class="bordered">
                <div class="section-title"></div>
                <p>{{ number_format($budget->total_budget_allocated) }} </p>
            </div>
        </div>


        <form action="{{ route('approve-status') }}" method="post">
            @csrf
            <div class="row gy-3">
                <div class="col-md-6">
                    <label for="status" class="form-label">Approval Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="" disabled selected>Choose Approval</option>
                        <option value="pending">Pending</option>
                        <option value="hold">Hold</option>
                        <option value="reject">Reject</option>
                        <option value="approve">Approve</option>
                    </select>
                    @error('status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @php
                $totalCost = $totalOPEX + $totalCapitalExpenditure;
                @endphp

                <input type="hidden" name="project_id" value="{{ $id }}">
                <input type="hidden" name="total_salary" value="{{ $totalSalary }}">
                <input type="hidden" name="total_facility_cost" value="{{ $totalFacilityCost }}">
                <input type="hidden" name="total_material_cost" value="{{ $totalMaterialCost }}">
                <input type="hidden" name="total_cost_overhead" value="{{ $totalCostOverhead }}">
                <input type="hidden" name="total_financial_cost" value="{{ $totalFinancialCost }}">
                <input type="hidden" name="total_capital_expenditure" value="{{ $totalCapitalExpenditure }}">
                <input type="hidden" name="total_cost" value="{{ $totalCost }}">
                <input type="hidden" name="expected_net_profit_after_tax"
                    value="{{ $totalNetProfitAfterTax }}">
                <input type="hidden" name="expected_net_profit_before_tax"
                    value="{{ $totalNetProfitBeforeTax }}">
                <input type="hidden" name="reference_code" value="{{ $budget->reference_code }}">
                <input type="hidden" name="client" value="{{ @$clients->id }}">
                <input type="hidden" name="source" value="{{ @$units->id }}">
                <input type="hidden" name="project" value="{{ @$projects->id }}">

                <div class="col-md-12 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary"
                        style="background-color:#0067aa; hover:#0067aa">Submit</button>
                </div>
            </div>
        </form>


        @if ($budget->approval_status === 'approve')
        <form action="{{ route('budget.allocate') }}" method="GET">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary"
                        style="background-color:#0067aa; border-color:#0067aa">Allocate Budget</button>
                    <input type="hidden" name="reference_code" value="{{ $budget->reference_code }}">
                </div>
            </div>
        </form>
        @endif

        <!-- Salary Update Modal -->
        <!-- Check if the salary object exists before rendering the modal -->
        @foreach ($salaries as $salary)
        <!-- Update Salary Modal -->
        <div class="modal fade" id="updateModal-{{ $salary->id }}" tabindex="-1"
            aria-labelledby="updateModalLabel-{{ $salary->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel-{{ $salary->id }}">Update Salary</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateSalaryForm-{{ $salary->id }}"
                            action="{{ url('/pages/update-budget-project-salary/' . $salary->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="Salary" {{ $salary->type == 'Salary' ? 'selected' : '' }}>
                                        Salary</option>
                                    <option value="Other" {{ $salary->type == 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>

                            <!-- <div class="mb-3">
                                            <label for="contract" class="form-label">Contract</label>
                                            <input type="text" class="form-control" id="contract" name="contract" value="{{ $salary->contract }}" required>
                                        </div> -->

                            <div class="mb-3">
                                <label for="project" class="form-label">Project</label>
                                <select class="form-select" id="project" name="project" required>
                                    @foreach ($allProjects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $salary->project == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="po" class="form-label">PO Type</label>
                                <select class="form-select" id="po" name="po" required>
                                    <option value="CAPEX" {{ $salary->po == 'CAPEX' ? 'selected' : '' }}>CAPEX
                                    </option>
                                    <option value="OPEX" {{ $salary->po == 'OPEX' ? 'selected' : '' }}>OPEX
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="expense" class="form-label">Expense Head</label>
                                <input type="text" class="form-control" id="expense" name="expenses"
                                    value="{{ $salary->expenses }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="cost_per_month" class="form-label">Cost Per Month</label>
                                <input type="number" class="form-control" id="cost_per_month"
                                    name="cost_per_month" value="{{ $salary->cost_per_month }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    value="{{ $salary->description }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" class="form-control" id="status" name="status"
                                    value="{{ $salary->status }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="noOfPerson" class="form-label">No Of Persons</label>
                                <input type="number" class="form-control" id="noOfPerson" name="no_of_staff"
                                    value="{{ $salary->no_of_staff }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="months" class="form-label">Months</label>
                                <input type="number" class="form-control" id="months" name="no_of_months"
                                    value="{{ $salary->no_of_months }}" required>
                            </div>
                            <input type="hidden" name="isajax" value="false">
                            <button type="submit" class="btn btn-primary">Update Salary</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Facility Update Modal -->
        @foreach ($facilities as $facility)
        <!-- Update Facilities Modal -->
        <div class="modal fade" id="updateModal-{{ $facility->id }}" tabindex="-1"
            aria-labelledby="updateModalLabel-{{ $facility->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel-{{ $facility->id }}">Update Facility Cost
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateFacilitiesForm-{{ $facility->id }}"
                            action="{{ url('/pages/update-budget-project-facility-cost/' . $facility->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="Facility Cost"
                                        {{ $facility->type == 'Facility Cost' ? 'selected' : '' }}>Facility Cost
                                    </option>
                                    <option value="Other" {{ $facility->type == 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                            <!-- <div class="mb-3">
                                            <label for="contract" class="form-label">Contract</label>
                                            <input type="text" class="form-control" id="contract" name="contract"
                                                value="{{ $facility->contract }}">
                                        </div> -->
                            <div class="mb-3">
                                <label for="project" class="form-label">Project</label>
                                <select class="form-select" id="project" name="project" required>
                                    @foreach ($allProjects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $project->id == $facility->project ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="po" class="form-label">PO</label>
                                <select class="form-select" id="po" name="po" required>
                                    <option value="CAPEX" {{ $facility->po == 'CAPEX' ? 'selected' : '' }}>CAPEX
                                    </option>
                                    <option value="OPEX" {{ $facility->po == 'OPEX' ? 'selected' : '' }}>OPEX
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expense" class="form-label">Expense Head</label>
                                <input type="text" class="form-control" id="expense" name="expense"
                                    value="{{ $facility->expenses }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="cost_per_month" class="form-label">Cost Per Month</label>
                                <input type="number" class="form-control" id="cost_per_month"
                                    name="cost_per_month" value="{{ $facility->cost_per_month }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    value="{{ $facility->description }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" class="form-control" id="status" name="status"
                                    value="{{ $facility->status }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="noOfPerson" class="form-label">No Of Person</label>
                                <input type="number" class="form-control" id="noOfPerson" name="no_of_staff"
                                    value="{{ $facility->no_of_staff }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="months" class="form-label">Months</label>
                                <input type="number" class="form-control" id="months" name="no_of_months"
                                    value="{{ $facility->no_of_months }}" required>
                            </div>
                            <input type="hidden" name="project_id" value="{{ $budget->id }}">
                            <button type="submit" class="btn btn-primary">Update Facilities Cost</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- MAterial Update Modal -->
        @foreach ($materials as $material)
        <!-- Update Material Modal -->
        <div class="modal fade" id="updateMaterialModal-{{ $material->id }}" tabindex="-1"
            aria-labelledby="updateMaterialModalLabel-{{ $material->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateMaterialModalLabel-{{ $material->id }}">Update
                            Material Cost</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateMaterialForm-{{ $material->id }}"
                            action="{{ url('/pages/update-budget-project-material/' . $material->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="Material"
                                        {{ $material->type == 'Material' ? 'selected' : '' }}>Material</option>
                                    <option value="Other" {{ $material->type == 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="project" class="form-label">Project</label>
                                <select class="form-select" id="project" name="project" required>
                                    @foreach ($allProjects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $project->id == $material->project ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="po" class="form-label">PO</label>
                                <select class="form-select" id="po" name="po" required>
                                    <option value="CAPEX" {{ $material->po == 'CAPEX' ? 'selected' : '' }}>CAPEX
                                    </option>
                                    <option value="OPEX" {{ $material->po == 'OPEX' ? 'selected' : '' }}>OPEX
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expense" class="form-label">Expense Head</label>
                                <input type="text" class="form-control" id="expense" name="expense"
                                    value="{{ $material->expenses }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                    step="any" value="{{ $material->quantity }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit</label>
                                <select class="form-select" id="unit" name="unit" required>
                                    <option value="meters" {{ $material->unit == 'meters' ? 'selected' : '' }}>
                                        Meters</option>
                                    <option value="feet" {{ $material->unit == 'feet' ? 'selected' : '' }}>Feet
                                    </option>
                                    <option value="rolls" {{ $material->unit == 'rolls' ? 'selected' : '' }}>
                                        Rolls</option>
                                    <option value="pieces" {{ $material->unit == 'pieces' ? 'selected' : '' }}>
                                        Pieces</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="unit_cost" class="form-label">Unit Cost</label>
                                <input type="number" class="form-control" id="unit_cost" name="unit_cost"
                                    step="any" value="{{ $material->unit_cost }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    value="{{ $material->description }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" class="form-control" id="status" name="status"
                                    value="{{ $material->status }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Material Cost</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Cost Overhead Modal -->
        @foreach ($overheads as $costOverhead)
        <!-- Cost Overhead Modal -->
        <div class="modal fade" id="updateOverHeadCostModal-{{ $costOverhead->id }}" tabindex="-1"
            aria-labelledby="updateOverHeadCostModalLabel-{{ $costOverhead->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateOverHeadCostModalLabel-{{ $costOverhead->id }}">
                            Update Cost Overhead
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateOverHeadCostForm-{{ $costOverhead->id }}"
                            action="{{ url('/pages/update-budget-project-overhead-cost/' . $costOverhead->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="type-{{ $costOverhead->id }}" class="form-label">Type</label>
                                <select class="form-select" id="type-{{ $costOverhead->id }}" name="type"
                                    required>
                                    <option value="overhead cost"
                                        {{ $costOverhead->type === 'overhead cost' ? 'selected' : '' }}>
                                        Overhead Cost
                                    </option>
                                    <option value="Other"
                                        {{ $costOverhead->type === 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="project-{{ $costOverhead->id }}" class="form-label">Project</label>
                                <select class="form-select" id="project-{{ $costOverhead->id }}" name="project"
                                    required>
                                    @foreach ($allProjects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $project->id == $costOverhead->project ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="po-{{ $costOverhead->id }}" class="form-label">PO</label>
                                <select class="form-select" id="po-{{ $costOverhead->id }}" name="po"
                                    required>
                                    <option value="CAPEX" {{ $costOverhead->po === 'CAPEX' ? 'selected' : '' }}>
                                        CAPEX</option>
                                    <option value="OPEX" {{ $costOverhead->po === 'OPEX' ? 'selected' : '' }}>
                                        OPEX</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expenses-{{ $costOverhead->id }}" class="form-label">Expense
                                    Head</label>
                                <input type="text" class="form-control"
                                    id="expenses-{{ $costOverhead->id }}" name="expenses"
                                    value="{{ $costOverhead->expenses }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="cost_per_month-{{ $costOverhead->id }}" class="form-label">
                                    Amount</label>
                                <input type="number" class="form-control"
                                    id="cost_per_month-{{ $costOverhead->id }}" name="amount" step="any"
                                    value="{{ $costOverhead->amount }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Cost Overhead</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Update Financial Cost Modal -->

        @foreach ($financials as $financialCost)
        <div class="modal fade" id="updateModal-{{ $financialCost->id }}" tabindex="-1"
            aria-labelledby="updateModalLabel-{{ $financialCost->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel-{{ $financialCost->id }}">Update Financial
                            Cost</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateFinancialCostForm-{{ $financialCost->id }}"
                            action="{{ url('/pages/update-budget-project-financial-cost/' . $financialCost->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="type-{{ $financialCost->id }}" class="form-label">Type</label>
                                <select class="form-select" id="type-{{ $financialCost->id }}" name="type"
                                    required>
                                    <option value="financial cost"
                                        {{ $financialCost->type === 'financial cost' ? 'selected' : '' }}>
                                        Financial Cost</option>
                                    <option value="Other"
                                        {{ $financialCost->type === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="project-{{ $financialCost->id }}" class="form-label">Project</label>
                                <select class="form-select" id="project-{{ $financialCost->id }}"
                                    name="project" required>
                                    @foreach ($allProjects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $project->id == $financialCost->project ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="po-{{ $financialCost->id }}" class="form-label">PO</label>
                                <select class="form-select" id="po-{{ $financialCost->id }}" name="po"
                                    required>
                                    <option value="CAPEX"
                                        {{ $financialCost->po === 'CAPEX' ? 'selected' : '' }}>CAPEX</option>
                                    <option value="OPEX" {{ $financialCost->po === 'OPEX' ? 'selected' : '' }}>
                                        OPEX</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expense-{{ $financialCost->id }}" class="form-label">Expense</label>
                                <input type="text" class="form-control"
                                    id="expense-{{ $financialCost->id }}" name="expenses"
                                    value="{{ $financialCost->expenses }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="months-{{ $financialCost->id }}"
                                    class="form-label">Percentage</label>
                                <input type="number" class="form-control" id="months-{{ $financialCost->id }}"
                                    name="amount" value="{{ $financialCost->percentage }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Financial Cost</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Capital Expenditure Modal -->
        @foreach ($capitalExpenditures as $capital)
        <div class="modal fade" id="updateCapitalModal-{{ $capital->id }}" tabindex="-1"
            aria-labelledby="updateCapitalModalLabel-{{ $capital->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateCapitalModalLabel-{{ $capital->id }}">Update Capital
                            Expenditure</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateCapitalExpenseForm-{{ $capital->id }}"
                            action="{{ url('/pages/update-budget-capital-expense/' . $capital->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="type-{{ $capital->id }}" class="form-label">Type</label>
                                <select class="form-select" id="type-{{ $capital->id }}" name="type"
                                    required>
                                    <option value="Capital Expenditure"
                                        {{ $capital->type == 'Capital Expenditure' ? 'selected' : '' }}>Capital
                                        Expenditure</option>
                                    <option value="Other" {{ $capital->type == 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="project-{{ $capital->id }}" class="form-label">Project</label>
                                <select class="form-select" id="project-{{ $capital->id }}" name="project"
                                    required>
                                    @foreach ($allProjects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $project->id == $capital->project ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="po-{{ $capital->id }}" class="form-label">PO</label>
                                <select class="form-select" id="po-{{ $capital->id }}" name="po"
                                    required>
                                    <option value="CAPEX" {{ $capital->po == 'CAPEX' ? 'selected' : '' }}>CAPEX
                                    </option>
                                    <option value="OPEX" {{ $capital->po == 'OPEX' ? 'selected' : '' }}>OPEX
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expense-{{ $capital->id }}" class="form-label">Expense</label>
                                <input type="text" class="form-control" id="expense-{{ $capital->id }}"
                                    name="expenses" value="{{ $capital->expenses }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description-{{ $capital->id }}"
                                    class="form-label">Description</label>
                                <input type="text" class="form-control" id="description-{{ $capital->id }}"
                                    name="description" value="{{ $capital->description }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="status-{{ $capital->id }}" class="form-label">Status</label>
                                <input type="text" class="form-control" id="status-{{ $capital->id }}"
                                    name="status" value="{{ $capital->status }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="cost_per_month-{{ $capital->id }}"
                                    class="form-label">QUANTITY</label>
                                <input type="number" class="form-control"
                                    id="cost_per_month-{{ $capital->id }}" name="total_number" step="any"
                                    value="{{ $capital->total_number }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="noOfPerson-{{ $capital->id }}" class="form-label">COST</label>
                                <input type="number" class="form-control" id="noOfPerson-{{ $capital->id }}"
                                    name="cost" step="any" value="{{ $capital->cost }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update CAPEX</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Revenue Plan Modal -->
        @foreach ($revenuePlans as $revenuePlan)
        <div class="modal fade" id="editRevenuePlan-{{ $revenuePlan->id }}" tabindex="-1"
            aria-labelledby="editRevenuePlanLabel-{{ $revenuePlan->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRevenuePlanLabel-{{ $revenuePlan->id }}">Edit Revenue
                            Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateRevenueForm-{{ $revenuePlan->id }}"
                            action="{{ url('/pages/update-budget-project-revenue/' . $revenuePlan->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="type-{{ $revenuePlan->id }}" class="form-label">Type</label>
                                <select class="form-select" id="type-{{ $revenuePlan->id }}" name="type"
                                    required>
                                    <option value="Revenue"
                                        {{ $revenuePlan->type == 'Revenue' ? 'selected' : '' }}>Revenue</option>
                                    <option value="Other" {{ $revenuePlan->type == 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="project-{{ $revenuePlan->id }}" class="form-label">Project</label>
                                <select class="form-select" id="project-{{ $revenuePlan->id }}" name="project">
                                    @foreach ($allProjects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $project->id == $revenuePlan->project ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount-{{ $revenuePlan->id }}" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount-{{ $revenuePlan->id }}"
                                    name="amount" value="{{ $revenuePlan->amount }}" required
                                    placeholder="Enter amount">
                            </div>
                            <div class="mb-3">
                                <label for="description-{{ $revenuePlan->id }}"
                                    class="form-label">Description</label>
                                <input type="text" class="form-control"
                                    id="description-{{ $revenuePlan->id }}" name="description"
                                    value="{{ $revenuePlan->description }}" required
                                    placeholder="Enter description">
                            </div>
                            <input type="hidden" name="project_id" value="{{ $budget->id }}">

                            <button type="submit" class="btn btn-primary">Update Revenue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach


</body>

</html>

@endsection