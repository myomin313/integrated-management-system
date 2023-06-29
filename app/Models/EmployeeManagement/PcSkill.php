<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcSkill extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'level',
        'created_by',
        'updated_by'
    ];
}
