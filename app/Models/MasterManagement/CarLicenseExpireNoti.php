<?php

namespace App\Models\MasterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarLicenseExpireNoti extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_person_email',
        'second_person_email'
    ];
}
