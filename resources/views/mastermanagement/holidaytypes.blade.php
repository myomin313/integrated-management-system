@extends('layouts.master')
@section('title','Holiday Type Management')
@section('content')
	<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active">Holiday Type Management</li>
            </ol>
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
                <h3 class="card-title">Holiday Type List</h3>
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
                      <th>Name</th>
                      <th>Created Date</th>
                      <th>Updated Date</th>
                      <th>Created By</th>
                      <th>Updated By</th>
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php $n=0 ?>
                    @foreach($holidaytypes as $holidaytype)
                    <tr>
                      <td>{{ $n +1 }}</td>
                      <td>{{ $holidaytype->name }}</td>
                      <td>{{ $holidaytype->created_user }}</td>
                      <td>{{ $holidaytype->updated_user }} </td>
                      <td>{{ $holidaytype->created_at->format('Y-m-d g:i:s A') }}</td>
                      <td>{{ $holidaytype->updated_at->format('Y-m-d g:i:s A') }} </td>
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
        $.ajax({
           type:'POST',
           url:"{{ route('holiday-types.store') }}",
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
           url:"{{ route('holiday-types.update') }}",
           data:{"name":name_update,"id":id_update,"_token":"{{ csrf_token() }}"},
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