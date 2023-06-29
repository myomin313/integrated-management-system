@extends('layouts.master')
@section('title','SSC Report')
@section("style")
  <style type="text/css">
    #ssc_table::-webkit-scrollbar {
      display: block;
      width: 5px;
    }
    #ssc_table::-webkit-scrollbar-track {
        background: transparent;
    }
        
    #ssc_table::-webkit-scrollbar-thumb {
        background-color: #999;
        border-right: none;
        border-left: none;
    }
    #ssc_table::-webkit-scrollbar-track-piece:end {
        background: transparent;
    }

    #ssc_table::-webkit-scrollbar-track-piece:start {
        background: transparent;
    }
  </style>
    

    
@stop
@section("content")
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item">Tax Management</li>
              <li class="breadcrumb-item active"><a href="{{url('tax-management/ssc-report')}}">SSC Report</a></li>
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
                <form action="{{url('tax-management/ssc-report')}}" method="get">
                  @php
                    $today_date=\Carbon\Carbon::now()->format('m/Y');
                    
                    $from_date=app('request')->get('from_date');
                    $to_date=app('request')->get('to_date');
                    $employee=app('request')->get('employee');
                    $branch=app('request')->get('branch');
                    
                  @endphp
                  <div class="row">
                    
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
                    <div class="col-sm-2">
                      <div class="form-group" style="margin-top: 8px;">
                        <label>Branch</label>
                        <select class="form-control select2bs4" name="branch" id="branch" style="width: 100%;">
                          <option selected="selected" value="all">- All -</option>
                          @foreach($branches as $key=>$value)
                            <option value="{{$value->id}}" {{$value->id==$branch?'selected':''}}>{{$value->name}}</option>    
                          @endforeach    
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>From Date</label>
                        <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                          <input type="text" name="from_date" id="from_date" required placeholder="YYYY" value="{{isset($from_date)?$from_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                          <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 8px;">
                        <label>To Date</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                          <input type="text" name="to_date" id="to_date" required placeholder="YYYY" value="{{isset($to_date)?$to_date:$today_date}}" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                          <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    

                    <div class="col-sm-4">
                      <!-- text input -->
                      <div class="form-group" style="margin-top: 37px;">
                          <button type="submit" class="btn btn-primary">Search</button>
                          <a href="{{url('tax-management/ssc-report')}}" class="btn btn-warning text-white">Reset</a>
                          
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
                    <h3 class="card-title">SSC Report</h3>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a href="{{route('tax.ssc-report-download',['from_date'=>isset($from_date)?$from_date:$today_date,'to_date'=>isset($to_date)?$to_date:$today_date,'employee'=>isset($employee)?$employee:'all','branch'=>isset($branch)?$branch:'all'])}}" class="btn btn-success text-white breadcrumb-btn"><i class="fas fa-download"></i> Export</a>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive table-scroll" id="ssc_table" style="height:600px;">
                <table class="table main-table" id="main-table">
                  <thead style="color: #111;position: -webkit-sticky;position: sticky;top: 0;z-index: 5;">
                    <tr>
                      <td style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;vertical-align: bottom;" rowspan="2">Sr.<br> No. <br> စဥ်</td>
                      <td style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;vertical-align: bottom;" rowspan="2">Month</td>
                      <th style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;" rowspan="2">Insurance Name<br> အာမခံထားသူအမည်</th>
                      <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;" rowspan="2">
                        Position<br>ရာထူးအမည်
                      </th>
                      <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;" rowspan="2">
                        SSN No.<br>အာမခံစိစစ်ရေးအမှတ်
                      </th>
                      <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;" rowspan="2">
                          Payment<br>လုပ်ခ လစာ (ကျပ်)
                      </th>
                      <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;" colspan="2">
                        Health and Social Care Insurance System <br>ကျန်းမာရေးနှင့်  လူမှုရေး စောင့်ရှောက်မှု အာမခံစနစ်
                      </th>
                      <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;">
                        Employment Injury Benefit Insurance System<br> အလုပ်တွင်ထိခိုက်မှု အကျိုးခံစားခွင့် အာမခံစနစ်
                      </th>
                      <th style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;" colspan="3">
                        Total<br> စုစုပေါင်း
                      </th>


                      <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;" rowspan="2">Remark<br> မှတ်ချက်</th>
                    </tr>
                    <tr>
                      <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;">
                        Employer <br>အလုပ်ရှင် 2% (ကျပ်)
                        </th>
                        <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;">
                        Employee <br>အလုပ်သမား 2% (ကျပ်)
                        </th>
                        <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;">
                        Employer အလုပ်ရှင်<br> 1% (ကျပ်)
                        </th>
                        <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;">
                        Employer <br> အလုပ်ရှင် 3% (ကျပ်)
                        </th>
                        <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;">
                        Employee <br>အလုပ်သမား 2% (ကျပ်)
                        </th>
                        <th class="" style="text-align: center;font-weight: bold;border: 1px solid #111;background: #fff;">
                        Total <br>စုစုပေါင်း 5% (ကျပ်)
                        </th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $total_employer = 0;$total_employee = 0;$total = 0; 

                    @endphp
                    @foreach($sscs as $employee_type=>$ssc)
                      @php
                        $sub_total_employer = 0;$sub_total_employee = 0;$sub_total = 0;
                      @endphp
                      <tr>
                        <td colspan="13" style="border: 1px solid #111;font-weight: bold">{{getEmployeeType($employee_type)}}</td>
                      </tr>
                      @foreach($ssc as $key=>$value)
                      <tr>
                        <td style="border: 1px solid #111;">{{$key+1}}</td>
                        <td style="border: 1px solid #111;">{{\Carbon\Carbon::parse($value->date)->format("F, Y")}}</td>
                        <td style="border: 1px solid #111;">{{getUserFieldWithId($value->user_id,"employee_name")}}</td>
                        
                        <td style="border: 1px solid #111;">{{getUserFieldWithId($value->user_id,"position_id")}}</td>
                        <td style="border: 1px solid #111;">{{getUserFieldWithId($value->user_id,"ssc_no")}}</td>
                        <td style="border: 1px solid #111;" class="text-right">{{siteformat_number($value->salary_mmk)}}</td>
                        @php
                          $employer3percent = $value->salary_mmk * 3 / 100;
                          $employer1percent = $value->salary_mmk * 1 / 100;
                          $employer2percent = $value->salary_mmk * 2 / 100;
                          $employee = $value->salary_mmk * 2 / 100;
                          $total_employer += $employer3percent;
                          $total_employee += $employee;
                          $total += $employer3percent;
                          $total += $employee;

                          $sub_total_employer += $employer3percent;
                          $sub_total_employee += $employee;
                          $sub_total += $employer3percent;
                          $sub_total += $employee;
                        @endphp
                        <td style="border: 1px solid #111;" class="text-right">{{siteformat_number($employer2percent)}}</td>
                        <td style="border: 1px solid #111;" class="text-right">{{siteformat_number($employee)}}</td>
                        <td style="border: 1px solid #111;" class="text-right">{{siteformat_number($employer1percent)}}</td>
                        <td style="border: 1px solid #111;" class="text-right">{{siteformat_number($employer3percent)}}</td>
                        <td style="border: 1px solid #111;" class="text-right">{{siteformat_number($employee)}}</td>
                        <td style="border: 1px solid #111;" class="text-right">{{siteformat_number($employer3percent + $employee)}}</td>
                        <td style="border: 1px solid #111;">{{$value->remark}}</td>
                        
                      </tr>
                      @endforeach
                      <tr>
                        <td style="border: 1px solid #111;font-weight: bold;" colspan="11" class="text-right">Sub Total</td>
                        <td style="border: 1px solid #111;font-weight: bold;" class=" text-right">{{siteformat_number($sub_total)}}</td>
                        <td style="border: 1px solid #111;" class="text-bold"></td>
                      </tr>
                      
                    @endforeach
                    <tr>
                        <td style="border: 1px solid #111;font-weight: bold;" colspan="11" class="text-right">All Total</td>
                        <td style="border: 1px solid #111;font-weight: bold;" class=" text-right">{{siteformat_number($total)}}</td>
                        <td style="border: 1px solid #111;" class="text-bold"></td>
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