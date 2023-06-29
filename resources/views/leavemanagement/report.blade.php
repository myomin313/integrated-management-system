<table >
                                        <thead>
                                            
                                            <tr>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Day Type</th>
                                                <th>Leave Type</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                           @foreach($departments as $department) 
                                                @foreach($department->users as  $user)                                                  
                                                  @foreach($user->casual as $casual)
                                                  <tr>
                                                    <td>{{ $casual->employee_name }}</td>                                      
                                                    <td>{{ $casual->date }}</td> 
                                                    <td>{{ $casual->day_type }}</td>
                                                    <td>Casual Leave</td>
                                                 </tr>
                                                   @endforeach
                                                    @endforeach  
                                                   <tr>
                                                    <td>Total</td>
                                                    <td>{{ $user->casualcount }}</td>
                                                  </tr>                                                 
                                                                                                    
                                                  

                                                   @foreach($user->earned as $earned)
                                                  <tr>
                                                    <td>{{ $earned->employee_name }}</td>  
                                                   <td>{{ $earned->date }}</td>
                                                   <td >{{ $earned->day_type }}</td>
                                                   <td>Earned Leave</td>                                                   
                                                 </tr>
                                                   @endforeach 

                                                   @foreach($user->medical as $medical)
                                                    <tr>
                                                    <td>{{ $medical->employee_name }}</td> 
                                                    <td>{{ $medical->date }}</td>
                                                    <td>{{ $medical->day_type }}</td>
                                                    <td>Medical Leave</td>                                                                                                     
                                                 </tr>
                                                   @endforeach

                                                                                       
                                                   @foreach($user->maternity as $maternity)
                                                     <tr>
                                                    <td>{{ $maternity->employee_name }}</td>  
                                                    <td>{{ $maternity->date }}</td>
                                                    <td>{{ $maternity->day_type }}</td>
                                                    <td>Maternity Leave</td>
                                                                                                     
                                                 </tr>
                                                   @endforeach 

                                                   @foreach($user->paternity as $paternity)
                                                     <tr>
                                                    <td>{{ $paternity->employee_name }}</td>   
                                                   <td>{{ $paternity->date }}</td>
                                                   <td>{{ $paternity->day_type }}</td>
                                                   <td>Paternity Leave</td>
                                                                                                    
                                                 </tr>
                                                   @endforeach
                                        
                                                   @foreach($user->longtermsickleave as $longtermsickleave)
                                                     <tr>
                                                   <td>{{ $longtermsickleave->employee_name }}</td> 
                                                   <td>{{ $longtermsickleave->date }}</td>
                                                   <td>{{ $longtermsickleave->day_type }}</td>
                                                   <td>Long Term Sick Leave</td>
                                                                                                    
                                                 </tr>
                                                   @endforeach

                                                   @foreach($user->congratulaty as $congratulaty)
                                                     <tr>
                                                    <td>{{ $congratulaty->employee_name }}</td> 
                                                    <td>{{ $congratulaty->date }}</td>
                                                    <td>{{ $congratulaty->day_type }}</td>
                                                    <td>Congratulaty Leave</td>
                                                                                                     
                                                 </tr>
                                                   @endforeach

                                         
                                                   @foreach($user->condolence as $condolence)
                                                     <tr>
                                                     <td>{{ $condolence->employee_name }}</td>
                                                     <td>{{ $condolence->date }}</td>
                                                     <td>{{ $condolence->day_type }}</td>
                                                     <td>Condolence Leave</td>
                                                                                                      
                                                 </tr>
                                                   @endforeach                           
                                                                                           
                                                   @foreach($user->unpaid as $unpaid)
                                                     <tr>
                                                     <td>{{ $unpaid->employee_name }}</td> 
                                                     <td>{{ $unpaid->date }}</td>
                                                     <td>{{ $unpaid->day_type }}</td>
                                                      <td>{{ $unpaid->day_type }}</td>
                                                      <td>Unpaid Leave</td>
                                                                                                       
                                                 </tr>
                                                   @endforeach
                                                
                                                  
                                            @endforeach
                                            

                                        </tbody>

                                    </table>