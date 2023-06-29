<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeAssurance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'premium_amount',
        'year',
        'created_by',
        'updated_by'
    ];
}
