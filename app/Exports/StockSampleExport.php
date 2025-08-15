composer require maatwebsite/excel<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockSampleExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['Paracetamol', 'PCT001', 100, 10, 1.50, 'Tablet'],
            ['Ibuprofen', 'IBU002', 50, 5, 2.00, 'Tablet'],
        ];
    }
    public function headings(): array
    {
        return ['name', 'sku', 'quantity', 'low_stock_threshold', 'price', 'unit'];
    }
}
