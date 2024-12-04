<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherBankLoan extends Model
{
    use HasFactory,SoftDeletes;
    public function customer()
    {
      return $this->belongsTo(Customer::class, 'customer_number', 'id');
    }
    public function bank()
    {
    return $this->belongsTo(Banks::class, 'bank_id', 'id');
    }
}
