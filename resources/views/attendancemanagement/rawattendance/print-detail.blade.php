@extends('layouts.master')
@section('title','Print Attendance Detail')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Attendance Management</li>
              <li class="breadcrumb-item"><a href="{{url('attendance-management/raw-attendance/list')}}">Raw Attendance</a></li>
              <li class="breadcrumb-item active"><a href="{{url('attendance-management/raw-attendance/detail')}}">Attendance Detail</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-4 text-right">            
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
            
            <div class="card">
              <div class="card-header">
                <form action="{{url('attendance-management/raw-attendance/print-detail-view')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('d/m/Y');
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                    $branch=app('request')->get('branch');
                    $staff_type = app('request')->get('staff_type')
                          
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
                        <label>Branch</label>
                        <select class="form-control select2bs4" name="branch" id="branch" style="width: 100%;">
                          <option selected="selected" value="all">- All -</option>
                          @foreach($branches as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$branch?'selected':''}}>{{$value->name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="dd/mm/YYYY" value="{{isset($from_date)?$from_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <input type="text" name="to_date" id="to_date" required placeholder="dd/mm/YYYY" value="{{isset($to_date)?$to_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Staff Type</label>
                        <select class="form-control" name="staff_type" id="staff_type" style="width: 100%;">
                          <option value="all">-All-</option>
                          <option value="normal_staff" {{$staff_type=="normal_staff"?'selected':''}}>Normal Staff</option>    
                          <option value="receptionist" {{$staff_type=="receptionist"?'selected':''}}>Receptionist</option>    
                             
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('attendance-management/raw-attendance/print-detail-view')}}" class="btn btn-warning">Reset</a>
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
            
            <div class="card m-0">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h3 class="card-title">Daily Attandance Detail</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="#" class="btn btn-success breadcrumb-btn" onclick="window.print()">
                    <i class="fas fa-print"></i> Print</a>
                    @can('export-attendance-detail-list')
                    <a class="btn btn-primary breadcrumb-btn" href="{{route('attendance.detail-download',['from_date'=>isset($from_date)?$from_date:$start_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all','branch'=>isset($branch)?$branch:'all','staff_type'=>isset($staff_type)?$staff_type:'all'])}}">
                    <i class="fas fa-download"></i> Export</a>
                    @endcan
                  </div>
                </div>
              </div>

              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" id="section-to-print">
              	<div id="print-header-section" style="display:none;">
	              	<div class="row">
	                  <div class="col-sm-12 text-center" style="padding: 10px 15px;">
	                    <img src="{{asset('dist/img/marubeni.jpg')}}" alt="Marubeni Home" class="brand-image" style="width: 200px;"><br>
	              		<h4 style="font-size:20px;">Daily Detail Attendance</h4>
	                  </div>
	                  
	                </div>
	              	<div class="row">
	                  <div class="col-sm-6" style="padding: 10px 15px;">
	                    <strong>From Date : {{isset($from_date)?$from_date:$today_date}}</strong>
	                  </div>
	                  <div class="col-sm-6 text-right" style="padding: 10px 15px;">
	                    <strong>To Date : {{isset($to_date)?$to_date:$today_date}}</strong>
	                  </div>
	                </div>
	                <div class="row last-row">
	                  <div class="col-sm-6" style="padding: 10px 15px;">
	                  	@if(isset($employee) and $employee!="all")
	                    <strong>Employee : {{getUserFieldWithId($employee,'employee_name')}}</strong>
	                    @else
	                    <strong>Employee : All</strong>
	                    @endif
	                  </div>
	                </div>
                </div>
                <table class="table table-head-fixed" id="attendance_detail_print">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Device</th>
                      <th>Type</th>
                      <th>Arrival Time</th>
                      <th>Leave Time</th>
                      <th>Working Hour</th>
                      <th>Reason</th>
                      <th>Apply</th>
                      <th>Approval</th>
                      <th>Normal OT Hr</th>
                      <th>Sat OT Hr</th>
                      <th>Sunday OT Hr</th>
                      <th>Public Holiday OT Hr</th>
                      <th>OT Apply</th>
                      <th>OT Approval</th>
                      
                    </tr>
                  </thead>
                  
                  <tbody>
                    @php $i = 0; @endphp
                    @foreach($attendances as $user=>$attendance)

                      @if(count($attendance))
                        <tr style="background-color: #999 !important;color: white !important;" class="user-name">
                          <th colspan="4" class="employee_name">{{$user}}</th>
                          <td colspan="12"></td>
                        </tr>

                        @foreach($attendance as $key=>$value)

                        @if($value['id'])
                          @php
                            $att_date = new \Carbon\Carbon($value['date']);

                            if(date('N', strtotime($value['date'])) == 6){
                              $color = "#f2b6dd";
                              $holiday = 1;
                              if(isDriver($value->user_id) or isQcStaff($value->user_id)){
                                $color = "";
                                $holiday = 0;
                              }
                            }

                            else if(date('N', strtotime($value['date'])) == 7){
                              $color = "#f2b6dd";
                              $holiday = 1;
                            }

                            else if(getPublicHoliday($value['date'])){
                              $color = "#f2b6dd";
                              $holiday = 1;
                            }
                            else if($value->device=="Leave"){
                              $color = "#f2daac";
                              $holiday = 0;
                            }
                            else{
                              $color = "";
                              $holiday = 0;
                            }

                          @endphp
                          @if($value->device!="Leave")
                          <tr>
                            <th style="background-color: {{$color}}">{{siteformat_date($value['date'])}}</th>
                            <td style="background-color: {{$color}}">{{$value->device}}</td>
                            <td style="background-color: {{$color}}">
                              {{$types[$value->type.'_'.$value->type_id]}}
                            </td>
                            
                            <td style="background-color: {{$color}}">
                              {{siteformat_time24($value->start_time)}}
                            </td>
                            <td style="background-color: {{$color}}">
                              {{$value->end_time?siteformat_time24($value->end_time):' ~' }}
                            </td>
                            <td style="background-color: {{$color}}">
                              {{$holiday?'00:00':siteformat_time24($value->corrected_start_time)}}-{{$holiday?'00:00':siteformat_time24($value->corrected_end_time) }}
                            </td>
                            <td style="background-color: {{$color}}">
                              {{$value->remark}}
                            </td>

                            <td style="background-color: {{$color}}">{{$value->change_request_date?siteformat_datetime($value->change_request_date):''}}</td>
                            <td style="background-color: {{$color}}">{{$value->change_approve_date?siteformat_datetime($value->change_approve_date):''}}</td>
                            <td style="background-color: {{$color}}">{{$value->normal_ot_hr?convertTime($value->normal_ot_hr):''}}</td>
                            <td style="background-color: {{$color}}">{{$value->sat_ot_hr?convertTime($value->sat_ot_hr):''}}</td>
                            <td style="background-color: {{$color}}">{{$value->sunday_ot_hr?convertTime($value->sunday_ot_hr):''}}</td>
                            <td style="background-color: {{$color}}">{{$value->public_holiday_ot_hr?convertTime($value->public_holiday_ot_hr):''}}</td>
                            @if($value->normal_ot_hr or $value->sunday_ot_hr or $value->public_holiday_ot_hr)
                            <td style="background-color: {{$color}}">{{$value->ot_request_date?siteformat_datetime($value->ot_request_date):''}}</td>
                            <td style="background-color: {{$color}}">{{$value->ot_approve_date?siteformat_datetime($value->ot_approve_date):''}}</td>
                            @else
                            <td style="background-color: {{$color}}"></td>
                            <td style="background-color: {{$color}}"></td>
                            @endif
                          </tr>
                          @else
                          <tr>
                            <th style="background-color: {{$color}}">{{siteformat_date($value['date'])}}</th>
                            
                            <td style="background-color: {{$color}}" colspan="16">
                              {{$types[$value->type.'_'.$value->type_id]}}
                            </td>
                          </tr>
                          @endif
                        @else
                          @php
                            $att_date = new \Carbon\Carbon($value['date']);
                          @endphp
                          @if(date('N', strtotime($value['date'])) == 6)

                          <tr>
                            <th style="background-color:#f2b6dd;">{{siteformat_date($value['date'])}}</th>
                            
                            <td colspan="16" style="background-color:#f2b6dd;">Saturday</td>
                            
                            {{-- #f2daac --}}
                          </tr>
                          @elseif(date('N', strtotime($value['date'])) == 7)
                          <tr>
                            <th style="background-color:#f2b6dd;">{{siteformat_date($value['date'])}}</th>
                            
                            <td colspan="16" class="br-warning" style="background-color:#f2b6dd;">Sunday</td>
                            

                          </tr>
                          @elseif(getPublicHoliday($value['date']))
                          <tr>
                            <th style="background-color:#f2b6dd;">{{siteformat_date($value['date'])}}</th>
                            
                            <td style="background-color:#f2b6dd;" colspan="16">{{getPublicHoliday($value['date'])}}</td>
                            

                          </tr>
                          @else
                          <tr>
                            <th style="background-color: #f2daac;">{{siteformat_date($value['date'])}}</th>
                            
                            <td style="background-color: #f2daac;" colspan="16"></td>
                            

                          </tr>
                          @endif
                        @endif
                        @php $i += 1; @endphp
                        @endforeach
                      @endif
                    @endforeach
                    
                    <!-- attendance length -->
                      <input type="hidden" name="count" id="count" value="{{$i}}">
                    <!-- attendance length -->
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

    </section>
    <!-- /.content -->

@stop
@section('script')
<script>
    $(function () {
      window.print();
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
      
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#dataTables').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "responsive": true,
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