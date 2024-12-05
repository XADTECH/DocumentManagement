@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')


<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Planned Cash /</span> Planned Cash Report
</h4>

<div class="row">
  <div class="col-md-12">
    
    
      <!-- Alert box HTML -->
      <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
        <span id="alertMessage"></span>
        <button type="button" class="btn-close" aria-label="Close"></button>
      </div>

<div class="card">
  <h5 class="card-header">Report</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Project</th>
          <th>Opening Balance</th>
          <th>Planned Cash</th>
          <th>Received Amount</th>
          <th>Total Received Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
          @foreach($projects as $project)
            <tr>
                <td>
                    <!-- Custom icon and project display -->
                    <i class="bx bx-project bx-sm text-primary me-3"></i>
                    <span class="fw-medium">{{ $project['Project'] }}</span>
                </td>
                <td>{{ number_format($project['Opening Balance'], 0, '.', ',') }}</td>
                <td>{{ number_format($project['Planned Cash'], 0, '.', ',') }}</td>
                <td>{{ number_format($project['Received Amount'], 0, '.', ',') }}</td>
                <td>{{ number_format($project['Total Received'], 0, '.', ',') }}</td>
                <td>{{ number_format($project['Remaining Amount'], 0, '.', ',') }}</td>
	            <td>
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);">
                        <i class="bx bx-edit-alt me-1"></i> Edit
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <i class="bx bx-trash me-1"></i> Delete
                    </a>
                </div>
            </div>
        </td>
            </tr>
         @endforeach
    </tbody>
 
    </table>
  </div>
</div>

      <!-- /Notifications -->
    </div>
  </div>
</div>


<script>
  document.getElementById('plannedCashForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('/api/add-cash-receive', {
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
      const alertBox = document.getElementById('responseAlert');
      const alertMessage = document.getElementById('alertMessage');
      console.log(data);
      if (data.success) {
        document.getElementById('plannedCashForm').reset();
        alertBox.className = 'alert alert-success alert-dismissible fade show';
        alertMessage.textContent = data.success;
      } else {
        document.getElementById('plannedCashForm').reset();
        alertBox.className = 'alert alert-danger alert-dismissible fade show';
        alertMessage.textContent = data.message;
      }

      // Show the alert
      alertBox.style.display = 'block';

      // Hide the alert after 5 seconds
      setTimeout(() => {
        alertBox.style.display = 'none';
      }, 5000);
    });
  });




</script>

@endsection
