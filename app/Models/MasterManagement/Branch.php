<?php

namespace App\Models\MasterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'short_name',
        'status',
        'created_by',
        'updated_by'
    ];
}
