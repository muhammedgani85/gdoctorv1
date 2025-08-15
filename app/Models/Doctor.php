<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_name', 'phone_number', 'speciality', 'availability_days', 'timing_from', 'timing_to', 'status', 'fees',
    ];

    public function timeSlots()
    {
        return $this->hasMany(DoctorTimeSlot::class);
    }

}
