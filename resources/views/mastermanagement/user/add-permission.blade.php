@extends('layouts.master')
@section('title','User Management')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active"><a href="{{url('master-management/user/list')}}">User Management</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            
            
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
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
                    <h3 class="card-title">
                      <strong>Add Permission</strong>
                      <br>
                      <small>Employee Name : {{$user->employee_name}}</small><br>
                      <small>Department : {{getDepartmentField($user->department_id,'name')}}</small><br>
                      <small>Position : {{getPositionName($user->position_id,'name')}}</small><br>
                    </h3>

                  </div>
                  <div class="col-sm-6 text-right">
                    <input type="checkbox" id="permission1" class="all" /> Check All
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <form method="post" action="{{url('master-management/user/add-permission',$user->id)}}" class="prevent-multiple-submit">
                  {{csrf_field()}}
                  <table class="table">
                    @php 
                      $i=2;        
                    @endphp
                    @foreach ($permissions as $type => $permission_list)

                      @php $j=0; @endphp
                      <tr>
                        <th colspan="5" style="padding-left: 10px;font-size: 14px;background-color: #eee">{{$type}}</th>
                      </tr>
                                            
                      <tr>
                        @foreach ($permission_list as $key=>$per)
                          @php
                            $checkbox="permission".$i;
                          @endphp
                          <td style="font-size: 12px;">
                            @if($per->name=="attendance-read-all" or $per->name=="attendance-read-group" or $per->name=="attendance-read-one")
                              <input type="radio" data-title="{{$per->description}}" name="attendance_permission" id="{{$checkbox}}" value="{{$per->name}}" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}
                            @elseif($per->name=="ot-read-all" or $per->name=="ot-read-group" or $per->name=="ot-read-one")
                              <input type="radio" data-title="{{$per->description}}" name="ot_data_permission" id="{{$checkbox}}" value="{{$per->name}}" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}
                            @elseif($per->name=="salary-read-all" or $per->name=="salary-read-group" or $per->name=="salary-read-one")
                              <input type="radio" data-title="{{$per->description}}" name="salary_data_permission" id="{{$checkbox}}" value="{{$per->name}}" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}
                            @elseif($per->name=="tax-read-all" or $per->name=="tax-read-group" or $per->name=="tax-read-one")
                              <input type="radio" data-title="{{$per->description}}" name="tax_data_permission" id="{{$checkbox}}" value="{{$per->name}}" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}
                              
                               <!--start myo -->
                                  @elseif($per->name=="leave-read-all" or $per->name=="leave-read-group" or $per->name=="leave-read-one")
                              <input type="radio" data-title="{{$per->description}}" name="leave_data_permission" id="{{$checkbox}}" value="{{$per->name}}" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}

                              @elseif($per->name=="car-read-all" or $per->name=="car-read-group" or $per->name=="car-read-one")
                              <input type="radio" data-title="{{$per->description}}" name="car_data_permission" id="{{$checkbox}}" value="{{$per->name}}" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}

                            

                               @elseif($per->name=="employee-read-all" or $per->name=="employee-read-group" or $per->name=="employee-read-one")
                              <input type="radio" data-title="{{$per->description}}" name="employee_data_permission" id="{{$checkbox}}" value="{{$per->name}}" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}

                              <!-- end myo  -->
                            @else         
                              <input type="checkbox" data-title="{{$per->description}}" name="permission[]" id="{{$checkbox}}" value="{{$per->name}}" class="checkAll" 
                              @if(in_array($per->name,$selectedPermission))
                                checked="checked"
                              @endif /> {{$per->display_name}}   

                            @endif   
                          </td>

                          @php
                            $j+=1; 
                            $i+=1;
                          @endphp

                          @if($j%5==0)
                            </tr><tr>
                          @endif
                        @endforeach
                      </tr>
                    @endforeach
                  </table>
                  <div class="ln_solid"></div>
                  <div class="item form-group">
                    <div class="col-md-6 col-sm-6 offset-md-3">    
                      <button type="submit" class="btn btn-success" name="save">Save</button>
                      <a href="{{url('master-management/user/list')}}" class="btn btn-primary" type="button">Cancel</a>
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
              
        $('.all').click(function(){
          if(this.checked){
            $('.checkAll').each(function(){
              this.checked=true;
            });
          }
          else{
            $('.checkAll').each(function(){
              this.checked=false;
            });
          }

        });

    });
    
  </script>
@stop