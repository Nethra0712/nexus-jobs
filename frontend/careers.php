<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Careers</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .static-header { background: var(--surface); padding: 4rem 5%; text-align: center; border-bottom: 1px solid var(--border); }
        .static-header h1 { font-size: 3rem; color: var(--primary); }
        .static-content { max-width: 800px; margin: 4rem auto; padding: 0 5%; }
        .nav-landing { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; background: var(--surface); border-bottom: 1px solid var(--border); }
        .job-card { background: var(--surface); padding: 2rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid var(--border); }
        .job-card h3 { color: var(--primary); margin-bottom: 0.5rem; }
        .job-card p { color: var(--text-muted); margin-bottom: 1.5rem; }
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
        <h1>Careers at Nexus Jobs</h1>
        <p>Come build the future of professional networking with us.</p>
    </header>
    <main class="static-content">
        <h2>Open Roles</h2>
        <div class="job-card">
            <h3>Senior Backend Engineer (Python/Flask)</h3>
            <p>San Francisco, CA (or Remote) &bull; Full-time</p>
            <button class="btn-brand">Apply Now</button>
        </div>
        <div class="job-card">
            <h3>Product Designer</h3>
            <p>New York, NY (or Remote) &bull; Full-time</p>
            <button class="btn-brand">Apply Now</button>
        </div>
        <br><a href="index.php" class="btn-outline">Return Home</a>
    </main>
</body>
</html>
