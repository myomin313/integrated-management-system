@extends('layouts.master')
@section('title','Calculate Income Tax (NS 1 Year)')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/monthly-tax-statement')}}">Calculate Income Tax (NS 1 Year)</a></li>
            </ol>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h3 class="card-title">Calculate Income Tax (NS 1 Year)</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('tax.monthly-tax-statement-detail-download',$ns_tax->id)}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <div class="row">
                  <div class="col-md-6">
                    <strong>Name : {{$user->employee_name?$user->employee_name:$user->name}}</strong><br>
                    <strong>Position : {{getPositionName($user->position_id)}}</strong><br>
                  </div>
                  <div class="col-md-6">
                    <strong>Department : {{getDepartmentField($user->department_id,'name')}}</strong>
                  </div>
                </div>
                <hr style="border: 1px solid #999;">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Month</th>
                      <th>Monthly Salary<br>(USD)</th>
                      <th>Overtime Allowances<br>(USD)</th>
                      <th>Social Security Contributioin<br>(USD)</th>
                      <th>Bonus<br>(USD)</th>
                      <th>Basic Salary & Bonus<br>(USD)</th>
                      <th>Total Basic Salary<br>(Kyats)</th>
                      <th>Exchange Rate<br>(1USD=MMK)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $net_salary_mmk = 0; $net_salary_usd = 0; $total_salary = 0; $total_ot = 0;$total_ssc = 0;$total_bonus = 0; @endphp
                    @foreach($ns_tax->ns_detail as $key=>$value)
                      <tr>
                        <th>{{$value->month}}' {{$value->year}}</th>
                      	<td class="text-right">{{siteformat_number($value->salary_usd)}}</td>
                      	
                        <td class="text-right">{{siteformat_number($value->ot_usd)}}</td>
                        <td class="text-right">({{siteformat_number($value->ssc_usd)}})</td>
                        <td class="text-right">{{siteformat_number($value->bonus_usd)}}</td>

                      	<td class="text-right">{{siteformat_number($value->total_salary_usd)}}</td>
                        <td class="text-right">{{siteformat_number($value->total_salary_mmk)}}</td>
                        <td class="text-right">{{siteformat_number($value->exchange_rate)}}</td>
                      	@php
                          $total_salary += $value->salary_usd;
                          $total_ot += $value->ot_usd;
                          $total_ssc += $value->ssc_usd;
                          $total_bonus += $value->bonus_usd;
                          $net_salary_usd += $value->total_salary_usd;
                          $net_salary_mmk += $value->total_salary_mmk;
                          
                        @endphp
                      </tr>
                      
                    @endforeach
                    <tr>
                      <th class="text-bold">Total</th>
                      <th class="text-bold text-right">{{siteformat_number($total_salary)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_ot)}}</th>
                      <th class="text-bold text-right">({{siteformat_number($total_ssc)}})</th>
                      <th class="text-bold text-right">{{siteformat_number($total_bonus)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($net_salary_usd)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($net_salary_mmk)}}</th>
                    </tr>
                  </tbody>
                </table>
                <hr style="border:1px solid #333;">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Tax Calculation</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>(Kyats)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th>Total Assessable salary income</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">{{siteformat_number($net_salary_mmk)}}</th>
                    </tr>
                    <tr>
                      <th>Less</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th>Basic allowance - 20%  </th>
                      @php
                        $basic_allowance = $net_salary_mmk * 20 / 100;
                      @endphp
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">{{siteformat_number($basic_allowance)}}</th>
                      <th></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th>but maximum  </th>
                      
                      <th></th>
                      <th>(Ks. 10,000,000)</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">({{siteformat_number($ns_tax->basic_max_allowance)}})</th>
                    </tr>
                    <tr>
                      <th>Parent (Jobless) Allowance  </th>
                      
                      <th></th>
                      <th>(Ks. 1,000,000)</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">({{siteformat_number($ns_tax->parent_allowance)}})</th>
                    </tr>
                    <tr>
                      <th>Spouse Allowance  </th>
                      
                      <th></th>
                      <th>(Ks. 1,000,000)</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">({{siteformat_number($ns_tax->spouse_allowance)}})</th>
                    </tr>
                    <tr>
                      <th>Children Allowance  </th>
                      
                      <th></th>
                      <th>(Ks. 500,000) per children</th>
                      <th></th>
                      <th></th>
                      <th class="text-right">{{$ns_tax->children_allowance/500000}}</th>
                      <th class="text-right">({{siteformat_number($ns_tax->children_allowance)}})</th>
                    </tr>
                    <tr>
                      <th>Life Assured Annualised Premium  </th>
                      
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">({{siteformat_number($ns_tax->life_assured)}})</th>
                    </tr>
                    @php
                      $tax_income = $net_salary_mmk - $ns_tax->basic_max_allowance - $ns_tax->parent_allowance - $ns_tax->spouse_allownce - $ns_tax->children_allownce - $ns_tax->life_assured;
                    @endphp
                    <tr>
                      <th>Net taxable salary income  </th>
                      
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">{{siteformat_number($tax_income)}}</th>
                    </tr>
                  </tbody>
                </table>
                <hr style="border:1px solid #333;">
                <table class="table">
                  <thead>
                    <tr>
                      <th>From Kyat</th>
                      <th></th>
                      <th>To Kyat</th>
                      <th>(Difference)</th>
                      <th>(%)</th>
                      <th>Tax Amount (Kyats)</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $one_year_tax = 0;$last_diff = $tax_income;$difference_show = 1; @endphp
                    @foreach($tax_ranges as $key=>$value)
                      <tr>
                        <td class="text-right">{{siteformat_number($value->from_kyat)}}</td>
                        <td></td>
                        <td class="text-right">{{$value->from_kyat==70000001?"Above":siteformat_number($value->to_kyat)}}</td>
                        @php
                        if($tax_income >= $value->to_kyat){
                            $different = ($value->to_kyat - $value->from_kyat + 1);
                            $tax = ( ($value->to_kyat - $value->from_kyat + 1) * $value->percent ) / 100;
                            $one_year_tax += $tax;
                            $last_diff -= $different;
                        }
                        else if($tax_income >= $value->from_kyat){
                            $different = ($tax_income - $value->from_kyat + 1);
                            $tax = ( ($tax_income - $value->from_kyat + 1) * $value->percent ) / 100;
                            $one_year_tax += $tax;
                            $last_diff = 0;

                        }
                        else{
                            $tax = 0;
                            $last_diff = 0;
                            $difference_show = 0;
                        }
                        
                        @endphp
                        <td class="text-right">{{$difference_show == 1?siteformat_number($different):''}}</td>
                        <td class="text-right">{{$value->percent}}</td>
                        <td class="text-right">{{siteformat_number($tax)}}</td>
                        <td class="text-right">{{$last_diff?siteformat_number($last_diff):''}}</td>
                      </tr>
                    @endforeach
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">{{siteformat_number($one_year_tax)}}</th>
                    </tr>
                  </tbody>
                  
                  <tfoot>
                    
                    <tr>
                      <th colspan="" style="border-top: 1px solid #333 !important;"></th>
                      <th colspan="3" style="border-top: 1px solid #333 !important;">Total Tax For 1 Year</th>
                      <th style="border-top: 1px solid #333 !important;">(Ks.)</th>
                      <th class="text-right" style="border-top: 1px solid #333 !important;">{{siteformat_number($one_year_tax)}}</th>
                      <th style="border-top: 1px solid #333 !important;"></th>
                    </tr>
                    <tr>
                      <th colspan=""></th>
                      <th colspan="3">Total Tax For 1 Month</th>
                      <th>(Ks.)</th>
                      <th class="text-right">{{number_format($one_year_tax/12,2)}}</th>
                      <th></th>
                    </tr>
                    <tr>
                      <th colspan=""></th>
                      <th colspan="3">Deducted Monthly Estimated Tax Rate</th>
                      <th></th>
                      <th class="text-right">{{round(($one_year_tax/$tax_income) * 100)}}%</th>
                      <th></th>
                    </tr>
                    <tr><td colspan="8"></td></tr>
                    <tr><td colspan="8" style="border-bottom: 1px solid #333;"></td></tr>

                    @php 
                        $tax_year = \Carbon\Carbon::parse($ns_tax->date)->format('Y');
                        $first_year = $tax_year;
                        $last_year = $tax_year;
                        $tax_month = \Carbon\Carbon::parse($ns_tax->date)->format('F');
                        if(strtolower($tax_month)=="january" or strtolower($tax_month)=="february" or strtolower($tax_month)=="march")
                          $first_year = $tax_year - 1;
                        else
                          $last_year = $tax_year +1;
                      
                      $month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];
                      $total_actual_tax = 0;
                      $total_actual_tax_usd = 0;
                    @endphp 
                    @foreach($month_arr as $key=>$value)
                      <tr>
                        @php 
                          if($key=="01" or $key=="02" or $key=="03")
                            $show_year = $last_year;
                          else
                            $show_year = $first_year;
                          $actual_tax = getNSActualTax($ns_tax->user_id,\Carbon\Carbon::parse("$show_year-$key")->endOfMonth()->format("Y-m-d"));
                          if($actual_tax){
                            $mmk_tax = $actual_tax->tax_amount_mmk;
                            $usd_tax = $actual_tax->tax_amount_usd;
                          }
                          else{
                            $mmk_tax = 0;
                            $usd_tax = 0;
                          }
                          $total_actual_tax += $mmk_tax;
                          $total_actual_tax_usd += $usd_tax;
                        @endphp
                        <th colspan="4">Tax Amount for {{$value}}'{{$show_year}} (Kyats) </th>
                        <th>=</th>

                        <th class="text-right">{{siteformat_number($mmk_tax)}}</th>
                        <th class="text-right">{{siteformat_number($usd_tax)}}</th>
                      </tr>
                    @endforeach
                    <tr>
                      <th colspan="4">Total Tax Amount For FY {{$first_year}} - {{$last_year}}</th>
                      <th>=</th>
                      <th class="text-right">{{siteformat_number($total_actual_tax)}}</th>
                      <th class="text-right">{{siteformat_number($total_actual_tax_usd)}}</th>
                    </tr>
                    <tr>
                      <th colspan="4">Remaining Tax Amount For FY {{$first_year}} - {{$last_year}}</th>
                      <th>=</th>
                      @php
                        $remaining_tax = $one_year_tax - $total_actual_tax;
                      @endphp
                      <th class="text-right">{{siteformat_number($remaining_tax)}}</th>
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
          format: 'MM/YYYY'
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