<?php

namespace App\Http\Controllers\Auth; 
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use App\Models\ResetPassword; 
use Mail; 
use Hash;
use Illuminate\Support\Str;
  
class ForgotPasswordController extends Controller
{
    /**
       * Write code on Method
       *
       * @return response()
    */
    public function showForgetPasswordForm()
    {
        $users = User::select('id','name')->get();
        return view('auth.forget_password',compact('users'));
    }
  
    /**
       * Write code on Method
       *
       * @return response()
    */
    public function submitForgetPasswordForm(Request $request)
    {
        //return $request->all();
        $request->validate([
            'user_id' => 'required',
        ]);
  
        //$token = Str::random(64);
        $code = random_int(100000, 999999);;
  
        ResetPassword::create([
            'user_id' => $request->user_id, 
            'code' => $code
        ]);
        
        $user = User::findOrFail($request->user_id);
        
        if($user->noti_type=='email'){
            Mail::send('emails.forgetPassword', ['code' => $code], function($message) use($request,$user){
                $message->to($user->email);
                $message->subject('Reset Password');
            });
        }
        else{
            $message = "Reset Code : $code\n";
            sendSMS($user->phone,$message);
        }
        $id = $user->id;
        
        return redirect()->route('otp.get',encrypt($user->id));
        
    }

    public function showOtpForm($id)
    {
        $user = User::findOrFail(decrypt($id));
        $message = 'We have sent the OTP code to your phone. Please fill the code.';
        if($user->noti_type=='email'){
            
            $message = "We have sent the OTP code to your email. Please fill the code.";
        }
        $id = $user->id;
        return view('auth.otp_form',compact('id','message'));
    }

    public function submitOtpForm(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'user_id' => 'required'
        ]);

        $reset = ResetPassword::where([['user_id','=',$request->user_id],['code','=',$request->code]])->first();
        if(!$reset){
            return redirect()->back()->with('error_msg','Wrong otp code');
        }
        return redirect()->route('reset.password.get',['code'=>encrypt($request->code),'id'=>encrypt($request->user_id)]);
        //return view('auth.otp_form');
    }
    /**
       * Write code on Method
       *
       * @return response()
    */
    public function showResetPasswordForm(Request $request) { 
        $code = $request->code;
        $user_id = $request->id;
        return view('auth.forgetPasswordLink', compact('code','user_id'));
    }
  
    /**
       * Write code on Method
       *
       * @return response()
    */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'code' => 'required',
            'password'=>'required|string|min:12|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.regex' => 'Passwords must have at least one digit (0-9), one uppercase letter (A-Z) and one none alphanumeric charactor.'
        ]);
  
        $updatePassword = ResetPassword::where([
                                'user_id' => decrypt($request->id), 
                                'code' => decrypt($request->code)
                              ])
                              ->first();
  
        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }
  
        $user = User::where('id', decrypt($request->id))->update(['password' => Hash::make($request->password)]);
 
        ResetPassword::where(['user_id'=> decrypt($request->id)])->delete();
  
        return redirect('/login')->with('reset_password', 'Your password has been changed!');
      }
}