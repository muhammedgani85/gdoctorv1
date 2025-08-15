<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineSaleItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'medicine_sale_id',
        'stock_item_id',
        'medicine_name',
        'sku',
        'batch_number',
        'unit',
        'price',
        'quantity',
        'subtotal',
    ];

    public function sale()
    {
        return $this->belongsTo(MedicineSale::class, 'medicine_sale_id');
    }
}
