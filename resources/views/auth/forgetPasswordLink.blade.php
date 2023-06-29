<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('dist/img/favicon2.png')}}" type="image/ico" />
    <title>Reset Password - Marubeni</title>

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
                <div class="container-fluid mt-5">
                    {{-- <div class="row">
                         <div class="col-md-4 m-auto">
                            <img class="img-fluid" src="{{asset('dist/img/marubeni.jpg')}}">
                            <h1 style="font-size:24px" class="text-center text-bold">Integrated Personal System</h1>
                         </div>
                    </div> --}}
                    <div class="row m-0">
                        <div class="col-md-4 m-auto">
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
                        <div class="col-md-4 m-auto border border-dark p-5 m-0" style="background-color:#C0C0C0">
                            <form method="POST" action="{{ route('reset.password.post') }}" class="register-form">
                                @csrf
                                <h4 class="m-auto text-danger" style="font-size: 18px;font-weight: bold;">Reset Password </h4>
                                <br>
                                <input type="hidden" name="id" value="{{$user_id}}">
                                <input type="hidden" name="code" value="{{$code}}">
                                <div class="input-group" data-target-input="nearest">
                                    <label for="password" class="col-sm-12">New Password <span class="required text-danger">*</span></label>
                                    <input type="password" name="password" id="password" required class="form-control" />
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-white" id="password-icon" onclick="showPassword()"><i class="fa fa-eye"></i></div>
                                    </div>

                                </div>
                                <br>
                                <div class="input-group" data-target-input="nearest">
                                    <label for="password_confirmation" class="col-sm-12">Confirm Password <span class="required text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-white" id="confirm-password-icon" onclick="showConfirmPassword()"><i class="fa fa-eye"></i></div>
                                    </div>
                                </div>
                                <div class="form-group row p-3">
                                    <div class="col-md-12 m-auto text-right">
                                        <input type="submit" class="btn btn-primary" value="Reset Password">
                                    </div>
                                </div>
                               <small class="text-danger">*** Password must contains at least 12 characters and must contains at least one upper case letter, digits and special character.</small>
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
        $(document).ready(function(){
            $('#name').val('');
            $('#password').val('');
        });
        function showPassword(){
            var className = $("#password-icon i").attr('class');
            var lastClass = $('#password-icon i').attr('class').split(' ').pop();
            
            if(lastClass=="fa-eye"){
                $("#password-icon i").removeClass("fa-eye");
                $("#password-icon i").addClass("fa-eye-slash");
                $("#password").attr("type","text");
            }
            else{
                
                $("#password-icon i").removeClass("fa-eye-slash");
                $("#password-icon i").addClass("fa-eye");
                $("#password").attr('type','password');
            }
        }
        function showConfirmPassword(){
            var className = $("#confirm-password-icon i").attr('class');
            var lastClass = $('#confirm-password-icon i').attr('class').split(' ').pop();
            
            if(lastClass=="fa-eye"){
                $("#confirm-password-icon i").removeClass("fa-eye");
                $("#confirm-password-icon i").addClass("fa-eye-slash");
                $("#password_confirmation").attr("type","text");
            }
            else{
                
                $("#confirm-password-icon i").removeClass("fa-eye-slash");
                $("#confirm-password-icon i").addClass("fa-eye");
                $("#password_confirmation").attr('type','password');
            }
        }
    </script>
</body>

</html>