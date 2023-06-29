<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'dept_id',
        'car_type',
        'car_number',
        'user_id',
        'model_year',
        'chasis_no',
        'parking',
        'main_user',
        'tire_size',
        'created_by',
        'updated_by'
    ];
}
