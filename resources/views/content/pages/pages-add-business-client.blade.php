@extends('layouts/contentNavbarLayout')

@section('title', 'Account Settings - Business Clients')

@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Project Budgeting /</span> Add Business Client
</h4>

<div class="row">
  <div class="col-md-12">
    
    <!-- Alert box HTML -->
    <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
      <span id="alertMessage"></span>
      <button type="button" class="btn-close" aria-label="Close"></button>
    </div>

    <!-- Business Client Form -->
    <div class="card">
      <div class="card-body">
        <h6>Add A Business Client</h6>
        <form id="businessClientForm">
          <div class="row">
            <div class="col-sm-6">
              <input type="text" class="form-control" name="clientname" placeholder="Enter Client Name" required />
            </div>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="clientdetail" placeholder="Enter Client Detail"  />
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-sm-6">
              <input type="text" class="form-control" name="clientremark" placeholder="Enter Client Remark"  />
            </div>
            <div class="col-sm-6">
              <select class="form-control" name="status" >
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Business Clients Table -->
    <div class="card mt-4">
      <h5 class="card-header">Client List</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Detail</th>
              <th>Remark</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="business-client-table-body" class="table-border-bottom-0">
            <!-- Business client rows will be added here -->
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Edit Business Client Modal -->
<div class="modal fade" id="editBusinessClientModal" tabindex="-1" aria-labelledby="editBusinessClientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBusinessClientModalLabel">Edit Business Client Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editBusinessClientForm">
          <input type="hidden" id="businessClientId" name="client_id">
          <div class="mb-3">
            <label for="clientName" class="form-label">Client Name</label>
            <input type="text" class="form-control" id="clientname" name="clientname" required>
          </div>
          <div class="mb-3">
            <label for="clientEmail" class="form-label">Detail</label>
            <input type="text" class="form-control" id="clientdetail" name="clientdetail" >
          </div>
          <div class="mb-3">
            <label for="clientPhone" class="form-label">Remarks</label>
            <input type="text" class="form-control" id="clientremark" name="clientremark" >
          </div>
          <div class="mb-3">
            <label for="clientStatus" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
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
  fetchBusinessClients();
});

function formatText(text) {
  return text || 'Not Entered';
}

document.getElementById('businessClientForm').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);

  fetch('/api/business-clients', {
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
      showAlert('success', 'Business client added successfully.');
      fetchBusinessClients();
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

function fetchBusinessClients() {
  fetch('/api/business-clients')
    .then(response => response.json())
    .then(data => {
      const tableBody = document.getElementById('business-client-table-body');
      tableBody.innerHTML = '';

      data.forEach(client => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td><i class="bx bxl-angular bx-sm text-danger me-3"></i>${formatText(client.clientname)}</td>
          <td>${formatText(client.clientdetail)}</td>
          <td>${formatText(client.clientremark)}</td>
          <td>${formatText(client.status)}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item edit-btn" data-id="${client.id}" data-name="${client.clientname}" data-detail="${client.clientdetail}" data-remark="${client.clientremark}" data-status="${client.status}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <a class="dropdown-item delete-btn" data-id="${client.id}"><i class="bx bx-trash me-1"></i> Delete</a>
              </div>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
      });

      attachButtonListeners();
    })
    .catch(error => {
      console.error('Error fetching business clients:', error);
    });
}

document.getElementById('editBusinessClientForm').addEventListener('submit', function(event) {
  event.preventDefault();
  const formData = new FormData(this);

  fetch('/api/update-business-clients', {
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
      const modal = bootstrap.Modal.getInstance(document.getElementById('editBusinessClientModal'));
      modal.hide();
      fetchBusinessClients();
      showAlert('success', 'Business client updated successfully.');
    } else {
      showAlert('danger', data.message || 'Error updating business client.');
    }
  })
  .catch(error => {
    console.error('Network error:', error);
    showAlert('danger', 'Network error occurred.');
  });
});

function attachButtonListeners() {
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
      const clientId = this.getAttribute('data-id');
      const clientName = this.getAttribute('data-name');
      const clientDetail = this.getAttribute('data-detail');
      const clientRemark = this.getAttribute('data-remark');
      const status = this.getAttribute('data-status');

      document.getElementById('businessClientId').value = clientId;
      document.getElementById('clientname').value = formatText(clientName);
      document.getElementById('clientdetail').value = (clientDetail === null || clientDetail === undefined || clientDetail === '' || clientDetail === 'null') ? "Not Entered" : clientDetail;
      document.getElementById('clientremark').value = (clientRemark === null || clientRemark === undefined || clientRemark === '' || clientRemark === 'null') ? "Not Entered" : clientRemark;

      document.getElementById('status').value = formatText(status);

      const modal = new bootstrap.Modal(document.getElementById('editBusinessClientModal'));
      modal.show();
    });
  });

  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
      const clientId = this.getAttribute('data-id');
      console.log("hi")
      fetch('/api/delete-business-clients', {
        body: JSON.stringify({ client_id: clientId }),
        method: 'POST',
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
          fetchBusinessClients();
          showAlert('success', 'Business client deleted successfully.');
        } else {
          showAlert('danger', data.message || 'Error deleting business client.');
        }
      })
      .catch(error => {
        console.error('Network error:', error);
        showAlert('danger', 'Network error occurred.');
      });
    });
  });
}

function showAlert(type, message) {
  const alertBox = document.getElementById('responseAlert');
  alertBox.className = 'alert alert-' + type + ' alert-dismissible fade show';
  document.getElementById('alertMessage').textContent = message;
  alertBox.style.display = 'block';

  setTimeout(() => {
    alertBox.style.display = 'none';
  }, 3000);
}

</script>

@endsection
