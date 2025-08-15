<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'sku', 'quantity', 'low_stock_threshold', 'price', 'unit', 'batch_number',
    ];
}
