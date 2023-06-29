<table>
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th colspan="2">Casual</th>
                                                <th colspan="2">Earned</th>
                                                <th colspan="2">Medical</th>
                                                <th colspan="2">Maternity</th>
                                                <th colspan="2">Paternity</th>
                                                <th colspan="2">Sick</th>
                                                <th colspan="2">Without Pay Leave</th>
                                                <th colspan="2">Contgratulatory</th>
                                                <th colspan="2">Condolence</th>
                                            </tr>
                                            <tr>
                                                <td>name</td>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                                <th>date</th>
                                                <th>Fullday Or Halfday(AM or PM)</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                           @foreach($departments as $department)      
                                                  @foreach($department->users as  $user)
                                                   <tr>
                                                    <td>{{ $user->employee_name }}</td> 

                                                    <td>                                                  
                                                    <table> 
                                                  @foreach($user->casual as $casual)
                                                    <tr>                                                                        
                                                   <td >{{ $casual->date }}</td>                                                  
                                                   </tr>                                                
                                                   @endforeach                                             
                                                    </table>                                                    
                                                     </td>


                                                     <td>                                                  
                                                    <table> 
                                                  @foreach($user->casual as $casual)
                                                    <tr>                    
                                                   <td>{{ $casual->day_type }}</td>                                                   
                                                   </tr>                                                
                                                   @endforeach                                             
                                                    </table>                                                    
                                                     </td>    
                                                   
                                                     <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->earned as $earned)                                                   
                                                    <tr>               
                                                   <td>{{ $earned->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->earned as $earned)                                                   
                                                    <tr>               
                                                   <td >{{ $earned->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->medical as $medical)                                                   
                                                    <tr>               
                                                   <td >{{ $medical->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>


                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->medical as $medical)                                                   
                                                    <tr>               
                                                   <td >{{ $medical->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->maternity as $maternity)                                                   
                                                    <tr>               
                                                   <td>{{ $maternity->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->maternity as $maternity)                                                   
                                                    <tr>               
                                                   <td>{{ $maternity->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                   <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->paternity as $paternity)                                                   
                                                    <tr>               
                                                   <td>{{ $paternity->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->paternity as $paternity)                                                   
                                                    <tr>               
                                                   <td>{{ $paternity->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->longtermsickleave as $longtermsickleave)                                                   
                                                    <tr>               
                                                   <td>{{ $longtermsickleave->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->longtermsickleave as $longtermsickleave)                                                   
                                                    <tr>               
                                                   <td>{{ $longtermsickleave->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->congratulaty as $congratulaty)                                                   
                                                    <tr>               
                                                   <td>{{ $congratulaty->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->congratulaty as $congratulaty)                                                   
                                                    <tr>               
                                                   <td>{{ $congratulaty->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                     <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->condolence as $condolence)                                                   
                                                    <tr>               
                                                   <td>{{ $condolence->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->condolence as $condolence)                                                   
                                                    <tr>               
                                                   <td>{{ $condolence->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->unpaid as $unpaid)                                                   
                                                    <tr>               
                                                   <td>{{ $unpaid->date }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>

                                                    <td>                                                  
                                                    <table>                                           
                                                   @foreach($user->unpaid as $unpaid)                                                   
                                                    <tr>               
                                                   <td>{{ $unpaid->day_type }}</td>
                                                   </tr>
                                                   @endforeach 
                                                  </table>  
                                                   </td>
                                                  </tr>

                                                   <tr>
                                                    <td>Total</td>
                                                    <td>{{ $user->casualcount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->earnedcount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->medicalcount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->maternitycount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->paternitycount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->longtermsickleavecount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->congratulatycount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->condolencecount }}</td>
                                                    <td></td>
                                                    <td>{{ $user->unpaidcount }}</td>
                                                  </tr>
                                                   @endforeach
                                            @endforeach
                                            

                                        </tbody>

                                    </table>