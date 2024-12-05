<style>
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
<div class="container mt-4">
    <div class="card mt-4">
        <div class="card-body">
            <div class="dropdown-section">
                <h3 class="dropdown-header">Renenue & Profit ▼</h3>
                <div class="dropdown-content">
                    <!-- Salary Section -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Net Profit after Tax : {{ number_format($totalNetProfitAfterTax) }} AED</h5>
                            <div class="d-flex">
                                <div style="display: flex; align-items: center; justify-content: right;">
                                    <!-- Separate Form for File Upload -->
                                    <form action="{{ route('revenue.import') }}" method="POST" enctype="multipart/form-data"
                                        id="revenue-file-upload-form" class="m-2">
                                        @csrf
                                        <!-- Hidden file input -->
                                        <input type="file" name="revenue-file" id="revenue-file-upload" style="display: none;" required>
                                        <input type="hidden" name="bg_id" value="{{$project_id}}">

                                        <!-- Upload Button Triggers File Input -->
                                        <button type="button" class="btn btn-primary btn-custom"
                                            onclick="revenuetriggerFileUpload()">Upload</button>
                                    </form>

                                    <!-- Download Button -->
                                    <a href="{{ route('revenueplan-export',$project_id) }}" class="btn btn-primary btn-custom m-2">
                                        Download Excel
                                    </a>


                                </div>
                                @if ($budget->approval_status === 'pending')
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addNewRevenuePlan">ADD REVENUE</button>
                                @else
                                <button class="btn btn-secondary" disabled>Approved</button>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive text-nowrap limited-scroll mt-2">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>TYPE</th>
                                        <th>PROJECT</th>
                                        <th>DESCRIPTION</th>
                                        <th>AMOUNT</th>
                                        <th>TOTAL PROFIT</th>
                                        <th>NET PROFIT BEFORE TAX</th>
                                        <th>TAXED AMOUNT</th>
                                        <th>NET PROFIT AFTER TAX</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budget->revenuePlans as $revenuePlan)
                                    <tr>
                                        @php
                                        $project = $projects->where('id', $revenuePlan->project)->first();
                                        @endphp
                                        <td>{{ $revenuePlan->sn }}</td>
                                        <td>{{ $revenuePlan->type }}</td>
                                        <td>{{ $project->name ?? 'no entry' }}</td>
                                        <td>{{ $revenuePlan->description }}</td>
                                        <td>{{ number_format($revenuePlan->amount, 0) }}</td>
                                        <td>{{ number_format($revenuePlan->total_profit, 0) }}</td>
                                        <td>{{ number_format($revenuePlan->net_profit_before_tax, 0) }}</td>
                                        <td>{{ number_format($revenuePlan->tax, 0) }}</td>
                                        <td>{{ number_format($revenuePlan->net_profit_after_tax, 0) }}</td>
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

<!-- Salary Modal -->
<div class="modal fade" id="addNewRevenuePlan" tabindex="-1" aria-labelledby="addNewSalaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewSalaryModalLabel">Add Revenue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNewRevenueForm" action="{{ url('/pages/add-budget-project-revenue') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="Revenue">Revenue</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="project" class="form-label">Project</label>
                        <select class="form-select" id="project" name="project">
                            @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required
                            placeholder="Enter amount (e.g., 10000)">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required
                            placeholder="Enter description (e.g., NOC Payment)">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status"
                            placeholder="Enter status (e.g., Pending)">
                    </div>
                    <input type="hidden" name="project_id" value="{{ $budget->id }}">

                    <button type="submit" class="btn btn-primary">Add Revenue</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    function revenuetriggerFileUpload() {
        document.getElementById('revenue-file-upload').click();
    }
    document.getElementById('revenue-file-upload').addEventListener('change', function() {
        const overlay = document.getElementById('loading-overlay');
        overlay.style.display = 'flex'; // Show the spinner
        setTimeout(() => {
            document.getElementById('revenue-file-upload-form').submit(); // Submit form after delay
        }, 500); // Small delay to ensure spinner is visible
    });
</script>