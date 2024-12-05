@extends('layouts/contentNavbarLayout')

@section('title', 'Allocate Budget')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<style>
    .icon-box {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80px;
        width: 80px;
        background-color: #f5f5f5;
        border-radius: 50%;
    }

    .card-body {
        padding: 1.5rem;
        height: 180px;
        min-height: 180px;
    }

    .card {}
</style>

@section('content')
    <div class="container mt-5">
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
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        <h2>Approved Budget - {{ $approvedBudget->reference_code }}</h2>


        <div class="row">
            <!-- Salary Cost Card -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-briefcase fa-3x text-primary"></i>
                        </div>
                        <span class="fw-semibold d-block mb-1">Salary Cost</span>
                        <h3 class="card-title mb-2">{{ number_format($approvedBudget->total_salary) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Facility Cost Card -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-building fa-3x text-success"></i>
                        </div>
                        <span>Facility Cost</span>
                        <h3 class="card-title text-nowrap mb-1">{{ number_format($approvedBudget->total_facility_cost) }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Material Cost Card -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-toolbox fa-3x text-warning"></i>
                        </div>
                        <span>Material Cost</span>
                        <h3 class="card-title mb-2">{{ number_format($approvedBudget->total_material_cost) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Cost Overhead Card -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-cogs fa-3x text-info"></i>
                        </div>
                        <span>Cost Overhead</span>
                        <h3 class="card-title text-nowrap mb-1">{{ number_format($approvedBudget->total_cost_overhead) }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Financial Cost Card -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-dollar-sign fa-3x text-danger"></i>
                        </div>
                        <span>Financial Cost</span>
                        <h3 class="card-title mb-2">{{ number_format($approvedBudget->total_financial_cost) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Capital Expense Card -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-chart-pie fa-3x text-secondary"></i>
                        </div>
                        <span>Capital Expense</span>
                        <h3 class="card-title text-nowrap mb-1">
                            {{ number_format($approvedBudget->total_capital_expenditure) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Section -->
        <div class="row">
            <!-- Net Profit Before Tax -->
            <div class="col-lg-6 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-chart-line fa-3x text-success"></i>
                        </div>
                        <span>Net Profit Before Tax</span>
                        <h3 class="card-title text-nowrap mb-1">
                            {{ number_format($approvedBudget->expected_net_profit_before_tax) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Net Profit After Tax -->
            <div class="col-lg-6 col-md-6 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-box mb-2">
                            <i class="fas fa-coins fa-3x text-gold"></i>
                        </div>
                        <span>Net Profit After Tax</span>
                        <h3 class="card-title text-nowrap mb-1">
                            {{ number_format($approvedBudget->expected_net_profit_after_tax) }}</h3>
                    </div>
                </div>
            </div>
        </div>




        <!-- Budget Allocation Form -->
        <form method="POST" action="{{ route('budget.allocateBudgetByFinance') }}">
            @csrf
            <div class="row">

                <!-- Salary Card -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Salary
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Approved Budget: {{ number_format($approvedBudget->total_salary) }}</h6>
                            <h6 class="card-title">Allocated Fund:
                                {{ number_format($allocatedBudget->total_salary ?? 0, 0) }}</h6>
                            @if (isset($allocatedBudget->total_salary) && isset($approvedBudget->total_salary))
                                <button type="button" class="btn btn-primary open-modal" data-toggle="modal"
                                    data-target="#salaryModal"
                                    data-budget-project-id="{{ $approvedBudget->budget_project_id }}">
                                    More Fund
                                </button>
                            @else
                                <div class="form-group">
                                    <label for="salary_allocation_display">Allocate Budget for Salary</label>
                                    <input type="text" class="form-control" id="salary_allocation_display"
                                        name="salary_allocation_display" placeholder="Enter amount"
                                        value="{{ number_format($allocatedBudget->total_salary ?? 0, 0) }}"
                                        oninput="formatNumber(this, 'salary_allocation_hidden')" required>
                                    <input type="hidden" id="salary_allocation_hidden" name="salary_allocation"
                                        value="{{ $allocatedBudget->total_salary ?? 0 }}">
                                    <input type="hidden" name="approved_salary_allocation"
                                        value="{{ $approvedBudget->total_salary }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Facility Cost Card -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Facility Cost
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Approved Budget:
                                {{ number_format($approvedBudget->total_facility_cost) }}</h6>
                            <h6 class="card-title">Allocated Fund:
                                {{ number_format($allocatedBudget->total_facility_cost ?? 0, 0) }}</h6>

                            @if (isset($allocatedBudget->total_facility_cost) && isset($approvedBudget->total_facility_cost))
                                <button type="button" class="btn btn-primary open-modal" data-toggle="modal"
                                    data-target="#facilityModal"
                                    data-budget-project-id="{{ $approvedBudget->budget_project_id }}">More Fund</button>
                            @else
                                <div class="form-group">
                                    <label for="facility_allocation_display">Allocate Budget for Facility</label>
                                    <input type="text" class="form-control" id="facility_allocation_display"
                                        name="facility_allocation_display" placeholder="Enter amount"
                                        value="{{ number_format($allocatedBudget->total_facility_cost ?? 0, 0) }}"
                                        oninput="formatNumber(this, 'facility_allocation_hidden')" required>
                                    <input type="hidden" id="facility_allocation_hidden" name="facility_allocation"
                                        value="{{ $allocatedBudget->total_facility_cost ?? 0 }}">
                                    <input type="hidden" name="approved_facility_allocation"
                                        value="{{ $approvedBudget->total_facility_cost }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Material Cost Card -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Material Cost
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Approved Budget:
                                {{ number_format($approvedBudget->total_material_cost) }}</h5>
                            <h6 class="card-title">Allocated Fund:
                                {{ number_format($allocatedBudget->total_material_cost ?? 0, 0) }}</h6>

                            @if (isset($allocatedBudget->total_material_cost) && isset($approvedBudget->total_material_cost))
                                <button type="button" class="btn btn-primary  open-modal" data-toggle="modal"
                                    data-target="#materialModal"
                                    data-budget-project-id="{{ $approvedBudget->budget_project_id }}">More Fund</button>
                            @else
                                <div class="form-group">
                                    <label for="material_allocation_display">Allocate Budget for Material</label>
                                    <input type="text" class="form-control" id="material_allocation_display"
                                        name="material_allocation_display" placeholder="Enter amount"
                                        value="{{ number_format($allocatedBudget->total_material_cost ?? 0, 0) }}"
                                        oninput="formatNumber(this, 'material_allocation_hidden')" required>
                                    <input type="hidden" id="material_allocation_hidden" name="material_allocation"
                                        value="{{ $allocatedBudget->total_material_cost ?? 0 }}">
                                    <input type="hidden" name="approved_material_allocation"
                                        value="{{ $approvedBudget->total_material_cost }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Overhead Cost Card -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Overhead Cost
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Approved Budget:
                                {{ number_format($approvedBudget->total_cost_overhead) }}</h5>
                            <h6 class="card-title">Allocated Fund:
                                {{ number_format($allocatedBudget->total_cost_overhead ?? 0, 0) }}</h6>

                            <div class="form-group">

                                @if (isset($allocatedBudget->total_cost_overhead) && isset($approvedBudget->total_cost_overhead))
                                    <!-- Show Button -->
                                    <button type="button" class="btn btn-primary  open-modal" data-toggle="modal"
                                        data-target="#overheadModal"
                                        data-budget-project-id="{{ $approvedBudget->budget_project_id }}">More
                                        Fund</button>
                                @else
                                    <label for="overhead_allocation_display">Allocate Budget for Overhead</label>

                                    <!-- Show Input if no allocation yet -->
                                    <input type="text" class="form-control" id="overhead_allocation_display"
                                        name="overhead_allocation_display" placeholder="Enter amount"
                                        value="{{ number_format($allocatedBudget->total_cost_overhead ?? 0, 0) }}"
                                        oninput="formatNumber(this, 'overhead_allocation_hidden')" required>
                                    <input type="hidden" id="overhead_allocation_hidden" name="overhead_allocation"
                                        value="{{ $allocatedBudget->total_cost_overhead ?? 0 }}">
                                    <input type="hidden" name="approved_overhead_allocation"
                                        value="{{ $approvedBudget->total_cost_overhead }}">
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Financial Cost Card -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Financial Cost
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Approved Budget:
                                {{ number_format($approvedBudget->total_financial_cost) }}</h6>
                            <h6 class="card-title">Approved Budget:
                                {{ number_format($allocatedBudget->total_financial_cost ?? 0) }}</h6>

                            @if (isset($allocatedBudget->total_financial_cost) && isset($approvedBudget->total_financial_cost))
                                <button type="button" class="btn btn-primary  open-modal" data-toggle="modal"
                                    data-target="#financialModal"
                                    data-budget-project-id="{{ $approvedBudget->budget_project_id }}">More Fund</button>
                            @else
                                <div class="form-group">
                                    <label for="financial_allocation_display">Allocate Budget for Financial</label>
                                    <input type="text" class="form-control" id="financial_allocation_display"
                                        name="financial_allocation_display" placeholder="Enter amount"
                                        value="{{ number_format($allocatedBudget->total_financial_cost ?? 0, 0) }}"
                                        oninput="formatNumber(this, 'financial_allocation_hidden')" required>
                                    <input type="hidden" id="financial_allocation_hidden" name="financial_allocation"
                                        value="{{ $allocatedBudget->total_financial_cost ?? 0 }}">
                                    <input type="hidden" name="approved_financial_allocation"
                                        value="{{ $approvedBudget->total_financial_cost }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Capital Expenditure Card -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Capital Expenditure
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Approved Budget:
                                {{ number_format($approvedBudget->total_capital_expenditure) }}</h6>
                            <h6 class="card-title">Approved Budget:
                                {{ number_format($allocatedBudget->total_capital_expenditure ?? 0) }}</h6>

                            @if (isset($allocatedBudget->total_financial_cost) && isset($approvedBudget->total_financial_cost))
                                <button type="button" class="btn btn-primary  open-modal" data-toggle="modal"
                                    data-target="#capexModal"
                                    data-budget-project-id="{{ $approvedBudget->budget_project_id }}">More Fund</button>
                            @else
                                <div class="form-group">
                                    <label for="capital_expenditure_allocation_display">Allocate Budget for Capital
                                        Expenditure</label>
                                    <input type="text" class="form-control"
                                        id="capital_expenditure_allocation_display"
                                        name="capital_expenditure_allocation_display" placeholder="Enter amount"
                                        value="{{ number_format($allocatedBudget->total_capital_expenditure ?? 0, 0) }}"
                                        oninput="formatNumber(this, 'capital_expenditure_allocation_hidden')" required>
                                    <input type="hidden" id="capital_expenditure_allocation_hidden"
                                        name="capital_expenditure_allocation"
                                        value="{{ $allocatedBudget->total_capital_expenditure ?? 0 }}">
                                    <input type="hidden" name="approved_capital_expenditure_allocation"
                                        value="{{ $approvedBudget->total_capital_expenditure }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Repeat similarly for Overhead Cost and Financial Cost -->


            <!-- Hidden Inputs and Submit Button -->
            <input type="hidden" name="project" value="{{ $budgetProject->id }}" required>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Allocate Budget</button>
                </div>
            </div>
        </form>

        <!-- Salary Modal -->

        <div class="modal fade" id="salaryModal" tabindex="-1" role="dialog" aria-labelledby="salaryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="salaryModalLabel">More Fund for Salary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cashflow.allocateFund') }}" method="POST">
                            @csrf
                            <input type="hidden" name="budget_project_id" id="budget_project_id"
                                value="{{ $approvedBudget->budget_project_id }}">

                            <div class="form-group">
                                <label for="salaryAmount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="salaryAmount"
                                    placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <label for="salaryReason">Reason</label>
                                <textarea name="reason" class="form-control" id="salaryReason" rows="3"
                                    placeholder="Reason for additional funds"></textarea>
                            </div>
                            <input type="hidden" name="fund_type" value="salary">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facility Cost Modal -->
        <div class="modal fade" id="facilityModal" tabindex="-1" role="dialog" aria-labelledby="facilityModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="facilityModalLabel">More Fund for Facility Cost</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cashflow.allocateFund') }}" method="POST">
                            @csrf
                            <input type="hidden" name="budget_project_id" id="budget_project_id"
                                value="{{ $approvedBudget->budget_project_id }}">

                            <div class="form-group">
                                <label for="facilityAmount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="facilityAmount"
                                    placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <label for="facilityReason">Reason</label>
                                <textarea name="reason" class="form-control" id="facilityReason" rows="3"
                                    placeholder="Reason for additional funds"></textarea>
                            </div>
                            <input type="hidden" name="fund_type" value="facility">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Material Cost Modal -->
        <div class="modal fade" id="materialModal" tabindex="-1" role="dialog" aria-labelledby="materialModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="materialModalLabel">More Fund for Material Cost</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cashflow.allocateFund') }}" method="POST">
                            @csrf
                            <input type="hidden" name="budget_project_id" id="budget_project_id"
                                value="{{ $approvedBudget->budget_project_id }}">

                            <div class="form-group">
                                <label for="materialAmount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="materialAmount"
                                    placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <label for="materialReason">Reason</label>
                                <textarea name="reason" class="form-control" id="materialReason" rows="3"
                                    placeholder="Reason for additional funds"></textarea>
                            </div>
                            <input type="hidden" name="fund_type" value="material">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overhead Cost Modal -->
        <div class="modal fade" id="overheadModal" tabindex="-1" role="dialog" aria-labelledby="overheadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="overheadModalLabel">More Fund for Overhead Cost</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cashflow.allocateFund') }}" method="POST">
                            @csrf
                            <input type="hidden" name="budget_project_id" id="budget_project_id"
                                value="{{ $approvedBudget->budget_project_id }}">

                            <div class="form-group">
                                <label for="overheadAmount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="overheadAmount"
                                    placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <label for="overheadReason">Reason</label>
                                <textarea name="reason" class="form-control" id="overheadReason" rows="3"
                                    placeholder="Reason for additional funds"></textarea>
                            </div>
                            <input type="hidden" name="fund_type" value="overhead">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Cost Modal -->
        <div class="modal fade" id="financialModal" tabindex="-1" role="dialog" aria-labelledby="financialModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="financialModalLabel">More Fund for Financial Cost</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cashflow.allocateFund') }}" method="POST">
                            @csrf
                            <input type="hidden" name="budget_project_id" id="budget_project_id"
                                value="{{ $approvedBudget->budget_project_id }}">

                            <div class="form-group">
                                <label for="financialAmount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="financialAmount"
                                    placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <label for="financialReason">Reason</label>
                                <textarea name="reason" class="form-control" id="financialReason" rows="3"
                                    placeholder="Reason for additional funds"></textarea>
                            </div>
                            <input type="hidden" name="fund_type" value="financial">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- CAPEX Modal -->
        <div class="modal fade" id="capexModal" tabindex="-1" role="dialog" aria-labelledby="capexModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="capexModalLabel">More Fund for CAPEX</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cashflow.allocateFund') }}" method="POST">
                            @csrf
                            <input type="hidden" name="budget_project_id" id="budget_project_id"
                                value="{{ $approvedBudget->budget_project_id }}">

                            <div class="form-group">
                                <label for="capexAmount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="capexAmount"
                                    placeholder="Enter amount">
                            </div>
                            <div class="form-group">
                                <label for="capexReason">Reason</label>
                                <textarea name="reason" class="form-control" id="capexReason" rows="3"
                                    placeholder="Reason for additional funds"></textarea>
                            </div>
                            <input type="hidden" name="fund_type" value="capital expenditure">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#salaryModal, #facilityModal, #materialModal, #overheadModal, #financialModal, #capexModal').on(
                    'show.bs.modal',
                    function(event) {
                        alert("Modal is about to show"); // Alert to check if the function runs
                        var button = $(event.relatedTarget); // Button that triggered the modal
                        var budgetId = button.data('budget-id'); // Extract info from data-* attributes
                        var modal = $(this);
                        modal.find('.modal-body #budget_project_id').val(
                            budgetId); // Set the value of the hidden input
                    });
            });
        </script>


        <script>
            function formatNumber(input, hiddenFieldId) {
                // Remove non-digit characters (except for decimal point)
                let value = input.value.replace(/[^0-9.]/g, '');

                if (value) {
                    let parts = value.split('.');
                    let integerPart = parseInt(parts[0]).toLocaleString('en-US');
                    let formattedValue = integerPart;

                    if (parts[1] !== undefined) {
                        formattedValue += '.' + parts[1].slice(0, 2); // Allow up to 2 decimal places
                    }

                    input.value = formattedValue;
                    document.getElementById(hiddenFieldId).value = value;
                } else {
                    input.value = '';
                    document.getElementById(hiddenFieldId).value = '';
                }
            }
        </script>

    @endsection
