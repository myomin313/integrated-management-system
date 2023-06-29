<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

	<p>Admin ({{getUserFieldWithId($change_by,'employee_name')}}) accepted all attendance of the staff ({{getUserFieldWithId($monthlyot->user_id,'employee_name')}}) for {{\Carbon\Carbon::parse($monthlyot->date)->format('F, Y')}}. <br>Please check the following link.</p>
	<br><br>
	
	<a href="https://marubeniyangon.com.mm/ot-management/monthly-receptionist/approved-by-admin-gm">Approved It</a></p>

<br>   <br>
Thank you