@extends('layouts.master')
@section('title','Dashboard')
@section('content')

<div class="row">
 <a class="btn btn-primary m-auto" href="{{ url('/leave-certificate/download/'.$certificate ) }}">download file</a>
 </div>
<div class="row"> 
<embed src="{{ $url }}" class="col-md-10 m-auto" height="600px"/>
</div>
 </section>
@stop