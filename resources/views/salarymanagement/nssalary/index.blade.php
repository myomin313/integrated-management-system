@extends('layouts.master')
@section('title','NS Salary List')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Salary Management</li>
              <li class="breadcrumb-item active"><a href="{{url('attendance-management/change-request/list')}}">NS Salary List</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            
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
            
            @if(session('success_create'))
              <div class="col-md-12 p-0">
                <div class="alert alert-success alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
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
                <form action="{{url('salary-management/ns-salary/list')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('Y');
                    $month = \Carbon\Carbon::now()->format('F');
                    $month = strtolower($month);

                    if($month=='january' || $month=="february" || $month=="march")
                      $today_date = $today_date-1;

                    $year=app('request')->get('year');
                    $employee=app('request')->get('employee');

                    $year = isset($year)?$year:$today_date;
                          
                  @endphp
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Employee Name</label>
                        <select class="form-control select2bs4" name="employee" id="employee" style="width: 100%;">
                          <option selected="selected" value="all">- Employee Name -</option>
                          @foreach($employees as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$employee?'selected':''}}>{{$value->employee_name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Year</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="year" id="year" required placeholder="dd/mm/YYYY" value="{{isset($year)?$year:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <a href="{{url('salary-management/ns-salary/list')}}" class="btn btn-warning">Reset</a>
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
                  <div class="col-sm-8">
                    <h3 class="card-title">NS Salary List</h3>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Year</th>
                      <th>Employee Name</th>
                      <th>Position</th>
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
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    @foreach($nssalary as $key=>$value)                       
                      <tr id="row{{$key}}">
                        <td>{{$key+1}}</td>
                        <td>{{$year}}</td>
                        @if(Auth::user()->can('edit-ns-salary'))
                        <td id="user_name{{$key}}">
                          <a href="{{url('salary-management/ns-salary/edit',[$value->id,$year])}}">
                          {{$value->employee_name?$value->employee_name:$value->name}}</a>
                        </td>
                        @else
                        <td id="user_name{{$key}}">
                          
                          {{$value->employee_name?$value->employee_name:$value->name}}
                        </td>
                        @endcan
                        <td id="position{{$key}}">{{$value->position_id?getPositionName($value->position_id):$value->position}}</td>
                        <td id="april{{$key}}">{{getNsSalaryField($value->id,$year,'april')}}</td>
                        <td id="may{{$key}}">{{getNsSalaryField($value->id,$year,'may')}}</td>
                        <td id="june{{$key}}">{{getNsSalaryField($value->id,$year,'june')}}</td>
                        <td id="july{{$key}}">{{getNsSalaryField($value->id,$year,'july')}}</td>
                        <td id="august{{$key}}">{{getNsSalaryField($value->id,$year,'august')}}</td>
                        <td id="september{{$key}}">{{getNsSalaryField($value->id,$year,'september')}}</td>
                        <td id="october{{$key}}">{{getNsSalaryField($value->id,$year,'october')}}</td>
                        <td id="november{{$key}}">{{getNsSalaryField($value->id,$year,'november')}}</td>
                        <td id="december{{$key}}">{{getNsSalaryField($value->id,$year,'december')}}</td>
                        <td id="january{{$key}}">{{getNsSalaryField($value->id,$year,'january')}}</td>
                        <td id="february{{$key}}">{{getNsSalaryField($value->id,$year,'february')}}</td>
                        <td id="march{{$key}}">{{getNsSalaryField($value->id,$year,'march')}}</td>
                        <td>
                          @can('edit-ns-salary')
                          <a href="{{url('salary-management/ns-salary/edit',[$value->id,$year])}}">
                            <i class="fas fa-edit text-primary"></i>
                          </a>
                          @endcan
                        </td>
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
          format: 'DD/MM/YYYY'
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

      //Timepicker
      $('#timepicker').datetimepicker({
        format: 'LT'
      });
      $('#timepicker1').datetimepicker({
        format: 'LT'
      });
      
      var change_table = $('#change_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": false,
          
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