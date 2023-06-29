<div class="modal fade" id="time_in">
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
              <h4 class="modal-title">Time In</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('dashboard/check-in')}}" method="post" class="prevent-multiple-submit-modal">
              
              @csrf
              <div class="modal-body">
                
               
                <div class="form-group">
                  <label for="usage_date">If you have OT, click "OT" button. Otherwise, click "No" button</label>
                  
                </div>
                
              </div>
              <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-primary" name="no_btn">No</button>
                <button type="submit" name="ot_btn" class="btn btn-success">OT</button>
              </div>
              
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>