<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date_of_acquition',
        'certificate',
        'created_by',
        'updated_by'
    ];
}
