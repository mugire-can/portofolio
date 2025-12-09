<?php
// Portfolio Contact Form Handler
session_start();

$form_submitted = false;
$form_message = "";
$form_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $surname = htmlspecialchars(trim($_POST['surname'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Validation
    if (empty($name) || empty($surname) || empty($email) || empty($subject) || empty($message)) {
        $form_error = true;
        $form_message = "⚠️ All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $form_error = true;
        $form_message = "⚠️ Invalid email format!";
    } else {
        // Process the form (you can add email sending, database storage, etc.)
        $form_submitted = true;
        $form_message = "✅ Thank you, {$name}! Your message has been received. We'll get back to you soon.";
        
        // Log submission
        $log_entry = date('Y-m-d H:i:s') . " | {$name} {$surname} | {$email} | {$subject}\n";
        file_put_contents('submissions.log', $log_entry, FILE_APPEND);
        
        // Clear form data after success
        $name = $surname = $email = $subject = $message = "";
    }
}
?>
<!Doctype html>
<html lang="en"> 
<head>
    <link rel="stylesheet" href="assets/css/my_portfolio.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cybersecurity Portfolio | Mugire CAN</title>
    <script src="assets/js/my_portofolio.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <h1>🛡️ Cybersecurity Specialist</h1>
            <p class="tagline">Defending the Digital Frontier</p>
            <div class="header-buttons">
                <button class="btn-primary" onclick="downloadCV()">📥 Download CV</button>
                <button class="btn-secondary" onclick="copyEmailToClipboard()">📧 Copy Email</button>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#expertise">Expertise</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#certifications">Certifications</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    
    <main id="main">
        <section id="about">
            <h2>About Me</h2>
            <p>I'm a cybersecurity specialist passionate about protecting digital assets and building secure systems. With expertise in threat detection, vulnerability assessment, and security architecture, I work to safeguard organizations against evolving cyber threats.</p>
            <p>My approach combines technical proficiency with a strategic mindset—understanding not just how to defend, but why defense matters. I'm committed to continuous learning and staying ahead of emerging threats.</p>
        </section>
    </main>

    <!-- Expertise Section -->
    <section id="expertise">
        <h2>🔐 Core Expertise</h2>
        <div class="skills-grid">
            <div class="skill-card">
                <h3>Threat Detection & Response</h3>
                <ul>
                    <li>SIEM Tools (Splunk, ELK Stack)</li>
                    <li>Incident Response & Forensics</li>
                    <li>Intrusion Detection Systems</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Penetration Testing</h3>
                <ul>
                    <li>Web Application Security</li>
                    <li>Network Penetration Testing</li>
                    <li>Social Engineering Assessment</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Vulnerability Management</h3>
                <ul>
                    <li>Vulnerability Scanning & Analysis</li>
                    <li>Risk Assessment</li>
                    <li>Patch Management</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Security Infrastructure</h3>
                <ul>
                    <li>Firewalls & Network Security</li>
                    <li>Cloud Security (AWS, Azure)</li>
                    <li>Zero Trust Architecture</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Technical Skills</h3>
                <ul>
                    <li>Python & Bash Scripting</li>
                    <li>Linux/Windows Administration</li>
                    <li>Database Security</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Compliance & Governance</h3>
                <ul>
                    <li>GDPR, HIPAA, PCI-DSS</li>
                    <li>Security Policies & Standards</li>
                    <li>Audit & Documentation</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects">
        <h2>🚀 Featured Projects</h2>
        <div class="projects-container">
            <div class="project-card">
                <h3>Malware Analysis Lab</h3>
                <p>Developed an isolated environment for analyzing malware behavior, reverse engineering binaries, and documenting IOCs using IDA Pro and Wireshark.</p>
                <span class="tech-badge">Reverse Engineering</span>
                <span class="tech-badge">Malware Analysis</span>
            </div>
            <div class="project-card">
                <h3>Security Automation Framework</h3>
                <p>Created Python scripts to automate vulnerability scanning, log analysis, and threat intelligence gathering across enterprise infrastructure.</p>
                <span class="tech-badge">Python</span>
                <span class="tech-badge">Automation</span>
            </div>
            <div class="project-card">
                <h3>Network Intrusion Detection</h3>
                <p>Deployed and configured Snort/Suricata IDS with custom rule sets to detect and alert on suspicious network activity in real-time.</p>
                <span class="tech-badge">Snort</span>
                <span class="tech-badge">Network Security</span>
            </div>
        </div>
    </section>

    <!-- Certifications Section -->
    <section id="certifications">
        <h2>📜 Certifications & Training</h2>
        <div class="cert-list">
            <div class="cert-item">
                <h3>CompTIA Security+</h3>
                <p>Comprehensive security fundamentals and best practices</p>
            </div>
            <div class="cert-item">
                <h3>Certified Ethical Hacker (CEH)</h3>
                <p>Advanced penetration testing and ethical hacking techniques</p>
            </div>
            <div class="cert-item">
                <h3>Linux Professional Institute (LPIC-1)</h3>
                <p>Linux administration and security hardening</p>
            </div>
            <div class="cert-item">
                <h3>GIAC Security Essentials (GSEC)</h3>
                <p>In-depth security concepts and practical applications</p>
            </div>
        </div>
    </section>

    <!-- Experience & Education Section -->
    <section id="experience">
        <h2>💼 Experience & Education</h2>
        <div class="exp-edu-container">
            <div class="experience-section">
                <h3>Work Experience</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4>Cybersecurity Analyst</h4>
                            <p class="role-company">Your Company • 2024 - Present</p>
                            <p class="role-description">Monitoring and responding to security incidents, performing vulnerability assessments, and implementing security best practices.</p>
                            <ul class="achievements">
                                <li>Reduced incident response time by 40%</li>
                                <li>Identified and remediated 50+ vulnerabilities</li>
                                <li>Implemented SIEM solution for real-time threat detection</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="education-section">
                <h3>Education</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h4>Bachelor's in Cybersecurity</h4>
                            <p class="role-company">Your University • 2024</p>
                            <p class="role-description">Focus on network security, cryptography, and ethical hacking</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <h2>📬 Get In Touch</h2>
        <p style="color: #b0b0b0; margin-bottom: 20px;">Have a question or want to collaborate? Reach out to me!</p>
        
        <!-- Display Form Message -->
        <?php if ($form_submitted || $form_error): ?>
            <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; <?php echo $form_error ? 'background: rgba(255, 71, 87, 0.2); color: #ff4757;' : 'background: rgba(46, 213, 115, 0.2); color: #2ed573;'; ?>">
                <?php echo $form_message; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="post" class="contact-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">First Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Your first name" value="<?php echo htmlspecialchars($name ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="surname">Last Name:</label>
                    <input type="text" id="surname" name="surname" required placeholder="Your last name" value="<?php echo htmlspecialchars($surname ?? ''); ?>">
                </div>
            </div>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="your.email@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>">

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required placeholder="What is this about?" value="<?php echo htmlspecialchars($subject ?? ''); ?>">

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required placeholder="Tell me more about your inquiry..."><?php echo htmlspecialchars($message ?? ''); ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>All Rights Reserved © 2024</p>
    </footer>
</body>
</html>
