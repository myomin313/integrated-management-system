<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterManagement\EmployeeType;
use Redirect;
use Session;

class EmployeeTypeController extends Controller
{
     public function index()
    {
        $employeetypes = EmployeeType::all();
        return view('mastermanagement.employeetypes',compact('employeetypes'));
    }
}
