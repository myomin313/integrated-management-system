@extends('layouts.master')
@section('title','Daily OT Request')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">OT Management</li>
              <li class="breadcrumb-item active"><a href="{{url('ot-management/daily-ot-request/list')}}">Daily OT Request List</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            
            <a class="btn btn-default breadcrumb-btn openFilter" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
                        
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content filter-row">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
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
            
            <div class="col-md-12 p-0" id="alert-section" style="display: none;">
                <div class="alert alert-dismissible " role="alert" style="font-size: 12px" id="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong></strong>
                </div>
            </div>
            @if(session('success_create'))
              <div class="col-md-12 p-0">
                <div class="alert alert-success alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
                </div>
              </div>
            @endif
            @if(session('error'))
              <input type="hidden" id="error" name="error" value="yes">
            @else
              <input type="hidden" id="error" name="error" value="no">
            @endif
            @if(session('end_error'))
              <input type="hidden" id="end_error" name="end_error" value="yes">
            @else
              <input type="hidden" id="end_error" name="end_error" value="no">
            @endif
            @if(session('success_delete'))
              <div class="col-md-12 p-0">
                <div class="alert alert-danger alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_delete')}}</strong>
                </div>
              </div>
            @endif
            <div class="card">
              <div class="card-header">
                <form action="{{url('ot-management/daily-ot-request/list')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('d/m/Y');
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                    $department=app('request')->get('department');
                    $status=app('request')->get('status');
                    $monthly_request=app('request')->get('monthly_request');
                          
                  @endphp
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Employee Name</label>
                        <select class="form-control select2bs4" name="employee" id="employee" style="width: 100%;">
                          <option selected="selected" value="all">- All -</option>
                          @foreach($employees as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$employee?'selected':''}}>{{$value->employee_name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Department</label>
                        <select class="form-control select2bs4" name="department" id="department" style="width: 100%;">
                          <option selected="selected" value="all">- All -</option>
                          @foreach($departments as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$department?'selected':''}}>{{$value->name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Status</label>
                        <select class="form-control" name="status" id="status" style="width: 100%;">
                          @if(isset($status) and $status=="all")
                            <option value="all" selected>- All -</option>
                          @else
                            <option value="all">- All -</option>
                          @endif
                          @if(!isset($status) or $status=="0")
                            <option value="0" selected>Pending</option>
                          @else
                            <option value="0">Pending</option>
                          @endif   
                          <option value="1" {{$status==1?'selected':''}}>Accept</option>    
                          <option value="2" {{$status==2?'selected':''}}>Reject</option>    
                        </select>
                      </div>
                    </div>
                    {{-- <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Request State</label>
                        <select class="form-control" name="monthly_request" id="monthly_request" style="width: 100%;">
                          @if(isset($monthly_request) and $monthly_request=="all")
                            <option value="all" selected>- All -</option>
                          @else
                            <option value="all">- All -</option>
                          @endif
                          @if(!isset($monthly_request) or $monthly_request=="0")
                            <option value="0" selected>Daily Request</option>
                          @else
                            <option value="0">Daily Request</option>
                          @endif   
                              
                          <option value="1"  {{$monthly_request==1?'selected':''}}>Monthly Request</option>    
                        </select>
                      </div>
                    </div> --}}
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" placeholder="dd/mm/YYYY" value="{{isset($from_date)?$from_date:''}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>To Date</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                          <input type="text" name="to_date" id="to_date" placeholder="dd/mm/YYYY" value="{{isset($to_date)?$to_date:''}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('ot-management/daily-ot-request/list')}}" class="btn btn-warning">Reset</a>
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
                <div class="row">
                  <div class="col-sm-6">
                    <h3 class="card-title">Daily OT Request List</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    @can('create-ot')
                    <a href="#" data-toggle="modal" class="btn btn-success breadcrumb-btn delete-modal" data-target="#modal-ot-start-request">
                      Start OT
                    </a>
                    @endcan
                    @can('send-monthly-ot-request')
                    {{-- url('ot-management/daily-ot-request/send-monthly-request') --}}
                    <a href="#" type="button"  data-toggle="modal" data-target="#monthly_request" class="btn btn-primary breadcrumb-btn"> Monthly OT Request</a>
                    
                    @endcan
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover" id="ot_record">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Employee Name</th>
                      <th>Branch</th>
                      <th>Apply Date</th>
                      <th>Start Request Time</th>
                      <th>End Request Time</th>
                      <th>Approved</th>
                      <th>Requested Date</th>
                      <th>Approve By</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $latest_index = 'null';
                    @endphp
                    @foreach($dailyrequests as $key=>$value)                       
                      <tr id="row{{$key}}">
                        <td><small id='snum{{$key}}' style="font-size:14px;">{{$key+1}}</small></td>
                        <td><span id="user_name{{$key}}">{{getUserFieldWithId($value->user_id,'employee_name')}}</span></td>
                        <td><span id="branch{{$key}}">{{getBranchField(getUserFieldWithId($value->user_id,'branch_id'),'name')}}</span></td>
                        <td><span id="apply_date{{$key}}">{{siteformat_date($value->apply_date)}}</span></td>
                        <td><span id="start_request_time{{$key}}">
                          <strong>Start Time : </strong>{{siteformat_time24($value->start_from_time)}}  <br>
                          <strong>End Time : </strong>{{siteformat_time24_nextday($value->start_to_time,$value->start_next_day)}} {{$value->start_hotel?'(hotel)':''}} <br>
                          @if($value->start_break_hour or $value->start_break_minute)
                          <strong>Break Time : </strong>{{$value->start_break_hour?$value->start_break_hour.' hr':''}} {{$value->start_break_minute?$value->start_break_minute.' min':''}}  <br>
                          @endif
                          <strong>Reason : </strong>{{$value->start_reason}}
                        </span></td>
                        <td><span id="end_request_time{{$key}}">

                          {{-- @if($value->end_from_time) --}}
                          <strong>Start Time : </strong><span class="from">{{$value->end_from_time?siteformat_time24($value->end_from_time):''}}</span>  <br>
                          <strong>End Time : </strong><span class="to">{{$value->end_to_time?siteformat_time24_nextday($value->end_to_time,$value->end_next_day):''}}</span>  {{$value->end_hotel?'(hotel)':''}}<br>
                          {{-- @if($value->end_break_hour or $value->end_break_minute) --}}
                          <strong>Break Time : </strong><span class="break">{{$value->end_break_hour?$value->end_break_hour.' hr':''}} {{$value->end_break_minute?$value->end_break_minute.' min':''}} </span> <br>
                          {{-- @endif --}}
                          <strong>Reason : </strong><span class="reason">{{$value->end_reason}}</span>
                          
                          {{-- @endif --}}
                        </span></td>
                        @if($value->status==0)
                          @php $ot_status = 'Pending';$color = "text-primary"; @endphp
                        @elseif($value->status==1)
                          @php $ot_status = 'Accept';$color = "text-success"; @endphp
                        @else
                          @php $ot_status = 'Reject';$color = "text-danger"; @endphp
                        @endif
                        
                        @php
                          $user = getUserFieldWithId($value->user_id,'employee_name');
                          $position = getPositionName(getUserFieldWithId($value->user_id,'position_id'));
                          $userdepartment = getDepartmentField(getUserFieldWithId($value->user_id,'department_id'),'name');
                          
                        @endphp
                        <td><span id="status{{$key}}">
                          @if(Auth::user()->can('change-ot-manager-status') and $value->monthly_request==0)
                            @if($value->end_from_time)
                              @php
                                $start_time = $value->end_from_time;
                                $end_time = $value->end_to_time;
                                $hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                                $minute = $value->end_break_minute?$value->end_break_minute.' min':'';
                                $breaktime = $hour.' '.$minute;
                                $hotel = $value->end_hotel;
                                $next_day = $value->end_next_day;
                                $reason = $value->end_reason;
                                $next_day = $value->end_next_day;
                              @endphp
                            @else
                              @php
                                $start_time = $value->start_from_time;
                                $end_time = $value->start_to_time;
                                $hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                                $minute = $value->start_break_minute?$value->start_break_minute.' min':'';
                                $breaktime = $hour.' '.$minute;
                                $hotel = $value->start_hotel;
                                $next_day = $value->start_next_day;
                                $reason = $value->start_reason;
                                $next_day = $value->start_next_day;
                              @endphp
                            @endif
                            <a class="{{$color}}" id="statusModal{{$key}}" href="#" data-toggle="modal" data-target="#modal-ot-approve" data-id="{{$value->id}}" data-applydate="{{siteformat_date($value->apply_date)}}" data-ottype="{{$value->ot_type}}" data-starttime="{{siteformat_time24($start_time)}}" data-endtime="{{siteformat_time24_nextday($end_time,$next_day)}}" data-reason="{{$reason}}" data-hotel="{{$hotel}}" data-nextday="{{$next_day}}" data-user="{{$user}}" data-position="{{$position}}" data-department="{{$userdepartment}}" data-breaktime="{{$breaktime}}" data-index="{{$key}}" onclick="addValueForStatus(this)"><strong class="{{$color}}" id="status_name{{$key}}">{{$ot_status}}</strong></a><br>
                            @if($value->monthly_request_id)
                            <strong id="status_reason{{$key}}">({{$value->monthly_status_reason}})</strong>
                            @elseif($value->status_reason)
                            <strong id="status_reason{{$key}}">({{$value->status_reason}})</strong>
                            @endif
                          @else
                            <strong class="{{$color}}" id="status_name{{$key}}">{{$ot_status}}</strong>
                            @if($value->monthly_request_id)
                            <strong id="status_reason{{$key}}">({{$value->monthly_status_reason}})</strong>
                            @elseif($value->status_reason)
                            <strong id="status_reason{{$key}}">({{$value->status_reason}})</strong>
                            @endif
                          @endif
                        </span></td>

                        <td><span id="request_date{{$key}}">{{siteformat_date($value->created_at)}}</span></td>
                        <td><span id="status_change_by{{$key}}">{{$value->status_change_by?getUserFieldWithId($value->status_change_by,'employee_name'):''}}</span></td>

                        {{-- <td><span id="status_change_date{{$key}}">{{$value->status_change_date?siteformat_date($value->status_change_date):''}}</span></td> --}}

                        <td><span>
                          @if($value->user_id==Auth::user()->id and (!$value->end_from_time or $value->status!=1))
                          <a href="#" id="end_ot{{$key}}" data-toggle="modal" class="btn btn-info breadcrumb-btn delete-modal" data-target="#modal-ot-end-request" data-id="{{$value->id}}" data-applydate="{{siteformat_date($value->apply_date)}}" data-ottype="{{$value->ot_type}}" data-fromtime="{{$value->end_from_time?siteformat_time($value->end_from_time):''}}" data-totime="{{$value->end_to_time?siteformat_time($value->end_to_time):''}}" data-breakhour="{{$value->end_break_hour}}" data-breakminute="{{$value->end_break_minute}}" data-reason="{{$value->end_reason}}" data-hotel="{{$value->end_hotel}}" data-nextday="{{$value->end_next_day}}"  data-index="{{$key}}" onclick="addValueForEndOt(this)">EndOT</a>
                          @else
                          <a id="end_ot{{$key}}" class="btn btn-default breadcrumb-btn delete-modal btn-disabled">EndOT</a>
                          @endif
                          <br>
                          <br>
                          @if($value->user_id==Auth::user()->id)
                          
                          <a href="#" title="Cancel Record" data-toggle="modal" class="delete-modal btn btn-danger breadcrumb-btn" data-target="#modal-delete" data-id="{{$value->id}}"onclick="addValueForDelete(this)" id="deleteModal{{$key}}">
                          Cancel
                        </a>
                          @else
                          <a id="#" class="btn btn-default breadcrumb-btn delete-modal btn-disabled">Cancel</a>
                          @endif
                        </span></td>
                        
                      </tr>
                      @php
                        $latest_index = $key;
                      @endphp
                    @endforeach
                  </tbody>
                </table>
              </div>
              <input type="hidden" name="latest_index" id="latest_index" value="{{$latest_index}}">
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
      @include('otmanagement.dailyotrequest.start-request')
      @include('otmanagement.dailyotrequest.end-request')
      @include('otmanagement.dailyotrequest.status-change')
      <!-- /.modal -->
      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Cancel OT Request</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('ot-management/daily-ot-request/delete')}}" method="post" class="prevent-multiple-submit-modal">
            <div class="modal-body">
                <input type="hidden" name="id" id="del_id">
                    
                @csrf
                <strong>Are you sure want to cancel the OT Request?</strong>
                <br>
                <br>
                <div class="form-group">
                    <label for="end_reason">Reason<span class="required text-danger">*</span></label>
                    <textarea id="reason" name="reason" class="form-control" required></textarea>
                </div>
            </div>
            
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Sure</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

      <div class="modal fade requestModal" id="monthly_request">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Monthly OT Request</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('ot-management/daily-ot-request/send-monthly-request')}}" method="get" class="prevent-multiple-submit-modal">
            <div class="modal-body">
                <input type="hidden" name="id" id="del_id">
                    
                @csrf
                <strong>Are you sure want to request for monthly OT?</strong><br><br>
                <div class="form-group ">
                    <label for="year">Request For: <span class="required text-danger">*</span></label>
                    <div class="input-group date">
                        <input type="month" id="request_for" name="request_for" class="form-control" max="{{\Carbon\Carbon::now()->subMonth()->format('Y-m')}}" placeholder="Month YYYY" required autocomplete="off">
                    </div>
                </div>
            </div>
            
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Sure</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </section>
    <!-- /.content -->

@stop
@section('script')
<script>
    $(function () {
      var error = $("#error").val();
      var end_error = $("#end_error").val();
      if(error=="yes"){
        $('#modal-ot-start-request').modal('show');
      }
      if(end_error=="yes"){
        $('#modal-ot-end-request').modal('show');
      }
      $('#datetimepicker').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker1').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker2').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker3').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('.select2').select2();
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

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
      
      var ot_table = $('#ot_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": false,
      });

      $(document).on('click', '#monthly_ot_request', function() {
        $(".loading-overlay, .loading-overlay-image-container").show();
        $("#mysidebar").css("z-index",0);
        $("#mynavbar").css("z-index",0);
        return true;
      });
      $(document).on('click', '#advance_search', function () {
        var className = $('#advance_search').attr('class');
        var lastClass = $('#advance_search').attr('class').split(' ').pop();
        console.log('class name ' + className);
        console.log('lastClass ' + lastClass);
        if (lastClass == 'closeFilter') {
          $('#advance_search').removeClass('closeFilter');
          $('#advance_search').addClass('openFilter');
          $('.filter-row').removeClass('hide-view');
          $('#advance_search .fas').removeClass('fa-search-plus');
          $('#advance_search .fas').addClass('fa-search-minus');
        }
        else {
          $('#advance_search').removeClass('openFilter');
          $('#advance_search').addClass('closeFilter');
          $('.filter-row').addClass('hide-view');
          $('#advance_search .fas').removeClass('fa-search-minus');
          
          $('#advance_search .fas').addClass('fa-search-plus');
        }

      });
        
      var frm = $('#create_form');
      frm.submit(function (e) {
        e.preventDefault();               

        $.ajax({
          type: frm.attr('method'),
          url: "{{ url('ot-management/daily-ot-request/store') }}",
          data: frm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {

            if(data.status=="marubeni_holiday"){
              $('#alert_error strong').html('You can not apply this date '+data.apply_date+' because it is murubeni holiday.');
              $('#alert_error').removeClass('alert-success');
              $('#alert_error').removeClass('alert-warning');
              $('#alert_error').addClass('alert-warning');
              $('#error-alert').show();
            }
            else{
              $('#modal-ot-start-request').modal('hide');
              var index = $('#latest_index').val();
              if(index=='null')
                index = 0;
              else
                index = Number(index)+1;

              var num = count=$('table#ot_record tr').length;
              
              var num_column = '<small id="snum'+index+'" style="font-size:14px;">'+num+'</small>';
              var hotel = '';
              var nextday = '';
              if(data.start_hotel){
                hotel = "(hotel)";
              }
              if(data.start_next_day){
                nextday = "(next day)";
              }
              var user_name = '<span id="user_name'+index+'">'+data.user_name+'</span>';
              var branch_name = '<span id="branch'+index+'">'+data.branch_name+'</span>';
              var apply_date = '<span id="apply_date'+index+'">'+data.apply_date+'</span>';
              var start_time = '<span id="start_request_time'+index+'"><strong>Start Time : </strong>'+data.start_from_time+'  <br><strong>End Time : </strong>'+data.start_to_time+' '+hotel+' <br><strong>Break Time : </strong>'+data.start_breaktime+'  <br><strong>Reason : </strong>'+data.start_reason+'</span>';
              var end_time = '<span id="end_request_time'+index+'"><strong>Start Time : </strong><span class="from"></span>  <br><strong>End Time : </strong><span class="to"></span> <br><strong>Break Time : </strong><span class="break"> </span> <br><strong>Reason : </strong><span class="reason"></span></span>';
              
              if(data.manager=="yes")
                var status = '<span id="status'+index+'"><a class="text-primary" id="statusModal'+index+'" href="#" data-toggle="modal" data-target="#modal-ot-approve" data-id="'+data.id+'" data-applydate="'+data.apply_date+'" data-ottype="'+data.ot_type+'" data-starttime="'+data.start_from_time+'" data-endtime="'+data.start_to_time+'" data-reason="'+data.start_reason+'" data-hotel="'+data.start_hotel+'" data-nextday="'+data.start_next_day+'" data-user="'+data.user_name+'" data-position="'+data.position+'" data-department="'+data.department+'" data-breaktime="'+data.start_breaktime+'" data-index="'+data.index+'" onclick="addValueForStatus(this)"><strong class="text-primary" id="status_name'+index+'">Pending</strong></a><br></span>';

              else
                var status = '<span id="status'+index+'"><strong class="text-primary" id="status'+index+'">Pending</strong></span>';
              
              var request_date = '<span id="request_date'+index+'">'+data.requested_date+'</span>';
              var change_by = '<span id="status_change_by'+index+'"></span>';
              var del_label = "return confirm('Are you sure want to cancel?')";
              var action_value = '<a href="#" id="end_ot'+index+'" data-toggle="modal" class="btn btn-info delete-modal breadcrumb-btn" data-target="#modal-ot-end-request" data-id="'+data.id+'" data-applydate="'+data.apply_date+'" data-ottype="'+data.ot_type+'" data-index="'+index+'" onclick="addValueForEndOt(this)">EndOT</a> <br><br><a href="'+data.url+'" class="btn btn-danger breadcrumb-btn" onclick="'+del_label+'">Cancel</a>';


              ot_table.row.add( [
                    num_column,
                    user_name,
                    branch_name,
                    apply_date,
                    start_time,
                    end_time,
                    status,
                    request_date,
                    change_by,
                    action_value
                ] ).draw();

              $('#latest_index').val(index);

              $('#alert strong').html('Successfully added the new record');
              $('#alert').removeClass('alert-success');
              $('#alert').removeClass('alert-danger');
              $('#alert').addClass('alert-success');
              $('#alert-section').show();
            }
              
                    
          },
          error: function (data) {
            console.log('An error occurred.');
            console.log(data);
          },
        });
      });

      var editfrm = $('#end_form');
      editfrm.submit(function (e) {
        e.preventDefault();               

        $.ajax({
          type: editfrm.attr('method'),
          url: "{{ url('ot-management/daily-ot-request/update') }}",
          data: editfrm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {
            console.log(data);
            $('#modal-ot-end-request').modal('hide');
            var index = data.index;

            var endtime = data.end_to_time;
            
            if(data.end_hotel){
              endtime = endtime+" (hotel)";
            }
            

            $('#end_request_time'+index+' > .from').html(data.end_from_time);
            $('#end_request_time'+index+' > .to').html(endtime);
            $('#end_request_time'+index+' > .break').html(data.break_time);
            $('#end_request_time'+index+' > .reason').html(data.end_reason);
            if(data.manager=="yes"){
              $('#statusModal'+index).data('starttime',data.end_from_time);
              $('#statusModal'+index).data('endtime',data.end_to_time);
              $('#statusModal'+index).data('reason',data.end_reason);
              $('#statusModal'+index).data('hotel',data.end_hotel);
              //$('#statusModal'+index).data('nextday',data.end_next_day);
              $('#statusModal'+index).data('breaktime',data.break_time);
            }

            $('#status_name'+index).html("Pending");
            $('#status_name'+index).removeClass("text-danger");
            $('#status_name'+index).removeClass("text-success");
            $('#status_name'+index).addClass("text-primary");
            $('#status_reason'+index).html("");
              

            $('#alert strong').html('Successfully update the record');
            $('#alert').removeClass('alert-success');
            $('#alert').removeClass('alert-danger');
            $('#alert').addClass('alert-success');
            $('#alert-section').show();
                    
          },
          error: function (data) {
            console.log('An error occurred.');
            console.log(data);
          },
        });
      });

      

    });

    // $('#datetimepicker2').datetimepicker({
    //       format: 'DD/MM/YYYY',
    //       daysOfWeekDisabled: [0,6]
    // });
    function addValueForStatus(btn){
    
      var id=$(btn).data('id');
      var user=$(btn).data('user');
      var position=$(btn).data('position');
      var department=$(btn).data('department');
      var applydate=$(btn).data('applydate');
      var ottype=$(btn).data('ottype');
      var starttime=$(btn).data('starttime');
      var endtime=$(btn).data('endtime');
      var reason=$(btn).data('reason');
      var breaktime=$(btn).data('breaktime');
      var index=$(btn).data('index');
      var hotel=$(btn).data('hotel');
      var nextday=$(btn).data('nextday');

      if(hotel==1){
        endtime = endtime + " (hotel)";
      }
      // if(nextday==1){
      //   endtime = endtime + " (next day)";
      // }
      $("#modal-ot-approve #status_change_id").val(id);
      $("#modal-ot-approve #status_change_index").val(index);
      $("#modal-ot-approve #request_user_name").html(user);
      $("#modal-ot-approve #user_position").html(position);
      $("#modal-ot-approve #user_department").html(department);
      $("#modal-ot-approve #ot_apply_date").html(applydate);
      $("#modal-ot-approve #ot_request_type").html(ottype);
      $("#modal-ot-approve #ot_request_start_time").html(starttime);
      $("#modal-ot-approve #ot_request_end_time").html(endtime);
      $("#modal-ot-approve #ot_request_reason").html(reason);
      $("#modal-ot-approve #ot_break_time").html(breaktime);
    }

    function addValueForEndOt(btn){
    
      var id=$(btn).data('id');
      var index=$(btn).data('index');
      var applydate=$(btn).data('applydate');
      var ottype=$(btn).data('ottype');

      var fromtime = $(btn).data('fromtime');
      var totime = $(btn).data('totime');
      var breakhour = $(btn).data('breakhour');
      var breakminute = $(btn).data('breakminute');
      var reason = $(btn).data('reason');
      var hotel = $(btn).data('hotel');
      var nextday = $(btn).data('nextday');
      $(".endModal #end_id").val(id);
      $(".endModal #end_index").val(index);
      $(".endModal #end_apply_date").val(applydate);
      $(".endModal select#end_ot_type").val(ottype).change();

      if(hotel!=0)
        $( ".endModal #customCheckbox4" ).prop( "checked", true );
      if(nextday!=0)
        $( ".endModal #customCheckbox5" ).prop( "checked", true );
      $(".endModal #end_from_time").val(fromtime);
      $(".endModal #end_to_time").val(totime);
      $(".endModal #end_break_hour").val(breakhour);
      $(".endModal #end_break_minute").val(breakminute);
      $(".endModal #end_reason").val(reason);
      //$(".endModal select#end_ot_type").attr('disabled',true);
    }

    function checkSerialValue(){
      var obj=$('table#ot_record tbody tr').find('small');
      $.each( obj, function( key, value ) {
        id=value.id;
        
        $('#'+id).html(key+1);
      });
    }
    function addValueForDelete(btn){
    
      var id=$(btn).data('id');
      $(".deleteModal #del_id").val(id);
    }
  </script>
@stop