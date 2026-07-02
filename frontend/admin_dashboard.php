<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nexus Jobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=3.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .kpi-card {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }
        .kpi-info h3 {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin: 0;
            font-weight: 500;
        }
        .kpi-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 0.25rem;
        }
        .dashboard-grid-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .chart-container {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .activity-feed {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid var(--border);
        }
        .activity-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .badge-user { background: rgba(79, 70, 229, 0.1); color: var(--primary); }
        .badge-job { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .badge-payment { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        
        .activity-content h4 {
            font-size: 0.95rem;
            margin: 0;
            font-weight: 600;
        }
        .activity-content p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 2px 0 0 0;
        }
        /* Dashboard Layout Override from purple theme */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            margin-top: 2rem;
            min-height: auto;
        }
        .sidebar-card {
            padding: 1rem;
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }
        .main-content {
            padding: 0;
            background: transparent;
        }
        @media (max-width: 1024px) {
            .dashboard-grid-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            Nexus Jobs - Admin
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div style="background: rgba(79, 70, 229, 0.1); padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid var(--primary); font-weight: 600; color: var(--primary);">
                Wallet: Rs. <span id="nav-wallet-balance">0.00</span>
            </div>
            <span id="user-greeting" style="font-weight: 500;"></span>
            <button onclick="logout()" class="btn btn-secondary">Logout</button>
        </div>
    </nav>

    <div class="container dashboard-grid">
        
        <!-- Sidebar Navigation -->
        <aside>
            <div class="sidebar-card">
                <ul style="list-style: none;">
                    <li style="margin-bottom: 0.5rem;"><button onclick="showSection('overview')" class="btn" style="width: 100%; text-align: left; background: var(--surface)">Overview & Reports</button></li>
                    <li style="margin-bottom: 0.5rem;"><button onclick="showSection('seekers')" class="btn" style="width: 100%; text-align: left; background: var(--surface)">Job Seekers</button></li>
                    <li style="margin-bottom: 0.5rem;"><button onclick="showSection('recruiters')" class="btn" style="width: 100%; text-align: left; background: var(--surface)">Recruiters</button></li>
                    <li style="margin-bottom: 0.5rem;"><button onclick="showSection('jobs')" class="btn" style="width: 100%; text-align: left; background: var(--surface)">All Jobs</button></li>
                    <li style="margin-bottom: 0.5rem;"><button onclick="showSection('payments')" class="btn" style="width: 100%; text-align: left; background: var(--surface)">Payments</button></li>
                    <li style="margin-top: 1.5rem;"><button onclick="showSection('wallet')" class="btn btn-primary" style="width: 100%; text-align: center;">Wallet & Withdraw</button></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <div id="alert-box" class="alert"></div>

            <!-- Overview Section -->
            <section id="section-overview">
                <!-- Filters -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <div>
                        <h2 style="margin: 0;">Reports & Analytics</h2>
                        <div style="font-size: 0.9rem; color: var(--text-muted);" id="last-updated">System Overview</div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <select id="filter-month" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                            <option value="1">January</option><option value="2">February</option>
                            <option value="3">March</option><option value="4">April</option>
                            <option value="5">May</option><option value="6">June</option>
                            <option value="7">July</option><option value="8">August</option>
                            <option value="9">September</option><option value="10">October</option>
                            <option value="11">November</option><option value="12">December</option>
                        </select>
                        <select id="filter-year" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                            <option value="2025">2025</option><option value="2026">2026</option>
                        </select>
                        <button onclick="fetchFilteredStats()" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Apply</button>
                        <button onclick="window.print()" class="btn btn-secondary" style="padding: 0.5rem 1rem;">Print</button>
                    </div>
                </div>
                
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kpi-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="kpi-info">
                            <h3>Active Seekers</h3>
                            <div class="kpi-value" id="kpi-monthly-seekers">0</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Life-time: <span id="kpi-total-seekers">0</span></div>
                        </div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        </div>
                        <div class="kpi-info">
                            <h3>New Employers</h3>
                            <div class="kpi-value" id="kpi-monthly-employers">0</div>
                        </div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <div class="kpi-info">
                            <h3>Monthly Revenue</h3>
                            <div class="kpi-value">Rs. <span id="kpi-revenue">0</span></div>
                            <div style="font-size: 0.75rem; color: var(--primary); margin-top: 4px;"><span id="kpi-collection-rate">0</span>% collection rate</div>
                        </div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="18" rx="2" ry="2"></rect><line x1="16" y1="8" x2="20" y2="8"></line><line x1="16" y1="16" x2="20" y2="16"></line><line x1="12" y1="8" x2="12" y2="16"></line></svg>
                        </div>
                        <div class="kpi-info">
                            <h3>Total Wallet</h3>
                            <div class="kpi-value">Rs. <span id="kpi-wallet">0</span></div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-grid-layout">
                    <div class="chart-container">
                        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
                            Revenue Trends
                        </h3>
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="revenueTrendChart"></canvas>
                        </div>
                    </div>

                    <div class="activity-feed">
                        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            Recent Activity
                        </h3>
                        <div id="activity-list" style="max-height: 400px; overflow-y: auto;">
                            <!-- Items populated by JS -->
                            <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Loading activity...</p>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                    <div class="chart-container">
                        <h3 style="margin-bottom: 1.5rem;">Industry / Category Distribution</h3>
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="industryDistChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Seekers Section -->
            <section id="section-seekers" class="card" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                    <h2 style="margin: 0;">Registered Job Seekers</h2>
                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <input type="date" id="filter-seekers-start" title="Start Date" onchange="fetchTableData('seeker', 'seekers')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <span style="color: var(--text-muted);">to</span>
                        <input type="date" id="filter-seekers-end" title="End Date" onchange="fetchTableData('seeker', 'seekers')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Registered On</th></tr></thead>
                        <tbody id="seekers-body"></tbody>
                    </table>
                </div>
            </section>

            <!-- Recruiters Section -->
            <section id="section-recruiters" class="card" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                    <h2 style="margin: 0;">Registered Recruiters</h2>
                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <input type="date" id="filter-recruiters-start" title="Start Date" onchange="fetchTableData('employer', 'recruiters')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <span style="color: var(--text-muted);">to</span>
                        <input type="date" id="filter-recruiters-end" title="End Date" onchange="fetchTableData('employer', 'recruiters')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Registered On</th></tr></thead>
                        <tbody id="recruiters-body"></tbody>
                    </table>
                </div>
            </section>

            <!-- Jobs Section -->
            <section id="section-jobs" class="card" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                    <h2 style="margin: 0;">All Posted Jobs</h2>
                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <input type="text" id="filter-jobs-title" placeholder="Search by title" onkeyup="fetchList('admin/jobs', 'jobs')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <select id="filter-jobs-employer" onchange="fetchList('admin/jobs', 'jobs')" class="btn btn-secondary" style="padding: 0.5rem 1rem; width: auto;">
                            <option value="">All Employers</option>
                        </select>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead><tr><th>ID</th><th>Title</th><th>Employer</th><th>Posted On</th></tr></thead>
                        <tbody id="jobs-body"></tbody>
                    </table>
                </div>
            </section>

            <!-- Payments Section -->
            <section id="section-payments" class="card" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                    <h2 style="margin: 0;">Payment History</h2>
                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <input type="date" id="filter-payments-start" title="Start Date" onchange="fetchList('admin/payments', 'payments')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <span style="color: var(--text-muted);">to</span>
                        <input type="date" id="filter-payments-end" title="End Date" onchange="fetchList('admin/payments', 'payments')" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                        <select id="filter-payments-employer" onchange="fetchList('admin/payments', 'payments')" class="btn btn-secondary" style="padding: 0.5rem 1rem; width: auto;">
                            <option value="">All Employers</option>
                        </select>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead><tr><th>ID</th><th>Recruiter</th><th>Amount</th><th>Type</th><th>Date</th></tr></thead>
                        <tbody id="payments-body"></tbody>
                    </table>
                </div>
            </section>

            <!-- Wallet Section -->
            <section id="section-wallet" class="card" style="display: none;">
                <h2 style="margin-bottom: 1.5rem;">Admin Wallet & Withdrawal</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Manage your earnings and withdraw funds to your bank account.</p>
                
                <div style="background: var(--surface); padding: 3rem; border-radius: 12px; border: 1px solid var(--border); text-align: center; margin-bottom: 2rem;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 1rem; color: #10b981;"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 0.5rem;">Current Available Balance</p>
                    <div style="font-size: 3.5rem; font-weight: 800; color: #10b981; margin: 1rem 0;">Rs. <span id="main-wallet-balance">0.00</span></div>
                    
                    <div style="max-width: 400px; margin: 2rem auto 0 auto;">
                        <button onclick="requestWithdrawal()" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem; width: 100%; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            Withdraw Funds Now
                        </button>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script>
        const API_BASE = 'http://localhost:5000/api';
        let currentUser = null;
        let charts = { industry: null, trend: null };

        window.onload = () => {
            const userStr = localStorage.getItem('user');
            if (!userStr) {
                window.location.href = 'index.php';
                return;
            }
            currentUser = JSON.parse(userStr);
            if (currentUser.role !== 'admin') {
                logout();
            }
            
            document.getElementById('user-greeting').textContent = `Admin: ${currentUser.username}`;
            
            // Set default filters to current month/year
            const d = new Date();
            document.getElementById('filter-month').value = d.getMonth() + 1;
            document.getElementById('filter-year').value = d.getFullYear();

            fetchEmployers();
            showSection('overview');
        };

        async function fetchEmployers() {
            try {
                const res = await fetch(`${API_BASE}/admin/users?role=employer`);
                const data = await res.json();
                
                const options = data.users.map(e => `<option value="${e.id}">${e.username}</option>`).join('');
                document.getElementById('filter-jobs-employer').innerHTML += options;
                document.getElementById('filter-payments-employer').innerHTML += options;
            } catch (err) {
                console.error("Failed to fetch employers", err);
            }
        }

        function logout() {
            localStorage.removeItem('user');
            window.location.href = 'index.php';
        }

        async function showSection(sectionId) {
            document.querySelectorAll('main section').forEach(sec => sec.style.display = 'none');
            document.getElementById(`section-${sectionId}`).style.display = 'block';
            
            if (sectionId === 'overview') await fetchFilteredStats();
            if (sectionId === 'seekers') await fetchTableData('seeker', 'seekers');
            if (sectionId === 'recruiters') await fetchTableData('employer', 'recruiters');
            if (sectionId === 'jobs') await fetchList('admin/jobs', 'jobs');
            if (sectionId === 'payments') await fetchList('admin/payments', 'payments');
            if (sectionId === 'wallet') await fetchFilteredStats(); // Updates wallet balance
        }

        async function fetchFilteredStats() {
            const m = document.getElementById('filter-month').value;
            const y = document.getElementById('filter-year').value;
            
            try {
                const res = await fetch(`${API_BASE}/admin/stats?month=${m}&year=${y}`);
                const data = await res.json();
                
                // KPIs
                document.getElementById('kpi-monthly-seekers').textContent = data.monthly_seekers.toLocaleString();
                document.getElementById('kpi-total-seekers').textContent = data.total_seekers.toLocaleString();
                document.getElementById('kpi-monthly-employers').textContent = data.monthly_employers.toLocaleString();
                document.getElementById('kpi-revenue').textContent = data.monthly_revenue.toLocaleString();
                document.getElementById('kpi-collection-rate').textContent = data.collection_rate;
                
                const balanceStr = (data.wallet_balance || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
                document.getElementById('kpi-wallet').textContent = balanceStr;
                document.getElementById('nav-wallet-balance').textContent = balanceStr;
                document.getElementById('main-wallet-balance').textContent = balanceStr;

                renderCharts(data);
                renderActivityFeed(data.recent_activities);
            } catch (err) {
                console.error("Failed to fetch stats", err);
            }
        }

        function renderCharts(data) {
            // 1. Industry Distribution (Horizontal Bar)
            const indCtx = document.getElementById('industryDistChart').getContext('2d');
            if (charts.industry) charts.industry.destroy();
            
            const indLabels = data.industry_distribution.map(d => d.label);
            const indValues = data.industry_distribution.map(d => d.count);

            charts.industry = new Chart(indCtx, {
                type: 'bar',
                data: {
                    labels: indLabels,
                    datasets: [{
                        label: 'Postings',
                        data: indValues,
                        backgroundColor: '#4F46E5', // Standard primary
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });

            // 2. Revenue Trend (Line Chart)
            const trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
            if (charts.trend) charts.trend.destroy();

            const trendLabels = data.revenue_trend.map(d => d.month);
            const trendValues = data.revenue_trend.map(d => d.revenue);

            charts.trend = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Revenue (Rs.)',
                        data: trendValues,
                        borderColor: '#10B981', // Standard success
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        function renderActivityFeed(activities) {
            const list = document.getElementById('activity-list');
            if (!activities || activities.length === 0) {
                list.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 2rem;">No recent activity</p>';
                return;
            }

            const html = activities.map(act => {
                let badgeClass = 'badge-user';
                let icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                let typeText = 'New User Joined';

                if (act.type === 'job') {
                    badgeClass = 'badge-job';
                    icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>';
                    typeText = 'New Job Posted';
                } else if (act.type === 'payment') {
                    badgeClass = 'badge-payment';
                    icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
                    typeText = 'Payment Received';
                }

                const timeAgo = formatTimeAgo(new Date(act.created_at));

                return `
                    <div class="activity-item">
                        <div class="activity-badge ${badgeClass}">${icon}</div>
                        <div class="activity-content">
                            <h4>${act.title}</h4>
                            <p>${typeText} • ${timeAgo}</p>
                        </div>
                    </div>
                `;
            }).join('');

            list.innerHTML = html;
        }

        function formatTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            let interval = seconds / 31536000;
            if (interval > 1) return Math.floor(interval) + " years ago";
            interval = seconds / 2592000;
            if (interval > 1) return Math.floor(interval) + " months ago";
            interval = seconds / 86400;
            if (interval > 1) return Math.floor(interval) + " days ago";
            interval = seconds / 3600;
            if (interval > 1) return Math.floor(interval) + " hours ago";
            interval = seconds / 60;
            if (interval > 1) return Math.floor(interval) + " minutes ago";
            return Math.floor(seconds) + " seconds ago";
        }

        async function fetchTableData(role, prefix) {
            let url = `${API_BASE}/admin/users?role=${role}`;
            
            const startDate = document.getElementById(`filter-${prefix}-start`)?.value;
            const endDate = document.getElementById(`filter-${prefix}-end`)?.value;
            
            if (startDate) {
                url += `&start_date=${startDate}`;
            }
            if (endDate) {
                url += `&end_date=${endDate}`;
            }

            const res = await fetch(url);
            const data = await res.json();
            const body = document.getElementById(`${prefix}-body`);
            body.innerHTML = data.users.map(u => `
                <tr><td>#${u.id}</td><td>${u.username}</td><td>${u.email}</td><td>${new Date(u.created_at).toLocaleDateString()}</td></tr>
            `).join('');
        }

        async function fetchList(endpoint, prefix) {
            let url = `${API_BASE}/${endpoint}`;
            const params = new URLSearchParams();

            const employerId = document.getElementById(`filter-${prefix}-employer`)?.value;
            if (employerId) {
                params.append('employer_id', employerId);
            }

            if (prefix === 'jobs') {
                const title = document.getElementById('filter-jobs-title')?.value;
                if (title) {
                    params.append('title', title);
                }
            }

            if (prefix === 'payments') {
                const startDate = document.getElementById('filter-payments-start')?.value;
                const endDate = document.getElementById('filter-payments-end')?.value;
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);
            }

            const queryString = params.toString();
            if (queryString) {
                url += `?${queryString}`;
            }

            const res = await fetch(url);
            const data = await res.json();
            const body = document.getElementById(`${prefix}-body`);
            const items = data[prefix] || [];
            
            if (prefix === 'jobs') {
                body.innerHTML = items.map(j => `<tr><td>#${j.id}</td><td>${j.title}</td><td>${j.employer_name}</td><td>${new Date(j.created_at).toLocaleDateString()}</td></tr>`).join('');
            } else {
                body.innerHTML = items.map(p => `<tr><td>#${p.id}</td><td>${p.employer_name}</td><td style="color: #10b981; font-weight: 600;">Rs. ${p.amount.toLocaleString()}</td><td><span class="badge badge-approved">${p.payment_type}</span></td><td>${new Date(p.created_at).toLocaleDateString()}</td></tr>`).join('');
            }
        }

        async function requestWithdrawal() {
            const balanceText = document.getElementById('main-wallet-balance').textContent.replace(/,/g, '');
            const currentBalance = parseFloat(balanceText);

            if (currentBalance <= 0) {
                Swal.fire('Empty Wallet', 'You have no funds to withdraw.', 'info');
                return;
            }

            const { value: amount } = await Swal.fire({
                title: 'Withdraw Funds',
                input: 'number',
                inputLabel: `Maximum available: Rs. ${currentBalance.toLocaleString()}`,
                inputPlaceholder: 'Enter amount to withdraw',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) return 'You need to write something!';
                    if (value <= 0) return 'Must be greater than 0';
                    if (value > currentBalance) return 'Insufficient balance';
                }
            });

            if (amount) {
                try {
                    const res = await fetch(`${API_BASE}/admin/withdraw`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ amount })
                    });
                    const data = await res.json();
                    
                    if (!res.ok) throw new Error(data.error || 'Server error');
                    
                    Swal.fire('Success!', data.message, 'success');
                    fetchFilteredStats(); // Update balances
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
            }
        }
    </script>
</body>
</html>
