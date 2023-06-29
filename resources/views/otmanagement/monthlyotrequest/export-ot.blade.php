
                <table>
                  <thead>
                    <tr>
                      <th colspan="6"></th>
                    </tr>
                    <tr>
                      <th colspan="6" style="font-size:16px"><strong>{{$user->employee_name}} ({{\Carbon\Carbon::parse($monthlyot->date)->format("F-Y")}})</strong></th>
                    </tr>
                    <tr>
                      <th colspan="6"></th>
                    </tr>
                    <tr>
                      <th><strong>Apply Date</strong></th>
                      <th><strong>Card Time</strong></th>
                      <th><strong>OT Time</strong></th>
                      <th><strong>Requested Reason</strong></th>
                      <th><strong>Requested Date</strong></th>
                      <th><strong>Approved by Dept GM</strong></th>
                      <th><strong>Approved by Account</strong></th>
                      <th><strong>Approved by Admin GM</strong></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    @php $inner = 0; @endphp
                    @foreach($monthlyotdetail as $key=>$value)
                      <tr>
                      	<td>{{siteformat_date($value->apply_date)}}<br>
                              ({{$value->ot_type}})</td>
                        <td>
                              <strong>Time In : </strong>{{getCardTime($value->apply_date,$value->user_id,'start_time')}}  <br>
                              <strong>Time Out : </strong>{{getCardTime($value->apply_date,$value->user_id,'end_time')}} <br>
                            
                            
                          </td>
                          <td>
                            @php
                              if($value->end_from_time){
                                $next_day = $value->end_next_day;
                                $hotel = $value->end_hotel;
                                $start_time = siteformat_time24($value->end_from_time);
                                $end_time = siteformat_time24_nextday($value->end_to_time,$next_day);
                                $break_hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                                $break_min = $value->end_break_minute?$value->end_break_minute.' min':'';
                                $break_time = $break_hour.' '.$break_min;
                                $reason = $value->end_reason;
                                
                              }
                              else{
                                $next_day = $value->start_next_day;
                                $hotel = $value->start_hotel;
                                $start_time = siteformat_time24($value->start_from_time);
                                $end_time = siteformat_time24_nextday($value->start_to_time,$next_day);
                                $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                                $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                                $break_time = $break_hour.' '.$break_min;
                                $reason = $value->start_reason;
                              }
                            @endphp
                            <strong>Start Time : </strong>{{$start_time}}<br>
                            <strong>End Time : </strong>{{$end_time}} {{$hotel?'(hotel)':''}}<br>
                            
                            <strong>Break Time : </strong>{{$break_time}} <br>
                            
                          </td>
                          <td>{{$reason}}</td>
                          <td>{{siteformat_datetime24($value->created_at)}}</td>
                          @if($value->manager_status==0)
                            @php $manager_status = 'Pending';$color = "#0c71ed"; @endphp
                          @elseif($value->manager_status==1)
                            @php $manager_status = 'Accept';$color = "#138f34"; @endphp
                          @else
                            @php $manager_status = 'Reject';$color = "#f51637"; @endphp
                          @endif
                          
                          <td style="color:{{$color}}">
                            <strong>
                              {{$manager_status}}<br>
                            </strong>
                            <strong>
                              {{$value->manager_change_by?getUserFieldWithId($value->manager_change_by,'employee_name'):''}}<br>
                              {{$value->manager_change_date?siteformat_datetime24($value->manager_change_date):''}}
                            </strong>
                          </td>

                          @if($value->account_status==0)
                            @php $account_status = 'Pending';$color = "#0c71ed"; @endphp
                          @elseif($value->account_status==1)
                            @php $account_status = 'Accept';$color = "#138f34"; @endphp
                          @else
                            @php $account_status = 'Reject';$color = "#f51637"; @endphp
                          @endif
                          
                          <td style="color:{{$color}}">
                            <strong>
                              {{$account_status}}<br>
                            </strong>
                            <strong>
                              {{$value->account_change_by?getUserFieldWithId($value->account_change_by,'employee_name'):''}}<br>
                              {{$value->account_change_date?siteformat_datetime24($value->account_change_date):''}}
                            </strong>
                          </td>

                          @if($value->gm_status==0)
                            @php $gm_status = 'Pending';$color = "#0c71ed"; @endphp
                          @elseif($value->gm_status==1)
                            @php $gm_status = 'Accept';$color = "#138f34"; @endphp
                          @else
                            @php $gm_status = 'Reject';$color = "#f51637r"; @endphp
                          @endif
                          
                          <td style="color:{{$color}}">
                            <strong>
                              {{$gm_status}}<br>
                            </strong>
                            <strong>
                              {{$value->gm_change_by?getUserFieldWithId($value->gm_change_by,'employee_name'):''}}<br>
                              {{$value->gm_change_date?siteformat_datetime24($value->gm_change_date):''}}
                            </strong>
                          </td>
                      	
                      </tr>
                      
                      @php $inner += 1; @endphp
                    @endforeach
                    
                  </tbody>
                </table>
              