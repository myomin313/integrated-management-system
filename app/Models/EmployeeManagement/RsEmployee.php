<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'second_bank_name_mmk',
        'second_bank_account_mmk',
        'final_education',
        'residant_place',
        'form_c',
        'frc_no',
        'graduation_name_of_university',
        'major',
        'mjsrv',
        'mjsrv_expire_date',
        'stay_permit',
        'stay_permit_expire_date',
        'aboard_date',
        'japan_hot_line',
        'japan_address',
        'japan_phone',
        'myanmar_address'
    ];
}
