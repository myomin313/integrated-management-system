<h3>Dear {{$name}},</h3>

<p>I would like to inform the Leave information for marubeni integrated management system.</p>
<p>You Request For </p>
<p>from  - {{ $from_date }} </p>
<p>to  - {{ $to_date }}  is {{ $status }} by admin </p>
@if(!empty($approve_reason_by_GM))
<p>Aprrove Reason By ADMI GM</p>
<p>{{ $approve_reason_by_GM }}</p>
@endif
<h3>Thank you,</h3>
<h3>Admin Department</h3>
