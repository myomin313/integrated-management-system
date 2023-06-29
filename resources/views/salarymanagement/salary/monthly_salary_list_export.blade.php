
                <table class="table">
                  <thead>
                    <tr>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">No</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Name</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Occupation</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Salary (USD)</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">KBZ Opening <br> A/C (USD)</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">W/O Pay <br> Leave (USD)</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Est. Income <br> Tax (USD)</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">SSC <br>(USD)</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Net Salary <br>(USD)</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Transfer To <br> MMK A/C (MMK)</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Remark</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $total_usd = 0; $total_mmk = 0;$salary_total = 0; $kbz_opening_total = 0;$leave_total = 0; $estimated_tax_total = 0;$ssc_total = 0;

                    @endphp
                    @foreach($paylists as $employee_type=>$salary)
                      <tr>
                        <th colspan="11" style="font-weight:bold;border: 5px solid #111;height: 30px;">{{getEmployeeType($employee_type)}}</th>
                      </tr>
                      @php
                        $sub_total_usd = 0; $sub_total_mmk = 0;$sub_salary_total = 0; $sub_kbz_opening_total = 0;$sub_leave_total = 0; $sub_estimated_tax_total = 0;$sub_ssc_total = 0;
                        
                      @endphp
                      @foreach($salary as $key=>$value)
                      <tr>
                      	<td style="border: 5px solid #111;height: 30px;">{{$key+1}}</td>
                        @php $emp_name = getUserFieldWithId($value->user_id,"employee_name"); @endphp
                      	<td style="border: 5px solid #111;height: 30px;">{{$emp_name?$emp_name:getUserFieldWithId($value->user_id,"name")}}</td>
                        <td style="border: 5px solid #111;height: 30px;">{{getUserFieldWithId($value->user_id,"position_id")}}</td>
                        
                        <td style="border: 5px solid #111;height: 30px;" class="text-right">{{$value->salary_usd}}</td>
                        <td style="border: 5px solid #111;height: 30px;" class="text-right">-{{$value->kbz_opening_usd}}</td>
                        <td style="border: 5px solid #111;height: 30px;" class="text-right">-{{$value->leave_amount_usd}}</td>
                        <td style="border: 5px solid #111;height: 30px;" class="text-right">-{{$value->estimated_tax_usd}}</td>
                        <td style="border: 5px solid #111;height: 30px;" class="text-right">-{{$value->ssc_usd}}</td>
                        @php
                          $net_salary_usd = $value->salary_usd - $value->kbz_opening_usd - $value->leave_amount_usd - $value->estimated_tax_usd - $value->ssc_usd;
                          $net_salary_mmk = $value->salary_mmk - $value->kbz_opening_mmk - $value->leave_amount_mmk - $value->estimated_tax_mmk - $value->ssc_mmk;

                          $total_usd += $net_salary_usd;
                          $total_mmk += $value->transfer_mmk_acc;

                          $salary_total += $value->salary_usd;
                          $kbz_opening_total += $value->kbz_opening_usd;
                          $leave_total += $value->leave_usd;
                          $estimated_tax_total += $value->estimated_tax_usd;
                          $ssc_total += $value->ssc_usd;

                          $sub_total_usd += $net_salary_usd;
                          $sub_total_mmk += $value->transfer_mmk_acc;

                          $sub_salary_total += $value->salary_usd;
                          $sub_kbz_opening_total += $value->kbz_opening_usd;
                          $sub_leave_total += $value->leave_usd;
                          $sub_estimated_tax_total += $value->estimated_tax_usd;
                          $sub_ssc_total += $value->ssc_usd;
                        @endphp
                        <td style="border: 5px solid #111;height: 30px;" class="text-right">{{$net_salary_usd}}</td>
                        <td style="border: 5px solid #111;height: 30px;" class="text-right">{{$value->transfer_mmk_acc}}</td>
                        <td style="border: 5px solid #111;height: 30px;">{{\Carbon\Carbon::parse($value->pay_date)->format("F, Y")}}</td>
                        
                      	
                      </tr>
                      @endforeach
                      <tr>
                        <th colspan="3" style="font-weight:bold;border: 5px solid #111;height: 30px;text-align: right;">Sub Total</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;">{{$sub_salary_total}}</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$sub_kbz_opening_total}}</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$sub_leave_total}}</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$sub_estimated_tax_total}}</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$sub_ssc_total}}</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;">{{$sub_total_usd}}</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;">{{$sub_total_mmk}}</th>
                        <th style="font-weight:bold;border: 5px solid #111;height: 30px;"></th>
                      </tr>
                      
                    @endforeach
                    <tr>
                      <th colspan="3" style="font-weight:bold;border: 5px solid #111;height: 30px;">All Total</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;">{{$salary_total}}</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$kbz_opening_total}}</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$leave_total}}</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$estimated_tax_total}}</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;">-{{$ssc_total}}</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;">{{$total_usd}}</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;">{{$total_mmk}}</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 30px;"></th>
                    </tr>
                  </tbody>
                </table>
              