<h3>Dear {{ $dep_person_name }},</h3>

<p>I would like to inform the Leave information for marubeni integrated management system.</p>
<p>I would like to Cancel My Leave Request  For </p>
<p>from  - {{ $from_date }} </p>
<p>to  - {{ $to_date }} </p>
@if(!empty($leave_cancel_reason))
<p>{{ $leave_cancel_reason }}</p>
@endif
@if($check_ns_rs == 1 )
<p>To Approve Leave  <a href="https://marubeniyangon.com.mm/leave-management/leave-requests-approve">click here</a></p>
@else
<p>To Approve Leave  <a href="https://marubeniyangon.com.mm/leave-management/leave-requests-admin-approve">click here</a></p>
@endif
<h3>Best Regards,</h3>
<h3>{{ $name }}</h3>
