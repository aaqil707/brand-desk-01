/**
 * Entity-specific content for the LinkedIn Profile Generator.
 * Each entity (VDart, VDart Digital, TrustPeople) has its own
 * headlines, about sections, and experience descriptions.
 */

// ── Headlines ──

const vdartHeadlines = {
  'Senior Recruiter': [
    'Driving Success Through Talent | IT Recruitment Professional at VDart | Hiring for USA & Canada | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Bridging Talent & Opportunity | Senior Recruiter at VDart | Supporting USA, Canada & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Strategic Hiring for Business Growth | Senior Recruitment Specialist at VDart | Hiring for UAE & North America | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Your Trusted Hiring Partner | Senior Recruiter at VDart | Supporting Canada, USA & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Building High-Performance Teams | Recruitment Leader at VDart | Hiring for USA & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
  ],
  'Technical Recruiter': [
    'Tech Talent That Drives Innovation | Technical Recruiter at VDart | Hiring for USA & Canada | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Connecting Top IT Minds with Top Companies | IT Recruiter at VDart | USA & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Engineering the Future with the Right Talent | Tech Hiring Expert at VDart | Mexico & USA | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Building Smart Tech Teams | IT Recruitment Professional at VDart | Canada & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Precision Hiring for the Digital Age | Technical Recruiter at VDart | USA & North America | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
  ],
  'Delivery Manager & Team Lead': [
    'Leading Hiring Strategies for Global Growth | Delivery Manager at VDart | USA & Canada | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Scaling Teams with the Right Talent | Hiring Team Lead at VDart | UAE & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Strategic Workforce Planning & Talent Acquisition | Delivery Manager at VDart | USA & North America | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Your Recruitment Partner for Global Expansion | Team Lead at VDart | Canada & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Bringing Talent & Leadership Together | Hiring Lead at VDart | USA & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
  ],
};

const vdartDigitalHeadlines = {
  'Senior Recruiter': [
    'Driving Success Through Talent | IT Recruitment Professional at VDart Digital GCC | Hiring for USA & Canada | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Bridging Talent & Opportunity | Senior Recruiter at VDart Digital GCC | Supporting USA, Canada & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Strategic Hiring for Business Growth | Senior Recruitment Specialist at VDart Digital GCC | Hiring for UAE & North America | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Your Trusted Hiring Partner | Senior Recruiter at VDart Digital GCC | Supporting Canada, USA & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Building High-Performance Teams | Recruitment Leader at VDart Digital GCC | Hiring for USA & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
  ],
  'Technical Recruiter': [
    'Tech Talent That Drives Innovation | Technical Recruiter at VDart Digital GCC | Hiring for USA & Canada | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Connecting Top IT Minds with Top Companies | IT Recruiter at VDart Digital GCC | USA & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Engineering the Future with the Right Talent | Tech Hiring Expert at VDart Digital GCC | Mexico & USA | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Building Smart Tech Teams | IT Recruitment Professional at VDart Digital GCC | Canada & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Precision Hiring for the Digital Age | Technical Recruiter at VDart Digital GCC | USA & North America | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
  ],
  'Delivery Manager & Team Lead': [
    'Leading Hiring Strategies for Global Growth | Delivery Manager at VDart Digital GCC | USA & Canada | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Scaling Teams with the Right Talent | Hiring Team Lead at VDart Digital GCC | UAE & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Strategic Workforce Planning & Talent Acquisition | Delivery Manager at VDart Digital GCC | USA & North America | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Your Recruitment Partner for Global Expansion | Team Lead at VDart Digital GCC | Canada & UAE | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
    'Bringing Talent & Leadership Together | Hiring Lead at VDart Digital GCC | USA & Mexico | HQ: Alpharetta, GA | Offshore Operations: Tiruchirapalli',
  ],
};

const trustpeopleHeadlines = {
  'Senior Recruiter': [
    'Connecting Global Talent with Opportunity | Senior Recruiter at TrustPeople | Hiring for USA & Canada | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Bridging Talent and Innovation | Senior Recruitment Specialist | Supporting USA, Canada & Europe | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Your Trusted Hiring Partner | Empowering Companies with Top Talent in North America & Asia | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Driving Business Success Through Strategic Hiring | Senior Recruiter | Global Workforce Solutions | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Unlocking Growth with Smart Recruitment | Senior Talent Acquisition | Global Reach, Local Expertise | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
  ],
  'Technical Recruiter': [
    'Powering the Digital Workforce | IT Recruiter | Hiring for USA & Canada | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Bridging the Tech Talent Gap | Technical Recruitment Expert | Supporting UAE & North America | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Precision Hiring for the Digital Future | IT & Engineering Talent Solutions | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Recruiting for Tomorrow\'s Innovations | Tech Hiring Lead | Hiring for Global Enterprises | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Driving Innovation with Exceptional Tech Talent | Global IT Recruitment Experts | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
  ],
  'Delivery Manager & Team Lead': [
    'Scaling Businesses with Global Talent | Delivery Manager | Hiring for USA & Canada | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Strategic Workforce Planning & Talent Acquisition | Optimizing Hiring for Business Success | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Shaping High-Performing Teams | Hiring Lead | USA, Canada & UAE | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Building Workforce Resilience with Smart Hiring | Delivery Manager | Global Operations | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
    'Your Recruitment Partner for Enterprise Growth | Talent Acquisition Excellence Worldwide | HQ: Alpharetta, Georgia | Offshore Operations: Tiruchirapalli',
  ],
};

// ── About Sections ──

const fresherAbouts = [
  { label: 'Learning & Growth-Focused', value: 'As a fresher, stepping into the professional world is both exciting and full of opportunities. I am eager to learn, grow, and adapt in a fast-paced environment while building skills that will shape my career. At {company}, I am surrounded by a team that encourages learning and challenges me to improve every day.\nLooking forward to gaining valuable experience, developing new skills, and making a meaningful impact!' },
  { label: 'People & Connection-Oriented', value: 'Starting my career at {company} is an incredible opportunity to grow both personally and professionally. I have always been passionate about building strong relationships, understanding different perspectives, and working collaboratively. As a fresher, I am excited to develop my skills, take on new challenges, and learn from those around me.\nExcited to connect with professionals, exchange ideas, and contribute in meaningful ways!' },
  { label: 'Curious & Problem-Solving Mindset', value: 'Being a fresher means having endless possibilities to explore. I am passionate about solving problems, thinking critically, and adapting to new challenges. Joining {company} has given me a chance to work in a dynamic environment where I can develop my skills, gain industry insights, and grow professionally.\nEager to take on new experiences, learn continuously, and make a difference!' },
  { label: 'Passion & Ambition-Driven', value: 'Starting my journey as a fresher, I believe that learning and taking initiative are the keys to success. I am motivated to push my limits, take on challenges, and contribute positively wherever I can. {company} provides the perfect environment to gain hands-on experience and work with talented professionals who inspire growth. Excited for this journey and looking forward to shaping my career with every new opportunity!' },
  { label: 'Motivational & Future-Oriented', value: 'Every career starts with a single step, and for me, that step is filled with excitement, curiosity, and ambition. As a fresher, I am eager to explore different opportunities, sharpen my skills, and build a strong foundation for the future. At {company}, I look forward to learning from experienced professionals and contributing to a dynamic workplace.\nGrateful for this opportunity and excited about what\'s ahead!' },
];

const experiencedAbouts = [
  { label: 'People-Centered & Relationship-Focused', value: 'Great recruitment isn\'t just about filling roles—it\'s about connecting people with opportunities that align with their skills, ambitions, and aspirations. My passion lies in building strong relationships and helping professionals take the next step in their careers.\nAt {company}, I work closely with talented individuals, matching them with the right opportunities while ensuring a seamless experience throughout the hiring process. Understanding people\'s career goals and guiding them toward success is what drives me every day.\nBefore joining {company}, I worked at [Previous Company Name], where I developed expertise in talent acquisition, candidate engagement, and recruitment strategies. Every interaction strengthens my ability to make meaningful connections in the industry.' },
  { label: 'Results-Oriented & Strategic', value: 'Recruitment is more than just a process—it\'s about finding the right talent to drive business success. I thrive in fast-paced environments where I can leverage my skills to identify top talent and ensure a smooth hiring experience.\nAt {company}, I specialize in sourcing and placing skilled professionals across various industries. My approach combines market insights, strategic hiring, and a deep understanding of candidates\' career goals to create lasting connections.\nPreviously, at [Previous Company Name], I gained hands-on experience in workforce planning, talent sourcing, and employer branding. With each step, I refine my ability to align business needs with exceptional talent.' },
  { label: 'Passion for Talent & Growth', value: 'A company\'s success starts with the right people, and I\'m passionate about finding the perfect match between talent and opportunity. My goal is to not just fill positions but to help professionals build meaningful careers while supporting business growth.\nAt {company}, I focus on understanding candidates beyond their resumes, ensuring they find roles that truly align with their expertise and aspirations. By fostering connections, I help create impactful career paths for job seekers.\nBefore {company}, I worked at [Previous Company Name], where I built strong foundations in recruitment, talent engagement, and career coaching. Every experience has reinforced my commitment to making hiring a positive and fulfilling journey for all.' },
  { label: 'Innovation & Technology-Driven Approach', value: 'The recruitment industry is constantly evolving, and leveraging technology is key to staying ahead. I believe in combining innovation with a human touch to create an efficient and engaging hiring experience for both candidates and employers.\nAt {company}, I embrace data-driven hiring strategies and advanced sourcing techniques to connect top talent with the right opportunities. My role allows me to stay at the forefront of recruitment trends while ensuring candidates have the best experience.\nPrior to {company}, I was with [Previous Company Name], where I gained experience in applicant tracking systems, AI-driven recruitment, and process optimization. Every day, I strive to refine my methods and bring value to both job seekers and businesses.' },
  { label: 'Candidate Experience & Employer Branding Focus', value: 'A great hiring experience is more than just an offer—it\'s about how candidates feel throughout the process. I am passionate about ensuring every professional I interact with feels valued, informed, and supported in their career journey.\nAt {company}, I prioritize transparent communication, timely updates, and a candidate-first approach. Whether it\'s guiding job seekers through the hiring process or representing our employer brand, I strive to make recruitment a smooth and rewarding experience.\nBefore joining {company}, I worked at [Previous Company Name], where I focused on employer branding, recruitment marketing, and enhancing candidate touchpoints. Every interaction is a chance to create a lasting positive impression.' },
];

const otherAbouts = [
  { label: 'Growth & Expertise-Focused', value: 'Growth happens when we step outside our comfort zones. Throughout my journey in [industry/domain], I\'ve taken on challenges that have sharpened my ability to think critically, solve problems, and adapt to new situations.\nAt {company}, I am part of a team that values continuous learning and collaboration. My role allows me to apply my expertise in [mention specific function] while contributing to projects that make a real impact.\nBefore joining {company}, I worked at [Previous Company Name], where I gained experience in [mention key responsibilities/achievements]. Every step of my career has helped me grow, and I look forward to new opportunities that push me further.' },
  { label: 'People & Leadership-Oriented', value: 'A successful career is built on strong relationships and meaningful contributions. Over the years, I\'ve had the opportunity to work with talented teams, lead projects, and collaborate on solutions that drive success.\nAt {company}, I work alongside professionals who inspire me to grow and think differently. The opportunity to contribute my skills in [mention key area] while learning from those around me makes my work truly fulfilling.\nPreviously, I was with [Previous Company Name], where I built expertise in [mention key responsibilities]. My career has always been about learning, mentoring, and making a meaningful impact in my field.' },
  { label: 'Problem-Solving & Innovation-Focused', value: 'Every challenge presents a chance to think differently. I\'ve always been drawn to problem-solving—finding solutions, improving processes, and creating value. My experience in [industry/domain] has helped me develop a strong analytical mindset and a proactive approach to tackling obstacles.\nAt {company}, I embrace challenges that require innovative thinking and a proactive approach. Every experience here helps me strengthen my problem-solving abilities while contributing to impactful solutions.\nBefore joining {company}, I worked with [Previous Company Name], where I developed expertise in [mention key responsibilities]. Each experience has shaped my ability to innovate and drive results.' },
  { label: 'Passion & Impact-Driven', value: 'Passion is the key to making a difference. I thrive in environments where I can contribute my skills, take on challenges, and help create meaningful solutions. My career has been built on a strong foundation of curiosity, adaptability, and a desire to grow.\nAt {company}, I get to be part of an organization that values learning, collaboration, and impact. Here, I continue to develop my expertise in [mention function/industry] while working alongside some of the best in the field.\nBefore joining {company}, I was with [Previous Company Name], where I gained valuable experience in [mention key responsibilities]. I look forward to the next steps in my career, embracing new challenges that push me further.' },
  { label: 'Motivational & Future-Oriented', value: 'A career is a journey, not a destination. My experiences in [industry/domain] have taught me that adaptability, persistence, and continuous learning are key to long-term success.\nBeing at {company} allows me to put my skills to use in a collaborative and forward-thinking environment. Every project I work on strengthens my expertise and helps me contribute to something greater.\nBefore {company}, I worked at [Previous Company Name], where I gained experience in [mention key areas]. I am always looking for new opportunities to learn, grow, and make an impact in everything I do.' },
];

// ── Experience Descriptions ──

const experienceDescriptions = {
  vdart: {
    companyName: 'VDart',
    companyFullName: 'VDart · Full-time',
    logoUrl: 'https://github.com/Saranraj102000/VDart-images/blob/main/VDart_Logo.png?raw=true',
    description: 'VDart Inc. is a global IT services and staffing company headquartered in Alpharetta, Georgia. We are a certified minority-owned business specializing in technology consulting, digital transformation, and IT workforce solutions across North America, Asia, and the Middle East.\nAs a key player in the staffing and services industry, VDart has been recognized among the fastest-growing private companies in the US. Our commitment to innovation and talent excellence drives our mission to bridge the gap between businesses and technology professionals.\nExplore our latest awards and recognitions: https://www.vdartdigital.com/awards/',
  },
  'vdart-digital': {
    companyName: 'VDart Digital',
    companyFullName: 'VDart Digital · Full-time',
    logoUrl: 'http://vdpl.co/dnimg/VDart_Digital_Blue_Logo.png',
    description: 'VDart Digital is a technology and innovation-led company that empowers global enterprises to accelerate digital transformation and drive intelligent enterprise outcomes.\nWe help businesses reimagine their digital core through our strategic solutions in cloud, cybersecurity, data analytics, enterprise platforms, and managed services, creating scalable impact and future-ready operations.\nAs part of the VDart Group, we combine deep industry expertise with a global delivery model and a proven track record as an award-winning technology partner. Our success is defined by the value we create for our clients, teams, and communities, fueling innovation at scale.\nExplore our latest awards and recognitions: https://www.vdartdigital.com/awards/',
  },
  trustpeople: {
    companyName: 'Trustpeople',
    companyFullName: 'Trustpeople · Full-time',
    logoUrl: 'https://github.com/Saranraj102000/VDart-images/blob/main/Trustpeople.png?raw=true',
    description: 'Trustpeople stands as a leader in comprehensive workforce solutions, specializing in non-IT recruitment across all organizational levels. Our expertise extends beyond traditional customer service to encompass administrative, operational, clinical, and specialized roles. We serve diverse industries including BFSI, Healthcare, Technology, and Government sectors. With a global workforce exceeding 3,500 professionals, we created 18,000 jobs across 115 countries. As a minority and Asian-owned company, we connect exceptional talent with Fortune 100 clients through our Hire-Train-Deploy model. We believe meaningful employment transforms communities, driving our commitment to empowering individuals while advancing purposeful business that respects our planets boundaries.',
  },
};

// ── Build Entity Config ──

function buildAboutSections(companyName) {
  const replace = (items) =>
    items.map((item) => ({
      ...item,
      value: item.value.replace(/\{company\}/g, companyName),
    }));

  return {
    'Templates for Freshers': replace(fresherAbouts),
    'Templates for Experienced Professionals': replace(experiencedAbouts),
    Others: replace(otherAbouts),
  };
}

export const ENTITY_CONTENT = {
  vdart: {
    headlines: vdartHeadlines,
    aboutSections: buildAboutSections('VDart'),
    experience: experienceDescriptions.vdart,
    location: 'Tiruchirapalli, Tamil Nadu, India',
  },
  'vdart-digital': {
    headlines: vdartDigitalHeadlines,
    aboutSections: buildAboutSections('VDart Digital'),
    experience: experienceDescriptions['vdart-digital'],
    location: 'Tiruchirapalli, Tamil Nadu, India',
  },
  trustpeople: {
    headlines: trustpeopleHeadlines,
    aboutSections: buildAboutSections('VDart'),
    experience: experienceDescriptions.trustpeople,
    location: 'Tiruchirapalli, Tamil Nadu, India',
  },
};
