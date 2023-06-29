@extends('layouts.master')
@section('title','NS Employee List')
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
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="../../pages/mastermanagement/branch-create.html"><i class="fas fa-plus"></i> Add New</a> -->
            <!-- <a class="btn btn-success breadcrumb-btn float-sm-right" href="#" data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i> Add New</a> -->
            <a class="btn btn-success breadcrumb-btn float-sm-right openFilter" href="#"  id="advance_search"><i class="fas fa-search-minus"></i> Advanced Search</a>
            
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
                <form action="{{ route('employee.ns-list') }}" method="post">
                  <div class="row">
                      @csrf
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
                        <select class="form-control select2bs4" name="search_department" id="search_department" style="width: 100%;">
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
                        <a href="{{ route('employee.ns-list') }}" class="btn btn-warning">Reset</a>
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
    
    <!--start excel-->
          <!-- start  -->
          
           @can('ns-export')
      <section  class="content">
        <div class="container-fluid">
                <div class="row">
                   <div class="col-12">
                      <div class="card">
                      
                       <!--start export ns employee -->
                       <div class="card-body">
                          <div class="card card-default collapsed-card basic-info">
                              
                      
                        <div class="card-header">
                          <h3 class="card-title">Export NS Employee</h3>
                          <div class="card-tools">
                           <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                           </button>
                          </div>
                        </div>
                         
                   <div class="card-body">
                   <form method="POST" action="{{ route('employee.ns-export') }}"  enctype="multipart/form-data">
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
                               <h3 class="text-center"><input class="form-check-input mt-2"  id="all_users" type="checkbox">Basic Information</h3>
                            </div>

                               <input id="users" type="hidden" name="users[]"  value="users.id">

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
                                    <label for="status">Entrance Date  </label>
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
                                    <label for="status">Employee Type </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="employee_types.type as employee_type_name">
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
                                    <label for="status">Blood Type</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="users.blood_type">
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
                                    <label for="status">Bank Name (MMK)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="bank_mmk.name as bank_name_mmk_data" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Bank Account (MMK)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="users.bank_account_mmk"  >
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
                                    <label for="status">Grade</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="ns_employees.grade" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">NRC No</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                      value="ns_employees.nrc_no">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Phone </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="users.phone">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Religion </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="ns_employees.religion">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Race </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="ns_employees.race">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Current Address </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="ns_employees.current_address">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">New Address  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox"  id="users" name="users[]"
                                     value="ns_employees.new_address">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">New Phone  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="ns_employees.new_phone">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Other Address  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="ns_employees.others_address">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Other Phone  </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="ns_employees.others_phone">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Employment Contract No </label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]"
                                     value="ns_employees.employment_contract_no">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Hourly Rate</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="ns_employees.hourly_rate">
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
                                    <label for="status">Other Changing Condition</label>
                                    <div class="form-check">
                                     <input class="form-check-input users" type="checkbox" id="users" name="users[]" 
                                     value="users.other_changing_condition">
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

                          <!-- </div>
                        </div> -->

                        <!-- start contact -->
                            <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_contacts" type="checkbox">Contact Information</h3>
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
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_relationship" type="checkbox">family Information</h3>
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
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_assurance" type="checkbox">Life Assurance Information</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Year</label>
                                    <div class="form-check">
                                     <input class="form-check-input assurance" type="checkbox" id="life_assurances" name="life_assurances[]"
                                     value="life_assurances.year">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Premium Amount </label>
                                    <div class="form-check">
                                     <input class="form-check-input assurance" type="checkbox" id="life_assurances" name="life_assurances[]"
                                     value="life_assurances.premium_amount" >
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
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_education" type="checkbox">Education Information</h3>
                             </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Education Type</label>
                                    <div class="form-check">
                                     <input class="form-check-input  education" type="checkbox" id="education" name="education[]"
                                     value="education.education_type" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">School Name  </label>
                                    <div class="form-check">
                                     <input class="form-check-input education" type="checkbox" id="education" name="education[]"
                                     value="education.school_name"  >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Year</label>
                                    <div class="form-check">
                                     <input class="form-check-input education" type="checkbox" id="education" name="education[]"
                                     value="education.date_of_graduation">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Major</label>
                                    <div class="form-check">
                                     <input class="form-check-input education" type="checkbox" id="education" name="education[]"
                                     value="education.major">
                                    </div>
                                    </div>
                                 </div>


                                 
                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end education info -->

                         <!-- start qualification info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_qualification" type="checkbox">Qualification/Certificate Information</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Year</label>
                                    <div class="form-check">
                                     <input class="form-check-input qualification" type="checkbox"  id="qualifications" name="qualifications[]" 
                                     value="qualifications.date_of_acquition">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Certificate</label>
                                    <div class="form-check">
                                     <input class="form-check-input qualification" type="checkbox" id="qualifications" name="qualifications[]"
                                     value="qualifications.certificate" >
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end qualification info -->

                         <!-- start qualification info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                             <h3 class="text-center"><input class="form-check-input mt-2"  id="all_language" type="checkbox">Language</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Language Skill</label>
                                    <div class="form-check">
                                     <input class="form-check-input language" type="checkbox" id="languages" name="languages[]"
                                     value="languages.language_skill" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Level  </label>
                                    <div class="form-check">
                                     <input class="form-check-input language" type="checkbox" id="languages" name="languages[]" 
                                     value="languages.level">
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end qualification info -->

                           <!-- start qualification info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_english" type="checkbox">English Skill</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Type Of English Test</label>
                                    <div class="form-check">
                                     <input class="form-check-input english" type="checkbox" name="english_skills[]" id="english_skills"
                                     value="english_skills.test_type" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Mark  </label>
                                    <div class="form-check">
                                     <input class="form-check-input english" type="checkbox" name="english_skills[]" id="english_skills"
                                     value="english_skills.mark" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Level  </label>
                                    <div class="form-check">
                                     <input class="form-check-input english" type="checkbox" name="english_skills[]" id="english_skills" 
                                     value="english_skills.level">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Year </label>
                                    <div class="form-check">
                                     <input class="form-check-input english" type="checkbox" name="english_skills[]" id="english_skills" 
                                     value="english_skills.date_of_acquition">
                                    </div>
                                    </div>
                                 </div>


                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end qualification info -->

                           <!-- start driver info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                             <h3 class="text-center"><input class="form-check-input mt-2"  id="all_driven_license" type="checkbox">Driver License</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">License Number</label>
                                    <div class="form-check">
                                     <input class="form-check-input driven_license" type="checkbox" name="driver_licenses[]" id="driver_licenses" 
                                     value="driver_licenses.license_number">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Start Date </label>
                                    <div class="form-check">
                                     <input class="form-check-input driven_license" type="checkbox" name="driver_licenses[]" id="driver_licenses"
                                     value="driver_licenses.start_date">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Due Date </label>
                                    <div class="form-check">
                                     <input class="form-check-input driven_license" type="checkbox" name="driver_licenses[]" id="driver_licenses" 
                                     value="driver_licenses.due_date">
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end driver license info -->

                         <!-- start PC Info info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_pc_skill" type="checkbox">PC Skill </h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Microsoft Word</label>
                                    <div class="form-check">
                                     <input class="form-check-input pc_skill" type="checkbox" name="users[]" id="users"
                                     value="ns_employees.microsoft_word">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Microsoft Excel</label>
                                    <div class="form-check">
                                     <input class="form-check-input pc_skill" type="checkbox" name="users[]" id="users" 
                                     value="ns_employees.microsoft_excel">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Microsoft Powerpoint</label>
                                    <div class="form-check">
                                     <input class="form-check-input pc_skill" type="checkbox" name="users[]" id="users"
                                     value="ns_employees.microsoft_powerpoint" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Skill Title</label>
                                    <div class="form-check">
                                     <input class="form-check-input pc_skill" type="checkbox" name="pc_skills[]" id="pc_skills" 
                                      value="pc_skills.title">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Skill Level</label>
                                    <div class="form-check">
                                     <input class="form-check-input pc_skill" type="checkbox" name="pc_skills[]" id="pc_skills"
                                     value="pc_skills.level" >
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end PC Skill  info -->

                        <!-- start employment record info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_employment_record" type="checkbox">Employment Record </h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Company Name</label>
                                    <div class="form-check">
                                     <input class="form-check-input employment_record" type="checkbox" name="employment_records[]" id="employment_records" 
                                     value="employment_records.company_name">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">From</label>
                                    <div class="form-check">
                                     <input class="form-check-input employment_record" type="checkbox" name="employment_records[]" id="employment_records"
                                     value="employment_records.start_date" >
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">To</label>
                                    <div class="form-check">
                                     <input class="form-check-input employment_record" type="checkbox" name="employment_records[]" id="employment_records"
                                     value="employment_records.end_date">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Position </label>
                                    <div class="form-check">
                                     <input class="form-check-input employment_record" type="checkbox" name="employment_records[]" id="employment_records"
                                     value="employment_records.position">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Department</label>
                                    <div class="form-check">
                                     <input class="form-check-input employment_record" type="checkbox" name="employment_records[]" id="employment_records" 
                                     value="employment_records.department">
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end Employment Record  info -->

                        <!-- start Oversea record info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_oversea_record" type="checkbox"> Oversea Record</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">country Name</label>
                                    <div class="form-check">
                                     <input class="form-check-input oversea_record" type="checkbox" name="oversea_records[]" id="oversea_records"
                                     value="oversea_records.country_name">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">From</label>
                                    <div class="form-check">
                                     <input class="form-check-input oversea_record" type="checkbox" name="oversea_records[]" id="oversea_records"
                                     value="oversea_records.start_date">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">To</label>
                                    <div class="form-check">
                                     <input class="form-check-input oversea_record" type="checkbox" name="oversea_records[]" id="oversea_records"
                                     value="oversea_records.end_date">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Purpose</label>
                                    <div class="form-check">
                                     <input class="form-check-input oversea_record" type="checkbox" name="oversea_records[]" id="oversea_records" 
                                     value="oversea_records.purpose">
                                    </div>
                                    </div>
                                 </div>

                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end Oversea Record  info -->

                         <!-- start warning record info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                             <h3 class="text-center"><input class="form-check-input mt-2"  id="all_warning" type="checkbox"> Warning</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Date</label>
                                    <div class="form-check">
                                     <input class="form-check-input warning" type="checkbox" name="warnings[]" id="warnings"
                                     value="warnings.date" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Reason</label>
                                    <div class="form-check">
                                     <input class="form-check-input warning" type="checkbox" name="warnings[]" id="warnings"
                                     value="warnings.reason">
                                    </div>
                                    </div>
                                 </div>


                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end warning Record  info -->

                        <!-- start Retirement record info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_retirement" type="checkbox"> Retirement</h3>                              
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Date</label>
                                    <div class="form-check">
                                     <input class="form-check-input retirement" type="checkbox" name="users[]" id="users"
                                     value="retirements.date as retirement_date">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Reason</label>
                                    <div class="form-check">
                                     <input class="form-check-input retirement" type="checkbox" name="users[]" id="users"
                                     value="retirements.reason as retirement_reason" >
                                    </div>
                                    </div>
                                 </div>


                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end Retirement Record  info -->

                           <!-- start other record info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                             <h3 class="text-center"><input class="form-check-input mt-2"  id="all_other" type="checkbox"> Other</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Interest</label>
                                    <div class="form-check">
                                     <input class="form-check-input other" type="checkbox" name="users[]" id="users"
                                     value="others.interest" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Strong Point</label>
                                    <div class="form-check">
                                     <input class="form-check-input other" type="checkbox" name="users[]" id="users"
                                     value="others.strong_point">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Weak Point</label>
                                    <div class="form-check">
                                     <input class="form-check-input other" type="checkbox" name="users[]" id="users"
                                     value="others.weak_point">
                                    </div>
                                    </div>
                                 </div>


                                  </div>
                              </div>
                          <!-- </div>
                        </div> -->
                        <!-- end other Record  info -->

                         <!-- start other record info -->
                          <!-- <div class="card-body">
                          <div class="card card-default collapsed-card basic-info"> -->

                            <div class="card-header">
                              <h3 class="text-center"><input class="form-check-input mt-2"  id="all_evaluation" type="checkbox"> Performance Evaluation</h3>
                            </div>

                              <div class="card-body">
                                  <div class="row">
                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Year</label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.year">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Band</label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.grade" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Title</label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.title">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Position</label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.position">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Competency</label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.competency" >
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Performance </label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.performance">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Salary(Net Pay)  </label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.net_pay">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Basic Salary </label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations" 
                                     value="evaluations.basic_salary">
                                    </div>
                                    </div>
                                 </div>

                                 <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">Duty Allowance </label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.allowance">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status">OT Rate </label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"                                     
                                     value="evaluations.ot_rate">
                                    </div>
                                    </div>
                                 </div>

                                  <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status"> Bonus (Water Festival) </label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.water_festival_bonus">
                                    </div>
                                    </div>
                                 </div>

                                   <div class="col-md-2 col-sm-2">
                                    <div class="form-group">
                                    <label for="status"> 	Bonus (Thadingyut) </label>
                                    <div class="form-check">
                                     <input class="form-check-input evaluation" type="checkbox" name="evaluations[]" id="evaluations"
                                     value="evaluations.thadingyut_bonus">
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
    <!--end excel-->
   @endcan
     
     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">NS Employee List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="user_record" class="table table-hover">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Employee ID</th>
                      <th>Employee Name</th>
                      <th>Place of Working</th>
                      <th>Department</th>
                      <th>Entrance Date</th>
                      @canany(['ns-edit','ns-delete'])
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
                        <td>{{ $i }}</td>
                        <td>{{ $user->employee_id  }}</td>
                        <td>{{ $user->employee_name }}</td>
                        <td>{{ $user->branch_name }}</td>
                        <td>{{ $user->docname }}</td>
                        <td>{{ $entranced_date }}</td>
                        <td>
                          @can('edit-ns-record')
                        <a href="{{ route('employee.ns-edit',$user->id ) }}">
                          <i class="fas fa-edit text-info"></i>
                        </a>
                        @endcan
                         @can('delete-ns-record')
                        <a href="#" data-toggle="modal" class="delete-modal" data-target="#modal-delete" data-id="{{ $user->id  }}" data-name="{{ $user->employee_name }}"  onclick="addValueForDelete(this)">
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
              <h4 class="modal-title">Delete NS Employee</h4>
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
           $('#all_assurance').change(function () {
           if ($(this).prop('checked')) {
             $('.assurance').prop('checked', true);
             }else {
             $('.assurance').prop('checked', false);
            }
           });
           
            $('#all_education').change(function () {
           if ($(this).prop('checked')) {
             $('.education').prop('checked', true);
             }else {
             $('.education').prop('checked', false);
            }
           });
           
            $('#all_qualification').change(function () {
           if ($(this).prop('checked')) {
             $('.qualification').prop('checked', true);
             }else {
             $('.qualification').prop('checked', false);
            }
           });
           
            $('#all_language').change(function () {
           if ($(this).prop('checked')) {
             $('.language').prop('checked', true);
             }else {
             $('.language').prop('checked', false);
            }
           });
           
           $('#all_english').change(function () {
           if ($(this).prop('checked')) {
             $('.english').prop('checked', true);
             }else {
             $('.english').prop('checked', false);
            }
           });
           $('#all_driven_license').change(function () {
             if ($(this).prop('checked')) {
             $('.driven_license').prop('checked', true);
             }else {
             $('.driven_license').prop('checked', false);
            }
           });
           $('#all_pc_skill').change(function () {
            if ($(this).prop('checked')) {
              $('.pc_skill').prop('checked', true);
             }else {
               $('.pc_skill').prop('checked', false);
             }
           });
            $('#all_employment_record').change(function () {
            if ($(this).prop('checked')) {
              $('.employment_record').prop('checked', true);
             }else {
               $('.employment_record').prop('checked', false);
             }
           });
           
            $('#all_oversea_record').change(function () {
            if ($(this).prop('checked')) {
              $('.oversea_record').prop('checked', true);
            }else {
              $('.oversea_record').prop('checked', false);
            }
           });
            $('#all_warning').change(function () {
            if ($(this).prop('checked')) {
              $('.warning').prop('checked', true);
            }else {
              $('.warning').prop('checked', false);
            }
           });
           
            $('#all_retirement').change(function () {
            if ($(this).prop('checked')) {
              $('.retirement').prop('checked', true);
            }else {
              $('.retirement').prop('checked', false);
            }
           });
           
           $('#all_other').change(function () {
            if ($(this).prop('checked')) {
              $('.other').prop('checked', true);
            }else {
              $('.other').prop('checked', false);
            }
           });
           
           
           $('#all_evaluation').change(function () {
            if ($(this).prop('checked')) {
              $('.evaluation').prop('checked', true);
            }else {
              $('.evaluation').prop('checked', false);
            }
           });
           
           
           
           
           
           
           
           
           
        </script>
</section>
@stop