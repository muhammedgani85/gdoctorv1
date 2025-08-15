<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorTimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with('timeSlots')->get();
        return view('content.doctor.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_name'       => 'required|string',
            'phone_number'      => 'required|string',
            'speciality'        => 'required|string',
            'availability_days' => 'required|array|min:1',
            'timing_from'       => 'required|array|min:1',
            'timing_to'         => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $doctor = Doctor::create([
                'doctor_name'       => $request->doctor_name,
                'phone_number'      => $request->phone_number,
                'speciality'        => $request->speciality,
                'availability_days' => implode(',', $request->availability_days),
                'status'            => 'Active',
                'fees'              => $request->fees,
            ]);

            foreach ($request->timing_from as $index => $fromTime) {
                $toTime = $request->timing_to[$index] ?? null;
                if ($toTime) {
                    DoctorTimeSlot::create([
                        'doctor_id'   => $doctor->id,
                        'timing_from' => $fromTime,
                        'timing_to'   => $toTime,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Doctor added successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Doctor store error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error occurred while adding doctor.']);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $doctor = Doctor::with('timeSlots')->findOrFail($id);

        return response()->json([
            'id'                => $doctor->id,
            'doctor_name'       => $doctor->doctor_name,
            'phone_number'      => $doctor->phone_number,
            'speciality'        => $doctor->speciality,
            'fees'              => $doctor->fees,
            'availability_days' => $doctor->availability_days,
            'time_slots'        => $doctor->timeSlots->map(function ($slot) {
                return [

                    'timing_from' => \Carbon\Carbon::parse($slot->timing_from)->format('H:i'),
                    'timing_to'   => \Carbon\Carbon::parse($slot->timing_to)->format('H:i'),

                ];
            }),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'doctor_name'       => 'required|string',
            'phone_number'      => 'required|string',
            'speciality'        => 'required|string',
            'availability_days' => 'required|array|min:1',
            'timing_from'       => 'required|array',
            'timing_to'         => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $doctor = Doctor::findOrFail($id);

            // Update doctor details
            $doctor->update([
                'doctor_name'       => $request->doctor_name,
                'phone_number'      => $request->phone_number,
                'speciality'        => $request->speciality,
                'availability_days' => implode(',', $request->availability_days),
            ]);

            // Remove old time slots
            $doctor->timeSlots()->delete();

            // Add new time slots
            foreach ($request->timing_from as $index => $from) {
                if (! empty($from) && ! empty($request->timing_to[$index])) {
                    $doctor->timeSlots()->create([
                        'timing_from' => $from,
                        'timing_to'   => $request->timing_to[$index],
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Doctor updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Doctor update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error occurred while updating doctor.']);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Doctor::findOrFail($id)->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Doctor deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Doctor delete error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error occurred while deleting doctor.']);
        }
    }

    public function toggleStatus($id)
    {
        DB::beginTransaction();
        try {
            $doctor         = Doctor::findOrFail($id);
            $doctor->status = $doctor->status === 'Active' ? 'Inactive' : 'Active';
            $doctor->save();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Doctor status updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Status toggle error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error changing status.']);
        }
    }
}
