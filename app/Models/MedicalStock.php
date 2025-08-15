<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalStock extends Model
{
    use HasFactory;
     protected $fillable = [
        'item_name', 'batch_number', 'quantity', 'unit', 'min_quantity_threshold', 'price_per_unit', 'expiry_date', 'vendor'
    ];
}
