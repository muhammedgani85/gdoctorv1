<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'patient_name',
    ];

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
