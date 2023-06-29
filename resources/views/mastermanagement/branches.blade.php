@extends('layouts.master')
@section('title','Branch Management')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active">Branch Management</li>
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
                <h3 class="card-title">Branch List</h3>
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
                      <th>Branch Name</th>
                      <th>Abbreviation</th>
                      <!--<th>Status</th>-->
                      <th>Created By</th>
                      <th>Updated By</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                      <!--<th>Action</th>-->
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n=0 ?>
                    @foreach($branches as $branch)
                    <tr>
                      <td>{{ $n+1 }}</td>
                      <td>{{ $branch->name }}</td>
                      <td>{{ $branch->short_name }}</td>
                     
                      <td>{{ $branch->created_user }}</td>
                      <td>{{ $branch->updated_user }}</td>
                      <td>{{ $branch->created_at->format('Y-m-d g:i:s A') }}</td>
                      <td>{{ $branch->updated_at->format('Y-m-d g:i:s A') }} </td>
                      
                      
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

      <!--   <div class="modal fade" id="modal-create">-->
      <!--  <div class="modal-dialog">-->
      <!--    <div class="modal-content">-->
      <!--      <div class="modal-header">-->
      <!--        <h4 class="modal-title">Create New Branch</h4>-->
      <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
      <!--          <span aria-hidden="true">&times;</span>-->
      <!--        </button>-->
      <!--      </div>-->
      <!--      <form  method="post">-->
      <!--          @csrf-->
      <!--      <div class="modal-body">-->
      <!--        <div class="form-group">-->
      <!--            <label for="name">Branch Name <span class="required text-danger">*</span></label>-->
      <!--            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Branch Name" required>-->
      <!--           <span class="text-danger error-text name_error"></span> -->
      <!--        </div>-->
      <!--        <div class="form-group">-->
      <!--          <label for="short_name">Abbreviation <span class="required text-danger">*</span></label>-->
      <!--          <input type="text" class="form-control" id="short_name" name="short_name" placeholder="Enter Abbreviation Name" required>-->
      <!--          <span class="text-danger error-text short_name_error"></span>  -->
      <!--      </div>-->
      <!--      </div>-->
      <!--       <div class="modal-footer justify-content-between">-->
      <!--        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>-->
      <!--        <button type="button" class="btn btn-success btn-submit">Add</button>-->
      <!--       </div>-->
      <!--      </form>-->
      <!--  </div>-->
      <!--</div>-->

      <!--<div class="modal fade editModal" id="modal-edit">-->
      <!--  <div class="modal-dialog">-->
      <!--    <div class="modal-content bg-default">-->
      <!--      <div class="modal-header">-->
      <!--        <h4 class="modal-title">Edit Branch</h4>-->
      <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
      <!--          <span aria-hidden="true">&times;</span>-->
      <!--        </button>-->
      <!--      </div>-->
      <!--      <form  method="post">-->
      <!--      @csrf-->
      <!--      <div class="modal-body">-->
      <!--          <input type="hidden" name="id_update" id="id_update">-->
      <!--        <div class="form-group">-->
      <!--              <label for="name">Branch Name <span class="required text-danger">*</span></label>-->
      <!--                <input type="text" class="form-control" id="name_update" name="name" placeholder="Enter Branch Name" required>-->
      <!--                <span class="text-danger error-text edit_name_error"></span>-->
      <!--        </div>-->
      <!--        <div class="form-group">-->
      <!--          <label for="name">Abbreviation <span class="required text-danger">*</span></label>-->
      <!--          <input type="text" class="form-control" id="short_name_update" name="short_name" placeholder="Enter Abbreviation Name"-->
      <!--            required>-->
      <!--            <span class="text-danger error-text edit_short_name_error"></span>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="modal-footer justify-content-between">-->
      <!--        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>-->
      <!--        <button type="button" class="btn btn-success btn-update">Update</button>-->
      <!--      </div>-->
      <!--      </form>-->
      <!--    </div>-->
          <!-- /.modal-content -->
      <!--  </div>-->
        <!-- /.modal-dialog -->
      <!--</div>-->
      <!-- /.modal -->

     

      <!-- delete start -->
      <!-- <div class="modal fade deleteModal" id="modal-delete">-->
      <!--  <div class="modal-dialog">-->
      <!--    <div class="modal-content bg-default">-->
      <!--      <div class="modal-header">-->
      <!--        <h4 class="modal-title">Delete Branch</h4>-->
      <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
      <!--          <span aria-hidden="true">&times;</span>-->
      <!--        </button>-->
      <!--      </div>-->
      <!--      <form action="{{ route('branches.delete') }}"  method="POST">-->
      <!--          @csrf-->
      <!--      <div class="modal-body">-->
      <!--          <input type="hidden" name="id" id="id" >-->
      <!--          <p>Are you sure want to delete "<strong></strong>" Branch ?</p>-->
      <!--      </div>-->
         
      <!--      <div class="modal-footer justify-content-between">-->
      <!--        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>-->
      <!--        <button type="submit" class="btn btn-success btn-delete">Sure</button>-->
      <!--      </div>-->
      <!--         </form>-->
      <!--    </div>-->
          <!-- /.modal-content -->
      <!--  </div>-->
        <!-- /.modal-dialog -->
      <!--</div>-->
      <!-- /.modal -->
      <!-- delete end -->

      <!-- status start -->
      <!-- <div class="modal fade statusModal" id="modal-status">-->
      <!--  <div class="modal-dialog">-->
      <!--    <div class="modal-content bg-default">-->
      <!--      <div class="modal-header">-->
      <!--        <h4 class="modal-title">Change Status</h4>-->
      <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
      <!--          <span aria-hidden="true">&times;</span>-->
      <!--        </button>-->
      <!--      </div>-->
      <!--      <form action="{{ route('branches.status') }}"  method="POST">-->
      <!--          @csrf-->
      <!--      <div class="modal-body">-->
      <!--        <div class="form-group">-->
      <!--            <label for="name">Status <span class="required text-danger">*</span></label>-->
      <!--            <select class="form-control select2bs4" name="status" id="status" style="width: 100%;">-->
      <!--            </select>-->
      <!--            <input type="hidden" name="id" id="id">-->
      <!--        </div>-->
               
      <!--      </div>-->
         
      <!--      <div class="modal-footer justify-content-between">-->
      <!--        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>-->
      <!--        <button type="submit" class="btn btn-success">change</button>-->
      <!--      </div>-->
      <!--         </form>-->
      <!--    </div>-->
          <!-- /.modal-content -->
      <!--  </div>-->
        <!-- /.modal-dialog -->
      <!--</div>-->
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
        $.ajax({
           type:'POST',
           url:"{{ route('branches.store') }}",
           data:{"name":name,"short_name":short_name,"_token": "{{ csrf_token() }}"},
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
        $.ajax({
           type:'POST',
           url:"{{ route('branches.update') }}",
           data:{"name":name_update,"short_name":short_name_update,"id":id_update,"_token":"{{ csrf_token() }}"},
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
               console.log('data_'+value);
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
      $(".editModal #id_update").val(id);
      $(".editModal #name_update").val(name);
      $(".editModal #short_name_update").val(short_name);
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