@extends('layouts.master')
@section('title','Annual OT Summary')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">OT Management</li>
              <li class="breadcrumb-item active"><a href="{{url('ot-management/annual-ot-summary')}}">Annual OT Summary</a></li>
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
                <form action="{{url('ot-management/annual-ot-summary')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('Y');
                    $month = \Carbon\Carbon::now()->format('F');
                    $month = strtolower($month);

                    if($month=='january' || $month=="february" ||$month=="march")
                      $today_date = $today_date-1;
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
                        <label>From Year</label>
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
                        <label>To Year</label>
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
                          <a href="{{url('ot-management/annual-ot-summary')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">Annual OT Summary</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('monthly-ot-request.annual-ot-summary-download',['from_date'=>isset($from_date)?$from_date:$today_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all'])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table" id="attendance_record">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>Department</th>
                      <th>Apr</th>
                      <th>May</th>
                      <th>Jun</th>
                      <th>Jul</th>
                      <th>Aug</th>
                      <th>Sep</th>
                      <th>Oct</th>
                      <th>Nov</th>
                      <th>Dec</th>
                      <th>Jan</th>
                      <th>Feb</th>
                      <th>Mar</th>
                      <th>
                        Total
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    @foreach($otsummaries as $branch=>$otsummary)
                      @if(count($otsummary))
                        <tr style="background-color: #999;color: white;">
                          <th>{{getBranchField($branch,'name')}}</th>
                          <th colspan="15"></th>
                        </tr>
                        @php $i = 1;
                        $april_total = 0;
                        $may_total = 0;
                        $june_total = 0;
                        $july_total = 0;
                        $auguest_total = 0;
                        $september_total = 0;
                        $october_total = 0;
                        $november_total = 0;
                        $december_total = 0;
                        $january_total = 0;
                        $february_total = 0;
                        $march_total = 0;
                        $all_total = 0; 
                        $total = 0;
                        @endphp
                        @foreach($otsummary as $key=>$value)
                        <tr>
                          <td class="text-right">{{$i}}</td>
                          <td>{{getUserFieldWithId($value->user_id,'employee_name')}}</td>
                          <td>{{getDepartmentField($value->user_id,'name')}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/04/".$value->year,true);
                            $april = round_up($value->april * $otpayment,2) + getTaxiCharge($value->user_id,"01/04/".($value->year));
                            
                          @endphp
                          <td>{{$april}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/05/".$value->year,true);
                            $may = round_up($value->may * $otpayment,2) + getTaxiCharge($value->user_id,"01/05/".($value->year));
                         
                          @endphp
                          <td>{{$may}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/06/".$value->year,true);
                            $june = round_up($value->june * $otpayment,2) + getTaxiCharge($value->user_id,"01/06/".($value->year));
                            
                          @endphp
                          <td>{{$june}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/07/".$value->year,true);
                            $july = round_up($value->july * $otpayment,2) + getTaxiCharge($value->user_id,"01/07/".($value->year));
                            
                          @endphp
                          <td>{{$july}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/08/".$value->year,true);
                            $august = round_up($value->august * $otpayment,2) + getTaxiCharge($value->user_id,"01/08/".($value->year));
                           
                          @endphp
                          <td>{{$august}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/09/".$value->year,true);
                            $september = round_up($value->september * $otpayment,2) + getTaxiCharge($value->user_id,"01/09/".($value->year));
                            
                          @endphp
                          <td>{{$september}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/10/".$value->year,true);
                            $october = round_up($value->october * $otpayment,2) + getTaxiCharge($value->user_id,"01/10/".($value->year));
                            
                          @endphp
                          <td>{{$october}}</td>
                          @php
                           
                            $otpayment = getOTPayment($value->user_id,"01/11/".$value->year,true);
                            $november = round_up($value->november * $otpayment,2) + getTaxiCharge($value->user_id,"01/11/".($value->year));
                            
                          @endphp
                          <td>{{$november}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/12/".$value->year,true);
                            $december = round_up($value->december * $otpayment,2) + getTaxiCharge($value->user_id,"01/12/".($value->year));
                            
                          @endphp
                          <td>{{$december}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/01/".$value->year,true);
                            $january = round_up($value->january * $otpayment,2) + getTaxiCharge($value->user_id,"01/01/".($value->year+1));
                            
                          @endphp
                          <td>{{$january}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/02/".$value->year,true);
                            $february = round_up($value->february * $otpayment,2) + getTaxiCharge($value->user_id,"01/02/".($value->year+1));
                            
                          @endphp
                          <td>{{$february}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/03/".$value->year,true);
                            $march = round_up($value->march * $otpayment,2) + getTaxiCharge($value->user_id,"01/03/".($value->year+1));
                            
                          @endphp
                          <td>{{$march}}</td>
                          @php 
                          $total = $april + $may + $june + $july + $august + $september + $october + $november + $december + $january + $february + $march;

                          $april_total += $april;
                          $may_total += $may;
                          $june_total += $june;
                          $july_total += $july;
                          $auguest_total += $august;
                          $september_total += $september;
                          $october_total += $october;
                          $november_total += $november;
                          $december_total += $december;
                          $january_total += $january;
                          $february_total += $february;
                          $march_total += $march;
                          $all_total += $total;
                          @endphp
                          <td>{{$total}}</td>
                          
                        </tr>
                        @php $i += 1; @endphp
                        @endforeach
                        <tr class="text-bold">
                          <td colspan="3" class="text-right">Total</td>
                          <td>{{$april_total}}</td>
                          <td>{{$may_total}}</td>
                          <td>{{$june_total}}</td>
                          <td>{{$july_total}}</td>
                          <td>{{$auguest_total}}</td>
                          <td>{{$september_total}}</td>
                          <td>{{$october_total}}</td>
                          <td>{{$november_total}}</td>
                          <td>{{$december_total}}</td>
                          <td>{{$january_total}}</td>
                          <td>{{$february_total}}</td>
                          <td>{{$march_total}}</td>
                          <td>{{$all_total}}</td>
                        </tr>
                      @endif
                    @endforeach
                    
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
          format: 'YYYY'
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