<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class Analytics extends Controller
{
  public function index()
  {
    $departments = Department::all();
    return view('content.dashboard.dashboards-analytics', compact('departments'));
  }
}