<div class="modal fade editModal" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="col-md-12 p-0" id="error-alert" style="display:none;">
                <div class="alert alert-dismissible " role="alert" style="font-size: 12px" id="alert_error">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong></strong>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title">Edit Payment Exchange Rate</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('salary-management/payment-exchange-rate/update')}}" method="post" class="prevent-multiple-submit-modal">
                @if(session('error'))
                <div class="col-md-12 p-0">
                    <div class="alert alert-danger alert-dismissible " role="alert" style="font-size: 12px">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <small><strong>{{session('error')}}</strong></small>
                    </div>
                </div>
                @endif
                @csrf
                <div class="modal-body">
                    
                    <div class="form-group row">
                        <label for="date" class="col-md-12">Payment For<span class="required text-danger">*</span></label>
                        <input type="hidden" name="id" id="edit_id">
                        <input type="month" name="date" id="edit_date" value="{{old('date')}}" class="form-control" required readonly  />
                            
                    </div>
                    <div class="form-group row">
                        <label for="date" class="col-md-12">Exchange Rate (1USD=MMK)<span class="required text-danger">*</span></label>
                        
                        <input type="text" name="usd" id="edit_usd" value="{{old('usd')}}" class="form-control" onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" autocomplete="off" required />
                        <small class="text-danger usd"></small>
                            
                    </div>
                    <div class="form-group row">
                        <label for="ot_exchange_rate" class="col-md-12">OT Exchange Rate (1USD=MMK)<span class="required text-danger">*</span></label>
                        
                        <input type="text" name="ot_exchange_rate" id="edit_ot_exchange_rate" value="{{old('ot_exchange_rate')}}" class="form-control" onkeypress="return isNumberKey(event,'ot_exchange_rate')" onchange="return isNumberKey(event,'ot_exchange_rate')" autocomplete="off" required />
                        <small class="text-danger ot_exchange_rate"></small>
                            
                    </div>
                    <div class="form-group row">
                        <label for="payment_date" class="col-md-12">Payment Date<span class="required text-danger">*</span></label>
                        <div class="input-group date" id="edit_payment_date_picker" data-target-input="nearest">
                            <input type="text" name="payment_date" id="edit_payment_date" value="{{old('payment_date')}}" class="form-control datetimepicker-input" required data-target="#edit_payment_date_picker" />
                            <div class="input-group-append" data-target="#edit_payment_date_picker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
            
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>