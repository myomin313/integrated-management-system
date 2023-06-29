@extends('layouts.master')
@section('title','Pay Slip Detail')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Salary Management</li>
              <li class="breadcrumb-item active"><a href="{{url('salary-management/payslip-list/detail')}}">Pay Slip Detail</a></li>
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
                    <h3 class="card-title">Pay Slip Detail</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('salary.payslip-detail-download',$salary->id)}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                
                <table class="table table-bordered" style="border: 2px solid #999;">
                  <thead>
                    <tr style="font-size:20px;">
                      <th style="border-bottom:2px solid #999;" colspan="4">Salary for {{$salary->pay_for}}</th>
                      <th style="border-bottom:2px solid #999;" colspan="3" class="text-right">on {{\Carbon\Carbon::parse($salary->pay_date)->format("d F Y")}}</th>
                    </tr>
                    <tr style="font-size:18px;">
                      <th style="border-bottom:2px solid #999;" colspan="7">{{getUserNameWithPrefix($user->id)}}</th>
                      
                    </tr>
                    <tr>
                      <th colspan="4" style="border-bottom:2px solid #999;text-align: center;"></th>
                      <th style="border-bottom:2px solid #999;text-align: center;">USD</th>
                      <th style="border-bottom:2px solid #999;text-align: center;">Exchange Rate</th>
                      <th style="border-bottom:2px solid #999;text-align: center;">MMK</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($salary->exchange_rate_salary_detail as $key=>$detail)
                      <tr>
                        @if($key==0)
                        <th colspan="3">Salary for {{$salary->pay_for}}</th>
                        @else
                        <th colspan="3"></th>
                        @endif
                      	<td class="text-center text-bold">
                          <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                        </td>
                      	
                        <td class="text-right">{{siteformat_number($detail->usd_amount)}}</td>
                        <td class="text-right">{{siteformat_number($detail->exchange_rate)}}</td>
                        <td class="text-right">{{siteformat_number($detail->mmk_amount)}}</td>
                      	
                      </tr>
                      @endforeach
                      @if($salary->estimated_tax_usd>0)
                        @foreach($salary->exchange_rate_tax_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3">I-Tax ({{$salary->pay_for}})</th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <td class="text-center text-bold">
                            <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                          </td>
                          
                          <td class="text-right">({{siteformat_number($detail->usd_amount)}})</td>
                          <td class="text-right">{{siteformat_number($detail->exchange_rate)}}</td>
                          <td class="text-right">({{siteformat_number($detail->mmk_amount)}})</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->ssc_usd>0)
                        @foreach($salary->exchange_rate_ssc_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3">SSC ({{$salary->pay_for}})</th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <td class="text-center text-bold">
                            <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                          </td>
                          
                          <td class="text-right">({{siteformat_number($detail->usd_amount)}})</td>
                          <td class="text-right">{{siteformat_number($detail->exchange_rate)}}</td>
                          <td class="text-right">({{siteformat_number($detail->mmk_amount)}})</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->total_ot_payment_usd>0)
                        @foreach($salary->exchange_rate_ot_detail as $key=>$detail)
                        <tr>
                        	@if($salary->is_retire)
                        		@php $month_name = \Carbon\Carbon::parse("$salary->year-$salary->month")->subMonth()->format('F, Y')."+".\Carbon\Carbon::parse("$salary->year-$salary->month")->format('F, Y'); @endphp
                        	@else
                        		@php $month_name = \Carbon\Carbon::parse("$salary->year-$salary->month")->subMonth()->format('F, Y'); @endphp
                        	@endif
                          @if($key==0)
                          <th colspan="3">Overtime - ({{$month_name}}) </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <td class="text-center text-bold">
                            <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                          </td>
                          
                          <td class="text-right">{{siteformat_number($detail->usd_amount)}}</td>
                          <td class="text-right">{{siteformat_number($detail->exchange_rate)}}</td>
                          <td class="text-right">{{siteformat_number($detail->mmk_amount)}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->leave_amount_usd>0)
                        @foreach($salary->exchange_rate_leave_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3">W/O Pay Leave ({{getLeaveDay($salary->user_id,$salary->leave_from_date,$salary->leave_to_date)}}) </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <td class="text-center text-bold">
                            <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                          </td>
                          
                          <td class="text-right">({{siteformat_number($detail->usd_amount)}})</td>
                          <td class="text-right">{{siteformat_number($detail->exchange_rate)}}</td>
                          <td class="text-right">({{siteformat_number($detail->mmk_amount)}})</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->kbz_opening_usd>0)
                        @foreach($salary->exchange_rate_kbz_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3">KBZ Opening A/C </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <td class="text-center text-bold">
                            <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                          </td>
                          
                          <td class="text-right">({{siteformat_number($detail->usd_amount)}})</td>
                          <td class="text-right">{{siteformat_number($detail->exchange_rate)}}</td>
                          <td class="text-right">({{siteformat_number($detail->mmk_amount)}})</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      @if($salary->bonus_usd>0)
                        @foreach($salary->exchange_rate_bonus_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3">Bonus ({{$salary->bonus_name=="Other"?$salary->other_bonus:$salary->bonus_name}}) </th>
                          @else
                          <th colspan="3"></th>
                          @endif
                          <td class="text-center text-bold">
                            <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                          </td>
                          
                          <td class="text-right">{{siteformat_number($detail->usd_amount)}}</td>
                          <td class="text-right">{{siteformat_number($detail->exchange_rate)}}</td>
                          <td class="text-right">{{siteformat_number($detail->mmk_amount)}}</td>
                          
                        </tr>
                        @endforeach
                      @endif
                      {{-- <tr>
                        <th colspan="3">Paid with Myanmar Kyat(Cash) (Central Bank Exchange Rate US$ 1 = KS ) </th>
                        <td class="text-center text-bold">
                          
                        </td>
                        
                        <td class="text-right">USD</td>
                        <td class="text-right">{{$salary->payment_exchange_rate}}</td>
                        
                      </tr> --}}
                      @if($salary->other_adjustment_usd>0 or $salary->other_adjustment_usd<0)
                      <tr>
                        <th colspan="7">Other Adjustment </th>
                        
                      </tr>
                      @foreach($salary->other_adjustment as $key=>$value)
                      <tr>
                        <td colspan="3"><small style="visibility:hidden;">ABCDE</small>{{$value->name}} </td>
                        <th class="text-center text-bold">
                          @if($value->usd_amount<0)
                        	<i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                          @else
                          <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                          @endif
                        </th>
                        <td class="text-right">{{$value->usd_amount<0?'('.str_replace("-", "", siteformat_number($value->usd_amount)).')':siteformat_number($value->usd_amount)}}</td>
                        <td class="text-right">{{siteformat_number($value->exchange_rate)}}</td>
                        <td class="text-right">{{$value->usd_amount<0?'('.str_replace("-", "", siteformat_number($value->mmk_amount)).')':siteformat_number($value->mmk_amount)}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      <tr>
                        <th colspan="3">Total Adjustment </th>
                        <th class="text-center text-bold">
                          @if($salary->other_adjustment_usd<0)
                          <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                          @else
                          <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                          @endif
                        </th>
                        
                        <th class="text-right">{{$salary->other_adjustment_usd<0?'('.str_replace("-", "", siteformat_number($salary->other_adjustment_usd)).')':siteformat_number($salary->other_adjustment_usd)}}</th>
                        <th class="text-right"></th>
                        <th class="text-right">{{$salary->other_adjustment_usd<0?'('.str_replace("-", "", siteformat_number($salary->other_adjustment_mmk)).')':siteformat_number($salary->other_adjustment_mmk)}}</th>
                        
                      </tr>
                      @endif
                      @if($salary->usd_allowance_usd>0 or $salary->mmk_allowance_mmk>0)
                      <tr>
                        <th colspan="7">Other Allowance </th>
                        
                      </tr>
                      @foreach($salary->other_allowance as $key=>$value)
                      <tr>
                        <td colspan="3"><small style="visibility:hidden;">ABCDE</small>{{$value->name}} </td>
                        <td class="text-center text-bold">{{strtoupper($value->currency)}}</td>
                        <td class="text-right">{{$value->currency=="usd"?siteformat_number($value->amount):''}}</td>
                        <td class="text-right"></td>
                        <td class="text-right">{{$value->currency=="mmk"?siteformat_number($value->amount):''}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      @if($salary->usd_allowance_usd>0)
                        @foreach($salary->exchange_rate_usd_allowance_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" class="">USD Allowance </th>
                          @else
                          <th colspan="3" class="text-right"> </th>
                          @endif
                          <th class="text-center text-bold">
                            <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                          </th>
                          
                          <th class="text-right">{{siteformat_number($detail->usd_amount)}}</th>
                          <th class="text-right">{{siteformat_number($detail->exchange_rate)}}</th>
                          <th class="text-right">{{siteformat_number($detail->mmk_amount)}}</th>
                          
                        </tr>
                        @endforeach
                      @endif
                      <tr>
                        <th colspan="3">Total Allowance </th>
                        <th class="text-center text-bold">
                          <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                        </th>
                        
                        <th class="text-right">{{siteformat_number($salary->usd_allowance_usd+$salary->mmk_allowance_usd)}}</th>
                        <th class="text-right"></th>
                        <th class="text-right">{{siteformat_number($salary->usd_allowance_mmk+$salary->mmk_allowance_mmk)}}</th>
                        
                      </tr>
                      @endif
                      @if($salary->usd_deduction_usd>0 or $salary->mmk_deduction_mmk>0)
                      <tr>
                        <th colspan="7">Other Deduction </th>
                        
                      </tr>
                      @foreach($salary->other_deduction as $key=>$value)
                      <tr>
                        <td colspan="3"><small style="visibility:hidden;">ABCDE</small>{{$value->name}} </td>
                        <td class="text-center text-bold">{{strtoupper($value->currency)}}</td>
                        <td class="text-right">{{$value->currency=="usd"?'('.siteformat_number($value->amount).')':''}}</td>
                        <td></td>
                        <td class="text-right">{{$value->currency=="mmk"?'('.siteformat_number($value->amount).')':''}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      @if($salary->usd_deduction_usd>0)
                        @foreach($salary->exchange_rate_usd_deduction_detail as $key=>$detail)
                        <tr>
                          @if($key==0)
                          <th colspan="3" class="">USD Deduction </th>
                          @else
                          <th colspan="3" class="text-right"> </th>
                          @endif
                          <th class="text-center text-bold">
                            <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                          </th>
                          
                          <th class="text-right">({{siteformat_number($detail->usd_amount)}})</th>
                          <th class="text-right">{{siteformat_number($detail->exchange_rate)}}</th>
                          <th class="text-right">({{siteformat_number($detail->mmk_amount)}})</th>
                          
                        </tr>
                        @endforeach
                      @endif
                      <tr>
                        <th colspan="3">Total Deduction </th>
                        <th class="text-center text-bold">
                          <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                        </th>
                        
                        <th class="text-right">({{siteformat_number($salary->usd_deduction_usd+$salary->mmk_deduction_usd)}})</th>
                        <th class="text-right"></th>
                        <th class="text-right">({{siteformat_number($salary->usd_deduction_mmk+$salary->mmk_deduction_mmk)}})</th>
                        
                      </tr> 
                      @endif 

                      <tr>
                        <th colspan="3" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;">Salary and Other Payments (Total) </th>
                        <th class="text-center text-bold" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;">
                          
                        </th>
                        
                        <th class="text-right" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;">{{siteformat_number($salary->net_salary_usd)}}</th>
                        <th class="text-right" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;"></th>
                        <th class="text-right" style="border-top:2px solid #999 !important;border-bottom: 2px solid #999 !important;">{{siteformat_number($salary->net_salary_mmk)}}</th>
                        
                      </tr>
                      @if($salary->transfer_usd_acc>0) 
                      <tr>
                        <th colspan="6">Salary Transfer To USD A/C </th>
                        
                        <td class="text-right text-bold">{{siteformat_number($salary->transfer_usd_acc)}}</td>
                        
                      </tr>
                      @endif 
                      @if($salary->transfer_mmk_acc>0) 
                      <tr>
                        <th colspan="6">Salary Transfer To MMK A/C </th>
                        
                        <td class="text-right  text-bold">{{siteformat_number($salary->transfer_mmk_acc)}}</td>
                        
                      </tr> 
                      @endif
                      @if($salary->transfer_usd_cash>0)
                      <tr>
                        <th colspan="6">Salary Transfer To USD Cash </th>
                        
                        <td class="text-right  text-bold">{{siteformat_number($salary->transfer_usd_cash)}}</td>
                        
                      </tr> 
                      @endif 
                      @if($salary->transfer_mmk_cash>0)
                      <tr>
                        <th colspan="6">Salary Transfer To MMK Cash </th>
                        
                        <td class="text-right text-bold">{{siteformat_number($salary->transfer_mmk_cash)}}</td>
                        
                      </tr>    
                      @endif
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