@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')


<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Planned Cash /</span> Add opening balance
</h4>

<h5>Opening Balance: <span id="opening-balance"> </span></h5>

<div class="row">
  <div class="col-md-12">
    
    
      <!-- Alert box HTML -->
      <div id="responseAlert" class="alert alert-info alert-dismissible fade show" role="alert" style="display: none; width:80%; margin:10px auto">
        <span id="alertMessage"></span>
        <button type="button" class="btn-close" aria-label="Close"></button>
      </div>


      <div class="card">
      <div class="card-body">
        <h6>Enter the opening balance for a cash flow </h6>
        <form id="openingBalanceForm">
          <div class="row">
            <div class="col-sm-6">
              <input  type="text" class="form-select" name="openingbalance" placeholder="Enter Opening Balance AED" required/>
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

document.addEventListener('DOMContentLoaded', function() {
             fetchOpeningBalance();
        });


  document.getElementById('openingBalanceForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('/api/add-opening-balance', {
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
      if (data.success) {
        alertBox.className = 'alert alert-success alert-dismissible fade show';
        alertMessage.textContent = data.success;
        this.reset();
        fetchOpeningBalance() 
      } else {
        alertBox.className = 'alert alert-danger alert-dismissible fade show';
        alertMessage.textContent = data.message;
        this.reset();

      }

      // Show the alert
      alertBox.style.display = 'block';

      // Hide the alert after 5 seconds
      setTimeout(() => {
        alertBox.style.display = 'none';
      }, 3000);
    });
  });


  function fetchOpeningBalance() 
  {
      fetch('/api/get-opening-balance')
      .then(response => response.json())
      .then(data => {
      if (data.opening_balance !== undefined) {
        document.getElementById('opening-balance').textContent = parseFloat(data.opening_balance).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " AED";
      } else {
                console.error('Opening balance not found');
          }
            })
              .catch(error => {
                  console.error('Error fetching opening balance:', error);
            });
  }

</script>

@endsection
