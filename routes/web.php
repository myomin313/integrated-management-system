<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MasterManagement\UserController;
use App\Http\Controllers\AttendanceManagement\RawAttendanceController;
use App\Http\Controllers\AttendanceManagement\ChangeRequestController;
use App\Http\Controllers\OTManagement\DailyOTRequestController;
use App\Http\Controllers\OTManagement\MonthlyOTRequestController;
use App\Http\Controllers\OTManagement\MonthlyDriverOTRequestController;
use App\Http\Controllers\OTManagement\MonthlyReceptionistRequestController;

use App\Http\Controllers\BankController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\HolidayTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarInsuranceController;
use App\Http\Controllers\CarInsuranceClaimHistoryController;
use App\Http\Controllers\CarLicenseController;
use App\Http\Controllers\CarRepairAndMaintananceController;
use App\Http\Controllers\CarFuelingController;
use App\Http\Controllers\CarMileageController;

use App\Http\Controllers\SalaryManagement\NSSalaryController;
use App\Http\Controllers\SalaryManagement\RSSalaryController;
use App\Http\Controllers\SalaryManagement\SalaryController;
use App\Http\Controllers\SalaryManagement\ManualSalaryController;
use App\Http\Controllers\SalaryManagement\PaymentExchangeRateController;

use App\Http\Controllers\TaxManagement\TaxController;
use App\Http\Controllers\TaxManagement\NsTaxController;
use App\Http\Controllers\TaxManagement\RsTaxController;

use App\Http\Controllers\LeaveManagement\LeaveController;
use App\Http\Controllers\EmployeeManagement\EmployeeController;
use App\Http\Controllers\MasterManagement\CarLicenseNotiController;


use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



use Illuminate\Support\Facades\Mail;
use App\Mail\LoginAttempt;
use Illuminate\Http\Request;
use App\Models\User;
use Stevebauman\Location\Facades\Location;
use Carbon\Carbon;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
Route::get('/add-permission-salary', function () {
        $per = DB::transaction(function ()  {
        $permissions=[
                [
                    'name'=>'salary-read-all',
                    'display_name'=>'Read All',
                    'description'=>'The login user can see the record list of all staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'salary-read-group',
                    'display_name'=>'Read Group',
                    'description'=>'The login user can see the record list of all staff with the same department in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'salary-read-one',
                    'display_name'=>'Read One',
                    'description'=>'The login user can only see their own record list in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'rs-salary-list',
                    'display_name'=>'RS Salary List',
                    'description'=>'The login user can see the RS Salary List in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'edit-rs-salary',
                    'display_name'=>'Edit RS Salary',
                    'description'=>'The login user can edit the RS Salary List in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'ns-salary-list',
                    'display_name'=>'NS Salary List',
                    'description'=>'The login user can see the NS Salary List in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'edit-ns-salary',
                    'display_name'=>'Edit NS Salary',
                    'description'=>'The login user can edit the NS Salary List in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'calculate-salary',
                    'display_name'=>'Salary Calculation',
                    'description'=>'The login user can make the salary calculation for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'edit-salary-calculation',
                    'display_name'=>'Edit Salary Calculation',
                    'description'=>'The login user can edit the salary calculation for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'payslip-detail',
                    'display_name'=>'Pay Slip Detail',
                    'description'=>'The login user can see the Pay Slip Detail for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'monthly-salary-list',
                    'display_name'=>'Monthly Salary List',
                    'description'=>'The login user can see the Monthly Salary List for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'monthly-salary-list-download',
                    'display_name'=>'Export Monthly Salary List',
                    'description'=>'The login user can download as excel format the Monthly Salary List for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'pay-list-bank',
                    'display_name'=>'Pay List (Bank)',
                    'description'=>'The login user can see the Pay List (Bank) for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'pay-list-bank-download',
                    'display_name'=>'Export Pay List (Bank)',
                    'description'=>'The login user can download as excel format the Pay List (Bank) for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'pay-list-ns',
                    'display_name'=>'Pay List (NS) Internal',
                    'description'=>'The login user can see the Pay List (NS) Internal for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'pay-list-ns-download',
                    'display_name'=>'Export Pay List (NS) Internal',
                    'description'=>'The login user can download as excel format the Pay List (NS) Internal for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'pay-list-jpn',
                    'display_name'=>'Pay List (JPN) Internal',
                    'description'=>'The login user can see the Pay List (JPN) Internal for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'pay-list-jpn-download',
                    'display_name'=>'Export Pay List (JPN) Internal',
                    'description'=>'The login user can download as excel format the Pay List (JPN) Internal for the Staff in salary management',
                    'type'=>'Salary Management',
                    'guard_name'=>'web'
                ]
                

            ];

            foreach ($permissions as $key => $value) {
                Permission::create($value);
            }
    });
});
Route::get('/add-permission', function () {
        $per = DB::transaction(function ()  {
        $permissions=[
                [
                    'name'=>'tax-read-all',
                    'display_name'=>'Read All',
                    'description'=>'The login user can see the record list of all staff in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'tax-read-group',
                    'display_name'=>'Read Group',
                    'description'=>'The login user can see the record list of all staff with the same department in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'tax-read-one',
                    'display_name'=>'Read One',
                    'description'=>'The login user can only see their own record list in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'ns-actual-tax-list',
                    'display_name'=>'NS Actual Income Tax List',
                    'description'=>'The login user can see the NS Actual Income Tax List in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'create-ns-actual-tax',
                    'display_name'=>'Add NS Actual Income Tax',
                    'description'=>'The login user can add new record for NS Actual Income Tax in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'edit-ns-actual-tax',
                    'display_name'=>'Edit NS Actual Income Tax',
                    'description'=>'The login user can edit the NS Actual Income Tax in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'delete-ns-actual-tax',
                    'display_name'=>'Delete NS Actual Income Tax',
                    'description'=>'The login user can delete the NS Actual Income Tax in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'rs-actual-tax-list',
                    'display_name'=>'RS Actual Income Tax List',
                    'description'=>'The login user can see the RS Actual Income Tax List in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'create-rs-actual-tax',
                    'display_name'=>'Add RS Actual Income Tax',
                    'description'=>'The login user can add new record for RS Actual Income Tax in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'edit-rs-actual-tax',
                    'display_name'=>'Edit RS Actual Income Tax',
                    'description'=>'The login user can edit the RS Actual Income Tax in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'delete-rs-actual-tax',
                    'display_name'=>'Delete RS Actual Income Tax',
                    'description'=>'The login user can delete the RS Actual Income Tax in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],

                [
                    'name'=>'ssc-report',
                    'display_name'=>'SSC Report',
                    'description'=>'The login user can see the SSC Report in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'ssc-report-download',
                    'display_name'=>'Export SSC Report',
                    'description'=>'The login user can download as excel format the SSC Report in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],

                [
                    'name'=>'monthly-tax-statement',
                    'display_name'=>'Monthly Tax Statement',
                    'description'=>'The login user can see the Monthly Tax Statement in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'monthly-tax-statement-download',
                    'display_name'=>'Export Monthly Tax Statement',
                    'description'=>'The login user can download as excel format the Monthly Tax Statement in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],

                [
                    'name'=>'monthly-paye',
                    'display_name'=>'Monthly PAYE',
                    'description'=>'The login user can see the Monthly PAYE in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'monthly-paye-download',
                    'display_name'=>'Export Monthly PAYE',
                    'description'=>'The login user can download as excel format the Monthly PAYE in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],

                [
                    'name'=>'ns-actual-tax',
                    'display_name'=>'Actual Tax Payment (NS)',
                    'description'=>'The login user can see the Actual Tax Payment (NS) in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'ns-actual-tax-download',
                    'display_name'=>'Export Actual Tax Payment (NS)',
                    'description'=>'The login user can download as excel format the Actual Tax Payment (NS) in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                
                [
                    'name'=>'rs-actual-tax',
                    'display_name'=>'Actual Tax Payment (JPN)',
                    'description'=>'The login user can see the Actual Tax Payment (JPN) in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'rs-actual-tax-download',
                    'display_name'=>'Export Actual Tax Payment (JPN)',
                    'description'=>'The login user can download as excel format the Actual Tax Payment (JPN) in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],

                [
                    'name'=>'annual-paid-personal',
                    'display_name'=>'Annual Deducted & Paid Personal',
                    'description'=>'The login user can see the Annual Deducted & Paid Personal in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'annual-paid-personal-download',
                    'display_name'=>'Export Annual Deducted & Paid Personal',
                    'description'=>'The login user can download as excel format the Annual Deducted & Paid Personal in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],

                [
                    'name'=>'tax-office-submission',
                    'display_name'=>'Annaul Tax Office Submission',
                    'description'=>'The login user can see the Annaul Tax Office Submission in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'tax-office-submission-download',
                    'display_name'=>'Export Annaul Tax Office Submission',
                    'description'=>'The login user can download as excel format the Annaul Tax Office Submission in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],

                [
                    'name'=>'exchange-rate',
                    'display_name'=>'Exchange Rate Report',
                    'description'=>'The login user can see the Exchange Rate Report in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ],
                [
                    'name'=>'exchange-rate-download',
                    'display_name'=>'Export Exchange Rate Report',
                    'description'=>'The login user can download as excel format the Exchange Rate Report in Tax Management',
                    'type'=>'Tax Management',
                    'guard_name'=>'web'
                ]
            ];

            foreach ($permissions as $key => $value) {
                Permission::create($value);
            }
    });

});

Route::get('/test', function (Request $request) {
    return getRequestIp();
    // if(isAdministrator())
    //     return "yes";
    // return "no";
    // $str = 'Working Day_0';
    // $str_arr = explode("_", $str);
    // return $str_arr[0];

    // $month = Carbon::parse("2023-01-04")->format('F');
    // return strtolower($month);
    // $time_in = "8:15";
    // $working_time = "8:30";
    // if (strtotime($time_in) > strtotime($working_time)){
    //     return "Late";
    // }
    // else{ 
    //     return "no late";
    // }
    //return getFingerPrintIP();
    //$ip = '202.165.81.135';
    $ip = $request->ip();
    $currentUserInfo = Location::get($ip);
    echo $currentUserInfo->ip."<br>";
    echo $currentUserInfo->countryName."<br>";
    echo $currentUserInfo->countryCode."<br>";
    echo $currentUserInfo->regionCode."<br>";
    echo $currentUserInfo->regionName."<br>";
    echo $currentUserInfo->cityName."<br>";
    echo $currentUserInfo->zipCode."<br>";
    echo $currentUserInfo->latitude."<br>";
    echo $currentUserInfo->longitude."<br>";
});
// Route::get('/send-mail', function () {
//     $ip = \Request::ip();
//     //return $ip;
//     $agent = \Request::header('user-agent');
//     Mail::to('htikelay48@gmail.com')->send(new LoginAttempt($ip,$agent));
//     return 'success';
// });

Route::get('/leave-permission', function () {
        $per = DB::transaction(function ()  {
        $permissions=[
                //start myomin
             [
                'name'=>'car-read-all',
                'display_name'=>'Read All',
                'description'=>'The login user can see the record list of all car data in car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-read-group',
                'display_name'=>'Read Group',
                'description'=>'The login user can see the record list of car with the same department in car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-read-one',
                'display_name'=>'Read One',
                'description'=>'The login user can only see their own record list in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-registration',
                'display_name'=>'Car Registration',
                'description'=>'The login user can only see their car registration list in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-registration-edit',
                'display_name'=>'Car Registration Edit',
                'description'=>'The login user can Edit Car Registration   in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-registration-delete',
                'display_name'=>'Car Registration Delete',
                'description'=>'The login user can Delete  Car Registration  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-insurance-list',
                'display_name'=>'Car Insurance List',
                'description'=>'The login user can only see their car insurance list in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-insurance-edit',
                'display_name'=>'Car Insurance Edit',
                'description'=>'The login user can Edit  Car Insurance    in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-insurance-delete',
                'display_name'=>'Car Insurance Delete',
                'description'=>'The login user can  Delete Car Insurance    in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],

            [
                'name'=>'car-insurance-claim-list',
                'display_name'=>'Car Insurance Claim List',
                'description'=>'The login user can only see their car insurance claim list in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-insurance-claim-edit',
                'display_name'=>'Car Insurance Claim Edit',
                'description'=>'The login user can  Edit Insurance Claim  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-insurance-claim-delete',
                'display_name'=>'Car Insurance Claim Delete',
                'description'=>'The login user can  Delete Insurance Claim  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-maintanance-repair-list',
                'display_name'=>'Maintanance & Repair',
                'description'=>'The login user can only see their  maintanance  & repair  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],

             [
                'name'=>'car-maintanance-repair-edit',
                'display_name'=>'maintanance & Repair Edit',
                'description'=>'The login user can Edit Car maintanance  & repair    in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-maintanance-repair-delete',
                'display_name'=>'maintanance & Repair Delete',
                'description'=>'The login user can Delete Car maintanance  & repair    in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-fueling-list',
                'display_name'=>'Car Fueling List',
                'description'=>'The login user can  see Car Fueling List  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-fueling-edit',
                'display_name'=>'Car Fueling Edit',
                'description'=>'The login user can Edit Car Fueling   in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-fueling-delete',
                'display_name'=>'Car Fueling Delete',
                'description'=>'The login user can Delete Car Fueling   in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-license-list',
                'display_name'=>'Car License List',
                'description'=>'The login user can see Car License List  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-license-edit',
                'display_name'=>'Car License Edit',
                'description'=>'The login user can Edit Car License   in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'car-license-delete',
                'display_name'=>'Car License Delete',
                'description'=>'The login user can Delete Car License   in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-mileage-list',
                'display_name'=>'Car Mileage List',
                'description'=>'The login user can see Car Mileage List  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-mileage-edit',
                'display_name'=>'Car Mileage Edit',
                'description'=>'The login user can see Car Mileage Edit  in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'car-mileage-delete',
                'display_name'=>'Car Mileage Delete',
                'description'=>'The login user can see Car Mileage Delete in Car management',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'export-car-insurance',
                'display_name'=>'Export Car Insurance',
                'description'=>'The login user can export Car Insurance',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-kilo-for-maintanance',
                'display_name'=>'Export Kilo For Maintenance',
                'description'=>'The login user can export Kilo For Maintenance',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-repair-record-for-by-car',
                'display_name'=>'Export Repair Record (By Car)',
                'description'=>'The login user can export repair record by car',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-repair-record',
                'display_name'=>'Export Repair Record ',
                'description'=>'The login user can export repair record',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'fueling-export',
                'display_name'=>'Fueling Export',
                'description'=>'The login user can export fueling',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'export-license',
                'display_name'=>'Export License',
                'description'=>'The login user can export license',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-car-mileages',
                'display_name'=>'Export Car Mileage',
                'description'=>'The login user can export car mileages',
                'type'=>'Car Management',
                'guard_name'=>'web'
            ],
            
            
            //leave
            [
                'name'=>'list-of-yearly-leave',
                'display_name'=>'List Of Yearly Leave',
                'description'=>'The login user can search and see List of yearly leave of users',
                'type'=>'Leave Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-list-of-yearly-leave',
                'display_name'=>'List Of Yearly Leave Export',
                'description'=>'The login user can search and export List of yearly leave of users',
                'type'=>'Leave Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'leave-approve-by-dep-manager',
                'display_name'=>'Leave Approve By Dep Manager',
                'description'=>'The login user can change the account status(Accept and Reject) for leave',
                'type'=>'Leave Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'leave-approve-by-admi-gm',
                'display_name'=>'Leave Approve By Admin GM',
                'description'=>'The login user can change the account status(Accept and Reject) for leave',
                'type'=>'Leave Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'alert-email-for-unpaid-leave',
                'display_name'=>'Receive Account Email For Unpaid Leave',
                'description'=>'The login user can receive mail if employee take unpaid leave',
                'type'=>'Leave Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'employee-read-all',
                'display_name'=>'Read All',
                'description'=>'The login user can see the  list of all NS and RS in employee management',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'employee-read-group',
                'display_name'=>'Read Group',
                'description'=>'The login user can see  the  list of  NS and RS with the same department in employee management',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'employee-read-one',
                'display_name'=>'Read One',
                'description'=>'The login user can only see their own record list in employee management',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'edit-ns-record',
                'display_name'=>'Edit NS Employee',
                'description'=>'The login user can edit the NS Employee record',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'edit-rs-record',
                'display_name'=>'Edit RS Employee',
                'description'=>'The login user can edit the RS Employee record',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete-ns-record',
                'display_name'=>'Delete NS Employee',
                'description'=>'The login user can delete the NS Employee record',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'delete-rs-record',
                'display_name'=>'Delete RS Employee',
                'description'=>'The login user can delete the RS Employee record',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
              [
                'name'=>'dep-gm-for-performance-evaluation',
                'display_name'=>'Performance Evaluation Permission For Dep Manager',
                'description'=>'The login user can see performance evaluation of staff',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'admin-gm-for-performance-evaluation',
                'display_name'=>'Performance Evaluation Edit Permission For Admin GM',
                'description'=>'The login user can edit performance evaluation of Admin Gm',
                'type'=>'Employee Management',
                'guard_name'=>'web'
            ],
            ];

            foreach ($permissions as $key => $value) {
                Permission::create($value);
            }
    });
});

// start master management
Route::get('/master-permission', function () {
        $per = DB::transaction(function ()  {
        $mspermissions=[
                //start myomin
             [
                'name'=>'user-create',
                'display_name'=>'Create User',
                'description'=>'The login user can create user in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'user-list',
                'display_name'=>'User List',
                'description'=>'The login user can see all user in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'user-edit',
                'display_name'=>'Edit User',
                'description'=>'The login user can edit all user in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'user-delete',
                'display_name'=>'Delete User',
                'description'=>'The login user can delete all user in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'user-permission',
                'display_name'=>'Give Permission To User',
                'description'=>'The login user can give permission all user in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'branch-list',
                'display_name'=>'Branch List',
                'description'=>'The login user can see Branch List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'department-list',
                'display_name'=>'Department List',
                'description'=>'The login user can see Department List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'department-create',
                'display_name'=>'create Department',
                'description'=>'The login user can create Department  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'department-edit',
                'display_name'=>'Edit Department',
                'description'=>'The login user can edit Department  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'holiday-type-list',
                'display_name'=>'Holiday Type List',
                'description'=>'The login user can see Holiday Type  List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'holiday-list',
                'display_name'=>'Holiday  List',
                'description'=>'The login user can see Holiday  List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'holiday-create',
                'display_name'=>'Create Holiday',
                'description'=>'The login user can create Holiday in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'holiday-edit',
                'display_name'=>'Edit Holiday',
                'description'=>'The login user can Edit Holiday in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'holiday-delete',
                'display_name'=>'Delete Holiday',
                'description'=>'The login user can Delete Holiday in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'employee-type-list',
                'display_name'=>'Employee Type List',
                'description'=>'The login user can see Employee Type List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'bank-list',
                'display_name'=>'Bank List',
                'description'=>'The login user can see Bank List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'bank-create',
                'display_name'=>'Create Bank',
                'description'=>'The login user can create Bank  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'bank-edit',
                'display_name'=>'Edit Bank',
                'description'=>'The login user can edit Bank  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'bank-delete',
                'display_name'=>'Delete Bank',
                'description'=>'The login user can Delete Bank  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],            
             [
                'name'=>'leave-type-list',
                'display_name'=>'Leave Type List',
                'description'=>'The login user can see Leave Type  List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
             [
                'name'=>'role-list',
                'display_name'=>'Role List',
                'description'=>'The login user can see Bank List in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'role-create',
                'display_name'=>'Create Role',
                'description'=>'The login user can create Role  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'role-edit',
                'display_name'=>'Edit Role',
                'description'=>'The login user can edit Role  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'role-delete',
                'display_name'=>'Delete Role',
                'description'=>'The login user can Delete Role  in  Master management',
                'type'=>'Master Management',
                'guard_name'=>'web'
            ], 
            ];

            foreach ($mspermissions as $key => $value) {
                Permission::create($value);
            }
    });
});
//end master management



Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});
Route::get('/clear-config', function() {
    Artisan::call('config:cache');
    return 'Application cache has been cleared';
});
Route::get('/clear-view', function() {
    Artisan::call('view:clear');
    return 'Application cache has been cleared';
});
Route::get('/permit-license-expire', function() {
    Artisan::call('mjsrvexpire:daily');
    return 'Application cache has been cleared';
});

Route::get('/symlink', function () {
    Artisan::call('storage:link');
     return 'storage cleared';
});

Route::get('/', function () {
    //return view('dashboard');
    if(Auth::check()){
        //return view('welcome');
        return redirect('/dashboard');
    }
    else{
        //return getSiteName();
        return view('auth.login');  
    }
});
Route::group(['middleware'=>['forticlient']],function(){
    Route::get('/', function () {
        //return view('dashboard');
        if(Auth::check()){
            //return view('welcome');
            return redirect('/dashboard');
        }
        else{
            //return getSiteName();
            return view('auth.login');  
        }
    });
    Auth::routes();
    Route::get('/logout',[LoginController::class,'logout']);

    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
    Route::get('confirmation-code/{id}', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.get');
    Route::post('confirmation-code', [ForgotPasswordController::class, 'submitOtpForm'])->name('otp.post');
    Route::get('reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
      
    Route::get('/complete-registration', [RegisterController::class, 'completeRegistration'])->name('complete.registration');
    Route::group(['middleware'=>'auth'],function(){
        
        Route::get('master-management/user/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
        Route::post('master-management/user/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');

        Route::get('master-management/user/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('master-management/user/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
            
        Route::middleware([getRequestIp()])->group(function () {
           
            Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
            Route::post('dashboard/check-in',[HomeController::class,'checkIn'])->name('check-in');
            Route::get('dashboard/check-in',[HomeController::class,'checkIn'])->name('check-in');
            Route::post('dashboard/check-out',[HomeController::class,'checkOut'])->name('check-out');
            Route::get('dashboard/check-out',[HomeController::class,'checkOut'])->name('check-out');
            Route::get('dashboard/ot-request',[HomeController::class,'otRequest'])->name('ot-request');
            Route::get('dashboard/monthly-ot-request',[HomeController::class,'storeMonthlyOT'])->name('dashboard.monthly-ot-request');
            Route::post('dashboard/attendance-change-request',[HomeController::class,'storeChangeRequest'])->name('dashboard.change-request');
            Route::post('dashboard/request-hotel-usage',[HomeController::class,'storeHotelUsage'])->name('dashboard.hotel-usage');
            
            Route::post('/2fa', function () {
                //return 'success';
                if(auth()->user()->password_change==0){
                    return redirect('master-management/user/change-password');
                }
                else if(auth()->user()->profile_add==0)
                    return redirect('master-management/user/profile');
                    
                return redirect(route('dashboard'));
            })->name('2fa');

            /*--------------Attendance Management------------------*/
            Route::get('attendance-management/raw-attendance/list', [RawAttendanceController::class, 'index'])->name('raw-attendance.list');
            Route::get('attendance-management/raw-attendance/create', [RawAttendanceController::class, 'create'])->name('raw-attendance.create');
            Route::post('attendance-management/raw-attendance/store', [RawAttendanceController::class, 'store'])->name('raw-attendance.store');
            Route::post('attendance-management/raw-attendance/update', [RawAttendanceController::class, 'updateAtt'])->name('raw-attendance.update');
            Route::post('attendance-management/raw-attendance/delete', [RawAttendanceController::class, 'delete'])->name('raw-attendance.destroy');
            Route::get('attendance-management/raw-attendance/detail', [RawAttendanceController::class, 'detail'])->name('raw-attendance.detail');

            Route::get('attendance-management/raw-attendance/print-detail-view', [RawAttendanceController::class, 'printDetail'])->name('attendance.print-detail');
            Route::get('attendance-management/raw-attendance/detail/download-excel', [RawAttendanceController::class, 'detailDownload'])->name('attendance.detail-download');

            Route::post('attendance-management/raw-attendance/update-detail', [RawAttendanceController::class, 'updateDetail'])->name('raw-attendance.update-detail');

            Route::get('attendance-management/attendance-detail-list', [RawAttendanceController::class, 'detail'])->name('raw-attendance.detail');
            
            Route::get('attendance-management/delete/{id}', [RawAttendanceController::class, 'destroy'])->name('raw-attendance.destoy');

            //Change Request
            Route::get('attendance-management/change-request/list', [ChangeRequestController::class, 'index'])->name('change-request.list');
            Route::get('attendance-management/change-request/create', [ChangeRequestController::class, 'create'])->name('change-request.create');
            Route::post('attendance-management/change-request/store', [ChangeRequestController::class, 'store'])->name('change-request.store');
            Route::post('attendance-management/change-request/update', [ChangeRequestController::class, 'updateAtt'])->name('change-request.update');
            Route::post('attendance-management/change-request/change-status', [ChangeRequestController::class, 'changeStatus'])->name('change-request.change-status');
            Route::post('attendance-management/change-request/delete', [ChangeRequestController::class, 'delete'])->name('change-request.destroy');

            //Late Record
            Route::get('attendance-management/late-record',[RawAttendanceController::class,'lateRecord'])->name('attendance.late-record');
            Route::get('attendance-management/late-record/download-excel',[RawAttendanceController::class,'lateRecordDownload'])->name('attendance.late-record-download');
            /*--------------Attendance Management------------------*/

            /*--------------OT Management------------------*/
            //Daily OT Request
            Route::get('ot-management/daily-ot-request/list', [DailyOTRequestController::class, 'index'])->name('daily-ot-request.list');
            Route::get('ot-management/daily-ot-request/create', [DailyOTRequestController::class, 'create'])->name('daily-ot-request.create');
            Route::post('ot-management/daily-ot-request/store', [DailyOTRequestController::class, 'store'])->name('daily-ot-request.store');
            Route::post('ot-management/daily-ot-request/update', [DailyOTRequestController::class, 'updateEndTime'])->name('daily-ot-request.update');
            Route::post('ot-management/daily-ot-request/change-status', [DailyOTRequestController::class, 'changeStatus'])->name('daily-ot-request.change-status');
            Route::post('ot-management/daily-ot-request/delete', [DailyOTRequestController::class, 'delete'])->name('daily-ot-request.destroy');
            
            Route::get('ot-management/daily-ot-request/send-monthly-request', [DailyOTRequestController::class, 'storeMonthlyRequest'])->name('daily-ot-request.send-monthly-request');
            
            //Monthly OT Request
            // Route::get('ot-management/monthly-ot-request/list', [MonthlyOTRequestController::class, 'index'])->name('monthly-ot-request.list');
            // Route::post('ot-management/monthly-ot-request/change-status/{user}/{type}', [MonthlyOTRequestController::class, 'changeStatus'])->name('monthly-ot-request.change-status');
            //Monthly OT Request
            Route::get('ot-management/monthly-ot-staff/excel-download/{user}/{type}', [MonthlyOTRequestController::class, 'approvedDownload'])->name('monthly-ot-staff.approved-download');
            
            Route::get('ot-management/monthly-ot-request/list', [MonthlyOTRequestController::class, 'index'])->name('monthly-ot-request.list');
            Route::get('ot-management/monthly-ot-staff/monthly-ot-by-staff', [MonthlyOTRequestController::class, 'indexStaff'])->name('monthly-ot-request.monthly-ot-by-staff');
            Route::get('ot-management/monthly-ot-staff/approved-by-dept-gm', [MonthlyOTRequestController::class, 'indexDeptGM'])->name('monthly-ot-request.approved-by-dept-gm');
            Route::get('ot-management/monthly-ot-staff/approved-by-account', [MonthlyOTRequestController::class, 'indexAccount'])->name('monthly-ot-request.approved-by-account');
            Route::get('ot-management/monthly-ot-staff/approved-by-admin-gm', [MonthlyOTRequestController::class, 'indexAdminGM'])->name('monthly-ot-request.approved-by-admin-gm');

            Route::post('ot-management/monthly-ot-request/change-status/{user}/{type}', [MonthlyOTRequestController::class, 'changeStatus'])->name('monthly-ot-request.change-status');
            Route::post('ot-management/monthly-ot-request/dept-gm-change-status/{user}/{type}', [MonthlyOTRequestController::class, 'changeStatusDeptGM'])->name('monthly-ot-request.dept-change-status');
            Route::post('ot-management/monthly-ot-request/account-change-status/{user}/{type}', [MonthlyOTRequestController::class, 'changeStatusAccount'])->name('monthly-ot-request.account-change-status');
            Route::post('ot-management/monthly-ot-request/admin-gm-change-status/{user}/{type}', [MonthlyOTRequestController::class, 'changeStatusAdminGM'])->name('monthly-ot-request.gm-change-status');
            
            //Monthly OT Request (Driver and Assistant)
            Route::get('ot-management/monthly-ot-driver/monthly-ot-by-staff', [MonthlyDriverOTRequestController::class, 'indexStaff'])->name('monthly-ot-driver.monthly-ot-by-staff');
            
            Route::get('ot-management/monthly-ot-driver/approved-by-admin', [MonthlyDriverOTRequestController::class, 'indexDeptGM'])->name('monthly-ot-driver.approved-by-admin');

            Route::get('ot-management/monthly-ot-driver/delete/{id}', [MonthlyDriverOTRequestController::class, 'destroy'])->name('monthly-ot-driver.destroy');
            Route::post('ot-management/monthly-ot-driver/update', [MonthlyDriverOTRequestController::class, 'updateEndTime'])->name('monthly-ot-driver.update');

            Route::get('ot-management/monthly-ot-driver/approved-by-account', [MonthlyDriverOTRequestController::class, 'indexAccount'])->name('monthly-ot-driver.approved-by-account');
            Route::get('ot-management/monthly-ot-driver/approved-by-admin-gm', [MonthlyDriverOTRequestController::class, 'indexAdminGM'])->name('monthly-ot-driver.approved-by-admin-gm');
            
            Route::get('ot-management/monthly-ot-driver/excel-download/{user}/{type}', [MonthlyDriverOTRequestController::class, 'approvedDownload'])->name('monthly-ot-driver.approved-download');
            
            Route::post('ot-management/monthly-ot-driver/admin-change-status/{user}/{type}', [MonthlyDriverOTRequestController::class, 'changeStatusDeptGM'])->name('monthly-ot-driver.dept-change-status');
            Route::post('ot-management/monthly-ot-driver/account-change-status/{user}/{type}', [MonthlyDriverOTRequestController::class, 'changeStatusAccount'])->name('monthly-ot-driver.account-change-status');
            Route::post('ot-management/monthly-ot-driver/admin-gm-change-status/{user}/{type}', [MonthlyDriverOTRequestController::class, 'changeStatusAdminGM'])->name('monthly-ot-driver.gm-change-status');
            
            //Receptionist Salary
            Route::get('ot-management/monthly-receptionist/monthly-record-by-staff', [MonthlyReceptionistRequestController::class, 'indexStaff'])->name('monthly-receptionist.monthly-ot-by-staff');

            Route::get('ot-management/monthly-receptionist/approved-by-admin', [MonthlyReceptionistRequestController::class, 'indexDeptGM'])->name('monthly-receptionist.approved-by-admin');

            Route::get('ot-management/monthly-receptionist/delete/{id}', [MonthlyReceptionistRequestController::class, 'destroy'])->name('monthly-receptionist.destroy');
            Route::post('ot-management/monthly-receptionist/update', [MonthlyReceptionistRequestController::class, 'updateEndTime'])->name('monthly-receptionist.update');
            Route::get('ot-management/monthly-receptionist/approved-by-admin-gm', [MonthlyReceptionistRequestController::class, 'indexAdminGM'])->name('monthly-receptionist.approved-by-admin-gm');

            Route::get('ot-management/monthly-receptionist/excel-download/{user}/{type}', [MonthlyReceptionistRequestController::class, 'approvedDownload'])->name('monthly-receptionist.approved-download');

            Route::post('ot-management/monthly-receptionist/admin-change-status/{user}/{type}', [MonthlyReceptionistRequestController::class, 'changeStatusDeptGM'])->name('monthly-receptionist.dept-change-status');
            Route::post('ot-management/monthly-receptionist/admin-gm-change-status/{user}/{type}', [MonthlyReceptionistRequestController::class, 'changeStatusAdminGM'])->name('monthly-receptionist.gm-change-status');


            Route::get('ot-management/annual-ot-summary', [MonthlyOTRequestController::class, 'annualOTSummary'])->name('monthly-ot-request.annual-ot-summary');

            Route::get('ot-management/annual-ot-summary/download-excel',[MonthlyOTRequestController::class,'annualOTSummaryDownload'])->name('monthly-ot-request.annual-ot-summary-download');
            
            Route::get('ot-management/monthly-ot-summary', [MonthlyOTRequestController::class, 'monthlyOTSummary'])->name('monthly-ot-request.monthly-ot-summary');
            Route::get('ot-management/monthly-ot-summary/download-excel',[MonthlyOTRequestController::class,'monthlyOTSummaryDownload'])->name('monthly-ot-request.monthly-ot-summary-download');

            Route::get('ot-management/monthly-ot-detail', [MonthlyOTRequestController::class, 'monthlyOTIndividual'])->name('monthly-ot-request.monthly-ot-detail');
            Route::get('ot-management/monthly-ot-detail/download-excel',[MonthlyOTRequestController::class,'monthlyOTIndividualDownload'])->name('monthly-ot-request.monthly-ot-detail-download');

            
            /*--------------OT Management------------------*/
            
            /*--------------Salary Management------------------*/
            //NS Salary
            Route::get('salary-management/ns-salary/list',[NSSalaryController::class,'index'])->name('ns-salary.list');
            Route::get('salary-management/ns-salary/edit/{user_id}/{year}',[NSSalaryController::class,'editSalary'])->name('ns-salary.edit');
            Route::post('salary-management/ns-salary/edit/{user_id}/{year}',[NSSalaryController::class,'updateSalary'])->name('ns-salary.update');


            //RS Salary
            Route::get('salary-management/rs-salary/list',[RSSalaryController::class,'index'])->name('rs-salary.list');
            Route::get('salary-management/rs-salary/edit/{user_id}/{year}',[RSSalaryController::class,'editSalary'])->name('rs-salary.edit');
            Route::post('salary-management/rs-salary/edit/{user_id}/{year}',[RSSalaryController::class,'updateSalary'])->name('rs-salary.update');
            Route::get('salary-management/rs-salary/detail/{user_id}/{year}',[RSSalaryController::class,'detailSalary'])->name('ns-salary.detail');
            
            //Payament Exchange Rate
            Route::get('salary-management/payment-exchange-rate/list',[PaymentExchangeRateController::class,'index'])->name('payment-exchange-rate.list');
            Route::post('salary-management/payment-exchange-rate/store',[PaymentExchangeRateController::class,'store'])->name('payment-exchange-rate.store');
            Route::post('salary-management/payment-exchange-rate/update',[PaymentExchangeRateController::class,'update'])->name('payment-exchange-rate.update');
            Route::post('salary-management/payment-exchange-rate/delete',[PaymentExchangeRateController::class,'delete'])->name('payment-exchange-rate.delete');
            
            //Add Salary (April)
            Route::get('salary-management/add-salary',[ManualSalaryController::class,'create'])->name('add-salary.create');
            Route::post('salary-management/add-salary',[ManualSalaryController::class,'store'])->name('add-salary.store');

            Route::get('salary-management/add-salary-form',[ManualSalaryController::class,'salaryForm'])->name('salary.add-salary-form');
            Route::post('salary-management/add-salary-form',[ManualSalaryController::class,'storeSalary'])->name('add-salary-form.store');

            //Salary Calculation
            Route::get('salary-management/calculate-salary',[SalaryController::class,'create'])->name('salary.create');
            Route::post('salary-management/calculate-salary',[SalaryController::class,'store'])->name('salary.store');

            Route::get('salary-management/calculate-salary-form',[SalaryController::class,'salaryForm'])->name('salary.salary-form');
            Route::post('salary-management/calculate-salary-form',[SalaryController::class,'storeSalary'])->name('salary-form.store');

            Route::get('salary-management/payslip-list/detail',[SalaryController::class,'paySlipList'])->name('salary.payslip-list');
            Route::get('salary-management/payslip-list/detail/{id}',[SalaryController::class,'paySlipDetail'])->name('salary.payslip-detail');
            Route::get('salary-management/payslip-list/detail/excel-download/{id}',[SalaryController::class,'paySlipDetailDownload'])->name('salary.payslip-detail-download');

            Route::get('salary-management/payslip-list/monthy-salary',[SalaryController::class,'monthlySalaryList'])->name('salary.monthly-salary-list');
            Route::get('salary-management/payslip-list/monthy-salary/excel-download',[SalaryController::class,'monthlySalaryListDownload'])->name('salary.monthly-salary-download');

            Route::get('salary-management/payslip-list/bank',[SalaryController::class,'paySlipBank'])->name('salary.bank-list');
            Route::get('salary-management/payslip-list/bank/excel-download',[SalaryController::class,'paySlipBankDownload'])->name('salary.bank-download');

            Route::get('salary-management/payslip-list/ns-internal',[SalaryController::class,'paySlipNS'])->name('salary.ns-payslip-list');
            Route::get('salary-management/payslip-list/ns-internal/excel-download',[SalaryController::class,'paySlipNSDownload'])->name('salary.ns-payslip-download');

            Route::get('salary-management/payslip-list/jpn-internal',[SalaryController::class,'paySlipRS'])->name('salary.rs-payslip-list');
            Route::get('salary-management/payslip-list/jpn-internal/excel-download',[SalaryController::class,'paySlipRSDownload'])->name('salary.rs-payslip-download');

            /*--------------Salary Management------------------*/
            
            /*--------------Tax Management------------------*/
            //NS Actual Tax
            Route::get('tax-management/actual-tax/ns-income-tax-list', [NsTaxController::class, 'index'])->name('ns-tax.list');
            Route::get('tax-management/actual-tax/ns-income-tax-create', [NsTaxController::class, 'create'])->name('ns-tax.create');
            Route::post('tax-management/actual-tax/ns-income-tax-create', [NsTaxController::class, 'store'])->name('ns-tax.store');
            
            Route::post('tax-management/actual-tax/ns-income-tax-update', [NsTaxController::class, 'updateTax'])->name('ns-tax.update');
            Route::post('tax-management/actual-tax/ns-income-tax-delete', [NsTaxController::class, 'deleteTax'])->name('ns-tax.destroy');

            //RS Actual Tax
            Route::get('tax-management/actual-tax/rs-income-tax-list', [RsTaxController::class, 'index'])->name('rs-tax.list');
            Route::get('tax-management/actual-tax/rs-income-tax-create', [RsTaxController::class, 'create'])->name('rs-tax.create');
            Route::post('tax-management/actual-tax/rs-income-tax-create', [RsTaxController::class, 'store'])->name('rs-tax.store');
            
            Route::post('tax-management/actual-tax/rs-income-tax-update', [RsTaxController::class, 'updateTax'])->name('rs-tax.update');
            Route::post('tax-management/actual-tax/rs-income-tax-delete', [RsTaxController::class, 'deleteTax'])->name('rs-tax.destroy');

            Route::get('tax-management/ssc-report', [TaxController::class, 'sscReport'])->name('tax.ssc-report');
            Route::get('tax-management/ssc-report/excel-download', [TaxController::class, 'sscReportDownload'])->name('tax.ssc-report-download');
            
            Route::get('tax-management/monthly-tax-statement', [TaxController::class, 'monthlyTaxStatement'])->name('tax.monthly-tax-statement');
            Route::get('tax-management/monthly-tax-statement/excel-download', [TaxController::class, 'monthlyTaxStatementDownload'])->name('tax.monthly-tax-statement-download');
            Route::get('tax-management/monthly-tax-statement/detail/{id}', [TaxController::class, 'monthlyTaxStatementDetail'])->name('tax.monthly-tax-statement-detail');
            Route::get('tax-management/monthly-tax-statement/detail/excel-download/{id}', [TaxController::class, 'monthlyTaxStatementDetailDownload'])->name('tax.monthly-tax-statement-detail-download');
            Route::get('tax-management/annual-tax/ns-tax-payment-report', [TaxController::class, 'nsActualTaxPayment'])->name('tax.ns-tax-payment-report');
            Route::get('tax-management/annual-tax/ns-tax-payment-report/excel-download', [TaxController::class, 'nsActualTaxPaymentDownload'])->name('tax.ns-tax-payment-report-download');
            Route::get('tax-management/annual-tax/rs-tax-payment-report', [TaxController::class, 'rsActualTaxPayment'])->name('tax.rs-tax-payment-report');
            Route::get('tax-management/annual-tax/rs-tax-payment-report/excel-download', [TaxController::class, 'rsActualTaxPaymentDownload'])->name('tax.rs-tax-payment-report-download');
            Route::get('tax-management/rs-income-tax/detail/{user_id}/{date}', [TaxController::class, 'rsTaxDetail'])->name('tax.rs-tax-detail');
            Route::get('tax-management/rs-income-tax/detail/download/{user_id}/{date}', [TaxController::class, 'rsTaxDetailDownload'])->name('tax.rs-tax-detail-download');
            Route::get('tax-management/monthly-paye-report', [TaxController::class, 'monthlyPaye'])->name('tax.monthly-paye-report');
            Route::get('tax-management/monthly-paye-report/excel-download', [TaxController::class, 'monthlyPayeDownload'])->name('tax.monthly-paye-report-download');
            
            Route::get('tax-management/exchange-rate-detail', [TaxController::class, 'exchangeRateDetail'])->name('tax.exchange-rate-detail');
            Route::get('tax-management/exchange-rate-detail/excel-download', [TaxController::class, 'exchangeRateDetailDownload'])->name('tax.exchange-rate-detail-download');
            Route::get('tax-management/exchange-rate-summary', [TaxController::class, 'exchangeRateSummary'])->name('tax.exchange-rate-summary');
            Route::get('tax-management/exchange-rate-summary/download-excel', [TaxController::class, 'exchangeRateSummaryDownload'])->name('tax.exchange-rate-summary-download');
            
            Route::get('tax-management/annual-tax/deducted-paid-personal-report', [TaxController::class, 'paidPersonal'])->name('tax.paid-personal');
            Route::get('tax-management/annual-tax/deducted-paid-personal-report/download-excel', [TaxController::class, 'paidPersonalDownload'])->name('tax.paid-personal-download');
            
            Route::get('tax-management/annual-tax/tax-office-submission', [TaxController::class, 'taxOfficeSubmission'])->name('tax.tax-office-submission');
            Route::get('tax-management/annual-tax/tax-office-submission/download-excel', [TaxController::class, 'taxOfficeSubmissionDownload'])->name('tax.tax-office-submission-download');
            /*--------------Tax Management------------------*/

            /*--------------Master Management------------------*/
            //User Management
            Route::get('master-management/user/list', [UserController::class, 'index'])->name('user.list');
            Route::get('master-management/user/create', [UserController::class, 'create'])->name('user.create');
            Route::post('master-management/user/store', [UserController::class, 'store'])->name('user.store');
            Route::post('master-management/user/update-info', [UserController::class, 'updateInfo'])->name('user.update');
            Route::post('master-management/user/delete', [UserController::class, 'delete'])->name('user.destroy');
            
            
            Route::get('master-management/user/add-permission/{id}', [UserController::class, 'addPermission'])->name('user.add-permission');
            Route::post('master-management/user/add-permission/{id}', [UserController::class, 'updatePermission'])->name('user.update-permission');
            
            // banks
            Route::get('/master-management/banks',[BankController::class,'index'])->name('banks.index');
            Route::post('/master-management/bank/store',[BankController::class,'store'])->name('banks.store');
            Route::post('/master-management/bank/update',[BankController::class,'update'])->name('banks.update');
            Route::post('/master-management/bank/delete',[BankController::class,'delete'])->name('banks.delete');
            Route::post('/master-management/bank/status',[BankController::class,'status'])->name('banks.status');
            
            // branch
            Route::get('/master-management/branches',[BranchController::class,'index'])->name('branches.index');
            Route::post('/master-management/branch/store',[BranchController::class,'store'])->name('branches.store');
            Route::post('/master-management/branch/update',[BranchController::class,'update'])->name('branches.update');
            Route::post('/master-management/branch/delete',[BranchController::class,'delete'])->name('branches.delete');
            Route::post('/master-management/branch/status',[BranchController::class,'status'])->name('branches.status');
            // deparment
            Route::get('/master-management/departments',[DepartmentController::class,'index'])->name('departments.index');
            Route::post('/master-management/department/store',[DepartmentController::class,'store'])->name('departments.store');
            Route::post('/master-management/department/update',[DepartmentController::class,'update'])->name('departments.update');
            Route::post('/master-management/department/delete',[DepartmentController::class,'delete'])->name('departments.delete');
            Route::post('/master-management/department/status',[DepartmentController::class,'status'])->name('departments.status');
            // employee type
            Route::get('/master-management/employee-types',[EmployeeTypeController::class,'index'])->name('employee-types.index');
            Route::post('/master-management/employee-type/store',[EmployeeTypeController::class,'store'])->name('employee-types.store');
            Route::post('/master-management/employee-type/update',[EmployeeTypeController::class,'update'])->name('employee-types.update');
            Route::post('/master-management/employee-type/delete',[EmployeeTypeController::class,'delete'])->name('employee-types.delete');
            Route::post('/master-management/employee-type/status',[EmployeeTypeController::class,'status'])->name('employee-types.status');
            // holiday type
            Route::get('/master-management/holiday-types',[HolidayTypeController::class,'index'])->name('holiday-types.index');
            Route::post('/master-management/holiday-type/store',[HolidayTypeController::class,'store'])->name('holiday-types.store');
            Route::post('/master-management/holiday-type/update',[HolidayTypeController::class,'update'])->name('holiday-types.update');
            Route::post('/master-management/holiday-type/delete',[HolidayTypeController::class,'delete'])->name('holiday-types.delete');
            Route::post('/master-management/holiday-type/status',[HolidayTypeController::class,'status'])->name('holiday-types.status');
            // holidays
            Route::get('/master-management/holidays',[HolidayController::class,'index'])->name('holidays.index');
            Route::post('/master-management/holiday/store',[HolidayController::class,'store'])->name('holidays.store');
            Route::post('/master-management/holiday/update',[HolidayController::class,'update'])->name('holidays.update');
            Route::post('/master-management/holiday/delete',[HolidayController::class,'delete'])->name('holidays.delete');
            Route::post('/master-management/holiday/status',[HolidayController::class,'status'])->name('holidays.status');
            // leavetypes
            Route::get('/master-management/leavetypes',[LeaveTypeController::class,'index'])->name('leavetypes.index');
            Route::post('/master-management/leavetype/store',[LeaveTypeController::class,'store'])->name('leavetypes.store');
            Route::post('/master-management/leavetype/update',[LeaveTypeController::class,'update'])->name('leavetypes.update');
            Route::post('/master-management/leavetype/delete',[LeaveTypeController::class,'delete'])->name('leavetypes.delete');
            Route::post('/master-management/leavetype/status',[LeaveTypeController::class,'status'])->name('leavetypes.status');
            // roles
            Route::get('/master-management/roles',[RoleController::class,'index'])->name('roles.index');
            Route::post('/master-management/role/store',[RoleController::class,'store'])->name('roles.store');
            Route::post('/master-management/role/update',[RoleController::class,'update'])->name('roles.update');
            Route::post('/master-management/role/delete',[RoleController::class,'delete'])->name('roles.delete');
            Route::post('/master-management/role/status',[RoleController::class,'status'])->name('roles.status');
            
            Route::get('/master-management/alert/car-license-noti',[CarLicenseNotiController::class,'create'])->name('alert.car-license-noti');
            Route::post('/master-management/alert/car-license-noti-update',[CarLicenseNotiController::class,'store'])->name('alert.car-license-noti-update');

            /*--------------Master Management------------------*/
            
              /*--------------car Management------------------*/
            // car reg
            Route::any('/car-management/cars',[CarController::class,'index'])->name('cars.index');
            Route::post('/car-management/car/store',[CarController::class,'store'])->name('cars.store');
            Route::post('/car-management/car/update',[CarController::class,'update'])->name('cars.update');
            Route::post('/car-management/car/delete',[CarController::class,'delete'])->name('cars.delete');
            Route::post('/car-management/car/update-driver',[CarController::class,'updateDriver'])->name('cars.update-driver');
            Route::post('/car-management/car/update-main-user',[CarController::class,'updateMainUser'])->name('cars.update-main-user');
            
            Route::any('/car-management/car-license-remind',[CarController::class,'licenseRemind'])->name('car.licenseRemind');
            // car insurance
            Route::any('/car-management/insurance-management/car-insurances',[CarInsuranceController::class,'index'])->name('car-insurances.index');
            Route::post('/car-management/insurance-management/car-insurance/store',[CarInsuranceController::class,'store'])->name('car-insurances.store');
            Route::post('/car-management/insurance-management/car-insurance/update',[CarInsuranceController::class,'update'])->name('car-insurances.update');
            Route::post('/car-management/insurance-management/car-insurance/delete',[CarInsuranceController::class,'delete'])->name('car-insurances.delete');
            Route::post('/car-management/insurance-management/car-insurances/select-car-number',[CarInsuranceController::class,'selectCarNumber'])->name('car-insurances.select-car-number');
            
            Route::post('/car-management/insurance-management/car-insurances/select-car-number-with-liter',[CarInsuranceController::class,'selectCarNumberwithLiter'])->name('car-insurances.select-car-number-with-liter');
            
            Route::post('/car-management/insurance-management/car-insurance/premiun_amount_update',[CarInsuranceController::class,'premiumAmountUpdate'])->name('car-insurances.premiun_amount_update');
            Route::any('/car-management/insurance-management/car-insurance/export',[CarInsuranceController::class,'yearlyInsuranceExport'])->name('car-insurances.export');
            
            // car insurance claim history
            Route::any('/car-management/insurance-management/car-insurance-claim-histories',[CarInsuranceClaimHistoryController::class,'index'])->name('car-insurance-claim-histories.index');
            Route::post('/car-management/insurance-management/car-insurance-claim-history/store',[CarInsuranceClaimHistoryController::class,'store'])->name('car-insurance-claim-histories.store');
            Route::post('/car-management/insurance-management/car-insurance-claim-history/update',[CarInsuranceClaimHistoryController::class,'update'])->name('car-insurance-claim-histories.update');
            Route::post('/car-management/insurance-management/car-insurance-claim-history/delete',[CarInsuranceClaimHistoryController::class,'delete'])->name('car-insurance-claim-histories.delete');
            Route::post('/car-management/insurance-management/car-insurance-claim-histories/select-insurance-company',[CarInsuranceClaimHistoryController::class,'selectInsuranceNo'])->name('car-insurance-claim-histories.select-insurance-company');
            
            // car insurance
            Route::any('/car-management/monthly-car-management/car-licenses',[CarLicenseController::class,'index'])->name('car-licenses.index');
            Route::post('/car-management/monthly-car-management/car-license/store',[CarLicenseController::class,'store'])->name('car-licenses.store');
            Route::post('/car-management/monthly-car-management/car-license/update',[CarLicenseController::class,'update'])->name('car-licenses.update');
            Route::post('/car-management/monthly-car-management/car-license/delete',[CarLicenseController::class,'delete'])->name('car-licenses.delete');
            Route::any('/car-management/monthly-car-management/car-license/export',[CarLicenseController::class,'exportlicenses'])->name('car-licenses.exportlicenses');
            
            
            // car repair
            Route::any('/car-management/car-repair-and-maintanances',[CarRepairAndMaintananceController::class,'index'])->name('car-repair-and-maintanances.index');
            Route::post('/car-management/car-repair-and-maintanance/store',[CarRepairAndMaintananceController::class,'store'])->name('car-repair-and-maintanances.store');
            Route::post('/car-management/car-repair-and-maintanance/update',[CarRepairAndMaintananceController::class,'update'])->name('car-repair-and-maintanances.update');
            Route::post('/car-management/car-repair-and-maintanance/delete',[CarRepairAndMaintananceController::class,'delete'])->name('car-repair-and-maintanances.delete');
            Route::get('/car-management/car-repair-and-maintanances/exportforkmmaintanance',[CarRepairAndMaintananceController::class,'exportForKMMaintanance'])->name('car-repair-and-maintances.exportforkmmaintanance');
            Route::get('/car-management/car-repair-and-maintanances/repairrecordbycar',[CarRepairAndMaintananceController::class,'repairRecordByCar'])->name('car-repair-and-maintances.repairrecordbycar');
            Route::any('/car-management/car-repair-and-maintanances/exportforrepairrecord',[CarRepairAndMaintananceController::class,'repairRecordExport'])->name('car-repair-and-maintances.exportforrepairrecord');
            
            // car fueling
            Route::any('/car-management/monthly-car-management/car-fuelings',[CarFuelingController::class,'index'])->name('car-fuelings.index');
            Route::post('/car-management/monthly-car-management/car-fueling/store',[CarFuelingController::class,'store'])->name('car-fuelings.store');
            Route::post('/car-management/monthly-car-management/car-fueling/update',[CarFuelingController::class,'update'])->name('car-fuelings.update');
            Route::post('/car-management/monthly-car-management/car-fueling/delete',[CarFuelingController::class,'delete'])->name('car-fuelings.delete');
            Route::any('/car-management/monthly-car-management/car-fueling/export',[CarFuelingController::class,'fuelingExport'])->name('car-fuelings.excel-export');
            Route::get('/car-management/monthly-car-management/car-fueling/change-status',[CarFuelingController::class,'changeStatus'])->name('car-fuelings.change-status');
            
            //car mileage
            Route::any('/car-management/monthly-car-management/car-mileages',[CarMileageController::class,'index'])->name('car-mileages.index');
            Route::post('/car-management/monthly-car-management/car-mileage/store',[CarMileageController::class,'store'])->name('car-mileages.store');
            Route::post('/car-management/monthly-car-management/car-mileage/update',[CarMileageController::class,'update'])->name('car-mileages.update');
            Route::post('/car-management/monthly-car-management/car-mileage/delete',[CarMileageController::class,'delete'])->name('car-mileages.delete');
            Route::any('/car-management/monthly-car-management/car-mileages/export',[CarMileageController::class,'yearlyMileageExport'])->name('car-mileages.export');
            Route::get('/car-management/insurance-management/insurance-amount-histories/{car_insurance_id}',[CarInsuranceController::class,'insuranceAmountHistory'])->name('car-insurances.insurance-amount-histories');
             
             /*--------------car Management------------------*/
             /*--------------leave Management------------------*/
             Route::any('/leave-management/leave-requests',[LeaveController::class,'index'])->name('leave-requests.index');
             Route::post('/leave-management/leave-requests/store',[LeaveController::class,'store'])->name('leave-requests.store');
             Route::any('/leave-management/leave-requests-approve',[LeaveController::class,'approveListForDepManager'])->name('leave-requests.approve');
             Route::get('/leave-management/leave-requests-dep-manager-approve',[LeaveController::class,'approveByDepManager'])->name('leave-request.dep-manager-change-status');
             Route::any('/leave-management/leave-requests-admin-approve',[LeaveController::class,'approveListForADMI'])->name('leave-request.admin-approve');
             Route::get('/leave-management/leave-admin-approve',[LeaveController::class,'approveByAdmin'])->name('leave-request.admin-change-status');
             Route::post('/leave-management/select-leave-type',[LeaveController::class,'selectLeaveType'])->name('leave-request.select-leave-type');
             Route::any('/leave-management/remaining-days',[LeaveController::class,'getRemainingDays'])->name('leave-request.remaining-days');
             //start
             Route::any('/leave-management/rs-remaining-days',[LeaveController::class,'getRsRemainingDays'])->name('leave-request.rs-remaining-days');
             Route::any('/leave-management/excel-data',[LeaveController::class,'getExcelData'])->name('leave-request.export');
             Route::any('/leave-management/leave-excel-data',[LeaveController::class,'getleaveExcelData'])->name('leave-request.leave-export');
             Route::post('/leave-management/rs-leave-requests/store',[LeaveController::class,'RsLeavestore'])->name('rs-leave-requests.store');

            Route::post('/leave-management/select-leave-type',[LeaveController::class,'selectLeaveType'])->name('leave-request.select-leave-type');
            Route::post('/leave-management/select-rs-leave-type',[LeaveController::class,'selectRsLeaveType'])->name('leave-request.select-Rs-leave-type');
            Route::post('/leave-management/request-leave-cancel',[LeaveController::class,'requestLeaveCancel'])->name('leave-request.request-cancel');
            Route::get('/leave-management/leave-cancel-dep-manager-change-status',[LeaveController::class,'requestLeaveCancelapproveByDepManager'])->name('leave-request.leave-cancel-dep-manager-change-status');
            Route::get('/leave-management/leave-cancel-admin-manager-change-status',[LeaveController::class,'requestLeaveCancelapproveByAdminManager'])->name('leave-request.leave-cancel-admin-manager-change-status');
            
            
            
            Route::any('/leave-management/leave-requests-approve-gm',[LeaveController::class,'approveListForGM'])->name('leave-requests.approve-by-gm');
            Route::get('/leave-management/leave-gm-approve',[LeaveController::class,'approveByGM'])->name('leave-request.gm-change-status');
            Route::get('/leave-management/rs-leave-admin-approve',[LeaveController::class,'approveByRSAdmin'])->name('leave-request.rs-admin-change-status');
            Route::get('/leave-management/leave-cancel-gm-change-status',[LeaveController::class,'requestLeaveCancelapproveByRSGM'])->name('leave-request.leave-cancel-gm-change-status');
            
            //download file
            Route::get('/leave-certificate/download/{certificate}',[LeaveController::class,'getDownload'])->name('leave-request.download');
            Route::get('/leave-certificate/preview/{certificate}',[LeaveController::class,'getPreview'])->name('leave-request.preview');
            // Route::get('/download', 'HomeController@getDownload');
            //download file end
            
             /*--------------leave Management------------------*/
            /*--------------employee Management------------------*/
            
          Route::any('/employee-management/ns-list',[EmployeeController::class,'getNSList'])->name('employee.ns-list');
             Route::any('/employee-management/rs-list',[EmployeeController::class,'getRSList'])->name('employee.rs-list');
             Route::get('/employee-management/rs-edit/{id}',[EmployeeController::class,'editRSList'])->name('employee.rs-edit');
             Route::get('/employee-management/ns-edit/{id}',[EmployeeController::class,'editNSList'])->name('employee.ns-edit');
             Route::post('/employee-management/rs-basic-update',[EmployeeController::class,'updateBasicInfo'])->name('employee.rs-basic-update');
             Route::post('/employee-management/rs-contact-update',[EmployeeController::class,'updateContactInfo'])->name('employee.rs-contact-update');
             Route::post('/employee-management/rs-family-update',[EmployeeController::class,'updateFamilyInfo'])->name('employee.rs-family-update');
             Route::post('/employee-management/education-update',[EmployeeController::class,'updateEducationInfo'])->name('employee.education-update');
             Route::post('/employee-management/qualification-update',[EmployeeController::class,'updateQualificationInfo'])->name('employee.qualification-update');
             Route::post('/employee-management/language-skill-update',[EmployeeController::class,'updateLanguageSkill'])->name('employee.language-skill-update');
             Route::post('/employee-management/english-skill-update',[EmployeeController::class,'updateEnglishSkill'])->name('employee.english-skill-update');
             Route::post('/employee-management/employment-records-update',[EmployeeController::class,'updateEmploymentRecords'])->name('employee.employment-records-update');
             Route::post('/employee-management/oversea-records-update',[EmployeeController::class,'updateOverseaRecords'])->name('employee.oversea-records-update');
             Route::post('/employee-management/warnings-update',[EmployeeController::class,'updateWarning'])->name('employee.warnings-update');
             Route::post('/employee-management/evaluation-records-update',[EmployeeController::class,'updateEvalucationRecords'])->name('employee.evaluation-records-update');
             Route::post('/employee-management/other',[EmployeeController::class,'updateOther'])->name('employee.other');
             Route::post('/employee-management/retirements',[EmployeeController::class,'updateRetirements'])->name('employee.retirements');
             Route::post('/employee-management/ns-basic-update',[EmployeeController::class,'updateBasicInfoForNS'])->name('employee.ns-basic-update');

             //employee.pc-skill-update
             Route::post('/employee-management/pc-skill-update',[EmployeeController::class,'updatePCSkill'])->name('employee.pc-skill-update');
             Route::post('/employee-management/license-update',[EmployeeController::class,'updateLicense'])->name('employee.license-update');
             Route::post('/employee-management/users/delete',[EmployeeController::class,'delete'])->name('users.delete');

             Route::post('/employee-management/rs-leave-update',[EmployeeController::class,'updateRSLeave'])->name('employee.rs-leave-update');
             Route::post('/employee-management/assurance-update',[EmployeeController::class,'updateAssurance'])->name('employee.assurance-update');
             
             Route::post('/employee-management/rs-refresh-update',[EmployeeController::class,'updateRefresh'])->name('employee.rs-refresh-update');
             Route::get('/employee/delete-refresh-leaves/{id}',[EmployeeController::class,'deleteRefresh']);

             Route::post('/employee-management/bank-info-update',[EmployeeController::class,'updateBankInfo'])->name('employee.bank-info-update');
             Route::post('/employee-management/delete-bank-info',[EmployeeController::class,'deleteBankInfo'])->name('employee.bank-info-delete');
            
             /*--------------employee Management------------------*/
              Route::post('/employee/ns_export',[EmployeeController::class,'exportNS'])->name('employee.ns-export');
              Route::post('/employee/rs_export',[EmployeeController::class,'exportRS'])->name('employee.rs-export');
            
            
        });
    });
});


