import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { profileApi } from '../../api/client';
import './ProfilePage.css';

export default function ProfilePage() {
  const { id } = useParams();
  const [profile, setProfile] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const response = await profileApi.getProfile(id);
        if (response.success && response.data) {
          setProfile(response.data);
        } else {
          setError(response.message || 'Profile not found');
        }
      } catch (err) {
        setError('Failed to load profile');
      } finally {
        setLoading(false);
      }
    };
    fetchProfile();
  }, [id]);

  if (loading) {
    return (
      <div className="profile-page-loading">
        <div className="spinner" style={{ width: 40, height: 40, margin: '0 auto 16px' }} />
        <p>Loading Profile...</p>
      </div>
    );
  }

  if (error || !profile) {
    return (
      <div className="profile-page-error">
        <h2>Oops!</h2>
        <p>{error || 'Profile not found.'}</p>
      </div>
    );
  }

  // Parse YouTube or Vimeo URL to embed
  const renderVideo = (videoUrl) => {
    if (!videoUrl) return null;
    let embedUrl = videoUrl;
    if (videoUrl.includes('youtube.com/watch?v=')) {
      embedUrl = videoUrl.replace('watch?v=', 'embed/');
    } else if (videoUrl.includes('youtu.be/')) {
      embedUrl = videoUrl.replace('youtu.be/', 'youtube.com/embed/');
    }

    return (
      <div className="video-container">
        <iframe
          src={embedUrl}
          title="Personal Video Message"
          frameBorder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowFullScreen
        ></iframe>
      </div>
    );
  };

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
        {profile.videoUrl ? renderVideo(profile.videoUrl) : (
           <div className="video-container">
              <img src="https://via.placeholder.com/1280x720/cccccc/ffffff?text=No+Video+Provided" alt="Video Placeholder" />
           </div>
        )}
      </div>

      <div className="card recruiter-info">
        <img
          src={profile.photoUrl || "https://via.placeholder.com/120/cccccc/ffffff?text=Photo"}
          alt="Recruiter"
          className="profile-photo"
        />
        <h2 className="recruiter-name">{profile.name}</h2>
        <p className="recruiter-title">{profile.title}</p>
        {(profile.teamLead === 'Yes' || profile.teamLead === 'yes' || profile.leadName) && (
          <div className="recruiter-lead-info" style={{ marginTop: '10px', fontSize: '0.9rem', color: '#555' }}>
            {profile.teamLead && <p style={{ margin: '2px 0' }}><strong>Team Lead:</strong> {profile.teamLead}</p>}
            {profile.leadName && <p style={{ margin: '2px 0' }}><strong>Lead Name:</strong> {profile.leadName}</p>}
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

      {profile.reviews && profile.reviews.length > 0 && (
        <div className="testimonials">
          <h2>Hear from Placed Candidates</h2>
          <div className="testimonials-carousel">
            {profile.reviews.map((review, idx) => {
              const rating = parseFloat(review.rating) || 5;
              
              return (
                <div className="testimonial-card" key={idx}>
                  <div className="star-rating" style={{ display: 'flex', alignItems: 'center', gap: '4px', marginBottom: '8px', color: '#FFD700' }}>
                    {(() => {
                      let rating = Number(review.rating);
                      if (isNaN(rating) || rating < 1) rating = 5;
                      if (rating > 5) rating = 5;

                      const fullStars = Math.floor(rating);
                      const hasHalfStar = rating % 1 !== 0;
                      const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

                      return (
                        <>
                          {[...Array(fullStars)].map((_, i) => (
                            <svg key={`full-${i}`} width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.856 1.4-8.168L.132 9.21l8.2-1.192L12 .587z"/>
                            </svg>
                          ))}

                          {hasHalfStar && (
                            <svg key="half" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                              <defs>
                                <linearGradient id="halfGrad">
                                  <stop offset="50%" stopColor="currentColor" />
                                  <stop offset="50%" stopColor="#ccc" />
                                </linearGradient>
                              </defs>
                              <path fill="url(#halfGrad)" d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.856 1.4-8.168L.132 9.21l8.2-1.192L12 .587z"/>
                            </svg>
                          )}

                          {[...Array(emptyStars)].map((_, i) => (
                            <svg key={`empty-${i}`} width="20" height="20" fill="#ccc" viewBox="0 0 24 24">
                              <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.856 1.4-8.168L.132 9.21l8.2-1.192L12 .587z"/>
                            </svg>
                          ))}

                          <span style={{ marginLeft: '8px', fontSize: '0.9rem', fontWeight: 'bold', color: '#555' }}>
                            {rating}/5
                          </span>
                        </>
                      );
                    })()}
                  </div>
                  <p className="testimonial-text">"{review.text}"</p>
                  <p className="testimonial-author">- {review.author}</p>
                </div>
              );
            })}
          </div>
        </div>
      )}

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

      <div className="cta-section">
        {profile.linkedin && (
          <a href={profile.linkedin} target="_blank" rel="noreferrer" className="cta-button cta-primary">
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
