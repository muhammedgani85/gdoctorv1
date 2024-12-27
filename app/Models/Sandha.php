<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sandha extends Model
{
    use HasFactory;
    protected $table = 'sandhas';
    protected $fillable = [
      'sandha_name',
      'duration',
      'price',
      'status',
      'description',
      'added_by',
      'no_of_copies'
  ];

  // If you are using soft deletes, you can also include:
  protected $dates = ['deleted_at'];
}
