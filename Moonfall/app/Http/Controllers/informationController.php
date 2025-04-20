<?php

namespace App\Http\Controllers;

use App\Events\NewsAlert;
use App\Models\Information;
use Illuminate\Http\Request;

class informationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newsData = Information::all();
        return view('admin/news', compact('newsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'news_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'urgency' => ['required', 'string', 'max:255'],
        ]);

        $newsData = Information::create($data);
        event(new NewsAlert($newsData));
        if ($request->wantsJson()){
            return response()->json([
                'success' => true,
                'message' => 'News added successfully',
                'shelter' => $newsData
            ]);
        }
        return redirect()->route('adminNewsCreate');
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
        $news = Information::findOrFail($id);
        $news->delete();

        return redirect()->route('adminNewsCreate');
    }
}
