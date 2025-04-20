<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class zoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->select(['id', 'location_name', 'occupation', 'radius', 'latitude', 'longitude'])
                ->get();
        $users = User::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->select(['id', 'name', 'email', 'latitude', 'longitude'])
                ->get();

        $newsData = Information::orderBy('created_at', 'desc')->take(5)->get();
        return response()->json($zones);
            // return response()->json([
            //     'zones' => $zones,
            //     'users' => $users
            // ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin/safeZone');
    }

    public function userView(){
        return view('users/viewZone');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'occupation' => 'required|in:Food,Danger,Hospital,Evacuation,Police',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:100|max:30000'
        ]);
    
        try {
            $zone = Zone::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Safe zone created successfully',
                'zone' => $zone
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create safe zone: ' . $e->getMessage()
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
