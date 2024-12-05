@extends('layouts/contentNavbarLayout')

@section('title', 'Project Budgeting - Pages')

@section('content')

<style>
.limited-scroll {
  max-height: 200px; /* Set the maximum height as needed */
  overflow-y: auto;  /* Adds a vertical scrollbar when content overflows */
  display: block;    /* Ensures the scrollbar is visible on the tbody */
}
</style>
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Project Budgeting /</span> Add Project Name
</h4>

<div class="row">
  <div class="col-md-12">
    
    <!-- Alert box HTML -->
    <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
      <span id="alertMessage"></span>
      <button type="button" class="btn-close" aria-label="Close"></button>
    </div>

    <!-- Project Form -->
    <div class="card">
      <div class="card-body">
        <h6>Add A Project Name</h6>
        <form id="projectForm">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" class="form-control" name="projectname" placeholder="Enter Project Name" required />
            </div>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="projectdetail" placeholder="Enter Project Detail"  />
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-sm-6">
              <input type="text" class="form-control" name="projectremark" placeholder="Remarks"  />
            </div>
            <div class="col-sm-6">
              <select class="form-select" name="status">
                <option value="Active">Active</option>
                <option value="Non Active">Not Active</option>
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
      <h5 class="card-header">List</h5>
      <div class="table-responsive text-nowrap  limited-scroll">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Details</th>
              <th>Remarks</th>
              <th>Status</th>
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

  fetch('/api/update-project', { // Replace with your actual API endpoint
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
      console.log('Project details updated successfully:', data);
      modal.hide();
      fetchProjects(); // Refresh the project records
      showAlert('success', 'Project record updated successfully.');
    } else {
      console.error('Error updating project details:', data.message);
      modal.hide();
      showAlert('message', data.message || 'Error updating project details.');
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
  fetch('/api/get-projects') // Replace with your API endpoint
    .then(response => response.json())
    .then(data => {
      const tableBody = document.getElementById('project-table-body');
      tableBody.innerHTML = ''; // Clear existing rows

      data.forEach(project => {
        const row = document.createElement('tr');

        row.innerHTML = `
        
          <td><i class="bx bxl-angular bx-sm text-danger me-3"></i> ${formatText(project.name)}</td>
          <td>${formatText(project.projectdetail)}</td>
          <td>${formatText(project.projectremark)}</td>
          <td>${formatText(project.status)}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item edit-btn" data-id="${project.id}" data-name="${project.name}" data-details="${project.projectdetail}" data-remarks="${project.projectremark}" data-status="${project.status}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <a class="dropdown-item delete-btn" data-id="${project.id}"><i class="bx bx-trash me-1"></i> Delete</a>
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
document.getElementById('projectForm').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);

  fetch('/api/add-project', { // Replace with your actual API endpoint
    method: 'POST',
    body: formData,
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showAlert('success', data.success);
      fetchProjects(); // Refresh the project list
      this.reset();
    } else {
      showAlert('danger', data.message);
    }
  })
  .catch(error => {
    console.error('Network error:', error);
    showAlert('danger', 'A network error occurred. Please try again.');
  });
});

// Function to handle editing and deleting projects
function attachButtonListeners() {
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      const projectId = this.getAttribute('data-id');
      const projectName = this.getAttribute('data-name');
      const projectDetails = this.getAttribute('data-details');
      const projectRemarks = this.getAttribute('data-remarks');
      const projectStatus = this.getAttribute('data-status');

     

      // Populate the modal fields
      document.getElementById('projectId').value = projectId;
      document.getElementById('projectName').value = projectName;
      document.getElementById('projectDetails').value = projectDetails === 'null' ? "Not Entered" : projectDetails
      document.getElementById('projectRemarks').value = projectRemarks === 'null' ? "Not Entered" :projectRemarks
      document.getElementById('projectStatus').value = projectStatus;

      // Show the edit modal
      const modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
      modal.show();
    });
  });

  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      const projectId = this.getAttribute('data-id');

      // Confirm deletion with the user
      if (confirm('Are you sure you want to delete this project record?')) {
        deleteProject(projectId); // Call the function to delete the record
      }
    });
  });
}

// Function to delete a project record
function deleteProject(projectId) {
  fetch('/api/delete-project', { // Replace with your actual API endpoint
    method: 'POST',
    body: JSON.stringify({ project_id: projectId }),
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
      showAlert('danger', data.message || 'An error occurred while deleting the project record.');
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
