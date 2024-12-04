<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanInterest;
use App\Models\LoanType;
use App\Models\Banks;
use App\Models\OtherBankLoan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class OtherBankLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

      try{

          $location = session('user_data')->location;


          $other_loans = OtherBankLoan::with(['customer', 'bank'])->orderBy('id','DESC')->get();



          $totalLoan = $other_loans->count();

          // Count customers added today
          $todayLoans = $other_loans->where('created_at', '>=', Carbon::today())->count();

          // Count customers added this week
          $weekLoans = $other_loans->where('created_at', '>=', Carbon::now()->startOfWeek())->count();

          // Count customers added this month
          $monthLoans = $other_loans->where('created_at', '>=', Carbon::now()->startOfMonth())->count();


          return view('content.other_bank_loan.index', compact('other_loans', 'todayLoans', 'weekLoans', 'monthLoans'));

      }catch(Exception $e){

      }


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

      $location = session('user_data')->location;
      $loan_type = LoanType::where('status','Active')->get();


      $branch = LoanType::find(1);
      $interest = LoanInterest::where('status','1')->get();


      // Count the number of loans for this location
      $loanCount = Loan::where('location_id', $location)->count();
      $loanNumber = "SJ-".$branch->loan_prefix . '-' . str_pad($loanCount + 1, 5, '0', STR_PAD_LEFT);

      $customer = Customer::where('status','Active')->get();
      $banks = Banks::where('status','Active')->get();

        return view('content.other_bank_loan.create',compact('customer','loan_type','loanNumber','interest','location','banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


      $validated = $request->validate([
        'customer_number' => 'required|integer',
        'customer_loan_no' => 'required|string',
        'bank_loan_number' => 'required|string',
        'interest_rate' => 'required|string',
        'loan_amount' => 'required|string',
        'loan_date' => 'required|string',
        'bank_id' => 'required|integer',

        'tenurity' => 'required|integer',
        'customer_photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'customer_other' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
    ]);


    $user_id = session('user_data')->id;

    $customerId = $request->input('customer_number');
    $loanNumber = $request->input('customer_loan_no');

      $existingLoan = OtherBankLoan::where('customer_number', $customerId)
                                 ->where('customer_loan_no', $loanNumber)
                                 ->first();

    if ($existingLoan) {
        return response()->json([
            'status' => 'error',
            'message' => 'Loan already exists for this customer.',
        ], 400); // HTTP 400 for bad request
    }




      DB::beginTransaction();

      try {



          $loan = new OtherBankLoan();
          $loan->customer_number = $request->input('customer_number', NULL);
          $loan->customer_loan_no = $request->input('customer_loan_no', NULL);
          $loan->bank_id = $request->input('bank_id', NULL);
          $loan->bank_loan_number = $request->input('bank_loan_number', NULL);
          $loan->interest_rate = $request->input('interest_rate', NULL);
          $loan->loan_amount = $request->input('loan_amount', NULL);
          $loan->loan_date = $request->input('loan_date', NULL);
          $loan->additional_charges = $request->input('additional_charges', NULL);
          $loan->tenurity = $request->input('tenurity', NULL);
          $loan->jewel_loan_weight = $request->input('jewel_loan_weight', NULL);
          $loan->tenurity = $request->input('tenurity', NULL);
          $loan->added_by = $user_id;

          if ($request->hasFile('customer_photo')) {
            $loan->customer_photo = $request->file('customer_photo')->store('photos', 'public');
        }

        if ($request->hasFile('customer_other')) {
            $loan->customer_other = $request->file('customer_other')->store('documents', 'public');
        }

          $loan->save();
          DB::commit();

          return response()->json(['message' => 'Loan saved successfully.'], 200);
      } catch (\Exception $e) {
          DB::rollBack();

          return response()->json(['message' => 'Error saving loan: ' . $e->getMessage()], 500);
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


    public function getLoanNumbers($customer_id)
    {
        $loanNumbers = Loan::where('customer_id', $customer_id)->pluck('loan_number');

        return response()->json($loanNumbers);
    }

  public function interestReminder(Request $request){



    $loans = OtherBankLoan::whereRaw('DATE_ADD(loan_date, INTERVAL 1 MONTH) <= ?', [now()])
    ->whereNull('deleted_at')
    ->get();

    dd($loans);



  }




}
