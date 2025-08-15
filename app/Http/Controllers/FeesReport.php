<?php
namespace App\Http\Controllers;

use App\Models\AppointmentNote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FeesReport extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from_date ?? Carbon::now()->startOfMonth()->toDateString();
        $to   = $request->to_date ?? Carbon::now()->endOfMonth()->toDateString();

        $fees = AppointmentNote::with('appointment')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereHas('appointment', function ($q) use ($from, $to) {
                    $q->whereBetween('appointment_date', [$from, $to]);
                });
            })
            ->get();

        // Calculate totals
        $totalConsulting = $fees->sum('consulting_fees');
        $totalMedicine   = $fees->sum('medicine');
        $totalFees       = $totalConsulting + $totalMedicine;

        return view('content.loan.fees_report', compact('fees', 'from', 'to', 'totalConsulting', 'totalMedicine', 'totalFees'));
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
