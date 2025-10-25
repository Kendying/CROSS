<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CROSS - Servus Amoris Foundation</title>
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
                    <a href="about.php" class="nav-link">About</a>
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
<section class="hero fade-in">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Care, Renewal, Outreach, Support & Sustainability</h1>
                <p>An Integrated Web Platform for Servus Amoris Foundation's Programs and Scholarships</p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-gold">Apply for Scholarship</a>
                    <a href="login.php" class="btn btn-secondary">Login to Dashboard</a>
                </div>
            </div>
            <div class="hero-visual">
                <!-- Remove the placeholder-illustration div and put image directly -->
                <img src="assets/images/servusamoris.png" alt="Servus Amoris Foundation" class="hero-image">
            </div>
        </div>
    </div>
</section>

    <!-- Statistics Section -->
    <section class="section" style="background-color: var(--white);">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>500+</h3>
                    <p>Scholars Supported</p>
                </div>
                <div class="stat-card">
                    <h3>50+</h3>
                    <p>Seminars Conducted</p>
                </div>
                <div class="stat-card">
                    <h3>95%</h3>
                    <p>Graduation Rate</p>
                </div>
                <div class="stat-card">
                    <h3>15+</h3>
                    <p>Partner Schools</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About CROSS Section -->
    <section id="about" class="section">
        <div class="container">
            <h2 class="section-title">What is CROSS?</h2>
            <div class="content-with-illustration">
                <div>
                    <div class="card">
                        <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px;">
                            <strong>CROSS</strong> (Care, Renewal, Outreach, Support & Sustainability) is an innovative web platform 
                            designed to transform the scholarship experience for students under the Servus Amoris Foundation.
                        </p>
                        <p style="font-size: 1.1rem; line-height: 1.8;">
                            Our platform integrates technology with personalized support to create a comprehensive ecosystem 
                            that nurtures academic excellence, personal growth, and community engagement.
                        </p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
                        <div class="feature-item">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--maroon), var(--gold)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                                <span style="color: white; font-size: 1.2rem;">🎓</span>
                            </div>
                            <h4>Easy Application</h4>
                            <p>Streamlined scholarship application process</p>
                        </div>
                        <div class="feature-item">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--gold), var(--maroon)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                                <span style="color: white; font-size: 1.2rem;">📊</span>
                            </div>
                            <h4>Progress Tracking</h4>
                            <p>Monitor academic and seminar progress</p>
                        </div>
                    </div>
                </div>
                <div class="illustration-container">
                        <img src="assets/images/features.png" alt="CROSS Platform Features" class="hero-image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="section" style="background-color: var(--gray);">
        <div class="container">
            <h2 class="section-title">Our Core Values</h2>
            <div class="about-grid">
                <div class="card about-card fade-in">
                    <div class="about-icon">C</div>
                    <h3>Care</h3>
                    <p>Providing compassionate support and guidance to our scholars throughout their academic journey.</p>
                </div>
                <div class="card about-card fade-in">
                    <div class="about-icon">R</div>
                    <h3>Renewal</h3>
                    <p>Encouraging personal growth and continuous improvement through seminars and workshops.</p>
                </div>
                <div class="card about-card fade-in">
                    <div class="about-icon">O</div>
                    <h3>Outreach</h3>
                    <p>Extending our support to more students in need through community partnerships.</p>
                </div>
                <div class="card about-card fade-in">
                    <div class="about-icon">S</div>
                    <h3>Support</h3>
                    <p>Offering financial assistance, mentorship, and resources for academic success.</p>
                </div>
                <div class="card about-card fade-in">
                    <div class="about-icon">S</div>
                    <h3>Sustainability</h3>
                    <p>Building long-term programs that create lasting impact in our scholars' lives.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">What Our Scholars Say</h2>
            <div class="testimonials-grid">
                <div class="card testimonial-card">
                    <div class="testimonial-content">
                        <p>"The CROSS platform made managing my scholarship so much easier. The seminar tracking and certificate system are incredibly helpful!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">M</div>
                        <div class="author-info">
                            <h4>Maria Santos</h4>
                            <p>BS Nursing, 3rd Year</p>
                        </div>
                    </div>
                </div>
                <div class="card testimonial-card">
                    <div class="testimonial-content">
                        <p>"The mentorship program through CROSS connected me with industry professionals who guided my career path. Truly life-changing!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">J</div>
                        <div class="author-info">
                            <h4>Juan Dela Cruz</h4>
                            <p>BS Computer Science, 4th Year</p>
                        </div>
                    </div>
                </div>
                <div class="card testimonial-card">
                    <div class="testimonial-content">
                        <p>"As a working student, the flexible seminar schedules and online resources have been invaluable for my academic success."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">A</div>
                        <div class="author-info">
                            <h4>Ana Reyes</h4>
                            <p>BS Education, 2nd Year</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Links Section -->
    <section class="section" style="background: linear-gradient(135deg, var(--maroon), var(--gold)); color: white;">
        <div class="container">
            <h2 class="section-title" style="color: white;">Get Started Today</h2>
            <div class="quick-links-grid">
                <div class="quick-link-card">
                    <div style="font-size: 3rem; margin-bottom: 20px;">🎓</div>
                    <h3>New Applicants</h3>
                    <p>Start your scholarship journey with us. Register now and join our community of scholars.</p>
                    <a href="register.php" class="btn btn-gold">Register</a>
                </div>
                <div class="quick-link-card">
                    <div style="font-size: 3rem; margin-bottom: 20px;">📚</div>
                    <h3>Current Scholars</h3>
                    <p>Access your dashboard to manage your scholarship and view upcoming seminars.</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php" class="btn" style="background-color: white; color: var(--maroon);">Go to Dashboard</a>
                    <?php else: ?>
                        <a href="login.php" class="btn" style="background-color: white; color: var(--maroon);">Login Here</a>
                    <?php endif; ?>
                </div>
                <div class="quick-link-card">
                    <div style="font-size: 3rem; margin-bottom: 20px;">🏫</div>
                    <h3>Learn More</h3>
                    <p>Discover our programs, mission, and the team behind Servus Amoris Foundation.</p>
                    <a href="about.php" class="btn btn-secondary" style="border-color: white; color: white;">About Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section" style="background-color: var(--gray);">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-grid">
                <div class="card faq-card">
                    <h4>Who can apply for the scholarship?</h4>
                    <p>Currently enrolled college students with good academic standing and demonstrated financial need may apply.</p>
                </div>
                <div class="card faq-card">
                    <h4>What documents are required?</h4>
                    <p>You'll need your latest grades, proof of enrollment, and a personal statement. Check the application form for complete details.</p>
                </div>
                <div class="card faq-card">
                    <h4>Are the seminars mandatory?</h4>
                    <p>Yes, seminar attendance is required to maintain scholarship status and access certificate programs.</p>
                </div>
                <div class="card faq-card">
                    <h4>How do I track my attendance?</h4>
                    <p>Login to your dashboard to view your attendance record and download certificates for completed seminars.</p>
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