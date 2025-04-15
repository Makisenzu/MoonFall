<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Volunteer;
use Illuminate\Http\Request;

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
        return view('admin/volunteer', compact('pending', 'approved', 'volunteerData'));
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $applicantInfo = Applicant::FindOrFail($id);
            $userData = $request->validate([
                'status' => ['required', 'in:Pending,Approved,Denied'],
            ]);
            $applicantInfo->update($userData);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
