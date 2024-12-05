<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Phpass\PasswordHash;

class UserController extends Controller
{
  public function index()
  {
    return view('content.pages.pages-add-user-account');
  }
  public function usersList()
  {
    return view('content.pages.pages-users-list');
  }

  // Store a newly created resource in storage
  public function store(Request $request)
  {
    //return response()->json($request->all());

    try {
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string',
        'xad_id' => 'required',
        'role' => 'required|string',
        'permissions' => 'nullable|array',
      ]);

      // Check if validation fails
      if ($validator->fails()) {
        $errors = $validator->errors();
        return back()
          ->withErrors($errors)
          ->withInput();
      }

      // Process image upload if an image is provided
      $fileName = '';
      if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $fileName = time() . rand(1, 99) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/profile'), $fileName);
      }

      $rounds = 12;
      // Hash the password using bcrypt
      $hashedpassword = Hash::make(trim($request->password), ['rounds' => $rounds]);
      // Create a new instance of the User model
      $user = new User();

      // Set the attributes
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->email = $request->email;
      $user->organization_unit = $request->organization_unit;
      $user->phone_number = $request->phone_number;
      $user->password = Hash::make(trim($request->password), ['rounds' => $rounds]);
      $user->role = $request->role;
      $user->permissions = $request->permissions ? json_encode($request->permissions) : null;
      $user->profile_image = $fileName;
      $user->nationality = $request->nationality;
      $user->xad_id = $request->xad_id;

      // Save the user to the database
      $user->save();
      return redirect()
        ->back()
        ->with('success', 'User created successfully');
    } catch (Exception $e) {
      return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
  }

  public function getRecords()
  {

    $users = User::all();
    return response()->json($users);
  }

  public function updateRecord(Request $request)
  {
    //return response()->json($request->all());
    try {
      // Create a Validator instance
      $validator = Validator::make($request->all(), [
        'id' => 'required|integer|exists:users,id',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required',
        'role' => 'required',
      ]);

      // Check if validation fails
      if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 422);
      }
      // Find the user record by ID
      $user = User::findOrFail($request->input('id'));

      // Prepare the data for updating
      $userData = [
        'first_name' => $request->input('first_name', $user->first_name),
        'last_name' => $request->input('last_name', $user->last_name),
        'email' => $request->input('email', $user->email),
        'phone_number' => $request->input('phone_number', $user->phone_number),
        'role' => $request->input('role', default: $user->role),
        'xad_id' => $request->input(key: 'xad_id', default: $user->xad_id),
      ];

      // Check if password is provided in the request
      if ($request->filled('password')) {
        $userData['password'] = Hash::make($request->input('password'));
      }

      // Update the user record with validated data
      $user->update($userData);

      return response()->json(['success' => 'Project updated successfully']);
    } catch (Exception $e) {
      // Handle exceptions
      return response()->json(['message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
    }
  }

  public function deleteRecord(Request $request)
  {
    try {
      // Validate that project_id is provided
      $validator = Validator::make($request->all(), [
        'user_id' => 'required|integer|exists:users,id',
      ]);

      if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
      }

      // Find the project record by ID
      $project = User::find($request->input('user_id'));

      if (!$project) {
        return response()->json(['message' => 'User record not found.'], 404);
      }

      // Delete the project record
      $project->delete();

      return response()->json(['success' => 'User deleted successfully']);
    } catch (Exception $e) {
      return response()->json(['error' => 'An error occurred while deleting the project record.'], 500);
    }
  }
}
