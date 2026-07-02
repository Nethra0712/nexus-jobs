from flask import Flask, request, jsonify, send_from_directory
from flask_cors import CORS
import mysql.connector
import os
from werkzeug.security import generate_password_hash, check_password_hash
from werkzeug.utils import secure_filename

# Initialize Flask app
app = Flask(__name__)
# Enable CORS for all routes so PHP/frontend can make requests
CORS(app)

UPLOAD_FOLDER = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'uploads', 'resumes')
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Database Configuration
import os
from dotenv import load_dotenv
load_dotenv()

db_config = {
    'host': os.environ.get('DB_HOST', 'localhost'),
    'user': os.environ.get('DB_USER', 'root'),
    'password': os.environ.get('DB_PASSWORD', ''),
    'database': os.environ.get('DB_NAME', 'job_portal')
}

def get_db_connection():
    try:
        connection = mysql.connector.connect(**db_config)
        return connection
    except mysql.connector.Error as err:
        print(f"Error connecting to database: {err}")
        return None

# Import separated route logic
from auth import create_user, login_user, update_user_profile
from jobs import apply_for_job, get_job_applicants, update_application_status, save_job, unsave_job, get_saved_jobs
from interviews import propose_interview, get_seeker_interviews, respond_interview
import ai_service

# --- ERROR HANDLER --- #
@app.errorhandler(500)
def handle_500(e):
    return jsonify({"error": "An internal server error occurred."}), 500

# --- AUTH ROUTES --- #
@app.route('/api/register', methods=['POST'])
def register():
    data = request.json
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    required_fields = ['username', 'email', 'password', 'role']
    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields"}), 400

    result = create_user(db, data['username'], data['email'], data['password'], data['role'])
    
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 201

@app.route('/api/login', methods=['POST'])
def login():
    data = request.json
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    if 'username' not in data or 'password' not in data:
        return jsonify({"error": "Missing username or password"}), 400

    result = login_user(db, data['username'], data['password'])
    
    if "error" in result:
        return jsonify(result), 401
    return jsonify(result), 200

@app.route('/api/user/<int:user_id>', methods=['PUT'])
def update_profile(user_id):
    data = request.json
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    if 'username' not in data or 'email' not in data:
        return jsonify({"error": "Missing required fields (username, email)"}), 400
        
    new_password_hash = None
    if data.get('password'):
        new_password_hash = generate_password_hash(data['password'])
        
    new_skills = data.get('skills', None)
        
    result = update_user_profile(db, user_id, data['username'], data['email'], new_password_hash, new_skills)
    
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 200

# --- JOB ROUTES --- #
@app.route('/api/jobs', methods=['GET'])
def get_all_jobs():
    """Fetch all active jobs (for seekers)"""
    db = get_db_connection()
    cursor = db.cursor(dictionary=True)
    
    query = """
    SELECT j.id, j.title, j.description, j.skills_required, j.job_type, j.industry, j.salary, u.username as employer_name, j.created_at
    FROM jobs j
    JOIN users u ON j.employer_id = u.id
    ORDER BY j.created_at DESC
    """
    cursor.execute(query)
    jobs = cursor.fetchall()
    
    # Convert datetime to string
    for job in jobs:
        job['created_at'] = str(job['created_at'])
        
    cursor.close()
    return jsonify({"jobs": jobs}), 200


@app.route('/api/employer/<int:employer_id>/jobs', methods=['GET'])
def get_employer_jobs(employer_id):
    """Fetch jobs posted by a specific employer"""
    db = get_db_connection()
    cursor = db.cursor(dictionary=True)
    
    cursor.execute("SELECT * FROM jobs WHERE employer_id = %s ORDER BY created_at DESC", (employer_id,))
    jobs = cursor.fetchall()
    
    for job in jobs:
        job['created_at'] = str(job['created_at'])
        
    cursor.close()
    return jsonify({"jobs": jobs}), 200

@app.route('/api/employer/<int:employer_id>/jobs/<int:job_id>', methods=['DELETE'])
def delete_employer_job(employer_id, job_id):
    """Employer deletes a job they posted"""
    db = get_db_connection()
    cursor = db.cursor()
    
    try:
        # First verify the job belongs to this employer
        cursor.execute("SELECT id FROM jobs WHERE id = %s AND employer_id = %s", (job_id, employer_id))
        if not cursor.fetchone():
            return jsonify({"error": "Job not found or unauthorized to delete"}), 404
            
        # Delete dependent applications first to avoid foreign key constraints
        cursor.execute("DELETE FROM applications WHERE job_id = %s", (job_id,))
        
        # Now delete the job
        cursor.execute("DELETE FROM jobs WHERE id = %s AND employer_id = %s", (job_id, employer_id))
        db.commit()
        return jsonify({"message": "Job deleted successfully"}), 200
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500
    finally:
        cursor.close()


@app.route('/api/jobs', methods=['POST'])
def create_job():
    """Employer posts a new job"""
    data = request.json
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
    
    required_fields = ['employer_id', 'title', 'description', 'skills_required']
    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields"}), 400
        
    cursor = db.cursor()
    
    # Check if this is the employer's first job
    try:
        cursor.execute("SELECT COUNT(*) FROM jobs WHERE employer_id = %s", (data['employer_id'],))
        result = cursor.fetchone()
        job_count = result[0] if result else 0
        
        is_free = (job_count == 0)
        payment_amount = 0.00 if is_free else 10000.00
        payment_type = 'first_job_free' if is_free else 'job_post_fee'
        
        query = """
        INSERT INTO jobs (employer_id, title, description, skills_required, external_apply_link, job_type, industry, salary)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        values = (
            data['employer_id'], 
            data['title'], 
            data['description'], 
            data['skills_required'],
            data.get('external_apply_link', ''),
            data.get('job_type', 'Full-time'),
            data.get('industry', 'Technology'),
            data.get('salary', None)
        )
        
        cursor.execute(query, values)
        job_id = cursor.lastrowid
        
        # Process payment record
        payment_query = """
        INSERT INTO payments (employer_id, amount, payment_type)
        VALUES (%s, %s, %s)
        """
        cursor.execute(payment_query, (data['employer_id'], payment_amount, payment_type))
        
        # Credit the admin wallet only if not free
        if not is_free:
            admin_wallet_query = """
            UPDATE users SET wallet_balance = wallet_balance + %s WHERE role = 'admin'
            """
            cursor.execute(admin_wallet_query, (10000.00,))
        
        db.commit()
        
        message = "First job posted for free!" if is_free else "Job posted successfully!"
        return jsonify({"message": message, "job_id": job_id, "is_free": is_free}), 201
        
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500
    finally:
        cursor.close()

# --- APPLICATION ROUTES (Native Portal Feature) --- #
@app.route('/api/jobs/<int:job_id>/apply', methods=['POST'])
def apply_job(job_id):
    """Seeker applies for a job directly in the portal"""
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    # Check if dealing with FormData (multipart/form-data)
    if 'multipart/form-data' in request.content_type:
        data = request.form
        file = request.files.get('resume')
    else:
        data = request.json or {}
        file = None
        
    required_fields = ['seeker_id', 'seeker_skills']
    if not all(field in data for field in required_fields):
        return jsonify({"error": "Missing required fields. Provide seeker_id, seeker_skills"}), 400
        
    resume_path = None
    if file and file.filename:
        if file.filename.lower().endswith('.pdf'):
            filename = secure_filename(f"{job_id}_{data['seeker_id']}_{file.filename}")
            filepath = os.path.join(app.config['UPLOAD_FOLDER'], filename)
            file.save(filepath)
            resume_path = filename
        else:
            return jsonify({"error": "Only PDF files are allowed for resumes"}), 400

    result = apply_for_job(
        db, job_id, data['seeker_id'], data['seeker_skills'], data.get('message', ''), resume_path
    )
    
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 201


# --- SAVED JOBS ROUTES --- #
@app.route('/api/seeker/<int:seeker_id>/saved_jobs/<int:job_id>', methods=['POST'])
def api_save_job(seeker_id, job_id):
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
    
    result = save_job(db, seeker_id, job_id)
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 201

@app.route('/api/seeker/<int:seeker_id>/saved_jobs/<int:job_id>', methods=['DELETE'])
def api_unsave_job(seeker_id, job_id):
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
    
    result = unsave_job(db, seeker_id, job_id)
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 200

@app.route('/api/seeker/<int:seeker_id>/saved_jobs', methods=['GET'])
def api_get_saved_jobs(seeker_id):
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    jobs = get_saved_jobs(db, seeker_id)
    return jsonify(jobs), 200


@app.route('/api/generate_cover_letter', methods=['POST'])
def generate_cl():
    data = request.json or {}
    
    seeker_name = data.get('seeker_name', '')
    seeker_skills = data.get('seeker_skills', '')
    job_title = data.get('job_title', 'this job')
    job_description = data.get('job_description', '')
    employer_name = data.get('employer_name', 'Hiring Manager')

    try:
        letter = ai_service.generate_cover_letter(seeker_name, seeker_skills, job_title, job_description, employer_name)
        return jsonify({"cover_letter": letter}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/api/chatbot', methods=['POST'])
def api_chatbot():
    data = request.json or {}
    message = data.get('message', '')
    
    if not message:
        return jsonify({"error": "Message is required"}), 400
        
    try:
        reply = ai_service.generate_chat_response(message)
        return jsonify({"reply": reply}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500


@app.route('/api/employer/<int:employer_id>/jobs/<int:job_id>/applicants', methods=['GET'])
def fetch_applicants(employer_id, job_id):
    """Employer gets ranked list of applicants for a specific job"""
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    result = get_job_applicants(db, job_id, employer_id)
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 200


@app.route('/api/employer/<int:employer_id>/applications/<int:app_id>/status', methods=['PUT'])
def update_status(employer_id, app_id):
    """Employer approves or rejects an application"""
    data = request.json or {}
    status = data.get('status')
    
    if status not in ['approved', 'not approved']:
        return jsonify({"error": "Status must be 'approved' or 'not approved'"}), 400
        
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    result = update_application_status(db, app_id, employer_id, status)
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 200

# --- INTERVIEW ROUTES --- #
@app.route('/api/employer/<int:employer_id>/applications/<int:app_id>/interview', methods=['POST'])
def api_propose_interview(employer_id, app_id):
    data = request.json or {}
    proposed_time = data.get('proposed_time')
    
    if not proposed_time:
        return jsonify({"error": "Proposed time is required"}), 400
        
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    result = propose_interview(db, employer_id, app_id, proposed_time)
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 201

@app.route('/api/seeker/<int:seeker_id>/interviews', methods=['GET'])
def api_get_interviews(seeker_id):
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    interviews = get_seeker_interviews(db, seeker_id)
    return jsonify(interviews), 200

@app.route('/api/seeker/<int:seeker_id>/interviews/<int:interview_id>/respond', methods=['POST'])
def api_respond_interview(seeker_id, interview_id):
    data = request.json or {}
    status = data.get('status')
    
    if status not in ['accepted', 'declined']:
        return jsonify({"error": "Status must be 'accepted' or 'declined'"}), 400
        
    db = get_db_connection()
    if not db:
        return jsonify({"error": "Database connection failed"}), 500
        
    result = respond_interview(db, seeker_id, interview_id, status)
    if "error" in result:
        return jsonify(result), 400
    return jsonify(result), 200

@app.route('/api/download/resume/<filename>', methods=['GET'])
def download_resume(filename):
    """Serves the requested PDF resume file securely"""
    try:
        return send_from_directory(app.config['UPLOAD_FOLDER'], filename, as_attachment=False)
    except Exception as e:
        return jsonify({"error": "File not found or permission denied"}), 404

# --- ADMIN ROUTES --- #
@app.route('/api/admin/stats', methods=['GET'])
def get_admin_stats():
    """Fetch advanced filtered KPI stats for reports & analytics"""
    import datetime
    now = datetime.datetime.now()
    
    # Get filters from query params, default to current month/year
    month = request.args.get('month', now.month, type=int)
    year = request.args.get('year', now.year, type=int)
    
    db = get_db_connection()
    cursor = db.cursor(dictionary=True)
    
    stats = {
        "filtered_month": month,
        "filtered_year": year,
        "revenue_target": 50000.00 # Demo target
    }
    
    # 1. Monthly KPIs (Filtered)
    # Total Seekers Registered in this month
    cursor.execute("""
        SELECT COUNT(*) as count FROM users 
        WHERE role = 'seeker' AND MONTH(created_at) = %s AND YEAR(created_at) = %s
    """, (month, year))
    stats['monthly_seekers'] = cursor.fetchone()['count']
    
    # Total Employers Registered in this month
    cursor.execute("""
        SELECT COUNT(*) as count FROM users 
        WHERE role = 'employer' AND MONTH(created_at) = %s AND YEAR(created_at) = %s
    """, (month, year))
    stats['monthly_employers'] = cursor.fetchone()['count']
    
    # Total Revenue in this month
    cursor.execute("""
        SELECT SUM(amount) as total FROM payments 
        WHERE MONTH(created_at) = %s AND YEAR(created_at) = %s
    """, (month, year))
    res = cursor.fetchone()
    stats['monthly_revenue'] = float(res['total'] or 0)
    
    # Collection rate calculation
    stats['collection_rate'] = round((stats['monthly_revenue'] / stats['revenue_target']) * 100, 2)
    
    # Active students (seekers) count - All time
    cursor.execute("SELECT COUNT(*) as count FROM users WHERE role = 'seeker'")
    stats['total_seekers'] = cursor.fetchone()['count']

    # 2. Distribution Data (Industry / Category)
    try:
        cursor.execute("""
            SELECT industry as label, COUNT(*) as count 
            FROM jobs 
            GROUP BY industry ORDER BY count DESC LIMIT 5
        """)
        stats['industry_distribution'] = cursor.fetchall()
    except:
        stats['industry_distribution'] = []

    # 3. Trend Data (Revenue Trend Last 6 Months)
    cursor.execute("""
        SELECT DATE_FORMAT(created_at, '%b') as month, SUM(amount) as revenue 
        FROM payments 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY month, YEAR(created_at), MONTH(created_at)
        ORDER BY YEAR(created_at) ASC, MONTH(created_at) ASC
    """)
    stats['revenue_trend'] = cursor.fetchall()

    # 4. Top Insights (Top 5 Recent Applications or high-value items)
    cursor.execute("""
        SELECT a.id, u.username as seeker_name, j.title as job_title, a.status, a.created_at
        FROM applications a
        JOIN users u ON a.seeker_id = u.id
        JOIN jobs j ON a.job_id = j.id
        ORDER BY a.created_at DESC LIMIT 5
    """)
    stats['top_insights'] = cursor.fetchall()
    for item in stats['top_insights']:
        item['created_at'] = str(item['created_at'])

    # 5. Recent Activity (Global)
    activities = []
    cursor.execute("SELECT 'user' as type, username as title, created_at FROM users ORDER BY created_at DESC LIMIT 5")
    for row in cursor.fetchall():
        row['created_at'] = str(row['created_at'])
        activities.append(row)
        
    cursor.execute("SELECT 'job' as type, title, created_at FROM jobs ORDER BY created_at DESC LIMIT 5")
    for row in cursor.fetchall():
        row['created_at'] = str(row['created_at'])
        activities.append(row)
        
    activities.sort(key=lambda x: x['created_at'], reverse=True)
    stats['recent_activities'] = activities[:10]

    # Global Walllet / Revenue
    cursor.execute("SELECT SUM(amount) as total FROM payments")
    stats['total_revenue'] = float(cursor.fetchone()['total'] or 0)
    
    cursor.execute("SELECT wallet_balance FROM users WHERE role = 'admin' LIMIT 1")
    admin_user = cursor.fetchone()
    stats['wallet_balance'] = float(admin_user['wallet_balance']) if admin_user else 0.0
    
    cursor.close()
    return jsonify(stats), 200


@app.route('/api/admin/users', methods=['GET'])
def get_admin_users():
    """Fetch users based on role query parameter"""
    role = request.args.get('role', 'seeker')
    start_date = request.args.get('start_date')
    end_date = request.args.get('end_date')
    if role not in ['seeker', 'employer', 'admin']:
        return jsonify({"error": "Invalid role"}), 400
        
    db = get_db_connection()
    cursor = db.cursor(dictionary=True)
    
    query = "SELECT id, username, email, created_at FROM users WHERE role = %s"
    params = [role]
    
    if start_date:
        query += " AND DATE(created_at) >= %s"
        params.append(start_date)
        
    if end_date:
        query += " AND DATE(created_at) <= %s"
        params.append(end_date)
        
    query += " ORDER BY created_at DESC"
    
    cursor.execute(query, params)
    users = cursor.fetchall()
    
    for u in users:
        u['created_at'] = str(u['created_at'])
        
    cursor.close()
    return jsonify({"users": users}), 200

@app.route('/api/admin/jobs', methods=['GET'])
def get_admin_all_jobs():
    """Fetch all jobs for admin view"""
    employer_id = request.args.get('employer_id')
    title = request.args.get('title')
    db = get_db_connection()
    cursor = db.cursor(dictionary=True)
    
    query = """
    SELECT j.id, j.title, u.username as employer_name, j.created_at
    FROM jobs j
    JOIN users u ON j.employer_id = u.id
    WHERE 1=1
    """
    params = []
    if employer_id:
        query += " AND j.employer_id = %s"
        params.append(employer_id)
        
    if title:
        query += " AND j.title LIKE %s"
        params.append(f"%{title}%")
        
    query += " ORDER BY j.created_at DESC"
    
    cursor.execute(query, params)
    jobs = cursor.fetchall()
    
    for j in jobs:
        j['created_at'] = str(j['created_at'])
        
    cursor.close()
    return jsonify({"jobs": jobs}), 200

@app.route('/api/admin/payments', methods=['GET'])
def get_admin_payments():
    """Fetch all payments"""
    employer_id = request.args.get('employer_id')
    start_date = request.args.get('start_date')
    end_date = request.args.get('end_date')
    db = get_db_connection()
    cursor = db.cursor(dictionary=True)
    
    query = """
    SELECT p.id, u.username as employer_name, p.amount, p.payment_type, p.created_at 
    FROM payments p
    JOIN users u ON p.employer_id = u.id
    WHERE 1=1
    """
    params = []
    if employer_id:
        query += " AND p.employer_id = %s"
        params.append(employer_id)

    if start_date:
        query += " AND DATE(p.created_at) >= %s"
        params.append(start_date)

    if end_date:
        query += " AND DATE(p.created_at) <= %s"
        params.append(end_date)
        
    query += " ORDER BY p.created_at DESC"
    
    cursor.execute(query, params)
    payments = cursor.fetchall()
    
    for p in payments:
        p['created_at'] = str(p['created_at'])
        p['amount'] = float(p['amount'])
        
    cursor.close()
    return jsonify({"payments": payments}), 200

@app.route('/api/admin/withdraw', methods=['POST'])
def admin_withdraw():
    """Admin withdraws money from wallet"""
    data = request.json
    db = get_db_connection()
    cursor = db.cursor(dictionary=True)
    
    amount = float(data.get('amount', 0))
    if amount <= 0:
        return jsonify({"error": "Invalid amount"}), 400
        
    cursor.execute("SELECT id, wallet_balance FROM users WHERE role = 'admin' LIMIT 1")
    admin_user = cursor.fetchone()
    
    if not admin_user:
        return jsonify({"error": "Admin not found"}), 404
        
    if float(admin_user['wallet_balance']) < amount:
        return jsonify({"error": "Insufficient balance"}), 400
        
    # Deduct amount
    cursor.execute("UPDATE users SET wallet_balance = wallet_balance - %s WHERE id = %s", (amount, admin_user['id']))
    
    # Alternatively one could log this withdrawal in a transactions table, but we will keep it simple here.
    db.commit()
    cursor.close()
    
    return jsonify({"message": f"Successfully withdrew Rs. {amount:,.2f}"}), 200

# Run the API
if __name__ == '__main__':
    print("Job Portal Backend Server Starting on Port 5000...")
    app.run(debug=True, host='0.0.0.0', port=5000)
