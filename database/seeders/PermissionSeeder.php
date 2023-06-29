<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions=[
            [
                'name'=>'attendance-read-all',
                'display_name'=>'Read All',
                'description'=>'The login user can see the record list of all staff in attendance management',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'attendance-read-group',
                'display_name'=>'Read Group',
                'description'=>'The login user can see the record list of all staff with the same department in attendance management',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'attendance-read-one',
                'display_name'=>'Read One',
                'description'=>'The login user can only see their own record list in attendance management',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'raw-attendance-list',
                'display_name'=>'Raw Attendance List',
                'description'=>'The login user can see the "Raw Attendance List" menu in attendance management',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'attendance-change-request',
                'display_name'=>'Attendance Change Request List',
                'description'=>'The login user can see the "Attendance Change Request" menu in attendance management',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'late-record-list',
                'display_name'=>'Late Record List',
                'description'=>'The login user can see the "Late Record List" menu in attendance management',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-late-record-list',
                'display_name'=>'Export Late Record List',
                'description'=>'The login user can export the Late Record List as excel format',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create-attendance',
                'display_name'=>'Create Manual Attendance',
                'description'=>'The login user can create the attendance record by manually',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'edit-attendance',
                'display_name'=>'Edit Raw Attendance',
                'description'=>'The login user can edit the raw attendance record',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'delete-attendance',
                'display_name'=>'Delete Raw Attendance',
                'description'=>'The login user can delete the raw attendance record',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'attendance-detail-list',
                'display_name'=>'Attendance Detail View',
                'description'=>'The login user can see the detail attendance record',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'print-attendance-detail-list',
                'display_name'=>'Print Attendance Detail View',
                'description'=>'The login user can print the detail attendance record',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-attendance-detail-list',
                'display_name'=>'Export Attendance Detail View',
                'description'=>'The login user can export the detail attendance record as excel format',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'update-attendance-detail-list',
                'display_name'=>'Update Attendance Detail View',
                'description'=>'The login user can update the detail attendance information',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create-change-request',
                'display_name'=>'Create Change Request',
                'description'=>'The login user can request to change the attendance time in and time out',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'attendance-change-status',
                'display_name'=>'Change Status',
                'description'=>'The login user can change the status (Accept and Reject) of Change Request',
                'type'=>'Attendance Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'ot-read-all',
                'display_name'=>'Read All',
                'description'=>'The login user can see the record list of all staff in ot management',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'ot-read-group',
                'display_name'=>'Read Group',
                'description'=>'The login user can see the record list of staff with the same department in ot management',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'ot-read-one',
                'display_name'=>'Read One',
                'description'=>'The login user can only see their own record list in ot management',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'daily-ot-request-list',
                'display_name'=>'Daily OT Request List',
                'description'=>'The login user can see the "Daily OT Request List" menu in ot management',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'monthly-ot-request-list',
                'display_name'=>'Monthly OT Request List',
                'description'=>'The login user can see the "Monthly OT Request List" menu in ot management',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'monthly-ot-summary-list',
                'display_name'=>'Monthly OT Summary List',
                'description'=>'The login user can see the "Monthly OT Summary List" menu in ot management',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-monthly-ot-summary-list',
                'display_name'=>'Export Monthly OT Summary List',
                'description'=>'The login user can export the monthly summary list as excel format',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'annual-ot-summary-list',
                'display_name'=>'Annual OT Summary List',
                'description'=>'The login user can see the "Annual OT Summary List" menu in ot management',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'export-annual-ot-summary-list',
                'display_name'=>'Export Annual OT Summary List',
                'description'=>'The login user can export the annual summary list as excel format',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'create-ot',
                'display_name'=>'Create OT Request',
                'description'=>'The login user can request for overtime',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'send-monthly-ot-request',
                'display_name'=>'Send Monthly OT Request',
                'description'=>'The login user can send monthly overtime record to Manager',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'change-ot-manager-status',
                'display_name'=>'Change Department GM Status',
                'description'=>'The login user can change the department GM status(Accept and Reject) for overtime',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'change-ot-account-status',
                'display_name'=>'Change Account Status',
                'description'=>'The login user can change the account status(Accept and Reject) for overtime',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ],
            [
                'name'=>'change-ot-gm-status',
                'display_name'=>'Change Admin GM Status',
                'description'=>'The login user can change the admin GM status(Accept and Reject) for overtime',
                'type'=>'OT Management',
                'guard_name'=>'web'
            ]

        ];

        foreach ($permissions as $key => $value) {
            Permission::create($value);
        }
    }
}
