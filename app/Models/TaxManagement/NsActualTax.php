<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NsActualTax extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "tax_for",
        "tax_period",
        "tax_amount_mmk",
        "exchange_rate",
        "tax_amount_usd",
        "pay_date",
        "created_by",
        "updated_by"
    ];
}
