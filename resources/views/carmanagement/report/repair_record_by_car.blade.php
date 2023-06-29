<table>  
    <tbody>
          
           <tr>
              <td ><b>Car Number</b></td>
              <td><b>{{ $car_number }}</b></td>
           </tr> 
           <tr>
             <td><b>Current  Mileage</b></td>
             <td><b>{{ number_format($max_current_km) }}</b></td>
          </tr>
          <tr>
             <td rowspan="2"><b>Fiscal Year</b></td>
             <td><b>From</b></td>
             <td><b>To</b></td>
          </tr>
           <tr>
             <td><b>{{ $start_date }}</b></td>
             <td><b>{{ $end_date }}</b></td>
          </tr>
          <tr>
             <td><b>Date</b></td>
             <td style="text-align:right"><b>Amount</b></td>
             <td style="color:red">Repair Category(by filter)<br>
             1.Maintenance<br>
             2.Tyre<br>
             3.Battery<br>
             4.Others</td>
          </tr> 
          @foreach($repair_by_cars as $repair_by_car)
            <?php
               $date = str_replace('-"', '/', $repair_by_car->repair_date);
               $repair_date = date("d/m/Y", strtotime($date));
            ?>
            <tr>
                <td>{{  $repair_date }}</td>
                <td style="text-align:right">{{  number_format($repair_by_car->amount) }}</td>
                <td>{{  $repair_by_car->repair_type }}</td>
            </tr>
            @endforeach
              
</tbody>
</table>