<?php

namespace App\Models\CarManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMainUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'main_user_id',
        'created_by',
        'updated_by'
    ];
}
