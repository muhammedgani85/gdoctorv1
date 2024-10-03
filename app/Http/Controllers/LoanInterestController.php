<?php

namespace App\Http\Controllers;

use App\Models\LoanInterest;
use Illuminate\Http\Request;

class LoanInterestController extends Controller
{
  public function index()
  {
    $loanInterests = LoanInterest::with('loanType')->get();

      return view('content.loan_interests.index', compact('loanInterests'));
  }

  public function create()
  {
      return view('loan_interests.create');
  }

  public function store(Request $request)
  {
      $request->validate([
          'type' => 'required|string|max:255',
          'interest_rate' => 'required|numeric|min:0',
          'months' => 'required|integer|in:3,6,9,12',
          'status' => 'required|boolean',
      ]);

      LoanInterest::create($request->all());

      return redirect()->route('loan_interests.index')->with('success', 'Loan interest added successfully.');
  }
}
