<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Business Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .static-header { background: var(--surface); padding: 4rem 5%; text-align: center; border-bottom: 1px solid var(--border); }
        .static-header h1 { font-size: 3rem; color: var(--primary); margin-bottom: 1rem; }
        .static-content { max-width: 800px; margin: 4rem auto; padding: 0 5%; line-height: 1.8; text-align: center; min-height: 40vh; }
        .nav-landing { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; background: var(--surface); border-bottom: 1px solid var(--border); }
        .service-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 3rem; text-align: left; }
        .service-card { padding: 2rem; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); }
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
        <h1>Nexus Business Services</h1>
        <p>Premium tools for recruiters and enterprises to hire at scale.</p>
    </header>
    <main class="static-content">
        <h2>Our Offerings</h2>
        <div class="service-grid">
            <div class="service-card">
                <h3 style="color: var(--primary); margin-bottom: 0.5rem;">Nexus Recruiter Lite</h3>
                <p>Find standout candidates faster with advanced search filters and direct messaging capabilities.</p>
            </div>
            <div class="service-card">
                <h3 style="color: var(--primary); margin-bottom: 0.5rem;">Nexus Enterprise</h3>
                <p>Custom integrations, bulk job postings, and dedicated account management for large organizations.</p>
            </div>
        </div>
        <br><br>
        <a href="index.php" class="btn-brand">Return Home</a>
    </main>
</body>
</html>
