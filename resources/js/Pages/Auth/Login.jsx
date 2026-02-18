import React, { useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/inertia-react';
import Toast from '@/Components/Toast';
import { ShieldCheckIcon, EyeIcon, EyeSlashIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: '',
    });

    const [showPassword, setShowPassword] = useState(false);

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? event.target.checked : event.target.value);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <>
            <Toast />
            <div className="min-h-screen flex">
            <Head title="Login | E-SPMI" />

            {/* Left Side - Branding */}
            <div className="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 flex-col justify-center items-center text-white p-12">
                <div className="max-w-md text-center">
                    <div className="w-20 h-20 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30">
                        <ShieldCheckIcon className="w-12 h-12 text-white" />
                    </div>
                    <h1 className="text-4xl font-bold mb-4">E-SPMI</h1>
                    <p className="text-xl text-blue-200 mb-6">Sistem Penjaminan Mutu Internal</p>
                    <p className="text-gray-300 leading-relaxed">
                        Platform terintegrasi untuk mengelola siklus PPEPP 
                        (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan) 
                        menuju tercapainya budaya mutu yang berkelanjutan.
                    </p>
                    
                    <div className="mt-12 grid grid-cols-3 gap-6 text-center">
                        <div>
                            <div className="text-3xl font-bold text-blue-400">8</div>
                            <div className="text-sm text-gray-400">IKU</div>
                        </div>
                        <div>
                            <div className="text-3xl font-bold text-blue-400">5</div>
                            <div className="text-sm text-gray-400">Fase PPEPP</div>
                        </div>
                        <div>
                            <div className="text-3xl font-bold text-blue-400">100%</div>
                            <div className="text-sm text-gray-400">Digital</div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Right Side - Login Form */}
            <div className="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 bg-gray-50">
                <div className="w-full max-w-md">
                    {/* Mobile Logo */}
                    <div className="lg:hidden text-center mb-8">
                        <div className="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <ShieldCheckIcon className="w-10 h-10 text-white" />
                        </div>
                        <h1 className="text-2xl font-bold text-gray-900">E-SPMI</h1>
                        <p className="text-gray-500">Sistem Penjaminan Mutu Internal</p>
                    </div>

                    <div className="bg-white rounded-2xl shadow-xl p-8">
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                        <p className="text-gray-500 mb-6">Masuk ke akun E-SPMI Anda</p>

                        {status && (
                            <div className="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p className="text-sm text-green-600">{status}</p>
                            </div>
                        )}

                        <form onSubmit={submit}>
                            <div className="space-y-5">
                                {/* Email */}
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1">
                                        Email
                                    </label>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value={data.email}
                                        className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="nama@universitas.ac.id"
                                        autoComplete="username"
                                        autoFocus
                                        onChange={onHandleChange}
                                    />
                                    {errors.email && (
                                        <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                    )}
                                </div>

                                {/* Password */}
                                <div>
                                    <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-1">
                                        Password
                                    </label>
                                    <div className="relative">
                                        <input
                                            id="password"
                                            type={showPassword ? 'text' : 'password'}
                                            name="password"
                                            value={data.password}
                                            className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-12"
                                            placeholder="••••••••"
                                            autoComplete="current-password"
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

                                {/* Remember & Forgot */}
                                <div className="flex items-center justify-between">
                                    <label className="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="remember"
                                            checked={data.remember}
                                            onChange={onHandleChange}
                                            className="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        />
                                        <span className="ml-2 text-sm text-gray-600">Ingat saya</span>
                                    </label>
                                    {canResetPassword && (
                                        <Link
                                            href={route('password.request')}
                                            className="text-sm text-blue-600 hover:text-blue-700 font-medium"
                                        >
                                            Lupa password?
                                        </Link>
                                    )}
                                </div>

                                {/* Submit Button */}
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {processing ? 'Memproses...' : 'Masuk'}
                                </button>
                            </div>
                        </form>

                        {/* Admin Contact */}
                        <div className="mt-6 p-4 bg-blue-50 rounded-lg">
                            <p className="text-sm text-blue-700 text-center">
                                Belum punya akun? Hubungi admin untuk pembuatan akun.
                            </p>
                        </div>
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
