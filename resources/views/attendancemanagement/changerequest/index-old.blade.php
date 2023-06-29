@extends('layouts.master')
@section('title','Change Request')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Change Request</li>
              <li class="breadcrumb-item active"><a href="{{url('attendance-management/change-request/list')}}">List</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            
            <a class="btn btn-success breadcrumb-btn" href="#" data-toggle="modal" data-target="#modal-create">
              <i class="fas fa-plus"></i> Add New</a>
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
                <form action="{{url('attendance-management/change-request/list')}}" method="get">
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
                          @foreach($employees as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$employee?'selected':''}}>{{$value->employee_name}}</option>    
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

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('attendance-management/change-request/list')}}" class="btn btn-warning">Reset</a>
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
                    <h3 class="card-title">Change Request List</h3>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover" id="change_record">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Employee Name</th>
                      <th>Branch</th>
                      <th>Current Date</th>
                      <th>Changing Date</th>
                      <th>Changing Time</th>
                      <th>Reason</th>
                      <th>Status</th>
                      <th>Requested Date</th>
                      <th>Approved By</th>
                      <th>Approved Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $latest_index = 'null';
                    @endphp
                    @foreach($changes as $key=>$value)                       
                      <tr id="row{{$key}}">
                        <td>{{$key+1}}</td>
                        <td id="user_name{{$key}}">{{getUserFieldWithId($value->user_id,'employee_name')}}</td>
                        <td id="branch{{$key}}">{{getBranchField(getUserFieldWithId($value->user_id,'branch_id'),'name')}}</td>
                        <td id="current_date{{$key}}">{{siteformat_date($value->actual_date)}}</td>
                        <td id="changing_date{{$key}}">{{siteformat_date($value->changing_date)}}</td>
                        <td id="change_time{{$key}}">{{siteformat_time24($value->changing_start_time)}} - {{siteformat_time24($value->changing_end_time)}}</td>
                        <td id="reason{{$key}}">{{$value->reason_of_correction}}</td>
                        @if($value->status==0)
                          @php $status = 'Pending';$color = "text-primary"; @endphp
                        @elseif($value->status==1)
                          @php $status = 'Accept';$color = "text-success"; @endphp
                        @else
                          @php $status = 'Reject';$color = "text-danger"; @endphp
                        @endif
                        <td id="status{{$key}}" class="{{$color}}">
                          <strong>{{$status}}</strong>
                          @if($status=="Pending")
                          <a href="#" id="statusModal{{$key}}" style="font-size:11px;padding:1px" data-toggle="modal" class="update-modal btn btn-info" data-target="#modal-status" data-id="{{$value->id}}" data-username="{{getUserFieldWithId($value->user_id,'employee_name')}}" data-index="{{$key}}" onclick="addValueForStatus({{$key}})">
                              Change Status
                          </a>&nbsp;
                          @endif
                        </td>
                        <td id="requested_date{{$key}}">{{siteformat_date($value->requested_date)}}</td>
                        <td id="status_change_by{{$key}}">{{$value->status_change_by?getUserFieldWithId($value->status_change_by,'employee_name'):''}}</td>
                        <td id="status_change_date{{$key}}">{{$value->status_change_date?siteformat_date($value->status_change_date):''}}</td>
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
      @include('attendancemanagement.changerequest.create')
      <div class="modal fade statusModal" id="modal-status">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Change Status</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('attendance-management/change-request/change-status')}}" class="prevent-multiple-submit-modal" method="post">
              <div class="modal-body">
                @csrf
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="index" id="index">
                <input type="hidden" name="from_date" id="from_date" value="{{isset($from_date)?$from_date:$today_date}}">
                <input type="hidden" name="to_date" id="to_date" value="{{isset($to_date)?$to_date:$today_date}}">
                <input type="hidden" name="employee" id="employee" value="{{isset($employee)?$employee:'all'}}">
                <p>Please click the "Accept" button for <strong></strong> if the information is correct. Otherwise click the "Reject" button.</p>
              </div>
                            
              <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-danger" id="reject" name="reject">Reject</button>
                <button type="submit" class="btn btn-success" id="accept" name="accept">Accept</button>
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

      //Timepicker
      $('#timepicker').datetimepicker({
        format: 'LT'
      });
      $('#timepicker1').datetimepicker({
        format: 'LT'
      });
      
      var change_table = $('#change_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": false,
          "createdRow": function( row, data, dataIndex ) {
              $(row).attr('id', 'row'+dataIndex);
          },
          "columnDefs": [
            {
              'targets': 1,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'user_name'+row); 
              }
            },
            {
              'targets': 2,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'branch'+row); 
              }
            },
            {
              'targets': 3,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'current_date'+row); 
              }
            },
            {
              'targets': 4,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'current_date'+row); 
              }
            },
            {
              'targets': 5,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'change_time'+row); 
              }
            },
            {
              'targets': 6,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'reason'+row); 
              }
            },
            {
              'targets': 7,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'status'+row);
                  $(td).addClass('text-primary');
              }
            },
            {
              'targets': 8,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'requested_date'+row);
              }
            },
            {
              'targets': 9,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'status_change_by'+row);
              }
            },
            {
              'targets': 10,
              'createdCell':  function (td, cellData, rowData, row, col) {
                  $(td).attr('id', 'status_change_date'+row);
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
          url: "{{ url('attendance-management/change-request/store') }}",
          data: frm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {
            if(data.status=="no_record"){
              $('#alert_error strong').html('There is no attendance record for '+data.current_date);
              $('#alert_error').removeClass('alert-success');
              $('#alert_error').removeClass('alert-warning');
              $('#alert_error').addClass('alert-warning');
              $('#error-alert').show();
            }
            else{
              $('#modal-create').modal('hide');
              var index = $('#latest_index').val();
              if(index=='null')
                index = 0;
              else
                index = Number(index)+1;

              var num = index + 1;
              var record ='<tr id="row'+index+'"><td>'+num+'</td>';
              record += '<td id="user_name'+index+'">'+data.user_name+'</td>';
              record += '<td id="branch'+index+'">'+data.branch+'</td>';
              record += '<td id="current_date'+index+'">'+data.current_date+'</td>';
              record += '<td id="changing_date'+index+'">'+data.changing_date+'</td>';
              record += '<td id="change_time'+index+'">'+data.changing_time+'</td>';
              record += '<td id="reason'+index+'">'+data.reason+'</td>';
              record += '<td id="status'+index+'" class="text-primary"><strong>Pending</strong><a href="#" id="statusModal'+index+'" style="font-size:11px;padding:1px" data-toggle="modal" class="update-modal btn btn-info" data-target="#modal-status" data-id="'+data.id+'" data-username="'+data.user_name+'" data-index="'+index+'" onclick="addValueForStatus('+index+')">Change Status</a> </td>';
              record += '<td id="requested_date'+index+'">'+data.requested_date+'</td>';
              record += '<td id="status_change_by'+index+'"></td>';
              record += '<td id="status_change_date'+index+'"></td></tr>';

              var status = '<strong>Pending</strong><a href="#" id="statusModal'+index+'" style="font-size:11px;padding:1px" data-toggle="modal" class="update-modal btn btn-info" data-target="#modal-status" data-id="'+data.id+'" data-username="'+data.user_name+'" data-index="'+index+'" onclick="addValueForStatus('+index+')">Change Status</a>';
              change_table.row.add( [
                  num,
                  data.user_name,
                  data.branch,
                  data.current_date,
                  data.changing_date,
                  data.changing_time,
                  data.reason,
                  status,
                  data.requested_date,
                  '',
                  ''
              ] ).draw();

              $('#latest_index').val(index);

              $('#alert strong').html('Successfully added the new record');
              $('#alert').removeClass('alert-success');
              $('#alert').removeClass('alert-danger');
              $('#alert').addClass('alert-success');
              $('#alert-section').show();
            }
              
                    
          },
          error: function (data) {
            console.log('An error occurred.');
            console.log(data);
          },
        });
      });

      var statusfrm = $('#status_form');
      statusfrm.submit(function (e) {
        e.preventDefault();               
        
        $.ajax({
          type: statusfrm.attr('method'),
          url: "{{ url('attendance-management/change-request/change-status') }}",
          data: statusfrm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
          success: function (data) {
            console.log(data);
            $('#modal-status').modal('hide');
            var index = data.index;
            
            $("#status"+index+" strong").html(data.status_name);
            $("#status_change_by"+index).html(data.change_by);
            $("#status_change_date"+index).html(data.change_date);

            $('#alert strong').html('Successfully change the status');
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

    });
    function addValueForStatus($i){
    
      var id=$('#statusModal'+$i).data('id');
      var username=$('#statusModal'+$i).data('username');
      var index=$('#statusModal'+$i).data('index');
      $("#modal-status #id").val(id);
      $("#modal-status #index").val(index);
      $(".statusModal strong").html(username);
    }

  </script>
@stop