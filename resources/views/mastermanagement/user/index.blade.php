@extends('layouts.master')
@section('title','User Management')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Master Management</li>
              <li class="breadcrumb-item active"><a href="{{url('master-management/user/list')}}">User Management</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            @can('user-create') 
            <a class="btn btn-success breadcrumb-btn" href="{{route('user.create')}}" id="new_form"><i class="fas fa-plus"></i> Add New</a>
            @endcan
            <a class="btn btn-default breadcrumb-btn openFilter" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
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
            @if(session('success_create'))
              <div class="col-md-12 p-0">
                <div class="alert alert-success alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
                </div>
              </div>
            @endif
            @if(session('success_update'))
              <div class="col-md-12 p-0">
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_update')}}</strong>
                </div>
              </div>
            @endif
            @if(session('success_delete'))
              <div class="col-md-12 p-0">
                <div class="alert alert-danger alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_delete')}}</strong>
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
            
            <div class="card">
              <div class="card-header">
                <form action="{{url('master-management/user/list')}}" method="get">
                  @php
                    $search=app('request')->get('search');
                    $position=app('request')->get('position');
                          
                  @endphp
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Position</label>
                        <select class="form-control select2bs4" name="position" id="position" style="width: 100%;">
                          <option selected="selected" value="all">- Select -</option>
                          @foreach($positions as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$position?'selected':''}}>{{$value->name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>User Name</label>
                        <input type="text" name="search" value="{{$search}}" class="form-control" placeholder="Search by user name">
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('master-management/user/list')}}" class="btn btn-warning">Reset</a>
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
                <h3 class="card-title"><strong>User List</strong></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover" id="user_record">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User Name</th>
                      <th>Position</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Status</th>
                      <th>Finger Print Profile</th>
                      <th>Created Date</th>
                       @canany(['user-edit','user-permission','user-delete'])
                      <th>Action</th>
                       @endcan
                      <!-- <th>Action (modal)</th> -->
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach($users as $key=>$value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td id="name{{$key}}">{{$value->name}}</td>
                      <td id="position{{$key}}">{{getPositionName($value->position_id)}}</td>
                      <td id="email{{$key}}">{{$value->email}}</td>
                      <td id="phone{{$key}}">{{$value->phone}}</td>
                      <td>{{$value->active?'Active':'Inactive'}}</td>
                      <td id="profile{{$key}}">{{getProfileNameWithId($value->profile_id)}}</td>
                      <td>{{siteformat_datetime($value->created_at)}}</td>
                      <td>
                        
                        @can('user-edit') 
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{$value->id}}" data-notitype="{{$value->noti_type}}" data-email="{{$value->email}}" data-phone="{{$value->phone}}" data-username="{{$value->name}}" data-position="{{$value->position_id}}" data-index="{{$key}}" id="editModal{{$key}}" onclick="addValueForEdit(this)" title="Edit Record">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp;
                        @endcan
                         @can('user-permission')
                        <a href="{{url('master-management/user/add-permission',$value->id)}}" title="Give Permission" >
                          <i class="fas fa-lock text-primary"></i>
                        </a>
                         @endcan
                        @can('user-delete') 
                        <a href="#" title="Inactive Record" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{$value->id}}" data-name="{{$value->name}}" onclick="addValueForDelete(this)">
                          <i class="fas fa-trash text-danger"></i>
                        </a>
                         @endcan
                      </td>
                      
                    </tr>
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

      {{-- @include('mastermanagement.user.create-modal') --}}

      @include('mastermanagement.user.edit-modal')
      <!-- /.modal -->

      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('master-management/user/delete')}}" method="post">
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                    
                @csrf
                <p>Are you sure want to delete the user "<strong></strong>"?</p>
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
      
      	$('.select2bs4').select2({
        	theme: 'bootstrap4'
      	})

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

        
      	$(document).on('click','#new_form',function(){
            var val = $("input[type='radio']:checked").val();

            if(val=='email'){
            	$('#phone').removeAttr('required');
            	$('#phone_section').hide();
            }
            else{
            	$('#email').removeAttr('required');
            	$('#email_section').hide();
            }	
              
        });

        $('input[type=radio][name=noti_type]').change(function() {
          
		        if(this.value=='email'){
            	$('#phone').removeAttr('required');
            	$('#phone_section').hide();

            	$('#email').attr('required');
            	$('#email_section').show();
            }
            else{
            	$('#email').removeAttr('required');
            	$('#email_section').hide();

            	$('#phone').attr('required');
            	$('#phone_section').show();
            }
		    });
            
        var frm = $('#edit_user');
        frm.submit(function (e) {
            
            e.preventDefault();               

            $.ajax({
                type: frm.attr('method'),
                url: "{{ url('master-management/user/update-info') }}",
                data: frm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
                success: function (data) {
                    $('#modal-edit').modal('hide');
                    var index = data.index;
                    $('#name'+index).html(data.name);
                    $('#position'+index).html(data.position);
                    $('#email'+index).html(data.email);
                    $('#phone'+index).html(data.phone);
                    //$('#profile'+index).html(data.profile);

                    // $('#editModal'+index).removeAttr('data-notitype');
                    // $('#editModal'+index).removeAttr('data-email');
                    // $('#editModal'+index).removeAttr('data-phone');
                    // $('#editModal'+index).removeAttr('data-username');
                    // $('#editModal'+index).removeAttr('data-position');
                    // $('#editModal'+index).removeAttr('data-profileid');

                    $('#editModal'+index).data('notitype',data.noti_type);
                    $('#editModal'+index).data('email',data.email);
                    $('#editModal'+index).data('phone',data.phone);
                    $('#editModal'+index).data('username',data.name);
                    $('#editModal'+index).data('position',data.position_id);
                    //$('#editModal'+index).data('profileid',data.profile_id);
                    
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        });

        var delfrm = $('#delete_user');
        delfrm.submit(function (e) {
            
            e.preventDefault();               

            $.ajax({
                type: delfrm.attr('method'),
                url: "{{ url('master-management/user/delete') }}",
                data: delfrm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
                success: function (data) {
                    $('#modal-delete').modal('hide');
                    var index = data.index;
                    $('#name'+index).html(data.name);
                    $('#position'+index).html(data.position);
                    $('#email'+index).html(data.email);
                    $('#phone'+index).html(data.phone);

                    $('#editModal'+index).removeAttr('data-notitype');
                    $('#editModal'+index).removeAttr('data-email');
                    $('#editModal'+index).removeAttr('data-phone');
                    $('#editModal'+index).removeAttr('data-username');
                    $('#editModal'+index).removeAttr('data-position');

                    $('#editModal'+index).data('notitype',data.noti_type);
                    $('#editModal'+index).data('email',data.email);
                    $('#editModal'+index).data('phone',data.phone);
                    $('#editModal'+index).data('username',data.name);
                    $('#editModal'+index).data('position',data.position_id);
                    
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
      var notitype=$(btn).data('notitype');
      var email=$(btn).data('email');
      var phone=$(btn).data('phone');
      var username=$(btn).data('username');
      var position=$(btn).data('position');
      //var profile=$(btn).data('profileid');
      var index=$(btn).data('index');

      $(".editModal #id").val(id);
      if(notitype=='email'){
        $('input[type=radio][value=phone]').removeAttr('checked');
        $('input[type=radio][value=email]').attr('checked',true);

        $('#phone').removeAttr('required');
        $('#phone').val('');
        $('#phone_section').hide();
      }
      else if(notitype=='phone'){
        $('input[type=radio][value=email]').removeAttr('checked');
        $('input[type=radio][value=phone]').attr('checked',true);

        $('#email').removeAttr('required');
        $('#email').val('');
        $('#email_section').hide();
      }
      $(".editModal #email").val(email);
      $(".editModal #phone").val(phone);
      $(".editModal #name").val(username);
      $(".editModal #index").val(index);
      if(position)
        $("#modal-edit select#position_id").val(position).change();
      //if(profile)
        //$("#modal-edit select#profile_id").val(profile).change();
    }

    function addValueForDelete(btn){
    
      var id=$(btn).data('id');
      var name=$(btn).data('name');
      $(".deleteModal #id").val(id);
      $(".deleteModal strong").html(name);
    }
  </script>
@stop