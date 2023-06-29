@extends('layouts.master')
@section('title','Car Insurance Claim List')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">

      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Car  Management</li>
               <li class="breadcrumb-item active">
                   <a href="{{ route('car-insurance-claim-histories.index')}}">Car Insurance Claim List</a></li>
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
                <form action="{{ route('car-insurance-claim-histories.index')}}" method="post">
                  @csrf
                 <div class="row">
                    <div class="col-sm-2">
                      <!-- text input -->
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
                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label  for="insurance_no">Insurance No</label>

                       <select class="form-control" name="insurance_no" style="width: 100%;">
                        <option value="">- Select Insurance No -</option>
                        @foreach($car_insurances as $car_insurance)
                        <option value="{{ $car_insurance->id}}" {{ old("insurance_no", $insurance_no) == $car_insurance->id  ? 'selected' : '' }}>{{ $car_insurance->insurance_no }}</option>
                        @endforeach
                       </select>

                      </div>
                    </div>
                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="claim_date">Claim Date</label>
                         <div class="input-group date" id="datetimepickerSearch" data-target-input="nearest">
                      <input type="text" name="claim_date"  class="form-control datetimepicker-input"
                       data-target="#datetimepickerSearch"  value="{{ old('claim_date',$claim_date) }}"/>
                       <div class="input-group-append" data-target="#datetimepickerSearch" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                    </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('car-insurance-claim-histories.index')}}" class="btn btn-warning">Reset</a>
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
                <h3 class="card-title">Car Insurance List</h3>
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
                      <th>Insurance No</th>
                      <th>Insurance Company</th>
                      <th>Driver</th>
                      <th>Claim Date</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                     @canany(['car-insurance-claim-edit','car-insurance-claim-delete'])
                      <th>Action</th>
                      @endcan
                    </tr>
                      <!-- <th>Action (modal)</th> -->
                  </thead>
                  <tbody>
                        <?php $n=1; ?>
                   @foreach($car_insurance_claim_histories as $car_insurance_claim_history)
                    <?php
                    $date = str_replace('-"', '/', $car_insurance_claim_history->claim_date);
                    $history_claim_date = date("d/m/Y", strtotime($date));
                   ?>
                   <tr>
                      <td>{{ $n }}</td>
                       <td>type   - {{ $car_insurance_claim_history->car_type }}<br>
                          no     - {{ $car_insurance_claim_history->car_number }}<br>
                          model  - {{ $car_insurance_claim_history->model_year }}
                     </td>
                      <td>
                          {{ $car_insurance_claim_history->insurance_no }}
                      </td>
                    <td>
                       {{ $car_insurance_claim_history->insurance_company }}
                    </td>
                      <td>{{ $car_insurance_claim_history->driver_name }}<br>
                          {{ $car_insurance_claim_history->driver_type }}
                     </td>
                      <td>{{ $history_claim_date }}</td>
                      <td>{{ $car_insurance_claim_history->updated_user }}</td>
                       <td>
                        {{ $car_insurance_claim_history->created_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                        {{ $car_insurance_claim_history->updated_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                          
                         @can('car-insurance-claim-edit')
                      <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $car_insurance_claim_history->id }}" data-car_type="{{ $car_insurance_claim_history->car_type }}"
                       data-car_id="{{ $car_insurance_claim_history->car_id }}" data-model_year="{{ $car_insurance_claim_history->model_year }}" data-chasis_no="{{ $car_insurance_claim_history->chasis_no }}"
                        data-insurance_id="{{ $car_insurance_claim_history->insurance_id }}" data-insurance_company="{{ $car_insurance_claim_history->insurance_company }}" data-premium_amount="{{ $car_insurance_claim_history->premium_amount }}"
                         data-claim_date="{{ $history_claim_date }}" data-claim_detail="{{ $car_insurance_claim_history->claim_detail }}"
                        data-department_name="{{ $car_insurance_claim_history->docname }}"   onclick="addValueForEdit(this)">
                        <i class="fas fa-edit text-info"></i>
                      </a>
                      @endcan
                       @can('car-insurance-claim-delete')
                      <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $car_insurance_claim_history->id }}" data-name="{{ $car_insurance_claim_history->insurance_no }}"
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
      <!-- /.container-fluid -->

         <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Car Insurance Claim Registration</h4>
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
                <label for="insurance_no" >Insurance No <span class="required text-danger">*</span></label>
                  <select class="form-control" name="insurance_no" id="insurance_no" style="width: 100%;">
                        <option value="">- Select Insurance No -</option>
                        @foreach($car_insurances as $car_insurance)
                        <option value="{{ $car_insurance->id}}">{{ $car_insurance->insurance_no }}</option>
                        @endforeach
                 </select>
                  <span class="text-danger error-text insurance_no_error"></span>
              </div>

              <div class="form-group">
                <label for="insurance_company">Insurance Company</label>
                  <input type="text" class="form-control" id="insurance_company" name="insurance_company"
                    placeholder="enter insurance company" disabled="disabled">
                    <span class="text-danger error-text insurance_company_error"></span>
              </div>
              <!-- start -->
              <div class="form-group">
                <label for="claim_date">Claim Date <span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                    <input type="text" name="claim_date" id="claim_date" class="form-control datetimepicker-input"
                      data-target="#datetimepicker" />
                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                </div>
                 <span class="text-danger error-text claim_date_error"></span>
              </div>

              <!-- end -->
              <div class="form-group">
                <label for="claim_detail">Claim Detail</label>
                  <textarea name="claim_detail" id="claim_detail" class="form-control"></textarea>
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

      <!-- start -->
         <div class="modal fade editModal" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Car Insurance Claim Update</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
               <input type="hidden" id="id_update" name="id_update">
            <div class="modal-body">
              <div class="form-group">
                <label for="car_no" >Car Number <span class="required text-danger">*</span></label>
                 <select class="form-control" name="car_number_update" id="car_number_update" style="width: 100%;">
                      @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number}}</option>
                      @endforeach
                    </select>
                    <span class="text-danger error-text edit_car_number_error"></span>
            </div>
            <div class="form-group">
                    <label for="name">Department Name</label>
                    <input type="text" class="form-control" id="department_update" name="department_update" disabled="disabled">
                   <span class="text-danger error-text edit_department_error"></span>
             </div>
             <div class="form-group">
                <label for="car_type">Car Type</label>
                    <input type="text" class="form-control" id="car_type_update" name="car_type_update" disabled="disabled">
                    <span class="text-danger error-text edit_car_type_error"></span>
              </div>
             <div class="form-group">
                <label for="insurance_no" >Insurance No <span class="required text-danger">*</span></label>
                  <select class="form-control" name="insurance_no_update" id="insurance_no_update" style="width: 100%;">
                        <option value="">- Select Insurance No -</option>
                        @foreach($car_insurances as $car_insurance)
                        <option value="{{ $car_insurance->id}}">{{ $car_insurance->insurance_no }}</option>
                        @endforeach
                 </select>
                  <span class="text-danger error-text edit_insurance_no_error"></span>
              </div>
            <div class="form-group">
                <label for="insurance_company" >Insurance Company</label>
                    <input type="text" class="form-control" id="insurance_company_update" name="insurance_company_update" disabled="disabled" placeholder="enter insurance company">
                 <span class="text-danger error-text edit_insurance_company_error"></span>
            </div>
            <div class="form-group">
                <label for="claim_date">Claim Date<span class="required text-danger">*</span></label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                      <input type="text" name="claim_date_update" id="claim_date_update" class="form-control datetimepicker-input"
                        data-target="#datetimepicker2" />
                      <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                      </div>
                </div>
                 <span class="text-danger error-text edit_claim_date_error"></span>
            </div>



            <div class="form-group">
                <label for="claim_detail">Claim Detail</label>
                    <textarea name="claim_detail_update" id="claim_detail_update" class="form-control"></textarea>
                    <span class="text-danger error-text edit_claim_detail_error"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-update">Update</button>
            </div>
            </div>
            </form>
        </div>
      </div>


      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Car Insurance Claim History</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('car-insurance-claim-histories.delete') }}" method="post">
              @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <p>Are you sure want to delete "<strong></strong>" Insurance No?</p>
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
        var car_number = $("#car_number").val();
        var insurance_no = $("#insurance_no").val();
        var insurance_company = $("#insurance_company").val();
        var claim_date = $("#claim_date").val();
        var claim_detail = $("#claim_detail").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-insurance-claim-histories.store') }}",
           data:{"car_number":car_number,"insurance_no":insurance_no,"insurance_company":insurance_company,
           "claim_date":claim_date,"claim_detail":claim_detail,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#department").val('');
                    $("#car_type").val('');
                    $("#car_number").val('');
                    $("#insurance_no").val('');
                    $("#insurance_company").val('');
                    $("#premium_amount").val('');
                    $("#claim_date").val('');
                    $("#claim_detail").val('');
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
        var insurance_no = $("#insurance_no_update").val();
        var insurance_company = $("#insurance_company_update").val();
        var claim_date = $("#claim_date_update").val();
        var claim_detail = $("#claim_detail_update").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-insurance-claim-histories.update') }}",
           data:{"id":id,"car_number":car_number,"insurance_no":insurance_no,"insurance_company":insurance_company,
           "claim_date":claim_date,"claim_detail":claim_detail,"_token": "{{ csrf_token() }}"},
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
      var insurance_id=$(btn).data('insurance_id');
      var insurance_company=$(btn).data('insurance_company');
      var claim_date=$(btn).data('claim_date');
      var claim_detail=$(btn).data('claim_detail');

      $(".editModal #id_update").val(id);
      $(".editModal #department_update").val(department_name);
      $(".editModal #car_type_update").val(car_type);
      $(".editModal #insurance_company_update").val(insurance_company);
      $(".editModal #claim_date_update").val(claim_date);
      $(".editModal #claim_detail_update").val(claim_detail);
      $(".editModal #department_update").val(department_name);
      $("#car_number_update").val(car_id).change();
      $("#insurance_no_update").val(insurance_id).change();

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



  function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

</script>




    </section>
@stop
