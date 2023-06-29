@extends('layouts.master')
@section('title','Annual Detucted & Paid Personal')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/annual-tax/deducted-paid-personal-report')}}">Detucted & Paid Personal</a></li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-4 text-right">
            
            <a class="btn btn-default breadcrumb-btn openFilter" href="#" id="advance_search">
              <i class="fas fa-search-minus"></i> Advanced Search</a>
                        
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content filter-row">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
            <div class="card">
              <div class="card-header">
                <form action="{{url('tax-management/annual-tax/deducted-paid-personal-report')}}" method="get">
                  
                  <div class="row">
                    
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="YYYY" value="{{$from_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('tax-management/annual-tax/deducted-paid-personal-report')}}" class="btn btn-warning text-white">Reset</a>
                          
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
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h3 class="card-title">Annual Deducted & Paid Personal</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('tax.paid-personal-download',['from_date'=>$from_date])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Name</th>
                      <th>Salary</th>
                      <th>Band</th>
                      <th>Total I-Tax for {{$first_year}} - {{$last_year}}<br>(MMK)</th>
                      <th>Total Tax Payable<br>(USD)</th>
                      <th></th>
                      <th colspan="2">Apr</th>
                      <th colspan="2">May</th>
                      <th colspan="2">Jun</th>
                      <th colspan="2">Jul</th>
                      <th colspan="2">Aug</th>
                      <th colspan="2">Sep</th>
                      <th colspan="2">Oct</th>
                      <th colspan="2">Nov</th>
                      <th colspan="2">Dec</th>
                      <th colspan="2">Jan</th>
                      <th colspan="2">Feb</th>
                      <th colspan="2">Mar</th>
                      <th>Total Deducted Estd Tax {{$first_year}} - {{$last_year}}</th>
                      <th colspan="2">Total Tax Paid {{$first_year}} - {{$last_year}}</th>
                      <th>Tax Balance for {{$first_year}} - {{$last_year}}</th>
                      
                      <th>Tax Refund for {{$first_year}} - {{$last_year}}</th>
                      <th>Paid by Bank Exchange Rate</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $i = 1;
                      $total_year_income_tax = 0;
                      $total_tax_payable = 0;
                      $estimated_apr = 0;
                      $estimated_may = 0;
                      $estimated_jun = 0;
                      $estimated_jul = 0;
                      $estimated_aug = 0;
                      $estimated_sep = 0;
                      $estimated_oct = 0;
                      $estimated_nov = 0;
                      $estimated_dec = 0;
                      $estimated_jan = 0;
                      $estimated_feb = 0;
                      $estimated_mar = 0;
                      $actual_apr_usd = 0;
                      $actual_may_usd = 0;
                      $actual_jun_usd = 0;
                      $actual_jul_usd = 0;
                      $actual_aug_usd = 0;
                      $actual_sep_usd = 0;
                      $actual_oct_usd = 0;
                      $actual_nov_usd = 0;
                      $actual_dec_usd = 0;
                      $actual_jan_usd = 0;
                      $actual_feb_usd = 0;
                      $actual_mar_usd = 0;
                      $actual_apr_mmk = 0;
                      $actual_may_mmk = 0;
                      $actual_jun_mmk = 0;
                      $actual_jul_mmk = 0;
                      $actual_aug_mmk = 0;
                      $actual_sep_mmk = 0;
                      $actual_oct_mmk = 0;
                      $actual_nov_mmk = 0;
                      $actual_dec_mmk = 0;
                      $actual_jan_mmk = 0;
                      $actual_feb_mmk = 0;
                      $actual_mar_mmk = 0;

                      $total_deducted_tax = 0;
                      $total_tax_paid_usd = 0;
                      $total_tax_paid_mmk = 0;
                      $total_tax_refund = 0;
                      $total_paid_mmk = 0;
                    @endphp
                    @foreach($taxes as $user_id=>$tax)
                      <tr>
                        <td rowspan="4">{{$i}}</td>
                        <td rowspan="4">{{getUserFieldWithId($user_id,"employee_name")}}</td>
                        @php
                          $salary = \App\Helpers\TaxHelper::getTotalSalaryForNs($user_id,$from_date);

                        @endphp
                        <td rowspan="4">{{$salary}}</td>
                        <td rowspan="4"></td>
                        <td rowspan="4"></td>
                        <td rowspan="4"></td>
                        <td></td>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <th colspan="2">USD</th>
                        <!-- total estimated-->

                        <th>USD</th>

                        <!-- actual estimated-->
                        <th></th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                        @php
                          $total_estimated_tax = 0;
                          $total_actual_tax_usd = 0;
                          $total_actual_tax_mmk = 0;
                        @endphp
                        @foreach($tax as $key=>$value)

                          @if($key=="estimated")
                          
                            
                          <tr>
                            <td>{{$key}}</td>
                            <td colspan="2">{{$value["usd"][0]}}</td>
                            <td colspan="2">{{$value["usd"][1]}}</td>
                            <td colspan="2">{{$value["usd"][2]}}</td>
                            <td colspan="2">{{$value["usd"][3]}}</td>
                            <td colspan="2">{{$value["usd"][4]}}</td>
                            <td colspan="2">{{$value["usd"][5]}}</td>
                            <td colspan="2">{{$value["usd"][6]}}</td>
                            <td colspan="2">{{$value["usd"][7]}}</td>
                            <td colspan="2">{{$value["usd"][8]}}</td>
                            <td colspan="2">{{$value["usd"][9]}}</td>
                            <td colspan="2">{{$value["usd"][10]}}</td>
                            <td colspan="2">{{$value["usd"][11]}}</td>
                            @php
                              $total_estimated_tax = $value["usd"][0] + $value["usd"][1] + $value["usd"][2] + $value["usd"][3] + $value["usd"][4] + $value["usd"][5] + $value["usd"][6] + $value["usd"][7] + $value["usd"][8] + $value["usd"][9] + $value["usd"][10] + $value["usd"][11];
                            @endphp
                            <td>{{$total_estimated_tax}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @php
                              $estimated_apr += $value["usd"][0];
                              $estimated_may += $value["usd"][1];
                              $estimated_jun += $value["usd"][2];
                              $estimated_jul += $value["usd"][3];
                              $estimated_aug += $value["usd"][4];
                              $estimated_sep += $value["usd"][5];
                              $estimated_oct += $value["usd"][6];
                              $estimated_nov += $value["usd"][7];
                              $estimated_dec += $value["usd"][8];
                              $estimated_jan += $value["usd"][9];
                              $estimated_feb += $value["usd"][10];
                              $estimated_mar += $value["usd"][11];

                              $total_deducted_tax += $total_estimated_tax;
                            @endphp
                          </tr>
                          @else
                          <tr>
                            <td></td>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th>USD</th>
                            <th>MMK</th>

                            <th></th>
                            <th>USD</th>
                            <th>MMK</th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                          <tr>
                            <td>{{$key}}</td>
                            <td>{{$value["usd"][0]}}</td>
                            <td>{{$value["mmk"][0]}}</td>
                            <td>{{$value["usd"][1]}}</td>
                            <td>{{$value["mmk"][1]}}</td>
                            <td>{{$value["usd"][2]}}</td>
                            <td>{{$value["mmk"][2]}}</td>
                            <td>{{$value["usd"][3]}}</td>
                            <td>{{$value["mmk"][3]}}</td>
                            <td>{{$value["usd"][4]}}</td>
                            <td>{{$value["mmk"][4]}}</td>
                            <td>{{$value["usd"][5]}}</td>
                            <td>{{$value["mmk"][5]}}</td>
                            <td>{{$value["usd"][6]}}</td>
                            <td>{{$value["mmk"][6]}}</td>
                            <td>{{$value["usd"][7]}}</td>
                            <td>{{$value["mmk"][7]}}</td>
                            <td>{{$value["usd"][8]}}</td>
                            <td>{{$value["usd"][8]}}</td>
                            <td>{{$value["mmk"][9]}}</td>
                            <td>{{$value["mmk"][9]}}</td>
                            <td>{{$value["usd"][10]}}</td>
                            <td>{{$value["mmk"][10]}}</td>
                            <td>{{$value["usd"][11]}}</td>
                            <td>{{$value["mmk"][11]}}</td>
                            <th></th>
                            @php
                              $total_actual_tax_usd = $value["usd"][0] + $value["usd"][1] + $value["usd"][2] + $value["usd"][3] + $value["usd"][4] + $value["usd"][5] + $value["usd"][6] + $value["usd"][7] + $value["usd"][8] + $value["usd"][9] + $value["usd"][10] + $value["usd"][11];
                              $total_actual_tax_mmk = $value["mmk"][0] + $value["mmk"][1] + $value["mmk"][2] + $value["mmk"][3] + $value["mmk"][4] + $value["mmk"][5] + $value["mmk"][6] + $value["mmk"][7] + $value["mmk"][8] + $value["mmk"][9] + $value["mmk"][10] + $value["mmk"][11];

                              $tax_balance = $total_estimated_tax - $total_actual_tax_usd;

                              $total_tax_paid_usd += $total_actual_tax_usd;
                              $total_tax_paid_mmk += $total_actual_tax_mmk;

                              $total_tax_refund += $tax_balance;
                            @endphp
                            <td>{{$total_actual_tax_usd}}</td>
                            <td>{{$total_actual_tax_mmk}}</td>
                            <th>{{$tax_balance}}</th>
                            <th>{{$tax_balance}}</th>
                            <th></th>
                            @php
                              $actual_apr_usd += $value["usd"][0];
                              $actual_apr_mmk += $value["mmk"][0];
                              $actual_may_usd += $value["usd"][1];
                              $actual_may_mmk += $value["mmk"][1];
                              $actual_jun_usd += $value["usd"][2];
                              $actual_jun_mmk += $value["mmk"][2];
                              $actual_jul_usd += $value["usd"][3];
                              $actual_jul_mmk += $value["mmk"][3];
                              $actual_aug_usd += $value["usd"][4];
                              $actual_aug_mmk += $value["mmk"][4];
                              $actual_sep_usd += $value["usd"][5];
                              $actual_sep_mmk += $value["mmk"][5];
                              $actual_oct_usd += $value["usd"][6];
                              $actual_oct_mmk += $value["mmk"][6];
                              $actual_nov_usd += $value["usd"][7];
                              $actual_nov_mmk += $value["mmk"][7];
                              $actual_dec_usd += $value["usd"][8];
                              $actual_dec_mmk += $value["mmk"][8];
                              $actual_jan_usd += $value["usd"][9];
                              $actual_jan_mmk += $value["mmk"][9];
                              $actual_feb_usd += $value["usd"][10];
                              $actual_feb_mmk += $value["mmk"][10];
                              $actual_mar_usd += $value["usd"][11];
                              $actual_mar_mmk += $value["mmk"][11];
                            @endphp
                          </tr>
                          @endif
                          
                        @endforeach
                       

                      @php
                        $i += 1;
                      @endphp

                    @endforeach
                      
                  </tbody>
                  <tfoot>
                    <tr class="footer-border-top">
                      <th rowspan="4"></th>
                      <th colspan="3" rowspan="4">Total</th>
                      <th rowspan="4">{{$total_year_income_tax}}</th>
                      <th rowspan="4">{{$total_tax_payable}}</th>
                      <th></th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th colspan="2">USD</th>
                      <th>USD</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th>estimated</th>
                      <th colspan="2">{{$estimated_apr}}</th>
                      <th colspan="2">{{$estimated_may}}</th>
                      <th colspan="2">{{$estimated_jun}}</th>
                      <th colspan="2">{{$estimated_jul}}</th>
                      <th colspan="2">{{$estimated_aug}}</th>
                      <th colspan="2">{{$estimated_sep}}</th>
                      <th colspan="2">{{$estimated_oct}}</th>
                      <th colspan="2">{{$estimated_nov}}</th>
                      <th colspan="2">{{$estimated_dec}}</th>
                      <th colspan="2">{{$estimated_jan}}</th>
                      <th colspan="2">{{$estimated_feb}}</th>
                      <th colspan="2">{{$estimated_mar}}</th>
                      <th>{{$total_deducted_tax}}</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                    <tr class="footer-border-top">
                      
                      <th></th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMk</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th></th>
                      <th>USD</th>
                      <th>MMK</th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                    <tr>
                      
                      <th>actual</th>
                      <th>{{$actual_apr_usd}}</th>
                      <th>{{$actual_apr_mmk}}</th>
                      <th>{{$actual_may_usd}}</th>
                      <th>{{$actual_may_mmk}}</th>
                      <th>{{$actual_jun_usd}}</th>
                      <th>{{$actual_jun_mmk}}</th>
                      <th>{{$actual_jul_usd}}</th>
                      <th>{{$actual_jul_mmk}}</th>
                      <th>{{$actual_aug_usd}}</th>
                      <th>{{$actual_aug_mmk}}</th>
                      <th>{{$actual_sep_usd}}</th>
                      <th>{{$actual_sep_mmk}}</th>
                      <th>{{$actual_oct_usd}}</th>
                      <th>{{$actual_oct_mmk}}</th>
                      <th>{{$actual_nov_usd}}</th>
                      <th>{{$actual_nov_mmk}}</th>
                      <th>{{$actual_dec_usd}}</th>
                      <th>{{$actual_dec_mmk}}</th>
                      <th>{{$actual_jan_usd}}</th>
                      <th>{{$actual_jan_mmk}}</th>
                      <th>{{$actual_feb_usd}}</th>
                      <th>{{$actual_feb_mmk}}</th>
                      <th>{{$actual_mar_usd}}</th>
                      <th>{{$actual_mar_mmk}}</th>
                      <th></th>
                      <th>{{$total_tax_paid_usd}}</th>
                      <th>{{$total_tax_paid_mmk}}</th>
                      <th>{{$total_tax_refund}}</th>
                      <th>{{$total_tax_refund}}</th>
                      <th></th>
                    </tr>
                  </tfoot>
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

    </section>
    <!-- /.content -->

@stop
@section('script')
<script>
    $(function () {
      $('#datetimepicker').datetimepicker({
          format: 'YYYY'
      });
      $('#datetimepicker1').datetimepicker({
          format: 'MM/YYYY'
      });
      $('#datetimepicker2').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('#datetimepicker3').datetimepicker({
          format: 'DD/MM/YYYY'
      });
      $('.select2').select2();
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

      $(document).on('click', '#advance_search', function () {
        var className = $('#advance_search').attr('class');
        var lastClass = $('#advance_search').attr('class').split(' ').pop();
        console.log('class name ' + className);
        console.log('lastClass ' + lastClass);
        if (lastClass == 'closeFilter') {
          $('#advance_search').removeClass('closeFilter');
          $('#advance_search').addClass('openFilter');
          $('.filter-row').removeClass('hide-view');
          $('#advance_search .fas').removeClass('fa-search-plus');
          $('#advance_search .fas').addClass('fa-search-minus');
        }
        else {
          $('#advance_search').removeClass('openFilter');
          $('#advance_search').addClass('closeFilter');
          $('.filter-row').addClass('hide-view');
          $('#advance_search .fas').removeClass('fa-search-minus');
          
          $('#advance_search .fas').addClass('fa-search-plus');
        }

      });


    });

  </script>
@stop