<h3>Dear {{ $admin_name }},</h3>
<p>I would like to inform the Leave information for marubeni integrated management system.</p>
<p>We have approve </p>
<p>employee name - {{ $employee_name }}</p>
<p>from  - {{ $from_date }} </p>
<p>to  - {{ $to_date }}  is {{ $status }} by admin </p>
@if(!empty($approve_reason_by_GM))
<p>{{ $approve_reason_by_GM }}</p>
@endif
<p>To Approve Leave<a href="https://marubeniyangon.com.mm/leave-management/leave-requests-approve-gm"> Click Here</a></p>
<h3>Thank you,</h3>
<h3>{{ $department_name }}</h3>

