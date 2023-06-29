@extends('layouts.master')
@section('title','Payment Exchange Rate List')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Salary Management</li>
              <li class="breadcrumb-item active"><a href="{{url('salary-management/payment-exchange-rate/list')}}">Payment Exchange Rate</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            
            <a class="btn btn-success breadcrumb-btn" href="#" data-toggle="modal" data-target="#modal-create" id="new_form"><i class="fas fa-plus"></i> Add New</a>
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
                <form action="{{url('salary-management/payment-exchange-rate/list')}}" method="get">
                  
                  <div class="row">
                    
                    @php
                      $today_date=\Carbon\Carbon::now()->format('Y-m');
                    
                      $from_date=app('request')->get('from_date');
                      $to_date=app('request')->get('to_date');
                    @endphp
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        
                        <input type="month" name="from_date" id="from_date" placeholder="mm-YYYY" value="{{$from_date?$from_date:$today_date}}" class="form-control " />
                          
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>To Date</label>
                        
                          <input type="month" name="to_date" id="to_date" required placeholder="mm-YYYY" value="{{$to_date?$to_date:$today_date}}" class="form-control" />
                                                  
                      </div>
                    </div>
                    

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('salary-management/payment-exchange-rate/list')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                <h3 class="card-title"><strong>Payment Exchange Rate List</strong></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover" id="exchange_record">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Month</th>
                      <th>Salary Exchange Rate (1USD=MMK)</th>
                      <th>OT Exchange Rate (1USD=MMK)</th>
                      <th>Payment Date</th>
                      <th>Created Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($exchange_rates as $key=>$value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{\Carbon\Carbon::parse($value->date)->format("F, Y")}}</td>
                      
                      <td>{{siteformat_number($value->usd)}}</td>
                      <td>{{siteformat_number($value->ot_exchange_rate)}}</td>
                      <td id="payment_date{{$key}}">{{$value->payment_date?siteformat_date($value->payment_date):''}}</td>
                      <td id="tax_mmk{{$key}}">{{siteformat_datetime24($value->created_at)}}</td>
                      
                      <td>
                        @php $date = \Carbon\Carbon::parse($value->date)->format("Y-m");
                        @endphp
                        <a href="#" data-toggle="modal" class="edit-modal" data-target="#modal-edit" data-id="{{$value->id}}" data-date="{{\Carbon\Carbon::parse($value->date)->format('Y-m')}}" data-usd="{{$value->usd}}" data-yen="{{$value->yen}}" data-ot="{{$value->ot_exchange_rate}}"data-index="{{$key}}" id="editModal{{$key}}" data-paymentdate="{{$value->payment_date?siteformat_date($value->payment_date):''}}" onclick="addValueForEdit(this)" title="Edit Record">
                          <i class="fas fa-edit text-warning"></i>
                        </a>&nbsp;
                        
                        <a href="#" title="Delete Record" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{$value->id}}" data-usd="{{siteformat_number($value->usd)}}" data-yen="{{$value->yen}}" data-ot="{{$value->ot_exchange_rate}}" data-date="{{\Carbon\Carbon::parse($value->date)->format('F, Y')}}" onclick="addValueForDelete(this)" id="deleteModal{{$key}}">
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


      @include('salarymanagement.paymentexchange.create')
      @include('salarymanagement.paymentexchange.edit')
      <!-- /.modal -->

      <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete Payment Exchange Rate</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('salary-management/payment-exchange-rate/delete')}}" method="post">
            <div class="modal-body">
                <input type="hidden" name="id" id="del_id">
                    
                @csrf
                <p>Are you sure want to delete the exchange rate usd "<strong id="del_usd"></strong>" for <strong id="del_date"></strong>?</p>
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
        
        $('#payment_date_picker').datetimepicker({
          format: 'DD/MM/YYYY'
        });
        $('#edit_payment_date_picker').datetimepicker({
          format: 'DD/MM/YYYY'
        });
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

        $('#exchange_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        });
        
    });
    function isNumberKey(evt,id_attr){
      var charCode = (evt.which) ? evt.which : event.keyCode;
      //console.log("hello");
      if (charCode > 31 && (charCode < 45 || charCode > 57))
      {
          $("#"+id_attr).css("border","2px solid red");
          $("small."+id_attr).html("Please fill valid number");
          return false;
      }
      else{
          $("#"+id_attr).css("border","1px solid #999");
          $("small."+id_attr).html("");
      }
      return true;
    }
    
    function addValueForEdit(btn){
    
      var id=$(btn).data('id');
      var date=$(btn).data('date');
      var usd=$(btn).data('usd');
      var yen=$(btn).data('yen');
      var ot=$(btn).data('ot');
      var paymentdate=$(btn).data('paymentdate');
      var index=$(btn).data('index');

      $(".editModal #edit_id").val(id);
      
      $(".editModal #edit_date").val(date);
      $(".editModal #edit_usd").val(usd);
      $(".editModal #edit_ot_exchange_rate").val(ot);
      $(".editModal #edit_payment_date").val(paymentdate);
    }

    function addValueForDelete(btn){
    
      var id=$(btn).data('id');
      var usd=$(btn).data('usd');
      var ot=$(btn).data('ot');
      var date=$(btn).data('date');
      $(".deleteModal #del_id").val(id);
      $(".deleteModal strong#del_usd").html(usd+" and "+ot);
      $(".deleteModal strong#del_date").html(date);
    }
  </script>
@stop