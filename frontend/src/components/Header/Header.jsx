/**
 * Header — Top navigation bar with light glassmorphism.
 */
import { motion } from 'framer-motion';
import { LogOut, HelpCircle, Home } from 'lucide-react';
import vdartLogo from '../../assets/vdart_logo.png';
import { useAuthStore } from '../../store';
import './Header.css';
const LOGO_URL = 'https://raw.githubusercontent.com/Saranraj102000/VDart-images/main/VDart_Logo.png';


export default function Header({ showHome = false, onHomeClick }) {
  const { user, logout } = useAuthStore();

  return (
    <motion.header
      className="app-header glass-light"
      initial={{ y: -100, opacity: 0 }}
      animate={{ y: 0, opacity: 1 }}
      transition={{ duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
    >
      <div className="header-inner">
        {/* Decorative glowing orb */}
        <div className="header-glow" aria-hidden="true" />

         <div className="header-left">
            <img src={vdartLogo} alt="VDart Logo" className="header-logo" />
         </div>

        <div className="header-right">
          {user && (
            <motion.span
              className="user-badge"
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 0.3 }}
            >
              <span className="user-avatar">
                {(user.name || user.email || '?')[0].toUpperCase()}
              </span>
              <span className="user-name">{user.name || user.email}</span>
            </motion.span>
          )}
          {showHome && (
            <motion.button
              className="btn btn-secondary btn-sm"
              onClick={onHomeClick}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              style={{ marginRight: '8px' }}
            >
              <Home size={16} style={{ marginRight: '4px' }} />
              Home
            </motion.button>
          )}
          <motion.button
            className="btn btn-danger btn-sm header-logout"
            onClick={logout}
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            id="logout-button"
          >
            <LogOut size={16} />
            Logout
          </motion.button>
        </div>
      </div>
    </motion.header>
  );
}