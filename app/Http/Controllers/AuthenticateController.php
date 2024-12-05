<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterConfirmEmail;
use Illuminate\Support\Str;

class AuthenticateController extends Controller
{
  public function VerifyUser($email, $token)
  {
    //return response()->json(['email'=>$email, 'token'=>$token]);

    $user = User::where('email', $email)
      ->where('verification_token', $token)
      ->first();
    //return response()->json(['user'=>$user]);

    if ($user) {
      if (!$user->verified && !is_null($user->verification_token)) {
        $user->update(['verified' => true, 'verification_token' => null]);
        Auth::login($user);
        return redirect()
          ->route('index')
          ->with('success', 'Congratulations, you are now verified!');
      } else {
        return redirect()
          ->route('index')
          ->with('success', 'You are already verified!');
      }
    } else {
      // User not found, handle accordingly (e.g., show an error message)
      return redirect()
        ->route('index')
        ->with('success', 'Invalid email or you are already registered');
    }
  }

  public function RegisterProvider(Request $request)
  {
    // $requestData = $request->all();
    // return response()->json(['data'=>$requestData]);

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'phone' => 'required|string|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'type' => 'required|string|in:provider,admin,user',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
      $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
        'password' => Hash::make($request->input('password')),
        'type' => $request->input('type'),
      ]);

      // Log in the user programmatically after registration
      Auth::login($user);

      return redirect()
        ->route('index')
        ->with('success', 'registered as provider');
    } catch (\Exception $e) {
      return response()->json(['error' => $e]);
    }
  }

  public function RegisterUser(Request $request)
  {
    $userType = $request->input('type');

    // Define a default value for 'verified'
    $verified = $userType === 'user' ? true : false;

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'phone' => 'required|string|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'type' => 'required|string|in:agent,user,business', // Assuming 'type' was your original field name
    ]);

    if ($validator->fails()) {
      // return response()->json(['errors' => $validator->errors()], 400);
      $errors = $validator->errors();
      return redirect()
        ->route('user-signup')
        ->withErrors($errors);
    }

    try {
      $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
        'verified' => $verified,
        'password' => Hash::make($request->input('password')),
        'type' => $request->input('type'),
        'verification_token' => Str::random(40),
      ]);

      Mail::to($user->email)->send(new RegisterConfirmEmail($user->email, $user->verification_token));

      // Auth::login($user);
      return redirect()
        ->route('index')
        ->with('success', 'Please Check your Email For Confirmation');
    } catch (\Exception $e) {
      return redirect()
        ->route('index')
        ->with('success', 'Please Check your Email For Confirmation');
    }
  }

  public function LogoutUser()
  {
    Auth::logout();
    return redirect()
      ->route('auth-login-basic')
      ->with('success', 'successfully Log out From System');
  }

public function loginUser(Request $request)
{
 
    try {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('error', 'Please enter correct fields.');
        }

        // Check if the user exists in the database
        $user = User::where('email', $request->input('email'))->first();
        

        if (!$user) {
            return redirect()
                ->back()
                ->with('error', 'User not found.');
        }

        // Check if the provided password matches the stored password
        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()
                ->back()
                ->with('error', 'Invalid email or password.');
        }

        // Attempt to log in the user
        Auth::login($user);

        return redirect()
            ->route('dashboard-analytics')
            ->with('success', 'Login successful.');

    } catch (\Exception $e) {
        // Log the exception or handle it as needed
        

        // Return an error response or redirect back with an error message
        return redirect()
            ->back()
            ->with('error', 'An error occurred during login. Please try again.');
    }
}


  public function checkHash(Request $request)
  {
    return view('content.pages.checkhash');
  }

  public function updateType(Request $request, $id)
  {
    $user = User::find($id);

    if (!$user) {
      // User not found, handle the error appropriately (e.g., show error message)
    }

    // Validate the form input
    $request->validate([
      'user_type' => 'required|in:admin,provider,user',
    ]);

    // Update the user type
    $user->type = $request->input('user_type');
    $user->save();

    // Redirect back to the page after the update
    return redirect()
      ->back()
      ->with('success', 'User type updated successfully.');
  }

  public function deleteUser(Request $request)
  {
    $userId = $request->input('user_id');
    $user = User::find($userId);

    if (!$user) {
      // User not found, handle the error appropriately (e.g., show error message)
      return redirect()
        ->back()
        ->with('error', 'User not found.');
    }

    // Delete the user
    $user->delete();

    // Redirect back to the previous page with success message
    return redirect()
      ->back()
      ->with('success', 'User has been removed from the database.');
  }
}
