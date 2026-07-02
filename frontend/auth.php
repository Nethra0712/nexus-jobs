<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Job Portal - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="center-flex">

    <div class="auth-container">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="brand" style="justify-content: center; font-size: 2rem;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                Nexus Jobs
            </div>
            <p style="margin-top: 0.5rem;">The premium platform connecting talent.</p>
        </div>

        <div class="card">
            <div class="tabs">
                <div class="tab active" id="tab-login" onclick="switchTab('login')">Login</div>
                <div class="tab" id="tab-register" onclick="switchTab('register')">Register</div>
            </div>

            <div id="alert-box" class="alert"></div>

            <!-- Login Form -->
            <form id="form-login" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" id="login-username" required placeholder="john_doe">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="login-password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%">Sign In</button>
            </form>

            <!-- Register Form (Hidden by default) -->
            <form id="form-register" onsubmit="handleRegister(event)" style="display: none;">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" id="reg-username" required placeholder="john_doe">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="reg-email" required placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="reg-password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>I am a...</label>
                    <select id="reg-role" required>
                        <option value="seeker">Job Seeker</option>
                        <option value="employer">Employer / Recruiter</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = 'http://localhost:5000/api';

        function showAlert(msg, isError = true) {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'block';
            alertBox.className = `alert ${isError ? 'alert-error' : 'alert-success'}`;
            alertBox.textContent = msg;
        }

        function switchTab(tabName) {
            document.getElementById('tab-login').classList.remove('active');
            document.getElementById('tab-register').classList.remove('active');
            
            document.getElementById('form-login').style.display = 'none';
            document.getElementById('form-register').style.display = 'none';
            
            document.getElementById(`tab-${tabName}`).classList.add('active');
            document.getElementById(`form-${tabName}`).style.display = 'block';
            document.getElementById('alert-box').style.display = 'none';
        }

        async function handleLogin(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            btn.disabled = true;
            btn.textContent = 'Signing in...';

            try {
                const res = await fetch(`${API_BASE}/login`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        username: document.getElementById('login-username').value,
                        password: document.getElementById('login-password').value
                    })
                });
                const data = await res.json();
                
                if (!res.ok) throw new Error(data.error || 'Login failed');
                
                // Store user data
                localStorage.setItem('user', JSON.stringify(data));
                
                // Redirect based on role
                if (data.role === 'admin') {
                    window.location.href = 'admin_dashboard.php';
                } else if (data.role === 'employer') {
                    window.location.href = 'employer_dashboard.php';
                } else {
                    window.location.href = 'seeker_dashboard.php';
                }
            } catch (err) {
                showAlert(err.message);
                btn.disabled = false;
                btn.textContent = 'Sign In';
            }
        }

        async function handleRegister(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            btn.disabled = true;
            btn.textContent = 'Creating account...';

            try {
                const res = await fetch(`${API_BASE}/register`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        username: document.getElementById('reg-username').value,
                        email: document.getElementById('reg-email').value,
                        password: document.getElementById('reg-password').value,
                        role: document.getElementById('reg-role').value
                    })
                });
                const data = await res.json();
                
                if (!res.ok) throw new Error(data.error || 'Registration failed');
                
                showAlert('Registration successful! You can now login.', false);
                setTimeout(() => switchTab('login'), 2000);
                
            } catch (err) {
                showAlert(err.message);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Create Account';
            }
        }

        // Check if already logged in or handle URL params
        window.onload = () => {
            const userStr = localStorage.getItem('user');
            if (userStr) {
                const user = JSON.parse(userStr);
                if (user.role === 'admin') window.location.href = 'admin_dashboard.php';
                else if (user.role === 'employer') window.location.href = 'employer_dashboard.php';
                else window.location.href = 'seeker_dashboard.php';
                return;
            }

            // Check URL parameters for tab routing
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            if (action === 'register') {
                switchTab('register');
            } else {
                switchTab('login');
            }
        };
    </script>
</body>
</html>
