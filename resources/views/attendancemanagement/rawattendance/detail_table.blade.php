@php $i = 0; @endphp
@foreach($attendances as $user=>$attendance)

  @if(count($attendance))

    <tr style="background-color: #999 !important;color: white !important;" class="user-name">
      <th colspan="4" class="employee_name">{{$user}}</th>
      <td colspan="13"></td>
    </tr>

    @foreach($attendance as $key=>$value)

      @if($value['id'])
        @php
          $att_date = new \Carbon\Carbon($value['date']);

                            
          if(date('N', strtotime($value['date'])) == 6){
            $color = "#f2b6dd";
            $holiday = 1;
            if(isDriver($value->user_id) or isQcStaff($value->user_id)){
              $color = "";
              $holiday = 0;
            }
          }
          else if(date('N', strtotime($value['date'])) == 7){
            $color = "#f2b6dd";
            $holiday = 1;
          }

          else if(getPublicHoliday($value['date'])){
            $color = "#f2b6dd";
            $holiday = 1;
          }
          else if($value->leave_form_id || $value->device=="Leave"){
            $color = "#f2daac";
            $holiday = 0;
          }
          else{
            $color = "";
            $holiday = 0;
          }

        @endphp
        @if($value->device!="Leave" || ($value->device=="Leave" and (strtotime($value->start_time)>strtotime("00:00:00") or strtotime($value->end_time)>strtotime("00:00:00") ) ) )

          <tr>
            <th style="background-color: {{$color}}">
              {{siteformat_date($value['date'])}}
              <br>
              {{$value->leave_form_id?"(Half Leave)":""}}
            </th>
            <td style="background-color: {{$color}}">{{$value->device}}</td>
            <td style="background-color: {{$color}}">
              <input type="hidden" name="id[]" id="id{{$i}}" value="{{$value->id}}">
              <select class="form-control select2bs4" name="type[]" id="type{{$i}}" style="width: 100%;padding: 0px;" onchange="selectCheckbox({{$i}})">
                <option value="">- Select -</option>
                @php
                  $tp = $value->type."_".$value->type_id;

                @endphp
                @foreach($types as $index=>$val)
                  <option value="{{$index}}" {{$index==$tp?'selected':''}}>{{$val}}</option>    
                @endforeach   
                                    
              </select>

            </td>
                            
            <td style="background-color: {{$color}}">
                    
              <div class="input-group date" style="width:120px;" id="timepicker{{$i}}" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#timepicker{{$i}}" name="start_time[]" id="start_time{{$i}}" value="{{siteformat_time24($value->start_time)}}" onkeyup="selectCheckbox({{$i}})" onchange="selectCheckbox({{$i}})" required/>
                <div class="input-group-append" data-target="#timepicker{{$i}}" data-toggle="datetimepicker" onclick="selectCheckbox({{$i}})">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
            </td>
            <td style="background-color: {{$color}}">
                              
              <div class="input-group date" style="width:120px;" id="end_timepicker{{$i}}" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#end_timepicker{{$i}}" name="end_time[]" id="end_time{{$i}}" value="{{$value->end_time?siteformat_time24($value->end_time):''}}" onchange="selectCheckbox({{$i}})" onkeyup="selectCheckbox({{$i}})" />
                <div class="input-group-append" data-target="#end_timepicker{{$i}}" data-toggle="datetimepicker" onclick="selectCheckbox({{$i}})">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
            </td>
            <td style="background-color: {{$color}}">
                              
              <div class="input-group date" style="width:120px;" id="correctstart_timepicker{{$i}}" data-target-input="nearest">

                <input type="text" class="form-control datetimepicker-input" data-target="#correctstart_timepicker{{$i}}" name="corrected_start_time[]" id="corrected_start_time{{$i}}" value="{{$holiday?'00:00':siteformat_time24($value->corrected_start_time)}}" onchange="selectCheckbox({{$i}})" onkeyup="selectCheckbox({{$i}})" required/>
                <div class="input-group-append" data-target="#correctstart_timepicker{{$i}}" data-toggle="datetimepicker" onclick="selectCheckbox({{$i}})">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
                              
              <div class="input-group date" style="width:120px;" id="correctend_timepicker{{$i}}" data-target-input="nearest">
                @php
                  if(isQcStaff($value->user_id) and date('N', strtotime($value['date'])) == 6)
                    $corrected_end_time = "12:00";
                  elseif($holiday)
                    $corrected_end_time = "00:00";
                  else
                    $corrected_end_time = siteformat_time24($value->corrected_end_time);
                
                @endphp
                <input type="text" class="form-control datetimepicker-input" data-target="#correctend_timepicker{{$i}}" name="corrected_end_time[]" id="corrected_end_time{{$i}}" value="{{$corrected_end_time}}" onchange="selectCheckbox({{$i}})" onkeyup="selectCheckbox({{$i}})" required/>
                <div class="input-group-append" data-target="#correctend_timepicker{{$i}}" data-toggle="datetimepicker" onclick="selectCheckbox({{$i}})">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
            </td>
            <td style="background-color: {{$color}}">
              <textarea rows="2" name="remark[]" id="reason_of_correction{{$i}}" onchange="selectCheckbox({{$i}})" onkeyup="selectCheckbox({{$i}})">{{$value->remark}}</textarea>
            </td>

            <td style="background-color: {{$color}}">
              <input type="checkbox" id="checkbox{{$i}}" class="checkAll" name="all_attendance[]" value="{{$value->id}}" style="width:18px;height:18px;">
            </td>
            <td style="background-color: {{$color}}">
                @if(isDriver($value->user_id) or isAssistant($value->user_id))
                <div class="form-check" style="width:90px;">
                    
                    <input type="checkbox" id="morning_ot{{$i}}" class="morning-ot form-check-input" name="all_morning_ot[]" value="{{$value->id}}" {{$value->morning_ot==1?'checked':''}} style="width:18px;height:18px;">
                    <label class="form-check-label" for="morning_ot{{$i}}" style="height: 18px;">Morning</label>
                </div>
                <div class="form-check" style="width:90px;">
                    
                    <input type="checkbox" id="evening_ot{{$i}}" class="evening-ot form-check-input" name="all_evening_ot[]" value="{{$value->id}}" {{$value->evening_ot==1?'checked':''}} style="width:18px;height:18px;">
                    <label class="form-check-label" for="evening_ot{{$i}}" style="height: 18px;">Evening</label>
                </div>
                @endif
              
            </td>
            <td style="background-color: {{$color}}">{{$value->change_request_date?siteformat_datetime($value->change_request_date):''}}</td>
            <td style="background-color: {{$color}}">{{$value->change_approve_date?siteformat_datetime($value->change_approve_date):''}}</td>
            <td style="background-color: {{$color}}">{{$value->normal_ot_hr?convertTime($value->normal_ot_hr):''}}</td>
            <td style="background-color: {{$color}}">{{$value->sat_ot_hr?convertTime($value->sat_ot_hr):''}}</td>
            <td style="background-color: {{$color}}">{{$value->sunday_ot_hr?convertTime($value->sunday_ot_hr):''}}</td>
            <td style="background-color: {{$color}}">{{$value->public_holiday_ot_hr?convertTime($value->public_holiday_ot_hr):''}}</td>
            @if($value->normal_ot_hr or $value->sunday_ot_hr or $value->public_holiday_ot_hr)
              <td style="background-color: {{$color}}">{{$value->ot_request_date?siteformat_datetime($value->ot_request_date):''}}</td>
              <td style="background-color: {{$color}}">{{$value->ot_approve_date?siteformat_datetime($value->ot_approve_date):''}}</td>
            @else
              <td style="background-color: {{$color}}"></td>
              <td style="background-color: {{$color}}"></td>
            @endif
          </tr>
        @else
          <tr>
            <th style="background-color: {{$color}}">
              {{siteformat_date($value['date'])}}
            </th>
                            
            <td style="background-color: {{$color}}" colspan="16">
              {{$types[$value->type.'_'.$value->type_id]}}
            </td>
          </tr>
        @endif
      @else
        @php
          $att_date = new \Carbon\Carbon($value['date']);
        @endphp
                          
        @if(date('N', strtotime($value['date'])) == 6)
          @if(isDriver($value["user_id"]) or isQcStaff($value["user_id"]))
            <tr>
              <th>{{siteformat_date($value['date'])}}</th>
                              
              <td colspan="16"></td>        
            </tr>
          @else
            <tr>
              <th style="background-color:#f2b6dd;">{{siteformat_date($value['date'])}}</th>
                              
              <td colspan="16" style="background-color:#f2b6dd;">Saturday</td>
                              
            </tr>
          @endif
                            
        @elseif(date('N', strtotime($value['date'])) == 7)
          <tr>
            <th style="background-color:#f2b6dd;">{{siteformat_date($value['date'])}}</th>
                            
            <td colspan="16" class="br-warning" style="background-color:#f2b6dd;">Sunday</td>
          </tr>
        @elseif(getPublicHoliday($value['date']))
          <tr>
            <th style="background-color:#f2b6dd;">{{siteformat_date($value['date'])}}</th>
                            
            <td style="background-color:#f2b6dd;" colspan="16">{{getPublicHoliday($value['date'])}}</td>
                  
          </tr>
        @else
          <tr>
            <th style="background-color: #f2daac;">{{siteformat_date($value['date'])}}</th>
                            
            <td style="background-color: #f2daac;" colspan="16"></td>
          </tr>
        @endif
      @endif
      @php $i += 1; @endphp
    @endforeach
  @endif
@endforeach
                    
<!-- attendance length -->
  <input type="hidden" name="count" id="count" value="{{$i}}">
<!-- attendance length -->