      <div class="modal fade" id="modal-create">
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
              <h4 class="modal-title">Change Request</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('change-request.store')}}" method="post" class="prevent-multiple-submit-modal">
              @csrf
              <div class="modal-body">
                
                {{-- <div class="form-group">
                  <label for="actual_date">Current Date<span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                    <input type="text" name="actual_date" id="actual_date" required placeholder="dd/mm/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>
                </div> --}}
                <div class="form-group">
                  <label for="changing_date">Apply Date<span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                    <input type="text" name="changing_date" id="changing_date" required placeholder="dd/mm/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker3"/>
                    <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="time">Attendance Request<span class="required text-danger"></span></label>
                  <div class="row">
                    <div class="col-md-6">
                      <!-- <input type="time" class="form-control" id="changing_start_time" name="changing_start_time" required> -->
                      <div class="input-group date" id="timepicker" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#timepicker" id="changing_start_time" name="changing_start_time" />
                          <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      
                      <div class="input-group date" id="timepicker1" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#timepicker1" id="changing_end_time" name="changing_end_time" />
                          <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                      </div>
                    </div>
                    
                  </div>
                    
                  
                </div>
                <div class="form-group">
                  <label for="time">Changing Time (Shift)<span class="required text-danger"></span></label>
                  <div class="row">
                    <div class="col-md-6">
                      
                      <div class="input-group date" id="timepicker2" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#timepicker2" id="working_start_time" name="working_start_time" />
                          <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      
                      <div class="input-group date" id="timepicker3" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#timepicker3" id="working_end_time" name="working_end_time" />
                          <div class="input-group-append" data-target="#timepicker3" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                      </div>
                    </div>
                    
                  </div>
                    
                  
                </div>
                <div class="form-group">
                  <label for="reason_of_correction">Reason<span class="required text-danger">*</span></label>
                  <textarea class="form-control" name="reason_of_correction" required></textarea>
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
      <!-- /.modal -->