<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    use EncryptedAttribute;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $dates=['deleted_at'];
    protected $fillable = [
        'name',
        'noti_type',
        'email',
        'password',
        'google2fa_secret',
        'profile_id',
        'employee_name',
        'dob',
        'working_start_time',
        'working_end_time',
        'entranced_date',
        'personal_email',
        'employee_type_id',
        'branch_id',
        'department_id',
        'position_id',
        'position',
        'gender',
        'marital_status',
        'blood_type',
        'ssc_no',
        'bank_name_usd',
        'bank_account_usd',
        'bank_name_mmk',
        'bank_account_mmk',
        'passport_no',
        'date_of_issue',
        'date_of_expire',
        'active',
        'retire',
        'photo_name',
        'photo_url',
        'sign_photo_name',
        'sign_photo_url',
        'other_changing_condition',
        'check_ns_rs',
        'password_change',
        'profile_add',
        'working_day_per_week',
        'phone',
        'working_day_type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $encryptable = [
            'passport_no','google2fa_secret'
    ];
    protected $decryptable = [
            'passport_no','google2fa_secret'
    ];

    public function attendances() 
    {
        return $this->hasMany('App\Models\AttendanceManagement\Attendance', 'user_id');
    }

    
}
