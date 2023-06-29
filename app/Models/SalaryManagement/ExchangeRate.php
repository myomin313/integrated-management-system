<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'dollar',
        'yen',
        'created_by',
        'updated_by'
    ];
}
