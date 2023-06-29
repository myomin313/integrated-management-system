@extends('layouts.master')
@section('title','Edit RS Salary')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Salary Management</li>
              <li class="breadcrumb-item active"><a href="{{url('master-management/user/list')}}">Edit RS Salary</a></li>
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
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
                </div>
              </div>
            @endif
            <div class="card" style="padding:20px;width: 100%">
              <div class="card-header">
                <h3 class="card-title">Edit NS Employee Salary Info</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <form action="{{route('rs-salary.update',[$user->id,$year])}}" method="post" id="create_user" class="prevent-multiple-submit">
                  @csrf
                  <div class="row">
                          
                    <div class="col-md-4 col-sm-4">
                      <div class="form-group">
                        <label>Employee Name <span class="required text-danger">*</span></label>
                        <input type="text" name="employee_name" class="form-control" value="{{$user->employee_name?$user->employee_name:$user->name}}" readonly>
                      </div>
                    </div>
                    
                    <div class="col-md-4 col-sm-4">
                      <div class="form-group">
                        <label>Department <span class="required text-danger">*</span></label>
                        <!-- <input type="text" name="department" class="form-control" value="{{getDepartmentField($user->department_id,'name')}}" readonly> -->
                        <textarea rows="2" name="department" class="form-control" readonly>{{getDepartmentField($user->department_id,'name')}}</textarea>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                      <div class="form-group">
                        <label>Position <span class="required text-danger"></span></label>
                        <input type="text" name="position" class="form-control" value="{{$user->position_id?getPositionName($user->position_id):$user->position}}" readonly>
                      </div>
                    </div>
                          
                  </div>

                        <!-- start table -->
                  <table class="table rs-text-border" id="salary">
                    <thead>
                      
                      <tr>
                        <th>{{$year}}</th>
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
                      </tr>
                      <tr><th colspan="14" style="background: #eee;">Myanmar Salary (USD)</th></tr>
                    </thead>
                    <tbody>
                      @foreach($mm_salary_types as $key=>$value)
                        <tr>
                       
                          <th>{{$value['label']}}</td>
                          <input type="hidden" name="type[]" value="{{$value['type']}}">
                          <td><input type="text" class="form-control" name="apr[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'april')}}"></td>
                          <td><input type="text" class="form-control" name="may[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'may')}}"></td>
                          <td><input type="text" class="form-control" name="jun[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'june')}}"></td>
                          <td><input type="text" class="form-control" name="jul[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'july')}}"></td>
                          <td><input type="text" class="form-control" name="aug[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'august')}}"></td>
                          <td><input type="text" class="form-control" name="sep[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'september')}}"></td>
                          <td><input type="text" class="form-control" name="oct[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'october')}}"></td>
                          <td><input type="text" class="form-control" name="nov[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'november')}}"></td>
                          <td><input type="text" class="form-control" name="dec[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'december')}}"></td>
                          <td><input type="text" class="form-control" name="jan[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'january')}}"></td>
                          <td><input type="text" class="form-control" name="feb[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'february')}}"></td>
                          <td><input type="text" class="form-control" name="mar[]y" value="{{getRsSalaryField($user->id,$year,$value['type'],'march')}}"></td>
                        
                        </tr>
                      @endforeach

                                                                
                    </tbody>
                  
                    <thead>
                      <tr><th colspan="14" style="background: #eee;">Japan Salary (Yen)</th></tr>
                      
                    </thead>
                    <tbody>

                      <tbody>
                      @foreach($jpn_salary_types as $key=>$value)
                        <tr>
                       
                          <th>{{$value['label']}}</td>
                          <input type="hidden" name="type[]" value="{{$value['type']}}">
                          <td><input type="text" class="form-control" name="apr[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'april')}}"></td>
                          <td><input type="text" class="form-control" name="may[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'may')}}"></td>
                          <td><input type="text" class="form-control" name="jun[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'june')}}"></td>
                          <td><input type="text" class="form-control" name="jul[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'july')}}"></td>
                          <td><input type="text" class="form-control" name="aug[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'august')}}"></td>
                          <td><input type="text" class="form-control" name="sep[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'september')}}"></td>
                          <td><input type="text" class="form-control" name="oct[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'october')}}"></td>
                          <td><input type="text" class="form-control" name="nov[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'november')}}"></td>
                          <td><input type="text" class="form-control" name="dec[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'december')}}"></td>
                          <td><input type="text" class="form-control" name="jan[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'january')}}"></td>
                          <td><input type="text" class="form-control" name="feb[]" value="{{getRsSalaryField($user->id,$year,$value['type'],'february')}}"></td>
                          <td><input type="text" class="form-control" name="mar[]y" value="{{getRsSalaryField($user->id,$year,$value['type'],'march')}}"></td>
                        
                        </tr>
                      @endforeach

                                                                
                    </tbody>

                                                                
                    </tbody>
                  </table>

                  <div class="row">
                    <div class="col-md-12 col-sm-12">
                      <div class="form-group text-center">
                        <input type="submit" class="btn btn-success" value="save">
                        <a href="{{url('salary-management/rs-salary/list')}}" class="btn btn-primary">Cancel</a>
                      </div>
                    </div>
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


            
    });

  </script>
@stop