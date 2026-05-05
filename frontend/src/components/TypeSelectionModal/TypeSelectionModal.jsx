/**
 * TypeSelectionModal — Choose between LinkedIn / Signature Generator
 * with animated glassmorphism panel.
 */
import { motion, AnimatePresence } from 'framer-motion';
import { Mail, X } from 'lucide-react';
import './TypeSelectionModal.css';

// Lucide doesn't have a LinkedIn icon, so we use inline SVG
const LinkedinIcon = ({ size = 24 }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
  </svg>
);


const backdrop = {
  hidden: { opacity: 0 },
  visible: { opacity: 1 },
  exit: { opacity: 0 },
};

const panel = {
  hidden: { opacity: 0, scale: 0.92, y: 30 },
  visible: {
    opacity: 1, scale: 1, y: 0,
    transition: { type: 'spring', stiffness: 300, damping: 25, delay: 0.05 },
  },
  exit: {
    opacity: 0, scale: 0.95, y: -20,
    transition: { duration: 0.2 },
  },
};

export default function TypeSelectionModal({ isOpen, onClose, onSelect }) {
  return (
    <AnimatePresence>
      {isOpen && (
        <motion.div
          className="modal-overlay"
          variants={backdrop}
          initial="hidden"
          animate="visible"
          exit="exit"
          onClick={onClose}
        >
          <motion.div
            className="modal-panel type-modal-panel"
            variants={panel}
            onClick={(e) => e.stopPropagation()}
          >
            <button className="modal-close" onClick={onClose} aria-label="Close">
              <X size={20} />
            </button>

            <div className="type-modal-header">
              <h2>Choose Your Option</h2>
              <p>Select the type of asset you want to generate</p>
            </div>

            <div className="type-selection-grid">
              <motion.button
                className="type-option"
                onClick={() => onSelect('linkedin')}
                whileHover={{ y: -4, boxShadow: '0 12px 32px rgba(0,119,181,0.2)' }}
                whileTap={{ scale: 0.97 }}
                id="type-option-linkedin"
              >
                <div className="type-icon type-icon-linkedin">
                  <LinkedinIcon size={28} />
                </div>
                <span className="type-label">LinkedIn</span>
                <span className="type-hint">Profile picture generator</span>
              </motion.button>

              <motion.button
                className="type-option"
                onClick={() => onSelect('email')}
                whileHover={{ y: -4, boxShadow: '0 12px 32px rgba(64,112,244,0.2)' }}
                whileTap={{ scale: 0.97 }}
                id="type-option-email"
              >
                <div className="type-icon type-icon-email">
                  <Mail size={28} />
                </div>
                <span className="type-label">Signature Generator</span>
                <span className="type-hint">Email signature builder</span>
              </motion.button>
            </div>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
