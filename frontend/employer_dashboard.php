<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - Nexus Jobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            Nexus Jobs
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <button onclick="showSection('post-job')" class="btn" style="background: transparent; border: 1px solid var(--border); color: var(--text);">Post a Job</button>
            <button onclick="showSection('view-jobs')" class="btn" style="background: transparent; border: 1px solid var(--border); color: var(--text);">My Posted Jobs</button>
            <button onclick="showSection('edit-profile')" class="btn" style="background: transparent; border: 1px solid var(--border); color: var(--text);">Edit Profile</button>
            <span id="user-greeting" style="font-weight: 500; margin-left: 1rem; color: var(--primary);"></span>
            <button onclick="logout()" class="btn btn-secondary">Logout</button>
        </div>
    </nav>

    <!-- Swapped dashboard-grid for a centered max-width container -->
    <div class="container" style="max-width: 100%; margin: 2rem auto; padding: 0 4rem; width: 100%;">
        
        <!-- Main Content Area -->
        <main style="width: 100%;">
            <div id="alert-box" class="alert"></div>

            <!-- Section 1: Post Job -->
            <section id="section-post-job" class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <div>
                        <h2>Post a New Job</h2>
                        <p style="margin-top: 0.5rem;">Create a new opportunity to find top talent.</p>
                    </div>
                    <div id="cost-indicator" style="background: rgba(79, 70, 229, 0.1); padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid var(--primary);">
                        <span style="color: var(--primary); font-weight: 600;">Premium Feature:</span> Rs. 10,000 / post
                    </div>
                </div>
                
                <form onsubmit="handlePostJob(event)">
                    <div class="form-group">
                        <label>Job Title</label>
                        <input type="text" id="job-title" required placeholder="e.g. Senior Backend Engineer">
                    </div>
                    
                    <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label>Job Type</label>
                            <select id="job-type" required>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div>
                            <label>Industry / Category</label>
                            <select id="job-industry" required>
                                <option value="Technology">Technology</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Finance">Finance</option>
                                <option value="Education">Education</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Design">Design</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Salary (LKR / Month) - Optional</label>
                        <input type="number" id="job-salary" placeholder="e.g. 150000">
                    </div>

                    <div class="form-group">
                        <label>Job Description</label>
                        <textarea id="job-description" rows="5" required placeholder="Describe the role..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Required Skills (Comma separated)</label>
                        <input type="text" id="job-skills" required placeholder="e.g. Python, Flask, MySQL, AWS">
                        <small style="color: var(--text-muted); display: block; margin-top: 5px;">We use these skills to automatically rank your applicants.</small>
                    </div>

                    <div class="form-group">
                        <label>External Application Link (Optional)</label>
                        <input type="url" id="job-link" placeholder="https://docs.google.com/forms/...">
                    </div>

                    <button type="submit" id="post-job-submit" class="btn btn-primary" style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; width: 100%;">
                        <svg id="post-job-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span id="post-job-btn-text">Proceed to Payment (Rs. 10,000)</span>
                    </button>
                </form>
            </section>

            <!-- Section 2: View Jobs & Applicants -->
            <section id="section-view-jobs" style="display: none;">
                <div class="card" style="margin-bottom: 2rem;">
                    <h2>Your Posted Jobs</h2>
                    <div id="jobs-list" style="margin-top: 1rem;">
                        <!-- Jobs injected here -->
                    </div>
                </div>
                
                <!-- Applicants Table Container -->
                <div class="card" id="applicants-section" style="display: none;">
                    <h2 id="applicants-job-title" style="margin-bottom: 1rem;">Applicants</h2>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Applicant Name</th>
                                    <th>Match Score</th>
                                    <th>Skills</th>
                                    <th>Resume</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="applicants-table-body">
                                <!-- Applicants injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Section 3: Edit Profile -->
            <section id="section-edit-profile" class="card" style="display: none;">
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
                        <label>New Password (Optional)</label>
                        <input type="password" id="prof-password" placeholder="Leave blank to keep current password">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </section>

        </main>
    </div>

    <!-- Payment Modal Template (Hidden) -->
    <template id="payment-modal-template">
        <form id="payment-form" style="text-align: left; padding: 1rem 0;">
            <div style="background: var(--surface); padding: 1rem; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 500;">Total Amount Due:</span>
                <span style="font-size: 1.25rem; font-weight: 700; color: #2e7d32;">Rs. 10,000</span>
            </div>
            
            <div class="form-group">
                <label>Cardholder Name</label>
                <input type="text" id="pay-name" class="swal2-input" style="width: 100%; margin: 0; margin-bottom: 1rem;" placeholder="John Doe" required>
            </div>
            
            <div class="form-group">
                <label>Card Number</label>
                <div style="position: relative;">
                    <input type="text" id="pay-card" class="swal2-input" style="width: 100%; margin: 0; margin-bottom: 1rem; padding-left: 2.5rem;" placeholder="0000 0000 0000 0000" maxlength="19" required>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="position: absolute; left: 10px; top: 12px;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label>Expiry Date</label>
                    <input type="text" id="pay-exp" class="swal2-input" style="width: 100%; margin: 0; margin-bottom: 1rem;" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>CVV</label>
                    <input type="text" id="pay-cvv" class="swal2-input" style="width: 100%; margin: 0; margin-bottom: 1rem;" placeholder="123" maxlength="4" required>
                </div>
            </div>
            
            <div style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-top: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Payments are securely processed locally.
            </div>
        </form>
    </template>

    <!-- Interview Scheduling Modal Template (Hidden) -->
    <template id="interview-modal-template">
        <form id="interview-form" style="text-align: left; padding: 1rem 0;">
            <div class="form-group">
                <label>Proposed Date & Time</label>
                <input type="datetime-local" id="interview-time" class="swal2-input" style="width: 100%; margin: 0; margin-bottom: 1rem;" required>
            </div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 1rem;">
                The applicant will receive an email to Accept or Decline this proposed time.
            </div>
        </form>
    </template>

    <script>
        const API_BASE = 'http://localhost:5000/api';
        let currentUser = null;

        // Init
        window.onload = () => {
            const userStr = localStorage.getItem('user');
            if (!userStr) {
                window.location.href = 'index.php';
                return;
            }
            currentUser = JSON.parse(userStr);
            if (currentUser.role !== 'employer') {
                window.location.href = 'seeker_dashboard.php';
            }
            
            document.getElementById('user-greeting').textContent = `Hello, ${currentUser.username}`;
            showSection('post-job');
        };

        function logout() {
            localStorage.removeItem('user');
            window.location.href = 'index.php';
        }

        function showAlert(msg, isError = false) {
            const alertBox = document.getElementById('alert-box');
            alertBox.style.display = 'block';
            alertBox.className = `alert ${isError ? 'alert-error' : 'alert-success'}`;
            alertBox.textContent = msg;
            setTimeout(() => alertBox.style.display = 'none', 5000);
        }

        function showSection(sectionId) {
            document.getElementById('section-post-job').style.display = 'none';
            document.getElementById('section-view-jobs').style.display = 'none';
            document.getElementById('section-edit-profile').style.display = 'none';
            
            document.getElementById(`section-${sectionId}`).style.display = 'block';
            
            if (sectionId === 'post-job') {
                checkFirstJobStatus();
            }
            if (sectionId === 'view-jobs') {
                document.getElementById('applicants-section').style.display = 'none';
                fetchMyJobs();
            }
            if (sectionId === 'edit-profile') {
                document.getElementById('prof-username').value = currentUser.username;
                document.getElementById('prof-email').value = currentUser.email;
                document.getElementById('prof-password').value = '';
            }
        }

        async function handlePostJob(e) {
            e.preventDefault();
            
            // Gather form data first
            const jobData = {
                employer_id: currentUser.user_id,
                title: document.getElementById('job-title').value,
                description: document.getElementById('job-description').value,
                skills_required: document.getElementById('job-skills').value,
                job_type: document.getElementById('job-type').value,
                industry: document.getElementById('job-industry').value,
                salary: document.getElementById('job-salary').value || null,
                external_apply_link: document.getElementById('job-link').value
            };

            if (!isFirstJob) {
                // Trigger simulated Payment Modal only if not the first job
                const { value: isPaid } = await Swal.fire({
                    title: 'Checkout',
                    html: document.getElementById('payment-modal-template').innerHTML,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Pay Rs. 10,000 & Post',
                    confirmButtonColor: 'var(--primary)',
                    preConfirm: () => {
                        const name = document.getElementById('pay-name').value;
                        const card = document.getElementById('pay-card').value;
                        const exp = document.getElementById('pay-exp').value;
                        const cvv = document.getElementById('pay-cvv').value;
                        
                        if (!name || !card || !exp || !cvv) {
                            Swal.showValidationMessage('Please fill out all payment fields');
                            return false;
                        }
                        if (card.length < 15) {
                            Swal.showValidationMessage('Please enter a valid card number');
                            return false;
                        }
                        return true;
                    }
                });
                
                if (!isPaid) return; // User cancelled payment
            }

            // Proceed with job posting after successful payment simulation
            Swal.fire({
                title: isFirstJob ? 'Publishing Job...' : 'Processing Payment...',
                html: isFirstJob ? 'Setting up your first free job post.' : 'Securing your transaction and publishing your job post.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                // Simulate network latency for payment processing
                await new Promise(r => setTimeout(r, 1500));

                const res = await fetch(`${API_BASE}/jobs`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(jobData)
                });
                
                if (!res.ok) throw new Error('Failed to post job to server after payment');
                
                Swal.fire({
                    icon: 'success',
                    title: isFirstJob ? 'Job Published!' : 'Payment Successful!',
                    text: isFirstJob ? 'Your first job has been posted for free.' : 'Your job has been published to the Nexus Jobs network.',
                    confirmButtonColor: 'var(--primary)',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    e.target.reset();
                    showSection('view-jobs');
                });
                
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: err.message,
                    confirmButtonColor: 'var(--primary)'
                });
            }
        }

        async function fetchMyJobs() {
            try {
                const res = await fetch(`${API_BASE}/employer/${currentUser.user_id}/jobs`);
                const data = await res.json();
                
                const html = data.jobs.map(job => `
                    <div style="padding: 1rem; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3 style="margin-bottom: 0.25rem">${job.title}</h3>
                            <p style="font-size: 0.875rem">Required: ${job.skills_required || 'None'}</p>
                        </div>
                        <div style="display: flex; gap: 0.5rem">
                            <button onclick="viewApplicants(${job.id}, '${job.title.replace(/'/g, "\\'")}')" class="btn btn-secondary">View Applicants</button>
                            <button onclick="deleteJob(${job.id})" class="btn btn-danger">Delete Job</button>
                        </div>
                    </div>
                `).join('');
                
                document.getElementById('jobs-list').innerHTML = html || '<p>You haven\'t posted any jobs yet.</p>';
            } catch (err) {
                showAlert('Failed to fetch jobs', true);
            }
        }

        async function deleteJob(jobId) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the job setup and all its applications.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--surface)',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const res = await fetch(`${API_BASE}/employer/${currentUser.user_id}/jobs/${jobId}`, {
                        method: 'DELETE'
                    });
                    
                    const data = await res.json();
                    
                    if (!res.ok) throw new Error(data.error || 'Failed to delete job');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Job has been deleted.',
                        confirmButtonColor: 'var(--primary)',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    fetchMyJobs(); // Refresh the list
                } catch (err) {
                    showAlert(err.message, true);
                }
            }
        }

        async function viewApplicants(jobId, jobTitle) {
            try {
                const res = await fetch(`${API_BASE}/employer/${currentUser.user_id}/jobs/${jobId}/applicants`);
                const data = await res.json();
                
                document.getElementById('applicants-section').style.display = 'block';
                document.getElementById('applicants-job-title').textContent = `Applicants for: ${jobTitle}`;
                
                if (!data.applicants || data.applicants.length === 0) {
                    document.getElementById('applicants-table-body').innerHTML = '<tr><td colspan="8" style="text-align: center;">No applicants yet</td></tr>';
                    return;
                }

                const html = data.applicants.map((app, index) => {
                    const isTop10 = index < 10;
                    
                    let badgeClass = 'badge-pending';
                    if (app.status === 'approved') badgeClass = 'badge-approved';
                    if (app.status === 'not approved') badgeClass = 'badge-rejected';

                    // Determine row styling for top 10
                    const rowStyle = isTop10 ? 'background: rgba(16, 185, 129, 0.05); border-left: 3px solid var(--success);' : '';
                    const rankBadge = isTop10 ? `<span style="font-size: 1.2rem; font-weight: bold; color: var(--success)">#${index+1}</span>` : `#${index+1}`;

                    return `
                        <tr style="${rowStyle}">
                            <td>${rankBadge}</td>
                            <td>
                                <div style="font-weight: 500">${app.applicant_name}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted)">${app.email}</div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: ${app.skills_matched_score >= 80 ? 'var(--success)' : 'inherit'}">
                                    ${app.skills_matched_score}%
                                </div>
                            </td>
                            <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${app.seeker_skills}">
                                ${app.seeker_skills}
                            </td>
                            <td>
                                ${app.resume_path ? `<a href="${API_BASE.replace('/api', '')}/api/download/resume/${app.resume_path}" target="_blank" class="btn btn-outline" style="padding: 0.2rem 0.4rem; font-size: 0.75rem; text-decoration: none;">Download</a>` : '-'}
                            </td>
                            <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${app.apply_message}">
                                ${app.apply_message || '-'}
                            </td>
                            <td><span class="badge ${badgeClass}">${app.status.toUpperCase()}</span></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem">
                                    ${app.status !== 'approved' ? `
                                        <button onclick="updateStatus(${app.application_id}, 'approved', ${jobId}, '${jobTitle}')" 
                                                class="btn btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Approve</button>
                                    ` : ''}
                                    ${app.status !== 'not approved' ? `
                                        <button onclick="updateStatus(${app.application_id}, 'not approved', ${jobId}, '${jobTitle}')" 
                                                class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Reject</button>
                                    ` : ''}
                                    <button onclick="scheduleInterview(${app.application_id}, ${jobId}, '${jobTitle}')" 
                                            class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Schedule Interview</button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
                
                document.getElementById('applicants-table-body').innerHTML = html;
                
            } catch (err) {
                showAlert('Failed to fetch applicants', true);
            }
        }

        async function updateStatus(applicationId, status, jobId, jobTitle) {
            try {
                const res = await fetch(`${API_BASE}/employer/${currentUser.user_id}/applications/${applicationId}/status`, {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ status })
                });
                
                if (!res.ok) throw new Error('Failed to update status');
                
                showAlert(`Application marked as ${status}`);
                // Refresh list
                viewApplicants(jobId, jobTitle);
            } catch (err) {
                showAlert(err.message, true);
            }
        }

        async function scheduleInterview(appId, jobId, jobTitle) {
            const { value: formValues } = await Swal.fire({
                title: 'Schedule Interview',
                html: document.getElementById('interview-modal-template').innerHTML,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Propose Time',
                confirmButtonColor: 'var(--primary)',
                preConfirm: () => {
                    const time = document.getElementById('interview-time').value;
                    if (!time) {
                        Swal.showValidationMessage('Please select a date and time');
                    }
                    return time;
                }
            });

            if (formValues) {
                try {
                    Swal.fire({
                        title: 'Sending Proposal...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const proposedTime = formValues;
                    const res = await fetch(`${API_BASE}/employer/${currentUser.user_id}/applications/${appId}/interview`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ proposed_time: proposedTime })
                    });
                    
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.error || 'Failed to propose interview');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Proposed!',
                        text: 'Interview invitation sent to applicant.',
                        confirmButtonColor: 'var(--primary)',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    viewApplicants(jobId, jobTitle);
                } catch (err) {
                    Swal.fire('Error', err.message, 'error');
                }
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
                localStorage.setItem('user', JSON.stringify(currentUser));
                
                document.getElementById('user-greeting').textContent = `Hello, ${currentUser.username}`;
                showAlert('Profile updated successfully!', false);
                document.getElementById('prof-password').value = '';
                
            } catch (err) {
                showAlert(err.message, true);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Save Changes';
            }
        }

        let isFirstJob = false;
        async function checkFirstJobStatus() {
            try {
                const res = await fetch(`${API_BASE}/employer/${currentUser.user_id}/jobs`);
                const data = await res.json();
                isFirstJob = (data.jobs.length === 0);
                updatePostJobUI();
            } catch (err) {
                console.error("Failed to check job status", err);
            }
        }

        function updatePostJobUI() {
            const indicator = document.getElementById('cost-indicator');
            const btnText = document.getElementById('post-job-btn-text');
            const icon = document.getElementById('post-job-icon');

            if (isFirstJob) {
                indicator.innerHTML = '<span style="color: #2e7d32; font-weight: 700;">Special Offer:</span> First Job is FREE!';
                indicator.style.borderColor = '#2e7d32';
                indicator.style.background = 'rgba(46, 125, 50, 0.1)';
                btnText.textContent = 'Post for Free';
                if (icon) icon.style.display = 'none';
            } else {
                indicator.innerHTML = '<span style="color: var(--primary); font-weight: 600;">Premium Feature:</span> Rs. 10,000 / post';
                indicator.style.borderColor = 'var(--primary)';
                indicator.style.background = 'rgba(79, 70, 229, 0.1)';
                btnText.textContent = 'Proceed to Payment (Rs. 10,000)';
                if (icon) icon.style.display = 'block';
            }
        }
    </script>
</body>
</html>
