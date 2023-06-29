@extends('layouts.master')
@section('title','Monthly OT by Staff')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">OT Management</li>
              <li class="breadcrumb-item active"><a href="{{url('ot-management/monthly-ot-staff/monthly-ot-by-staff')}}">Monthly OT by Staff</a></li>
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
                <form action="{{url('ot-management/monthly-ot-staff/monthly-ot-by-staff')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('m/Y');
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                    $department=app('request')->get('department');
                    
                          
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
                          <a href="{{url('ot-management/monthly-ot-staff/monthly-ot-by-staff')}}" class="btn btn-warning">Reset</a>
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
                    <h3 class="card-title">Monthly OT by Staff</h3>
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
                      <th>Approved By Account</th>
                      <th>Approved by Admin GM</th>
                    </tr>
                  </thead>
                  @php $outer = 0; @endphp 
                  @foreach($monthlyrequests as $index=>$monthlyrequest)
                    
                    
                    <tbody>
                    
                        @php $index_arr = explode("_", $index); @endphp 
                                            
                        <tr style="background-color: #999 !important;color: white !important">
                          <td class="header-snum"><small style="font-size:14px;">{{$outer+1}}</small></td>
                        
                          <td class="header-row">{{getUserFieldWithId($index_arr[1],'employee_name')}} ({{\Carbon\Carbon::parse($index_arr[0])->format('F-Y')}})</td>
                          
                          
                          <td class="reason-info" align="right">
                           
                          </td>
                          <td align="right" class="header-snum">
                            
                          </td>
                        
                        </tr>
                        @foreach($monthlyrequest as $key=>$value)
                        <tr>
                          <td class="snum"></td>
                          <td>
                              {{siteformat_date($value->apply_date)}}<br>
                              ({{$value->ot_type}})
                           
                          </td>
                          <td>
                              <strong>Time In : </strong>{{getCardTime($value->apply_date,$value->user_id,'start_time')}}  <br>
                              <strong>Time Out : </strong>{{getCardTime($value->apply_date,$value->user_id,'end_time')}} <br>
                            
                          </td>
                          <td>
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
                            
                          </td>
                          <td>{{$reason}}</td>
                          <td>{{siteformat_datetime24($value->created_at)}}</td>
                         
                          
                          @if($value->manager_status==0)
                            @php $manager_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->manager_status==1)
                            @php $manager_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $manager_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td>
                            <strong class="{{$color}} text-center">
                              {{$manager_status}}<br>
                            </strong>
                            <strong class="text-purple" style="font-size:12px;">
                              {{$value->manager_change_by?getUserFieldWithId($value->manager_change_by,'employee_name'):''}}<br>
                              {{$value->manager_change_date?siteformat_datetime24($value->manager_change_date):''}}
                            </strong>
                          </td>

                          @if($value->account_status==0)
                            @php $account_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->account_status==1)
                            @php $account_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $account_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td>
                            <strong class="{{$color}} text-center">
                              {{$account_status}}<br>
                            </strong>
                            <strong class="text-purple" style="font-size:12px;">
                              {{$value->account_change_by?getUserFieldWithId($value->account_change_by,'employee_name'):''}}<br>
                              {{$value->account_change_date?siteformat_datetime24($value->account_change_date):''}}
                            </strong>
                          </td>

                          @if($value->gm_status==0)
                            @php $gm_status = 'Pending';$color = "text-primary"; @endphp
                          @elseif($value->gm_status==1)
                            @php $gm_status = 'Accept';$color = "text-success"; @endphp
                          @else
                            @php $gm_status = 'Reject';$color = "text-danger"; @endphp
                          @endif
                          
                          <td>
                            <strong class="{{$color}} text-center">
                              {{$gm_status}}<br>
                            </strong>
                            <strong class="text-purple" style="font-size:12px;">
                              {{$value->gm_change_by?getUserFieldWithId($value->gm_change_by,'employee_name'):''}}<br>
                              {{$value->gm_change_date?siteformat_datetime24($value->gm_change_date):''}}
                            </strong>
                          </td>

                        </tr>
                       
                      @endforeach
                      @php $outer += 1; @endphp
                      
                    
                  	</tbody>
                  
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

    
  </script>
@stop