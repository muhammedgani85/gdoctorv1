<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicineSale;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineSaleController extends Controller
{
    public function index()
    {
        $sales = MedicineSale::with('items')->latest()->get();
        return view('content.medicine_sale.index', compact('sales'));
    }

    public function create()
    {
        $patients = DB::table('appointments')
            ->select('id', 'patient_name', 'phone_number', 'gender', 'age', 'doctor_id', 'slot_id', 'start_time', 'end_time', 'token_number', 'appointment_date', 'status', 'btype', 'illness')
            ->orderByDesc('id')
            ->get();

        $stockItems = StockItem::select('id', 'name', 'sku', 'quantity', 'low_stock_threshold', 'price', 'unit', 'batch_number', 'created_at')->get();

        return view('content.medicine_sale.create', compact('patients', 'stockItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'            => 'required|integer',
            'total_amount'          => 'required|numeric',
            'discount'              => 'nullable|numeric',
            'final_amount'          => 'required|numeric',
            'notes'                 => 'nullable|string',
            'items'                 => 'required|array',
            'items.*.medicine_name' => 'required|string',
            'items.*.batch_number'  => 'required|string',
            'items.*.unit'          => 'required|string',
            'items.*.price'         => 'required|numeric',
            'items.*.quantity'      => 'required|integer',
            'items.*.subtotal'      => 'required|numeric',
        ]);

        $sale = MedicineSale::create($request->only([
            'patient_id', 'total_amount', 'discount', 'final_amount', 'notes',
        ]));

        foreach ($request->items as $item) {
            $stock = StockItem::where('name', $item['medicine_name'])
                ->where('unit', $item['unit'])
                ->where('price', $item['price'])
                ->where('batch_number', $item['batch_number'])
                ->first();

            $sale->items()->create([
                'stock_item_id' => $stock ? $stock->id : null,
                'medicine_name' => $item['medicine_name'],
                'sku'           => $stock ? $stock->sku : '',
                'batch_number'  => $item['batch_number'],
                'unit'          => $item['unit'],
                'price'         => $item['price'],
                'quantity'      => $item['quantity'],
                'subtotal'      => $item['subtotal'],
            ]);
        }

        if ($request->has('print')) {
            return redirect()->route('medicine-sale.print', $sale->id);
        }

        return redirect()->route('medicine-sale.index')->with('success', 'Sale recorded successfully.');
    }

    public function todayPatientsWithPrescription()
    {
        $today        = date('Y-m-d');
        $appointments = Appointment::with([
            'doctor',
            'prescription' => function ($q) {
                $q->with('items');
            },
        ])
            ->whereDate('appointment_date', $today)
            ->orderByDesc('id')
            ->get();

        return view('content.medicine_sale.prescription_patients', compact('appointments'));
    }

    public function todayPrescribedPatients()
    {
        $appointments = Appointment::with('doctor')->latest()->get();
        return view('content.medicine_sale.prescribed_today', compact('appointments'));

    }
}
