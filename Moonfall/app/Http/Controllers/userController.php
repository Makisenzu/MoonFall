<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use App\Models\Applicant;
use App\Models\Information;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
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
    public function create($id)
    {
        // $newsData = Information::where('audience', 'Civilian')->get();
        $isVolunteer = Volunteer::where('users_id', $id)->exists();
        $newsData = $isVolunteer
        ? Information::whereIn('audience', ['civilian', 'volunteer'])->get()
        : Information::where('audience', 'civilian')->get();
        $zoneData = Zone::all();
        $audience = Volunteer::where('users_id', $id)->exists() ? 'volunteer' : 'civilian';
        return view('users/userDashboard', compact('newsData', 'zoneData', 'audience'));
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
                return redirect()->route('userDashboardCreate', $user->id)->with('success', 'Login Successfully');
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
                'latitude' => ['required', 'numeric'],
                'longitude' => ['required', 'numeric'],
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
        $userData = User::with(['applicant'])->findOrFail($id);
        $applicants = Applicant::all();
        return view('users/profile', compact('userData', 'applicants'));
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
        try {
            $userInfo = User::findOrFail($id);
            $data = $request->validate([
                'name' => ['required', 'max:100'],
                'lastname' => ['required', 'max:100'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
                'password' => ['nullable', 'string', 'min:8', 'max:16', 'confirmed'],
                'phone_number' => ['required'],
                'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $data['picture'] = $filename;
            }
            $userInfo->update($data);
    
            return response()->json([
                'success' => true,
                'message' => 'Information updated successfully!'
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
