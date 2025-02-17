@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Department Management /</span> Add Department
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
            <h5 class="card-header">Add Department</h5>
            <!-- Account -->

            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('store-department') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="xad_id" class="form-label">Name</label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="please add department name ..." autofocus />
                        </div>
                    </div>
                
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>

    </div>
</div>



@endsection