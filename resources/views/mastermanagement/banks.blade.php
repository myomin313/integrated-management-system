@extends('layouts.master')
@section('title','Bank Management')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active">Bank Management</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
             @can('bank-create')
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
                <h3 class="card-title">Bank List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                   @if(Session::has('msg'))
                   <div class="alert alert-success"> 
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {!! session()->get('msg') !!} 
                      </div>
                  @endif
                <table id="user_record" class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                       @canany(['bank-edit','bank-delete'])
                      <th>Action</th>
                      @endcan
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                     <?php $n=0 ?>
                    @foreach($banks as $bank)
                    <tr>
                      <td>{{ $n+1}}</td>
                      <td>{{ $bank->name }}</td>
                      @if( $bank->status== 1)
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-success" data-target="#modal-status" data-id="{{ $bank->id }}" data-status="{{ $bank->status }}" onclick="addValueForStatusChange(this)">
                           Active
                         </a>
                      </td>
                      @else
                      <td>
                         <a href="#" data-toggle="modal" class="edit-modal text-danger" data-target="#modal-status" data-id="{{ $bank->id }}" data-status="{{ $bank->status }}" onclick="addValueForStatusChange(this)">
                         Unactive
                         </a>
                      </td>
                      @endif
                      <td>{{ $bank->created_user }}</td>
                      <td>{{ $bank->updated_user }}</td>
                      <td>{{ $bank->created_at->format('Y-m-d g:i:s A') }}</td>
                      <td>{{ $bank->updated_at->format('Y-m-d g:i:s A') }} </td>
                      <td>
                        <!-- <a href="../../pages/mastermanagement/branch-edit.html">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp; -->
                        @can('bank-edit')
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{ $bank->id }}" data-name="{{ $bank->name }}" onclick="addValueForEdit(this)">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp;
                        @endcan
                         @can('bank-delete')
                        <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $bank->id }}" data-name="{{ $bank->name }}" onclick="addValueForDelete(this)">
                          <i class="fas fa-trash text-danger"></i>
                        </a>
                        @endcan
                      </td>
                      
                    </tr>
                    <?php $n++ ?>
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
              <h4 class="modal-title">Create New Bank</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
                 @csrf
            <div class="modal-body">
              <!-- <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
              </div> -->
              <div class="form-group">
                    <label for="name">Bank Name <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">
                    </div>               
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
              <h4 class="modal-title">Edit Bank</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form  method="post">
              @csrf
            <div class="modal-body">
              <div class="form-group">
                    <label for="name">Bank Name <span class="required text-danger">*</span></label>
                    
                      <input type="text" class="form-control" id="name_update" name="name"  required>
                      <input type="hidden" name="id_update" id="id_update">
              </div>
            </div>
            </form>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success btn-update">Update</button>
            </div>
          </div>
     
        </div>
   
      </div>

      <!-- delete start -->
       <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Bank</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('banks.delete') }}"  method="POST">
                @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id" >
                <p>Are you sure want to delete "<strong></strong>" bank?</p>
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
            <form action="{{ route('banks.status') }}"  method="POST">
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
//create script start
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var name = $("#name").val();
        $.ajax({
           type:'POST',
           url:"{{ route('banks.store') }}",
           data:{"name":name,"_token": "{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    $("#name").val('');
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
        $.ajax({
           type:'POST',
           url:"{{ route('banks.update') }}",
           data:{"name":name_update,"id":id_update,"_token":"{{ csrf_token() }}"},
           success:function(data){
                if($.isEmptyObject(data.error)){
                    alert(data.success);
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });

    function printErrorMsg (msg) {
        $(".invalid-feedback").css('display','block');
        $.each( msg, function( key, value ) {
            $(".invalid-feedback").append('<label>'+value+'</label>');
        });
    }
  

  //update script start

  function addValueForEdit(btn){
    
      var id=$(btn).data('id');
      var name=$(btn).data('name');
      $(".editModal #id_update").val(id);
      $(".editModal #name_update").val(name);
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