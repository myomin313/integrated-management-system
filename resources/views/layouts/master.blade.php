<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{asset('dist/img/favicon2.png')}}" type="image/ico" />
  <title>@yield('title') - Marubeni</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

  <!-- <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.min.css')}}"> -->
  <link href="{{asset('plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet">


  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">

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
  <link rel="stylesheet" href="{{asset('dist/css/print.css')}}" media="print" type="text/css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
  @yield("style")
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="loading-overlay" class="ui-widget-overlay" style="z-index: 1001; display: none;"></div>
  <div class='loading-overlay-image-container' style='display: none;'><img class='loading-overlay-img' src="{{asset('dist/img/spinner.gif')}}"/></div>
  
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    @include('layouts.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('layouts.sidebar')
    <!-- Main Sidebar Container -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background: #f4f6f9;">
      
      @yield('content')

    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    @include('layouts.footer')
    <!-- Footer -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
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
  <!-- Bootstrap4 Duallistbox -->
  <script src="{{asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>

  <!-- DataTables  & Plugins -->
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

  <script src="{{asset('plugins/inputmask/jquery.inputmask.min.js')}}"></script>
  <!-- bootstrap color picker -->
  <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
  <!-- Bootstrap Switch -->
  <script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
  <!-- BS-Stepper -->
  <script src="{{asset('plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
  <!-- dropzonejs -->
  <script src="{{asset('plugins/dropzone/min/dropzone.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('dist/js/adminlte.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('dist/js/demo.js')}}"></script>
   <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{asset('dist/js/custom.js')}}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
  @yield('script')
  <script>
    
     // start
  $(function () {
    var car_table = $('#cars_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": false,
      });
  }); 
   $(document).ready(function(){
     var leave_table = $('#leave_record').DataTable({
          "paging": true,
          "lengthChange": false,
          "pageLength": 15,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": false,
      });
   });
    $(document).ready(function(){
       var user_table = $('#user_record').DataTable({
           "paging": true,
           "lengthChange": false,
           "pageLength": 15,
           "searching": false,
           "ordering": true,
           "info": true,
           "autoWidth": false,
           "responsive": false,
        });
    });
     
  // end
  
    $(function () {

      $('.prevent-multiple-submit').on('submit', function(){
        
            $(".loading-overlay, .loading-overlay-image-container").show();
            $("#mysidebar").css("z-index",0);
            $("#mynavbar").css("z-index",0);

            return true;

           
      });
      $('.prevent-multiple-submit-modal').on('submit', function(){
            $('.modal').css({'z-index':0});
            $(".loading-overlay, .loading-overlay-image-container").show();
            $("#mysidebar").css("z-index",0);
            $("#mynavbar").css("z-index",0);
            return true;

           
      });
      //Date picker
      $('#datetimepicker').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      
       $('#datetimepicker2').datetimepicker({
           format: 'DD/MM/YYYY'
      });
      $('#datetimepicker3').datetimepicker({
           format: 'DD/MM/YYYY'
      });
      $('#datetimepicker4').datetimepicker({
           format: 'DD/MM/YYYY'
      });
       $('#datetimepicker5').datetimepicker({
           format: 'DD/MM/YYYY'
      });
      $('#datetimepickerSearch').datetimepicker({
          format: 'DD/MM/YYYY'
      });
       $('#datetimepickerSearch2').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      //start
      $('#datetimepickerExcel').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepickerExcel2').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepickerExcel3').datetimepicker({
          format: 'MM/YYYY'
      });
      $('#datetimepickerExcel4').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepickerExcel5').datetimepicker({
          format: 'DD/MM/YYYY'
      });

      $('#datetimepickerExcel6').datetimepicker({
         format: 'MM/YYYY',
      });
      //end
      $('#datetimepickeredit').datetimepicker({
          format: 'DD/MM/YYYY'
      });
       $('#datetimepickeredit2').datetimepicker({
         format: 'DD/MM/YYYY'
      });

      $('#datetimepicker1').datetimepicker({
          format: 'DD/MM/YYYY hh:mm:ss A'
      });

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#dataTables').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "responsive": true,
      });
      
      
       $(document).on('click','#advance_search',function(){
              var className = $('#advance_search').attr('class');
              var lastClass = $('#advance_search').attr('class').split(' ').pop();
              console.log('class name '+ className);
              console.log('lastClass '+ lastClass);
              if(lastClass=='closeFilter'){
                  $('#advance_search').removeClass('closeFilter');
                  $('#advance_search').addClass('openFilter');
                  $('.filter-row').removeClass('hide-view');
                  $('#advance_search .fas').removeClass('fa-search-plus');
                  $('#advance_search .fas').addClass('fa-search-minus');
              }
              else{
                  $('#advance_search').removeClass('openFilter');
                  $('#advance_search').addClass('closeFilter');
                  $('.filter-row').addClass('hide-view');
                  $('#advance_search .fas').removeClass('fa-search-minus');
                  $('#advance_search .fas').addClass('fa-search-plus');
              }
              
          });
          // advance search 2
           $(document).on('click','#advance_search2',function(){
              var className = $('#advance_search2').attr('class');
              var lastClass = $('#advance_search2').attr('class').split(' ').pop();
              console.log('class name '+ className);
              console.log('lastClass '+ lastClass);
              if(lastClass=='closeFilter2'){
                  $('#advance_search2').removeClass('closeFilter2');
                  $('#advance_search2').addClass('openFilter2');
                  $('.filter-row2').removeClass('hide-view');
                  $('#advance_search2 .fas').removeClass('fa-search-plus');
                  $('#advance_search2 .fas').addClass('fa-search-minus');
              }
              else{
                  $('#advance_search2').removeClass('openFilter2');
                  $('#advance_search2').addClass('closeFilter2');
                  $('.filter-row2').addClass('hide-view');
                  $('#advance_search2 .fas').removeClass('fa-search-minus');
                  $('#advance_search2 .fas').addClass('fa-search-plus');
              }
              
          });

          // advance search 3
           $(document).on('click','#advance_search3',function(){
              var className = $('#advance_search3').attr('class');
              var lastClass = $('#advance_search3').attr('class').split(' ').pop();
              console.log('class name '+ className);
              console.log('lastClass '+ lastClass);
              if(lastClass=='closeFilter3'){
                  $('#advance_search3').removeClass('closeFilter3');
                  $('#advance_search3').addClass('openFilter3');
                  $('.filter-row3').removeClass('hide-view');
                  $('#advance_search3 .fas').removeClass('fa-search-plus');
                  $('#advance_search3 .fas').addClass('fa-search-minus');
              }
              else{
                  $('#advance_search3').removeClass('openFilter3');
                  $('#advance_search3').addClass('closeFilter3');
                  $('.filter-row3').addClass('hide-view');
                  $('#advance_search3 .fas').removeClass('fa-search-minus');
                  $('#advance_search3 .fas').addClass('fa-search-plus');
              }
              
          });
          
          // advance search 4
            $(document).on('click','#advance_search4',function(){
              var className = $('#advance_search4').attr('class');
              var lastClass = $('#advance_search4').attr('class').split(' ').pop();
              console.log('class name '+ className);
              console.log('lastClass '+ lastClass);
              if(lastClass=='closeFilter4'){
                  $('#advance_search4').removeClass('closeFilter4');
                  $('#advance_search4').addClass('openFilter4');
                  $('.filter-row4').removeClass('hide-view');
                  $('#advance_search4 .fas').removeClass('fa-search-plus');
                  $('#advance_search4 .fas').addClass('fa-search-minus');
              }
              else{
                  $('#advance_search4').removeClass('openFilter4');
                  $('#advance_search4').addClass('closeFilter4');
                  $('.filter-row4').addClass('hide-view');
                  $('#advance_search4 .fas').removeClass('fa-search-minus');
                  $('#advance_search4 .fas').addClass('fa-search-plus');
              }

          });

      

      

    });


   
  </script>
</body>
