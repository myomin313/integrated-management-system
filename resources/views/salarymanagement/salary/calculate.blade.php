@extends('layouts.master')
@section('title','Salary Calculation')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Salary Management</li>
              <li class="breadcrumb-item active"><a href="{{url('salary-management/calculate-salary')}}">Salary Calculation</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
          
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-8 m-auto">
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
            @if(session('success_update'))
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_update')}}</strong>
                </div>
              </div>
            @endif
            <div class="card" style="padding:20px;">
              <div class="card-header">
                <h3 class="card-title">Salary Calculation</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="{{route('salary.store')}}" method="post" id="create_user" class="prevent-multiple-submit">
                  @csrf
                  
                  
                  <div class="form-group ">
                      <label for="user_id">Employee Name <span class="required text-danger">*</span></label>
                    
                      <select class="form-control select2bs4" name="user_id" id="user_id" required style="width: 100%;">
                          <option value="" selected="selected">- Select -</option>
                          @foreach($employees as $key=>$value)
                            <option value="{{$value->id}}">{{$value->employee_name?$value->employee_name:$value->name}}</option>
                          @endforeach

                      </select>
                  </div>
                  <div class="form-group ">
                      <label for="year">Year <span class="required text-danger">*</span></label>
                      <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="year" id="year" required placeholder="YYYY" value="{{\Carbon\Carbon::now()->format('Y')}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group ">
                      <label for="month">Month <span class="required text-danger">*</span></label>
                      <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                          <input type="text" name="month" id="month" required placeholder="Month" value="{{\Carbon\Carbon::now()->format('F')}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-6">
                      <label for="leave_from_date">Leave From Date <span class="required text-danger">*</span></label>
                      <div class="input-group date" id="leave_from_date" data-target-input="nearest">
                          <input type="text" name="leave_from_date" id="leave_from_date" required value="{{\Carbon\Carbon::now()->subMonth()->format('25/m/Y')}}" class="form-control datetimepicker-input" data-target="#leave_from_date"/>
                          <div class="input-group-append" data-target="#leave_from_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="leave_to_date">Leave To Date <span class="required text-danger">*</span></label>
                      <div class="input-group date" id="leave_to_date" data-target-input="nearest">
                          <input type="text" name="leave_to_date" id="leave_to_date" required value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" class="form-control datetimepicker-input" data-target="#leave_to_date"/>
                          <div class="input-group-append" data-target="#leave_to_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-success" name="save_new">Calculate</button>
                      
                  </div>
                </form>
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
      
      	$('.select2bs4').select2({
        	theme: 'bootstrap4'
      	})

   
        $('#datetimepicker').datetimepicker({
            format: 'YYYY'
        });
        $('#datetimepicker1').datetimepicker({
            format: 'MMMM',
            viewMode: "months",
        });
        $('#leave_from_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#leave_to_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
            
    });

  </script>
@stop