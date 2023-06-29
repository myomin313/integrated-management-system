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
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
                </div>
              </div>
            @endif
            <div class="card" style="padding:20px;">
              <div class="card-header">
                <h3 class="card-title">Create User</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="{{route('user.store')}}" method="post" id="create_user" class="prevent-multiple-submit">
                  @csrf
                  <div class="form-group row">
                    <label for="noti_type" class="col-sm-3 col-form-label">Noti Type</label>
                    <div class="col-sm-9">
                      <div class="form-check" style="margin-top:9px;">
                          <input class="form-check-input" type="radio" name="noti_type" value="email" checked>
                          <label class="form-check-label">Email</label>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input class="form-check-input" type="radio" name="noti_type" value="phone">
                          <label class="form-check-label">Phone</label>
                      </div>
                      
                    </div>
                      
                  </div>
                  <div class="form-group" id="phone_section">
                      <label for="phone">Phone <span class="required text-danger">*</span></label>
                      <input type="text" class="form-control" id="phone" name="phone" placeholder="Eg. 09254068494" required>
                  </div>
                  <div class="form-group" id="email_section">
                      <label for="email">Email <span class="required text-danger">*</span></label>
                      <input type="text" class="form-control" id="email" name="email" placeholder="Eg. example@gmail.com" autofocus required>
                  </div>
                  <div class="form-group">
                      <label for="name">Username <span class="required text-danger">*</span></label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Eg. aunguang" required>
                  </div>
                  {{-- <div class="form-group ">
                      <label for="position_id">Position <span class="required text-danger">*</span></label>
                    
                      <select class="form-control select2bs4" name="position_id" id="position_id" required style="width: 100%;">
                          <option selected="selected">- Select -</option>
                          @foreach($positions as $key=>$value)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                          @endforeach

                      </select>
                  </div>
                  <div class="form-group ">
                      <label for="profile_id">Fingerprint Profile <span class="required text-danger">*</span></label>
                    
                      <select class="form-control select2bs4" name="profile_id" id="profile_id" required style="width: 100%;">
                          <option selected="selected" value="0">- Select -</option>
                          @foreach($fig_profiles as $key=>$value)
                            <option value="{{$value->pro_id}}">{{$value->pro_UserName}}</option>
                          @endforeach

                      </select>
                  </div> --}}
                  <div class="form-group">
                    <button type="submit" class="btn btn-success" name="save_new">Save & New</button>
                    <button type="submit" class="btn btn-info" name="save">Save</button>
                    <a href="{{route('user.list')}}" type="button" class="btn btn-primary">Cancel</a>
                      
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

   
        var val = $("input[type='radio']:checked").val();

        if(val=='email'){
          $('#phone').removeAttr('required');
          $('#phone_section').hide();
        }
        else{
          $('#email').removeAttr('required');
          $('#email_section').hide();
        }	

        $('input[type=radio][name=noti_type]').change(function() {
		        if(this.value=='email'){
            	$('#phone').removeAttr('required');
            	$('#phone_section').hide();

            	$('#email').attr('required');
            	$('#email_section').show();
            }
            else{
            	$('#email').removeAttr('required');
            	$('#email_section').hide();

            	$('#phone').attr('required');
            	$('#phone_section').show();
            }
		    });
            
    });

  </script>
@stop