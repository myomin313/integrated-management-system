<?php

namespace App\Models\OTManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualOtSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'branch',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
        'january',
        'february',
        'march',
        'year'
    ];
}
