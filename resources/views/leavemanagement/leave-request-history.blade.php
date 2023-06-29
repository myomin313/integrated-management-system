@extends('layouts.master')
@section('title','Leave Request History')
@section('content')



            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Leave History</a></li>
                                <li class="breadcrumb-item">Leave Management</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/position-create.html"><i class="fas fa-plus"></i> Add New</a> -->
                           @if(!empty(auth()->user()->check_ns_rs ==  1 ))
                            <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal"
                                data-target="#modal-create"><i class="fas fa-plus"></i> Request Leave</a>
                           @else
                           <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal"
                                data-target="#modal-rs-create"><i class="fas fa-plus"></i> Request Leave</a>
                            @endif

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
     @if(!empty(auth()->user()->check_ns_rs ==  1 ))
    <section class="content filter-row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('leave-requests.index') }}" method="post">
                                @csrf
                                <div class="row">
                                     <div class="col-sm-2">
                                        <!-- text input -->
                                        <div class="form-group" style="margin-top: 8px;">
                                            <label>Employee Name</label>
                                            <select class="form-control select2bs4" name="employee_name" id="employee_name" style="width: 100%;">
                                                <option value="">- Select Employee Name -</option>
                                                @foreach($users as $user)
                                                 <option value="{{ $user->id }}">{{ $user->employee_name }}</option>
                                                @endforeach
                                         </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <!-- text input -->
                                        <div class="form-group" style="margin-top: 8px;">
                                            <label>Leave Type</label>
                                            <select class="form-control" name="search_leave_type_id" id="search_leave_type_id" style="width: 100%;">
                                                <option value="">- Select Leave Type -</option>
                                                @foreach($leave_types as $leave_type)
                                                 <option value="{{ $leave_type->id }}">{{ $leave_type->leave_type_name }}</option>
                                                @endforeach
                                         </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <!-- text input -->
                                        <div class="form-group" style="margin-top: 37px;">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a  href="{{ route('leave-requests.index') }}" class="btn btn-warning">Reset</a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </section>
    @else
    <section class="content filter-row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('leave-requests.index') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-2">
                                        <!-- text input -->
                                        <div class="form-group" style="margin-top: 8px;">
                                            <label>Leave Type</label>
                                            <select class="form-control" name="search_leave_type_id" id="search_leave_type_id" style="width: 100%;">
                                                <option value="">- Select Leave Type -</option>
                                                <option value="1">Earned Leave</option>
                                                <option value="2">Refresh Leave</option>
                                                <!-- @foreach($leave_types as $leave_type)
                                                 <option value="{{ $leave_type->id }}">{{ $leave_type->leave_type_name }}</option>
                                                @endforeach -->
                                         </select>
                                        </div>
                                    </div>


                                    <!-- start for status -->
                                      <div class="col-sm-2">
                                        <!-- text input -->
                                        <div class="form-group" style="margin-top: 8px;">
                                            <label>Status</label>
                                            <select class="form-control" name="search_status" id="search_status" style="width: 100%;">
                                                <option value="">- Select Status -</option>
                                                <option value="pending">Pending</option>
                                                <option value="reject">Reject</option>
                                                <option value="accept">Accept</option>
                                                <!-- @foreach($leave_types as $leave_type)
                                                 <option value="{{ $leave_type->id }}">{{ $leave_type->leave_type_name }}</option>
                                                @endforeach -->
                                         </select>
                                        </div>
                                    </div>

                                    <!-- end for status -->

                                    <div class="col-sm-2">
                                        <!-- text input -->
                                        <div class="form-group" style="margin-top: 37px;">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a  href="{{ route('leave-requests.index') }}" class="btn btn-warning">Reset</a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </section>
    @endif


      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Leave Request History</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="leave_record" class="table table-hover">
                                <thead>
                                    <tr>
                                        <!-- <th>ID</th> -->
                                        <th>Employee Name</th>
                                        <th>Leave Type</th>
                                        <th>File</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Total</th>
                                        <th>Day Type</th>
                                        <th>Approve By Dep Manager</th>
                                        <th>Approve By ADMI GM</th>
                                        <th>Approve By GM</th>
                                        <th>Request At</th>
                                       
                                        <th>Cancel Leave</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  $i=1; ?>
                                    @foreach($leave_requests as $leave_request)
                                   
                                    <?php
                                          $fdate = str_replace('-"', '/', $leave_request->from_date);
                                            $from_date = date("d/m/Y", strtotime($fdate));

                                            $tdate = str_replace('-"', '/', $leave_request->to_date);
                                            $to_date = date("d/m/Y", strtotime($tdate));
                                         ?>
                                         <?php 
                                         
                                             if($leave_request->check_ns_rs == 1){
                                                 $leave_type_name= $leave_request->leave_type_name;
                                             }else{
                                                  if($leave_request->leave_type_id == 1){
                                                   $leave_type_name= 'Earned Leave';
                                                  }else if($leave_request->leave_type_id == 2){
                                                    $leave_type_name= 'Refresh Leave';
                                                  }
                                             }
                                         ?>
                                    <tr>
                                        
                                        <td>{{ $leave_request->employee_name }}</td>
                                        <!--<td><a class="text-primary" href="#" data-toggle="modal" data-target="#modal-detail"-->
                                        <!--    data-start_date="{{ $from_date}}" data-end_date="{{ $to_date}}" data-leave_type_name="{{ $leave_type_name}}"-->
                                        <!--    data-total="{{ $leave_request->total_days }}" data-approve_by_dep_manager="{{ $leave_request->approve_by_dep_manager }}"-->
                                        <!--    data-approve_by_gm="{{ $leave_request->approve_by_GM }}" data-updated_at="{{ $leave_request->updated_at->format('d/m/Y g:i:s A') }}"-->
                                        <!--    data-leave_day="{{ $leave_request->leave_day }}" data-reason="{{ $leave_request->reason }}" data-remaining_days="{{ $leave_request->remaining_days }}"-->
                                        <!--    data-took_total="{{ $leave_request->took_total }}" data-day_type="{{ $leave_request->day_type }}" onclick="addValueForDetail(this)">{{ $leave_type_name }}</a></td>-->
                                        
                                         @if($leave_request->check_ns_rs == 1)
                                        <td><a class="text-primary" href="#" data-toggle="modal" data-target="#modal-detail"
                                            data-start_date="{{ $from_date}}" data-end_date="{{ $to_date}}" data-leave_type_name="{{ $leave_type_name}}"
                                            data-total="{{ $leave_request->total_days }}" data-approve_by_dep_manager="{{ $leave_request->approve_by_dep_manager }}"
                                            data-approve_by_gm="{{ $leave_request->approve_by_GM }}" data-updated_at="{{ $leave_request->updated_at->format('d/m/Y g:i:s A') }}"
                                            data-leave_day="{{ $leave_request->leave_day }}" data-reason="{{ $leave_request->reason }}" data-remaining_days="{{ $leave_request->remaining_days }}"
                                            data-took_total="{{ $leave_request->took_total }}" data-day_type="{{ $leave_request->day_type }}" onclick="addValueForDetail(this)">{{ $leave_type_name }}</a></td>
                                         @else
                                          <td><a class="text-primary" href="#" data-toggle="modal" data-target="#modal-detail"
                                            data-start_date="{{ $from_date}}" data-end_date="{{ $to_date}}" data-leave_type_name="{{ $leave_type_name}}"
                                            data-total="{{ $leave_request->total_days }}" data-approve_by_dep_manager="{{ $leave_request->approve_by_dep_manager }}"
                                            data-approve_by_gm="{{ $leave_request->approve_by_GM }}" data-updated_at="{{ $leave_request->updated_at->format('d/m/Y g:i:s A') }}"
                                            data-leave_day="{{ $leave_request->rs_earned_leaves }}" data-reason="{{ $leave_request->reason }}" data-remaining_days="{{ $leave_request->remaining_days }}"
                                            data-took_total="{{ $leave_request->took_total }}" data-day_type="{{ $leave_request->day_type }}" onclick="addValueForDetail(this)">{{ $leave_type_name }}</a></td>
                                          @endif
                                          
                                        
                                        @if(!empty($leave_request->certificate))
                                        <td><a class="btn btn-success btn-sm" href="{{ url('/leave-certificate/preview/'.$leave_request->certificate ) }}" target="_blank">view file</a>
                                         </td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>{{ $from_date }}</td>
                                        <td>{{ $to_date }}</td>
                                        <td>{{ $leave_request->total_days }}</td>
                                        <td>{{ $leave_request->day_type }}</td>
                                         @if($leave_request->check_ns_rs == 1)
                                        <td style="color:green">{{ $leave_request->approve_by_dep_manager }}</td>
                                         @else
                                         <td></td>
                                         @endif
                                        <td style="color:green">{{ $leave_request->approve_by_GM }}</td>
                                        @if($leave_request->check_ns_rs == 0)
                                        <td style="color:green">{{ $leave_request->approve_by_RS_GM }}</td>
                                        @else
                                         <td></td>
                                        @endif
                                        <td> {{  $leave_request->updated_at->format('d/m/Y g:i:s A')  }}</td>
                                        <td>
                                            @if($leave_request->user_id == auth()->user()->id )
                                            <a class="btn btn-warning btn-sm"  href="#" data-toggle="modal" data-target="#request-modal" 
                                           data-request_id="{{ $leave_request->id }} " onclick="addValueForCancel(this)">Request Cancel</a>
                                           @endif
                                        </td>
                                    </tr>
                                   
                                    @endforeach

                                    <!-- rs -->
                                     
                                    @foreach($leave_requests_rs as $leave_request)
                                   
                                    <?php
                                          $fdate = str_replace('-"', '/', $leave_request->from_date);
                                            $from_date = date("d/m/Y", strtotime($fdate));

                                            $tdate = str_replace('-"', '/', $leave_request->to_date);
                                            $to_date = date("d/m/Y", strtotime($tdate));
                                         ?>
                                         <?php 
                                         
                                             if($leave_request->check_ns_rs == 1){
                                                 $leave_type_name= $leave_request->leave_type_name;
                                             }else{
                                                  if($leave_request->leave_type_id == 1){
                                                   $leave_type_name= 'Earned Leave';
                                                  }else if($leave_request->leave_type_id == 2){
                                                    $leave_type_name= 'Refresh Leave';
                                                  }
                                             }
                                         ?>
                                    <tr>
                                        
                                        <td>{{ $leave_request->employee_name }}</td>
                                        <!--<td><a class="text-primary" href="#" data-toggle="modal" data-target="#modal-detail"-->
                                        <!--    data-start_date="{{ $from_date}}" data-end_date="{{ $to_date}}" data-leave_type_name="{{ $leave_type_name}}"-->
                                        <!--    data-total="{{ $leave_request->total_days }}" data-approve_by_dep_manager="{{ $leave_request->approve_by_dep_manager }}"-->
                                        <!--    data-approve_by_gm="{{ $leave_request->approve_by_GM }}" data-updated_at="{{ $leave_request->updated_at->format('d/m/Y g:i:s A') }}"-->
                                        <!--    data-leave_day="{{ $leave_request->leave_day }}" data-reason="{{ $leave_request->reason }}" data-remaining_days="{{ $leave_request->remaining_days }}"-->
                                        <!--    data-took_total="{{ $leave_request->took_total }}" data-day_type="{{ $leave_request->day_type }}" onclick="addValueForDetail(this)">{{ $leave_type_name }}</a></td>-->
                                        
                                         @if($leave_request->check_ns_rs == 1)
                                        <td><a class="text-primary" href="#" data-toggle="modal" data-target="#modal-detail"
                                            data-start_date="{{ $from_date}}" data-end_date="{{ $to_date}}" data-leave_type_name="{{ $leave_type_name}}"
                                            data-total="{{ $leave_request->total_days }}" data-approve_by_dep_manager="{{ $leave_request->approve_by_dep_manager }}"
                                            data-approve_by_gm="{{ $leave_request->approve_by_GM }}" data-updated_at="{{ $leave_request->updated_at->format('d/m/Y g:i:s A') }}"
                                            data-leave_day="{{ $leave_request->leave_day }}" data-reason="{{ $leave_request->reason }}" data-remaining_days="{{ $leave_request->remaining_days }}"
                                            data-took_total="{{ $leave_request->took_total }}" data-day_type="{{ $leave_request->day_type }}" onclick="addValueForDetail(this)">{{ $leave_type_name }}</a></td>
                                         @else
                                          <td><a class="text-primary" href="#" data-toggle="modal" data-target="#modal-detail"
                                            data-start_date="{{ $from_date}}" data-end_date="{{ $to_date}}" data-leave_type_name="{{ $leave_type_name}}"
                                            data-total="{{ $leave_request->total_days }}" data-approve_by_dep_manager="{{ $leave_request->approve_by_dep_manager }}"
                                            data-approve_by_gm="{{ $leave_request->approve_by_GM }}" data-updated_at="{{ $leave_request->updated_at->format('d/m/Y g:i:s A') }}"
                                            data-leave_day="{{ $leave_request->rs_earned_leaves }}" data-reason="{{ $leave_request->reason }}" data-remaining_days="{{ $leave_request->remaining_days }}"
                                            data-took_total="{{ $leave_request->took_total }}" data-day_type="{{ $leave_request->day_type }}" onclick="addValueForDetail(this)">{{ $leave_type_name }}</a></td>
                                          @endif
                                          
                                        
                                        @if(!empty($leave_request->certificate))
                                        <td><a class="btn btn-success btn-sm" href="{{ url('/leave-certificate/preview/'.$leave_request->certificate ) }}" target="_blank">view file</a>
                                         </td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>{{ $from_date }}</td>
                                        <td>{{ $to_date }}</td>
                                        <td>{{ $leave_request->total_days }}</td>
                                        <td>{{ $leave_request->day_type }}</td>
                                         @if($leave_request->check_ns_rs == 1)
                                        <td style="color:green">{{ $leave_request->approve_by_dep_manager }}</td>
                                         @else
                                         <td></td>
                                         @endif
                                        <td style="color:green">{{ $leave_request->approve_by_GM }}</td>
                                        @if($leave_request->check_ns_rs == 0)
                                        <td style="color:green">{{ $leave_request->approve_by_RS_GM }}</td>
                                        @else
                                         <td></td>
                                        @endif
                                        <td> {{  $leave_request->updated_at->format('d/m/Y g:i:s A')  }}</td>
                                        <td>
                                            @if($leave_request->user_id == auth()->user()->id )
                                            <a class="btn btn-warning btn-sm"  href="#" data-toggle="modal" data-target="#request-modal" 
                                           data-request_id="{{ $leave_request->id }} " onclick="addValueForCancel(this)">Request Cancel</a>
                                           @endif
                                        </td>
                                    </tr>
                                  
                                    @endforeach
                                    <!-- rs -->
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- start for cancel -->


         <!-- /.container-fluid -->
        <div class="modal fade" id="request-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cancel  Leave Request </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('leave-request.request-cancel') }}"  method="post">
                        @csrf
                        <input type="hidden" id="request_id" name="id">
                        <div class="modal-body">                            
                            <div class="form-group">
                                <label for="remaining_days" class="col-form-label">Cancel Reason </label><br>
                                <textarea name="leave_cancel_reason" class="form-control" value="{{ old('leave_cancel_reason') }}"></textarea>                                  
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Apply</button>
                        </div>                        
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- end for cancel -->


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
                        <h4 class="modal-title">Apply   Leave Form</h4>
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
                                                <option value="">Select</option>
                                                <option value="1">Earned Leaves</option>
                                                <!-- <option value="2">Refresh Leaves</option> -->
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

        <div class="modal fade" id="modal-detail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Apply Leave Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                        <div class="modal-body">
                            <div class="row p-2">
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">Start Date :</span>
                                    <span class="text-primary start_date"></span>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">End Date :</span>
                                    <span class="text-primary end_date"></span>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">Request Days :</span>
                                    <span class="text-primary total"></span>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold"> Taken Leave Days :</span>                                    
                                    <span class="text-primary second_total"></span>
                                    <span class="text-primary"> / </span>
                                    <span class="text-primary before_remaining_days"></span>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold"> Remaining Days :  </span>
                                    <span class="text-primary after_remaining_days"> </span>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">Leave Type :</span>
                                    <span class="text-primary leave_type_name"></span>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">Approve By Dep GM:</span>
                                    <span class="text-primary approve_by_dep_manager"></span>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">Approve By ADMI :</span>
                                    <span class="text-primary approve_by_gm"></span>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">Day Type :</span>
                                    <span class="text-primary day_type"> </span>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <span class="text-bold">Submitted At :</span>
                                    <span class="text-primary updated_at"> </span>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-md-12 col-sm-12">
                                <span class="text-bold">Reason:</span><br>
                                <p class="reason"></p>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


        <script type="text/javascript">
        
        // $('#certificat_file_div').hide();
           $('.file_danger').hide();

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
    
//   $(".btn-submit").click(function(e){
//         e.preventDefault();
//         var leave_type_id = $("#leave_type_id").val();
//         var remaining_days = $("#remaining_days").val();
//         var start_date = $("#start_date").val();
//         var end_date = $("#end_date").val();
//         var holiday_type_id =$("input[name='holiday_type_id']:checked").val();
//         var day_type = $("#day_type").val();
//         var reason = $("#reason").val();
//         $.ajax({
//           type:'POST',
//           url:"{{ route('leave-requests.store') }}",
//           data:{"leave_type_id":leave_type_id,"remaining_days":remaining_days,"start_date":start_date,"end_date":end_date,
//           "holiday_type_id":holiday_type_id,"day_type":day_type,"reason":reason,"_token": "{{ csrf_token() }}"},
//           success:function(data){
//                 if($.isEmptyObject(data.error)){

//                     if(data.status === 0){
//                         alert(data.message);
//                     }else{
//                         alert(data.message);

//                       $("#leave_type_id").val('');
//                       $("#remaining_days").val('');
//                       $("#start_date").val('');
//                       $("#end_date").val('');
//                       $("#holiday_type_id").val('');
//                       $("#day_type").val('');
//                       $("#reason").val('');
//                       location.reload();
//                     }
//                 }else{
//                     printErrorMsg(data.error);
//                 }
//           }
//         });
//     });

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
    
     
      
      
    // create script end

    function printErrorMsg (msg) {
      $.each( msg, function( key, value ) {
         $('.'+key+'_error').text(value);
           });
    }

    function addValueForCancel(btn){
        
         var request_id=$(btn).data('request_id');
          $("#request_id").val(request_id);         
    }

//     function addValueForDetail(btn){
//       var from_date=$(btn).data('start_date');
//       var to_date=$(btn).data('end_date');
//       var leave_type_name=$(btn).data('leave_type_name');
//       var total=$(btn).data('total');
//       var approve_by_dep_manager=$(btn).data('approve_by_dep_manager');
//       var approve_by_gm=$(btn).data('approve_by_gm');
//       var updated_at=$(btn).data('updated_at');
//       var reason=$(btn).data('reason');
//       var remaining_days=$(btn).data('remaining_days');
//       var leave_day = $(btn).data('leave_day');
//       var day_type=$(btn).data('day_type');
//       var after_remaining_days = $(btn).data('remaining_days') -  $(btn).data('total');
      
//       var gday = leave_day -  remaining_days;
      
//      // alert(gday);
//       var second_total = gday + total; 
      

//       $(".start_date").text(from_date);
//       $(".end_date").text(to_date);
//       $(".leave_type_name").text(leave_type_name);
//       $(".total").text(total);
//       $(".approve_by_dep_manager").text(approve_by_dep_manager);
//       $(".approve_by_gm").text(approve_by_gm);
//       $(".updated_at").text(updated_at);
//       $(".reason").text(reason);remain_days
//      $(".before_remaining_days").text(leave_day);
//      $(".second_total").text(second_total);
//      $(".day_type").text(day_type);
//      $(".after_remaining_days").text(after_remaining_days);
//   }

     function addValueForDetail(btn){
      var from_date=$(btn).data('start_date');
      var to_date=$(btn).data('end_date');
      var leave_type_name=$(btn).data('leave_type_name');
      var total=$(btn).data('total');
      var took_total=$(btn).data('took_total');
      var approve_by_dep_manager=$(btn).data('approve_by_dep_manager');
      var approve_by_gm=$(btn).data('approve_by_gm');
      var updated_at=$(btn).data('updated_at');
      var reason=$(btn).data('reason');
      var remaining_days=$(btn).data('remaining_days');
      var leave_day = $(btn).data('leave_day');
      var day_type=$(btn).data('day_type');
     // var after_remaining_days = $(btn).data('remaining_days') -  $(btn).data('total');
      var after_remaining_days = $(btn).data('leave_day') -  (  took_total + total ); 
     // var gday = leave_day -  remaining_days;
      
     // alert(gday);
      var second_total = took_total + total; 
      

      $(".start_date").text(from_date);
      $(".end_date").text(to_date);
      $(".leave_type_name").text(leave_type_name);
      $(".total").text(total);
      $(".approve_by_dep_manager").text(approve_by_dep_manager);
      $(".approve_by_gm").text(approve_by_gm);
      $(".updated_at").text(updated_at);
      $(".reason").text(reason);remain_days
     $(".before_remaining_days").text(leave_day);
     $(".second_total").text(second_total);
     $(".day_type").text(day_type);
     $(".after_remaining_days").text(after_remaining_days);
  }




  // leave type select change
  $("#leave_type_id").change(function() {
    var selectedValue = this.value;
       
       if(selectedValue == 3 ||  selectedValue == 4 || selectedValue == 5 || selectedValue == 6 || selectedValue == 7 || selectedValue == 8 ){
           // $('#certificat_file_div').show();
            $('.file_danger').show();
          if(selectedValue == 3 ||  selectedValue == 4 || selectedValue == 5 || selectedValue == 6 ||  selectedValue == 8 ){
            $('.file_danger').show();
          }else if(selectedValue == 7){
            $('.file_danger').hide();
          }
        }else{
            
             $('.file_danger').hide();
        //  $('#certificat_file_div').hide();
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
    </script>
    <script>
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
    </script>
    <!--for date picker-->

</section>
@stop

