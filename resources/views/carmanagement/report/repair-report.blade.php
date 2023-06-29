<table>  
    <tbody>
           <tr>
              <td></td>
              <td></td>
              <td style="background:#998912"><b>YGN Office Driver</b></td>
              <td style="background:#43e8a3"><b>YGN Contract Driver</b></td>
              <td style="background:green"><b>YGN Rental Driver</b></td>
           </tr>
           <tr>
              <td></td>
              <td></td>
              <td style="background:#eff450"><b>NPT Office Driver</b></td>
              <td style="background:#E0A800"><b>NPT Contract Driver</b></td>
           </tr>           
           <tr>
           </tr>
           <tr>
             <td colspan="9"><b>Detail Information</b></td>
             <td colspan="{{ ($maintenance_count >= 1 ? $maintenance_count: 1 ) +  ( $tyre_count >= 1 ? $tyre_count: 1  ) + ( $battery_count >= 1 ? $tyre_count: 1 ) + ( $other_count >= 1 ? $other_count: 1 )}}"><b>Repairing  Category</b></td>
          </tr> 
          <tr>
                <td><b>No</b></td>
                <td><b>Dept</b></td>
                <td><b>Main User</b></td>
                <td><b>Car</b></td>
                <td><b>Car No</b></td>
                <td><b>Driver</b></td>
                <td><b>Model Year</b></td>
                <td><b>Car Parking</b></td>
                <td></td>
                <td colspan="{{ $maintenance_count }}" style="text-align:center">Maintenance</td>
                <td colspan="{{ $tyre_count }}" style="text-align:center">Tyre</td>
                <td colspan="{{ $battery_count }}" style="text-align:center">Battery</td>
                <td colspan="{{ $other_count }}" style="text-align:center">Other</td>
            </tr>
           <?php  $i = 1; ?>
           @foreach($cars as $car)
              <tr>
                <td rowspan="4">{{ $i }}</td>
                <td rowspan="4">{{ $car->docname }}</td>
                <td rowspan="4">{{ $car->main_user_name }}</td>
                <td rowspan="4" style="background:#998912;">{{ $car->car_type }}</td>
                <td rowspan="4">{{ $car->car_number }}</td>
                @if($car->employee_type_id == 2)
                <td rowspan="4" style="background:#998912">{{ $car->driver_name }}</td>
                @elseif($car->employee_type_id == 3)
                 <td rowspan="4" style="background:#43e8a3">{{ $car->driver_name }}</td>
                   @elseif($car->employee_type_id == 4)
                 <td rowspan="4" style="background:green">{{ $car->driver_name }}</td>
                  @elseif($car->employee_type_id == 6)
                 <td rowspan="4" style="background:#eff450">{{ $car->driver_name }}</td>
                 @elseif($car->employee_type_id == 7)
                 <td rowspan="4" style="background:#E0A800">{{ $car->driver_name }}</td>
                 @endif
                <td rowspan="4">{{ $car->model_year }}</td>
                <td rowspan="4">{{ $car->parking }}</td>
              </tr>
              
               <tr>
                <td>Date</td>
                <?php $co=$maintenance_count - count($car->repair_maintenance_record);
                 ?>
                 <?php $tyre_co= $tyre_count - count($car->repair_tyre_record); ?>
                 <?php $battery_co= $battery_count - count($car->repair_battery_record); ?>
                  <?php $other_co= $other_count - count($car->repair_other_record); ?>

                
                @foreach($car->repair_maintenance_record as $maintenance_record)
                    <?php
                     $mdate = str_replace('-"', '/', $maintenance_record->repair_date);
                      $repairmDate = date("d/m/Y", strtotime($mdate));
                     ?>
                   <td style="text-align:right">{{ $repairmDate }} </td>
                @endforeach
                 @if(count($car->repair_maintenance_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($a=0;$a < $co;$a++)
                 <td style="text-align:right"></td>
                @endfor
                @endif

            
               
                @foreach($car->repair_tyre_record as $tyre_record)
                    <?php
                     $tdate = str_replace('-"', '/', $tyre_record->repair_date);
                      $repairtDate = date("d/m/Y", strtotime($tdate));
                     ?>
                   <td style="text-align:right">{{ $repairtDate }} </td>
                @endforeach
                @if(count($car->repair_tyre_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($t=0;$t < $tyre_co;$t++)
                 <td style="text-align:right"></td> 
                @endfor
                @endif


                
              
                @foreach($car->repair_battery_record as $battery_record)
                    <?php
                     $bdate = str_replace('-"', '/', $battery_record->repair_date);
                      $repairbDate = date("d/m/Y", strtotime($bdate));
                     ?>
                   <td style="text-align:right">{{ $repairbDate }} </td>
                @endforeach
                 @if(count($car->repair_battery_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                 @for($b=0;$b <  $battery_co;$b++) 
                 <td style="text-align:right"></td>
                @endfor
                @endif
                
               
                 
                @foreach($car->repair_other_record as $other_record)
                  <?php
                     $odate = str_replace('-"', '/', $other_record->repair_date);
                     $repairoDate = date("d/m/Y", strtotime($odate));
                  ?>
                   <td style="text-align:right">{{ $repairoDate }} </td>
                @endforeach 
                 @if(count($car->repair_other_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($o=0;$o < $other_co;$o++)
                <td style="text-align:right"></td>
                @endfor
               @endif
            </tr>
            <tr>
                <td>Kilometer</td>
              
                @foreach($car->repair_maintenance_record as $maintenance_record)
                   <td style="text-align:right">{{ number_format($maintenance_record->kilo_meter) }}</td>
                @endforeach
                @if(count($car->repair_maintenance_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($a=0;$a < $co;$a++)
                 <td style="text-align:right"></td>
                @endfor
                @endif

                @foreach($car->repair_tyre_record as $tyre_record)
                   <td style="text-align:right">{{ number_format($tyre_record->kilo_meter) }}</td>
                @endforeach
                @if(count($car->repair_tyre_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($t=0;$t < $tyre_co;$t++)
                 <td style="text-align:right"></td> 
                @endfor
                @endif


                @foreach($car->repair_battery_record as $battery_record)
                   <td style="text-align:right">{{ number_format($battery_record->kilo_meter) }}</td>
                @endforeach
                @if(count($car->repair_battery_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                 @for($b=0;$b <  $battery_co;$b++) 
                 <td style="text-align:right"></td>
                @endfor
                @endif
                
                

                @foreach($car->repair_other_record as $other_record)
                   <td style="text-align:right">{{ number_format($other_record->kilo_meter) }}</td>
                @endforeach
                @if(count($car->repair_other_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($o=0;$o < $other_co;$o++)
                <td style="text-align:right"></td>
                @endfor
                @endif

             </tr>
              <tr>
                <td>Amount</td>

                @foreach($car->repair_maintenance_record as $maintenance_record)
                   <td style="text-align:right">{{ number_format($maintenance_record->amount) }}</td>
                @endforeach
                 @if(count($car->repair_maintenance_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($a=0;$a < $co;$a++)
                 <td style="text-align:right"></td>
                @endfor
                @endif

                @foreach($car->repair_tyre_record as $tyre_record)
                   <td style="text-align:right">{{ number_format($tyre_record->amount) }}</td>
                @endforeach
                @if(count($car->repair_tyre_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($t=0;$t < $tyre_co;$t++)
                 <td style="text-align:right"></td> 
                @endfor
                @endif

                @foreach($car->repair_battery_record as $battery_record)
                   <td style="text-align:right">{{ number_format($battery_record->amount) }}</td>
                @endforeach
                @if(count($car->repair_battery_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($b=0;$b <  $battery_co;$b++) 
                 <td style="text-align:right"></td>
                @endfor
                @endif
                
                @foreach($car->repair_other_record as $other_record)
                   <td style="text-align:right">{{ number_format($other_record->amount) }}</td>
                @endforeach
                @if(count($car->repair_other_record) == 0)
                  <td style="text-align:right"></td>
                 @else
                @for($o=0;$o < $other_co;$o++)
                <td style="text-align:right"></td>
                @endfor
                @endif
            </tr>
            <?php  $i++; ?>
           @endforeach
</tbody>
</table>