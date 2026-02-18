import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/inertia-react';
import Toast from '@/Components/Toast';
import { 
    HomeIcon, 
    UsersIcon, 
    BuildingOfficeIcon, 
    ShieldCheckIcon,
    DocumentCheckIcon,
    ClipboardDocumentListIcon,
    ChartBarIcon,
    Cog6ToothIcon,
    BookOpenIcon,
    CalendarIcon,
    FolderIcon,
    ExclamationTriangleIcon,
    UserGroupIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    Bars3Icon,
    XMarkIcon,
    DocumentTextIcon,
    ArchiveBoxIcon,
    AcademicCapIcon,
    ClipboardIcon,
    BellIcon,
    UserCircleIcon,
    ArrowRightOnRectangleIcon,
    Cog8ToothIcon
} from '@heroicons/react/24/outline';

// Icon mapping
const iconMap = {
    HomeIcon,
    UsersIcon,
    BuildingOfficeIcon,
    ShieldCheckIcon,
    DocumentCheckIcon,
    ClipboardDocumentListIcon,
    ChartBarIcon,
    Cog6ToothIcon,
    BookOpenIcon,
    CalendarIcon,
    FolderIcon,
    ExclamationTriangleIcon,
    UserGroupIcon,
    DocumentTextIcon,
    ArchiveBoxIcon,
    AcademicCapIcon,
    ClipboardIcon,
};

export default function EspmiLayout({ auth, children, title = 'Dashboard' }) {
    const { menu } = usePage().props;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [profileOpen, setProfileOpen] = useState(false);
    const [expandedMenus, setExpandedMenus] = useState(() => {
        // Expand first menu by default
        if (menu && menu.length > 0) {
            const firstWithChildren = menu.find(item => item.children);
            return firstWithChildren ? { [firstWithChildren.id]: true } : {};
        }
        return {};
    });

    const toggleMenu = (menuId) => {
        setExpandedMenus(prev => ({ ...prev, [menuId]: !prev[menuId] }));
    };

    const getIcon = (iconName) => {
        const Icon = iconMap[iconName] || HomeIcon;
        return Icon;
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Toast Notifications */}
            <Toast />
            
            {/* Mobile sidebar overlay */}
            {sidebarOpen && (
                <div 
                    className="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
                    onClick={() => setSidebarOpen(false)}
                />
            )}

            {/* Sidebar */}
            <aside className={`fixed top-0 left-0 z-50 h-full w-64 bg-slate-900 text-white transform transition-transform duration-300 ease-in-out ${
                sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            }`}>
                {/* Logo */}
                <div className="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800">
                    <div className="flex items-center gap-3">
                        <div className="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <ShieldCheckIcon className="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h1 className="font-bold text-lg leading-tight">E-SPMI</h1>
                            <p className="text-xs text-slate-400">Sistem Penjaminan Mutu</p>
                        </div>
                    </div>
                </div>

                {/* Navigation */}
                <nav className="flex-1 overflow-y-auto py-4 px-3 space-y-1 pb-20">
                    {menu && menu.map((item) => (
                        <div key={item.id}>
                            {item.children ? (
                                <div>
                                    <button
                                        onClick={() => toggleMenu(item.id)}
                                        className={`w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors ${
                                            expandedMenus[item.id] 
                                                ? 'bg-blue-600 text-white' 
                                                : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                                        }`}
                                    >
                                        <div className="flex items-center gap-3">
                                            {(() => {
                                                const Icon = getIcon(item.icon);
                                                return <Icon className="w-5 h-5" />;
                                            })()}
                                            <span>{item.name}</span>
                                        </div>
                                        {expandedMenus[item.id] ? (
                                            <ChevronDownIcon className="w-4 h-4" />
                                        ) : (
                                            <ChevronRightIcon className="w-4 h-4" />
                                        )}
                                    </button>
                                    {expandedMenus[item.id] && (
                                        <div className="mt-1 ml-4 pl-4 border-l border-slate-700 space-y-1">
                                            {item.children.map((child, idx) => {
                                                const ChildIcon = getIcon(child.icon);
                                                return (
                                                    <Link
                                                        key={idx}
                                                        href={child.href}
                                                        className="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-400 hover:bg-slate-800 hover:text-white transition-colors"
                                                    >
                                                        <ChildIcon className="w-4 h-4" />
                                                        <span>{child.name}</span>
                                                    </Link>
                                                );
                                            })}
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <Link
                                    href={item.href}
                                    className={`flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors ${
                                        item.active
                                            ? 'bg-blue-600 text-white'
                                            : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                                    }`}
                                >
                                    {(() => {
                                        const Icon = getIcon(item.icon);
                                        return <Icon className="w-5 h-5" />;
                                    })()}
                                    <span>{item.name}</span>
                                </Link>
                            )}
                        </div>
                    ))}
                </nav>
            </aside>

            {/* Main content */}
            <div className="lg:ml-64 min-h-screen">
                {/* Top header */}
                <header className="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30">
                    <div className="flex items-center gap-4">
                        <button
                            onClick={() => setSidebarOpen(true)}
                            className="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100"
                        >
                            <Bars3Icon className="w-6 h-6" />
                        </button>
                        <h2 className="text-xl font-semibold text-gray-800">{title}</h2>
                    </div>
                    
                    {/* Right side - Date, Notifications, Profile */}
                    <div className="flex items-center gap-4">
                        {/* Date */}
                        <span className="text-sm text-gray-600 hidden md:inline">
                            {new Date().toLocaleDateString('id-ID', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}
                        </span>

                        {/* Notification Bell */}
                        <button className="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <BellIcon className="w-6 h-6" />
                            <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        {/* Profile Dropdown */}
                        <div className="relative">
                            <button
                                onClick={() => setProfileOpen(!profileOpen)}
                                className="flex items-center gap-3 p-1.5 pr-3 rounded-full hover:bg-gray-100 transition-colors"
                            >
                                <div className="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                    {auth.user.name.charAt(0).toUpperCase()}
                                </div>
                                <div className="hidden sm:block text-left">
                                    <p className="text-sm font-medium text-gray-700 leading-tight">{auth.user.name}</p>
                                    <p className="text-xs text-gray-500 leading-tight">
                                        {auth.user.roles && auth.user.roles[0]}
                                    </p>
                                </div>
                                <ChevronDownIcon className={`w-4 h-4 text-gray-500 transition-transform ${profileOpen ? 'rotate-180' : ''}`} />
                            </button>

                            {/* Profile Dropdown Menu */}
                            {profileOpen && (
                                <>
                                    <div 
                                        className="fixed inset-0 z-40"
                                        onClick={() => setProfileOpen(false)}
                                    />
                                    <div className="absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 z-50 py-2">
                                        {/* User Info */}
                                        <div className="px-4 py-3 border-b border-gray-100">
                                            <p className="font-medium text-gray-900">{auth.user.name}</p>
                                            <p className="text-sm text-gray-500">{auth.user.email}</p>
                                            <div className="mt-2 flex flex-wrap gap-1">
                                                {auth.user.roles && auth.user.roles.map((role, idx) => (
                                                    <span key={idx} className="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">
                                                        {role}
                                                    </span>
                                                ))}
                                            </div>
                                        </div>

                                        {/* Menu Items */}
                                        <div className="py-1">
                                            <Link
                                                href="#"
                                                className="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                            >
                                                <UserCircleIcon className="w-5 h-5 text-gray-400" />
                                                Profil Saya
                                            </Link>
                                            <Link
                                                href="#"
                                                className="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                            >
                                                <Cog8ToothIcon className="w-5 h-5 text-gray-400" />
                                                Pengaturan
                                            </Link>
                                        </div>

                                        {/* Divider */}
                                        <div className="border-t border-gray-100 my-1"></div>

                                        {/* Logout */}
                                        <Link
                                            href={route('logout')}
                                            method="post"
                                            as="button"
                                            className="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                        >
                                            <ArrowRightOnRectangleIcon className="w-5 h-5" />
                                            Keluar
                                        </Link>
                                    </div>
                                </>
                            )}
                        </div>
                    </div>
                </header>

                {/* Page content */}
                <main className="p-4 lg:p-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
