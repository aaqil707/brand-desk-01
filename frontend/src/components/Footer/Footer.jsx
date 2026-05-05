/**
 * Footer — Minimal gradient footer.
 */
import { motion } from 'framer-motion';
import './Footer.css';

export default function Footer() {
  return (
    <motion.footer
      className="app-footer"
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      transition={{ delay: 0.5, duration: 0.5 }}
    >
      <div className="footer-inner">
        <p>&copy; {new Date().getFullYear()} VDart. All rights reserved.</p>
      </div>
    </motion.footer>
  );
}
