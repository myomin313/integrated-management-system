@extends('layouts.master')
@section('title','Dashboard')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
            </ol>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
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
              <div class="col-md-12 col-sm-12" style="padding: 0 17px;">
                <div class="alert alert-success alert-dismissible " role="alert" style="font-size: 12px;padding-top:8px;padding-bottom:8px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-top: -7px;"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
                </div>
              </div>
            @endif
          @if(session('hotel_error'))
              <input type="hidden" id="hotel_error" name="hotel_error" value="yes">
          @else
              <input type="hidden" id="hotel_error" name="hotel_error" value="no">
          @endif
          
          @if(!$attendance or $attendance->start_time=="00:00:00")
            @if(isDriver() or isAssistant())
            <div class="col-md-2 col-sm-2">
              <a href="#" class="btn btn-app bg-success" data-toggle="modal" data-target="#time_in">
                <span id="cTime" class="badge bg-purple">{{\Carbon\Carbon::now()->format('h:i:s A')}}</span>
                <i class="fas fa-clock"></i> Time In
              </a>
            </div>
            @else
            <div class="col-md-2 col-sm-2">
              <a href="{{url('dashboard/check-in')}}" class="btn btn-app bg-success prevent-multiple-click">
                <span id="cTime" class="badge bg-purple">{{\Carbon\Carbon::now()->format('h:i:s A')}}</span>
                <i class="fas fa-clock"></i> Time In
              </a>
            </div>
            @endif
          @else
            @if(isDriver() or isAssistant())
            <div class="col-md-2 col-sm-2">
              <a href="#" class="btn btn-app bg-success" data-toggle="modal" data-target="#time_out">
                <span id="cTime" class="badge bg-purple">{{\Carbon\Carbon::now()->format('h:i:s A')}}</span>
                <i class="fas fa-clock"></i> Time Out
              </a>

            </div>
            @else
            <div class="col-md-2 col-sm-2">
              <a href="{{url('dashboard/check-out')}}" class="btn btn-app bg-success prevent-multiple-click">
                <span id="cTime" class="badge bg-purple">{{\Carbon\Carbon::now()->format('h:i:s A')}}</span>
                <i class="fas fa-clock"></i> Time Out
              </a>

            </div>
            @endif
            
            {{-- @if(isDriver() or isAssistant())
            <div class="col-md-2 col-sm-2">
              <a href="{{url('dashboard/ot-request')}}" class="btn btn-app bg-info prevent-multiple-click">
                
                <span id="otTime" class="badge bg-purple">{{\Carbon\Carbon::now()->format('d/m/Y')}}</span>
                <i class="fas fa-tachometer-alt"></i> OT Request
              </a>
            </div>
            @endif --}}
          @endif
          
          @if(isDriver())
          <div class="col-md-2 col-sm-2">
            <a href="#" class="btn btn-app bg-warning" data-toggle="modal" data-target="#hotel_usage">
              
              <i class="fas fa-hotel"></i><small style="font-size: 12px;">Hotel Usage</small>
            </a>
          </div>
          @endif
          {{-- @can("change-ot-admin-status")
          <div class="col-md-2 col-sm-2">
            <a href="{{url('dashboard/monthly-ot-request')}}" class="btn btn-app bg-warning prevent-multiple-click">
              
              <i class="fas fa-columns"></i><small style="font-size: 12px;"> Monthly OT (Dri & Ass) ({{\Carbon\Carbon::now()->subMonth()->format("M")}})</small>
            </a>
            
          </div>
          @endcan --}}
          
          <div class="col-md-2 col-sm-2">
            <a href="#" class="btn btn-app bg-primary" data-toggle="modal" data-target="#change_request">
              
              <i class="fas fa-paper-plane"></i><small style="font-size: 12px;">Attendance Change</small>
            </a>
          </div>
          <!-- /.col -->
           <!--start for leave-->
          <div class="col-md-2 col-sm-2">
                           @if(auth()->user()->employee_type_id !== 4 )
                           @if(!empty(auth()->user()->check_ns_rs ==  1 ))
                            <a class="btn bg-success btn-app float-sm-right" href="#" data-toggle="modal"
                                data-target="#modal-create"><i class="fas fa-plus"></i> Request Leave</a>
                           @else
                           <a class="btn bg-success btn-app float-sm-right" href="#" data-toggle="modal"
                                data-target="#modal-rs-create"><i class="fas fa-plus"></i> Request Leave</a>
                            @endif
                            @endif
          </div>
          <!-- end for leave-->
            
             <div class="col-md-2 col-sm-2">
                
            <!--<a href="" class="btn btn-app bg-info prevent-multiple-click">-->
                 <a class="btn bg-info btn-app float-sm-right" href="#" data-toggle="modal"
                                data-target="#modal-fuel-create">
              <i class="fas fa-gas-pump"></i><small style="font-size: 12px;">Car Fueling</small>
            </a>
            
          </div>
          
        </div>
        <!-- /.row -->
      </div>
      
      <div class="row" style="padding-left: 22px;padding-right: 22px;">
        @can('attendance-change-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('attendance-management/change-request/list')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved</span>
                <span class="info-box-number">
                  
                  <small>Change Request</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>
            <!-- /.info-box -->
        </div>
        @endcan
        @can('change-ot-manager-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('ot-management/daily-ot-request/list')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved by Dept GM</span>
                <span class="info-box-number">
                  
                  <small>Daily OT</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
        @can('change-ot-manager-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('ot-management/monthly-ot-staff/approved-by-dept-gm')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved by Dept GM</span>
                <span class="info-box-number">
                  
                  <small>Monthly OT (Staff)</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
        @can('change-ot-account-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('ot-management/monthly-ot-staff/approved-by-account')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved by Account</span>
                <span class="info-box-number">
                  
                  <small>Monthly OT (Staff)</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
        @can('change-ot-gm-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('ot-management/monthly-ot-staff/approved-by-admin-gm')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved by Admin GM</span>
                <span class="info-box-number">
                  
                  <small>Monthly OT (Staff)</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan

        @can('change-ot-admin-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('ot-management/monthly-ot-driver/approved-by-admin')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved by Admin</span>
                <span class="info-box-number">
                  
                  <small>Monthly OT (Dri & Ass)</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
        @can('change-ot-account-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('ot-management/monthly-ot-driver/approved-by-account')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved by Account</span>
                <span class="info-box-number">
                  
                  <small>Monthly OT (Dri & Ass)</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
        @can('change-ot-gm-status')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('ot-management/monthly-ot-driver/approved-by-admin-gm')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approved by Admin GM</span>
                <span class="info-box-number">
                  
                  <small>Monthly OT (Dri & Ass)</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
      </div>
      
      <!--start leave-->
        <div class="row" style="padding-left: 22px;padding-right: 22px;">
        @can('leave-approve-by-dep-manager')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('/leave-management/leave-requests-approve')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approve By Dep Manager </span>
                <span class="info-box-number">
                  
                  <small>Leave Request</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
        
        @can('leave-approve-by-admi-gm')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('/leave-management/leave-requests-admin-approve')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approve By Admin GM </span>
                <span class="info-box-number">
                  
                  <small>Leave Request</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
        
        @can('leave-approve-by-gm')
        <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('/leave-management/leave-requests-approve-gm')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approve by GM </span>
                <span class="info-box-number">
                  
                  <small>Leave Request</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        @endcan
      
        
        
        
        </div>
       <div class="row" style="padding-left: 22px;padding-right: 22px;">
              <div class="col-12 col-sm-6 col-md-3">
          <a href="{{url('car-management/monthly-car-management/car-fuelings')}}">
            <div class="info-box bg-navy">
              <span class="info-box-icon elevation-1" style="background-color:#647b91;"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Approve by Main User </span>
                <span class="info-box-number">
                  
                  <small>Car Fuelings</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>  
            <!-- /.info-box -->
        </div>
        
        </div>
      <!--end leave-->
      <!-- /.container-fluid -->
      
      <!-- /.container-fluid -->
        <div class="modal fade" id="modal-create">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Apply Leave Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!--<form  method="post">-->
                     <form  method="post" id="leave-upload" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="leave_type_id" class="col-form-label">Leave Type <span
                                        class="required text-danger">*</span></label>
                                         <select class="form-control" name="leave_type_id" id="leave_type_id" style="width: 100%;">
                                                <option value="">- Select Leave Type -</option>
                                                @foreach($leave_types as $leave_type)
                                                 <option value="{{ $leave_type->id }}">{{ $leave_type->leave_type_name }}</option>
                                                @endforeach
                                         </select>
                                          <span class="text-danger error-text leave_type_id_error"></span>
                            </div>
                            
                             <div class="form-group">
                                    <input type="hidden" step="any" class="form-control" id="remaining_days" name="remaining_days">
                            </div>
                            
                            
                            <div class="form-group">
                                <label for="remaining_days" class="col-form-label">Remaining Days </label>
                                    <input type="number" step="any" class="form-control" id="remain_days" name="remain_days" disabled="disabled">
                            </div>
                            
                            <div class="form-group">
                                <label for="date" >Start Date <span class="required text-danger">*</span></label>
                                  <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                    <input type="text" name="start_date" id="start_date" class="form-control datetimepicker-input" />
                                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                                <span class="text-danger error-text start_date_error"></span>
                              </div>
                              <div class="form-group">
                                <label for="date" >End Date <span class="required text-danger">*</span></label>
                                  <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker-input" />
                                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                                <span class="text-danger error-text end_date_error"></span>
                              </div>
                              
                            <div class="form-group">
                                <label for="holiday_type_id" class="col-form-label">Holiday Type </label><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="form-check-input type_id" type="radio" name="holiday_type_id" value="1" checked>
                                    <label class="form-check-label">Full Day</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="form-check-input type_id" type="radio" name="holiday_type_id" value="0">
                                    <label class="form-check-label">Half Day</label>
                            </div>
                            <div class="form-group type_day">
                                    <select class="form-control select2bs4" name="day_type" id="day_type" style="width: 100%;">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label for="reason" class="col-form-label">Reason<span class="required text-danger">*</span></label>
                                <textarea class="form-control" name="reason" id="reason"></textarea>
                                <span class="text-danger error-text reason_error"></span>
                            </div>
                            
                           <!-- start -->
                            <div class="form-group" id="certificat_file_div">
                                <label for="reason" class="col-form-label">File<span class="required text-danger file_danger">*</span></label>
                                <input type="file" name="certificate" id="certificate" class="form-control" accept="image/*,.pdf" />
                                <span class="text-danger error-text certificate_error"></span>
                            </div>
                            <!-- end -->

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success btn-submit">Apply</button>
                        </div>
                </div>
                </form>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- ns leave form model start-->
         <!-- /.container-fluid -->
        <div class="modal fade" id="modal-rs-create">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Apply Leave Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form  method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="leave_type_id" class="col-form-label">Leave Type <span
                                        class="required text-danger">*</span></label>
                                         <select class="form-control" name="rs_leave_type_id" id="rs_leave_type_id" style="width: 100%;">
                                                <option value="">- Select Leave Type -</option>
                                                <option value="1">Earned Leaves</option>
                                                <!-- @foreach($leave_types as $leave_type)
                                                 <option value="{{ $leave_type->id }}">{{ $leave_type->leave_type_name }}</option>
                                                @endforeach -->
                                         </select>
                                          <span class="text-danger error-text leave_type_id_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="remaining_days" class="col-form-label">Remaining Days </label>
                                    <input type="number" step="any" class="form-control" id="rs_remaining_days" name="rs_remaining_days" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label for="date" >Start Date <span class="required text-danger">*</span></label>
                                  <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                    <input type="text" name="rs_start_date" id="rs_start_date" class="form-control datetimepicker-input" data-target="#datetimepicker3" />
                                    <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                                <span class="text-danger error-text start_date_error"></span>
                              </div>
                              <div class="form-group">
                                <label for="date" >End Date <span class="required text-danger">*</span></label>
                                  <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                    <input type="text" name="rs_end_date" id="rs_end_date" class="form-control datetimepicker-input" data-target="#datetimepicker4" />
                                    <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                                <span class="text-danger error-text end_date_error"></span>
                              </div>
                            <div class="form-group">
                                <label for="rs_holiday_type_id" class="col-form-label">Holiday Type </label><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="form-check-input rs_type_id" type="radio" id="rs_holiday_type_id" name="rs_holiday_type_id"  value="1" checked>
                                    <label class="form-check-label">Full Day</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input class="form-check-input rs_type_id" type="radio" id="rs_holiday_type_id" name="rs_holiday_type_id" value="0">
                                    <label class="form-check-label">Half Day</label>
                            </div>
                            <div class="form-group rs_type_day">
                                    <select class="form-control select2bs4" name="rs_day_type" id="rs_day_type" style="width: 100%;">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label for="reason" class="col-form-label">Reason<span class="required text-danger">*</span></label>
                                <textarea class="form-control" name="rs_reason" id="rs_reason"></textarea>
                                <span class="text-danger error-text reason_error"></span>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success rs-btn-submit">Apply</button>
                        </div>
                </div>
                </form>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- ns leave form model end -->
        <!--car fueling start-->
         <!-- /.container-fluid -->
      <div class="modal fade" id="modal-fuel-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Car Fueling Registration</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                @csrf
            <div class="modal-body">
               <div class="form-group">
                <label for="name">Car Number <span class="required text-danger">*</span></label>
                 <select class="form-control" name="car_number" id="car_number" style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number}}</option>
                        @endforeach
                 </select>
                  <span class="text-danger error-text car_number_error"></span>
              </div>
               <div class="form-group">
                    <label for="name">Department Name</label>
                    <input type="text" class="form-control" id="department" name="department" disabled="disabled">
               </div>
              <div class="form-group">
                <label for="car_type">Car Type</label>
                    <input type="text" class="form-control" id="car_type" name="car_type" disabled="disabled">
              </div>

              <div class="form-group">
                <label for="date" >Date <span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker5" data-target-input="nearest">
                    <input type="text" name="date" id="date" class="form-control datetimepicker-input" data-target="#datetimepicker5" />
                    <div class="input-group-append" data-target="#datetimepicker5" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                </div>
                <span class="text-danger error-text date_error"></span>
              </div>

              <div class="form-group">
                <label for="liter">Rate<span class="required text-danger">*</span></label>
                    <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="rate" name="rate">
                  <span class="text-danger error-text rate_error"></span>
             </div>

              <div class="form-group">
                <label for="liter">Liter <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="liter" name="liter">
                  <span class="text-danger error-text liter_error"></span>
             </div>
             <div class="form-group">
                <label for="current_meter">Fueling Kilometer <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="current_meter" name="current_meter">
                 <span class="text-danger error-text current_meter_error"></span>
             </div>
             
             <div class="form-group">
                <label for="reason">Reason </label>
                    <!--<input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="current_meter" name="current_meter">-->
                    <textarea name="reason" class="form-control"  id="reason"></textarea>
                 <!--<span class="text-danger error-text current_meter_error"></span>-->
             </div>
             
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-success btn-fuel-submit">Save</button>
            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

        <!-- car fueling end -->
      
      
      <!-- myo add for leave end-->

    @include("change-request")
    @include("time-in-modal")
    @include("time-out-modal")
    <div class="modal fade" id="hotel_usage">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="col-md-12 p-0" id="error-alert" style="display:none;">
                <div class="alert alert-dismissible " role="alert" style="font-size: 12px" id="alert_error">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong></strong>
                </div>
            </div>
            <div class="modal-header">
              <h4 class="modal-title">Hotel Usage</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('dashboard.hotel-usage')}}" method="post" class="prevent-multiple-submit-modal">
              @if(session('hotel_error'))
                <div class="col-md-12 p-0">
                    <div class="alert alert-danger alert-dismissible " role="alert" style="font-size: 12px">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <small><strong>{{session('hotel_error')}}</strong></small>
                    </div>
                </div>
              @endif
              @csrf
              <div class="modal-body">
                
               
                <div class="form-group">
                  <label for="usage_date">Hotel Usage Date<span class="required text-danger">*</span></label>
                  <div class="input-group date" id="usage_date" data-target-input="nearest">
                    <input type="text" name="usage_date" id="usage_date" value="{{old('usage_date')}}" required placeholder="dd/mm/YYYY" class="form-control datetimepicker-input" data-target="#usage_date"/>
                    <div class="input-group-append" data-target="#usage_date" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Send</button>
              </div>
              
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    
      
    </section>
@stop
@section('script')


<script type="text/javascript">

  $('.file_danger').hide();

    //myo  add for leave

      //create script start
        
           $.ajaxSetup({
             headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }

          });
          //create script start
   $("#leave-upload").submit(function(e){
        e.preventDefault();

           let formData = new FormData(this);

           console.log(formData);
        $.ajax({
           type:'POST',
           url:"{{ route('leave-requests.store') }}",
           data:formData,
           cache:false,
           contentType: false,
           processData: false,
           success:function(data){
                if($.isEmptyObject(data.error)){

                    if(data.status === 0){
                        alert(data.message);
                    }else{
                        alert(data.message);

                       $("#leave_type_id").val('');
                       $("#remaining_days").val('');
                       $("#start_date").val('');
                       $("#end_date").val('');
                       $("#holiday_type_id").val('');
                       $("#day_type").val('');
                       $("#reason").val('');
                       location.reload();
                    }
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    // rs leave

    $(".rs-btn-submit").click(function(e){
        e.preventDefault();
        var leave_type_id = $("#rs_leave_type_id").val();
        var remaining_days = $("#rs_remaining_days").val();
        var start_date = $("#rs_start_date").val();
        var end_date = $("#rs_end_date").val();
        var holiday_type_id =$("input[name='rs_holiday_type_id']:checked").val();
        var day_type = $("#rs_day_type").val();
        var reason = $("#rs_reason").val();
        $.ajax({
           type:'POST',
           url:"{{ route('rs-leave-requests.store') }}",
           data:{"leave_type_id":leave_type_id,"remaining_days":remaining_days,"start_date":start_date,"end_date":end_date,
           "holiday_type_id":holiday_type_id,"day_type":day_type,"reason":reason,"_token": "{{ csrf_token() }}"},
           success:function(data){

                if($.isEmptyObject(data.error)){
                    if(data.status === 0){
                        alert(data.message);
                    }else{
                       $("#rs_leave_type_id").val('');
                       $("#rs_remaining_days").val('');
                       $("#rs_start_date").val('');
                       $("#rs_end_date").val('');
                       $("#rs_holiday_type_id").val('');
                       $("#rs_day_type").val('');
                       $("#rs_reason").val('');
                       location.reload();
                    }
                }else{
                    printErrorMsg(data.error);
                }
                
           }
        });
    });

    // ns leave
    //create script start

   $(".btn-fuel-submit").click(function(e){
        e.preventDefault();
        var car_number = $("#car_number").val();
        var rate = $("#rate").val();
        var liter = $("#liter").val();
        var date = $("#date").val();
        var current_meter = $("#current_meter").val();
        var reason = $("#reason").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-fuelings.store') }}",
           data:{"car_number":car_number,"liter":liter,"date":date,"rate":rate,
           "current_meter":current_meter,"reason":reason,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#department").val('');
                    $("#car_type").val('');
                    $("#car_number").val('');
                    $("#rate").val('');
                    $("#liter").val('');
                    $("#date").val('');
                    $("#current_meter").val('');
                    $("#reason").val('');
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    
    // car number select change
  $("#car_number").change(function() {
    var selectedValue = this.value;
    $.ajax({
        url: "{{ route('car-insurances.select-car-number') }}",
        type: "POST",
        data: {option : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#car_type").val(data.car_type);
                   $("#department").val(data.name);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
      });
  });

//start for float charcode
function isNumberKey(event) {
    var key = window.event ? event.keyCode : event.which;
if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
    if($(this).val().indexOf('.') == -1)
        return true;
    else
        return false;
}
else if ( key < 48 || key > 57 ) {
    return false;
}
else return true;
};


    // create script end
    // create script end

    function printErrorMsg (msg) {
      $.each( msg, function( key, value ) {
         $('.'+key+'_error').text(value);
           });
    }


  // leave type select change
  $("#leave_type_id").change(function() {
    var selectedValue = this.value;
     if(selectedValue == 3 ||  selectedValue == 4 || selectedValue == 5 || selectedValue == 6 || selectedValue == 7 || selectedValue == 8 ){
            $('#file_danger').show();
          if(selectedValue == 3 ||  selectedValue == 4 || selectedValue == 5 || selectedValue == 6 ||  selectedValue == 8 ){
            $('.file_danger').show();
          }else if(selectedValue == 7){
            $('.file_danger').hide();
          }
        }else{
            $('.file_danger').hide();
       }
       
    $.ajax({
        url: "{{ route('leave-request.select-leave-type') }}",
        type: "POST",
        data: {leave_type_id : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#remaining_days").val(data.remaining_days);
                   $("#remain_days").val(data.remaining_days);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
       });
    });
    //rs leave type select    
     $("#rs_leave_type_id").change(function() {
    var selectedValue = this.value;
    $.ajax({
        url: "{{ route('leave-request.select-Rs-leave-type') }}",
        type: "POST",
        data: {leave_type_id : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#rs_remaining_days").val(data.remaining_days);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
       });
    });
    
       $(document).ready(function(){
        $('.type_day').hide();
        $('.type_id').change(function(){
            if($("input[name='holiday_type_id']:checked").val() == '1'){
                $('.type_day').hide();
            }else{
                $('.type_day').show();
            }
        });
    });
     $(document).ready(function(){
        $('.rs_type_day').hide();
        $('.rs_type_id').change(function(){
            if($("input[name='rs_holiday_type_id']:checked").val() == '1'){
                $('.rs_type_day').hide();
            }else{
                $('.rs_type_day').show();
            }
        });
    });
    
    
    
    $(document).ready(function () {
        DisplayCurrentTime();
        if ($(".alert")[0]){
          setTimeout(function(){ 
            $(".alert").hide();
          }, 3000);
        }
          
        $('#changing_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        
        $('#usage_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        
        var hotel_error = $("#hotel_error").val();
        if(hotel_error=="yes"){
          $('#hotel_usage').modal('show');
        }
        //Timepicker
        $('#timepicker').datetimepicker({
          format: 'LT'
        });
        $('#timepicker1').datetimepicker({
          format: 'LT'
        });
        $('#timepicker2').datetimepicker({
          format: 'LT'
        });
        $('#timepicker3').datetimepicker({
          format: 'LT'
        });
        $(document).on('click', '.prevent-multiple-click', function() {
          $(".loading-overlay, .loading-overlay-image-container").show();
          $("#mysidebar").css("z-index",0);
          $("#mynavbar").css("z-index",0);
          return true;
        });
    });
    function DisplayCurrentTime() {
        var dt = new Date();
        var refresh = 1000; //Refresh rate 1000 milli sec means 1 sec
        var cDate = (dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear();
        document.getElementById('cTime').innerHTML = dt.toLocaleTimeString();
        window.setTimeout('DisplayCurrentTime()', refresh);
    }
</script>
@stop