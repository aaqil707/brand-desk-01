/**
 * ToastManager — Animated toast notification system.
 */
import { useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { CheckCircle, XCircle, Info, AlertTriangle, X } from 'lucide-react';
import { useUIStore } from '../../store';
import './Toast.css';

const icons = {
  success: <CheckCircle size={18} />,
  error: <XCircle size={18} />,
  info: <Info size={18} />,
  warning: <AlertTriangle size={18} />,
};

function Toast({ toast, onDismiss }) {
  useEffect(() => {
    const timer = setTimeout(() => onDismiss(toast.id), 4000);
    return () => clearTimeout(timer);
  }, [toast.id, onDismiss]);

  return (
    <motion.div
      className={`toast toast-${toast.type || 'info'}`}
      initial={{ opacity: 0, x: 60, scale: 0.9 }}
      animate={{ opacity: 1, x: 0, scale: 1 }}
      exit={{ opacity: 0, x: 60, scale: 0.9 }}
      transition={{ type: 'spring', stiffness: 400, damping: 25 }}
      layout
    >
      <span className="toast-icon">{icons[toast.type || 'info']}</span>
      <span className="toast-message">{toast.message}</span>
      <button className="toast-dismiss" onClick={() => onDismiss(toast.id)} aria-label="Dismiss">
        <X size={14} />
      </button>
    </motion.div>
  );
}

export default function ToastManager() {
  const { toasts, removeToast } = useUIStore();

  return (
    <div className="toast-container" role="status" aria-live="polite">
      <AnimatePresence mode="popLayout">
        {toasts.map((toast) => (
          <Toast key={toast.id} toast={toast} onDismiss={removeToast} />
        ))}
      </AnimatePresence>
    </div>
  );
}
