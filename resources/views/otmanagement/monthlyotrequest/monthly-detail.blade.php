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
                <form action="{{url('ot-management/monthly-ot-summary')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('m/Y');
                    
                    $date=app('request')->get('date');
                    $employee=app('request')->get('employee');
                    $type=app('request')->get('type');
                    $employee_type = isset($type)?$type:1;

                    $detail_date = isset($date)?$date:$today_date;
                    $explode_date = explode('/', $detail_date);
                    $month = $explode_date[0];
                    $year = $explode_date[1];
                    $request_date = $year."-".$month;
                  @endphp
                  <div class="row">
                  	<div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Type</label>
                        <select class="form-control select2bs4" name="type" id="type" style="width: 100%;">
                          
                          @foreach($employee_types as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$employee_type?'selected':''}}>{{$value->type}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
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
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="date" id="date" required placeholder="YYYY" value="{{isset($date)?$date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <a href="{{url('ot-management/monthly-ot-summary')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">Monthly OT Summary</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('monthly-ot-request.monthly-ot-summary-download',['date'=>isset($date)?$date:$today_date,'employee'=>isset($employee)?$employee:'all','type'=>isset($type)?$type:1])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>Mon ~ Sat</th>
                      <th>P/Holiday</th>
                      <th>Sunday (<8:00 & >18:00)</th>
                      <th>Sunday (8:00 ~ 18:00)</th>
                      <th>OT Rate ($)</th>
                      <th>Mon ~ Sat Amt ($)</th>
                      <th>P/Holiday Amt ($)</th>
                      <th>Sunday (<8:00 & >18:00) Amt ($)</th>
                      <th>Sunday (8:00 ~ 18:00) Amt ($)</th>
                      <th>Total Overtime Allowances</th>
                      <th>Taxi Charge ($)</th>
                      <th>All Total Allowances ($)</th>
                      <th>Exchange Rate (1USD=MMK)</th>
                      <th>Transfer to MMK A/C (MMK)</th>
                      <th></th>
                      <th></th>
                      <th>Remark</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = 1;$net_total = 0;$overtime_net_total = 0;$total_taxi=0;$mmk_net_total=0; @endphp
                    @foreach($otsummaries as $user=>$otsummary)
                      <tr>
                      	<td>{{$i}}</td>
                      	<td><a href="{{route('monthly-ot-request.monthly-ot-detail',['id'=>$user,'date'=>$request_date,'type'=>$otsummary['emp_type']])}}">{{$otsummary['name']}}</a></td>
                      	
                      	@php
                          $ot_payment = $otsummary['ot_payment'];
                          //monday to sat
                      		$monday_hour = isset($otsummary['monday_hour'])?$otsummary['monday_hour']:0;
                          $monday_minute = isset($otsummary['monday_minute'])?$otsummary['monday_minute']:0;

                          $monday_hour += floor($monday_minute / 60);
                          $monday_minute = ($monday_minute -   floor($monday_minute / 60) * 60);
                          $monday_time = str_pad($monday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($monday_minute, 2, '0', STR_PAD_LEFT);
                          $monday_payment = getOTAmount($monday_time,$ot_payment);

                          //public holiday
                      		$public_hour = isset($otsummary['public_hour'])?$otsummary['public_hour']:0;
                          $public_minute = isset($otsummary['public_minute'])?$otsummary['public_minute']:0;

                          $public_hour += floor($public_minute / 60);
                          $public_minute = ($public_minute -   floor($public_minute / 60) * 60);
                          $public_time = str_pad($public_hour, 2, '0', STR_PAD_LEFT).":".str_pad($public_minute, 2, '0', STR_PAD_LEFT);
                          $public_payment = getOTAmount($public_time,$ot_payment);

                          //sunday less
                          $sunday_less_hour = isset($otsummary['sunday_less_hour'])?$otsummary['sunday_less_hour']:0;
                          $sunday_less_minute = isset($otsummary['sunday_less_minute'])?$otsummary['sunday_less_minute']:0;

                          $sunday_less_hour += floor($sunday_less_minute / 60);
                          $sunday_less_minute = ($sunday_less_minute -   floor($sunday_less_minute / 60) * 60);
                          $sunday_less_time = str_pad($sunday_less_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_less_minute, 2, '0', STR_PAD_LEFT);
                          $sunday_less_payment = getOTAmount($sunday_less_time,$ot_payment);

                          //sunday between
                          $sunday_between_hour = isset($otsummary['sunday_between_hour'])?$otsummary['sunday_between_hour']:0;
                          $sunday_between_minute = isset($otsummary['sunday_between_minute'])?$otsummary['sunday_between_minute']:0;
                          $sunday_between_hour += floor($sunday_between_minute / 60);
                          $sunday_between_minute = ($sunday_between_minute -   floor($sunday_between_minute / 60) * 60);
                          $sunday_between_time = str_pad($sunday_between_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_between_minute, 2, '0', STR_PAD_LEFT);
                          $sunday_between_payment = getOTAmount($sunday_between_time,$ot_payment);

                          //total overtime allowance
                          $overtime_total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment;
                          $overtime_net_total += round_up_nodecimal($overtime_total);

                      		//all overtime allowance
                      		$total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment + $otsummary['taxi_charge'];
                      		$net_total += round_up_nodecimal($total);
                      	@endphp
                        <td>{{$monday_time}}</td>
                        <td>{{$public_time}}</td>
                        <td>{{$sunday_less_time}}</td>
                        <td>{{$sunday_between_time}}</td>
                        <td class="text-right">{{$otsummary['ot_payment']}}</td>
                      	<td class="text-right">{{siteformat_number($monday_payment)?siteformat_number($monday_payment):$monday_payment}}</td>
                      	<td class="text-right">{{siteformat_number($public_payment)?siteformat_number($public_payment):$public_payment}}</td>
                        <td class="text-right">{{siteformat_number($sunday_less_payment)?siteformat_number($sunday_less_payment):$sunday_less_payment}}</td>
                        <td class="text-right">{{siteformat_number($sunday_between_payment)?siteformat_number($sunday_between_payment):$sunday_between_payment}}</td>
                        <td class="text-right">{{number_format(round_up_nodecimal($overtime_total))}}</td>
                      	<td class="text-right">{{$otsummary['taxi_charge']}}.00</td>
                      	<td class="text-right">{{number_format(round_up_nodecimal($total))}}</td>
                        @php
                          $exchange_rate = getOTExchangeRate($user,$date);
                          $mmk_amount = round_up_nodecimal($total) * $exchange_rate;

                          $total_taxi += $otsummary['taxi_charge'];
                          $mmk_net_total += $mmk_amount;
                        @endphp
                      	<td class="text-right">{{$exchange_rate?siteformat_number($exchange_rate):''}}</td>
                        <td colspan="3" class="text-right">{{$mmk_amount?number_format($mmk_amount):''}}</td>
                      	<td>{{$otsummary['remark']}}</td>
                      	<td>
                         <a href="{{route('monthly-ot-request.monthly-ot-detail',['id'=>$user,'date'=>$request_date,'type'=>$otsummary['emp_type']])}}">
                          <i class="fas fa-eye text-primary"></i>
                        </a> 
                        </td>
                      </tr>
                      
                      

                      @php $i += 1; @endphp
                    @endforeach
                    <tr>
                      <td></td>
                      <td colspan="10" class="text-bold text-right">Total</td>
                      <td class="text-bold text-right">{{number_format($overtime_net_total)}}</td>
                      <td class="text-right text-bold">{{siteformat_number($total_taxi)}}</td>
                      <td class="text-bold text-right">{{number_format($net_total)}}</td>
                      
                      <td class="text-right text-bold" colspan="4">{{number_format($mmk_net_total)}}</td>
                      <td colspan="2" class="text-bold"></td>
                    </tr>
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
      $('#datetimepicker').datetimepicker({
          format: 'MM/YYYY'
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