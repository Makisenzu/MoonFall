<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Zone;
use Illuminate\Http\Request;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userData = User::whereNotNull('latitude')
                      ->whereNotNull('longitude')
                      ->where('role', '!=', 'admin')
                      ->select(['id', 'name', 'email', 'latitude', 'longitude'])
                      ->get();
        
        $zoneData = Zone::whereNotNull('latitude')
                      ->whereNotNull('longitude')
                      ->select(['id', 'location_name', 'occupation', 'radius', 'latitude', 'longitude'])
                      ->get();
        
        $userCount = User::where('role', '!=', 'admin')->count();
        $zoneCount = Zone::count();
        $volunteerCount = Volunteer::count();
        
        return view('admin/adminIndex', compact(
            'userCount', 
            'zoneCount', 
            'volunteerCount', 
            'userData', 
            'zoneData'
        ));
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
    public function store(Request $request)
    {
        //
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
