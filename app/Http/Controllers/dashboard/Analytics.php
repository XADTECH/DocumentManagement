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

        if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
            // Admin, Secretary, and CEO can see all documents
            $departments = Department::with([
                'documents' => function ($query) {
                    $query->select('id', 'name', 'department_id', 'subcategory_id', 'file_paths', 'uploaded_by', 'ceo_approval', 'approval_status');
                },
            ])->get();


            // Count documents requiring CEO approval
            $ceoApprovalCount = $departments->flatMap->documents
                ->filter(function ($doc) {
                    return $doc->ceo_approval && $doc->approval_status === 'Pending';
                })
                ->sum(function ($doc) {
                    $filePaths = json_decode($doc->file_paths, true);
                    return is_array($filePaths) ? count($filePaths) : 0;
                });

            // Count files approved by the CEO
            $ceoApprovedCount = $departments->flatMap->documents
                ->filter(function ($doc) {
                    return !$doc->ceo_approval && $doc->approval_status === 'Approved';
                })
                ->sum(function ($doc) {
                    $filePaths = json_decode($doc->file_paths, true);
                    return is_array($filePaths) ? count($filePaths) : 0;
                });

            // Count files rejected by the CEO
            $ceoRejectedCount = $departments->flatMap->documents
                ->filter(function ($doc) {
                    return !$doc->ceo_approval && $doc->approval_status === 'Rejected';
                })
                ->sum(function ($doc) {
                    $filePaths = json_decode($doc->file_paths, true);
                    return is_array($filePaths) ? count($filePaths) : 0;
                });

            return view('content.dashboard.dashboards-analytics', compact('departments', 'ceoApprovalCount', 'ceoApprovedCount', 'ceoRejectedCount'));
        } else {
            // For other users, fetch only their uploaded documents
            $departments = Department::with([
                'documents' => function ($query) use ($user) {
                    $query
                        ->where(function ($q) use ($user) {
                            $q->where('uploaded_by', $user->id)->orWhere(function ($subQuery) use ($user) {
                                $subQuery
                                    ->where('ceo_approval', true)
                                    ->where('approval_status', 'Pending')
                                    ->where('uploaded_by', $user->id);
                            });
                        })
                        ->select('id', 'name', 'department_id', 'subcategory_id', 'file_paths', 'uploaded_by', 'ceo_approval', 'approval_status');
                },
            ])
                ->where('name', $user->role) // Filter departments based on the user's role
                ->get();

            // Count documents requiring CEO approval for this user
            $ceoApprovalCount = $departments->flatMap->documents
                ->filter(function ($doc) {
                    return $doc->ceo_approval && $doc->approval_status === 'Pending';
                })
                ->count();

            // Count documents approved by the CEO for this user
            $ceoApprovedCount = $departments->flatMap->documents
                ->filter(function ($doc) {
                    return !$doc->ceo_approval && $doc->approval_status === 'Approved';
                })
                ->count();

            // Count documents rejected by the CEO for this user
            $ceoRejectedCount = $departments->flatMap->documents
                ->filter(function ($doc) {
                    return !$doc->ceo_approval && $doc->approval_status === 'Rejected';
                })
                ->count();

            return view('content.dashboard.dashboards-analytics', compact('departments', 'ceoApprovalCount', 'ceoApprovedCount', 'ceoRejectedCount'));
        }
    }
}
