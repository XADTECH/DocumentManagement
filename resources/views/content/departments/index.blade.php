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

    .font_style {
        font-weight: bold;
    }

    #error-alert,
    #success-alert {
        transition: opacity 0.5s ease-out;
    }
</style>
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Department Management /</span>Departments
</h4>
<div class="row">
    <div class="col-md-12"> @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
        @endif
    </div>
</div>

<!-- Projects Table -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Departments List</h5>

    </div>

</div>

<div class="card mt-4">
    <div class="table-responsive text-nowrap  limited-scroll">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Sr,No</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="project-table-body" class="table-border-bottom-0">

                <tr>
                    <td style="color:#0067aa">1</td>
                    <td class="font_style">test</td>
                    <td class="font_style">action</td>

                </tr>
            </tbody>
        </table>
    </div>
</div>

<div style="margin-bottom:50px"></div>

@endsection