<?php

namespace App\Models\AttendanceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawAtt extends Model
{
    use HasFactory;
    protected $table = 'raw_att';
    public $timestamps = false;
}
