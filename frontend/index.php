<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Your Professional Community</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Landing Page Specific Styles */
        body {
            background-color: var(--surface);
        }
        
        .nav-landing {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            background: var(--surface);
        }

        .nav-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--text-main);
            color: var(--text-main);
            padding: 0.6rem 1.5rem;
            border-radius: 999px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            background: rgba(0,0,0,0.05);
        }

        .btn-brand {
            background: var(--primary);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 999px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--primary);
        }

        .btn-brand:hover {
            background: var(--primary-hover);
        }

        .hero-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            padding: 4rem 5%;
            min-height: 80vh;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 300;
            color: #8f5849; /* LinkedIn style accent */
            line-height: 1.2;
            margin-bottom: 2rem;
        }

        @media (prefers-color-scheme: dark) {
            .hero-content h1 {
                color: #d89f8d;
            }
        }

        .hero-illustration {
            width: 100%;
            height: auto;
            max-width: 600px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
        }

        .action-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 400px;
        }

        .big-btn {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 1.2rem 1.5rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            color: var(--text-main);
            font-size: 1.2rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
        }

        .big-btn:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* Section 2: Discover Jobs */
        .section-discover {
            background: var(--background);
            padding: 5rem 5%;
        }

        .section-discover h2 {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 3rem;
        }
        
        .pill-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .pill {
            padding: 1rem 1.5rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-main);
            font-weight: 600;
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        
        .pill:hover {
            background: rgba(0,0,0,0.05);
            border-color: var(--text-main);
        }

        /* Section 3: Feature Highlight */
        .section-feature {
            background: var(--surface);
            padding: 5rem 5%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .feature-box {
            background: #fdf3ef; /* LinkedIn light red/orange */
            padding: 3rem;
            border-radius: 20px;
        }
        
        @media (prefers-color-scheme: dark) {
            .feature-box { background: #3d251d; }
        }

        .feature-box h2 {
            font-size: 2.5rem;
            font-weight: 300;
            color: #b24020;
            margin-bottom: 2rem;
        }
        
        @media (prefers-color-scheme: dark) {
            .feature-box h2 { color: #eb8c73; }
        }

        .feature-box .btn-brand {
            display: inline-block;
            font-size: 1.2rem;
            padding: 1rem 2rem;
        }

        /* Footer */
        .footer {
            background: #f8fafc;
            padding: 4rem 5%;
            border-top: 1px solid var(--border);
        }
        @media (prefers-color-scheme: dark) {
            .footer { background: #111827; }
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .footer-col h4 {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
        }

        .footer-col ul li {
            margin-bottom: 0.5rem;
        }

        .footer-col ul li a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .footer-col ul li a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .footer-bottom {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        @media (max-width: 900px) {
            .hero-section, .section-feature {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .action-container {
                margin: 0 auto;
            }
            .hero-content h1 {
                font-size: 2.5rem;
            }
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 500px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="nav-landing">
        <a href="index.php" class="brand">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            Nexus Jobs
        </a>
        <div class="nav-actions">
            <a href="auth.php?action=register" class="btn-outline">Join now</a>
            <a href="auth.php?action=login" class="btn-brand">Sign in</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="hero-section">
        <div class="hero-content">
            <h1>Welcome to your professional community</h1>
            
            <div class="action-container">
                <a href="auth.php?action=login" class="big-btn">
                    Find the right job or internship
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
                <a href="auth.php?action=login" class="big-btn">
                    Post a job for free
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>

        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Professionals working together" class="hero-illustration">
        </div>
    </main>

    <!-- Discover Jobs Section -->
    <section class="section-discover">
        <div style="max-width: 1200px; margin: 0 auto;">
            <h2>Explore topics you are interested in</h2>
            <div class="pill-container">
                <a href="#" onclick="handleTopic('Engineering', event)" class="pill">Engineering</a>
                <a href="#" onclick="handleTopic('Business Development', event)" class="pill">Business Development</a>
                <a href="#" onclick="handleTopic('Finance', event)" class="pill">Finance</a>
                <a href="#" onclick="handleTopic('Administrative Assistant', event)" class="pill">Administrative Assistant</a>
                <a href="#" onclick="handleTopic('Retail Associate', event)" class="pill">Retail Associate</a>
                <a href="#" onclick="handleTopic('Customer Service', event)" class="pill">Customer Service</a>
                <a href="#" onclick="handleTopic('Operations', event)" class="pill">Operations</a>
                <a href="#" onclick="handleTopic('Information Technology', event)" class="pill">Information Technology</a>
                <a href="#" onclick="handleTopic('Marketing', event)" class="pill">Marketing</a>
                <a href="#" onclick="handleTopic('Human Resources', event)" class="pill">Human Resources</a>
            </div>
        </div>
    </section>

    <!-- Feature Section -->
    <section class="section-feature">
        <div class="feature-content" style="max-width: 500px; justify-self: end;">
            <h2 style="font-size: 3rem; font-weight: 300; margin-bottom: 2rem;">Post your job for millions of people to see</h2>
            <a href="auth.php?action=login" class="btn-outline" style="font-size: 1.2rem; padding: 1rem 2rem;">Post a job</a>
        </div>
        <div class="feature-image">
            <img src="https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Hiring" class="hero-illustration">
        </div>
    </section>

    <!-- Connect Feature Section -->
    <section class="section-feature" style="background: var(--background)">
        <div class="feature-image" style="justify-self: end;">
            <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Networking" class="hero-illustration">
        </div>
        <div class="feature-box">
            <h2>Let the right people know you're open to work</h2>
            <p style="margin-bottom: 2rem; font-size: 1.1rem;">With the Open To Work feature, you can secretly tell recruiters or publicly share with the Nexus Jobs community that you are looking for new job opportunities.</p>
            <a href="auth.php?action=register" class="btn-brand">Get started</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>General</h4>
                <ul>
                    <li><a href="auth.php?action=register">Sign Up</a></li>
                    <li><a href="help.php">Help Center</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="press.php">Press</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="careers.php">Careers</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Browse Nexus Jobs</h4>
                <ul>
                    <li><a href="learning.php">Learning</a></li>
                    <li><a href="auth.php?action=login">Jobs</a></li>
                    <li><a href="salary.php">Salary</a></li>
                    <li><a href="mobile.php">Mobile</a></li>
                    <li><a href="services.php">Services</a></li>
                </ul>
        </div>
        <div class="footer-bottom">
            <span class="brand" style="font-size: 1.2rem; margin-right: 1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                Nexus
            </span>
            © 2026 Nexus Jobs
            <a href="about.php" style="color: var(--text-muted); text-decoration: none; margin-left: 1rem;">About</a>
            <a href="help.php" style="color: var(--text-muted); text-decoration: none; margin-left: 1rem;">Accessibility</a>
            <a href="help.php" style="color: var(--text-muted); text-decoration: none; margin-left: 1rem;">User Agreement</a>
            <a href="help.php" style="color: var(--text-muted); text-decoration: none; margin-left: 1rem;">Privacy Policy</a>
        </div>
    </footer>

    <script>
        function handleTopic(topic, e) {
            e.preventDefault();
            localStorage.setItem('search_topic', topic);
            const userStr = localStorage.getItem('user');
            if (userStr) {
                const user = JSON.parse(userStr);
                if (user.role === 'admin') {
                    window.location.href = 'admin_dashboard.php';
                } else if (user.role === 'employer') {
                    window.location.href = 'employer_dashboard.php';
                } else {
                    window.location.href = 'seeker_dashboard.php';
                }
            } else {
                window.location.href = 'auth.php?action=login';
            }
        }

        // Check if already logged in natively, auto direct to dashboard
        window.onload = () => {
            const userStr = localStorage.getItem('user');
            if (userStr) {
                const user = JSON.parse(userStr);
                if (user.role === 'admin') {
                    window.location.href = 'admin_dashboard.php';
                } else if (user.role === 'employer') {
                    window.location.href = 'employer_dashboard.php';
                } else {
                    window.location.href = 'seeker_dashboard.php';
                }
            }
        };
    </script>
</body>
</html>
