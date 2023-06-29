<aside id="mysidebar" class="main-sidebar sidebar-light-secondary elevation-4" style="background: #efefef;color: #111 !important;font-weight: bold;">
  <!-- Brand Logo -->
  <a href="{{url('/')}}" class="brand-link bg-white">
    <img src="{{asset('dist/img/marubeni.jpg')}}" alt="Marubeni Home" class="brand-image" style="width: 200px;"><br>
    <span class="font-weight-bold" style="font-size: 16px;margin-top: 0px;padding-top: 0px;">Integrated Management System</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3  d-flex border-bottom-0" style="margin: 0px !important;padding-bottom: 0px !important;">
      <div class="image mt-2">
          @if(auth()->user()->photo_name)
            <img src="{{ url('/employee/'.auth()->user()->photo_name ) }}"  class="img-circle elevation-2" alt="User Image">
          @else
            <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
          @endif
      </div>
      <div class="info">
        <a href="#" class="d-block">{{Auth::user()->employee_name?Auth::user()->employee_name:Auth::user()->name}}</a>
        
        <span style="font-size:12px;">{{getPositionName(Auth::user()->position_id)}}</span>
        
      </div>
    </div>


    <!-- Sidebar Menu -->
    <nav class="mt-2 sidebar-nav">
      <ul class="nav nav-09op nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{url('/')}}" class="nav-link {{classActiveSegment(1,'dashboard')}}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        @canany(['raw-attendance-list','attendance-change-request','late-record-list'])
        <li class="nav-item {{classActiveSegment(1,'attendance-management')}}">
          <a href="#" class="nav-link {{classActiveSegment(1,'attendance-management')}}">
            <i class="nav-icon far fa-calendar"></i>
            <p>
              Attendance Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('raw-attendance-list')
            <li class="nav-item">
              
              <a href="{{url('attendance-management/raw-attendance/list')}}" class="nav-link {{classActiveSegment(2,'raw-attendance')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Raw Attendance List</p>
              </a>
            </li>
            @endcan
            {{-- <li class="nav-item border-width-2  border-white">
              <a href="{{url('attendance-management/attendance-detail-list')}}" class="nav-link {{classActivePath('attendance-management/attendance-detail-list')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Attendance Detail List</p>
              </a>
            </li> --}}
            @can('attendance-change-request')
            <li class="nav-item">
              <a href="{{url('attendance-management/change-request/list')}}" class="nav-link {{classActiveSegment(2,'change-request')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Attendance Change Request</p>
              </a>
            </li>
            @endcan
            @can('late-record-list')
            <li class="nav-item border-width-2  border-white">
              <a href="{{url('attendance-management/late-record')}}" class="nav-link {{classActivePath('attendance-management/late-record')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Late Record List</p>
              </a>
            </li>
            @endcan

          </ul>
        </li>
        @endcanany
        @canany(['daily-ot-request-list','monthly-ot-request-list','monthly-ot-summary-list','annual-ot-summary-list'])
        <li class="nav-item {{classActiveSegment(1,'ot-management')}}">
          <a href="#" class="nav-link {{classActiveSegment(1,'ot-management')}}">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              OT Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('daily-ot-request-list')
            <li class="nav-item">
              <a href="{{url('ot-management/daily-ot-request/list')}}" class="nav-link {{classActiveSegment(2,'daily-ot-request')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Daily OT Request List</p>
              </a>
            </li>
            @endcan
            @if(Auth::user()->can('change-ot-manager-status') or Auth::user()->can('change-ot-account-status') or Auth::user()->can('change-ot-gm-status') or (!isDriver(Auth::user()->id) and !isAssistant(Auth::user()->id) and !isReceptionist(Auth::user()->id)))
            <li class="nav-item {{classActiveSegment(2,'monthly-ot-staff')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'monthly-ot-staff')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Monthly OT (Staff)
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview nav-sub-treeview">
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-staff/monthly-ot-by-staff')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-staff/monthly-ot-by-staff')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Monthly OT by Staff</p>
                  </a>
                </li>
                @can('change-ot-manager-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-staff/approved-by-dept-gm')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-staff/approved-by-dept-gm')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Approved by Dept GM</p>
                  </a>
                </li>
                @endcan
                @can('change-ot-account-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-staff/approved-by-account')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-staff/approved-by-account')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Approved by Account</p>
                  </a>
                </li>
                @endcan
                @can('change-ot-gm-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-staff/approved-by-admin-gm')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-staff/approved-by-admin-gm')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Approved by Admin GM</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endif
            @if(Auth::user()->can('change-ot-admin-status') or Auth::user()->can('change-ot-account-status') or Auth::user()->can('change-ot-gm-status') or isDriver(Auth::user()->id) or isAssistant(Auth::user()->id))
            <li class="nav-item {{classActiveSegment(2,'monthly-ot-driver')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'monthly-ot-driver')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Monthly OT (Dri & Ass)
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview nav-sub-treeview">
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-driver/monthly-ot-by-staff')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-driver/monthly-ot-by-staff')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Monthly OT by Staff</p>
                  </a>
                </li>
                @can('change-ot-admin-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-driver/approved-by-admin')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-driver/approved-by-admin')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Approved by Admin</p>
                  </a>
                </li>
                @endcan
                @can('change-ot-account-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-driver/approved-by-account')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-driver/approved-by-account')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Approved by Account</p>
                  </a>
                </li>
                @endcan
                @can('change-ot-gm-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-ot-driver/approved-by-admin-gm')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-driver/approved-by-admin-gm')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Approved by Admin GM</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endif
            @if(Auth::user()->can('change-ot-admin-status') or Auth::user()->can('change-ot-gm-status') or isReceptionist(Auth::user()->id))
            <li class="nav-item {{classActiveSegment(2,'monthly-receptionist')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'monthly-receptionist')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Receptionist Salary
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview nav-sub-treeview">
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-receptionist/monthly-record-by-staff')}}" class="nav-link {{classActivePath('ot-management/monthly-receptionist/monthly-record-by-staff')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Monthly OT by Staff</p>
                  </a>
                </li>
                @can('change-ot-admin-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-receptionist/approved-by-admin')}}" class="nav-link {{classActivePath('ot-management/monthly-receptionist/approved-by-admin')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Approved by Admin</p>
                  </a>
                </li>
                @endcan
                @can('change-ot-gm-status')
                <li class="nav-item">
                  <a href="{{url('ot-management/monthly-receptionist/approved-by-admin-gm')}}" class="nav-link {{classActivePath('ot-management/monthly-receptionist/approved-by-admin-gm')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Approved by Admin GM</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endif
            @can('monthly-ot-summary-list')
            <li class="nav-item">
              <a href="{{url('ot-management/monthly-ot-summary')}}" class="nav-link {{classActivePath('ot-management/monthly-ot-summary')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Monthly OT Summary List</p>
              </a>
            </li>
            @endcan
            @can('annual-ot-summary-list')
            <li class="nav-item">
              <a href="{{url('ot-management/annual-ot-summary')}}" class="nav-link {{classActivePath('ot-management/annual-ot-summary')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Annual OT Summary List</p>
              </a>
            </li>
            @endcan
            
          </ul>
        </li>
        @endcanany
        <li class="nav-item {{classActiveSegment(1,'employee-management')}}">
          <a href="#" class="nav-link  {{classActiveSegment(1,'employee-management')}}">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Employee Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('employee.ns-list') }}" class="nav-link {{classActivePath('employee-management/ns-list')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>NS Employee List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('employee.rs-list') }}" class="nav-link {{classActivePath('employee-management/rs-list')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>RS Employee List</p>
              </a>
            </li>

          </ul>
        </li>
        
        @canany(['rs-salary-list','ns-salary-list','calculate-salary','payslip-detail','monthly-salary-list','pay-list-bank','pay-list-ns','pay-list-jpn'])
        <li class="nav-item {{classActiveSegment(1,'salary-management')}}">
          <a href="#" class="nav-link {{classActiveSegment(1,'salary-management')}}">
            <i class="nav-icon fas fa-dollar-sign"></i>
            <p>
              Salary Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @canany(['rs-salary-list','ns-salary-list','calculate-salary'])
            <li class="nav-item {{classActiveSegment(2,'ns-salary')}} {{classActiveSegment(2,'rs-salary')}} {{classActiveSegment(2,'calculate-salary')}} {{classActiveSegment(2,'calculate-salary-form')}} {{classActiveSegment(2,'payment-exchange-rate')}} {{classActiveSegment(2,'add-salary')}} {{classActiveSegment(2,'add-salary-form')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'ns-salary')}} {{classActiveSegment(2,'rs-salary')}} {{classActiveSegment(2,'calculate-salary')}}" {{classActiveSegment(2,'calculate-salary-form')}} {{classActiveSegment(2,'payment-exchange-rate')}}>
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Salary Management
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview nav-sub-treeview">
                @can('rs-salary-list')
                <li class="nav-item">
                  <a href="{{url('salary-management/rs-salary/list')}}" class="nav-link {{classActivePath('salary-management/rs-salary/list')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>RS Salary List</p>
                  </a>
                </li>
                @endcan
                @can('ns-salary-list')
                <li class="nav-item">
                  <a href="{{url('salary-management/ns-salary/list')}}" class="nav-link {{classActivePath('salary-management/ns-salary/list')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>NS Salary List</p>
                  </a>
                </li>
                @endcan
                @can('exchange-rate')
                <li class="nav-item">
                  <a href="{{url('salary-management/payment-exchange-rate/list')}}" class="nav-link {{classActivePath('salary-management/payment-exchange-rate/list')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Payment Exchange Rate</p>
                  </a>
                </li>
                @endcan
                @can('calculate-salary')
                
                <li class="nav-item">
                  <a href="{{url('salary-management/calculate-salary')}}" class="nav-link {{classActivePath('salary-management/calculate-salary')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Calculate Salary</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('salary-management/add-salary')}}" class="nav-link {{classActivePath('salary-management/add-salary')}} {{classActivePath('salary-management/add-salary-form')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Add Salary (April, 2023)</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcanany
            @canany(['payslip-detail','monthly-salary-list','pay-list-bank','pay-list-ns','pay-list-jpn'])
            <li class="nav-item {{classActiveSegment(2,'payslip-list')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'payslip-list')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Payslip List
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview nav-sub-treeview">
                @can('payslip-detail')
                <li class="nav-item">
                  <a href="{{url('salary-management/payslip-list/detail')}}" class="nav-link {{classActivePath('salary-management/payslip-list/detail')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Pay Slip Detail</p>
                  </a>
                </li>
                @endcan
                @can('monthly-salary-list')
                <li class="nav-item">
                  <a href="{{url('salary-management/payslip-list/monthy-salary')}}" class="nav-link {{classActivePath('salary-management/payslip-list/monthy-salary')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Monthly Salary List</p>
                  </a>
                </li>
                @endcan
                @can('pay-list-bank')
                <li class="nav-item">
                  <a href="{{url('salary-management/payslip-list/bank')}}" class="nav-link {{classActivePath('salary-management/payslip-list/bank')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Pay List (Bank)</p>
                  </a>
                </li>
                @endcan
                @can('pay-list-ns')
                <li class="nav-item">
                  <a href="{{url('salary-management/payslip-list/ns-internal')}}" class="nav-link {{classActivePath('salary-management/payslip-list/ns-internal')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Pay List (NS) Internal</p>
                  </a>
                </li>
                @endcan
                @can('pay-list-jpn')
                <li class="nav-item">
                  <a href="{{url('salary-management/payslip-list/jpn-internal')}}" class="nav-link {{classActivePath('salary-management/payslip-list/jpn-internal')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Pay List (JPN) Internal</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li> 
            @endcanany        

          </ul>
        </li>
        @endcanany
        @canany(['ns-actual-tax-list','rs-actual-tax-list','ssc-report','monthly-tax-statement','monthly-paye','ns-actual-tax','rs-actual-tax','annual-paid-personal','tax-office-submission','exchange-rate'])
        <li class="nav-item {{classActiveSegment(1,'tax-management')}}">
          <a href="#" class="nav-link {{classActiveSegment(1,'tax-management')}}">
            <i class="nav-icon fas fa-money-bill"></i>
            <p>
              Tax Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @canany(['ns-actual-tax-list','create-ns-actual-tax','edit-ns-actual-tax','delete-ns-actual-tax','rs-actual-tax-list','create-rs-actual-tax','edit-rs-actual-tax','delete-rs-actual-tax'])
            <li class="nav-item {{classActiveSegment(2,'actual-tax')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'actual-tax')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Add Actual Income Tax
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview nav-sub-treeview">
                @canany(['ns-actual-tax-list','create-ns-actual-tax','edit-ns-actual-tax','delete-ns-actual-tax'])
                <li class="nav-item">
                  <a href="{{url('tax-management/actual-tax/ns-income-tax-list')}}" class="nav-link {{classActivePath('tax-management/actual-tax/ns-income-tax-list')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>NS Actual Income Tax</p>
                  </a>
                </li>
                @endcanany
                @canany(['rs-actual-tax-list','create-rs-actual-tax','edit-rs-actual-tax','delete-rs-actual-tax'])
                <li class="nav-item">
                  <a href="{{url('tax-management/actual-tax/rs-income-tax-list')}}" class="nav-link {{classActivePath('tax-management/actual-tax/rs-income-tax-list')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>RS Actual Income Tax</p>
                  </a>
                </li>
                @endcanany
              </ul>
            </li>
            @endcanany
            @can('ssc-report')
            <li class="nav-item">
              <a href="{{url('tax-management/ssc-report')}}" class="nav-link {{classActivePath('tax-management/ssc-report')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>SSC</p>
              </a>
            </li>
            @endcan
            @can('monthly-tax-statement')
            <li class="nav-item">
              <a href="{{url('tax-management/monthly-tax-statement')}}" class="nav-link {{classActivePath('tax-management/monthly-tax-statement')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Monthly Tax Statement</p>
              </a>
            </li>
            @endcan
            @can('monthly-paye')
            <li class="nav-item">
              <a href="{{url('tax-management/monthly-paye-report')}}" class="nav-link {{classActivePath('tax-management/monthly-paye-report')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Monthly PAYE</p>
              </a>
            </li>
            @endcan

            @canany(['ns-actual-tax','rs-actual-tax','annual-paid-personal','tax-office-submission'])
            <li class="nav-item {{classActiveSegment(2,'annual-tax')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'annual-tax')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Annual Tax Statement
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview nav-sub-treeview">
                @can('ns-actual-tax')
                <li class="nav-item">
                  <a href="{{url('tax-management/annual-tax/ns-tax-payment-report')}}" class="nav-link {{classActivePath('tax-management/annual-tax/ns-tax-payment-report')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Actual Tax Payment (NS)</p>
                  </a>
                </li>
                @endcan
                @can('rs-actual-tax')
                <li class="nav-item">
                  <a href="{{url('tax-management/annual-tax/rs-tax-payment-report')}}" class="nav-link {{classActivePath('tax-management/annual-tax/rs-tax-payment-report')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Actual Tax Payment (JPN)</p>
                  </a>
                </li>
                @endcan
                @can('annual-paid-personal')
                <li class="nav-item">
                  <a href="{{url('tax-management/annual-tax/deducted-paid-personal-report')}}" class="nav-link {{classActivePath('tax-management/annual-tax/deducted-paid-personal-report')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Annual Deducted & Paid Personal</p>
                  </a>
                </li>
                @endcan
                @can('tax-office-submission')
                <li class="nav-item">
                  <a href="{{url('tax-management/annual-tax/tax-office-submission')}}" class="nav-link {{classActivePath('tax-management/annual-tax/tax-office-submission')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p style="font-size:13px !important;">Annual Tax Office Submission</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endcanany
            @can('exchange-rate')
            <li class="nav-item">
              <a href="{{url('tax-management/exchange-rate-detail')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Exchange Rate Detail</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{url('tax-management/exchange-rate-summary')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Exchange Rate Summary</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcanany
        
        @if(auth()->user()->employee_type_id !== 4 )
        <li class="nav-item {{classActiveSegment(1,'leave-management')}}">
          <a href="#" class="nav-link {{classActiveSegment(1,'leave-management')}}">
            <i class="nav-icon fas fa-calendar-minus"></i>
            <p>
              Leave Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
               @can('list-of-yearly-leave')
            <li class="nav-item">
              <a href="{{url('/leave-management/remaining-days')}}" class="nav-link {{classActivePath('leave-management/remaining-days')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>List Of Yearly Leave</p>
              </a>
            </li>
              @endcan
            <li class="nav-item">
              <a href="{{url('/leave-management/leave-requests')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Leave Request History</p>
              </a>
            </li>
             @can('leave-approve-by-dep-manager')
            <li class="nav-item">
              <a href="{{url('/leave-management/leave-requests-approve')}}" class="nav-link {{classActivePath('leave-management/leave-requests-approve')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Leave Request History By Dep Manager</p>
              </a>
            </li>
            @endcan
             @can('leave-approve-by-admi-gm')
            <li class="nav-item">
              <a href="{{url('/leave-management/leave-requests-admin-approve')}}" class="nav-link {{classActivePath('/leave-management/leave-requests-admin-approve')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Leave Request History By Admin</p>
              </a>
            </li>
             @endcan
             
                @can('leave-approve-by-gm')
            <li class="nav-item">
              <a href="{{url('/leave-management/leave-requests-approve-gm')}}" class="nav-link {{classActivePath('/leave-management/leave-requests-admin-approve')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Leave Request History By GM</p>
              </a>
            </li>
             @endcan


          </ul>
        </li>
        @endif
        
        <li class="nav-item {{classActiveSegment(1,'car-management')}}">
          <a href="#" class="nav-link {{classActiveSegment(1,'car-management')}}">
            <i class="nav-icon fas fa-car"></i>
            <p>
              Car Management
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
             @can('car-registration')
            <li class="nav-item {{classActiveSegment(2,'cars')}}">
              <a href="{{ url('car-management/cars') }}" class="nav-link  {{classActivePath('car-management/cars')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Car Registration</p>
              </a>
            </li>
             @endcan
             
              @canany(['car-insurance-claim-list','car-insurance-list'])
              
            <li class="nav-item {{classActiveSegment(2,'insurance-management')}}">
              <a href="#" class="nav-link {{classActiveSegment(2,'insurance-management')}} >
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Car Insurance Management
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('car-insurance-list')
                <li class="nav-item">
                  <a href="{{ url('car-management/insurance-management/car-insurances') }}" class="nav-link {{classActivePath('car-management/insurance-management/car-insurances')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Car Insurance List</p>
                  </a>
                </li>
                 @endcan
                  @can('car-insurance-claim-list')
                <li class="nav-item">
                  <a href="{{ url('car-management/insurance-management/car-insurance-claim-histories') }}" class="nav-link {{classActivePath('car-management/insurance-management/car-insurance-claim-histories')}}">
                   <i class="far fa-star nav-icon"></i>
                    <p>Car Insurance Claim List</p>
                  </a>
                </li>
                 @endcan
              </ul>
            </li>
            @endcan
            
             @can('car-maintanance-repair-list')
            <li href="#" class="nav-item {{classActiveSegment(2,'car-repair-and-maintanances')}}">
              <a href="{{ url('car-management/car-repair-and-maintanances') }}" 
              class="nav-link {{classActivePath('car-management/car-repair-and-maintanances')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Maintanance & Repair</p>
              </a>
            </li>
              @endcan
              
               @canany(['car-fueling-list','car-license-list','car-mileage-list'])
            <li class="nav-item {{classActiveSegment(2,'monthly-car-management')}}">
              <a href="pages/layout/top-nav-sidebar.html" class="nav-link  {{classActiveSegment(2,'monthly-car-management')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Monthly Car Management
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
              @can('car-fueling-list')
                <li class="nav-item">
                  <a href="{{ url('car-management/monthly-car-management/car-fuelings') }}" class="nav-link {{classActivePath('car-management/monthly-car-management/car-fuelings')}}">
                   <i class="far fa-star nav-icon"></i>
                    <p>Car Fueling List</p>
                  </a>
                </li>
                @endcan
                 @can('car-license-list')
                <li class="nav-item">
                  <a href="{{ url('car-management/monthly-car-management/car-licenses') }}" class="nav-link {{classActivePath('car-management/monthly-car-management/car-licenses')}}">
                  <i class="far fa-star nav-icon"></i>
                    <p>Car License List</p>
                  </a>
                </li>
                 @endcan
                @can('car-mileage-list')
                <li class="nav-item">
                  <a href="{{ url('car-management/monthly-car-management/car-mileages') }}" class="nav-link {{classActivePath('car-management/monthly-car-management/car-mileages')}}">
                    <i class="far fa-star nav-icon"></i>
                    <p>Car Mileage List</p>
                  </a>
                </li>
                 @endcan
              </ul>
            </li>
            @endcan
          </ul>
        </li>
        <li class="nav-item {{classActiveSegment(1,'master-management')}}">
          <a href="#" class="nav-link {{classActiveSegment(1,'master-management')}}">
            <i class="nav-icon fas fa-cog"></i>
            <p>
              Master Management
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
          
           @can('user-list')
            <li class="nav-item">
              <a href="{{url('master-management/user/list')}}" class="nav-link  {{classActivePath('master-management/user/list')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>User Management</p>
              </a>
            </li>
             @endcan
              @can('branch-list')
            <li class="nav-item">
              <a href="{{ url('master-management/branches') }}" class="nav-link {{classActivePath('master-management/branches')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Branch Management</p>
              </a>
            </li>
             @endcan
              @can('department-list')
            <li class="nav-item">
              <a href="{{ url('master-management/departments') }}" class="nav-link {{classActivePath('master-management/departments')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Department Management</p>
              </a>
            </li>
              @endcan
            @can('holiday-type-list')
            <li class="nav-item">
              <a href="{{ url('master-management/holiday-types') }}" class="nav-link {{classActivePath('master-management/holiday-types')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Holiday Type Management</p>
              </a>
            </li>
             @endcan
              @can('holiday-list')
            <li class="nav-item ">
              <a href="{{ url('master-management/holidays') }}" class="nav-link {{classActivePath('master-management/holidays')}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Holiday Management</p>
              </a>
            </li>
            @endcan
             @can('employee-type-list')
            <li class="nav-item {{ classActivePath('master-management/employee-types') }}">
              <a href="{{ url('master-management/employee-types') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Employee Type Management</p>
              </a>
            </li>
            @endcan
            @can('bank-list')
            <li class="nav-item">
              <a href="{{ url('master-management/banks') }}" class="nav-link {{ classActivePath('master-management/banks') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Bank Management</p>
              </a>
            </li>
            @endcan
            @can('leave-type-list')
            <li class="nav-item">
              <a href="{{ url('master-management/leavetypes') }}" class="nav-link  {{ classActivePath('master-management/leavetypes') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Leave Type Management</p>
              </a>
            </li>
            @endcan
            @can('alert-car-license-expire')
             <li class="nav-item">
               <a href="{{ url('master-management/alert/car-license-noti') }}" class="nav-link  {{ classActivePath('master-management/alerts') }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Alert For Car License Expire</p>
               </a>
             </li>
            @endcan
            <!--@can('role-list')-->
            <!-- <li class="nav-item">-->
            <!--  <a href="{{url('master-management/roles')}}" class="nav-link {{classActivePath('master-management/roles')}}">-->
            <!--    <i class="far fa-circle nav-icon"></i>-->
            <!--    <p>Role Management</p>-->
            <!--  </a>-->
            <!--</li>-->
            <!-- @endcan-->
          </ul>
        </li>


      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>