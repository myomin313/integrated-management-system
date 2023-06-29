@extends('layouts.master')
@section('title','Approved by Admin')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">OT Management</li>
              <li class="breadcrumb-item active"><a href="{{url('ot-management/monthly-ot-driver/approved-by-admin')}}">Approved by Admin</a></li>
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
            @if(session('success_update'))
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_update')}}</strong>
                </div>
              </div>
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
                <form action="{{url('ot-management/monthly-ot-driver/approved-by-admin')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('m/Y');
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                    $department=app('request')->get('department');
                    $status=app('request')->get('status');
                          
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

                    <div class="col-sm-2" style="margin-top: 37px;">
                      <!-- text input -->
                      <div class="form-group">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('ot-management/monthly-ot-driver/approved-by-admin')}}" class="btn btn-warning">Reset</a>
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
                    <h3 class="card-title">Approved by Admin</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table fixed-tbody" id="monthly_ot_record">
                  <thead>
                    <tr style="background-color: #17cdcc !important;">
                      <th class="snum">No</th>
                      <th>Apply Date</th>
                      <th>Card Time</th>
                      <th>OT Time</th>
                      <th>
                        Special Taxi Charge (Morning)
                        <div class="form-check">
                          <input type="checkbox" id="morning" class="form-check-input morningAll" style="width:17px;height:17px;"><label class="form-check-label" for="morning">Select All</label>
                        </div>
                      </th>
                      <th>
                        Special Taxi Charge (Evening)
                        <div class="form-check">
                          <input type="checkbox" id="evening" class="form-check-input eveningAll" style="width:17px;height:17px;"><label class="form-check-label" for="evening">Select All</label>
                        </div>
                      </th>
                      <th>Approved By Admin</th>
                      <th>
                        Accept
                        <div class="form-check">
                          <input type="checkbox" id="checkboxA" class="form-check-input acceptAll" style="width:17px;height:17px;"><label class="form-check-label" for="checkboxA">Select All</label>
                        </div>
                      </th>
                      <th>
                        Reject
                        <div class="form-check">
                          <input type="checkbox" id="checkboxB" class="form-check-input rejectAll" style="width:17px;height:17px;"><label class="form-check-label" for="checkboxA">Select All</label>
                        </div>
                      </th>
                    </tr>
                  </thead>
                  @php 
                    $outer = 0;$inner = 0;$type="user";$limited_row=3;$status_label="";
                  
                    $type = "manager";
                    $limited_row = 2;
                    $status_label = "Dept GM Status";
                  
                  @endphp
                  @foreach($monthlyrequests as $index=>$monthlyrequest)
                    @php
                      $num_row = is_countable($monthlyrequest)?count($monthlyrequest):0;
                    @endphp
                    <form action="{{route('monthly-ot-driver.dept-change-status',[$index,$type])}}" method="post" class="prevent-multiple-submit"> 
                      @csrf
                      <tbody class="{{$num_row>$limited_row?'fixed':''}}">
                    
                        @php $index_arr = explode("_", $index); @endphp 
                                            
                        <tr id="row{{$outer}}" style="background-color: #999 !important;color: white !important;position: sticky;top: 0;">
                          <td class="header-snum"><small id='snum{{$outer}}' style="font-size:14px;">{{$outer+1}}</small></td>
                        
                          <td class="header-row"><span id="user_name{{$outer}}">{{getUserFieldWithId($index_arr[1],'employee_name')}} ({{\Carbon\Carbon::parse($index_arr[0])->format('F-Y')}})</span></td>
                          
                          @php $main_status = getMainStatusDriver($index_arr[0],$index_arr[1]); @endphp
                          <td class="reason-info" align="right">
                            
                            @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)
                            <input type="text" name="reason" id="reason{{$outer}}" placeholder="Remark" class="form-control">
                            
                            @endif
                            
                          
                            
                          </td>
                          <td align="right" class="header-snum">
                            
                            @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)
                            <input type="submit" value="Approved" class="btn btn-success">
                          
                            @endif
                            

                          </td>
                          <td>
                            <a href="{{route('monthly-ot-driver.approved-download',[$index,$type])}}" class="btn btn-info text-white"><i class="fas fa-download"></i> Export</a>
                          </td>
                        
                        </tr>
                        @foreach($monthlyrequest as $key=>$value)
                        <tr id="next_row{{$inner}}">
                          <td class="snum"></td>
                          <td>
                            <span id="apply_date{{$inner}}">
                              {{siteformat_date($value->apply_date)}}<br>
                              ({{$value->ot_type}})<br>
                              @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)
                              <a href="{{url('ot-management/monthly-ot-driver/delete',$value->id)}}" onclick="return confirm('Are you sure want to delete?')">
                              <i class="fas fa-trash text-danger"></i></a>
                              @endif
                            </span>
                          </td>
                          <td>
                            <span id="card_time{{$inner}}">
                              <strong>Time In : </strong>{{getCardTime($value->apply_date,$value->user_id,'start_time')}}  <br>
                              <strong>Time Out : </strong>{{getCardTime($value->apply_date,$value->user_id,'end_time')}} <br>
                            
                            </span>
                          </td>
                          <td><span id="request_time{{$inner}}">
                            @php
                              if($value->end_from_time){
                                $next_day = $value->end_next_day;
                                $hotel = $value->end_hotel;
                                $start_time = siteformat_time24($value->end_from_time);
                                $end_time = siteformat_time24_nextday($value->end_to_time,$next_day);
                                $break_hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                                $break_min = $value->end_break_minute?$value->end_break_minute.' min':'';
                                $break_time = $break_hour.' '.$break_min;
                                $reason = $value->end_reason;
                                
                              }
                              else{
                                $next_day = $value->start_next_day;
                                $hotel = $value->start_hotel;
                                $start_time = siteformat_time24($value->start_from_time);
                                $end_time = siteformat_time24_nextday($value->start_to_time,$next_day);
                                $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                                $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                                $break_time = $break_hour.' '.$break_min;
                                $reason = $value->start_reason;
                              }
                            @endphp
                            <strong>Start Time : </strong><k id="start_time{{$key}}">{{$start_time}} </k> <br>
                            <strong>End Time : </strong><k id="end_time{{$key}}">{{$end_time}} {{$hotel?'(hotel)':''}}</k> <br>
                            
                            <strong>Break Time : </strong><k id="break_time{{$key}}">{{$break_time}} </k> <br>
                            @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)
                            <a id="editModal{{$key}}" href="#" data-toggle="modal" data-target="#modal-edit-time" data-id="{{$value->id}}" data-applydate="{{siteformat_date($value->apply_date)}}" data-starttime="{{siteformat_time($value->end_from_time)}}" data-endtime="{{siteformat_time($value->end_to_time)}}" data-hotel="{{$value->end_hotel}}" data-index="{{$key}}" onclick="addValueForEditTime(this)"><i class="fas fa-edit text-primary"></i></a>
                            @endif
                          </span></td>
                          <td>
                            
                            @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)
                            <div class="form-check">
                              <input type="checkbox" id="morning_checkbox{{$inner}}" class="form-check-input morningCheckAll" name="all_morning_request[]" value="{{$value->id}}" {{$value->morning_taxi_time==1?'checked':''}} style="width:17px;height:17px;"><label class="form-check-label" for="morning_checkbox{{$inner}}">Morning</label>
                            </div>
                            @else
                            <strong style="visibility:hidden;">Morning</strong>
                              
                            @endif
                      
                            
                          </td>
                          <td>
                            
                            @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)
                            <div class="form-check">
                              <input type="checkbox" id="evening_checkbox{{$inner}}" class="form-check-input eveningCheckAll" name="all_evening_request[]" value="{{$value->id}}" {{$value->evening_taxi_time==1?'checked':''}} style="width:17px;height:17px;"><label class="form-check-label" for="evening_checkbox{{$inner}}">Evening</label>
                            </div>
                            @else
                            <strong style="visibility:hidden;">Evening</strong>
                              
                            @endif
                      
                            
                          </td>
                          <input type="hidden" name="all_id[]" id="id{{$inner}}" value="{{$value->id}}">
                          <input type="hidden" name="m_id" id="m_id{{$inner}}" value="{{$value->m_id}}">
                          
                          @if($value->manager_status==0)
                            @php $manager_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->manager_status==1)
                            @php $manager_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $manager_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td><span id="manager_status{{$key}}">
                            <strong class="{{$color}} text-center">
                              {{$manager_status}}<br>
                            </strong>
                            <strong class="text-purple" style="font-size:12px;">
                              {{$value->manager_change_by?getUserFieldWithId($value->manager_change_by,'employee_name'):''}}<br>
                              {{$value->manager_change_date?siteformat_datetime24($value->manager_change_date):''}}
                            </strong>
                          </span></td>

                          
                          <td>
                            {{-- @if(Auth::user()->can('change-ot-admin-status')  and $main_status[1]!=1 and$main_status[2]!=1) --}}
                            @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)
                            <div class="form-check">
                              <input type="checkbox" id="checkbox{{$inner}}" class="form-check-input acceptCheckAll" onclick="changeAcceptCheckbox({{$inner}})" onchange="changeAcceptCheckbox({{$inner}})" name="all_accept_request[]" value="{{$value->id}}" {{$value->manager_status==1?'checked':''}} style="width:17px;height:17px;" {{$value->manager_status==2?'disabled':''}}><label class="form-check-label" for="checkbox{{$inner}}">Accept</label>
                            </div>
                            @else
                            <strong style="visibility:hidden;">Accept</strong>
                              
                            @endif
                            <input type="hidden" name="id[]" id="id{{$inner}}" value="{{$value->id}}">
                            
                          </td>
                          <td>
                            {{-- @if(Auth::user()->can('change-ot-admin-status') and $value->account_status!=1 and $value->gm_status!=1) --}}
                            @if(Auth::user()->can('change-ot-admin-status') and $main_status[1]!=1 and $main_status[2]!=1)

                              <input type="checkbox" id="reject_checkbox{{$inner}}" class="rejectCheckAll" onclick="changeRejectCheckbox({{$inner}})" onchange="changeRejectCheckbox({{$inner}})" name="all_reject_request[]" value="{{$value->id}}" {{$value->manager_status==2?'checked':''}} style="width:17px;height:17px;" {{$value->manager_status==1?'disabled':''}}><label class="form-check-label" for="checkbox{{$inner}}">Reject</label>
                            @else
                            <strong style="visibility:hidden;">Reject</strong>
                            @endif
                            
                          </td>
                          
                        </tr>
                        @php $inner += 1; @endphp
                      @endforeach
                      @php $outer += 1; @endphp
                      
                    
                  </tbody>
                  </form>
                  @endforeach
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
      @include('otmanagement.monthlyotdriver.edit-time')
      <!-- /.modal -->

    </section>
    <!-- /.content -->

@stop
@section('script')
<script>
    $(function () {
      $('#datetimepicker').datetimepicker({
          format: 'MM/YYYY'
      });
      $('#datetimepicker1').datetimepicker({
          format: 'MM/YYYY'
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
      
      $('.acceptAll').click(function(){
          if(this.checked){
              $('.acceptCheckAll').each(function(){
                  this.disabled=false;
              });

              $('.acceptCheckAll').each(function(){
                  this.checked=true;
              });
              $('.rejectCheckAll').each(function(){
                  this.disabled=true;
              });
              $('.rejectCheckAll').each(function(){
                  this.checked=false;
              });
              $(":checkbox.rejectAll").attr("disabled", true);
          }
          else{
              $('.acceptCheckAll').each(function(){
                  this.checked=false;
              });
              $('.rejectCheckAll').each(function(){
                  this.disabled=false;
              });
              $(":checkbox.rejectAll").removeAttr("disabled");
          }

      });

      $('.rejectAll').click(function(){
          if(this.checked){
              
              $('.rejectCheckAll').each(function(){
                  this.disabled=false;
              });
              $('.rejectCheckAll').each(function(){
                  this.checked=true;
              });
              $('.acceptCheckAll').each(function(){
                  this.disabled=true;
              });
              $('.acceptCheckAll').each(function(){
                  this.checked=false;
              });
              $(":checkbox.acceptAll").attr("disabled", true);

          }
          else{
              $('.rejectCheckAll').each(function(){
                  this.checked=false;
              });

              $('.acceptCheckAll').each(function(){
                  this.disabled=false;
              });
              $(":checkbox.acceptAll").removeAttr("disabled");
          }

        });

      $('.morningAll').click(function(){
          if(this.checked){
              $('.morningCheckAll').each(function(){
                  this.checked=true;
              });
              
          }
          else{
              $('.morningCheckAll').each(function(){
                  this.checked=false;
              });
              
          }

      });

      $('.eveningAll').click(function(){
          if(this.checked){
              $('.eveningCheckAll').each(function(){
                  this.checked=true;
              });
              
          }
          else{
              $('.eveningCheckAll').each(function(){
                  this.checked=false;
              });
              
          }

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

      var editfrm = $('#end_form');
      editfrm.submit(function (e) {
        e.preventDefault();               

        $.ajax({
          type: editfrm.attr('method'),
          url: "{{ url('ot-management/monthly-ot-driver/update') }}",
          data: editfrm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {
            console.log(data);
            $('#modal-edit-time').modal('hide');
            var index = data.index;

            var endtime = data.end_to_time;
            
            if(data.end_hotel){
              endtime = endtime+" (hotel)";
            }
            

            $('#start_time'+index).html(data.end_from_time);
            $('#end_time'+index).html(endtime);
            $('#break_time'+index).html(data.break_time);
            
            $("#editModal"+index).data('starttime',data.end_from_time);
            $("#editModal"+index).data('endtime',data.end_to_time);
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

    
    function changeAcceptCheckbox($i){
      if($("#checkbox"+$i).is(":checked")){
        $(":checkbox#reject_checkbox"+$i).attr("disabled", true);
      }
      else{
        $(":checkbox#reject_checkbox"+$i).removeAttr("disabled");
      }
    }
    function changeRejectCheckbox($i){
      if($("#reject_checkbox"+$i).is(":checked")){
        $(":checkbox#checkbox"+$i).attr("disabled", true);
      }
      else{
        $(":checkbox#checkbox"+$i).removeAttr("disabled");
      }
    }
    function addValueForEditTime(btn){
    
      var id=$(btn).data('id');
      var applydate=$(btn).data('applydate');
      var starttime=$(btn).data('starttime');
      var endtime=$(btn).data('endtime');
      var hotel=$(btn).data('hotel');
      var index=$(btn).data('index');

      $("#modal-edit-time #edit_id").val(id);
      $("#modal-edit-time #edit_index").val(index);
      $("#modal-edit-time #end_apply_date").html(applydate);
      $("#modal-edit-time #end_from_time").val(starttime);
      $("#modal-edit-time #end_to_time").val(endtime);
      if(hotel!=0)
        $( "#modal-edit-time #customCheckbox4" ).prop( "checked", true );
    }
    
  </script>
@stop