@extends('layouts.master')
@section('title','Pay List (NS) Internal')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Salary Management</li>
              <li class="breadcrumb-item active"><a href="{{url('salary-management/payslip-list/ns-internal')}}">Pay List (NS) Internal</a></li>
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
                <form action="{{url('salary-management/payslip-list/ns-internal')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('m/Y');
                    
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                    
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
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="YYYY" value="{{isset($from_date)?$from_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <input type="text" name="to_date" id="to_date" required placeholder="YYYY" value="{{isset($to_date)?$to_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('salary-management/payslip-list/ns-internal')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">Pay List (NS) Internal</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    
                    <a href="{{route('salary.ns-payslip-download',['from_date'=>isset($from_date)?$from_date:$today_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all'])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Staff's Name</th>
                      <th>Salary<br>(USD)</th>
                      <th>Bonus<br>(USD)</th>
                      <th>W/O paid Leave<br>(USD)</th>
                      <th>KBZ A/C Opening<br>(USD)</th>
                      <th>Income Tax<br>(USD)</th>
                      <th>SSC<br>(USD)</th>
                      <th>Overtime<br>(USD)</th>
                      <th>Other<br>(USD)</th>
                      <th>(in USD)</th>
                      <th>(in USD) not include others expense</th>
                      <th>Salary to MMK A/C (@  --)</th>
                      <th>Salary to MMK A/C (@  --)</th>
                      <th>Other in MMK</th>
                      <th>Total Transfer to MMK A/C</th>
                      <th>Total Transfer to MMK Cash</th>
                      <th>Total Transfer to USD A/C</th>
                      <th>Total Transfer to USD Cash</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $salary = 0;$bonus = 0;$leave = 0;$kbz_opening = 0;$income_tax = 0;$ssc = 0;$overtime = 0;$other = 0;$total_usd = 0;$total_income_usd = 0;$first_salary_to_mmk = 0;$second_salary_to_mmk = 0;$other_in_mmk = 0;$transfer_to_mmk_acc = 0;$transfer_to_mmk_cash = 0;$transfer_to_usd_acc = 0;$transfer_to_usd_cash = 0; @endphp
                    @foreach($paylists as $key=>$value)
                      <tr>
                      	<td>{{$key+1}}</td>
                        @php
                          $emp_name = getUserFieldWithId($value->user_id,"employee_name");
                        @endphp
                      	<td>{{$emp_name?$emp_name:getUserFieldWithId($value->user_id,"name")}}</td>
                        
                        <td class="text-right">{{$value->salary_usd}}</td>
                        <td class="text-right">{{$value->bonus_usd}}</td>
                        <td class="text-right">{{$value->leave_usd}}</td>
                        <td class="text-right">{{$value->kbz_opening_usd}}</td>
                        <td class="text-right">{{$value->estimated_tax_usd}}</td>
                        <td class="text-right">{{$value->ssc_usd}}</td>
                        <td class="text-right">{{$value->total_ot_payment_usd}}</td>
                        @php
                        	$other_usd = $value->usd_allowance_usd - $value->usd_deduction_usd;

                        	$in_usd = $value->salary_usd + $value->bonus_usd - $value->leave_usd - $value->kbz_opening_usd - $value->estimated_tax_usd - $value->ssc_usd + $value->total_ot_payment_usd + $other_usd;

                          	$income_usd = $value->salary_usd + $value->bonus_usd - $value->kbz_opening_usd - $value->estimated_tax_usd - $value->ssc_usd + $value->total_ot_payment_usd;

                          	if($income_usd>400){
                          		$first_mmk = 400 * 2000;
                          		$second_mmk = ($income_usd - 400) * 1850;
                          	}
                          	else{
                          		$first_mmk = $in_usd * 2000;
                          		$second_mmk = 0;
                          	}
                        @endphp
                        <td class="text-right">{{$other_usd}}</td>
                        <td class="text-right">{{$in_usd}}</td>
                        <td class="text-right">{{$income_usd}}</td>
                        <td class="text-right">{{$first_mmk}}</td>
                        <td class="text-right">{{$second_mmk}}</td>
                        @php
                        	$other_mmk = $value->mmk_allowance_mmk - $value->mmk_deduction_mmk;
                        @endphp
                        <td class="text-right">{{$other_mmk}}</td>
                        <td class="text-right">{{$value->transfer_mmk_acc}}</td>
                        <td class="text-right">{{$value->transfer_mmk_cash}}</td>
                        <td class="text-right">{{$value->transfer_usd_acc}}</td>
                        <td class="text-right">{{$value->transfer_usd_cash}}</td>

                        @php

                          	$salary += $value->salary_usd;
                          	$bonus += $value->bonus_usd;
                          	$leave += $value->leave_usd;
                          	$kbz_opening += $value->kbz_opening_usd;
                          	$income_tax += $value->estimated_tax_usd;
                          	$ssc += $value->ssc_usd;
                          	$overtime += $value->total_ot_payment_usd;
                          	$other += $other_usd;
                          	$total_usd += $in_usd;
                          	$total_income_usd += $in_usd;
                          	$first_salary_to_mmk += $first_mmk;
                          	$second_salary_to_mmk += $second_mmk;
                          	$other_in_mmk += $other_mmk;
                          	$transfer_to_mmk_acc += $value->transfer_mmk_acc;
                          	$transfer_to_mmk_cash += $value->transfer_mmk_cash;
                          	$transfer_to_usd_acc += $value->transfer_usd_acc;
                          	$transfer_to_usd_cash += $value->transfer_usd_cash;

                        @endphp
                        
                      	
                      </tr>

                      
                    @endforeach
                    <tr>
                      <th colspan="2">Total</th>
                      <th class="text-right">{{siteformat_number($salary)}}</th>
                      <th class="text-right">{{$bonus}}</th>
                      <th class="text-right">{{$leave}}</th>
                      <th class="text-right">{{siteformat_number($kbz_opening)}}</th>
                      <th class="text-right">{{$income_tax}}</th>
                      <th class="text-right">{{$ssc}}</th>
                      <th class="text-right">{{$overtime}}</th>
                      <th class="text-right">{{$other}}</th>
                      <th class="text-right">{{$total_usd}}</th>
                      <th class="text-right">{{$total_income_usd}}</th>
                      <th class="text-right">{{$first_salary_to_mmk}}</th>
                      <th class="text-right">{{$second_salary_to_mmk}}</th>
                      <th class="text-right">{{$other_in_mmk}}</th>
                      <th class="text-right">{{$transfer_to_mmk_acc}}</th>
                      <th class="text-right">{{$transfer_to_mmk_cash}}</th>
                      <th class="text-right">{{$transfer_to_usd_acc}}</th>
                      <th class="text-right">{{$transfer_to_usd_cash}}</th>
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