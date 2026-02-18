import React from 'react';
import EspmiLayout from '@/Layouts/EspmiLayout';
import { Head, usePage } from '@inertiajs/inertia-react';
import { 
    ClipboardDocumentCheckIcon, 
    ExclamationTriangleIcon, 
    FolderIcon, 
    UsersIcon,
    ChartBarIcon,
    CalendarIcon,
    ArrowTrendingUpIcon,
    ArrowTrendingDownIcon,
    CheckCircleIcon,
    ClockIcon,
    ShieldCheckIcon,
    UserGroupIcon,
    DocumentTextIcon,
    AcademicCapIcon
} from '@heroicons/react/24/outline';

export default function Dashboard({ auth, stats }) {
    const { menu } = usePage().props;
    
    // Check user roles
    const isAdmin = auth.user.is_admin;
    const isAuditor = auth.user.is_auditor;
    const roles = auth.user.roles || [];

    // Sample data - in production, this comes from backend
    const dashboardStats = {
        totalAudits: 12,
        activeAudits: 5,
        pendingFindings: 8,
        totalEvidence: 156,
        completedEvidence: 134,
        ikuAchievement: 78.5,
    };

    const recentActivities = [
        { id: 1, type: 'audit', message: 'Audit Mutu Internal FTIK dimulai', time: '2 jam yang lalu', icon: ClipboardDocumentCheckIcon, color: 'blue' },
        { id: 2, type: 'finding', message: '3 temuan KTS Minor pada Prodi TI', time: '4 jam yang lalu', icon: ExclamationTriangleIcon, color: 'orange' },
        { id: 3, type: 'evidence', message: 'Bukti dokumen kurikulum diupload', time: '5 jam yang lalu', icon: FolderIcon, color: 'green' },
        { id: 4, type: 'user', message: 'Auditor baru ditugaskan', time: '1 hari yang lalu', icon: UsersIcon, color: 'purple' },
    ];

    const upcomingSchedules = [
        { id: 1, title: 'Visitasi Audit Prodi SI', date: '20 Feb 2025', time: '09:00 - 12:00', unit: 'Prodi Sistem Informasi' },
        { id: 2, title: 'RTM Semester Genap', date: '25 Feb 2025', time: '10:00 - 14:00', unit: 'Universitas' },
        { id: 3, title: 'Deadline Upload Bukti', date: '28 Feb 2025', time: '23:59', unit: 'Semua Prodi' },
    ];

    const ikuProgress = [
        { id: 1, name: 'Daya Saing Lulusan', target: 80, actual: 82, status: 'achieved' },
        { id: 2, name: 'Penjelajahan Data', target: 25, actual: 18, status: 'warning' },
        { id: 3, name: 'Karya Inovatif', target: 15, actual: 12, status: 'warning' },
        { id: 4, name: 'Publikasi Bereputasi', target: 25, actual: 28, status: 'achieved' },
    ];

    const getStatusColor = (status) => {
        const colors = {
            achieved: 'bg-green-100 text-green-800',
            warning: 'bg-yellow-100 text-yellow-800',
            danger: 'bg-red-100 text-red-800',
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    };

    const getStatusIcon = (status) => {
        if (status === 'achieved') return <CheckCircleIcon className="w-4 h-4 text-green-600" />;
        return <ClockIcon className="w-4 h-4 text-yellow-600" />;
    };

    // Role-specific welcome message
    const getWelcomeMessage = () => {
        if (isAdmin) return 'Selamat datang di Panel Admin E-SPMI';
        if (isAuditor) return 'Selamat datang, Auditor Internal';
        return 'Selamat datang di E-SPMI';
    };

    return (
        <EspmiLayout auth={auth} title="Dashboard E-SPMI">
            <Head title="Dashboard" />

            {/* Welcome Banner */}
            <div className="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 mb-8 text-white">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold mb-2">{getWelcomeMessage()}</h1>
                        <p className="text-blue-100">
                            {isAdmin 
                                ? 'Kelola seluruh sistem SPMI, pengguna, dan konfigurasi dari panel ini.'
                                : isAuditor
                                ? 'Kelola jadwal audit, kertas kerja, dan temuan dari panel ini.'
                                : 'Akses informasi audit, upload bukti, dan pantau status SPMI Anda.'}
                        </p>
                    </div>
                    <div className="hidden md:flex items-center gap-2 bg-white/10 px-4 py-2 rounded-lg">
                        <ShieldCheckIcon className="w-5 h-5" />
                        <span className="text-sm">{roles.join(', ')}</span>
                    </div>
                </div>
            </div>

            {/* Stats Cards - Role based */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {/* Audit Card - Admin & Auditor */}
                {(isAdmin || isAuditor) && (
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Total Audit</p>
                                <p className="text-2xl font-bold text-gray-900 mt-1">{dashboardStats.totalAudits}</p>
                                <p className="text-xs text-gray-500 mt-1">{dashboardStats.activeAudits} aktif</p>
                            </div>
                            <div className="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <ClipboardDocumentCheckIcon className="w-6 h-6 text-blue-600" />
                            </div>
                        </div>
                    </div>
                )}

                {/* Findings Card - All roles */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm font-medium text-gray-600">Temuan Tindak Lanjut</p>
                            <p className="text-2xl font-bold text-gray-900 mt-1">{dashboardStats.pendingFindings}</p>
                            <p className="text-xs text-gray-500 mt-1">Menunggu verifikasi</p>
                        </div>
                        <div className="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <ExclamationTriangleIcon className="w-6 h-6 text-orange-600" />
                        </div>
                    </div>
                </div>

                {/* Evidence Card - All roles */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm font-medium text-gray-600">Upload Bukti</p>
                            <p className="text-2xl font-bold text-gray-900 mt-1">{dashboardStats.completedEvidence}/{dashboardStats.totalEvidence}</p>
                            <p className="text-xs text-gray-500 mt-1">
                                {Math.round((dashboardStats.completedEvidence / dashboardStats.totalEvidence) * 100)}% selesai
                            </p>
                        </div>
                        <div className="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <FolderIcon className="w-6 h-6 text-green-600" />
                        </div>
                    </div>
                </div>

                {/* IKU Card - All roles */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm font-medium text-gray-600">Pencapaian IKU</p>
                            <p className="text-2xl font-bold text-gray-900 mt-1">{dashboardStats.ikuAchievement}%</p>
                            <p className="text-xs text-gray-500 mt-1">Rata-rata 8 IKU</p>
                        </div>
                        <div className="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <ChartBarIcon className="w-6 h-6 text-purple-600" />
                        </div>
                    </div>
                </div>
            </div>

            {/* Main Content Grid */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Left Column - IKU Progress */}
                <div className="lg:col-span-2 space-y-8">
                    {/* IKU Progress Section */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900">Progres Indikator Kinerja Utama (IKU)</h3>
                            <p className="text-sm text-gray-500">Status pencapaian 8 IKU periode berjalan</p>
                        </div>
                        <div className="p-6">
                            <div className="space-y-6">
                                {ikuProgress.map((iku) => (
                                    <div key={iku.id}>
                                        <div className="flex items-center justify-between mb-2">
                                            <div className="flex items-center gap-2">
                                                <span className="text-sm font-medium text-gray-700">{iku.name}</span>
                                                {getStatusIcon(iku.status)}
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <span className="text-sm font-semibold text-gray-900">{iku.actual}%</span>
                                                <span className="text-xs text-gray-500">/ {iku.target}% target</span>
                                            </div>
                                        </div>
                                        <div className="w-full bg-gray-200 rounded-full h-2.5">
                                            <div 
                                                className={`h-2.5 rounded-full transition-all ${
                                                    iku.status === 'achieved' ? 'bg-green-500' : 
                                                    iku.status === 'warning' ? 'bg-yellow-500' : 'bg-red-500'
                                                }`}
                                                style={{ width: `${Math.min((iku.actual / iku.target) * 100, 100)}%` }}
                                            />
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Recent Activity */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                        </div>
                        <div className="p-6">
                            <div className="space-y-4">
                                {recentActivities.map((activity) => (
                                    <div key={activity.id} className="flex items-start gap-4">
                                        <div className={`w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-${activity.color}-100`}>
                                            <activity.icon className={`w-5 h-5 text-${activity.color}-600`} />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-sm font-medium text-gray-900">{activity.message}</p>
                                            <p className="text-xs text-gray-500 mt-0.5">{activity.time}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Right Column - Schedules & Quick Links */}
                <div className="space-y-8">
                    {/* Upcoming Schedule */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900">Jadwal Mendatang</h3>
                        </div>
                        <div className="p-6">
                            <div className="space-y-4">
                                {upcomingSchedules.map((schedule) => (
                                    <div key={schedule.id} className="border-l-4 border-blue-500 pl-4 py-2">
                                        <h4 className="text-sm font-semibold text-gray-900">{schedule.title}</h4>
                                        <div className="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                            <CalendarIcon className="w-4 h-4" />
                                            <span>{schedule.date}</span>
                                            <span>•</span>
                                            <span>{schedule.time}</span>
                                        </div>
                                        <p className="text-xs text-gray-600 mt-1">{schedule.unit}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Quick Links - Role Based */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900">Akses Cepat</h3>
                        </div>
                        <div className="p-4">
                            <div className="grid grid-cols-2 gap-3">
                                <a href="#" className="flex flex-col items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors group">
                                    <FolderIcon className="w-6 h-6 text-gray-400 group-hover:text-blue-500 mb-2" />
                                    <span className="text-xs font-medium text-gray-700 group-hover:text-blue-700">Upload Bukti</span>
                                </a>
                                {(isAdmin || isAuditor) && (
                                    <a href="#" className="flex flex-col items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors group">
                                        <ClipboardDocumentCheckIcon className="w-6 h-6 text-gray-400 group-hover:text-blue-500 mb-2" />
                                        <span className="text-xs font-medium text-gray-700 group-hover:text-blue-700">Audit Saya</span>
                                    </a>
                                )}
                                <a href="#" className="flex flex-col items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors group">
                                    <ExclamationTriangleIcon className="w-6 h-6 text-gray-400 group-hover:text-blue-500 mb-2" />
                                    <span className="text-xs font-medium text-gray-700 group-hover:text-blue-700">Temuan PTK</span>
                                </a>
                                <a href="#" className="flex flex-col items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors group">
                                    <ChartBarIcon className="w-6 h-6 text-gray-400 group-hover:text-blue-500 mb-2" />
                                    <span className="text-xs font-medium text-gray-700 group-hover:text-blue-700">Laporan IKU</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </EspmiLayout>
    );
}
