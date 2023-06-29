@extends('layouts.master')
@section('title','Attendance Detail')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Attendance Management</li>
              <li class="breadcrumb-item"><a href="{{url('attendance-management/raw-attendance/list')}}">Raw Attendance</a></li>
              <li class="breadcrumb-item active"><a href="{{url('attendance-management/raw-attendance/detail')}}">Attendance Detail</a></li>
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
            @if(count($errors)>0)
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>Whoops!</strong> There were some problems with your input.<br><br>
                  <ul>
                    @foreach($errors->all() as $error)
                      <li>{{$error}}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            @endif
            @if(session('success_update'))
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_update')}}</strong>
                </div>
              </div>
            @endif       
            @if(session('success_delete'))
              <div class="col-md-12 p-0">
                <div class="alert alert-danger alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_delete')}}</strong>
                </div>
              </div>
            @endif
            <div class="card">
              <div class="card-header">
                <form action="{{url('attendance-management/raw-attendance/detail')}}" method="get">
                  @php
                    $start_date=\Carbon\Carbon::now()->format('1/m/Y');
                    $today_date=\Carbon\Carbon::now()->format('d/m/Y');
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                    $branch=app('request')->get('branch');
                    $staff_type = app('request')->get('staff_type')
                          
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
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Branch</label>
                        <select class="form-control select2bs4" name="branch" id="branch" style="width: 100%;">
                          <option selected="selected" value="all">- All -</option>
                          @foreach($branches as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$branch?'selected':''}}>{{$value->name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="dd/mm/YYYY" value="{{isset($from_date)?$from_date:$start_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <input type="text" name="to_date" id="to_date" required placeholder="dd/mm/YYYY" value="{{isset($to_date)?$to_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Staff Type</label>
                        <select class="form-control" name="staff_type" id="staff_type" style="width: 100%;">
                          <option value="all">-All-</option>
                          <option value="normal_staff" {{$staff_type=="normal_staff"?'selected':''}}>Normal Staff</option>    
                          <option value="receptionist" {{$staff_type=="receptionist"?'selected':''}}>Receptionist</option>    
                             
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('attendance-management/raw-attendance/detail')}}" class="btn btn-warning">Reset</a>
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
            <form action="{{url('attendance-management/raw-attendance/update-detail')}}" method="post" class="prevent-multiple-submit">
              @csrf
            <div class="card attendance">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h3 class="card-title">Daily Attandance Detail</h3>
                  </div>
                  <div class="col-sm-6 text-right">

                    @can("print-attendance-detail-list")
                    <a href="{{route('attendance.print-detail',['from_date'=>isset($from_date)?$from_date:$start_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all','branch'=>isset($branch)?$branch:'all','staff_type'=>isset($staff_type)?$staff_type:'all'])}}" class="btn btn-success breadcrumb-btn" target="_blank">
                    <i class="fas fa-print"></i> Print</a>
                    @endcan
                    @can('export-attendance-detail-list')
                    <a class="btn btn-primary breadcrumb-btn" href="{{route('attendance.detail-download',['from_date'=>isset($from_date)?$from_date:$start_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all','branch'=>isset($branch)?$branch:'all','staff_type'=>isset($staff_type)?$staff_type:'all'])}}">
                    <i class="fas fa-download"></i> Export</a>
                    @endcan
                    @can('update-attendance-detail-list')
                    <button type="submit" class="btn btn-warning breadcrumb-btn" name="update"> Update</button>
                    @endcan
                    @can('update-ot-request')
                    <button type="submit" class="btn btn-info breadcrumb-btn" name="ot"> Update OT</button>
                    @endcan
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0 table-scroll" id="table-scroll">
                <table class="table main-table" id="main-table">
                  <thead>
                    <tr>
                      <th class="col">
                        
                        {{-- <div class="form-check">
                          <input type="checkbox" class="form-check-input showLocation" style="width:17px;height:17px;"><label class="form-check-label" for="checkboxA" style="font-size:11px;">Show Location</label>
                        </div> --}}
                        Date
                      </th>
                      <th class="col">Device</th>
                      <th class="col">Type</th>
                      <th class="col">Arrival Time</th>
                      <th class="col">Leave Time</th>
                      <th class="col">Working Hour</th>
                      <th class="col">Reason of Correction</th>
                      <th class="col"></th>
                      <th class="col">OT Request</th>
                      <th class="col">Apply</th>
                      <th class="col">Approval</th>
                      <th class="col">Normal OT Hr</th>
                      <th class="col">Sat OT Hr</th>
                      <th class="col">Sunday OT Hr</th>
                      <th class="col">P/Holiday OT Hr</th>
                      <th class="col">OT Apply</th>
                      <th class="col">OT Approval</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    @include("attendancemanagement.rawattendance.detail_table")
                  </tbody>
                </table>

              </div>
              
              <!-- /.card-body -->

            </div>
            <!-- /.card -->
            </form>

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
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker1').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker2').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker3').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $(document).on('focus', '.start_time',function(){               
            $(this).datepicker({ format: 'hh:mm', autoclose: true });
      });
      $('.select2').select2();
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $('.showLocation').click(function(){
          if(this.checked){
              $('.location').show();
              
          }
          else{
              $('.location').hide();
          }

      });
      //Timepicker
      var att_count = $('#count').val();
      if(Number(att_count)>0){
        for(var i=0;i<att_count;i++){
          $('#timepicker'+i).datetimepicker({
            format: 'HH:mm'
          });

          $('#end_timepicker'+i).datetimepicker({
            format: 'HH:mm'
          });

          $('#correctstart_timepicker'+i).datetimepicker({
            format: 'HH:mm'
          });

          $('#correctend_timepicker'+i).datetimepicker({
            format: 'HH:mm'
          });
        }
      }

      
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
    function selectCheckbox($i){
      
      $("#checkbox"+$i).attr('checked',true);
      $(".card .card-header").css({'z-index':0});
      $(".bootstrap-datetimepicker-widget").css({'z-index':100000});
    }

    function selectCheckboxTime($i){
      console.log("i = " + $i);
      $("#checkbox"+$i).attr('checked',true);
      $("#timepicker"+$i).css({'z-index':100000});
      $(".bootstrap-datetimepicker-widget").css({'z-index':100000});
    }

  </script>
@stop