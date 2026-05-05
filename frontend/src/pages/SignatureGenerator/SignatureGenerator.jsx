import { useState, useRef, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import Header from '../../components/Header/Header';
import Footer from '../../components/Footer/Footer';
import * as XLSX from 'xlsx';
import { uploadApi, profileApi } from '../../api/client';
import './SignatureGenerator.css';

// ── Instruction Steps Data ──
const OUTLOOK_STEPS = [
  { step: 1, title: 'Fill and Generate Signature', icon: 'form', imagePath: 'images/Outlook_step1.png',
    description: 'Enter your Full Name, Designation, Phone Number (format: 4703238433), Email Address, and LinkedIn Profile URL (optional). Click "Generate Signature".' },
  { step: 2, title: 'Copy Generated Signature', icon: 'copy', imagePath: 'images/Outlook_step2.png',
    description: 'Switch to "Outlook Format" tab in preview section → Review your generated signature → Click "Copy Signature for Outlook" → Wait for the success message.' },
  { step: 3, title: 'Access Outlook Settings', icon: 'settings', imagePath: 'images/Outlook_step3.png',
    description: 'Open Microsoft Outlook → Click "File" in the top menu → Click "Options" → Select "Mail" → Find and click the "Signatures..." button.' },
  { step: 4, title: 'Create New Signature', icon: 'create', imagePath: 'images/Outlook_step4.png',
    description: 'Click the "New" button → Enter a name (e.g., "VDart Signature") → Click "OK" → In the right pane, paste your copied signature (Ctrl+V) → Verify all elements.' },
  { step: 5, title: 'Set Default Signature', icon: 'default', imagePath: 'images/Outlook_step5.png',
    description: 'Under "Choose default signature" → Select your email account → Choose your new signature for "New messages" and optionally "Replies/forwards" → Click "OK".' },
];

const CEIPAL_STEPS = [
  { step: 1, title: 'Fill and Generate Signature', icon: 'form', imagePath: 'images/Ceipal Step1 (1).png',
    description: 'Enter your Full Name, Designation, Phone Number, Email Address, and LinkedIn Profile URL (optional). Click "Generate Signature".' },
  { step: 2, title: 'Copy Generated Signature', icon: 'copy', imagePath: 'images/Ceipal Step2 (1).png',
    description: 'Switch to "CEIPAL Format" tab → Review your generated signature → Click "Copy Signature for CEIPAL" → Wait for the "Signature copied" message.' },
  { step: 3, title: 'Access CEIPAL Settings', icon: 'settings', imagePath: 'images/Ceipal Step3 (1).png',
    description: 'Log into your CEIPAL account → Click on your profile picture/icon → Select "Settings" from the dropdown → Navigate to "Email Settings".' },
  { step: 4, title: 'Paste Your Signature', icon: 'create', imagePath: 'images/Ceipal Step4 (1).png',
    description: 'Locate the "Email Signature" section → Paste your copied signature (Ctrl+V) → Verify all elements appear correctly → Click "Save Changes".' },
  { step: 5, title: 'Verify Signature Display', icon: 'verify', imagePath: 'images/Ceipal Step5 (1).png',
    description: 'Locate the "Email Signature" section → Verify all elements appear correctly → Click "Save Changes" to apply.' },
  { step: 6, title: 'Final Confirmation', icon: 'confirm', imagePath: 'images/Ceipal Step6 (1).png',
    description: 'Confirm your signature is saved → Send a test email to verify appearance → Adjust if needed.' },
];

const SIG_STEP_ICONS = {
  form: (<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>),
  copy: (<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>),
  settings: (<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>),
  create: (<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>),
  default: (<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>),
  verify: (<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>),
  confirm: (<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>),
};

// ── Job Titles (shared across entities, matching PHP files) ──
const JOB_TITLES = [
  'Trainee Recruiter','Technical Sourcer','Executive Technical Sourcer','Technical Recruiter',
  'Senior Recruiter','Senior Recruiter - APAC','Senior Recruiter - Gulf','Lead Recruiter',
  'Associate Team Lead - Sourcing','Team Lead','Delivery Manager','Delivery Account Lead',
  'BU Engagement Leader','Customer Relationship Manager','Manager - Client Relations',
  'Account Manager','Account Manager - Strategic Accounts','Senior Account Executive',
  'Operations Head - India Strategic Accounts','Associate Director - Strategic Accounts',
  'Associate Director - Delivery','Assistant General Manager','Executive','Global Delivery Head',
  'Head of Learning and Development','Assistant Manager','Manager','Manager - HR Operations',
  'Regional Head - Corporate HR','Lead','L3 Support Engineer','Senior Executive',
  'L3 EPS Administrator','L2 Support Engineer','Assistant Manager - Admin','Lead - Digital Marketing',
  'Senior Manager','Executive Assistant - CEO','Lead - HR TAG','L3 Support System Engineer',
  'L1 Support Engineer','PMO Lead','Executive Multiskill Technician','Office Assistant',
  'Executive - Web Developer','Executive - Graphic Designer','Executive - MIS',
  'Senior Executive - MIS','Executive - Digital Marketing Specialist',
];

export default function SignatureGenerator({ entity, onBack }) {
  // Config per entity
  const SIGNATURE_CONFIG = {
    'vdart': {
      logo: 'http://vdpl.co/dnimg/vdartlogo.png',
      banner: 'http://vdpl.co/dnimg/greatplacebanner.jpg',
      color: '#242299',
      linkColor: '#0066cc',
      unsubscribe: 'https://www.surveymonkey.com/r/Opout',
      about: 'https://www.vdart.com/what-we-do/media/',
      linkedinIcon: 'http://vdpl.co/dnimg/linkedinlogo.png',
      emailDomain: '@vdartinc.com',
      supportLink: 'https://www.surveymonkey.com/r/Vsupport',
    },
    'vdart-digital': {
      logo: 'http://vdpl.co/dnimg/VDart_Digital_Blue_Logo.png',
      banner: 'http://vdpl.co/dnimg/VDart_Digital_Email_Banner.png',
      color: '#0066cc',
      linkColor: '#0066cc',
      unsubscribe: 'https://www.surveymonkey.com/r/Opout',
      about: 'https://www.vdart.com/what-we-do/media/vdart-celebrates-five-consecutive-wins-receives-national-supplier-of-the-year-class-iv-award-from-nmsdc',
      linkedinIcon: 'http://vdpl.co/dnimg/linkedinlogo.png',
      emailDomain: '@vdartdigital.com',
      supportLink: 'https://www.surveymonkey.com/r/Vsupport',
    },
    'trustpeople': {
      logo: 'http://vdpl.co/dnimg/trustpeople.png',
      banner: 'http://vdpl.co/dnimg/trustpeoplefinal.png',
      color: '#242299',
      linkColor: '#0066cc',
      unsubscribe: 'https://www.surveymonkey.com/r/Opout',
      about: 'https://www.vdart.com/what-we-do/media/',
      linkedinIcon: 'http://vdpl.co/dnimg/linkedinlogo.png',
      emailDomain: '@trustpeople.com',
      supportLink: 'https://www.surveymonkey.com/r/Vsupport',
    }
  };

  const config = SIGNATURE_CONFIG[entity.id] || SIGNATURE_CONFIG['vdart'];

  const [activeTab, setActiveTab] = useState(() => {
    return sessionStorage.getItem('sigGenTab') || 'outlook';
  });
  const [copySuccess, setCopySuccess] = useState(false);
  const [showInstructions, setShowInstructions] = useState(false);
  const instructionsRef = useRef(null);
  const [instrTab, setInstrTab] = useState('outlook');
  const [isGenerated, setIsGenerated] = useState(() => {
    return sessionStorage.getItem('sigGenGenerated') === 'true';
  });
  const [formData, setFormData] = useState(() => {
    const saved = sessionStorage.getItem('sigGenFormData');
    return saved ? JSON.parse(saved) : {
      name: '',
      title: '',
      countryCode: '+91',
      phone: '',
      email: '',
      linkedin: ''
    };
  });
  const [emailError, setEmailError] = useState('');
  const [phoneError, setPhoneError] = useState('');
  const [titleDropdownOpen, setTitleDropdownOpen] = useState(false);
  const [titleSearch, setTitleSearch] = useState('');
  const titleDropdownRef = useRef(null);
  
  const [profilePhoto, setProfilePhoto] = useState(null);
  const [videoUrl, setVideoUrl] = useState('');
  const [reviewsFile, setReviewsFile] = useState(null);
  const [isGenerating, setIsGenerating] = useState(false);
  const [isLoadingProfile, setIsLoadingProfile] = useState(false);
  const [profileId, setProfileId] = useState('');
  const [inputProfileId, setInputProfileId] = useState('');
  const [savedProfiles, setSavedProfiles] = useState([]);

  // Google Sheets Integration State
  const [sheetApiKey, setSheetApiKey] = useState('');
  const [sheetId, setSheetId] = useState('');
  const [empId, setEmpId] = useState('');
  const [sheetError, setSheetError] = useState('');
  const [sheetSuccess, setSheetSuccess] = useState('');
  const [isFetchingSheet, setIsFetchingSheet] = useState(false);
  const [fetchedReview, setFetchedReview] = useState('');
  const [fetchedRating, setFetchedRating] = useState(0);

  // Fetch profiles from database on load
  useEffect(() => {
    const fetchUserProfiles = async () => {
      try {
        const res = await profileApi.getUserProfiles();
        if (res.success && res.data) {
          setSavedProfiles(res.data);
        }
      } catch (err) {
        console.error('Error fetching user profiles:', err);
      }
    };
    fetchUserProfiles();
  }, []);

  // Close title dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (e) => {
      if (titleDropdownRef.current && !titleDropdownRef.current.contains(e.target)) {
        setTitleDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  useEffect(() => {
    sessionStorage.setItem('sigGenTab', activeTab);
  }, [activeTab]);

  useEffect(() => {
    sessionStorage.setItem('sigGenGenerated', isGenerated);
  }, [isGenerated]);

  useEffect(() => {
    sessionStorage.setItem('sigGenFormData', JSON.stringify(formData));
  }, [formData]);

  const previewRef = useRef(null);

  const formatPhone = (phone) => {
    if (!phone) return '';
    const cleaned = phone.replace(/\D/g, '');
    const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
    if (match) {
      return `(${match[1]}) ${match[2]}-${match[3]}`;
    }
    return phone;
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;

    if (name === 'phone') {
      // Allow only digits, max 10
      const digits = value.replace(/\D/g, '').slice(0, 10);
      setFormData(prev => ({ ...prev, phone: digits }));
      if (digits.length > 0 && digits.length < 10) {
        setPhoneError('Phone number must be exactly 10 digits');
      } else {
        setPhoneError('');
      }
      return;
    }

    if (name === 'email') {
      setFormData(prev => ({ ...prev, email: value }));
      if (value && !value.endsWith(config.emailDomain)) {
        setEmailError(`Email must end with ${config.emailDomain}`);
      } else {
        setEmailError('');
      }
      return;
    }

    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleTitleSelect = (title) => {
    setFormData(prev => ({ ...prev, title }));
    setTitleDropdownOpen(false);
    setTitleSearch('');
  };

  const filteredTitles = JOB_TITLES.filter(t =>
    t.toLowerCase().includes(titleSearch.toLowerCase())
  );

    const handleLoadProfile = async () => {
      if (!inputProfileId) return;
      setIsLoadingProfile(true);
      try {
        const res = await profileApi.getProfile(inputProfileId);
        if (res.success && res.data) {
          setFormData({
            name: res.data.name || '',
            title: res.data.title || '',
            countryCode: res.data.countryCode || '+91',
            phone: res.data.phone || '',
            email: res.data.email || '',
            linkedin: res.data.linkedin || '',
            teamLead: res.data.teamLead || '',
            leadName: res.data.leadName || ''
          });
          setVideoUrl(res.data.videoUrl || '');
          setProfileId(inputProfileId);

          if (res.data.email && !res.data.email.endsWith(config.emailDomain)) {
            setEmailError(`Profile loaded from another entity. Please update the email domain to ${config.emailDomain}`);
            setIsGenerated(false);
          } else {
            setEmailError('');
            setIsGenerated(true);
          }
          // Note: we cannot easily populate file inputs (photo, reviews) for security reasons
        } else {
          alert('Profile not found.');
        }
      } catch (err) {
        console.error('Error fetching profile:', err);
        alert('Failed to load profile.');
      } finally {
        setIsLoadingProfile(false);
      }
    };

  const handleGenerate = async () => {
    // Validate email domain
    if (!formData.email.endsWith(config.emailDomain)) {
      setEmailError(`Email must end with ${config.emailDomain}`);
      return;
    }
    // Validate phone is 10 digits
    const phoneDigits = formData.phone.replace(/\D/g, '');
    if (phoneDigits.length !== 10) {
      setPhoneError('Phone number must be exactly 10 digits');
      return;
    }
    if (formData.name && formData.title && formData.phone && formData.email) {
      setIsGenerating(true);
      try {
        let finalLinkedin = formData.linkedin;
        if (finalLinkedin && !finalLinkedin.startsWith('http://') && !finalLinkedin.startsWith('https://')) {
          finalLinkedin = 'https://' + finalLinkedin;
          setFormData(prev => ({ ...prev, linkedin: finalLinkedin }));
        }

        let photoUrl = '';
        if (profilePhoto) {
          const uploadRes = await uploadApi.uploadImages([profilePhoto]);
          if (uploadRes.success && uploadRes.files.length > 0) {
            photoUrl = 'http://localhost:8001/pages/' + uploadRes.files[0].path;
          }
        }

        let reviews = [];
        if (reviewsFile) {
          const buffer = await reviewsFile.arrayBuffer();
          const workbook = XLSX.read(buffer, { type: 'array' });
          const firstSheetName = workbook.SheetNames[0];
          const worksheet = workbook.Sheets[firstSheetName];
          const data = XLSX.utils.sheet_to_json(worksheet);
          
          reviews = data.map(row => ({
            text: row['Review'] || row['Review Text'] || row['Text'] || row['Feedback'] || '',
            rating: parseInt(row['Rating'] || row['Stars'] || 5),
            author: row['Name'] || row['Author'] || row['Reviewer'] || 'Anonymous'
          })).filter(r => r.text);

          // Add fetched review from Google Sheets if available
          if (fetchedReview && Array.isArray(fetchedReview)) {
            reviews = [...reviews, ...fetchedReview];
          } else if (fetchedReview) {
            reviews.push({
              text: fetchedReview,
              rating: fetchedRating || 5,
              author: 'Verified Candidate'
            });
          }
        } else if (fetchedReview && Array.isArray(fetchedReview)) {
          reviews = [...fetchedReview];
        } else if (fetchedReview) {
          // If no file uploaded but we have a fetched review, still save it
          reviews = [{
            text: fetchedReview,
            rating: fetchedRating || 5,
            author: 'Verified Candidate'
          }];
        }

        const profileData = {
          id: profileId || undefined,
          ...formData,
          photoUrl,
          videoUrl,
          reviews
        };

        const saveRes = await profileApi.saveProfile(profileData);
        if (saveRes.success) {
          setProfileId(saveRes.id);
          setInputProfileId(saveRes.id);
          setIsGenerated(true);
          
          // Refresh user profiles from DB
          const fetchRes = await profileApi.getUserProfiles();
          if (fetchRes.success && fetchRes.data) {
            setSavedProfiles(fetchRes.data);
          }
        } else {
          alert('Failed to generate profile link');
        }
      } catch (err) {
        console.error('Error generating profile:', err);
        alert('An error occurred during generation');
      } finally {
        setIsGenerating(false);
      }
    }
  };

  const handleReset = () => {
    setFormData({
      name: '',
      title: '',
      countryCode: '+91',
      phone: '',
      email: '',
      linkedin: '',
      teamLead: '',
      leadName: ''
    });
    setIsGenerated(false);
    setProfileId('');
    setInputProfileId('');
    setEmailError('');
    setPhoneError('');
    setSheetError('');
    setSheetSuccess('');
    setFetchedReview('');
    setFetchedRating(0);
    setEmpId('');
    sessionStorage.removeItem('sigGenTab');
    sessionStorage.removeItem('sigGenGenerated');
    sessionStorage.removeItem('sigGenFormData');
  };

  // Fetch from Google Sheets Public Visualization API
  const handleFetchSheetData = async () => {
    setSheetError('');
    setSheetSuccess('');
    
    if (!empId) {
      setSheetError('Please provide an Employee ID.');
      return;
    }

    setIsFetchingSheet(true);
    // Use Google Visualization API to query public sheet data safely without CORS issues
    const url = 'https://docs.google.com/spreadsheets/d/1d_WRPltqOlzT55bx-tNs0qvd-t9RB9EAeTTsp8m8HdM/gviz/tq?tqx=out:json&gid=1611340410';

    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error('Failed to fetch from Google Sheets.');
      }

      const textResponse = await response.text();
      
      // Extract the valid JSON from the Google Viz response wrapper
      const jsonString = textResponse.substring(textResponse.indexOf('{'), textResponse.lastIndexOf('}') + 1);
      let data;
      
      try {
        data = JSON.parse(jsonString);
      } catch (parseError) {
        throw new Error('Invalid JSON response received from Google Sheets.');
      }

      if (!data.table || !data.table.rows) {
        throw new Error('Unexpected data format from Google Sheets.');
      }

      const rows = data.table.rows;
      let foundRow = null;

      if (!rows || !Array.isArray(rows)) {
        throw new Error('No rows found in the spreadsheet data.');
      }

      for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (!row || !row.c) continue;
        
        const cells = row.c;
        const cell = cells[0];
        
        let cellValue = null;
        if (cell) {
          if (cell.v !== undefined) cellValue = cell.v;
          else if (cell.f !== undefined) cellValue = cell.f;
          else cellValue = cell;
        }
        
        if (cellValue !== null && String(cellValue).trim().toLowerCase() === String(empId).trim().toLowerCase()) {
          foundRow = cells;
          break;
        }
      }

      if (!foundRow) {
         throw new Error(`Employee ID "${empId}" not found in the sheet. Please verify the ID is correct and the sheet is published as "Anyone with the link can view".`);
      }

      // Extract values: 
      // 0=empid, 1=name, 2=designation, 3=email, 4=linkedin_url, 5=Team lead, 6=Lead name, 7=rating, 8=review
      const name = foundRow[1]?.v || '';
      const designation = foundRow[2]?.v || '';
      const email = foundRow[3]?.v || '';
      const linkedin_url = foundRow[4]?.v || '';
      const teamLead = foundRow[5]?.v || '';
      const leadName = foundRow[6]?.v || '';
      const ratingRaw = foundRow[7]?.v || '0';
      const reviewText = foundRow[8]?.v || '';

      const ratings = String(ratingRaw).split(/\/n/).map(r => parseFloat(r.trim()) || 0);

      if (name) {
        setFormData(prev => ({ 
          ...prev, 
          name, 
          title: designation, 
          email, 
          linkedin: linkedin_url,
          teamLead,
          leadName
        }));
      }
      
      // Parse reviews
      const parsedReviews = [];
      if (reviewText) {
        // Split reviews by /n or /e
        const chunks = reviewText.split(/\/n|\/e/).map(s => s.trim()).filter(Boolean);
        
        chunks.forEach((chunk, index) => {
          const lastDashIndex = chunk.lastIndexOf('-');
          let text, author;
          
          if (lastDashIndex !== -1) {
            text = chunk.substring(0, lastDashIndex).trim();
            author = chunk.substring(lastDashIndex + 1).trim();
          } else {
            text = chunk;
            author = 'Verified Candidate';
          }
          
          // Assign corresponding rating from the split ratings array, 
          // falling back to the first rating or 0 if not available
          const rating = ratings[index] !== undefined ? ratings[index] : (ratings[0] || 0);
          
          parsedReviews.push({ text, author, rating });
        });
      }

      setFetchedReview(parsedReviews);
      setFetchedRating(ratings[0] || 0);
      setSheetSuccess(`Successfully fetched data for ${name || 'Employee'}`);
      
    } catch (err) {
      setSheetError(err.message || 'Error fetching data.');
    } finally {
      setIsFetchingSheet(false);
    }
  };

  const generateStarsHtml = (rating) => {
    if (!rating) return '';
    const maxStars = 5;
    let starsHtml = '';
    const filledStarSvg = `<svg width="12" height="12" viewBox="0 0 24 24" fill="#FFD700" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 2px;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`;
    const emptyStarSvg = `<svg width="12" height="12" viewBox="0 0 24 24" fill="#E0E0E0" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 2px;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`;

    for (let i = 1; i <= maxStars; i++) {
        if (i <= Math.round(rating)) {
            starsHtml += filledStarSvg;
        } else {
            starsHtml += emptyStarSvg;
        }
    }
    return starsHtml;
  };

  // Build the raw HTML string for CEIPAL copy (matching the PHP output exactly)
  const buildCeipalHTML = () => {
    const fp = formatPhone(formData.phone);
    const linkedinBlock = formData.linkedin
      ? `<div style="margin-bottom: 6px !important;"><a href="${formData.linkedin}" style="display: inline-block !important; text-decoration: none !important; border: none !important;"><img src="${config.linkedinIcon}" alt="LinkedIn" width="15" height="15" style="border: none !important; display: block !important;"></a></div>`
      : '';

    return `<div style="font-family: 'Montserrat', Arial, sans-serif !important; font-size: 12px !important; color: #000000 !important; line-height: 1.2 !important; max-width: 600px !important; margin: 0 !important; padding: 0 !important;">
<table cellpadding="0" cellspacing="0" border="0" style="width: 330px !important; border-collapse: collapse !important;">
<tr><td style="padding: 0 !important; border: none !important;">
<table cellpadding="0" cellspacing="0" border="0" style="width: 100% !important; border-collapse: collapse !important;">
<tr>
<td width="80" valign="top" style="padding-right: 20px; border: none !important;">
<a target="_blank" style="border: none !important; text-decoration: none !important;"><img src="${config.logo}" alt="${entity.title}" width="90" style="display: block !important; max-width: 110px !important; height: auto !important; border: none !important;"></a>
</td>
<td valign="top" style="border: none !important;">
<div style="color: ${config.color} !important; font-size: 13.33px !important; font-weight: bold !important; margin-bottom: 3px !important; font-family: 'Montserrat', Arial, sans-serif !important;">${formData.name}</div>
<div style="color: #000000 !important; font-size: 10.67px !important; margin-bottom: 6px !important; font-family: 'Montserrat', Arial, sans-serif !important;">${formData.title}</div>
<div style="font-size: 10.67px !important; margin-bottom: 3px !important; font-family: 'Montserrat', Arial, sans-serif !important;"><img src="https://img.icons8.com/ios-filled/12/000000/phone.png" alt="P" width="12" height="12" style="vertical-align: middle; border: none; margin-right: 4px;"> ${fp}</div>
<div style="font-size: 10.67px !important; margin-bottom: 6px !important; font-family: 'Montserrat', Arial, sans-serif !important;"><img src="https://img.icons8.com/ios-filled/12/000000/email.png" alt="E" width="12" height="12" style="vertical-align: middle; border: none; margin-right: 4px;"> <a href="mailto:${formData.email}" style="color: #0066cc !important; text-decoration: none !important;">${formData.email}</a></div>
${linkedinBlock}
<div><a href="${window.location.origin}/profile/${profileId}" style="color: rgb(11, 11, 11) !important; text-decoration: none !important; font-size: 10.67px !important; font-weight: bold !important; font-style: italic !important; font-family: 'Montserrat', Arial, sans-serif !important;">To know more, click here</a></div>
</td>
</tr>
</table>
</td></tr>
<tr><td style="padding: 0px 0 !important; border: none !important;">
<table cellpadding="0" cellspacing="0" border="0" style="max-width: 100%; border-collapse: collapse !important;">
<tr><td><div style="background-color: ${config.color}; height: 1px; line-height: 1px; font-size: 0; width: 100%;">&nbsp;</div></td></tr>
<tr><td style="padding: 5px 0 0 0 !important; border: none !important;"><img src="${config.banner}" alt="Banner" width="100%" style="display: block !important; border: none !important; max-width: 330px; height: auto;"></td></tr>
</table>
</td></tr>
<tr><td style="padding-top: 8px; border: none !important; text-align: center;">
<a href="${config.unsubscribe}" style="color: #0066cc !important; text-decoration: none !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;">Unsubscribe</a>
<span style="color: #666666 !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;"> | </span>
<a href="${config.about}" style="color: #0066cc !important; text-decoration: none !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;">Want to know more About Us?</a>
</td></tr>
</table>
</div>`;
  };

  const buildOutlookHTML = () => {
    const linkedinBlock = formData.linkedin
      ? `<div style="margin-bottom: 6px; border: none;"><a href="${formData.linkedin}" style="display: inline-block; text-decoration: none; border: none;"><img src="${config.linkedinIcon}" alt="LinkedIn" width="15" height="15" style="border: none; display: block;" /></a></div>`
      : '';

    return `<div style="font-family: 'Montserrat', Arial, sans-serif; font-size: 12px; color: #000000; line-height: 1.2; max-width: 600px; margin: 0; padding: 0; border: none;">
<table cellpadding="0" cellspacing="0" style="width: 330px; border-collapse: collapse; margin: 0; padding: 0;">
<tbody>
<tr>
<td style="padding: 0; border: none;">
<table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
<tbody>
<tr>
<td width="80" valign="top" style="padding-right: 20px; border: none;">
<a href="https://www.vdart.com" target="_blank" rel="noreferrer" style="border: none; text-decoration: none;">
<img src="${config.logo}" alt="${entity.title}" width="90" style="display: block; max-width: 110px; height: auto; border: none;" />
</a>
</td>
<td valign="top" style="border: none;">
<div style="color: ${config.color}; font-size: 13.33px; font-weight: bold; margin-bottom: 3px; font-family: 'Montserrat', Arial, sans-serif;">${formData.name}</div>
<div style="color: #000000; font-size: 10.67px; margin-bottom: 6px; font-family: 'Montserrat', Arial, sans-serif;">${formData.title}</div>
<div style="font-size: 10.67px; margin-bottom: 3px; font-family: 'Montserrat', Arial, sans-serif;"><img src="https://img.icons8.com/ios-filled/12/000000/phone.png" alt="P" width="12" height="12" style="vertical-align: middle; border: none; margin-right: 4px;" /> ${formatPhone(formData.phone)}</div>
<div style="font-size: 10.67px; margin-bottom: 6px; font-family: 'Montserrat', Arial, sans-serif;"><img src="https://img.icons8.com/ios-filled/12/000000/email.png" alt="E" width="12" height="12" style="vertical-align: middle; border: none; margin-right: 4px;" /> <a href="mailto:${formData.email}" style="color: ${config.linkColor}; text-decoration: none; border: none;">${formData.email}</a></div>
${linkedinBlock}
<div>
<a href="${window.location.origin}/profile/${profileId}" style="color: rgb(12, 12, 12); text-decoration: none; font-size: 10.67px; font-weight: bold; font-style: italic; border: none; font-family: 'Montserrat', Arial, sans-serif;">To know more, click here</a>
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="padding: 2px 0; border: none;">
<table cellpadding="0" cellspacing="0" style="width: 330px; border-collapse: collapse; margin: 0;">
<tbody>
<tr>
<td style="border: none;">
<table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
<tbody>
<tr>
<td style="padding: 0; margin: 0; height: 8px; line-height: 1px; border: none;">
<table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
<tbody>
<tr>
<td bgcolor="${config.color}" style="height: 1px; line-height: 1px; padding: 0; margin: 0; font-size: 1px; border: none;"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="padding: 2px 0 0 0; border: none;">
<img src="${config.banner}" alt="Banner" width="330" style="display: block; border: none;" />
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="padding-top: 8px; border: none;">
<p style="margin: 0; font-size: 8px; color: #A9A9A9; line-height: 1.3; border: none; font-family: 'Montserrat', Arial, sans-serif;">The content of this email is confidential and intended for the recipient specified in the message only. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you received this message by mistake, please reply to this message and follow with its deletion, so that we can ensure such a mistake does not occur in the future.</p>
</td>
</tr>
<tr>
<td style="padding-bottom: 8px; padding-top: 8px; border: none; text-align: center; border-collapse: collapse;">
<a href="${config.unsubscribe}" style="color: ${config.linkColor}; text-decoration: none; font-size: 10px; border: none; border-collapse: collapse;">Unsubscribe</a>
<span style="color: #666666; font-size: 10px; font-family: 'Montserrat', Arial, sans-serif;"> | </span>
<a href="${config.about}" style="color: ${config.linkColor}; text-decoration: none; font-size: 10px; border: none; border-collapse: collapse;">Want to know more About Us?</a>
</td>
</tr>
</tbody>
</table>
</div>`;
  };

  const copyToClipboard = async () => {
    try {
      // Use HTML builder for both tabs
      const html = activeTab === 'outlook' ? buildOutlookHTML() : buildCeipalHTML();
      await navigator.clipboard.writeText(html);
      
      setCopySuccess(true);
      setTimeout(() => setCopySuccess(false), 2000);
    } catch (err) {
      console.error('Failed to copy HTML:', err);
    }
  };

  const phoneDigits = formData.phone.replace(/\D/g, '');
  const isFormFilled = formData.name && formData.title && phoneDigits.length === 10 && formData.email && formData.email.endsWith(config.emailDomain);
  const isComplete = isFormFilled && isGenerated;
  const formattedPhone = formatPhone(formData.phone);

  return (
    <div className="sig-gen">
      <Header showHome={true} onHomeClick={onBack} />
      <main className="sig-gen__main">
        {/* Hero */}
        <motion.div
          className="sig-gen__hero"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
        >
          <button className="sig-gen__back" onClick={onBack} type="button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <line x1="19" y1="12" x2="5" y2="12" />
              <polyline points="12 19 5 12 12 5" />
            </svg>
            Back to Dashboard
          </button>

          <div className="sig-gen__hero-content">
            <img src={entity.logoUrl} alt={entity.title} className="sig-gen__entity-logo" />
            <div>
              <h2 className="sig-gen__title">
                Email Signature <span className="text-gradient">Generator</span>
              </h2>
              <p className="sig-gen__subtitle">
                Generate your branded email signature for <strong>{entity.title}</strong>
              </p>
            </div>
          </div>
        </motion.div>

        {/* ── Instruction Content Block ── */}
        <motion.div
          ref={instructionsRef}
          className="sig-gen__instructions card"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.15 }}
        >
          <button
            className="sig-gen__instructions-toggle"
            onClick={() => setShowInstructions(!showInstructions)}
            type="button"
            aria-expanded={showInstructions}
          >
            <div className="sig-gen__instructions-toggle-left">
              <div className="sig-gen__instructions-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <circle cx="12" cy="12" r="10" />
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                  <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
              </div>
              <div>
                <h3 className="sig-gen__instructions-title">How to Set Up Your Email Signature</h3>
                <p className="sig-gen__instructions-desc">
                  Follow the step-by-step guide for Outlook or CEIPAL to apply your signature
                </p>
              </div>
            </div>
            <svg
              className={`sig-gen__instructions-chevron ${showInstructions ? 'open' : ''}`}
              width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
            >
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </button>

          <AnimatePresence>
            {showInstructions && (
              <motion.div
                className="sig-gen__steps-wrapper"
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                exit={{ opacity: 0, height: 0 }}
                transition={{ duration: 0.3 }}
              >
                {/* Tab Switcher */}
                <div className="sig-gen__instr-tabs">
                  <button
                    type="button"
                    className={`sig-gen__instr-tab ${instrTab === 'outlook' ? 'active' : ''}`}
                    onClick={() => setInstrTab('outlook')}
                  >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                      <path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    Outlook Steps
                  </button>
                  <button
                    type="button"
                    className={`sig-gen__instr-tab ${instrTab === 'ceipal' ? 'active' : ''}`}
                    onClick={() => setInstrTab('ceipal')}
                  >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                      <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    CEIPAL Steps
                  </button>
                </div>

                {/* Step Cards */}
                <div className="sig-gen__steps-grid">
                  {(instrTab === 'outlook' ? OUTLOOK_STEPS : CEIPAL_STEPS).map((step, index) => (
                    <motion.div
                      key={`${instrTab}-${step.step}`}
                      className="sig-gen__step-card"
                      initial={{ opacity: 0, y: 20 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ duration: 0.3, delay: index * 0.08 }}
                    >
                      <div className="sig-gen__step-number">{step.step}</div>
                      <div className="sig-gen__step-icon-wrap">
                        {SIG_STEP_ICONS[step.icon]}
                      </div>
                      <h4 className="sig-gen__step-title">{step.title}</h4>
                      <p className="sig-gen__step-desc">{step.description}</p>
                      {/* Image placeholder — images at: {step.imagePath} */}
                    </motion.div>
                  ))}
                </div>
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>

        <div className="sig-gen__grid">
          {/* Left: Input Form */}
          <motion.div
            className="sig-gen__form card"
            initial={{ opacity: 0, x: -30 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.5, delay: 0.1 }}
          >
              <h3 className="sig-gen__section-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                Your Details
              </h3>

              {/* Google Sheets Data Fetch Section */}
              <div className="sig-gen__input-group" style={{ backgroundColor: '#f0f4f8', padding: '15px', borderRadius: '8px', marginBottom: '20px', border: '1px solid #d1d5db' }}>
                <label style={{ display: 'block', fontWeight: 'bold', marginBottom: '10px' }}>Fetch Data via Google Sheets (Optional)</label>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
                  <div style={{ display: 'flex', gap: '10px' }}>
                    <input
                      className="input-field"
                      type="text"
                      placeholder="Employee ID"
                      value={empId}
                      onChange={(e) => setEmpId(e.target.value)}
                      style={{ flex: 1 }}
                    />
                    <button
                      type="button"
                      className="btn btn-secondary"
                      onClick={handleFetchSheetData}
                      disabled={isFetchingSheet}
                      style={{ whiteSpace: 'nowrap' }}
                    >
                      {isFetchingSheet ? 'Fetching...' : 'Fetch Data'}
                    </button>
                  </div>
                  {sheetError && <small className="sig-gen__field-error" style={{ color: '#d9534f' }}>{sheetError}</small>}
                  {sheetSuccess && <small style={{ color: '#28a745', fontSize: '0.85rem' }}>{sheetSuccess}</small>}
                </div>
              </div>

              <div className="sig-gen__input-group">
                {/* Profile ID Input Box */}
                <div className="input-group" style={{ backgroundColor: "#f9fafb", padding: "15px", borderRadius: "8px", border: "1px dashed #d1d5db", marginBottom: "15px" }}>
                  <label htmlFor="inputProfileId" style={{ display: 'flex', alignItems: 'center', gap: '5px' }}>
                    Select Profile to Edit
                    <div className="sig-gen__info-tooltip">
                      <svg className="sig-gen__info-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                      </svg>
                      <span className="tooltip-text">Select a profile you have previously created on your account to edit its information.</span>
                    </div>
                  </label>
                  <div style={{ display: 'flex', gap: '10px' }}>
                    <select
                      className="input-field"
                      id="inputProfileId"
                      value={inputProfileId}
                      onChange={(e) => setInputProfileId(e.target.value)}
                    >
                      <option value="">-- Create New Profile --</option>
                      {savedProfiles.map(p => (
                        <option key={p.id} value={p.id}>{p.name} ({p.id}) - {new Date(p.date).toLocaleDateString()}</option>
                      ))}
                    </select>
                    <button 
                      type="button" 
                      className="btn btn-secondary" 
                      onClick={handleLoadProfile}
                      disabled={!inputProfileId || isLoadingProfile}
                      style={{ whiteSpace: 'nowrap' }}
                    >
                      {isLoadingProfile ? 'Loading...' : 'Load Profile'}
                    </button>
                  </div>
                </div>

                <div className="input-group">
                  <label htmlFor="name">Full Name *</label>
                <input
                  className="input-field"
                  type="text"
                  id="name"
                  name="name"
                  value={formData.name}
                  onChange={handleInputChange}
                  placeholder="e.g., John Doe"
                  required
                />
              </div>

              {/* Job Title Dropdown */}
              <div className="input-group" ref={titleDropdownRef}>
                <label htmlFor="title">Job Title *</label>
                <div className="sig-gen__title-dropdown">
                  <button
                    type="button"
                    className={`input-field sig-gen__title-trigger ${formData.title ? 'has-value' : ''}`}
                    onClick={() => setTitleDropdownOpen(!titleDropdownOpen)}
                  >
                    <span>{formData.title || 'Select Your Title'}</span>
                    <svg className={`sig-gen__title-arrow ${titleDropdownOpen ? 'open' : ''}`} width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <polyline points="6 9 12 15 18 9" />
                    </svg>
                  </button>
                  {titleDropdownOpen && (
                    <div className="sig-gen__title-options">
                      <div className="sig-gen__title-search-wrap">
                        <input
                          type="text"
                          className="sig-gen__title-search"
                          placeholder="Search titles..."
                          value={titleSearch}
                          onChange={(e) => setTitleSearch(e.target.value)}
                          autoFocus
                          onClick={(e) => e.stopPropagation()}
                        />
                      </div>
                      <div className="sig-gen__title-list">
                        {filteredTitles.map((title) => (
                          <div
                            key={title}
                            className={`sig-gen__title-option ${formData.title === title ? 'selected' : ''}`}
                            onClick={() => handleTitleSelect(title)}
                          >
                            {title}
                          </div>
                        ))}
                        {filteredTitles.length === 0 && (
                          <div className="sig-gen__title-option disabled">No matching titles</div>
                        )}
                      </div>
                    </div>
                  )}
                </div>
              </div>

              {/* Phone */}
              <div className="input-group">
                <label htmlFor="phone">Phone Number * (10 digits)</label>
                <div className="sig-gen__phone-row">
                  <input
                    className="input-field sig-gen__phone-input"
                    type="tel"
                    id="phone"
                    name="phone"
                    value={formData.phone}
                    onChange={handleInputChange}
                    placeholder="e.g., 4703238433"
                    pattern="[0-9]{10}"
                    maxLength="10"
                    required
                  />
                </div>
                {phoneError && <small className="sig-gen__field-error">{phoneError}</small>}
              </div>

              {/* Email with Domain Validation */}
              <div className="input-group">
                <label htmlFor="email">Email Address * (must end with {config.emailDomain})</label>
                <input
                  className={`input-field ${emailError ? 'input-error' : ''}`}
                  type="email"
                  id="email"
                  name="email"
                  value={formData.email}
                  onChange={handleInputChange}
                  placeholder={`e.g., john.doe${config.emailDomain}`}
                  required
                />
                {emailError && <small className="sig-gen__field-error">{emailError}</small>}
              </div>

              <div className="input-group">
                <label htmlFor="linkedin">LinkedIn URL (Optional)</label>
                <input
                  className="input-field"
                  type="url"
                  id="linkedin"
                  name="linkedin"
                  value={formData.linkedin}
                  onChange={handleInputChange}
                  placeholder="https://linkedin.com/in/johndoe"
                />
              </div>

              <div className="input-group">
                <label htmlFor="profilePhoto">Profile Photo (Optional)</label>
                <input
                  className="input-field"
                  type="file"
                  id="profilePhoto"
                  accept="image/*"
                  onChange={(e) => setProfilePhoto(e.target.files[0])}
                />
              </div>

              <div className="input-group">
                <label htmlFor="videoUrl">Intro Video URL (YouTube/Vimeo) (Optional)</label>
                <input
                  className="input-field"
                  type="url"
                  id="videoUrl"
                  value={videoUrl}
                  onChange={(e) => setVideoUrl(e.target.value)}
                  placeholder="https://www.youtube.com/watch?v=..."
                />
              </div>

              <div className="input-group">
                <label htmlFor="reviewsFile">Candidate Reviews (Excel File) (Optional)</label>
                <input
                  className="input-field"
                  type="file"
                  id="reviewsFile"
                  accept=".xlsx, .xls"
                  onChange={(e) => setReviewsFile(e.target.files[0])}
                />
              </div>
            </div>

            {/* Generate Button */}
            <div className="sig-gen__actions">
               <button
                 type="button"
                 className="btn btn-primary sig-gen__generate-btn"
                 onClick={handleGenerate}
                 disabled={!isFormFilled || isGenerating}
               >
                 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                   <polyline points="20 6 9 17 4 12" />
                 </svg>
                 {isGenerating ? (profileId ? 'Updating...' : 'Generating...') : (profileId ? 'Update Profile & Generate Signature' : 'Create Profile & Generate Signature')}
               </button>
               {isGenerated && (
                 <button 
                   className="btn btn-secondary" 
                   onClick={handleReset} 
                   type="button" 
                   style={{ marginLeft: '10px' }}
                 >
                   Start Over
                 </button>
               )}
            </div>
          </motion.div>

          {/* Right: Preview */}
          <motion.div
            className="sig-gen__preview-section"
            initial={{ opacity: 0, x: 30 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
          >
            <div className="sig-gen__tabs">
              <button
                type="button"
                className={`sig-gen__tab ${activeTab === 'outlook' ? 'active' : ''}`}
                onClick={() => setActiveTab('outlook')}
              >
                Outlook / Webmail
              </button>
              <button
                type="button"
                className={`sig-gen__tab ${activeTab === 'ceipal' ? 'active' : ''}`}
                onClick={() => setActiveTab('ceipal')}
              >
                CEIPAL (Raw HTML)
              </button>
            </div>

            <div className="sig-gen__preview-card card">
              <div className="sig-gen__preview-header">
                <h3>Preview</h3>
                {isComplete && (
                  <button
                    type="button"
                    className="copy-btn sig-gen__copy-btn"
                    onClick={copyToClipboard}
                    title="Copy HTML Code"
                    style={copySuccess ? { background: '#10b981' } : {}}
                  >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                      <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                    </svg>
                    {copySuccess ? 'Copied!' : 'Copy HTML Code'}
                  </button>
                )}
              </div>

              <div className="sig-gen__preview-content">
                {!isComplete ? (
                  <div className="sig-gen__empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1" strokeLinecap="round" strokeLinejoin="round" className="text-gray-400 mb-4">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                      <line x1="3" y1="9" x2="21" y2="9" />
                      <line x1="9" y1="21" x2="9" y2="9" />
                    </svg>
                    <p>Fill out the required fields to generate your signature</p>
                  </div>
                ) : (
                  <div className="sig-gen__rendered-container">
                    <div className="sig-gen__rendered" ref={previewRef}>
                      {activeTab === 'outlook' ? (
                        // OUTLOOK SIGNATURE
                        <div style={{ fontFamily: "'Montserrat', Arial, sans-serif", fontSize: "12px", color: "#000000", lineHeight: 1.2, maxWidth: "600px", margin: 0, padding: 0, border: "none" }}>
                          <table cellPadding="0" cellSpacing="0" style={{ width: "330px", borderCollapse: "collapse", margin: 0, padding: 0 }}>
                            <tbody>
                              <tr>
                                <td style={{ padding: 0, border: "none" }}>
                                  <table cellPadding="0" cellSpacing="0" style={{ width: "100%", borderCollapse: "collapse" }}>
                                    <tbody>
                                      <tr>
                                        <td width="80" valign="top" style={{ paddingRight: "20px", border: "none" }}>
                                          <a href="https://www.vdart.com" target="_blank" rel="noreferrer" style={{ border: "none", textDecoration: "none" }}>
                                            <img src={config.logo} alt={entity.title} width="90" style={{ display: "block", maxWidth: "110px", height: "auto", border: "none" }} />
                                          </a>
                                        </td>
                                        <td valign="top" style={{ border: "none" }}>
                                          <div style={{ color: config.color, fontSize: "13.33px", fontWeight: "bold", marginBottom: "3px", fontFamily: "'Montserrat', Arial, sans-serif" }}>{formData.name}</div>
                                          <div style={{ color: "#000000", fontSize: "10.67px", marginBottom: "6px", fontFamily: "'Montserrat', Arial, sans-serif" }}>{formData.title}</div>
                                          <div style={{ fontSize: "10.67px", marginBottom: "3px", fontFamily: "'Montserrat', Arial, sans-serif" }}><img src="https://img.icons8.com/ios-filled/12/000000/phone.png" alt="P" width="12" height="12" style={{ verticalAlign: "middle", border: "none", marginRight: "4px" }} /> {formattedPhone}</div>
                                          <div style={{ fontSize: "10.67px", marginBottom: "6px", fontFamily: "'Montserrat', Arial, sans-serif" }}><img src="https://img.icons8.com/ios-filled/12/000000/email.png" alt="E" width="12" height="12" style={{ verticalAlign: "middle", border: "none", marginRight: "4px" }} /> <a href={`mailto:${formData.email}`} style={{ color: config.linkColor, textDecoration: "none", border: "none" }}>{formData.email}</a></div>
                                          <div style={{ marginBottom: "6px", border: "none" }}>
                                            {formData.linkedin && (
                                              <a href={formData.linkedin} style={{ display: "inline-block", textDecoration: "none", border: "none" }}>
                                                <img src={config.linkedinIcon} alt="LinkedIn" width="15" height="15" style={{ border: "none", display: "block" }} />
                                              </a>
                                            )}
                                          </div>
                                          <div>
                                            <a href={`${window.location.origin}/profile/${profileId}`} style={{ color: "rgb(12, 12, 12)", textDecoration: "none", fontSize: "10.67px", fontWeight: "bold", fontStyle: "italic", border: "none", fontFamily: "'Montserrat', Arial, sans-serif" }}>To know more, click here</a>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td style={{ padding: "2px 0", border: "none" }}>
                                  <table cellPadding="0" cellSpacing="0" style={{ width: "330px", borderCollapse: "collapse", margin: 0 }}>
                                    <tbody>
                                      <tr>
                                        <td style={{ border: "none" }}>
                                          <table cellPadding="0" cellSpacing="0" style={{ width: "100%", borderCollapse: "collapse" }}>
                                            <tbody>
                                              <tr>
                                                <td style={{ padding: 0, margin: 0, height: "8px", lineHeight: "1px", border: "none" }}>
                                                  <table cellPadding="0" cellSpacing="0" width="100%" style={{ borderCollapse: "collapse" }}>
                                                    <tbody>
                                                      <tr>
                                                        <td bgcolor={config.color} style={{ height: "1px", lineHeight: "1px", padding: 0, margin: 0, fontSize: "1px", border: "none" }}></td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style={{ padding: "2px 0 0 0", border: "none" }}>
                                          <img src={config.banner} alt="Banner" width="330" style={{ display: "block", border: "none" }} />
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td style={{ paddingTop: "8px", border: "none" }}>
                                  <p style={{ margin: 0, fontSize: "8px", color: "#A9A9A9", lineHeight: 1.3, border: "none", fontFamily: "'Montserrat', Arial, sans-serif" }}>
                                    The content of this email is confidential and intended for the recipient specified in the message only. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you received this message by mistake, please reply to this message and follow with its deletion, so that we can ensure such a mistake does not occur in the future.
                                  </p>
                                </td>
                              </tr>
                              <tr>
                                <td style={{ paddingBottom: "8px", paddingTop: "8px", border: "none", textAlign: "center", borderCollapse: "collapse" }}>
                                  <a href={config.unsubscribe} style={{ color: config.linkColor, textDecoration: "none", fontSize: "10px", border: "none", borderCollapse: "collapse" }}>Unsubscribe</a>
                                  <span style={{ color: "#666666", fontSize: "10px", fontFamily: "'Montserrat', Arial, sans-serif" }}> | </span>
                                  <a href={config.about} style={{ color: config.linkColor, textDecoration: "none", fontSize: "10px", border: "none", borderCollapse: "collapse" }}>Want to know more About Us?</a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      ) : (
                        // CEIPAL SIGNATURE
                        <div style={{ fontFamily: "'Montserrat', Arial, sans-serif !important", fontSize: "12px !important", color: "#000000 !important", lineHeight: "1.2 !important", maxWidth: "600px !important", margin: "0 !important", padding: "0 !important" }}>
                          <table cellPadding="0" cellSpacing="0" border="0" style={{ width: "330px !important", borderCollapse: "collapse !important" }}>
                            <tbody>
                              <tr>
                                <td style={{ padding: "0 !important", border: "none !important" }}>
                                  <table cellPadding="0" cellSpacing="0" border="0" style={{ width: "100% !important", borderCollapse: "collapse !important" }}>
                                    <tbody>
                                      <tr>
                                        <td width="80" valign="top" style={{ paddingRight: "20px", border: "none !important" }}>
                                          <a href="https://www.vdart.com" target="_blank" rel="noreferrer" style={{ border: "none", textDecoration: "none" }}>
                                            <img src={config.logo} alt={entity.title} width="90" style={{ display: "block", maxWidth: "110px", height: "auto", border: "none" }} />
                                          </a>
                                        </td>
                                        <td valign="top" style={{ border: "none !important" }}>
                                          <div style={{ color: `${config.color} !important`, fontSize: "13.33px !important", fontWeight: "bold !important", marginBottom: "3px !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}>{formData.name}</div>
                                          <div style={{ color: "#000000 !important", fontSize: "10.67px !important", marginBottom: "6px !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}>{formData.title}</div>
                                          <div style={{ fontSize: "10.67px !important", marginBottom: "3px !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}><img src="https://img.icons8.com/ios-filled/12/000000/phone.png" alt="P" width="12" height="12" style={{ verticalAlign: "middle", border: "none", marginRight: "4px" }} /> {formattedPhone}</div>
                                          <div style={{ fontSize: "10.67px !important", marginBottom: "6px !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}><img src="https://img.icons8.com/ios-filled/12/000000/email.png" alt="E" width="12" height="12" style={{ verticalAlign: "middle", border: "none", marginRight: "4px" }} /> <a href={`mailto:${formData.email}`} style={{ color: `${config.linkColor} !important`, textDecoration: "none !important" }}>{formData.email}</a></div>
                                          <div style={{ marginBottom: "6px !important" }}>
                                            {formData.linkedin && (
                                              <a href={formData.linkedin} style={{ display: "inline-block !important", textDecoration: "none !important", border: "none !important" }}>
                                                <img src={config.linkedinIcon} alt="LinkedIn" width="15" height="15" style={{ border: "none !important", display: "block !important" }} />
                                              </a>
                                            )}
                                          </div>
                                          <div>
                                            <a href={`${window.location.origin}/profile/${profileId}`} style={{ color: "rgb(11, 11, 11) !important", textDecoration: "none !important", fontSize: "10px !important", fontWeight: "bold !important", fontStyle: "italic !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}>To know more, click here</a>
                                          </div>
                                          {fetchedReview && (
                                            <div style={{ marginTop: "10px !important", padding: "10px !important", borderLeft: `3px solid ${config.color} !important`, backgroundColor: "#f9f9f9 !important", borderRadius: "0 4px 4px 0 !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}>
                                              <div style={{ marginBottom: "5px !important", fontSize: "0 !important" }} dangerouslySetInnerHTML={{ __html: generateStarsHtml(fetchedRating) }} />
                                              <div style={{ fontStyle: "italic !important", color: "#555 !important", fontSize: "10.67px !important" }}>"{fetchedReview}"</div>
                                            </div>
                                          )}
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td style={{ padding: "2px 0", border: "none" }}>
                                  <table cellPadding="0" cellSpacing="0" style={{ width: "330px", borderCollapse: "collapse", margin: 0 }}>
                                    <tbody>
                                      <tr>
                                        <td style={{ border: "none" }}>
                                          <table cellPadding="0" cellSpacing="0" style={{ width: "100%", borderCollapse: "collapse" }}>
                                            <tbody>
                                              <tr>
                                                <td style={{ padding: 0, margin: 0, height: "8px", lineHeight: "1px", border: "none" }}>
                                                  <table cellPadding="0" cellSpacing="0" width="100%" style={{ borderCollapse: "collapse" }}>
                                                    <tbody>
                                                      <tr>
                                                        <td bgcolor={config.color} style={{ height: "1px", lineHeight: "1px", padding: 0, margin: 0, fontSize: "1px", border: "none" }}></td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style={{ padding: "2px 0 0 0", border: "none" }}>
                                          <img src={config.banner} alt="Banner" width="330" style={{ display: "block", border: "none" }} />
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td style={{ paddingTop: "8px", border: "none !important", textAlign: "center" }}>
                                  <a href={config.unsubscribe} style={{ color: `${config.linkColor} !important`, textDecoration: "none !important", fontSize: "10px !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}>Unsubscribe</a>
                                  <span style={{ color: "#666666 !important", fontSize: "10px !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}> | </span>
                                  <a href={config.about} style={{ color: `${config.linkColor} !important`, textDecoration: "none !important", fontSize: "10px !important", fontFamily: "'Montserrat', Arial, sans-serif !important" }}>Want to know more About Us?</a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      )}
                    </div>
                  </div>
                )}
              </div>
            </div>
            
            {isComplete && (
              <div className="sig-gen__ceipal-note">
                <p>Clicking "Copy HTML Code" will copy the raw HTML source for the {activeTab === 'outlook' ? 'Outlook/Webmail' : 'CEIPAL'} format. You can paste it directly into the signature editor.</p>
              </div>
            )}
          </motion.div>
        </div>
      </main>

      {/* ── CTA Banner ── */}
      <div className="sig-gen__cta-banner">
        <div className="sig-gen__cta-inner">
          <div className="sig-gen__cta-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
              <polyline points="22,6 12,13 2,6" />
            </svg>
          </div>
          <div className="sig-gen__cta-text">
            <span className="sig-gen__cta-question">Need help setting up your email signature?</span>
            <span className="sig-gen__cta-hint">Follow our step-by-step guides for Outlook and CEIPAL</span>
          </div>
           <button
             className="sig-gen__cta-action"
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
