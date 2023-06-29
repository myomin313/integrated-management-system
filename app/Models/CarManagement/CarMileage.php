<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMileage extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'date',
        'current_km',
        'km',
        'liter',
        'actual_km',
        'created_by',
        'updated_by'
    ];
}
