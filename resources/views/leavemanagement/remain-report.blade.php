<table>
                                        <thead>
                                            <tr>
                                                <th rowspan="2">Employee Name</th>
                                                 @if(!empty($employee_type == 1))
                                                <th rowspan="2">Dept</th>
                                                 @endif
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
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                                <th>Taken</th>
                                                <th>Remain</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                           @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->employee_name }}</td>
                                                 @if(!empty($employee_type == 1))
                                                <td>{{ $user->docname }}</td>
                                                 @endif
                                               
                                                <td>{{ $user->casualleavedays }}</td>
                                                <td>{{ $casualleave->leave_day  -  $user->casualleavedays }}</td>
                                                <td>{{ $user->earnedleavedays }}</td>
                                                <td>{{ $earnedleave->leave_day - $user->earnedleavedays }}</td>
                                                <td>{{ $user->medicalleavedays  }}</td>
                                                <td>{{ $medicalleave->leave_day - $user->medicalleavedays }}</td>
                                                <td>{{ $user->maternityleavedays }}</td>
                                                <td>{{$maternityleave->leave_day - $user->maternityleavedays }}</td>
                                                <td>{{ $user->paternityleavedays }}</td>
                                                <td>{{  $paternityleave->leave_day - $user->paternityleavedays }}</td>
                                                <td>{{ $user->longtermsickleavedays  }}</td>
                                                <td>{{ $longtermsickleave->leave_day - $user->longtermsickleavedays }}</td>
                                                <td>0</td>
                                                <td>{{ $user->unpaidleavedays }}</td>
                                                <td>{{ $user->congratulatyleavedays }}</td>
                                                <td>{{ $congratulatyleave->leave_day - $user->congratulatyleavedays }}</td>
                                                <td>{{ $user->condolenceleavedays }}</td>
                                                <td>{{ $condolenceleave->leave_day - $user->condolenceleavedays }}</td>
                                                
                                            </tr>
                                            @endforeach
                                            

                                        </tbody>

                                    </table>