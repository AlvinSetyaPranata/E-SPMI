import React from 'react';
import EspmiLayout from '@/Layouts/EspmiLayout';
import { Head, Link } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia';
import { 
    PlusIcon, 
    PencilIcon, 
    TrashIcon, 
    EyeIcon,
    CheckCircleIcon,
    XCircleIcon,
    UserGroupIcon,
    MagnifyingGlassIcon,
    ArrowPathIcon
} from '@heroicons/react/24/outline';

export default function UsersIndex({ auth, users }) {
    const handleDelete = (user) => {
        if (confirm(`Apakah Anda yakin ingin menghapus pengguna "${user.name}"?`)) {
            Inertia.delete(route('core.users.destroy', user.id));
        }
    };

    const handleToggleActive = (user) => {
        const action = user.is_active ? 'menonaktifkan' : 'mengaktifkan';
        if (confirm(`Apakah Anda yakin ingin ${action} pengguna "${user.name}"?`)) {
            Inertia.post(route('core.users.toggle-active', user.id));
        }
    };

    const getRoleBadgeColor = (roleName) => {
        const colors = {
            superadmin: 'bg-red-100 text-red-800',
            lpm_admin: 'bg-blue-100 text-blue-800',
            auditor: 'bg-green-100 text-green-800',
            auditee: 'bg-yellow-100 text-yellow-800',
            rector: 'bg-purple-100 text-purple-800',
            dean: 'bg-pink-100 text-pink-800',
            head_of_study_program: 'bg-indigo-100 text-indigo-800',
        };
        return colors[roleName] || 'bg-gray-100 text-gray-800';
    };

    return (
        <EspmiLayout auth={auth} title="Manajemen Pengguna">
            <Head title="Manajemen Pengguna" />

            {/* Header */}
            <div className="mb-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
                        <p className="text-gray-500 mt-1">Kelola pengguna sistem E-SPMI</p>
                    </div>
                    <Link
                        href={route('core.users.create')}
                        className="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <PlusIcon className="w-5 h-5" />
                        Tambah Pengguna
                    </Link>
                </div>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Total Pengguna</p>
                            <p className="text-2xl font-bold text-gray-900">{users.total}</p>
                        </div>
                        <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <UserGroupIcon className="w-5 h-5 text-blue-600" />
                        </div>
                    </div>
                </div>
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Aktif</p>
                            <p className="text-2xl font-bold text-green-600">
                                {users.data.filter(u => u.is_active).length}
                            </p>
                        </div>
                        <div className="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <CheckCircleIcon className="w-5 h-5 text-green-600" />
                        </div>
                    </div>
                </div>
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Non-Aktif</p>
                            <p className="text-2xl font-bold text-red-600">
                                {users.data.filter(u => !u.is_active).length}
                            </p>
                        </div>
                        <div className="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <XCircleIcon className="w-5 h-5 text-red-600" />
                        </div>
                    </div>
                </div>
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-gray-500">Halaman</p>
                            <p className="text-2xl font-bold text-gray-900">
                                {users.current_page} / {users.last_page}
                            </p>
                        </div>
                        <div className="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <ArrowPathIcon className="w-5 h-5 text-gray-600" />
                        </div>
                    </div>
                </div>
            </div>

            {/* Users Table */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full">
                        <thead className="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200">
                            {users.data.map((user) => (
                                <tr key={user.id} className="hover:bg-gray-50">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                                {user.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <p className="font-medium text-gray-900">{user.name}</p>
                                                <p className="text-sm text-gray-500">{user.email}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-sm text-gray-600">
                                        {user.nip || '-'}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-wrap gap-1">
                                            {user.roles?.map((role) => (
                                                <span 
                                                    key={role.id} 
                                                    className={`px-2 py-1 text-xs rounded-full ${getRoleBadgeColor(role.name)}`}
                                                >
                                                    {role.display_name}
                                                </span>
                                            ))}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-sm text-gray-600">
                                        {user.units?.map(unit => unit.name).join(', ') || '-'}
                                    </td>
                                    <td className="px-6 py-4">
                                        <button
                                            onClick={() => handleToggleActive(user)}
                                            className={`px-3 py-1 rounded-full text-xs font-medium transition-colors ${
                                                user.is_active 
                                                    ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                                    : 'bg-red-100 text-red-800 hover:bg-red-200'
                                            }`}
                                        >
                                            {user.is_active ? 'Aktif' : 'Non-Aktif'}
                                        </button>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <Link
                                                href={route('core.users.show', user.id)}
                                                className="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Detail"
                                            >
                                                <EyeIcon className="w-4 h-4" />
                                            </Link>
                                            <Link
                                                href={route('core.users.edit', user.id)}
                                                className="p-2 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                                title="Edit"
                                            >
                                                <PencilIcon className="w-4 h-4" />
                                            </Link>
                                            {user.id !== auth.user.id && (
                                                <button
                                                    onClick={() => handleDelete(user)}
                                                    className="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Hapus"
                                                >
                                                    <TrashIcon className="w-4 h-4" />
                                                </button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {users.links && (
                    <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <p className="text-sm text-gray-500">
                            Menampilkan {users.from} - {users.to} dari {users.total} pengguna
                        </p>
                        <div className="flex items-center gap-2">
                            {users.links.map((link, index) => (
                                <button
                                    key={index}
                                    onClick={() => link.url && Inertia.visit(link.url)}
                                    disabled={!link.url || link.active}
                                    className={`px-3 py-1 text-sm rounded-lg transition-colors ${
                                        link.active 
                                            ? 'bg-blue-600 text-white' 
                                            : link.url 
                                                ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' 
                                                : 'bg-gray-50 text-gray-400 cursor-not-allowed'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </EspmiLayout>
    );
}
