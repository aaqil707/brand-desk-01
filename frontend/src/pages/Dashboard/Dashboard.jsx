/**
 * Dashboard — Main entity selection page after login.
 * Renders the entity grid, type selection modal, and guidelines modal.
 * LinkedIn profile generation now routes to the React ProfileGenerator
 * instead of the legacy PHP pages.
 **/
import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import Header from '../../components/Header/Header';
import Footer from '../../components/Footer/Footer';
import EntityCard from '../../components/EntityCard/EntityCard';
import TypeSelectionModal from '../../components/TypeSelectionModal/TypeSelectionModal';
import GuidelinesModal from '../../components/GuidelinesModal/GuidelinesModal';
import HelpButton from '../../components/HelpButton/HelpButton';
import AlgorithmicBackground from '../../components/AlgorithmicBackground/AlgorithmicBackground';
import ProfileGenerator from '../ProfileGenerator/ProfileGenerator';
import SignatureGenerator from '../SignatureGenerator/SignatureGenerator';
import './Dashboard.css';

const ENTITIES = [
  {
    id: 'vdart',
    title: 'VDART',
    description:
      'Empowering digital transformation through innovative workforce solutions and technology services. Leading the future of work with global expertise.',
    logoUrl: '/assets/logos/vdart_logo.png',
    signaturePath: 'Pages/signature.php',
  },
  {
    id: 'vdart-digital',
    title: 'VDart Digital',
    description:
      'Driving digital innovation through cutting-edge technology solutions. Transforming businesses with expert consulting and advanced development services.',
    logoUrl: '/assets/logos/vdart_digital_logo.png',
    signaturePath: 'Pages/dimiour.php',
  },
  {
    id: 'trustpeople',
    title: 'TRUSTPEOPLE',
    description:
      'Connecting exceptional talent with opportunities. Building trusted partnerships through personalized staffing solutions and career development.',
    logoUrl: '/assets/logos/trustpeople_logo.png',
    signaturePath: 'Pages/trustpeople.php',
  },
];

export default function Dashboard() {
  const [selectedEntity, setSelectedEntity] = useState(null);
  const [showTypeModal, setShowTypeModal] = useState(false);
  const [showGuidelines, setShowGuidelines] = useState(false);
  const [guidelineType, setGuidelineType] = useState('email');

  // When set, we show the React ProfileGenerator or SignatureGenerator instead of the dashboard
  const [activeGenerator, setActiveGenerator] = useState(() => {
    const saved = sessionStorage.getItem('activeGenerator');
    if (!saved) return null;
    try {
      const { type, entityId } = JSON.parse(saved);
      const entity = ENTITIES.find((e) => e.id === entityId);
      return entity ? { type, entity } : null;
    } catch (e) {
      return null;
    }
  });

  useEffect(() => {
    if (activeGenerator) {
      sessionStorage.setItem(
        'activeGenerator',
        JSON.stringify({ type: activeGenerator.type, entityId: activeGenerator.entity.id }),
      );
    } else {
      sessionStorage.removeItem('activeGenerator');
    }
  }, [activeGenerator]);

  const handleEntityClick = (entity) => {
    setSelectedEntity(entity);
    setShowTypeModal(true);
  };

  const handleTypeSelect = (type) => {
    setShowTypeModal(false);
    setGuidelineType(type);
    setShowGuidelines(true);
  };

  const handleAcceptGuidelines = () => {
    setShowGuidelines(false);
    if (!selectedEntity) return;

    setActiveGenerator({ type: guidelineType, entity: selectedEntity });
  };

  // ── Render Generator views ──
  if (activeGenerator) {
    if (activeGenerator.type === 'linkedin') {
      return (
        <ProfileGenerator
          entity={activeGenerator.entity}
          onBack={() => setActiveGenerator(null)}
        />
      );
    } else if (activeGenerator.type === 'email') {
      return (
        <SignatureGenerator
          entity={activeGenerator.entity}
          onBack={() => setActiveGenerator(null)}
        />
      );
    }
  }

  // ── Render Dashboard view ──
  return (
    <div className="dashboard">
      <AlgorithmicBackground />
      <Header />

      <main className="dashboard-main">
        {/* Section header with parallax-like stagger */}
        <motion.div
          className="dashboard-hero"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.2 }}
        >
          <h2 className="dashboard-heading">
            Choose Your <span className="text-gradient">Entity</span>
          </h2>
          <p className="dashboard-subheading">
            Select your organization to generate assets
          </p>
        </motion.div>

        {/* Entity grid */}
        <div className="entity-grid">
          {ENTITIES.map((entity, index) => (
            <EntityCard
              key={entity.id}
              title={entity.title}
              description={entity.description}
              logoUrl={entity.logoUrl}
              onClick={() => handleEntityClick(entity)}
              index={index}
            />
          ))}
        </div>
      </main>

      <Footer />
      <HelpButton />

      {/* Modals */}
      <TypeSelectionModal
        isOpen={showTypeModal}
        onClose={() => setShowTypeModal(false)}
        onSelect={handleTypeSelect}
      />

      <GuidelinesModal
        isOpen={showGuidelines}
        onClose={() => setShowGuidelines(false)}
        onAccept={handleAcceptGuidelines}
        type={guidelineType}
      />
    </div>
  );
}
