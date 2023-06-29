<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>

<p>The staff ({{getUserFieldWithId($ot->user_id,'employee_name')}}) cancel overtime request for {{siteformat_date($ot->apply_date)}}.</p>
<br>
<br>
<strong>Reason * : {{$reason}}</strong>

<br>

    <br><br>
Thank you