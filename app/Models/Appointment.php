<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_name',
        'phone_number',
        'gender',
        'age',
        'doctor_id',
        'slot_id',
        'start_time',
        'end_time',
        'token_number',
        'appointment_date',
        'btype',
        'illness',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function slot()
    {
        return $this->belongsTo(DoctorTimeSlot::class, 'slot_id');
    }

    public function note()
    {
        return $this->hasOne(AppointmentNote::class);
    }
    public function prescription()
    {
        return $this->hasOne(\App\Models\Prescription::class, 'appointment_id');
    }

}
