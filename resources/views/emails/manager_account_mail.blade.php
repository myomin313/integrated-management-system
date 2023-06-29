<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

@if($type=="accountant")
	
	<p>Accountant ({{getUserFieldWithId($change_by,'employee_name')}}) rejected the following overtime for the staff ({{$applicant->employee_name?$applicant->employee_name:$applicant->name}}).</p><br>
	<table>
		<tr>
			<th>Date</th>
			<th> </th>
			<th>OT Type</th>

		</tr>
		@foreach($ot as $value)
		<tr>
			<td>{{siteformat_date($value->apply_date)}}</td>
			<td> </td>
			<td>{{$value->ot_type}}</td>
		</tr>
		@endforeach
	</table>
	<br>
	<strong>The Reason : {{$value->account_status_reason}}</strong>
	<br>
	@if($emp_type=="staff")
		
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-staff/approved-by-dept-gm">Check It</a></p>
	@else
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-driver/approved-by-admin">Check It</a></p>
	@endif

@elseif($type=="gm-manager")
	<p>Your Monthly  OT is changed by Admin GM  ({{getUserFieldWithId($change_by,'employee_name')}}) with the following status.</p><br>
	<table>
		<tr>
			<th>Date</th>
			<th> </th>
			<th>OT Type</th>
			<th> </th>
			<th>Status</th>
		</tr>
		@foreach($ot as $value)
		<tr>
			<td>{{$value['date']}}</td>
			<td> </td>
			<td>{{$value['ot_type']}}</td>
			<td> </td>
			<td>{{$value['status']==1?'Accept':'Reject'}}</td>
		</tr>
		@endforeach
	</table>
	<br>
	@if($emp_type=="staff")
		
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-staff/approved-by-dept-gm">Check It</a></p>
	@else
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-driver/approved-by-admin">Check It</a></p>
	@endif
@else
	<p>Your Monthly  OT is changed by Admin GM  ({{getUserFieldWithId($change_by,'employee_name')}}) with the following status.</p><br>
	<table>
		<tr>
			<th>Date</th>
			<th> </th>
			<th>OT Type</th>
			<th> </th>
			<th>Status</th>
		</tr>
		@foreach($ot as $value)
		<tr>
			<td>{{$value['date']}}</td>
			<td> </td>
			<td>{{$value['ot_type']}}</td>
			<td> </td>
			<td>{{$value['status']==1?'Accept':'Reject'}}</td>
		</tr>
		@endforeach
	</table>
	<br>
	@if($emp_type=="staff")
		
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-staff/approved-by-account">Check It</a></p>
	@else
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-driver/approved-by-account">Check It</a></p>
	@endif
@endif

<br> 
<br> 
Thank you