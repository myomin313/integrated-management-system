<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

<p>The staff ({{getUserFieldWithId($change_request->user_id,'employee_name')}}) cancel attendance change request for {{siteformat_date($change_request->changing_date)}}.</p>
<br>
<br>
<strong>Reason * : {{$reason}}</strong>

<br>

    <br><br>
Thank you