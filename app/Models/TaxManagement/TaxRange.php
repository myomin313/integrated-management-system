<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRange extends Model
{
    use HasFactory;
    protected $fillable = [
        "from_kyat",
        "to_kyat",
        "percent"
    ];
}
