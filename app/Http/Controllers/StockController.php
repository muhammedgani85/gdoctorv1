<?php
namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StockController extends Controller
{
    // Show stock list
    public function index(Request $request)
    {
        $query = StockItem::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $allStockItems = $query->get();
        // Group by name+unit for total count and popup
        $grouped = $allStockItems->groupBy(function ($item) {
            return $item->name . '|' . $item->unit;
        });
        $displayItems = $grouped->map(function ($items) {
            $first = $items->first();
            return [
                'name'           => $first->name,
                'unit'           => $first->unit,
                'total_quantity' => $items->sum('quantity'),
                'total_value'    => $items->sum(function ($i) {return $i->quantity * $i->price;}),
                'skus'           => $items->pluck('sku')->unique()->toArray(),
            ];
        })->values();
        $totalCount = $allStockItems->sum('quantity');
        $totalValue = $allStockItems->sum(function ($i) {return $i->quantity * $i->price;});
        $lastBatch     = session('last_batch_number');
        $batchProducts = $lastBatch ? $allStockItems->where('batch_number', $lastBatch) : collect();
        return view('content.stock.index', [
            'stockItems'    => $displayItems,
            'totalCount'    => $totalCount,
            'totalValue'    => $totalValue,
            'lastBatch'     => $lastBatch,
            'batchProducts' => $batchProducts,
            'allStockItems' => $allStockItems,
            'groupedStock'  => $grouped,
        ]);
    }

    // Show upload form
    public function uploadForm()
    {
        return view('content.stock.upload');
    }

    // Handle CSV upload (native PHP, no Excel package)
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        $file     = $request->file('file');
        $handle   = fopen($file->getRealPath(), 'r');
        $header   = fgetcsv($handle);
        $expected = ['name', 'sku', 'quantity', 'low_stock_threshold', 'price', 'unit', 'batch_number'];
        if (array_map('strtolower', $header) !== $expected) {
            return back()->withErrors(['file' => 'Invalid CSV header.']);
        }
        $count        = 0;
        $batch_number = null;
        $grouped      = [];
        $rows         = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 7) {
                continue;
            }
            $name = $row[0];
            $unit = $row[5];
            $key  = strtolower(trim($name)) . '|' . strtolower(trim($unit));
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'name'                => $name,
                    'sku'                 => $row[1],
                    'quantity'            => (int) $row[2],
                    'low_stock_threshold' => (int) $row[3],
                    'price'               => (float) $row[4],
                    'unit'                => $unit,
                    'batch_number'        => strtoupper(Str::random(6)),
                ];
            } else {
                $grouped[$key]['quantity'] += (int) $row[2];
            }
        }
        fclose($handle);
        foreach ($grouped as $item) {
            \App\Models\StockItem::create($item);
            $batch_number = $item['batch_number'];
            $count++;
        }
        // Store batch info in session for popup
        session(['last_batch_number' => $batch_number]);
        return redirect()->route('stock.index')->with('success', "$count stock items uploaded/updated. Batch: $batch_number");
    }

    // Download sample file (manual static file)
    public function downloadSample()
    {
        $path = public_path('samples/stock_sample.csv');
        if (! file_exists($path)) {
            abort(404, 'Sample file not found.');
        }
        return response()->download($path, 'stock_sample.csv');
    }

    // Low stock notification (AJAX or view)
    public function lowStock()
    {
        $lowStockItems = StockItem::whereColumn('quantity', '<', 'low_stock_threshold')->get();
        return response()->json($lowStockItems);
    }

    // Delete all items in a batch, with reason
    public function deleteBatch(Request $request)
    {
        $request->validate([
            'batch_number' => 'required',
            'reason'       => 'required|string|min:3',
        ]);
        $batch  = $request->input('batch_number');
        $reason = $request->input('reason');
        // Optionally log the reason somewhere (e.g. BatchDeleteLog model/table)
        \App\Models\StockItem::where('batch_number', $batch)->delete();
        return response()->json(['success' => true]);
    }

    // AJAX: Get all batches for a name+unit
    public function batchDetails(Request $request)
    {
        $name  = $request->query('name');
        $unit  = $request->query('unit');
        $items = StockItem::where('name', $name)->where('unit', $unit)->orderByDesc('id')->get();
        return response()->json($items);
    }

    // AJAX: Delete single stock item
    public function itemDelete(Request $request)
    {
        $id   = $request->input('id');
        $item = StockItem::find($id);
        if ($item) {
            $item->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
