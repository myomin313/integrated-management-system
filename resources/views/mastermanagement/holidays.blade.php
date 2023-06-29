@extends('layouts.master')
@section('title','Holiday Management')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active">Holiday Management</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
             @can('holiday-create')
            <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a>
            @endcan
           </div>
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
                <h3 class="card-title">Holiday List</h3>
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
                      <th>Date</th>
                      <th>Title</th>
                      <th>Holiday Type</th>
                      <th>Driver</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                       @canany(['holiday-edit','holiday-delete'])
                      <th>Action</th>
                      @endcan
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n=0 ?>
                    @foreach($holidays as $holiday)
                    <tr>
                      <td>{{ $n+1}}</td>
                      <td>{{ $holiday->date }}</td>
                      <td>{{ $holiday->title }}</td>
                      <td>{{ $holiday->holiday_type_name }}</td>
                      <td>
                         @if( $holiday->driver == 1)
                         <input type="checkbox" checked="checked" disabled="disabled">
                         @else
                         <input type="checkbox" disabled="disabled">
                         @endif
                      </td>
                      @if( $holiday->status== 1)
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-success" data-target="#modal-status" data-id="{{ $holiday->id }}" data-status="{{ $holiday->status }}" onclick="addValueForStatusChange(this)">
                           Active
                         </a>
                      </td>
                      @else
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-danger" data-target="#modal-status" data-id="{{ $holiday->id }}" data-status="{{ $holiday->status }}" onclick="addValueForStatusChange(this)">
                         Unactive
                         </a>
                      </td>
                      @endif
                      <td>{{ $holiday->created_user }}</td>
                      <td>{{ $holiday->updated_user }} </td>
                      <td>{{ $holiday->created_at->format('Y-m-d g:i:s A') }}</td>
                      <td>{{ $holiday->updated_at->format('Y-m-d g:i:s A') }} </td>
                      <td>
                        <!-- <a href="../../pages/mastermanagement/branch-edit.html">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp; -->
                         @can('holiday-edit')
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $holiday->id }}" 
                        data-date="{{ $holiday->date }}" data-holiday_type_id="{{ $holiday->holiday_type_id }}" data-driver="{{ $holiday->driver }}" 
                        data-title="{{ $holiday->title }}" onclick="addValueForEdit(this)">
                          <i class="fas fa-edit text-warning"></i>
                         
                        </a>&nbsp;
                         @endcan
                         @can('holiday-delete')
                        <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $holiday->id }}" 
                        data-title="{{ $holiday->title }}" onclick="addValueForDelete(this)">
                          <i class="fas fa-trash text-danger"></i>
                        </a>
                         @endcan
                      </td>
                    </tr>
                    <?php  $n++; ?>
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
      <!-- start create -->
       <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create New Holiday</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                  @csrf
            <div class="modal-body">
              <div class="form-group">
                    <label for="date">Date <span class="required text-danger">*</span></label>
                      <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                        <input type="text" name="date" id="date" placeholder="Select Date" required class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div><br> 
                    </div>
                        <span class="text-danger error-text date_error"></span>
              </div>
              <div class="form-group">
                    <label for="title">Title <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" required>
                    <span class="text-danger error-text title_error"></span> 
              </div>
              <div class="form-group">
                    <label for="holiday_type_id">Holiday Type <span class="required text-danger">*</span></label>
                    <select class="form-control select2bs5" name="holiday_type_id" id="holiday_type_id" style="width: 100%;">
                        <option value="">- Select -</option>
                        @foreach($holidaytypes as $holidaytype)
                         <option value="{{ $holidaytype->id }}">{{ $holidaytype->name }}</option>
                        @endforeach
                      </select>
                      <span class="text-danger error-text holiday_type_id_error"></span> 
              </div>
              <div class="form-group">
                <label for="driver">Driver</label>
                <input type="checkbox" id="driver" name="driver"  placeholder="Enter Title" required>
            </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success btn-submit">Save</button>
            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <!-- end create -->
      

      <!-- delete start -->
       <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Holiday</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('holidays.delete') }}"  method="POST">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id" >
                <p>Are you sure want to delete "<strong></strong>" holiday type?</p>
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

      <!-- status start -->
       <div class="modal fade statusModal" id="modal-status">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Change Status</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('holidays.status') }}"  method="POST">
                @csrf
            <div class="modal-body">
              <div class="form-group">
                  <label for="name">Status<span class="required text-danger">*</span></label>
                  <select class="form-control select2bs4" name="status" id="status" style="width: 100%;">
                  </select>
                  <input type="hidden" name="id" id="id">
              </div>
               
            </div>
         
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">change</button>
            </div>
               </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <!-- status end -->

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
              <input type="hidden" name="id_update" id="id_update">
              <div class="form-group">
                    <label for="date">Date <span class="required text-danger">*</span></label>
                    <div class="input-group date" id="datetimepickeredit" data-target-input="nearest">
                        <input type="text" name="date_update" id="date_update" placeholder="Select Date" required class="form-control datetimepicker-input" data-target="#datetimepickeredit"/>
                        <div class="input-group-append" data-target="#datetimepickeredit" data-toggle="datetimepicker">
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
                        @foreach($holidaytypes as $holidaytype)
                         <option value="{{ $holidaytype->id }}">{{ $holidaytype->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text edit_holiday_type_id_error"></span>
              </div>
              <div class="form-group">
                <label for="name">Driver</label>
                <input type="checkbox" id="driver_update" name="driver_update"   required>
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
      


      <!-- create script start -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.2.3/jquery.min.js"></script> -->
<script type="text/javascript">
//create script start
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var date = $("#date").val();
        var title = $("#title").val();
        var holiday_type_id = $("#holiday_type_id").val();
        if($("#driver").prop("checked") == true){
         var driver =1;
        }else{
         var driver =0;
        }
        $.ajax({
           type:'POST',
           url:"{{ route('holidays.store') }}",
           data:{"date":date,"title":title,"driver":driver,"holiday_type_id":holiday_type_id,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#date").val('');
                    $("#holiday_type_id").val('');
                    $("#driver").val('');
                    $("#title").val('');
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    // create script end 
    //
     $(".btn-update").click(function(e){
        e.preventDefault();
        var date_update = $("#date_update").val();
        var title_update = $("#title_update").val();
        var holiday_type_id_update = $("#holiday_type_id_update").val();
        var id_update = $("#id_update").val();
        if($("#driver_update").prop("checked") == true){
            driver_update =1;
        }else{
            driver_update =0;
        }
        $.ajax({
           type:'POST',
           url:"{{ route('holidays.update') }}",
            data:{"id":id_update,"date":date_update,"title":title_update,"driver":driver_update,"holiday_type_id":holiday_type_id_update,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    location.reload();
                }else{
                    printEditErrorMsg(data.error);
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
    function printEditErrorMsg (msg) {
           $.each( msg, function( key, value ) {
               console.log(value);
              $('.edit_'+key+'_error').text(value);
          });
    }
  

  //update script start

  function addValueForEdit(btn){
      
      var edit_id=$(btn).data('id');
      var edit_date=$(btn).data('date');
      var edit_title=$(btn).data('title');
      var edit_driver=$(btn).data('driver');
      var edit_holiday_type_id=$(btn).data('holiday_type_id');
      $(".editModal #id_update").val(edit_id);
      $(".editModal #date_update").val(edit_date);
      $(".editModal #title_update").val(edit_title);
      $(".editModal #driver_update").val(edit_driver);
      $("#holiday_type_id_update").val(edit_holiday_type_id).change();

      if(edit_driver == 1){
        $( "#driver_update" ).prop( "checked", true );
      }
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
      var title=$(btn).data('title');
      $(".deleteModal #id").val(id);
      $(".deleteModal strong").html(title);
  }
  $('div.alert').delay(5000).slideUp(300);


  
</script>


 
    
      
    </section>
@stop