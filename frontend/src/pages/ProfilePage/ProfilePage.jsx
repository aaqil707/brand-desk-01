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
        // Guard: Silently ignore 401/unauthorized for public visitors
        if (err.status !== 401) {
          console.error('Error fetching profiles:', err);
        }
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
  }, [selectedProfileId]); // re-fetch when the selected profile changes

  useEffect(() => {
    if (!selectedProfileId) return;

    const intervalId = setInterval(async () => {
      try {
        const response = await profileApi.getProfile(selectedProfileId);
        if (response.success && response.data) {
          setActiveProfileData(prev =>
            JSON.stringify(prev) === JSON.stringify(response.data) ? prev : response.data
          );
        }
      } catch (err) {
        console.error('Polling error:', err);
      }
    }, 5000);

    return () => clearInterval(intervalId);
  }, [selectedProfileId]);

  const handleLoadProfile = async () => {
    if (!selectedProfileId) return;
    await fetchProfile(selectedProfileId);
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
          {activeProfileData.phone && (
            <p className="recruiter-phone" style={{ margin: '4px 0', color: '#444', fontSize: '0.95rem' }}>
              <a href={`tel:${activeProfileData.phone}`} style={{ color: 'inherit', textDecoration: 'none' }}>
                📞 {activeProfileData.phone}
              </a>
            </p>
          )}
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
