/**
 * ProfileGenerator — Full React replacement for picturegenerator.php
 *
 * Flow:
 *  1. User uploads a portrait photo (or enters employee ID)
 *  2. PHP backend processes the image (crop, composite with shapes/banner)
 *  3. React renders LinkedIn-style preview with downloadable assets
 *  4. User selects headline, about, experience text and copies to clipboard
 */
import { useState, useRef, useCallback, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { generateProfile } from '../../api/profileApi';
import { ENTITY_CONTENT } from '../../data/entityContent';
import CopyButton from '../../components/CopyButton/CopyButton';
import Header from '../../components/Header/Header';
import Footer from '../../components/Footer/Footer';
import './ProfileGenerator.css';

// ── Instruction Steps Data ──
// Image paths preserved from legacy PHP — images will be uploaded manually to these paths
const INSTRUCTION_STEPS = [
  {
    step: 1,
    title: 'Upload Your Photo',
    description: 'Choose a professional headshot photo. You can either upload directly or use your Employee ID to fetch your photo from the database.',
    icon: 'upload',
    // Legacy image path preserved for future manual upload:
    // images/LinkedIn_step1.png
    imagePath: 'images/LinkedIn_step1.png',
  },
  {
    step: 2,
    title: 'Generate Profile Assets',
    description: 'Click "Generate Profile" to create your branded profile picture with company overlay and a matching LinkedIn banner image.',
    icon: 'generate',
    // Legacy image path preserved for future manual upload:
    // images/LinkedIn_step2.png
    imagePath: 'images/LinkedIn_step2.png',
  },
  {
    step: 3,
    title: 'Download Your Images',
    description: 'Download your generated profile picture and banner. Use "Save All Images" to download both at once, or save them individually.',
    icon: 'download',
    // Legacy image path preserved for future manual upload:
    // images/LinkedIn_step3.png
    imagePath: 'images/LinkedIn_step3.png',
  },
  {
    step: 4,
    title: 'Update Profile Picture',
    description: 'Go to your LinkedIn profile → Click the camera icon on your profile photo → Upload the generated profile picture → Adjust the crop if needed → Click "Apply".',
    icon: 'profile',
    // Legacy image path preserved for future manual upload:
    // images/LinkedIn_step4.png
    imagePath: 'images/LinkedIn_step4.png',
  },
  {
    step: 5,
    title: 'Update Banner Image',
    description: 'On your LinkedIn profile → Click the camera icon on the banner area → Select "Upload photo" → Choose your generated banner → Position and save.',
    icon: 'banner',
    // Legacy image path preserved for future manual upload:
    // images/LinkedIn_step5.png
    imagePath: 'images/LinkedIn_step5.png',
  },
  {
    step: 6,
    title: 'Update Profile Content',
    description: 'Select your Headline, About section, and Experience description from the templates below. Click the copy button next to each section and paste it into your LinkedIn profile.',
    icon: 'content',
    // Legacy image path preserved for future manual upload:
    // images/LinkedIn_step6.png
    imagePath: 'images/LinkedIn_step6.png',
  },
];

// Step icon components
const StepIcons = {
  upload: (
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
      <polyline points="17 8 12 3 7 8" />
      <line x1="12" y1="3" x2="12" y2="15" />
    </svg>
  ),
  generate: (
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
    </svg>
  ),
  download: (
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
      <polyline points="7 10 12 15 17 10" />
      <line x1="12" y1="15" x2="12" y2="3" />
    </svg>
  ),
  profile: (
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
      <circle cx="12" cy="7" r="4" />
    </svg>
  ),
  banner: (
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
      <line x1="8" y1="21" x2="16" y2="21" />
      <line x1="12" y1="17" x2="12" y2="21" />
    </svg>
  ),
  content: (
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
      <polyline points="14 2 14 8 20 8" />
      <line x1="16" y1="13" x2="8" y2="13" />
      <line x1="16" y1="17" x2="8" y2="17" />
      <polyline points="10 9 9 9 8 9" />
    </svg>
  ),
};

export default function ProfileGenerator({ entity, onBack }) {
  const content = ENTITY_CONTENT[entity.id] || ENTITY_CONTENT.vdart;

  // Upload state
  const [uploadMethod, setUploadMethod] = useState(() => {
    return sessionStorage.getItem('profGenMethod') || 'manual';
  });
  const [employeeId, setEmployeeId] = useState(() => {
    return sessionStorage.getItem('profGenEmpId') || '';
  });
  const [selectedFile, setSelectedFile] = useState(null);
  const [preview, setPreview] = useState(null);
  const [dragActive, setDragActive] = useState(false);
  const fileInputRef = useRef(null);
  const instructionsRef = useRef(null);

  // Generation state
  const [isGenerating, setIsGenerating] = useState(false);
  const [error, setError] = useState('');
  const [result, setResult] = useState(() => {
    const saved = sessionStorage.getItem('profGenResult');
    return saved ? JSON.parse(saved) : null;
  });

  // Content selection state
  const [selectedHeadline, setSelectedHeadline] = useState(() => {
    return sessionStorage.getItem('profGenHeadline') || '';
  });
  const [selectedAbout, setSelectedAbout] = useState(() => {
    return sessionStorage.getItem('profGenAbout') || '';
  });
  const [selectedExperience, setSelectedExperience] = useState(() => {
    return sessionStorage.getItem('profGenExp') || '';
  });

  useEffect(() => {
    sessionStorage.setItem('profGenMethod', uploadMethod);
  }, [uploadMethod]);

  useEffect(() => {
    sessionStorage.setItem('profGenEmpId', employeeId);
  }, [employeeId]);

  useEffect(() => {
    if (result) {
      sessionStorage.setItem('profGenResult', JSON.stringify(result));
    } else {
      sessionStorage.removeItem('profGenResult');
    }
  }, [result]);

  useEffect(() => {
    sessionStorage.setItem('profGenHeadline', selectedHeadline);
  }, [selectedHeadline]);

  useEffect(() => {
    sessionStorage.setItem('profGenAbout', selectedAbout);
  }, [selectedAbout]);

  useEffect(() => {
    sessionStorage.setItem('profGenExp', selectedExperience);
  }, [selectedExperience]);

  // Instruction panel state
  const [showInstructions, setShowInstructions] = useState(false);

  // ── File Handling ──

  const handleFileSelect = useCallback((file) => {
    if (!file) return;
    const validTypes = ['image/png', 'image/jpeg', 'image/gif'];
    if (!validTypes.includes(file.type)) {
      setError('Please upload a valid image (PNG, JPEG, or GIF)');
      return;
    }
    if (file.size > 10 * 1024 * 1024) {
      setError('File size must be under 10MB');
      return;
    }
    setError('');
    setSelectedFile(file);
    const reader = new FileReader();
    reader.onload = (e) => setPreview(e.target.result);
    reader.readAsDataURL(file);
  }, []);

  const handleDrop = useCallback((e) => {
    e.preventDefault();
    setDragActive(false);
    if (e.dataTransfer.files?.[0]) {
      handleFileSelect(e.dataTransfer.files[0]);
    }
  }, [handleFileSelect]);

  const handleDragOver = useCallback((e) => {
    e.preventDefault();
    setDragActive(true);
  }, []);

  const handleDragLeave = useCallback(() => setDragActive(false), []);

  // ── Generate ──

  const handleGenerate = async () => {
    setError('');

    if (uploadMethod === 'employee' && !employeeId.trim()) {
      setError('Please enter an Employee ID');
      return;
    }
    if (uploadMethod === 'manual' && !selectedFile) {
      setError('Please select a photo to upload');
      return;
    }

    setIsGenerating(true);

    try {
      const data = await generateProfile(
        selectedFile,
        entity.id,
        uploadMethod,
        employeeId,
      );

      if (data.success) {
        setResult(data);
      } else {
        setError(data.message || 'Generation failed');
      }
    } catch (err) {
      setError(err.message || 'Network error. Please try again.');
    } finally {
      setIsGenerating(false);
    }
  };

  const handleReset = () => {
    setSelectedFile(null);
    setPreview(null);
    setResult(null);
    setError('');
    setSelectedHeadline('');
    setSelectedAbout('');
    setSelectedExperience('');
    setEmployeeId('');
    sessionStorage.removeItem('profGenMethod');
    sessionStorage.removeItem('profGenEmpId');
    sessionStorage.removeItem('profGenResult');
    sessionStorage.removeItem('profGenHeadline');
    sessionStorage.removeItem('profGenAbout');
    sessionStorage.removeItem('profGenExp');
  };

  // ── Render ──

  return (
    <div className="profile-gen">
      <Header showHome={true} onHomeClick={onBack} />

      <main className="profile-gen__main">
        {/* Hero */}
        <motion.div
          className="profile-gen__hero"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
        >
          <button className="profile-gen__back" onClick={onBack} type="button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <line x1="19" y1="12" x2="5" y2="12" />
              <polyline points="12 19 5 12 12 5" />
            </svg>
            Back to Dashboard
          </button>

          <div className="profile-gen__hero-content">
            <img src={entity.logoUrl} alt={entity.title} className="profile-gen__entity-logo" />
            <div>
              <h2 className="profile-gen__title">
                LinkedIn Profile <span className="text-gradient">Generator</span>
              </h2>
              <p className="profile-gen__subtitle">
                Generate your branded profile picture, banner & LinkedIn content for <strong>{entity.title}</strong>
              </p>
            </div>
          </div>
        </motion.div>

        {/* ── Instruction Content Block ── */}
         <motion.div
           ref={instructionsRef}
           className="profile-gen__instructions card"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.15 }}
        >
          <button
            className="profile-gen__instructions-toggle"
            onClick={() => setShowInstructions(!showInstructions)}
            type="button"
            aria-expanded={showInstructions}
          >
            <div className="profile-gen__instructions-toggle-left">
              <div className="profile-gen__instructions-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <circle cx="12" cy="12" r="10" />
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                  <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
              </div>
              <div>
                <h3 className="profile-gen__instructions-title">How to Update Your LinkedIn Profile</h3>
                <p className="profile-gen__instructions-desc">
                  Follow these 6 simple steps to create and update your professional LinkedIn presence
                </p>
              </div>
            </div>
            <svg
              className={`profile-gen__instructions-chevron ${showInstructions ? 'open' : ''}`}
              width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
            >
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </button>

          <AnimatePresence>
            {showInstructions && (
              <motion.div
                className="profile-gen__steps-grid"
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                exit={{ opacity: 0, height: 0 }}
                transition={{ duration: 0.3 }}
              >
                {INSTRUCTION_STEPS.map((step, index) => (
                  <motion.div
                    key={step.step}
                    className="profile-gen__step-card"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.3, delay: index * 0.08 }}
                  >
                    <div className="profile-gen__step-number">{step.step}</div>
                    <div className="profile-gen__step-icon-wrap">
                      {StepIcons[step.icon]}
                    </div>
                    <h4 className="profile-gen__step-title">{step.title}</h4>
                    <p className="profile-gen__step-desc">{step.description}</p>
                    {/* Image placeholder — images will be uploaded manually to: {step.imagePath} */}
                  </motion.div>
                ))}
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>

        <div className="profile-gen__top-row">
          {/* ── Left: Upload Form ── */}
          <motion.div
            className="profile-gen__form card"
            initial={{ opacity: 0, x: -30 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
          >
            <h3 className="profile-gen__section-title">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="17 8 12 3 7 8" />
                <line x1="12" y1="3" x2="12" y2="15" />
              </svg>
              Upload Your Photo
            </h3>

            {/* Upload Method Toggle */}
            <div className="profile-gen__method-toggle">
              <button
                className={`profile-gen__method-btn ${uploadMethod === 'manual' ? 'active' : ''}`}
                onClick={() => setUploadMethod('manual')}
                type="button"
              >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                  <circle cx="8.5" cy="8.5" r="1.5" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
                Upload Photo
              </button>
              <button
                className={`profile-gen__method-btn ${uploadMethod === 'employee' ? 'active' : ''}`}
                onClick={() => setUploadMethod('employee')}
                type="button"
              >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                  <circle cx="8.5" cy="7" r="4" />
                  <line x1="20" y1="8" x2="20" y2="14" />
                  <line x1="23" y1="11" x2="17" y2="11" />
                </svg>
                Employee ID
              </button>
            </div>

            {uploadMethod === 'manual' ? (
              <>
                {/* Drop Zone */}
                <div
                  className={`profile-gen__dropzone ${dragActive ? 'active' : ''} ${preview ? 'has-preview' : ''}`}
                  onDrop={handleDrop}
                  onDragOver={handleDragOver}
                  onDragLeave={handleDragLeave}
                  onClick={() => fileInputRef.current?.click()}
                  role="button"
                  tabIndex={0}
                >
                  <input
                    ref={fileInputRef}
                    type="file"
                    accept="image/png,image/jpeg,image/gif"
                    onChange={(e) => handleFileSelect(e.target.files?.[0])}
                    hidden
                  />

                  {preview ? (
                    <div className="profile-gen__preview-wrap">
                      <img src={preview} alt="Preview" className="profile-gen__preview-img" />
                      <div className="profile-gen__preview-overlay">
                        <span>Click to change photo</span>
                      </div>
                    </div>
                  ) : (
                    <div className="profile-gen__dropzone-content">
                      <div className="profile-gen__dropzone-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                          <polyline points="17 8 12 3 7 8" />
                          <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                      </div>
                      <p className="profile-gen__dropzone-text">
                        <strong>Drag & drop</strong> your photo here
                      </p>
                      <p className="profile-gen__dropzone-hint">or click to browse • PNG, JPEG, GIF up to 10MB</p>
                    </div>
                  )}
                </div>

                {selectedFile && (
                  <div className="profile-gen__file-info">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-success)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                      <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <span>{selectedFile.name}</span>
                    <span className="profile-gen__file-size">
                      {(selectedFile.size / 1024).toFixed(0)} KB
                    </span>
                  </div>
                )}
              </>
            ) : (
              <div className="profile-gen__employee-input">
                <label htmlFor="employeeId">Employee ID</label>
                <input
                  id="employeeId"
                  type="text"
                  className="input-field"
                  placeholder="Enter your Employee ID..."
                  value={employeeId}
                  onChange={(e) => setEmployeeId(e.target.value)}
                />
                <p className="profile-gen__employee-hint">
                  Your photo will be retrieved from the employee database
                </p>
              </div>
            )}

            {/* Error */}
            <AnimatePresence>
              {error && (
                <motion.div
                  className="profile-gen__error"
                  initial={{ opacity: 0, height: 0 }}
                  animate={{ opacity: 1, height: 'auto' }}
                  exit={{ opacity: 0, height: 0 }}
                >
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                  </svg>
                  {error}
                </motion.div>
              )}
            </AnimatePresence>

            {/* Actions */}
            <div className="profile-gen__actions">
              <button
                className="btn btn-primary btn-lg profile-gen__generate-btn"
                onClick={handleGenerate}
                disabled={isGenerating}
              >
                {isGenerating ? (
                  <>
                    <div className="spinner" style={{ width: 20, height: 20 }} />
                    Generating...
                  </>
                ) : (
                  <>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                    </svg>
                    Generate Profile
                  </>
                )}
              </button>

              {result && (
                <>
                  <button 
                    className="btn btn-primary" 
                    onClick={() => {
                      const link1 = document.createElement('a');
                      link1.href = result.profile;
                      link1.download = 'profile_picture.png';
                      document.body.appendChild(link1);
                      link1.click();
                      document.body.removeChild(link1);

                      setTimeout(() => {
                        const link2 = document.createElement('a');
                        link2.href = result.banner;
                        link2.download = 'linkedin_banner.png';
                        document.body.appendChild(link2);
                        link2.click();
                        document.body.removeChild(link2);
                      }, 500);
                    }} 
                    type="button"
                    style={{ marginLeft: '10px' }}
                  >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ marginRight: '8px', verticalAlign: 'middle' }}>
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                      <polyline points="7 10 12 15 17 10" />
                      <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Save All Images
                  </button>
                  <button className="btn btn-secondary" onClick={handleReset} type="button" style={{ marginLeft: '10px' }}>
                    Start Over
                  </button>
                </>
              )}
            </div>
          </motion.div>

          {/* ── Right: Features Section ── */}
          <motion.div
            className="features-section"
            initial={{ opacity: 0, x: 30 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
          >
            {/* Upload Card */}
            <div className="feature-card card">
              <div className="card-content">
              
                <i className="fas fa-upload feature-icon">
                   <svg className="rr" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="17 8 12 3 7 8" />
                <line x1="12" y1="3" x2="12" y2="15" />
              </svg>
                </i>
                  
                <div className="feature-title">Easy Upload</div>
                <div className="feature-description">
                  <ul className="feature-list">
                    <li><i className="fas fa-check"></i> Drag and drop functionality</li>
                    <li><i className="fas fa-check"></i> Supports JPG, PNG, GIF formats</li>
                    <li><i className="fas fa-check"></i> Quick upload process</li>
                    <li><i className="fas fa-check"></i> Auto-size optimization</li>
                  </ul>
                </div>
              </div>
            </div>

            {/* Not Recommended Card */}
            <div className="feature-card card">
              <div className="card-content">
                <div className="status-badge not-recommended">
                  <i className="fas fa-times-circle"></i>
                  Not Recommended
                </div>
                <div className="image-container">
                  <img src="/img/Business.jpg" alt="Not Recommended" className="feature-image" />
                </div>
                <div className="feature-title">Avoid This Format</div>
                <div className="feature-description">Poor quality image with distracting background.</div>
              </div>
            </div>

            {/* Recommended Card */}
            <div className="feature-card card">
              <div className="card-content">
                <div className="status-badge recommended">
                  <i className="fas fa-check-circle"></i>
                  Professional Standard
                </div>
                <div className="image-container">
                  <img src="/img/Business1.jpg" alt="Recommended" className="feature-image" />
                </div>
                <div className="feature-title">Perfect Format</div>
                <div className="feature-description">High resolution with clean background.</div>
              </div>
            </div>

            {/* Background Removal Card */}
            <div className="feature-card card">
              <div className="card-content">
                <i className="fas fa-magic feature-icon">
                  <svg className="rr" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M12 3v18" />
                    <path d="M16.5 4.5L7.5 19.5" />
                    <path d="M19.5 6.5L4.5 17.5" />
                  </svg>
                </i>
                <div className="feature-title">Remove Background</div>
                <div className="feature-description">
                  <ul className="feature-list">
                    <li><i className="fas fa-check"></i> Professional appearance</li>
                    <li><i className="fas fa-check"></i> Clean, distraction-free look</li>
                    <li><i className="fas fa-check"></i> Enhanced focus on subject</li>
                    <li><i className="fas fa-check"></i> Perfect for branding</li>
                  </ul>
                </div>
                <a href="https://www.remove.bg/" target="_blank" rel="noreferrer" className="bg-remove-link">
                  Try remove.bg <i className="fas fa-external-link-alt"></i>
                </a>
              </div>
            </div>
          </motion.div>
        </div>

        {/* ── Bottom: Preview ── */}
        <motion.div
          className="profile-gen__preview card"
          initial={{ opacity: 0, y: 24 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.2 }}
        >
          <h3 className="profile-gen__section-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
              <line x1="8" y1="21" x2="16" y2="21" />
              <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
            LinkedIn Preview
          </h3>

          <div className="linkedin-preview">
            {/* Banner */}
            <div className="linkedin-banner">
              {result?.banner && <img src={result.banner} alt="LinkedIn Banner" />}
              {result?.banner && (
                <a
                  href={result.banner}
                  download
                  className="linkedin-download-btn banner-dl"
                >
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                  </svg>
                  Save Banner
                </a>
              )}

              {/* Profile Photo */}
              <div className="linkedin-profile-photo">
                {result?.profile ? (
                  <img src={result.profile} alt="Generated Profile" />
                ) : (
                  <div style={{ width: '100%', height: '100%', backgroundColor: '#e5e7eb', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                      <circle cx="12" cy="7" r="4" />
                    </svg>
                  </div>
                )}
                {result?.profile && (
                  <a
                    href={result.profile}
                    download
                    className="linkedin-download-btn photo-dl"
                  >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                      <polyline points="7 10 12 15 17 10" />
                      <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                  </a>
                )}
              </div>
            </div>

            {/* Content */}
            <div className="linkedin-content">
              <div className="linkedin-name">John Doe</div>

                {/* Headline */}
                <LinkedInSection
                  title="Headline"
                  icon={
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                      <line x1="7" y1="7" x2="7.01" y2="7" />
                    </svg>
                  }
                  selectedValue={selectedHeadline}
                  placeholder="Select your professional headline"
                  groups={content.headlines}
                  onChange={setSelectedHeadline}
                />

                {/* Location */}
                <div className="linkedin-location">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                    <circle cx="12" cy="10" r="3" />
                  </svg>
                  <span>{content.location}</span>
                  <CopyButton text={content.location} />
                </div>

                {/* LinkedIn Action Buttons (decorative) */}
                <div className="linkedin-action-btns">
                  <span className="linkedin-action-btn primary">Open to</span>
                  <span className="linkedin-action-btn secondary">Add profile section</span>
                  <span className="linkedin-action-btn secondary">More</span>
                </div>

                {/* About */}
                <LinkedInSection
                  title="About"
                  icon={
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <circle cx="12" cy="12" r="10" />
                      <line x1="12" y1="16" x2="12" y2="12" />
                      <line x1="12" y1="8" x2="12.01" y2="8" />
                    </svg>
                  }
                  selectedValue={selectedAbout}
                  placeholder="Select your professional summary"
                  groups={content.aboutSections}
                  onChange={setSelectedAbout}
                  helpSteps={[
                    'Choose a template matching your role',
                    'Add specific achievements and metrics',
                    'Keep it concise (3-5 sentences)',
                    'Include relevant keywords',
                    'Highlight your expertise areas',
                  ]}
                />

                {/* Experience */}
                <div className="linkedin-experience">
                  <div className="linkedin-section-header">
                    <h4>
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                      </svg>
                      Experience
                    </h4>
                  </div>

                  <div className="linkedin-experience-card">
                    <img
                      src={content.experience.logoUrl}
                      alt={content.experience.companyName}
                      className="linkedin-company-logo"
                    />
                    <div className="linkedin-experience-info">
                      <h5>Job Title</h5>
                      <div className="linkedin-company-name">{content.experience.companyFullName}</div>
                      <div className="linkedin-exp-meta">Oct 2024 – Present · 5 mos</div>
                      <div className="linkedin-exp-meta">Tiruchirappalli, Tamil Nadu, India · On-site</div>
                    </div>
                  </div>

                  <select
                    className="linkedin-dropdown"
                    value={selectedExperience}
                    onChange={(e) => setSelectedExperience(e.target.value)}
                  >
                    <option value="" disabled>Select your role description</option>
                    <option value={content.experience.description}>Standard Description</option>
                  </select>

                  {selectedExperience && (
                    <div className="linkedin-display-box">
                      <p style={{ whiteSpace: 'pre-line' }}>{selectedExperience}</p>
                      <CopyButton text={selectedExperience} />
                    </div>
                  )}

                  <div className="linkedin-skills-notice">
                    <span>⚠️</span>
                    <span>Please add a brief description of your role under the LinkedIn Experience section and copy-paste the content above.</span>
                  </div>
                </div>
              </div>
            </div>
        </motion.div>
      </main>

      {/* ── CTA Banner — LinkedIn Update Prompt ── */}
      <div className="profile-gen__cta-banner">
        <div className="profile-gen__cta-inner">
          <div className="profile-gen__cta-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
              <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z" />
            </svg>
          </div>
          <div className="profile-gen__cta-text">
            <span className="profile-gen__cta-question">Still haven't updated your LinkedIn profile picture?</span>
            <span className="profile-gen__cta-hint">Use the tools above to generate your branded profile assets and follow the step-by-step guide</span>
          </div>
           <button
             className="profile-gen__cta-action"
             onClick={() => {
               setShowInstructions(true);
               setTimeout(() => {
                 instructionsRef.current?.scrollIntoView({ 
                   behavior: 'smooth', 
                   block: 'start' 
                 });
               }, 100);
             }}
             type="button"
           >
            View Instructions
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <line x1="5" y1="12" x2="19" y2="12" />
              <polyline points="12 5 19 12 12 19" />
            </svg>
          </button>
        </div>
      </div>

      <Footer />
    </div>
  );
}

// ── Sub-component: LinkedIn Section with dropdown + copy ──

function LinkedInSection({ title, icon, selectedValue, placeholder, groups, onChange, helpSteps }) {
  const [showHelp, setShowHelp] = useState(false);

  return (
    <div className="linkedin-section">
      <div className="linkedin-section-header">
        <h4>{icon} {title}</h4>
        {helpSteps && (
          <button
            className="linkedin-help-btn"
            onClick={() => setShowHelp(!showHelp)}
            type="button"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <circle cx="12" cy="12" r="10" />
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
              <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
          </button>
        )}
      </div>

      <AnimatePresence>
        {showHelp && helpSteps && (
          <motion.div
            className="linkedin-help-panel"
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
          >
            <h5>How to Write a Great {title}</h5>
            {helpSteps.map((step, i) => (
              <div key={i} className="linkedin-help-step">
                <span className="linkedin-help-num">{i + 1}</span>
                {step}
              </div>
            ))}
          </motion.div>
        )}
      </AnimatePresence>

      <select
        className="linkedin-dropdown"
        value={selectedValue}
        onChange={(e) => onChange(e.target.value)}
      >
        <option value="" disabled>{placeholder}</option>
        {Object.entries(groups).map(([groupLabel, items]) => (
          <optgroup key={groupLabel} label={groupLabel}>
            {(Array.isArray(items) ? items : [items]).map((item, idx) => {
              const val = typeof item === 'string' ? item : item.value;
              const label = typeof item === 'string'
                ? item.substring(0, 80) + (item.length > 80 ? '...' : '')
                : item.label;
              return (
                <option key={idx} value={val}>{label}</option>
              );
            })}
          </optgroup>
        ))}
      </select>

      {selectedValue && (
        <div className="linkedin-display-box">
          <p style={{ whiteSpace: 'pre-line' }}>{selectedValue}</p>
          <CopyButton text={selectedValue} />
        </div>
      )}
    </div>
  );
}