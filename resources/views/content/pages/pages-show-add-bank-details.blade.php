@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')

    <style>
        /* Custom modal height and scrollbar styling */
        .modal-dialog {
            max-width: 40%;
            /* Optional: Set your preferred width */
        }

        .modal-body {
            max-height: 450px;
            /* Set max height for modal */
            overflow-y: auto;
            /* Enable vertical scrolling */
            padding-left: 10px;
            /* Optional: Adjust left padding to allow space for custom scrollbar */
        }

        /* Custom Scrollbar Styles */
        .modal-body::-webkit-scrollbar {
            width: 8px;
            /* Width of the scrollbar */
        }

        .modal-body::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            /* Light background for the scrollbar track */
        }

        .modal-body::-webkit-scrollbar-thumb {
            background-color: #0067aa;
            /* Color of the scrollbar thumb */
            border-radius: 10px;
            /* Rounded corners for the thumb */
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background-color: #005b99;
            /* Darker color when hovering */
        }

        .btn-custom {
            min-width: 120px;
            height: 40px;
            font-size: 16px;
        }
    </style>


    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Bank Management /</span> Add Bank Details
    </h4>

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

    <!-- Spinner and Backdrop -->
    <div id="loading-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6>Enter the Bank Details </h6>
                    <!-- Main Form for Bank Details -->
                    <form id="openingBalanceForm" action="{{ route('banks.store') }}" method="POST">
                        @csrf <!-- Include CSRF token for security -->
                        <div class="row">
                            <!-- Bank Name -->
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" />
                            </div>

                            <!-- IBAN -->
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="iban" placeholder="Enter IBAN" />
                            </div>

                            <!-- Account Number -->
                            <div class="col-sm-6 mt-4">
                                <input type="text" class="form-control" name="account"
                                    placeholder="Enter Account Number" />
                            </div>

                            <!-- SWIFT Code -->
                            <div class="col-sm-6 mt-4">
                                <input type="text" class="form-control" name="swift_code"
                                    placeholder="Enter SWIFT Code" />
                            </div>

                            <!-- Country Selection -->
                            <div class="col-sm-6 mt-4">
                                <select class="form-select" id="country" name="country" onchange="updateRegions()">
                                    <option disabled selected value>Country</option>
                                    <option value="KSA">KSA</option>
                                    <option value="UAE">UAE</option>
                                    <option value="UK">UK</option>
                                    <option value="USA">USA</option>
                                </select>
                            </div>

                            <!-- Region Selection -->
                            <div class="col-sm-6 mt-4">
                                <select class="form-select" id="region" name="region">
                                    <option disabled selected value>Region</option>
                                </select>
                            </div>

                            <!-- Address (Text Area) -->
                            <div class="col-sm-6 mt-4">
                                <textarea class="form-control" name="address" placeholder="Enter Address"></textarea>
                            </div>

                            <!-- Branch -->
                            <div class="col-sm-6 mt-4">
                                <input type="text" class="form-control" name="branch" placeholder="Enter Branch" />
                            </div>

                            <!-- RM Detail (Text Area) -->
                            <div class="col-sm-6 mt-4">
                                <textarea class="form-control" name="rm_detail" placeholder="Enter RM Detail"></textarea>
                            </div>

                            <!-- Balance Amount -->
                            <div class="col-sm-6 mt-4">
                                <input type="text" class="form-control" name="balance_amount" placeholder="Enter Balance"
                                    value="0" />
                            </div>
                        </div>

                        <!-- Submit Button for Main Form -->
                        <div class="d-flex gap-2 mt-2">
                            <button type="submit" class="btn btn-primary btn-custom">Submit</button>
                        </div>
                    </form>


                    <!-- Spinner and Backdrop -->
                    <div id="loading-overlay"
                        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: right;">
                        <!-- Separate Form for File Upload -->
                        <form action="{{ route('banks.import') }}" method="POST" enctype="multipart/form-data"
                            id="file-upload-form" class="m-2">
                            @csrf
                            <!-- Hidden file input -->
                            <input type="file" name="file" id="file-upload" style="display: none;" required>

                            <!-- Upload Button Triggers File Input -->
                            <button type="button" class="btn btn-primary btn-custom"
                                onclick="triggerFileUpload()">Upload</button>
                        </form>

                        <!-- Download Button -->
                        <a href="{{ route('banks.download') }}" class="btn btn-primary btn-custom m-2">
                            Download Excel
                        </a>

                        <!-- Filter Input -->
                        <div class="col-md-4">
                            <input type="text" id="bankFilter" class="form-control"
                                placeholder="Search by Name or Address" />
                        </div>
                    </div>


                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No #</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Ledger</th>
                                <th>Branch</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bank-table-body" class="table-border-bottom-0">
                            @foreach ($banks as $index => $bank)
                                <tr>
                                    <td style="color:#0067aa"><i class="bx bxl-angular bx-sm text-danger me-3"></i><span
                                            style="font-size:12px; margin-left:-10px">{{ $index }}</span></td>

                                    <!-- Add class bank-name to the Name column -->
                                    <td class="bank-name">{{ $bank->bank_name }}</td>

                                    <!-- Add class bank-address to the Address column -->
                                    <td class="bank-address">{{ substr($bank->bank_address, 0, 25) }}</td>

                                    <td><a href="{{ route('banks.ledger', ['id' => $bank->id]) }}">View Ledger</a></td>

                                    <td class="bank-branch">{{ $bank->branch }}</td>
                                    <td>
                                        <!-- Example actions: Edit and Delete -->
                                        <button class="btn btn-sm btn-primary" onclick="populateEditModal(this)"
                                            data-id="{{ $bank->id }}" data-name="{{ $bank->bank_name }}"
                                            data-iban="{{ $bank->iban }}" data-account="{{ $bank->account }}"
                                            data-swift_code="{{ $bank->swift_code }}"
                                            data-address="{{ $bank->bank_address }}" data-branch="{{ $bank->branch }}"
                                            data-rm_detail="{{ $bank->rm_detail }}"
                                            data-balance_amount="{{ $bank->balance_amount }}"
                                            data-country="{{ $bank->country }}" data-region="{{ $bank->region }}">Edit
                                        </button>
                                        <form action="{{ route('banks.delete') }}" method="POST"
                                            style="display:inline;" onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="bank_id" value="{{ $bank->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                        <a href="{{ route('banks.detail', $bank->id) }}"
                                            class="btn btn-sm btn-info">Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>



                </div>
                <!-- /Notifications -->
            </div>
        </div>
    </div>

    <!-- Model -->
    <div class="modal fade" id="editBankModal" tabindex="-1" aria-labelledby="editBankModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBankModalLabel">Edit Bank Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBankForm" action="{{ route('banks.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="bankId" name="bank_id">

                        <div class="mb-3">
                            <label for="bankName" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="bankName" name="name">
                        </div>

                        <div class="mb-3">
                            <label for="iban" class="form-label">IBAN</label>
                            <input type="text" class="form-control" id="iban" name="iban">
                        </div>

                        <div class="mb-3">
                            <label for="account" class="form-label">Account</label>
                            <input type="text" class="form-control" id="account" name="account">
                        </div>

                        <div class="mb-3">
                            <label for="swiftCode" class="form-label">Swift Code</label>
                            <input type="text" class="form-control" id="swiftCode" name="swift_code">
                        </div>

                        <div class="mb-3">
                            <label for="bankAddress" class="form-label">Bank Address</label>
                            <input type="text" class="form-control" id="bankAddress" name="address">
                        </div>

                        <div class="mb-3">
                            <label for="branch" class="form-label">Branch</label>
                            <input type="text" class="form-control" id="branch" name="branch">
                        </div>

                        <div class="mb-3">
                            <label for="rmDetail" class="form-label">RM Detail</label>
                            <input type="textarea" class="form-control" id="rmDetail" name="rm_detail">
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="country2" name="country"
                                placeholder="Enter Country">
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="region2" name="region"
                                placeholder="Enter Region">
                        </div>


                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Trigger file input click
        function triggerFileUpload() {
            document.getElementById('file-upload').click();
        }

        // Show loading spinner and submit form
        document.getElementById('file-upload').addEventListener('change', function() {
            const overlay = document.getElementById('loading-overlay');
            overlay.style.display = 'flex'; // Show the spinner
            setTimeout(() => {
                document.getElementById('file-upload-form').submit(); // Submit form after delay
            }, 500); // Small delay to ensure spinner is visible
        });

        function confirmDelete() {
            return confirm("Are you sure you want to delete this bank?");
        }

        function updateRegions() {

            const country = document.getElementById("country").value;
            console.log(country)
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

        function updateRegions_two() {

            const country = document.getElementById("country2").value;
            console.log(country)
            const regionSelect = document.getElementById("region2");
            regionSelect.innerHTML = '<option disabled selected value>Choose</option>'; // Reset regions

            let regions = {
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

        function populateEditModal(model) {

            const regions = {
                'KSA': ['Riyadh', 'Jeddah', 'Dammam', 'Makkah', 'Madinah'],
                'UAE': ['Abu Dhabi', 'Dubai', 'Sharjah', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'],
                'UK': ['London', 'Manchester', 'Birmingham', 'Glasgow', 'Edinburgh'],
                'USA': ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Miami']
            };

            const form = document.getElementById('openingBalanceForm');
            console.log(model.getAttribute('data-region'));
            const regionSelect = document.getElementById('region2');

            // Loop through each country in the regions object
            for (const country in regions) {
                // Create an option for the country
                const countryOption = document.createElement('option');
                countryOption.value = country;
                countryOption.textContent = country;
                regionSelect.appendChild(countryOption);

                // Loop through each city in the country and add it as a sub-option
                regions[country].forEach(city => {
                    const cityOption = document.createElement('option');
                    cityOption.value = city;
                    cityOption.textContent = `${city}`;
                    regionSelect.appendChild(cityOption);
                });
            }

            document.getElementById('bankName').value = model.getAttribute('data-name');
            document.getElementById('iban').value = model.getAttribute('data-iban');
            document.getElementById('account').value = model.getAttribute('data-account');
            document.getElementById('swiftCode').value = model.getAttribute('data-swift_code');
            document.getElementById('bankAddress').value = model.getAttribute('data-address');
            document.getElementById('branch').value = model.getAttribute('data-branch');
            document.getElementById('rmDetail').value = model.getAttribute('data-rm_detail');
            document.getElementById('country2').value = model.getAttribute('data-country');
            document.getElementById('region2').value = model.getAttribute('data-region');
            document.getElementById('bankId').value = model.getAttribute('data-id');

            // Show the modal
            new bootstrap.Modal(document.getElementById('editBankModal')).show();
        }

        // JavaScript to filter table rows based on input
        document.getElementById('bankFilter').addEventListener('input', function() {

            const filter = this.value.toLowerCase();

            const rows = document.getElementById('bank-table-body').getElementsByTagName('tr');

            // Loop through each row
            Array.from(rows).forEach(row => {
                console.log(row);
                const name = row.querySelector('.bank-name') ? row.querySelector('.bank-name').textContent
                    .toLowerCase() : '';
                const address = row.querySelector('.bank-address') ? row.querySelector('.bank-address')
                    .textContent.toLowerCase() : '';

                const branch = row.querySelector('.bank-branch') ? row.querySelector('.bank-branch')
                    .textContent.toLowerCase() : '';

                if (name.includes(filter) || address.includes(filter)) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        });
    </script>

@endsection
