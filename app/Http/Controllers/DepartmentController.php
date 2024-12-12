<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $departments = Department::query();
    
        if ($filter) {
            $departments->where('id', $filter);
        }
    
        $departments = $departments->get();
    
        return view('content.departments.index', compact('departments', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();

        return view('content.departments.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        // Store the department
        Department::create([
            'name' => $validatedData['name'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Department added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
        ]);
    
        $department = Department::findOrFail($id);
        $department->update([
            'name' => $validatedData['name'],
        ]);
    
        return redirect()->route('departments-list')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}
