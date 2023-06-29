<h3>Dear {{$employee_name}},</h3>

<p>I would like to inform the Leave information for marubeni integrated management system.</p>
<p>You Request For </p>
<p>from  - {{ $from_date }} </p>
<p>to  - {{ $to_date }}  is {{ $status }} </p>
@if(!empty($approve_reason_by_dep_manager))
<p>Aprrove Reason By Dept Manager GM</p>
<p>{{  $approve_reason_by_dep_manager  }}</p>
@endif
<h3>Thank you,</h3>
<h3>{{ $department_name }}</h3>
