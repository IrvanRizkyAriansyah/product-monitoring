<?php

namespace Database\Seeders;

use App\Models\Line;
use App\Models\Part;
use App\Models\RejectType;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProductionMonitoringSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        $leaderRole = Role::firstOrCreate([
            'name' => 'Leader',
            'guard_name' => 'web',
        ]);

        $operatorRole = Role::firstOrCreate([
            'name' => 'Operator',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Custom Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            'approve_production_report',
            'submit_production_report',
            'export_production_report',
            'view_dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        |
        | Password mengikuti nama user (lowercase tanpa spasi)
        |
        */

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
            ]
        );

        $manager = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('manager'),
            ]
        );

        $leader = User::firstOrCreate(
            ['email' => 'leader@gmail.com'],
            [
                'name' => 'Leader',
                'password' => Hash::make('leader'),
            ]
        );

        $operator = User::firstOrCreate(
            ['email' => 'operator@gmail.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('operator'),
            ]
        );

        $admin->syncRoles([$superAdminRole]);
        $manager->syncRoles([$managerRole]);
        $leader->syncRoles([$leaderRole]);
        $operator->syncRoles([$operatorRole]);

        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */

        // $managerRole->givePermissionTo([
        //     'approve_production_report',
        //     'export_production_report',
        //     'view_dashboard',
        // ]);

        // $leaderRole->givePermissionTo([
        //     'submit_production_report',
        //     'view_dashboard',
        // ]);

        /*
        |--------------------------------------------------------------------------
        | Lines
        |--------------------------------------------------------------------------
        */

        $lines = [
            [
                'code' => 'LINE-A',
                'name' => 'Line A',
            ],
            [
                'code' => 'LINE-B',
                'name' => 'Line B',
            ],
            [
                'code' => 'LINE-C',
                'name' => 'Line C',
            ],
            [
                'code' => 'LINE-EF',
                'name' => 'Line E&F',
            ],
        ];

        foreach ($lines as $line) {
            Line::firstOrCreate(
                ['code' => $line['code']],
                $line
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Shifts
        |--------------------------------------------------------------------------
        */

        $shifts = [
            [
                'name' => 'Shift 1',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
            ],
            [
                'name' => 'Shift 2',
                'start_time' => '15:00:00',
                'end_time' => '23:00:00',
            ],
            [
                'name' => 'Shift 3',
                'start_time' => '23:00:00',
                'end_time' => '07:00:00',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::firstOrCreate(
                ['name' => $shift['name']],
                $shift
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Parts
        |--------------------------------------------------------------------------
        */

        $parts = [
            [
                'part_no' => 'P001',
                'part_name' => 'Cover Engine',
                'std_run' => 180,
            ],
            [
                'part_no' => 'P002',
                'part_name' => 'Bracket Front',
                'std_run' => 200,
            ],
            [
                'part_no' => 'P003',
                'part_name' => 'Housing Pump',
                'std_run' => 150,
            ],
            [
                'part_no' => 'P004',
                'part_name' => 'Frame Support',
                'std_run' => 120,
            ],
        ];

        foreach ($parts as $part) {
            Part::firstOrCreate(
                ['part_no' => $part['part_no']],
                $part
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RejectType
        |--------------------------------------------------------------------------
        */
        RejectType::insert([
        [
            'code' => 'DNT',
            'name' => 'Dent',
        ],
        [
            'code' => 'SCR',
            'name' => 'Scratch',
        ],
        [
            'code' => 'CRK',
            'name' => 'Crack',
        ],
        [
            'code' => 'WRC',
            'name' => 'Wrong Color',
        ],
        [
            'code' => 'DIM',
            'name' => 'Dimension NG',
        ],
    ]);
    }
}