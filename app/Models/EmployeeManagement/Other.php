<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Other extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'interest',
        'strong_point',
        'weak_point',
        'created_by',
        'updated_by'
    ];
}
