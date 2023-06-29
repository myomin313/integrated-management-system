@extends('layouts.master')
@section('title','Car License List')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard </a></li>

               <li class="breadcrumb-item">Car  Management</li>
               <li class="breadcrumb-item active">
                   <a href="{{ route('car-licenses.index')}}">Car License List</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
             <a class="btn btn-default breadcrumb-btn openFilter float-sm-right" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
            <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a>
           </div>
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
                <form action="{{ route('car-licenses.index') }}" method="post">
                    @csrf
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Car No</label>
                      <select class="form-control" name="car_number"  style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}" {{ old("car_number", $car_number) == $car->id  ? 'selected' : '' }}>{{ $car->car_number }}</option>
                        @endforeach
                      </select>
                      </div>
                    </div>

                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Department</label>
                        <select class="form-control" name="department" style="width: 100%;">
                        <option value="">- Select Department-</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old("department", $depart) == $department->id  ? 'selected' : '' }}>{{ $department->short_name }}</option>
                        @endforeach
                      </select>
                      </div>
                    </div>
                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Driver</label>
                         <select class="form-control" name="driver"  style="width: 100%;">
                        <option value="">- Select driver-</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old("driver", $drive) == $driver->id  ? 'selected' : '' }}>{{ $driver->employee_name }}</option>
                        @endforeach
                      </select>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('car-licenses.index') }}" class="btn btn-warning">Reset</a>
                      </div>
                    </div>
                    
                      @can('export-license')
                     <div class="col-sm-2">
                        <div class="form-group" style="margin-top: 39px;">
                        <a class="btn btn-warning" href="{{ route('car-licenses.exportlicenses') }}">
                          Export  License
                        </a>
                      </div>
                      @endcan
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
                <h3 class="card-title">Car License List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                   @if(Session::has('msg'))
                   <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! session()->get('msg') !!}
                      </div>
                  @endif
                <table id="cars_record" class="table table-hover">
                  <thead>
                     <tr>
                      <th>No</th>
                      <th>Car</th>
                      <th>Department</th>
                      <th>Driver</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                       @canany(['car-license-edit','car-license-delete'])
                      <th>Action</th>
                      @endcan
                    </tr>
                  </thead>
                  <tbody>
                        <?php $n=1; ?>
                   @foreach($car_licenses as $car_license)
                   <?php
                    $start = str_replace('-"', '/', $car_license->start_date);
                    $license_start_date = date("d/m/Y", strtotime($start));

                     $end_date = str_replace('-"', '/', $car_license->due_date);
                    $license_end_date = date("d/m/Y", strtotime($end_date));
                   ?>
                  <tr>
                      <td>{{ $n }}</td>
                       <td>type   - {{ $car_license->car_type }}<br>
                          no     - {{ $car_license->car_number }}<br>
                          model  - {{ $car_license->model_year }}
                     </td>
                      <td>
                          {{ $car_license->docname }}<br>
                          {{ $car_license->main_user_name }}
                      </td>
                      <td>
                           {{ $car_license->driver_name }} <br>
                           {{ $car_license->driver_type }}
                    </td>
                      <td>{{ $license_start_date }}</td>
                      <td>{{ $license_end_date }}</td>
                      <td>{{ $car_license->updated_user }}</td>
                       <td>
                        {{ $car_license->created_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                        {{ $car_license->updated_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                     @can('car-license-edit')
                      <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $car_license->id }}" data-car_type="{{ $car_license->car_type }}"
                       data-car_id="{{ $car_license->car_id }}" data-start_date="{{ $license_start_date }}"
                       data-end_date="{{ $license_end_date }}"  data-department_name="{{ $car_license->docname }}"   onclick="addValueForEdit(this)">
                        <i class="fas fa-edit text-info"></i>
                      </a>
                      @endcan
                       @can('car-license-delete')
                      <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $car_license->id }}" data-name="{{ $car_license->car_number }}"
                        onclick="addValueForDelete(this)">
                        <i class="fas fa-trash text-danger"></i>
                      </a>&nbsp;
                      </td>
                       @endcan
                    </tr>
                    <?php $n++; ?>
                    @endforeach
                  </tbody>
                </table>

              </div>


            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

        </div>
        <!-- /.row -->


      </div>
      <!-- /.container-fluid -->

      <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Car License Registration</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
            <div class="modal-body">
                 @csrf
               <div class="form-group">
                <label for="car_no">Car Number <span class="required text-danger">*</span></label>
                    <select class="form-control" name="car_number" id="car_number" style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                       @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text car_number_error"></span>
                </div>

               <div class="form-group">
                  <label for="department_id" >Department Name </label>
                  <input type="text" class="form-control" id="department" name="department" disabled="disabled">
                </div>

              <div class="form-group">
                    <label for="car_type" >Car Type</label>
                      <input type="text" class="form-control" id="car_type" name="car_type" value="" disabled="disabled">
              </div>

              <div class="form-group">
                <label for="start_date" >Start Date <span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                    <input type="text" name="start_date" id="start_date" class="form-control datetimepicker-input"
                      data-target="#datetimepicker" />
                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                </div>
                <span class="text-danger error-text start_date_error"></span>
              </div>

              <div class="form-group">
                <label for="end_date">End Date <span class="required text-danger">*</span></label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                      <input type="text" name="end_date" id="end_date" class="form-control datetimepicker-input"
                        data-target="#datetimepicker2" />
                      <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                      </div>
                    </div>
                </div>
                 <span class="text-danger error-text end_date_error"></span>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-success btn-submit">Save</button>
            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <div class="modal fade editModal" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Car License Update</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                @csrf
            <div class="modal-body">
                 <input type="hidden" id="id_update" name="id_update">
               <div class="form-group">
                <label for="car_no">Car Number <span class="required text-danger">*</span></label>
                    <select class="form-control" name="car_number_update" id="car_number_update" style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                       @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text edit_car_number_error"></span>
                </div>

               <div class="form-group">
                  <label for="department_id" >Department Name </label>
                  <input type="text" class="form-control" id="department_update" name="department_update" disabled="disabled">
                </div>
                <div class="form-group">
                    <label for="car_type">Car Type</label>
                        <input type="text" class="form-control" id="car_type_update" name="car_type_update" value="honda fit">
                   </div>

                <div class="form-group">
                    <label for="start_date">Start Date <span class="required text-danger">*</span></label>
                          <div class="input-group date" id="datetimepickeredit" data-target-input="nearest">
                            <input type="type" name="start_date_update" id="start_date_update" class="form-control datetimepicker-input" data-target="#datetimepickeredit" />
                            <div class="input-group-append" data-target="#datetimepickeredit" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                          </div>
                          <span class="text-danger error-text edit_start_date_error"></span>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date <span class="required text-danger">*</span></label>
                        <div class="input-group date" id="datetimepickeredit2" data-target-input="nearest">
                          <input type="type" name="end_date_update" id="end_date_update" class="form-control datetimepicker-input"
                            data-target="#datetimepickeredit2" />
                          <div class="input-group-append" data-target="#datetimepickeredit2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                        <span class="text-danger error-text edit_end_date_error"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-update">Update</button>
            </div>
            </div>
            </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Car License</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('car-licenses.delete') }}" method="post">
              @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <p>Are you sure want to delete "<strong></strong>" license No?</p>
            </div>

            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Sure</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


      <!-- create script start -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
//create script start
   $(".btn-submit").click(function(e){
        e.preventDefault();
        var department = $("#department").val();
        var car_type = $("#car_type").val();
        var car_number = $("#car_number").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-licenses.store') }}",
           data:{"department":department,"car_type":car_type,"car_number":car_number,
           "start_date":start_date,"end_date":end_date,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#department").val('');
                    $("#car_type").val('');
                    $("#car_number").val('');
                    $("#start_date").val('');
                    $("#end_date").val('');
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    // create script end
    // create script end
    //
     $(".btn-update").click(function(e){
        e.preventDefault();
        var id_update = $("#id_update").val();
        var department = $("#department_update").val();
        var car_type = $("#car_type_update").val();
        var car_number = $("#car_number_update").val();
        var start_date = $("#start_date_update").val();
        var end_date = $("#end_date_update").val();

        $.ajax({
           type:'POST',
           url:"{{ route('car-licenses.update') }}",
          data:{"id":id_update,"department":department,"car_type":car_type,"car_number":car_number,
           "start_date":start_date,"end_date":end_date,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    location.reload();
                }else{
                    printEditErrorMsg(data.error);
                }
           }
        });
    });

   function printErrorMsg (msg) {

           $.each( msg, function( key, value ) {
              $('.'+key+'_error').text(value);
          });
    }
    function printEditErrorMsg (msg) {
           $.each( msg, function( key, value ) {
              $('.edit_'+key+'_error').text(value);
          });
    }

  //update script start

  function addValueForEdit(btn){

      var id=$(btn).data('id');
      var department_name=$(btn).data('department_name');
      var car_type=$(btn).data('car_type');
      var car_id=$(btn).data('car_id');
      var start_date=$(btn).data('start_date');
      var end_date=$(btn).data('end_date');

      $(".editModal #id_update").val(id);
      $(".editModal #car_type_update").val(car_type);
      $(".editModal #start_date_update").val(start_date);
      $(".editModal #end_date_update").val(end_date);
      $(".editModal #department_update").val(department_name);
      $("#car_number_update").val(car_id).change();
      // $("#department_update").val(department_id).change();

  }

  function addValueForStatusChange(btn){
      var id=$(btn).data('id');
      var status=$(btn).data('status');

      $(".statusModal #id").val(id);
      if(status == 1){
        $("#status").html('');
        $("#status").append('<option value="' + status + '" "selected">' +'Active' +
                                '</option><option value="0" "selected">' +'Unactive' +
                                '</option>');
      }else{
        $("#status").html('');
        $("#status").append('<option value="' + status + '" "selected">' +'Unactive' +
                                '</option><option value="1" "selected">' +'Active' +
                                '</option>');
      }
  }

   function addValueForDelete(btn){
        var id=$(btn).data('id');
        var name=$(btn).data('name');
        $(".deleteModal #id").val(id);
        $(".deleteModal strong").html(name);
  }
  $('div.alert').delay(5000).slideUp(300);

  // car number select change
  $("#car_number").change(function() {
    var selectedValue = this.value;
    $.ajax({
        url: "{{ route('car-insurances.select-car-number') }}",
        type: "POST",
        data: {option : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#car_type").val(data.car_type);
                   $("#department").val(data.name);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
      });
  });

   // car number select change
  $("#car_number_update").change(function() {
    var selectedValue = this.value;
    $.ajax({
        url: "{{ route('car-insurances.select-car-number') }}",
        type: "POST",
        data: {option : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#car_type_update").val(data.car_type);
                   $("#department_update").val(data.name);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
      });
  });


  function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

</script>




    </section>
@stop
