@extends('layouts.master')
@section('title','Department Management')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
             <li class="breadcrumb-item active">Department Management</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
             @can('department-create')
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
                <h3 class="card-title">Department List</h3>
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
                      <th>Department Name</th>
                      <th>Abbreviation</th>
                      <th>Order No</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                       @can('department-edit')
                      <th>Action</th>
                      @endcan
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n=0 ?>
                    @foreach($departments as $department)
                    <tr>
                      <td>{{ $n+1}}</td>
                      <td>{{ $department->name }}</td>
                      <td>{{ $department->short_name }}</td>
                      <td>{{ $department->order_no }}</td>
                      @if( $department->status== 1)
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-success" data-target="#modal-status" data-id="{{ $department->id }}" data-status="{{ $department->status }}" onclick="addValueForStatusChange(this)">
                           Active
                         </a>
                      </td>
                      @else
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-danger" data-target="#modal-status" data-id="{{ $department->id }}"  data-status="{{ $department->status }}" onclick="addValueForStatusChange(this)">
                         Unactive
                         </a>
                      </td>
                      @endif
                      <td>{{ $department->created_user }}</td>
                      <td>{{ $department->updated_user }} </td>
                      <td>{{ $department->created_at->format('Y-m-d g:i:s A') }}</td>
                      <td>{{ $department->updated_at->format('Y-m-d g:i:s A') }} </td>
                      <td>
                        <!-- <a href="../../pages/mastermanagement/branch-edit.html">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp; -->
                         @can('department-edit')
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $department->id }}" data-order_no="{{ $department->order_no }}" data-name="{{ $department->name }}" data-short_name="{{ $department->short_name }}" onclick="addValueForEdit(this)">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp;
                        @endcan
                        <!--<a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $department->id }}" data-order_no="{{ $department->order_no }}" data-name="{{ $department->name }}" data-short_name="{{ $department->short_name }}" onclick="addValueForDelete(this)">-->
                        <!--  <i class="fas fa-trash text-danger"></i>-->
                        <!--</a>-->
                      </td>
                      
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
              <h4 class="modal-title">Create New Department</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                @csrf
            <div class="modal-body">
              <div class="form-group">
                    <label for="name" >Department Name <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Department Name" required>
                    <span class="text-danger error-text name_error"></span>
              </div>
              <div class="form-group">
                    <label for="abbreviation" >Abbreviation</label>
                    <input type="text" class="form-control" id="short_name" name="short_name" placeholder="Enter Department's Abbreviation">
                    <span class="text-danger error-text short_name_error"></span>
              </div>
               <div class="form-group">
                    <label for="order_no" >Order No</label>
                    <input type="text" class="form-control" id="order_no" name="order_no" placeholder="Enter Department's Order No">
                    <span class="text-danger error-text order_no_error"></span>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success btn-submit">Save</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <div class="modal fade editModal" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Edit Department</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
            <div class="modal-body">
               <input type="hidden" name="id_update" id="id_update">
              <div class="form-group">
                    <label for="name_update">Department Name <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="name_update" name="name_update" placeholder="Enter Department Name" required>
                    <span class="text-danger error-text edit_name_error"></span>
              </div>
              <div class="form-group">
                    <label for="short_name_update" class="col-sm-5 col-form-label">Abbreviation</label>
                      <input type="text" class="form-control" id="short_name_update" name="short_name_update" placeholder="Enter Department's Abbreviation">
                       <span class="text-danger error-text edit_short_name_error"></span>
              </div>
              <div class="form-group">
                    <label for="order_no_update" >Order No</label>
                    <input type="text" class="form-control" id="order_no_update" name="order_no_update" placeholder="Enter Department's Order No">
                    <span class="text-danger error-text edit_order_no_error"></span>
              </div>
            </div>
            </form>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success btn-update">Update</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
     

      <!-- delete start -->
       <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Department</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('departments.delete') }}"  method="POST">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id" >
                <p>Are you sure want to delete "<strong></strong>" Department ?</p>
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
            <form action="{{ route('departments.status') }}"  method="POST">
                @csrf
            <div class="modal-body">
              <div class="form-group">
                  <label for="name">Status <span class="required text-danger">*</span></label>
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


      <!-- create script start -->
<!--      <script src="http://code.jquery.com/jquery-3.3.1.min.js"-->
<!--      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="-->
<!--      crossorigin="anonymous">-->
<!--</script>-->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
//create script start
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var name = $("#name").val();
        var short_name = $("#short_name").val();
        var order_no = $("#order_no").val();
        $.ajax({
           type:'POST',
           url:"{{ route('departments.store') }}",
           data:{"name":name,"order_no":order_no,"short_name":short_name,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#name").val('');
                    $("#short_name").val('');
                    $("#order_no").val('');
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
        var name_update = $("#name_update").val();
        var id_update = $("#id_update").val();
        var short_name_update = $("#short_name_update").val();
        var order_no_update = $("#order_no_update").val();
        $.ajax({
           type:'POST',
           url:"{{ route('departments.update') }}",
           data:{"name":name_update,"short_name":short_name_update,"order_no":order_no_update,"id":id_update,"_token":"{{ csrf_token() }}"},
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
    
      var id=$(btn).data('id');
      var name=$(btn).data('name');
      var short_name=$(btn).data('short_name');
      var order_no=$(btn).data('order_no');
      $(".editModal #id_update").val(id);
      $(".editModal #name_update").val(name);
      $(".editModal #short_name_update").val(short_name);
      $(".editModal #order_no_update").val(order_no);
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
  
</script>
      

    
      
    </section>
@stop