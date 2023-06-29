@extends('layouts.master')
@section('title','Annual Tax Office Submission')
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/annual-tax/tax-office-submission')}}">Annual Tax Office Submission</a></li>
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
                <form action="{{url('tax-management/annual-tax/tax-office-submission')}}" method="get">
                  
                  <div class="row">
                    
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Choose Year</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="YYYY" value="{{$from_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Employee Name</label>
                        <select class="form-control select2bs4" name="employee" id="employee" style="width: 100%;">
                          <option selected="selected" value="all">- All -</option>
                          @foreach($employees as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$employee?'selected':''}}>{{$value->employee_name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('tax-management/annual-tax/tax-office-submission')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">Annual Tax Office Submission</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('tax.tax-office-submission-download',['from_date'=>$from_date,'employee'=>isset($employee)?$employee:null])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>Name of Salary Earner </th>
                      <th>Designation / Occupation (Barnd Name)</th>
                      <th>GIR NO.</th>
                      <th>Annual Salary / Wages</th>
                      <th>Overtime</th>
                      <th>Other Disbursements (Bonus)</th>
                      <th>Total [5+6+7]</th>
                      <th>Sums Contributed to General Provident Fund</th>
                      <th>Life Insurance Premium Paid</th>
                      <th>Social Security Contribution Fund</th>
                      <th>Other Saving recognized by the Govt</th>
                      <th>Total Payment ( 9+10+11+12 )</th>
                      <th>Whether the Wife or Husband earned taxable income within the year</th>
                      <th>Numbers of Children</th>
                      <th>Amount of Income Tax Deducted</th>
                      <th>Remarks</th>
                      
                    </tr>
                    <tr>
                      <th class="text-center">1</th>
                      <th class="text-center">2</th>
                      <th class="text-center">3</th>
                      <th class="text-center">4</th>
                      <th class="text-center">5</th>
                      <th class="text-center">6</th>
                      <th class="text-center">7</th>
                      <th class="text-center">8</th>
                      <th class="text-center">9</th>
                      <th class="text-center">10</th>
                      <th class="text-center">11</th>
                      <th class="text-center">12</th>
                      <th class="text-center">13</th>
                      <th class="text-center">14</th>
                      <th class="text-center">15</th>
                      <th class="text-center">16</th>
                      <th class="text-center">17</th>
                      
                    </tr>
                    <tr>
                      <th class="text-center"></th>
                      <th class="text-center"></th>
                      <th class="text-center"></th>
                      <th class="text-center"></th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center"></th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center"></th>
                      <th class="text-center">(Kyats)</th>
                      <th class="text-center"></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = 1; @endphp
                    @foreach($taxes as $user_id=>$tax)
                      <tr>
                        <td rowspan="12">{{$i}}</td>
                        <td rowspan="12">{{getUserFieldWithId($user_id,"employee_name")}}</td>
                        <td rowspan="12">{{getUserFieldWithId($user_id,"position_id")}}</td>
                        <td rowspan="12"></td>
                        @php 
                          $j=0;$total_salary = 0;$total_ot = 0;$total_bonus = 0;$total_salaray_bonus = 0;$total_ssc = 0;$total_tax = 0;
                        @endphp
                        @foreach($tax as $key=>$value)
                          @if($j!=0)
                            <tr>
                          @endif
                          <td class="text-right">{{siteformat_number($value["salary"])}}</td>
                          <td class="text-right">{{siteformat_number($value["ot"])}}</td>
                          <td class="text-right">{{siteformat_number($value["bonus"])}}</td>
                          @php
                            $salaray_bonus = $value["salary"] + $value["ot"] + $value["bonus"];
                          @endphp
                          <td class="text-right">{{siteformat_number($salaray_bonus)}}</td>
                          <td class="text-right">0</td>
                          <td class="text-right">0</td>
                          <td class="text-right">{{siteformat_number($value["ssc"])}}</td>

                          <td class="text-right">0</td>
                          <td class="text-right">{{siteformat_number($value["ssc"])}}</td>
                          <td class="text-right">0</td>
                          <td class="text-right">{{getNumberOfChildren($user_id)}}</td>
                          <td class="text-right">{{siteformat_number($value["tax"])}}</td>
                          <td>{{$value["pay_for"]}}</td>
                        </tr>

                          @php
                            $j += 1;
                            $total_salary += $value["salary"];
                            $total_ot += $value["ot"];
                            $total_bonus += $value["bonus"];
                            $total_salaray_bonus += $salaray_bonus;
                            $total_ssc += $value["ssc"];
                            $total_tax += $value["tax"];
                          @endphp
                        @endforeach
                        <tr>
                          <th colspan="4">Total</th>
                          <th class="text-right">{{siteformat_number($total_salary)}}</th>
                          <th class="text-right">{{siteformat_number($total_ot)}}</th>
                          <th class="text-right">{{siteformat_number($total_bonus)}}</th>
                          <th class="text-right">{{siteformat_number($total_salaray_bonus)}}</th>
                          <th class="text-right">0</th>
                          <th class="text-right">0</th>
                          <th class="text-right">{{siteformat_number($total_ssc)}}</th>
                          <th class="text-right">0</th>
                          <th class="text-right">{{siteformat_number($total_ssc)}}</th>
                          <th class="text-right">0</th>
                          <th class="text-right">{{getNumberOfChildren($user_id)}}</th>
                          <th class="text-right">{{siteformat_number($total_tax)}}</th>
                          <th>FY ({{$first_year}} - {{$last_year}})</th>
                        </tr>
                      @php $i += 1; @endphp
                    @endforeach
                      
                  </tbody>
                  <tfoot>
                    
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