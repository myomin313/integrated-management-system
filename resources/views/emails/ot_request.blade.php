<h3>Dear {{$user->employee_name?$user->employee_name:$user->name}},</h3>
@if($type=="start")
<p>The staff ({{getUserFieldWithId($ot->user_id,'employee_name')}}) made pre overtime request for {{siteformat_date($ot->apply_date)}}. Please check the following link.</p>
@else
<p>The staff ({{getUserFieldWithId($ot->user_id,'employee_name')}}) made post overtime request for {{siteformat_date($ot->apply_date)}}. Please check the following link.</p>
@endif
<br>
<a href="https://marubeniyangon.com.mm/ot-management/daily-ot-request/list?employee=all&department=all&status=0&from_date=&to_date=">Approved It</a></p>
    <br><br>
Thank you