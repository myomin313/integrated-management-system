@extends('layouts.master')
@section('title','Exchange Rate Summary')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/exchange-rate-summary')}}">Exchange Rate</a></li>
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
                <form action="{{url('tax-management/exchange-rate-summary')}}" method="get">
                  
                  <div class="row">
                  	
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="YYYY" value="{{$from_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <a href="{{url('tax-management/exchange-rate-summary')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">Exchange Rate</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('tax.exchange-rate-summary-download',['from_date'=>$from_date])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr class="bg-success">
                      <th colspan="3" class="text-center text-white">Exchange Rate - USD (FY {{$first_year}} - {{$last_year}})</th>
                      <th></th>
                      <th colspan="3" class="text-center text-white">Exchange Rate - YEN (FY {{$first_year}} - {{$last_year}})</th>
                    </tr>
                    <tr class="bg-success">
                    	<th class="text-center text-white">No</th>
                    	<th class="text-center text-white">Month</th>
                    	<th class="text-center text-white">Average Exchange Rate (MMK per USD)</th>
                    	
                    	<th class="text-center text-white"></th>
                    	<th class="text-center text-white">No</th>
                    	<th class="text-center text-white">Month</th>
                    	<th class="text-center text-white">Average Exchange Rate (1JPY=100MMK)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $no_of_day = 0;
                      $total_exchange_usd = 0;
                      $total_exchange_yen = 0;
                      $i = 1;
                    @endphp
                  	@foreach($exchange_rates as $key=>$value)
                  		<tr>
                  			<td>{{$i}}</td>
                  			<td>{{$key}}</td>
                  			<td class="text-right">{{siteformat_number($value['usd'])}}</td>
                  			<td></td>
                        	<td>{{$i}}</td>
                  			<td>{{$key}}</td>
                  			<td class="text-right">{{siteformat_number($value['yen'])}}</td>
	                    </tr>
	                    @php
	                    	$i += 1;
	                    	if($value['usd']>0)
	                    		$no_of_day += 1;
                      	$total_exchange_usd += $value['usd'];
                      	$total_exchange_yen += $value['yen'];
	                    @endphp
                  	@endforeach
	                    
                  </tbody>
                  <tfoot>
                    <tr class="bg-success text-white">
                      <th colspan="2">Average</th>
                      @php
                        if($no_of_day>0){
                          $average_usd = round($total_exchange_usd / $no_of_day);
                          $average_yen = round($total_exchange_yen / $no_of_day);
                        }
                        else{
                          $average_usd = 0;
                          $average_yen = 0;
                        }
                      @endphp
                      <th class="text-right">{{siteformat_number($average_usd)}}</th>
                      <th></th>
                      <th colspan="2">Average</th>
                      <th class="text-right">{{siteformat_number($average_yen)}}</th>
                        
                    </tr>
                  </tfoot>
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
          format: 'YYYY'
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