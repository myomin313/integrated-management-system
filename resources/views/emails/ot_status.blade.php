<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

@if($approved_by=="manager")
	@if($ot->status==1)
	<p>Your {{$ot->ot_type}} OT for {{siteformat_date($ot->apply_date)}} is accepted by Department Manager ({{getUserFieldWithId($ot->status_change_by,'employee_name')}}).</p>
	@else
	<p>Your {{$ot->ot_type}} OT for {{siteformat_date($ot->apply_date)}} is rejected by Department Manager ({{getUserFieldWithId($ot->status_change_by,'employee_name')}}).</p>
	<strong>Reason : {{$ot->status_reason}}</strong><br>
	
	@endif

@endif

<br> 
Thank you