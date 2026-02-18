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
    ClipboardIcon
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
                <nav className="flex-1 overflow-y-auto py-4 px-3 space-y-1">
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

                {/* User section */}
                <div className="border-t border-slate-800 p-4">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {auth.user.name.charAt(0).toUpperCase()}
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-white truncate">{auth.user.name}</p>
                            <p className="text-xs text-slate-400 truncate">{auth.user.email}</p>
                        </div>
                    </div>
                    <div className="mt-2 flex flex-wrap gap-1">
                        {auth.user.roles && auth.user.roles.map((role, idx) => (
                            <span key={idx} className="px-2 py-0.5 bg-slate-700 text-slate-300 text-xs rounded">
                                {role}
                            </span>
                        ))}
                    </div>
                    <Link
                        href={route('logout')}
                        method="post"
                        as="button"
                        className="mt-3 w-full flex items-center justify-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm rounded-lg transition-colors"
                    >
                        <span>Keluar</span>
                    </Link>
                </div>
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
                    <div className="flex items-center gap-4">
                        <span className="text-sm text-gray-600 hidden sm:inline">
                            {new Date().toLocaleDateString('id-ID', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}
                        </span>
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
