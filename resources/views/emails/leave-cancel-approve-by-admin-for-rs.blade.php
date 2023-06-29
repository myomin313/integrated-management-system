<h3>Dear {{ $admin_name }},</h3>

<p>I would like to inform the Leave information for marubeni integrated management system.</p>
<p>We have approve </p>
<p>employee name - {{ $name }}</p>
<p>from  - {{ $from_date }} </p>
<p>to  - {{ $to_date }}  is {{ $status }} </p>
@if(!empty($cancel_leave_approve_reason_by_admi_manager))
<p>{{ $cancel_leave_approve_reason_by_admi_manager }}</p>
@endif
<p>To Approve Leave<a href="https://marubeniyangon.com.mm/leave-management/leave-requests-approve-gm"> Click Here</a></p>
<h3>Thank you,</h3>
<h3>{{ $department_name }}</h3>
