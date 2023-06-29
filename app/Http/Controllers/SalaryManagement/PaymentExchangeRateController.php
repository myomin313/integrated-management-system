<?php

namespace App\Http\Controllers\SalaryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalaryManagement\PaymentExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;

class PaymentExchangeRateController extends Controller
{
    public function index(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $start_date = Carbon::now()->format('Y-m-01');
        $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $from_date = $request->from_date?$request->from_date."-01":$start_date;
        $to_date = $request->to_date?Carbon::parse($request->to_date)->endOfMonth()->format("Y-m-d"):$end_date;


        $query = PaymentExchangeRate::select("*");
        if($from_date)
            $query->where("date",">=",$from_date);
        if($to_date)
            $query->where("date","<=",$to_date);

        $query->orderBy("date","desc");
        $exchange_rates = $query->get();
        return view('salarymanagement.paymentexchange.index',compact('exchange_rates'));
    }

    public function store(Request $request){
        $validate_array = [
            'date' => 'required',
            'usd'=>'required|numeric',
            'ot_exchange_rate'=>'required|numeric',
        ];
        $input = $request->all();
        $exchange_rate = PaymentExchangeRate::where("date","=",$input["date"]."-01")->first();
        if($exchange_rate)
            return redirect()->back()->with("success_update","Exchange Rate already exist for".Carbon::parse($exchange_rate->date)->format("F, Y"));
        $this->validate($request,$validate_array);
        
        $exchange_rate = DB::transaction(function () use ($input){
            $exchange_rate = new PaymentExchangeRate();
            $exchange_rate->date = $input["date"]."-01";
            $exchange_rate->usd = $input["usd"];
            $exchange_rate->ot_exchange_rate = $input["ot_exchange_rate"];
            $exchange_rate->payment_date = isset($input["payment_date"])?format_dbdate($input["payment_date"]):NULL;
            $exchange_rate->created_by = auth()->user()->id;
            $exchange_rate->save();

            return $exchange_rate;
        });

        return redirect()->back()->with("success_create","Successfully create new payment exchange rate!!!");
    }

    public function update(Request $request){
        $validate_array = [
            'date' => 'required',
            'usd'=>'required|numeric',
            'ot_exchange_rate'=>'required|numeric',
        ];
        //return $request->all();
        $input = $request->all();
        
        $this->validate($request,$validate_array);
        
        $exchange_rate = DB::transaction(function () use ($input){
            $exchange_rate = PaymentExchangeRate::findOrFail($input['id']);
            $exchange_rate->date = $input["date"]."-01";
            $exchange_rate->usd = $input["usd"];
            $exchange_rate->ot_exchange_rate = $input["ot_exchange_rate"];
            $exchange_rate->payment_date = isset($input["payment_date"])?format_dbdate($input["payment_date"]):NULL;
            $exchange_rate->updated_by = auth()->user()->id;
            $exchange_rate->save();

            return $exchange_rate;
        });

        return redirect()->back()->with("success_create","Successfully update the payment exchange rate!!!");
    }

    public function delete(Request $request){
        $exchange_rate = PaymentExchangeRate::findOrFail($request->id);
        $exchange_rate->delete();

        return redirect()->back()->with("success_create","Successfully delete the payment exchange rate!!!");
    }
}
