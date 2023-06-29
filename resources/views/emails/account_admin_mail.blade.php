<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

@if($type=="manager")
	<p>Department Manager ({{getUserFieldWithId($change_by,'employee_name')}}) accepted all overtime of the staff ({{getUserFieldWithId($monthlyot->user_id,'employee_name')}}) for {{\Carbon\Carbon::parse($monthlyot->date)->format('F, Y')}}. <br>Please check the following link.</p>
	<br><br>
	@if($emp_type=="staff")
		
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-staff/approved-by-account">Approved It</a></p>
	@else
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-driver/approved-by-account">Approved It</a></p>
	@endif
@else
	<p>Accountant ({{getUserFieldWithId($change_by,'employee_name')}}) accepted all overtime of the staff ({{getUserFieldWithId($monthlyot->user_id,'employee_name')}}) for {{\Carbon\Carbon::parse($monthlyot->date)->format('F, Y')}}. <br>Please check it.</p>
	<br><br>
	@if($emp_type=="staff")
		
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-staff/approved-by-admin-gm">Approved It</a></p>
	@else
		<a href="https://marubeniyangon.com.mm/ot-management/monthly-ot-driver/approved-by-admin-gm">Approved It</a></p>
	@endif
@endif
<br>   <br>
Thank you