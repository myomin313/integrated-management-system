@extends('layouts.master')
@section('title','Leave Type Management')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active">Leave Type Management</li>
            </ol>
          </div><!-- /.col -->
          <!--<div class="col-sm-6">-->
          <!--  <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a>-->
          <!-- </div>-->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Leave Type List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                   @if(Session::has('msg'))
                   <div class="alert alert-success"> 
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! session()->get('msg') !!} 
                      </div>
                  @endif
                <table class="table table-hover" id="user_record">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Leave Type Name</th>
                      <th>Leave Day</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Created Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n=0; ?>
                    @foreach($leavetypes as $leavetype)
                    <tr>
                      <td>{{ $n+1 }}</td>
                      <td>{{ $leavetype->leave_type_name }}</td>
                      <td>{{ $leavetype->leave_day }}</td>
                      <td>{{ $leavetype->type }}</td>
                      @if( $leavetype->status== 1)
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-success" data-target="#modal-status" data-id="{{ $leavetype->id }}" data-status="{{ $leavetype->status }}" onclick="addValueForStatusChange(this)">
                           Active
                         </a>
                      </td>
                      @else
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-danger" data-target="#modal-status" data-id="{{ $leavetype->id }}" data-status="{{ $leavetype->status }}" onclick="addValueForStatusChange(this)">
                         Unactive
                         </a>
                      </td>
                      @endif
                     <td>{{ $leavetype->created_user }}</td>
                     @if(!empty($leavetype->created_at))
                      <td>{{ $leavetype->created_at->format('Y-m-d g:i:s A') }}</td>
                      @else
                      <td></td>
                      @endif
                    </tr>
                    <?php $n++; ?>
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
      <div class="modal fade" id="modal-create">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Create New Leave Type</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Leave Type <span
                                                class="required text-danger">*</span></label>
                                         <input type="text" class="form-control" id="leave_type_name" name="leave_type_name" required>
                                        <span class="text-danger error-text leave_type_name_error"></span>
                                    </div>
                                     <div class="form-group">
                                      <label for="type">Holiday Type <span class="required text-danger">*</span></label>
                                      <select class="form-control select2bs5" name="type" id="type" style="width: 100%;">
                                         <option value="paid">Paid Leave</option>
                                         <option value="unpaid">Unpaid Leave</option>
                                       </select>
                                       <span class="text-danger error-text type_error"></span> 
                                     </div>
                                    <div class="form-group">
                                        <label for="leave_day">Total Leave Days <span
                                                class="required text-danger">*</span></label>
                                            <input type="text" class="form-control" id="leave_day" name="leave_day">
                                            <span class="text-danger error-text leave_day_error"></span> 
                                        </div>
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

      <!-- create script start -->
      <script src="http://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
</script>
<script type="text/javascript">
//create script start
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var leave_type_name = $("#leave_type_name").val();
        var leave_day = $("#leave_day").val();
        var type = $("#type").val();
        $.ajax({
           type:'POST',
           url:"{{ route('leavetypes.store') }}",
           data:{"leave_type_name":leave_type_name,"leave_day":leave_day,"type":type,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#leave_type_name").val('');
                    $("#leave_day").val('');
                    $("#type").val('');
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    

    function printErrorMsg (msg) {
           $.each( msg, function( key, value ) {
               console.log(value);
              $('.'+key+'_error').text(value);
          });
    }

  //update script start



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

 <div class="modal fade editModal" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Edit Holiday</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post">
               @csrf
            <div class="modal-body">
              <div class="form-group">
                    <label for="date">Date <span class="required text-danger">*</span></label>
                    <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                        <input type="date" name="date_update" id="date_update" placeholder="Select Date" required class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                    </div>
                    <span class="text-danger error-text edit_date_error"></span>
              </div>
              <div class="form-group">
                    <label for="name">Title <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="title_update" name="title_update" placeholder="Enter Title" required>
                    <span class="text-danger error-text edit_title_error"></span>
              </div>
              <div class="form-group ">
                    <label for="holiday_type_id">Holiday Type <span class="required text-danger">*</span></label>
                    <select class="form-control select2bs4" name="holiday_type_id_update" id="holiday_type_id_update" style="width: 100%;">
                      
                    </select>
                    <span class="text-danger error-text edit_holiday_type_id_error"></span>
              </div>
              <div class="form-group">
                <label for="name">Driver <span class="required text-danger">*</span></label>
                <input type="checkbox" id="driver_update" name="driver_update"  required>
              </div>
            </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-update">Update</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      
 
    
      
    </section>
@stop