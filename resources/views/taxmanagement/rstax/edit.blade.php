	<div class="modal fade editModal" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Edit RS Actual Income Tax</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('tax-management/actual-tax/rs-income-tax-update')}}" method="POST" id="edit_tax">
            <div class="modal-body">
	             @csrf
            	<div class="form-group row">
            		    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="index" id="index">
                    <label for="tax_for" class="col-sm-3 col-form-label">Pay For<span class="required text-danger">*</span></label>
                    <div class="col-sm-9">
                    	<input type="month" id="tax_for" name="tax_for" class="form-control" placeholder="Month YYYY" required autocomplete="off">
	                    
                    </div>
	                    
              </div>
              <div class="form-group row">
                    
                    <label for="user_id" class="col-sm-3 col-form-label">Employee<span class="required text-danger">*</span></label>
                    <div class="col-sm-9">
                      <select class="form-control select2bs4" name="user_id" id="user_id" required style="width: 100%;">
                        <option selected="">- Select -</option>
                        @foreach($employees as $value)
                          <option value="{{$value->id}}">{{$value->employee_name?$value->employee_name:$value->name}}</option>
                        @endforeach

                      </select>
                      
                    </div>
                      
              </div>
              
              
              <div class="form-group row">
                    
                    <label for="tax_for" class="col-sm-3 col-form-label">Tax Amount (MMk)<span class="required text-danger">*</span></label>
                    <div class="col-sm-9">
                      <input type="text" id="tax_amount_mmk0" name="tax_amount_mmk" class="form-control" required autocomplete="off" onkeyup="calculateUSD(0)" onchange="calculateUSD(0)">
                      
                    </div>
                      
              </div>
              <div class="form-group row">
                    
                    <label for="exchange_rate0" class="col-sm-3 col-form-label">Exchange Rate<span class="required text-danger">*</span></label>
                    <div class="col-sm-9">
                      <input type="text" id="exchange_rate0" name="exchange_rate" class="form-control" required autocomplete="off" onkeyup="calculateUSD(0)" onchange="calculateUSD(0)">
                      
                    </div>
                      
              </div>
              <div class="form-group row">
                    
                    <label for="tax_amount_usd0" class="col-sm-3 col-form-label">Tax Amount (USD)<span class="required text-danger">*</span></label>
                    <div class="col-sm-9">
                      <input type="text" id="tax_amount_usd0" name="tax_amount_usd" class="form-control" required readonly autocomplete="off">
                      
                    </div>
                      
              </div>
              <div class="form-group row">
                    
                    <label for="pay_date" class="col-sm-3 col-form-label">Pay Date<span class="required text-danger">*</span></label>
                    <div class="col-sm-9">
                      <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                        <input type="text" name="pay_date" id="pay_date" class="form-control datetimepicker-input" required data-target="#datetimepicker3" />
                        <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                        </div>
                      </div>
                      
                    </div>
                      
              </div>
            </div>
            
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Update</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>