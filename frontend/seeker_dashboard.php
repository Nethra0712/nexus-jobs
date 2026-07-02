<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seeker Dashboard - Nexus Jobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <!-- SweetAlert for nice popup moduls -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            Nexus Jobs
        </div>
            <button onclick="showJobs()" class="btn btn-outline" style="border: none; font-weight: 500;">Find Jobs</button>
            <button onclick="showSavedJobs()" class="btn btn-outline" style="border: none; font-weight: 500;">Saved Jobs</button>
            <button onclick="showInterviews()" class="btn btn-outline" style="border: none; font-weight: 500;">Interviews</button>
            <button onclick="showEditProfile()" class="btn btn-outline" style="border: none; font-weight: 500;">Edit Profile</button>
            <span id="user-greeting" style="font-weight: 500; margin-left: 1rem; border-left: 1px solid var(--border); padding-left: 1rem;"></span>
            <button onclick="logout()" class="btn btn-secondary">Logout</button>
        </div>
    </nav>

    <div id="alert-box" class="alert container" style="margin-top: 1rem; max-width: 800px; display: none;"></div>

    <div id="profile-warning-banner" class="container" style="max-width: 800px; margin-top: 1rem; display: none;">
        <div style="background: rgba(245, 158, 11, 0.1); border-left: 4px solid var(--warning); padding: 1rem; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
            <div style="color: #92400e;">
                <strong>Profile Incomplete:</strong> Please add your skills to unlock Smart Recommendations and AI Tools!
            </div>
            <button onclick="showEditProfile()" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Add Skills Now</button>
        </div>
    </div>

    <div id="main-content-area" class="container" style="max-width: 800px; margin-top: 2rem;">
        <div id="search-header" style="margin-bottom: 2rem;">
            <h2>Discover New Opportunities</h2>
            <p>Find your next big career advancement here.</p>
            
            <div style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.5rem; background: var(--surface); padding: 1rem; border-radius: 12px; border: 1px solid var(--border);">
                <input type="text" id="job-search" placeholder="Search keywords..." style="flex: 1; min-width: 150px; padding: 0.5rem; border: 1px solid var(--border); border-radius: 6px;" onkeyup="filterJobs()">
                
                <select id="filter-type" style="width: auto; padding: 0.5rem; border-radius: 6px; border: 1px solid var(--border);" onchange="filterJobs()">
                    <option value="">All Types</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Contract">Contract</option>
                    <option value="Freelance">Freelance</option>
                    <option value="Internship">Internship</option>
                </select>

                <select id="filter-industry" style="width: auto; padding: 0.5rem; border-radius: 6px; border: 1px solid var(--border);" onchange="filterJobs()">
                    <option value="">All Industries</option>
                    <option value="Technology">Technology</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Finance">Finance</option>
                    <option value="Education">Education</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Design">Design</option>
                    <option value="Other">Other</option>
                </select>

                <select id="sort-by" style="width: auto; padding: 0.5rem; border-radius: 6px; border: 1px solid var(--border); background: rgba(79, 70, 229, 0.05); color: var(--primary);" onchange="filterJobs()">
                    <option value="newest">Sort: Newest</option>
                    <option value="match">Sort: Match Score</option>
                    <option value="salary_high">Sort: Highest Salary</option>
                </select>
            </div>
        </div>

        <div id="recommended-container" style="margin-bottom: 3rem;">
            <!-- Recommended Jobs injected here -->
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>All Available Jobs</h3>
        </div>
        
        <div id="jobs-container">
            <!-- Jobs will be injected here -->
            <div style="text-align: center; padding: 3rem; color: var(--text-muted)">
                Loading available positions...
            </div>
        </div>

        <div id="interviews-container" style="display: none; margin-top: 2rem;">
        </div>

        <!-- Edit Profile Section (Hidden by default) -->
        <div id="edit-profile-container" class="card" style="display: none; margin-top: 2rem;">
            <h2 style="margin-bottom: 1.5rem;">Edit Profile</h2>
            <form onsubmit="handleProfileUpdate(event)">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" id="prof-username" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="prof-email" required>
                </div>
                <div class="form-group mt-3">
                    <label>Your Skills (Comma separated)</label>
                    <input type="text" id="prof-skills" placeholder="e.g. Graphic Design, Adobe Illustrator, HTML">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Used for your Smart Recommendations and AI Cover Letters.</small>
                </div>
                <div class="form-group mt-3">
                    <label>New Password (Optional)</label>
                    <input type="password" id="prof-password" placeholder="Leave blank to keep current password">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Chatbot UI -->
    <div id="chatbot-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-family: 'Inter', sans-serif;">
        <button id="chatbot-toggle" onclick="toggleChatbot()" style="background: var(--primary); color: white; border: none; border-radius: 50%; width: 60px; height: 60px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.2s;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        </button>
        
        <div id="chatbot-window" style="display: none; position: absolute; bottom: 80px; right: 0; width: 350px; background: var(--surface); border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); border: 1px solid var(--border); flex-direction: column; overflow: hidden; max-height: 500px;">
            <div style="background: var(--primary); color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
                    AI Coach
                </h3>
                <button onclick="toggleChatbot()" style="background: none; border: none; color: white; cursor: pointer;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <div id="chatbot-messages" style="padding: 1rem; overflow-y: auto; height: 300px; display: flex; flex-direction: column; gap: 1rem; background: var(--background);">
                <div style="align-self: flex-start; background: var(--surface); padding: 0.75rem 1rem; border-radius: 12px; border-bottom-left-radius: 2px; border: 1px solid var(--border); color: var(--text-main); font-size: 0.9rem; max-width: 85%;">
                    Hi there! I'm your Nexus AI Coach. I can help with career advice, resume tips, interview prep, and job search strategies. How can I help you today?
                </div>
            </div>
            
            <div style="padding: 1rem; border-top: 1px solid var(--border); background: var(--surface); display: flex; gap: 0.5rem;">
                <input type="text" id="chatbot-input" placeholder="Ask me anything..." onkeypress="handleChatKeyPress(event)" style="flex: 1; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                <button onclick="sendChatMessage()" style="background: var(--primary); color: white; border: none; border-radius: 6px; padding: 0 1rem; cursor: pointer;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Apply Modal Structure (Hidden) -->
    <template id="apply-modal-template">
        <form id="apply-form" style="text-align: left;">
            <div class="form-group">
                <label>Your Skills (Comma separated)</label>
                <input type="text" id="apply-skills" class="swal2-input" style="width: 100%; margin: 0; margin-bottom: 1rem;" placeholder="e.g. Python, MySQL, React" required>
                <small style="color: var(--text-muted); font-size: 0.8rem;">Enter skills honestly for the best match algorithm results.</small>
            </div>
            <div class="form-group mt-3">
                <label>Upload Resume (PDF only)</label>
                <input type="file" id="apply-resume" class="swal2-file" accept=".pdf" style="width: 100%; margin: 0;">
            </div>
            <div class="form-group mt-3">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="margin: 0;">Cover Letter / Message (Optional)</label>
                    <button type="button" id="btn-ai-generate" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.8rem; display: flex; align-items: center; gap: 0.25rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
                        Auto-Generate ✨
                    </button>
                </div>
                <textarea id="apply-message" class="swal2-textarea" style="width: 100%; margin: 0; min-height: 120px;" placeholder="Say hello to the employer!"></textarea>
            </div>
        </form>
    </template>

    <script>
        const API_BASE = 'http://localhost:5000/api';
        let currentUser = null;
        let allJobs = [];
        let filteredJobs = [];
        let savedJobs = [];
        let savedJobIds = new Set();
        let interviews = [];

        window.onload = () => {
            const userStr = localStorage.getItem('user');
            if (!userStr) {
                window.location.href = 'index.php';
                return;
            }
            currentUser = JSON.parse(userStr);
            if (currentUser.role !== 'seeker') {
                window.location.href = 'employer_dashboard.php';
            }
            
            document.getElementById('user-greeting').textContent = `Hello, ${currentUser.username}`;
            
            // Show warning banner if skills are empty
            if (!currentUser.skills || currentUser.skills.trim() === '') {
                document.getElementById('profile-warning-banner').style.display = 'block';
            }
            
            fetchJobs();
            fetchSavedJobs();
            fetchInterviews();
        };

        function logout() {
            localStorage.removeItem('user');
            window.location.href = 'index.php';
        }

        function showAlert(msg, isError = false) {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'block';
            alertBox.className = `alert container ${isError ? 'alert-error' : 'alert-success'}`;
            alertBox.textContent = msg;
            setTimeout(() => alertBox.style.display = 'none', 5000);
        }

        function showJobs() {
            document.getElementById('search-header').style.display = 'block';
            document.getElementById('jobs-container').style.display = 'block';
            document.getElementById('interviews-container').style.display = 'none';
            document.getElementById('recommended-container').style.display = 'block'; // Ensure recommended jobs are shown again
            document.getElementById('profile-warning-banner').style.display = 'block'; // Re-show if needed
            document.getElementById('edit-profile-container').style.display = 'none';
            renderJobs(); // render all jobs
        }

        function showSavedJobs() {
            document.getElementById('search-header').style.display = 'none';
            document.getElementById('recommended-container').style.display = 'none';
            document.getElementById('profile-warning-banner').style.display = 'none';
            document.getElementById('edit-profile-container').style.display = 'none';
            document.getElementById('interviews-container').style.display = 'none';
            document.getElementById('jobs-container').style.display = 'block';
            renderSavedJobs();
        }

        function showInterviews() {
            document.getElementById('search-header').style.display = 'none';
            document.getElementById('jobs-container').style.display = 'none';
            document.getElementById('recommended-container').style.display = 'none';
            document.getElementById('profile-warning-banner').style.display = 'none';
            document.getElementById('edit-profile-container').style.display = 'none';
            document.getElementById('interviews-container').style.display = 'block';
            fetchInterviews(); // Refresh to ensure latest status
        }

        function showEditProfile() {
            document.getElementById('search-header').style.display = 'none';
            document.getElementById('jobs-container').style.display = 'none';
            document.getElementById('interviews-container').style.display = 'none';
            document.getElementById('recommended-container').style.display = 'none';
            document.getElementById('profile-warning-banner').style.display = 'none'; // hide it when resolving
            document.getElementById('edit-profile-container').style.display = 'block';
            
            document.getElementById('prof-username').value = currentUser.username;
            document.getElementById('prof-email').value = currentUser.email;
            document.getElementById('prof-skills').value = currentUser.skills || '';
            document.getElementById('prof-password').value = '';
        }

        async function handleProfileUpdate(event) {
            event.preventDefault();
            const username = document.getElementById('prof-username').value;
            const email = document.getElementById('prof-email').value;
            const skills = document.getElementById('prof-skills').value;
            const password = document.getElementById('prof-password').value;

            const updateData = { username, email, skills };
            if (password) {
                updateData.password = password;
            }

            try {
                const res = await fetch(`${API_BASE}/seeker/${currentUser.user_id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${currentUser.token}`
                    },
                    body: JSON.stringify(updateData)
                });

                const data = await res.json();

                if (res.ok) {
                    showAlert('Profile updated successfully!');
                    // Update local storage and currentUser object
                    currentUser = { ...currentUser, ...updateData };
                    localStorage.setItem('user', JSON.stringify(currentUser));
                    document.getElementById('user-greeting').textContent = `Hello, ${currentUser.username}`;
                    
                    // Re-evaluate warning banner
                    if (!currentUser.skills || currentUser.skills.trim() === '') {
                        document.getElementById('profile-warning-banner').style.display = 'block';
                    } else {
                        document.getElementById('profile-warning-banner').style.display = 'none';
                    }

                    // Re-fetch jobs to update match scores if skills changed
                    fetchJobs();
                    showJobs(); // Go back to job list
                } else {
                    showAlert(data.message || 'Failed to update profile.', true);
                }
            } catch (err) {
                showAlert('An error occurred while updating profile.', true);
            }
        }

        async function fetchJobs() {
            try {
                const res = await fetch(`${API_BASE}/jobs`);
                const data = await res.json();
                
                // Calculate match score for each job locally based on seeker's skills
                const mySkills = (currentUser.skills || '').toLowerCase().split(',').map(s => s.trim()).filter(s => s);
                
                allJobs = (data.jobs || []).map(job => {
                    const reqSkills = (job.skills_required || '').toLowerCase().split(',').map(s => s.trim()).filter(s => s);
                    let matchCount = 0;
                    
                    if (reqSkills.length > 0 && mySkills.length > 0) {
                        reqSkills.forEach(req => {
                            if (mySkills.some(my => my.includes(req) || req.includes(my))) {
                                matchCount++;
                            }
                        });
                        job.matchScore = Math.round((matchCount / reqSkills.length) * 100);
                    } else {
                        job.matchScore = 0;
                    }
                    return job;
                });
                
                filteredJobs = [...allJobs];
                
                // Check if there was a redirected search query from the landing page
                const searchTopic = localStorage.getItem('search_topic');
                if (searchTopic) {
                    document.getElementById('job-search').value = searchTopic;
                    localStorage.removeItem('search_topic');
                    filterJobs();
                } else {
                    renderJobs();
                }
            } catch (err) {
                document.getElementById('jobs-container').innerHTML = 
                    `<div class="alert alert-error" style="display:block">Failed to load jobs. Ensure backend is running.</div>`;
            }
        }

        async function fetchSavedJobs() {
            try {
                const res = await fetch(`${API_BASE}/seeker/${currentUser.user_id}/saved_jobs`);
                const jobs = await res.json();
                savedJobs = jobs;
                savedJobIds = new Set(jobs.map(j => j.id));
                renderJobs(); // Re-render to update the bookmark icons
            } catch (err) {
                console.error("Failed to fetch saved jobs", err);
            }
        }

        async function toggleSaveJob(jobId) {
            const isSaved = savedJobIds.has(jobId);
            const method = isSaved ? 'DELETE' : 'POST';
            
            try {
                const res = await fetch(`${API_BASE}/seeker/${currentUser.user_id}/saved_jobs/${jobId}`, {
                    method: method
                });
                
                if (res.ok) {
                    if (isSaved) {
                        savedJobIds.delete(jobId);
                        savedJobs = savedJobs.filter(j => j.id !== jobId);
                        showAlert('Job removed from saved list.');
                    } else {
                        savedJobIds.add(jobId);
                        // find the full job obj to add to savedJobs
                        const job = allJobs.find(j => j.id === jobId);
                        if (job) savedJobs.push(job);
                        showAlert('Job saved successfully!');
                    }
                    
                    // Re-render current view to update the UI
                    if (document.getElementById('search-header').style.display === 'none' && 
                        document.getElementById('edit-profile-container').style.display === 'none') {
                        renderSavedJobs();
                    } else {
                        renderJobs();
                    }
                } else {
                    showAlert('Failed to update saved job status.', true);
                }
            } catch (err) {
                showAlert('Error updating saved job status.', true);
            }
        }

        function filterJobs() {
            const query = document.getElementById('job-search').value.toLowerCase();
            const typeFill = document.getElementById('filter-type').value;
            const indFill = document.getElementById('filter-industry').value;
            const sortBy = document.getElementById('sort-by').value;

            filteredJobs = allJobs.filter(job => {
                const matchesSearch = !query || 
                    job.title.toLowerCase().includes(query) || 
                    job.description.toLowerCase().includes(query) ||
                    job.skills_required.toLowerCase().includes(query) ||
                    job.employer_name.toLowerCase().includes(query);
                    
                const matchesType = !typeFill || job.job_type === typeFill;
                const matchesInd = !indFill || job.industry === indFill;
                
                return matchesSearch && matchesType && matchesInd;
            });

            // Sorting
            filteredJobs.sort((a, b) => {
                if (sortBy === 'match') {
                    return b.matchScore - a.matchScore;
                } else if (sortBy === 'salary_high') {
                    const salA = a.salary ? parseFloat(a.salary) : 0;
                    const salB = b.salary ? parseFloat(b.salary) : 0;
                    return salB - salA;
                } else {
                    // Newest by default (already sorted by DB usually, but just in case)
                    return new Date(b.created_at) - new Date(a.created_at);
                }
            });

            renderJobs();
        }

        function renderJobs() {
            const container = document.getElementById('jobs-container');
            const recommendedContainer = document.getElementById('recommended-container');
            
            // Handle Smart Recommendations
            const query = document.getElementById('job-search').value.toLowerCase();
            const typeFill = document.getElementById('filter-type').value;
            const indFill = document.getElementById('filter-industry').value;
            
            // Only show recommendations if no filters are actively applied
            if (!query && !typeFill && !indFill) {
                // Find top matching jobs >= 50%
                const topMatches = [...allJobs].sort((a,b) => b.matchScore - a.matchScore).filter(j => j.matchScore >= 50).slice(0, 3);
                
                if (topMatches.length > 0) {
                    recommendedContainer.innerHTML = `
                        <h3 style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            Recommended For You
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            ${topMatches.map(job => `
                                <div class="card" style="border-top: 4px solid var(--primary); padding: 1.5rem; display: flex; flex-direction: column;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                        <h4 style="margin-bottom: 0.25rem;">${job.title}</h4>
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">${job.matchScore}% Match</span>
                                    </div>
                                    <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem;">${job.employer_name}</div>
                                    <p style="font-size: 0.875rem; color: var(--text-main); margin-bottom: 1rem; flex: 1;">
                                        ${job.description.substring(0, 80)}...
                                    </p>
                                    <button onclick="applyForJob(${job.id}, '${job.title.replace(/'/g, "\\'")}', '${job.description.replace(/\n'/g, "\\'")}')" class="btn btn-primary" style="padding: 0.5rem; width: 100%;">Apply Now</button>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    recommendedContainer.style.display = 'block';
                } else {
                    recommendedContainer.style.display = 'none';
                }
            } else {
                recommendedContainer.style.display = 'none';
            }
            
            if (filteredJobs.length === 0) {
                container.innerHTML = `
                    <div class="card" style="text-align: center; padding: 3rem;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="margin-bottom: 1rem"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                        <h3>No Jobs Available</h3>
                        <p style="margin-top: 0.5rem;">Check back later for new opportunities.</p>
                    </div>`;
                return;
            }

            container.innerHTML = filteredJobs.map(job => `
                <div class="card" style="margin-bottom: 1.5rem; transition: transform 0.2s;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; color: var(--primary); margin-bottom: 0.25rem;">${job.title}</h3>
                            <div style="font-size: 0.875rem; color: var(--text-muted); display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.5rem;">
                                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> ${job.employer_name}</span>
                                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> ${new Date(job.created_at).toLocaleDateString()}</span>
                                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> ${job.salary ? 'Rs. ' + parseInt(job.salary).toLocaleString() + '/mo' : 'Salary Not Disclosed'}</span>
                            </div>
                        </div>
                         <div style="text-align: right;">
                            <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: var(--primary); margin-bottom: 0.25rem; display: inline-block;">${job.job_type || 'Full-time'}</span>
                            <br>
                            <span style="font-size: 0.75rem; font-weight: 600; color: ${job.matchScore >= 80 ? 'var(--success)' : 'var(--text-muted)'}">${job.matchScore}% Match</span>
                        </div>
                    </div>
                    
                    <p style="margin-bottom: 1.5rem; color: var(--text-main);">${job.description.replace(/\n/g, '<br>')}</p>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <strong>Required Skills:</strong> 
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                            ${job.skills_required.split(',').map(s => `<span style="background: var(--background); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; border: 1px solid var(--border)">${s.trim()}</span>`).join('')}
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid var(--border); padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <button onclick="toggleSaveJob(${job.id})" class="btn btn-outline" style="padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem; border-color: ${savedJobIds.has(job.id) ? 'var(--primary)' : 'var(--border)'}; color: ${savedJobIds.has(job.id) ? 'var(--primary)' : 'var(--text-main)'}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="${savedJobIds.has(job.id) ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                            ${savedJobIds.has(job.id) ? 'Saved' : 'Save Job'}
                        </button>
                        <button onclick="applyForJob(${job.id}, '${job.title.replace(/'/g, "\\'")}', '${job.description.replace(/\n'/g, "\\'")}', '${job.employer_name.replace(/'/g, "\\'")}')" class="btn btn-primary" style="padding: 0.5rem 1.5rem">
                            Apply Now
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function renderSavedJobs() {
            const container = document.getElementById('jobs-container');
            
            if (savedJobs.length === 0) {
                container.innerHTML = `
                    <div class="card" style="text-align: center; padding: 3rem;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="margin-bottom: 1rem"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                        <h3 style="margin-top: 0.5rem;">No Saved Jobs</h3>
                        <p style="margin-top: 0.5rem; color: var(--text-muted);">Jobs you bookmark will appear here.</p>
                        <button onclick="showJobs()" class="btn btn-primary mt-3">Find Jobs</button>
                    </div>`;
                return;
            }

            container.innerHTML = `<h2 style="margin-bottom: 1.5rem;">Your Saved Jobs</h2>` + savedJobs.map(job => `
                <div class="card" style="margin-bottom: 1.5rem; transition: transform 0.2s;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; color: var(--primary); margin-bottom: 0.25rem;">${job.title}</h3>
                            <div style="font-size: 0.875rem; color: var(--text-muted); display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.5rem;">
                                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> ${job.employer_name}</span>
                                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> ${job.salary ? 'Rs. ' + parseInt(job.salary).toLocaleString() + '/mo' : 'Salary Not Disclosed'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <p style="margin-bottom: 1.5rem; color: var(--text-main);">${job.description.replace(/\n/g, '<br>')}</p>
                    
                    <div style="border-top: 1px solid var(--border); padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <button onclick="toggleSaveJob(${job.id})" class="btn btn-outline" style="padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem; border-color: var(--primary); color: var(--primary)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                            Unsave Job
                        </button>
                        <button onclick="applyForJob(${job.id}, '${job.title.replace(/'/g, "\\'")}', '${job.description.replace(/\n'/g, "\\'")}', '${job.employer_name.replace(/'/g, "\\'")}')" class="btn btn-primary" style="padding: 0.5rem 1.5rem">
                            Apply Now
                        </button>
                    </div>
                </div>
            `).join('');
        }

        async function applyForJob(jobId, jobTitle, jobDescription, employerName) {
            // Use SweetAlert2 to show a beautiful modal popup
            const { value: formValues } = await Swal.fire({
                title: `Apply for ${jobTitle}`,
                html: document.getElementById('apply-modal-template').innerHTML,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit Application',
                confirmButtonColor: 'var(--primary)',
                didOpen: () => {
                    const aiBtn = document.getElementById('btn-ai-generate');
                    if (aiBtn) {
                        aiBtn.onclick = () => generateAICoverLetter(jobTitle, jobDescription, employerName, aiBtn);
                    }
                },
                preConfirm: () => {
                    const skills = document.getElementById('apply-skills').value;
                    const message = document.getElementById('apply-message').value;
                    const resume = document.getElementById('apply-resume').files[0];
                    if (!skills) {
                        Swal.showValidationMessage('Please enter your skills');
                    }
                    if (resume && resume.type !== 'application/pdf') {
                        Swal.showValidationMessage('Only PDF files are accepted');
                    }
                    return { skills, message, resume };
                }
            });

            if (formValues) {
                // Submit to backend
                try {
                    Swal.fire({
                        title: 'Submitting...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    
                    const formData = new FormData();
                    formData.append('seeker_id', currentUser.user_id);
                    formData.append('seeker_skills', formValues.skills);
                    formData.append('message', formValues.message);
                    if (formValues.resume) {
                        formData.append('resume', formValues.resume);
                    }

                    const res = await fetch(`${API_BASE}/jobs/${jobId}/apply`, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await res.json();
                    
                    if (!res.ok) throw new Error(data.error);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: `Application submitted successfully. Your skill match score is ${data.score}%`,
                        confirmButtonColor: 'var(--primary)'
                    });
                    
                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to apply',
                        text: err.message,
                        confirmButtonColor: 'var(--primary)'
                    });
                }
            }
        }

        async function fetchInterviews() {
            try {
                const res = await fetch(`${API_BASE}/seeker/${currentUser.user_id}/interviews`);
                interviews = await res.json();
                renderInterviews();
            } catch (err) {
                console.error("Failed to fetch interviews", err);
            }
        }

        function renderInterviews() {
            const container = document.getElementById('interviews-container');
            
            if (interviews.length === 0) {
                container.innerHTML = `
                    <div class="card" style="text-align: center; padding: 3rem;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="margin-bottom: 1rem"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <h3 style="margin-top: 0.5rem;">No Interviews Yet</h3>
                        <p style="margin-top: 0.5rem; color: var(--text-muted);">Apply to more jobs to get interview invitations!</p>
                    </div>`;
                return;
            }

            container.innerHTML = `<h2 style="margin-bottom: 1.5rem;">Your Interviews</h2>` + interviews.map(inv => {
                let badgeClass = 'badge-pending';
                if (inv.status === 'accepted') badgeClass = 'badge-approved';
                if (inv.status === 'declined') badgeClass = 'badge-rejected';
                
                const timeStr = new Date(inv.proposed_time).toLocaleString();

                return `
                <div class="card" style="margin-bottom: 1.5rem; border-left: 4px solid ${inv.status === 'pending' ? 'var(--primary)' : (inv.status === 'accepted' ? 'var(--success)' : 'var(--danger)')};">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">${inv.job_title}</h3>
                            <div style="color: var(--text-muted); margin-bottom: 0.5rem;">${inv.employer_name}</div>
                            <div style="font-weight: 500; font-size: 1.1rem; margin-bottom: 1rem; color: var(--text-main);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 0.25rem;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                Proposed Time: ${timeStr}
                            </div>
                        </div>
                        <div>
                            <span class="badge ${badgeClass}">${inv.status.toUpperCase()}</span>
                        </div>
                    </div>
                    
                    ${inv.status === 'pending' ? `
                        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                            <button onclick="respondToInterview(${inv.interview_id}, 'accepted')" class="btn btn-success" style="padding: 0.5rem 1.5rem;">Accept Interview</button>
                            <button onclick="respondToInterview(${inv.interview_id}, 'declined')" class="btn btn-outline" style="padding: 0.5rem 1.5rem;">Decline</button>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.75rem;">An email notification will be sent to the employer automatically.</div>
                    ` : inv.status === 'accepted' ? `
                        <div style="margin-top: 1rem; padding: 1rem; background: rgba(16, 185, 129, 0.05); border-radius: 8px;">
                            <strong>Confirmed!</strong> The employer will contact you at <strong>${currentUser.email}</strong> or provide a meeting link via email. 
                            <br>Employer Contact: <a href="mailto:${inv.employer_email}">${inv.employer_email}</a>
                        </div>
                    ` : ''}
                </div>
            `}).join('');
        }

        async function respondToInterview(interviewId, status) {
            try {
                // Optimistic UI update or loading state
                Swal.fire({title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                
                const res = await fetch(`${API_BASE}/seeker/${currentUser.user_id}/interviews/${interviewId}/respond`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ status })
                });
                
                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Failed to update');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                
                fetchInterviews();
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        }

        async function handleProfileUpdate(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            btn.disabled = true;
            btn.textContent = 'Saving...';
            
            const reqBody = {
                username: document.getElementById('prof-username').value,
                email: document.getElementById('prof-email').value,
                skills: document.getElementById('prof-skills').value
            };
            
            const newPass = document.getElementById('prof-password').value;
            if (newPass) reqBody.password = newPass;

            try {
                const res = await fetch(`${API_BASE}/user/${currentUser.user_id}`, {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(reqBody)
                });
                const data = await res.json();
                
                if (!res.ok) throw new Error(data.error || 'Update failed');
                
                // Update local session
                currentUser.username = reqBody.username;
                currentUser.email = reqBody.email;
                currentUser.skills = reqBody.skills;
                localStorage.setItem('user', JSON.stringify(currentUser));
                
                document.getElementById('user-greeting').textContent = `Hello, ${currentUser.username}`;
                showAlert('Profile updated successfully!', false);
                document.getElementById('prof-password').value = '';
                
                // Refresh jobs so the match scores recalculate
                fetchJobs();
                
                // Optionally show the jobs view again
                setTimeout(() => showJobs(), 1500);
                
            } catch (err) {
                showAlert(err.message, true);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Save Changes';
            }
        }

        async function generateAICoverLetter(jobTitle, jobDesc, employerName, btnEl) {
            const skillsInput = document.getElementById('apply-skills').value || currentUser.skills;
            if (!skillsInput) {
                Swal.showValidationMessage('Please enter your skills first to generate a customized letter.');
                return;
            }

            const originalText = btnEl.innerHTML;
            btnEl.disabled = true;
            btnEl.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> Generating...`;

            try {
                const res = await fetch(`${API_BASE}/generate_cover_letter`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        seeker_name: currentUser.username,
                        seeker_skills: skillsInput,
                        job_title: jobTitle,
                        job_description: jobDesc,
                        employer_name: employerName
                    })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Failed to generate');

                document.getElementById('apply-message').value = data.cover_letter;

            } catch (err) {
                console.error("AI Gen Error:", err);
                Swal.showValidationMessage('AI Generation failed: ' + err.message);
            } finally {
                btnEl.disabled = false;
                btnEl.innerHTML = originalText;
            }
        }

        let isChatbotOpen = false;

        function toggleChatbot() {
            const chatWindow = document.getElementById('chatbot-window');
            isChatbotOpen = !isChatbotOpen;
            chatWindow.style.display = isChatbotOpen ? 'flex' : 'none';
            if (isChatbotOpen) {
                document.getElementById('chatbot-input').focus();
            }
        }

        function handleChatKeyPress(event) {
            if (event.key === 'Enter') {
                sendChatMessage();
            }
        }

        async function sendChatMessage() {
            const inputEl = document.getElementById('chatbot-input');
            const message = inputEl.value.trim();
            if (!message) return;

            inputEl.value = '';
            appendChatMessage(message, 'user');

            // Show typing indicator
            const typingId = 'typing-' + Date.now();
            appendChatMessage('<div class="typing-indicator" style="display: flex; gap: 4px; align-items: center; height: 20px;"><span style="width: 6px; height: 6px; background: var(--text-muted); border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both;"></span><span style="width: 6px; height: 6px; background: var(--text-muted); border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; animation-delay: -0.32s;"></span><span style="width: 6px; height: 6px; background: var(--text-muted); border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; animation-delay: -0.16s;"></span></div>', 'ai', typingId);

            try {
                const res = await fetch(`${API_BASE}/chatbot`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message })
                });

                const data = await res.json();
                
                // Remove typing indicator
                const typingEl = document.getElementById(typingId);
                if (typingEl) typingEl.remove();

                if (res.ok) {
                    appendChatMessage(data.reply, 'ai');
                } else {
                    appendChatMessage("Sorry, I encountered an error. Please try again.", 'ai');
                }
            } catch (err) {
                const typingEl = document.getElementById(typingId);
                if (typingEl) typingEl.remove();
                appendChatMessage("Network error. Could not reach the server.", 'ai');
            }
        }

        function appendChatMessage(text, sender, id = null) {
            const msgsContainer = document.getElementById('chatbot-messages');
            const msgObj = document.createElement('div');
            if (id) msgObj.id = id;
            
            if (sender === 'user') {
                msgObj.style.alignSelf = 'flex-end';
                msgObj.style.background = 'var(--primary)';
                msgObj.style.color = 'white';
                msgObj.style.padding = '0.75rem 1rem';
                msgObj.style.borderRadius = '12px';
                msgObj.style.borderBottomRightRadius = '2px';
                msgObj.style.maxWidth = '85%';
                msgObj.style.fontSize = '0.9rem';
            } else {
                msgObj.style.alignSelf = 'flex-start';
                msgObj.style.background = 'var(--surface)';
                msgObj.style.color = 'var(--text-main)';
                msgObj.style.padding = '0.75rem 1rem';
                msgObj.style.borderRadius = '12px';
                msgObj.style.borderBottomLeftRadius = '2px';
                msgObj.style.border = '1px solid var(--border)';
                msgObj.style.maxWidth = '85%';
                msgObj.style.fontSize = '0.9rem';
            }
            
            msgObj.innerHTML = text;
            msgsContainer.appendChild(msgObj);
            msgsContainer.scrollTop = msgsContainer.scrollHeight;
        }
    </script>
</body>
</html>
