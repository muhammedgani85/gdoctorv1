<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Branch;

class AttendanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{


  $employees = User::all();
$locations = Branch::all();

$query = Attendance::query();

// Filter by month
if ($request->filled('month')) {
    $query->whereMonth('date', date('m', strtotime($request->month)))
          ->whereYear('date', date('Y', strtotime($request->month)));
}

// Filter by employee ID
if ($request->filled('employee_id')) {
    $query->where('employee_id', $request->employee_id);  // Ensure field name matches the database column
}

// Filter by location (branch)
if ($request->filled('location_id')) {
    $query->whereHas('user', function ($q) use ($request) {
        $q->where('location', $request->location_id);  // Use 'location_id' to match the column name in the 'User' table
    });
}

// Retrieve filtered results with eager loading
$attendanceRecords = $query->with(['user', 'user.branch'])->get();


    #dd($attendanceRecords);

    return view('content.attendance.report.index', compact('attendanceRecords', 'employees', 'locations'));
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
