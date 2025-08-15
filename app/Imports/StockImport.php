<?php
namespace App\Imports;

use App\Models\StockItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return StockItem::updateOrCreate(
            ['sku' => $row['sku']],
            [
                'name'                => $row['name'],
                'quantity'            => $row['quantity'],
                'low_stock_threshold' => $row['low_stock_threshold'],
                'price'               => $row['price'],
                'unit'                => $row['unit'] ?? null,
            ]
        );
    }
}
