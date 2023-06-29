@extends('layouts.master')
@section('title','Dashboard')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Leave Request By Employee</a></li>
                    <li class="breadcrumb-item">Leave Management</li>
                </ol>
            </div>

            <div class="col-sm-6">
                <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/position-create.html"><i class="fas fa-plus"></i> Add New</a> -->
                <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal"
                    data-target="#modal-create"><i class="fas fa-plus"></i> Request Leave</a> -->

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

<section class="content filter-row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ route('leave-request.admin-approve') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-2">
                                    <!-- text input -->
                                    <div class="form-group" style="margin-top: 8px;">
                                        <label>Employee Name</label>
                                        <select class="form-control select2bs4" name="employee_name" id="employee_name" style="width: 100%;">
                                            <option value="">- Select -</option>
                                            @foreach($all_users as $user)
                                            <option value="{{ $user->id }}">{{ $user->employee_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <span style="margin-top: 39px;">~</span>
                                <div class="col-sm-2">
                                    <!-- text input -->
                                    <div class="form-group" style="margin-top: 8px;">
                                        <label>Department</label>
                                        <select class="form-control select2bs4" name="search_department" id="search_department" style="width: 100%;">
                                            <option value="">- Select -</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->short_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <span style="margin-top: 39px;">~</span>
                                <div class="col-sm-2">
                                    <!-- text input -->
                                    <div class="form-group" style="margin-top: 8px;">
                                        <label>Status</label>
                                        <select class="form-control select2bs4" name="search_status" id="search_status" style="width: 100%;">
                                            <!-- <option value="">- Select -</option> -->
                                            <option value="pending" {{ old('search_status',$status) == 'pending' ? 'selected':''}}>- Pending -</option>
                                            <option value="reject" {{ old('search_status',$status) == 'reject' ? 'selected':''}}>- Reject -</option>
                                            <option value="accept" {{ old('search_status',$status) == 'accept' ? 'selected':''}}>- Accept -</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="col-sm-2">
                                    <!-- text input -->
                                    <div class="form-group" style="margin-top: 37px;">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('leave-request.admin-approve') }}" class="btn btn-warning">Reset</a>
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
                                    <th>ID</th>
                                    <th>Profile</th>
                                    <th>Employee Name</th>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Total</th>
                                    <th>Approve By Department Manager</th>
                                    <th>Approve By Admin Manager</th>                                    
                                    <th>Remaining Days</th>
                                    <th>Leave Cancel Approve By Department Manager</th>
                                    <th>Leave Cancel Approve By Admin Manager</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1 ; ?>
                                @foreach($leave_requests as $leave_request)
                                <?php
                                $fdate = str_replace('-"', '/', $leave_request->from_date);
                                  $from_date = date("d/m/Y", strtotime($fdate));

                                  $tdate = str_replace('-"', '/', $leave_request->to_date);
                                  $to_date = date("d/m/Y", strtotime($tdate));
                               ?>
                                  <?php 
                                          // echo $leave_request->check_ns_rs;exit();
                                             if($leave_request->check_ns_rs == 1){
                                                 $leave_type_name= $leave_request->leave_type_name;
                                             }else{
                                                  if($leave_request->leave_type_id == 1){
                                                   $leave_type_name= 'Earned Leave';
                                                  }else if($leave_request->leave_type_id == 2){
                                                    $leave_type_name= 'Refresh Leave';
                                                  }
                                             }
                                            //  if(!empty($leave_request->updated_at)){
                                            //   $updated_at =  $leave_request->updated_at->format('d/m/Y g:i:s A');
                                            //  }else{
                                            //   $updated_at ='';
                                            //  }
                                         ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td><img src="{{ url('/employee/'.$leave_request->photo_name ) }}" style="width:75px;height:75px" class="img-circle profile_photo" /></td>
                                    <td>                                       
                                        {{ $leave_request->employee_name }}<br>
                                        {{ $leave_request->role_name }}<br>
                                        {{ $leave_request->docname }}
                                    </td>
                                    <td>
                                      <a class="text-primary" href="#" data-toggle="modal" data-target="#modal-detail"  data-cancel_leave_approve_reason_by_admi_manager="{{ $leave_request->cancel_leave_approve_reason_by_admi_manager }}"
                                        data-id="{{ $leave_request->id }}"  data-department_name="{{ $leave_request->docname }}"  data-employee_name="{{ $leave_request->employee_name }}"  data-role_name="{{ $leave_request->role_name }}"  data-start_date="{{ $from_date}}" data-end_date="{{ $to_date}}" 
                                        data-leave_type_name="{{ $leave_type_name}}" data-photo="{{ url('/employee/'.$leave_request->photo_name ) }}" data-total="{{ $leave_request->total_days }}" data-approve_by_dep_manager="{{ $leave_request->approve_by_dep_manager }}"
                                        data-approve_by_gm="{{ $leave_request->approve_by_GM }}" data-updated_at="{{ $leave_request->updated_at }}"
                                         data-approve_reason_by_gm="{{ $leave_request->approve_reason_by_GM }}" data-reason="{{ $leave_request->reason }}" data-remaining_days="{{ $leave_request->remaining_days }}"
                                            onclick="addValueForDetail(this)">
                                           {{ $leave_type_name }}
                                      </a>
                                    </td>
                                    <td>{{ $from_date }}</td>
                                    <td>{{ $to_date }}</td>
                                    <td>{{ $leave_request->total_days }}</td>
                                    <td>{{ $leave_request->approve_by_dep_manager }}</td>
                                    <td>{{ $leave_request->approve_by_GM }}</td>
                                    <td>{{ $leave_request->remaining_days }}</td>
                                    <td>{{ $leave_request->cancel_leave_approve_by_dep_manager }}</td>
                                    <td><a href="#"  data-id="{{ $leave_request->id }}" data-toggle="modal" data-target="#modal-cancel"
                                    data-leave_cancel_reason="{{ $leave_request->leave_cancel_reason }}"  data-cancel_leave_approve_reason_by_admi_manager="{{ $leave_request->cancel_leave_approve_reason_by_admi_manager }}" onclick="addValueForCancel(this)">{{ $leave_request->cancel_leave_approve_by_admi_manager }}</a></td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach

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
    <!-- /.container-fluid -->
    <!-- start cancel -->
      <div class="modal fade" id="modal-cancel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apply Leave Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="../../pages/mastermanagement/allowance-list.html" method="post">
                    <div class="modal-body">

                         <input type="hidden" name="id" class="leave_cancel_request_id">

                        <div class="row">
                            <div class="col-md-6 col-sm-6 text-left">
                                <span class="leave_cancel_reason"></span>
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label for="cancel_leave_approve_reason_by_admi_manager">Leave Cancel Approve Reason</label>
                                <textarea name="cancel_leave_approve_reason_by_admi_manager" id="cancel_leave_approve_reason_by_admi_manager"
                                class="form-control">
                              </textarea>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-success leave-cancel-accept-btn">Accept</button>
                        <button type="button" class="btn btn-danger leave-cancel-reject-btn">Reject</button>
                    </div>
                    </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- end cancel -->
    <div class="modal fade" id="modal-create">
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
                                     <select class="form-control" name="leave_type_id" id="leave_type_id" style="width: 100%;">
                                            <option value="">- Select Leave Type -</option>
                                            @foreach($leave_types as $leave_type)
                                             <option value="{{ $leave_type->id }}">{{ $leave_type->leave_type_name }}</option>
                                            @endforeach
                                     </select>
                                      <span class="text-danger error-text leave_type_id_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="remaining_days" class="col-form-label">Remaining Days </label>
                                <input type="text" class="form-control" id="remaining_days" name="remaining_days" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label for="date" >Start Date <span class="required text-danger">*</span></label>
                              <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                <input type="text" name="start_date" id="start_date" class="form-control datetimepicker-input" data-target="#datetimepicker" />
                                <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                            </div>
                            <span class="text-danger error-text start_date_error"></span>
                          </div>
                          <div class="form-group">
                            <label for="date" >End Date <span class="required text-danger">*</span></label>
                              <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" name="end_date" id="end_date" class="form-control datetimepicker-input" data-target="#datetimepicker2" />
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

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success btn-submit">Apply</button>
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

    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apply Leave Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="../../pages/mastermanagement/allowance-list.html" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="leave_id" class="leave_request_id">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 text-right">
                                <img style="width:100px;height:100px" class="img-circle profile_photo" />
                            </div>
                            <div class="col-md-6 col-sm-6 text-left">
                                <span class="employee_name"></span><br>
                                <span class="role_name"></span><br>
                                <span class="department_name"></span><br>
                            </div>
                        </div>
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
                                <!-- <span class="text-bold">Remaining Days :</span>
                                <span class="text-primary before_remaining_days"></span> -->

                                 <span class="text-bold"> Taken Leave Days :</span>                                    
                                <span class="text-primary second_total"></span>
                                <span class="text-primary"> / </span>
                                <span class="text-primary before_remaining_days"></span>

                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-6 col-sm-6">
                                <span class="text-bold">Remaining Days :</span>
                                <span class="text-primary after_remaining_days"> </span>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <span class="text-bold">Leave Type :</span>
                                <span class="text-primary leave_type_name"></span>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-6 col-sm-6">
                                <span class="text-bold">Approve By Dep :</span>
                                <span class="text-primary approve_by_dep_manager"></span>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <span class="text-bold">Approve By ADMI :</span>
                                <span class="text-primary approve_by_gm"></span>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12 col-sm-12">
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
                        
                        <div class="row p-2">
                             <label for="reason" class="col-form-label">Enter Accept / Reject Reason</label>
                             <textarea class="form-control" name="approve_reason_by_GM" id="approve_reason_by_GM"  placeholder="enter reason for reject"></textarea>
                         </div>
                         
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-success accept-btn">Accept</button>
                        <button type="button" class="btn btn-danger reject-btn">Reject</button>

                    </div>
            </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</script>

    <script type="text/javascript">
    
        $(document).ready(function()
        {
              //leave-cancel
         $('.leave-cancel-accept-btn').click(function(){
        var id=$('.leave_cancel_request_id').val();
        var cancel_leave_approve_reason_by_admi_manager = $('#cancel_leave_approve_reason_by_admi_manager').val();
        $.ajax
        ({
        type: "GET",
        url: "{{ route('leave-request.leave-cancel-admin-manager-change-status') }}",
        data: {"status":'accept',"id": id,"cancel_leave_approve_reason_by_admi_manager":cancel_leave_approve_reason_by_admi_manager},
        success:function(data){
            if($.isEmptyObject(data.error)){
                    alert(data.success);
                     location.reload();
            }else{
               printErrorMsg(data.error);
             }
            }
         });
       });
       //leave-cancel
         $('.leave-cancel-reject-btn').click(function(){
        var id=$('.leave_cancel_request_id').val();
        var cancel_leave_approve_reason_by_admi_manager = $('#cancel_leave_approve_reason_by_admi_manager').val();
        $.ajax
        ({
        type: "GET",
        url: "{{ route('leave-request.leave-cancel-admin-manager-change-status') }}",
        data: {"status":'reject',"id": id,"cancel_leave_approve_reason_by_admi_manager":cancel_leave_approve_reason_by_admi_manager},
        success:function(data){
            if($.isEmptyObject(data.error)){
                    alert(data.success);
                     location.reload();
            }else{
               printErrorMsg(data.error);
             }
            }
         });
       });
             //accept
        $('.accept-btn').click(function(){
        var id=$('.leave_request_id').val();
        var approve_reason_by_GM = $("#approve_reason_by_GM").val();
        $.ajax
        ({
        type: "GET",
        url: "{{ route('leave-request.admin-change-status') }}",
        data: {"status":'accept',"id": id,"approve_reason_by_GM":approve_reason_by_GM},
        success:function(data){
            if($.isEmptyObject(data.error)){
                    alert(data.success);
                     location.reload();
            }else{
               printErrorMsg(data.error);
             }
            }
         });
       });
       //reject
        $('.reject-btn').click(function(){
        var id=$('.leave_request_id').val();
         var approve_reason_by_GM = $("#approve_reason_by_GM").val();
        $.ajax
        ({
        type: "GET",
        url: "{{ route('leave-request.admin-change-status') }}",
        data: {"status": 'reject',"id": id,"approve_reason_by_GM":approve_reason_by_GM},
        success:function(data){
            if($.isEmptyObject(data.error)){
                    alert(data.success);
                     location.reload();
            }else{
               printErrorMsg(data.error);
             }
            }
         });
       });

        });

            //create script start
            
   $(".btn-submit").click(function(e){
        e.preventDefault();
        var leave_type_id = $("#leave_type_id").val();
        var remaining_days = $("#remaining_days").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var holiday_type_id = $("#holiday_type_id").val();
        var day_type = $("#day_type").val();
        var reason = $("#reason").val();
        $.ajax({
           type:'POST',
           url:"{{ route('leave-requests.store') }}",
           data:{"leave_type_id":leave_type_id,"remaining_days":remaining_days,"start_date":start_date,"end_date":end_date,
           "holiday_type_id":holiday_type_id,"day_type":day_type,"reason":reason,"_token": "{{ csrf_token() }}"},
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
    // create script end
     function printErrorMsg (msg) {
      $.each( msg, function( key, value ) {
         $('.'+key+'_error').text(value);
           });
    }
    // leave type select change
  $("#leave_type_id").change(function() {
    var selectedValue = this.value;
    $.ajax({
        url: "{{ route('leave-request.select-leave-type') }}",
        type: "POST",
        data: {leave_type_id : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#remaining_days").val(data.remaining_days);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
       });
    });

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
     
   
      function addValueForCancel(btn){
       var id=$(btn).data('id');
       var leave_cancel_reason=$(btn).data('leave_cancel_reason');
       var cancel_leave_approve_reason_by_admi_manager= $(btn).data('cancel_leave_approve_reason_by_admi_manager'); 

       $('.leave_cancel_reason').text(leave_cancel_reason);
       $('#cancel_leave_approve_reason_by_admi_manager').text(cancel_leave_approve_reason_by_admi_manager);
       $('.leave_cancel_request_id').val(id);       

     }

         //for detail
   function addValueForDetail(btn){

       var from_date=$(btn).data('start_date');
       var to_date=$(btn).data('end_date');
       var leave_type_name=$(btn).data('leave_type_name');
       var total=$(btn).data('total');
       var approve_by_dep_manager=$(btn).data('approve_by_dep_manager');
       var approve_by_gm=$(btn).data('approve_by_gm');
       var updated_at=$(btn).data('updated_at');
       var reason=$(btn).data('reason');
       var remaining_days=$(btn).data('remaining_days');
       var after_remaining_days = $(btn).data('remaining_days') -  $(btn).data('total');
       var role_name=$(btn).data('role_name');
       var employee_name=$(btn).data('employee_name');
       var department_name=$(btn).data('department_name');
       var photo_name=$(btn).data('photo');
       var id=$(btn).data('id');
       var approve_reason_by_gm=$(btn).data('approve_reason_by_gm');

       $(".leave_request_id").val(id);
       $(".start_date").text(from_date);
       $(".end_date").text(to_date);
       $(".leave_type_name").text(leave_type_name);
       $(".total").text(total);
       $(".approve_by_dep_manager").text(approve_by_dep_manager);
       $(".approve_by_gm").text(approve_by_gm);
       $(".updated_at").text(updated_at);
       $(".reason").text(reason);
    //    $(".before_remaining_days").text(remaining_days);
    //    $(".after_remaining_days").text(after_remaining_days);
        $(".before_remaining_days").text(remaining_days);
        $(".second_total").text(total);
        $(".after_remaining_days").text(after_remaining_days);
       $(".role_name").text(role_name);
       $(".employee_name").text(employee_name);
       $(".department_name").text(department_name);
       $(".profile_photo").attr("src",photo_name);
       $("#approve_reason_by_GM").val(approve_reason_by_gm);
       
    //    $(".profile_photo").text(photo_name);
       

   }
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


</section>

@stop
