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
                    @php $i = 1;$monday_hour=0;$monday_minute=0;$public_hour=0;$public_minute=0;
                    $sunday_less_hour=0;$sunday_less_minute=0;$sunday_between_hour=0;$sunday_between_minute=0;$net_total = 0;$overtime_net_total = 0; @endphp
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
                      	
                        <td>{{siteformat_time24($value->start_from_time)}} - {{siteformat_time24_nextday($value->start_to_time,$value->start_next_day)}}</td>
                        <td>{{siteformat_time24($value->end_from_time)}} - {{siteformat_time24_nextday($value->end_to_time,$value->end_next_day)}}</td>
                        @php
                          if($value->end_from_time){
                            $start_time = siteformat_time24($value->end_from_time);
                            $end_time = siteformat_time24($value->end_to_time);
                            $break_hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                            $break_min = $value->end_break_minute?$value->end_break_minute.' min':'';
                            $break_time = $break_hour.' '.$break_min;
                            $reason = $value->end_reason;
                            $next_day = $value->end_next_day;
                          }
                          else{
                            $start_time = siteformat_time24($value->start_from_time);
                            $end_time = siteformat_time24($value->start_to_time);
                            $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                            $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                            $break_time = $break_hour.' '.$break_min;
                            $reason = $value->start_reason;
                            $next_day = $value->start_next_day;
                          }
                          $ot_hour = getOTHour($value->id,$type);

                          if($value->ot_type=="Weekday" or $value->ot_type=="Saturday"){
                            
                            $monday = explode(":",convertTime($ot_hour));
                            $monday_hour += $monday[0];
                            $monday_minute += $monday[1];
                          }
                          else if($value->ot_type=="Sunday"){
                            if (strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and !$next_day){
                              
                              $sundayless = explode(":",convertTime($ot_hour));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
                     
                            }
                            else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and $next_day){
                              $time = getTimeDiff($start_time,"8:00")+getTimeDiff("18:00","24:00") + getTimeDiff("00:00",$end_time);
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
            
                              $time = getTimeDiff("8:00","18:00");
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];

                            }
                            else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and !$next_day){
                              $time = getTimeDiff($start_time,"8:00");
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
                              
                              $time = getTimeDiff("8:00",$end_time);
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
          
                            }
                            else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and $next_day){
                              $time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00");
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                              $time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00",$end_time);
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
                                
                            }
                            else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and !$next_day){
                              $time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00",$end_time);
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                              $time = getTimeDiff("8:00","18:00");
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
          
                            }
                            else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and $next_day){
                              $time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                              $time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00","18:00");
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
          
                            }
                        
                            else if (strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                              
                              $sundayless = explode(":",convertTime($ot_hour));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                            }
                            else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                              $time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          

                              $time = getTimeDiff("8:00","18:00");
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
            

                            }
                            else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) < strtotime("8:00")){
                              $time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00",$end_time);
                              

                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          

                            }
                            else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("8:00") and strtotime($end_time) < strtotime("18:00")){
                              $time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00");
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                              $time = getTimeDiff("8:00",$end_time);
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
          
                            }

                            else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and !$next_day){
                              
                              $sundaybetween = explode(":",convertTime($ot_hour));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
                  
                            }
                            else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and $next_day){
                              $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00");
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                              $time = getTimeDiff($start_time,"18:00") + getTimeDiff("8:00",$end_time);
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
          

                            }
                            else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                              $time = getTimeDiff("18:00",$end_time);
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                              $time = getTimeDiff($start_time,"18:00");
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
          
                            }
                            else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                              $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00")+getTimeDiff("18:00",$end_time);
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
        
                              $time = getTimeDiff($start_time,"18:00")+getTimeDiff("8:00","18:00");
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
                            }
                            else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <= strtotime("8:00")){
                              $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00",$end_time);
                              
                              $sundayless = explode(":",convertTime($time));
                              $sunday_less_hour += $sundayless[0];
                              $sunday_less_minute += $sundayless[1];
          
                              $time = getTimeDiff($start_time,"18:00");
                              
                              $sundaybetween = explode(":",convertTime($time));
                              $sunday_between_hour += $sundaybetween[0];
                              $sunday_between_minute += $sundaybetween[1];
          
                            }
                
                          }
                          else{
                            
                            $public = explode(":",convertTime($ot_hour));
                            $public_hour += $public[0];
                            $public_minute += $public[1];
                          }
                        @endphp
                        <td>{{$break_time}}</td>
                      	<td>{{convertTime($ot_hour)}}</td>
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
                      <td colspan="6">Monday to Saturday Overtime Allowance</td>
                      <td></td>
                      @php
                        $monday_hour += floor($monday_minute / 60);
                        $monday_minute = ($monday_minute -   floor($monday_minute / 60) * 60);
                        $ot_time = str_pad($monday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($monday_minute, 2, '0', STR_PAD_LEFT);
                        $monday_payment = getOTAmount($ot_time,$ot_payment);
                      @endphp
                      <td colspan="2">{{$ot_time}} HRS x ${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td colspan="2">${{siteformat_number($monday_payment)?siteformat_number($monday_payment):$monday_payment}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="6">Public Holidays Overtime Allowance</td>
                      <td></td>
                      @php
                        $public_hour += floor($public_minute / 60);
                        $public_minute = ($public_minute -   floor($public_minute / 60) * 60);
                        $ot_time = str_pad($public_hour, 2, '0', STR_PAD_LEFT).":".str_pad($public_minute, 2, '0', STR_PAD_LEFT);
                        $public_payment = getOTAmount($ot_time,$ot_payment);
                      @endphp
                      <td colspan="2">{{$ot_time}} HRS x ${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td colspan="2">${{siteformat_number($public_payment)?siteformat_number($public_payment):$public_payment}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="6">Sunday Allowance (<8:00 am and >18:00 pm)</td>
                      <td></td>
                      @php
                        $sunday_less_hour += floor($sunday_less_minute / 60);
                        $sunday_less_minute = ($sunday_less_minute -   floor($sunday_less_minute / 60) * 60);
                        $ot_time = str_pad($sunday_less_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_less_minute, 2, '0', STR_PAD_LEFT);
                        $sunday_less_payment = getOTAmount($ot_time,$ot_payment);
                      @endphp
                      <td colspan="2">{{$ot_time}} HRS x ${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td colspan="2">${{siteformat_number($sunday_less_payment)?siteformat_number($sunday_less_payment):$sunday_less_payment}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="6">Sunday Allowance (8:00 ~ 18:00)</td>
                      <td></td>
                      @php
                        $sunday_between_hour += floor($sunday_between_minute / 60);
                        $sunday_between_minute = ($sunday_between_minute -   floor($sunday_between_minute / 60) * 60);
                        $ot_time = str_pad($sunday_between_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_between_minute, 2, '0', STR_PAD_LEFT);
                        $sunday_between_payment = getOTAmount($ot_time,$ot_payment);

                        $overtime_net_total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment;

                        $net_total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment + $taxi_charge;
                      @endphp
                      <td colspan="2">{{$ot_time}} HRS x ${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td colspan="2">${{siteformat_number($sunday_between_payment)?siteformat_number($sunday_between_payment):$sunday_between_payment}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="6">OT Rate</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td colspan="2">${{siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment}}</td>
                      <td colspan="2"></td>
                      <td colspan="3"></td>
                      
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="6">Total Overtime Allowances</td>
                      <td></td>
                      <td colspan="2"></td>
                      <td colspan="2">${{number_format(round_up_nodecimal($overtime_net_total))}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="6">(<6:30 am and >21:30 pm) <br>Overtime Transportation Allowance</td>
                        <td></td>
                      <td colspan="2">{{$taxi_time}} Times x $3</td>
                      <td colspan="2">${{$taxi_charge}}</td>
                      <td colspan="3"></td>
                    </tr>
                    <tr class="text-right text-bold">
                      <td colspan="6">All Total Allowances</td>
                      <td></td>
                      <td colspan="2"></td>
                      <td colspan="2">${{number_format(round_up_nodecimal($net_total))}}</td>
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