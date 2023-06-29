<table style="border-collapse: collapse;">  
    <tbody>
           <tr>
              <td></td>
              <td></td>
              <td style="background:#998912">YGN Office Driver</td>
              <td style="background:#43e8a3">YGN Contract Driver</td>
              <td style="background:green"><b>YGN Rental Driver</b></td>
           </tr>
           <tr>
              <td></td>
              <td></td>
              <td style="background:#eff450;border:5px;">NPT Office Driver</td>
              <td style="background:#E0A800;border:5px">NPT Contract Driver</td>
           </tr>           
           <tr>
           </tr>
           <tr>
             <td colspan="10"><b>Detail Information</b></td>
             <td colspan="{{ $insurance_count + $insurance_count }}"><b>Insurance (AYA SOMPO)</b></td>
          </tr>
          <tr>
             
                <td rowspan="2" style="padding:18px"><b>No</b></td>
                <td rowspan="2" style="padding:18px"><b>Dept</b></td>
                <td rowspan="2" style="padding:18px"><b>Main User</b></td>
                <td rowspan="2" style="padding:18px"><b>Car</b></td>
                <td rowspan="2" style="padding:18px"><b>Car No</b></td>
                <td rowspan="2" style="padding:18px"><b>Driver</b></td>
                <td rowspan="2" style="padding:18px"><b>Model Year</b></td>
                <td rowspan="2" style="padding:18px"><b>Car Parking</b></td>
                <td rowspan="2" style="padding:18px"><b>Insurance No</b></td>
                <td rowspan="2" style="padding:18px"><b>Insurance Expire Date</b></td>
                <td colspan="{{ $insurance_count + $insurance_count }}"><b>Claim History</b></td>
          </tr> 
            <tr>
                 @for($t=0;$t < $insurance_count;$t++)
                 <td style="padding:18px"><b>Date</b></td>
                 <td style="padding:18px"><b>Detail</b></td> 
                @endfor
            </tr>
           <?php  $i = 1; ?>
           @foreach($cars as $car)

           <?php
         $date = str_replace('-"', '/', $car->end_date);
         $endDate = date("d/m/Y", strtotime($date));
          ?>
              <tr>
                <td style="text-align:left">{{ $i }}</td>
                <td style="text-align:left">{{ $car->docname }}</td>
                <td style="text-align:left">{{ $car->main_user_name }}</td>
                <td style="background:#998912;text-align:left">{{ $car->car_type }}</td>
                <td>{{ $car->car_number }}</td>
                @if($car->employee_type_id == 2)
                <td style="background:#998912;text-align:left">{{ $car->driver_name }}</td>
                @elseif($car->employee_type_id == 3)
                 <td style="background:#43e8a3;text-align:left">{{ $car->driver_name }}</td>
                  @elseif($car->employee_type_id == 4)
                 <td style="background:green;text-align:left">{{ $car->driver_name }}</td>
                  @elseif($car->employee_type_id == 5)
                 <td style="background:#eff450;text-align:left">{{ $car->driver_name }}</td>
                 @elseif($car->employee_type_id == 6)
                 <td style="background:#E0A800;text-align:left">{{ $car->driver_name }}</td>
                 @endif
                <td>{{ $car->model_year }}</td>
                <td style="text-align:left">{{ $car->parking }}</td>
                <td style="text-align:left">{{ $car->insurance_no }}</td>
                 <td style="text-align:left">{{ $endDate }}</td>
                
                @foreach($car->insurance_claim_history as $claim_history)                  
                   <?php
                    $claimdate = str_replace('-"', '/', $claim_history->claim_date);
                    $claim_date = date("d/m/Y", strtotime($claimdate));
                  ?>
                   <td style="text-align:left">{{ $claim_date  }}</td>
                    <td style="text-align:left">{{ $claim_history->claim_detail }}</td>
                @endforeach 
            </tr>
           
            <?php  $i++; ?>
           @endforeach
</tbody>
</table>