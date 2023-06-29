@extends('layouts.master')
@section('title','RS Edit')
@section('content')

@if( Session::has('error'))
  <div class="alert alert-danger" align="center">
  <p>{{ Session::get('error') }}</p>
  </div>
@endif

@if( Session::has('success'))
  <div class="alert alert-success" align="center">
  <p>{{ Session::get('success') }}</p>
  </div>
@endif

 <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Employee Management</li>
                <li class="breadcrumb-item active">RS Employee Management</li>
              </ol>
            </div><!-- /.col -->
            <div class="col-sm-6">

            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

        <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Edit RS Employee</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="card card-default collapsed-card basic-info">
                    <div class="card-header">
                      <h3 class="card-title">Basic Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form method="POST" id="basic-update" enctype="multipart/form-data">
                        @csrf
                        
                       
                         <input type="hidden" name="id" value="{{ $user->id }}">

                        <input type="hidden" name="old_photo_name" value="{{ $user->photo_name }}">
                        <input type="hidden" name="old_sign_photo_name" value="{{ $user->sign_photo_name }}">

                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="employee_id">Employee ID </label>
                              <input type="text" class="form-control" value="{{ old('employee_id',$user->employee_id) }}" id="employee_id" name="employee_id">
                              <span class="text-danger error-text employee_id_error"></span>
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="employee_name">Name <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" value="{{ old('employee_name',$user->employee_name) }}" id="employee_name" name="employee_name">
                               <span class="text-danger error-text employee_name_error"></span>
                            </div>
                          </div>
                           <?php
                           if(!empty($user->dob)){
                               $dob_date = str_replace('-"', '/', $user->dob);
                               $dob = date("d/m/Y", strtotime($dob_date));
                           }else{
                               $dob='';
                           }
                           ?>

                          <div class="col-md-4 col-sm-4">
                               <div class="form-group">
                              <label for="entrance_date">DOB <span class="required text-danger">*</span></label>
                              <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                <input type="text" name="dob" value="{{ old('dob',$dob) }}" id="dob"
                                  class="form-control datetimepicker-input" data-target="#datetimepicker" />
                                <div class="input-group-append" data-target="#datetimepicker"
                                  data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>                                
                              </div>
                              <span class="text-danger error-text dob_error"></span>
                            </div>
                          </div>
                        </div>
                        <?php
                           if(!empty($user->entranced_date)){
                               $entranced_date_data = str_replace('-', '/', $user->entranced_date);
                               $entranced_date = date("d/m/Y", strtotime($entranced_date_data));
                           }else{
                               $entranced_date='';
                           }
                           ?>

                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="entranced_date">Marubeni Entrance Date <span class="required text-danger">*</span></label>
                              <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" name="entranced_date" id="entranced_date" value="{{ old('entranced_date',$entranced_date) }}" 
                                  class="form-control datetimepicker2-input" data-target="#datetimepicker2" />
                                <div class="input-group-append" data-target="#datetimepicker2"
                                  data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                              </div>
                               <span class="text-danger error-text entranced_date_error"></span>
                            </div>
                          </div>
                          
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="prersonal_email">Email (Personal) <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ old('personal_email',$user->personal_email) }}" id="personal_email" name="personal_email">
                               <span class="text-danger error-text personal_email_error"></span>
                          </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="office_email">Email (Office) <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" value="{{ old('email',$user->email) }}" id="office_email" name="office_email">
                              <span class="text-danger error-text office_email_error"></span>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            @if(!empty($user->photo_name))
                            <img width="100px" id="preview-photo" src="{{url('/employee/'.$user->photo_name )}}" alt="profile" title="profile">
                            @else
                            <img width="100px" id="preview-photo">
                            @endif
                            <div class="form-group">
                              <label for="photo">Face Photo </label><br>
                              <input type="file" id="photo" name="photo" accept="image/png,image/jpeg">
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                             @if(!empty($user->sign_photo_name))
                             <img id="preview-sign-photo" src="{{url('/employee/'.$user->sign_photo_name )}}" width="100px" alt="sign photo" title="sign photo">
                             @else
                              <img id="preview-sign-photo"  width="100px">
                             @endif
                            <div class="form-group">
                              <label for="sign_photo">Sign Photo</label><br>
                              <input type="file" id="sign_photo" name="sign_photo" accept="image/png,image/jpeg">
                               <span class="text-danger error-text sign_photo_error"></span>
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="employee_type">Employee Type <span class="required text-danger">*</span></label>
                              <select class="form-control select2bs4" name="employee_type" id="employee_type"
                                style="width: 100%;" >
                                <option value="">- Select -</option>
                                @foreach($employee_types as $employee_type)
                                <option  value="{{ $employee_type->id }}" {{old('employee_type',$user->employee_type_id)== $employee_type->id ? 'selected':''}}>{{ $employee_type->type}}</option>
                                @endforeach
                              </select>
                              <span class="text-danger error-text employee_type_error"></span>
                            </div>
                          </div>
                          
                        </div>
                        
                        <!--start-->
                        <div class="row">
                           <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                            <label for="working_day_type">Working Day Type <span class="required text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="working_day_type" value="full" checked>
                                                <label class="form-check-label">Full Day</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input class="form-check-input" type="radio" name="working_day_type" value="half">
                                                <label class="form-check-label">Half Day</label>
                                            </div>
                                            <span class="text-danger error-text working_day_type_error"></span>
                                </div>
                            </div>
                            
                           
                                    
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="working_start_time">Working Start Time <span class="required text-danger">*</span></label>
                                            <div class="input-group date" id="timepicker2" data-target-input="nearest">
                                                <input type="text" name="working_start_time" id="working_start_time" value="{{$user->working_start_time?$user->working_start_time:''}}" placeholder="hh:mm AM"  class="form-control datetimepicker-input" data-target="#timepicker2"/>
                                                <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                             <span class="text-danger error-text working_start_time_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="working_end_time">Working End Time <span class="required text-danger">*</span></label>
                                            <div class="input-group date" id="timepicker3" data-target-input="nearest">
                                                <input type="text" name="working_end_time" id="working_end_time" value="{{$user->working_end_time?$user->working_end_time:''}}" placeholder="hh:mm PM"  class="form-control datetimepicker-input" data-target="#timepicker3"/>
                                                <div class="input-group-append" data-target="#timepicker3" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                            <span class="text-danger error-text working_end_time_error"></span>
                                        </div>
                                    </div>
                                    
                                    
                      </div>
                      
                      
                      <div class="row">
                          
                           <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="marital_status">Check Staff Type <span class="required text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="check_ns_rs" value="1" {{$user->check_ns_rs==1?"checked":""}}>
                                                <label class="form-check-label">NS</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input class="form-check-input" type="radio" name="check_ns_rs" value="0" {{$user->check_ns_rs==0?"checked":""}}>
                                                <label class="form-check-label">RS</label>
                                            </div>
                                            <span class="text-danger error-text check_ns_rs_error"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="working_day_per_week">Working Day per Week <span class="required text-danger">*</span></label>
                                            <input type="text" class="form-control" id="working_day_per_week" name="working_day_per_week" value="{{$user->working_day_per_week}}" onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" autocomplete="off" >
                                            <!--<small class="text-danger working-day"></small>-->
                                            <span class="text-danger error-text working_day_per_week_error"></span>
                                        </div>
                                    </div>
                                    
                           @if($user->employee_type_id == 4 )
                          
                          <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="other_phone">OT Rate</label>
                            <input type="text" class="form-control" id="ot_rate" name="ot_rate" onkeypress="return setTwoNumberDecimal(event,this)" value="{{ old('ot_rate',$user->ot_rate)}}">
                          </div>
                        </div>
                        
                         @endif
                          
                      </div>
                        
                        <!--end-->

                     
                        <div class="row">
                            
                            
                            <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label  for="bank_account_usd">Position <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" id="position" name="position" value="{{ old('position',$user->position )}}">
                               <span class="text-danger error-text position_error"></span>
                            </div>
                          </div>
                          
                            
                          <!--   <div class="col-md-4 col-sm-4">-->
                          <!--  <div class="form-group">-->
                          <!--    <label for="position">Position <span class="required text-danger">*</span></label>-->
                          <!--    <select class="form-control select2bs4" name="position_id" id="position_id" style="width: 100%;">-->
                          <!--      <option value="">- Select -</option>-->
                          <!--       @foreach($roles as $role)-->
                          <!--      <option  value="{{ $role->id }}" {{old('position_id',$user->position_id)== $role->id ? 'selected':''}}>{{ $role->name}}</option>-->
                          <!--      @endforeach                          -->
                          <!--    </select>-->
                          <!--    <span class="text-danger error-text position_id_error"></span>-->
                          <!--  </div>-->
                          <!--</div>-->
                          
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="gender">Gender <span class="required text-danger">*</span></label>
                              <div class="form-check">                          
                                <input class="form-check-input" type="radio" name="gender" value="male" {{old('gender',$user->gender) == 'male' ? 'checked':''}}>
                                <label class="form-check-label">Male</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="radio" name="gender" value="female" {{old('gender',$user->gender) == 'female' ? 'checked':''}}>
                                <label class="form-check-label" >Female</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="radio" name="gender" value="other" {{old('gender',$user->gender) == 'other' ? 'checked':''}}>
                                <label class="form-check-label" >Other</label>
                              </div>
                              <span class="text-danger error-text gender_error"></span>
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="marital_status">Marital Status <span class="required text-danger">*</span></label>
                              <div class="form-check">                          
                                <input class="form-check-input" type="radio" value="single" name="marital_status" {{old('marital_status',$user->marital_status) == 'male' ? 'checked':''}}>
                                <label class="form-check-label">Single</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="radio" value="married" name="marital_status" {{old('marital_status',$user->marital_status) == 'married' ? 'checked':''}}>
                                <label class="form-check-label">Married</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="radio" value="divorced" name="marital_status" {{old('marital_status',$user->marital_status) == 'divorced' ? 'checked':''}}>
                                <label class="form-check-label">Divorced</label>
                              </div>
                              <span class="text-danger error-text marital_status_error"></span>
                            </div>
                          </div>

                        </div>
                        <!--start-->
                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="bank_id_usd">Graduation Name Of University</label>
                               <input type="text" class="form-control" id="graduation_name_of_university" name="graduation_name_of_university" value="{{ old('graduation_name_of_university',$user->graduation_name_of_university )}}">
                             </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label  for="bank_account_usd">Major</label>
                              <input type="text" class="form-control" id="major" name="major" value="{{ old('major',$user->major )}}">
                            </div>
                          </div>
                        </div>
                        
                        <!--end-->

                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="bank_id_usd">Bank Name (USD) <span class="required text-danger">*</span></label>
                              <select class="form-control select2bs4" name="bank_name_usd" id="bank_name_usd"
                                style="width: 100%;">
                                <option value="">- Select -</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->id }}"  {{old('bank_name_usd',$user->bank_name_usd) == $bank->id ? 'selected':''}}>{{ $bank->name }}</option>
                                @endforeach
                              </select>
                              <span class="text-danger error-text bank_name_usd_error"></span>
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label  for="bank_account_usd">Bank Account (USD) <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" id="bank_account_usd" name="bank_account_usd" value="{{ old('bank_account_usd',$user->bank_account_usd )}}">
                              <span class="text-danger error-text bank_account_usd_error"></span>
                            </div>
                          </div>
                        </div>

                        <div class="row">

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="bank_name_mmk">Bank Name (MMK) <span class="required text-danger">*</span></label>
                              <select class="form-control select2bs4" name="bank_name_mmk" id="bank_name_mmk"
                                style="width: 100%;">
                                <option value="">- Select -</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->id }}" {{old('bank_name_mmk',$user->bank_name_mmk) == $bank->id ? 'selected':''}}>{{ $bank->name }}</option>
                                @endforeach
                              </select>
                              <span class="text-danger error-text bank_name_mmk_error"></span>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="bank_account_mmk">Bank Account (MMK) <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" id="bank_account_mmk" name="bank_account_mmk" value="{{ old('bank_account_mmk',$user->bank_account_mmk)}}">
                            <span class="text-danger error-text bank_account_mmk_error"></span>
                            </div>
                          </div>

                       
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                              <div class="form-group">
                                <label for="bank_id_mmk_two">Bank Name (MMK) </label>
                                <select class="form-control select2bs4" name="second_bank_name_mmk" id="second_bank_name_mmk" style="width: 100%;">
                                  <option value="">- Select -</option>
                                   @foreach($banks as $bank)
                                <option value="{{ $bank->id }}" {{old('second_bank_name_mmk',$user->second_bank_name_mmk) == $bank->id ? 'selected':''}}>{{ $bank->name }}</option>
                                @endforeach                                  
                                </select>
                                <span class="text-danger error-text second_bank_name_mmk_error"></span>
                              </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-4">
                              <div class="form-group">
                                <label for="second_bank_account_mmk">Bank Account (MMK)</label>
                                <input type="text" class="form-control" id="second_bank_account_mmk" name="second_bank_account_mmk" value="{{ old('second_bank_account_mmk',$user->second_bank_account_mmk)}}">
                                 <span class="text-danger error-text second_bank_account_mmk_error"></span>
                              </div>
                            </div>

                        </div>
                        <!-- class start -->
                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="ssb_no">SSC No </label>
                              <input type="text" class="form-control" id="ssc_no" name="ssc_no" value="{{ old('ssc_no',$user->ssc_no)}}">
                              <span class="text-danger error-text ssc_no_error"></span>
                            </div>
                          </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="blood_type">Blood Type <span class="required text-danger">*</span></label>
                            <div class="form-check">                        
                              <input class="form-check-input" type="radio" value="a" name="blood_type" {{ old('blood_type',$user->blood_type) == 'a' ? 'checked':''}}>
                              <label class="form-check-label">A</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" value="b" name="blood_type"  {{ old('blood_type',$user->blood_type) == 'b' ? 'checked':''}}>
                              <label class="form-check-label">B</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" value="ab" name="blood_type" {{ old('blood_type',$user->blood_type) == 'ab' ? 'checked':''}}>
                              <label class="form-check-label">AB</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" value="o" name="blood_type" {{ old('blood_type',$user->blood_type) == 'o' ? 'checked':''}}>
                              <label class="form-check-label">O</label>
                            </div>
                            <span class="text-danger error-text blood_type_error"></span>
                          </div>
                        </div>

                        

                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="final_education">Final Education <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" id="final_education" name="final_education" value="{{ old('final_education',$user->final_education)}}">
                             <span class="text-danger error-text final_education_error"></span>
                          </div>
                        </div>
                        
                        </div>
                        <!-- class end -->

                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="passport_number">Passport No <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number',$user->passport_number)}}">
                              <span class="text-danger error-text passport_number_error"></span>
                            </div>
                          </div>

                           <?php
                           if(!empty($user->date_of_issue)){
                               $date_of_issue_data = str_replace('-', '/', $user->date_of_issue);
                               $date_of_issue = date("d/m/Y", strtotime($date_of_issue_data));
                           }else{
                               $date_of_issue='';
                           }
                           ?>


                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="date_of_issue">Date of Issue <span class="required text-danger">*</span></label>
                              <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                <input type="text" name="date_of_issue" id="date_of_issue" value="{{ old('date_of_issue',$date_of_issue) }}"
                                  class="form-control datetimepicker3-input" data-target="#datetimepicker3" />
                                <div class="input-group-append" data-target="#datetimepicker3"
                                  data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                              </div>
                              <span class="text-danger error-text date_of_issue_error"></span>
                            </div>
                          </div>

                          <?php
                           if(!empty($user->date_of_expire)){
                               $date_of_expire_data = str_replace('-', '/', $user->date_of_expire);
                               $date_of_expire = date("d/m/Y", strtotime($date_of_expire_data));
                           }else{
                               $date_of_expire='';
                           }
                           ?>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="date_of_expire">Date of Expiry<span class="required text-danger">*</span></label>
                              <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                <input type="text" name="date_of_expire" id="date_of_expire" value="{{ old('date_of_expire',$date_of_expire) }}" class="form-control datetimepicker4-input" data-target="#datetimepicker4" />
                                  <div class="input-group-append" data-target="#datetimepicker4"
                                  data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>                                
                              </div>
                              <span class="text-danger error-text date_of_expire_error"></span>
                            </div>
                          </div>                          

                        </div>

                        <div class="row">

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="residant_place">Residant Place <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" id="residant_place" name="residant_place" value="{{ old('residant_place',$user->residant_place)}}">
                              <span class="text-danger error-text residant_place_error"></span>
                            </div>
                          </div>
                          <?php
                           if(!empty($user->dob)){
                            $bday = new DateTime($user->dob); // Your date of birth
                            $today = new Datetime(date('Y-m-d'));
                             $diff = $today->diff($bday);
                             $age = $diff->y;
                           }else{
                              $age = 0;
                           }
                          ?>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="age">Age </label>
                              <input type="text" class="form-control" id="age" name="age" value="{{ old('age',$age)}}" disabled="disabled">
                              <span class="text-danger error-text age_error"></span>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="status">Active </label>
                              <div class="form-check">
                          
                                <input class="form-check-input" type="checkbox" name="active" id="active" {{old('active',$user->active) == 1 ? 'checked':''}}>
                              </div>
                            </div>
                          </div>

                        </div>

                        <!-- start date -->
                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="mjsrv">MJSRV </label>
                              <input type="text" class="form-control" id="mjsrv" name="mjsrv" value="{{ old('mjsrv',$user->mjsrv)}}">
                            </div>
                          </div>
                          
                          <?php
                           if(!empty($user->mjsrv_expire_date)){
                               $mjsrv_expire_date_data = str_replace('-"', '/', $user->mjsrv_expire_date);
                               $mjsrv_expire_date = date("d/m/Y", strtotime($mjsrv_expire_date_data));
                           }else{
                               $mjsrv_expire_date='';
                           }
                           ?>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="mjsrv_expire_date">MJSRV Expired Date</label>
                              <div class="input-group date" id="datetimepickerSearch" data-target-input="nearest">
                                <input type="text" name="mjsrv_expire_date" id="mjsrv_expire_date" value="{{ old('mjsrv_expire_date',$mjsrv_expire_date) }}"
                                  class="form-control datetimepickerSearch-input" data-target="#datetimepickerSearch" />
                                <div class="input-group-append" data-target="#datetimepickerSearch"
                                  data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                        <!-- end date -->

                        <!-- start date -->
                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="stay_permit">Stay Permit </label>
                              <input type="text" class="form-control" id="stay_permit" name="stay_permit" value="{{ old('stay_permit',$user->stay_permit)}}">
                            </div>
                          </div>

                          <?php
                           if(!empty($user->stay_permit_expire_date)){
                               $stay_permit_expire_date_data = str_replace('-"', '/', $user->stay_permit_expire_date);
                               $stay_permit_expire_date = date("d/m/Y", strtotime($stay_permit_expire_date_data));
                           }else{
                               $stay_permit_expire_date='';
                           }
                           ?>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="stay_permit_expire_date">Stay Permit Expired Date</label>
                              <div class="input-group date" id="datetimepickerSearch2" data-target-input="nearest">
                                <input type="text" name="stay_permit_expire_date" id="stay_permit_expire_date" value="{{ old('stay_permit_expire_date',$stay_permit_expire_date) }}"
                                  class="form-control datetimepickerSearch2-input" data-target="#datetimepickerSearch2" />
                                <div class="input-group-append" data-target="#datetimepickerSearch2"
                                  data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>                                
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- end date -->


                        <div class="row">

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="form_c">Form C </label>
                              <input type="text" class="form-control"  id="form_c" name="form_c" value="{{ old('form_c',$user->form_c)}}">
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="frc_no">FRC No </label>
                              <input type="text" class="form-control" id="frc_no" name="frc_no" value="{{ old('frc_no',$user->frc_no)}}">
                            </div>
                          </div>
                           
                          <?php
                           if(!empty($user->aboard_date)){
                               $aboard_date_data = str_replace('-', '/', $user->aboard_date);
                               $aboard_date = date("d/m/Y", strtotime($aboard_date_data));
                           }else{
                               $aboard_date='';
                           }
                           ?>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="aboard_date">First Arrival Date </label>
                              <div class="input-group date" id="datetimepickerExcel" data-target-input="nearest">
                                <input type="text" name="aboard_date" id="aboard_date" value="{{ old('aboard_date',$aboard_date) }}" 
                                  class="form-control datetimepickerExcel-input" data-target="#datetimepickerExcel" />
                                <div class="input-group-append" data-target="#datetimepickerExcel"
                                  data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>

                     

                        <div class="row">
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="japan_address">Address In Japan </label>
                              <input type="text" class="form-control" id="japan_address" name="japan_address"  value="{{ old('japan_address',$user->japan_address)}}">
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="japan_phone">Phone In Japan </label>
                              <input type="text" class="form-control" id="japan_phone" name="japan_phone"  value="{{ old('japan_phone',$user->japan_phone)}}">
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="japan_hot_line">Hot Line In Japan </label>
                              <input type="text" class="form-control" id="japan_hot_line" name="japan_hot_line" value="{{ old('japan_hot_line',$user->japan_hot_line)}}">
                               <span class="text-danger error-text japan_hot_line_error"></span>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-8 col-sm-8">
                            <div class="form-group">
                              <label for="myanmar_address">Address in Myanmar </label>
                              <input type="text" class="form-control" id="myanmar_address" name="myanmar_address" value="{{ old('myanmar_address',$user->myanmar_address) }}">
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                              <label for="phone">Phone In Myanmar </label>
                              <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone',$user->phone) }}">
                            </div>
                          </div>
                        </div>

                        <div class="row">

                          <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                              <label for="other_changing_condition">Other Changing Condition </label>
                              <textarea class="form-control" id="other_changing_condition"
                                name="other_changing_condition" rows="2">{{ old('other_changing_condition',$user->other_changing_condition) }}</textarea>
                            </div>
                          </div>

                        </div>
                        
                        
                        
                         <div class="row">
                         <div class="col-md-12 col-sm-12">
                           <div class="form-group">
                                <label for="branch">Branch <span class="required text-danger">*</span></label>
                                <select class="form-control select2bs4" name="branch_id" id="branch_id" style="width: 100%;" >
                                  <option value="">- Select -</option>
                                 @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{old('branch',$user->branch_id) == $branch->id ? 'selected':''}}>{{ $branch->name }}</option>
                                @endforeach                                 
                                </select>
                                <span class="text-danger error-text branch_id_error"></span>
                              </div>
                        </div> 
                          </div>
                        <div class="row">
                                 <div class="col-md-12 col-sm-12">
                                   <label for="branch">Departments <span class="required text-danger">*</span></label>
                               </div>
                               <?php 
                                  $depart = explode(",",$user->department_id);
                               ?>
                               @foreach($departments as $department) 
                               <div class="col-md-4 col-sm-4">
                                    <div class="form-group">                                 
                                    <input type="checkbox" id="department_id" name="department_id[]" <?= (in_array($department->id, $depart)?'checked="checked"':NULL) ?>  value="{{ old('department_id',$department->id)}}">
                                    <label for="department_id">{{  $department->name }}  ({{ $department->short_name }})</label>
                                  </div>
                                </div>                              
                                  @endforeach
                                  <span class="text-danger error-text department_id_error"></span>
                        </div>

                        <!-- end for form -->
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group text-center">
                              <input type="submit" class="btn btn-primary rs-edit" value="save">
                            </div>
                          </div>
                        </div>
                      </form>
                      <!-- end for form -->

                    </div>
                    <!-- /.card-body -->
                  </div>

                  <div class="card card-default collapsed-card contact-info">
                    <div class="card-header">
                      <h3 class="card-title">Contact Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form method="POST"  id="contact-update">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="row">
                          <div class="col-md-6 col-sm-6">
                            <label>First Person : </label>
                            <div class="form-group">
                              <label>Name <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" id="first_person_name" name="first_person_name" value="{{ old('first_person_name',$user->first_person_name)}}">
                              <span class="text-danger error-text first_person_name_error"></span>
                            </div>
                            <div class="form-group">
                              <label>Email </label>
                              <input type="text" class="form-control" id="first_person_email" name="first_person_email" value="{{ old('first_person_email',$user->first_person_email)}}">
                              <span class="text-danger error-text first_person_email_error"></span>
                            </div>
                            <div class="form-group">
                              <label>Phone <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" id="first_person_phone" name="first_person_phone" value="{{ old('first_person_phone',$user->first_person_phone)}}">
                               <span class="text-danger error-text first_person_phone_error"></span>
                            </div>
                            <div class="form-group">
                              <label>Hotline </label>
                              <input type="text" class="form-control" id="first_person_hotline" name="first_person_hotline" value="{{ old('first_person_hotline',$user->first_person_hotline)}}">
                              <span class="text-danger error-text first_person_hotline_error"></span>
                            </div>
                            
                            <div class="form-group">
                               <label>Relationship <span class="required text-danger">*</span></label>
                              <input type="text" class="form-control" id="first_person_relationship" name="first_person_relationship" value="{{ old('first_person_relationship',$user->first_person_relationship)}}">
                             <span class="text-danger error-text first_person_relationship_error"></span>
                            </div>
                            
                            <!--<div class="form-group">-->
                            <!--  <label>Relationship <span class="required text-danger">*</span></label>-->
                            <!--  <select class="form-control" name="first_person_relationship" id="first_person_relationship"-->
                            <!--    style="width: 100%;">-->
                            <!--    <option value="">- Select -</option>-->
                            <!--    <option value="father" {{old('first_person_relationship',$user->first_person_relationship) == 'father' ? 'selected':''}}>Father</option>-->
                            <!--    <option value="mother" {{old('first_person_relationship',$user->first_person_relationship) == 'mother' ? 'selected':''}}>Mother</option>-->
                            <!--    <option value="spouse" {{old('first_person_relationship',$user->first_person_relationship) == 'spouse' ? 'selected':''}}>Spouse</option>-->
                            <!--  </select>-->
                            <!--  <span class="text-danger error-text first_person_relationship_error"></span>-->
                            <!--</div>-->
                            
                            
                            <div class="form-group">
                              <label>Address <span class="required text-danger">*</span></label>
                              <textarea class="form-control" id="first_person_address" name=" first_person_address" rows="2">
                                {{ $user->first_person_hotline  }}
                              </textarea>
                              <span class="text-danger error-text first_person_address_error"></span>
                            </div>
                          </div>

                          <div class="col-md-6 col-sm-6">
                            <label>Second Person : </label>
                            <div class="form-group">
                              <label>Name </label>
                              <input type="text" class="form-control" id="second_person_name" name="second_person_name" value="{{ old('second_person_name',$user->second_person_name)}}">
                            </div>
                            <div class="form-group">
                              <label>Email </label>
                              <input type="text" class="form-control" id="second_person_email" name="second_person_email" value="{{ old('second_person_email',$user->second_person_email)}}">
                           </div>
                            <div class="form-group">
                              <label>Phone </label>
                              <input type="text" class="form-control" id="second_person_phone" name="second_person_phone" value="{{ old('second_person_phone',$user->second_person_phone)}}">
                            </div>
                            <div class="form-group">
                              <label>Hotline </label>
                              <input type="text" class="form-control" id="second_person_hotline" name="second_person_hotline"  value="{{ old('second_person_hotline',$user->second_person_hotline)}}">
                            </div>
                            
                            <div class="form-group">
                              <label>Relationship </label>
                              <input type="text" class="form-control" id="second_person_relationship" name="second_person_relationship" value="{{ old('second_person_relationship',$user->second_person_relationship)}}">
                            </div>
                            
                            <!--<div class="form-group">-->
                            <!--  <label>Relationship </label>-->
                            <!--  <select class="form-control select2bs4" name="second_person_relationship" id="second_person_relationship"-->
                            <!--    style="width: 100%;">-->
                            <!--    <option value="">- Select -</option>-->
                            <!--    <option value="father" {{old('second_person_relationship',$user->second_person_relationship) == 'father' ? 'selected':''}}>Father</option>-->
                            <!--    <option value="mother" {{old('second_person_relationship',$user->second_person_relationship) == 'mother' ? 'selected':''}}>Mother</option>-->
                            <!--    <option value="spouse" {{old('second_person_relationship',$user->second_person_relationship) == 'spouse' ? 'selected':''}}>Spouse</option>-->
                            <!--  </select>-->
                            <!--</div>-->
                            
                            
                            <div class="form-group">
                              <label>Address </label>
                              <textarea class="form-control" id="second_person_address" name="second_person_address" rows="2">
                                {{ $user->second_person_address  }}
                              </textarea>
                            </div>
                          </div>

                        </div>
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group text-center">
                              <input type="submit" class="btn btn-primary" value="save">
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                    <!-- /.card-body -->
                  </div>

                  <div class="card card-default collapsed-card family-info">
                    <div class="card-header">
                      <h3 class="card-title">Family Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.rs-family-update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">

                      
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <a class="btn btn-success breadcrumb-btn float-sm-right family-addmore"><i
                                class="fas fa-plus"></i> Add</a>
                          </div>
                           
                           
                              <div class="col-md-12 col-sm-12">
                            <table class="table" id="family_records">
                              <tbody>
                                 @if(count($families) > 0)                                
                                 @foreach($families as $family)
                                <tr id="familyNumone0">
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;">
                                    <small id="snum0">1</small></td>
                                  <td style="border-top: none;">
                                    <label>Relationship <span class="required text-danger">*</span> </label>
                                    <input type="text" id="family_relationship0" name="family_relationship[]" class="form-control"
                                      value="{{ $family->relationship }}" required autocomplete="off">

                                  </td>
                                  <td style="border-top: none;">
                                    <label>Name <span class="required text-danger">*</span> </label>
                                    <input type="text" id="family_name0" name="family_name[]" class="form-control"
                                      value="{{ $family->name }}" required autocomplete="off">
                                  </td>
                                  
                                    <td style="border-top: none;">
                                    <label>Allowance </label>
                                       <select class="form-control select2bs4" name="allowance[]" id="allowance0"
                                         style="width: 100%;">
                                         <option value="">- Select -</option>
                                         <option value="parent allowance" {{old('allowance',$family->allowance) == 'parent allowance' ? 'selected':''}}>Parent Allowance</option>
                                         <option value="spouse allowance" {{old('allowance',$family->allowance) == 'spouse allowance' ? 'selected':''}}>Spouse Allowance</option>
                                         <option value="children allowance" {{old('allowance',$family->allowance) == 'children allowance' ? 'selected':''}}>Children Allowance</option>
                                       </select>
                                  </td>
                                  
                                   <td style="border-top: none;">
                                    <label>Allowance Fee </label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="number" name="allowance_fee[]" onkeypress="return isNumberKey(event)" id="allowance_fee0" class="form-control"
                                        value="{{ $family->allowance_fee }}" />
                                      </div>
                                  </td>
                                </tr>
                                <tr id="familyNumtwo0">
                                    
                                     <td style="border-top: none;">
                                    <label>DOB </label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="date" name="family_dob[]" id="family_dob0" class="form-control"
                                        value="{{ $family->family_dob }}" />
                                      </div>
                                  </td>
                                  <!-- start -->
                                 
                                  <!-- end -->
                                  <td style="border-top: none;">
                                    <label>Work / School </label>
                                    <input type="text" id="family_work0" name="family_work[]" class="form-control"
                                       value="{{ $family->work }}" autocomplete="off" />
                                  </td>
                                  <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action</label>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_Family(0)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td>
                                    
                                </tr>
                                @endforeach
                                @else
                                 <tr id="familyNumone0">
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;">
                                    <small id="snum0">1</small></td>
                                  <td style="border-top: none;">
                                    <label>Relationship <span class="required text-danger">*</span></label>
                                    
                                      <input type="text" id="family_relationship0" name="family_relationship[]" class="form-control" required autocomplete="off">
                                      
                                    </td>
                                  <td style="border-top: none;">
                                    <label>Name <span class="required text-danger">*</span></label>
                                    <input type="text" id="family_name0" name="family_name[]" class="form-control"
                                      autocomplete="off" required>
                                  </td>
                                  
                                    <td style="border-top: none;">
                                    <label>Allowance </label>
                                       <select class="form-control select2bs4" name="allowance[]" id="allowance0"
                                         style="width: 100%;">
                                         <option value="">- Select -</option>
                                         <option value="parent allowance">Parent Allowance</option>
                                         <option value="spouse allowance" >Spouse Allowance</option>
                                         <option value="children allowance" >Children Allowance</option>
                                       </select>
                                  </td>
                                  
                                   <td style="border-top: none;">
                                    <label>Allowance Fee </label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="number" name="allowance_fee[]" onkeypress="return isNumberKey(event)" id="allowance_fee0" class="form-control"
                                        value="" />
                                      </div>
                                  </td>
                                  
                                
                                </tr>
                                <tr id="familyNumtwo0">
                                    
                                      <td style="border-top: none;">
                                    <label>DOB </label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="date" name="family_dob[]" id="family_dob0" class="form-control" />
                                      </div>
                                  </td>
                                  <!-- start -->
                                 
                                  <!-- end -->
                                  <td style="border-top: none;">
                                    <label>Work / School  </label>
                                    <input type="text" id="family_work0" name="family_work[]" class="form-control"
                                      autocomplete="off">
                                  </td>
                                    
                                </tr>
                                 @endif
                              </tbody>
                            </table>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group text-center">
                              <input type="submit" class="btn btn-primary" value="save">
                            </div>
                          </div>
                        </div>
                      </form>

                    </div>
                    <!-- /.card-body -->
                  </div>
                  
                </div>
                <!-- /.card-body -->

                <!-- start new -->
                    <div class="card card-default collapsed-card leave-info">
                    <div class="card-header">
                      <h3 class="card-title">Leave Information</h3>
                      
                       

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        
                       
                      <form action="{{ route('employee.rs-leave-update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                           
                      
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <!--<a class="btn btn-success breadcrumb-btn float-sm-right"><i-->
                            <!--    class="fas fa-plus"></i> Add</a>-->
                               
                          </div>
                           
                           
                                 <div class="col-md-12 col-sm-12">
                            <table class="table">
                              <tbody>
                                  
                                
                                
                                 <tr>
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;">
                                    <small id="snum0">1</small></td>
                                
                                  <td style="border-top: none;">
                                    <label>Year  <span class="required text-danger">*</span> </label>
                                    <input type="number"  name="year" class="form-control" autocomplete="off" required>
                                  </td>
                                  
                                  <td style="border-top: none;">
                                    <label>Earned Leaves  <span class="required text-danger">*</span></label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="text" name="earned_leaves" onkeypress="return isNumberKey(event)"  class="form-control" required/>
                                      </div>
                                  </td>
                                  <td style="border-top: none;">
                                    <label>Refresh Leaves   <span class="required text-danger">*</span></label>
                                    <input type="text"  name="refresh_leaves" onkeypress="return isNumberKey(event)" class="form-control"  autocomplete="off" required>
                                  </td>
                                </tr>

                                  @if(count($rs_leave_datas) > 0)                                
                             @foreach($rs_leave_datas as $rs_leave)

                             <?php   
                             if ($rs_leave->year != date('Y') - 1 ) {
                               $class = 'text-success';
                             }else{
                               $class = 'text-danger';
                             } 
                             ?>

                                <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                
                                <td style="border-top: none;">
                                  <label class="{{ $class }}">{{ $rs_leave->year }}</label>
                                </td>
                                <td style="border-top: none;">
                                  <label class="{{ $class }}">{{ $rs_leave->earned_leaves }}</label>
                                </td>
                                <td style="border-top: none;">
                                  <label class="{{ $class }}">{{ $rs_leave->refresh_leaves }}</label>
                                </td>

                              </tr>
                             @endforeach
                             @endif
                                
                              </tbody>
                            </table>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group text-center">
                              <input type="submit" class="btn btn-primary" value="save">
                            </div>
                          </div>
                        </div>
                      </form>

                    </div>
                    <!-- /.card-body -->
                  </div>

                   <!-- start leave taken -->
                  <div class="card card-default collapsed-card family-info">
                    <div class="card-header">
                      <h3 class="card-title">Refresh Leaves Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                   

                      
                        <div class="row">
                          <!-- <div class="col-md-12 col-sm-12">
                            <a class="btn btn-success breadcrumb-btn float-sm-right family-addmore"><i
                                class="fas fa-plus"></i> Add</a>
                          </div> -->
                           
                           
                              <div class="col-md-12 col-sm-12">
                            <table class="table" id="family_records">
                              <tbody>
                               
                            @if(!empty($rs_refresh_leaves))
                              @foreach($rs_refresh_leaves as $rs_refresh_leave)
                               <tr id="familyNumone0">
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;">
                                    <small id="snum0">1</small></td>
                                  
                                  <td style="border-top: none;">
                                    <label>Start Date <span class="required text-danger">*</span></label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $rs_refresh_leave->start_date }}" class="form-control"
                                      autocomplete="off" readonly required>
                                  </td>


                                  <td style="border-top: none;">
                                    <label>End Date <span class="required text-danger">*</span></label>
                                    <input type="date" id="end_date" name="end_date"  value="{{ $rs_refresh_leave->end_date }}" class="form-control"
                                      autocomplete="off" readonly required>
                                  </td>
                                  
                                   <td style="border-top: none;">
                                    <label>Refresh Leave <span class="required text-danger">*</span></label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="text" name="refresh_leaves"  value="{{ $rs_refresh_leave->refresh_leaves }}" onkeypress="return isNumberKey(event)" id="refresh_leaves" class="form-control"
                                        readonly  value="" />
                                      </div>
                                  </td>

                                   <td style="border-top: none;">
                                    <label>Earned Leave</label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="text" name="earned_leaves" value="{{ $rs_refresh_leave->earned_leaves }}" onkeypress="return isNumberKey(event)" id="earned_leaves" class="form-control"
                                       readonly  value="" />
                                      </div>
                                   </td>
                                  
                                
                                </tr>
                                <tr id="familyNumtwo0"> 
                                  <td style="border-top: none;">
                                    <label> Others (Sat, Sun & Holidays )</label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="text" name="other" value="{{ $rs_refresh_leave->other }}" readonly onkeypress="return isNumberKey(event)" id="other" class="form-control" />
                                      </div>
                                  </td>
                                  <!-- start -->                                 
                                  <!-- end -->
                                  <td style="border-top: none;">
                                    <label>Maximum Airfare  </label>
                                    <input type="text" id="airfare" name="airfare"  value="{{ $rs_refresh_leave->airfare }}" readonly class="form-control"
                                      autocomplete="off">
                                  </td>
                                  <td style="border-top: none;padding-top: 38px;">
                                     <label></label>
                                    <a  href="{{ url('/employee/delete-refresh-leaves/'.$rs_refresh_leave->id) }}" class="btn btn-danger" >Delete</a>
                                  </td>
                                </tr>
                              @endforeach
                              @endif 

                             <form action="{{ route('employee.rs-refresh-update') }}" method="post">
                             @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                 <tr id="familyNumone0">
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;">
                                    <small id="snum0">1</small></td>
                                  
                                  <td style="border-top: none;">
                                    <label>Start Date <span class="required text-danger">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control"
                                      autocomplete="off" required>
                                  </td>


                                  <td style="border-top: none;">
                                    <label>End Date <span class="required text-danger">*</span></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control"
                                      autocomplete="off" required>
                                  </td>
                                  
                                   <td style="border-top: none;">
                                    <label>Refresh Leave <span class="required text-danger">*</span></label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="text" name="refresh_leaves" onkeypress="return isNumberKey(event)" id="refresh_leaves" class="form-control"
                                        value="" />
                                      </div>
                                  </td>

                                   <td style="border-top: none;">
                                    <label>Earned Leave</label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="text" name="earned_leaves" onkeypress="return isNumberKey(event)" id="earned_leaves" class="form-control"
                                        value="" />
                                      </div>
                                   </td>
                                  
                                
                                </tr>
                                <tr id="familyNumtwo0"> 
                                  <td style="border-top: none;">
                                    <label> Others (Sat, Sun & Holidays )</label>
                                      <div class="input-group date"  data-target-input="nearest">
                                       <input type="text" name="other" onkeypress="return isNumberKey(event)" id="other" class="form-control" />
                                      </div>
                                  </td>
                                  <!-- start -->                                 
                                  <!-- end -->
                                  <td style="border-top: none;">
                                    <label>Maximum Airfare  </label>
                                    <input type="text" id="airfare" name="airfare" class="form-control"
                                      autocomplete="off">
                                  </td>
                                    
                                </tr>
                                
                              </tbody>
                            </table>
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group text-center">
                              <input type="submit" class="btn btn-primary" value="save">
                            </div>
                          </div>
                        </div>
                      </form>

                    </div>
                    <!-- /.card-body -->
                  </div>
                  
                </div>
                <!-- /.card-body -->

                <!-- end leave taken -->
                  
                </div>
                <!-- /.card-body -->
               
                <!-- end new -->
              </div>
              <!-- /.card -->

            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

      <!--    <script src="http://code.jquery.com/jquery-3.3.1.min.js"-->
      <!--integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="-->
      <!--crossorigin="anonymous">-->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

  $(function () {
            //Date picker
         
            $('#timepicker2').datetimepicker({
                format: 'HH:mm'
            });
            $('#timepicker3').datetimepicker({
                format: 'HH:mm'
            });

       });

//create script start
$(document).ready(function(){
 $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#photo').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => { 
            $('#preview-photo').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('#sign_photo').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => { 
            $('#preview-sign-photo').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });

    //basic info update
    $("#basic-update").submit(function(e){    
        e.preventDefault();
         let formData = new FormData(this);
        $.ajax({
           type:'POST',
           url:"{{ route('employee.rs-basic-update') }}",
           data:formData,
           contentType: false,
           processData: false,
           success:function(data){
                if($.isEmptyObject(data.error)){
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    //contact info update
     $("#contact-update").submit(function(e){    
        e.preventDefault();
         let formData = new FormData(this);
        $.ajax({
           type:'POST',
           url:"{{ route('employee.rs-contact-update') }}",
           data:formData,
           contentType: false,
           processData: false,
           success:function(data){
                if($.isEmptyObject(data.error)){
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });
    });
    function printErrorMsg (msg) {

           $.each( msg, function( key, value ) {
              $('.'+key+'_error').text(value);
          });
    }
    
});
 </script> 

   <script type="text/javascript">
// function isNumberKey(evt){
//     var charCode = (evt.which) ? evt.which : event.keyCode
//     if (charCode > 31 && (charCode < 48 || charCode > 57))
//         return false;
//     return true;
// }

function isNumberKey(event) {
    var key = window.event ? event.keyCode : event.which;
if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
    if($(this).val().indexOf('.') == -1)
        return true;
    else
        return false;
}
else if ( key < 48 || key > 57 ) {
    return false;
}
else return true;
};
</script>



</section>
@stop