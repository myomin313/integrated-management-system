<?php

namespace App\Http\Controllers\MasterManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterManagement\CarLicenseExpireNoti;
use Illuminate\Support\Facades\Validator;
use DB;

class CarLicenseNotiController extends Controller
{
     public function  create(){ 
          $data = CarLicenseExpireNoti::select()->first();
       return view('mastermanagement.car_license_noti',compact('data'));
    }
    public function store(Request $request){
        
        $request->validate([
            'first_person_email' => 'required|email',
         ]);

        
       $first_person_email = $request->first_person_email;
       $second_person_email= $request->second_person_email;

        $data = CarLicenseExpireNoti::select()->first();

                CarLicenseExpireNoti::find($data->id)->update([
                   'first_person_email'=>$first_person_email,
                   'second_person_email'=>$second_person_email,
                ]);

         return back()->with('message', 'Successfully Update!');;
    }
}