<?php
namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    // Store prescription from AJAX
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_name'   => 'required|string',
            'medicines'      => 'required|array|min:1',
        ]);

        // Find or create prescription for this appointment
        $prescription = Prescription::firstOrCreate(
            ['appointment_id' => $request->appointment_id],
            ['patient_name' => $request->patient_name]
        );
        // If already exists, update patient_name
        $prescription->patient_name = $request->patient_name;
        $prescription->save();

        // Remove all old items and re-add (simple way)
        $prescription->items()->delete();
        foreach ($request->medicines as $med) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine'        => $med['medicine'],
                'morning'         => $med['morning'],
                'afternoon'       => $med['afternoon'],
                'evening'         => $med['evening'],
                'days'            => $med['days'],
            ]);
        }

        return response()->json(['status' => 'success', 'id' => $prescription->id]);
    }

    // Edit modal: fetch prescription and items for appointment
    public function edit($appointment_id)
    {
        $prescription = Prescription::where('appointment_id', $appointment_id)->with('items')->first();
        if (! $prescription) {
            return response()->json(['status' => 'not_found']);
        }
        return response()->json([
            'status'       => 'success',
            'prescription' => $prescription,
            'items'        => $prescription->items,
        ]);
    }
}
