@extends('layouts.master')
@section('title','NS Edit')
@section('content')
 <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item">Employee Management</li>
              <li class="breadcrumb-item active">NS Employee Management</li>
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
                <h3 class="card-title">Edit NS Employee</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="card card-default collapsed-card basic-info">
                    <div class="card-header">
                      <h3 class="card-title">Basic Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
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
                            <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ old('employee_id',$user->employee_id)}}">
                             <span class="text-danger error-text employee_id_error"></span>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="employee_name">Name <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{ old('employee_name',$user->employee_name)}}">
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
                            <label for="dob">DOB <span class="required text-danger">*</span></label>
                            <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                              <input type="text" name="dob" id="dob" value="{{ old('dob',$dob)}}"  class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                              <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
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
                            <label for="entrance_date">Entrance Date <span class="required text-danger">*</span></label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                              <input type="text" name="entranced_date"  value="{{ old('entrance_date',$entranced_date)}}" id="entranced_date"  class="form-control datetimepicker2-input" data-target="#datetimepicker2"/>
                              <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text bg-white"><i class="fa fa-calendar-alt"></i></div>
                              </div>
                            </div>
                            <span class="text-danger error-text entranced_date_error"></span>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="personal_email">Email (Personal) </label>
                            <input type="text" class="form-control" id="personal_email"  value="{{ old('personal_email',$user->personal_email)}}" name="personal_email">
                            <span class="text-danger error-text personal_email_error"></span>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="office_email">Email (Office) </label>
                            <input type="text" class="form-control" id="office_email" value="{{ old('office_email',$user->email)}}" name="office_email">
                            <span class="text-danger error-text office_email_error"></span>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="employee_type_id">Employee Type <span class="required text-danger">*</span></label>
                            <select class="form-control select2bs4" name="employee_type"  id="employee_type" style="width: 100%;">
                              <option value="">- Select -</option>
                               @foreach($employee_types as $employee_type)
                                <option  value="{{ $employee_type->id }}" {{old('employee_type',$user->employee_type_id)== $employee_type->id ? 'selected':''}}>{{ $employee_type->type}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text employee_type_error"></span>
                          </div>
                        </div>
                         <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="gender">Gender <span class="required text-danger">*</span></label>
                            <div class="form-check">

                              <input class="form-check-input" type="radio" name="gender" value="male" {{old('gender',$user->gender) == 'male' ? 'checked':''}}>
                              <label class="form-check-label">Male</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" name="gender"  value="female" {{old('gender',$user->gender) == 'female' ? 'checked':''}}>
                              <label class="form-check-label">Female</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" name="gender" value="other" {{old('gender',$user->gender) == 'other' ? 'checked':''}}>
                              <label class="form-check-label">Other</label>
                            </div>
                            <span class="text-danger error-text gender_error"></span>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="marital_status">Marital Status <span class="required text-danger">*</span></label>
                            <div class="form-check">

                              <input class="form-check-input" type="radio" name="marital_status"  value="single" {{old('marital_status',$user->marital_status) == 'single' ? 'checked':''}} >
                              <label class="form-check-label">Single</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" name="marital_status" value="married" {{old('marital_status',$user->marital_status) == 'married' ? 'checked':''}} >
                              <label class="form-check-label">Married</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="radio" value="divorced" name="marital_status" value="divorced" {{old('marital_status',$user->marital_status) == 'divorced' ? 'checked':''}}>
                                <label class="form-check-label">Divorced</label>
                            </div>
                             <span class="text-danger error-text marital_status_error"></span>
                          </div>
                        </div>
                        
                        <!--<div class="col-md-4 col-sm-4">-->
                        <!--  <div class="form-group">-->
                        <!--    <label for="branch_id">Branch <span class="required text-danger">*</span></label>-->
                        <!--    <select class="form-control select2bs4" name="branch_id" id="branch_id" style="width: 100%;" >-->
                        <!--      <option value="">- Select -</option>-->
                        <!--       @foreach($branches as $branch)-->
                        <!--        <option value="{{ $branch->id }}" {{old('branch',$user->branch_id) == $branch->id ? 'selected':''}}>{{ $branch->name }}</option>-->
                        <!--        @endforeach-->
                        <!--    </select>-->
                        <!--    <span class="text-danger error-text branch_id_error"></span>-->
                        <!--  </div>-->
                        <!--</div>-->
                        <!--<div class="col-md-4 col-sm-4">-->
                        <!--  <div class="form-group">-->
                        <!--    <label for="department_id">Department <span class="required text-danger">*</span></label>-->
                        <!--    <select class="form-control select2bs4" name="department_id" id="department_id" style="width: 100%;">-->
                        <!--      <option value="">- Select -</option>-->
                        <!--      @foreach($departments as $department)-->
                        <!--        <option value="{{ $department->id }}"  {{old('department_id',$user->department_id) == $department->id ? 'selected':''}}>{{ $department->name }}</option>-->
                        <!--        @endforeach-->
                        <!--    </select>-->
                        <!--    <span class="text-danger error-text department_id_error"></span>-->
                        <!--  </div>-->
                        <!--</div>-->
                      </div>

                      <!--<div class="row">-->
                      <!--  <div class="col-md-4 col-sm-4">-->
                      <!--    <div class="form-group">-->
                      <!--      <label for="position_id">Position <span class="required text-danger">*</span></label>-->
                      <!--      <select class="form-control select2bs4" name="position_id" id="position_id" style="width: 100%;">-->
                      <!--        <option value="">- Select -</option>-->
                      <!--         @foreach($roles as $role)-->
                      <!--          <option  value="{{ $role->id }}" {{old('position_id',$user->position_id)== $role->id ? 'selected':''}}>{{ $role->name}}</option>-->
                      <!--          @endforeach-->
                      <!--      </select>-->
                      <!--      <span class="text-danger error-text position_id_error"></span>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!--  <div class="col-md-4 col-sm-4">-->
                      <!--    <div class="form-group">-->
                      <!--      <label for="gender">Gender <span class="required text-danger">*</span></label>-->
                      <!--      <div class="form-check">-->

                      <!--        <input class="form-check-input" type="radio" name="gender" value="male" {{old('gender',$user->gender) == 'male' ? 'checked':''}}>-->
                      <!--        <label class="form-check-label">Male</label>-->
                      <!--        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                      <!--        <input class="form-check-input" type="radio" name="gender"  value="female" {{old('gender',$user->gender) == 'female' ? 'checked':''}}>-->
                      <!--        <label class="form-check-label">Female</label>-->
                      <!--        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                      <!--        <input class="form-check-input" type="radio" name="gender" value="other" {{old('gender',$user->gender) == 'other' ? 'checked':''}}>-->
                      <!--        <label class="form-check-label">Other</label>-->
                      <!--      </div>-->
                      <!--      <span class="text-danger error-text gender_error"></span>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!--  <div class="col-md-4 col-sm-4">-->
                      <!--    <div class="form-group">-->
                      <!--      <label for="marital_status">Marital Status <span class="required text-danger">*</span></label>-->
                      <!--      <div class="form-check">-->

                      <!--        <input class="form-check-input" type="radio" name="marital_status"  value="single" {{old('marital_status',$user->marital_status) == 'single' ? 'checked':''}} >-->
                      <!--        <label class="form-check-label">Single</label>-->
                      <!--        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                      <!--        <input class="form-check-input" type="radio" name="marital_status" value="single" {{old('marital_status',$user->marital_status) == 'married' ? 'checked':''}} >-->
                      <!--        <label class="form-check-label">Married</label>-->
                      <!--        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                      <!--          <input class="form-check-input" type="radio" value="divorced" name="marital_status" value="divorced" {{old('marital_status',$user->marital_status) == 'divorced' ? 'checked':''}}>-->
                      <!--          <label class="form-check-label">Divorced</label>-->
                      <!--      </div>-->
                      <!--       <span class="text-danger error-text marital_status_error"></span>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!--</div>-->

                      <div class="row">
                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="blood_type">Blood Type <span class="required text-danger">*</span></label>
                            <div class="form-check">

                              <input class="form-check-input" type="radio" name="blood_type" value="a" {{ old('blood_type',$user->blood_type) == 'a' ? 'checked':''}}>
                              <label class="form-check-label">A</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" name="blood_type" value="b" {{ old('blood_type',$user->blood_type) == 'b' ? 'checked':''}}>
                              <label class="form-check-label">B</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" name="blood_type" value="ab" {{ old('blood_type',$user->blood_type) == 'ab' ? 'checked':''}}>
                              <label class="form-check-label">AB</label>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input class="form-check-input" type="radio" name="blood_type" value="o" {{ old('blood_type',$user->blood_type) == 'o' ? 'checked':''}}>
                              <label class="form-check-label">O</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="ssc">SSC No</label>
                            <input type="text" class="form-control" id="ssc_no" name="ssc_no" value="{{ old('ssc_no',$user->ssc_no)}}">
                             <span class="text-danger error-text ssc_no_error"></span>
                          </div>
                        </div>
                        
                       
                      </div>

                      <div class="row">
                          
                           <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="bank_name_usd">Bank Name (USD) </label>
                            <select class="form-control select2bs4" name="bank_name_usd" id="bank_name_usd" style="width: 100%;">
                            <option value="">- Select -</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->id }}"  {{old('bank_name_usd',$user->bank_name_usd) == $bank->id ? 'selected':''}}>{{ $bank->name }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        
                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="bank_account_usd">Bank Account (USD) </label>
                            <input type="text" class="form-control" id="bank_account_usd" name="bank_account_usd" value="{{ old('bank_account_usd',$user->bank_account_usd )}}">
                          </div>
                        </div>

                        
                      </div>
                      
                        <div class="row">
                            
                            <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="bank_name_mmk">Bank Name (MMK) <span class="required text-danger">*</span></label>
                            <select class="form-control select2bs4" name="bank_name_mmk" id="bank_name_mmk" style="width: 100%;">
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
                            <label for="bank_account_mmk">Bank Accoount (MMK) <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" id="bank_account_mmk" name="bank_account_mmk" value="{{ old('bank_account_mmk',$user->bank_account_mmk)}}">
                            <span class="text-danger error-text bank_account_mmk_error"></span>
                          </div>
                        </div>
                            
                            
                        </div>

                      <div class="row">
                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="passport_number">Passport No </label>
                            <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number',$user->passport_number)}}">
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
                            <label for="date_of_issue">Date of Issue </label>
                            <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                              <input type="text" name="date_of_issue" id="date_of_issue"  value="{{ old('date_of_issue',$date_of_issue) }}" class="form-control datetimepicker-input" data-target="#datetimepicker3"/>
                              <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                              </div>
                            </div>
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
                            <label for="date_of_expiry">Date of Expiry </label>
                            <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                              <input type="text" name="date_of_expire" id="date_of_expire" value="{{ old('date_of_expire',$date_of_expire) }}" class="form-control datetimepicker-input" data-target="#datetimepicker4"/>
                              <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="grade">Grade </label>
                            <input type="number" class="form-control" id="grade" name="grade" value="{{ old('grade',$user->grade)}}" disabled>
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="nrc_no">NRC No <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" id="nrc_no" name="nrc_no" value="{{ old('nrc_no',$user->nrc_no)}}">
                             <span class="text-danger error-text nrc_no_error"></span>
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="phone">Phone </label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone',$user->phone)}}">
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="religion">Religion </label>
                            <input type="text" class="form-control" id="religion" name="religion" value="{{ old('religion',$user->religion)}}">
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="race">Race </label>
                            <input type="text" class="form-control" id="race" name="race" value="{{ old('race',$user->race)}}">
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label label="current_address">Current Address <span class="required text-danger">*</span></label>
                            <input type="text" class="form-control" id="current_address" name="current_address"  value="{{ old('current_address',$user->current_address)}}">
                            <span class="text-danger error-text current_address_error"></span>
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label label="new_address">New Address </label>
                            <input type="text" class="form-control" id="new_address" name="new_address" value="{{ old('new_address',$user->new_address)}}">
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label label="new_phone">New Phone </label>
                            <input type="text" class="form-control" id="new_phone" name="new_phone" value="{{ old('new_phone',$user->new_phone)}}">
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

                      <div class="row">
                        
                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="other_address">Other Address </label>
                            <input type="text" class="form-control" id="others_address" name="others_address" value="{{ old('others_address',$user->others_address)}}">
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="other_phone">Other Phone </label>
                            <input type="text" class="form-control" id="others_phone" name="others_phone" value="{{ old('others_phone',$user->others_phone)}}">
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="contract_no">Employment Contract No </label>
                            <input type="text" class="form-control" id="employment_contract_no" name="employment_contract_no" value="{{ old('employment_contract_no',$user->employment_contract_no)}}">
                          </div>
                        </div>

                      </div>
                      <div class="row">

                      <div class="col-md-4 col-sm-4">
                          <div class="form-group">
                            <label for="other_phone">Hourly Rate</label>
                            <input type="text" class="form-control" id="hourly_rate" name="hourly_rate" onkeypress="return setTwoNumberDecimal(event,this)" value="{{ old('hourly_rate',$user->hourly_rate)}}">
                          </div>
                        </div>                     
                        
                        <div class="col-md-4 col-sm-4">
                           @if(!empty($user->photo_name))
                            <img width="100px" id="preview-photo" src="{{ url('/employee/'.$user->photo_name )}}" alt="profile" title="profile">
                            @else
                            <img width="100px" id="preview-photo">
                            @endif
                          <div class="form-group">
                            <label for="photo">Face Photo</label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/png,image/jpeg">
                              <label class="custom-file-label" for="photo">Photo</label>
                            </div>
                          </div>
                        </div>

                        
                        <div class="col-md-4 col-sm-4">
                           @if(!empty($user->sign_photo_name))
                             <img id="preview-sign-photo" src="{{url('/employee/'.$user->sign_photo_name )}}" width="100px" alt="sign photo" title="sign photo">
                             @else
                              <img id="preview-sign-photo"  width="100px">
                             @endif

                          <div class="form-group">
                            <label>Sign Photo </label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="sign_photo" name="sign_photo" accept="image/png,image/jpeg">
                              <label class="custom-file-label" for="sign_photo">Sign Photo</label>
                            </div>
                          </div>
                        </div>
                      </div>
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
                                            <input type="text" class="form-control" id="working_day_per_week" name="working_day_per_week" value="{{$user->working_day_per_week}}" onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" autocomplete="off">
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
                     

                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="form-group">
                            <label for="other_changing_condition">Other Changing Condition </label>
                            <textarea class="form-control" id="other_changing_condition" name="other_changing_condition" rows="2">
                                {{ old('other_changing_condition',$user->other_changing_condition) }}
                            </textarea>
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
                                  $depart = explode(",", $user->department_id);
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
                               <input type="submit" class="btn btn-primary" value="save" >
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
                              <input type="text" class="form-control" id="first_person_name" name="first_person_name" value="{{ old('first_person_name',$user->first_person_name) }}">
                              <span class="text-danger error-text first_person_name_error"></span>
                            </div>
                            <div class="form-group">
                              <label>Email </label>
                              <input type="text" class="form-control" id="first_person_email" name="first_person_email" value="{{ old('first_person_email',$user->first_person_email)}}">
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
                            <!--  <select class="form-control select2bs4" name="first_person_relationship" id="first_person_relationship"-->
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
                                {{ $user->first_person_address  }}
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
                              <label>Relationship</label>
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

                  <!-- bank -->
                   <div class="card card-default collapsed-card family-info">
                    <div class="card-header">
                      <h3 class="card-title">Bank Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.bank-info-update') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                      
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <a class="btn btn-success breadcrumb-btn float-sm-right user-bank-addmore"><i
                                class="fas fa-plus"></i> Add</a>
                          </div>
                           
                           
                              <div class="col-md-12 col-sm-12">
                            <table class="table" id="user_bank_records">
                              <tbody>
                                 @if(count($userbanks) > 0)                                
                                 @foreach($userbanks as $userbank)
                               

                               <input type="hidden" id="id" name="id[]" class="form-control"
                                   value="{{ $userbank->id }}"  autocomplete="off" required>


                                <tr id="userbankNumone0">
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;">
                                    <small id="snum0">1</small></td>
                                    
                                  <td style="border-top: none;">
                                    <label for="bank_name_usd">Bank Name</label>
                                    <select class="form-control select2bs4" name="bank_id[]" id="bank_id0" style="width: 100%;">
                                      <option value="">- Select -</option>
                                      @foreach($banks as $bank)
                                     <option value="{{ $bank->id }}" {{old('bank_id',$userbank->bank_id) == $bank->id ? 'selected':''}}>{{ $bank->name }}</option>
                                      @endforeach
                                 </select>
                                </td>

                                <td style="border-top: none;">
                                    <label>Currency </label>
                                      <select class="form-control select2bs4" name="currency[]" id="currency0"
                                         style="width: 100%;">
                                         <option value="mmk" {{old('currency',$userbank->currency) == 'mmk' ? 'selected':''}}>MMK</option>
                                         <option value="usd" {{old('currency',$userbank->currency) == 'usd' ? 'selected':''}}>USD</option>                                         
                                      </select>
                                  </td>

                                  <td style="border-top: none;">
                                    <label>Bank Account <span class="required text-danger">*</span></label>
                                    <input type="text" id="bank_account0" name="bank_account[]" class="form-control"
                                     value="{{ $userbank->bank_account }}" autocomplete="off" required>
                                  </td>  
                                  
                                   <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action</label>
                                   <div class="delete-row btn btn-danger remove" onclick='delete_BankAccount("{{ $userbank->id }}")' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                    </div>
                                   </td>
                                </tr>
                                @endforeach
                                @else
                                 <tr id="userbankNumone0">
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;">
                                    <small id="snum0">1</small></td>
                                 
                                  


                                  <td style="border-top: none;">
                                    <label for="bank_name_usd">Bank Name</label>
                                    <select class="form-control select2bs4" name="bank_id[]" id="bank_id0" style="width: 100%;">
                                      <option value="">- Select -</option>
                                      @foreach($banks as $bank)
                                     <option value="{{ $bank->id }}" {{old('bank_id',$user->bank_id) == $bank->id ? 'selected':''}}>{{ $bank->name }}</option>
                                      @endforeach
                                 </select>
                                </td>

                                <td style="border-top: none;">
                                    <label>Currency </label>
                                      <select class="form-control select2bs4" name="currency[]" id="currency0"
                                         style="width: 100%;">
                                         <option value="mmk">MMK</option>
                                         <option value="usd" >USD</option>                                         
                                      </select>
                                  </td>

                                  <td style="border-top: none;">
                                    <label>Bank Account <span class="required text-danger">*</span></label>
                                    <input type="text" id="bank_account0" name="bank_account[]" class="form-control"
                                      autocomplete="off" required>
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
                  

                  <!-- bank -->
                  
                  
                   <!-- start life assured -->
                   <div class="card card-default collapsed-card assurance-info">
                    <div class="card-header">
                      <h3 class="card-title">Life  Assurance Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.assurance-update') }}" method="post">
                            @csrf
                          <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <a class="btn btn-success breadcrumb-btn float-sm-right assurance-addmore"><i class="fas fa-plus"></i> Add</a>
                        </div>
                        
                        <div class="col-md-12 col-sm-12">
                          <table class="table" id="assurance_records">
                            <tbody>
                              @if(count($life_assurances) > 0)                                
                             @foreach($life_assurances as $life_assurance)
                            
                              <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Year <span class="required text-danger">*</span> </label>
                                  <input type="text" id="year0" name="year[]" onkeypress="return isNumberKey(event)" value="{{ old('year',$life_assurance->year)}}"  required class="form-control acquisition" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Premium Amount <span class="required text-danger">*</span> </label>
                                  <input type="text" id="premium_amount0" name="premium_amount[]" value="{{ old('premium_amount',$life_assurance->premium_amount)}}" onkeypress="return isNumberKey(event)" required class="form-control" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label><br>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_Assurance(this)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td>
                              </tr>
                             @endforeach
                             @else
                              <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Year <span class="required text-danger">*</span> </label>
                                  <input type="text" id="year0" name="year[]" onkeypress="return isNumberKey(event)" required class="form-control acquisition" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Premium Amount <span class="required text-danger">*</span> </label>
                                  <input type="text" id="premium_amount0" name="premium_amount[]" onkeypress="return isNumberKey(event)" required class="form-control" autocomplete="off">
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
                  <!--end life assured  -->

                  <div class="card card-default collapsed-card education-info">
                    <div class="card-header">
                      <h3 class="card-title">Education Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.education-update') }}" method="post">
                          @csrf
                          <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <a class="btn btn-success breadcrumb-btn float-sm-right education-addmore"><i class="fas fa-plus"></i> Add</a>
                        </div>
                        
                        <div class="col-md-12 col-sm-12">
                          <table class="table" id="education_records">
                            
                            <tbody>
                             @if(count($educations) > 0)                                
                             @foreach($educations as $education)
                             <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Education Type <span class="required text-danger">*</span> </label>
                                  <div>
                                    <div style="width:200px;" id="education0">
                                         <input type="text"  id="education_type0" name="education_type[]" value="{{ $education->education_type }}"  class="form-control" autocomplete="off" required>
                                      </div>
                                  </div>
                                </td>
                                <td style="border-top: none;">
                                  <label>School Name <span class="required text-danger">*</span></label>
                                  <input type="text" id="school_name0" name="school_name[]" value="{{ $education->school_name }}"  class="form-control" autocomplete="off" required>
                                </td>
                                <td style="border-top: none;">
                                  <label>Year <span class="required text-danger">*</span></label>
                                  <input type="text" id="date_of_graduation0" onkeypress="return isNumberKey(event)"  name="date_of_graduation[]" value="{{ $education->date_of_graduation }}" required class="form-control graduation" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Major <span class="required text-danger">*</span></label>
                                  <input type="text" id="major0" name="major[]" class="form-control" value="{{ $education->major }}" required autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_Education(this)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td>
                              </tr>
                             @endforeach
                             @else
                              <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Education Type <span class="required text-danger">*</span> </label>
                                   <input type="text" id="education_type0" name="education_type[]" value=""  class="form-control" autocomplete="off" required>
                                </td>
                                <td style="border-top: none;">
                                  <label>School Name <span class="required text-danger">*</span> </label>
                                  <input type="text" id="school_name0" name="school_name[]" class="form-control" autocomplete="off" required>
                                </td>
                                <td style="border-top: none;">
                                  <label>Year <span class="required text-danger">*</span></label>
                                  <input type="text" id="date_of_graduation0" name="date_of_graduation[]"  onkeypress="return isNumberKey(event)"  required class="form-control graduation" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Major <span class="required text-danger">*</span></label>
                                  <input type="text" id="major0" name="major[]" class="form-control" required autocomplete="off">
                                </td>
                                <!-- <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_Education(this)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td> -->
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

                  <div class="card card-default collapsed-card qualification-info">
                    <div class="card-header">
                      <h3 class="card-title">Qualification/Certificate Information</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.qualification-update') }}" method="post">
                            @csrf
                          <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <a class="btn btn-success breadcrumb-btn float-sm-right qualification-addmore"><i class="fas fa-plus"></i> Add</a>
                        </div>
                        
                        <div class="col-md-12 col-sm-12">
                          <table class="table" id="qualification_records">
                            <tbody>
                              @if(count($qualifications) > 0)                                
                             @foreach($qualifications as $qualification)
                             <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Year <span class="required text-danger">*</span>  </label>
                                  <input type="text" id="date_of_acquition0" name="date_of_acquition[]" onkeypress="return isNumberKey(event)" required class="form-control acquisition" value="{{ old('date_of_acquition',$qualification->date_of_acquition)}}" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Certificate <span class="required text-danger">*</span> </label>
                                  <input type="text" id="certificate0" name="certificate[]" required value="{{ old('certificate',$qualification->certificate)}}" class="form-control" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label><br>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_Qualification(this)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td>
                              </tr>
                             @endforeach
                             @else
                              <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Year <span class="required text-danger">*</span> </label>
                                  <input type="text" id="date_of_acquition0" name="date_of_acquition[]" onkeypress="return isNumberKey(event)" required class="form-control acquisition" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Certificate <span class="required text-danger">*</span> </label>
                                  <input type="text" id="certificate0" name="certificate[]" required class="form-control" autocomplete="off">
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

                  <div class="card card-default collapsed-card language-skill">
                    <div class="card-header">
                      <h3 class="card-title">Language Skill</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.language-skill-update') }}" method="post">
                         @csrf
                      <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <a class="btn btn-success breadcrumb-btn float-sm-right language-addmore"><i class="fas fa-plus"></i> Add</a>
                        </div>
                        
                        <div class="col-md-12 col-sm-12">
                          <table class="table" id="language_records">
                            
                            <tbody>
                              @if(count($languages) > 0)                                
                             @foreach($languages as $language)
                             <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                
                                <td style="border-top: none;">
                                  <label>Language Skill  <span class="required text-danger">*</span> </label>
                                  <input type="text" id="language_skill0" name="language_skill[]" value="{{ $language->language_skill }}" class="form-control" required autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Level  <span class="required text-danger">*</span> </label>
                                  <input type="text" id="level0" name="level[]" value="{{ $language->level }}" class="form-control"  required autocomplete="off">
                                </td>

                                 <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_Language(this)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td>

                              </tr>
                             @endforeach
                             @else
                              <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                
                                <td style="border-top: none;">
                                  <label>Language Skill  <span class="required text-danger">*</span> </label>
                                  <input type="text" id="language_skill0" name="language_skill[]" class="form-control" required autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Level  <span class="required text-danger">*</span> </label>
                                  <input type="text" id="level0" name="level[]" class="form-control"  required autocomplete="off">
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

                  <div class="card card-default collapsed-card english-skill">
                    <div class="card-header">
                      <h3 class="card-title">English Skill</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.english-skill-update') }}" method="post">
                        @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <a class="btn btn-success breadcrumb-btn float-sm-right english-addmore"><i class="fas fa-plus"></i> Add</a>
                        </div>
                        
                        <div class="col-md-12 col-sm-12">
                          <table class="table" id="english_records">
                            
                            <tbody>
                              @if(count($englishes) > 0)                                
                             @foreach($englishes as $english)
                              <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Type of English Test <span class='required text-danger'>*</span> </label>
                                  
                                  <input type="text" id="test_type0" name="test_type[]" required value="{{ $english->test_type }}" class="form-control" autocomplete="off">
                                    
                                </td>
                                <td style="border-top: none;">
                                  <label>Mark </label>
                                  <input type="number" id="mark0" name="mark[]"  value="{{ $english->mark }}" onkeypress="return setTwoNumberDecimal(event,this)" class="form-control" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Level </label>
                                  <input type="text" id="level0" name="level[]"   value="{{ $english->level }}" class="form-control" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Year </label>
                                  <input type="number" id="date_of_acquition0" value="{{ $english->date_of_acquition }}" onkeypress="return isNumberKey(event)"  name="date_of_acquition[]" class="form-control english-test-date" autocomplete="off">
                                </td>
                                
                                <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_English(this)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td>
                              </tr>
                             @endforeach
                             @else
                              <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                <td style="border-top: none;">
                                  <label>Type of English Test <span class='required text-danger'>*</span> </label>
                                   <input type="text" id="test_type0" name="test_type[]" required  class="form-control" autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Mark </label>
                                  <input type="number" id="mark0" name="mark[]" onkeypress="return setTwoNumberDecimal(event,this)" class="form-control" autocomplete="off" >
                                </td>
                                <td style="border-top: none;">
                                  <label>Level </label>
                                  <input type="text" id="level0" name="level[]" class="form-control" autocomplete="off" >
                                </td>
                                <td style="border-top: none;">
                                  <label>Year </label>
                                  <input type="number" id="date_of_acquition0" name="date_of_acquition[]" onkeypress="return isNumberKey(event)" class="form-control english-test-date" autocomplete="off" >
                                </td>
                                
                                <!-- <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_English(this)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td> -->
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

                  <!-- start driver -->

                  <div class="card card-default collapsed-card license-skill">
                    <div class="card-header">
                      <h3 class="card-title">Driver License</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.license-update') }}" method="post">
                         @csrf
                      <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <!-- <div class="col-md-12 col-sm-12">
                          <a class="btn btn-success breadcrumb-btn float-sm-right license-addmore"><i class="fas fa-plus"></i> Add</a>
                        </div> -->
                        
                        <div class="col-md-12 col-sm-12">
                          <table class="table" id="llicense_records">
                            
                            <tbody>
                               <tr>
                              
                                
                                <td style="border-top: none;">
                                  <label>License Number <span class='required text-danger'>*</span>  </label>
                                  <input type="text" id="license_number0" name="license_number" class="form-control" required autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>Start Date <span class='required text-danger'>*</span> </label>
                                  <input type="date" id="start_date0" name="start_date" class="form-control"  required autocomplete="off">
                                </td>

                                 <td style="border-top: none;">
                                  <label>Due Date <span class='required text-danger'>*</span>  </label>
                                  <input type="date" id="due_date0" name="due_date" class="form-control"  required autocomplete="off">
                                    </td>
                                <td>
                                   <label> </label>
                                <div class="form-group text-center">
                                  <input type="submit" class="btn btn-primary" value="Extend">
                                 </div>                                  
                                </td>

                              </tr>

                              <tr>
                                 <th>License Number</th>
                                 <th>Start Date</th>
                                  <th>End Date</th>
                              </tr>
                              @if(count($driver_licenses) > 0)                                
                             @foreach($driver_licenses as $license)
                           <?php   if ($license->due_date > date('Y-m-d') ) {
                               $class = 'text-success';
                             }else{
                               $class = 'text-danger';
                             } 
                             ?>
                             <?php
                           if(!empty($license->start_date)){
                               $license_start_data = str_replace('-', '/', $license->start_date);
                               $license_start_date = date("d/m/Y", strtotime($license_start_data));
                           }else{
                               $license_start_date='';
                           }
                           ?>
                              <?php
                           if(!empty($license->due_date)){
                               $license_due_data = str_replace('-', '/', $license->due_date);
                               $license_due_date = date("d/m/Y", strtotime($license_due_data));
                           }else{
                               $license_due_date='';
                           }
                           ?>
                             <tr>
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                
                                <td style="border-top: none;">
                                  <label class="{{ $class }}">{{ $license->license_number }}</label>
                                </td>
                                <td style="border-top: none;">
                                  <label class="{{ $class }}">{{ $license_start_date }}</label>
                                </td>
                                <td style="border-top: none;">
                                  <label class="{{ $class }}">{{ $license_due_date }}</label>
                                </td>

                              </tr>
                             @endforeach
                             @endif                             

                            </tbody>
                          </table>
                        </div>
                      </div>
                      </form>
                    </div>
                    <!-- /.card-body -->
                  </div>

                  

                  <!-- end driver -->

                  <div class="card card-default collapsed-card pc-skill">
                    <div class="card-header">
                      <h3 class="card-title">PC Skill</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.pc-skill-update') }}" method="post">
                        @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                          <div class="row">
                            <div class="col-md-12 col-sm-12">
                              <a class="btn btn-success breadcrumb-btn float-sm-right pc-addmore"><i class="fas fa-plus"></i> Add</a>
                            </div>
                          
                            <div class="col-md-12 col-sm-12">
                              <table class="table" id="pc_records">
                          
                                <tbody>
                                   <?php

                                   $microsoft_word = explode(",", $user->microsoft_word);

                                   ?>
                                   
                                   <tr>
                                      <td>MicroSoft Word <span class="text-danger">*</span></td>
                                      <td style="border-top: none;">
                                        <input type="checkbox" name="microsoft_word[]" value="myanmar"  <?=(in_array('myanmar', $microsoft_word)?'checked="checked"':NULL)?>  >
                                        <label>Myanmar </label>
                                      </td>
                                      <td style="border-top: none;">
                                        <input type="checkbox"  name="microsoft_word[]" value="english" <?=(in_array('english', $microsoft_word)?'checked="checked"':NULL)?> >
                                        <label>English </label>
                                      </td>
                                      <td style="border-top: none;">
                                        <input type="checkbox"  name="microsoft_word[]" value="japanese" <?=(in_array('japanese', $microsoft_word)?'checked="checked"':NULL)?> >
                                        <label>Japanese </label>
                                      </td>
                                   </tr>
                                    <tr>
                                      <td>MicroSoft Excel <span class="text-danger">*</span></td>
                                      <td style="border-top: none;">
                                        <input type="radio"  name="microsoft_excel" value="basic" {{old('microsoft_excel',$user->microsoft_excel) == 'basic' ? 'checked':''}}>
                                        <label>Basic </label>
                                      </td>
                                      <td style="border-top: none;">
                                        <input type="radio"  name="microsoft_excel" value="intermediate" {{old('microsoft_excel',$user->microsoft_excel) == 'intermediate' ? 'checked':''}}>
                                        <label>Intermediate </label>
                                      </td>
                                      <td style="border-top: none;">
                                        <input type="radio"  name="microsoft_excel" value="advanced"  {{old('microsoft_excel',$user->microsoft_excel) == 'advanced' ? 'checked':''}}>
                                        <label>Advanced </label>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>MicroSoft Powerpoint <span class="text-danger">*</span></td>
                                      <td style="border-top: none;">
                                        <input type="radio"  name="microsoft_powerpoint" value="basic" {{old('microsoft_powerpoint',$user->microsoft_powerpoint) == 'basic' ? 'checked':''}}>
                                        <label>Basic </label>
                                      </td>
                                      <td style="border-top: none;">
                                        <input type="radio"  name="microsoft_powerpoint" value="intermediate" {{old('microsoft_powerpoint',$user->microsoft_powerpoint) == 'intermediate' ? 'checked':''}}>
                                        <label>Intermediate </label>
                                      </td>
                                      <td style="border-top: none;">
                                        <input type="radio"  name="microsoft_powerpoint" value="advanced" {{old('microsoft_powerpoint',$user->microsoft_powerpoint) == 'advanced' ? 'checked':''}}>
                                        <label>Advanced </label>
                                      </td>
                                    </tr>

                                     @if(count($pcskills) > 0)                                
                                @foreach($pcskills as $pcskill)
                                  <tr>
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                  
                                    <td style="border-top: none;">
                                      <label>Skill  Title <span class='required text-danger'>*</span> </label>
                                      <input type="text" id="skill_title0" name="skill_title[]"  value="{{ $pcskill->title }}" required class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Level  </label>
                                      <input type="text" id="skill_level0" name="skill_level[]" value="{{ $pcskill->level }}"   class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label style="visibility: hidden;">Action </label><br>
                                      <div class="delete-row btn btn-danger remove" onclick='delete_Row_PC(this)' title="Remove row">
                                        <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                      </div>
                                    </td>
                                  </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                     <td style="border-top: none;">
                                      <label>Skill  Title <span class='required text-danger'>*</span> </label>
                                      <input type="text" id="skill_title0" name="skill_title[]" required class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Level  </label>
                                      <input type="text" id="skill_level0" name="skill_level[]"  class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label style="visibility: hidden;">Action </label><br>
                                      <div class="delete-row btn btn-danger remove" onclick='delete_Row_PC(this)' title="Remove row">
                                        <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                      </div>
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

                  <div class="card card-default collapsed-card employment-record">
                    <div class="card-header">
                      <h3 class="card-title">Employment Record</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('employee.employment-records-update') }}" method="post">
                        @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                          <div class="row">
                            <div class="col-md-12 col-sm-12">
                              <a class="btn btn-success breadcrumb-btn float-sm-right employement-addmore"><i class="fas fa-plus"></i> Add</a>
                            </div>
                          
                            <div class="col-md-12 col-sm-12">
                              <table class="table" id="emplyement_records">
                          
                                <tbody>
                                   @if(count($employmentrecords) > 0)                                
                                @foreach($employmentrecords as $employmentrecord)

                                 <tr id="employementNumone0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                        id="snum0">1</small></td>
                          
                                    <td style="border-top: none;">
                                      <label>Company Name  <span class='required text-danger'>*</span> </label>
                                      <input type="text" id="emplyement_company_name0" name="company_name[]" value="{{ $employmentrecord->company_name  }}" required class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>From (Year) </label>
                                       <input type="number"  name="start_date[]" id="emplyement_start_date0" onkeypress="return isNumberKey(event)" value="{{ $employmentrecord->start_date  }}"  class="form-control"/>
                                    </td>
                                    <td style="border-top: none;padding-top: 18px;">
                                      <label >To (Year)</label>
                                        <input type="number" name="end_date[]" id="emplyement_end_date0" onkeypress="return isNumberKey(event)" value="{{ $employmentrecord->end_date  }}"  class="form-control"/>
                                    </td>
                                  </tr>
                                  <tr id="employementNumtwo0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                  
                                    <td style="border-top: none;">
                                      <label>Position</label>
                                      <input type="text" id="emplyement_position0" name="position[]"  value="{{ $employmentrecord->position  }}"   class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Department </label>
                                      <input type="text" id="emplyement_department0" name="department[]" value="{{ $employmentrecord->department  }}"  class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label style="visibility: hidden;">Action </label><br>
                                      <div class="delete-row btn btn-danger remove" onclick='delete_Row_Employement(0)' title="Remove row">
                                        <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                      </div>
                                    </td>
                                  </tr>


                                @endforeach
                                @else
                                  <tr id="employementNumone0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                        id="snum0">1</small></td>
                          
                                    <td style="border-top: none;">
                                      <label>Company Name  <span class='required text-danger'>*</span></label>
                                      <input type="text" id="emplyement_record0" name="company_name[]" required class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>From (Year) </label>
                                       <input type="number" name="start_date[]" onkeypress="return isNumberKey(event)" id="emplyement_record0"  class="form-control"/>
                                    </td>
                                    <td style="border-top: none;padding-top: 18px;">
                                      <label>To (Year)</label>
                                        <input type="number" name="end_date[]"  onkeypress="return isNumberKey(event)" id="emplyement_record0"   class="form-control"/>
                                    </td>
                                  </tr>
                                  <tr id="employementNumtwo0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                  
                                    <td style="border-top: none;">
                                      <label>Position</label>
                                      <input type="text" id="emplyement_record0" name="position[]"  class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Department </label>
                                      <input type="text" id="emplyement_record0" name="department[]"  class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label style="visibility: hidden;">Action </label><br>
                                      <div class="delete-row btn btn-danger remove" onclick='delete_Row_Employement(0)' title="Remove row">
                                        <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                      </div>
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

                  <div class="card card-default collapsed-card oversea-record">
                    <div class="card-header">
                      <h3 class="card-title">Oversea Record</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.oversea-records-update') }}" method="post">
                        @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <a class="btn btn-success breadcrumb-btn float-sm-right oversea-addmore"><i class="fas fa-plus"></i> Add</a>
                        </div>
                      
                        <div class="col-md-12 col-sm-12">
                          <table class="table" id="oversea_records">
                            <tbody>
                                @if(count($oversearecords) > 0)                                
                                @foreach($oversearecords as $oversearecord)
                                    <tr id="overseaNumone0">
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                    id="snum0">1</small></td>
                      
                                <td style="border-top: none;">
                                  <label>Country Name  <span class='required text-danger'>*</span></label>
                                  <input type="text" id="oversea_record0" name="country_name[]" required value="{{ $oversearecord->country_name }}" class="form-control"
                                    autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>From (Year)</label>
                                    <input type="number" name="start_date[]" id="oversea_record0" onkeypress="return isNumberKey(event)"  value="{{ $oversearecord->start_date }}"
                                      class="form-control"  />
                                </td>
                                <td style="border-top: none;padding-top: 18px;">
                                  <label>To (Year)</label>
                                    <input type="number" name="end_date[]" id="oversea_record0" onkeypress="return isNumberKey(event)"  value="{{ $oversearecord->end_date }}" class="form-control" />
                                </td>
                              </tr>
                              <tr id="overseaNumtwo0">
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                    id="snum0">1</small></td>
                      
                                <td style="border-top: none;">
                                  <label>Purpose</label>
                                    <textarea name="purpose[]" id="oversea_record0" class="form-control"  value="{{ $oversearecord->purpose }}">
                                      {{ $oversearecord->purpose }}
                                    </textarea>
                                </td>
                                
                                <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label><br>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_oversea(0)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
                                </td>
                              </tr>

                                    @endforeach
                                    @else
                                    <tr id="overseaNumone0">
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                    id="snum0">1</small></td>
                      
                                <td style="border-top: none;">
                                  <label>Country Name  <span class='required text-danger'>*</span></label>
                                  <input type="text" id="oversea_record0" name="country_name[]" required class="form-control"
                                    autocomplete="off">
                                </td>
                                <td style="border-top: none;">
                                  <label>From (Year) </label>
                                    <input type="number" name="start_date[]" onkeypress="return isNumberKey(event)"  id="oversea_record0"
                                      class="form-control"  />
                                </td>
                                <td style="border-top: none;padding-top: 18px;">
                                  <label>To (Year)</label>
                                    <input type="number" name="end_date[]" onkeypress="return isNumberKey(event)" id="oversea_record0"  class="form-control" />
                                </td>
                              </tr>
                              <tr id="overseaNumtwo0">
                                <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                    id="snum0">1</small></td>
                      
                                <td style="border-top: none;">
                                  <label>Purpose</label>
                                    <textarea name="purpose[]" id="oversea_record0"  class="form-control"></textarea>
                                </td>
                                
                                <td style="border-top: none;">
                                  <label style="visibility: hidden;">Action </label><br>
                                  <div class="delete-row btn btn-danger remove" onclick='delete_Row_oversea(0)' title="Remove row">
                                    <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                  </div>
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

                  <div class="card card-default collapsed-card warning-info">
                    <div class="card-header">
                      <h3 class="card-title">Warning</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                       <form action="{{ route('employee.warnings-update') }}" method="post">
                        @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <a class="btn btn-success breadcrumb-btn float-sm-right warning-addmore"><i class="fas fa-plus"></i> Add</a>
                          </div>
                        
                          <div class="col-md-12 col-sm-12">
                            <table class="table" id="warning_records">
                        
                              <tbody>
                                @if(count($warnings) > 0)                                
                                @foreach($warnings as $warning)
                                <tr>
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                      id="snum0">1</small></td>
                        
                                  <td style="border-top: none;">
                                    <label>Date  <span class='required text-danger'>*</span></label>
                                      <input type="date" name="date[]" id="warning_date0" value="{{ $warning->date }}" required class="form-control" />
                                  </td>
                                  <td style="border-top: none;">
                                    <label>Reason </label>
                                    <input type="text" id="warning_reason0" name="reason[]" value="{{ $warning->reason }}"  class="form-control" autocomplete="off">
                                  </td>
                                  <td style="border-top: none;">
                                    <label style="visibility: hidden;">Action </label><br>
                                    <div class="delete-row btn btn-danger remove" onclick='delete_Row_warning(this)' title="Remove row">
                                      <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                    </div>
                                  </td>
                                </tr>
                                @endforeach
                                @else                                
                                <tr>
                                  <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                      id="snum0">1</small></td>
                        
                                  <td style="border-top: none;">
                                    <label>Date  <span class='required text-danger'>*</span></label>
                                      <input type="date" name="date[]" id="warning_date0" required class="form-control" />
                                  </td>
                                  <td style="border-top: none;">
                                    <label>Reason </label>
                                    <input type="text" id="warning_reason0" name="reason[]"  class="form-control" autocomplete="off">
                                  </td>
                                  <td style="border-top: none;">
                                    <label style="visibility: hidden;">Action </label><br>
                                    <div class="delete-row btn btn-danger remove" onclick='delete_Row_warning(this)' title="Remove row">
                                      <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                    </div>
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

                  <div class="card card-default collapsed-card retirement-info">
                    <div class="card-header">
                      <h3 class="card-title">Retirement</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ route('employee.retirements')}}" method="post">
                         @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                         <?php
                              if(!empty($retirement->date)){
                                  $retirement_date = $retirement->date;
                              }else{
                                  $retirement_date = '';
                              }
                            ?>
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="form-group">
                            <label>Date  <span class='required text-danger'>*</span></label>
                              <input type="date" name="date" id="retirements" value="{{ $retirement_date }}" required class="form-control"/>
                          </div>
                        </div>
                      </div>
                       <?php
                              if(!empty($retirement->reason)){
                                  $retirement_reason = $retirement->reason;
                              }else{
                                  $retirement_reason = '';
                              }
                            ?>
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="form-group">
                            <label>Reason</label>
                            <textarea class="form-control" id="retirements" name="reason" rows="2">
                              {{ $retirement_reason }}
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

                  <div class="card card-default collapsed-card other-info">
                    <div class="card-header">
                      <h3 class="card-title">Other</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body"> 
                      <form action="{{ route('employee.other')}}" method="post">
                         @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="form-group">
                            <label>Interest</label>
                            <?php
                              if(!empty($other->interest)){
                                  $interest = $other->interest;
                              }else{
                                  $interest = '';
                              }
                            ?>
                            <textarea class="form-control" id="other_changing_condition" name="interest" rows="2">
                               {{ $interest }}
                            </textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="form-group">
                            <label>Strong Point</label>
                             <?php
                              if(!empty($other->strong_point)){
                                  $strong_point = $other->strong_point;
                              }else{
                                  $strong_point = '';
                              }
                            ?>
                            <textarea class="form-control" id="other_changing_condition" name="strong_point" rows="2">
                              {{ $strong_point }}
                            </textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="form-group">
                            <label>Weak Point</label>
                            <?php
                              if(!empty($other->weak_point)){
                                  $weak_point = $other->weak_point;
                              }else{
                                  $weak_point = '';
                              }
                            ?>
                            <textarea class="form-control" id="other_changing_condition" name="weak_point" rows="2">
                              {{ $weak_point }}
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
                 
                 <!--start-->
               
                 @if( auth()->user()->can('dep-gm-for-performance-evaluation') || auth()->user()->can('admin-gm-for-performance-evaluation') || $user->id == auth()->user()->id )
                  <div class="card card-default collapsed-card performance-evaluation">
                    <div class="card-header">
                      <h3 class="card-title">Performance Evaluation</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                      </div>
                      <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                       <form action="{{ route('employee.evaluation-records-update') }}" method="post">
                          @csrf
                         <input type="hidden" name="id" value="{{ $user->id }}">
                          <div class="row">
                             @if(auth()->user()->can('admin-gm-for-performance-evaluation') )
                              <div class="col-md-12 col-sm-12">
                                <a class="btn btn-success breadcrumb-btn float-sm-right evaluation-addmore"><i class="fas fa-plus"></i> Add</a>
                               </div>
                              @endif
                            <div class="col-md-12 col-sm-12">
                              <table class="table" id="evaluation_records">
                                <tbody>
                                  @if(count($evaluations) > 0)                                
                                  @foreach($evaluations as $evaluation)
                                   <tr id="evaluationNumone0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                        id="snum0">1</small></td>
                          
                                    <td style="border-top: none;">
                                      <label>Year <span class='required text-danger'>*</span></label>
                                      <input type="number" id="warning_date0" name="year[]" required value="{{ $evaluation->year }}" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Band </label>
                                      <input type="number" id="warning_reason0" name="grade[]"  value="{{ $evaluation->grade }}" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Title </label>
                                      <input type="text" id="warning_reason0" name="title[]"  value="{{ $evaluation->title }}" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    
                                    <td style="border-top: none;">
                                      <label>Position<span class='required text-danger'>*</span></label>
                                      <input type="text" id="position0" name="position[]" required  value="{{ $evaluation->position }}" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    
                                    
                                  </tr>
                                  <tr id="evaluationNumtwo0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                  
                                    <td style="border-top: none;">
                                      <label>Competency</label>
                                      <input type="text" id="warning_date0" name="competency[]"  value="{{ $evaluation->competency }}" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Performance </label>
                                      <select  class="form-control select2bs4" name="performance[]"  id="performance_id0"  style="width: 100%;" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'disabled' : '' }}>
                                        <option value="">Select Performance</option>
                                        <option value="S" {{old('performance',$evaluation->performance) == 'S' ? 'selected':''}}>S</option>
                                        <option value="A" {{old('performance',$evaluation->performance) == 'A' ? 'selected':''}}>A</option>
                                        <option value="B" {{old('performance',$evaluation->performance) == 'B' ? 'selected':''}}>B</option>
                                        <option value="C" {{old('performance',$evaluation->performance) == 'C' ? 'selected':''}}>C</option>
                                        <option value="D" {{old('performance',$evaluation->performance) == 'D' ? 'selected':''}}>D</option>
                                      </select>
                                     
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Salary(Net Pay) <span class='required text-danger'>*</span></label>
                                      <input type="text" id="warning_reason0" name="net_pay[]" required onkeypress="return setTwoNumberDecimal(event,this)" value="{{ $evaluation->net_pay }}"  {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Basic Salary <span class='required text-danger'>*</span></label>
                                      <input type="text" id="warning_reason0" name="basic_salary[]" required onkeypress="return setTwoNumberDecimal(event,this)" value="{{ $evaluation->basic_salary }}" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                  </tr>

                                  <tr id="evaluationNumthree0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                  
                                    <td style="border-top: none;">
                                      <label>Duty Allowance</label>
                                      <input type="text" id="allowance0" name="allowance[]"  onkeypress="return setTwoNumberDecimal(event,this)" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} value="{{ $evaluation->allowance }}" class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>OT Rate </label>
                                      <input type="text" id="warning_reason0" name="ot_rate[]"  onkeypress="return setTwoNumberDecimal(event,this)" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} value="{{ $evaluation->ot_rate }}" class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Bonus (Water Fastival) </label>
                                      <input type="text" id="warning_reason0" name="water_festival_bonus[]"  onkeypress="return setTwoNumberDecimal(event,this)" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }}  value="{{ $evaluation->water_festival_bonus }}" class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Bonus (Thadingyut)</label>
                                      <input type="text" id="warning_reason0" name="thadingyut_bonus[]"   onkeypress="return setTwoNumberDecimal(event,this)" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} value="{{ $evaluation->thadingyut_bonus }}" oninput="setTwoNumberDecimal(this)"  step="0.01" class="form-control" autocomplete="off">
                                    </td> 
                                    
                                    <td style="border-top: none;">
                                      <label style="visibility: hidden;">Action </label><br>
                                      <div class="delete-row btn btn-danger remove" onclick='delete_Row_evaluation(0)' title="Remove row">
                                        <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                      </div>
                                    </td>
                                  </tr>

                                  @endforeach
                                  @else                                

                                  <tr id="evaluationNumone0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small
                                        id="snum0">1</small></td>
                          
                                    <td style="border-top: none;">
                                      <label>Year <span class='required text-danger'>*</span></label>
                                      <input type="number" id="warning_date0" name="year[]" required class="form-control" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Band</label>
                                      <input type="number" id="warning_reason0" name="grade[]"  class="form-control" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Title </label>
                                      <input type="text" id="warning_reason0" name="title[]"  class="form-control" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} autocomplete="off">
                                    </td>
                                    
                                   <td style="border-top: none;">
                                      <label>Position<span class='required text-danger'>*</span></label>
                                      <input type="text" id="position0" name="position[]" required   {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>

                                    
                                  </tr>
                                  <tr id="evaluationNumtwo0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                  
                                    <td style="border-top: none;">
                                      <label>Competency</label>
                                      <input type="text" id="warning_date0" name="competency[]" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }}  class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Performance </label>
                                      <select id="performance_id0"  name="performance[]" style="width: 100%;" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'disabled' : '' }}  class="form-control select2bs4">
                                        <option value="">Select Performance</option>
                                        <option value="S">S</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                      </select>
                                     
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Salary(Net Pay) <span class='required text-danger'>*</span></label>
                                      <input type="text" id="warning_reason0" required  onkeypress="return setTwoNumberDecimal(event,this)" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }}  name="net_pay[]" class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Basic Salary <span class='required text-danger'>*</span></label>
                                      <input type="text" id="warning_reason0" required  onkeypress="return setTwoNumberDecimal(event,this)" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} name="basic_salary[]" class="form-control" autocomplete="off">
                                    </td>
                                  </tr>

                                  <tr id="evaluationNumthree0">
                                    <td style="padding-left: 9px;vertical-align: middle; display: none;border-top: none;"><small id="snum0">1</small></td>
                                  
                                    <td style="border-top: none;">
                                      <label>Duty Allowance</label>
                                      <input type="text" id="allowance0"  onkeypress="return setTwoNumberDecimal(event,this)" name="allowance[]" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>OT Rate </label>
                                      <input type="text" id="warning_reason0"  onkeypress="return setTwoNumberDecimal(event,this)" name="ot_rate[]" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Bonus (Water Fastival) </label>
                                      <input type="text" id="warning_reason0"  onkeypress="return setTwoNumberDecimal(event,this)" name="water_festival_bonus[]" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label>Bonus (Thadingyut)</label>
                                      <input type="text" id="warning_reason0"  onkeypress="return setTwoNumberDecimal(event,this)" step="0.01" name="thadingyut_bonus[]" {{ !auth()->user()->can('admin-gm-for-performance-evaluation') ? 'readonly' : '' }} class="form-control" autocomplete="off">
                                    </td>
                                    <td style="border-top: none;">
                                      <label style="visibility: hidden;">Action </label><br>
                                      <div class="delete-row btn btn-danger remove" onclick='delete_Row_evaluation(0)' title="Remove row">
                                        <i class="fa fa-minus" style="font-size: 14px;font-weight: bold;color: white;"></i>
                                      </div>
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
                  @endif

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</script>
<script type="text/javascript">
//create script start


        $(function () {
            //Date picker
         
            $('#timepicker2').datetimepicker({
                format: 'HH:mm'
            });
            $('#timepicker3').datetimepicker({
                format: 'HH:mm'
            });

       });

function setTwoNumberDecimal(evt, element) {
      //  el.value = parseFloat(el.value).toFixed(2);

        var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode > 31 && (charCode < 48 || charCode > 57) && !(charCode == 46 || charCode == 8))
    return false;
  else {
    var len = $(element).val().length;
    var index = $(element).val().indexOf('.');
    if (index > 0 && charCode == 46) {
      return false;
    }
    if (index > 0) {
      var CharAfterdot = (len + 1) - index;
      if (CharAfterdot > 3) {
        return false;
      }
    }

  }
  return true;
    };

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
           url:"{{ route('employee.ns-basic-update') }}",
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

    //delete bank info

     function  delete_BankAccount(id){   
       
      // alert(id);
    $.ajax({
           type:'POST',
           url:"{{ route('employee.bank-info-delete') }}",
           data: {"_token": "{{ csrf_token() }}","id": id },
           success:function(data){
                if($.isEmptyObject(data.error)){
                    location.reload();
                }else{
                    printErrorMsg(data.error);
                }
           }
        });       
 }
 </script> 
  <script type="text/javascript">
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>

</section>
@stop