@extends('layouts.master')
@section('title','Car License Noti')
@section('content')
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active">Car License Noti</li>
            </ol>
          </div><!-- /.col -->
          <!--<div class="col-sm-6">-->
          <!--  <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a>-->
          <!-- </div>-->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
           
        <div class="col-12">
              @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

            <div class="card">
               <div class="card-header">
                <h3 class="card-title">Edit Car License Expire</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="{{ route('alert.car-license-noti-update') }}" method="post">
                        @csrf
              <div class="form-group">
                    <label for="name">First Person Email<span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_person_email" name="first_person_email" placeholder="Enter First Person Email"
                    value="{{ $data->first_person_email }}" >
                    @if($errors->has('first_person_email'))
                        <div class="error text-danger">{{ $errors->first('first_person_email') }}</div>
                    @endif
              </div>
              <div class="form-group">
                <label for="abbreviation">Second Person Email</label>
                <input type="text" class="form-control" id="second_person_email" name="second_person_email" placeholder="Enter Second Person Email"
                 value="{{ $data->second_person_email }}">
                <span class="text-danger error-text short_name_error"></span>
              </div>
               <div class="col-md-12 col-sm-12">
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="save">
                 </div>
                 </div>
                </form>         

            </div>
          </div>
       </div>

              
         
        </div>
       </div>
    </section>
  </section>
@stop