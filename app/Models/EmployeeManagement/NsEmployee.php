<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class NsEmployee extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    protected $fillable = [
        'user_id',
        'hourly_rate',
        'ot_rate',
        'nrc_no',
        'religion',
        'race',
        'microsoft_word',
        'microsoft_excel',
        'microsoft_powerpoint',
        'current_address',
        'new_address',
        'new_phone',
        'others_address',
        'others_phone',
        'employment_contract_no'
    ];

    protected $encryptable = [
            'nrc'
    ];
}
