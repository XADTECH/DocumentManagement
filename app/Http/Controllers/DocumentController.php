<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use App\Models\SubCategory;
use App\Models\DocumentType;
use Illuminate\Support\Facades\File; // Ensure this is imported
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin, Secretary, and CEO can see all departments and documents
        if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
            $departments = Department::with([
                'documents' => function ($query) {
                    $query->select('id', 'name', 'department_id', 'ceo_approval', 'approval_status');
                },
            ])->get();

            // Count documents requiring CEO approval
            $ceoApprovalCount = $departments->flatMap->documents
                ->filter(function ($doc) {
                    return $doc->ceo_approval && $doc->approval_status === 'pending';
                })
                ->count();

            return view('dashboard', compact('departments', 'ceoApprovalCount'));
        } else {
            // For other roles, filter by uploaded_by field
            $departments = Department::with([
                'documents' => function ($query) use ($user) {
                    $query->where('uploaded_by', $user->id);
                },
            ])->get();

            return view('dashboard', compact('departments'));
        }
    }

   

    // Show the document details
    public function show($id)
    {
        // Fetch the document along with relationships
        $document = Document::with(['department', 'user', 'subcategory', 'documentType'])->findOrFail($id);

        return view('content.dashboard.dashboard-document-detail', compact('document'));
    }

    //show the document detail another way
    public function showDetail($id)
{
    // Set the page title
    $title = 'Document Details';

    // Fetch the specific document by ID along with its relationships
    $document = Document::with(['user', 'department'])->find($id);

    if (!$document) {
        // Handle the case where the document is not found
        return redirect()->back()->with('error', 'Document not found.');
    }

    // Check if the user has permission to view the document
    $user = auth()->user(); // Assuming you're using Laravel's auth system

    if (!in_array($user->role, ['Admin', 'Secretary', 'CEO']) && $document->uploaded_by !== $user->id) {
        // Redirect if the user is not authorized to view the document
        return redirect()->back()->with('error', 'You do not have permission to view this document.');
    }

    // Pass the document and title to the view
    return view('content.dashboard.documents-detail', compact('document', 'title'));
}


    public function updateStatus(Request $request)
    {
        // Validate the request input
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'status' => 'required|in:Approved,Pending,Rejected',
            'remarks' => 'nullable|string|max:255', // Validate remarks
        ]);

        // Retrieve the document using the provided document ID
        $document = Document::findOrFail($request->input('document_id'));

        // Update the document fields based on the status
        switch ($request->input('status')) {
            case 'Approved':
                $document->approval_status = 'Approved';
                $document->ceo_approval = 0;
                break;
            case 'Pending':
                $document->approval_status = 'Pending';
                $document->ceo_approval = 1;
                break;
            case 'Rejected':
                $document->approval_status = 'Rejected';
                $document->ceo_approval = 0;
                break;
        }

        // Update remarks field
        $document->remarks = $request->input('remarks');

        // Save the updated document
        $document->save();

        // Redirect to the specific route with a success message
        return redirect()->route('dashboard-analytics')->with('success', 'Document status and remarks updated successfully.');
    }

    public function destroy($id)
    {
        try {
            // Find the document by ID
            $document = Document::findOrFail($id);

            // Decode the file paths stored in the database
            $filePaths = json_decode($document->file_path);

            // Ensure file paths are valid
            if (!empty($filePaths)) {
                foreach ($filePaths as $filePath) {
                    // Use public_path or Storage to locate files
                    $fullPath = public_path($filePath); // Adjust this as needed
                    if (file_exists($fullPath)) {
                        unlink($fullPath); // Delete file from the filesystem
                    } else {
                        // Optional: Log file not found for debugging
                        \Log::warning("File not found: $fullPath");
                    }
                }
            }

            // Delete the document record from the database
            $document->delete();

            // Set a success message
            Session::flash('success', 'Document and associated files deleted successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error deleting document: ' . $e->getMessage());

            // Set an error message
            Session::flash('error', 'An error occurred while deleting the document and files.');
        }

        // Redirect to the dashboard-analytics route
        return redirect()->route('dashboard-analytics');
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

    //store the document
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
        $finalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $file->getClientOriginalExtension();

        // Save document record in the database
        Document::create([
            'name' => $request->input('name'),
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

    //show all catgories and doucment count
    public function showSubcategories($id)
    {
        $user = Auth::user();

        // Fetch the department
        $department = Department::findOrFail($id);

        // Check if the user is Admin, CEO, or Secretary
        if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
            // Fetch all subcategories and calculate total files for each
            $subcategories = $department->subcategories->map(function ($subcategory) {
                $totalFiles = $subcategory->documents->reduce(function ($carry, $document) {
                    $filePaths = json_decode($document->file_paths, true);
                    return $carry + (is_array($filePaths) ? count($filePaths) : 0);
                }, 0);

                $subcategory->total_files = $totalFiles; // Add a dynamic property for the total files
                return $subcategory;
            });
        } else {
            // Fetch subcategories with only the documents uploaded by the current user
            $subcategories = $department->subcategories->map(function ($subcategory) use ($user) {
                $filteredDocuments = $subcategory->documents->filter(function ($document) use ($user) {
                    return $document->uploaded_by === $user->id;
                });

                $totalFiles = $filteredDocuments->reduce(function ($carry, $document) {
                    $filePaths = json_decode($document->file_paths, true);
                    return $carry + (is_array($filePaths) ? count($filePaths) : 0);
                }, 0);

                $subcategory->total_files = $totalFiles; // Add a dynamic property for the total files
                return $subcategory;
            });
        }

        return view('content.dashboard.dashboards-subcategories', compact('department', 'subcategories'));
    }

    //show all documents in a view
    public function showDocuments($id)
    {
        // Fetch the document type by ID
        $documentType = DocumentType::findOrFail($id);

        // Check if the logged-in user has a role of Admin, Secretary, or CEO
        if (
            auth()
                ->user()
                ->hasRole(['Admin', 'Secretary', 'CEO'])
        ) {
            // Fetch all documents for the document type
            $documents = Document::where('document_type_id', $id)->get();
        } else {
            // Fetch only documents uploaded by the logged-in user
            $documents = Document::where('document_type_id', $id)->where('uploaded_by', auth()->id())->get();
        }

        return view('content.dashboard.dashboards-show-documents', compact('documentType', 'documents'));
    }

    //show all document types and count
    public function showDocumentTypes(Subcategory $subcategory)
    {
        $user = Auth::user();

        if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
            // Admin, Secretary, and CEO see all document types and their related files
            $documentTypes = DocumentType::where('subcategory_id', $subcategory->id)
                ->with([
                    'documents' => function ($query) {
                        $query->select('id', 'name', 'file_paths', 'document_type_id', 'uploaded_by', 'subcategory_id');
                    },
                ])
                ->get();

            // Calculate total files for each document type
            $documentTypes = $documentTypes->map(function ($documentType) {
                $totalFiles = $documentType->documents->reduce(function ($carry, $document) {
                    $filePaths = json_decode($document->file_paths, true);
                    return $carry + (is_array($filePaths) ? count($filePaths) : 0);
                }, 0);

                $documentType->total_files = $totalFiles; // Add a dynamic property for total files
                return $documentType;
            });
            // return response()->json($documentTypes);
        } else {
            // Other users see only their own uploaded documents for each document type
            $documentTypes = DocumentType::where('subcategory_id', $subcategory->id)
                ->with([
                    'documents' => function ($query) use ($user) {
                        $query->where('uploaded_by', $user->id)->select('id', 'name', 'file_paths', 'document_type_id', 'uploaded_by', 'subcategory_id');
                    },
                ])
                ->get();

            // Calculate total files for each document type
            $documentTypes = $documentTypes->map(function ($documentType) {
                $totalFiles = $documentType->documents->reduce(function ($carry, $document) {
                    $filePaths = json_decode($document->file_paths, true);
                    return $carry + (is_array($filePaths) ? count($filePaths) : 0);
                }, 0);

                $documentType->total_files = $totalFiles; // Add a dynamic property for total files
                return $documentType;
            });
        }

        return view('content.dashboard.dashboard-document-types', compact('subcategory', 'documentTypes'));
    }

   


    public function showApprovedDocuments()
    {
        $user = Auth::user();
        $title = 'CEO Approved Documents';

        if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
            // Admin, Secretary, and CEO can see all approved documents
            $documents = Document::with(['user', 'department'])
                ->where('ceo_approval', false)
                ->where('approval_status', 'Approved')
                ->get();
        } else {
            // Regular users can only see their uploaded approved documents
            $documents = Document::with(['user', 'department'])
                ->where('ceo_approval', false)
                ->where('approval_status', 'Approved')
                ->where('uploaded_by', $user->id)
                ->get();
        }

        return view('content.dashboard.documents-approved', compact('documents', 'title'));
    }

    public function showRejectedDocuments()
    {
        $user = Auth::user();
        $title = 'CEO Rejected Documents';

        if (in_array($user->role, ['Admin', 'Secretary', 'CEO'])) {
            // Admin, Secretary, and CEO can see all rejected documents
            $documents = Document::with(['user', 'department'])
                ->where('ceo_approval', false)
                ->where('approval_status', 'Rejected')
                ->get();
        } else {
            // Regular users can only see their uploaded rejected documents
            $documents = Document::with(['user', 'department'])
                ->where('ceo_approval', false)
                ->where('approval_status', 'Rejected')
                ->where('uploaded_by', $user->id)
                ->get();
        }

        return view('content.dashboard.documents-rejected', compact('documents', 'title'));
    }

    public function getSubcategories($department_id)
    {
        $subcategories = Subcategory::where('department_id', $department_id)->get();
        return response()->json($subcategories);
    }

    public function getDocumentTypes($subcategory_id)
    {
        $documentTypes = DocumentType::where('subcategory_id', $subcategory_id)->get();
        return response()->json($documentTypes);
    }

    public function download(Request $request)
    {
        // Validate the filePath input
        $validated = $request->validate([
            'filePath' => 'required|string',
        ]);

        $filePath = $validated['filePath'];

        // Check if the file exists
        if (!file_exists(public_path($filePath))) {
            return back()->with('error', 'File not found.');
        }

        // Generate the file name for download
        $fileName = basename($filePath);

        // Return the file for download
        return response()->download(public_path($filePath), $fileName);
    }

    public function myDocuments(Request $request)
    {
        $user = auth()->user();
    
        if ($user->hasRole(['Admin', 'CEO', 'Secretary'])) {
            $documents = Document::with(['department', 'subcategory', 'documentType'])->get();
        } else {
            $documents = Document::with(['user','department', 'subcategory', 'documentType'])
                ->where('uploaded_by', $user->id)
                ->get();
        }
    
        // return response()->json($documents); // comment this out
        return view('content.documents.myDocuments', compact('documents'));
    }
    

    public function documentPending()
    {
        $user = auth()->user();
        $documents = $user->hasRole(['Admin', 'CEO', 'Secretary'])
            ? Document::with(['user', 'department', 'subcategory', 'documentType'])
                ->where('approval_status', 'Pending')
                ->get()
            : Document::with(['user', 'department', 'subcategory', 'documentType'])
                ->where('uploaded_by', $user->id)
                ->where('approval_status', 'Pending')
                ->get();

        $title = 'Pending Documents';
        return view('content.documents.pending-documents', compact('documents', 'title'));
    }

    public function documentApproved()
    {
        $user = auth()->user();
        $documents = $user->hasRole(['Admin', 'CEO', 'Secretary'])
            ? Document::with(['user', 'department', 'subcategory', 'documentType'])
                ->where('approval_status', 'Approved')
                ->get()
            : Document::with(['user', 'department', 'subcategory', 'documentType'])
                ->where('uploaded_by', $user->id)
                ->where('approval_status', 'Approved')
                ->get();

        $title = 'Approved Documents';
        return view('content.documents.approveddocuments', compact('documents', 'title'));
    }

    public function documentRejected()
    {
        $user = auth()->user();
        $documents = $user->hasRole(['Admin', 'CEO', 'Secretary'])
            ? Document::with(['user', 'department', 'subcategory', 'documentType'])
                ->where('approval_status', 'Rejected')
                ->get()
            : Document::with(['user', 'department', 'subcategory', 'documentType'])
                ->where('uploaded_by', $user->id)
                ->where('approval_status', 'Rejected')
                ->get();

        $title = 'Rejected Documents';
        return view('content.documents.rejecteddocuments', compact('documents', 'title'));
    }
}
