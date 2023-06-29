<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>


@if($emp_type=="staff")
	<p>The staff ({{$applicant->employee_name?$applicant->employee_name:$applicant->name}}) requested monthly overtime for the following date : </p><br>
	@foreach($apply_date as $value)
		<strong>{{siteformat_date($value)}}</strong><br>
	@endforeach

	<p>Please check it.</p>

	<br><br>
	<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-staff/approved-by-dept-gm">Approved It</a>
@else
	<p>There are overtime of Assistant and Driver for {{\Carbon\Carbon::parse($apply_date)->format("F, Y")}} </p><br>
	
	<p>Please check it to approve.</p>

	<br><br>
	<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-driver/approved-by-admin">Approved It</a>
@endif

<br>
<br>
    
Thank you