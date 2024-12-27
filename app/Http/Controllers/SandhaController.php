<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sandha;

class SandhaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sandhas = Sandha::all();
        return view('content.sandha.index', compact('sandhas'));
    }

    public function create()
    {
        return view('content.sandha.newcustomer');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sandha_name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
            /* 'status' => 'required|in:active,inactive', */
            'description' => 'nullable|string',
            /* 'added_by' => 'required|integer', */
            'no_of_copies' => 'required|integer',
        ]);

        Sandha::create($request->all());
        return redirect()->route('sandhas.index')->with('success', 'Sandha created successfully.');
    }

    public function show(Sandha $sandha)
    {
        return view('sandhas.show', compact('sandha'));
    }

    public function edit(Sandha $sandha)
    {
        return view('content.sandha.edit', compact('sandha'));
    }

    public function update(Request $request, Sandha $sandha)
    {
        $request->validate([
            'sandha_name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $sandha->update($request->all());
        //return redirect()->route('sandhas.index')->with('success', 'Sandha updated successfully.');
        return response()->json(['success' => true, 'message' => 'Sandha status updated to Inactive.']);
    }

    public function destroy(Sandha $sandha)
    {
        $sandha->delete();
        return redirect()->route('sandhas.index')->with('success', 'Sandha deleted successfully.');
    }

    public function softDelete($id)
    {
        $sandha = Sandha::find($id);

        if ($sandha) {
            $sandha->status = 'inactive';
            // $user->deleted_at = now();
            $sandha->save();

            return response()->json(['success' => true, 'message' => 'Sandha status updated to Inactive.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Sandha not found.'], 404);
        }
    }
}
