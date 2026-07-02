import re
import mysql.connector

def calculate_skill_match_score(job_skills, seeker_skills):
    """
    Calculates the percentage of job skills the seeker has.
    Case-insensitive exact matching after stripping whitespace.
    """
    if not job_skills or not seeker_skills:
        return 0.0

    # Parse and clean skills (e.g., "Python, MySQL, React" -> ['python', 'mysql', 'react'])
    parse = lambda text: [s.strip().lower() for s in text.split(',') if s.strip()]
    
    job_skills_list = parse(job_skills)
    seeker_skills_list = parse(seeker_skills)

    if not job_skills_list:
        return 100.0 # No skills required

    matched_skills = set(job_skills_list).intersection(set(seeker_skills_list))
    
    score = (len(matched_skills) / len(job_skills_list)) * 100
    return float(round(score, 2))


def apply_for_job(db, job_id, seeker_id, seeker_skills, message, resume_path=None):
    cursor = db.cursor(dictionary=True)
    
    # 1. Fetch job to get required skills
    cursor.execute("SELECT skills_required FROM jobs WHERE id = %s", (job_id,))
    job = cursor.fetchone()
    if not job:
        cursor.close()
        return {"error": "Job not found"}
        
    job_skills = job['skills_required']
    
    # 2. Calculate match score
    score = calculate_skill_match_score(job_skills, seeker_skills)
    
    # 3. Insert application
    try:
        query = """
        INSERT INTO applications (job_id, seeker_id, seeker_skills, skills_matched_score, apply_message, resume_path)
        VALUES (%s, %s, %s, %s, %s, %s)
        """
        cursor.execute(query, (job_id, seeker_id, seeker_skills, score, message, resume_path))
        db.commit()
        result = {"message": "Application submitted successfully", "score": score}
    except Exception as e:
        # Handle duplicate entry due to UNIQUE constraint
        if "Duplicate" in str(e):
            result = {"error": "You have already applied for this job"}
        else:
            result = {"error": str(e)}
            
    cursor.close()
    return result


def get_job_applicants(db, job_id, employer_id):
    cursor = db.cursor(dictionary=True)
    
    # Verify the job belongs to this employer
    cursor.execute("SELECT id FROM jobs WHERE id = %s AND employer_id = %s", (job_id, employer_id))
    if not cursor.fetchone():
        cursor.close()
        return {"error": "Unauthorized or job not found"}

    # Fetch applications ranked by score DESC
    query = """
    SELECT a.id as application_id, u.username as applicant_name, u.email, 
           a.seeker_skills, a.skills_matched_score, a.status, a.apply_message, a.created_at, a.resume_path
    FROM applications a
    JOIN users u ON a.seeker_id = u.id
    WHERE a.job_id = %s
    ORDER BY a.skills_matched_score DESC, a.created_at ASC
    """
    cursor.execute(query, (job_id,))
    applicants = cursor.fetchall()
    
    # Convert datetime objects to strings
    for app in applicants:
        app['created_at'] = str(app['created_at'])
        # Convert Decimal to float for JSON serialization
        app['skills_matched_score'] = float(app['skills_matched_score'])
        
    cursor.close()
    return {"applicants": applicants}


def update_application_status(db, application_id, employer_id, status):
    if status not in ['approved', 'not approved']:
        return {"error": "Invalid status"}
        
    cursor = db.cursor(dictionary=True)
    
    # Verify employer owns the job this application is for
    verify_query = """
    SELECT a.id FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.id = %s AND j.employer_id = %s
    """
    cursor.execute(verify_query, (application_id, employer_id))
    if not cursor.fetchone():
        cursor.close()
        return {"error": "Unauthorized or application not found"}

    # Update status
    cursor.execute("UPDATE applications SET status = %s WHERE id = %s", (status, application_id))
    
    # If approved, draft and send the automated email
    if status == 'approved':
        email_query = """
        SELECT 
            s.email AS seeker_email, s.username AS seeker_name,
            e.email AS employer_email, e.username AS employer_name,
            j.title AS job_title
        FROM applications a
        JOIN users s ON a.seeker_id = s.id
        JOIN jobs j ON a.job_id = j.id
        JOIN users e ON j.employer_id = e.id
        WHERE a.id = %s
        """
        cursor.execute(email_query, (application_id,))
        details = cursor.fetchone()
        
        if details:
            try:
                from email_service import send_approval_email
                send_approval_email(
                    details['seeker_email'], details['seeker_name'],
                    details['employer_email'], details['employer_name'],
                    details['job_title']
                )
            except Exception as e:
                print(f"Warning: Could not send approval email: {e}")

    db.commit()
    cursor.close()
    
    return {"message": f"Applicant marked as {status}"}

# --- JOB BOOKMARKING --- #

def save_job(db, seeker_id, job_id):
    cursor = db.cursor(dictionary=True)
    try:
        query = "INSERT INTO saved_jobs (seeker_id, job_id) VALUES (%s, %s)"
        cursor.execute(query, (seeker_id, job_id))
        db.commit()
        return {"message": "Job saved successfully"}
    except mysql.connector.IntegrityError:
        return {"error": "Job already saved or invalid job/seeker"}
    except Exception as e:
        return {"error": str(e)}
    finally:
        cursor.close()

def unsave_job(db, seeker_id, job_id):
    cursor = db.cursor(dictionary=True)
    try:
        query = "DELETE FROM saved_jobs WHERE seeker_id = %s AND job_id = %s"
        cursor.execute(query, (seeker_id, job_id))
        db.commit()
        if cursor.rowcount > 0:
            return {"message": "Job unsaved successfully"}
        return {"error": "Job was not saved"}
    except Exception as e:
        return {"error": str(e)}
    finally:
        cursor.close()

def get_saved_jobs(db, seeker_id):
    cursor = db.cursor(dictionary=True)
    query = """
    SELECT j.*, e.username AS employer_name 
    FROM saved_jobs sj
    JOIN jobs j ON sj.job_id = j.id
    JOIN users e ON j.employer_id = e.id
    WHERE sj.seeker_id = %s
    ORDER BY sj.created_at DESC
    """
    cursor.execute(query, (seeker_id,))
    jobs = cursor.fetchall()
    cursor.close()
    return jobs
