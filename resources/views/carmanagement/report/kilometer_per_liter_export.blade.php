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
             <td colspan="50">Monthly usage Kilometer/ Liter</td>
          </tr>
          <tr>
                <td rowspan="2"><b>No</b></td>
                <td rowspan="2"><b>Dept</b></td>
                <td rowspan="2"><b>Main User</b></td>
                <td rowspan="2"><b>Car</b></td>
                <td rowspan="2"><b>Car No</b></td>
                <td rowspan="2"><b>Driver</b></td>
                <td rowspan="2"><b>Model Year</b></td>
                <td rowspan="2"><b>Car Parking</b></td>
                <td colspan="3" style="text-align:center"><b>Apr</b></td>
                <td colspan="3" style="text-align:center"><b>May</b></td>
                <td colspan="3" style="text-align:center"><b>June</b></td>
                <td colspan="3" style="text-align:center"><b>July</b></td>
                <td colspan="3" style="text-align:center"><b>Aug</b></td>
                <td colspan="3" style="text-align:center"><b>Sep</b></td>
                <td colspan="3" style="text-align:center"><b>Oct</b></td>
                <td colspan="3" style="text-align:center"><b>Nov</b></td>
                <td colspan="3" style="text-align:center"><b>Dec</b></td>
                <td colspan="3" style="text-align:center"><b>Jan</b></td>
                <td colspan="3" style="text-align:center"><b>Feb</b></td>
                <td colspan="3" style="text-align:center"><b>Mar</b></td>
                <td colspan="3" style="text-align:center"><b>Total</b></td>
            </tr>
            <tr>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <!--<td style="text-align:right"><b>Actual <br>KM</b></td>-->
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
               <td style="text-align:right"><b>KM</b></td>
               <td style="text-align:right"><b>L</b></td>
               <td style="text-align:right"><b>KM/L</b></td>
            </tr>
           <?php  $i = 1; ?>
           @foreach($cars as $car)
              <tr>
                <td>{{ $i }}</td>
                <td>{{ $car->docname }}</td>
                <td>{{ $car->main_user_name }}</td>
                <td style="background:#998912;">{{ $car->car_type }}</td>
                <td>{{ $car->car_number }}</td>
                @if($car->employee_type_id == 2)
                <td style="background:#998912">{{ $car->driver_name }}</td>
                @elseif($car->employee_type_id == 3)
                 <td style="background:#43e8a3">{{ $car->driver_name }}</td>
                  @elseif($car->employee_type_id == 4)
                 <td style="background:green">{{ $car->driver_name }}</td>
                  @elseif($car->employee_type_id == 5)
                 <td style="background:#eff450">{{ $car->driver_name }}</td>
                 @elseif($car->employee_type_id == 6)
                 <td style="background:#E0A800">{{ $car->driver_name }}</td>
                 @endif
                    <td style="text-align:left">{{ $car->model_year }}</td>
                    <td style="text-align:left">{{ $car->parking }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->april_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->april_total_km) }}</td>
	                <td style="text-align:right">{{ $car->april_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->april_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->may_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->may_total_km) }}</td>
	                <td style="text-align:right">{{ $car->may_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->may_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->june_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->june_total_km) }}</td>
	                <td style="text-align:right">{{ $car->june_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->june_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->july_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->july_total_km) }}</td>
	                <td style="text-align:right">{{  $car->july_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->july_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->august_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->august_total_km) }}</td>
	                <td style="text-align:right">{{ $car->august_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->august_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->sep_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->sep_total_km) }}</td>
	                <td style="text-align:right">{{ $car->sep_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->sep_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->oct_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->oct_total_km) }}</td>
	                <td style="text-align:right">{{ $car->oct_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->oct_per_km) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->nov_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->nov_total_km) }}</td>
	                <td style="text-align:right">{{ $car->nov_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->nov_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->dec_total_current_km) }} </td>-->
	                <td style="text-align:right">{{ number_format($car->dec_total_km) }} </td>
	                <td style="text-align:right">{{ $car->dec_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->dec_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->jan_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->jan_total_km) }}</td>
	                <td style="text-align:right">{{ $car->jan_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->jan_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->feb_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->feb_total_km) }}</td>
	                <td style="text-align:right">{{ $car->feb_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->feb_per_km,2) }}</td>
                    <!--<td style="text-align:right">{{ number_format($car->march_total_current_km) }}</td>-->
	                <td style="text-align:right">{{ number_format($car->march_total_km) }}</td>
	                <td style="text-align:right">{{ $car->march_total_liter }}</td>
                    <td style="text-align:right">{{ number_format($car->march_per_km,2) }}</td>
                    <!-- total -->
                    <td style="text-align:right">{{ number_format($car->april_total_km +  $car->may_total_km +  $car->june_total_km +  $car->july_total_km +
                         $car->august_total_km +  $car->sep_total_km + $car->oct_total_km + $car->nov_total_km
                         + $car->dec_total_km + $car->jan_total_km + $car->feb_total_km  + $car->march_total_km) }}</td>

                    <td style="text-align:right">{{ $car->april_total_liter + $car->may_total_liter + $car->june_total_liter + $car->july_total_liter
                        + $car->august_total_liter + $car->sep_total_liter + $car->oct_total_liter + $car->nov_total_liter
                        + $car->dec_total_liter + $car->jan_total_liter + $car->်နဘ_total_liter + $car->march_total_liter }}</td>

                    <td style="text-align:right">{{ number_format($car->april_per_km + $car->may_per_km + $car->june_per_km + $car->july_per_km
                        + $car->august_per_km + $car->sep_per_km + $car->oct_per_km + $car->nov_per_km
                        + $car->dec_per_km  + $car->jan_per_km + $car->feb_per_km + $car->march_per_km,2) }}</td>


              </tr>



            <?php  $i++; ?>
           @endforeach
</tbody>
</table>
