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
                        $april_total = array();
                        $may_total = array();
                        $june_total = array();
                        $july_total = array();
                        $auguest_total = array();
                        $september_total = array();
                        $october_total = array();
                        $november_total = array();
                        $december_total = array();
                        $january_total = array();
                        $february_total = array();
                        $march_total = array();
                        $all_total = array(); 
                        $total = array();
                        @endphp
                        @foreach($otsummary as $key=>$value)
                        <tr>
                          <td class="text-right">{{$i}}</td>
                          <td>{{getUserFieldWithId($value->user_id,'employee_name')}}</td>
                          <td>{{getDepartmentField(getUserFieldWithId($value->user_id,'department_id'),'name')}}</td>
                          @php
                            $april = getMonthlyOTHour($value->user_id,$value->year."-04",true);
                            $april_total[] = $april;

                            $may = getMonthlyOTHour($value->user_id,$value->year."-05",true);
                            $may_total[] = $may;

                            $june = getMonthlyOTHour($value->user_id,$value->year."-06",true);
                            $june_total[] = $june;

                            $july = getMonthlyOTHour($value->user_id,$value->year."-07",true);
                            $july_total[] = $july;

                            $august = getMonthlyOTHour($value->user_id,$value->year."-08",true);
                            $august_total[] = $august;

                            $september = getMonthlyOTHour($value->user_id,$value->year."-09",true);
                            $september_total[] = $september;

                            $october = getMonthlyOTHour($value->user_id,$value->year."-10",true);
                            $october_total[] = $october;

                            $november = getMonthlyOTHour($value->user_id,$value->year."-11",true);
                            $november_total[] = $november;

                            $december = getMonthlyOTHour($value->user_id,$value->year."-12",true);
                            $december_total[] = $december;

                            $january = getMonthlyOTHour($value->user_id,($value->year+1)."-01",true);
                            $january_total[] = $january;

                            $february = getMonthlyOTHour($value->user_id,($value->year+1)."-02",true);
                            $february_total[] = $february;

                            $march = getMonthlyOTHour($value->user_id,($value->year+1)."-03",true);
                            $march_total[] = $march;
                          @endphp
                          <td>{{$april!="00:00"?$april:''}}</td>
                          
                          <td>{{$may!="00:00"?$may:''}}</td>
                          
                          <td>{{$june!="00:00"?$june:''}}</td>
                          
                          <td>{{$july!="00:00"?$july:''}}</td>
                          
                          <td>{{$august!="00:00"?$august:''}}</td>
                          
                          <td>{{$september!="00:00"?$september:''}}</td>
                          
                          <td>{{$october!="00:00"?$october:''}}</td>
                          
                          <td>{{$november!="00:00"?$november:''}}</td>
                          
                          <td>{{$december!="00:00"?$december:''}}</td>
                          
                          <td>{{$january!="00:00"?$january:''}}</td>
                          
                          <td>{{$february!="00:00"?$february:''}}</td>
                          
                          <td>{{$march!="00:00"?$march:''}}</td>
                          @php 
                          $total = sumTime([$april, $may , $june , $july , $august , $september, $october , $november , $december , $january , $february , $march]);

                          $all_total[] = $total;
                          @endphp
                          <td>{{$total!="00:00"?$total:''}}</td>
                          
                        </tr>
                        @php $i += 1; @endphp
                        @endforeach
                        @php
                          $total_april = sumTime($april_total);
                          $total_may = sumTime($may_total);
                          $total_june = sumTime($june_total);
                          $total_july = sumTime($july_total);
                          $total_august = sumTime($august_total);
                          $total_september = sumTime($september_total);
                          $total_october = sumTime($october_total);
                          $total_november = sumTime($november_total);
                          $total_december = sumTime($december_total);
                          $total_january = sumTime($january_total);
                          $total_february = sumTime($february_total);
                          $total_march = sumTime($march_total);

                          $main_total = sumTime($all_total);

                          
                        @endphp
                        <tr class="text-bold">
                          <td colspan="3" class="text-right">Total</td>
                          <td>{{$total_april!="00:00"?$total_april:''}}</td>
                          <td>{{$total_may!="00:00"?$total_may:''}}</td>
                          <td>{{$total_june!="00:00"?$total_june:''}}</td>
                          <td>{{$total_july!="00:00"?$total_july:''}}</td>
                          <td>{{$total_august!="00:00"?$total_august:''}}</td>
                          <td>{{$total_september!="00:00"?$total_september:''}}</td>
                          <td>{{$total_october!="00:00"?$total_october:''}}</td>
                          <td>{{$total_november!="00:00"?$total_november:''}}</td>
                          <td>{{$total_december!="00:00"?$total_december:''}}</td>
                          <td>{{$total_january!="00:00"?$total_january:''}}</td>
                          <td>{{$total_february!="00:00"?$total_february:''}}</td>
                          <td>{{$total_march!="00:00"?$total_march:''}}</td>
                          <td>{{$main_total!="00:00"?$main_total:''}}</td>
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