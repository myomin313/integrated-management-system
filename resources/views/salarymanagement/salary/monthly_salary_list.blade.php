@extends('layouts.master')
@section('title','Monthly Salary List')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Salary Management</li>
              <li class="breadcrumb-item active"><a href="{{url('salary-management/payslip-list/monthy-salary')}}">Monthly List</a></li>
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
                <form action="{{url('salary-management/payslip-list/monthy-salary')}}" method="get">
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
                          <a href="{{url('salary-management/payslip-list/monthy-salary')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">Monthly Salary List</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    
                    <a href="{{route('salary.monthly-salary-download',['from_date'=>isset($from_date)?$from_date:$today_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all'])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
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
                      <th>Occupation</th>
                      <th>Salary (USD)</th>
                      <th>KBZ Opening A/C (USD)</th>
                      <th>W/O Pay Leave (USD)</th>
                      <th>Est. Income Tax (USD)</th>
                      <th>SSC (USD)</th>
                      <th>Net Salary (USD)</th>
                      <th>Transfer To MMK A/C (MMK)</th>
                      <th>Remark</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $total_usd = 0; $total_mmk = 0;$salary_total = 0; $kbz_opening_total = 0;$leave_total = 0; $estimated_tax_total = 0;$ssc_total = 0;

                    @endphp
                    @foreach($paylists as $employee_type=>$salary)
                      <tr>
                        <th colspan="11">{{getEmployeeType($employee_type)}}</th>
                      </tr>
                      @php
                        $sub_total_usd = 0; $sub_total_mmk = 0;$sub_salary_total = 0; $sub_kbz_opening_total = 0;$sub_leave_total = 0; $sub_estimated_tax_total = 0;$sub_ssc_total = 0;
                        
                      @endphp
                      @foreach($salary as $key=>$value)
                      <tr>
                      	<td>{{$key+1}}</td>
                        @php $emp_name = getUserFieldWithId($value->user_id,"employee_name"); @endphp
                      	<td>{{$emp_name?$emp_name:getUserFieldWithId($value->user_id,"name")}}</td>
                        <td>{{getUserFieldWithId($value->user_id,"position_id")}}</td>
                        
                        <td class="text-right">{{siteformat_number($value->salary_usd)}}</td>
                        <td class="text-right">({{siteformat_number($value->kbz_opening_usd)}})</td>
                        <td class="text-right">({{siteformat_number($value->leave_amount_usd)}})</td>
                        <td class="text-right">({{siteformat_number($value->estimated_tax_usd)}})</td>
                        <td class="text-right">({{siteformat_number($value->ssc_usd)}})</td>
                        @php
                          $net_salary_usd = $value->salary_usd - $value->kbz_opening_usd - $value->leave_amount_usd - $value->estimated_tax_usd - $value->ssc_usd;
                          $net_salary_mmk = $value->salary_mmk - $value->kbz_opening_mmk - $value->leave_amount_mmk - $value->estimated_tax_mmk - $value->ssc_mmk;

                          $total_usd += $net_salary_usd;
                          $total_mmk += $value->transfer_mmk_acc;

                          $salary_total += $value->salary_usd;
                          $kbz_opening_total += $value->kbz_opening_usd;
                          $leave_total += $value->leave_usd;
                          $estimated_tax_total += $value->estimated_tax_usd;
                          $ssc_total += $value->ssc_usd;

                          $sub_total_usd += $net_salary_usd;
                          $sub_total_mmk += $value->transfer_mmk_acc;

                          $sub_salary_total += $value->salary_usd;
                          $sub_kbz_opening_total += $value->kbz_opening_usd;
                          $sub_leave_total += $value->leave_usd;
                          $sub_estimated_tax_total += $value->estimated_tax_usd;
                          $sub_ssc_total += $value->ssc_usd;
                        @endphp
                        <td class="text-right">{{siteformat_number($net_salary_usd)}}</td>
                        <td class="text-right">{{siteformat_number($value->transfer_mmk_acc)}}</td>
                        <td>{{\Carbon\Carbon::parse($value->pay_date)->format("F, Y")}}</td>
                        
                      	
                      </tr>
                      @endforeach
                      <tr>
                        <th colspan="3" class="text-right">Sub Total</th>
                        <th class="text-right">{{siteformat_number($sub_salary_total)}}</th>
                        <th class="text-right">({{siteformat_number($sub_kbz_opening_total)}})</th>
                        <th class="text-right">({{siteformat_number($sub_leave_total)}})</th>
                        <th class="text-right">({{siteformat_number($sub_estimated_tax_total)}})</th>
                        <th class="text-right">({{siteformat_number($sub_ssc_total)}})</th>
                        <th class="text-right">{{siteformat_number($sub_total_usd)}}</th>
                        <th class="text-right">{{siteformat_number($sub_total_mmk)}}</th>
                        <th></th>
                      </tr>
                      
                    @endforeach
                    <tr>
                      <th colspan="3">All Total</th>
                      <th class="text-right">{{siteformat_number($salary_total)}}</th>
                      <th class="text-right">({{siteformat_number($kbz_opening_total)}})</th>
                      <th class="text-right">({{siteformat_number($leave_total)}})</th>
                      <th class="text-right">({{siteformat_number($estimated_tax_total)}})</th>
                      <th class="text-right">({{siteformat_number($ssc_total)}})</th>
                      <th class="text-right">{{siteformat_number($total_usd)}}</th>
                      <th class="text-right">{{siteformat_number($total_mmk)}}</th>
                      <th></th>
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