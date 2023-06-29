<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

	@if($change_request->status==1)
	<p>Your Attendance Change Request for {{siteformat_date($change_request->changing_date)}} is accepted by Department Manager ({{getUserFieldWithId($change_request->status_change_by,'employee_name')}}).</p>
	@else
	<p>Your Attendance Change Request for {{siteformat_date($change_request->changing_date)}} is rejected by Department Manager ({{getUserFieldWithId($change_request->status_change_by,'employee_name')}}).</p>
	{{-- <strong>Reason : {{$change_request->status_reason}}</strong><br> --}}
	
	@endif
	<br>
	<br>
	@if($change_request->status_reason)
	<p><strong>Reason : </strong>{{$change_request->status_reason}}</p>
	@endif
<br> 
<br> 
Thank you