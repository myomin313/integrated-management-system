<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDriver extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'driver_id',
        'created_by',
        'updated_by'
    ];
}
