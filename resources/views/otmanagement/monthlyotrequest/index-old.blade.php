@extends('layouts.master')
@section('title','Monthly OT Request')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">OT Management</li>
              <li class="breadcrumb-item active"><a href="{{url('ot-management/monthly-ot-request/list')}}">Monthly OT Request List</a></li>
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
                <form action="{{url('ot-management/monthly-ot-request/list')}}" method="get">
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
                          @if(!isset($status) or $status=="all")
                            <option value="all" selected>- All -</option>
                          @else
                            <option value="all">- All -</option>
                          @endif
                          @if(isset($status) and $status=="0")
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
                          <input type="text" name="from_date" id="from_date" placeholder="dd/mm/YYYY" value="{{isset($from_date)?$from_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <input type="text" name="to_date" id="to_date" placeholder="dd/mm/YYYY" value="{{isset($to_date)?$to_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('ot-management/monthly-ot-request/list')}}" class="btn btn-warning">Reset</a>
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
                    <h3 class="card-title">Monthly OT Request List</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover" id="ot_record">
                  <thead>
                    <tr style="background-color: #17cdcc !important;">
                      <th>No</th>
                      <th>Apply Date</th>
                      <th>Request Time</th>
                      <th>Request Reason</th>
                      <th>Requested Date</th>
                      @if(isManager())
                      <th>Manager Status</th>
                      @elseif(isAccountant())
                      <th>Account Status</th>
                      @elseif(isAdministrator())
                      <th>GM Status</th>
                      @endif
                      <th>Approved By Manager</th>
                      <th>Approved By Accountant</th>
                      <th>Approved By Admin GM</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $outer = 0;$inner = 0; @endphp
                    @foreach($monthlyrequests as $index=>$monthlyrequest)
                      @php $index_arr = explode(",", $index); @endphp 
                                            
                      <tr id="row{{$outer}}" style="background-color: #999 !important;color: white !important;position: sticky;top: 0;">
                        <td><small id='snum{{$outer}}' style="font-size:14px;">{{$outer+1}}</small></td>
                        
                        <td colspan="3"><span id="user_name{{$outer}}">{{getUserFieldWithId($index_arr[1],'employee_name')}} ({{\Carbon\Carbon::parse($index_arr[0])->format('F-Y')}})</span></td>
                        <td colspan="6"></td>
                        
                      </tr>
                      @foreach($monthlyrequest as $key=>$value)
                        <tr id="next_row{{$inner}}">
                          <td></td>
                          <td><span id="apply_date{{$inner}}">{{siteformat_date($value->apply_date)}}</span></td>
                          <td><span id="request_time{{$inner}}">
                            <strong>Start Time : </strong>{{$value->end_from_time?siteformat_time24($value->end_from_time):siteformat_time24($value->start_from_time)}}  <br>
                            <strong>End Time : </strong>{{$value->end_to_time?siteformat_time24($value->end_to_time):siteformat_time24($value->start_to_time)}} <br>
                            @if($value->end_to_time or $value->end_from_time
                            )
                            <strong>Break Time : </strong>{{$value->end_break_hour?$value->end_break_hour.' hr':''}} {{$value->end_break_minute?$value->end_break_minute.' min':''}}  <br>
                            @elseif($value->start_to_time or $value->start_from_time
                            )
                            <strong>Break Time : </strong>{{$value->start_break_hour?$value->start_break_hour.' hr':''}} {{$value->start_break_minute?$value->start_break_minute.' min':''}}  <br>
                            @endif
                          </span></td>
                          <td><span id="request_reason{{$inner}}">{{$value->start_reason}}</span></td>
                          <td><span id="request_date{{$inner}}">{{siteformat_datetime($value->created_at)}}</span></td>
                          <input type="hidden" name="id[]" id="id{{$inner}}" value="{{$value->id}}">
                          @if(isManager())
                          <td>
                            <select class="form-control" name="manager_status[]" id="manager_status{{$inner}}" style="width: 100%;" onchange="selectCheckbox({{$inner}})">
                                  <option value="0" {{$value->manager_status==0?'selected':''}}>Pending</option>
                                  <option value="1" {{$value->manager_status==1?'selected':''}}>Accept</option>
                                  <option value="2" {{$value->manager_status==2?'selected':''}}>Reject</option>
                                                                    
                              </select><br>
                              <input type="text" class="form-control" name="manager_status_reason[]" value="{{$value->manager_status_reason}}" id="manager_status_reason{{$inner}}" onkeyup="selectCheckbox({{$inner}})" onchange="selectCheckbox({{$inner}})">
                          </td>
                          @elseif(isAccountant())
                          <td>Account Status</td>
                          @elseif(isAdministrator())
                          <td>GM Status</td>
                          @endif
                          @if($value->manager_status==0)
                            @php $manager_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->manager_status==1)
                            @php $manager_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $manager_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td><span id="manager_status{{$key}}">
                            <strong class="{{$color}}">{{$manager_status}}</strong>
                          </span></td>

                          @if($value->account_status==0)
                            @php $account_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->account_status==1)
                            @php $account_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $account_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td><span id="account_status{{$key}}">
                            <strong class="{{$color}}">{{$account_status}}</strong>
                          </span></td>

                          @if($value->gm_status==0)
                            @php $gm_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->gm_status==1)
                            @php $gm_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $gm_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td><span id="gm_status{{$key}}">
                            <strong class="{{$color}}">{{$gm_status}}</strong>
                          </span></td>
                          <td>
                            <input type="checkbox" id="checkbox{{$inner}}" class="checkAll" name="all_request[]" value="{{$value->id}}" style="width:18px;height:18px;">
                          </td>
                          
                        </tr>
                        @php $inner += 1; @endphp
                      @endforeach
                      @php $outer += 1; @endphp
                      
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
        
    });

    function selectCheckbox($i){
      
      $("#checkbox"+$i).attr('checked',true);
    }
    
  </script>
@stop