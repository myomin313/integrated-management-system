@extends('layouts.master')
@section('title','Car Insurance Amount Update History')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Car Management</li>
             <li class="breadcrumb-item active">Car Insurance Amount Update History</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a> -->
           </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Premium Update List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                   @if(Session::has('msg'))
                   <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! session()->get('msg') !!}
                      </div>
                  @endif
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Insurance No</th>
                      <th>Insurance Company</th>
                      <th>Premiun Amount</th>
                      <th>Start Date</th>
                      <th>Due Date</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n=1 ?>
                    @foreach($insurance_update_datas as $insurance_update_data)
                    <tr>
                      <td>{{ $n }}</td>
                      <td>{{ $insurance_update_data->insurance_no }}</td>
                      <td>{{ $insurance_update_data->insurance_company }}</td>
                      <td>{{ $insurance_update_data->premium_amount }} {{ $insurance_update_data->currency }}</td>
                      <td>{{ $insurance_update_data->start_date }}</td>
                      <td>{{ $insurance_update_data->end_date }} </td>
                      <td>{{ $insurance_update_data->updated_user }} </td>
                      <td>{{ $insurance_update_data->created_at->format('Y-m-d g:i:s A') }}</td>
                      <td>{{ $insurance_update_data->updated_at->format('Y-m-d g:i:s A') }} </td>
                    </tr>
                    <?php $n++; ?>
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
@stop
