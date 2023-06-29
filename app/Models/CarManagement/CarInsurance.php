<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarInsurance extends Model
{
    use HasFactory;
    protected $fillable = [
        'insurance_no',
        'car_id',
        'insurance_company',
        'premium_amount',
        'currency',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by'
    ];
}
