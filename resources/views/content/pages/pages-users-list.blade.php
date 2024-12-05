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
</style>
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">User Managment /</span> User Lists
</h4>
<div class="col-12">
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

</div>
<!-- Projects Table -->
<div class="card mt-4">
    <h5 class="card-header">Users List</h5>
    <div class="table-responsive text-nowrap  limited-scroll">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Xad Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="project-table-body" class="table-border-bottom-0">
                <!-- Project rows will be added here -->
            </tbody>
        </table>
    </div>
</div>

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
                    <input type="hidden" id="userid" name="id">
                    <div class="mb-3">
                        <label for="xadid" class="form-label">Xad Id</label>
                        <input type="text" class="form-control" id="xadid" name="xad_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="fistName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectDetails" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectRemarks" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3 ">
                        <label class="form-label" for="phone_number">Phone Number</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">UAE (+971)</span>
                            <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="050 123 567" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="projectStatus" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            @foreach(App\Models\User::roles() as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Function to format balance (you can adjust this based on your needs)
    function formatText(text) {
        return text || 'Not Entered';
    }

    //update project modal 

    document.getElementById('editProjectForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const modalElement = document.getElementById('editProjectModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        const formData = new FormData(this);

        fetch('/api/update-user', { // Replace with your actual API endpoint
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token for security
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.hide();
                    fetchProjects(); // Refresh the project records
                    showAlert('success', 'User record updated successfully.');
                } else {
                    console.error('Error updating User details:', data.message);
                    modal.hide();
                    showAlert('message', data.message || 'Error updating User details.');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showAlert('error', 'Network error occurred.');
            });
    });


    // Function to show alerts
    function showAlert(type, message) {
        const alertBox = document.getElementById('responseAlert');
        const alertMessage = document.getElementById('alertMessage');
        alertBox.className = `alert alert-${type} alert-dismissible fade show`;
        alertMessage.textContent = message;
        alertBox.style.display = 'block';

        // Hide the alert after 3 seconds
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000);
    }

    // Function to fetch project records and populate the table
    function fetchProjects() {
        fetch('/api/get-users') // Replace with your API endpoint
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('project-table-body');
                tableBody.innerHTML = ''; // Clear existing rows

                data.forEach(user => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
        
          <td> ${formatText(user.xad_id)}</td>
          <td> ${formatText(user.first_name)}</td>
          <td>${formatText(user.last_name)}</td>
          <td>${formatText(user.email)}</td>
          <td>${formatText(user.phone_number)}</td>
          <td>${formatText(user.role)}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item edit-btn" data-userid="${user.id}" data-xadid="${user.xad_id}" data-firstname="${user.first_name}" data-lastname="${user.last_name}" data-phonenumber="${user.phone_number}" data-email="${user.email}" data-role="${user.role}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <a class="dropdown-item delete-btn" data-id="${user.id}"><i class="bx bx-trash me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        `;

                    tableBody.appendChild(row);
                });

                // Re-attach event listeners to the new edit and delete buttons
                attachButtonListeners();
            })
            .catch(error => {
                console.error('Error fetching projects:', error);
            });
    }

    // Function to handle form submission for adding a project

    // Function to handle editing and deleting projects
    function attachButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const userId = this.getAttribute('data-userid');
                const xadid = this.getAttribute('data-xadid');
                const userFistName = this.getAttribute('data-firstname');
                const userLastName = this.getAttribute('data-lastname');
                const email = this.getAttribute('data-email');
                const phonenumber = this.getAttribute('data-phonenumber');
                const role = this.getAttribute('data-role');

                console.log({
                    phonenumber
                })

                // Populate the modal fields
                document.getElementById('userid').value = userId;
                document.getElementById('xadid').value = xadid;
                document.getElementById('fistName').value = userFistName;
                document.getElementById('lastName').value = userLastName;
                document.getElementById('email').value = email;
                document.getElementById('phone_number').value = phonenumber === "null" ? '' : phonenumber;
                document.getElementById('role').value = role;


                // Show the edit modal
                const modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
                modal.show();
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const userId = this.getAttribute('data-id');

                // Confirm deletion with the user
                if (confirm('Are you sure you want to delete this project record?')) {
                    deleteProject(userId); // Call the function to delete the record
                }
            });
        });
    }

    // Function to delete a project record
    function deleteProject(userId) {
        fetch('/api/delete-user', { // Replace with your actual API endpoint
                method: 'POST',
                body: JSON.stringify({
                    user_id: userId
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
                    fetchProjects(); // Refresh the project list after deletion
                } else {
                    showAlert('danger', data.message || 'An error occurred while deleting the User record.');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showAlert('danger', 'A network error occurred. Please try again.');
            });
    }

    // Call fetchProjects on DOM content loaded
    document.addEventListener('DOMContentLoaded', fetchProjects);
</script>

@endsection