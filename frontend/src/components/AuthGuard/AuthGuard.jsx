/**
 * AuthGuard — Protects routes by verifying session state.
 * Shows loading spinner while checking, redirects to login if unauthenticated.
 */
import { useEffect } from 'react';
import { useAuthStore } from '../../store';

export default function AuthGuard({ children }) {
  const { isAuthenticated, isLoading, checkSession } = useAuthStore();

  useEffect(() => {
    checkSession();
  }, [checkSession]);

  if (isLoading) {
    return (
      <div
        style={{
          minHeight: '100vh',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          background: 'var(--color-bg)',
        }}
      >
        <div style={{ textAlign: 'center' }}>
          <div className="spinner" style={{ width: 40, height: 40, margin: '0 auto 16px' }} />
          <p style={{ color: 'var(--color-gray-500)', fontSize: '0.9rem' }}>
            Verifying session...
          </p>
        </div>
      </div>
    );
  }

  if (!isAuthenticated) {
    return null; // App.jsx handles rendering LoginPage for unauthenticated users
  }

  return children;
}
