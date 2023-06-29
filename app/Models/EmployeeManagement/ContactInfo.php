<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_person_name',
        'first_person_email',
        'first_person_phone',
        'first_person_hotline',
        'first_person_relationship',
        'first_person_address',
        'second_person_name',
        'second_person_email',
        'second_person_phone',
        'second_person_hotline',
        'second_person_relationship',
        'second_person_address',
        'created_by',
        'updated_by'
    ];
}
