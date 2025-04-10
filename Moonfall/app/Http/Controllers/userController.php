<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users/userIndex');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newsData = Information::all();
        return view('users/userDashboard', compact('newsData'));
    }
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:16']
        ], [
            'email.required' => 'Email is required!',
            'email.email' =>   'Please enter a valid Email address',
            'password.required' => 'Password is required!',
            'password.min' => 'The password must be at least 8 characters',
            'password.max' => 'The password must not be greater than 16 characters'
        ]);
    
        if (Auth::attempt(['email' => $loginData['email'], 'password' => $loginData['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();

            if($user->role == 'admin'){
                return redirect()->route('adminIndex')->with('success', 'Hello Admin!');
            }else{
                return redirect()->route('userDashboardCreate')->with('success', 'Login Successfully');
            }
        }
    
        return back()->withErrors([
            'email' => 'Your email does not match our records.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'max:16'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'phone_number' => ['required', 'unique:users','string', 'regex:/^[0-9]+$/', 'min:7', 'max:15'],
                'birthday' => ['required', 'date', 'before_or_equal:' . now()->subYears(13)->format('Y-m-d')],
                'gender' => ['required', 'in:male,female,other'],
                'picture' => ['nullable'],
            ], [
                'email.unique' => 'This email already existed!',
                'phone_number.unique' => 'This number already existed!',
                'phone_number.regex' => 'The phone number must only contain digits',
                'birthday.before_or_equal' => 'You must be at least 13 years old to register.'
            ]);
            $data['password'] = bcrypt($data['password']);
            User::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Account successfully created!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.' . $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        Auth::guard('web')->logout();
        
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    
        
        return view('users/userIndex');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
