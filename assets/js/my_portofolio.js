// ============================================
// CYBERSECURITY PORTFOLIO - PERSONAL DATA
// ============================================

// Personal Information (From CV: Mugire CAN)
const portfolioData = {
  personal: {
    firstName: "Mugire",
    lastName: "CAN",
    title: "Cybersecurity Specialist",
    tagline: "Defending the Digital Frontier",
    email: "mugirecan@gmail.com",
    phone: "+1 (XXX) XXX-XXXX",
    location: "Your Location",
    cvFile: "Mugire CAN CV.pdf"
  },

  skills: {
    threatDetection: [
      "SIEM Tools (Splunk, ELK Stack)",
      "Incident Response & Forensics",
      "Intrusion Detection Systems"
    ],
    penetrationTesting: [
      "Web Application Security",
      "Network Penetration Testing",
      "Social Engineering Assessment"
    ],
    vulnerabilityManagement: [
      "Vulnerability Scanning & Analysis",
      "Risk Assessment",
      "Patch Management"
    ],
    securityInfrastructure: [
      "Firewalls & Network Security",
      "Cloud Security (AWS, Azure)",
      "Zero Trust Architecture"
    ],
    technicalSkills: [
      "Python & Bash Scripting",
      "Linux/Windows Administration",
      "Database Security"
    ],
    compliance: [
      "GDPR, HIPAA, PCI-DSS",
      "Security Policies & Standards",
      "Audit & Documentation"
    ]
  },

  projects: [
    {
      id: 1,
      title: "Malware Analysis Lab",
      description: "Developed an isolated environment for analyzing malware behavior, reverse engineering binaries, and documenting IOCs using IDA Pro and Wireshark.",
      tags: ["Reverse Engineering", "Malware Analysis"],
      link: "#", // Update with GitHub link
      technologies: ["IDA Pro", "Wireshark", "Python"]
    },
    {
      id: 2,
      title: "Security Automation Framework",
      description: "Created Python scripts to automate vulnerability scanning, log analysis, and threat intelligence gathering across enterprise infrastructure.",
      tags: ["Python", "Automation"],
      link: "#", // Update with GitHub link
      technologies: ["Python", "Splunk", "Bash"]
    },
    {
      id: 3,
      title: "Network Intrusion Detection",
      description: "Deployed and configured Snort/Suricata IDS with custom rule sets to detect and alert on suspicious network activity in real-time.",
      tags: ["Snort", "Network Security"],
      link: "#", // Update with GitHub link
      technologies: ["Snort", "Suricata", "Tcpdump"]
    }
  ],

  certifications: [
    {
      id: 1,
      title: "CompTIA Security+",
      description: "Comprehensive security fundamentals and best practices",
      year: 2024, // Update with your year
      status: "In Progress" // Change to "Certified" when earned
    },
    {
      id: 2,
      title: "Certified Ethical Hacker (CEH)",
      description: "Advanced penetration testing and ethical hacking techniques",
      year: 2024,
      status: "In Progress"
    },
    {
      id: 3,
      title: "Linux Professional Institute (LPIC-1)",
      description: "Linux administration and security hardening",
      year: 2024,
      status: "In Progress"
    },
    {
      id: 4,
      title: "GIAC Security Essentials (GSEC)",
      description: "In-depth security concepts and practical applications",
      year: 2024,
      status: "In Progress"
    }
  ],

  experience: [
    {
      id: 1,
      role: "Cybersecurity Analyst",
      company: "Current Position",
      duration: "2024 - Present",
      description: "Monitoring and responding to security incidents, performing vulnerability assessments, and implementing security best practices.",
      achievements: [
        "Reduced incident response time by 40%",
        "Identified and remediated 50+ vulnerabilities",
        "Implemented SIEM solution for real-time threat detection"
      ]
    }
  ],

  education: [
    {
      id: 1,
      degree: "Bachelor's in Cybersecurity",
      institution: "University",
      year: 2024,
      details: "Focus on network security, cryptography, and ethical hacking"
    }
  ]
};

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Initialize portfolio with personal data
function initializePortfolio() {
  updatePersonalInfo();
  setupScrollAnimations();
  console.log("✓ Portfolio initialized with data");
}

// Update personal information in the DOM
function updatePersonalInfo() {
  const { firstName, lastName, email, phone, location } = portfolioData.personal;
  
  // Update header
  const headerTitle = document.querySelector("header h1");
  if (headerTitle) {
    headerTitle.textContent = `🛡️ ${firstName} ${lastName}`;
  }

  // Log personal info to console
  console.log(`Name: ${firstName} ${lastName}`);
  console.log(`Email: ${email}`);
  console.log(`Phone: ${phone}`);
  console.log(`Location: ${location}`);
}

// Download CV
function downloadCV() {
  const cvFile = portfolioData.personal.cvFile;
  const link = document.createElement("a");
  link.href = cvFile;
  link.download = cvFile;
  link.click();
  console.log(`📥 Downloading CV: ${cvFile}`);
}

// Smooth scroll to section
function smoothScrollToSection(sectionId) {
  const section = document.getElementById(sectionId);
  if (section) {
    section.scrollIntoView({ behavior: "smooth" });
  }
}

// Copy email to clipboard
function copyEmailToClipboard() {
  const email = portfolioData.personal.email;
  navigator.clipboard.writeText(email).then(() => {
    showToast(`📧 Email copied: ${email}`);
  });
}

// ============================================
// SCROLL ANIMATIONS & VIBE CODING
// ============================================

// Intersection Observer for scroll animations
function setupScrollAnimations() {
  const options = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        observer.unobserve(entry.target);
      }
    });
  }, options);

  // Observe all elements with animation classes
  document.querySelectorAll('section, .skill-card, .project-card, .cert-item').forEach(el => {
    observer.observe(el);
  });
}

// Toast notification system
function showToast(message, duration = 3000) {
  const toast = document.createElement('div');
  toast.textContent = message;
  toast.style.cssText = `
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: rgba(0, 212, 255, 0.2);
    border: 1px solid rgba(0, 212, 255, 0.5);
    color: #00d4ff;
    padding: 15px 25px;
    border-radius: 8px;
    z-index: 9999;
    animation: slideInLeft 0.4s ease forwards;
    font-weight: 600;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
  `;
  
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.style.animation = 'slideInLeft 0.4s ease forwards reverse';
    setTimeout(() => toast.remove(), 400);
  }, duration);
}

// Get total projects count
function getProjectCount() {
  return portfolioData.projects.length;
}

// Get total certifications count
function getCertificationCount() {
  return portfolioData.certifications.length;
}

// Get skill by category
function getSkillsByCategory(category) {
  return portfolioData.skills[category] || [];
}

// Filter projects by tag
function filterProjectsByTag(tag) {
  return portfolioData.projects.filter(project => 
    project.tags.includes(tag)
  );
}

// Display all projects (could be used for dynamic rendering)
function displayProjects() {
  const projects = portfolioData.projects;
  console.log(`📊 Total Projects: ${projects.length}`);
  projects.forEach(project => {
    console.log(`- ${project.title}: ${project.description.substring(0, 50)}...`);
  });
}

// Get contact info
function getContactInfo() {
  return {
    email: portfolioData.personal.email,
    phone: portfolioData.personal.phone,
    location: portfolioData.personal.location
  };
}

// Format experience for display
function formatExperience() {
  return portfolioData.experience.map(exp => ({
    role: exp.role,
    company: exp.company,
    duration: exp.duration
  }));
}

// ============================================
// ANALYTICS & TRACKING
// ============================================

// Track section views
function trackSectionView(sectionName) {
  console.log(`👁️ Viewed section: ${sectionName} at ${new Date().toLocaleTimeString()}`);
}

// Track CV downloads
let cvDownloadCount = 0;
function trackCVDownload() {
  cvDownloadCount++;
  console.log(`📊 CV Downloads: ${cvDownloadCount}`);
}

// ============================================
// EVENT LISTENERS
// ============================================

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  initializePortfolio();
  setupEventListeners();
  console.log("🚀 Portfolio ready!");
});

// Setup event listeners
function setupEventListeners() {
  // Track navigation clicks
  const navLinks = document.querySelectorAll("nav a");
  navLinks.forEach(link => {
    link.addEventListener("click", (e) => {
      const sectionId = link.getAttribute("href").substring(1);
      trackSectionView(sectionId);
      smoothScrollToSection(sectionId);
    });
  });

  // Track form submission
  const contactForm = document.querySelector(".contact-form");
  if (contactForm) {
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(contactForm);
      console.log(`📨 Form submitted from: ${formData.get("email")}`);
      showToast(`✅ Message sent! We'll get back to you soon.`);
      setTimeout(() => contactForm.reset(), 500);
    });
  }

  // Add ripple effect to buttons
  document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
    button.addEventListener('click', (e) => {
      const ripple = button.querySelector('::before');
      if (ripple) {
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
      }
    });
  });
}

// ============================================
// EXPORT DATA
// ============================================

// Display summary statistics
function displayPortfolioSummary() {
  console.log("=".repeat(50));
  console.log("📋 PORTFOLIO SUMMARY");
  console.log("=".repeat(50));
  console.log(`👤 Name: ${portfolioData.personal.firstName} ${portfolioData.personal.lastName}`);
  console.log(`💼 Title: ${portfolioData.personal.title}`);
  console.log(`🎯 Projects: ${getProjectCount()}`);
  console.log(`📜 Certifications: ${getCertificationCount()}`);
  console.log(`🏢 Experience: ${portfolioData.experience.length} role(s)`);
  console.log(`🎓 Education: ${portfolioData.education.length} degree(s)`);
  console.log("=".repeat(50));
}

// Display on page load
displayPortfolioSummary();

// ============================================
// GAME STATS INTEGRATION
// ============================================

function loadGameStatsToPortfolio() {
    const scores = getGameScores();
    if (scores.length === 0) {
        document.getElementById('portfolio-leaderboard').innerHTML = '<p style="color: #b0b0b0; text-align: center; padding: 20px;">No game scores yet. <a href="cyber_hacker_game.html" target="_blank" style="color: #00d4ff; text-decoration: underline;">Play the game!</a></p>';
        return;
    }

    // Group scores by player
    const grouped = {};
    scores.forEach(s => {
        if (!grouped[s.username]) {
            grouped[s.username] = { total: 0, games: 0, best: 0 };
        }
        grouped[s.username].total += s.score;
        grouped[s.username].games++;
        grouped[s.username].best = Math.max(grouped[s.username].best, s.score);
    });

    // Sort and get top 20
    const board = Object.entries(grouped)
        .map(([name, stats]) => ({ name, ...stats }))
        .sort((a, b) => b.total - a.total)
        .slice(0, 20);

    // Build table
    let html = '<table class="leaderboard-table"><tr><th class="rank">#</th><th>Player</th><th class="score">Total Score</th><th>Games</th><th>Best</th></tr>';
    board.forEach((p, i) => {
        html += `<tr><td class="rank">${i+1}</td><td>${p.name}</td><td class="score">${p.total}</td><td>${p.games}</td><td>${p.best}</td></tr>`;
    });
    html += '</table>';
    document.getElementById('portfolio-leaderboard').innerHTML = html;
}

// Get game scores from localStorage (same as game)
function getGameScores() {
    try {
        const scores = localStorage.getItem('gameScores');
        return scores ? JSON.parse(scores) : [];
    } catch {
        return [];
    }
}

// Load stats on portfolio page load
document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById('portfolio-leaderboard')) {
        setTimeout(loadGameStatsToPortfolio, 500);
    }
});
