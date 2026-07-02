<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Press</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .static-header { background: var(--surface); padding: 4rem 5%; text-align: center; border-bottom: 1px solid var(--border); }
        .static-header h1 { font-size: 3rem; color: var(--primary); }
        .static-content { max-width: 800px; margin: 4rem auto; padding: 0 5%; line-height: 1.8; font-size: 1.1rem; }
        .nav-landing { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; background: var(--surface); border-bottom: 1px solid var(--border); }
        .news-item { padding: 2rem; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 2rem; }
        .news-date { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem; }
    </style>
</head>
<body>
    <nav class="nav-landing">
        <a href="index.php" class="brand">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            Nexus Jobs
        </a>
    </nav>
    <header class="static-header">
        <h1>Press Room</h1>
        <p>Latest news, announcements, and media resources from Nexus Jobs.</p>
    </header>
    <main class="static-content">
        <div class="news-item">
            <div class="news-date">October 15, 2026</div>
            <h2>Nexus Jobs Surpasses 1 Million Local Users</h2>
            <p>We are thrilled to announce that our platform has connected over one million job seekers with their dream careers within local communities, solidifying our mission to create economic opportunity.</p>
        </div>
        <div class="news-item">
            <div class="news-date">September 02, 2026</div>
            <h2>Introducing the New Skill-Matching Algorithm</h2>
            <p>Our engineering team has rolled out a new, highly accurate skill-matching algorithm that helps employers instantly find top talent by mathematically scoring resumes against job requirements.</p>
        </div>
        <br><a href="index.php" class="btn-outline">Return Home</a>
    </main>
</body>
</html>
