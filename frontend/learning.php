<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .static-header { background: var(--surface); padding: 4rem 5%; text-align: center; border-bottom: 1px solid var(--border); }
        .static-header h1 { font-size: 3rem; color: var(--primary); }
        .static-content { max-width: 1000px; margin: 4rem auto; padding: 0 5%; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;}
        .nav-landing { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; background: var(--surface); border-bottom: 1px solid var(--border); }
        .course-card { background: var(--surface); padding: 2rem; border-radius: 12px; border: 1px solid var(--border); }
        .course-card h3 { color: var(--text-main); margin-bottom: 0.5rem; }
        .course-card .author { color: var(--primary); font-size: 0.9rem; margin-bottom: 1rem;}
        .course-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; }
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
        <h1>Nexus Learning</h1>
        <p>Keep your skills up-to-date with expert-led courses.</p>
    </header>
    <div style="text-align: center; margin-top: 2rem;">
        <h2>Top Courses This Week</h2>
    </div>
    <main class="static-content">
        <div class="course-card">
            <img src="https://images.unsplash.com/photo-1516116216624-53e697fedbea?auto=format&fit=crop&q=80&w=400" alt="Code">
            <h3>Advanced Python Programming</h3>
            <div class="author">By Jane Doe</div>
            <p>Master Python to increase your Nexus Match Score by up to 20% on backend roles.</p>
        </div>
        <div class="course-card">
            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=400" alt="Business">
            <h3>Effective Communication</h3>
            <div class="author">By John Smith</div>
            <p>Learn the soft skills necessary to ace your next virtual or in-person interview.</p>
        </div>
    </main>
    <div style="text-align: center; margin-bottom: 4rem;">
        <br><a href="index.php" class="btn-outline">Return Home</a>
    </div>
</body>
</html>
