<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBankAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates=['deleted_at'];
    protected $fillable = [
        'currency',
        'bank_id',
        'user_id',
        'bank_account',
        'created_at',
        'updated_at'
    ];
}
