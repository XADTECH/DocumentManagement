@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')


<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Planned Cash /</span> Allocate Planned Cash
</h4>

<div class="row">
  <div class="col-md-12">
    
    
      <!-- Alert box HTML -->
      <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
        <span id="alertMessage"></span>
        <button type="button" class="btn-close" aria-label="Close"></button>
      </div>


      <div class="card">
      <div class="card-body">
        <h6>Enter the Amount for a Planned Cash </h6>
        <form id="plannedCashForm">
          <div class="row">
            <div class="col-sm-6">
              <!-- Dropdown for selecting options -->
                <select class="form-select" name="project_id">
                    <option value="" disabled selected>Select Project</option>
                    <option value="1">ADIB</option>
                    <option value="2">RDCB</option>
                    <option value="3">Etisalat</option>
                    <option value="4">DU</option>
                </select>

      <!-- Text input for entering planned cash amount -->
      <input type="text" class="form-control mt-3" name="plancash" placeholder="Enter planned cash amount AED" />
            </div>
            
            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-2">Submit</button>
            </div>
          </div>
        </form>
      </div>

      </div>

      <!-- /Notifications -->
    </div>
  </div>
</div>


<script>
 document.getElementById('plannedCashForm').addEventListener('submit', function(event) {
  event.preventDefault();

  // Get the value of the 'plancash' field
  const plancashField = document.querySelector('input[name="plancash"]');
  const plancashValue = plancashField.value.trim();

  // Validate the 'plancash' field
  if (plancashValue === '') {
    const alertBox = document.getElementById('responseAlert');
    const alertMessage = document.getElementById('alertMessage');

    alertBox.className = 'alert alert-danger alert-dismissible fade show';
    alertMessage.textContent = 'Please enter a planned cash amount.';
    alertBox.style.display = 'block';

    // Hide the alert after 5 seconds
    setTimeout(() => {
      alertBox.style.display = 'none';
    }, 5000);

    return; // Exit the function if validation fails
  }

  // If validation passes, proceed with form submission
  const formData = new FormData(this);

  fetch('/api/add-cash-plan', {
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
