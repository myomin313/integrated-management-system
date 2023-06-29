<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarInsuranceClaimHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'insurance_id',
        'claim_date',
        'claim_detail',
        'created_by',
        'updated_by'
    ];
}
