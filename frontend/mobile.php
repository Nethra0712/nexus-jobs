<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Jobs - Mobile App</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background: var(--surface); }
        .hero-mobile { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; padding: 4rem 5%; align-items: center; min-height: 80vh; }
        .hero-mobile h1 { font-size: 3.5rem; color: var(--text-main); line-height: 1.2; margin-bottom: 2rem; }
        .hero-mobile p { font-size: 1.2rem; color: var(--text-muted); margin-bottom: 2rem; }
        .mobile-img { width: 100%; max-width: 400px; border-radius: 20px; box-shadow: var(--shadow-xl); margin: 0 auto; display: block; }
        .nav-landing { display: flex; justify-content: space-between; align-items: center; padding: 1rem 5%; }
        .store-btns { display: flex; gap: 1rem; }
    </style>
</head>
<body>
    <nav class="nav-landing">
        <a href="index.php" class="brand">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            Nexus Jobs
        </a>
    </nav>
    <main class="hero-mobile">
        <div>
            <h1>Take your network wherever you go.</h1>
            <p>Your professional community is just a tap away. Apply for jobs, connect with recruiters, and read the latest industry news on the Nexus Jobs mobile app.</p>
            <div class="store-btns">
                <button class="btn-brand" style="background: black; padding: 1rem 2rem;">Download on App Store</button>
                <button class="btn-brand" style="background: #0d47a1; padding: 1rem 2rem;">Get it on Google Play</button>
            </div>
            <br><br>
            <a href="index.php" class="btn-outline">Return Home</a>
        </div>
        <div>
            <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&q=80&w=800" alt="Mobile Experience" class="mobile-img">
        </div>
    </main>
</body>
</html>
