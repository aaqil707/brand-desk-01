/**
 * EntityCard — 3D floating card with glassmorphism, hover depth,
 * and micro-animations for the entity selector grid.
 */
import { motion } from 'framer-motion';
import { ArrowRight } from 'lucide-react';
import './EntityCard.css';

export default function EntityCard({ title, description, logoUrl, onClick, index }) {
  return (
    <motion.div
      className="entity-card card"
      initial={{ opacity: 0, y: 40, rotateX: 10 }}
      animate={{ opacity: 1, y: 0, rotateX: 0 }}
      transition={{
        duration: 0.6,
        delay: index * 0.12,
        ease: [0.22, 1, 0.36, 1],
      }}
      whileHover={{
        y: -12,
        rotateX: -2,
        rotateY: 2,
        transition: { duration: 0.3 },
      }}
      style={{ perspective: 1000 }}
    >
      {/* Shine effect overlay */}
      <div className="card-shine" aria-hidden="true" />

      {/* Floating glow ring */}
      <div className="card-glow-ring" aria-hidden="true" />

      <div className="card-content">
        <motion.div
          className="card-logo-wrapper"
          whileHover={{ scale: 1.05, rotate: -1 }}
          transition={{ type: 'spring', stiffness: 300, damping: 15 }}
        >
          <img
            src={logoUrl}
            alt={`${title} logo`}
            className="card-logo"
            loading="lazy"
          />
        </motion.div>

        <h2 className="card-title">{title}</h2>
        <p className="card-description">{description}</p>

        <motion.button
          className="btn btn-primary card-action"
          onClick={onClick}
          whileHover={{ scale: 1.04, gap: '12px' }}
          whileTap={{ scale: 0.97 }}
          id={`select-entity-${title.toLowerCase().replace(/\s+/g, '-')}`}
        >
          Select Entity
          <ArrowRight size={16} />
        </motion.button>
      </div>
    </motion.div>
  );
}
