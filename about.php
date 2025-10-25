<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="assets/images/logo.webp" alt="CROSS" class="logo-image">
                </div>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="about.php" class="nav-link active">About</a>
                    <a href="seminars.php" class="nav-link">Seminars</a>
                    <a href="announcements.php" class="nav-link">Announcements</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="certificates.php" class="nav-link">Certificates</a>
                        <a href="dashboard.php" class="nav-link">Dashboard</a>
                    <?php endif; ?>
                </div>
                <div class="user-menu">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?></div>
                        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="logout.php" class="btn btn-secondary">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary">Login</a>
                        <a href="register.php" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>About CROSS</h1>
                    <p>Learn about our mission, vision, and the dedicated team behind the Servus Amoris Foundation's scholarship programs.</p>
                    <div class="hero-buttons">
                        <a href="#mission" class="btn btn-gold">Our Mission</a>
                        <a href="#team" class="btn btn-secondary">Meet Our Team</a>
                    </div>
                </div>
                <div class="hero-visual">
                    <!-- Replaced with hero image -->
                    <img src="assets/images/platform.png" alt="About CROSS Hero" class="hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- About CROSS Section -->
    <section class="section">
        <div class="container">
            <div class="content-with-illustration">
                <div>
                    <h2 class="section-title">What is CROSS?</h2>
                    <div class="card">
                        <p style="font-size: 1.1rem; line-height: 1.8;">
                            <strong>CROSS</strong> (Care, Renewal, Outreach, Support & Sustainability) is an integrated web platform 
                            developed by the Servus Amoris Foundation to streamline scholarship management and provide comprehensive 
                            support to our scholars throughout their academic journey.
                        </p>
                        <p style="font-size: 1.1rem; line-height: 1.8; margin-top: 15px;">
                            Our platform combines technology with personalized care to ensure every scholar receives the guidance, 
                            resources, and opportunities they need to succeed academically and personally.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section id="mission" class="section" style="background-color: var(--gray);">
        <div class="container">
            <h2 class="section-title">Our Mission & Vision</h2>
            <div class="about-grid">
                <!-- Mission Card -->
                <div class="card mission-vision-card">
                    <img src="assets/mission.jpg" alt="Our Mission" class="mission-vision-image">
                    <h3>Our Mission</h3>
                    <p>
                        To provide comprehensive scholarship programs and personal development opportunities that empower students 
                        to achieve academic excellence and become responsible community leaders through innovative technology and 
                        personalized support systems.
                    </p>
                </div>

                <!-- Vision Card -->
                <div class="card mission-vision-card">
                    <img src="assets/vision.jpg" alt="Our Vision" class="mission-vision-image">
                    <h3>Our Vision</h3>
                    <p>
                        To create a sustainable community of scholars who exemplify the values of care, renewal, outreach, support, 
                        and sustainability, making positive impacts in their respective fields and communities while fostering 
                        lifelong learning and leadership.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Programs Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Our Programs</h2>
            <div class="about-grid">
                <!-- Scholarship Program -->
                <div class="card program-card">
                    <img src="assets/program-scholarship.jpg" alt="Scholarship Program" class="program-image">
                    <h3>Scholarship Program</h3>
                    <p>Comprehensive financial assistance for deserving students including tuition fees, allowance, and educational materials.</p>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <li>Full and partial scholarships</li>
                        <li>Academic performance-based</li>
                        <li>Financial need consideration</li>
                        <li>Renewable annually</li>
                    </ul>
                </div>

                <!-- Seminar Series -->
                <div class="card program-card">
                    <img src="assets/program-seminar.jpg" alt="Seminar Series" class="program-image">
                    <h3>Seminar Series</h3>
                    <p>Regular workshops and seminars focusing on personal and professional development for scholars.</p>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <li>Career development</li>
                        <li>Leadership training</li>
                        <li>Financial literacy</li>
                        <li>Mental health awareness</li>
                    </ul>
                </div>

                <!-- Mentorship Program -->
                <div class="card program-card">
                    <img src="assets/program-mentorship.jpg" alt="Mentorship Program" class="program-image">
                    <h3>Mentorship Program</h3>
                    <p>One-on-one guidance and support from industry professionals and alumni mentors.</p>
                    <ul style="margin-top: 15px; padding-left: 20px;">
                        <li>Career guidance</li>
                        <li>Academic support</li>
                        <li>Professional networking</li>
                        <li>Personal development</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="section" style="background-color: var(--gray);">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <p style="text-align: center; max-width: 600px; margin: 0 auto 50px; font-size: 1.1rem;">
                Dedicated professionals committed to supporting our scholars' journey towards academic and personal success.
            </p>
            
            <div class="team-grid">
                <!-- Team Member 1 -->
                <div class="team-card">
                    <img src="assets/team/quincy.jpg" alt="Quincy Clever Pernites" class="team-image">
                    <div class="team-info">
                        <h3>Quincy Clever Pernites</h3>
                        <p class="team-role">Team Leader/BackEnd Developer</p>
                        <p class="team-bio">Oversees all foundation operations and strategic direction.</p>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-card">
                    <img src="assets/team/christopher.jpg" alt="Christopher Solis" class="team-image">
                    <div class="team-info">
                        <h3>Christopher Solis</h3>
                        <p class="team-role">FrontEnd Developer</p>
                        <p class="team-bio">Manages scholarship programs and ensures effective implementation.</p>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-card">
                    <img src="assets/team/agustin.jpg" alt="Agustin Garcia" class="team-image">
                    <div class="team-info">
                        <h3>Agustin Garcia</h3>
                        <p class="team-role">Viewing Website</p>
                        <p class="team-bio">Provides direct support to scholars and manages communications.</p>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="team-card">
                    <img src="assets/team/rodfer.jpg" alt="RodFer Valdez" class="team-image">
                    <div class="team-info">
                        <h3>RodFer Valdez</h3>
                        <p class="team-role">Documentor</p>
                        <p class="team-bio">Oversees CROSS platform development and technical operations.</p>
                    </div>
                </div>

                <!-- Team Member 5 -->
                <div class="team-card">
                    <img src="assets/team/nicole.jpg" alt="Nicole Pengco" class="team-image">
                    <div class="team-info">
                        <h3>Nicole Pengco</h3>
                        <p class="team-role">Presentation</p>
                        <p class="team-bio">Manages finances, scholarship disbursements, and budgeting.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section">
        <div class="container">
            <div class="card" style="background: linear-gradient(135deg, var(--maroon), var(--gold)); color: white; text-align: center; padding: 60px 40px;">
                <h2 style="color: white; margin-bottom: 20px;">Join Our Scholarship Program</h2>
                <p style="font-size: 1.2rem; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Ready to start your journey with CROSS? Register now and become part of our growing community of scholars.
                </p>
                <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                    <a href="register.php" class="btn btn-gold">Apply for Scholarship</a>
                    <a href="login.php" class="btn" style="background-color: white; color: var(--maroon);">Scholar Login</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">CROSS</div>
                    <p>Care, Renewal, Outreach, Support & Sustainability - An initiative of Servus Amoris Foundation</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Apply</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Contact</h4>
                    <ul>
                        <li>Email: info@servusamoris.org</li>
                        <li>Phone: (02) 1234-5678</li>
                        <li>Address: Manila, Philippines</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Servus Amoris Foundation. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
