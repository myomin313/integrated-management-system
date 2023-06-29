<div class="modal fade endModal" id="modal-ot-end-request">
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
                <h4 class="modal-title">OT End Request Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('ot-management/daily-ot-request/update')}}" method="post" class="prevent-multiple-submit-modal">
                @if(session('end_error'))
                <div class="col-md-12 p-0">
                    <div class="alert alert-danger alert-dismissible " role="alert" style="font-size: 12px">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <small><strong>{{session('end_error')}}</strong></small>
                    </div>
                </div>
                @endif
                @csrf
                <input type="hidden" name="id" id="end_id" value="{{old('id')}}">
                <input type="hidden" name="index" id="end_index" value="{{old('index')}}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ot_type">OT Type<span class="required text-danger">*</span></label>
                        <select class="form-control" id="end_ot_type" name="ot_type" required>
                            <option selected value="">-Select OT Type-</option>
                            <option value="Weekday" {{old('ot_type')=="Weekday"?'selected':''}}>Weekday</option>
                            <option value="Saturday" {{old('ot_type')=="Saturday"?'selected':''}}>Saturday</option>
                            <option value="Sunday"{{old('ot_type')=="Sunday"?'selected':''}}>Sunday</option>
                            <option value="Public Holiday" {{old('ot_type')=="Public Holiday"?'selected':''}}>Public Holiday</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="apply_date" class="col-md-12">Apply Date<span class="required text-danger">*</span></label>
                        <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                            <input type="text" name="apply_date" id="end_apply_date" class="form-control datetimepicker-input" value="{{old('apply_date')}}" required data-target="#datetimepicker3" />
                            <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        
                        <div class="col-md-6">
                            
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox4" value="1" name="start_hotel" {{old('start_hotel')==1?'checked':''}}>
                                <label for="customCheckbox4" class="custom-control-label text-primary">Hotel</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox5" name="start_next_day" value="1" {{old('start_next_day')==1?'checked':''}}>
                                <label for="customCheckbox5" class="custom-control-label text-primary">Next Day</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="end_from_time" class="col-md-12">OT Time<span class="required text-danger">*</span></label>
                        <div class="col-md-6">
                            <!-- <input type="time" class="form-control" placeholder="from" id="end_from_time" name="end_from_time" required> -->
                            <div class="input-group date" id="timepicker2" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#timepicker2" placeholder="from" id="end_from_time" name="end_from_time" value="{{old('end_from_time')}}" required/>
                              <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                              </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- <input type="time" class="form-control" placeholder="to" id="end_to_time" name="end_to_time" required> -->
                            <div class="input-group date" id="timepicker3" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#timepicker3" placeholder="to" id="end_to_time" name="end_to_time" value="{{old('end_to_time')}}"  required/>
                              <div class="input-group-append" data-target="#timepicker3" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="end_break_hour" class="col-md-12">Break Time<span class="required text-danger"></span></label>
                        <div class="col-md-6">
                            <!-- <label>Hour</label> -->
                            <input type="number" class="form-control" placeholder="hour" id="end_break_hour" name="end_break_hour" value="{{old('end_break_hour')}}">
                        </div>
                        <div class="col-md-6">
                            <!-- <label>Minute</label> -->
                            <input type="number" class="form-control" placeholder="minute" id="end_break_minute" name="end_break_minute" value="{{old('end_break_minute')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end_reason">Reason<span class="required text-danger">*</span></label>
                        <textarea id="end_reason" name="end_reason" class="form-control" required>{{old('end_reason')}}</textarea>
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