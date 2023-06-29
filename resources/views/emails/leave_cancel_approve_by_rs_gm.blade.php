<h3>Dear {{$name}},</h3>

<p>I would like to inform the Leave information for marubeni integrated management system.</p>
<p>You Leave Request Cancel For </p>
<p>from  - {{ $from_date }} </p>
<p>to  - {{ $to_date }}  is {{ $status }} </p>
@if(!empty($cancel_leave_approve_reason_by_RS_GM))
<p>Aprrove Reason By ADMI GM</p>
<p>{{ $cancel_leave_approve_reason_by_RS_GM }}</p>
@endif
<h3>Thank you,</h3>
<h3>GM</h3>
