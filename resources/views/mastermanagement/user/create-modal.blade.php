	  <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create New User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('user.store')}}" method="post" id="create_user">
            <div class="modal-body">
            	@csrf
            	<div class="form-group row">
                    <label for="noti_type" class="col-sm-3 col-form-label">Noti Type</label>
                    <div class="col-sm-9">
                    	<div class="form-check" style="margin-top:9px;">
	                        <input class="form-check-input" type="radio" name="noti_type" value="email" checked>
	                        <label class="form-check-label">Email</label>
	                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                        <input class="form-check-input" type="radio" name="noti_type" value="phone">
	                        <label class="form-check-label">Phone</label>
	                    </div>
	                    
                    </div>
	                    
                </div>
                <div class="form-group" id="phone_section">
                    <label for="phone">Phone <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group" id="email_section">
                    <label for="email">Email <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="email" name="email" autofocus required>
                </div>
                <div class="form-group">
                    <label for="name">Username <span class="required text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Eg. aunguang" required>
                </div>
                <div class="form-group ">
                    <label for="position_id">Position <span class="required text-danger">*</span></label>
                  
                    <select class="form-control select2bs4" name="position_id" id="position_id" required style="width: 100%;">
                        <option selected="selected">- Select -</option>
                        @foreach($positions as $key=>$value)
                        	<option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach

                    </select>
              	</div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->