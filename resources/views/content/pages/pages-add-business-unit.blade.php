@extends('layouts/contentNavbarLayout')

@section('title', 'Account Settings - Pages')

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Project Budgeting /</span> Add Business Unit
</h4>

<div class="row">
  <div class="col-md-12">
    
    <!-- Alert box HTML -->
    <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
      <span id="alertMessage"></span>
      <button type="button" class="btn-close" aria-label="Close"></button>
    </div>

    <!-- Business Unit Form -->
    <div class="card">
      <div class="card-body">
        <h6>Add A Business Unit</h6>
        <form id="businessunitForm">
          <div class="row">
            <div class="col-sm-6">
              <select class="form-select" name="source" required>
              <option value="outsource">outsource</option>
              <option value="none outsource">none outsource</option>
              <option value="Other">other</option>
              </select>
            </div>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="unitdetail" placeholder="Enter a Unit Detail" />
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-sm-6">
              <input type="text" class="form-control" name="unitremark" placeholder="Remarks" />
            </div>
            <div class="col-sm-6">
              <select class="form-control" name="status" required>
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


<div class="card mt-4">
      <h5 class="card-header">Buniess Units</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Source</th>
              <th>Details</th>
              <th>Remarks</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="business-unit-table-body" class="table-border-bottom-0">
            <!-- Business unit rows will be added here -->
          </tbody>
        </table>
      </div>
    </div>

<!-- Edit Business Unit Modal -->
<div class="modal fade" id="editBusinessUnitModal" tabindex="-1" aria-labelledby="editBusinessUnitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBusinessUnitModalLabel">Edit Business Unit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editBusinessUnitForm">
          <input type="hidden" id="businessUnitId" name="business_unit_id">
          <div class="mb-3">
            <label for="businessUnitSource" class="form-label">Source</label>
            <select class="form-select" id="businessUnitSource" name="source" required>
              <option value="outsource">outsource</option>
              <option value="Other">Other</option>
              <option value="none outsource">none outsource</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="businessUnitDetail" class="form-label">Detail</label>
            <input type="text" class="form-control" id="businessUnitDetail" name="unitdetail" >
          </div>
          <div class="mb-3">
            <label for="businessUnitRemark" class="form-label">Remarks</label>
            <input type="text" class="form-control" id="businessUnitRemark" name="unitremark">
          </div>
          <div class="mb-3">
            <label for="businessUnitStatus" class="form-label">Status</label>
            <select class="form-select" id="businessUnitStatus" name="status">
              <option value="Active">Active</option>
              <option value="Non Active">Not Active</option>
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
  fetchBusinessUnits();
});

// Function to format text (used in the table)
function formatText(text) {
  return text || 'Not Entered';
}

// Handle form submission for adding a business unit
document.getElementById('businessunitForm').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);

  fetch('/api/add-business-unit', {
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
      showAlert('success', 'Business unit added successfully.');
      fetchBusinessUnits(); // Refresh the business unit list
      this.reset();
    } else {
      showAlert('danger', data.message);
      this.reset();
    }
  })
  .catch(error => {
    console.error('Network error:', error);
    showAlert('danger', 'A network error occurred. Please try again.');
  });
});

// Function to fetch business units and populate the table
function fetchBusinessUnits() {
  fetch('/api/get-business-units')
    .then(response => response.json())
    .then(data => {
      const tableBody = document.getElementById('business-unit-table-body');
      tableBody.innerHTML = ''; // Clear existing rows

      data.forEach(unit => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td><i class="bx bxl-angular bx-sm text-danger me-3"></i>${formatText(unit.source)}</td>
          <td>${formatText(unit.unitdetail)}</td>
          <td>${formatText(unit.unitremark)}</td>
          <td>${formatText(unit.status)}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item edit-btn" data-id="${unit.id}" data-source="${unit.source}" data-detail="${unit.unitdetail}" data-remark="${unit.unitremark}" data-status="${unit.status}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <a class="dropdown-item delete-btn" data-id="${unit.id}"><i class="bx bx-trash me-1"></i> Delete</a>
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
      console.error('Error fetching business units:', error);
    });
}

// Handle form submission for editing a business unit
document.getElementById('editBusinessUnitForm').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);
  
  fetch('/api/update-business-unit', {
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
      console.log('Business unit details updated successfully:', data);
      const modal = bootstrap.Modal.getInstance(document.getElementById('editBusinessUnitModal'));
      modal.hide();
      fetchBusinessUnits(); // Refresh the business unit list
      showAlert('success', 'Business unit updated successfully.');
    } else {
      console.error('Error updating business unit:', data.message);
      showAlert('message', data.message || 'Error updating business unit.');
    }
  })
  .catch(error => {
    console.error('Network error:', error);
    showAlert('danger', 'Network error occurred.');
  });
});

// Handle button clicks for editing and deleting
function attachButtonListeners() {
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      const unitId = this.getAttribute('data-id');
      const unitSource = this.getAttribute('data-source');
      const unitDetail = this.getAttribute('data-detail');
      const unitRemark = this.getAttribute('data-remark');
      const unitStatus = this.getAttribute('data-status');

      // Populate the modal fields
      document.getElementById('businessUnitId').value = unitId;
      document.getElementById('businessUnitSource').value = unitSource;
      document.getElementById('businessUnitDetail').value = formatText(unitDetail) === 'null' ? 'Not Entered' :  formatText(unitDetail); 
      document.getElementById('businessUnitRemark').value = formatText(unitRemark) === 'null' ? 'Not Entered' :  formatText(unitRemark);  
      document.getElementById('businessUnitStatus').value = unitStatus;

      // Show the edit modal
      const modal = new bootstrap.Modal(document.getElementById('editBusinessUnitModal'));
      modal.show();
    });
  });

  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      const unitId = this.getAttribute('data-id');

      if (confirm('Are you sure you want to delete this business unit?')) {
        deleteBusinessUnit(unitId);
      }
    });
  });
}

// Function to delete a business unit record
function deleteBusinessUnit(unitId) {
  fetch('/api/delete-business-unit', {
    method: 'POST',
    body: JSON.stringify({ business_unit_id: unitId }),
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
      showAlert('success', 'Business unit deleted successfully.');
      fetchBusinessUnits(); // Refresh the business unit list after deletion
    } else {
      showAlert('message', data.message || 'An error occurred while deleting the business unit.');
    }
  })
  .catch(error => {
    console.error('Network error:', error);
    showAlert('danger', 'A network error occurred. Please try again.');
  });
}

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
</script>

@endsection
