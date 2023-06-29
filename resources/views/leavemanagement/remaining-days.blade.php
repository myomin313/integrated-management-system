@extends('layouts.master')
@section('title','List Of Yearly Leave')
@section('content')
<!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item">Attandance Management</li>
                                <li class="breadcrumb-item active">List Of Yearly Leave</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            @can('export-list-of-yearly-leave')
                            <a class="btn btn-success breadcrumb-btn float-sm-right openFilter2" href="#"
                                id="advance_search2"><i class="fas fa-search-minus"></i> Leave Excel</a>
                            @endcan
                                
                            <a class="btn btn-success breadcrumb-btn float-sm-right openFilter" href="#"
                                id="advance_search"><i class="fas fa-search-minus"></i> Advanced Search</a>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>


             

             <!-- Main content -->
             
               @can('export-list-of-yearly-leave')
            <section class="content filter-row2">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <form action="{{ route('leave-request.export') }}" method="post">
                                        @csrf
                                        <div class="row">
                                           
                      <div class="col-sm-2">
                      <div class="form-group" >
                        <label for="repair_date">Year</label>
                         <div class="input-group date" id="datetimepickerExcel" data-target-input="nearest">
                        <input type="text" name="from_excel"  class="form-control datetimepicker-input"
                       data-target="#datetimepickerExcel" value="{{ old('from_excel') }}"  />
                       <div class="input-group-append" data-target="#datetimepickerExcel" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>
                       <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="repair_date"></label>
                         <div class="input-group date" id="datetimepickerExcel2" data-target-input="nearest">
                        <input type="text" name="to_excel"  class="form-control datetimepicker-input"
                       data-target="#datetimepickerExcel2" value="{{ old('to_excel') }}"  />
                       <div class="input-group-append" data-target="#datetimepickerExcel2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>


                                            <div class="col-sm-2">
                                                <!-- text input -->
                                                <div class="form-group" style="margin-top: 8px;">
                                                    <label></label>
                                                    <select class="form-control select2bs4" name="search_employee_type"
                                                        id="search_employee_type" style="width: 100%;">
                                                        <option value="1">NS</option>
                                                        <option value="0">RS</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Employee Name</label>
                                                    <select class="form-control select2bs4" name="search_employee_name"
                                                        id="search_export_employee_name" style="width: 100%;">
                                                        <option value="">- Select -</option>
                                                        @foreach($all_users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->employee_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <!-- text input -->
                                                <div class="form-group" style="margin-top: 25px;">
                                                    <button type="submit" class="btn btn-primary">Export</button>
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
            <!-- /.content -->
            @endcan



             <!-- Main content -->
            <section class="content filter-row">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <form action="{{ route('leave-request.remaining-days') }}" method="post">
                                        @csrf
                                        <div class="row">
                                           
                      <div class="col-sm-2">
                      <div class="form-group" >
                        <label for="repair_date">Year</label>
                         <div class="input-group date" id="leavestartyear" data-target-input="nearest">
                        <input type="text" name="from_search"  class="form-control datetimepicker-input"
                       data-target="#leavestartyear" value="{{ old('from_search') }}"  />
                       <div class="input-group-append" data-target="#leavestartyear" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>
                       <span style="margin-top: 39px;">~</span>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label for="repair_date"></label>
                         <div class="input-group date" id="leaveendyear" data-target-input="nearest">
                        <input type="text" name="to_search"  class="form-control datetimepicker-input"
                       data-target="#leaveendyear" value="{{ old('to_search') }}"  />
                       <div class="input-group-append" data-target="#leaveendyear" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                       </div>
                      </div>
                    </div>


                                            <div class="col-sm-2">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select class="form-control select2bs4" name="search_department"
                                                        id="search_department" style="width: 100%;">
                                                        <option value="">- Select -</option>
                                                        @foreach($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->short_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                             <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Employee Name</label>
                                                    <select class="form-control select2bs4" name="search_employee_name"
                                                        id="search_employee_name" style="width: 100%;">
                                                        <option value="">- Select -</option>
                                                        @foreach($all_users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->employee_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-sm-2">
                                                <!-- text input -->
                                                <div class="form-group" style="margin-top: 25px;">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                    <a href="{{ route('leave-request.remaining-days') }}" class="btn btn-warning">Reset</a>
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
            <!-- /.content -->

              <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h3 class="card-title">Remaining Leave Days</h3>
                                        </div>
                                        <!-- <div class="col-sm-6">
                                            <button class="btn btn-default float-right">Export</button>
                                        </div> -->
                                    </div>


                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-hover" id="leave_record">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" class="text-center">Employee Name</th>
                                                <th colspan="2" class="text-center">Casual</th>
                                                <th colspan="2" class="text-center">Earned</th>
                                                 <!-- start -->
                                                 <th colspan="2" class="text-center">Refresh</th>
                                                <!-- end -->
                                                <th colspan="2" class="text-center">Medical</th>
                                                <th colspan="2" class="text-center">Maternity</th>
                                                <th colspan="2" class="text-center">Paternity</th>
                                                <th colspan="2" class="text-center">Sick</th>
                                                <th colspan="2" class="text-center">Without Pay Leave</th>
                                                <th colspan="2" class="text-center">Contgratulatory</th>
                                                <th colspan="2" class="text-center">Condolence</th>
                                               
                                            </tr>
                                            <tr>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                           @foreach($users as $user)

                                            @if($user->check_ns_rs == 1)
                                            <tr>
                                               <td><span class="text-primary">
                                                    {{ $user->employee_name }}                                                   
                                                </span><br>
                                                    {{ $user->docname }}
                                                </td>
                                               
                                                <td>{{ $user->casualleavedays }}</td>
                                                <td>{{ $casualleave->leave_day  -  $user->casualleavedays }}</td>
                                                <td>{{ $user->earnedleavedays }}</td>
                                                <td>{{ $earnedleave->leave_day - $user->earnedleavedays }}</td>
                                                <td>0</td>
                                                <td>0</td>                                                
                                                <td>{{ $user->medicalleavedays  }}</td>
                                                <td>{{ $medicalleave->leave_day - $user->medicalleavedays }}</td>
                                                <td>{{ $user->maternityleavedays }}</td>
                                                <td>{{$maternityleave->leave_day - $user->maternityleavedays }}</td>
                                                <td>{{ $user->paternityleavedays }}</td>
                                                <td>{{  $paternityleave->leave_day - $user->paternityleavedays }}</td>
                                                <td>{{ $user->longtermsickleavedays  }}</td>
                                                <td>{{ $longtermsickleave->leave_day - $user->longtermsickleavedays }}</td>
                                                <td>0</td>
                                                <td>{{ $user->unpaidleavedays }}</td>
                                                <td>{{ $user->congratulatyleavedays }}</td>
                                                <td>{{ $congratulatyleave->leave_day - $user->congratulatyleavedays }}</td>
                                                <td>{{ $user->condolenceleavedays }}</td>
                                                <td>{{ $condolenceleave->leave_day - $user->condolenceleavedays }}</td>
                                                
                                            </tr>
                                            @elseif($user->check_ns_rs == 0)

                                            <tr>
                                               <td><span class="text-primary">
                                                    {{ $user->employee_name }}                                                   
                                                </span><br>
                                                    {{ $user->docname }}
                                                </td>
                                               
                                                <td>0</td>
                                                <td>0</td>
                                                <td>{{ $user->earnedleavedays }}</td>
                                                <td>{{ $user->earnedtotal - $user->earnedleavedays }}</td>
                                                <td>{{ $user->refreshleavedays }}</td>
                                                <td>{{ $user->refreshtotal - $user->refreshleavedays }}</td>                                                
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                
                                            </tr>

                                            @endif
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
  
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script  type="text/javascript">
               
                    $(function(){
                 $('#leavestartyear').datetimepicker({
                      format: 'YYYY'
                    });
                  $('#leaveendyear').datetimepicker({
                    format: 'YYYY'
                 });
                   
                //   start table records
    //               var change_table = $('#yearly_record').DataTable({
    //       "paging": true,
    //       "lengthChange": false,
    //       "pageLength": 10,
    //       "searching": false,
    //       "ordering": true,
    //       "info": true,
    //       "autoWidth": false,
    //       "responsive": false,
    //       "createdRow": function( row, data, dataIndex ) {
    //           $(row).attr('id', 'row'+dataIndex);
    //       },
    //       "columnDefs": [
    //         {
    //           'targets': 1,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'user_name'+row); 
    //           }
    //         },
    //         {
    //           'targets': 2,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'branch'+row); 
    //           }
    //         },
    //         {
    //           'targets': 3,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'current_date'+row); 
    //           }
    //         },
    //         {
    //           'targets': 4,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'change_time'+row); 
    //           }
    //         },
    //         {
    //           'targets': 5,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'reason'+row); 
    //           }
    //         },
    //         {
    //           'targets': 6,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'status'+row);
    //               $(td).addClass('text-primary');
    //           }
    //         },
    //         {
    //           'targets': 7,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'requested_date'+row);
    //           }
    //         },
    //         {
    //           'targets': 8,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'status_change_by'+row);
    //           }
    //         },
    //         {
    //           'targets': 9,
    //           'createdCell':  function (td, cellData, rowData, row, col) {
    //               $(td).attr('id', 'status_change_date'+row);
    //           }
    //         }
    //       ]
    //   });
            });
                </script>




    </section>
@stop