@extends('layouts.master')
@section('title','Monthly OT Summary')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">OT Management</li>
              <li class="breadcrumb-item active"><a href="{{url('ot-management/monthly-ot-summary')}}">Monthly OT Summary</a></li>
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
                <form action="{{route('monthly-ot-request.monthly-ot-detail')}}" method="get">
                  
                  <div class="row">
                  	
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Employee Name</label>
                        <select class="form-control select2bs4" name="id" id="id" required style="width: 100%;">
                          
                          @foreach($employees as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$user_id?'selected':''}}>{{$value->employee_name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="date" id="date" required placeholder="YYYY-MM" value="{{$date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('ot-management/monthly-ot-summary')}}" class="btn btn-warning text-white">Back</a>
                          
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
                    <h3 class="card-title text-bold">{{$user->employee_name}} - {{\Carbon\Carbon::parse($date)->format('F Y')}}</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <!-- <a href="#" class="btn btn-primary breadcrumb-btn" onclick="window.print()">
                    <i class="fas fa-print"></i> Print</a> -->
                    <a href="{{route('monthly-ot-request.monthly-ot-detail-download',['id'=>$user,'date'=>$date])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div id="section-to-print">
              <div class="card-body table-responsive">
                <div id="print-header-section" style="display:none;">
                  <div class="row">
                    <div class="col-sm-12 text-center" style="padding: 10px 15px;">
                      <img src="{{asset('dist/img/marubeni.jpg')}}" alt="Marubeni Home" class="brand-image" style="width: 200px;"><br>
                    <h4 style="font-size:20px;">OT Detail List</h4>
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-sm-6" style="padding: 10px 15px;">
                      <strong>{{$user->employee_name}} - {{\Carbon\Carbon::parse($date)->format('F Y')}}
                    </div>
                    
                  </div>
                  
                  </div>
                </div>
                <table class="table" id="ot_individual">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>OT Type</th>
                      <th>Estimate OT</th>
                      <th>Actual OT</th>
                      <th>Break Time</th>
                      <th>OT Hour</th>
                      <th>Hotel</th>
                      <th>Normal Taxi Times</th>
                      <th>Special Taxi Times</th>
                      <th>Reason</th>
                      <th>Applicant Sign</th>
                      <th>Dept Approval</th>
                      <th>Account Approval</th>
                      <th>Admin GM Approval</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = 1;$weekday_hour=0;$weekday_minute=0;$holiday_hour=0;$holiday_minute=0;
                    $net_total = 0;$total_hr=0;$total_hour = 0;$total_minute = 0;$overtime_net_total = 0; @endphp
                    @foreach($otdetails as $key=>$value)
                      @php
                        if(isset($value->attendance_id))
                          $type = "driver";
                        else
                          $type = "staff";
                      @endphp
                      <tr style="font-weight:normal;">
                      	<td>{{siteformat_date($value->apply_date)}}</td>
                        <td>{{$value->ot_type}}</td>
                      	
                        <td>{{siteformat_time24($value->start_from_time)}} - {{siteformat_time24_nextday($value->start_to_time,$value->start_next_day)}} {{$value->start_hotel?'(hotel)':''}}</td>
                        <td>{{siteformat_time24($value->end_from_time)}} - {{siteformat_time24_nextday($value->end_to_time,$value->end_next_day)}}</td>
                        @php
                              if($value->end_from_time){
                                $start_time = siteformat_time24($value->end_from_time);
                                $end_time = siteformat_time24($value->end_to_time);
                                $break_hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                                $break_min = $value->end_break_minute?$value->end_break_minute.' min':'';
                                $break_time = $break_hour.' '.$break_min;
                                $reason = $value->end_reason;
                              }
                              else{
                                $start_time = siteformat_time24($value->start_from_time);
                                $end_time = siteformat_time24($value->start_to_time);
                                $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                                $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                                $break_time = $break_hour.' '.$break_min;
                                $reason = $value->start_reason;
                              }
                              $ot_hour = getOTHour($value->id,$type);

                              if($value->ot_type=="Weekday"){
                                
                                $weekday = explode(":",convertTime($ot_hour));
                                $weekday_hour += $weekday[0];
                                $weekday_minute += $weekday[1];

                                $total_hour += $weekday[0];
                                $total_minute += $weekday[1];
                              }
                              else{
                                
                                $holiday = explode(":",convertTime($ot_hour));
                                $holiday_hour += $holiday[0];
                                $holiday_minute += $holiday[1];

                                $total_hour += $holiday[0];
                                $total_minute += $holiday[1];
                              }
                          @endphp
                        <td>{{$break_time}}</td>
                      	<td>{{convertTime($ot_hour)}}</td>
                      	@php
                      		$total_hr+=$ot_hour;
                      	@endphp
                        <td>
                          @if($value->end_hotel)
                          <i class="fa fa-check-square text-center"></i>
                          @endif

                        </td>
                        @if($value->morning_taxi_time or $value->evening_taxi_time)
                        <td></td>
                        <td>{{getTaxiTime($value->user_id,$value)}}</td>
                        @else
                        <td>{{getTaxiTime($value->user_id,$value)}}</td>
                        <td></td>
                        @endif
                      	<td>{{$reason}}</td>
                      	<td></td>
                      	<td><i class="fa fa-check-square text-center"></i></td>
                      	<td><i class="fa fa-check-square text-center"></i></td>
                      	<td><i class="fa fa-check-square text-center"></i></td>
                      	
                      </tr>
                      
                      

                      @php $i += 1; @endphp
                    @endforeach
                    @php
                      $request_date = \Carbon\Carbon::parse($date)->format("01/m/Y");
                      $ot_payment = getOTPayment($user_id,$request_date);

                      
                      $taxi_time = getTaxiCharge($user_id,$request_date,true);

                      $taxi_charge = round_up($taxi_time * 3,2);

                    @endphp
                    
                    <tr class="text-right text-bold">
                      @php
                        $weekday_hour += floor($weekday_minute / 60);
                        $weekday_minute = ($weekday_minute -   floor($weekday_minute / 60) * 60);
                        $weekday_time = str_pad($weekday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($weekday_minute, 2, '0', STR_PAD_LEFT);
                        $weekday_payment = getOTAmount($weekday_time,$ot_payment);
                      @endphp
                      <td colspan="8">Weekdays Overtime Allowance</td>
                      <td colspan="2">{{$weekday_time}} HRS x ${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td>${{siteformat_number($weekday_payment)?siteformat_number($weekday_payment):$weekday_payment}}</td>

                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      @php
                        $holiday_hour += floor($holiday_minute / 60);
                        $holiday_minute = ($holiday_minute -   floor($holiday_minute / 60) * 60);
                        $holiday_time = str_pad($holiday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($holiday_minute, 2, '0', STR_PAD_LEFT);
                        $holiday_payment = getOTAmount($holiday_time,$ot_payment);

                        $overtime_net_total = $weekday_payment + $holiday_payment;

                        $net_total = $weekday_payment + $holiday_payment + $taxi_charge;
                      @endphp
                      <td colspan="8">Holidays Overtime Allowance</td>
                      <td colspan="2">{{$holiday_time}} HRS x ${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td>${{siteformat_number($holiday_payment)?siteformat_number($holiday_payment):$holiday_payment}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      @php
                        $total_hour += floor($total_minute / 60);
                        $total_minute = ($total_minute -   floor($total_minute / 60) * 60);
                        $total_time = str_pad($total_hour, 2, '0', STR_PAD_LEFT).":".str_pad($total_minute, 2, '0', STR_PAD_LEFT);
                      @endphp
                      <td colspan="8">Total Overtime Allowance {{-- (Time Display) --}}</td>
                      <td></td>
                      <td></td>
                      <td>{{$total_time}} HRS</td>
                      <td colspan="3"></td>
                    </tr>
                    {{-- <tr class="text-right text-bold">
                      <td colspan="8">Total Overtime Allowance (Numerical Display)</td>
                      <td></td>
                      <td></td>
                      <td>{{$total_hr}}</td>
                      <td colspan="3"></td>
                    </tr> --}}
                    <tr class="text-right text-bold">
                      <td colspan="8">OT Rate</td>
                      <td></td>
                      <td></td>
                      <td>${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="8">Total Overtime Allowances</td>
                      <td></td>
                      <td></td>
                      <td>${{number_format(round_up_nodecimal($net_total))}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="8">Taxi Charge ({{getTaxiCharge($user_id,\Carbon\Carbon::parse($date)->format("1/m/Y"),true)}} times)</td>
                      <td></td>
                      <td></td>
                      <td>${{siteformat_number(getTaxiCharge($user_id,\Carbon\Carbon::parse($date)->format("1/m/Y")))}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="8">All Total Allowances</td>
                      <td></td>
                      <td></td>
                      <td>${{number_format(round_up_nodecimal($net_total))}}</td>
                      <td colspan="3"></td>
                    </tr>
                  </tbody>

                </table>
              </div>
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
      $('#datetimepicker').datetimepicker({
          format: 'YYYY-MM'
      });
      $('#datetimepicker1').datetimepicker({
          format: 'YYYY'
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

  </script>
@stop