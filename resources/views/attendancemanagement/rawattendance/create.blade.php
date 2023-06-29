      <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Attandance</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('raw-attendance.store')}}" method="post" class="prevent-multiple-submit-modal">
              @csrf
              <div class="modal-body">
                <div class="form-group ">
                  <label for="user_id">Name<span class="required text-danger">*</span></label>
                  <select class="form-control select2bs4" name="user_id" id="user_id" style="width: 100%;" required>
                    <option selected="selected" value="">- Employee Name -</option>
                    @foreach($employees as $key=>$value)
                      <option value="{{$value->id}}">{{$value->employee_name?$value->employee_name:$value->name}}</option>    
                    @endforeach    
                  </select>
                </div>
                <div class="form-group">
                  <label for="date">Date<span class="required text-danger">*</span></label>
                  <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                    <input type="text" name="date" id="date" required placeholder="dd/mm/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="time">Time<span class="required text-danger">*</span></label>
                  <div class="input-group date" id="timepicker" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" data-target="#timepicker" id="time" name="time" required/>
                      <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                  </div>
                    <!-- /.input group -->
                </div>
                <div class="form-group">
                  <label for="amount">Branch<span class="required text-danger">*</span></label>
                  <select class="form-control" name="branch" id="branch" required>
                    <option value="" selected>-Select-</option>
                    @foreach($branches as $key=>$value)
                      <option value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="amount">Reason<span class="required text-danger">*</span></label>
                  <textarea class="form-control" name="reason" required></textarea>
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