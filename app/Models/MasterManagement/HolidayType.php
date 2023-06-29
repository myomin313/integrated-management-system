<?php

namespace App\Models\MasterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];
}
