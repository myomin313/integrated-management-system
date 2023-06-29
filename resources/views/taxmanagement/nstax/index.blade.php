@extends('layouts.master')
@section('title','NS Actual Income Tax List')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/actual-tax/ns-income-tax-list')}}">NS Actual Income Tax</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            
            <a class="btn btn-success breadcrumb-btn" href="{{route('ns-tax.create')}}" id="new_form"><i class="fas fa-plus"></i> Add New</a>
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
                <form action="{{url('tax-management/actual-tax/ns-income-tax-list')}}" method="get">
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
                          <option selected="selected" value="all">- All -</option>
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
                          <input type="text" name="from_date" id="from_date" required placeholder="YYYY" value="{{isset($from_date)?$from_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
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
                          <input type="text" name="to_date" id="to_date" required placeholder="YYYY" value="{{isset($to_date)?$to_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
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
                          <a href="{{url('tax-management/actual-tax/ns-income-tax-list')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                <h3 class="card-title"><strong>NS Actual Income Tax List</strong></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover" id="ns_tax_record">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tax For</th>
                      <th>Name</th>
                      <th>Actual Tax (MMK)</th>
                      <th>Exchange Rate</th>
                      <th>Actual Tax (USD)</th>
                      <th>Pay Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($taxes as $key=>$value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td id="tax_for{{$key}}">{{\Carbon\Carbon::parse($value->tax_for)->format("F Y")}}</td>
                      @php
                        $emp_name = getUserFieldWithId($value->user_id,"employee_name");
                      @endphp
                      <td id="name{{$key}}">{{$emp_name?$emp_name:getUserFieldWithId($value->user_id,"name")}}</td>
                      <td id="tax_mmk{{$key}}">{{$value->tax_amount_mmk}}</td>
                      <td id="ex_rate{{$key}}">{{$value->exchange_rate}}</td>
                      <td id="tax_usd{{$key}}">{{$value->tax_amount_usd}}</td>
                      <td id="pay_date{{$key}}">{{siteformat_date($value->pay_date)}}</td>
                      <td>
                        @php $tax_for = \Carbon\Carbon::parse($value->tax_for)->format("m/Y");
                        @endphp
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{$value->id}}" data-userid="{{$value->user_id}}" data-taxfor="{{$value->tax_for}}" data-taxmmk="{{$value->tax_amount_mmk}}" data-exrate="{{$value->exchange_rate}}" data-taxusd="{{$value->tax_amount_usd}}" data-paydate="{{siteformat_date($value->pay_date)}}" data-index="{{$key}}" id="editModal{{$key}}" onclick="addValueForEdit(this)" title="Edit Record">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp;
                        
                        <a href="#" title="Delete Record" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{$value->id}}" data-name="{{$emp_name?$emp_name:getUserFieldWithId($value->user_id,'name')}}" data-taxfor="{{\Carbon\Carbon::parse($value->tax_for)->format('F Y')}}" onclick="addValueForDelete(this)" id="deleteModal{{$key}}">
                          <i class="fas fa-trash text-danger"></i>
                        </a>
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


      @include('taxmanagement.nstax.edit')
      <!-- /.modal -->

      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Actual Tax</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('tax-management/actual-tax/ns-income-tax-delete')}}" method="post">
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                    
                @csrf
                <p>Are you sure want to delete the actual tax for "<strong id="emp_name"></strong>" in <strong id="tax_period"></strong>?</p>
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
        $('#datetimepicker3').datetimepicker({
            format: 'DD/MM/YYYY'
        });
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

        $('#user_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        });
        
            
        var frm = $('#edit_tax');
        frm.submit(function (e) {
            
            e.preventDefault();               

            $.ajax({
                type: frm.attr('method'),
                url: "{{ url('tax-management/actual-tax/ns-income-tax-update') }}",
                data: frm.serialize(), // {code: code, name: name, designation: designation, contact: contact, group_id: group_id, address: address},
                success: function (data) {
                    $('#modal-edit').modal('hide');
                    var index = data.index;
                    $('#tax_for'+index).html(data.tax_for);
                    $('#name'+index).html(data.user_name);
                    $('#tax_mmk'+index).html(data.tax_mmk);
                    $('#ex_rate'+index).html(data.exchange_rate);
                    $('#tax_usd'+index).html(data.tax_usd);
                    $('#pay_date'+index).html(data.pay_date);
                    

                    $('#editModal'+index).data('userid',data.user_id);
                    $('#editModal'+index).data('taxfor',data.tax_period);
                    $('#editModal'+index).data('taxmmk',data.tax_mmk);
                    $('#editModal'+index).data('exrate',data.exchange_rate);
                    $('#editModal'+index).data('taxusd',data.tax_usd);
                    $('#editModal'+index).data('paydate',data.pay_date);
                    
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        });

        var delfrm = $('#delete_tax');
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
    function calculateUSD($i){
      var exchange_rate = $("#exchange_rate"+$i).val();
      if(isNaN(exchange_rate))
        exchange_rate =  0;

      var mmk_amount = $("#tax_amount_mmk"+$i).val();
      if(isNaN(mmk_amount))
        mmk_amount =  0;

      var amount = 0;
      if(exchange_rate>0)
        amount = Number(mmk_amount) / Number(exchange_rate);

      $("#tax_amount_usd"+$i).val(amount.toFixed(2));
      
    
    }
    function addValueForEdit(btn){
    
      var id=$(btn).data('id');
      var userid=$(btn).data('userid');
      var taxfor=$(btn).data('taxfor');
      var taxmmk=$(btn).data('taxmmk');
      var exrate=$(btn).data('exrate');
      var taxusd=$(btn).data('taxusd');
      var pay_date=$(btn).data('paydate');
      var index=$(btn).data('index');

      $(".editModal #id").val(id);
      if(userid)
        $("#modal-edit select#user_id").val(userid).change();
      $(".editModal #tax_for").val(taxfor);
      $(".editModal #tax_amount_mmk0").val(taxmmk);
      $(".editModal #exchange_rate0").val(exrate);
      $(".editModal #tax_amount_usd0").val(taxusd);
      $(".editModal #pay_date").val(pay_date);
      $(".editModal #index").val(index);
      
    }

    function addValueForDelete(btn){
    
      var id=$(btn).data('id');
      var name=$(btn).data('name');
      var taxfor=$(btn).data('taxfor');
      $(".deleteModal #id").val(id);
      $(".deleteModal strong#emp_name").html(name);
      $(".deleteModal strong#tax_period").html(taxfor);
    }
  </script>
@stop