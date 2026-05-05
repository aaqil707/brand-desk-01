/**
 * GuidelinesModal — Terms/Guidelines acceptance modal
 * with animated sections and smooth transitions.
 */
import { motion, AnimatePresence } from 'framer-motion';
import { X, CheckCircle, ShieldCheck, AlertTriangle, Headphones } from 'lucide-react';

const backdrop = {
  hidden: { opacity: 0 },
  visible: { opacity: 1 },
  exit: { opacity: 0 },
};

const panel = {
  hidden: { opacity: 0, scale: 0.92, y: 30 },
  visible: {
    opacity: 1, scale: 1, y: 0,
    transition: { type: 'spring', stiffness: 280, damping: 24, delay: 0.05 },
  },
  exit: { opacity: 0, scale: 0.95, y: -20, transition: { duration: 0.2 } },
};

const sectionVariant = {
  hidden: { opacity: 0, x: -12 },
  visible: (i) => ({
    opacity: 1, x: 0,
    transition: { delay: 0.1 + i * 0.08, duration: 0.35 },
  }),
};

export default function GuidelinesModal({ isOpen, onClose, onAccept, type = 'email' }) {
  const isLinkedIn = type === 'linkedin';
  const title = isLinkedIn ? 'LinkedIn Picture Guidelines' : 'Email Signature Guidelines';

  const sections = isLinkedIn
    ? [
        { icon: <CheckCircle size={20} />, title: 'Photo Requirements', text: 'Ensure your photo meets professional LinkedIn standards. The photo should be a clear headshot with a neutral background and professional attire.' },
        { icon: <CheckCircle size={20} />, title: 'Image Quality', text: 'Upload a high-quality image (minimum 400×400 pixels). The photo should be well-lit and in focus. Avoid using filters or heavy editing.' },
        { icon: <ShieldCheck size={20} />, title: 'Professional Standards', text: "Your photo should reflect VDart Group's professional image. Maintain appropriate business attire and a professional appearance." },
        { icon: <AlertTriangle size={20} />, title: 'Usage Guidelines', text: 'The generated image is for professional use on LinkedIn and other business platforms associated with VDart Group companies.' },
        { icon: <Headphones size={20} />, title: 'Support', text: 'For assistance with your LinkedIn profile picture, please contact csm@vdartinc.com.' },
      ]
    : [
        { icon: <CheckCircle size={20} />, title: 'Signature Selection', text: "Ensure to select your own entities for the signature. Do not use VDart's signature if working for Dimiour, TrustPeople." },
        { icon: <ShieldCheck size={20} />, title: 'Signature Integrity', text: 'Do not modify the signature in any way. This includes not adding any fields or altering the format and fonts of the signature.' },
        { icon: <AlertTriangle size={20} />, title: 'Compliance and Escalation', text: "Any modifications found in the signature will lead to escalation according to the company's policy." },
        { icon: <Headphones size={20} />, title: 'Assistance', text: 'If you need assistance or have questions about setting up your signature, please reach out to csm@vdartinc.com.' },
      ];

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
            className="modal-panel"
            variants={panel}
            onClick={(e) => e.stopPropagation()}
            style={{ maxWidth: 720 }}
          >
            <div className="modal-header">
              <h2>{title}</h2>
              <button className="modal-close" onClick={onClose} aria-label="Close">
                <X size={20} />
              </button>
            </div>

            <div className="modal-body" style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-5)' }}>
              <p style={{ color: 'var(--color-gray-500)', fontSize: '0.92rem' }}>
                {isLinkedIn
                  ? 'Please review these important guidelines before proceeding.'
                  : 'Welcome to our service. By proceeding, you agree to these guidelines.'}
              </p>

              {sections.map((section, i) => (
                <motion.div
                  key={section.title}
                  className="guideline-section"
                  custom={i}
                  variants={sectionVariant}
                  initial="hidden"
                  animate="visible"
                  style={{
                    display: 'flex',
                    gap: 'var(--space-3)',
                    padding: 'var(--space-4)',
                    background: 'var(--color-gray-100)',
                    borderRadius: 'var(--radius-md)',
                    border: '1px solid var(--color-gray-200)',
                    transition: 'all 0.25s ease',
                  }}
                  whileHover={{
                    borderColor: 'var(--color-primary)',
                    background: 'rgba(64,112,244,0.04)',
                  }}
                >
                  <span style={{ color: 'var(--color-primary)', flexShrink: 0, marginTop: 2 }}>
                    {section.icon}
                  </span>
                  <div>
                    <h4 style={{ fontSize: '0.95rem', fontWeight: 600, marginBottom: 4, color: 'var(--color-dark)' }}>
                      {section.title}
                    </h4>
                    <p style={{ fontSize: '0.87rem', lineHeight: 1.6, color: 'var(--color-gray-500)', margin: 0 }}>
                      {section.text}
                    </p>
                  </div>
                </motion.div>
              ))}
            </div>

            <div className="modal-footer">
              <motion.button
                className="btn btn-primary btn-lg"
                onClick={onAccept}
                whileHover={{ scale: 1.03 }}
                whileTap={{ scale: 0.97 }}
                id="accept-guidelines"
              >
                <CheckCircle size={18} />
                {isLinkedIn ? 'I Understand and Proceed' : 'I Understand and Accept'}
              </motion.button>
            </div>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
