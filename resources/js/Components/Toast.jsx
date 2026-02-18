import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/inertia-react';
import { 
    CheckCircleIcon, 
    XCircleIcon, 
    ExclamationTriangleIcon, 
    InformationCircleIcon,
    XMarkIcon 
} from '@heroicons/react/24/outline';

export default function Toast() {
    const { flash, errors } = usePage().props;
    const [toasts, setToasts] = useState([]);

    useEffect(() => {
        // Handle flash messages
        if (flash?.success) {
            addToast('success', flash.success);
        }
        if (flash?.error) {
            addToast('error', flash.error);
        }
        if (flash?.warning) {
            addToast('warning', flash.warning);
        }
        if (flash?.info) {
            addToast('info', flash.info);
        }

        // Handle validation errors
        if (errors && Object.keys(errors).length > 0) {
            const firstError = Object.values(errors)[0];
            addToast('error', firstError);
        }
    }, [flash, errors]);

    const addToast = (type, message) => {
        const id = Date.now();
        setToasts(prev => [...prev, { id, type, message }]);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            removeToast(id);
        }, 5000);
    };

    const removeToast = (id) => {
        setToasts(prev => prev.filter(toast => toast.id !== id));
    };

    const getIcon = (type) => {
        switch (type) {
            case 'success':
                return <CheckCircleIcon className="w-5 h-5 text-green-500" />;
            case 'error':
                return <XCircleIcon className="w-5 h-5 text-red-500" />;
            case 'warning':
                return <ExclamationTriangleIcon className="w-5 h-5 text-yellow-500" />;
            case 'info':
                return <InformationCircleIcon className="w-5 h-5 text-blue-500" />;
            default:
                return <InformationCircleIcon className="w-5 h-5 text-blue-500" />;
        }
    };

    const getStyles = (type) => {
        switch (type) {
            case 'success':
                return 'bg-green-50 border-green-200 text-green-800';
            case 'error':
                return 'bg-red-50 border-red-200 text-red-800';
            case 'warning':
                return 'bg-yellow-50 border-yellow-200 text-yellow-800';
            case 'info':
                return 'bg-blue-50 border-blue-200 text-blue-800';
            default:
                return 'bg-gray-50 border-gray-200 text-gray-800';
        }
    };

    if (toasts.length === 0) return null;

    return (
        <div className="fixed top-4 right-4 z-50 space-y-3">
            {toasts.map((toast) => (
                <div
                    key={toast.id}
                    className={`flex items-center gap-3 px-4 py-3 rounded-lg border shadow-lg min-w-[300px] max-w-md animate-slide-in ${getStyles(toast.type)}`}
                    style={{
                        animation: 'slideIn 0.3s ease-out',
                    }}
                >
                    {getIcon(toast.type)}
                    <p className="text-sm font-medium flex-1">{toast.message}</p>
                    <button
                        onClick={() => removeToast(toast.id)}
                        className="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <XMarkIcon className="w-4 h-4" />
                    </button>
                </div>
            ))}
            <style>{`
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `}</style>
        </div>
    );
}

// Hook to use toast programmatically
export const useToast = () => {
    const [localToasts, setLocalToasts] = useState([]);

    const show = (type, message) => {
        const id = Date.now();
        setLocalToasts(prev => [...prev, { id, type, message }]);
        
        setTimeout(() => {
            setLocalToasts(prev => prev.filter(t => t.id !== id));
        }, 5000);
    };

    return {
        success: (message) => show('success', message),
        error: (message) => show('error', message),
        warning: (message) => show('warning', message),
        info: (message) => show('info', message),
        toasts: localToasts,
    };
};
