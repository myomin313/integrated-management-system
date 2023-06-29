
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th colspan="5" style="text-align: center;background-color:green;">Exchange Rate - USD ({{$from_date?\Carbon\Carbon::parse($from_date)->format("F' Y"):\Carbon\Carbon::parse($today_date)->format("F' Y")}})</th>
                      <th style="text-align: center;background-color:green;"></th>
                      <th colspan="5"  style="text-align: center;background-color:green;">Exchange Rate - YEN ({{$from_date?\Carbon\Carbon::parse($from_date)->format("F' Y"):\Carbon\Carbon::parse($today_date)->format("F' Y")}})</th>
                    </tr>
                    <tr>
                    	<th  style="text-align: center;background-color:green;">Date</th>
                      <th  style="text-align: center;background-color:green;">Day</th>
                    	<th  style="text-align: center;background-color:green;">Currency</th>
                    	<th  style="text-align: center;background-color:green;">Value</th>
                    	<th  style="text-align: center;background-color:green;">Reference Rate</th>
                    	<th  style="text-align: center;background-color:green;"></th>
                    	<th  style="text-align: center;background-color:green;">Date</th>
                      <th  style="text-align: center;background-color:green;">Day</th>
                    	<th  style="text-align: center;background-color:green;">Currency</th>
                    	<th style="text-align: center;background-color:green;">Value</th>
                    	<th style="text-align: center;background-color:green;">Reference Rate</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $no_of_day = 0;
                      $total_exchange_usd = 0;
                      $total_exchange_yen = 0;
                    @endphp
                  	@foreach($exchange_rates as $key=>$value)
                  		<tr>
                  			
	                    	<th style="text-align: center;">{{\Carbon\Carbon::parse($value->date)->format("d-M-y")}}</th>
                        <th style="text-align: center;">{{getDateName($value->date)}}</th>
                        @php
                        $ex_date = \Carbon\Carbon::parse($value->date)->format("d-m");
                        @endphp

	                    	@if(isHoliday($value->date))
	                    	<th style="text-align: center;" colspan="2"></th>

	                    	<th class="text-center"></th>
                        
	                    	@elseif($ex_date=="01-04" or $ex_date=="01-08")
                        <th style="text-align: center;" colspan="2">Bank Holiday</th>

                        <th style="text-align: center;"></th>
	                    	@else
	                    	<th style="text-align: center;">Dollar USD</th>

	                    	<th style="text-align: center;">1/- = K</th>
	                    	<th style="text-align: right;">{{siteformat_number($value->dollar)}}</th>
                        @php
                          $no_of_day += 1;
                          $total_exchange_usd += $value->dollar;
                        @endphp

	                    	@endif


	                    	<th class="text-center"></th>
	                    	<th style="text-align: center;">{{\Carbon\Carbon::parse($value->date)->format("d-M-y")}}</th>
                        <th style="text-align: center;">{{getDateName($value->date)}}</th>
	                    	@if(isHoliday($value->date))
                        <th  style="text-align: center;" colspan="2"></th>

                        <th class="text-center"></th>
                        
                        @elseif($ex_date=="01-04" or $ex_date=="01-08")
                        <th style="text-align: center;" colspan="2">Bank Holiday</th>

                        <th class="text-center"></th>
                        @else
                        <th style="text-align: center;">JPN YEN</th>

                        <th style="text-align: center;">1Y = 100K</th>
                        <th style="text-align: right;">{{siteformat_number($value->yen)}}</th>
                        @php
                          $total_exchange_yen += $value->yen;
                        @endphp

                        @endif
	                    </tr>
                  	@endforeach
	                    
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" style="background-color:green;">Average</th>
                      @php
                        if($no_of_day>0){
                          $average_usd = round($total_exchange_usd / $no_of_day);
                          $average_yen = round($total_exchange_yen / $no_of_day);
                        }
                        else{
                          $average_usd = 0;
                          $average_yen = 0;
                        }
                      @endphp
                      <th style="text-align: right;background-color:green;">{{siteformat_number($average_usd)}}</th>
                      <th style="text-align: center;background-color:green;"></th>
                      <th colspan="4" style="background-color:green;">Average</th>
                      <th style="text-align: right;background-color:green;">{{siteformat_number($average_yen)}}</th>
                        
                    </tr>
                  </tfoot>
                </table>
              