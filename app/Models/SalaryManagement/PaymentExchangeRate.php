<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentExchangeRate extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'usd',
        'yen',
        'ot_exchange_rate',
        'payment_date',
        'created_by',
        'updated_by'
    ];
}
