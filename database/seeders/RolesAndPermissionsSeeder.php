<?php

namespace Database\Seeders;

use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Permissions
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'group' => 'dashboard'],
            
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'group' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'group' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'group' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'group' => 'users'],
            
            // Role Management
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'group' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'group' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'group' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'group' => 'roles'],
            
            // Unit Management
            ['name' => 'units.view', 'display_name' => 'View Units', 'group' => 'units'],
            ['name' => 'units.create', 'display_name' => 'Create Units', 'group' => 'units'],
            ['name' => 'units.edit', 'display_name' => 'Edit Units', 'group' => 'units'],
            ['name' => 'units.delete', 'display_name' => 'Delete Units', 'group' => 'units'],
            
            // Instrument Management
            ['name' => 'instruments.view', 'display_name' => 'View Instruments', 'group' => 'instruments'],
            ['name' => 'instruments.create', 'display_name' => 'Create Instruments', 'group' => 'instruments'],
            ['name' => 'instruments.edit', 'display_name' => 'Edit Instruments', 'group' => 'instruments'],
            ['name' => 'instruments.delete', 'display_name' => 'Delete Instruments', 'group' => 'instruments'],
            
            // Period Management
            ['name' => 'periods.view', 'display_name' => 'View Periods', 'group' => 'periods'],
            ['name' => 'periods.create', 'display_name' => 'Create Periods', 'group' => 'periods'],
            ['name' => 'periods.edit', 'display_name' => 'Edit Periods', 'group' => 'periods'],
            ['name' => 'periods.delete', 'display_name' => 'Delete Periods', 'group' => 'periods'],
            
            // Audit Management
            ['name' => 'audits.view', 'display_name' => 'View Audits', 'group' => 'audits'],
            ['name' => 'audits.create', 'display_name' => 'Create Audits', 'group' => 'audits'],
            ['name' => 'audits.edit', 'display_name' => 'Edit Audits', 'group' => 'audits'],
            ['name' => 'audits.delete', 'display_name' => 'Delete Audits', 'group' => 'audits'],
            ['name' => 'audits.assign_auditors', 'display_name' => 'Assign Auditors', 'group' => 'audits'],
            
            // Audit Evaluation
            ['name' => 'audits.evaluate', 'display_name' => 'Evaluate Audit', 'group' => 'audits'],
            ['name' => 'audits.input_finding', 'display_name' => 'Input Findings', 'group' => 'audits'],
            
            // Evidence Management
            ['name' => 'evidences.view', 'display_name' => 'View Evidences', 'group' => 'evidences'],
            ['name' => 'evidences.upload', 'display_name' => 'Upload Evidence', 'group' => 'evidences'],
            ['name' => 'evidences.verify', 'display_name' => 'Verify Evidence', 'group' => 'evidences'],
            
            // Finding/PTK Management
            ['name' => 'findings.view', 'display_name' => 'View Findings', 'group' => 'findings'],
            ['name' => 'findings.create', 'display_name' => 'Create Findings', 'group' => 'findings'],
            ['name' => 'findings.respond', 'display_name' => 'Respond to Finding', 'group' => 'findings'],
            ['name' => 'findings.verify', 'display_name' => 'Verify Finding Response', 'group' => 'findings'],
            
            // RTM Management
            ['name' => 'rtm.view', 'display_name' => 'View RTM', 'group' => 'rtm'],
            ['name' => 'rtm.create', 'display_name' => 'Create RTM', 'group' => 'rtm'],
            ['name' => 'rtm.edit', 'display_name' => 'Edit RTM', 'group' => 'rtm'],
            ['name' => 'rtm.attend', 'display_name' => 'Attend RTM', 'group' => 'rtm'],
            
            // IKU Management
            ['name' => 'iku.view', 'display_name' => 'View IKU', 'group' => 'iku'],
            ['name' => 'iku.input', 'display_name' => 'Input IKU Data', 'group' => 'iku'],
            ['name' => 'iku.verify', 'display_name' => 'Verify IKU Data', 'group' => 'iku'],
            
            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'group' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'group' => 'reports'],
            
            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'group' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'group' => 'settings'],
            
            // Activity Logs
            ['name' => 'logs.view', 'display_name' => 'View Activity Logs', 'group' => 'logs'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create Roles
        $roles = [
            [
                'name' => Role::ROLE_SUPERADMIN,
                'display_name' => 'Super Administrator',
                'description' => 'Full access to all system features',
                'is_system' => true,
            ],
            [
                'name' => Role::ROLE_LPM_ADMIN,
                'display_name' => 'LPM Administrator',
                'description' => 'Manages SPMI processes, standards, and audits',
                'is_system' => true,
            ],
            [
                'name' => Role::ROLE_AUDITOR,
                'display_name' => 'Auditor Internal',
                'description' => 'Conducts audits and evaluates evidence',
                'is_system' => true,
            ],
            [
                'name' => Role::ROLE_AUDITEE,
                'display_name' => 'Auditee',
                'description' => 'Submits evidence and responds to findings',
                'is_system' => true,
            ],
            [
                'name' => Role::ROLE_RECTOR,
                'display_name' => 'Rektor',
                'description' => 'Executive view of SPMI data',
                'is_system' => true,
            ],
            [
                'name' => Role::ROLE_DEAN,
                'display_name' => 'Dekan',
                'description' => 'Faculty-level SPMI oversight',
                'is_system' => true,
            ],
            [
                'name' => Role::ROLE_HEAD_OF_STUDY_PROGRAM,
                'display_name' => 'Ketua Prodi',
                'description' => 'Study program-level SPMI management',
                'is_system' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        // Assign all permissions to Super Admin
        $superAdmin = Role::where('name', Role::ROLE_SUPERADMIN)->first();
        $superAdmin->permissions()->sync(Permission::all()->pluck('id'));

        // Assign LPM Admin permissions
        $lpmAdmin = Role::where('name', Role::ROLE_LPM_ADMIN)->first();
        $lpmPermissions = Permission::whereIn('group', [
            'dashboard', 'users', 'units', 'instruments', 'periods', 
            'audits', 'evidences', 'findings', 'rtm', 'iku', 'reports', 'logs'
        ])->get();
        $lpmAdmin->permissions()->sync($lpmPermissions->pluck('id'));

        // Assign Auditor permissions
        $auditor = Role::where('name', Role::ROLE_AUDITOR)->first();
        $auditorPermissions = Permission::whereIn('group', [
            'dashboard', 'audits', 'evidences', 'findings', 'rtm'
        ])->get();
        $auditor->permissions()->sync($auditorPermissions->pluck('id'));

        // Assign Auditee permissions
        $auditee = Role::where('name', Role::ROLE_AUDITEE)->first();
        $auditeePermissions = Permission::whereIn('name', [
            'dashboard.view', 'audits.view', 'evidences.view', 'evidences.upload',
            'findings.view', 'findings.respond', 'rtm.view', 'rtm.attend'
        ])->get();
        $auditee->permissions()->sync($auditeePermissions->pluck('id'));

        // Assign Rector permissions
        $rector = Role::where('name', Role::ROLE_RECTOR)->first();
        $rectorPermissions = Permission::whereIn('name', [
            'dashboard.view', 'audits.view', 'reports.view', 'iku.view'
        ])->get();
        $rector->permissions()->sync($rectorPermissions->pluck('id'));

        // Assign Dean permissions
        $dean = Role::where('name', Role::ROLE_DEAN)->first();
        $deanPermissions = Permission::whereIn('name', [
            'dashboard.view', 'audits.view', 'evidences.view', 'findings.view',
            'reports.view', 'iku.view'
        ])->get();
        $dean->permissions()->sync($deanPermissions->pluck('id'));

        // Assign Head of Study Program permissions
        $headProdi = Role::where('name', Role::ROLE_HEAD_OF_STUDY_PROGRAM)->first();
        $headProdi->permissions()->sync($auditeePermissions->pluck('id'));
    }
}
