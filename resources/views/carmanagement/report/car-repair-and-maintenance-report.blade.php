<table>  
    <tbody>

           <tr>
              <td></td>
              <td></td>
              <td style="background:#998912">YGN Office Driver</td>
              <td style="background:#43e8a3">YGN Contract Driver</td>
              <td style="background:green"><b>YGN Rental Driver</b></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="color:red">Month</td>
           </tr>
           <tr>
              <td></td>
              <td></td>
              <td style="background:#eff450;border:5px;">NPT Office Driver</td>
              <td style="background:#E0A800;border:5px">NPT Contract Driver</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>{{ $month }}</td>
           </tr>
          <tr>
              <td colspan="10"><b>Detail Information</b></td>
              <!--<td><b>Different Kilometer for periodic maintenance</b></td>-->
          </tr>

          <tr>
                <td><b>No</b></td>
                <td><b>Dept</b></td>
                <td><b>Main User</b></td>
                <td><b>Car</b></td>
                <td><b>Car No</b></td>
                <td><b>Driver</b></td>
                <td style="text-align:left"><b>Model Year</b></td>
                <td style="text-align:left"><b>Car Parking</b></td>
                <td style="text-align:right"><b>Current Kilo - Maintenance Kilo</b></td>
                <td style="text-align:right"><b>Different Kilometer</b></td>
            </tr>
           <?php  $i = 1; ?>
           @foreach($km_for_maintenances as $km_for_maintenance)
           <?php  $difference = $km_for_maintenance->current_km - $km_for_maintenance->kilo_meter; ?>
              <tr>
                <td>{{ $i }}</td>
                <td>{{ $km_for_maintenance->docname }}</td>
                <td>{{ $km_for_maintenance->main_user_name }}</td>
                <td>{{ $km_for_maintenance->car_type }}</td>
                <td>{{ $km_for_maintenance->car_number }}</td>
                
                 @if($km_for_maintenance->employee_type_id == 2)
                <td style="background:#998912">{{ $km_for_maintenance->driver_name }}</td>
                @elseif($km_for_maintenance->employee_type_id == 3)
                 <td style="background:#43e8a3">{{ $km_for_maintenance->driver_name }}</td>
                   @elseif($km_for_maintenance->employee_type_id == 4)
                 <td style="background:green">{{ $km_for_maintenance->driver_name }}</td>
                  @elseif($km_for_maintenance->employee_type_id == 6)
                 <td style="background:#eff450">{{ $km_for_maintenance->driver_name }}</td>
                 @elseif($km_for_maintenance->employee_type_id == 7)
                 <td style="background:#E0A800">{{ $km_for_maintenance->driver_name }}</td>
                 @endif
                 
                <!--<td>{{ $km_for_maintenance->driver_name }}</td>-->
                <td style="text-align:left">{{ $km_for_maintenance->model_year }}</td>
                <td style="text-align:left">{{ $km_for_maintenance->parking }}</td>
                <td style="text-align:right">{{ number_format($km_for_maintenance->current_km)  }} - {{ number_format($km_for_maintenance->kilo_meter) }}</td>
                <td style="text-align:right">{{ number_format($difference) }}</td>
              </tr>
              <?php $i++; ?>
              @endforeach
              
</tbody>
</table>