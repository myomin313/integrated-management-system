<?php

namespace App\Models\MasterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'title',
        'year',
        'driver',
        'holiday_type_id',
        'status',
        'created_by',
        'updated_by'
    ];
}
