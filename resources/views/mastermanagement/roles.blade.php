@extends('layouts.master')
@section('title','Dashboard')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
             <li class="breadcrumb-item active">Role Management</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
            @can('role-create')
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
                <h3 class="card-title">Role List</h3>
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
                      <th>Role</th>
                      <th>Abbreviation</th>
                      <th>Guard Name</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                      @canany(['role-edit','role-delete'])
                      <th>Action</th>
                      @endcan
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n=0 ?>
                    @foreach($roles as $role)
                    <tr>
                      <td>{{ $n+1}}</td>
                      <td>{{ $role->name }}</td>
                      <td>{{ $role->short_name }}</td>
                      <td>{{ $role->guard_name }}</td>
                      @if( $role->status== 1)
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-success" data-target="#modal-status" data-id="{{ $role->id }}" data-status="{{ $role->status }}" onclick="addValueForStatusChange(this)">
                           Active
                         </a>
                      </td>
                      @else
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-danger" data-target="#modal-status" data-id="{{ $role->id }}"  data-status="{{ $role->status }}" data-short_name="{{ $role->short_name }}" data-guard_name="{{ $role->guard_name }}" onclick="addValueForStatusChange(this)">
                         Unactive
                         </a>
                      </td>
                      @endif
                      <td>{{ $role->created_user }}</td>
                      <td>{{ $role->updated_user }} </td>
                      <td>{{ $role->created_at->format('Y-m-d g:i:s A') }}</td>
                      <td>{{ $role->updated_at->format('Y-m-d g:i:s A') }} </td>
                      <td>
                        <!-- <a href="../../pages/mastermanagement/branch-edit.html">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp; -->
                        @can('role-edit')
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $role->id }}" data-order_no="{{ $role->order_no }}" data-name="{{ $role->name }}" data-guard_name="{{ $role->guard_name }}" data-short_name="{{ $role->short_name }}" onclick="addValueForEdit(this)">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp;
                        @endcan
                         @can('role-delete')
                        <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $role->id }}" data-order_no="{{ $role->order_no }}" data-name="{{ $role->name }}" data-short_name="{{ $role->short_name }}" onclick="addValueForDelete(this)">
                          <i class="fas fa-trash text-danger"></i>
                          @endcan
                          
                        </a>
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
              <h4 class="modal-title">Create New Role</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                @csrf
            <div class="modal-body">
              <div class="form-group">
                    <label for="name" >Role Name <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Role Name" required>
                    <span class="text-danger error-text name_error"></span>
              </div>
              <div class="form-group">
                    <label for="abbreviation">>Abbreviation</label>
                    <input type="text" class="form-control" id="short_name" name="short_name" placeholder="Enter Role's Abbreviation">
                    <span class="text-danger error-text short_name_error"></span>
              </div>
              <div class="form-group">
                    <label for="abbreviation">Guard Name</label>
                    <input type="text" class="form-control" id="guard_name" name="guard_name" placeholder="Enter Role's Abbreviation">
                    <span class="text-danger error-text guard_name_error"></span>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success btn-submit">Add</button>
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
              <h4 class="modal-title">Edit Role</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
            <div class="modal-body">
               <input type="hidden" name="id_update" id="id_update">
              <div class="form-group">
                    <label for="name_update">Role Name <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="name_update" name="name_update" placeholder="Enter Role Name" required>
                    <span class="text-danger error-text edit_name_error"></span>
              </div>
              <div class="form-group">
                    <label for="short_name_update" class="col-sm-5 col-form-label">Abbreviation</label>
                      <input type="text" class="form-control" id="short_name_update" name="short_name_update" placeholder="Enter Role's Abbreviation">
                       <span class="text-danger error-text edit_short_name_error"></span>
              </div>
                <div class="form-group">
                    <label for="guard_name_update">Guard Name</label>
                    <input type="text" class="form-control" id="guard_name_update" name="guard_name_update" placeholder="Enter Role's Abbreviation">
                    <span class="text-danger error-text edit_guard_name_error"></span>
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
              <h4 class="modal-title">Delete Role</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('roles.delete') }}"  method="POST">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id" >
                <p>Are you sure want to delete "<strong></strong>" Role ?</p>
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
            <form action="{{ route('roles.status') }}"  method="POST">
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
      <script src="http://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
</script>
<script type="text/javascript">
//create script start
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var name = $("#name").val();
        var short_name = $("#short_name").val();
        var guard_name = $("#guard_name").val();
        $.ajax({
           type:'POST',
           url:"{{ route('roles.store') }}",
           data:{"name":name,"short_name":short_name,"guard_name":guard_name,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#name").val('');
                    $("#short_name").val('');
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
        var guard_name_update = $("#guard_name_update").val();
        $.ajax({
           type:'POST',
           url:"{{ route('roles.update') }}",
           data:{"name":name_update,"short_name":short_name_update,"guard_name":guard_name_update,"id":id_update,"_token":"{{ csrf_token() }}"},
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
      var guard_name=$(btn).data('guard_name');
      $(".editModal #id_update").val(id);
      $(".editModal #name_update").val(name);
      $(".editModal #short_name_update").val(short_name);
      $(".editModal #guard_name_update").val(guard_name);
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