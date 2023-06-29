@extends('layouts.master')
@section('title','RS Employee List')
@section('content')
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
                            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
                            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a> -->
                            <a class="btn btn-success breadcrumb-btn float-sm-right openFilter" href="#"
                                id="advance_search"><i class="fas fa-search-minus"></i> Advanced Search</a>

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content filter-row">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <form action="{{ route('employee.rs-list') }}" method="post">
                                        @csrf
                                        <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group" style="margin-top: 8px;">
                                                <label>Employee Name</label>
                                                <select class="form-control select2bs4" name="search_employee_name" id="search_employee_name" style="width: 100%;">
                                                      <option value="">- Employee Name -</option>
                                                      @foreach($all_users as $user)
                                                      <option value="{{ $user->id }}">{{ $user->employee_name }}</option>
                                                       @endforeach
                                                </select>
                                            </div>
                                        </div>

                                            <div class="col-sm-2" style="margin-top: 8px;">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select class="form-control select2bs4" name="search_department"
                                                        id="search_department" style="width: 100%;">
                                                       <option value="">- Select Department -</option>
                                                       @foreach($all_departments as $depart)
                                                             <option value="{{ $depart->id }}">{{ $depart->name }}</option>
                                                            @endforeach>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <!-- text input -->
                                                <div class="form-group" style="margin-top: 35px;">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                     <a href="{{ route('employee.rs-list') }}" class="btn btn-warning">Reset</a>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.card -->

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->

            </section>
            <!-- /.content -->
            
             @can('rs-export')
                    <!-- start  -->
      <section  class="content">
        <div class="container-fluid">
                <div class="row">
                   <div class="col-12">
                      <div class="card">
                      
                       <!--start export ns employee -->
                       <div class="card-body">
                          <div class="card card-default collapsed-card basic-info">

                        <div class="card-header">
                          <h3 class="card-title">Export RS Employee</h3>
                          <div class="card-tools">
                           <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                           </button>
                          </div>
                        </div>
                         
                   <div class="card-body">
                   <form method="POST" action="{{ route('employee.rs-export') }}"  enctype="multipart/form-data">
                       @csrf
                        <!-- end export ns employee -->
                        <!-- select start employee -->
                         <div class="col-sm-12">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Employee Name</label>
                        <select class="form-control select2bs4" name="employee_name[]" id="employee_name" style="width: 100%;" multiple>
                          @foreach($all_users as $user)
                          <option value="{{ $user->id }}">{{ $user->employee_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    
                    
                    <div class="col-sm-12">
                            <button type='button' id='selectAll'>Select All</button>
                             <button type='button' id='deselectAll'>Deselect All </button>
                      </div>
                      
                        <!-- select end employee -->
                        
                             <div class="card-body">
                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_users" type="checkbox"> Basic Information</h3>
                            </div>

                               <input   id="users" type="hidden" name="users[]" 
                                     value="users.id">

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Employee ID </label>
                                    <div class="form-check">
                                     <input class="form-check-input users"  id="users" type="checkbox" name="users[]" 
                                     value="users.employee_id">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Employee Name </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" id="users" type="checkbox" name="users[]" 
                                     value="users.employee_name">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">DOB  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" id="users" type="checkbox" name="users[]" 
                                     value="users.dob">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Marubeni Entrance Date </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" id="users" type="checkbox" name="users[]" 
                                     value="users.entranced_date">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Email (Personal)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="users.personal_email">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Email (Office) </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"  
                                     value="users.email">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Face Photo</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"  
                                     value="users.photo_name">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Sign Photo </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="users.sign_photo_name">
                                    </div>
                                    </div>
                                 </div>


                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Employee Type </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="employee_types.type as employee_type_name">
                                    </div>
                                    </div>
                                 </div>

                                   <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Working Day Type</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.working_day_type">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Working Start Time</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.working_start_time">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Working End Time</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.working_end_time">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Working Day per Week</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.working_day_per_week">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Position</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.position">
                                    </div>
                                    </div>
                                 </div>


                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Gender  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="users.gender">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Marital Status  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.marital_status">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Graduation Name Of University  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="rs_employees.graduation_name_of_university ">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Major </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="rs_employees.major">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Bank Name (USD) </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="bank_usd.name as bank_name_usd_data">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Bank Account (USD)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.bank_account_usd">
                                    </div>
                                    </div>
                                 </div>

                                   <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Bank Name (USD) </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="bank_mmk.name as bank_name_mmk">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Bank Account (USD)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.bank_account_mmk">
                                    </div>
                                    </div>
                                 </div>


                                 
                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Bank Name (MMK)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="bank_mmk2.name as second_bank_name_mmk_data" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Bank Account (MMK)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.second_bank_account_mmk as second_bank_account_mmk_data">
                                    </div>
                                    </div>
                                 </div>
                                
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">SSC No</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.ssc_no">
                                    </div>
                                    </div>
                                 </div>
                                 

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Blood Type</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.blood_type">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Final Education</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="rs_employees.final_education">
                                    </div>
                                    </div>
                                 </div>

                                 

                                 


                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Passport No </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.passport_number">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Date of Issue </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.date_of_issue">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Passport Date of Expiry </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.date_of_expire">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Residant Place</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.residant_place" >
                                    </div>
                                    </div>
                                 </div>

                                 

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">MJSRV</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.mjsrv" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">MJSRV Expire Date</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.mjsrv_expire_date" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Stay Permit </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.stay_permit" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Stay Permit Expired Date </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.stay_permit_expire_date" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Form C</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.form_c" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">FRC NO</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.frc_no" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">First Arrival Date</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.aboard_date" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Address In Japan </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.japan_address" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Phone In Japan </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.japan_phone" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Hot Line In Japan  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.japan_hot_line" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Address in Myanmar   </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="rs_employees.myanmar_address" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Phone in Myanmar   </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="users.phone" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Other Changing Condition</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="users.other_changing_condition" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Branch</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="branches.name as branch_name">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Departments </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.department_id">
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>


                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_contacts" type="checkbox"> Contact Information</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">First Person Name</label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.first_person_name">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">First Person Email </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.first_person_email">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">First Person Phone  </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.first_person_phone">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">First Person HotLine </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.first_person_hotline">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">First Person Relationship </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.first_person_relationship" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">First Person Address</label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.first_person_address" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Second Person Name </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.second_person_name">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Second Person Email   </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]" 
                                     value="contact_infos.second_person_email">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Second Person Phone </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.second_person_phone">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Second Person Hotline </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                     value="contact_infos.second_person_hotline">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Second Person Relationship</label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                      value="contact_infos.second_person_relationship">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Second Person Address </label>
                                    <div class="form-check">
                                     <input class="form-check-input contacts" type="checkbox" id="users" name="users[]"
                                      value="contact_infos.second_person_address">
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>
                              

                          <!-- </div>
                        </div> -->
                        <!-- end contact -->
                        <!-- start family info -->
                                  <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_relationship" type="checkbox"> Family  Information</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Relationship</label>
                                    <div class="form-check">
                                     <input class="form-check-input relationship" type="checkbox" id="families" name="families[]"
                                     value="families.relationship" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Name </label>
                                    <div class="form-check">
                                     <input class="form-check-input relationship" type="checkbox" id="families" name="families[]"
                                     value="families.name" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Allowance  </label>
                                    <div class="form-check">
                                     <input class="form-check-input relationship" type="checkbox" id="families" name="families[]"
                                     value="families.allowance" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Allowance Fee</label>
                                    <div class="form-check">
                                     <input class="form-check-input relationship" type="checkbox" id="families" name="families[]"
                                     value="families.allowance_fee" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">DOB  </label>
                                    <div class="form-check">
                                     <input class="form-check-input relationship" type="checkbox" id="families" name="families[]" 
                                     value="families.family_dob">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Work / School </label>
                                    <div class="form-check">
                                     <input class="form-check-input relationship" type="checkbox" id="families" name="families[]"
                                     value="families.work" >
                                    </div>
                                    </div>
                                 </div>
                                 
                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end family info -->
                           <!-- start assurance info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_leave" type="checkbox"> Leave Information</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Year</label>
                                    <div class="form-check">
                                     <input class="form-check-input leave" type="checkbox" id="rs_leave_data" name="rs_leave_data[]"
                                     value="rs_leave_data.year">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Earned Leaves </label>
                                    <div class="form-check">
                                     <input class="form-check-input leave" type="checkbox" id="rs_leave_data" name="rs_leave_data[]"
                                     value="rs_leave_data.earned_leaves" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Refresh Leaves</label>
                                    <div class="form-check">
                                     <input class="form-check-input leave" type="checkbox" id="rs_leave_data" name="rs_leave_data[]"
                                     value="rs_leave_data.earned_leaves" >
                                    </div>
                                    </div>
                                 </div>

                                 
                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end assurance info -->

                         <!-- start education info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_refresh_leave" type="checkbox"> Refresh Leave Information</h3>
                             </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Refresh Leave</label>
                                    <div class="form-check">
                                     <input class="form-check-input refresh_leave" type="checkbox" id="rs_refresh_leaves" name="rs_refresh_leaves[]"
                                     value="rs_refresh_leaves.refresh_leaves" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Earned Leaves  </label>
                                    <div class="form-check">
                                     <input class="form-check-input refresh_leave" type="checkbox" id="rs_refresh_leaves" name="rs_refresh_leaves[]"
                                     value="rs_refresh_leaves.earned_leaves"  >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Other</label>
                                    <div class="form-check">
                                     <input class="form-check-input refresh_leave" type="checkbox" id="rs_refresh_leaves" name="rs_refresh_leaves[]"
                                     value="rs_refresh_leaves.other">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Airfare</label>
                                    <div class="form-check">
                                     <input class="form-check-input refresh_leave" type="checkbox" id="rs_refresh_leaves" name="rs_refresh_leaves[]"
                                     value="rs_refresh_leaves.airfare">
                                    </div>
                                    </div>
                                 </div>
                                  
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Start Date</label>
                                    <div class="form-check">
                                     <input class="form-check-input refresh_leave" type="checkbox" id="rs_refresh_leaves" name="rs_refresh_leaves[]"
                                     value="rs_refresh_leaves.start_date">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">End Date</label>
                                    <div class="form-check">
                                     <input class="form-check-input refresh_leave" type="checkbox" id="rs_refresh_leaves" name="rs_refresh_leaves[]"
                                     value="rs_refresh_leaves.end_date">
                                    </div>
                                    </div>
                                 </div>


                                 
                                  </div>
                              </div>                       
                            
                                  <!-- start -->
                                   <div class="row">
                          <div class="col-md-12 col-sm-12">
                            <div class="form-group text-center">
                               <input type="submit" class="btn btn-primary" value="export" >
                            </div>
                          </div>
                        </div>
                    </form>
                              </div>
                             </div>
                          </div>
                      </div>
                    </div>
                </div>
      <!-- end -->


    </section>
     @endcan

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">RS Employee List</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                      <table class="table table-hover" id="user_record">
                                        <thead>
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Name(English)</th>
                                                <th>Name(Japanese)</th>
                                                <th>Place of Working</th>
                                                <th>Entrance Date</th>
                                                <th>Department</th>
                                                @canany(['rs-edit','rs-delete'])
                                                <th>Action</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  $i=1; ?>
                                           @foreach($users as $user)
                                          <?php
                                             $date = str_replace('-', '/', $user->entranced_date);
                                             $entranced_date = date("d/m/Y", strtotime($date));
                                           ?>
                                            <tr>
                                                <td>{{ $user->employee_id  }}</td>
                                                <td>{{ $user->employee_name }}</td>
                                                <td>{{ $user->employee_name_jp }}</td>
                                                <td>{{ $user->branch_name }}</td>
                                                <td>{{ $entranced_date }}</td>
                                                <td>{{ $user->docname }}</td>
                                                <td>
                                                     @can('edit-rs-record')
                                                    <a href="{{ route('employee.rs-edit',$user->id ) }}">
                                                        <i class="fas fa-edit text-info"></i>
                                                    </a>
                                                    @endcan
                                                   @can('delete-rs-record')
                                                    <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $user->id }}"
                                                        data-name="{{ $user->employee_name }}" data-date="29/10/2022" onclick="addValueForDelete(this)">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </a>
                                                     @endcan
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                            @endforeach
                                        </tbody>

                                    </table>
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
                <div class="modal fade deleteModal" id="modal-delete">
        <div class="modal-dialog">
          <div class="modal-content bg-default">
            <div class="modal-header">
              <h4 class="modal-title">Delete RS Employee</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('users.delete') }}" method="post">
               @csrf
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <p>Are you sure want to delete "<strong></strong>"?</p>
            </div>
            
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success btn-delete">Sure</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</script>
<script type="text/javascript">

            $('#selectAll').click(function() {
              
           $('#employee_name option').prop('selected', true)
           $('#employee_name').select2();
           //$('#employee_name option').attr("selected","selected");
        });
        
        $('#deselectAll').click(function() {
              
           $('#employee_name option').prop('selected', false)
           $('#employee_name').select2();
           //$('#employee_name option').attr("selected","selected");
        });
        
         function addValueForDelete(btn){    
             var id=$(btn).data('id');
            var name=$(btn).data('name');
           $(".deleteModal #id").val(id);
           $(".deleteModal strong").html(name);
        }
        </script>
        <script type="text/javascript">
            $('#all_users').change(function () {
           if ($(this).prop('checked')) {
             $('.users').prop('checked', true);
             }else {
             $('.users').prop('checked', false);
            }
           });
           
           $('#all_contacts').change(function () {
           if ($(this).prop('checked')) {
             $('.contacts').prop('checked', true);
             }else {
             $('.contacts').prop('checked', false);
            }
           });
           
            $('#all_relationship').change(function () {
           if ($(this).prop('checked')) {
             $('.relationship').prop('checked', true);
             }else {
             $('.relationship').prop('checked', false);
            }
           });
           
           //leave
            $('#all_leave').change(function () {
           if ($(this).prop('checked')) {
             $('.leave').prop('checked', true);
             }else {
             $('.leave').prop('checked', false);
            }
           });
           //refresh_leave
            $('#all_refresh_leave').change(function () {
           if ($(this).prop('checked')) {
             $('.refresh_leave').prop('checked', true);
             }else {
             $('.refresh_leave').prop('checked', false);
            }
           });
           
        </script>

 

</section>
@stop