<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

<p>The staff ({{getUserFieldWithId($change_request->user_id,'employee_name')}}) requested to change attendance time for {{siteformat_date($change_request->changing_date)}}. Please check to approved it.</p>
<br>
<p>
<a href="https://marubeniyangon.com.mm/attendance-management/change-request/list">Approved It</a></p>
<br>
<br>
Thank you