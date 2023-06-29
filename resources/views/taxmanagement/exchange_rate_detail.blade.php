@extends('layouts.master')
@section('title','Exchange Rate Detail')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/exchange-rate-detail')}}">Exchange Rate</a></li>
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
                <form action="{{url('tax-management/exchange-rate-detail')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('Y-m');
                    
                    $from_date=app('request')->get('from_date');
                    
                  @endphp
                  <div class="row">
                  	
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Choose Month</label>
                        <div class="input-group" data-target-input="nearest">
                          <input type="month" name="from_date" id="from_date" required placeholder="YYYY" value="{{isset($from_date)?$from_date:$today_date}}" class="form-control"/>
                          
                        </div>
                      </div>
                    </div>
                    

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('tax-management/exchange-rate-detail')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <a href="{{route('tax.exchange-rate-detail-download',['from_date'=>isset($from_date)?$from_date:$today_date])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr class="bg-success">
                      <th colspan="5" class="text-center text-white">Exchange Rate - USD ({{$from_date?\Carbon\Carbon::parse($from_date)->format("F' Y"):\Carbon\Carbon::parse($today_date)->format("F' Y")}})</th>
                      <th></th>
                      <th colspan="5" class="text-center text-white">Exchange Rate - YEN ({{$from_date?\Carbon\Carbon::parse($from_date)->format("F' Y"):\Carbon\Carbon::parse($today_date)->format("F' Y")}})</th>
                    </tr>
                    <tr class="bg-success">
                    	<th class="text-center text-white">Date</th>
                      <th class="text-center text-white">Day</th>
                    	<th class="text-center text-white">Currency</th>
                    	<th class="text-center text-white">Value</th>
                    	<th class="text-center text-white">Reference Rate</th>
                    	<th class="text-center text-white"></th>
                    	<th class="text-center text-white">Date</th>
                      <th class="text-center text-white">Day</th>
                    	<th class="text-center text-white">Currency</th>
                    	<th class="text-center text-white">Value</th>
                    	<th class="text-center text-white">Reference Rate</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $no_of_day = 0;
                      $total_exchange_usd = 0;
                      $total_exchange_yen = 0;
                    @endphp
                  	@foreach($exchange_rates as $key=>$value)
                  		<tr>
                  			
	                    	<th class="text-center">{{\Carbon\Carbon::parse($value->date)->format("d-M-y")}}</th>
                        <th class="text-center">{{getDateName($value->date)}}</th>
                        @php
                        $ex_date = \Carbon\Carbon::parse($value->date)->format("d-m");
                        @endphp

	                    	@if(isHoliday($value->date))
	                    	<th class="text-center" colspan="2"></th>

	                    	<th class="text-center"></th>
                        
	                    	@elseif($ex_date=="01-04" or $ex_date=="01-08")
                        <th class="text-center" colspan="2">Bank Holiday</th>

                        <th class="text-center"></th>
	                    	@else
	                    	<th class="text-center">Dollar USD</th>

	                    	<th class="text-center">1/- = K</th>
	                    	<th class="text-right">{{siteformat_number($value->dollar)}}</th>
                        @php
                          $no_of_day += 1;
                          $total_exchange_usd += $value->dollar;
                        @endphp

	                    	@endif


	                    	<th class="text-center"></th>
	                    	<th class="text-center">{{\Carbon\Carbon::parse($value->date)->format("d-M-y")}}</th>
                        <th class="text-center">{{getDateName($value->date)}}</th>
	                    	@if(isHoliday($value->date))
                        <th class="text-center" colspan="2"></th>

                        <th class="text-center"></th>
                        
                        @elseif($ex_date=="01-04" or $ex_date=="01-08")
                        <th class="text-center" colspan="2">Bank Holiday</th>

                        <th class="text-center"></th>
                        @else
                        <th class="text-center">JPN YEN</th>

                        <th class="text-center">1Y = 100K</th>
                        <th class="text-right">{{siteformat_number($value->yen)}}</th>
                        @php
                          $total_exchange_yen += $value->yen;
                        @endphp

                        @endif
	                    </tr>
                  	@endforeach
	                    
                  </tbody>
                  <tfoot>
                    <tr class="bg-success text-white">
                      <th colspan="4">Average</th>
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
                      <th colspan="4">Average</th>
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
          format: 'MM, YYYY'
          startView: "months", 
    	  minViewMode: "months"
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