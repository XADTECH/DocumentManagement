<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\SubCategory;
use App\Models\DocumentType;
use Illuminate\Support\Facades\File; // Ensure this is imported


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

            

            return view('dashboard', compact('departments', 'ceoApprovalCount'));
        } else {
            // For other roles, filter by uploaded_by field
            $departments = Department::with(['documents' => function ($query) use ($user) {
                $query->where('uploaded_by', $user->id);
            }])->get();


            

            return view('dashboard', compact('departments'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
    
        // Initialize variables
        $departments = [];
        $subcategories = [];
        $documentTypes = [];
    
        // Check the user's role
        if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
            // Admin, Secretary, and CEO can access all departments, subcategories, and document types
            $departments = Department::all();
            $subcategories = Subcategory::all();
            $documentTypes = DocumentType::all();
        } else {
            // Other roles: Get departments, subcategories, and document types based on user's role
            $departments = Department::where('name', $user->role)->get(); // Filter departments by role
            $subcategories = Subcategory::whereIn('department_id', $departments->pluck('id'))->get(); // Get subcategories by department
            $documentTypes = DocumentType::whereIn('department_id', $departments->pluck('id'))->get(); // Get document types by department
        }
    
        // Pass data to the view
        return view('content.documents.add', [
            'departments' => $departments,
            'subcategories' => $subcategories,
            'documentTypes' => $documentTypes,
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:documents,name',
            'department_id' => 'required|exists:departments,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'document_type_id' => 'required',
            'files.*' => 'required|file|max:2048', 
        ]);

        // Fetch the names using the IDs
        $departmentName = Department::findOrFail($validatedData['department_id'])->name;
        $subcategoryName = Subcategory::findOrFail($validatedData['subcategory_id'])->name;
        $documentTypeName = DocumentType::findOrFail($validatedData['document_type_id'])->name;

        // Initialize file paths array
        $filePaths = [];

        // Loop through uploaded files and store them
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = "public/documents/{$departmentName}/{$subcategoryName}/{$documentTypeName}";
                // Ensure directory exists
                File::makeDirectory($path, 0777, true, true);
                // Save file and get the path
                $file->move($path, $originalName);
                $filePaths[] = "{$path}/{$originalName}";
            }
        }

        // Save the name with extension (using the last uploaded file for simplicity)
        $finalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
        . '.' . $file->getClientOriginalExtension();

        // Save document record in the database
        Document::create([
            'name' => $finalFileName,
            'file_paths' => json_encode($filePaths),
            'department_id' => $validatedData['department_id'],
            'subcategory_id' => $validatedData['subcategory_id'],
            'document_type_id' => $validatedData['document_type_id'],
            'uploaded_by' => auth()->id(),
            'ceo_approval' => $request->has('ceo_approval') ? 1 : 0,
            'approval_status' => 'Pending', // Default approval status
        ]);

        

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }


    public function showSubcategories($id)
    {
        // Fetch the department
        $department = Department::with(['subcategories.documents'])->findOrFail($id);

        // Fetch subcategories with document counts
        $subcategories = $department->subcategories()->withCount('documents')->get();

        return view('content.dashboard.dashboards-subcategories', compact('department', 'subcategories'));
    }

    //show documents
    public function showDocuments($id)
    {
        // Fetch the subcategory with its documents
        $subcategory = Subcategory::with('documents')->findOrFail($id);

        return view('content.dashboard.dashboards-show-documents', compact('subcategory'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}
