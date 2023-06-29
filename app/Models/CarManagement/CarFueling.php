<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarFueling extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'date',
        'liter',
        'rate',
        'totalRate',
        'driver',
        'current_meter',
        'mileage_difference',
        'created_by',
        'reason',
        'updated_by'
    ];
}
