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
                    <h3 class="card-title text-bold">Salary Calculation (RS)</h3>
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
                  <input type="hidden" name="leave_from_date" value="{{siteformat_date($old_salary->leave_from_date)}}">
                  <input type="hidden" name="leave_to_date" value="{{siteformat_date($old_salary->leave_to_date)}}">
                  <input type="hidden" name="exchange_rate_usd" value="{{$old_salary->exchange_rate_usd}}">
                  <input type="hidden" name="defined_exchange_yen" value="{{$old_salary->exchange_rate_yen}}">
                  <input type="hidden" name="employee_type" id="employee_type" value="rs">
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
                          <input type="text" class="form-control" id="exchange_rate_usd" name="payment_exchange_rate" value="{{$old_salary->payment_exchange_rate}}" readonly>
                          <div class="input-group-append">
                            <div class="input-group-text">MMK</div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="exchange_rate_yen" class="col-sm-4 col-form-label">Exchange Rate (Yen) </label>
                        <div class="input-group col-sm-8" data-target-input="nearest">
                          <input type="text" class="form-control" id="exchange_rate_yen" name="exchange_rate_yen" value="{{$old_salary->exchange_rate_yen}}" readonly>
                          <div class="input-group-append">
                            <div class="input-group-text">MMK</div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="salary_exchange_rate" class="col-sm-4 col-form-label">Salary </label>
                        <div class="col-sm-8">
                          <table id="salary_info">
                            <tbody>
                              @foreach($old_salary->exchange_rate_salary_detail as $key=>$detail)
                              <tr>
                                <td>
                                  @if($key==0)
                                  <strong>Rate</strong><br>
                                  @endif
                                  <input type="text" class="form-control" id="salary_exchange_rate{{$key}}" name="salary_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateSalaryMMK({{$key}})" onchange="calculateSalaryMMK({{$key}})">
                                </td>
                                <td>
                                  @if($key==0)
                                  <strong>USD </strong><br>
                                  @endif
                                  <input type="text" class="form-control" id="salary_usd_amount{{$key}}" name="salary_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateSalaryMMK({{$key}})" onchange="calculateSalaryMMK({{$key}})">
                                </td>
                                <td>
                                  @if($key==0)
                                  <strong>MMK </strong><br>
                                  @endif
                                  <input type="text" class="form-control" id="salary_mmk_amount{{$key}}" name="salary_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                </td>
                                <td>
                                  @if($key==0)
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  
                                  <div class="btn btn-success add-salary" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>

                                  @else
                                  <div class="delete-row btn btn-danger remove" onclick="delete_Row_Salary(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                  @endif
                                </td>
                              </tr>
                              @endforeach
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="salary_usd_label">{{$old_salary->salary_usd}}</strong>
                                  <input type="hidden" class="form-control" id="salary_usd" name="salary_usd" value="{{$old_salary->salary_usd}}">
                                </td>
                                <td>
                                  <strong id="salary_mmk_label">{{$old_salary->salary_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="salary_mmk" name="salary_mmk" value="{{$old_salary->salary_mmk}}">
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
                              @if(count($old_salary->exchange_rate_kbz_detail))
                                @foreach($old_salary->exchange_rate_kbz_detail as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Rate</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="kbz_exchange_rate{{$key}}" name="kbz_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateKBZMMK({{$key}})" onchange="calculateKBZMMK({{$key}})">
                                  </td>
                                   <td>
                                    @if($key==0)
                                    <strong>USD </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="kbz_usd_amount{{$key}}" name="kbz_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateKBZMMK({{$key}})" onchange="calculateKBZMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>MMK </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="kbz_mmk_amount{{$key}}" name="kbz_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                  </td>
                                 
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility: hidden;">Action</strong><br>
                                    
                                    <div class="btn btn-success add-kbz" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @else
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_KBZ(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              @else
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
                              @endif
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>

                                <td>
                                  <strong id="kbz_opening_usd_label">{{$old_salary->kbz_opening_usd}}</strong>
                                  <input type="hidden" class="form-control" id="kbz_opening_usd" name="kbz_opening_usd" value="{{$old_salary->kbz_opening_usd}}">
                                </td>
                                <td>
                                  <strong id="kbz_opening_mmk_label">{{$old_salary->kbz_opening_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="kbz_opening_mmk" name="kbz_opening_mmk" value="{{$old_salary->kbz_opening_mmk}}">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="transfer_to_exchange_rate" class="col-sm-4 col-form-label">Salary Transfer to Japan </label>
                        <div class="col-sm-8">
                          <table id="transfer_to_info">
                            <tbody>
                              @if(count($old_salary->exchange_rate_transfer_to_detail))
                                @foreach($old_salary->exchange_rate_transfer_to_detail as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Rate</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="transfer_to_exchange_rate{{$key}}" name="transfer_to_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateTransferToMMK({{$key}})" onchange="calculateTransferToMMK({{$key}})">
                                  </td>
                                   <td>
                                    @if($key==0)
                                    <strong>USD </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="transfer_to_usd_amount{{$key}}" name="transfer_to_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateTransferToMMK({{$key}})" onchange="calculateTransferToMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>MMK </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="transfer_to_mmk_amount{{$key}}" name="transfer_to_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                  </td>
                                 
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility: hidden;">Action</strong><br>
                                    <div class="btn btn-success add-transfer-to" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @else
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_TransferTo(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              @else
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="transfer_to_exchange_rate0" name="transfer_to_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateTransferToMMK(0)" onchange="calculateTransferToMMK(0)">
                                </td>
                                 <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="transfer_to_usd_amount0" name="transfer_to_usd_amount[]" value="" onkeyup="calculateTransferToMMK(0)" onchange="calculateTransferToMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="transfer_to_mmk_amount0" name="transfer_to_mmk_amount[]" value="" readonly>
                                </td>
                               
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-transfer-to" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                              @endif
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>

                                <td>
                                  <strong id="transfer_to_usd_label">{{$old_salary->transfer_to_japan_usd}}</strong>
                                  <input type="hidden" class="form-control" id="transfer_to_usd" name="transfer_to_usd" value="{{$old_salary->transfer_to_japan_usd}}">
                                </td>
                                <td>
                                  <strong id="transfer_to_mmk_label">{{$old_salary->transfer_to_japan_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="transfer_to_mmk" name="transfer_to_mmk" value="{{$old_salary->transfer_to_japan_mmk}}">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="transfer_from_exchange_rate" class="col-sm-4 col-form-label">Salary Transfer from Japan </label>
                        <div class="col-sm-8">
                          <table id="transfer_from_info">
                            <tbody>
                              @if(count($old_salary->exchange_rate_transfer_from_detail))
                                @foreach($old_salary->exchange_rate_transfer_from_detail as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Rate</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="transfer_from_exchange_rate{{$key}}" name="transfer_from_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateTransferFromMMK({{$key}})" onchange="calculateTransferFromMMK({{$key}})">
                                  </td>
                                   <td>
                                    @if($key==0)
                                    <strong>USD </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="transfer_from_usd_amount{{$key}}" name="transfer_from_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateTransferFromMMK({{$key}})" onchange="calculateTransferFromMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>MMK </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="transfer_from_mmk_amount{{$key}}" name="transfer_from_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                  </td>
                                 
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility: hidden;">Action</strong><br>
                                    <div class="btn btn-success add-transfer-from" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @else
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_TransferFrom(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              @else
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="transfer_from_exchange_rate0" name="transfer_from_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateTransferFromMMK(0)" onchange="calculateTransferFromMMK(0)">
                                </td>
                                 <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="transfer_from_usd_amount0" name="transfer_from_usd_amount[]" value="" onkeyup="calculateTransferFromMMK(0)" onchange="calculateTransferFromMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="transfer_from_mmk_amount0" name="transfer_from_mmk_amount[]" value="" readonly>
                                </td>
                               
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-transfer-from" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                              @endif
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>

                                <td>
                                  <strong id="transfer_from_usd_label">{{$old_salary->transfer_from_japan_usd}}</strong>
                                  <input type="hidden" class="form-control" id="transfer_from_usd" name="transfer_from_usd" value="{{$old_salary->transfer_from_japan_usd}}">
                                </td>
                                <td>
                                  <strong id="transfer_from_mmk_label">{{$old_salary->transfer_from_japan_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="transfer_from_mmk" name="transfer_from_mmk" value="{{$old_salary->transfer_from_japan_mmk}}">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="electricity_exchange_rate" class="col-sm-4 col-form-label">W/Electricity Charge </label>
                        <div class="col-sm-8">
                          <table id="electricity_info">
                            <tbody>
                              @if(count($old_salary->exchange_rate_electricity_detail))
                                @foreach($old_salary->exchange_rate_electricity_detail as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Rate</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="electricity_exchange_rate{{$key}}" name="electricity_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateElectricityMMK({{$key}})" onchange="calculateElectricityMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>USD </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="electricity_usd_amount{{$key}}" name="electricity_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateElectricityMMK({{$key}})" onchange="calculateElectricityMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>MMK </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="electricity_mmk_amount{{$key}}" name="electricity_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                  </td>
                                 
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility: hidden;">Action</strong><br>
                                    <div class="btn btn-success add-electricity" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @else
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_Electricity(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              @else
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="electricity_exchange_rate0" name="electricity_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateElectricityMMK(0)" onchange="calculateElectricityMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="electricity_usd_amount0" name="electricity_usd_amount[]" value="" onkeyup="calculateElectricityMMK(0)" onchange="calculateElectricityMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="electricity_mmk_amount0" name="electricity_mmk_amount[]" value="" readonly>
                                </td>
                               
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-electricity" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                              @endif
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>

                                <td>
                                  <strong id="electricity_usd_label">{{$old_salary->electricity_usd}}</strong>
                                  <input type="hidden" class="form-control" id="electricity_usd" name="electricity_usd" value="{{$old_salary->electricity_usd}}">
                                </td>
                                <td>
                                  <strong id="electricity_mmk_label">{{$old_salary->electricity_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="electricity_mmk" name="electricity_mmk" value="{{$old_salary->electricity_mmk}}">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="car_exchange_rate" class="col-sm-4 col-form-label">Car Charge </label>
                        <div class="col-sm-8">
                          <table id="car_info">
                            <tbody>
                              @if(count($old_salary->exchange_rate_car_detail))
                                @foreach($old_salary->exchange_rate_car_detail as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Rate</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="car_exchange_rate{{$key}}" name="car_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateCarMMK({{$key}})" onchange="calculateCarMMK({{$key}})">
                                  </td>
                                   <td>
                                    @if($key==0)
                                    <strong>USD </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="car_usd_amount{{$key}}" name="car_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateCarMMK({{$key}})" onchange="calculateCarMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>MMK </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="car_mmk_amount{{$key}}" name="car_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                  </td>
                                 
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility: hidden;">Action</strong><br>
                                    <div class="btn btn-success add-car" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @else
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_Car(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              @else
                              <tr>
                                <td>
                                  <strong>Rate</strong><br>
                                  <input type="text" class="form-control" id="car_exchange_rate0" name="car_exchange_rate[]" value="{{$exchange_rate->dollar}}" onkeyup="calculateCarMMK(0)" onchange="calculateCarMMK(0)">
                                </td>
                                 <td>
                                  
                                  <strong>USD </strong><br>
                                  <input type="text" class="form-control" id="car_usd_amount0" name="car_usd_amount[]" value="" onkeyup="calculateCarMMK(0)" onchange="calculateCarMMK(0)">
                                </td>
                                <td>
                                  
                                  <strong>MMK </strong><br>
                                  <input type="text" class="form-control" id="car_mmk_amount0" name="car_mmk_amount[]" value="" readonly>
                                </td>
                               
                                <td>
                                  <strong style="visibility: hidden;">Action</strong><br>
                                  <!-- <a class="btn btn-success add-salary" style="padding-top: 5px;padding-bottom: 5px;"> + </a> -->
                                  <div class="btn btn-success add-car" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                              </tr>
                              @endif
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>

                                <td>
                                  <strong id="car_usd_label">{{$old_salary->car_usd}}</strong>
                                  <input type="hidden" class="form-control" id="car_usd" name="car_usd" value="{{$old_salary->car_usd}}">
                                </td>
                                <td>
                                  <strong id="car_mmk_label">{{$old_salary->car_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="car_mmk" name="car_mmk" value="{{$old_salary->car_mmk}}">
                                </td>
                                
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
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
                              @if(count($old_salary->other_allowance))
                                @foreach($old_salary->other_allowance as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Description</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="allowance_name{{$key}}" name="allowance_name[]" value="{{$detail->name}}">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>Amount</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="allowance_amount{{$key}}" name="allowance_amount[]" value="{{$detail->amount}}" onkeyup="calculateTotalAllowance()" onchange="calculateTotalAllowance()">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>Currency</strong><br>
                                    @endif
                                    <select name="allowance_currency[]" id="allowance_currency{{$key}}" class="form-control" onchange="calculateTotalAllowance()">
                                      <option value="usd" {{$detail->currency=="usd"?"selected":""}}>USD</option>
                                      <option value="mmk" {{$detail->currency=="mmk"?"selected":""}}>MMK</option>
                                    </select>
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility:hidden;">Action</strong><br>
                                    @endif

                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_Allowance(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                  </td>
                                </tr>
                                @endforeach
                              @else
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
                              @endif
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
                                <input type="text" class="form-control" id="total_allowance_usd" name="total_allowance_usd" value="{{$old_salary->usd_allowance_usd+$old_salary->mm_allowance_usd}}" readonly>
                              </td>
                              <td>
                                <strong>MMK</strong>
                                <input type="text" class="form-control" id="total_allowance_mmk" name="total_allowance_mmk" value="{{$old_salary->mmk_allowance_mmk}}" readonly>
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
                              @if(count($old_salary->exchange_rate_usd_allowance_detail))
                                @foreach($old_salary->exchange_rate_usd_allowance_detail as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Rate</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="usd_allowance_exchange_rate{{$key}}" name="usd_allowance_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateAllowanceMMK({{$key}})" onchange="calculateAllowanceMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>USD </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="usd_allowance_usd_amount{{$key}}" name="usd_allowance_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateAllowanceMMK({{$key}})" onchange="calculateAllowanceMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>MMK </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="usd_allowance_mmk_amount{{$key}}" name="usd_allowance_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility: hidden;">Action</strong><br>
                                    
                                    <div class="btn btn-success add-usd-allowance" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @else
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_USD_Allowance(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              @else
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
                              @endif
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="usd_allowance_usd_label">{{$old_salary->usd_allowance_usd}}</strong>
                                  <input type="hidden" class="form-control" id="usd_allowance_usd" name="usd_allowance_usd" value="{{$old_salary->usd_allowance_usd}}">
                                </td>
                                <td>
                                  <strong id="usd_allowance_mmk_label">{{$old_salary->usd_allowance_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="usd_allowance_mmk" name="usd_allowance_mmk" value="{{$old_salary->usd_allowance_mmk}}">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      
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
                              @if(count($old_salary->other_deduction))
                                @foreach($old_salary->other_deduction as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Description</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="deduction_name{{$key}}" name="deduction_name[]" value="{{$detail->name}}">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>Amount</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="deduction_amount{{$key}}" name="deduction_amount[]" value="{{$detail->amount}}" onkeyup="calculateTotalDeduction()" onchange="calculateTotalDeduction()">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>Currency</strong><br>
                                    @endif
                                    <select name="deduction_currency[]" id="deduction_currency{{$key}}" class="form-control" onchange="calculateTotalDeduction()">
                                      <option value="usd" {{$detail->currency=="usd"?"selected":""}}>USD</option>
                                      <option value="mmk" {{$detail->currency=="mmk"?"selected":""}}>MMK</option>
                                    </select>
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility:hidden;">Action</strong><br>
                                    @endif
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_Deduction(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                  </td>
                                </tr>
                                @endforeach
                              @else
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
                              @endif
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
                                <input type="text" class="form-control" id="total_deduction_usd" name="total_deduction_usd" value="{{$old_salary->usd_deduction_usd}}" readonly>
                              </td>
                              <td>
                                <strong>MMK</strong>
                                <input type="text" class="form-control" id="total_deduction_mmk" name="total_deduction_mmk" value="{{$old_salary->mmk_deduction_mmk}}" readonly>
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
                              @if(count($old_salary->exchange_rate_usd_deduction_detail))
                                @foreach($old_salary->exchange_rate_usd_deduction_detail as $key=>$detail)
                                <tr>
                                  <td>
                                    @if($key==0)
                                    <strong>Rate</strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="usd_deduction_exchange_rate{{$key}}" name="usd_deduction_exchange_rate[]" value="{{$detail->exchange_rate}}" onkeyup="calculateDeductionMMK({{$key}})" onchange="calculateDeductionMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>USD </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="usd_deduction_usd_amount{{$key}}" name="usd_deduction_usd_amount[]" value="{{$detail->usd_amount}}" onkeyup="calculateDeductionMMK({{$key}})" onchange="calculateDeductionMMK({{$key}})">
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong>MMK </strong><br>
                                    @endif
                                    <input type="text" class="form-control" id="usd_deduction_mmk_amount{{$key}}" name="usd_deduction_mmk_amount[]" value="{{$detail->mmk_amount}}" readonly>
                                  </td>
                                  <td>
                                    @if($key==0)
                                    <strong style="visibility: hidden;">Action</strong><br>
                                    
                                    <div class="btn btn-success add-usd-deduction" title="Add row"><i class="fa fa-plus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @else
                                    <div class="delete-row btn btn-danger remove" onclick="delete_Row_USD_Deduction(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                    @endif
                                  </td>
                                </tr>
                                @endforeach
                              @else
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
                              @endif
                            </tbody>

                            <tfoot>
                              <tr>
                                <td>
                                  <strong>Total</strong>
                                </td>
                                <td>
                                  <strong id="usd_deduction_usd_label">{{$old_salary->usd_deduction_usd}}</strong>
                                  <input type="hidden" class="form-control" id="usd_deduction_usd" name="usd_deduction_usd" value="{{$old_salary->usd_deduction_usd}}">
                                </td>
                                <td>
                                  <strong id="usd_deduction_mmk_label">{{$old_salary->usd_deduction_mmk}}</strong>
                                  <input type="hidden" class="form-control" id="usd_deduction_mmk" name="usd_deduction_mmk" value="{{$old_salary->usd_deduction_mmk}}">
                                </td>
                                <td>
                                  
                                </td>
                              </tr>
                            </tfoot>
                              
                          </table>
                          
                        </div>
                      </div>
                      
                      <!-- Other Deduction -->


                      <hr style="border: solid 1px #999;">
                     
                      <!-- Net Salary -->
                      <div class="form-group row">
                        <label for="net_salary_usd" class="col-sm-4 col-form-label">Net Payment (USD) </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="net_salary_usd" name="net_salary_usd" value="{{$old_salary->net_salary_usd}}" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="net_salary_usd" class="col-sm-4 col-form-label">Net Payment (MMK) </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="net_salary_mmk" name="net_salary_mmk" value="{{$old_salary->net_salary_mmk}}" readonly>
                        </div>
                      </div>
                      <!-- Net Salary -->
                      <div class="form-group row">
                        <label for="transfer_usd_acc" class="col-sm-4 col-form-label">Transfer To USD A/C </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_usd_acc" name="transfer_usd_acc" value="{{$old_salary->transfer_usd_acc}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="transfer_mmk_acc" class="col-sm-4 col-form-label">Transfer To MMK A/C </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_mmk_acc" name="transfer_mmk_acc" value="{{$old_salary->transfer_mmk_acc}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="transfer_usd_cash" class="col-sm-4 col-form-label">Transfer To USD Cash </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_usd_cash" name="transfer_usd_cash" value="{{$old_salary->transfer_usd_cash}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="transfer_mmk_cash" class="col-sm-4 col-form-label">Transfer To MMK Cash </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="transfer_mmk_cash" name="transfer_mmk_cash" value="{{$old_salary->transfer_mmk_cash}}">
                        </div>
                      </div>

                    </div>
                  </div>
                    
                  <hr style="border: 1px solid #999;">
                  <div class="form-group col-sm-12 text-center">
                    <button type="submit" class="btn btn-success" name="save_new">Save</button>
                    <a href="{{url('salary-management/calculate-salary')}}" class="btn btn-primary">Cancel</a>
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

    
    function calculateTransferToMMK($i){
      var exchange_rate = $("#transfer_to_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#transfer_to_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#transfer_to_mmk_amount"+$i).val(amount);
      total_TransferTo();
    
    }
    function calculateTransferFromMMK($i){
      var exchange_rate = $("#transfer_from_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#transfer_from_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#transfer_from_mmk_amount"+$i).val(amount);
      total_TransferFrom();
    
    }

    function calculateElectricityMMK($i){
      var exchange_rate = $("#electricity_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#electricity_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#electricity_mmk_amount"+$i).val(amount);
      total_Electricity();
    
    }

    function calculateCarMMK($i){
      var exchange_rate = $("#car_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#car_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#car_mmk_amount"+$i).val(amount);
      total_Car();
    
    }
    

    function calculateTaxMMK($i){
      var exchange_rate = $("#tax_exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var usd_amount = $("#tax_usd_amount"+$i).val();
      if(isNaN(usd_amount))
        usd_amount =  0;

      var amount = Number(exchange_rate) * Number(usd_amount);

      $("#tax_mmk_amount"+$i).val(amount);
      //total_Leave();
      calculateNetSalary();
    
    }

    function calculateIncomeTax(){
      var salary = $("#salary_usd").val();
      if(isNaN(salary))
        salary =  0;

      var ot = $("#ot_usd").val();
      if(isNaN(ot))
        ot =  0;

      var leave = $("#leave_usd").val();
      if(isNaN(leave))
        leave =  0;

      var adjustment = $("#adjustment_usd").val();
      if(isNaN(adjustment))
        adjustment =  0;

      var percent = $("#estimated_percent").val();
      if(isNaN(percent))
        percent =  0;

      var total = Number(salary) + Number(ot) - Number(leave) + Number(adjustment);
      console.log("total income "+total);
      var income_tax = Number(total) * Number(percent) / 100;

      $("#tax_usd_amount0").val(income_tax.toFixed(2));

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

    function calculateBonus(){
      var dri_ass = $("#driver_assistant").val();
      var join_year = $("#joined_year").val();
      var join_month = $("#joined_month").val();
      var salary = Number($("#salary_usd").val());
      var performance = Number($("#performance").val());

      var bonus_name = $("#bonus_name option:selected").val();
      if(bonus_name=="Thadingyut"){
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
      }
      else{
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

      //transfer to
      var transfer_to_usd = Number($("#transfer_to_usd").val());
      if(transfer_to_usd && !isNaN(transfer_to_usd)){
        net_salary_usd -= transfer_to_usd;
      }

      var transfer_to_mmk = Number($("#transfer_to_mmk").val());
      if(transfer_to_mmk && !isNaN(transfer_to_mmk)){
        net_salary_mmk -= transfer_to_mmk;
      }

      //transfer from
      var transfer_from_usd = Number($("#transfer_from_usd").val());
      if(transfer_from_usd && !isNaN(transfer_from_usd)){
        net_salary_usd += transfer_from_usd;
      }

      var transfer_from_mmk = Number($("#transfer_from_mmk").val());
      if(transfer_from_mmk && !isNaN(transfer_from_mmk)){
        net_salary_mmk += transfer_from_mmk;
      }

      //electricity
      var electricity_usd = Number($("#electricity_usd").val());
      if(electricity_usd && !isNaN(electricity_usd)){
        net_salary_usd -= electricity_usd;
      }

      var electricity_mmk = Number($("#electricity_mmk").val());
      if(electricity_mmk && !isNaN(electricity_mmk)){
        net_salary_mmk -= electricity_mmk;
      }

      //car
      var car_usd = Number($("#car_usd").val());
      if(car_usd && !isNaN(car_usd)){
        net_salary_usd -= car_usd;
      }

      var car_mmk = Number($("#car_mmk").val());
      if(car_mmk && !isNaN(car_mmk)){
        net_salary_mmk -= car_mmk;
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
      // var bonus_usd = Number($("#bonus_usd_amount0").val());
      // if(bonus_usd && !isNaN(bonus_usd)){
      //   net_salary_usd += bonus_usd;
      // }

      // var bonus_mmk = Number($("#bonus_mmk_amount0").val());
      // if(bonus_mmk && !isNaN(bonus_mmk)){
      //   net_salary_mmk += bonus_mmk;
      // }
      // console.log('USD = '+net_salary_usd+', MMK = '+net_salary_mmk);
      $("#net_salary_usd").val(net_salary_usd.toFixed(2));
      $("#net_salary_mmk").val(net_salary_mmk.toFixed(2));

    }
  </script>
@stop