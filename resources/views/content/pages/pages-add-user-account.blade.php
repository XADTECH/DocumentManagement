@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">User Management /</span> Add User
</h4>

<style>
  .alert {
    opacity: 1;
    transition: opacity 0.6s ease-out;
    background-color: #0067ab;
    color: white;
    text-align: left;
  }
</style>

<div class="row">
  <div class="col-md-12">
    <!-- Alert box HTML -->
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

    <!-- <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-bell me-1"></i> Notifications</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections')}}"><i class="bx bx-link-alt me-1"></i> Connections</a></li>
    </ul> -->
    <div class="card mb-4">
      <h5 class="card-header">User Details</h5>
      <!-- Account -->

      <div class="card-body">
        <form id="formAccountSettings" method="POST" enctype="multipart/form-data" action="{{url('add-user')}}">

          @csrf
          <div class="row">
            <div class="card-body">
              <div class="d-flex align-items-start align-items-sm-center gap-4">
                <img src="{{asset('assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                <div class="button-wrapper">
                  <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Upload new photo</span>
                    <i class="bx bx-upload d-block d-sm-none"></i>
                    <input type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" name="profile_image" />
                  </label>
                  <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                    <i class="bx bx-reset d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Reset</span>
                  </button>

                  <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                </div>
              </div>
            </div>
            <hr class="my-0">
            <div class="mb-3 col-md-6">
              <label for="xad_id" class="form-label">XAD ID</label>
              <input class="form-control" type="text" id="xad_id" name="xad_id" value="" autofocus />
            </div>
            <div class="mb-3 col-md-6">
              <label for="first_name" class="form-label">First Name</label>
              <input class="form-control" type="text" id="first_name" name="first_name" value="" placeholder="John" autofocus />
            </div>
            <div class="mb-3 col-md-6">
              <label for="last_name" class="form-label">Last Name</label>
              <input class="form-control" type="text" name="last_name" id="last_name" value="" placeholder="Doe" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input class="form-control" type="text" id="email" name="email" value="" placeholder="john.doe@example.com" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="organization_unit" class="form-label">organization_unit Unit</label>
              <input type="text" class="form-control" id="organization_unit" name="organization_unit" placeholder="Finance / Admin" value="" />
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="phone_number">Phone Number</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text">UAE (+971)</span>
                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="050 123 567" />
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control" placeholder="202 555 0111" />
              </div>
            </div>
            <div class="mb-3 col-md-6">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm password" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" id="address" name="address" placeholder="Address" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="address" class="form-label">Role</label>
              <select name="role" class="form-control" required>
                @foreach(App\Models\User::roles() as $role)
                <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3 col-md-6">
              <label for="nationality" class="form-label">Nationality</label>
              <input type="text" class="form-control" id="nationality" name="nationality" placeholder="pakistan" maxlength="6" />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Permissions</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="permissions[]" value="Project Management" id="permission-project-management">
              <label class="form-check-label" for="permission-project-management">Project Management</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="permissions[]" value="Cash Flow Management" id="permission-cash-flow-management">
              <label class="form-check-label" for="permission-cash-flow-management">Cash Flow Management</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="permissions[]" value="Bank Management" id="permission-bank-management">
              <label class="form-check-label" for="permission-bank-management">Bank Management</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="permissions[]" value="User Management" id="permission-user-management">
              <label class="form-check-label" for="permission-user-management">User Management</label>
            </div>
          </div>

          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
            <input type="hidden" name="profile_image" id="profileImage">
            <!-- <button type="reset" class="btn btn-outline-secondary">Cancel</button> -->
          </div>

        </form>
      </div>
      <!-- /Account -->
    </div>
    <!-- <div class="card">
      <h5 class="card-header">Delete Account</h5>
      <div class="card-body">
        <div class="mb-3 col-12 mb-0">
          <div class="alert alert-warning">
            <h6 class="alert-heading fw-medium mb-1">Are you sure you want to delete your account?</h6>
            <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
          </div>
        </div>
        <form id="formAccountDeactivation" onsubmit="return false">
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
            <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
          </div>
          <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
        </form>
      </div>
    </div> -->
  </div>
</div>

<script>
  //hide alert 
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
</script>
@endsection