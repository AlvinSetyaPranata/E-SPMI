import React, { useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/inertia-react';
import Toast from '@/Components/Toast';
import { ShieldCheckIcon, EyeIcon, EyeSlashIcon, UserIcon, EnvelopeIcon, LockClosedIcon, BuildingOfficeIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        nip: '',
        unit: '',
        password: '',
        password_confirmation: '',
    });

    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('register'));
    };

    return (
        <>
            <Toast />
            <div className="min-h-screen flex">
            <Head title="Register | E-SPMI" />

            {/* Left Side - Branding */}
            <div className="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 flex-col justify-center items-center text-white p-12">
                <div className="max-w-md text-center">
                    <div className="w-20 h-20 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30">
                        <ShieldCheckIcon className="w-12 h-12 text-white" />
                    </div>
                    <h1 className="text-4xl font-bold mb-4">E-SPMI</h1>
                    <p className="text-xl text-blue-200 mb-6">Sistem Penjaminan Mutu Internal</p>
                    
                    <div className="mt-8 space-y-4 text-left">
                        <div className="flex items-start gap-4">
                            <div className="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span className="text-blue-400 font-bold">1</span>
                            </div>
                            <div>
                                <h3 className="font-semibold">Daftar Akun</h3>
                                <p className="text-sm text-gray-400">Buat akun dengan email institusi</p>
                            </div>
                        </div>
                        <div className="flex items-start gap-4">
                            <div className="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span className="text-blue-400 font-bold">2</span>
                            </div>
                            <div>
                                <h3 className="font-semibold">Verifikasi</h3>
                                <p className="text-sm text-gray-400">Tunggu verifikasi dari admin LPM</p>
                            </div>
                        </div>
                        <div className="flex items-start gap-4">
                            <div className="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span className="text-blue-400 font-bold">3</span>
                            </div>
                            <div>
                                <h3 className="font-semibold">Akses Sistem</h3>
                                <p className="text-sm text-gray-400">Mulai kelola SPMI Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Right Side - Register Form */}
            <div className="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 bg-gray-50 overflow-y-auto">
                <div className="w-full max-w-md py-8">
                    {/* Mobile Logo */}
                    <div className="lg:hidden text-center mb-8">
                        <div className="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <ShieldCheckIcon className="w-10 h-10 text-white" />
                        </div>
                        <h1 className="text-2xl font-bold text-gray-900">E-SPMI</h1>
                        <p className="text-gray-500">Sistem Penjaminan Mutu Internal</p>
                    </div>

                    <div className="bg-white rounded-2xl shadow-xl p-8">
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                        <p className="text-gray-500 mb-6">Daftar untuk mengakses E-SPMI</p>

                        <form onSubmit={submit}>
                            <div className="space-y-5">
                                {/* Name */}
                                <div>
                                    <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap
                                    </label>
                                    <div className="relative">
                                        <UserIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input
                                            id="name"
                                            type="text"
                                            name="name"
                                            value={data.name}
                                            className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Nama lengkap"
                                            autoComplete="name"
                                            autoFocus
                                            onChange={onHandleChange}
                                        />
                                    </div>
                                    {errors.name && (
                                        <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                                    )}
                                </div>

                                {/* Email */}
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1">
                                        Email Institusi
                                    </label>
                                    <div className="relative">
                                        <EnvelopeIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            value={data.email}
                                            className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="nama@universitas.ac.id"
                                            autoComplete="username"
                                            onChange={onHandleChange}
                                        />
                                    </div>
                                    {errors.email && (
                                        <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                    )}
                                </div>

                                {/* NIP */}
                                <div>
                                    <label htmlFor="nip" className="block text-sm font-medium text-gray-700 mb-1">
                                        NIP/NIDN
                                    </label>
                                    <input
                                        id="nip"
                                        type="text"
                                        name="nip"
                                        value={data.nip}
                                        className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="197001011990011001"
                                        onChange={onHandleChange}
                                    />
                                    {errors.nip && (
                                        <p className="mt-1 text-sm text-red-600">{errors.nip}</p>
                                    )}
                                </div>

                                {/* Unit */}
                                <div>
                                    <label htmlFor="unit" className="block text-sm font-medium text-gray-700 mb-1">
                                        Unit/Fakultas
                                    </label>
                                    <div className="relative">
                                        <BuildingOfficeIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <select
                                            id="unit"
                                            name="unit"
                                            value={data.unit}
                                            className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
                                            onChange={onHandleChange}
                                        >
                                            <option value="">Pilih Unit</option>
                                            <option value="ftik">Fakultas Teknik dan Ilmu Komputer</option>
                                            <option value="fe">Fakultas Ekonomi</option>
                                            <option value="fip">Fakultas Ilmu Pendidikan</option>
                                            <option value="fisip">Fakultas Ilmu Sosial dan Politik</option>
                                            <option value="fh">Fakultas Hukum</option>
                                            <option value="lpm">Lembaga Penjaminan Mutu</option>
                                        </select>
                                    </div>
                                    {errors.unit && (
                                        <p className="mt-1 text-sm text-red-600">{errors.unit}</p>
                                    )}
                                </div>

                                {/* Password */}
                                <div>
                                    <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-1">
                                        Password
                                    </label>
                                    <div className="relative">
                                        <LockClosedIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input
                                            id="password"
                                            type={showPassword ? 'text' : 'password'}
                                            name="password"
                                            value={data.password}
                                            className="w-full pl-10 pr-12 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Minimal 8 karakter"
                                            autoComplete="new-password"
                                            onChange={onHandleChange}
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowPassword(!showPassword)}
                                            className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                        >
                                            {showPassword ? (
                                                <EyeSlashIcon className="w-5 h-5" />
                                            ) : (
                                                <EyeIcon className="w-5 h-5" />
                                            )}
                                        </button>
                                    </div>
                                    {errors.password && (
                                        <p className="mt-1 text-sm text-red-600">{errors.password}</p>
                                    )}
                                </div>

                                {/* Confirm Password */}
                                <div>
                                    <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700 mb-1">
                                        Konfirmasi Password
                                    </label>
                                    <div className="relative">
                                        <LockClosedIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input
                                            id="password_confirmation"
                                            type={showConfirmPassword ? 'text' : 'password'}
                                            name="password_confirmation"
                                            value={data.password_confirmation}
                                            className="w-full pl-10 pr-12 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Ulangi password"
                                            autoComplete="new-password"
                                            onChange={onHandleChange}
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                                            className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                        >
                                            {showConfirmPassword ? (
                                                <EyeSlashIcon className="w-5 h-5" />
                                            ) : (
                                                <EyeIcon className="w-5 h-5" />
                                            )}
                                        </button>
                                    </div>
                                    {errors.password_confirmation && (
                                        <p className="mt-1 text-sm text-red-600">{errors.password_confirmation}</p>
                                    )}
                                </div>

                                {/* Terms */}
                                <div className="flex items-start gap-2">
                                    <input
                                        type="checkbox"
                                        id="terms"
                                        className="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1"
                                        required
                                    />
                                    <label htmlFor="terms" className="text-sm text-gray-600">
                                        Saya menyetujui{' '}
                                        <a href="#" className="text-blue-600 hover:text-blue-700 font-medium">Syarat dan Ketentuan</a>
                                        {' '}serta{' '}
                                        <a href="#" className="text-blue-600 hover:text-blue-700 font-medium">Kebijakan Privasi</a>
                                    </label>
                                </div>

                                {/* Submit Button */}
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {processing ? 'Mendaftar...' : 'Daftar Sekarang'}
                                </button>
                            </div>
                        </form>

                        {/* Divider */}
                        <div className="relative my-6">
                            <div className="absolute inset-0 flex items-center">
                                <div className="w-full border-t border-gray-200"></div>
                            </div>
                            <div className="relative flex justify-center">
                                <span className="px-4 bg-white text-sm text-gray-500">atau</span>
                            </div>
                        </div>

                        {/* Login Link */}
                        <p className="text-center text-sm text-gray-600">
                            Sudah punya akun?{' '}
                            <Link href={route('login')} className="text-blue-600 hover:text-blue-700 font-medium">
                                Masuk sekarang
                            </Link>
                        </p>
                    </div>

                    {/* Footer */}
                    <p className="text-center text-xs text-gray-400 mt-8">
                        © 2025 E-SPMI. Sistem Penjaminan Mutu Internal.
                    </p>
                </div>
            </div>
            </div>
        </>
    );
}
