<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanInterest extends Model
{
  use HasFactory;

  protected $fillable = ['type', 'interest_rate', 'months', 'status'];

  public function loanType()
  {
      return $this->belongsTo(LoanType::class);
  }
}
