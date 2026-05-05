import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import vdartLogo from '../../assets/vdart_logo.png';
import { 
  Mail, Lock, User, Eye, EyeOff, LogIn, UserPlus, 
  Users, Briefcase, Award, MonitorSmartphone, Wrench, Image as ImageIcon 
} from 'lucide-react';
import { useAuthStore, useUIStore } from '../../store';
import './LoginPage.css';

// Carousel Data mapping to your request
const slideData = [
  {
    id: 0,
    title: "Entity",
    items: [
      { text: "VDart", isLogo: true, logoUrl: "/assets/logos/vdart_logo.png" },
      { text: "VDart Digital", isLogo: true, logoUrl: "/assets/logos/vdart_digital_logo.png" },
      { text: "Trustpeople", isLogo: true, logoUrl: "/assets/logos/trustpeople_logo.png" },
    ]
  },
  {
    id: 1,
    title: "Profile Generator",
    items: [
      { text: "Employee Onboarding and Brand Alignment", icon: <Users size={24} /> },
      { text: "Corporate Identity Standardization", icon: <Briefcase size={24} /> },
      { text: "Professional Profile Optimization for Employees", icon: <Award size={24} /> },
    ]
  },
  {
    id: 2,
    title: "Email Signature",
    items: [
      { text: "Automated Email Branding", icon: <Mail size={24} /> },
      { text: "Multi-Platform Compatibility (Outlook & CEIPAL)", icon: <MonitorSmartphone size={24} /> },
      { text: "Guided Technical Setup for Non-Technical Staff", icon: <Wrench size={24} /> },
    ]
  }
];

export default function LoginPage() {
  // Auth State
  const [activeTab, setActiveTab] = useState('signin');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [form, setForm] = useState({ name: '', email: '', password: '' });

  // Carousel State
  const [currentSlide, setCurrentSlide] = useState(0);

  const { login, register } = useAuthStore();
  const { addToast } = useUIStore();
  const isSignUp = activeTab === 'signup';

  // Auto-swipe functionality
  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev === slideData.length - 1 ? 0 : prev + 1));
    }, 5000); // Swipes every 5 seconds
    return () => clearInterval(timer);
  }, []);

  const handleChange = (e) => setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  
  const handleTabSwitch = (tab) => {
    setActiveTab(tab);
    setForm({ name: '', email: '', password: '' });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    try {
      if (isSignUp) {
        if (!form.name.trim()) return addToast({ type: 'error', message: 'Please enter your name' });
        const result = await register(form.name, form.email, form.password);
        if (result?.success || result?.status === 'success') {
          addToast({ type: 'success', message: 'Account created! Please sign in.' });
          setActiveTab('signin');
          setForm((prev) => ({ ...prev, password: '' }));
        } else {
          addToast({ type: 'error', message: result?.message || 'Registration failed' });
        }
      } else {
        const result = await login(form.email, form.password);
        if (result.success) addToast({ type: 'success', message: 'Welcome back!' });
        else addToast({ type: 'error', message: result.message || 'Invalid credentials' });
      }
    } catch (err) {
      addToast({ type: 'error', message: err.message || 'Something went wrong' });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="login-page">
      
        {/* Left Panel: Auto-swiping Glassmorphism Content */}
        <div className="login-left-panel">
          <div className="top-header-row">
            <div className="header-glass-wrapper">
               <div className="header-left">
                 <img src={vdartLogo} alt="VDart Logo" className="header-logo" />
               </div>
            </div>
          </div>

          <div className="carousel-container">
           <div className="slide-content">
             <AnimatePresence mode="wait">
               <motion.div
                 key={currentSlide}
                 initial={{ opacity: 0, x: 20 }}
                 animate={{ opacity: 1, x: 0 }}
                 exit={{ opacity: 0, x: -20 }}
                 transition={{ duration: 0.4 }}
               >
                 <h1 className="slide-title">{slideData[currentSlide].title}</h1>
                 
                 <div className="feature-cards">
                   {slideData[currentSlide].items.map((item, index) => (
                     <motion.div 
                       key={index} 
                       className="feature-card"
                       initial={{ opacity: 0, y: 10 }}
                       animate={{ opacity: 1, y: 0 }}
                       transition={{ delay: index * 0.1 + 0.2 }}
                     >
                       {item.isLogo ? (
                         <div className="logo-box">
                           <img 
                             src={item.logoUrl} 
                             alt={item.text} 
                             style={{ width: '100%', height: '100%', objectFit: 'contain' }} 
                             loading="lazy" 
                             decoding="async"
                           />
                         </div>
                       ) : (
                         <div className="icon-box">
                           {item.icon}
                         </div>
                       )}
                       <span className="card-text">{item.text}</span>
                     </motion.div>
                   ))}
                 </div>
               </motion.div>
             </AnimatePresence>
           </div>
         
           <div className="carousel-dots">
             {slideData.map((_, idx) => (
               <button
                 key={idx}
                 className={`dot ${currentSlide === idx ? 'active' : ''}`}
                 onClick={() => setCurrentSlide(idx)}
                 aria-label={`Go to slide ${idx + 1}`}
               />
             ))}
           </div>
         </div>
       </div>

      {/* Right Panel: Glassmorphism Authentication Form */}
      <div className="login-right-panel">
        <div className="auth-container">
          <div className="auth-tabs">
            <button 
              className={`tab-btn ${!isSignUp ? 'active' : ''}`}
              onClick={() => handleTabSwitch('signin')}
            >
              Sign In
              {!isSignUp && <motion.div layoutId="tabIndicator" className="tab-indicator" />}
            </button>
            <button 
              className={`tab-btn ${isSignUp ? 'active' : ''}`}
              onClick={() => handleTabSwitch('signup')}
            >
              Sign Up
              {isSignUp && <motion.div layoutId="tabIndicator" className="tab-indicator" />}
            </button>
          </div>

          <form onSubmit={handleSubmit} className="login-form">
            <AnimatePresence>
              {isSignUp && (
                <motion.div
                  className="input-group"
                  initial={{ opacity: 0, height: 0 }}
                  animate={{ opacity: 1, height: 'auto' }}
                  exit={{ opacity: 0, height: 0 }}
                  transition={{ duration: 0.3 }}
                >
                  <label htmlFor="name"><User size={16} /> Full Name</label>
                  <input
                    id="name" name="name" type="text"
                    className="input-field" placeholder="John Doe"
                    value={form.name} onChange={handleChange}
                  />
                </motion.div>
              )}
            </AnimatePresence>

            <div className="input-group">
              <label htmlFor="email"><Mail size={16} /> Email</label>
              <input
                id="email" name="email" type="email"
                className="input-field" placeholder="you@company.com"
                value={form.email} onChange={handleChange} required
              />
            </div>

            <div className="input-group">
              <label htmlFor="password"><Lock size={16} /> Password</label>
              <div className="password-wrapper">
                <input
                  id="password" name="password" type={showPassword ? 'text' : 'password'}
                  className="input-field" placeholder="••••••••"
                  value={form.password} onChange={handleChange} required
                />
                <button
                  type="button" className="password-toggle"
                  onClick={() => setShowPassword(!showPassword)}
                >
                  {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                </button>
              </div>
            </div>

            <motion.button
              type="submit" className="btn btn-primary btn-lg login-submit"
              disabled={isLoading}
              whileHover={{ scale: 1.02 }}
              whileTap={{ scale: 0.98 }}
            >
              {isLoading ? (
                <span className="spinner" style={{ width: 20, height: 20, borderWidth: 2 }} />
              ) : (
                <>
                  {isSignUp ? <UserPlus size={18} /> : <LogIn size={18} />}
                  {isSignUp ? 'Create Account' : 'Sign In'}
                </>
              )}
            </motion.button>
          </form>
        </div>
      </div>
    </div>
  );
}