@extends('layouts.master')
@section('title','Add Salary (April, 2023)')
@section("content")

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 m-auto">
            @if(count($errors)>0)
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>Whoops!</strong> There were some problems with your input.<br><br>
                  <ul>
                    @foreach($errors->all() as $error)
                      <li>{{$error}}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            @endif
            @if(session('success_create'))
              <div class="col-md-12 p-0">
                <div class="alert alert-success alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
                </div>
              </div>
            @endif
            @if(session('success_update'))
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_update')}}</strong>
                </div>
              </div>
            @endif
            <div class="card" style="padding:20px;">
              <div class="card-header">
                
                <div class="row">
                  <div class="col-sm-6">
                    <h3 class="card-title text-bold">Add Calculation (NS)</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <h6 class="text-bold">{{$month_name}}, {{$year}}</h6>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="{{route('add-salary-form.store')}}" method="post" id="create_user" class="prevent-multiple-submit">
                  @csrf
                  @php $net_salary_usd = 0;$net_salary_mmk = 0; @endphp
                  <input type="hidden" name="edit_salary" value="{{$edit_salary}}">
                  <input type="hidden" name="user_id" value="{{$user->id}}">
                  <input type="hidden" name="year" value="{{$year}}">
                  <input type="hidden" name="month" value="{{$month_name}}">
                  <input type="hidden" name="leave_from_date" value="{{$leave_from_date}}">
                  <input type="hidden" name="leave_to_date" value="{{$leave_to_date}}">
                  <input type="hidden" name="exchange_rate_usd" value="{{$exchange_rate->central_dollar}}">
                  <input type="hidden" name="defined_exchange_yen" value="{{$exchange_rate->yen}}">
                  @php
                    $payfor =$month_name.', '.$year;
                  @endphp
                  <input type="hidden" name="pay_for" value="{{$payfor}}">

                  <div class="row">
                    <div class="col-md-6">
                      
                      
                      <div class="form-group row">
                        <label for="employee_name" class="col-sm-4 col-form-label">Employee</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{$user->employee_name?$user->employee_name:$user->name}}" readonly>
                          <input type="hidden" name="user_id" value="{{$user->id}}">
                        </div>
                      </div>
                      @php
                        
                        $pre_month_name = \Carbon\Carbon::parse("$month_name, $year")->subMonth()->format("F");
                      @endphp
                      
                      <div class="form-group row">
                        <label for="payment_exchange_rate" class="col-sm-4 col-form-label">Exchange Rate (USD) </label>
                        <div class="input-group col-sm-8" data-target-input="nearest">
                          <input type="text" class="form-control" id="exchange_rate_usd" name="payment_exchange_rate" value="{{$exchange_rate->dollar}}" readonly>
                          <div class="input-group-append">
                            <div class="input-group-text">MMK</div>
                          </div>
                        </div>
                      </div>
                      <input type="hidden" class="form-control" id="exchange_rate_yen" name="exchange_rate_yen" value="{{$exchange_rate->yen}}">
                      
                      <input type="hidden" name="monthly_salary" id="monthly_salary" value="{{$monthly_salary}}">
                      <div class="form-group row">
                        <label for="salary_exchange_rate" class="col-sm-4 col-form-label">Salary </label>
                        <div class="col-sm-8">
                          <table id="salary_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="salary_exchange_rate0" name="salary_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateSalaryMMK(0)" onchange="calculateSalaryMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="salary_usd_amount0" name="salary_usd_amount[]" value="{{$monthly_salary}}" onkeyup="calculateSalaryMMK(0)" onchange="calculateSalaryMMK(0)">
                                </td>
                                <td>
                                  @php
                                    $mmk_amount = $exchange_rate->dollar * $monthly_salary;

                                    $net_salary_usd += $monthly_salary;
                                    $net_salary_mmk += $mmk_amount;
                                  @endphp
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="salary_mmk_amount0" name="salary_mmk_amount[]" value="{{$mmk_amount}}" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-salary" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="salary_usd_label">{{$monthly_salary}}</strong>
                                  <input type="hidden" class="form-control" id="salary_usd" name="salary_usd" value="{{$monthly_salary}}">
                                </td>
                                <td>
                                  <strong id="salary_mmk_label">{{$mmk_amount}}</strong>
                                  <input type="hidden" class="form-control" id="salary_mmk" name="salary_mmk" value="{{$mmk_amount}}">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="kbz_exchange_rate" class="col-sm-4 col-form-label">KBZ Opening A/C </label>
                        <div class="col-sm-8">
                          <table id="kbz_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="kbz_exchange_rate0" name="kbz_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateKBZMMK(0)" onchange="calculateKBZMMK(0)">
                                </td>
                                 <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="kbz_usd_amount0" name="kbz_usd_amount[]" value="" onkeyup="calculateKBZMMK(0)" onchange="calculateKBZMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="kbz_mmk_amount0" name="kbz_mmk_amount[]" value="" readonly>
                                </td>
                               
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-kbz" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>

                                <td>
                                  <strong id="kbz_opening_usd_label">0</strong>
                                  <input type="hidden" class="form-control" id="kbz_opening_usd" name="kbz_opening_usd" value="0">
                                </td>
                                <td>
                                  <strong id="kbz_opening_mmk_label">0</strong>
                                  <input type="hidden" class="form-control" id="kbz_opening_mmk" name="kbz_opening_mmk" value="0">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="ssc_exchange_rate" class="col-sm-4 col-form-label">SSC <br><br>
                          <div class="form-check" onclick="changeSSC()">
                            <input type="checkbox" id="no_ssc" class="form-check-input" name="no_ssc" value="1" style="width:17px;height:17px;"><label class="form-check-label" for="retire">Not Deduct SSC</label>
                          </div>
                        </label>

                        <div class="col-sm-8">
                          <table>
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="ssc_exchange" name="ssc_exchange" value="{{$exchange_rate->dollar}}" onkeyup="calculateSSC()" onchange="calculateSSC()">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="mmk_ssc" name="mmk_ssc" value="{{$ssc_amount}}"  onkeyup="calculateSSC()" onchange="calculateSSC()">
                                </td>
                                <td>
                                  @php
                                    $usd_amount = round($ssc_amount / $exchange_rate->dollar);

                                  @endphp
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="usd_ssc" name="usd_ssc" value="{{$usd_amount}}" readonly>
                                </td>
                                
                              </tr>
                            </tbody>
                          </table>
                          <table id="ssc_info">
                            <tbody>
                              
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="ssc_exchange_rate0" name="ssc_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateSSCMMK(0)" onchange="calculateSSCMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="ssc_usd_amount0" name="ssc_usd_amount[]" value="{{$usd_amount}}"   onkeyup="calculateSSCMMK(0)" onchange="calculateSSCMMK(0)">
                                </td>
                                <td>
                                  @php
                                    $mmk_amount = $usd_amount * $exchange_rate->dollar;

                                    $net_salary_usd -= $usd_amount;
                                    $net_salary_mmk -= $mmk_amount;
                                  @endphp
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="ssc_mmk_amount0" name="ssc_mmk_amount[]" value="{{$mmk_amount}}" readonly>
                                </td>
                                
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-ssc" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="ssc_usd_label">{{$usd_amount}}</strong>
                                  <input type="hidden" class="form-control" id="ssc_usd" name="ssc_usd" value="{{$usd_amount}}">
                                </td>
                                <td>
                                  <strong id="ssc_mmk_label">{{$mmk_amount}}</strong>
                                  <input type="hidden" class="form-control" id="ssc_mmk" name="ssc_mmk" value="{{$mmk_amount}}">
                                </td>
                                
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="ssc_remark" class="col-sm-4 col-form-label">SSC Remark </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="ssc_remark" name="ssc_remark">
                        </div>
                      </div>
                      @php
                        $ot_payment = getOTPayment($user->id,siteformat_date($previous_month.'-01'));
                      @endphp
                      <div class="form-group row">
                        <label for="ot_rate_usd" class="col-sm-4 col-form-label">OT Rate ($) </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="ot_rate_usd" name="ot_rate_usd" required>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="previous_total_ot_payment" class="col-sm-4 col-form-label">OT Payment ({{$pre_month_name}}) ($)</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="previous_total_ot_payment" name="previous_total_ot_payment" onkeyup="calculateOTAmount()" onchange="calculateOTAmount()">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="previous_taxi_charge_usd" class="col-sm-4 col-form-label">Taxi Charge ({{$pre_month_name}}) ($)</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="previous_taxi_charge_usd" name="previous_taxi_charge_usd"  onkeyup="calculateOTAmount()" onchange="calculateOTAmount()">
                        </div>
                      </div>

                      
                      
                      <div class="form-group row">
                        <label for="ot_exchange_rate" class="col-sm-4 col-form-label">Total OT Payment </label>
                        <div class="col-sm-8">
                          <table id="ot_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="ot_exchange_rate0" name="ot_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateOTMMK(0)" onchange="calculateOTMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="ot_usd_amount0" name="ot_usd_amount[]" value="" onkeyup="calculateOTMMK(0)" onchange="calculateOTMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="ot_mmk_amount0" name="ot_mmk_amount[]" value="" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-ot" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="ot_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="ot_usd" name="ot_usd" value="">
                                </td>
                                <td>
                                  <strong id="ot_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="ot_mmk" name="ot_mmk" value="">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="daily_rate_usd" class="col-sm-4 col-form-label">Daily Rate ($) </label>
                        <div class="col-sm-8">
                          @php
                            $end_day = \Carbon\Carbon::parse("$month_name, $year")->endOfMonth()->format("d");
                            $daily_rate = floor_up($monthly_salary / $end_day,2);
                          @endphp
                          <input type="text" class="form-control" id="daily_rate_usd" name="daily_rate_usd" value="{{$daily_rate}}" readonly>
                        </div>
                      </div>
                      <input type="hidden" name="end_day" id="end_day" value="{{$end_day}}">
                      <div class="form-group row">
                        <label for="leave_exchange_rate" class="col-sm-4 col-form-label">Without Pay Leave </label>
                        <div class="input-group col-sm-8" data-target-input="nearest">
                          <input type="text" class="form-control" id="unpaid_leave_day" name="unpaid_leave_day" value="" onkeyup="calculateLeaveAmount()" onchange="calculateLeaveAmount()">
                          <div class="input-group-append">
                            <div class="input-group-text">Days</div>
                          </div>
                          
                          <table id="leave_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="leave_exchange_rate0" name="leave_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateLeaveMMK(0)" onchange="calculateLeaveMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="leave_usd_amount0" name="leave_usd_amount[]" value="" onkeyup="calculateLeaveMMK(0)" onchange="calculateLeaveMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="leave_mmk_amount0" name="leave_mmk_amount[]" value="" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-leave" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="leave_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="leave_usd" name="leave_usd" value="">
                                </td>
                                <td>
                                  <strong id="leave_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="leave_mmk" name="leave_mmk" value="">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      <hr style="border: 1px solid #999;">
                      <div class="form-group row">
                        <label for="adjustment_exchange_rate" class="col-sm-9 col-form-label">Adjustment </label>
                        <label for="allowance_name" class="col-sm-3 col-form-label"> 
                          <a class="btn-success add-adjustment" title="Add row" style="padding:5px 10px;"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;padding: 10px;"></i></a>
                        </label>
                        <div class="col-sm-12">
                          <table id="adjustment_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Month</strong><br>
                                  
                                  <input type="month" class="form-control" id="adjustment_month0" name="adjustment_month[]" max="{{\Carbon\Carbon::now()->format('Y-m')}}">
                                </td>
                                <td>
                                  <strong>Type</strong><br>
                                  <select name="adjustment_type[]" id="adjustment_type0" class="form-control" style="width: 100px;">
                                    <option value="Salary">Salary</option>
                                    <option value="Overtime">Overtime</option>
                                  </select>
                                  
                                </td>
                                
                                {{-- <td>
                                  <strong>Description</strong><br>
                                  <input type="text" class="form-control" id="adjustment_name0" name="adjustment_name[]" value="">
                                </td> --}}
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="adjustment_exchange_rate0" name="adjustment_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateAdjustmentMMK(0)" onchange="calculateAdjustmentMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="adjustment_usd_amount0" name="adjustment_usd_amount[]" value="" onkeyup="calculateAdjustmentMMK(0)" onchange="calculateAdjustmentMMK(0)">
                                </td>
                                <td>
                                  @php
                                    $mmk_amount = 0;
                                  @endphp
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="adjustment_mmk_amount0" name="adjustment_mmk_amount[]" value="{{$mmk_amount}}" readonly>
                                </td>
                                <td>
                                  <strong style="visibility:hidden;">Action</strong><br>
                                  <div class="delete-row btn btn-danger remove" onclick="delete_Row_Adjustment(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                                
                              </tr>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th colspan="3">Total</th>
                                <td>
                                  <strong id="adjustment_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="adjustment_usd" name="adjustment_usd" value="">
                                </td>
                                <td>
                                  <strong id="adjustment_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="adjustment_mmk" name="adjustment_mmk" value="">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      <hr style="border: 1px solid #999;">
                      <div class="form-group row">
                        <label for="estimated_percent" class="col-sm-4 col-form-label">Estimated Percent for Income Tax <span class="required text-danger">*</span></label>
                        <div class="col-sm-8 input-group input-group-md mb-3">
                          <input type="text" class="form-control" id="estimated_percent" name="estimated_percent" value="" onkeyup="calculateIncomeTax()" onchange="calculateIncomeTax()" required>
                          <div class="input-group-append">
                            <!-- <div class="input-group-text">Days</div> -->
                            <select class="form-control" id="estimated_type" name="estimated_type" onchange="calculateIncomeTax()">
                              <option value="percent"><strong>%</strong></option>
                              <option value="usd"><strong>USD</strong></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      

                      <div class="form-group row">
                        <label for="tax_exchange_rate" class="col-sm-4 col-form-label">Tax Per Month </label>
                        <div class="col-sm-8">
                          <table id="tax_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="tax_exchange_rate0" name="tax_exchange_rate" value="{{$exchange_rate->dollar}}" onkeyup="calculateTaxMMK(0)" onchange="calculateTaxMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="tax_usd_amount0" name="tax_usd_amount" value="" onkeyup="calculateTaxMMK(0)" onchange="calculateTaxMMK(0)" readonly>
                                </td>
                                <td>
                                  @php
                                    $mmk_amount = 0;
                                  @endphp
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="tax_mmk_amount0" name="tax_mmk_amount" value="{{$mmk_amount}}" readonly>
                                </td>
                                
                              </tr>
                            </tbody>

                              
                          </table>
                          
                        </div>
                      </div>
                    </div>

                    <!-- right side -->
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label for="monthly_paye_remark" class="col-sm-4 col-form-label">Monthly PAYE Remark </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="monthly_paye_remark" name="monthly_paye_remark">
                        </div>
                      </div>
                      <hr style="border: solid 1px #999;">
                      <!-- Other Allowance -->
                      <div class="form-group row">
                        <label for="allowance_name" class="col-sm-9 col-form-label">Other Allowance </label>
                        <label for="allowance_name" class="col-sm-3 col-form-label"> 
                          <a class="btn-success add-allowance" title="Add row" style="padding:5px 10px;"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;padding: 10px;"></i></a>
                        </label>
                        <div class="col-sm-12">
                          <table id="allowance_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Description</strong><br>
                                  <input type="text" class="form-control" id="allowance_name0" name="allowance_name[]">
                                </td>
                                <td>
                                  <strong>Amount</strong><br>
                                  <input type="text" class="form-control" id="allowance_amount0" name="allowance_amount[]" onkeyup="calculateTotalAllowance()" onchange="calculateTotalAllowance()">
                                </td>
                                <td>
                                  <strong>Currency</strong><br>
                                  <select name="allowance_currency[]" id="allowance_currency0" class="form-control" onchange="calculateTotalAllowance()">
                                    <option value="usd">USD</option>
                                    <option value="mmk">MMK</option>
                                  </select>
                                </td>
                                <td>
                                  <strong style="visibility:hidden;">Action</strong><br>
                                  <div class="delete-row btn btn-danger remove" onclick="delete_Row_Allowance(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>
                              
                          </table>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="leave_exchange_rate" class="col-sm-4 col-form-label">Total Allowance </label>
                        <div class="col-sm-8">
                          <table>
                            <tr>
                              <td>
                                <strong>USD</strong>
                                <input type="text" class="form-control" id="total_allowance_usd" name="total_allowance_usd" value="" readonly>
                              </td>
                              <td>
                                <strong>MMK</strong>
                                <input type="text" class="form-control" id="total_allowance_mmk" name="total_allowance_mmk" value="" readonly>
                              </td>
                            </tr>
                          </table>                          
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="usd_allowance_exchange_rate" class="col-sm-4 col-form-label">Total Allowance (USD) </label>
                        <div class="col-sm-8">
                          <table id="usd_allowance_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="usd_allowance_exchange_rate0" name="usd_allowance_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateAllowanceMMK(0)" onchange="calculateAllowanceMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="usd_allowance_usd_amount0" name="usd_allowance_usd_amount[]" value="" onkeyup="calculateAllowanceMMK(0)" onchange="calculateAllowanceMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="usd_allowance_mmk_amount0" name="usd_allowance_mmk_amount[]" value="" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-usd-allowance" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="usd_allowance_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="usd_allowance_usd" name="usd_allowance_usd" value="">
                                </td>
                                <td>
                                  <strong id="usd_allowance_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="usd_allowance_mmk" name="usd_allowance_mmk" value="">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      {{-- <div class="form-group row">
                        <label for="mmk_allowance_exchange_rate" class="col-sm-4 col-form-label">Total Allowance (MMK) </label>
                        <div class="col-sm-8">
                          <table id="mmk_allowance_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="mmk_allowance_exchange_rate0" name="mmk_allowance_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateAllowanceUSD(0)" onchange="calculateAllowanceUSD(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="mmk_allowance_mmk_amount0" name="mmk_allowance_mmk_amount[]" value=""  onkeyup="calculateAllowanceUSD(0)" onchange="calculateAllowanceUSD(0)">
                                </td>
                                <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="mmk_allowance_usd_amount0" name="mmk_allowance_usd_amount[]" value="" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-mmk-allowance" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="mmk_allowance_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="mmk_allowance_mmk" name="mmk_allowance_mmk" value="">
                                </td>
                                <td>
                                  <strong id="mmk_allowance_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="mmk_allowance_usd" name="mmk_allowance_usd" value="">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div> --}}
                      <!-- Other Allowance -->

                      <hr style="border: solid 1px #999;">
                      <!-- Other Deduction -->
                      <div class="form-group row">
                        <label for="deduction_name" class="col-sm-9 col-form-label">Other 
                        deduction </label>
                        <label for="deduction_name" class="col-sm-3 col-form-label"> 
                          <a class="btn-success add-deduction" title="Add row" style="padding:5px 10px;"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;padding: 10px;"></i></a>
                        </label>
                        <div class="col-sm-12">
                          <table id="deduction_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Description</strong><br>
                                  <input type="text" class="form-control" id="deduction_name0" name="deduction_name[]">
                                </td>
                                <td>
                                  <strong>Amount</strong><br>
                                  <input type="text" class="form-control" id="deduction_amount0" name="deduction_amount[]" onkeyup="calculateTotalDeduction()" onchange="calculateTotalDeduction()">
                                </td>
                                <td>
                                  <strong>Currency</strong><br>
                                  <select name="deduction_currency[]" id="deduction_currency0" class="form-control" onchange="calculateTotalDeduction()">
                                    <option value="usd">USD</option>
                                    <option value="mmk">MMK</option>
                                  </select>
                                </td>
                                <td>
                                  <strong style="visibility:hidden;">Action</strong><br>
                                  <div class="delete-row btn btn-danger remove" onclick="delete_Row_Deduction(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>
                              
                          </table>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="leave_exchange_rate" class="col-sm-4 col-form-label">Total Deduction </label>
                        <div class="col-sm-8">
                          <table>
                            <tr>
                              <td>
                                <strong>USD</strong>
                                <input type="text" class="form-control" id="total_deduction_usd" name="total_deduction_usd" value="" readonly>
                              </td>
                              <td>
                                <strong>MMK</strong>
                                <input type="text" class="form-control" id="total_deduction_mmk" name="total_deduction_mmk" value="" readonly>
                              </td>
                            </tr>
                          </table>                          
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="usd_deduction_exchange_rate" class="col-sm-4 col-form-label">Total Deduction (USD) </label>
                        <div class="col-sm-8">
                          <table id="usd_deduction_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="usd_deduction_exchange_rate0" name="usd_deduction_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateDeductionMMK(0)" onchange="calculateDeductionMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="usd_deduction_usd_amount0" name="usd_deduction_usd_amount[]" value="" onkeyup="calculateDeductionMMK(0)" onchange="calculateDeductionMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="usd_deduction_mmk_amount0" name="usd_deduction_mmk_amount[]" value="" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-usd-deduction" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="usd_deduction_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="usd_deduction_usd" name="usd_deduction_usd" value="">
                                </td>
                                <td>
                                  <strong id="usd_deduction_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="usd_deduction_mmk" name="usd_deduction_mmk" value="">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      {{-- <div class="form-group row">
                        <label for="mmk_deduction_exchange_rate" class="col-sm-4 col-form-label">Total Deduction (MMK) </label>
                        <div class="col-sm-8">
                          <table id="mmk_deduction_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="mmk_deduction_exchange_rate0" name="mmk_deduction_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateDeductionUSD(0)" onchange="calculateDeductionUSD(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="mmk_deduction_mmk_amount0" name="mmk_deduction_mmk_amount[]" value=""  onkeyup="calculateDeductionUSD(0)" onchange="calculateDeductionUSD(0)">
                                </td>
                                <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="mmk_deduction_usd_amount0" name="mmk_deduction_usd_amount[]" value="" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-mmk-deduction" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="mmk_deduction_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="mmk_deduction_mmk" name="mmk_deduction_mmk" value="">
                                </td>
                                <td>
                                  <strong id="mmk_deduction_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="mmk_deduction_usd" name="mmk_deduction_usd" value="">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div> --}}
                      <!-- Other Deduction -->


                      <hr style="border: solid 1px #999;">
                      <!-- Bonus -->
                      <div class="form-group row">
                        <label for="leave_exchange_rate" class="col-sm-4 col-form-label">Bonus </label>
                        <div class="col-sm-8">
                          <select name="bonus_name" id="bonus_name" class="form-control" onchange="calculateBonus()">
                            <option value="NULL">-Select-</option>
                            <option value="Thadingyut">Thadingyut Bonus</option>
                            <option value="Thingyan">Thingyan Bonus</option>
                            <option value="Other">Other</option>
                          </select>
                          @if(isDriver($user->id) or isAssistant($user->id))
                          <input type="hidden" name="driver_assistant" id="driver_assistant" value="yes">
                          @else
                          <input type="hidden" name="driver_assistant" id="driver_assistant" value="no">
                          @endif
                          <input type="hidden" name="joined_year" id="joined_year" value="{{getDifferentDate($user->entranced_date,'year')}}">
                          <input type="hidden" name="joined_month" id="joined_month" value="{{getDifferentDate($user->entranced_date,'month')}}">
                          <input type="hidden" name="employee_type" id="employee_type" value="ns">
                          <input type="hidden" name="performance" id="performance" value="{{getPerformanceNumber($performance)}}">
                          <table id="bonus_info">
                            <tbody>
                              <tr id="other_bonus" style="display: none;">
                                <td colspan="2">
                                  <strong>Description</strong><br>
                                  <input type="text" class="form-control" id="bonus_description" name="bonus_description" value="">
                                </td>
                                <td colspan="2">
                                  <strong>Amount ($) </strong><br>
                                  <input type="text" class="form-control" id="bonus_amount" name="bonus_amount" value="" onkeyup="changeBonus()" onchange="changeBonus()">
                                </td>
                                
                                
                              </tr>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="bonus_exchange_rate0" name="bonus_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateBonusMMK(0)" onchange="calculateBonusMMK(0)">
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="bonus_usd_amount0" name="bonus_usd_amount[]" value="" onkeyup="calculateBonusMMK(0)" onchange="calculateBonusMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="bonus_mmk_amount0" name="bonus_mmk_amount[]" value="" readonly>
                                </td>
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-bonus" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="bonus_usd_label"></strong>
                                  <input type="hidden" class="form-control" id="bonus_usd" name="bonus_usd" value="">
                                </td>
                                <td>
                                  <strong id="bonus_mmk_label"></strong>
                                  <input type="hidden" class="form-control" id="bonus_mmk" name="bonus_mmk" value="">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      
                      <!-- Bonus -->

                      <!-- Net Salary -->
                      <div class="form-group row">
                        <label for="net_salary_usd" class="col-sm-4 col-form-label">Net Payment (USD) </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="net_salary_usd" name="net_salary_usd" value="{{$net_salary_usd}}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="net_salary_usd" class="col-sm-4 col-form-label">Net Payment (MMK) </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="net_salary_mmk" name="net_salary_mmk" value="{{$net_salary_mmk}}" readonly>
                        </div>
                      </div>
                      <!-- Net Salary -->
                      <div class="form-group row">
                        <label for="transfer_usd_acc" class="col-sm-4 col-form-label">Transfer To USD A/C </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_usd_acc" name="transfer_usd_acc">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="transfer_mmk_acc" class="col-sm-4 col-form-label">Transfer To MMK A/C </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_mmk_acc" name="transfer_mmk_acc">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="transfer_usd_cash" class="col-sm-4 col-form-label">Transfer To USD Cash </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_usd_cash" name="transfer_usd_cash">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="transfer_mmk_cash" class="col-sm-4 col-form-label">Transfer To MMK Cash </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_mmk_cash" name="transfer_mmk_cash">
                        </div>
                      </div>

                    </div>
                  </div>
                    
                  <hr style="border: 1px solid #999;">
                  <div class="form-group col-sm-12 text-center">
                    <button type="submit" class="btn btn-success" name="save_new">Save</button>
                    <a href="{{url('salary-management/add-salary')}}" class="btn btn-primary">Cancel</a>
                  </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
  
      
    </section>
    <!-- /.content -->

@stop
@section('script')
<script src="{{asset('dist/js/salary.js')}}"></script>
<script>
    $(function () {
      
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        })

   
        $('#datetimepicker').datetimepicker({
            format: 'YYYY'
        });
        $('#datetimepicker1').datetimepicker({
            format: 'MMMM',
            viewMode: "months",
        });
            
    });

    function calculateTotalOTHrAndPayment(){
      if($("#retire").is(":checked")){
        $("#current_ot_hr_section").show();
        $("#current_ot_payment_section").show();

        var total_hr = $("#previous_current_ot_hr").val();
        
        $("#total_ot_hr").val(total_hr);

        var total_payment = $("#previous_current_ot_payment").val();
        var exchange_rate = $("#ot_exchange_rate0").val();
        var total_payment_mmk = Number(total_payment) * Number(exchange_rate);
        
        $("#ot_usd_amount0").val(total_payment);
        $("#ot_mmk_amount0").val(total_payment_mmk);
      }
      else{
        $("#current_ot_hr_section").hide();
        $("#current_ot_payment_section").hide();

        var total_hr = $("#previous_ot_hr").val();
        
        $("#total_ot_hr").val(total_hr);

        var total_payment = $("#previous_ot_payment").val();
        var exchange_rate = $("#ot_exchange_rate0").val();
        var total_payment_mmk = Number(total_payment) * Number(exchange_rate);
        
        $("#ot_usd_amount0").val(total_payment);
        $("#ot_mmk_amount0").val(total_payment_mmk);
      }

      total_OT();
    }

    function changeSSC(){
      if($("#no_ssc").is(":checked")){
        $("#usd_ssc").val(0);
        $("#mmk_ssc").val(0);
        $("#ssc_usd_amount0").val(0);
        $("#ssc_mmk_amount0").val(0);
        calculateSSCMMK(0);
      }
      else{
        calculateSSC();
      }
    }

    function decimalToTime(dec){
      var decimalTimeString = dec;
      var decimalTime = parseFloat(decimalTimeString);
      decimalTime = decimalTime * 60 * 60;
      var hours = Math.floor((decimalTime / (60 * 60)));
      decimalTime = decimalTime - (hours * 60 * 60);
      var minutes = Math.floor((decimalTime / 60));
      decimalTime = decimalTime - (minutes * 60);
      var seconds = Math.round(decimalTime);
      if(hours < 10)
      {
        hours = "0" + hours;
      }
      if(minutes < 10)
      {
        minutes = "0" + minutes;
      }
      if(seconds < 10)
      {
        seconds = "0" + seconds;
      }
      return hours + ":" + minutes;
    }
    function calculateSalaryMMK($i){
      var exchange_rate = $("#salary_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#salary_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#salary_mmk_amount"+$i).val(amount);
      total_Salary();
    
    }

    function calculateKBZMMK($i){
      var exchange_rate = $("#kbz_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#kbz_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#kbz_mmk_amount"+$i).val(amount);
      total_KBZ();
    
    }
    function calculateSSCExchange($i){
      var exchange_rate = $("#ssc_exchange_rate"+$i).val();

      if(isNaN(exchange_rate))
        exchange_rate =  0;
      if($i==0){
        var monthly_salary = $("#monthly_salary").val();
        var monthly_salary_mmk = (Number(monthly_salary) * Number(exchange_rate));
        if(monthly_salary_mmk>=300000)
          monthly_salary_mmk = 300000;

        var ssc_amount = monthly_salary_mmk * 2 /100;
        $("#ssc_mmk_amount"+$i).val(ssc_amount);
      }
        

      var mmk_amount = $("#ssc_mmk_amount"+$i).val();
      if(isNaN(mmk_amount))
        mmk_amount =  0;

      var amount = 0;
      if(exchange_rate>0)
        amount = Number(mmk_amount) / Number(exchange_rate);

      $("#ssc_usd_amount"+$i).val(amount.toFixed());
      total_SSC();
    
    }

    function calculateSSC(){
      var exchange_rate = $("#ssc_exchange").val();

      if(isNaN(exchange_rate))
        exchange_rate =  0;
     
      var monthly_salary = $("#monthly_salary").val();
      var ot_amount = $("#ot_usd").val();
      var monthly_salary_mmk = (Number(monthly_salary) * Number(exchange_rate)) + (Number(ot_amount) * Number(exchange_rate));
      if(monthly_salary_mmk>=300000)
          monthly_salary_mmk = 300000;

      var ssc_amount = monthly_salary_mmk * 2 /100;
      $("#mmk_ssc").val(ssc_amount.toFixed());
     

      var amount = 0;
      if(exchange_rate>0)
        amount = Number(ssc_amount) / Number(exchange_rate);
      $("#usd_ssc").val(amount.toFixed());

      $("#ssc_usd_amount0").val(amount.toFixed());
      calculateSSCMMK(0);
    
    }
    function calculateSSCMMK($i){
      var exchange_rate = $("#ssc_exchange_rate"+$i).val();

      if(isNaN(exchange_rate))
        exchange_rate =  0;
      
      var usd_amount = $("#ssc_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = 0;
      if(exchange_rate>0)
        amount = Number(usd_amount) * Number(exchange_rate);

      $("#ssc_mmk_amount"+$i).val(amount.toFixed());
      total_SSC();
    
    }
    function calculateSSCUSD($i){
      var exchange_rate = $("#ssc_exchange_rate"+$i).val();

      if(isNaN(exchange_rate))
        exchange_rate =  0;
      
      var mmk_amount = $("#ssc_mmk_amount"+$i).val();
      if(isNaN(mmk_amount))
        mmk_amount =  0;

      var amount = 0;
      if(exchange_rate>0)
        amount = Number(mmk_amount) / Number(exchange_rate);

      $("#ssc_usd_amount"+$i).val(amount.toFixed());
      total_SSC();
    
    }

    function calculateOTAmount(){
      var previous_total_ot_payment = $("#previous_total_ot_payment").val();
      if(isNaN(previous_total_ot_payment))
        previous_total_ot_payment =  0;

      var previous_taxi_charge_usd = $("#previous_taxi_charge_usd").val();
      if(isNaN(previous_taxi_charge_usd))
        previous_taxi_charge_usd =  0;

      var total_ot_payment = Number(previous_total_ot_payment) + Number(previous_taxi_charge_usd);

      var exchange_rate = $("#ot_exchange_rate0").val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var amount = Number(exchange_rate) * Number(total_ot_payment);

      $("#ot_usd_amount0").val(total_ot_payment);
      $("#ot_mmk_amount0").val(amount);
      total_OT();
      calculateSSC();
    }
    function calculateOTMMK($i){
      var exchange_rate = $("#ot_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#ot_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#ot_mmk_amount"+$i).val(amount);
      total_OT();
    
    }

    function calculateLeaveAmount(){
      var monthly_salary = Number($("#monthly_salary").val());
      var end_day = Number($("#end_day").val());
      var total_leave_day = Number($("#unpaid_leave_day").val());
      var leave_amount = (monthly_salary / end_day) * total_leave_day;
      $("#leave_usd_amount0").val(Math.floor(leave_amount * 100) / 100);
      calculateLeaveMMK(0);
    }
    function calculateLeaveMMK($i){
      var exchange_rate = $("#leave_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#leave_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#leave_mmk_amount"+$i).val(amount.toFixed(2));
      total_Leave();
    
    }

    function calculateAdjustmentMMK($i){
      var exchange_rate = $("#adjustment_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#adjustment_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#adjustment_mmk_amount"+$i).val(amount);
      total_Adjustment();
      //calculateNetSalary();
    }

    function calculateTaxMMK($i){
      var exchange_rate = $("#tax_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#tax_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#tax_mmk_amount"+$i).val(amount.toFixed());
      //total_Leave();
      calculateNetSalary();
    
    }

    function calculateIncomeTax(){
      var salary = $("#salary_usd").val();
      if(isNaN(salary))
        salary =  0;

      var ot = $("#ot_usd").val();
      var previous_taxi_charge = $("#previous_taxi_charge_usd").val()
      var current_taxi_charge = $("#current_taxi_charge_usd").val()
      if(isNaN(ot))
        ot =  0;
      if(isNaN(previous_taxi_charge))
        previous_taxi_charge =  0;
      if(isNaN(current_taxi_charge))
        current_taxi_charge =  0;

      ot = Number(ot) - Number(previous_taxi_charge) - Number(current_taxi_charge);

      var leave = $("#leave_usd").val();
      if(isNaN(leave))
        leave =  0;

      var adjustment = $("#adjustment_usd").val();
      if(isNaN(adjustment))
        adjustment =  0;

      var bonus = $("#bonus_usd").val();
      if(isNaN(bonus))
        bonus =  0;

      var percent = $("#estimated_percent").val();
      if(isNaN(percent))
        percent =  0;

      var total = Number(salary) + Number(ot) - Number(leave) + Number(adjustment) + Number(bonus);
      
      var type = $("#estimated_type option:selected").val();
      if(type=="percent"){
        var income_tax = Number(total) * Number(percent) / 100;

        $("#tax_usd_amount0").val(income_tax.toFixed());
      }
      else{
        var income_tax = Number(percent);

        $("#tax_usd_amount0").val(income_tax.toFixed());
        
      }
      calculateTaxMMK(0);
      
    }

    function calculateTotalAllowance(){

      var total_usd = 0.00;
      var total_mmk = 0.00;
      var amount = 0.00;  

      //calculate total amount
      $('input[name="allowance_amount[]"]').each(function(){
        amount = $(this).val();
        var rowid = $(this).attr('id');
        var row = rowid.replace("allowance_amount", "");
        var currency = $('#allowance_currency'+row+" option:selected").val();
        
        if(amount && !isNaN(amount)){
          if(currency=='usd')
            total_usd = total_usd + parseFloat(amount);
          else
            total_mmk = total_mmk + parseFloat(amount);
        }

        
        $('#total_allowance_usd').val(total_usd);
        $('#total_allowance_mmk').val(total_mmk);

        //total allowance usd
        $("#usd_allowance_usd_amount0").val(total_usd);
        var mmk_exchange = Number(total_usd) * Number($("#usd_allowance_exchange_rate0").val());
        $("#usd_allowance_mmk_amount0").val(mmk_exchange);

        $("#usd_allowance_usd_label").html(total_usd);
        $("#usd_allowance_usd").val(total_usd);

        $("#usd_allowance_mmk_label").html(mmk_exchange);
        $("#usd_allowance_mmk").val(mmk_exchange);

        //total allowance mmk
        // $("#mmk_allowance_mmk_amount0").val(total_mmk);
        
        // var usd_exchange = Number(total_mmk) / Number($("#mmk_allowance_exchange_rate0").val());
        // $("#mmk_allowance_usd_amount0").val(usd_exchange.toFixed(2));

        // $("#mmk_allowance_usd_label").html(usd_exchange.toFixed(2));
        // $("#mmk_allowance_usd").val(usd_exchange.toFixed(2));

        // $("#mmk_allowance_mmk_label").html(total_mmk);
        // $("#mmk_allowance_mmk").val(total_mmk);

        calculateNetSalary();
        
      });
      
    }

    function calculateAllowanceMMK($i){
      var exchange_rate = $("#usd_allowance_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#usd_allowance_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#usd_allowance_mmk_amount"+$i).val(amount);
      total_USD_Allowance();
    
    }

    function calculateAllowanceUSD($i){
      var exchange_rate = $("#mmk_allowance_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var mmk_amount = $("#mmk_allowance_mmk_amount"+$i).val();
      if(isNaN(mmk_amount))
        mmk_amount =  0;

      var amount = 0;
      if(exchange_rate>0)
        amount = Number(mmk_amount) / Number(exchange_rate);

      $("#mmk_allowance_usd_amount"+$i).val(amount.toFixed(2));
      total_MMK_Allowance();
    
    }

    function calculateTotalDeduction(){

      var total_usd = 0.00;
      var total_mmk = 0.00;
      var amount = 0.00;  

      //calculate total amount
      $('input[name="deduction_amount[]"]').each(function(){
        amount = $(this).val();
        var rowid = $(this).attr('id');
        var row = rowid.replace("deduction_amount", "");
        var currency = $('#deduction_currency'+row+" option:selected").val();
        
        if(amount && !isNaN(amount)){
          if(currency=='usd')
            total_usd = total_usd + parseFloat(amount);
          else
            total_mmk = total_mmk + parseFloat(amount);
        }

        
        $('#total_deduction_usd').val(total_usd);
        $('#total_deduction_mmk').val(total_mmk);

        //total deduction usd
        $("#usd_deduction_usd_amount0").val(total_usd);
        var mmk_exchange = Number(total_usd) * Number($("#usd_deduction_exchange_rate0").val());
        $("#usd_deduction_mmk_amount0").val(mmk_exchange);

        $("#usd_deduction_usd_label").html(total_usd);
        $("#usd_deduction_usd").val(total_usd);

        $("#usd_deduction_mmk_label").html(mmk_exchange);
        $("#usd_deduction_mmk").val(mmk_exchange);

        //total deduction mmk
        // $("#mmk_deduction_mmk_amount0").val(total_mmk);
        
        // var usd_exchange = Number(total_mmk) / Number($("#mmk_deduction_exchange_rate0").val());
        // $("#mmk_deduction_usd_amount0").val(usd_exchange.toFixed(2));

        // $("#mmk_deduction_usd_label").html(usd_exchange.toFixed(2));
        // $("#mmk_deduction_usd").val(usd_exchange.toFixed(2));

        // $("#mmk_deduction_mmk_label").html(total_mmk);
        // $("#mmk_deduction_mmk").val(total_mmk);

        calculateNetSalary();
        
      });
      
    }

    function calculateDeductionMMK($i){
      var exchange_rate = $("#usd_deduction_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#usd_deduction_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#usd_deduction_mmk_amount"+$i).val(amount);
      total_USD_Deduction();
    
    }

    function calculateDeductionUSD($i){
      var exchange_rate = $("#mmk_deduction_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var mmk_amount = $("#mmk_deduction_mmk_amount"+$i).val();
      if(isNaN(mmk_amount))
        mmk_amount =  0;

      var amount = 0;
      if(exchange_rate>0)
        amount = Number(mmk_amount) / Number(exchange_rate);

      $("#mmk_deduction_usd_amount"+$i).val(amount.toFixed(2));
      total_MMK_Deduction();
    
    }

    function changeBonus() {
      var bonus = $("#bonus_amount").val();
      var exchange = Number($("#bonus_exchange_rate0").val());
      var mmk_bonus = exchange * Number(bonus);

      $("#bonus_usd_amount0").val(bonus);
      $("#bonus_usd_label").html(bonus);
      $("#bonus_usd").val(bonus);

      $("#bonus_mmk_amount0").val(mmk_bonus);
      $("#bonus_mmk_label").html(mmk_bonus);
      $("#bonus_mmk").val(mmk_bonus);
      total_Bonus();
      calculateNetSalary();
    }
    function calculateBonus(){
      var dri_ass = $("#driver_assistant").val();
      var join_year = $("#joined_year").val();
      var join_month = $("#joined_month").val();
      var salary = Number($("#salary_usd").val());
      var performance = Number($("#performance").val());

      var bonus_name = $("#bonus_name option:selected").val();
      if(bonus_name=="Thadingyut"){
        $("#other_bonus").hide();
        if(dri_ass=="yes"){
          var bonus = salary * 0.5;

          var exchange = Number($("#bonus_exchange_rate0").val());
          var mmk_bonus = exchange * Number(bonus);

          
        }
        else{
          if(join_year>0){
            var bonus = salary * performance;

            var exchange = Number($("#bonus_exchange_rate0").val());
            var mmk_bonus = exchange * Number(bonus);
          }
          else{
            var bonus = salary * performance * join_month / 12;

            var exchange = Number($("#bonus_exchange_rate0").val());
            var mmk_bonus = exchange * Number(bonus);
          }
        }
      }
      else if(bonus_name=="Thingyan"){
        var bonus = salary;

        var exchange = Number($("#bonus_exchange_rate0").val());
        var mmk_bonus = exchange * Number(bonus);

        $("#other_bonus").hide();
      }
      else if(bonus_name=="Other"){
        $("#other_bonus").show();
        var bonus = 0;

        var exchange = Number($("#bonus_exchange_rate0").val());
        var mmk_bonus = exchange * Number(bonus);
      }
      else{
        $("#other_bonus").hide();
        var bonus = 0;

        var exchange = Number($("#bonus_exchange_rate0").val());
        var mmk_bonus = exchange * Number(bonus);
      }

      $("#bonus_usd_amount0").val(bonus);
      $("#bonus_usd_label").html(bonus);
      $("#bonus_usd").val(bonus);

      $("#bonus_mmk_amount0").val(mmk_bonus);
      $("#bonus_mmk_label").html(mmk_bonus);
      $("#bonus_mmk").val(mmk_bonus);
      total_Bonus();
      calculateNetSalary();
    }

    function calculateBonusMMK($i){
      var exchange_rate = $("#bonus_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#bonus_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#bonus_mmk_amount"+$i).val(amount);
      total_Bonus();
    
    }

    function calculateNetSalary(){
      var net_salary_usd = 0;
      var net_salary_mmk = 0;

      //salary
      var salary_usd = Number($("#salary_usd").val());
      if(salary_usd && !isNaN(salary_usd)){
        net_salary_usd += salary_usd;
      }

      var salary_mmk = Number($("#salary_mmk").val());
      if(salary_mmk && !isNaN(salary_mmk)){
        net_salary_mmk += salary_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);

      //kbz
      var kbz_usd = Number($("#kbz_opening_usd").val());
      if(kbz_usd && !isNaN(kbz_usd)){
        net_salary_usd -= kbz_usd;
      }

      var kbz_mmk = Number($("#kbz_opening_mmk").val());
      if(kbz_mmk && !isNaN(kbz_mmk)){
        net_salary_mmk -= kbz_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);

      //ssc
      var ssc_usd = Number($("#ssc_usd").val());
      if(ssc_usd && !isNaN(ssc_usd)){
        net_salary_usd -= ssc_usd;
      }

      var ssc_mmk = Number($("#ssc_mmk").val());
      if(ssc_mmk && !isNaN(ssc_mmk)){
        net_salary_mmk -= ssc_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      //ot
      var ot_usd = Number($("#ot_usd").val());
      if(ot_usd && !isNaN(ot_usd)){
        net_salary_usd += ot_usd;
      }

      var ot_mmk = Number($("#ot_mmk").val());
      if(ot_mmk && !isNaN(ot_mmk)){
        net_salary_mmk += ot_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      //leave
      var leave_usd = Number($("#leave_usd").val());
      if(leave_usd && !isNaN(leave_usd)){
        net_salary_usd -= leave_usd;
      }

      var leave_mmk = Number($("#leave_mmk").val());
      if(leave_mmk && !isNaN(leave_mmk)){
        net_salary_mmk -= leave_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
     
      //adjustment
      var adjustment_usd = Number($("#adjustment_usd").val());
      if(adjustment_usd && !isNaN(adjustment_usd)){
        net_salary_usd += adjustment_usd;
      }

      var adjustment_mmk = Number($("#adjustment_mmk").val());
      if(adjustment_mmk && !isNaN(adjustment_mmk)){
        net_salary_mmk += adjustment_mmk;
      }
      //tax
      var tax_usd = Number($("#tax_usd_amount0").val());
      if(tax_usd && !isNaN(tax_usd)){
        net_salary_usd -= tax_usd;
      }

      var tax_mmk = Number($("#tax_mmk_amount0").val());
      if(tax_mmk && !isNaN(tax_mmk)){
        net_salary_mmk -= tax_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      //mmk allowance
      // var mmk_allowance_usd = Number($("#mmk_allowance_usd").val());
      // if(mmk_allowance_usd && !isNaN(mmk_allowance_usd)){
      //   net_salary_usd += mmk_allowance_usd;
      // }

      var mmk_allowance_mmk = Number($("#total_allowance_mmk").val());
      if(mmk_allowance_mmk && !isNaN(mmk_allowance_mmk)){
        net_salary_mmk += mmk_allowance_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      //usd allowance
      var usd_allowance_usd = Number($("#usd_allowance_usd").val());
      if(usd_allowance_usd && !isNaN(usd_allowance_usd)){
        net_salary_usd += usd_allowance_usd;
      }

      var usd_allowance_mmk = Number($("#usd_allowance_mmk").val());
      if(usd_allowance_mmk && !isNaN(usd_allowance_mmk)){
        net_salary_mmk += usd_allowance_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      //mmk deduction
      // var mmk_deduction_usd = Number($("#mmk_deduction_usd").val());
      // if(mmk_deduction_usd && !isNaN(mmk_deduction_usd)){
      //   net_salary_usd -= mmk_deduction_usd;
      // }

      var mmk_deduction_mmk = Number($("#total_deduction_mmk").val());
      if(mmk_deduction_mmk && !isNaN(mmk_deduction_mmk)){
        net_salary_mmk -= mmk_deduction_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      //usd deduction
      var usd_deduction_usd = Number($("#usd_deduction_usd").val());
      if(usd_deduction_usd && !isNaN(usd_deduction_usd)){
        net_salary_usd -= usd_deduction_usd;
      }

      var usd_deduction_mmk = Number($("#usd_deduction_mmk").val());
      if(usd_deduction_mmk && !isNaN(usd_deduction_mmk)){
        net_salary_mmk -= usd_deduction_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      //bonus
      var bonus_usd = Number($("#bonus_usd_amount0").val());
      if(bonus_usd && !isNaN(bonus_usd)){
        net_salary_usd += bonus_usd;
      }

      var bonus_mmk = Number($("#bonus_mmk_amount0").val());
      if(bonus_mmk && !isNaN(bonus_mmk)){
        net_salary_mmk += bonus_mmk;
      }
      console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      $("#net_salary_usd").val(net_salary_usd.toFixed(2));
      $("#net_salary_mmk").val(net_salary_mmk.toFixed(2));

    }
  </script>
@stop