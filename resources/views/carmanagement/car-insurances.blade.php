@extends('layouts.master')
@section('title','Car Insurance List')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">

      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Car Management</li>

              <li class="breadcrumb-item active">
                   <a href="{{ route('car-insurances.index')}}">Car Insurance List</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
             <a class="btn btn-default breadcrumb-btn openFilter float-sm-right" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
               @can('export-car-insurance')
              <a class="btn btn-default breadcrumb-btn openFilter2 float-sm-right" href="#" id="advance_search4">
                <i class="fas fa-search-minus"></i>  Export Car Insurance </a>
               @endcan
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
            <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a>
           </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

        {{-- filter row 4 --}}
        <section class="content filter-row4">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <form action="{{ route('car-insurances.export') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-2">
                            <div class="form-group" style="margin-top: 8px;">
                              <label for="repair_date">Start Date</label>
                               <div class="input-group date" id="datetimepickerExcel4" data-target-input="nearest">
                              <input type="text" name="start_date"  class="form-control datetimepicker-input"
                             data-target="#datetimepickerExcel4" value="{{ old('start_date') }}"  />
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
                              <input type="text" name="end_date"  class="form-control datetimepicker-input"
                             data-target="#datetimepickerExcel5" value="{{ old('end_date') }}"  />
                             <div class="input-group-append" data-target="#datetimepickerExcel5" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                              </div>
                             </div>
                            </div>
                          </div>

                          <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group" style="margin-top: 39px;">
                              <button type="submit" class="btn btn-primary"> Export Car Insurance </button>
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
                  <form action="{{ route('car-insurances.index')}}" method="post">
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
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Insurance No</label>
                        <input type="text" class="form-control"  name="insurance_no"  value="{{ old('insurance_no',$insurance_no) }}">
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 39px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('car-insurances.index') }}" class="btn btn-warning">Reset</a>
                      </div>
                    </div>

                    {{-- <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 39px;">
                       <a class="btn btn-warning" href="{{ route('car-insurances.export') }}">
                         Export Car Insurance
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
                      <th>Premium Amount</th>
                      <th>Company Name</th>
                      <th>Start Date</th>
                      <th>Due Date</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                      @canany(['car-insurance-edit','car-insurance-delete'])
                      <th>Action</th>
                      @endcan
                    </tr>
                      <!-- <th>Action (modal)</th> -->
                  </thead>
                  <tbody>
                        <?php $n=1; ?>
                   @foreach($car_insurances as $car_insurance)
                   <?php
                    $start = str_replace('-"', '/', $car_insurance->start_date);
                    $insurance_start_date = date("d/m/Y", strtotime($start));

                     $end_date = str_replace('-"', '/', $car_insurance->end_date);
                    $insurance_end_date = date("d/m/Y", strtotime($end_date));
                   ?>
                  <tr>
                      <td>{{ $n }}</td>
                       <td>type   - {{ $car_insurance->car_type }}<br>
                          no     - {{ $car_insurance->car_number }}<br>
                          model  - {{ $car_insurance->model_year }}
                     </td>
                      <td>
                          {{ $car_insurance->insurance_no }}
                      </td>
                      <td>
                          <a href="{{ url('car-management/insurance-management/insurance-amount-histories/'.$car_insurance->car_id) }}">
                           {{ number_format($car_insurance->premium_amount) }}  {{ $car_insurance->currency }}
                          </a>
                           <a data-toggle="modal" class="edit-modal" data-target="#modal-edit-premium"
                           data-car_id="{{ $car_insurance->car_id }}"  data-car_insurance_id="{{ $car_insurance->id }}"
                           onclick="addValueForPremiumUpdate(this)">
                             <i class="fas fa-edit text-primary"></i>
                           </a>
                    </td>
                    <td>
                       {{ $car_insurance->insurance_company }}
                    </td>
                      <td>{{ $insurance_start_date }}</td>
                      <td>{{ $insurance_end_date }}</td>
                      <td>{{ $car_insurance->updated_user }}</td>
                      <td>
                        {{ $car_insurance->created_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                        {{ $car_insurance->updated_at->format('d/m/Y g:i:s A') }}
                      </td>
                      <td>
                    @can('car-insurance-edit')
                      <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $car_insurance->id }}" data-car_type="{{ $car_insurance->car_type }}"
                       data-car_id="{{ $car_insurance->car_id }}" data-model_year="{{ $car_insurance->model_year }}" data-chasis_no="{{ $car_insurance->chasis_no }}"
                        data-insurance_no="{{ $car_insurance->insurance_no }}" data-insurance_company="{{ $car_insurance->insurance_company }}" data-premium_amount="{{ $car_insurance->premium_amount }}" data-start_date="{{ $insurance_start_date }}"
                       data-end_date="{{ $insurance_end_date }}" data-currency="{{ $car_insurance->currency }}" data-department_name="{{ $car_insurance->docname }}"   onclick="addValueForEdit(this)">
                        <i class="fas fa-edit text-info"></i>
                      </a>
                       @endcan
                       @can('car-insurance-delete')
                      <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $car_insurance->id }}" data-name="{{ $car_insurance->insurance_no }}"
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


      <!-- start modal for premium -->

       <div class="modal fade" id="modal-edit-premium">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Car Insurance Premium Update</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                  @csrf

                <input type="hidden" name="premium_car_id" id="premium_car_id">
                {{-- <input type="hidden" name="car_number" id="premium_car_number"> --}}

            <div class="modal-body">

                <div class="form-group">
                    <label for="insurance_no">Insurance No <span class="required text-danger">*</span></label>
                      <input type="text" class="form-control" id="premium_insurance_no" name="premium_insurance_no"
                        placeholder="enter insurance number">
                        <span class="text-danger error-text premium_insurance_no_error"></span>
                  </div>

                  <div class="form-group">
                    <label for="insurance_company">Insurance Company <span class="required text-danger">*</span></label>
                      <input type="text" class="form-control" id="premium_insurance_company" name="premium_insurance_company"
                        placeholder="enter insurance company">
                        <span class="text-danger error-text premium_insurance_company_error"></span>

                  </div>

              <div class="form-group">
                <label for="Premium_amount">Premium Amount <span class="required text-danger">*</span></label>
                    <div class="row">
                    <div class="col-sm-7">
                  <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="premium_amount_edit" name="premium_amount_edit"
                    placeholder="enter amount">
                    <span class="text-danger error-text premium_premium_amount_error"></span>
                    </div>
                    <div class="col-sm-5">
                    <select class="form-control select2bs4" name="currency_premium_edit" id="currency_premium_edit" style="width: 100%;">
                       <option value="MMK">MMK</option>
                       <option value="USD">USD</option>
                    </select>
                    <span class="text-danger error-text premium_currency_error"></span>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label for="start_date">Start Date <span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                    <input type="text" name="premium_start_date" id="premium_start_date" class="form-control datetimepicker-input"
                      data-target="#datetimepicker3" />
                    <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                </div>
                <span class="text-danger error-text premium_start_date_error"></span>
              </div>
              <div class="form-group">
                <label for="due_date">Due Date <span class="required text-danger">*</span></label>
                <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                  <input type="text" name="premium_due_date" id="premium_due_date" class="form-control datetimepicker-input"
                    data-target="#datetimepicker4" />
                  <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
                <span class="text-danger error-text premium_due_date_error"></span>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-success btn-premium-submit">Save</button>
            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <!-- end modal for premium -->
      <!-- /.container-fluid -->

       <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Car Insurance Registration</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                  @csrf
            <div class="modal-body">

              <div class="form-group">
                <label for="car_no" >Car Number <span class="required text-danger">*</span></label>
                 <select class="form-control" name="car_number" id="car_number" style="width: 100%;">
                        <option value="">- Select Car Number -</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->car_number }}</option>
                        @endforeach
                    </select>
                     <span class="text-danger error-text car_number_error"></span>
             </div>
             <div class="form-group">
                    <label for="department" >Department Name</label>
                     <input type="text" class="form-control" id="department" name="department" disabled="disabled">

              </div>
              <div class="form-group">
                    <label for="car_type">Car Type</label>
                      <input type="text" class="form-control" id="car_type" name="car_type" disabled="disabled">
              </div>
              <div class="form-group">
                <label for="insurance_no">Insurance No <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="insurance_no" name="insurance_no"
                    placeholder="enter insurance number">
                    <span class="text-danger error-text insurance_no_error"></span>
              </div>

              <div class="form-group">
                <label for="insurance_company">Insurance Company <span class="required text-danger">*</span></label>
                  <input type="text" class="form-control" id="insurance_company" name="insurance_company"
                    placeholder="enter insurance company">
                    <span class="text-danger error-text insurance_company_error"></span>

              </div>
              <div class="form-group">
                <label for="Premium_amount">Premium Amount <span class="required text-danger">*</span></label>
                    <div class="row">
                    <div class="col-sm-7">
                  <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="premium_amount" name="premium_amount"
                    placeholder="enter amount">
                    <span class="text-danger error-text premium_amount_error"></span>
                    </div>
                    <div class="col-sm-5">
                    <select class="form-control select2bs4" name="currency" id="currency" style="width: 100%;">
                       <option value="MMK">MMK</option>
                       <option value="USD">USD</option>
                    </select>
                    <span class="text-danger error-text currency_error"></span>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label for="start_date">Start Date <span class="required text-danger">*</span></label>
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
                <label for="due_date">Due Date <span class="required text-danger">*</span></label>
                <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                  <input type="text" name="due_date" id="due_date" class="form-control datetimepicker-input"
                    data-target="#datetimepicker2" />
                  <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
                <span class="text-danger error-text due_date_error"></span>
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

      <div class="modal fade editModal" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Car Insurance Update</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post">
                @csrf
                <input type="hidden" name="id_update" id="id_update">
            <div class="modal-body">

            <div class="form-group">
                <label for="car_no">Car Number <span class="required text-danger">*</span></label>
                 <select class="form-control" name="car_number_update" id="car_number_update" style="width: 100%;">
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
                <label for="car_type" >Car Type </label>
                    <input type="text" class="form-control" id="car_type_update" name="car_type_update" disabled="disabled">
            </div>
            <div class="form-group">
                <label for="insurance_no">Insurance No <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="insurance_no_update" name="insurance_no_update" placeholder="enter insurance number">
                   <span class="text-danger error-text edit_insurance_no_error"></span>
            </div>

            <div class="form-group">
                <label for="insurance_company_update" >Insurance Company <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="insurance_company_update" name="insurance_company_update" placeholder="enter insurance company">
                  <span class="text-danger error-text edit_insurance_company_error"></span>
            </div>
            <div class="form-group">
                <label for="premium_amount">Premium Amount <span class="required text-danger">*</span></label>
                    <div class="row">
                        <div class="col-sm-7">
                            <input type="text" class="form-control"  onkeypress="return isNumberKey(event)" id="premium_amount_update" name="premium_amount_update" placeholder="enter amount">
                             <span class="text-danger error-text edit_premium_amount_error"></span>
                        </div>
                        <div class="col-sm-5">
                            <select class="form-control select2bs4" name="currency_update" id="currency_update"
                                style="width: 100%;">
                                 <option value="USD">USD</option>
                                 <option value="MMK">MMK</option>
                            </select>
                            <span class="text-danger error-text edit_currency_error"></span>
                        </div>
                </div>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date <span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepickeredit" data-target-input="nearest">
                    <input type="text" name="start_date_update" id="start_date_update" class="form-control datetimepicker-input"
                      data-target="#datetimepickeredit" />
                    <div class="input-group-append" data-target="#datetimepickeredit" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>
                   <span class="text-danger error-text edit_start_date_error"></span>
            </div>
            <div class="form-group">
                <label for="due_date" >Due Date <span class="required text-danger">*</span></label>
                    <div class="input-group date" id="datetimepickeredit2" data-target-input="nearest">
                      <input type="text" name="due_date_update" id="due_date_update" class="form-control datetimepicker-input"
                        data-target="#datetimepickeredit2" />
                      <div class="input-group-append" data-target="#datetimepickeredit2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                      </div>
                </div>
                 <span class="text-danger error-text edit_due_date_error"></span>
            </div>
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

      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Car Insurance</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('car-insurances.delete') }}" method="post">
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
        var department = $("#department").val();
        var car_type = $("#car_type").val();
        var car_number = $("#car_number").val();
        var insurance_no = $("#insurance_no").val();
        var insurance_company = $("#insurance_company").val();
        var premium_amount = $("#premium_amount").val();
        var start_date = $("#start_date").val();
        var due_date = $("#due_date").val();
        var currency = $("#currency").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-insurances.store') }}",
           data:{"department":department,"car_type":car_type,"car_number":car_number,
           "insurance_no":insurance_no,"insurance_company":insurance_company,"premium_amount":premium_amount,
           "start_date":start_date,"due_date":due_date,"currency":currency,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#department").val('');
                    $("#car_type").val('');
                    $("#car_number").val('');
                    $("#insurance_no").val('');
                    $("#insurance_company").val('');
                    $("#premium_amount").val('');
                    $("#start_date").val('');
                    $("#end_date").val('');
                    $("#currancy").val('');
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    // create script end

    //create  premium script start
   $(".btn-premium-submit").click(function(e){
        e.preventDefault();
        var car_id = $("#premium_car_id").val();
        var car_number = $("#premium_car_number").val();
        var premium_insurance_no = $("#premium_insurance_no").val();
        var premium_insurance_company = $("#premium_insurance_company").val();
        var premium_amount_edit = $("#premium_amount_edit").val();
        var premium_start_date = $("#premium_start_date").val();
        var premium_due_date = $("#premium_due_date").val();
        var currency_premium_edit = $("#currency_premium_edit").val();
        $.ajax({
           type:'POST',
           url:"{{ route('car-insurances.premiun_amount_update') }}",
           data:{"insurance_company":premium_insurance_company,"insurance_no":premium_insurance_no,"car_id":car_id,"premium_amount":premium_amount_edit,"start_date":premium_start_date,"due_date":premium_due_date,
           "currency":currency_premium_edit,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#premium_insurance_no").val('');
                    $("#premium_insurance_company").val('');
                    $("#premium_amount_edit").val('');
                    $("#premium_start_date").val('');
                    $("#premium_due_date").val('');
                    $("#currency_premium_edit").val('');
                    location.reload();
                }else{
                    printPremiumErrorMsg(data.error);
                }
           }
        });
    });
    // create premium script end

    // create script end
    //
     $(".btn-update").click(function(e){
        e.preventDefault();
        var id_update = $("#id_update").val();
        var department = $("#department_update").val();
        var car_type = $("#car_type_update").val();
        var car_number = $("#car_number_update").val();
        var insurance_no = $("#insurance_no_update").val();
        var insurance_company = $("#insurance_company_update").val();
        var premium_amount = $("#premium_amount_update").val();
        var start_date = $("#start_date_update").val();
        var due_date = $("#due_date_update").val();
        var currency = $("#currency_update").val();

        $.ajax({
           type:'POST',
           url:"{{ route('car-insurances.update') }}",
             data:{"id":id_update,"department":department,"car_type":car_type,"car_number":car_number,
           "insurance_no":insurance_no,"insurance_company":insurance_company,"premium_amount":premium_amount,
           "start_date":start_date,"due_date":due_date,"currency":currency,"_token": "{{ csrf_token() }}"},
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
    function printPremiumErrorMsg (msg) {
           $.each( msg, function( key, value ) {
              $('.premium_'+key+'_error').text(value);
          });
    }

  //update script start

  function addValueForEdit(btn){

      var id=$(btn).data('id');
      var department_name=$(btn).data('department_name');
      var car_type=$(btn).data('car_type');
      var car_id=$(btn).data('car_id');
      var insurance_no=$(btn).data('insurance_no');
      var insurance_company=$(btn).data('insurance_company');
      var premium_amount=$(btn).data('premium_amount');
      var currency=$(btn).data('currency');
      var start_date=$(btn).data('start_date');
      var end_date=$(btn).data('end_date');

      $(".editModal #id_update").val(id);
      $(".editModal #premium_amount_update").val(premium_amount);
      $(".editModal #car_type_update").val(car_type);
      $(".editModal #insurance_no_update").val(insurance_no);
      $(".editModal #insurance_company_update").val(insurance_company);
      $(".editModal #start_date_update").val(start_date);
      $(".editModal #due_date_update").val(end_date);
      $(".editModal #department_update").val(department_name);
      $("#currency_update").val(currency).change();
      $("#car_number_update").val(car_id).change();

  }

  function addValueForPremiumUpdate(btn){

     var car_id=$(btn).data('car_id');
      var car_insurance_id=$(btn).data('car_insurance_id');
      $("#premium_car_id").val(car_id);
      $("#car_insurance_id").val(car_insurance_id);

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




    </section>
@stop
