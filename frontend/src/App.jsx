/**
 * App — Root component. Handles auth-gated routing.
 */
import { useEffect } from 'react';
import { useAuthStore } from './store';
import LoginPage from './pages/LoginPage/LoginPage';
import Dashboard from './pages/Dashboard/Dashboard';
import ProfilePage from './pages/ProfilePage/ProfilePage';
import ToastManager from './components/Toast/Toast';
import { Routes, Route } from 'react-router-dom';

export default function App() {
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
            Loading VDart Brand Desk...
          </p>
        </div>
      </div>
    );
  }

  return (
    <>
      <Routes>
        <Route path="/profile/:id" element={<ProfilePage />} />
        <Route path="/*" element={isAuthenticated ? <Dashboard /> : <LoginPage />} />
      </Routes>
      <ToastManager />
    </>
  );
}
