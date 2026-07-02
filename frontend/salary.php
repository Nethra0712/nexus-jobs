<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Salary Insights</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .static-header { background: var(--surface); padding: 4rem 5%; text-align: center; border-bottom: 1px solid var(--border); }
        .static-header h1 { font-size: 3rem; color: var(--primary); }
        .static-content { max-width: 800px; margin: 4rem auto; padding: 0 5%; }
        .nav-landing { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; background: var(--surface); border-bottom: 1px solid var(--border); }
        .salary-box { background: var(--surface); padding: 2.5rem; border-radius: 12px; border: 1px solid var(--border); text-align: center; }
        .salary-amount { font-size: 3rem; font-weight: 700; color: #2e7d32; margin: 1rem 0; }
        .search-bar { display: flex; gap: 1rem; margin-bottom: 2rem; justify-content: center; }
        .search-bar input { padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid var(--border); width: 300px; font-size: 1rem; }
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
        <h1>Salary Insights</h1>
        <p>Discover your earning potential based on your skills and location.</p>
    </header>
    <main class="static-content">
        <div class="search-bar">
            <input type="text" placeholder="e.g. Software Engineer" value="Software Engineer">
            <button class="btn-brand">Search</button>
        </div>
        <div class="salary-box">
            <h2>Average Base Pay</h2>
            <div class="salary-amount">$120,500 <span style="font-size: 1.2rem; font-weight: 400; color: var(--text-muted);">/ yr</span></div>
            <p>Based on anonymized data from 10,000+Nexus Jobs users.</p>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="index.php" class="btn-outline">Return Home</a>
        </div>
    </main>
</body>
</html>
