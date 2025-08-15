<?php
namespace App\Http\Controllers;

use App\Models\AppointmentNote;
use Illuminate\Http\Request;

class AppointmentNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show($id)
    {
        $note = AppointmentNote::where('appointment_id', $id)->first();
        return response()->json($note);
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'weight'          => 'nullable|string',
            'height'          => 'nullable|string',
            'bp'              => 'nullable|string',
            'o2'              => 'nullable|string',
            'sugar_pp'        => 'nullable|string',
            'sugar_af'        => 'nullable|string',
            'notes'           => 'nullable|string',
            'consulting_fees' => 'nullable|string',
            'medicine'        => 'nullable|string',
            'scan_required'   => 'nullable|string',
            'scan_centre'     => 'nullable|string',
        ]);

        $note = AppointmentNote::updateOrCreate(
            ['appointment_id' => $id],
            $validated
        );

        return response()->json(['success' => true]);
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
