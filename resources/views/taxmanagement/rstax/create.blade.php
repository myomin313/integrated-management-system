@extends('layouts.master')
@section('title','Add RS Actual Income Taxs')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/actual-tax/rs-income-tax-list')}}">RS Actual Income Tax</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-6">
          
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 m-auto">
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
                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>{{session('success_create')}}</strong>
                </div>
              </div>
            @endif
            <div class="card" style="padding:20px;">
              <div class="card-header">
                <h3 class="card-title">Add Actual Income Tax for RS</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="{{route('rs-tax.store')}}" method="post" id="create_user" class="prevent-multiple-submit">
                  	@csrf
                  	<div class="row">
                        
                        <table class="table" id="rs_tax_records">
                            <thead>
                            	<tr>
                            		<th>No.</th>
                            		<th>Pay For<span class="required text-danger">*</span></th>
                            		<th>Employee Name<span class="required text-danger">*</span></th>
                            		<th>Tax Amount (MMk)<span class="required text-danger">*</span></th>
                            		<th>Exchange Rate<span class="required text-danger">*</span></th>
                            		<th>Tax Amount (USD)<span class="required text-danger">*</span></th>
                            		<th>Pay Date<span class="required text-danger">*</span></th>
                            		<th></th>
                            	</tr>
                            </thead>
                            <tbody>
                              <tr>

                                <td style="padding-left: 9px;vertical-align: middle;border-top: none;padding-top: 25px;"><small id="snum0" style="font-size:14px;">1</small></td>
                                <td style="border-top: none;">
                                  
                                  <input type="month" id="tax_for0" name="tax_for[]" class="form-control" placeholder="Month YYYY" required autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  
                                  <div>
                                    
                                    <div style="width:200px;" id="employee0">
                                        
                                        
                                      <select class="form-control select2bs4" name="user_id[]" id="user_id0" required style="width: 100%;">
                                        <option selected="">- Select -</option>
                                        @foreach($employees as $value)
                                        <option value="{{$value->id}}">{{$value->employee_name?$value->employee_name:$value->name}}</option>
                                        @endforeach

                                      </select>
                                    </div>
                                    
                                      
                                  </div>
                                    
                                    
                                </td>
                                <td style="border-top: none;">
                                  
                                  <input type="text" id="tax_amount_mmk0" name="tax_amount_mmk[]" class="form-control" required autocomplete="off" onkeyup="calculateUSD(0)" onchange="calculateUSD(0)">
                                </td>
                                <td style="border-top: none;">
                                  
                                  <input type="text" id="exchange_rate0" name="exchange_rate[]" class="form-control" required autocomplete="off" onkeyup="calculateUSD(0)" onchange="calculateUSD(0)">
                                </td>
                                <td style="border-top: none;">
                                 
                                  <input type="text" id="tax_amount_usd0" name="tax_amount_usd[]" class="form-control" required readonly autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  
                                  <input type="text" id="pay_date0" name="pay_date[]" class="form-control pay_date" required autocomplete="off">
                                </td>
                                <td>
                                  
                                  <div class="delete-row btn btn-danger remove" onclick="delete_Row_RsTax(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div>
                                </td>
                               
                              </tr>


                            </tbody>
                        </table>
                        
                    </div>

                	<div class="col-md-12 col-sm-12">
                        <a class="btn btn-success breadcrumb-btn rs-tax-addmore"><i class="fas fa-plus"></i> Add</a>
                    </div>
                  	<div class="form-group text-center">
                    	<button type="submit" class="btn btn-success" name="save_new">Save & New</button>
                    	<button type="submit" class="btn btn-info" name="save">Save</button>
                    	<a href="{{route('rs-tax.list')}}" type="button" class="btn btn-primary">Cancel</a>
                      
                  	</div>
                </form>
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
  
      
    </section>
    <!-- /.content -->

@stop
@section('script')
<script>
    $(function () {
      
      	$('.select2bs4').select2({
        	theme: 'bootstrap4'
      	});

      	$(document).on('focus', '.pay_date',function(){               
            $(this).datepicker({
            	format: 'DD/MM/YYYY'
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

      $("#tax_amount_usd"+$i).val(amount.toFixed());
      
    
    }
    var n = 0;
    $(".rs-tax-addmore").on("click", function () {
	    count = $("table#rs_tax_records tr").length;
	    var num = get_row_num("table#rs_tax_records");
	    n = Number(num) + 1;
    	var data = '';
    	data +='<tr><td style="padding-left: 9px;vertical-align: middle;border-top: none;padding-top: 25px;"><small id="snum'+n+'" style="font-size:14px;">'+count+'</small></td>';
    	data +='<td style="border-top: none;"><input type="month" id="tax_for'+n+'" name="tax_for[]" class="form-control" placeholder="Month YYYY" required autocomplete="off"></td>';
    	data +='<td style="border-top: none;"><div><div style="width:200px;" id="employee'+n+'"></div></div></td>';
    	data +='<td style="border-top: none;"><input type="text" id="tax_amount_mmk'+n+'" name="tax_amount_mmk[]" class="form-control" required autocomplete="off" onkeyup="calculateUSD('+n+')" onchange="calculateUSD('+n+')"></td>';
    	data +='<td style="border-top: none;"><input type="text" id="exchange_rate'+n+'" name="exchange_rate[]" class="form-control" required autocomplete="off" onkeyup="calculateUSD('+n+')" onchange="calculateUSD('+n+')"></td>';
    	data +='<td style="border-top: none;"><input type="text" id="tax_amount_usd'+n+'" name="tax_amount_usd[]" class="form-control" required readonly autocomplete="off"></td>';
    	data +='<td style="border-top: none;"><input type="text" id="pay_date'+n+'" name="pay_date[]" class="form-control pay_date" required autocomplete="off"></td>';
    	data +='<td><div class="delete-row btn btn-danger remove" onclick="delete_Row_RsTax(this)" title="Remove row"><i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i></div></td></tr>';
    	$("table#rs_tax_records tbody").append(data);

	    //copy family relationship
	    var sel = $("#user_id0");
	    var clone = sel.clone();

	    clone.attr("id", "user_id" + n);
	    clone.show();
	    clone
	      .insertAfter("#employee" + n)
	      .wrap("<div></div>")
	      .select2();

    	i++;
  	});
    function delete_Row_RsTax(row) {
	  var small = $(row).closest("tr").find("td small");
	  $(row).parents("tr").remove();
	  checkRSTax();
	  //delete_doneby(small);
	}
  	function checkRSTax() {
	  obj = $("table#rs_tax_records tbody tr").find("small");
	  $.each(obj, function (key, value) {
	    id = value.id;
	    console.log("small id " + id);
	    $("#" + id).html(key + 1);
	  });
	}

	function get_row_num(tbl_id) {
	  var last_row = $(tbl_id).find("tr").last();
	  console.log("Last Row : " + last_row[0]);
	  last_row = last_row[0];
	  var small = $(last_row).find("td small");
	  var sp = small[0];
	  console.log("small : " + sp);
	  if (typeof sp == "undefined" || sp == false) {
	    return 0;
	  }
	  var strid = sp.id;
	  var row = strid.replace("snum", "");
	  return row;
	}

  </script>
@stop