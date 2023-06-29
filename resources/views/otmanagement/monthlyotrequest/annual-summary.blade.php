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
                    $display_type=app('request')->get('display_type');
                    if(!isset($display_type)){
                      $display_type = "amount";
                    }
                    
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
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Display Type</label>
                        <select class="form-control" name="display_type" id="display_type" style="width: 100%;">
                          
                          <option value="amount" {{$display_type=="amount"?'selected':''}}>Amount</option>    
                          <option value="hour" {{$display_type=="hour"?'selected':''}}>Hour</option>    
                             
                        </select>
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
                    <a href="{{route('monthly-ot-request.annual-ot-summary-download',['from_date'=>isset($from_date)?$from_date:$today_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all','display_type'=>isset($display_type)?$display_type:'amount'])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
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
                      <th>Apr ($)</th>
                      <th>May ($)</th>
                      <th>Jun ($)</th>
                      <th>Jul ($)</th>
                      <th>Aug ($)</th>
                      <th>Sep ($)</th>
                      <th>Oct ($)</th>
                      <th>Nov ($)</th>
                      <th>Dec ($)</th>
                      <th>Jan ($)</th>
                      <th>Feb ($)</th>
                      <th>Mar ($)</th>
                      <th>
                        Total ($)
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
                          <td>{{getDepartmentField(getUserFieldWithId($value->user_id,'department_id'),'name')}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/04/".$value->year,true);

                            $hour_min = explode(":",convertTime($value->april));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $april = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$april}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/05/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->may));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $may = round_up_nodecimal(getOTAmount($time,$otpayment));
                         
                          @endphp
                          <td class="text-right">{{$may}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/06/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->june));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $june = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$june}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/07/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->july));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $july = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$july}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/08/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->august));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $august = round_up_nodecimal(getOTAmount($time,$otpayment));
                           
                          @endphp
                          <td class="text-right">{{$august}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/09/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->september));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $september = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$september}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/10/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->october));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $october = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$october}}</td>
                          @php
                           
                            $otpayment = getOTPayment($value->user_id,"01/11/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->november));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $november = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$november}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/12/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->december));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $december = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$december}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/01/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->january));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $january = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$january}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/02/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->february));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $february = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$february}}</td>
                          @php
                            
                            $otpayment = getOTPayment($value->user_id,"01/03/".$value->year,true);
                            $hour_min = explode(":",convertTime($value->march));
                            $hour = $hour_min[0];
                            $minute = $hour_min[1];

                            $hour += floor($minute / 60);
                            $minute = ($minute -   floor($minute / 60) * 60);
                            $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                            $march = round_up_nodecimal(getOTAmount($time,$otpayment));
                            
                          @endphp
                          <td class="text-right">{{$march}}</td>
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
                          <td class="text-right">{{number_format($total)}}</td>
                          
                        </tr>
                        @php $i += 1; @endphp
                        @endforeach
                        <tr class="text-bold">
                          <td colspan="3" class="text-right">Total</td>
                          <td class="text-right">${{number_format($april_total)}}</td>
                          <td class="text-right">${{number_format($may_total)}}</td>
                          <td class="text-right">${{number_format($june_total)}}</td>
                          <td class="text-right">${{number_format($july_total)}}</td>
                          <td class="text-right">${{number_format($auguest_total)}}</td>
                          <td class="text-right">${{number_format($september_total)}}</td>
                          <td class="text-right">${{number_format($october_total)}}</td>
                          <td class="text-right">${{number_format($november_total)}}</td>
                          <td class="text-right">${{number_format($december_total)}}</td>
                          <td class="text-right">${{number_format($january_total)}}</td>
                          <td class="text-right">${{number_format($february_total)}}</td>
                          <td class="text-right">${{number_format($march_total)}}</td>
                          <td class="text-right">${{number_format($all_total)}}</td>
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