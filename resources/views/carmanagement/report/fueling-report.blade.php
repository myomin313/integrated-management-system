<table>
    <tbody>

           <tr>
              <td></td>
              <td colspan="6"><b> ( <?php echo $monthName ?>  Month  <?php echo $year ?>  ), Year </b></td>
              <td></td>
           </tr>
           <tr>
             <td><b>Car No :</b></td>
             <td colspan="7"><b> {{ $number_of_car }}</b></td>
          </tr>
          <tr>
             <td><b>Driver Name : </b></td>
             <td colspan="7"><b> {{ $driver_name }}</b></td>
          </tr>
          <tr>
                <td><b>Date</b></td>
                <td style="text-align:right"><b>Fueling Kilometer</b></td>
                <td style="text-align:right"><b>Mileage Difference</b></td>
                <td style="text-align:right"><b>Liter</b></td>
                <td style="text-align:right"><b>Rate (Kyats)</b></td>
                <td style="text-align:right"><b>Total (Kyats)</b></td>
                <td style="text-align:right"><b>Gasoline filled driver's name</b></td>
                <td><b>User officer's Sign</b></td>
            </tr>
           <?php  $i = 1; ?>
           @foreach($cars as $car)
              <tr>
                <td>{{ $car->date }}</td>
                <td style="text-align:right">{{ number_format($car->current_meter) }}</td>
                <td style="text-align:right">{{ number_format($car->mileage_difference) }}</td>
                <td style="text-align:right">{{ $car->liter }}</td>
                <td style="text-align:right">{{ number_format($car->rate) }}</td>
                <td style="text-align:right">{{ number_format($car->totalRate) }}</td>
                <td>{{ $car->gasoline_driver_name }}</td>
                <td>
                     @if(!empty($car->sign_photo_name))
                   <!--<img src="{{ public_path('storage/employee/'.$car->sign_photo_name) }}" width="50px" height="50px">-->
                    <img src="{{ ('/home/httpmarubeniyang/public_html/public/employee/'.$car->sign_photo_name )}}" width="50px" height="50px">
                   @endif
                   </td>
              </tr>
           @endforeach

</tbody>
</table>
