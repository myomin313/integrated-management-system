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
             <td colspan="8"><b>Detail Information</b></td>
             <td colspan="2" style="color:red;"><b>License(need alert)</b></td>
            </tr>
            <tr>
                <td style="padding:18px"><b>No</b></td>
                <td style="padding:18px"><b>Dept</b></td>
                <td style="padding:18px"><b>Main User</b></td>
                <td style="padding:18px"><b>Car</b></td>
                <td style="padding:18px"><b>Car No</b></td>
                <td style="padding:18px"><b>Driver</b></td>
                <td style="padding:18px"><b>Model Year</b></td>
                <td style="padding:18px"><b>Car Parking</b></td>
                <td style="padding:18px;text-align:right"><b>Driver License Expire Date</b></td>
                <td style="padding:18px;text-align:right"><b>Car License Expire Date</b></td>
                
            </tr>
           <?php  $i = 1; ?>
           @foreach($car_licenses as $car_license)
              <tr>
                <td>{{ $i }}</td>
                <td>{{ $car_license->docname }}</td>
                <td>{{ $car_license->main_user_name }}</td>
                <td style="background:#998912;">{{ $car_license->car_type }}</td>
                <td>{{ $car_license->car_number }}</td>
                @if($car_license->employee_type_id == 2)
                <td style="background:#998912">{{ $car_license->driver_name }}</td>
                @elseif($car_license->employee_type_id == 3)
                 <td style="background:#43e8a3">{{ $car_license->driver_name }}</td>
                   @elseif($car_license->employee_type_id == 4)
                 <td style="background:green">{{ $car_license->driver_name }}</td>
                  @elseif($car_license->employee_type_id == 5)
                 <td style="background:#eff450">{{ $car_license->driver_name }}</td>
                 @elseif($car_license->employee_type_id == 6)
                 <td style="background:#E0A800">{{ $car_license->driver_name }}</td>
                 @endif
                <td>{{ $car_license->model_year }}</td>
                <td >{{ $car_license->parking }}</td>
                <?php
                  $driver_expire_date = str_replace('-"', '/', $car_license->driver_license_expire_date);
                 $driver_license_expire_date = date("d/m/Y", strtotime($driver_expire_date));

                 
                  $car_expire_date = str_replace('-"', '/', $car_license->car_license_expire_date);
                 $car_license_expire_date = date("d/m/Y", strtotime($car_expire_date));

                 ?>
                <td style="text-align:right">{{ $driver_license_expire_date }}</td>
                 <td style="text-align:right">{{ $car_license_expire_date }}</td>
                 </tr>
            <?php  $i++; ?>
           @endforeach
</tbody>
</table>