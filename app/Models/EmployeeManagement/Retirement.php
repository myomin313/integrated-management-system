<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retirement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'reason',
        'created_by',
        'updated_by'
    ];
}
