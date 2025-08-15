<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id', 'weight', 'height', 'bp', 'o2', 'sugar_pp', 'sugar_af', 'notes', 'consulting_fees', 'medicine', 'scan_required', 'scan_centre',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
