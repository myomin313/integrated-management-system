@extends('layouts.master')
@section('title','Maintanance & Repair')
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
                   <a href="{{ route('car-repair-and-maintanances.index')}}">Maintanance And Repair</a></li>

            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
               @can('export-kilo-for-maintanance')
             <a class="btn btn-default breadcrumb-btn openFilter3 float-sm-right" href="#" id="advance_search3">
              <i class="fas fa-search-minus"></i> Kilo For Maintanance</a>
               @endcan

             @can('export-repair-record')
              <a class="btn btn-default breadcrumb-btn openFilter2 float-sm-right" href="#" id="advance_search4">
                <i class="fas fa-search-minus"></i> Repair Record</a>
                @endcan
    
    
              @can('export-repair-record-for-by-car')
            <a class="btn btn-default breadcrumb-btn openFilter2 float-sm-right" href="#" id="advance_search2">
              <i class="fas fa-search-minus"></i> Repair Record (By Car)</a>
               @endcan

            <a class="btn btn-default breadcrumb-btn openFilter float-sm-right" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
            <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a>
           </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content filter-row3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <form action="{{  route('car-repair-and-maintances.exportforkmmaintanance') }}" method="get">
                  <div class="row">
                       @csrf
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="repair_date">Month</label>
                         <div class="input-group date" id="datetimepickerExcel3" data-target-input="nearest">
                        <input type="text" name="month" id="month_search" class="form-control datetimepicker-input"
                       data-target="#datetimepickerExcel3" value="{{ old('month') }}"  />
                       <div class="input-group-append" data-target="#datetimepickerExcel3" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Kilo For Maintanance </button>
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



     <section class="content filter-row2">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <form action="{{ route('car-repair-and-maintances.repairrecordbycar') }}" method="get">
                  <div class="row">

                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Car No</label>
                      <select class="form-control" name="car_id"  style="width: 100%;">
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}" {{ old("car_id") == $car->id  ? 'selected' : '' }}>{{ $car->car_number }}</option>
                        @endforeach
                      </select>
                      </div>
                    </div>

                      <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="repair_date">Start Date</label>
                         <div class="input-group date" id="datetimepickerExcel" data-target-input="nearest">
                        <input type="text" name="start_date"  class="form-control datetimepicker-input"
                       data-target="#datetimepickerExcel" value="{{ old('start_date') }}"  />
                       <div class="input-group-append" data-target="#datetimepickerExcel" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="repair_date">End Date</label>
                         <div class="input-group date" id="datetimepickerExcel2" data-target-input="nearest">
                        <input type="text" name="end_date"  class="form-control datetimepicker-input"
                       data-target="#datetimepickerExcel2" value="{{ old('end_date') }}"  />
                       <div class="input-group-append" data-target="#datetimepickerExcel2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Repair Record ( By Car )  </button>
                       </div>
                    </div>

                    <!-- <div class="col-sm-2">
                        <a class="btn btn-warning" href="{{ route('car-repair-and-maintances.exportforkmmaintanance') }}">
                            KM For Maintanance
                        </a>
                    </div> -->

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

     {{-- filter row 4 --}}
    <section class="content filter-row4">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <form action="{{ route('car-repair-and-maintances.exportforrepairrecord') }}" method="method">
                    @csrf
                    <div class="row">
                        <div class="col-sm-2">
                        <div class="form-group" style="margin-top: 8px;">
                          <label for="repair_date">Start Date</label>
                           <div class="input-group date" id="datetimepickerExcel4" data-target-input="nearest">
                          <input type="text" name="repair_start_date"  class="form-control datetimepicker-input"
                         data-target="#datetimepickerExcel4" value="{{ old('repair_start_date') }}"  />
                         <div class="input-group-append" data-target="#datetimepickerExcel4" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                         </div>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group" style="margin-top: 8px;">
                          <label for="repair_date">End Date</label>
                           <div class="input-group date" id="datetimepickerExcel5" data-target-input="nearest">
                          <input type="text" name="repair_end_date"  class="form-control datetimepicker-input"
                         data-target="#datetimepickerExcel5" value="{{ old('repair_end_date') }}"  />
                         <div class="input-group-append" data-target="#datetimepickerExcel5" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                         </div>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <!-- text input -->
                        <div class="form-group" style="margin-top: 39px;">
                          <button type="submit" class="btn btn-primary">Repair Record </button>
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
        {{-- filter row 4 --}}



    <section class="content filter-row">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <form action="{{ route('car-repair-and-maintanances.index') }}" method="get">
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
                    <div class="col-sm-1">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Kilo Meter</label>
                        <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" name="kilo_meter" value="{{ old('kilo_meter',$kilo_meter) }}" >
                      </div>
                    </div>
                    <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="repair_date">Repair Date</label>
                         <div class="input-group date" id="datetimepickerSearch" data-target-input="nearest">
                        <input type="text" name="repair_date"  class="form-control datetimepicker-input"
                       data-target="#datetimepickerSearch" value="{{ old('repair_date',$repair_date) }}"  />
                       <div class="input-group-append" data-target="#datetimepickerSearch" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>


                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('car-repair-and-maintanances.index') }}" class="btn btn-warning">Reset</a>
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
                <h3 class="card-title">Car Repair & Maintanance List</h3>
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
                         <!-- start -->
                      <th>No</th>
                      <th>Car</th>
                      <th>Kilo Meter</th>
                      <th>Driver</th>
                      <th>Repair Type</th>
                      <th>Amount</th>
                      <th>Repair Date</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                   @canany(['car-maintanance-repair-edit','car-maintanance-repair-delete'])
                      <th>Action</th>
                      @endcan
                    </tr>
                      <!-- <th>Action (modal)</th> -->
                  </thead>
                  <tbody>
                        <?php $n=1 ?>
                   @foreach($car_repair_and_maintanances as $car_repair)
                   <?php
                    $rep_date = str_replace('-"', '/', $car_repair->repair_date);
                    $car_repair_date = date("d/m/Y", strtotime($rep_date));
                   ?>
                  <tr>
                      <td>{{ $n }}</td>
                       <td>type   - {{ $car_repair->car_type }}<br>
                          no     - {{ $car_repair->car_number }}<br>
                          model  - {{ $car_repair->model_year }}
                     </td>
                      <td>
                          {{ number_format($car_repair->kilo_meter) }}
                      </td>
                      <td>
                           {{ $car_repair->driver_name }} <br>
                           {{ $car_repair->driver_type }}
                     </td>
                     <td>
                       {{ $car_repair->repair_type }}
                     </td>
                     <td>
                       {{  number_format($car_repair->amount)  }}
                      </td>
                      <td>
                       {{ $car_repair_date }}
                      </td>
                      <td>{{ $car_repair->updated_user }}</td>
                      <td>
                        {{ $car_repair->created_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                        {{ $car_repair->updated_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                      @can('car-maintanance-repair-edit')   
                      <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $car_repair->id }}" data-car_type="{{ $car_repair->car_type }}"
                       data-car_id="{{ $car_repair->car_id }}" data-kilo_meter="{{ $car_repair->kilo_meter }}" data-repair_date="{{ $car_repair_date }}"
                        data-amount="{{ $car_repair->amount }}" data-repair_detail="{{ $car_repair->repair_detail }}" data-repair_type="{{ $car_repair->repair_type }}" data-start_date="{{ $car_repair->start_date }}"
                       data-department_name="{{ $car_repair->docname }}" onclick="addValueForEdit(this)">
                        <i class="fas fa-edit text-info"></i>
                      </a>
                      @endcan
                      @can('car-maintanance-repair-delete')  
                      <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $car_repair->id }}" data-name="{{ $car_repair->car_number }}"
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
      <!-- start -->
        <div class="modal fade" id="modal-create">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Car Repair Registration</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
               @csrf
            <div class="modal-body">
                  <div class="row">
                    <div class="col-md-6 col-sm-6">
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
                    </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="car_type">Car Type</label>
                                <input type="text" class="form-control" id="car_type" name="car_type" disabled="disabled">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="department" >Department Name </label>
                             <input type="text" class="form-control" id="department" name="department" disabled="disabled">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="kilo_meter">Kilo Meter <span class="required text-danger">*</span></label>
                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="kilo_meter" name="kilo_meter" required>
                                <span class="text-danger error-text kilo_meter_error"></span>
                             </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="repair_date">Repair Date <span class="required text-danger">*</span></label>
                                <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                  <input type="text" name="repair_date" id="repair_date" class="form-control datetimepicker-input"
                                    data-target="#datetimepicker" />
                                  <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                  </div>
                                </div>
                                  <span class="text-danger error-text repair_date_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="amount">Amount <span class="required text-danger">*</span></label>
                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="amount" name="amount" required>
                               <span class="text-danger error-text amount_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <label>Repair Type <span class="required text-danger">*</span></label>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <input type="checkbox" name="repair_type" value="maintenance">
                                <label>Maintenance</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <input type="checkbox" name="repair_type" value="battery">
                                <label>Battery</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <input type="checkbox" name="repair_type" value="tyre">
                                <label>Tyre</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <input type="checkbox" name="repair_type" value="other">
                                <label>Other</label>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                          <span class="text-danger error-text repair_type_error"></span>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <label>Repair Detail</label>
                            <textarea class="form-control" name="repair_detail" id="repair_detail">
                            </textarea>
                        </div>



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
        <div class="modal-dialog modal-lg">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Car Repair Update</h4>
              <button type="button" class="close close-edit-modal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post">
            <div class="modal-body">
                 <input type="hidden" id="id_update" >
                <div class="row">
                   <div class="col-md-6 col-sm-6">
                       <div class="form-group">
                        <label for="car_no" >Car Number <span class="required text-danger">*</span></label>
                       <select class="form-control" name="car_number_update" id="car_number_update" style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number }}</option>
                        @endforeach
                        </select>
                        <span class="text-danger error-text edit_car_number_error"></span>
                       </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="car_type">Car Type </label>
                                <input type="text" class="form-control" id="car_type_update" name="car_type_update" disabled="disabled">
                                 <span class="text-danger error-text edit_car_type_error"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="department" >Department Name</label>
                             <input type="text" class="form-control" id="department_update" name="department_update" disabled="disabled">
                            <span class="text-danger error-text edit_department_error"></span>
                            </div>
                        </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="kilo_meter">Kilo Meter <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="kilo_meter_update" name="kilo_meter_update" required>
                        </div>
                         <span class="text-danger error-text edit_kilo_meter_error"></span>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="repair_date">Repair Date <span class="required text-danger">*</span></label>

                            <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                              <input type="text" name="repair_date_update" id="repair_date_update" class="form-control datetimepicker-input"
                                data-target="#datetimepicker3" />
                              <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                              </div>
                            </div>
                            <span class="text-danger error-text edit_repair_date_error"></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="amount">Amount <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="amount_update" name="amount_update" required>
                            <span class="text-danger error-text edit_amount_error"></span>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <label>Repair Type <span class="required text-danger">*</span></label>
                    </div>
                    <div class="col-md-12 col-sm-12" id="repair">

                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <input type="checkbox" class="repair_check"  value="maintenance" id="maintenance_update" name="repair_type_update" >
                            <label>Maintenance </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <input type="checkbox" class="repair_check"  value="battery" id="battery_update" name="repair_type_update">
                            <label>Battery </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <input type="checkbox" class="repair_check" value="tyre" id="tyre_update" name="repair_type_update">
                            <label>Tyre </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <input type="checkbox" class="repair_check"  value="other" id="other_update" name="repair_type_update">
                            <label>Other </label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <span class="text-danger error-text edit_repair_type_error"></span>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <label>Repair Detail</label>
                        <textarea class="form-control" name="repair_detail_update" id="repair_detail_update"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary close-edit" data-dismiss="modal">Close</button>
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
              <h4 class="modal-title">Delete Car Repair & Maintanance</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('car-repair-and-maintanances.delete') }}" method="post">
              @csrf
             <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <p>Are you sure want to delete "<strong></strong>" Repair Data?</p>
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
     $(".close-edit-modal").on('click', function(){
         $('.repair_check').prop('checked', false);
     });
     $(".close-edit").on('click', function(){
         $('.repair_check').prop('checked', false);
     });

   $(".btn-submit").click(function(e){
        e.preventDefault();
        var department = $("#department").val();
        var car_type = $("#car_type").val();
        var car_number = $("#car_number").val();
        var kilo_meter = $("#kilo_meter").val();
        var repair_date = $("#repair_date").val();
        var amount = $("#amount").val();
         var repair_type = [];
         $.each($("input[name='repair_type']:checked"), function(){
             repair_type.push($(this).val());
         });
        var repair_detail = $("#repair_detail").val();

        $.ajax({
           type:'POST',
           url:"{{ route('car-repair-and-maintanances.store') }}",
          data:{"department":department,"car_type":car_type,"car_number":car_number,
           "kilo_meter":kilo_meter,"repair_date":repair_date,"amount":amount,
           "repair_type":repair_type,"repair_detail":repair_detail,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
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
        var kilo_meter = $("#kilo_meter_update").val();
        var repair_date = $("#repair_date_update").val();
        var amount = $("#amount_update").val();
        var repair_type = [];
        $.each($("input[name='repair_type_update']:checked"), function(){
             repair_type.push($(this).val());
         });
        var repair_detail = $("#repair_detail_update").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-repair-and-maintanances.update') }}",
           data:{"id":id_update,"department":department,"car_type":car_type,"car_number":car_number,
           "kilo_meter":kilo_meter,"repair_date":repair_date,"amount":amount,
           "repair_type":repair_type,"repair_detail":repair_detail,"_token": "{{ csrf_token() }}"},
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
      var kilo_meter=$(btn).data('kilo_meter');
      var repair_date=$(btn).data('repair_date');
      var amount=$(btn).data('amount');
      var repair_type=$(btn).data('repair_type');
      var repair_detail=$(btn).data('repair_detail');

      console.log(repair_detail);

      $(".editModal #id_update").val(id);
      //premium_amount
      $(".editModal #kilo_meter_update").val(kilo_meter);
      $(".editModal #car_type_update").val(car_type);
      $(".editModal #repair_date_update").val(repair_date);
      $(".editModal #amount_update").val(amount);
      $(".editModal #repair_detail_update").text(repair_detail);
      $(".editModal #repair_detail_update").val(repair_detail);
      $(".editModal #department_update").val(department_name);
      // $("#currency_update").val(currency).change();
      $("#car_number_update").val(car_id).change();
      // $("#department_update").val(department_id).change();
        var parts = repair_type.split(",");
         for(var i=0;i<parts.length;i++) {
            $('#' + parts[i] + "_update").prop('checked', true)
        }
       // for (var i = 0; i < parts.length; i++) {
          // if(parts[i]== 'maintenance'){
          //      $("#maintenance_update").prop("checked", true);
          // }else{
          //     $("#maintenance_update").prop("checked", false);
          // }
          // if(parts[i]== 'battery'){
          //     $("#battery_update").prop("checked", true);
          // }else{
          //   $("#battery_update").prop("checked", false);
          // } if(parts[i]== 'tyre'){
          //   $("#tyre_update").prop("checked", true);
          // }else{
          //     $("#tyre_update").prop("checked", false);
          // } if(parts[i]== 'other'){
          //        $("#other_update").prop("checked", true);
          // }else{
          //     $("#other_update").prop("checked", false);
          // }
      // }
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
<!-- defaule month for kilo for maintenance   -->
<script>
  const monthControl = document.querySelector('#month_search');
const date= new Date();
const month=("0" + (date.getMonth() + 1)).slice(-2);
const year=date.getFullYear();

monthControl.value = `${month}/${year}`;

  </script>




    </section>
@stop
