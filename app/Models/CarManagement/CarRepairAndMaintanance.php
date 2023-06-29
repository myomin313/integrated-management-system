<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRepairAndMaintanance extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'kilo_meter',
        'repair_date',
        'amount',
        'repair_type',
        'repair_detail',
        'created_by',
        'updated_by'
    ];
}
