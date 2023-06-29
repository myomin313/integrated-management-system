<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarLicense extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'start_date',
        'due_date',
        'created_by',
        'updated_by'
    ];
}
