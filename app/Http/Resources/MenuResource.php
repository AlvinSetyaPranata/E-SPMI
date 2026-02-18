<?php

namespace App\Http\Resources;

use App\Modules\Core\Models\Role;

class MenuResource
{
    /**
     * Get menu items based on user role
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function getMenu($user)
    {
        if (!$user) {
            return [];
        }

        $menu = [];

        // Dashboard - All authenticated users
        $menu[] = [
            'id' => 'dashboard',
            'name' => 'Dashboard',
            'icon' => 'HomeIcon',
            'href' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
            'permission' => null,
        ];

        // Core Module - Admin only
        if ($user->isAdmin()) {
            $menu[] = [
                'id' => 'core',
                'name' => 'Manajemen Pengguna',
                'icon' => 'UsersIcon',
                'permission' => 'users.view',
                'children' => [
                    ['name' => 'Pengguna', 'href' => route('core.users.index'), 'icon' => 'UserGroupIcon', 'permission' => 'users.view'],
                    ['name' => 'Roles & Permissions', 'href' => '#', 'icon' => 'ShieldCheckIcon', 'permission' => 'roles.view'],
                    ['name' => 'Unit Organisasi', 'href' => '#', 'icon' => 'BuildingOfficeIcon', 'permission' => 'units.view'],
                    ['name' => 'Activity Log', 'href' => '#', 'icon' => 'ClipboardDocumentListIcon', 'permission' => 'logs.view'],
                ],
            ];
        }

        // Standar Module - Admin and LPM
        if ($user->hasAnyRole([Role::ROLE_SUPERADMIN, Role::ROLE_LPM_ADMIN])) {
            $menu[] = [
                'id' => 'standar',
                'name' => 'Standar & Instrumen',
                'icon' => 'BookOpenIcon',
                'permission' => 'instruments.view',
                'children' => [
                    ['name' => 'Instrumen Audit', 'href' => '#', 'icon' => 'DocumentCheckIcon', 'permission' => 'instruments.view'],
                    ['name' => 'Standar & Indikator', 'href' => '#', 'icon' => 'FolderIcon', 'permission' => 'instruments.view'],
                    ['name' => 'Periode Audit', 'href' => '#', 'icon' => 'CalendarIcon', 'permission' => 'periods.view'],
                ],
            ];
        }

        // Audit Module - Auditor, LPM Admin
        if ($user->hasAnyRole([Role::ROLE_SUPERADMIN, Role::ROLE_LPM_ADMIN, Role::ROLE_AUDITOR])) {
            $menu[] = [
                'id' => 'audit',
                'name' => 'Audit Mutu',
                'icon' => 'ClipboardDocumentListIcon',
                'permission' => 'audits.view',
                'children' => [
                    ['name' => 'Jadwal Audit', 'href' => '#', 'icon' => 'CalendarIcon', 'permission' => 'audits.view'],
                    ['name' => 'Penugasan Auditor', 'href' => '#', 'icon' => 'UsersIcon', 'permission' => 'audits.assign_auditors'],
                    ['name' => 'Kertas Kerja Audit', 'href' => '#', 'icon' => 'DocumentCheckIcon', 'permission' => 'audits.evaluate'],
                    ['name' => 'Hasil Penilaian', 'href' => '#', 'icon' => 'ChartBarIcon', 'permission' => 'audits.view'],
                ],
            ];
        }

        // Pelaksanaan Module - Auditee, Auditor, Admin
        if ($user->hasAnyRole([Role::ROLE_SUPERADMIN, Role::ROLE_LPM_ADMIN, Role::ROLE_AUDITOR, Role::ROLE_AUDITEE])) {
            $menu[] = [
                'id' => 'pelaksanaan',
                'name' => 'Pelaksanaan & Bukti',
                'icon' => 'FolderIcon',
                'permission' => 'evidences.view',
                'children' => [
                    ['name' => 'Repository Bukti', 'href' => '#', 'icon' => 'FolderIcon', 'permission' => 'evidences.view'],
                    ['name' => 'Evaluasi Diri', 'href' => '#', 'icon' => 'DocumentCheckIcon', 'permission' => 'evidences.upload'],
                    ['name' => 'Integrasi SIAKAD', 'href' => '#', 'icon' => 'Cog6ToothIcon', 'permission' => 'evidences.view'],
                ],
            ];
        }

        // Pengendalian Module - Auditor, Auditee, Admin
        if ($user->hasAnyRole([Role::ROLE_SUPERADMIN, Role::ROLE_LPM_ADMIN, Role::ROLE_AUDITOR, Role::ROLE_AUDITEE])) {
            $menu[] = [
                'id' => 'pengendalian',
                'name' => 'Pengendalian & PTK',
                'icon' => 'ExclamationTriangleIcon',
                'permission' => 'findings.view',
                'children' => [
                    ['name' => 'Temuan & PTK', 'href' => '#', 'icon' => 'ExclamationTriangleIcon', 'permission' => 'findings.view'],
                    ['name' => 'Tindak Lanjut', 'href' => '#', 'icon' => 'ClipboardDocumentListIcon', 'permission' => 'findings.respond'],
                    ['name' => 'RTM (Rapat Tinjauan)', 'href' => '#', 'icon' => 'UserGroupIcon', 'permission' => 'rtm.view'],
                    ['name' => 'Action Plan', 'href' => '#', 'icon' => 'CalendarIcon', 'permission' => 'rtm.view'],
                ],
            ];
        }

        // Analytics Module - All roles
        if ($user->hasPermission('iku.view') || $user->hasPermission('reports.view')) {
            $menu[] = [
                'id' => 'analytics',
                'name' => 'Analytics & IKU',
                'icon' => 'ChartBarIcon',
                'permission' => 'iku.view',
                'children' => [
                    ['name' => 'Dashboard IKU', 'href' => '#', 'icon' => 'ChartBarIcon', 'permission' => 'iku.view'],
                    ['name' => '8 Indikator Kinerja', 'href' => '#', 'icon' => 'DocumentCheckIcon', 'permission' => 'iku.view'],
                    ['name' => 'Laporan Eksekutif', 'href' => '#', 'icon' => 'FolderIcon', 'permission' => 'reports.view'],
                ],
            ];
        }

        return $menu;
    }

    /**
     * Filter menu items based on user permissions
     *
     * @param array $menu
     * @param \App\Models\User $user
     * @return array
     */
    public static function filterByPermission($menu, $user)
    {
        $filtered = [];

        foreach ($menu as $item) {
            // Check if item has children
            if (isset($item['children'])) {
                $filteredChildren = array_filter($item['children'], function ($child) use ($user) {
                    if (!isset($child['permission']) || $child['permission'] === null) {
                        return true;
                    }
                    return $user->hasPermission($child['permission']);
                });

                // Only add parent if it has visible children
                if (count($filteredChildren) > 0) {
                    $item['children'] = array_values($filteredChildren);
                    $filtered[] = $item;
                }
            } else {
                // Single item without children
                if (!isset($item['permission']) || $item['permission'] === null) {
                    $filtered[] = $item;
                } elseif ($user->hasPermission($item['permission'])) {
                    $filtered[] = $item;
                }
            }
        }

        return $filtered;
    }
}
