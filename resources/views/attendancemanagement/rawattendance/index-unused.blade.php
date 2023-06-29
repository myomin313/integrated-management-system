@extends('layouts.master')
@section('title','Raw Attendance Management')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Attendance Management</li>
              <li class="breadcrumb-item active"><a href="{{url('attendance-management/raw-attendance/list')}}">Raw Attendance</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            
            <a class="btn btn-success breadcrumb-btn" href="#" data-toggle="modal" data-target="#modal-create">
              <i class="fas fa-plus"></i> Add New</a>
            <a class="btn btn-primary breadcrumb-btn" href="{{url('attendance-management/raw-attendance/detail')}}">
              <i class="fas fa-arrows-alt"></i> Detail</a>
            <a class="btn btn-default breadcrumb-btn openFilter" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
                        
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content filter-row">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            @if(count($errors)>0)
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>Whoops!</strong> There were some problems with your input.<br><br>
                  <ul>
                    @foreach($errors->all() as $error)
                      <li>{{$error}}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            @endif
            
            <div class="col-md-12 p-0" id="alert-section" style="display: none;">
                <div class="alert alert-dismissible " role="alert" style="font-size: 12px" id="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong></strong>
                </div>
            </div>
            
            @if(session('success_delete'))
              <div class="col-md-12 p-0">
                <div class="alert alert-danger alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_delete')}}</strong>
                </div>
              </div>
            @endif
            <div class="card">
              <div class="card-header">
                <form action="{{url('attendance-management/raw-attendance/list')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('d/m/Y');
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                          
                  @endphp
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Employee Name</label>
                        <select class="form-control select2bs4" name="employee" id="employee" style="width: 100%;">
                          <option selected="selected" value="all">- Employee Name -</option>
                          @foreach($profiles as $key=>$value)
                            <option value="{{$value->pro_UserID}}" {{$value->pro_UserID==$employee?'selected':''}}>{{$value->pro_UserName}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="dd/mm/YYYY" value="{{isset($from_date)?$from_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>To Date</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                          <input type="text" name="to_date" id="to_date" required placeholder="dd/mm/YYYY" value="{{isset($to_date)?$to_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('attendance-management/raw-attendance/list')}}" class="btn btn-warning">Reset</a>
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
                <div class="row">
                  <div class="col-sm-8">
                    <h3 class="card-title">Raw Daily Attandance</h3>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="attendance_record" class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Employee Id</th>
                      <th>Employee Name</th>
                      <th>Device</th>
                      <th>Branch</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Reason</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $latest_index = 'null';
                    @endphp
                    @foreach($raw_attendances as $key=>$value)                       
                      <tr id="row{{$key}}">
                        <td><span id='snum{{$key}}'>{{$key+1}}</span></td>
                        <td id="user_id{{$key}}">{{$value->att_UserID}}</td>
                        <td id="user_name{{$key}}">{{getProfileName($value->att_UserID)}}</td>
                        @if(in_array($value->att_ip, getFingerPrintIP()))
                        <td>Finger Print</td>
                        @else
                        <td>Mobile</td>
                        @endif
                        <td id="branch{{$key}}">{{getBranchField($value->branch,'name')}}</td>
                        <td id="date{{$key}}">{{siteformat_date($value->att_Date)}}</td>
                        <td id="time{{$key}}">{{siteformat_time($value->att_Date)}}</td>
                        <td id="reason{{$key}}">{{$value->reason}}</td>
                        <td id="action{{$key}}">
                          <a href="#" id="editModal{{$key}}" data-toggle="modal" class="update-modal" data-target="#modal-edit" data-id="{{$value->att_id}}" data-userid="{{$value->att_UserID}}" data-branch="{{$value->branch}}" data-date="{{siteformat_date($value->att_Date)}}" data-time="{{siteformat_time24($value->att_Date)}}" data-reason="{{$value->reason}}" data-index="{{$key}}" onclick="addValueForEdit(this)">
                            <i class="fas fa-edit text-primary"></i>
                          </a>&nbsp;
                          <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{$value->att_id}}" data-name="{{getProfileName($value->att_UserID)}}" data-date="{{siteformat_date($value->att_Date)}}" data-time="{{siteformat_time($value->att_Date)}}"  data-index="{{$key}}" onclick="addValueForDelete(this)">
                            <i class="fas fa-trash text-danger"></i>
                          </a>
                        </td>
                      </tr>
                      @php
                        $latest_index = $key;
                      @endphp
                    @endforeach
                  </tbody>
                </table>

              </div>
              <input type="hidden" name="latest_index" id="latest_index" value="{{$latest_index}}">
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
            
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->

      @include('attendancemanagement.rawattendance.create')

      @include('attendancemanagement.rawattendance.edit')

      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Attandance</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('attendance-management/raw-attendance/delete')}}" method="post" id="delete_form">
              <div class="modal-body">
                @csrf
                <input type="hidden" name="id" id="del_id">
                <input type="hidden" name="index" id="del_index">
                <p>Are you sure want to delete "<strong id="first"></strong>" attandance for date <strong id="second"></strong> and time <strong id="third"></strong>?</p>
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
      <!-- /.modal -->

    </section>
    <!-- /.content -->

@stop
@section('script')
<script>
    $(function () {
      
      $('#datetimepicker').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker1').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker2').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker3').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('.select2').select2();
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });
      
      var atttable = $('#attendance_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": false,
          "createdRow": function( row, data, dataIndex ) {
              check();
              $(row).attr('id', 'row'+dataIndex);
          },
          "columnDefs": [
            {
              'targets': 1,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'user_id'+row); 
              }
            },
            {
              'targets': 2,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'user_name'+row); 
              }
            },
            {
              'targets': 4,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'branch'+row); 
              }
            },
            {
              'targets': 5,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'date'+row); 
              }
            },
            {
              'targets': 6,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'time'+row); 
              }
            },
            {
              'targets': 7,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'reason'+row);
              }
            },
            {
              'targets': 8,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'action'+row);
                  $(td).find('a:eq(0)').attr('id', 'editModal'+row);
              }
            }
          ]
      });
    

      $(document).on('click', '#advance_search', function () {
        var className = $('#advance_search').attr('class');
        var lastClass = $('#advance_search').attr('class').split(' ').pop();
        console.log('class name ' + className);
        console.log('lastClass ' + lastClass);
        if (lastClass == 'closeFilter') {
          $('#advance_search').removeClass('closeFilter');
          $('#advance_search').addClass('openFilter');
          $('.filter-row').removeClass('hide-view');
          $('#advance_search .fas').removeClass('fa-search-plus');
          $('#advance_search .fas').addClass('fa-search-minus');
        }
        else {
          $('#advance_search').removeClass('openFilter');
          $('#advance_search').addClass('closeFilter');
          $('.filter-row').addClass('hide-view');
          $('#advance_search .fas').removeClass('fa-search-minus');
          
          $('#advance_search .fas').addClass('fa-search-plus');
        }

      });   
        
      var frm = $('#create_form');
      frm.submit(function (e) {
        e.preventDefault();               

        $.ajax({
          type: frm.attr('method'),
          url: "{{ url('attendance-management/raw-attendance/store') }}",
          data: frm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {
            $('#modal-create').modal('hide');
            var index = $('#latest_index').val();
            if(index=='null')
              index = 0;
            else
              index = Number(index)+1;

            var num = index + 1;
            var device = "Mobile";
            console.log('att_id = '+data.att_id);
            var num_column = '<span id="snum'++'">{{$key+1}}</span>'
            var action_value = '<a href="#" data-toggle="modal" class="update-modal" data-target="#modal-edit" data-id="'+data.att_id+'" data-userid="'+data.user_id+'" data-branch="'+data.branch_id+'" data-date="'+data.date+'" data-time="'+data.time24+'" data-reason="'+data.reason+'" data-index="'+index+'" onclick="addValueForEdit(this)"><i class="fas fa-edit text-primary"></i></a> <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="'+data.att_id+'" data-name="'+data.user_name+'" data-date="'+data.date+'" data-time="'+data.time+'" data-index="'+index+'" onclick="addValueForDelete(this)"><i class="fas fa-trash text-danger"></i></a>';


            atttable.row.add( [
                  num,
                  data.user_id,
                  data.user_name,
                  device,
                  data.branch_name,
                  data.date,
                  data.time,
                  data.reason,
                  action_value
              ] ).draw();

            $('#latest_index').val(index);

            $('#alert strong').html('Successfully added the new record');
            $('#alert').removeClass('alert-success');
            $('#alert').removeClass('alert-danger');
            $('#alert').addClass('alert-success');
            $('#alert-section').show();
                    
          },
          error: function (data) {
            console.log('An error occurred.');
            console.log(data);
          },
        });
      });

      var editfrm = $('#edit_form');
      editfrm.submit(function (e) {
        e.preventDefault();               

        $.ajax({
          type: editfrm.attr('method'),
          url: "{{ url('attendance-management/raw-attendance/update') }}",
          data: editfrm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {
            console.log(data);
            $('#modal-edit').modal('hide');
            var index = data.index;
            $('#user_id'+index).html(data.user_id);
            $('#user_name'+index).html(data.user_name);
            $('#branch'+index).html(data.branch_name);
            $('#date'+index).html(data.date);
            $('#time'+index).html(data.time);
            $('#reason'+index).html(data.reason);

            // $('#editModal'+index).removeAttr('data-userid');
            // $('#editModal'+index).removeAttr('data-branch');
            // $('#editModal'+index).removeAttr('data-date');
            // $('#editModal'+index).removeAttr('data-time');
            // $('#editModal'+index).removeAttr('data-reason');
            // $('#editModal'+index).removeData('userid');
            // $('#editModal'+index).removeData('branch');
            // $('#editModal'+index).removeData('date');
            // $('#editModal'+index).removeData('time');
            // $('#editModal'+index).removeData('reason');

            $('#editModal'+index).data('userid',data.user_id);
            $('#editModal'+index).data('branch',data.branch_id);
            $('#editModal'+index).data('date',data.date);
            $('#editModal'+index).data('time',data.time24);
            $('#editModal'+index).data('reason',data.reason);

            $('#alert strong').html('Successfully update the record');
            $('#alert').removeClass('alert-success');
            $('#alert').removeClass('alert-danger');
            $('#alert').addClass('alert-success');
            $('#alert-section').show();
                    
          },
          error: function (data) {
            console.log('An error occurred.');
            console.log(data);
          },
        });
      });

      var delfrm = $('#delete_form');
      delfrm.submit(function (e) {
        e.preventDefault();               

        $.ajax({
          type: delfrm.attr('method'),
          url: "{{ url('attendance-management/raw-attendance/delete') }}",
          data: delfrm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {
            console.log(data);
            $('#modal-delete').modal('hide');
            var index = data.index;
            atttable.rows( '#row'+index ).remove().draw();

            $('#alert strong').html('Successfully delete the record');
            $('#alert').removeClass('alert-success');
            $('#alert').removeClass('alert-danger');
            $('#alert').addClass('alert-danger');
            $('#alert-section').show();
                    
          },
          error: function (data) {
            console.log('An error occurred.');
            console.log(data);
          },
        });
      });

    });
    function addValueForEdit(btn){
    
      var id=$(btn).data('id');
      var userid=$(btn).data('userid');
      var branch=$(btn).data('branch');
      var date=$(btn).data('date');
      var time=$(btn).data('time');
      var editreason=$(btn).data('reason');
      var index=$(btn).data('index');
      console.log('index = '+index);
      $("#modal-edit #id").val(id);
      $("#modal-edit #index").val(index);
      $("#modal-edit select#edit_att_UserID").val(userid).change();
      $("#modal-edit select#edit_branch").val(branch).change();
      
      $("#modal-edit #date").val(date);
      $("#modal-edit #time").val(time);
      $("#modal-edit #reason").val(editreason);
    }

    function addValueForDelete(btn){
    
      var id=$(btn).data('id');
      var name=$(btn).data('name');
      var date=$(btn).data('date');
      var time=$(btn).data('time');
      var index=$(btn).data('index');
      $(".deleteModal #del_id").val(id);
      $(".deleteModal #del_index").val(index);
      $(".deleteModal strong#first").html(name);
      $(".deleteModal strong#second").html(date);
      $(".deleteModal strong#third").html(time);
    }

    

  </script>
@stop