<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Cities;
use App\Models\Customer;
use App\Models\Pincode;
use App\Models\Sandha;
use App\Models\State;
use App\Models\User;

class CustomerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Get session data for the current user's location and role
        $location = session('user_data')->location;
        $role = session('user_data')->role;

        // Define filter options
        $fromDate = $request->input('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->input('to_date', now()->endOfMonth()->toDateString());
        $status = $request->input('status');
        $city_id = $request->input('city');
        $pincode_id = $request->input('pincode');
        $branchId = $request->input('branch_id');

        $city = Cities::where('status', 'Active')->get();
        $states = State::where('status', 'Active')->get();
        $pincode = Pincode::where('status', 'Active')->get();
        $sandhas = Sandha::where('status', 'Active')->get();
       // dd($request->all());

        // Retrieve all branches for the dropdown
        $branches = Branch::all();
        $ref_customer = Customer::all();

        // Initialize the customer query with default filters
        $query = Customer::with('branch','customerpincode','customercity')
                        ->whereBetween('created_at', [$fromDate, $toDate]);



        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $status);
        }
        if ($request->filled('city')) {
          $query->where('city', $city_id);
        }
        if ($request->filled('pincode')) {
          $query->where('pincode', $pincode_id);
        }

        if ($request->filled('pincode')) {
          $query->where('pincode', $pincode_id);
        }

        // Fetch filtered results
        $customers = $query->get();

        // If no records found, fetch all customers from the user's branch for the current month
        if ($customers->isEmpty()) {
                $fallbackQuery = Customer::with('branch','customerpincode','customercity')
                ->where('location_id', $location);

                // Apply status filter if provided
                if ($request->filled('status')) {
                $fallbackQuery->where('status', $status);
                }

                // Optional: Uncomment the below line if you want to restrict by date in the fallback query
                // $fallbackQuery->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);

                $customers = $fallbackQuery->get();
        }



//dd($customers);

// Pass data to the view
return view('content.customermanagement.report.index', compact('customers', 'branches', 'fromDate', 'toDate', 'status', 'branchId','role','city','states','pincode','sandhas','ref_customer'));




        // Pass data to the view

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

    public function getCustomerDetails(Request $request)
{
    $customer = Customer::find($request->id);

    if ($customer) {
        return response()->json([
            'r_phone' => $customer->phone_number,
            'r_address' => $customer->communication_address,
        ]);
    }

    return response()->json(['message' => 'Customer not found.'], 404);
}
}
