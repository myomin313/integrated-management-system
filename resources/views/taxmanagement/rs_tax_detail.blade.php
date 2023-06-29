@extends('layouts.master')
@section('title','Calculate Income Tax (RS 1 Year)')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/annual-tax/rs-tax-payment-report')}}">Calculate Income Tax (RS 1 Year)</a></li>
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
                    <h3 class="card-title">Calculate Income Tax (RS 1 Year)</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('tax.rs-tax-detail-download',[$user_id,$date])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
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
                  		<th colspan="12">Salary received in Japan</th>
                  	</tr>
                    <tr>
                      <th>Month</th>
                      <th>Salary<br>(JPY)</th>
                      <th>Transfer Salary From Myanmar<br>(JPY)</th>
                      <th>Adjustments<br>(JPY)</th>
                      <th>Income Tax Paid In Japan<br>(JPY)</th>
                      <th>Bonus<br>(JPY)</th>
                      <th>Basic Salary & Bonus <br>(JPY)</th>
                      <th>Oversea Settlement Allowances <br>(JPY)</th>
                      <th>DC Contribution <br>(JPY)</th>
                      <th>Total Salary<br>(JPY)</th>
                      <th>Total Salary <br>(Kyats)</th>
                      <th>Exchange Rate<br>(100Y =MMK)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $net_salary_mmk = 0; $net_salary_usd = 0;$net_salary_yen = 0;$net_salary_usd_mmk = 0;$net_salary_yen_mmk = 0; $total_salary = 0; $total_transfer_from = 0;$total_adjustment = 0;$total_tax_paid = 0;$total_bonus = 0;$total_salary_bonus = 0; $total_oversea = 0; $total_dc = 0;@endphp
                    @foreach($rs_tax->rs_jpy_detail as $key=>$value)
                      <tr>
                        <th>{{$value->month}}' {{$value->year}}</th>
                      	<td class="text-right">{{siteformat_number($value->salary_yen)}}</td>
                      	
                        <td class="text-right">{{siteformat_number($value->transfer_from_mm_yen)}}</td>
                        <td class="text-right">{{siteformat_number($value->adjustment_yen)}}</td>
                        <td class="text-right">({{siteformat_number($value->income_tax_jpy_yen)}})</td>

                      	<td class="text-right">{{siteformat_number($value->bonus_yen)}}</td>
                      	@php
                      		$salary_bonus = $value->salary_yen + $value->transfer_from_mm_yen + $value->adjustment_yen - $value->income_tax_jpy_yen + $value->bonus_yen;
                      	@endphp
                      	<td class="text-right">{{siteformat_number($salary_bonus)}}</td>

                        <td class="text-right">{{siteformat_number($value->oversea_yen)}}</td>
                        <td class="text-right">{{siteformat_number($value->dc_yen)}}</td>
                        <td class="text-right">{{siteformat_number($value->total_salary_yen)}}</td>

                        <td class="text-right">{{siteformat_number($value->total_salary_mmk)}}</td>
                        <td class="text-right">{{siteformat_number($value->exchange_rate)}}</td>
                      	@php
                          $net_salary_yen += $value->total_salary_yen;
                          $net_salary_yen_mmk += $value->total_salary_mmk;
                          $total_salary += $value->salary_yen; $total_transfer_from += $value->transfer_from_mm_yen;
                          $total_adjustment += $value->adjustment_yen;
                          $total_tax_paid += $value->income_tax_jpy_yen;
                          $total_bonus += $value->bonus_yen;
                          $total_salary_bonus += $salary_bonus;
                          $total_oversea += $value->oversea_yen;
                          $total_dc += $value->dc_yen;
                          
                        @endphp
                      </tr>
                      
                    @endforeach
                    <tr>
                      <th class="text-bold">Total</th>
                      <th class="text-bold text-right">{{siteformat_number($total_salary)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_transfer_from)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_adjustment)}}</th>
                      <th class="text-bold text-right">({{siteformat_number($total_tax_paid)}})</th>
                      <th class="text-bold text-right">{{siteformat_number($total_bonus)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_salary_bonus)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_oversea)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_dc)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($net_salary_yen)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($net_salary_yen_mmk)}}</th>
                    </tr>
                  </tbody>
                </table>
                <hr style="border: 1px solid #999;">
                <table class="table">
                  <thead>
                  	<tr>
                  		<th colspan="10">Salary received in Myanmar</th>
                  	</tr>
                    <tr>
                      <th>Month</th>
                      <th>Salary<br>(USD)</th>
                      <th>Transfer Salary to Japan<br>(USD)</th>
                      <th>Bonus<br>(USD)</th>
                      <th>Salary & Bonus <br>(USD)</th>
                      <th>Oversea Settlement Allowances <br>(USD)</th>
                      <th>DC Contribution <br>(USD)</th>
                      <th>Total Salary<br>(USD)</th>
                      <th>Total Salary <br>(Kyats)</th>
                      <th>Exchange Rate<br>(1USD =MMK)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $net_salary_usd = 0;$net_salary_usd_mmk = 0; $total_salary = 0; $total_transfer_to = 0;$total_bonus = 0;$total_salary_bonus = 0; $total_oversea = 0; $total_dc = 0;@endphp
                    @foreach($rs_tax->rs_mm_detail as $key=>$value)
                      <tr>
                        <th>{{$value->month}}' {{$value->year}}</th>
                      	<td class="text-right">{{siteformat_number($value->salary_usd)}}</td>
                      	
                        <td class="text-right">({{siteformat_number($value->transfer_to_jp_usd)}})</td>
                        
                      	<td class="text-right">{{siteformat_number($value->bonus_usd)}}</td>
                      	@php
                      		$salary_bonus = $value->salary_usd + $value->transfer_to_jp_usd + $value->bonus_usd;
                      	@endphp
                      	<td class="text-right">{{siteformat_number($salary_bonus)}}</td>

                        <td class="text-right">{{siteformat_number($value->oversea_usd)}}</td>
                        <td class="text-right">{{siteformat_number($value->dc_usd)}}</td>
                        <td class="text-right">{{siteformat_number($value->total_salary_usd)}}</td>
                        <td class="text-right">{{siteformat_number($value->total_salary_mmk)}}</td>

                        <td class="text-right">{{siteformat_number($value->exchange_rate)}}</td>
                      	@php
                          $net_salary_usd += $value->total_salary_usd;
                          $net_salary_usd_mmk += $value->total_salary_mmk;
                          $total_salary += $value->salary_usd; $total_transfer_to += $value->transfer_to_jp_usd;
                          $total_bonus += $value->bonus_usd;
                          $total_salary_bonus += $salary_bonus;
                          $total_oversea += $value->oversea_usd;
                          $total_dc += $value->dc_usd;
                          
                        @endphp
                      </tr>
                      
                    @endforeach
                    <tr>
                      <th class="text-bold">Total</th>
                      <th class="text-bold text-right">{{siteformat_number($total_salary)}}</th>
                      <th class="text-bold text-right">({{siteformat_number($total_transfer_to)}})</th>

                      <th class="text-bold text-right">{{siteformat_number($total_bonus)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_salary_bonus)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_oversea)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($total_dc)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($net_salary_usd)}}</th>
                      <th class="text-bold text-right">{{siteformat_number($net_salary_usd_mmk)}}</th>
                    </tr>
                  </tbody>
                  <tfoot>
                  	<tfoot>
                    
                    <tr>
                      
                      <th colspan="4" style="border-top: 1px solid #333 !important;">Total Salary (in Kyats) in Japan</th>
                      <th style="border-top: 1px solid #333 !important;">=</th>
                      <th class="text-right" style="border-top: 1px solid #333 !important;">{{siteformat_number($net_salary_yen_mmk)}}</th>
                      <th style="border-top: 1px solid #333 !important;" colspan="4"></th>
                    </tr>
                    <tr>
                      <th colspan="4">Total Salary (in Kyats) in Myanmar</th>
                      <th>=</th>
                      <th class="text-right">{{siteformat_number($net_salary_usd_mmk)}}</th>
                      <th colspan="4"></th>
                    </tr>
                    <tr>
                      <th colspan="4">Total Social Security Contribution Fund</th>
                      <th>=</th>
                      @php
                      $ssc = 6000 * 12;
                      $net_salary_mmk = $net_salary_yen_mmk + $net_salary_usd_mmk + $ssc;
                      @endphp
                      <th class="text-right">{{siteformat_number($ssc)}}</th>
                      <th colspan="4"></th>
                    </tr>
                    <tr>
                      <th colspan="4">Total Assessable salary income</th>
                      <th>=</th>
                      
                      <th class="text-right">{{siteformat_number($net_salary_mmk)}}</th>
                      <th colspan="4"></th>
                    </tr>
                  </tfoot>
                  </tfoot>
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
                      <th class="text-right">({{siteformat_number($rs_tax->max_allowance)}})</th>
                    </tr>
                    <tr>
                      <th>Parent (Jobless) Allowance  </th>
                      
                      <th></th>
                      <th>(Ks. 1,000,000)</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">({{siteformat_number($rs_tax->parent_allowance)}})</th>
                    </tr>
                    <tr>
                      <th>Spouse Allowance  </th>
                      
                      <th></th>
                      <th>(Ks. 1,000,000)</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th class="text-right">({{siteformat_number($rs_tax->spouse_allowance)}})</th>
                    </tr>
                    <tr>
                      <th>Children Allowance  </th>
                      
                      <th></th>
                      <th>(Ks. 500,000) per children</th>
                      <th></th>
                      <th></th>
                      <th class="text-right">{{$rs_tax->children_allowance/500000}}</th>
                      <th class="text-right">({{siteformat_number($rs_tax->children_allowance)}})</th>
                    </tr>
                    
                    @php
                      $tax_income = $net_salary_mmk - $rs_tax->max_allowance - $rs_tax->parent_allownce - $rs_tax->spouse_allownce - $rs_tax->children_allownce;
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
                      <th class="text-right">From Kyat</th>
                      <th></th>
                      <th class="text-right">To Kyat</th>
                      <th class="text-right">(Difference)</th>
                      <th class="text-right">(%)</th>
                      <th class="text-right">Tax Amount (Kyats)</th>
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
                      <th colspan="4" style="border-top: 1px solid #333 !important;">salary head mentioned in Return of Income Form [IRD (I. T) - 1]</th>
                      
                      <th style="border-top: 1px solid #333 !important;"></th>
                      <th class="text-right" style="border-top: 1px solid #333 !important;"></th>
                      <th style="border-top: 1px solid #333 !important;">(Kyats)</th>
                    </tr>
                    <tr>
                      <th colspan="3">Tax on tax Calculation</th>
                      <th>{{siteformat_number($one_year_tax)}}</th>
                      <th>/</th>
                      <th>{{$rs_tax->tax_calculation_percent}}</th>
                      @php
                      $year_tax = $one_year_tax / $rs_tax->tax_calculation_percent; @endphp
                      <th class="text-right">{{siteformat_number(round($year_tax,2))}}</th>
                      <th></th>
                    </tr>
                    <tr>
                      <th colspan="5">Estimated Total Tax for 1 Month</th>
                      <th></th>
                      <th class="text-right">{{siteformat_number(round($year_tax/12))}}</th>
                      <th></th>
                    </tr>
                    <tr><td colspan="8"></td></tr>
                    <tr><td colspan="8" style="border-bottom: 1px solid #333;"></td></tr>

                    <tr>
                      @php 
                        $tax_year = \Carbon\Carbon::parse($rs_tax->date)->format('Y');
                        $first_year = $tax_year;
                        $last_year = $tax_year;
                        $tax_month = \Carbon\Carbon::parse($rs_tax->date)->format('F');
                        if(strtolower($tax_month)=="january" or strtolower($tax_month)=="february" or strtolower($tax_month)=="march")
                          $first_year = $tax_year - 1;
                        else
                          $last_year = $tax_year +1;
                      @endphp
                      <th colspan="4">Total Individual Income tax payable for April' {{$first_year}} up to March' {{$last_year}} (Kyats)</th>
                      <th>=</th>
                      <th class="text-right">{{number_format($one_year_tax / $rs_tax->tax_calculation_percent,2)}}</th>
                      <th class="text-right">(USD)</th>
                    </tr>
                    @php
                      $month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];
                      $total_actual_tax = 0;
                    @endphp 
                    @foreach($month_arr as $key=>$value)
                      <tr>
                        @php 
                          if($key=="01" or $key=="02" or $key=="03")
                            $show_year = $last_year;
                          else
                            $show_year = $first_year;
                          $actual_tax = getRSActualTax($rs_tax->user_id,\Carbon\Carbon::parse("$show_year-$key")->endOfMonth()->format("Y-m-d"));
                          if($actual_tax){
                            $mmk_tax = $actual_tax->tax_amount_mmk;
                            $usd_tax = $actual_tax->tax_amount_usd;
                          }
                          else{
                            $mmk_tax = 0;
                            $usd_tax = 0;
                          }
                          $total_actual_tax += $mmk_tax;
                        @endphp
                        <th colspan="4">Salary-tax already paid for {{$value}}'{{$show_year}} (Kyats) </th>
                        <th>=</th>

                        <th class="text-right">{{siteformat_number($mmk_tax)}}</th>
                        <th class="text-right">{{siteformat_number($usd_tax)}}</th>
                      </tr>
                    @endforeach
                    {{-- <tr>
                      <th colspan="4">Total Tax</th>
                      <th>=</th>
                      <th>{{$total_actual_tax}}</th>
                    </tr> --}}
                    <tr>
                      <th colspan="4">Remaining Individual Income tax payable for April' {{$first_year}} up to March' {{$last_year}} (Kyats)</th>
                      <th>=</th>
                      @php
                        $remaining_tax = $year_tax - $total_actual_tax;
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