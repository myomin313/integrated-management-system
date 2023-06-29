<div class="modal fade" id="modal-ot-approve">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">OT Approve Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('ot-management/daily-ot-request/change-status')}}" method="post" class="prevent-multiple-submit-modal">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="status_change_id">
                    <input type="hidden" name="index" id="status_change_index">
                    <input type="hidden" name="from_date" id="status_from_date" value="{{isset($from_date)?$from_date:''}}">
                    <input type="hidden" name="from_date" id="status_from_date" value="{{isset($to_date)?$to_date:''}}">
                    <input type="hidden" name="employee" id="status_employee" value="{{isset($employee)?$employee:'all'}}">
                    <input type="hidden" name="department" id="status_department" value="{{isset($department)?$department:'all'}}">
                    <input type="hidden" name="status" id="status_status" value="{{isset($status)?$status:'all'}}">
                    <input type="hidden" name="monthly_request" id="status_monyhly_request" value="{{isset($monthly_request)?$monthly_request:0}}">

                    <div class="row">
                        <div class="col-md-6 col-sm-6 text-right">
                            <img src="{{asset('dist/img/avatar5.png')}}" style="width:100px;height:100px" class="img-circle"/>
                        </div>
                        <div class="col-md-6 col-sm-6 text-left">
                            <span id="request_user_name">Aung Aung</span><br>
                            <span id="user_position">Engineer</span><br>
                            <span id="user_department">IT Department</span><br>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-6 col-sm-6">
                            <span class="text-bold">Apply Date :</span>
                            <span class="text-primary" id="ot_apply_date"> 20-12-2022</span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <span class="text-bold">OT Type :</span>
                            <span class="text-primary" id="ot_request_type">Regular  Holiday</span>
                        </div>
                        
                    </div>
                                    
                    <div class="row p-2">
                        <div class="col-md-6 col-sm-6">
                            <span class="text-bold">Start Time :</span>
                            <span class="text-primary" id="ot_request_start_time"> 20-12-2022</span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <span class="text-bold">End Time :</span>
                            <span class="text-primary" id="ot_request_end_time"> 20-12-2022</span>
                        </div>
                    </div>
                                   
                    <div class="row p-2">
                        <div class="col-md-6 col-sm-6">
                            <span class="text-bold">Break Time :</span>
                            <span class="text-primary" id="ot_break_time"> 1 hr 30 min</span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <span class="text-bold">Reason:</span>
                            <span id="ot_request_reason">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            </span>
                            
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-12 col-sm-12">
                            <span class="text-bold">Manager Reason:</span>
                            <span id="status_change_reason">
                                <textarea class="form-control" name="status_reason"></textarea>
                            </span>
                            
                            
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        
                        <button type="submit" name="reject" id="reject" class="btn btn-danger">Reject</button>
                        <button type="submit" name="accept" id="accept" class="btn btn-success">Accept</button>
                
                    </div>
                </div>
            </form>
                        
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>