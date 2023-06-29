
                <table class="table table-bordered" style="border: 5px solid #111;">
                  <thead>
                    <tr>
                      <th style="border-bottom:5px solid #111;font-size:18px;" colspan="7"></th>
                    </tr>
                    <tr>
                      <th style="border-bottom:5px solid #111;border-top:5px solid #111;font-size:18px;border-right:5px solid #111;" colspan="4"><strong>Salary for {{$salary->pay_for}}</strong></th>
                      <th style="border-bottom:5px solid #111;border-top:5px solid #111;border-right:5px solid #111;text-align: right;font-size:18px;" colspan="3"><strong>on {{\Carbon\Carbon::parse($salary->pay_date)->format("d F Y")}}</strong></th>
                    </tr>
                    <tr>
                      <th style="border-bottom:2px solid #999;font-size:15px;border-right:5px solid #111;" colspan="7"><strong>{{getUserNameWithPrefix($user->id)}}</strong></th>
                      
                    </tr>
                    <tr style="font-size:26px;">
                      <th colspan="4" style="border-bottom:2px solid #999;text-align: center;"></th>
                      <th style="border-bottom:2px solid #999;text-align: center;font-size:13px"><strong>USD</strong></th>
                      <th style="border-bottom:2px solid #999;text-align: center;font-size:13px"><strong>Exchange Rate</strong> </th>
                      <th style="border-bottom:2px solid #999;text-align: center;border-right:5px solid #111;font-size:13px"><strong>MMK</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($salary->exchange_rate_salary_detail as $key=>$detail)
                      <tr>
                        @if($key==0)
                        <th colspan="3" style="font-size:12px;font-weight:bold;width: 100px">Salary for {{$salary->pay_for}}</th>
                        @else
                        <th colspan="3" style="font-size:12px"></th>
                        @endif
                      	<th  style="font-size:18px;text-align: center;">
                            <strong>+</strong>
                        </th>
                      	
                        <td style="font-size:12px;font-weight: bold;">{{$detail->usd_amount}}</td>
                        <td style="font-size:12px;font-weight: bold;">{{$detail->exchange_rate}}</td>
                        <td style="border-right:5px solid #111;font-size:12px;font-weight: bold;">{{$detail->mmk_amount}}</td>
                      	
                      </tr>
                      @endforeach
                      @if($salary->estimated_tax_usd>0)
                        @foreach($salary->exchange_rate_tax_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">I-Tax ({{$salary->pay_for}})</th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <th  style="font-size:22px;text-align: center;">
                            <strong>-</strong>
                          </th> 
                          
                          <td style="font-size:12px;font-weight:bold;">-{{$detail->usd_amount}}</td>
                          <td style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</td>
                          <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;" >-{{$detail->mmk_amount}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->ssc_usd>0)
                        @foreach($salary->exchange_rate_ssc_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">SSC ({{$salary->pay_for}})</th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <th  style="font-size:22px;text-align: center;">
                            <strong>-</strong>
                          </th>
                          
                          <td style="font-size:12px;font-weight:bold;">-{{$detail->usd_amount}}</td>
                          <td style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</td>
                          <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">-{{$detail->mmk_amount}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->total_ot_payment_usd>0)
                        @foreach($salary->exchange_rate_ot_detail as $key=>$detail)
                        <tr>
                        	@if($salary->is_retire)
                        		@php $month_name = \Carbon\Carbon::parse("$salary->year-$salary->month")->subMonth()->format('F, Y')."+".\Carbon\Carbon::parse("$salary->year-$salary->month")->format('F, Y'); @endphp
                        	@else
                        		@php $month_name = \Carbon\Carbon::parse("$salary->year-$salary->month")->subMonth()->format('F, Y'); @endphp
                        	@endif
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">Overtime - ({{$month_name}}) </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <th  style="font-size:18px;text-align: center;">
                            <strong>+</strong>
                          </th>
                          
                          <td style="font-size:12px;font-weight:bold;">{{$detail->usd_amount}}</td>
                          <td style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</td>
                          <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$detail->mmk_amount}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->leave_amount_usd>0)
                        @foreach($salary->exchange_rate_leave_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">W/O Pay Leave ({{getLeaveDay($salary->user_id,$salary->leave_from_date,$salary->leave_to_date)}}) </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <th  style="font-size:22px;text-align: center;">
                            <strong>-</strong>
                          </th>
                          
                          <td style="font-size:12px;font-weight:bold;">-{{$detail->usd_amount}}</td>
                          <td style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</td>
                          <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">-{{$detail->mmk_amount}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->kbz_opening_usd>0)
                        @foreach($salary->exchange_rate_kbz_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">KBZ Opening A/C </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <th  style="font-size:22px;text-align: center;">
                            <strong>-</strong>
                          </th>
                          
                          <td style="font-size:12px;font-weight:bold;">-{{$detail->usd_amount}}</td>
                          <td style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</td>
                          <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">-{{$detail->mmk_amount}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->bonus_usd>0)
                        @foreach($salary->exchange_rate_bonus_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">Bonus ({{$salary->bonus_name=="Other"?$salary->other_bonus:$salary->bonus_name}}) </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <th  style="font-size:18px;text-align: center;">
                            <strong>+</strong>
                          </th>
                          
                          <td style="font-size:12px;font-weight:bold;">{{$detail->usd_amount}}</td>
                          <td style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</td>
                          <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$detail->mmk_amount}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      
                      @if($salary->other_adjustment_usd>0 or $salary->other_adjustment_usd<0)
                      <tr>
                        <th colspan="7" style="border-right:5px solid #111;font-size:12px;font-weight:bold;">Other Adjustment </th>
                        
                      </tr>
                      @foreach($salary->other_adjustment as $key=>$value)
                      <tr>
                        <td></td>
                        <td colspan="2" style="font-size:12px;font-weight:bold;width: 200px">{{$value->name}} </td>
                        
                        @if($value->usd_amount<0)
                        	<th  style="font-size:22px;text-align: center;">
                            <strong>-</strong>
                          </th>
                        @else
                          <th  style="font-size:18px;text-align: center;">
                            <strong>+</strong>
                          </th>
                        @endif
                        
                        <td style="font-size:12px;font-weight:bold;">{{$value->usd_amount}}</td>
                        <td style="font-size:12px;font-weight:bold;">{{$value->exchange_rate}}</td>
                        <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$value->mmk_amount}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      <tr>
                        <th colspan="3" style="font-size:12px;font-weight:bold;">Total Adjustment </th>
                        
                        @if($salary->other_adjustment_usd<0)
                          <th  style="font-size:22px;text-align: center;">
                            <strong>-</strong>
                          </th>
                        @else
                          <th  style="font-size:18px;text-align: center;">
                            <strong>+</strong>
                          </th>
                        @endif
                        
                        <th style="font-size:12px;font-weight:bold;">{{$salary->other_adjustment_usd}}</th>
                        <th style="font-size:12px;font-weight:bold;"></th>
                        <th style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$salary->other_adjustment_mmk}}</th>
                        
                      </tr>
                      @endif
                      @if($salary->usd_allowance_usd>0 or $salary->mmk_allowance_mmk>0)
                      <tr>
                        <th colspan="7" style="border-right:5px solid #111;font-size:12px;font-weight:bold;">Other Allowance </th>
                        
                      </tr>
                      @foreach($salary->other_allowance as $key=>$value)
                      <tr>
                        <th></th>
                        <td colspan="2" style="font-size:12px;font-weight:bold;width: 200px">{{$value->name}} </td>
                        <td style="width: 50px;text-align: center;font-weight: bold;">{{strtoupper($value->currency)}}</td>
                        <td style="font-size:12px;font-weight:bold;">{{$value->currency=="usd"?$value->amount:''}}</td>
                        <td style="font-size:12px;font-weight:bold;"></td>
                        <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$value->currency=="mmk"?$value->amount:''}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      @if($salary->usd_allowance_usd>0)
                        @foreach($salary->exchange_rate_usd_allowance_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">USD Allowance </th>
                          @else
                          <th colspan="3" class="text-right"> </th>
                          @endif
                          <th  style="font-size:18px;text-align: center;">
                            <strong>+</strong>
                          </th>
                          
                          <th style="font-size:12px;font-weight:bold;">{{
                          	$detail->usd_amount}}</th>
                          <th style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</th>
                          <th style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$detail->mmk_amount}}</th>
                          
                        </tr>
                        @endforeach
                      @endif
                      <tr>
                        <th colspan="3" style="font-size:12px;font-weight:bold;">Total Allowance </th>
                        <th  style="font-size:18px;text-align: center;">
                            <strong>+</strong>
                        </th>
                        
                        <th style="font-size:12px;font-weight:bold;">{{$salary->usd_allowance_usd+$salary->mmk_allowance_usd}}</th>
                        <th class="text-right"></th>
                        <th style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$salary->usd_allowance_mmk+$salary->mmk_allowance_mmk}}</th>
                        
                      </tr>
                      @endif
                      @if($salary->usd_deduction_usd>0 or $salary->mmk_deduction_mmk>0)
                      <tr>
                        <th colspan="7" style="border-right:5px solid #111;font-size:12px;font-weight:bold;">Other Deduction </th>
                        
                      </tr>
                      @foreach($salary->other_deduction as $key=>$value)
                      <tr>
                        <td></td>
                        <td colspan="2" style="font-size:12px;font-weight:bold;width: 200px">{{$value->name}} </td>
                        <td style="font-weight:bold;text-align: center;">{{strtoupper($value->currency)}}</td>
                        <td style="font-size:12px;font-weight:bold;">{{$value->currency=="usd"?'-'.$value->amount:''}}</td>
                        <td></td>
                        <td style="border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$value->currency=="mmk"?'-'.$value->amount:''}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      @if($salary->usd_deduction_usd>0)
                        @foreach($salary->exchange_rate_usd_deduction_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" style="font-size:12px;font-weight:bold;">USD Deduction </th>
                          @else
                          <th colspan="3" class="text-right"> </th>
                          @endif
                          <th class="text-center text-bold">
                            <strong style="font-size:22px">-</strong>
                          </th>
                          
                          <th style="font-size:12px;font-weight:bold;">-{{$detail->usd_amount}}</th>
                          <th style="font-size:12px;font-weight:bold;">{{$detail->exchange_rate}}</th>
                          <th style="border-right:5px solid #111;font-size:12px;font-weight:bold;">-{{$detail->mmk_amount}}</th>
                          
                        </tr>
                        @endforeach
                      @endif
                      <tr>
                        <th colspan="3" style="font-size:12px;font-weight:bold;">Total Deduction </th>
                        <th  style="font-size:22px;text-align: center;">
                          <strong>-</strong>
                        </th>
                        
                        <th style="font-size:12px;font-weight:bold;">-{{$salary->usd_deduction_usd+$salary->mmk_deduction_usd}}</th>
                        <th style="font-size:12px;font-weight:bold;"></th>
                        <th style="border-right:5px solid #111;font-size:12px;font-weight:bold;">-{{$salary->usd_deduction_mmk+$salary->mmk_deduction_mmk}}</th>
                        
                      </tr> 
                      @endif 

                      <tr>
                        <th colspan="3" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;font-size:12px;font-weight:bold;">Salary and Other Payments (Total) </th>
                        <th class="text-center text-bold" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;">
                          
                        </th>
                        
                        <th class="text-right" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;font-size:12px;font-weight:bold;">{{$salary->net_salary_usd}}</th>
                        <th class="text-right" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;"></th>
                        <th class="text-right" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;border-right:5px solid #111;font-size:12px;font-weight:bold;">{{$salary->net_salary_mmk}}</th>
                        
                      </tr>
                      
                      @if($salary->transfer_usd_acc>0) 
                      <tr>
                        <th colspan="6" style="border-bottom: 5px solic #111;font-size:12px;font-weight:bold;">Salary Transfer To USD A/C </th>
                        
                        <td class="text-right text-bold" style="border-right:5px solid #111;border-bottom: 5px solic #111;font-size:12px;font-weight:bold;">{{$salary->transfer_usd_acc}}</td>
                        
                      </tr>
                      @endif 
                      @if($salary->transfer_mmk_acc>0) 
                      <tr>
                        <th colspan="6" style="border-bottom: 5px solic black;font-size:12px;font-weight:bold;">Salary Transfer To MMK A/C </th>
                        
                        <td class="text-right  text-bold" style="border-right:5px solid #111;border-bottom: 5px solic #111;font-size:12px;font-weight:bold;">{{$salary->transfer_mmk_acc}}</td>
                        
                      </tr> 
                      @endif
                      @if($salary->transfer_usd_cash>0)
                      <tr>
                        <th colspan="6" style="border-bottom: 5px solic #111;font-size:12px;font-weight:bold;">Salary Transfer To USD Cash </th>
                        
                        <td class="text-right  text-bold" style="border-right:5px solid #111;border-bottom: 5px solic #111;font-size:12px;font-weight:bold;">{{$salary->transfer_usd_cash}}</td>
                        
                      </tr> 
                      @endif 
                      @if($salary->transfer_mmk_cash>0)
                      <tr>
                        <th colspan="6" style="border-bottom: 5px solic #111;font-size:12px;font-weight:bold;">Salary Transfer To MMK Cash </th>
                        
                        <td class="text-right text-bold" style="border-right:5px solid #111;border-bottom: 5px solic #111;font-size:12px;font-weight:bold;">{{$salary->transfer_mmk_cash}}</td>
                        
                      </tr>    
                      @endif
                      <tr>
                        <td colspan="7" style="border-top: 1px solid black;"></td>
                      </tr>
                  </tbody>
                </table>
                
