
                <table class="table">
                  <thead>
                    <tr>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;vertical-align: middle;" rowspan="2">Sr.<br> No. <br> စဥ်</th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;vertical-align: middle;" rowspan="2">Month</th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;vertical-align: middle;" rowspan="2">Insurance Name<br> အာမခံထားသူ<br>အမည်</th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;width: 125px;vertical-align: middle;" rowspan="2">
                      	Position<br>ရာထူးအမည်
                      </th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;width: 125px;vertical-align: middle;" rowspan="2">
                      	SSN No.<br>အာမခံစိစစ်ရေး<br>အမှတ်
                      </th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;vertical-align: middle;" rowspan="2">
                      	  Payment<br>လုပ်ခ လစာ (ကျပ်)
                      </th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;vertical-align: middle;" colspan="2">
                      	Health and Social Care <br> Insurance System <br>ကျန်းမာရေးနှင့်  လူမှုရေး<br>စောင့်ရှောက်မှု အာမခံစနစ်
                      </th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;width: 110px;justify-content: center;vertical-align: middle;">
                      	Employment <br>Injury Benefit<br> Insurance System<br> အလုပ်တွင်<br>
                        ထိခိုက်မှု<br> အကျိုးခံစားခွင့်<br> အာမခံစနစ်
                      </th>
                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;vertical-align: middle;" colspan="3">
                      	Total<br> စုစုပေါင်း
                      </th>


                      <th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 120px;font-size: 10px;vertical-align: middle;" rowspan="2">Remark<br> မှတ်ချက်</th>
                    </tr>
                    <tr>
                    	<th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 70px;font-size: 10px;vertical-align: middle;">
                    		Employer <br>အလုပ်ရှင် <br>2% (ကျပ်)
                      	</th>
                      	<th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 70px;font-size: 10px;vertical-align: middle;">
                    		Employee <br>အလုပ်သမား <br>2% (ကျပ်)
                      	</th>
                      	<th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 70px;font-size: 10px;vertical-align: middle;">
                    		Employer <br>အလုပ်ရှင်<br> 1% (ကျပ်)
                      	</th>
                      	<th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 70px;font-size: 10px;vertical-align: middle;">
                    		Employer <br> အလုပ်ရှင် <br>3% (ကျပ်)
                      	</th>
                      	<th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 70px;font-size: 10px;vertical-align: middle;">
                    		Employee <br>အလုပ်သမား <br>2% (ကျပ်)
                      	</th>
                      	<th style="text-align: center;font-weight: bold;border: 2px solid #111;height: 70px;font-size: 10px;vertical-align: middle;">
                    		Total <br>စုစုပေါင်း <br>5% (ကျပ်)
                      	</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $total_employer = 0;$total_employee = 0;$total = 0; 

                    @endphp
                    @foreach($sscs as $employee_type=>$ssc)
                      @php
                        $sub_total_employer = 0;$sub_total_employee = 0;$sub_total = 0;
                      @endphp
                      <tr>
                        <th colspan="13" style="border:2px solid #111;font-weight: bold;height: 30px;">{{getEmployeeType($employee_type)}}</th>
                      </tr>
                      @foreach($ssc as $key=>$value)
                      <tr>
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;">{{$key+1}}</td>
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;">{{\Carbon\Carbon::parse($value->date)->format("F, Y")}}</td>
                      	<td style="border:2px solid #111;height: 30px;font-size: 11px;">{{getUserFieldWithId($value->user_id,"employee_name")}}</td>
                      	
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;">{{getUserFieldWithId($value->user_id,"position_id")}}</td>
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;">{{getUserFieldWithId($value->user_id,"ssc_no")}}</td>
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;" class="text-right">{{$value->salary_mmk}}</td>
                        @php
                          $employer3percent = $value->salary_mmk * 3 / 100;
                          $employer1percent = $value->salary_mmk * 1 / 100;
                          $employer2percent = $value->salary_mmk * 2 / 100;
                          $employee = $value->salary_mmk * 2 / 100;
                          $total_employer += $employer3percent;
                          $total_employee += $employee;
                          $total += $employer3percent;
                          $total += $employee;

                          $sub_total_employer += $employer3percent;
                          $sub_total_employee += $employee;
                          $sub_total += $employer3percent;
                          $sub_total += $employee;
                        @endphp
                      	<td style="border:2px solid #111;height: 30px;font-size: 11px;" class="text-right">{{$employer2percent}}</td>
                      	<td style="border:2px solid #111;height: 30px;font-size: 11px;" class="text-right">{{$employee}}</td>
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;" class="text-right">{{$employer1percent}}</td>
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;" class="text-right">{{$employer3percent}}</td>
                        <td style="border:2px solid #111;height: 30px;font-size: 11px;" class="text-right">{{$employee}}</td>
                      	<td style="border:2px solid #111;height: 30px;font-size: 11px;" class="text-right">{{$employer3percent + $employee}}</td>
                      	<td style="border:2px solid #111;height: 30px;font-size: 11px;">{{$value->remark}}</td>
                      	
                      </tr>
                      @endforeach
                      <tr>
                        <td style="border:2px solid #111;font-weight: bold;text-align: right;height: 30px;" colspan="11" class="text-right">Sub Total</td>
                        <td style="border:2px solid #111;font-weight: bold;height: 30px;" class=" text-right">{{$sub_total}}</td>
                        <td style="border:2px solid #111;height: 30px;" class="text-bold"></td>
                      </tr>
                      
                    @endforeach
                    <tr>
                        <td style="border:2px solid #111;font-weight: bold;height: 30px;text-align: right;" colspan="11" class="text-right">All Total</td>
                        <td style="border:2px solid #111;font-weight: bold;height: 30px;" class=" text-right">{{$total}}</td>
                        <td style="border:2px solid #111;height: 30px;" class="text-bold"></td>
                    </tr>
                  </tbody>
                </table>
              