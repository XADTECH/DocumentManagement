<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth; // Import the Auth facade


class Analytics extends Controller
{
  public function index()
  {
      $user = Auth::user();

      // Admin, Secretary, and CEO can see all departments and documents
      if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
          $departments = Department::with(['documents' => function ($query) {
              $query->select('id', 'name', 'department_id', 'ceo_approval', 'approval_status');
          }])->get();

          // Count documents requiring CEO approval
          $ceoApprovalCount = $departments->flatMap->documents->filter(function ($doc) {
              return $doc->ceo_approval && $doc->approval_status === 'pending';
          })->count();

          

          return view('content.dashboard.dashboards-analytics', compact('departments', 'ceoApprovalCount'));
      } else {
        $departments = Department::with(['documents' => function ($query) use ($user) {
          $query->where('uploaded_by', $user->id);
      }])->whereHas('documents', function ($query) use ($user) {
          $query->where('uploaded_by', $user->id);
      })->get();

          return view('content.dashboard.dashboards-analytics', compact('departments'));
      }
  }
}