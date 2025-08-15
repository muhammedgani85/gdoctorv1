<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorTimeSlot extends Model
{
    use HasFactory;
    protected $fillable = ['doctor_id', 'timing_from', 'timing_to'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

}
