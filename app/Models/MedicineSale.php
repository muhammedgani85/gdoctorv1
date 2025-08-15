<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'total_amount',
        'discount',
        'final_amount',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(MedicineSaleItem::class);
    }
}
