@extends('layouts.master')
@section('title','Salary Calculation')
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
                    <h3 class="card-title text-bold">Salary Calculation (Receptionist)</h3>
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
                  <input type="hidden" name="employee_type" id="employee_type" value="receptionist">
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
                      
                      <div class="form-group row">
                        <label for="exchange_rate_usd" class="col-sm-4 col-form-label">Exchange Rate (USD) </label>
                        <div class="input-group col-sm-8" data-target-input="nearest">
                          <input type="text" class="form-control" id="exchange_rate_usd" name="payment_exchange_rate" value="{{$exchange_rate->dollar}}" readonly>
                          <div class="input-group-append">
                            <div class="input-group-text">MMK</div>
                          </div>
                        </div>
                      </div>
                      <input type="hidden" class="form-control" id="exchange_rate_yen" name="exchange_rate_yen" value="{{$exchange_rate->yen}}">

                      {{-- <div class="form-group row">
                        <label for="exchange_rate_yen" class="col-sm-4 col-form-label">Exchange Rate (Yen) </label>
                        <div class="input-group col-sm-8" data-target-input="nearest">
                          <input type="text" class="form-control" id="exchange_rate_yen" name="exchange_rate_yen" value="{{$exchange_rate->yen}}" readonly>
                          <div class="input-group-append">
                            <div class="input-group-text">MMK</div>
                          </div>
                        </div>
                      </div> --}}

                      <div class="form-group row">
                        <label for="working_hour" class="col-sm-4 col-form-label">Working Hours </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="working_hour" name="working_hour" value="{{convertTime($working_hour)}}" readonly>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="hourly_rate_usd" class="col-sm-4 col-form-label">Hourly Rate ($) </label>
                        <div class="col-sm-8">
                          
                          <input type="text" value="{{$hourly_rate}}" class="form-control" id="hourly_rate_usd" name="hourly_rate_usd" onkeyup="calculateSalary()" onchange="calculateSalary()" required>
                        </div>
                      </div>
                      @php
                      $recept_salary = round_up_nodecimal($hourly_rate * $working_hour);
                      $recept_mmk_salary = $recept_salary * $exchange_rate->dollar;

                      $net_salary_usd += $recept_salary;
                      $net_salary_mmk += $recept_mmk_salary;
                      @endphp
                      <div class="form-group row">
                        <label for="salary_exchange_rate" class="col-sm-4 col-form-label">Salary </label>
                        <div class="col-sm-8">
                          <table id="salary_info">
                            <tbody>
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="salary_exchange_rate0" name="salary_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateSalaryMMK(0)" onchange="calculateSalaryMMK(0)" required>
                                </td>
                                <td>
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="salary_usd_amount0" name="salary_usd_amount[]" value="{{$recept_salary}}" value="" onkeyup="calculateSalaryMMK(0)" onchange="calculateSalaryMMK(0)" required>
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="salary_mmk_amount0" value="{{$recept_mmk_salary}}" name="salary_mmk_amount[]" value="" readonly>
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
                                  <strong id="salary_usd_label">{{$recept_salary}}</strong>
                                  <input type="hidden" class="form-control" id="salary_usd" name="salary_usd" value="{{$recept_salary}}">
                                </td>
                                <td>
                                  <strong id="salary_mmk_label">{{$recept_mmk_salary}}</strong>
                                  <input type="hidden" class="form-control" id="salary_mmk" name="salary_mmk" value="{{$recept_mmk_salary}}">
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
                      


                    </div>

                    <!-- right side -->
                    <div class="col-md-6">
                      
                      

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
    function calculateSalary(){
      var working_hour = Number($("#working_hour").val());
      if(isNaN(working_hour))
        working_hour =  0;
      var hourly_rate = Number($("#hourly_rate_usd").val());
      if(isNaN(hourly_rate))
        hourly_rate =  0;
      var salary = working_hour * hourly_rate;
      var result = salary.toString().split('.');
      var salary_amount = 0;
      if(typeof(result[1]) != "undefined" && Number(result[1])>0){
        salary_amount = Number(result[0])+1;
      }
      else{
          salary_amount = Number(result[0]);
      }
      $("#salary_usd_amount0").val(salary_amount);

      var exchange_rate = $("#salary_exchange_rate0").val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var amount = Number(exchange_rate) * Number(salary_amount);

      $("#salary_mmk_amount0").val(amount.toFixed(2));

      $("#salary_mmk_label").html(amount.toFixed(2));
      $("#salary_mmk").val(amount.toFixed(2));

      $("#salary_usd_label").html(salary_amount);
      $("#salary_usd").val(salary_amount);

      calculateNetSalary();
    }
    function calculateSalaryMMK($i){
      var exchange_rate = $("#salary_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#salary_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#salary_mmk_amount"+$i).val(amount.toFixed(2));
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
     


      $("#net_salary_usd").val(net_salary_usd.toFixed(2));
      $("#net_salary_mmk").val(net_salary_mmk.toFixed(2));

    }
  </script>
@stop