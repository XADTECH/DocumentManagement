@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Pages')

@section('content')

    <style>
        .limited-scroll {
            max-height: 200px;
            /* Set the maximum height as needed */
            overflow-y: auto;
            /* Adds a vertical scrollbar when content overflows */
            display: block;
            /* Ensures the scrollbar is visible on the tbody */
        }

        .font_style {
            font-weight: bold;
        }

        #error-alert,
        #success-alert {
            transition: opacity 0.5s ease-out;
        }

        .status-approved {
            color: #4caf50;
            /* Green for Approved */
        }

        .status-rejected {
            color: #ff6f6f;
            /* Red for Rejected */
        }

        .status-pending {
            color: #ff9800;
            /* Orange for Pending */
        }

        .status-default {
            color: #000000;
            /* Default color */
        }
    </style>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Budget Management /</span> Add Project Budget
    </h4>

    <div class="row">
        <div class="col-md-12">

            <!-- Alert box HTML -->
            <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert"
                style="display: none; width:80%; margin:10px auto">
                <span id="alertMessage"></span>
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>

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

            <!-- Project Form -->
            <div class="card">
                <div class="card-body">
                    <h6>Add A Budget </h6>
                    <form action="{{ route('add-project-budget') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="startdate" class="form-label">Start Date</label>
                                <input type="date" id="startdate" class="form-control" name="startdate"
                                    placeholder="Enter Start Date" required />
                            </div>
                            <div class="col-sm-4">
                                <label for="startdate" class="form-label">End Date</label>
                                <input type="date" id="enddate" class="form-control" name="enddate"
                                    placeholder="Enter Start Date" />
                            </div>
                            <div class="col-sm-4">
                                <label for="startdate" class="form-label">Choose Date</label>
                                <input type="date" class="form-control" name="month" placeholder="Enter End Date" />
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-4">
                                <label for="startdate" class="form-label">Choose Project Name</label>

                                <select class="form-select" name="projectname">
                                    <option disabled selected value>Choose</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="startdate" class="form-label">Choose Client </label>

                                <select class="form-select" name="client">
                                    <option disabled selected value>Choose</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->clientname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="startdate" class="form-label">Choose Division </label>
                                <select class="form-select" name="division">
                                    <option disabled selected value>Choose</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->source }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <label for="startdate" class="form-label">Site Name </label>
                                <input type="text" class="form-control" name="sitename"
                                    placeholder="Please enter site name" />
                            </div>

                            <div class="col-sm-6">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-select" id="country" name="country" onchange="updateRegions()">
                                    <option disabled selected value>Choose</option>
                                    <option value="KSA">KSA</option>
                                    <option value="UAE">UAE</option>
                                    <option value="UK">UK</option>
                                    <option value="USA">USA</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">

                            <div class="col-sm-6">
                                <label for="region" class="form-label">Region</label>
                                <select class="form-select" id="region" name="region">
                                    <option disabled selected value>Choose</option>
                                </select>
                            </div>



                            <div class="col-sm-6">
                                <label for="description" class="form-label"> Description </label>
                                <input type="text" class="form-control" name="description"
                                    placeholder="description" />
                            </div>

                        </div>

                        <div class="row mt-4">

                            <div class="col-sm-4">
                                <label for="budget_type" class="form-label">Project Budget Type</label>
                                <select class="form-select" name="budget_type" id="budget_type">
                                    <option disabled selected value>Choose</option>
                                    <option value="Fleet Management">Fleet Management</option>
                                    <option value="Auto Workshop">Auto Workshop</option>
                                    <option value="Etisalat Managed Service">Etisalat Managed Service</option>
                                    <option value="Other">Other</option>
                                </select>
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

    <!-- Projects Table -->
    <div class="card mt-4">
        <h5 class="card-header">Budget List</h5>
        <div class="table-responsive text-nowrap  limited-scroll">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>RefCode</th>
                        <th>Month</th>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Project Manager</th>
                        <th>Budget Type</th>
                        <th>Budget Allocated</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="project-table-body" class="table-border-bottom-0">
                    @foreach ($budgets as $budget)
                        @php
                            // Find the client name from the $clients collection
                            $client = $clients->firstWhere('id', $budget->client_id);
                            $clientName = $client ? $client->clientname : 'N/A'; // Handle cases where client is not found

                            $user = $users->firstWhere('id', $budget->manager_id);
                            $userName = $user ? $user->first_name : 'N/A'; // Handle cases where client is not found

                            $project = $projects->firstWhere('id', $budget->project_id);
                            $projectName = $project ? $project->name : 'N/A'; // Handle cases where client is not found

                            $unit = $units->firstWhere('id', $budget->unit_id);
                            $unitName = $unit ? $unit->source : 'N/A'; // Handle cases where client is not found

                            // Parse the month using Carbon
                            $month = \Carbon\Carbon::parse($budget->month);

                            // Format month and year
                            $formattedMonth = $month->format('F'); // Full month name (e.g., August)
                            $formattedYear = $month->format('Y'); // Year (e.g., 2024)
                        @endphp

                        <tr>
                            <td style="color:#0067aa"><i
                                    class="bx bxl-angular bx-sm text-danger me-3"></i><a href="{{ route('edit-project-budget', ['project_id' => $budget->id]) }}">{{ $budget->reference_code }}</a></td>
                            <td class="font_style">{{ $formattedMonth }} {{ $formattedYear }}</td>
                            <td class="font_style">{{ $projectName }}</td>
                            <td class="font_style">{{ $clientName }}</td>
                            <td class="font_style">{{ $userName }}</td>
                            <td class="font_style">{{ $budget->budget_type }}</td>
                            <td class="font_style"
                                style="color: {{ is_null($budget->total_budget_allocated) ? '#ff6f6f' : '#4caf50' }};">
                                {{ $budget->total_budget_allocated ?? 'Not Allocated' }}
                            </td>
                            <td class="font_style"
                                style="color: 
                        @if ($budget->approval_status === 'approve') #4caf50; /* Green for Approved */
                        @elseif($budget->approval_status === 'rejected')
                            #ff6f6f; /* Red for Rejected */
                        @elseif($budget->approval_status === 'pending')
                            #ff9800; /* Orange for Pending */
                        @else
                            #000000; /* Default color for other statuses */ @endif
                    ">
                                {{ $budget->approval_status }}
                            </td>


                            <!-- <td class="font_style">{{ $unitName }}</td> -->
                            <td>
                                <form action="{{ route('edit-project-budget', ['project_id' => $budget->id]) }}"
                                    method="GET" style="display:inline;">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                </form>

                                @if ($budget->approval_status == 'pending')
                                    <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                            onclick="return confirm('Are you sure?');">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!--Model-->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProjectForm">
                        <input type="hidden" id="projectId" name="project_id">
                        <div class="mb-3">
                            <label for="projectName" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="projectName" name="projectName" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectDetails" class="form-label">Project Details</label>
                            <input type="text" class="form-control" id="projectDetails" name="projectDetails">
                        </div>
                        <div class="mb-3">
                            <label for="projectRemarks" class="form-label">Remarks</label>
                            <input type="text" class="form-control" id="projectRemarks" name="projectRemarks">
                        </div>
                        <div class="mb-3">
                            <label for="projectStatus" class="form-label">Status</label>
                            <select class="form-select" id="projectStatus" name="projectStatus">
                                <option value="Active">Active</option>
                                <option value="Non Active">Non Active</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function hideAlertAfterDelay(alertId, delay) {
                console.log('Trying to hide', alertId);
                var alertElement = document.getElementById(alertId);
                if (alertElement) {
                    setTimeout(function() {
                        console.log('Hiding', alertId);
                        alertElement.style.opacity = 0; // Fade out effect
                        setTimeout(function() {
                            alertElement.style.display = 'none'; // Hide element after fading out
                        }, 500); // Match the duration of the fade-out effect
                    }, delay);
                } else {
                    console.log('Element not found:', alertId);
                }
            }

            // Hide alerts after 3000 ms
            hideAlertAfterDelay('error-alert', 3000);
            hideAlertAfterDelay('success-alert', 3000);
        });

        function updateRegions() {
            const country = document.getElementById("country").value;
            const regionSelect = document.getElementById("region");
            regionSelect.innerHTML = '<option disabled selected value>Choose</option>'; // Reset regions

            const regions = {
                'KSA': ['Riyadh', 'Jeddah', 'Dammam', 'Makkah', 'Madinah'],
                'UAE': ['Abu Dhabi', 'Dubai', 'Sharjah', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'],
                'UK': ['London', 'Manchester', 'Birmingham', 'Glasgow', 'Edinburgh'],
                'USA': ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Miami']
            };

            if (regions[country]) {
                regions[country].forEach(region => {
                    const option = document.createElement("option");
                    option.value = region;
                    option.text = region;
                    regionSelect.appendChild(option);
                });
            }
        }
    </script>

@endsection
