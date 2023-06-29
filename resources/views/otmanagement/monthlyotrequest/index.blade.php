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

                    <div class="col-sm-2" style="margin-top: 37px;">
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
                <table class="table fixed-tbody" id="monthly_ot_record">
                  <thead>
                    <tr style="background-color: #17cdcc !important;">
                      <th class="snum">No</th>
                      <th>Apply Date</th>
                      <th>Card Time</th>
                      <th>Request Time</th>
                      <th>Request Reason</th>
                      <th>Requested Date</th>
                      
                      <th>Approved By Department GM</th>
                      <th>Approved By Accountant</th>
                      <th>Approved By Admin GM</th>
                      <th></th>
                    </tr>
                  </thead>
                  @php 
                  $outer = 0;$inner = 0;$type="user";$limited_row=3;$status_label="";
                  if(Auth::user()->can('change-ot-manager-status')){
                    $type = "manager";
                    $limited_row = 2;
                    $status_label = "Dept GM Status";
                  }
                  else if(Auth::user()->can('change-ot-account-status')){
                    $type = "accountant";
                    $limited_row = 2;
                    $status_label = "Account Status";
                  }
                  else if(Auth::user()->can('change-ot-gm-status')){
                    $type = "gm";
                    $limited_row = 2;
                    $status_label = "Admin GM Staus";
                  }
                  @endphp
                  @foreach($monthlyrequests as $index=>$monthlyrequest)
                  @php
                  $num_row = is_countable($monthlyrequest)?count($monthlyrequest):0;
                  @endphp
                  <form action="{{route('monthly-ot-request.change-status',[$index,$type])}}" method="post" class="prevent-multiple-submit"> 
                    @csrf
                  <tbody class="{{$num_row>$limited_row?'fixed':''}}">
                    
                      @php $index_arr = explode("_", $index); @endphp 
                                            
                      <tr id="row{{$outer}}" style="background-color: #999 !important;color: white !important;position: sticky;top: 0;">
                        <td class="header-snum"><small id='snum{{$outer}}' style="font-size:14px;">{{$outer+1}}</small></td>
                        
                        <td class="header-row"><span id="user_name{{$outer}}">{{getUserFieldWithId($index_arr[1],'employee_name')}} ({{\Carbon\Carbon::parse($index_arr[0])->format('F-Y')}})</span></td>
                        <td class="status-info">{{$status_label}}</td>
                        {{-- <td class="status-info" colspan="2">
                          
                          @if(Auth::user()->can('change-ot-manager-status') or Auth::user()->can('change-ot-account-status') or Auth::user()->can('change-ot-gm-status'))
                          <select class="form-control" name="status" id="status{{$outer}}" style="width: 100%;" required>
                              <option value="">-Select-</option>
                              <option value="1">Accept</option>
                              <option value="2">Reject</option>
                                                                    
                          </select>
                          @endif

                        </td> --}}
                        <td class="reason-info" align="right">
                            
                            @if(Auth::user()->can('change-ot-manager-status') or Auth::user()->can('change-ot-account-status') or Auth::user()->can('change-ot-gm-status'))
                            <input type="text" name="reason" id="reason{{$outer}}" placeholder="Remark" class="form-control">
                            
                            @endif
                            
                          
                            
                        </td>
                        <td align="right" class="header-snum">
                          @php $main_status = getMainStatus($index_arr[0],$index_arr[1]); @endphp
                          @if(Auth::user()->can('change-ot-manager-status') and $main_status[0]!=1)
                            <input type="submit" value="Accept" class="btn btn-success">
                          @elseif(Auth::user()->can('change-ot-account-status') and $main_status[1]!=1)
                            <input type="submit" value="Accept" class="btn btn-success">
                          @elseif( Auth::user()->can('change-ot-gm-status') and $main_status[2]!=1)
                            <input type="submit" value="Accept" class="btn btn-success">
                          @endif
                        </td>
                        
                      </tr>
                      @foreach($monthlyrequest as $key=>$value)
                        <tr id="next_row{{$inner}}">
                          <td class="snum"></td>
                          <td>
                            <span id="apply_date{{$inner}}">
                              {{siteformat_date($value->apply_date)}}<br>
                              ({{$value->ot_type}})
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
                            <strong>Start Time : </strong>{{$start_time}}  <br>
                            <strong>End Time : </strong>{{$end_time}} {{$hotel?'(hotel)':''}} <br>
                            
                            <strong>Break Time : </strong>{{$break_time}}  <br>
                            
                          </span></td>
                          <td><span id="request_reason{{$inner}}">{{$reason}}</span></td>
                          <td><span id="request_date{{$inner}}">{{siteformat_datetime24($value->created_at)}}</span></td>
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
                              {{$value->manager_change_by?getUserFieldWithId($value->user_id,'employee_name'):''}}<br>
                              {{$value->manager_change_date?siteformat_datetime24($value->manager_change_date):''}}
                            </strong>
                          </span></td>

                          @if($value->account_status==0)
                            @php $account_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->account_status==1)
                            @php $account_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $account_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td><span id="account_status{{$key}}">
                            <strong class="{{$color}}">{{$account_status}}<br></strong>
                            <strong class="text-purple" style="font-size:12px;">
                              {{$value->account_change_by?getUserFieldWithId($value->user_id,'employee_name'):''}}<br>
                              {{$value->account_change_date?siteformat_datetime24($value->account_change_date):''}}
                            </strong>
                          </span></td>

                          @if($value->gm_status==0)
                            @php $gm_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->gm_status==1)
                            @php $gm_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $gm_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td><span id="gm_status{{$key}}">
                            <strong class="{{$color}}">{{$gm_status}}<br></strong>
                            <strong class="text-purple" style="font-size:12px;">
                              {{$value->gm_change_by?getUserFieldWithId($value->user_id,'employee_name'):''}}<br>
                              {{$value->gm_change_date?siteformat_datetime24($value->gm_change_date):''}}
                            </strong>
                            </span>
                          </td>
                          <td>
                            @if(Auth::user()->can('change-ot-manager-status') and $value->manager_status!=1)

                              <input type="checkbox" id="checkbox{{$inner}}" class="checkAll" name="all_request[]" value="{{$value->id}}" style="width:18px;height:18px;">
                              <input type="hidden" name="id[]" id="id{{$inner}}" value="{{$value->id}}">
                            @elseif(Auth::user()->can('change-ot-account-status') and $value->account_status!=1 and !Auth::user()->can('change-ot-gm-status'))
                            
                              <input type="checkbox" id="checkbox{{$inner}}" class="checkAll" name="all_request[]" value="{{$value->id}}" style="width:18px;height:18px;">
                              <input type="hidden" name="id[]" id="id{{$inner}}" value="{{$value->id}}">
                            @elseif(Auth::user()->can('change-ot-gm-status') and $value->gm_status!=1 and !Auth::user()->can('change-ot-account-status') and !Auth::user()->can('change-ot-manager-status'))
                            
                              <input type="checkbox" id="checkbox{{$inner}}" class="checkAll" name="all_request[]" value="{{$value->id}}" style="width:18px;height:18px;">
                              <input type="hidden" name="id[]" id="id{{$inner}}" value="{{$value->id}}">
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