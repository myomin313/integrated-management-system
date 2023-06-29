
                <table class="table">
                  <thead>
                    <tr>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">No</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">Account Name</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">N. R . C No.</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">A/C  NO.</th>
                      <th style="font-weight:bold;border: 5px solid #111;height: 40px;text-align: center;">MMK</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $total_mmk = 0; @endphp
                    @foreach($paylists as $key=>$value)
                      <tr>
                      	<td style="border: 5px solid #111;height: 40px;">{{$key+1}}</td>
                        @php
                          $emp_name = getUserNameWithPrefix($value->user_id);
                          $bank_name = getBankName(getUserFieldWithId($value->user_id,"bank_name_mmk"));
                        @endphp
                      	<td style="border: 5px solid #111;height: 40px;">{{$emp_name}}<br>{{$bank_name?'('.$bank_name.')':''}}</td>
                        
                        <td style="border: 5px solid #111;height: 40px;">{{getNSFieldWithId($value->user_id,"nrc_no")}}</td>
                        <td style="border: 5px solid #111;height: 40px;text-align: right;">{{getUserFieldWithId($value->user_id,"bank_account_mmk")}}</td>
                        <td style="border: 5px solid #111;height: 40px;">{{$value->transfer_mmk_acc}}</td>
                        @php
                          
                          $total_mmk += $value->transfer_mmk_acc;

                        @endphp
                        
                      	
                      </tr>

                      
                    @endforeach
                    <tr>
                      <th style="border: 5px solid #111;height: 30px;"></th>
                      <th colspan="3" style="border: 5px solid #111;height: 30px;font-weight: bold;">Total</th>
                      <th class="text-right" style="border: 5px solid #111;height: 30px;font-weight: bold;">{{$total_mmk}}</th>
                    </tr>
                  </tbody>
                </table>
             