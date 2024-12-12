<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Department;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $subcategories = Subcategory::query();
    
        if ($filter) {
            $subcategories->where('department_id', $filter); // Filter subcategories by department
        }
    
        $subcategories = $subcategories->get();
        $departments = Department::all(); // Fetch all departments
    
        return view('content.subcategories.index', compact('subcategories', 'departments', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
  
        $departments = Department::all(); // Fetch all departments
        return view('content.subcategories.add', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Store the subcategory
        Subcategory::create([
            'name' => $validatedData['name'],
            'department_id' => $validatedData['department_id'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Subcategory added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $departments = Department::all(); // Assuming you want to allow the user to change the department

        return view('content.subcategories.edit', compact('subcategory', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name,' . $id,
            'department_id' => 'required|exists:departments,id',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update([
            'name' => $validatedData['name'],
            'department_id' => $validatedData['department_id'],
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return redirect()->back()->with('success', 'Subcategory deleted successfully.');
    }
}
