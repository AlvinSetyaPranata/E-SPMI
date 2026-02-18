import React from 'react';
import { Link, Head } from '@inertiajs/inertia-react';
import { 
    ShieldCheckIcon, 
    ClipboardDocumentCheckIcon, 
    ChartBarIcon, 
    UsersIcon,
    ArrowRightIcon,
    CheckCircleIcon
} from '@heroicons/react/24/outline';

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    const features = [
        {
            icon: ClipboardDocumentCheckIcon,
            title: 'Siklus PPEPP Digital',
            description: 'Kelola seluruh siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan secara terintegrasi.',
        },
        {
            icon: ChartBarIcon,
            title: '8 IKU Real-time',
            description: 'Pantau Indikator Kinerja Utama perguruan tinggi secara real-time dengan dashboard interaktif.',
        },
        {
            icon: UsersIcon,
            title: 'Multi-Role Access',
            description: 'Sistem RBAC dengan peran Auditor, Auditee, LPM, Rektor, Dekan, dan Kaprodi.',
        },
        {
            icon: ShieldCheckIcon,
            title: 'SPBE Compliance',
            description: 'Memenuhi standar keamanan Sistem Pemerintahan Berbasis Elektronik dengan audit trail.',
        },
    ];

    return (
        <>
            <Head title="E-SPMI - Sistem Penjaminan Mutu Internal" />
            
            <div className="min-h-screen bg-gray-50">
                {/* Navigation */}
                <nav className="bg-white border-b border-gray-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-16">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <ShieldCheckIcon className="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <h1 className="font-bold text-xl text-gray-900">E-SPMI</h1>
                                </div>
                            </div>
                            <div className="flex items-center gap-4">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={route('login')}
                                            className="text-gray-600 hover:text-gray-900 font-medium"
                                        >
                                            Masuk
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                        >
                                            Daftar
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <div className="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
                        <div className="text-center max-w-3xl mx-auto">
                            <h1 className="text-4xl lg:text-6xl font-bold mb-6">
                                Sistem Penjaminan Mutu Internal
                            </h1>
                            <p className="text-xl text-blue-200 mb-8">
                                Platform terintegrasi untuk mengelola SPMI perguruan tinggi 
                                sesuai Permendiktisaintek No. 39 Tahun 2025
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-blue-500 hover:bg-blue-400 text-white rounded-xl font-semibold transition-colors"
                                >
                                    Mulai Sekarang
                                    <ArrowRightIcon className="w-5 h-5" />
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 text-white rounded-xl font-semibold transition-colors"
                                >
                                    Masuk ke Sistem
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Features Section */}
                <div className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                            <p className="text-gray-600 max-w-2xl mx-auto">
                                E-SPMI menyediakan berbagai fitur untuk mendukung implementasi 
                                penjaminan mutu internal yang efektif dan efisien.
                            </p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            {features.map((feature, index) => (
                                <div key={index} className="p-6 bg-gray-50 rounded-2xl hover:shadow-lg transition-shadow">
                                    <div className="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                                        <feature.icon className="w-6 h-6 text-blue-600" />
                                    </div>
                                    <h3 className="text-lg font-semibold text-gray-900 mb-2">{feature.title}</h3>
                                    <p className="text-gray-600 text-sm">{feature.description}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* PPEPP Section */}
                <div className="py-20 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">Siklus PPEPP</h2>
                            <p className="text-gray-600 max-w-2xl mx-auto">
                                Implementasi siklus berkelanjutan untuk peningkatan mutu yang sistematis
                            </p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
                            {[
                                { step: 'P', title: 'Penetapan', desc: 'Standar & Kebijakan' },
                                { step: 'P', title: 'Pelaksanaan', desc: 'Implementasi & Bukti' },
                                { step: 'E', title: 'Evaluasi', desc: 'Audit & Assessment' },
                                { step: 'P', title: 'Pengendalian', desc: 'Temuan & PTK' },
                                { step: 'P', title: 'Peningkatan', desc: 'RTM & Action Plan' },
                            ].map((item, index) => (
                                <div key={index} className="text-center p-6 bg-white rounded-2xl shadow-sm">
                                    <div className="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                        {item.step}
                                    </div>
                                    <h3 className="font-semibold text-gray-900">{item.title}</h3>
                                    <p className="text-sm text-gray-500">{item.desc}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* 8 IKU Section */}
                <div className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">8 Indikator Kinerja Utama</h2>
                            <p className="text-gray-600 max-w-2xl mx-auto">
                                Pemantauan kinerja perguruan tinggi berbasis 8 IKU sesuai kebijakan Kemendikbudristek
                            </p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {[
                                'Daya Saing Lulusan',
                                'Penjelajahan Data',
                                'Karya Inovatif',
                                'Kolaborasi Luar Negeri',
                                'Publikasi Bereputasi',
                                'Luaran Penelitian',
                                'Kepuasan Pengguna',
                                'Kemampuan Bahasa Inggris',
                            ].map((iku, index) => (
                                <div key={index} className="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                                    <CheckCircleIcon className="w-5 h-5 text-green-500 flex-shrink-0" />
                                    <span className="text-sm font-medium text-gray-700">{iku}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* CTA Section */}
                <div className="py-20 bg-blue-600">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h2 className="text-3xl font-bold text-white mb-4">
                            Siap Meningkatkan Mutu Institusi Anda?
                        </h2>
                        <p className="text-blue-100 mb-8 text-lg">
                            Bergabung dengan E-SPMI untuk transformasi digital penjaminan mutu internal
                        </p>
                        <Link
                            href={route('register')}
                            className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-gray-100 transition-colors"
                        >
                            Daftar Gratis Sekarang
                            <ArrowRightIcon className="w-5 h-5" />
                        </Link>
                    </div>
                </div>

                {/* Footer */}
                <footer className="bg-slate-900 text-gray-400 py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex flex-col md:flex-row justify-between items-center">
                            <div className="flex items-center gap-3 mb-4 md:mb-0">
                                <div className="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <ShieldCheckIcon className="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <h3 className="text-white font-bold">E-SPMI</h3>
                                    <p className="text-xs">Sistem Penjaminan Mutu Internal</p>
                                </div>
                            </div>
                            <div className="text-sm">
                                © 2025 E-SPMI. All rights reserved.
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
