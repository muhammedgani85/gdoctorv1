<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

public function users(){
    return $this->hasMany(User::class, 'location');
}
public function leaves()
{
    return $this->hasMany(Leave::class, 'location');
}

public function funds()
    {
        return $this->hasMany(Fund::class, 'location');
    }


}
