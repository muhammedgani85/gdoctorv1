<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorTimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index()
    {

        $appointments = Appointment::with('doctor')->latest()->get();
        return view('content.appointments.index', compact('appointments'));

    }

    public function store(Request $request)
    {

        //dd($request->appoinment_date);

        $request->validate([
            'patient_name' => 'required|string',
            'phone_number' => 'required|string',
            'gender'       => 'required|in:Male,Female,Other',
            'age'          => 'required|integer|min:0',
            'doctor_id'    => 'required|exists:doctors,id',
            'slot_id'      => 'required|exists:doctor_time_slots,id',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $slot   = DoctorTimeSlot::findOrFail($request->slot_id);

        $today     = Carbon::today()->toDateString();
        $slotStart = Carbon::parse($slot->timing_from);
        $slotEnd   = Carbon::parse($slot->timing_to);

        // Begin transaction to avoid duplicate token
        DB::beginTransaction();

        try {
            // Find last appointment in this doctor-slot-today
            $lastAppointment = Appointment::where('doctor_id', $doctor->id)
                ->where('slot_id', $slot->id)
                ->whereDate('appointment_date', $today)
                ->lockForUpdate()
                ->orderBy('end_time', 'desc')
                ->first();

            if ($lastAppointment) {
                $startTime = Carbon::parse($lastAppointment->end_time);
            } else {
                $startTime = $slotStart->copy();
            }

            if ($startTime->lt($slotStart)) {
                $startTime = $slotStart->copy();
            }

            $endTime = $startTime->copy()->addMinutes(15); // 15 min slot

            // Check slot overflow
            if ($endTime->gt($slotEnd)) {
                DB::rollBack();
                return response()->json([
                    'status'  => 'slot_full',
                    'message' => 'Time slot is full. Please choose another.',
                    'data'    => $slot,
                ]);
            }

            // Generate unique token: MU-15-250709-001
            $doctorCode = strtoupper(substr($doctor->doctor_name, 0, 2));
            $slotCode   = str_pad($slot->id, 2, '0', STR_PAD_LEFT);
            $todayShort = Carbon::now()->format('ymd'); // e.g. 250709

            $tokenCount = Appointment::where('doctor_id', $doctor->id)
                ->where('slot_id', $slot->id)
                ->whereDate('appointment_date', $request->appoinment_date)
                ->count() + 1;

            // $tokenNumber = "{$doctorCode}-{$slotCode}-{$todayShort}-" . str_pad($tokenCount, 3, '0', STR_PAD_LEFT);
            $tokenNumber = "{$doctorCode}-{$slotCode}-" . str_pad($tokenCount, 3, '0', STR_PAD_LEFT);

            // Create appointment
            $appointment = Appointment::create([
                'patient_name'     => $request->patient_name,
                'phone_number'     => $request->phone_number,
                'gender'           => $request->gender,
                'age'              => $request->age,
                'doctor_id'        => $doctor->id,
                'slot_id'          => $slot->id,
                'start_time'       => $startTime->format('H:i:s'),
                'end_time'         => $endTime->format('H:i:s'),
                'token_number'     => $tokenNumber,
                'appointment_date' => $request->appoinment_date,
                'btype'            => isset($request->btype) ? $request->btype : 'Direct Booking',
                'illness'          => $request->illness,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'token'  => $appointment->token_number,
                'time'   => $startTime->format('h:i A') . ' - ' . $endTime->format('h:i A'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Booking failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function getSlots($id)
    {
        $slots = DoctorTimeSlot::where('doctor_id', $id)->get(['id', 'timing_from', 'timing_to']);
        return response()->json($slots);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:appointments,id',
            'status' => 'required|string|in:Pending,Checked-in,In Queue,In Progress,Completed,Cancelled,No Show,Rescheduled',
        ]);

        $appointment         = Appointment::find($request->id);
        $appointment->status = $request->status;
        $appointment->save();

        return response()->json(['message' => 'Status updated']);
    }

    public function patient_booking()
    {

        $appointments = Appointment::with('doctor')->latest()->get();
        return view('content.appointments.patient_booking', compact('appointments'));

    }

    /**
     * Calendar View
     */
    public function calendarView()
    {
        return view('content.appointments.calendar');
    }

    public function calendarData()
    {
        $appointments = Appointment::with(['doctor', 'slot'])->get();

        $events = [];

        foreach ($appointments as $a) {
            $events[] = [
                'title' => "Doctor: " . $a->doctor->doctor_name .
                "\nPatient: " . $a->patient_name .
                "\nToken: " . $a->token_number .
                "\nTime: " . \Carbon\Carbon::parse($a->start_time)->format('h:i A') .
                ' - ' .
                \Carbon\Carbon::parse($a->end_time)->format('h:i A'),
                'start' => $a->appointment_date . 'T' . $a->start_time,
                'end'   => $a->appointment_date . 'T' . $a->end_time,
            ];
        }

        return response()->json($events);
    }

    public function token_display(Request $request)
    {
        $appointments = Appointment::with('doctor')->latest()->get();

        $doctorList = $appointments->pluck('doctor')
            ->filter()
            ->unique('id')
            ->map(function ($doctor) {
                return [
                    'id'   => $doctor->id,
                    'name' => $doctor->doctor_name,
                ];
            })->values()->toArray();

        return view('content.appointments.token', compact('appointments', 'doctorList'));
    }

    public function display()
    {

        $appointments = Appointment::with('doctor')->latest()->get();
        return view('content.appointments.display', compact('appointments'));

    }

}
