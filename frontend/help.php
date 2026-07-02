<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Help Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .static-header { background: var(--surface); padding: 4rem 5%; text-align: center; border-bottom: 1px solid var(--border); }
        .static-header h1 { font-size: 3rem; color: var(--primary); }
        .static-content { max-width: 800px; margin: 4rem auto; padding: 0 5%; }
        .faq-item { background: var(--surface); padding: 2rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid var(--border); }
        .faq-item h3 { color: var(--primary); margin-bottom: 1rem; }
        .nav-landing { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; background: var(--surface); border-bottom: 1px solid var(--border); }
    </style>
</head>
<body>
    <nav class="nav-landing">
        <a href="index.php" class="brand">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            Nexus Jobs
        </a>
    </nav>
    <header class="static-header">
        <h1>Help Center</h1>
        <p>Find answers to common questions about using Nexus Jobs.</p>
    </header>
    <main class="static-content">
        <div class="faq-item">
            <h3>How do I post a job as an employer?</h3>
            <p>You must first create a new account and select the role "Employer". Once logged in, your dashboard will have a dedicated form where you can post a new opportunity.</p>
        </div>
        <div class="faq-item">
            <h3>How are my skills automatically graded?</h3>
            <p>Our backend compares the comma-separated list of skills requested by the employer with the comma-separated list of skills you submit in your application. It calculates a mathematical intersection percentage to produce your match score.</p>
        </div>
        <div class="faq-item">
            <h3>I forgot my password. How do I reset it?</h3>
            <p>Currently, Nexus operates as a closed local system. Password resets are not automatically enabled via email. Please contact the database administrator.</p>
        </div>
        <br><a href="index.php" class="btn" style="display:inline-block;">Return Home</a>
    </main>
</body>
</html>
