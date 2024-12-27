<?php

namespace App\Http\Controllers;

use App\Models\Pincode;
use Illuminate\Http\Request;

class PinCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

      $pin_codes = Pincode::all();
      return view('content.pincode.index',compact('pin_codes'));

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

    public function softDelete($id)
    {
        $sandha = Pincode::find($id);

        if ($sandha) {
            $sandha->status = 'InActive';
            // $user->deleted_at = now();
            $sandha->save();

            return response()->json(['success' => true, 'message' => 'Pincode status updated to Inactive.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Pincode not found.'], 404);
        }
    }





}
