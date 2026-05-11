import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { profileApi } from '../../api/client';
import { syncProfileWithSheet } from '../../api/profileApi';
import './ProfilePage.css';

export default function ProfilePage() {
  const { id } = useParams();
  const [dropdownOptions, setDropdownOptions] = useState([]);
  const [selectedProfileId, setSelectedProfileId] = useState(id || null);
  const [activeProfileData, setActiveProfileData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const initializeProfiles = async () => {
      try {
        const response = await profileApi.getUserProfiles();
        if (response.success && Array.isArray(response.data)) {
          setDropdownOptions(response.data);
        } else {
          console.error('Failed to load profile options:', response.message);
        }
      } catch (err) {
        console.error('Error fetching profiles:', err);
      }
    };
    initializeProfiles();
  }, []);

  const fetchProfile = async (idToFetch) => {
    if (!idToFetch) return;
    setLoading(true);
    try {
      const response = await profileApi.getProfile(idToFetch);
      if (response.success && response.data) {
        setActiveProfileData(response.data);
        setError('');
      } else {
        setError(response.message || 'Profile not found');
      }
    } catch (err) {
      setError('Failed to load profile');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (selectedProfileId) {
      fetchProfile(selectedProfileId);
    }
  }, []); // Only on mount

  useEffect(() => {
    if (!activeProfileData?.empId) return;

    const intervalId = setInterval(async () => {
      try {
        // 1. Create a unique timestamp to bypass Google's cache
        const cacheBuster = Date.now(); 
        
        // 2. Append it to the URL as a dummy parameter (&_cb=...)
        const response = await fetch(`https://docs.google.com/spreadsheets/d/1d_WRPltqOlzT55bx-tNs0qvd-t9RB9EAeTTsp8m8HdM/gviz/tq?tqx=out:json&gid=1611340410&_cb=${cacheBuster}`);
        
        const text = await response.text();
        const jsonString = text.substring(text.indexOf('{'), text.lastIndexOf('}') + 1);
        const data = JSON.parse(jsonString);
        const { cols, rows } = data.table;

        const empIdColIndex = cols.findIndex(col => col.label === 'empId');
        const nameColIndex = cols.findIndex(col => col.label === 'Name');
        const titleColIndex = cols.findIndex(col => col.label === 'Designation');
        const reviewsColIndex = cols.findIndex(col => col.label === 'Reviews');

        if (empIdColIndex === -1) return;

        const row = rows.find(r => r.c[empIdColIndex]?.v === activeProfileData.empId);
        if (row) {
          const newName = row.c[nameColIndex]?.v;
          const newTitle = row.c[titleColIndex]?.v;
          const newReviews = row.c[reviewsColIndex]?.v;

           setActiveProfileData(prev => ({
             ...prev,
             name: newName || prev.name,
             title: newTitle || prev.title,
             reviews: newReviews ? (typeof newReviews === 'string' ? JSON.parse(newReviews) : newReviews) : prev.reviews
           }));

        }
      } catch (err) {
        console.error('Polling error:', err);
      }
    }, 5000);

    return () => clearInterval(intervalId);
  }, [activeProfileData?.empId]);

  const handleLoadProfile = async () => {
    if (!selectedProfileId) return;
    await fetchProfile(selectedProfileId);
  };

  const handleDeleteProfile = async () => {
    if (!activeProfileData?.id) return;
    const profileId = activeProfileData.id;

    if (!window.confirm('Are you sure you want to delete this profile? This action cannot be undone.')) {
      return;
    }

    try {
      const response = await fetch(`/api/profiles/delete_profile.php?id=${profileId}`, {
        method: 'DELETE',
      });

      if (response.ok) {
        setDropdownOptions(prev => prev.filter(option => option.id !== profileId));
        setActiveProfileData(null);
        setSelectedProfileId(null);
        alert('Profile deleted successfully');
      } else {
        const data = await response.json();
        alert(`Error deleting profile: ${data.error || 'Unknown error'}`);
      }
    } catch (err) {
      console.error('Deletion error:', err);
      alert('Failed to delete profile. Please try again.');
    }
  };

  if (loading) {
    return (
      <div className="profile-page-loading">
        <div className="spinner" style={{ width: 40, height: 40, margin: '0 auto 16px' }} />
        <p>Loading Profile...</p>
      </div>
    );
  }

  if (error || !activeProfileData) {
    return (
      <div className="profile-page-error">
        <h2>Oops!</h2>
        <p>{error || 'Profile not found.'}</p>
      </div>
    );
  }

  return (
    <div className="profile-container">
      <div className="profile-header">
        <img src="http://vdpl.co/dnimg/vdartlogo.png" alt="VDart Logo" className="logo" />
        <span className="badge">
          <span style={{ marginRight: '5px' }}>&#10003;</span> Verified VDart Recruiter
        </span>
      </div>

      <div className="profile-management card" style={{ marginBottom: '2rem', padding: '1.5rem', display: 'flex', gap: '1rem', alignItems: 'center', justifyContent: 'center', flexWrap: 'wrap' }}>
        <div className="management-item" style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
          <label htmlFor="profile-select" style={{ fontWeight: 'bold' }}>Select Profile:</label>
          <select
            id="profile-select"
            className="input-field"
            value={selectedProfileId || ''}
            onChange={(e) => setSelectedProfileId(e.target.value)}
            style={{ minWidth: '200px' }}
          >
            <option value="">-- Select a Profile --</option>
            {dropdownOptions.map(opt => (
              <option key={opt.id} value={opt.id}>{opt.name || opt.id}</option>
            ))}
          </select>
        </div>
        <button className="btn btn-primary" onClick={handleLoadProfile} disabled={!selectedProfileId}>
          Load Profile
        </button>
        {activeProfileData && (
          <button className="btn btn-danger" onClick={handleDeleteProfile} style={{ backgroundColor: '#dc3545', color: 'white', border: 'none' }}>
            Delete Profile
          </button>
        )}
      </div>

      <div className="hero-section">
        <h1>Meet Your Future Recruiter</h1>
        <p>I'm here to guide you to your next career opportunity.</p>
      </div>

<div className="card recruiter-info">
         <img
           src={activeProfileData.photoUrl || "https://via.placeholder.com/120/cccccc/ffffff?text=Photo"}
           alt="Recruiter"
           className="profile-photo"
         />
         <h2 className="recruiter-name">{activeProfileData.name}</h2>
         <p className="recruiter-title">{activeProfileData.title}</p>
         {(activeProfileData.teamLead === 'Yes' || activeProfileData.teamLead === 'yes' || activeProfileData.leadName) && (
           <div className="recruiter-lead-info" style={{ marginTop: '10px', fontSize: '0.9rem', color: '#555' }}>
             {activeProfileData.teamLead && <p style={{ margin: '2px 0' }}><strong>Team Lead:</strong> {activeProfileData.teamLead}</p>}
             {activeProfileData.leadName && <p style={{ margin: '2px 0' }}><strong>Lead Name:</strong> {activeProfileData.leadName}</p>}
           </div>
         )}

        <p className="bio">
          My passion is connecting talented people with career-defining roles. Let's work together to find your perfect fit and build a successful future.
        </p>
      </div>

      <div className="why-vdart">
        <h2>Why VDart is a Great Place to Work</h2>
        <div className="why-vdart-grid">
          <div className="reason">
            <div className="icon">&#x1F680;</div>
            <h3>Accelerated Growth</h3>
            <p>We invest in your career with continuous learning, mentorship, and clear paths to leadership.</p>
          </div>
          <div className="reason">
            <div className="icon">&#x1F91D;</div>
            <h3>Collaborative Culture</h3>
            <p>Be part of a supportive team where your ideas are valued and every voice is heard.</p>
          </div>
          <div className="reason">
            <div className="icon">&#x1F4A1;</div>
            <h3>Innovative Projects</h3>
            <p>Work on cutting-edge technology and solve challenging problems that make a real impact.</p>
          </div>
        </div>
      </div>

       {activeProfileData.reviews && activeProfileData.reviews.length > 0 && (
         <div className="testimonials">
           <h2>Hear from Placed Candidates</h2>
           <div className="testimonials-carousel">
             {activeProfileData.reviews.map((review, idx) => {

              const rating = parseFloat(review.rating) || 5;
              
              return (
                <div className="testimonial-card" key={idx}>
                  <div className="star-rating" style={{ display: 'flex', alignItems: 'center', marginBottom: '8px' }}>
                    {(() => {
                      let starCount = Math.round(Number(review.rating));
                      if (isNaN(starCount) || starCount < 1) starCount = 5;
                      if (starCount > 5) starCount = 5;
                      return (
                        <div style={{ display: 'flex', alignItems: 'center', color: '#FFD700', fontSize: '1.2rem' }}>
                          {'★'.repeat(starCount)}
                          <span style={{ color: '#ccc' }}>
                            {'☆'.repeat(5 - starCount)}
                          </span>
                        </div>
                      );
                    })()}
                    <span style={{ marginLeft: '8px', fontWeight: 'bold', color: '#555', fontSize: '0.95rem' }}>{rating.toFixed(1)}</span>
                  </div>
                  <p className="testimonial-text">"{review.text}"</p>
                  <p className="testimonial-author">- {review.author}</p>
                </div>
              );
            })}
          </div>
        </div>
      )}
{/*
      <div className="feedback-section">
        <h2>How Was Your Experience?</h2>
        <div className="feedback-buttons">
          <a href="#" className="feedback-button" onClick={(e) => e.preventDefault()}>
            <div className="icon feedback-up">&#x1F44D;</div>
            <div>Had a great experience!</div>
          </a>
          <a href="#" className="feedback-button" onClick={(e) => e.preventDefault()}>
            <div className="icon feedback-down">&#x1F44E;</div>
            <div>Something went wrong?</div>
          </a>
        </div>
      </div>
*/}
       <div className="cta-section">
         {activeProfileData.linkedin && (
           <a href={activeProfileData.linkedin} target="_blank" rel="noreferrer" className="cta-button cta-primary">
             Connect with Me on LinkedIn
           </a>
         )}

        <a href="https://www.vdart.com/careers" target="_blank" rel="noreferrer" className="cta-button cta-secondary">
          Explore VDart Careers
        </a>
      </div>
    </div>
  );
}
