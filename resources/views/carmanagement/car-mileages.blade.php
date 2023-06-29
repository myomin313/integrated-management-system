@extends('layouts.master')
@section('title','Car Mileage List')
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
                   <a href="{{ route('car-mileages.index')}}">Car Mileage List</a></li>
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

     {{-- filter row 4 --}}
     
      @can('export-car-mileages')
     <section class="content filter-row4">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <form action="{{ route('car-mileages.export') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-2">
                        <div class="form-group" style="margin-top: 8px;">
                          <label for="repair_date">Start Date</label>
                           <div class="input-group date" id="mileageOne" data-target-input="nearest">
                          <input type="text" name="start_date"  class="form-control datepicker-input"
                         data-target="#mileageOne" value="{{ old('start_date') }}"  required/>
                         <div class="input-group-append" data-target="#mileageOne" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                         </div>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group" style="margin-top: 8px;">
                          <label for="repair_date">End Date</label>
                           <div class="input-group date" id="mileageTwo" data-target-input="nearest">
                          <input type="text" name="end_date"  class="form-control datepicker-input"
                         data-target="#mileageTwo" value="{{ old('end_date') }}" required />
                         <div class="input-group-append" data-target="#mileageTwo" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                         </div>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <!-- text input -->
                        <div class="form-group" style="margin-top: 39px;">
                          <button type="submit" class="btn btn-primary"> Export Car Mileage </button>
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
      @endcan
        {{-- filter row 4 --}}


    
     <section class="content filter-row">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <form action="{{ route('car-mileages.index') }}" method="post">
                                        @csrf
                                        <div class="row">
                                         <div class="col-sm-2">
                                           <div class="form-group" style="margin-top: 8px;">
                                           <label for="car_no">Car No</label>
                                           <select class="form-control" name="car_number"  style="width: 100%;">
                                             <option value="">- Select Car Number -</option>
                                              @foreach($cars as $car)
                                              <option value="{{ $car->id }}" {{ old("car_number", $car_number) == $car->id  ? 'selected' : '' }}>{{ $car->car_number }}</option>
                                              @endforeach
                                             </select>
                                            </div>
                                            </div>
                                             <div class="col-sm-2">
                                                <div class="form-group" style="margin-top: 8px;">
                                                 <label>Department</label>
                                                 <select class="form-control" name="department" style="width: 100%;">
                                                 <option value="">- Select -</option>
                                                   @foreach($departments as $department)
                                                  <option value="{{ $department->id }}" {{ old("department", $depart) == $department->id  ? 'selected' : '' }}>{{ $department->short_name }}</option>
                                                   @endforeach
                                                 </select>
                                                 </div>
                                             </div>
                                                <!-- text input -->
                                             <div class="col-sm-2">
                                               <div class="form-group" style="margin-top: 8px;">
                                                 <label for="date">Date</label>
                                                <div class="input-group date" id="datetimepickerSearch" data-target-input="nearest">
                                                 <input type="text" name="date"  class="form-control datetimepicker-input"
                                                    data-target="#datetimepickerSearch" value="{{ old('date',$date) }}" />
                                                  <div class="input-group-append" data-target="#datetimepickerSearch" data-toggle="datetimepicker">
                                                   <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                   </div>
                                                  </div>
                                                  </div>
                                                </div>
                                              <div class="col-sm-2">
                                                <!-- text input -->
                                                  <div class="form-group" style="margin-top: 37px;">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                    <a href="{{ route('car-mileages.index') }}" class="btn btn-warning">Reset</a>
                                                  </div>
                                               </div>
{{--
                                            <div class="col-sm-2">
                                               <div class="form-group" style="margin-top: 37px;">
                                              <a class="btn btn-warning" href="{{ route('car-mileages.export') }}">
                                                Export Car Mileage
                                              </a>
                                              </div>
                                            </div> --}}

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
                <h3 class="card-title">Car Mileage List</h3>
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
                      <th>Date</th>
                      <th>Current KM</th>
                      <th>KM</th>
                      <th>Liter</th>
                      <th>KM/Liter</th>
                      <th>Actual KM</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                      @canany(['car-mileage-edit','car-mileage-delete'])
                      <th>Action</th>
                      @endcan
                    </tr>
                      <!-- <th>Action (modal)</th> -->
                  </thead>
                  <tbody>
                        <?php $n=1; ?>
                   @foreach($car_mileages as $car_mileage)
                    <?php
                    $mil_date = str_replace('-"', '/', $car_mileage->date);
                    $mileage_date = date("d/m/Y", strtotime($mil_date));
                    
                    if( $car_mileage->liter != 0.00)
                     $km_liter = $car_mileage->km / $car_mileage->liter;
                   else
                     $km_liter =  $car_mileage->km;
                    
                   ?>

                  <tr>
                      <td>{{ $n  }}</td>
                       <td>type   - {{ $car_mileage->car_type }}<br>
                          no     - {{ $car_mileage->car_number }}<br>
                          model  - {{ $car_mileage->model_year }}
                     </td>
                      <td>
                          {{ $car_mileage->docname }}<br>
                          {{ $car_mileage->main_user_name }}
                      </td>
                    <td>
                       {{ $car_mileage->driver_name }}<br>
                       {{ $car_mileage->driver_type }}
                    </td>
                     <td>
                        {{ $mileage_date }}
                      </td>
                      <td>{{ number_format($car_mileage->current_km) }}
                      </td>
                      <td>{{ number_format($car_mileage->km) }}</td>
                      <td>{{ $car_mileage->liter }}</td>
                      <td>{{ number_format($km_liter,2) }}</td>
                      <td>{{ number_format($car_mileage->actual_km) }}</td>
                      <td>{{ $car_mileage->updated_user }}</td>
                       <td>
                        {{ $car_mileage->created_at->format('d/m/y g:i:s A') }}
                      </td>
                      <td>
                        {{ $car_mileage->updated_at->format('d/m/y g:i:s A') }}
                      </td>
                      <td>
                     @can('car-mileage-edit')
                      <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $car_mileage->id }}" data-car_type="{{ $car_mileage->car_type }}"
                       data-car_id="{{ $car_mileage->car_id }}"  data-department_name="{{ $car_mileage->docname }}" data-date="{{ $mileage_date }}"
                         data-liter="{{ $car_mileage->liter }}" data-current_km="{{ $car_mileage->current_km }}"
                        data-actual_km="{{ $car_mileage->actual_km }}" data-km="{{ $car_mileage->km }}"  onclick="addValueForEdit(this)">
                        <i class="fas fa-edit text-info"></i>
                      </a>
                      @endcan
                      @can('car-mileage-delete')
                      <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $car_mileage->id }}" data-name="{{ $car_mileage->car_number }}"
                        onclick="addValueForDelete(this)">
                        <i class="fas fa-trash text-danger"></i>
                      </a>&nbsp;
                      @endcan
                      </td>
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

      <!-- start -->
       <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Car Mileage Registration</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                @csrf
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Car Number <span class="required text-danger">*</span></label>
                 <select class="form-control" name="car_number" id="car_number" style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number}}</option>
                        @endforeach
                 </select>
                  <span class="text-danger error-text car_number_error"></span>
              </div>
               <div class="form-group">
                    <label for="name">Department Name </label>
                    <input type="text" class="form-control" id="department" name="department" disabled="disabled">
               </div>
              <div class="form-group">
                <label for="car_type">Car Type</label>
                    <input type="text" class="form-control" id="car_type" name="car_type" disabled="disabled">
              </div>

              <div class="form-group">
                <label for="date">Date <span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                    <input type="text" name="date" id="date" onblur="return myFunction()" class="form-control datetimepicker-input" data-target="#datetimepicker" />
                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                </div>
                 <span class="text-danger error-text date_error"></span>
              </div>

              <div class="form-group">
                <label for="current_km">Current KM </label>
                  <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="current_km" name="current_km"
                    placeholder="enter current km">
                    <span class="text-danger error-text current_km_error"></span>
              </div>
              <div class="form-group">
                <label for="liter">Liter <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="liter" name="liter" placeholder="enter liter">
                  <span class="text-danger error-text liter_error"></span>
              </div>

              <div class="form-group">
                <label for="actual_km">Actual KM <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="actual_km" name="actual_km" placeholder="enter actual meter">
                   <span class="text-danger error-text actual_km_error"></span>
                </div>
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
      <!-- end -->
      <div class="modal fade editModal" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Car Mileage Update</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                @csrf
                 <input type="hidden" id="id_update" name="id_update">
            <div class="modal-body">
                 <div class="form-group">
                <label for="name">Car Number <span class="required text-danger">*</span></label>
                 <select class="form-control" name="car_number_update" id="car_number_update" style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number}}</option>
                        @endforeach
                 </select>
                  <span class="text-danger error-text edit_car_number_error"></span>
              </div>
               <div class="form-group">
                    <label for="name">Department Name</label>
                    <input type="text" class="form-control" id="department_update" name="department_update" disabled="disabled">
              </div>
              <div class="form-group">
                <label for="car_type">Car Type</label>
                    <input type="text" class="form-control" id="car_type_update" name="car_type_update" disabled="disabled">
                </div>
                <div class="form-group">
                  <label for="date">Date <span class="required text-danger">*</span></label>
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                      <input type="text" name="date_update" onblur="return myFunctionTwo()" id="date_update" class="form-control datetimepicker-input"
                        data-target="#datetimepicker3" />
                      <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                      </div>
                  </div>
                  <span class="text-danger error-text edit_date_error"></span>
                </div>

                <div class="form-group">
                  <label for="current_km">Current KM </label>
                    <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="current_km_update" name="current_km_update" placeholder="enter current km">
                  <span class="text-danger error-text edit_current_km_error"></span>
                </div>
                <div class="form-group">
                  <label for="liter">Liter <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="liter_update" name="liter_update" placeholder="enter liter">
                    <span class="text-danger error-text edit_liter_error"></span>
                </div>
                <div class="form-group">
                  <label for="actual_km">Actual KM <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="actual_km_update" name="actual_km_update" placeholder="enter Actual KM">
                     <span class="text-danger error-text edit_actual_km_error"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-success btn-update">Update</button>
            </div>
          </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->






        <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Car Mileage</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('car-mileages.delete') }}" method="post">
              @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <p>Are you sure want to delete "<strong></strong>" Mileage ?</p>
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
     
      <script>
      function myFunction(e) {        
        var car_number = $("#car_number").val();          
        var date = $("#date").val();
        $.ajax({
        url: "{{ route('car-insurances.select-car-number-with-liter') }}",
        type: "POST",
        data: {car_number : car_number,date : date,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#liter").val(data.total_liter);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
      });
     }
     
      function myFunctionTwo(e) {        
        var car_number = $("#car_number_update").val();          
        var date = $("#date_update").val();
        $.ajax({
        url: "{{ route('car-insurances.select-car-number-with-liter') }}",
        type: "POST",
        data: {car_number : car_number,date : date,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#liter_update").val(data.total_liter);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
      });
     }
     
</script>


<script type="text/javascript">
//create script start
   $(".btn-submit").click(function(e){
        e.preventDefault();
        var car_number = $("#car_number").val();
        var current_km = $("#current_km").val();
        var date = $("#date").val();
        var liter = $("#liter").val();
        var actual_km = $("#actual_km").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-mileages.store') }}",
           data:{"date":date,"car_number":car_number,"current_km":current_km,
           "liter":liter,"actual_km":actual_km,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#department").val('');
                    $("#car_type").val('');
                    $("#car_number").val('');
                    $("#current_km").val('');
                    $("#liter").val('');
                    $("#actual_km").val('');
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
        var id = $("#id_update").val();
        var car_number = $("#car_number_update").val();
        var current_km = $("#current_km_update").val();
        var date = $("#date_update").val();
        var liter = $("#liter_update").val();
        var actual_km = $("#actual_km_update").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-mileages.update') }}",
           data:{"id":id,"date":date,"car_number":car_number,"current_km":current_km,
           "liter":liter,"actual_km":actual_km,"_token": "{{ csrf_token() }}"},
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
      var car_number=$(btn).data('car_number');
      var car_id=$(btn).data('car_id');
      var date=$(btn).data('date');
      var liter=$(btn).data('liter');
      var current_km=$(btn).data('current_km');
      var actual_km=$(btn).data('actual_km');

      $(".editModal #id_update").val(id);
      $(".editModal #department_update").val(department_name);
      $(".editModal #car_type_update").val(car_type);
      $(".editModal #date_update").val(date);
      $(".editModal #liter_update").val(liter);
      $(".editModal #current_km_update").val(current_km);
      $(".editModal #actual_km_update").val(actual_km);
      $("#car_number_update").val(car_id).change();

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
  //select insurance no
   $("#insurance_no").change(function() {
    var selectedValue = this.value;
    $.ajax({
        url: "{{ route('car-insurance-claim-histories.select-insurance-company') }}",
        type: "POST",
        data: {option : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#insurance_company").val(data.insurance_company);
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
   //select insurance no
   $("#insurance_no_update").change(function() {
    var selectedValue = this.value;
    $.ajax({
        url: "{{ route('car-insurance-claim-histories.select-insurance-company') }}",
        type: "POST",
        data: {option : selectedValue,"_token": "{{ csrf_token() }}"},
         success:function(data){
                if($.isEmptyObject(data.error)){
                   $("#insurance_company_update").val(data.insurance_company);
                }else{
                    printEditErrorMsg(data.error);
                }
           }
      });
  });
 //start for float charcode
function isNumberKey(event) {
    var key = window.event ? event.keyCode : event.which;
if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
    if($(this).val().indexOf('.') == -1)
        return true;
    else
        return false;
}
else if ( key < 48 || key > 57 ) {
    return false;
}
else return true;
};

</script>

<script>
$(document).ready(function(){


    $('#mileageOne').datetimepicker({
         format: 'DD/MM/YYYY'
    })


    $('#mileageTwo').datetimepicker({
         format: 'DD/MM/YYYY',
         endDate: new Date(),
    })

});

//start
$(document).ready(function () {
    var d = new Date();
    var monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    today = monthNames[d.getMonth()] + ' ' + d.getDate() + ' ' + d.getFullYear();

    $('#mileageTwo').attr('disabled', 'disabled');
    $('#mileageOne').datetimepicker({
        defaultDate: "+1d",
        minDate: 1,
        maxDate: "+3M",
        dateFormat: 'dd M yy',
        showOtherMonths: true,
        changeMonth: true,
        selectOtherMonths: true,
        required: true,
        showOn: "focus",
        numberOfMonths: 1,
    });

    $('#mileageOne').change(function () {
        var from = $('#mileageOne').datetimepicker('getDate');
        var date_diff = Math.ceil((from.getTime() - Date.parse(today)) / 86400000);
        var maxDate_d = date_diff+'y';
        date_diff = date_diff + 'd';
        $('#mileageTwo').val('').removeAttr('disabled').removeClass('hasDatepicker').datepicker({
            dateFormat: 'dd.mm.yy',
            minDate: date_diff,
            maxDate: maxDate_d
        });
    });

    $('#mileageTwo').keyup(function () {
        $(this).val('');
        alert('Please select date from Calendar');
    });
    $('#mileageOne').keyup(function () {
        $('#mileageOne,#mileageTwo').val('');
        $('#mileageTwo').attr('disabled', 'disabled');
        alert('Please select date from Calendar');
    });

});
</script>


    </section>
@stop
