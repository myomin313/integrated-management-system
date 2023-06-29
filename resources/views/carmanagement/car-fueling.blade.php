@extends('layouts.master')
@section('title','Car Fueling List')
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
                   <a href="{{ route('car-fuelings.index')}}">Car Fueling List</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <a class="btn btn-default breadcrumb-btn openFilter float-sm-right" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
              
              
              @can('fueling-export')
              <a class="btn btn-primary breadcrumb-btn openFilter2 float-sm-right" href="#" id="advance_search2">
              <i class="fas fa-search-minus"></i>Fueling Export</a>
              @endcan

            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
            <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a>
           </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

     <!-- /.content-header -->
    <section class="content filter-row2">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <form action="{{ route('car-fuelings.excel-export') }}" method="post">
                   @csrf
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="car_no">Car No</label>
                        <select class="form-control" name="car_number_search"  style="width: 100%;">
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}" {{ old("car_number", $car_number) == $car->id  ? 'selected' : '' }}>{{ $car->car_number }}</option>
                        @endforeach
                      </select>
                      </div>
                    </div>
                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="date">Date</label>
                         <div class="input-group date" id="datetimepickerExcel3" data-target-input="nearest">
                      <input type="text" name="date_search"  class="form-control datetimepicker-input"
                       data-target="#datetimepickerExcel3" value="{{ old('date',$date) }}" required />
                       <div class="input-group-append" data-target="#datetimepickerExcel3" data-toggle="datetimepicker" >
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                    </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-warning">Fueling Export</button>
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
    <!-- /.content-header -->
    <section class="content filter-row">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <form action="{{ route('car-fuelings.index') }}" method="post">
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
                    <span style="margin-top: 39px;">~</span>
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
                    <span style="margin-top: 39px;">~</span>
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
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('car-fuelings.index') }}" class="btn btn-warning">Reset</a>
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
                <h3 class="card-title">Car Fueling List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                   @if(Session::has('msg'))
                   <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! session()->get('msg') !!}
                      </div>
                  @endif
                <table  id="cars_record"  class="table table-hover">
                  <thead>

                     <tr>
                      <th>No</th>
                      <th>Car</th>
                      <th>Department</th>
                      <th>Driver</th>
                      <th>Liter</th>
                      <th>Rate</th>
                      <th>Total</th>
                      <th>Date</th>
                      <th>Fueling Kilometer</th>
                      <th>Reason</th>
                      <th>Status</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                     @canany(['car-fueling-edit','car-fueling-delete'])
                      <th>Action</th>
                      @endcan
                    </tr>
                      <!-- <th>Action (modal)</th> -->
                  </thead>
                  <tbody>
                        <?php $n=1; ?>
                   @foreach($car_fuelings as $car_fueling)

                      <?php
                    $date = str_replace('-"', '/', $car_fueling->date);
                    $fueling_date = date("d/m/Y", strtotime($date));
                   ?>
                  <tr>
                      <td>{{ $n }}</td>
                       <td>type   - {{ $car_fueling->car_type }}<br>
                          no     - {{ $car_fueling->car_number }}<br>
                          model  - {{ $car_fueling->model_year }}<br>
                     </td>
                      <td>
                          {{ $car_fueling->docname }}<br>
                          {{ $car_fueling->main_user_name }}
                      </td>
                    <td>
                       {{ $car_fueling->driver_name }}<br>
                       {{ $car_fueling->driver_type }}
                    </td>
                      <td>{{ $car_fueling->liter }}</td>
                      <td>{{ number_format($car_fueling->rate) }}</td>
                      <td>{{ number_format($car_fueling->totalRate) }}</td>
                      <td>{{ $fueling_date }}</td>
                      <td>{{ number_format($car_fueling->current_meter) }}</td>
                      <td>{{ $car_fueling->reason }}</td>
                      <td>
                        @if(auth()->user()->id ==  $car_fueling->main_user )
                       <select class="status-select" dataId="{{ $car_fueling->id }}">
                           <option value="pending" {{ ( $car_fueling->status == 'pending') ? 'selected' : '' }}>pending</option>
                           <option value="reject" {{ ( $car_fueling->status == 'reject') ? 'selected' : '' }}>reject</option>
                           <option value="accept" {{ ( $car_fueling->status == 'accept') ? 'selected' : '' }}>accept</option>
                       </select>
                       @else
                       @if($car_fueling->status == 'accept')
                      <p style="color:green"> {{ $car_fueling->status }}</p>
                      @elseif($car_fueling->status == 'reject')
                      <p style="color:red"> {{ $car_fueling->status }}</p>
                      @else
                      <p style="color:orange"> {{ $car_fueling->status }}</p>
                      @endif
                       @endif
                      </td>
                      <td>{{ $car_fueling->updated_user }}</td>
                       <td>
                        {{ $car_fueling->created_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                        {{ $car_fueling->updated_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                     @can('car-fueling-edit')
                      <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $car_fueling->id }}" data-car_type="{{ $car_fueling->car_type }}"
                       data-car_id="{{ $car_fueling->car_id }}"  data-department_name="{{ $car_fueling->docname }}" data-date="{{ $fueling_date }}"
                         data-liter="{{ $car_fueling->liter }}" data-current_meter="{{ $car_fueling->current_meter }}"
                         data-rate="{{ $car_fueling->rate }}" data-reason="{{ $car_fueling->reason }}" onclick="addValueForEdit(this)">
                        <i class="fas fa-edit text-info"></i>
                      </a>
                      @endcan
                       @can('car-fueling-delete')
                      <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $car_fueling->id }}" data-name="{{ $car_fueling->car_number }}"
                        onclick="addValueForDelete(this)">
                        <i class="fas fa-trash text-danger"></i>
                      </a>&nbsp;
                      </td>
                      @endcan
                    </tr>
                    <?php $n++ ?>
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
              <h4 class="modal-title">Car Fueling Registration</h4>
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
                    <label for="name">Department Name</label>
                    <input type="text" class="form-control" id="department" name="department" disabled="disabled">
               </div>
              <div class="form-group">
                <label for="car_type">Car Type</label>
                    <input type="text" class="form-control" id="car_type" name="car_type" disabled="disabled">
              </div>

              <div class="form-group">
                <label for="date" >Date <span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                    <input type="text" name="date" id="date" class="form-control datetimepicker-input" data-target="#datetimepicker" />
                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                </div>
                <span class="text-danger error-text date_error"></span>
              </div>

              <div class="form-group">
                <label for="liter">Rate<span class="required text-danger">*</span></label>
                    <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="rate" name="rate">
                  <span class="text-danger error-text rate_error"></span>
             </div>

              <div class="form-group">
                <label for="liter">Liter <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="liter" name="liter">
                  <span class="text-danger error-text liter_error"></span>
             </div>
             <div class="form-group">
                <label for="current_meter">Fueling Kilometer <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="current_meter" name="current_meter">
                 <span class="text-danger error-text current_meter_error"></span>
             </div>
             
               <div class="form-group">
                <label for="current_meter">Reason </label>
                    <!--<input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="current_meter" name="current_meter">-->
                    <textarea name="reason" class="form-control"  id="reason"></textarea>
                 <!--<span class="text-danger error-text current_meter_error"></span>-->
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
              <h4 class="modal-title">Car Fueling Update</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
              @csrf
            <div class="modal-body">
               <input type="hidden" id="id_update" name="id_update">
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
                    <label for="name">Department Name <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="department_update" name="department_update" disabled="disabled">
                </div>
                <div class="form-group">
                <label for="car_type">Car Type <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="car_type_update" name="car_type_update" disabled="disabled">
                </div>

                <div class="form-group">
                    <label for="date">Date <span class="required text-danger">*</span></label>
                      <div class="input-group date" id="datetimepickeredit" data-target-input="nearest">
                        <input type="text" name="date_update" id="date_update" class="form-control datetimepicker-input"
                          data-target="#datetimepickeredit" />
                        <div class="input-group-append" data-target="#datetimepickeredit" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                    </div>
                     <span class="text-danger error-text edit_date_error"></span>
                </div>
                <div class="form-group">
                    <label for="rate">Rate<span class="required text-danger">*</span></label>
                        <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="rate_update" name="rate_update">
                      <span class="text-danger error-text edit_rate_error"></span>
                 </div>
                <div class="form-group">
                  <label for="liter_update">Liter <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="liter_update"  onkeypress="return isNumberKey(event)" name="liter_update">
                    <span class="text-danger error-text edit_liter_error"></span>
                </div>
                <div class="form-group">
                    <label for="current_meter">Fueling Kilometer <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="current_meter_update"  onkeypress="return isNumberKey(event)" name="current_meter_update">
                    <span class="text-danger error-text edit_current_meter_error"></span>
                 </div>
                 
                <div class="form-group">
                  <label for="current_meter">Reason </label>
                    <!--<input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="current_meter" name="current_meter">-->
                    <textarea name="reason_update" class="form-control"  id="reason_update"></textarea>
                 <!--<span class="text-danger error-text current_meter_error"></span>-->
                </div>
             
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-update">Save</button>
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
              <h4 class="modal-title">Delete Car Fueling History</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('car-fuelings.delete') }}" method="post">
              @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <p>Are you sure want to delete "<strong></strong>" Car No?</p>
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
        var rate = $("#rate").val();
        var liter = $("#liter").val();
        var date = $("#date").val();
        var current_meter = $("#current_meter").val();
        var reason = $("#reason").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-fuelings.store') }}",
           data:{"car_number":car_number,"liter":liter,"date":date,"rate":rate,
           "current_meter":current_meter,"reason":reason,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#department").val('');
                    $("#car_type").val('');
                    $("#car_number").val('');
                    $("#rate").val('');
                    $("#liter").val('');
                    $("#date").val('');
                    $("#current_meter").val('');
                    $("#reason").val('');
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
        var date = $("#date_update").val();
        var rate = $("#rate_update").val();
        var liter = $("#liter_update").val();
        var current_meter = $("#current_meter_update").val();
        var reason = $("#reason_update").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-fuelings.update') }}",
           data:{"id":id,"car_number":car_number,"date":date,"liter":liter,"rate":rate,
           "current_meter":current_meter,"reason":reason,"_token": "{{ csrf_token() }}"},
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
      console.log(department_name);
      var car_type=$(btn).data('car_type');
      var car_number=$(btn).data('car_number');
      var car_id=$(btn).data('car_id');
      var date=$(btn).data('date');
      var liter=$(btn).data('liter');
      var rate=$(btn).data('rate');
      var current_meter=$(btn).data('current_meter');
      var reason=$(btn).data('reason');


      $("#id_update").val(id);
      $("#department_update").val(department_name);
      $("#car_type_update").val(car_type);
      $("#date_update").val(date);
      $("#rate_update").val(rate);
      $("#liter_update").val(liter);
      $("#current_meter_update").val(current_meter);
      $("#reason_update").val(reason);
      $("#reason_update").text(reason);
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

<script type="text/javascript">
    $(document).ready(function()
    {
    $(".status-select").change(function()
    {
    var status=$(this).val();
    var id = $(this).attr('dataId');
    $.ajax
    ({
    type: "GET",
    url: "{{ route('car-fuelings.change-status') }}",
    data: {"status": status,"id": id},
    success:function(data){
        if($.isEmptyObject(data.error)){
                alert(data.success);
                 location.reload();
        }else{
           printErrorMsg(data.error);
         }
        }
    });

    });

    });
    </script>





    </section>
@stop
