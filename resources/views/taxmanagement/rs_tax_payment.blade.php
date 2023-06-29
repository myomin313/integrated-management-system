@extends('layouts.master')
@section('title','Actual Tax Payment (RS)')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/annual-tax/rs-tax-payment-report')}}">Actual Tax Payment (RS)</a></li>
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
            @if(session('message'))
              <div class="col-md-12 p-0">
                <div class="alert alert-success alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('message')}}</strong>
                </div>
              </div> 
            @endif
            <div class="card">
              <div class="card-header">
                <form action="{{url('tax-management/annual-tax/rs-tax-payment-report')}}" method="get">
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
                          <a href="{{url('tax-management/annual-tax/rs-tax-payment-report')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">Actual Tax Payment (RS)</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('tax.rs-tax-payment-report-download',['from_date'=>isset($from_date)?$from_date:$today_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all'])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
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
                      <th>Passport No.</th>
                      <th>GIR No.</th>
                      <th>Occupation</th>
                      <!-- <th>Salary<br>(USD)</th> -->
                      <th>Est .Income Tax<br>(USD)</th>
                      <th>Ex Rate</th>
                      <th>Income Tax Total (Kyats)</th>
                      <th>Remark</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = 1;$total_salary = 0;$total_tax_usd = 0;$total_tax_mmk = 0; @endphp
                    @foreach($income_taxes as $key=>$value)
                      <tr>
                      	<td>{{$i}}</td>
                      	<td><a href="{{url('tax-management/rs-income-tax/detail',[$value->user_id,$value->tax_period])}}">{{getUserFieldWithId($value->user_id,"employee_name")}}</a></td>
                      	
                        <td>{{getUserFieldWithId($value->user_id,"passport_number")}}</td>
                        <td></td>
                        <td>{{getPositionName(getUserFieldWithId($value->user_id,"position_id"))}}</td>
                       
                        <td class="text-right">{{siteformat_number($value->tax_amount_usd)}}</td>
                        <td class="text-right">{{siteformat_number($value->exchange_rate)}}</td>
                      	<td class="text-right">{{siteformat_number($value->tax_amount_mmk)}}</td>
                        <td>{{\Carbon\Carbon::parse($value->tax_period)->format("F, Y")}}<br>RS</td>
                      	@php
                          //$total_salary += $value->salary_usd;
                          $total_tax_usd += $value->tax_amount_usd;
                          $total_tax_mmk += $value->tax_amount_mmk;
                        @endphp
                      </tr>
                      
                      

                      @php $i += 1; @endphp
                    @endforeach
                    <tr>
                      <td colspan="5" class="text-bold text-right">Total</td>
                      <!-- <td class="text-bold">{{$total_salary}}</td> -->
                      <td class="text-bold text-right">{{siteformat_number($total_tax_usd)}}</td>
                      <td class="text-bold"></td>
                      <td class="text-bold text-right">{{siteformat_number($total_tax_mmk)}}</td>
                      <td></td>
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