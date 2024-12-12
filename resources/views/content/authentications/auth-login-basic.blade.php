@extends('layouts/blankLayout')

@section('title', 'Login - Document Management System')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
<style>
  .authentication-wrapper {
    display: flex;
    min-height: 100vh;
    background-color: #005693;
    color: #fff;
  }
  .authentication-inner {
    max-width: 900px;
    margin: auto;
    display: flex;
    background: #ffffff;
    box-shadow: 0px 6px 30px rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    overflow: hidden;
  }
  .auth-image-section {
    width: 50%;
    background: url('{{ asset('assets/img/dms/dms-image.jpg') }}') no-repeat center center;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .auth-image-section img {
    max-width: 100%;
    max-height: 80%;
    object-fit: contain;
  }
  .auth-form-section {
    width: 50%;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .card-header {
    text-align: center;
    margin-bottom: 2rem;
  }
  .app-brand img {
    height: 70px;
  }
  .btn-primary {
    background-color: #0067aa;
    border-color: #0067aa;
    border-radius: 6px;
  }
  .btn-primary:hover {
    background-color: #004c80;
    border-color: #004c80;
  }
  .form-control {
    border-radius: 6px;
  }
  .text-muted {
    font-size: 0.9rem;
  }
</style>
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper">
    <div class="authentication-inner">
      <!-- Left Section for Form -->
      <div class="auth-form-section">
        <div class="card-header">
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link">
              <img src="{{ asset('assets/img/xad/xad.jfif') }}" alt="Document Management System">
            </a>
          </div>
          <h4 class="mt-3">Welcome Back!</h4>
          <p class="mb-0 text-muted">Access your documents and manage efficiently</p>
        </div>
        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif
          @if (session('error'))
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
          @endif

          <form id="formAuthentication" action="{{ url('/login-user') }}" method="POST">
            @csrf
            <div class="mb-4">
              <label for="email" class="form-label">Email or Username</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email or username" autofocus required>
            </div>
            <div class="mb-4">
              <label for="password" class="form-label">Password</label>
              <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div>
              <a href="{{ url('auth/forgot-password-basic') }}" class="text-muted">Forgot Password?</a>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
          </form>

          <p class="text-center mt-4">
            <small>Powered by</small>
            <a href="{{ url('/') }}" class="text-primary">XAD Technology</a>
          </p>
        </div>
      </div>
      <!-- Right Section for Image -->
      <div class="auth-image-section" style="display: flex; align-items:center; justify-content:center; padding:20px">
        <img src="{{ asset('assets/img/dms/cartoon-girl-desk.jpg') }}" alt="Cartoon Girl Sitting on Desk">
      </div>
    </div>
  </div>
</div>
@endsection
