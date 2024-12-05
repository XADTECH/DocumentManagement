<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<div class="container mt-4">
    <div class="card mt-4">
        <div class="card-body">
            <div class="dropdown-section">
                <h3 class="dropdown-header">Indirect Cost ▼</h3>
                <div class="dropdown-content">
                    <h5>Total In Direct Cost - {{ number_format($totalInDirectCost) }}</h5>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Cost Overhead</h3>
                            {{-- <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addNewCostOverheadModal">ADD NEW</button> --}}
                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2"
                                onclick="location.reload()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                        <p>Total overhead Cost : <span
                                style="color:#0067aa; font-weight:bold">{{ number_format($totalCostOverhead) }}<span>
                        </p>
                        <div class="table-responsive text-nowrap limited-scroll mt-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th> <!-- Index column -->

                                        <th>TYPE</th>
                                        <th>PO</th>
                                        <th>PROJECT</th>
                                        <th>EXPENSE</th>
                                        <th>AMOUNT</th>
                                        {{-- <th>ACTION</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budget->costOverheads as $overhead)
                                    @php
                                    $project = $projects->where('id', $overhead->project)->first();
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td> <!-- Index -->

                                        <td>{{ $overhead->type ?? 'no entry' }}</td>
                                        <td>{{ $overhead->po ?? 'no entry' }}</td>
                                        <td>{{ $project->name ?? 'no entry' }}</td>
                                        <td>{{ $overhead->expenses ?? 'no entry' }}</td>
                                        <td>{{ number_format($overhead->amount, 2) ?? 'no entry' }}</td>

                                        {{-- <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item editcostBtn" data-id="{{$overhead->id}}"><i
                                            class=" bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item deletecostbtn" data-id="{{$overhead->id}}"><i
                                                class="bx bx-trash me-1"></i> Delete</a>
                        </div>
                    </div>
                    </td> --}}
                    </tr>
                    @endforeach

                    </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Financial Cost</h3>
                    {{-- <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addNewFinancialCostModal">ADD NEW</button> --}}
                </div>
                <p>Total Financial Cost : <span
                        style="color:#0067aa; font-weight:bold">{{ number_format($totalFinancialCost, 0) }}<span>
                </p>
                <div class="table-responsive text-nowrap limited-scroll mt-2">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th> <!-- Index column -->

                                <th>TYPE</th>
                                <th>PO</th>
                                <th>PROJECT</th>
                                <th>EXPENSE</th>
                                <th>AMOUNT</th>
                                {{-- <th>ACTION</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($budget->financialCosts as $financial)
                            @php
                            $project = $projects->where('id', $financial->project)->first();
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td> <!-- Index -->

                                <td>{{ $financial->type ?? 'no entry' }}</td>
                                <td>{{ $financial->po ?? 'no entry' }}</td>
                                <td>{{ $project->name ?? 'no entry' }}</td>
                                <td>{{ $financial->expenses ?? 'no entry' }}</td>
                                <td>{{ number_format($financial->total_cost, 0) ?? 'no entry' }}</td>

                                {{-- <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item editfinancialbtn" data-id="{{$financial->id}}"><i
                                    class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item deletefinancialbtn" data-id="{{$financial->id}}"><i
                                        class="bx bx-trash me-1"></i> Delete</a>
                </div>
            </div>
            </td> --}}
            </tr>
            @endforeach
            </tbody>
            </table>

        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<!-- Cost Overhead Modal -->
<div class="modal fade" id="addNewCostOverheadModal" tabindex="-1" aria-labelledby="addNewCostOverheadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewCostOverheadModalLabel">Add New Cost Overhead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNewCostOverheadForm" action="{{ url('/pages/add-budget-project-overhead-cost') }}"
                    method="POST">
                    @csrf
                    <div v class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="overhead cost">Overhead Cost</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <select class="form-select" id="project" name="project" required>
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="po" class="form-label">PO</label>
                        <select class="form-select" id="po" name="po" required>
                            <option selected value="OPEX">OPEX</option>
                            <option value="CAPEX">CAPEX</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="expense" class="form-label">Expense Head</label>
                        <select class="form-select" id="overhead-expense" name="expense" required>
                            <option value="" disabled selected>Select an expense head</option>
                            <option value="HO Cost">HO Cost</option>
                            <option value="Annual Benefit">Annual Benefit</option>
                            <option value="Insurance Cost">Insurance Cost</option>
                            <option value="Visa Renewal">Visa Renewal</option>
                            <option value="Other">Other</option> <!-- Optional for custom entries -->
                        </select>
                    </div>

                    <div class="mb-3" id="overhead-other-expense" style="display: none;">
                        <label for="other_expense" class="form-label">Other</label>
                        <input type="text" class="form-control" name="other_expense"
                            placeholder="Specify other expense">
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount"
                            placeholder="Please Enter the Amount" required>
                    </div>



                    <input type="hidden" name="project_id" value="{{ $budget->id }}">
                    <button type="submit" class="btn btn-primary">Add Cost Overhead</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Financial Cost Modal -->
<div class="modal fade" id="addNewFinancialCostModal" tabindex="-1" aria-labelledby="addNewFinancialCostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewFinancialCostModalLabel">Add New Financial Cost</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNewFinancialCostForm" action="{{ url('/pages/add-budget-project-financial-cost') }}"
                    method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="financial cost">financial cost</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <select class="form-select" id="project" name="project" required>
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="po" class="form-label">PO</label>
                        <select class="form-select" id="po" name="po" required>
                            <option value="CAPEX">CAPEX</option>
                            <option selected value="OPEX">OPEX</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="expense" class="form-label">Expense</label>
                        <select class="form-select" id="expense" name="expense" required>
                            <option value="" disabled selected>Select an expense type</option>
                            <option value="Risk">Risk</option>
                            <option value="Financial Cost">Financial Cost</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="amount" class="form-label">Percentage</label>
                        <input type="number" class="form-control" id="amount" name="amount"
                            placeholder="Please Enter % Eg. 1%, 15%" required min="0" max="45">
                    </div>



                    <input type="hidden" name="project_id" value="{{ $budget->id }}">
                    <button type="submit" class="btn btn-primary">Add Financial Cost</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editCostOverheadModal" tabindex="-1" aria-labelledby="editCostOverheadModal"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSalaryModalLabel">Edit Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCostForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div v class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="edit_overhead_type" name="type" required>
                            <option value="overhead cost">Overhead Cost</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <select class="form-select" id="edit_cost_project" name="project" required>
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="po" class="form-label">PO</label>
                        <select class="form-select" id="edit_cost_po" name="po" required>
                            <option selected value="OPEX">OPEX</option>
                            <option value="CAPEX">CAPEX</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="expense" class="form-label">Expense Head</label>
                        <select class="form-select" id="overhead-edit-expense" name="expenses" required>
                            <option value="" disabled selected>Select an expense head</option>
                            <option value="HO Cost">HO Cost</option>
                            <option value="Annual Benefit">Annual Benefit</option>
                            <option value="Insurance Cost">Insurance Cost</option>
                            <option value="Visa Renewal">Visa Renewal</option>
                            <option value="Other">Other</option> <!-- Optional for custom entries -->
                        </select>
                    </div>

                    <div class="mb-3" id="overhead-edit-other-expense" style="display: none;">
                        <label for="other_expense" class="form-label">Other</label>
                        <input type="text" class="form-control" id="other_cost_expense" name="other_expense"
                            placeholder="Specify other expense">
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="cost_amount" name="amount"
                            placeholder="Please Enter the Amount" required>
                    </div>


                    <input type="hidden" id="edit_cost_id" name="id">
                    <input type="hidden" name="budget_project_id" value="{{ $budget->id }}">
                    <button type="submit" class="btn btn-primary">Update Cost Overhead</button>

                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editNewFinancialCostModal" tabindex="-1" aria-labelledby="editNewFinancialCostModal"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSalaryModalLabel">Edit Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editFinancialForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="financial_type" name="type" required>
                            <option value="financial cost">financial cost</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <select class="form-select" id="financial_project" name="project" required>
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="po" class="form-label">PO</label>
                        <select class="form-select" id="financial_po" name="po" required>
                            <option value="CAPEX">CAPEX</option>
                            <option selected value="OPEX">OPEX</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="expense" class="form-label">Expense</label>
                        <select class="form-select" id="financial_expense" name="expenses" required>
                            <option value="" disabled selected>Select an expense type</option>
                            <option value="Risk">Risk</option>
                            <option value="Financial Cost">Financial Cost</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="amount" class="form-label">Percentage</label>
                        <input type="number" class="form-control" id="financial_amount" name="amount"
                            placeholder="Please Enter % Eg. 1%, 15%" required min="0" max="45">
                    </div>

                    <input type="hidden" id="financial_id" name="id">
                    <input type="hidden" name="budget_project_id" value="{{ $budget->id }}">
                    <button type="submit" class="btn btn-primary">Update Cost Financial</button>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Set up the event listener when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        function overheadExpenseHandling() {
            const overheadExpenseSelect = document.getElementById('overhead-expense');
            const otherField = document.getElementById('overhead-other-expense');

            overheadExpenseSelect.addEventListener('change', function() {
                otherField.style.display = (this.value == 'Other') ? 'block' : 'none';
            });
        }
        overheadExpenseHandling();
    });

    function openEditCostOverHeadModal(id) {
        // Fetch the salary data and populate the form
        $.ajax({
            url: `/pages/get-costoverhead-data/${id}`,
            type: 'GET',
            success: function(data) {
                $('#edit_cost_id').val(data.id);
                $('#edit_overhead_type').val(data.type);
                $('#edit_cost_project').val(data.project);
                $('#edit_cost_po').val(data.po);
                $('#overhead-edit-expense').val(data.expenses);
                $('#cost_amount').val(data.amount);
                if (!['HO Cost', 'Annual Benefit', 'Insurance Cost', 'Visa Renewal', 'Other'].includes(data
                        .expenses)) {
                    $('#overhead-edit-expense').val("Other");
                    $('#overhead-edit-other-expense').show();
                    $('#other_cost_expense').val(data.expenses);
                } else {
                    $('#overhead-other-expense').hide();
                }


                $('#editCostOverheadModal').modal('show');
            },
            error: function() {
                alert('Error fetching salary data');
            }
        });
    }
    $('#editCostForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var id = $('#edit_cost_id').val();

        $.ajax({
            type: "POST",
            url: `/pages/update-costoverhead/${id}`,
            data: form.serialize(),
            success: function(data) {
                if (data.success) {
                    showAlert('success', 'record updated successfully.');
                    $('#editCostOverheadModal').modal('hide');
                    // Refresh the salary table or update the specific row
                    setTimeout(() => {
                        window.location.reload()
                    }, 2000)
                } else {
                    alert('Error updating salary data');
                }
            },
            error: function() {
                alert('Error updating salary data');
            }
        });
    });

    $('.editcostBtn').on('click', function() {
        var id = $(this).data('id');
        openEditCostOverHeadModal(id);
    });

    function openEditFinancialModal(id) {
        // Fetch the salary data and populate the form
        $.ajax({
            url: `/pages/get-financial-data/${id}`,
            type: 'GET',
            success: function(data) {
                $('#financial_id').val(data.id);
                $('#financial_type').val(data.type);
                $('#financial_project').val(data.project);
                $('#financial_po').val(data.po);
                $('#financial_expense').val(data.expenses);
                $('#financial_amount').val(data.percentage);

                $('#editNewFinancialCostModal').modal('show');
            },
            error: function() {
                alert('Error fetching salary data');
            }
        });
    }


    $('#editFinancialForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var id = $('#financial_id').val();

        $.ajax({
            type: "POST",
            url: `/pages/update-financial/${id}`,
            data: form.serialize(),
            success: function(data) {
                if (data.success) {
                    showAlert('success', 'record updated successfully.');
                    $('#editNewFinancialCostModal').modal('hide');
                    // Refresh the salary table or update the specific row
                    setTimeout(() => {
                        window.location.reload()
                    }, 2000)
                } else {
                    alert('Error updating salary data');
                }
            },
            error: function() {
                alert('Error updating salary data');
            }
        });
    });


    $('.editfinancialbtn').on('click', function() {
        var id = $(this).data('id');
        openEditFinancialModal(id);
    });
    $('#overhead-edit-expense').on('change', function() {
        const overheadExpenseSelect = document.getElementById('overhead-edit-expense');
        const otherField = document.getElementById('overhead-edit-other-expense');
        if (overheadExpenseSelect.value == 'Other') {
            otherField.style.display = 'block'

        } else {

            otherField.style.display = 'none'
        }

    });
    document.querySelectorAll('.deletecostbtn').forEach(button => {
        button.addEventListener('click', function(e) {
            const userId = this.getAttribute('data-id');

            // Confirm deletion with the user
            if (confirm('Are you sure you want to delete this project record?')) {
                deleteCostOverHead(userId); // Call the function to delete the record
            }
        });
    });

    function deleteCostOverHead(id) {
        fetch('/api/delete-costoverhead', { // Replace with your actual API endpoint
                method: 'POST',
                body: JSON.stringify({
                    id: id
                }),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.success);
                    setTimeout(() => {
                        window.location.reload()
                    }, 2000)
                } else {
                    showAlert('danger', data.message || 'An error occurred while deleting the User record.');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showAlert('danger', 'A network error occurred. Please try again.');
            });
    }


    document.querySelectorAll('.deletefinancialbtn').forEach(button => {
        button.addEventListener('click', function(e) {
            const userId = this.getAttribute('data-id');

            // Confirm deletion with the user
            if (confirm('Are you sure you want to delete this project record?')) {
                deleteFinancial(userId); // Call the function to delete the record
            }
        });
    });

    function deleteFinancial(id) {
        fetch('/api/delete-financial', { // Replace with your actual API endpoint
                method: 'POST',
                body: JSON.stringify({
                    id: id
                }),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.success);
                    setTimeout(() => {
                        window.location.reload()
                    }, 2000)
                } else {
                    showAlert('danger', data.message || 'An error occurred while deleting the User record.');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showAlert('danger', 'A network error occurred. Please try again.');
            });
    }



    function showAlert(type, message) {
        const alertBox = document.getElementById('responseAlertnew');
        const alertMessage = document.getElementById('alertMessagenew');
        alertBox.className = `alert alert-${type} alert-dismissible fade show`;
        alertMessage.textContent = message;
        alertBox.style.display = 'block';

        // Hide the alert after 3 seconds
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000);
    }
</script>