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

    /* Custom styles for scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #0067aa;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background-color: #004c7f;
    }

    .dropdown-header {
        cursor: pointer;
        font-size: 1.5rem;
        /* Change to adjust font size */
        color: #0067aa;
    }

    .dropdown-content {
        display: none;
        /* Hidden by default */
        margin-top: 15px;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Budget Management /</span> Edit Project Budget
</h4>

<div class="row">
    <div class="col-md-12">

        <!-- Alert box HTML -->
        <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
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
                <h6>Edit Budget</h6>
                <form action="{{ route('update-project-budget', $budget->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Use PUT method for updating -->

                    <div class="row">
                        <div class="col-sm-4">
                            <label for="startdate" class="form-label">Start Date</label>
                            <input type="date" id="startdate" class="form-control" name="startdate" value="{{ old('startdate', $budget->start_date) }}" placeholder="Enter Start Date" required />
                        </div>
                        <div class="col-sm-4">
                            <label for="enddate" class="form-label">End Date</label>
                            <input type="date" id="enddate" class="form-control" name="enddate" value="{{ old('enddate', $budget->end_date) }}" placeholder="Enter End Date" />
                        </div>
                        <div class="col-sm-4">
                            <label for="month" class="form-label">Choose Date</label>
                            <input type="date" class="form-control" name="month" value="{{ old('month', $budget->month) }}" placeholder="Enter Month" />
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-4">
                            <label for="projectname" class="form-label">Choose Project Name</label>
                            <select class="form-select" name="projectname">
                                <option disabled selected value>Choose</option>
                                @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ $project->id == $budget->project_id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="client" class="form-label">Choose Client</label>
                            <select class="form-select" name="client">
                                <option disabled selected value>Choose</option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ $client->id == $budget->client_id ? 'selected' : '' }}>{{ $client->clientname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="division" class="form-label">Choose Division</label>
                            <select class="form-select" name="division">
                                <option disabled selected value>Choose</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ $unit->id == $budget->unit_id ? 'selected' : '' }}>{{ $unit->source }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <label for="sitename" class="form-label">Site Name</label>
                            <input type="text" class="form-control" name="sitename" value="{{ old('sitename', $budget->site_name) }}" placeholder="Please enter site name" />
                        </div>

                        <div class="col-sm-6">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country" onchange="updateRegions()">
                                <option disabled selected value>Choose</option>
                                <option value="KSA" {{ $budget->country == 'KSA' ? 'selected' : '' }}>KSA</option>
                                <option value="UAE" {{ $budget->country == 'UAE' ? 'selected' : '' }}>UAE</option>
                                <option value="UK" {{ $budget->country == 'UK' ? 'selected' : '' }}>UK</option>
                                <option value="USA" {{ $budget->country == 'USA' ? 'selected' : '' }}>USA</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <label for="region" class="form-label">Region</label>
                            <select class="form-select" id="region" name="region">
                                <option disabled selected value>Choose</option>
                                <!-- Hardcoded options for all regions -->
                                <!-- UAE Regions -->
                                <option value="Abu Dhabi" {{ $budget->region == 'Abu Dhabi' ? 'selected' : '' }}>Abu Dhabi</option>
                                <option value="Dubai" {{ $budget->region == 'Dubai' ? 'selected' : '' }}>Dubai</option>
                                <option value="Sharjah" {{ $budget->region == 'Sharjah' ? 'selected' : '' }}>Sharjah</option>
                                <option value="Umm Al Quwain" {{ $budget->region == 'Umm Al Quwain' ? 'selected' : '' }}>Umm Al Quwain</option>
                                <option value="Ras Al Khaimah" {{ $budget->region == 'Ras Al Khaimah' ? 'selected' : '' }}>Ras Al Khaimah</option>
                                <option value="Fujairah" {{ $budget->region == 'Fujairah' ? 'selected' : '' }}>Fujairah</option>

                                <!-- KSA Regions -->
                                <option value="Riyadh" {{ $budget->region == 'Riyadh' ? 'selected' : '' }}>Riyadh</option>
                                <option value="Jeddah" {{ $budget->region == 'Jeddah' ? 'selected' : '' }}>Jeddah</option>
                                <option value="Dammam" {{ $budget->region == 'Dammam' ? 'selected' : '' }}>Dammam</option>
                                <option value="Khobar" {{ $budget->region == 'Khobar' ? 'selected' : '' }}>Khobar</option>

                                <!-- UK Regions -->
                                <option value="London" {{ $budget->region == 'London' ? 'selected' : '' }}>London</option>
                                <option value="Manchester" {{ $budget->region == 'Manchester' ? 'selected' : '' }}>Manchester</option>
                                <option value="Birmingham" {{ $budget->region == 'Birmingham' ? 'selected' : '' }}>Birmingham</option>
                                <option value="Liverpool" {{ $budget->region == 'Liverpool' ? 'selected' : '' }}>Liverpool</option>

                                <!-- USA Regions -->
                                <option value="New York" {{ $budget->region == 'New York' ? 'selected' : '' }}>New York</option>
                                <option value="Los Angeles" {{ $budget->region == 'Los Angeles' ? 'selected' : '' }}>Los Angeles</option>
                                <option value="Chicago" {{ $budget->region == 'Chicago' ? 'selected' : '' }}>Chicago</option>
                                <option value="Houston" {{ $budget->region == 'Houston' ? 'selected' : '' }}>Houston</option>
                                <option value="Phoenix" {{ $budget->region == 'Phoenix' ? 'selected' : '' }}>Phoenix</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" value="{{ old('description', $budget->description) }}" placeholder="description" />
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-4">
                            <label for="budget_type" class="form-label">Project Budget Type</label>
                            <select class="form-select" name="budget_type" id="budget_type">
                                <option disabled selected value>Choose</option>
                                <option value="Fleet Management" {{ $budget->budget_type == 'Fleet Management' ? 'selected' : '' }}>Fleet Management</option>
                                <option value="Auto Workshop" {{ $budget->budget_type == 'Auto Workshop' ? 'selected' : '' }}>Auto Workshop</option>
                                <option value="Etisalat Managed Service" {{ $budget->budget_type == 'Etisalat Managed Service' ? 'selected' : '' }}>Etisalat Managed Service</option>
                                <option value="Other" {{ $budget->budget_type == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                </form>


            </div>
        </div>


        @include('content.pages.components.edit-direct-cost', [
        'projects' => $projects,
        'salaries' => $budget->salaries,
        'facilityCosts' => $budget->facilityCosts,
        'materialCosts' => $budget->materialCosts,
        'budget' => $budget
        ])

        @include('content.pages.components.edit-indirect-cost', [
        'projects' => $projects,
        'costOverheads' => $budget->costOverheads,
        'financialCosts' => $budget->financialCosts,
        'budget' => $budget
        ])

        @include('content.pages.components.edit-capital-expenditure', [
        'budget' => $budget,

        ])

        @include('content.pages.components.edit-revenue-plan', [
        'budget' => $budget,
        'revenuePlans' => $budget->revenuePlans
        ])

        @include('content.pages.components.edit-cash-management', [
        'budget' => $budget,
        ])



        <!--Model-->
        <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
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

            document.addEventListener('DOMContentLoaded', function() {
                const dropdownHeaders = document.querySelectorAll('.dropdown-header');

                dropdownHeaders.forEach(header => {
                    header.addEventListener('click', function() {
                        const content = this.nextElementSibling;
                        if (content.style.display === 'block') {
                            content.style.display = 'none';
                        } else {
                            content.style.display = 'block';
                        }
                    });
                });
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