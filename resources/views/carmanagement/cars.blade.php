@extends('layouts.master')
@section('title','Car Registration')
@section('content')
    <div class="content-header">

      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item active">Car Management</li>

               <li class="breadcrumb-item active">
                   <a href="{{ route('cars.index')}}">Car Registeration</a></li>
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
                <form action="{{ route('cars.index') }}" method="post">
                  @csrf
                  <div class="row">
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Car No</label>
                        <input type="text" class="form-control" id="search_car_number" name="search_car_number"  value="{{ old('search_car_number',$car_number) }}">
                      </div>
                    </div>
                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Department</label>
                        <select class="form-control select2bs4" name="search_department" id="search_department"
                          style="width: 100%;">
                          <option value="">- Select -</option>
                          @foreach($departments as $department)
                          <option value="{{ $department->id }}" {{ old("search_department", $depart) == $department->id  ? 'selected' : '' }}>{{ $department->short_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Driver</label>
                        <select class="form-control select2bs4" name="search_driver_name" id="search_driver_name"
                          style="width: 100%;">
                          <option value="">- Select -</option>
                          @foreach($users as $user)
                          <option value="{{ $user->id }}"  {{ old("search_driver_name", $driver_name) == $user->id  ? 'selected' : '' }}>{{ $user->employee_name}}</option>
                          @endforeach
                        </select>
                        <!-- <input type="text" class="form-control" id="driver_name" name="driver_name"> -->
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('cars.index') }}" class="btn btn-warning">Reset</a>
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
                <h3 class="card-title">Car Registration List</h3>
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
                      <th>ID</th>
                      <th>Car</th>
                      <th>Tyre Size</th>
                      <th>Department</th>
                      <th>Driver</th>
                      <th>Parking</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                   @canany(['car-registration-edit','car-registration-delete'])
                      <th>Action</th>
                      @endcan
                    </tr>
                  </thead>
                  <tbody>
                       <?php $n=1; ?>
                    @foreach($cars as $car)
                     <tr>
                      <td>{{ $n }}</td>
                      <td>type   - {{ $car->car_type }}<br>
                          no     - {{ $car->car_number }}<br>
                          model  - {{ $car->model_year }}<br>
                          chasis - {{ $car->chasis_no }}
                    </td>
                    <td>{{ $car->tire_size }}</td>
                      <td>

                         {{ $car->docname }} <br>
                         <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-main-user" data-id="{{ $car->id }}" data-main_user_id="{{ $car->main_user }}"
                        onclick="addValueForUpdateMainUser(this)">
                         {{ $car->main_user_name }}
                          </a>
                      </td>
                      <td>
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-driver" data-id="{{ $car->id }}" data-user_id="{{ $car->user_id }}"
                        onclick="addValueForUpdateDriver(this)">{{ $car->driver_name }}</a><br>
                         {{ $car->employee_type }} <br>

                    </td>
                      <td>{{ $car->parking }}</td>
                      <td>{{ $car->updated_user }}</td>
                      <td>
                        {{ $car->created_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                        {{ $car->updated_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                        <!-- <a href="../../pages/mastermanagement/department-edit.html">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp; -->
                        @can('car-registration-edit')  
                      <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $car->id }}" data-car_type="{{ $car->car_type }}"
                       data-car_number="{{ $car->car_number }}" data-model_year="{{ $car->model_year }}" data-chasis_no="{{ $car->chasis_no }}"
                        data-dept_id="{{ $car->dept_id }}" data-main_user="{{ $car->main_user }}" data-driver="{{ $car->user_id }}"
                       data-tire_size="{{ $car->tire_size }}" data-parking="{{ $car->parking }}"  onclick="addValueForEdit(this)">
                        <i class="fas fa-edit text-info"></i>
                      </a>
                       @endcan
                       @can('car-registration-delete')  
                      <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $car->id }}" data-name="{{ $car->car_number }}"
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
              <h4 class="modal-title">Car Registeration Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                 @csrf
            <div class="modal-body">
              <!--<div class="form-group">-->
              <!--      <label for="department_id">Department Name <span class="required text-danger">*</span></label>-->

              <!--          <select class="form-control select2bs4" name="department" id="department" style="width: 100%;">-->
              <!--           <option value="">- Select Department -</option>-->
              <!--            @foreach($departments as $department)-->
              <!--            <option value="{{ $department->id }}">{{ $department->name}}</option>-->
              <!--            @endforeach-->
              <!--          </select>-->
              <!--          <span class="text-danger error-text department_error"></span>-->
              <!--</div>-->
              <div class="form-group">
                    <label for="car_type">Car Type <span class="required text-danger">*</span></label>
                      <input type="text" class="form-control" id="car_type" name="car_type" placeholder="enter car type">
                      <span class="text-danger error-text car_type_error"></span>
              </div>
              <div class="form-group">
                <label for="car_no" >Car Number <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="car_number" name="car_number"
                    placeholder="enter car number">
                    <span class="text-danger error-text car_number_error"></span>
              </div>
              <div class="form-group">
                <label for="car_model" >Car Model <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="model_year" name="model_year"
                    placeholder="enter car model" onkeypress="return isNumberKey(event)">
                    <span class="text-danger error-text model_year_error"></span>
              </div>
              <div class="form-group">
                <label for="car_no" >Tyre Size</label>
                  <input type="text" class="form-control" id="tire_size" name="tire_size"
                    placeholder="enter Tyre Size">
              </div>
              <div class="form-group">
                <label for="chasis_no">Chasis No <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="chasis_no" name="chasis_no"
                    placeholder="enter chasis number">
                    <span class="text-danger error-text chasis_no_error"></span>
              </div>
              <div class="form-group">
                <label for="driver">Driver <span class="required text-danger">*</span></label>
                    <select class="form-control select2bs4" name="driver" id="driver" style="width: 100%;">
                          <option value="">- Select Driver -</option>
                          @foreach($users as $user)
                          <option value="{{ $user->id }}">{{ $user->employee_name}}</option>
                          @endforeach
                    </select>
                     <span class="text-danger error-text driver_error"></span>
              </div>
              <div class="form-group">
                <label for="parking">Parking <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="parking" name="parking"
                    placeholder="enter car parking">
                    <span class="text-danger error-text parking_error"></span>
              </div>
              <div class="form-group">
                <label for="main_user" >Main User <span class="required text-danger">*</span></label>
                  <select class="form-control select2bs4" name="main_user" id="main_user" style="width: 100%;">
                          <option value="">- Select Main User -</option>
                          @foreach($main_users as $main_user)
                          <option value="{{ $main_user->id }}">{{ $main_user->employee_name}}</option>
                          @endforeach
                    </select>
                     <span class="text-danger error-text main_user_error"></span>
              </div>
                <!-- start category -->
                <div class="form-group">
                  <label for="branch">Departments <span class="required text-danger">*</span></label>
                     @foreach($departments as $department) 
                      <div class="col-md-12 col-sm-12">                        
                        <input type="checkbox" name="department_id"  value="{{ old('department_id',$department->id)}}">
                        <label for="department_id">{{  $department->name }}  ({{ $department->short_name }})</label>
                      </div>                              
                      @endforeach
                      <span class="text-danger error-text department_error"></span>
                </div>
                <!-- end category -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success btn-submit">Add</button>
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
              <h4 class="modal-title">Edit Cars</h4>
              <button type="button" class="close close-edit-modal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post">
               @csrf
             <input type="hidden" name="id_update" id="id_update">
            <div class="modal-body">
              <!--<div class="form-group">-->
              <!--  <label for="department_id">Department Name <span class="required text-danger">*</span></label>-->
              <!--    <select class="form-control" name="department_update" id="department_update" style="width: 100%;">-->
              <!--      <option value="">- Select Department -</option>-->
              <!--            @foreach($departments as $department)-->
              <!--            <option value="{{ $department->id }}">{{ $department->name}}</option>-->
              <!--            @endforeach-->
              <!--    </select>-->
              <!--     <span class="text-danger error-text edit_department_error"></span>-->
              <!--</div>-->
              <div class="form-group">
                <label for="car_type" >Car Type <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="car_type_update" name="car_type_update" placeholder="enter car type">
                  <span class="text-danger error-text edit_car_type_error"></span>
                </div>
              <div class="form-group">
                <label for="car_no">Car Number <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="car_number_update" name="car_number_update" placeholder="enter car number">
                  <span class="text-danger error-text edit_car_number_error"></span>
              </div>
              <div class="form-group">
                <label for="car_model">Car Model <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="model_year_update" name="model_year_update" placeholder="enter car model"  onkeypress="return isNumberKey(event)">
                  <span class="text-danger error-text edit_model_year_error"></span>
               </div>
               <div class="form-group">
                <label for="car_no" >Tyre Size </label>
                  <input type="text" class="form-control" id="tire_size_update" name="tire_size_update"
                    placeholder="enter Tyre Size">
              </div>
              <div class="form-group">
                <label for="chasis_no">Chasis No <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="chasis_no_update" name="chasis_no_update" placeholder="enter chasis number">
                  <span class="text-danger error-text edit_chasis_no_error"></span>
              </div>
              <div class="form-group">
                <label for="driver">Driver <span class="required text-danger">*</span></label>
                    <select class="form-control select2bs4" name="driver_update" id="driver_update" style="width: 100%;">
                          <option value="">- Select Driver -</option>
                          @foreach($users as $user)
                          <option value="{{ $user->id }}">{{ $user->employee_name}}</option>
                          @endforeach
                    </select>
                    <span class="text-danger error-text edit_driver_error"></span>
              </div>
              <div class="form-group">
                <label for="car_parking">Car Parking <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="car_parking_update" name="car_parking_update" placeholder="enter car parking">
                  <span class="text-danger error-text edit_parking_error"></span>
              </div>
              
              <div class="form-group">
                <label for="main_user" >Main User <span class="required text-danger">*</span></label>
                  <select class="form-control select2bs4" name="main_user_update" id="main_user_update" style="width: 100%;">
                          <option value="">- Select Main User -</option>
                          @foreach($main_users as $main_user)
                          <option value="{{ $main_user->id }}">{{ $main_user->employee_name}}</option>
                          @endforeach
                    </select>
                     <span class="text-danger error-text edit_main_user_error"></span>
              </div>
              
              <!-- start category -->
                <div class="form-group">
                  <label for="branch">Departments <span class="required text-danger">*</span></label>
                     @foreach($departments as $department) 
                      <div class="col-md-12 col-sm-12">                        
                        <input type="checkbox" class="department_update" name="department_update" id="{{ $department->id }}_update" value="{{ old('department_id',$department->id)}}">
                        <label for="department_id">{{  $department->name }}  ({{ $department->short_name }})</label>
                      </div>                              
                      @endforeach
                      <span class="text-danger error-text department_error"></span>
                </div>
                <!-- end category -->
              
              
            </div>
            </form>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-success btn-update">Update</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- driver modal start -->
       <div class="modal fade" id="modal-driver">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Update Driver </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('cars.update-driver') }}"  method="POST">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="id_update_driver" id="id_update_driver" >
                <div class="form-group">
                <label for="driver">Driver <span class="required text-danger">*</span></label>
                    <select class="form-control" name="driver_name_update" id="driver_name_update" style="width: 100%;">
                         @foreach($users as $user)
                          <option value="{{ $user->id }}">{{ $user->employee_name}}</option>
                          @endforeach
                    </select>
                    <span class="text-danger error-text edit_driver_error"></span>
              </div>
            </div>

            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success btn-delete">Sure</button>
            </div>
               </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- driver modal end -->

      <!-- main user modal start -->
       <div class="modal fade" id="modal-main-user">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Update Main User </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('cars.update-main-user') }}"  method="POST">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="id_update_main_user" id="id_update_main_user" >
               <div class="form-group">
                <label for="main_user" >Main User <span class="required text-danger">*</span></label>
                  <select class="form-control" name="main_user_name_update" id="main_user_name_update" style="width: 100%;">
                          <option value="">- Select Main User -</option>
                          @foreach($main_users as $main_user)
                          <option value="{{ $main_user->id }}">{{ $main_user->employee_name}}</option>
                          @endforeach
                    </select>
                     <span class="text-danger error-text edit_main_user_error"></span>
              </div>
            </div>

            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success btn-delete">Sure</button>
            </div>
               </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- main user modal end -->


      <!-- delete start -->
       <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Car</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('cars.delete') }}"  method="POST">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id" >
                <p>Are you sure want to delete "<strong></strong>" Car ?</p>
            </div>

            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success btn-delete">Sure</button>
            </div>
               </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <!-- delete end -->




      <!-- create script start -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$(".close-edit-modal").on('click', function(){
         $('.department_update').prop('checked', false);
     });
     
//create script start
    $(".btn-submit").click(function(e){
        e.preventDefault();
        //var department = $("#department").val();
        var department = [];
             $.each($("input[name='department_id']:checked"), function(){
               department.push($(this).val());
            });
        var car_type = $("#car_type").val();
        var car_number = $("#car_number").val();
        var driver = $("#driver").val();
        var model_year = $("#model_year").val();
        var tire_size = $("#tire_size").val();
        var chasis_no = $("#chasis_no").val();
        var parking = $("#parking").val();
        var main_user = $("#main_user").val();
        $.ajax({
           type:'POST',
           url:"{{ route('cars.store') }}",
           data:{"department":department,"car_type":car_type,"car_number":car_number,
           "driver":driver,"model_year":model_year,"chasis_no":chasis_no,"parking":parking,
           "main_user":main_user,"tire_size":tire_size,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#department").val('');
                    $("#car_type").val('');
                    $("#car_number").val('');
                    $("#driver").val('');
                    $("#model_year").val('');
                    $("#tire_size").val('');
                    $("#chasis_no").val('');
                    $("#parking").val('');
                    $("#main_user").val('');
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    // create script end
    //  var atttable = $('#cars_record').DataTable({
    //       "paging": true,
    //       "lengthChange": false,
    //       "pageLength": 15,
    //       "searching": false,
    //       "ordering": true,
    //       "info": true,
    //       "autoWidth": false,
    //       "responsive": false,
    //   });
    //
     $(".btn-update").click(function(e){
        e.preventDefault();
        var id_update = $("#id_update").val();
        var department_update = $(".department_update").val();
        var car_type_update = $("#car_type_update").val();
        var car_number_update = $("#car_number_update").val();
        var tire_size_update = $("#tire_size_update").val();
        var driver_update = $("#driver_update").val();
        var model_year_update = $("#model_year_update").val();
        var chasis_no_update = $("#chasis_no_update").val();
        var parking_update = $("#car_parking_update").val();
        var main_user_update = $("#main_user_update").val();

        $.ajax({
           type:'POST',
           url:"{{ route('cars.update') }}",
           data:{"id":id_update,"department":department_update,"car_type":car_type_update,"car_number":car_number_update,
           "driver":driver_update,"model_year":model_year_update,"chasis_no":chasis_no_update,"parking": parking_update,
           "tire_size":tire_size_update,"main_user":main_user_update,"_token": "{{ csrf_token() }}"},
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
      var dept_id=$(btn).data('dept_id');
      var car_type=$(btn).data('car_type');
      var car_number=$(btn).data('car_number');
      var model_year=$(btn).data('model_year');
      var chasis_no=$(btn).data('chasis_no');
      var driver=$(btn).data('driver');
      var parking=$(btn).data('parking');
      var main_user=$(btn).data('main_user');
      var tire_size=$(btn).data('tire_size');
      
      
     

      $(".editModal #id_update").val(id);
      $(".editModal #car_type_update").val(car_type);
      $(".editModal #car_number_update").val(car_number);
      $(".editModal #model_year_update").val(model_year);
      $(".editModal #chasis_no_update").val(chasis_no);
      $(".editModal #car_parking_update").val(parking);
      $(".editModal #tire_size_update").val(tire_size);
      $("#driver_update").val(driver).change();
      $("#main_user_update").val(main_user).change();
      
      var parts = typeof dept_id === 'string' ? dept_id.split(',') : [dept_id];
      
      
      console.log(parts);
      //var parts = dept_id.split(",");
      for(var i=0;i<parts.length;i++) {
         $('#' + parts[i] + "_update").prop('checked', true)
      }
      
     
     

  }
  //update driver
   function addValueForUpdateDriver(btn){
     var id=$(btn).data('id');
     $("#id_update_driver").val(id);
      var driver_id=$(btn).data('user_id');
     $("#driver_name_update").val(driver_id).change();
   }
   //update main user
   function addValueForUpdateMainUser(btn){
     var id=$(btn).data('id');
     $("#id_update_main_user").val(id);
      var main_user_id=$(btn).data('main_user_id');
     $("#main_user_name_update").val(main_user_id).change();
   }
   //update status
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

</script>
<script type="text/javascript">
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>




    </section>
@stop
