/**
 * HelpButton — Floating help tooltip with contact details.
 */
import { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { HelpCircle, Headphones, Wrench, Info, Mail } from 'lucide-react';
import './HelpButton.css';

export default function HelpButton() {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <div className="help-floating" onMouseEnter={() => setIsOpen(true)} onMouseLeave={() => setIsOpen(false)}>
      <AnimatePresence>
        {isOpen && (
          <motion.div
            className="help-panel glass"
            initial={{ opacity: 0, y: 10, scale: 0.95 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 10, scale: 0.95 }}
            transition={{ type: 'spring', stiffness: 400, damping: 25 }}
          >
            <div className="help-panel-header">
              <Headphones size={20} />
              <h3>Need Help?</h3>
            </div>

            <div className="help-section">
              <h4><Wrench size={16} /> Technical Support</h4>
              <p>For application issues, bugs, or technical difficulties:</p>
              <a href="mailto:saranraj.s@vdartinc.com" className="help-contact">
                <Mail size={14} />
                saranraj.s@vdartinc.com
              </a>
            </div>

            <div className="help-section">
              <h4><Info size={16} /> General Inquiries</h4>
              <p>For profile information, account questions, or general assistance:</p>
              <a href="mailto:lyn.g@vdartinc.com" className="help-contact">
                <Mail size={14} />
                lyn.g@vdartinc.com
              </a>
              <a href="mailto:rukzana.r@vdartinc.com" className="help-contact">
                <Mail size={14} />
                rukzana.r@vdartinc.com
              </a>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      <motion.button
        className="help-fab"
        whileHover={{ scale: 1.08 }}
        whileTap={{ scale: 0.95 }}
        aria-label="Help"
        id="help-button"
      >
        <HelpCircle size={20} />
        <span>Need Help</span>
      </motion.button>
    </div>
  );
}
