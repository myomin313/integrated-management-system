<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>


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
	
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-receptionist/approved-by-admin">Check It</a></p>
	


<br> 
<br> 
Thank you