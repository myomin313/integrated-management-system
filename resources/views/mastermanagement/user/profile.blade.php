<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('dist/img/favicon2.png')}}" type="image/ico" />
    <title>Profile - Marubeni</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">


    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/custom.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">


  
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60"
                width="60">
        </div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid mt-5" style="margin-top: 20px !important;">
                    {{-- <div class="row">
                         <div class="col-md-4 m-auto">
                            <img class="img-fluid" src="{{asset('dist/img/marubeni.jpg')}}">
                            <h1 style="font-size:24px" class="text-center text-bold">Integrated Personal System</h1>
                         </div>
                    </div> --}}
                    <div class="row m-0">
                        <div class="col-md-8 m-auto">
                            @if(count($errors)>0)
                                <div class="col-md-12 p-0">
                                    <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{$error}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            @if(session('current_password'))
                              <div class="col-md-12 p-0">
                                <div class="alert alert-warning alert-dismissible " role="alert" style="font-size: 12px">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                  </button>
                                  <strong>{{session('current_password')}}</strong>
                                </div>
                              </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 m-auto border border-dark p-5 m-0" style="background-color:#C0C0C0;padding-top: 20px !important;">
                            <form method="POST" action="{{ route('user.update-profile') }}" class="register-form">
                                @csrf
                                <h4 class="m-auto text-center text-danger" style="font-weight: bold;">Please fill your profile</h4><br>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="profile_id">Fingerprint Profile <span class="required text-danger"></span></label>
                                            <select class="form-control select2bs4" name="profile_id" id="profile_id" style="width: 100%;">
                                                <option value="" selected="selected" value="0">- Select -</option>
                                                @foreach($fig_profiles as $key=>$value)
                                                <option value="{{$value->pro_id}}" {{$value->pro_id==$user->profile_id?'selected':''}}>{{$value->pro_UserName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="employee_name">Name <span class="required text-danger">*</span></label>
                                            <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{$user->employee_name}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="dob">DOB <span class="required text-danger">*</span></label>
                                            <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                                                <input type="text" name="dob" id="dob" required placeholder="dd/mm/YYYY" value="{{siteformat_date($user->dob)}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                                                <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="entranced_date">Entranced Date <span class="required text-danger">*</span></label>
                                            <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                                <input type="text" name="entranced_date" id="entranced_date" placeholder="dd/mm/YYYY" value="{{siteformat_date($user->entranced_date)}}" required class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                                                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="personl_email">Personal Email (if)</label>
                                            <input type="text" class="form-control" id="personal_email" name="email" value="{{$user->personal_email}}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="employee_type_id">Employee Type <span class="required text-danger">*</span></label>
                                            <select class="form-control select2bs4" name="employee_type_id" id="employee_type_id" style="width: 100%;" required>
                                                <option value="" selected="selected">- Select -</option>
                                                @foreach($employee_types as $key=>$value)
                                                <option value="{{$value->id}}" {{$user->employee_type_id==$value->id?'selected':''}}>{{$value->type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                        
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="branch_id">Branch <span class="required text-danger">*</span></label>
                                            <select class="form-control select2bs4" name="branch_id" id="branch_id" style="width: 100%;" required>
                                                <option value="" selected="selected">- Select -</option>
                                                @foreach($branches as $key=>$value)
                                                <option value="{{$value->id}}" {{$user->branch_id==$value->id?'selected':''}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="department_id">Department <span class="required text-danger">*</span></label>
                                            <select class="form-control select2bs4" name="department_id" id="department_id" style="width: 100%;" required>
                                                <option value="" selected="selected">- Select -</option>
                                                @foreach($departments as $key=>$value)
                                                <option value="{{$value->id}}" {{$user->department_id==$value->id?'selected':''}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="position_id">Position <span class="required text-danger"></span></label>
                                            <select class="form-control select2bs4" name="position_id" id="position_id" style="width: 100%;">
                                                <option value="0" selected="selected">- Select -</option>
                                                @foreach($positions as $key=>$value)
                                                <option value="{{$value->id}}" {{$user->position_id==$value->id?'selected':''}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="col-md-4 col-sm-3">
                                        <div class="form-group">
                                            <label for="working_day_type">Working Day Type <span class="required text-danger">*</span></label>
                                            <div class="form-check">

                                                <input class="form-check-input" type="radio" name="working_day_type" value="full" checked>
                                                <label class="form-check-label">Full Day</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input class="form-check-input" type="radio" name="working_day_type" value="half">
                                                <label class="form-check-label">Half Day</label>
                                            </div>
                                        </div>
                                    </div>
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
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="working_start_time">Working Start Time <span class="required text-danger">*</span></label>
                                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                                <input type="text" name="working_start_time" id="working_start_time" value="{{$user->working_start_time?$user->working_start_time:''}}" placeholder="hh:mm AM" required class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="working_end_time">Working End Time <span class="required text-danger">*</span></label>
                                            <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                                <input type="text" name="working_end_time" id="working_end_time" value="{{$user->working_end_time?$user->working_end_time:''}}" placeholder="hh:mm PM" required class="form-control datetimepicker-input" data-target="#datetimepicker3"/>
                                                <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label for="working_day_per_week">Working Day per Week <span class="required text-danger">*</span></label>
                                            <input type="text" class="form-control" id="working_day_per_week" name="working_day_per_week" value="{{$user->working_day_per_week}}" onkeypress="return isNumberKey(event)" onchange="return isNumberKey(event)" autocomplete="off" required>
                                            <small class="text-danger working-day"></small>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                                <div class="row">
                                 <div class="col-md-12 col-sm-12">
                                   <label for="branch">Departments <span class="required text-danger">*</span></label>
                                 </div>
                                 @php
                                  $depart = explode(",", $user->department_id);
                                 
                                 @endphp
                                 @foreach($departments as $department) 
                                  <div class="col-md-6 col-sm-4">
                                    <div class="form-group">                                 
                                     <input type="checkbox" id="department_id" name="department_id[]" <?= (in_array($department->id, $depart)?'checked="checked"':NULL) ?>  value="{{ old('department_id',$department->id)}}">
                                     <label for="department_id">{{  $department->name }}  ({{ $department->short_name }})</label>
                                    </div>
                                  </div>                              
                                 @endforeach
                                 <span class="text-danger error-text department_id_error"></span>
                                </div>                     
                                <div class="form-group row p-3">
                                    <div class="col-md-12 m-auto text-right">
                                        <input type="submit" class="btn btn-success" value="Save">
                                        @if($user->profile_add==1)
                                        <a href="{{url('/')}}" class="btn btn-primary">Cancel</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row text-danger">
                                    <p>
                                        <strong>***Note</strong>
                                        <br>
                                        <strong>If you use the finger print device to check in attendance, you need to fill the Fingerprint Profile. Otherwise, no need to fill.</strong>
                                    </p>
                                </div>
                               
                            </form>
                            <!-- end -->
                          
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->

            </section>
            <!-- /.content -->
        </div>
       
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- ChartJS -->
    <!-- Sparkline -->
    <script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
    <!-- daterangepicker -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <!-- Summernote -->
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

    <!-- DataTables & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            //Date picker
            $('#datetimepicker').datetimepicker({
                format: 'DD/MM/YYYY'
            });

            $('#datetimepicker1').datetimepicker({
                format: 'DD/MM/YYYY'
            });

            $('#datetimepicker2').datetimepicker({
                format: 'HH:mm'
            });
            $('#datetimepicker3').datetimepicker({
                format: 'HH:mm'
            });

            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })

    

  });

        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            console.log(charCode);
            if (charCode > 31 && (charCode < 45 || charCode > 57)){
                $("#working_day_per_week").css("border","2px solid red");
                $("small.working-day").html("Please fill valid number");
                //$("#working_day_per_week").val("");
                return false;
            }
            else{
                $("#working_day_per_week").css("border","none");
                $("small.working-day").html("");
            }
            return true;
        }
    </script>
</body>

</html>