<div class="modal fade" id="modal-ot-start-request">
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
                <h4 class="modal-title">OT Start Request Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('ot-management/daily-ot-request/store')}}" method="post" class="prevent-multiple-submit-modal">
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
                    <div class="form-group">
                        <label for="ot_type">OT Type<span class="required text-danger">*</span></label>
                        <select class="form-control" id="ot_type" name="ot_type" required>
                            <option selected value="">-Select OT Type-</option>
                            <option value="Weekday" {{old('ot_type')=="Weekday"?'selected':''}}>Weekday</option>
                            <option value="Saturday" {{old('ot_type')=="Saturday"?'selected':''}}>Saturday</option>
                            <option value="Sunday" {{old('ot_type')=="Sunday"?'selected':''}}>Sunday</option>
                            <option value="Public Holiday" {{old('ot_type')=="Public Holiday"?'selected':''}}>Public Holiday</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="apply_date" class="col-md-12">Apply Date<span class="required text-danger">*</span></label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input type="text" name="apply_date" id="apply_date" value="{{old('apply_date')}}" class="form-control datetimepicker-input" required data-target="#datetimepicker2" />
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        
                        <div class="col-md-6">
                            
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox2" value="1" name="start_hotel" {{old('start_hotel')==1?'checked':''}}>
                                <label for="customCheckbox2" class="custom-control-label text-primary">Hotel</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox3" name="start_next_day" value="1" {{old('start_next_day')==1?'checked':''}}>
                                <label for="customCheckbox3" class="custom-control-label text-primary">Next Day</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="start_from_time" class="col-md-12">OT Time<span class="required text-danger">*</span></label>
                        <div class="col-md-6">
                            
                            <div class="input-group date" id="timepicker" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#timepicker" placeholder="from" id="start_from_time" name="start_from_time" value="{{old('start_from_time')}}" required/>
                              <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                              </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            <div class="input-group date" id="timepicker1" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#timepicker1" placeholder="to" id="start_to_time" name="start_to_time" value="{{old('start_to_time')}}" required/>
                              <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="start_break_hour" class="col-md-12">Break Time<span class="required text-danger"></span></label>
                        <div class="col-md-6">
                            <!-- <label>Hour</label> -->
                            <input type="number" class="form-control" placeholder="hour" id="start_break_hour" name="start_break_hour" value="{{old('start_break_hour')}}">
                        </div>
                        <div class="col-md-6">
                            <!-- <label>Minute</label> -->
                            <input type="number" class="form-control" placeholder="minute" id="start_break_minute" name="start_break_minute" value="{{old('start_break_minute')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="start_reason">Reason<span class="required text-danger">*</span></label>
                        <textarea id="start_reason" name="start_reason" class="form-control" required>{{old('start_reason')}}</textarea>
                    </div>
                
                
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Send</button>
                </div>
            </form>
        </div>
            
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>