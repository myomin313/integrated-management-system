<table>
     <tbody>
          @foreach($datas as $user)
               <!-- start  -->              
               @if(!empty($users))
                 <tr>
              @foreach($users as $employee)
              
            @if($employee  == 'users.employee_id')  
          <td style="background:green">Employee ID</td> 
          @endif
          @if($employee  == 'users.employee_name')  
            <td style="background:green"><h2>Employee Name</h2></td>
          @endif
          @if($employee  == 'users.dob')         
          <td style="background:green">DOB</td>
          @endif
          @if($employee  == 'users.entranced_date') 
          <td style="background:green">Marubeni Entrance Date</td>
          @endif
           @if($employee  == 'users.personal_email') 
          <td style="background:green">Email (Personal) </td>
          @endif
           @if($employee  == 'users.email') 
          <td style="background:green">Email (Office) </td>
          @endif

           @if($employee  == 'users.photo_name') 
          <td>Face Photo</td>
          @endif
          @if($employee  == 'users.sign_photo_name') 
          <td >Sign Photo </td>
          @endif

            @if($employee  == 'employee_types.type as employee_type_name') 
          <td style="background:green">Employee Type </td>
          @endif

          @if($employee  == 'users.working_day_type')
          <td style="background:green">Working Day Type </td>
          @endif
          @if($employee  == 'users.working_start_time')
          <td style="background:green">Working Start Time </td>
          @endif
          @if($employee  == 'users.working_end_time')
          <td style="background:green">Working End Time</td>
          @endif
          @if($employee  == 'users.working_day_per_week')
          <td style="background:green">Working Day per Week</td>
          @endif
           @if($employee  == 'users.position')
          <td style="background:green">Position</td>
          @endif
          @if($employee  == 'users.gender')
          <td style="background:green">Gender</td>
          @endif

          @if($employee  == 'users.gender')
          <td style="background:green">Gender</td>
          @endif
           
           @if($employee  == 'users.marital_status')
          <td style="background:green">Marital Status</td>
          @endif

          @if($employee  == 'rs_employees.graduation_name_of_university')
          <td style="background:green">Graduation Name Of University</td>
          @endif

          @if($employee  == 'rs_employees.major')
          <td style="background:green">Major</td>
          @endif

          
          @if($employee  == 'bank_usd.name as bank_name_usd_data')
          <td style="background:green">Bank Name (USD) </td>
          @endif
           @if($employee  == 'users.bank_account_usd')
          <td style="background:green">Bank Account (USD) </td>
          @endif
          @if($employee  == 'bank_mmk.name as bank_name_mmk_data')
          <td style="background:green">Bank Name (MMK) </td>
          @endif
          @if($employee  == 'users.bank_account_mmk')
          <td style="background:green">Bank Account (MMK)  </td>
          @endif

           @if($employee  == 'bank_mmk2.second_bank_name_mmk as second_bank_name_mmk_data')
          <td style="background:green">Bank Name (MMK) </td>
          @endif
          @if($employee  == 'bank_mmk2.second_bank_account_mmk as second_bank_account_mmk_data')
          <td style="background:green">Bank Account (MMK)  </td>
          @endif
           @if($employee  == 'users.ssc_no')
          <td style="background:green">SSC No</td>
          @endif
           @if($employee  == 'users.blood_type')
          <td style="background:green">Blood Type</td>
          @endif
           @if($employee  == 'rs_employees.final_education')
          <td style="background:green">Final Education</td>
          @endif          
         
          @if($employee  == 'users.passport_number')
          <td style="background:green">Passport No </td>
          @endif
           @if($employee  == 'users.date_of_issue')
          <td style="background:green">Date of Issue  </td>
          @endif
           @if($employee  == 'users.date_of_expire')
          <td style="background:green">Date of Expiry  </td>
          @endif
           @if($employee  == 'rs_employees.residant_place')
          <td style="background:green">Residant Place</td>
          @endif

          @if($employee  == 'rs_employees.mjsrv')
          <td style="background:green">MJSRV</td>
          @endif
           @if($employee  == 'rs_employees.mjsrv_expire_date')
          <td style="background:green">MJSRV Expire Date</td>
          @endif

          @if($employee  == 'rs_employees.stay_permit')
          <td style="background:green">Stay Permit</td>
          @endif
           @if($employee  == 'rs_employees.stay_permit_expire_date')
          <td style="background:green">Stay Permit Expire Date</td>
          @endif

           @if($employee  == 'rs_employees.form_c')
          <td style="background:green">Form C</td>
          @endif
           @if($employee  == 'rs_employees.frc_no')
          <td style="background:green">FRC NO</td>
          @endif

           @if($employee  == 'rs_employees.aboard_date')
          <td style="background:green">Abord Date</td>
          @endif
           @if($employee  == 'rs_employees.japan_address')
          <td style="background:green">Address In Japan </td>
          @endif

           @if($employee  == 'rs_employees.japan_phone')
          <td style="background:green">Phone In Japan </td>
          @endif

           @if($employee  == 'rs_employees.japan_hot_line')
          <td style="background:green">Hot Line In Japan </td>
          @endif

           @if($employee  == 'rs_employees.myanmar_address')
          <td style="background:green">Address In Myanmar </td>
          @endif

           @if($employee  == 'users.phone')
          <td style="background:green">Phone In Myanmar </td>
          @endif

           @if($employee  == 'users.other_changing_condition')
          <td style="background:green">Other Changing Condition</td>
          @endif

           @if($employee  == 'users.other_changing_condition')
          <td style="background:green">Other Changing Condition</td>
          @endif

          @if($employee  == 'branches.name as branch_name')
          <td style="background:green">Branch</td>
          @endif

           @if($employee  == 'users.department_id')
          <td style="background:green">Departments</td>
          @endif
         
          @if($employee  == 'contact_infos.first_person_name')
            <td style="background:green">First Contact Person Name</td>
          @endif
          @if($employee  == 'contact_infos.first_person_email')
            <td style="background:green">First Contact Person Email</td>
          @endif
          @if($employee  == 'contact_infos.first_person_phone')
            <td style="background:green">First Contact Person Phone</td>
          @endif
          @if($employee  == 'contact_infos.first_person_hotline')
            <td style="background:green">First Contact Person Hotline</td>
          @endif
          @if($employee  == 'contact_infos.first_person_relationship')
            <td style="background:green">First Contact Person Relationship </td>
          @endif
           @if($employee  == 'contact_infos.first_person_address')
            <td style="background:green">First Contact Person Address </td>
          @endif
          @if($employee  == 'contact_infos.second_person_name')
            <td style="background:green">Second Contact Person Name</td>
          @endif
          @if($employee  == 'contact_infos.second_person_email')
            <td style="background:green">Second Contact Person Email</td>
          @endif
           @if($employee  == 'contact_infos.second_person_phone')
            <td style="background:green">Second Contact Person Phone</td>
          @endif
          @if($employee  == 'contact_infos.second_person_hotline')
            <td style="background:green">Second Contact Person Hotline</td>
          @endif
          @if($employee  == 'contact_infos.second_person_relationship')
            <td style="background:green">Second Contact Person Relationship </td>
          @endif
          @if($employee  == 'contact_infos.second_person_address')
            <td style="background:green">Second Contact Person Address </td>
          @endif  
      @endforeach                
      </tr>
      @endif
               <!-- end -->
                @if(!empty($users))
               <tr>   
                @foreach($users as $employee) 
               @if($employee  == 'users.employee_id')
              <td>{{ $user->employee_id }}</td>
              @endif
              
              @if($employee  == 'users.employee_name')          
              <td>{{ $user->employee_name  }}</td>
              @endif
               @if($employee  == 'users.dob')
              <td >{{ $user->dob  }}</td>
              @endif
               @if($employee  == 'users.entranced_date')
              <td>{{ $user->entranced_date  }}</td>
              @endif
               @if($employee  == 'users.personal_email')
              <td>{{ $user->personal_email  }}</td>
              @endif
               @if($employee  == 'users.email')
              <td>{{ $user->email  }}</td>
              @endif

                @if($employee  == 'users.photo_name')
              <td> @if(!empty($user->photo_name))
                   <img src="{{ ('/home/httpmarubeniyang/public_html/public/employee/'.$user->photo_name )}}" width="50px" height="50px">
                   @endif</td>
              @endif
               @if($employee  == 'users.sign_photo_name')
              <td>@if(!empty($user->sign_photo_name))
                   <img src="{{ ('/home/httpmarubeniyang/public_html/public/employee/'.$user->sign_photo_name )}}" width="50px" height="50px">
                   @endif</td>
              @endif

              @if($employee  == 'employee_types.type as employee_type_name')
              <td>{{ $user->employee_type_name  }}</td>
              @endif
              
              @if($employee  == 'users.working_day_type')
              <td>{{ $user->working_day_type   }}</td>
              @endif
              @if($employee  == 'users.working_start_time')
              <td>{{ $user->working_start_time   }}</td>
              @endif
              @if($employee  == 'users.working_end_time')
              <td>{{ $user->working_end_time   }}</td>
              @endif
              @if($employee  == 'users.working_day_per_week')
              <td>{{ $user->working_day_per_week   }}</td>
              @endif

              @if($employee  == 'users.position')
              <td>{{ $user->position   }}</td>
              @endif

              @if($employee  == 'users.gender')
              <td>{{ $user->gender  }}</td>
               @endif               
                @if($employee  == 'users.marital_status')
              <td>{{ $user->marital_status  }}</td>
               @endif

               @if($employee  == 'rs_employees.graduation_name_of_university')
              <td>{{ $user->graduation_name_of_university  }}</td>
               @endif
                @if($employee  == 'rs_employees.major')
              <td>{{ $user->major  }}</td>
               @endif

                @if($employee  == 'bank_usd.name as bank_name_usd_data')
              <td>{{ $user->bank_name_usd_data    }}</td>
               @endif
               @if($employee  == 'users.bank_account_usd')
              <td>{{ $user->bank_account_usd  }}</td>
              @endif
                @if($employee  == 'bank_mmk.name as bank_name_mmk_data')
              <td>{{ $user->bank_name_mmk_data   }}</td>
              @endif
              @if($employee  == 'users.bank_account_mmk')
              <td>{{ $user->bank_account_mmk }}</td>
              @endif

               @if($employee  == 'bank_mmk2.second_bank_name_mmk as second_bank_name_mmk_data')
              <td >{{ $user->second_bank_name_mmk_data   }}</td>
              @endif
              @if($employee  == 'bank_mmk2.second_bank_account_mmk as second_bank_account_mmk_data')
              <td>{{ $user->second_bank_account_mmk_data }}</td>
              @endif
              @if($employee  == 'users.ssc_no')
              <td>{{ $user->ssc_no }}</td>
               @endif 

              @if($employee  == 'users.blood_type')
              <td>{{ $user->blood_type   }}</td>
              @endif
                @if($employee  == 'rs_employees.final_education')
              <td>{{ $user->final_education   }}</td>
              @endif 
               @if($employee  == 'users.passport_number')
              <td>{{ $user->passport_number }}</td>
              @endif
               @if($employee  == 'users.date_of_issue')
              <td>{{ $user->date_of_issue }}</td>
              @endif
               @if($employee  == 'users.date_of_expire')
              <td>{{ $user->date_of_expire }}</td>
              @endif

              @if($employee  == 'rs_employees.residant_place')
              <td>{{ $user->residant_place }}</td>
              @endif

              @if($employee  == 'rs_employees.mjsrv')
              <td>{{ $user->mjsrv }}</td>
              @endif
               @if($employee  == 'rs_employees.mjsrv_expire_date')
              <td>{{ $user->mjsrv_expire_date }}</td>
              @endif

               @if($employee  == 'rs_employees.stay_permit')
              <td >{{ $user->mjsrv }}</td>
              @endif
               @if($employee  == 'rs_employees.stay_permit_expire_date')
              <td>{{ $user->stay_permit_expire_date }}</td>
              @endif

              @if($employee  == 'rs_employees.form_c')
              <td>{{ $user->form_c }}</td>
              @endif
              @if($employee  == 'rs_employees.frc_no')
              <td>{{ $user->frc_no }}</td>
              @endif
               @if($employee  == 'rs_employees.aboard_date')
              <td>{{ $user->aboard_date }}</td>
              @endif
               @if($employee  == 'rs_employees.japan_address')
              <td>{{ $user->japan_address }}</td>
              @endif
               @if($employee  == 'rs_employees.japan_phone')
              <td>{{ $user->japan_phone }}</td>
              @endif
              @if($employee  == 'rs_employees.japan_hot_line')
              <td>{{ $user->japan_hot_line  }}</td>
              @endif
               @if($employee  == 'rs_employees.myanmar_address')
              <td>{{ $user->myanmar_address  }}</td>
              @endif
               @if($employee  == 'users.phone')
              <td>{{ $user->phone  }}</td>
              @endif
               @if($employee  == 'users.other_changing_condition')
              <td>{{ $user->other_changing_condition  }}</td>
              @endif
               @if($employee  == 'branches.name as branch_name')
              <td>{{ $user->branch_name  }}</td>
              @endif
              @if($employee  == 'users.department_id')
              <td>{{ $user->departments  }}</td>
              @endif


               @if($employee  == 'contact_infos.first_person_name')
              <td>{{ $user->first_person_name  }}</td>
              @endif
               @if($employee  == 'contact_infos.first_person_email')
              <td>{{ $user->first_person_email }}</td>
              @endif
              @if($employee  == 'contact_infos.first_person_name')
              <td>{{ $user->first_person_phone }}</td>
              @endif
              @if($employee  == 'contact_infos.first_person_hotline')
              <td>{{ $user->first_person_hotline }}</td>
              @endif
              @if($employee  == 'contact_infos.first_person_relationship')
              <td>{{ $user->first_person_relationship  }}</td>
              @endif
               @if($employee  == 'contact_infos.first_person_address')
              <td>{{ $user->first_person_address }}</td>
              @endif
               @if($employee  == 'contact_infos.second_person_name')
              <td>{{ $user->second_person_name }}</td>
              @endif
              @if($employee  == 'contact_infos.second_person_email')
              <td>{{ $user->second_person_email  }}</td>
              @endif
               @if($employee  == 'contact_infos.second_person_phone')
              <td>{{ $user->second_person_phone }}</td>
              @endif
               @if($employee  == 'contact_infos.second_person_hotline')
              <td>{{ $user->second_person_hotline }}</td>
              @endif
              @if($employee  == 'contact_infos.second_person_relationship')
              <td>{{ $user->second_person_relationship }}</td>
              @endif
              @if($employee  == 'contact_infos.second_person_address')
              <td >{{ $user->second_person_address }}</td>
              @endif
              @endforeach
              </tr>
              @endif
              <!-- start family relationship -->
               @if(!empty($families))
               <tr>
              @foreach($families as $familiy)
              
               @if($familiy  == 'families.relationship')
                <td style="background:green">Family Relationship</td>
                @endif
                @if($familiy  == 'families.name')
                <td style="background:green">Family Name</td>
                @endif
                 @if($familiy  == 'families.allowance')
                <td style="background:green">Family Allowance</td>
                @endif
                @if($familiy  == 'families.allowance_fee')
                <td style="background:green">Family Allowance Fee</td>
                @endif
                  @if($familiy  == 'families.family_dob')
                <td style="background:green">Family DOB</td>
                @endif
                 @if($familiy  == 'families.work')
                <td style="background:green">Family Work / School </td>
                @endif               
              @endforeach              
              </tr>
              @endif
              <!-- end family relationship -->
              @if(!empty($user->family_information))
              @foreach($user->family_information as $family)
                <tr>
                  <td>{{ $family->relationship}}</td>
                  <td>{{ $family->name }}</td>                  
                  <td>{{ $family->allowance }}</td>
                  <td>{{ $family->allowance_fee }}</td>
                  <td>{{ $family->family_dob }}</td>
                  <td>{{ $family->work }}</td>
                </tr>
              @endforeach
              @endif

              @if(!empty($rs_leave_data))              
               <tr>  
              @foreach($rs_leave_data as $rs_leave)
                @if($rs_leave  == 'rs_leave_data.year')              
                  <td style="background:green">Year </td>
                @endif
                @if($rs_leave  == 'rs_leave_data.earned_leaves')  
                  <td style="background:green">Earned Leaves </td>
                @endif
                @if($rs_leave  == 'rs_leave_data.refresh_leaves')  
                  <td style="background:green">Refresh Leaves </td>
                @endif
               @endforeach               
               </tr>
              @endif
              
              @if(!empty($user->rs_leave_data))           
              @foreach($user->rs_leave_data as $rs_leave_info)
              <tr>
                <td>{{ $rs_leave_info->year}}</td>
                <td>{{ $rs_leave_info->earned_leaves }}</td>
                <td>{{ $rs_leave_info->refresh_leaves }}</td>
              </tr>
              @endforeach
              @endif


              @if(!empty($rs_refresh_leaves))              
               <tr>  
              @foreach($rs_refresh_leaves as $rs_refresh_leave)
                @if($rs_refresh_leave  == 'rs_refresh_leaves.refresh_leaves')              
                  <td style="background:green">Refresh Leave </td>
                @endif
                @if($rs_refresh_leave  == 'rs_refresh_leaves.earned_leaves')  
                  <td style="background:green">Earned Leaves </td>
                @endif
                @if($rs_refresh_leave  == 'rs_refresh_leaves.other')  
                  <td style="background:green">Other</td>
                @endif
                 @if($rs_refresh_leave  == 'rs_refresh_leaves.airfare')  
                  <td style="background:green">Airfare </td>
                @endif
                @if($rs_refresh_leave  == 'rs_refresh_leaves.start_date')  
                  <td style="background:green">Start Date  </td>
                @endif
                @if($rs_refresh_leave  == 'rs_refresh_leaves.end_date')  
                  <td style="background:green">End Date </td>
                @endif
               @endforeach               
               </tr>
              @endif
              
              @if(!empty($user->rs_refresh_leaves))           
              @foreach($user->rs_refresh_leaves as $rs_refresh_leave)
              <tr>
                <td>{{ $rs_refresh_leave->refresh_leaves}}</td>
                <td>{{ $rs_refresh_leave->earned_leaves }}</td>
                <td>{{ $rs_refresh_leave->other }}</td>
                <td>{{ $rs_refresh_leave->airfare }}</td>
                <td>{{ $rs_refresh_leave->start_date }}</td>
                <td>{{ $rs_refresh_leave->end_date }}</td>
              </tr>
              @endforeach
              @endif

               @endforeach

           
     </tbody>

</table>