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
          <td style="background:green">Employee Name</td>
          @endif
          @if($employee  == 'users.dob')         
          <td style="background:green">DOB</td>
          @endif
          @if($employee  == 'users.entranced_date') 
          <td style="background:green">Entrance Date</td>
          @endif
           @if($employee  == 'users.personal_email') 
          <td style="background:green">Email (Personal) </td>
          @endif
           @if($employee  == 'users.email') 
          <td style="background:green">Email (Office) </td>
          @endif
          @if($employee  == 'employee_types.type as employee_type_name') 
          <td style="background:green">Employee Type </td>
          @endif
           @if($employee  == 'users.gender')
          <td style="background:green">Gender</td>
          @endif
           @if($employee  == 'users.marital_status')
          <td style="background:green">Marital Status</td>
          @endif
           @if($employee  == 'users.blood_type')
          <td style="background:green">Blood Type</td>
          @endif
           @if($employee  == 'users.ssc_no')
          <td style="background:green">SSC No</td>
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
          @if($employee  == 'users.passport_number')
          <td style="background:green">Passport No </td>
          @endif
           @if($employee  == 'users.date_of_issue')
          <td style="background:green">Date of Issue  </td>
          @endif
           @if($employee  == 'users.date_of_expire')
          <td style="background:green">Date of Expiry  </td>
          @endif
            @if($employee  == 'ns_employees.grade')
          <td style="background:green">Grade</td>
          @endif
          @if($employee  == 'ns_employees.nrc_no')
          <td style="background:green">NRC No</td>
          @endif
          @if($employee  == 'users.phone')
          <td style="background:green">Phone </td>
          @endif
            @if($employee  == 'ns_employees.religion')
          <td style="background:green">Religion </td>
          @endif
          @if($employee  == 'ns_employees.race')
          <td style="background:green">Race  </td>
          @endif
          @if($employee  == 'ns_employees.current_address')
          <td style="background:green">Current Address </td>
          @endif
          @if($employee  == 'ns_employees.new_address')
          <td style="background:green">New Address</td>
          @endif
           @if($employee  == 'ns_employees.new_phone')
          <td style="background:green">New Phone </td>
          @endif
           @if($employee  == 'ns_employees.others_address')
          <td style="background:green"> Other Address </td>
          @endif
          @if($employee  == 'ns_employees.others_phone')
          <td style="background:green"> Other Phone  </td> 
          @endif
           @if($employee  == 'ns_employees.employment_contract_no')         
          <td style="background:green">Employment Contract No</td>
          @endif
            @if($employee  == 'ns_employees.hourly_rate')  
          <td style="background:green">Hourly Rate</td> 
          @endif
          @if($employee  == 'users.photo_name') 
          <td style="background:green">Face Photo</td>
          @endif
          @if($employee  == 'users.sign_photo_name') 
          <td style="background:green">Sign Photo </td>
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
              @if($employee  == 'employee_types.type as employee_type_name')
              <td>{{ $user->employee_type_name  }}</td>
              @endif
              @if($employee  == 'users.gender')
              <td>{{ $user->gender  }}</td>
               @endif
                @if($employee  == 'users.marital_status')
              <td>{{ $user->marital_status  }}</td>
               @endif
              @if($employee  == 'users.blood_type')
              <td>{{ $user->blood_type   }}</td>
              @endif
              @if($employee  == 'users.ssc_no')
              <td>{{ $user->ssc_no }}</td>
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
               @if($employee  == 'users.passport_number')
              <td>{{ $user->passport_number }}</td>
              @endif
               @if($employee  == 'users.date_of_issue')
              <td>{{ $user->date_of_issue }}</td>
              @endif
               @if($employee  == 'users.date_of_expire')
              <td>{{ $user->date_of_expire }}</td>
              @endif
               @if($employee  == 'ns_employees.grade')
              <td>{{ $user->grade }}</td>
              @endif
               @if($employee  == 'ns_employees.nrc_no')
              <td>{{ $user->nrc_no }}</td>
              @endif
              @if($employee  == 'users.phone')
              <td>{{ $user->phone }}</td>
              @endif
              @if($employee  == 'ns_employees.religion')
              <td>{{ $user->religion }}</td>
              @endif
               @if($employee  == 'ns_employees.race')
              <td>{{ $user->race }}</td>
              @endif
               @if($employee  == 'ns_employees.current_address')
              <td>{{ $user->current_address }}</td>
              @endif
               @if($employee  == 'ns_employees.new_address')
              <td>{{ $user->new_address }}</td>
              @endif
              @if($employee  == 'ns_employees.new_phone')
              <td>{{ $user->new_phone }}</td>
              @endif
              @if($employee  == 'ns_employees.others_address')
              <td>{{ $user->others_address }}</td>
              @endif
              @if($employee  == 'ns_employees.others_phone')
              <td>{{ $user->others_phone }}</td>
              @endif
               @if($employee  == 'ns_employees.employment_contract_no')
              <td>{{ $user->employment_contract_no }}</td>
              @endif
               @if($employee  == 'ns_employees.hourly_rate')
              <td>{{ $user->hourly_rate  }}</td>
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
              @if($employee  == 'users.other_changing_condition')
              <td>{{ $user->other_changing_condition   }}</td>
              @endif
               @if($employee  == 'branches.name as branch_name')
              <td>{{ $user->branch_name   }}</td>
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
              @if($employee  == 'contact_infos.first_person_phone')
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

              @if(!empty($life_assurances))              
               <tr>  
              @foreach($life_assurances as $assurance)
                @if($assurance  == 'life_assurances.year')              
                  <td style="background:green">Life Assurance Year </td>
                @endif
                @if($assurance  == 'life_assurances.premium_amount')  
                  <td style="background:green">Life Assurance Premium Amount  </td>
                @endif
               @endforeach               
               </tr>
              @endif
              
              @if(!empty($user->life_assurances))           
              @foreach($user->life_assurances as $assurance)
              <tr>
                <td>{{ $assurance->year}}</td>
                <td>{{ $assurance->premium_amount }}</td>
              </tr>
              @endforeach
              @endif
             
            @if(!empty($education))            
              <tr>
              @foreach($education as $edu)
                 @if($edu  == 'education.education_type') 
                <td style="background:green">Education Type </td>
                @endif
                 @if($edu  == 'education.school_name') 
                <td style="background:green">School Name  </td>
                @endif
                 @if($edu  == 'education.date_of_graduation') 
                <td style="background:green">Year Of Education </td>
                @endif
                 @if($edu  == 'education.major') 
                 <td style="background:green">Major </td>
                 @endif
              @endforeach              
              </tr>
              @endif
             
               @if(!empty($user->education)) 
              @foreach($user->education as $education)
              <tr>
                <td>{{ $education->education_type }}</td>
                <td>{{ $education->school_name  }}</td>
                <td>{{ $education->date_of_graduation  }}</td>
                <td>{{ $education->major  }}</td>
              </tr>
              @endforeach
              @endif
             
              @if(!empty($qualifications))              
              <tr>
              @foreach($qualifications as $qualification)
                 @if($qualification  == 'qualifications.date_of_acquition') 
                 <td style="background:green">Qualification Year </td>
                 @endif
                 @if($qualification  == 'qualifications.certificate') 
                 <td style="background:green">Qualification Certificate </td>
                 @endif
              @endforeach              
              </tr> 
              @endif
                        
                          
               @if(!empty($user->qualifications)) 
              @foreach($user->qualifications as $qualification)
              <tr>
              <td>{{ $qualification->date_of_acquition }}</td>
              <td>{{ $qualification->certificate  }}</td>
              </tr>
              @endforeach
              @endif

                @if(!empty($languages))                
              <tr>
              @foreach($languages as $language)
                @if($language  == 'languages.language_skill') 
                <td style="background:green">Language Skill</td>
                @endif
                 @if($language  == 'languages.level') 
                <td style="background:green">Language Level</td>
                @endif
              @endforeach              
              </tr> 
              @endif 

              @if(!empty($user->languages)) 
              @foreach($user->languages as $language)
              <tr>
              <td>{{ $language->language_skill }}</td>
              <td>{{ $language->level  }}</td>
              </tr>
              @endforeach
              @endif
          
              @if(!empty($english_skills))                
              <tr>
              @foreach($english_skills as $english_skill) 
               @if($english_skill  == 'english_skills.test_type') 
                 <td style="background:green">Type Of English Test</td>
               @endif
                @if($english_skill  == 'english_skills.mark') 
               <td style="background:green">English Mark </td>
               @endif
               @if($english_skill  == 'english_skills.level') 
               <td style="background:green">English Level</td>
               @endif
                @if($english_skill  == 'english_skills.date_of_acquition') 
               <td style="background:green">English Year </td>
               @endif
              @endforeach
              </tr>
              @endif

              @if(!empty($user->english_skills))
              @foreach($user->english_skills as $english)
              <tr>
              <td>{{ $english->test_type }}</td>
              <td>{{ $english->mark  }}</td>
              <td>{{ $english->level  }}</td>
              <td>{{ $english->date_of_acquition  }}</td>
             </tr>            
              @endforeach
              @endif
              
              @if(!empty($driver_licenses))              
              <tr>
              @foreach($driver_licenses as $driver_license) 
              @if($driver_license  == 'driver_licenses.license_number')
                <td style="background:green">License Number</td>
              @endif
               @if($driver_license  == 'driver_licenses.start_date')
              <td style="background:green">License Start Date </td>
              @endif
              @if($driver_license  == 'driver_licenses.due_date')
              <td style="background:green">License Due Date </td>
              @endif
             @endforeach             
             </tr>
             @endif

              @if(!empty($user->driver_licenses))
              @foreach($user->driver_licenses as $license)
              <tr>
                <td>{{ $license->license_number }}</td>
                <td>{{ $license->start_date }}</td>
                <td>{{ $license->due_date  }}</td>
              </tr>            
              @endforeach
              @endif
              
               @if(!empty($pc_skills))                
              <tr>
              @foreach($pc_skills as $pc_skill)
               @if($pc_skill  == 'pc_skills.title')
               <td style="background:green">PC Skill Title</td>
               @endif
               @if($pc_skill  == 'pc_skills.level')
                <td style="background:green">PC Skill Level </td>
               @endif
              @endforeach              
              </tr>
              @endif

               @if(!empty($user->pc_skills))
              @foreach($user->pc_skills as $pc_skill)
              <tr>
                <td>{{ $pc_skill->title }}</td>
                <td>{{ $pc_skill->level }}</td> 
              </tr>            
              @endforeach
              @endif
            
              @if(!empty($employment_records))               
            <tr>
              @foreach($employment_records as $employment_record)
              @if($employment_record  == 'employment_records.company_name')
              <td style="background:green">Company Name</td>
              @endif
              @if($employment_record  == 'employment_records.start_date')
              <td style="background:green">From</td>
              @endif
              @if($employment_record  == 'employment_records.end_date')
              <td style="background:green">To</td>
              @endif
              @if($employment_record  == 'employment_records.position')
              <td style="background:green">Position </td>
              @endif
               @if($employment_record  == 'employment_records.department')
              <td style="background:green">Department</td>
              @endif
            @endforeach            
            </tr>
            @endif

              @if(!empty($user->employment_records))
              @foreach($user->employment_records as $employment_record)
              <tr>
              <td>{{ $employment_record->company_name }}</td>
              <td>{{ $employment_record->start_date }}</td>
              <td>{{ $employment_record->end_date }}</td>              
              <td>{{ $employment_record->position }}</td>
              <td>{{ $employment_record->department }}</td> 
              </tr>              
              @endforeach
              @endif
               
               @if(!empty($oversea_records))                
              <tr>
              @foreach($oversea_records as $oversea_record)
                @if($oversea_record  == 'oversea_records.country_name')
                <td style="background:green">country Name (Oversea Record)</td>
                @endif
                @if($oversea_record  == 'oversea_records.start_date')
                <td style="background:green">From (Oversea Record)</td>
                @endif
                 @if($oversea_record  == 'oversea_records.end_date')
                <td style="background:green">To (Oversea Record)</td>
                @endif
                 @if($oversea_record  == 'oversea_records.purpose')
                <td style="background:green">Purpose (Oversea Record)</td>
                 @endif
              @endforeach              
              </tr>
              @endif

               @if(!empty($user->oversea_records))
               @foreach($user->oversea_records as $oversea_record)
               <tr>
              <td>{{ $oversea_record->country_name }}</td>
              <td>{{ $oversea_record->start_date }}</td>
              <td>{{ $oversea_record->end_date }}</td>              
              <td>{{ $oversea_record->purpose  }}</td>
              </tr>              
              @endforeach
              @endif
              
              @if(!empty($warnings))              
              <tr>
              @foreach($warnings as $warning)
              @if($warning  == 'warnings.date')
                <td style="background:green">Warning Date</td>
               @endif
               @if($warning  == 'warnings.reason')
               <td style="background:green">Warning Reason</td>
               @endif
              @endforeach              
              </tr>
              @endif

               @if(!empty($user->warnings))
               @foreach($user->warnings as $warning)
               <tr>
                 <td>{{ $warning->date }}</td>
                 <td>{{ $warning->reason }}</td>
              </tr>              
              @endforeach
              @endif
              

              @if(!empty($users))
              <tr>
              @foreach($users as $retire)
               @if($retire  == 'retirements.date as retirement_date') 
               <td style="background:green">Retirement Date</td>
                @endif
               @if($retire  == 'retirements.reason as retirement_reason') 
              <td style="background:green">Retirement Reason</td>
               @endif
               @endforeach
             </tr>
              @endif
              
              <tr>
              @if(!empty($user->retirement_date))
              <td>{{ $user->retirement_date }}</td>
               @endif
              @if(!empty($user->retirement_reason))
              <td>{{ $user->retirement_reason }}</td>
              @endif
              </tr>

               @if(!empty($users))
               <tr>
              @foreach($users as $other)
               @if($other  == 'others.interest')
               <td style="background:green">Interest</td>
               @endif
              @if($other  == 'others.strong_point')
               <td style="background:green">Strong Point</td>
               @endif
               @if($other  == 'others.weak_point')
                <td style="background:green">Weak Point</td>
               @endif               
               @endforeach
               </tr>
               @endif
              
               <tr>
              @if(!empty($user->interest))
                <td>{{ $user->interest }}</td>
              @endif
              @if(!empty($user->strong_point))
                <td>{{ $user->strong_point  }}</td>
              @endif
              @if(!empty($user->weak_point))
                <td>{{ $user->weak_point }}</td>
              @endif
              </tr>

           @if(!empty($evaluations))           
          <tr> 
          @foreach($evaluations as $evaluation)
            @if($evaluation  == 'evaluations.year')  
          <td style="background:green">Year</td>
          @endif
          @if($evaluation  == 'evaluations.grade')
          <td style="background:green">Band</td>
          @endif
           @if($evaluation  == 'evaluations.title')
          <td style="background:green">Title</td>
          @endif
           @if($evaluation  == 'evaluations.position')
          <td style="background:green">Position</td>
          @endif
          @if($evaluation  == 'evaluations.competency')
          <td style="background:green">Competency</td>
          @endif
          @if($evaluation  == 'evaluations.performance')
          <td style="background:green">Performance</td>
          @endif
          @if($evaluation  == 'evaluations.net_pay')
          <td style="background:green">Salary(Net Pay)</td>
          @endif
          @if($evaluation  == 'evaluations.basic_salary')
          <td style="background:green">Basic Salary </td>
          @endif
          @if($evaluation  == 'evaluations.allowance')
          <td style="background:green">Duty Allowance </td>
          @endif
           @if($evaluation  == 'evaluations.ot_rate')
          <td style="background:green">OT Rate </td>
          @endif
          @if($evaluation  == 'evaluations.water_festival_bonus')
          <td style="background:green">Bonus (Water Festival)  </td>
          @endif
          @if($evaluation  == 'evaluations.thadingyut_bonus')
          <td style="background:green">Bonus (Thadingyut)  </td>
          @endif
          @endforeach          
          </tr>
          @endif

             
               @if(!empty($user->evaluations))
              @foreach($user->evaluations as $evaluation)
              <tr>
              <td>{{ $evaluation->year }}</td>
              <td>{{ $evaluation->grade }}</td>
              <td>{{ $evaluation->title }}</td>
              <td>{{ $evaluation->position }}</td>
              <td>{{ $evaluation->competency }}</td>
              <td>{{ $evaluation->performance }}</td>
              <td>{{ $evaluation->net_pay }}</td>
              <td>{{ $evaluation->basic_salary }}</td>
              <td>{{ $evaluation->allowance }}</td>
              <td>{{ $evaluation->ot_rate }}</td>
              <td>{{ $evaluation->water_festival_bonus }}</td>
              <td>{{ $evaluation->thadingyut_bonus }}</td> 
              </tr>            
              @endforeach
              @endif

               @endforeach

           
     </tbody>

</table>