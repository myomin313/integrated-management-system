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
                
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th colspan="4">{{getUserNameWithPrefix($user->id)}}</th>
                      <th colspan="2" class="text-right">on {{$salary->pay_for}}</th>
                    </tr>
                    <tr>
                      <th colspan="4"></th>
                      <th>USD</th>
                      <th>MMK</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                      <tr>
                        <th colspan="3">Salary for {{$salary->pay_for}}</th>
                      	<td class="text-center text-bold">
                          <i class="fas fa-plus text-bold" style="font-size:15px;"></i>
                        </td>
                      	
                        <td class="text-right">{{siteformat_number($salary->salary_usd)}}</td>
                        <td class="text-right">{{number_format($salary->salary_mmk)}}</td>
                      	
                      </tr>
                      @if($salary->transfer_from_japan_usd>0)
                      <tr>
                        <th colspan="3">Transfer Salary From Japan</th>
                        <td class="text-center text-bold">
                          <i class="fas fa-plus text-bold" style="font-size:18px;"></i>
                        </td>
                        
                        <td class="text-right">{{siteformat_number($salary->transfer_from_japan_usd)}}</td>
                        <td class="text-right">{{number_format($salary->transfer_from_japan_mmk)}}</td>
                        
                      </tr>
                      @endif
                      @if($salary->transfer_to_japan_usd>0)
                      <tr>
                        <th colspan="3">Transfer Salary To Japan</th>
                        <td class="text-center text-bold">
                          <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                        </td>
                        
                        <td class="text-right">({{siteformat_number($salary->transfer_to_japan_usd)}})</td>
                        <td class="text-right">({{number_format($salary->transfer_to_japan_mmk)}})</td>
                        
                      </tr>
                      @endif
                      @if($salary->electricity_usd>0)
                      <tr>
                        <th colspan="3">Public utility fees </th>
                        <td class="text-center text-bold">
                          <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                        </td>
                        
                        <td class="text-right">({{siteformat_number($salary->electricity_usd)}})</td>
                        <td class="text-right">({{number_format($salary->electricity_mmk)}})</td>
                        
                      </tr>
                      @endif
                      @if($salary->car_usd>0)
                      <tr>
                        <th colspan="3">Car Charge </th>
                        <td class="text-center text-bold">
                          <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                        </td>
                        
                        <td class="text-right">({{siteformat_number($salary->car_usd)}})</td>
                        <td class="text-right">({{number_format($salary->car_mmk)}})</td>
                        
                      </tr>
                      @endif
                      @if($salary->kbz_opening_usd>0)
                      <tr>
                        <th colspan="3">KBZ Opening A/C </th>
                        <td class="text-center text-bold">
                          <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                        </td>
                        
                        <td class="text-right">({{siteformat_number($salary->kbz_opening_usd)}})</td>
                        <td class="text-right">({{number_format($salary->kbz_opening_mmk)}})</td>
                        
                      </tr>
                      @endif
                      {{-- <tr>
                        <th colspan="3">Paid with Myanmar Kyat(Cash) (Central Bank Exchange Rate US$ 1 = KS ) </th>
                        <td class="text-center text-bold">
                          
                        </td>
                        
                        <td class="text-right">USD</td>
                        <td class="text-right">{{$salary->exchange_rate_usd}}</td>
                        
                      </tr> --}}
                      @if($salary->usd_allowance_usd>0 or $salary->mmk_allowance_mmk>0)
                      <tr>
                        <th colspan="7">Other Allowance </th>
                        
                      </tr>
                      @foreach($salary->other_allowance as $key=>$value)
                      <tr>
                        <th colspan="3"><small style="visibility:hidden;">ABCDE</small>{{$value->name}} </th>
                        <th class="text-center">{{strtoupper($value->currency)}}</th>
                        <td class="text-right">{{$value->currency=="usd"?siteformat_number($value->amount):''}}</td>
                        <td class="text-right">{{$value->currency=="mmk"?number_format($value->amount):number_format($value->amount*$salary->payment_exchange_rate)}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      <tr>
                        <th colspan="3">Total Allowance </th>
                        <th class="text-center text-bold">
                          <i class="fas fa-plus text-bold" style="font-size:18px;"></i>
                        </th>
                        
                        <th class="text-right">{{siteformat_number($salary->usd_allowance_usd+$salary->mmk_allowance_usd)}}</th>
                        <th class="text-right">{{number_format($salary->usd_allowance_mmk+$salary->mmk_allowance_mmk)}}</th>
                        
                      </tr>
                      @endif
                      @if($salary->usd_deduction_usd>0 or $salary->mmk_deduction_mmk>0)
                      <tr>
                        <th colspan="7">Other Deduction </th>
                        
                      </tr>
                      @foreach($salary->other_deduction as $key=>$value)
                      <tr>
                        <th colspan="3"><small style="visibility:hidden;">ABCDE</small>{{$value->name}} </th>
                        <th class="text-center">{{strtoupper($value->currency)}}</th>
                        <td class="text-right">{{$value->currency=="usd"?'('.siteformat_number($value->amount).')':'0'}}</td>
                        <td class="text-right">{{$value->currency=="mmk"?'('.number_format($value->amount).')':'('.number_format($value->amount*$salary->payment_exchange_rate).')'}}</td>
                        <!-- <th colspan="2"></th> -->
                        
                        
                      </tr>
                      @endforeach 
                      <tr>
                        <th colspan="3">Total Deduction </th>
                        <th class="text-center text-bold">
                          <i class="fas fa-sort-up text-bold" style="font-size:18px;"></i>
                        </th>
                        
                        <th class="text-right">({{siteformat_number($salary->usd_deduction_usd+$salary->mmk_deduction_usd)}})</th>
                        <th class="text-right">({{number_format($salary->usd_deduction_mmk+$salary->mmk_deduction_mmk)}})</th>
                        
                      </tr> 
                      @endif 

                      <tr>
                        <th colspan="3" style="border-top:1px solid #999 !important;border-bottom: 1px solid #999 !important;">Salary and Other Payments (Total) </th>
                        <td class="text-center text-bold" style="border-top:1px solid #999 !important;border-bottom: 1px solid #999 !important;">
                          
                        </td>
                        
                        <td class="text-right" style="border-top:1px solid #999 !important;border-bottom: 1px solid #999 !important;">{{siteformat_number($salary->net_salary_usd)}}</td>
                        <td class="text-right" style="border-top:1px solid #999 !important;border-bottom: 1px solid #999 !important;">{{number_format($salary->net_salary_mmk)}}</td>
                        
                      </tr>  
                      <tr>
                        <th colspan="5">Salary Transfer To USD A/C </th>
                        
                        <td class="text-right">{{siteformat_number($salary->transfer_usd_acc)}}</td>
                        
                      </tr>   
                      <tr>
                        <th colspan="5">Salary Transfer To MMK A/C </th>
                        
                        <td class="text-right">{{number_format($salary->transfer_mmk_acc)}}</td>
                        
                      </tr> 
                      <tr>
                        <th colspan="5">Salary Transfer To USD Cash </th>
                        
                        <td class="text-right">{{siteformat_number($salary->transfer_usd_cash)}}</td>
                        
                      </tr> 
                      <tr>
                        <th colspan="5">Salary Transfer To MMK Cash </th>
                        
                        <td class="text-right">{{number_format($salary->transfer_mmk_cash)}}</td>
                        
                      </tr>    
                    
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