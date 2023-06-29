<?php

namespace App\Models\OTManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtRate extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "ot_rate",
        "hourly_rate",
        "date"
    ];
}
