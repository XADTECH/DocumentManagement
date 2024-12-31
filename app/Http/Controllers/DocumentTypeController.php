<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Models\Subcategory; 
use App\Models\Department; 

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('search'); // Retrieve the search query
        $documentTypes = DocumentType::query()
            ->with(['department', 'subcategory']); // Eager load related data
    
        if ($filter) {
            $documentTypes->where('name', 'LIKE', "%$filter%"); // Apply filter to the name column
        }
    
        $documentTypes = $documentTypes->get();
    
        return view('content.document-types.index', compact('documentTypes', 'filter'));
    }
    
    /**
     * Show the form for creating a new resource.
     */

     public function create()
     {
         $departments = Department::all();
         $subcategories = Subcategory::all();
         return view('content.document-types.add', compact('departments', 'subcategories'));
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response($request->all());
        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name',
            'department_id' => 'required|exists:departments,id', // Validate department_id
            'subcategory_id' => 'required|exists:subcategories,id', // Validate subcategory_id
        ]);
        
        // Store the document type
        DocumentType::create([
            'name' => $validatedData['name'],
            'department_id' => $validatedData['department_id'],
            'subcategory_id' => $validatedData['subcategory_id'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Document Type added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $documentType = DocumentType::findOrFail($id);

        return view('content.document-types.edit', compact('documentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Retrieve the ID from the form
        $id = $request->input('id');
    
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name,' . $id,
        ]);
    
        // Find the Document Type and update it
        $documentType = DocumentType::findOrFail($id);
        $documentType->update([
            'name' => $validatedData['name'],
        ]);
    
        // Redirect back with a success message
        return redirect()->route('document-types.index')->with('success', 'Document Type updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $documentType = DocumentType::findOrFail($id);
        $documentType->delete();

        return redirect()->back()->with('success', 'Document Type deleted successfully.');
    }
}
