<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Applicant;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as Escape;

class volunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $volunteerData = Volunteer::with('user')->count();
        $pending = Applicant::where('status', 'Pending')->count();
        $approved = Applicant::where('status', 'Approved')->count();
        $volunteers = Volunteer::with('user')->get();
        return view('admin/volunteer', compact('pending', 'approved', 'volunteerData', 'volunteers'));
    }
    public function viewApplicants(){
        $applicants = Applicant::with('user')->get();
        return view('admin/viewApplicant', compact('applicants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'status' => ['max:200',]
            ]);

            $data['applicant_id'] = $id;
            Applicant::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Applied Successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again. ' . $e->getMessage()
            ], 500);
        }
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
    public function denied(Request $request, string $id)
    {
        $applicant = Applicant::with('user')->findOrFail($id);
        $userData = $request->validate([
            'status' => ['required'],
        ]);
        $applicant->update($userData);
    
        if (!$applicant) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found',
            ], 404);
        }
    
        $applicant->status = 'Denied';
        $applicant->save();
        return response()->json([
            'success' => true,
            'message' => 'Removed Successfully',
            'status' => $applicant->status,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $applicantInfo = Applicant::with('user')->FindOrFail($id);
            $userData = $request->validate([
                'status' => ['required', 'in:Pending,Approved,Denied'],
            ]);
            Escape::beginTransaction();

            $applicantInfo->update($userData);
            if ($userData['status'] === 'Approved') {
                Volunteer::create([
                    'users_id' => $applicantInfo->applicant_id,
                    'latitude' => $applicantInfo->user->latitude,
                    'longitude' => $applicantInfo->user->longitude,
                    'created_at' => now(),
                ]);
            }

            Escape::commit();
            return response()->json([
                'success' => true,
                'message' => 'Approved Successfully',
                'status' => $applicantInfo->status
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again. ' . $e->getMessage()
            ], 500);
        }
    }
    public function removeVolunteer(string $id)
    {
        $applicant = Applicant::where('applicant_id', $id)->first();
    
        if (!$applicant) {
            return response()->json([
                'success' => false,
                'message' => 'Applicant not found',
            ], 404);
        }
    
        $applicant->status = 'Removed';
        $applicant->save();

        $volunteer = Volunteer::where('users_id', $id)->first();
    
        if ($volunteer) {
            $volunteer->delete();
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Removed Successfully',
            'status' => $applicant->status,
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
