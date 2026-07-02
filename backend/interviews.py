import mysql.connector
from datetime import datetime
from email_service import (
    send_interview_proposal_email,
    send_interview_confirmation_email,
    send_interview_declined_email
)

def propose_interview(db, employer_id, application_id, proposed_time_str):
    cursor = db.cursor(dictionary=True)
    try:
        # Validate employer owns the job via application -> job -> employer
        verify_query = """
        SELECT a.seeker_id, j.title, e.username AS employer_name, e.email AS employer_email, s.username AS seeker_name, s.email AS seeker_email
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        JOIN users e ON j.employer_id = e.id
        JOIN users s ON a.seeker_id = s.id
        WHERE a.id = %s AND e.id = %s
        """
        cursor.execute(verify_query, (application_id, employer_id))
        info = cursor.fetchone()
        
        if not info:
            return {"error": "Application not found or unauthorized"}
            
        proposed_time = datetime.fromisoformat(proposed_time_str.replace('Z', '+00:00'))

        # 1. Check for same-time slot (Overlap Check)
        overlap_query = """
        SELECT id FROM interviews 
        WHERE employer_id = %s AND proposed_time = %s AND status IN ('pending', 'accepted')
        """
        cursor.execute(overlap_query, (employer_id, proposed_time))
        if cursor.fetchone():
            return {"error": "already an interview is scheduled for that time slot !"}

        # 2. Check for daily limit (Max 5 Check)
        count_query = """
        SELECT COUNT(*) as count FROM interviews 
        WHERE employer_id = %s AND DATE(proposed_time) = DATE(%s) AND status IN ('pending', 'accepted')
        """
        cursor.execute(count_query, (employer_id, proposed_time))
        count_result = cursor.fetchone()
        if count_result and count_result['count'] >= 5:
            return {"error": "Interview limit reached for this day (max 5 interviews per day)!"}

        insert_query = """
        INSERT INTO interviews (application_id, employer_id, seeker_id, proposed_time, status)
        VALUES (%s, %s, %s, %s, 'pending')
        """
        cursor.execute(insert_query, (application_id, employer_id, info['seeker_id'], proposed_time))
        db.commit()
        
        try:
            send_interview_proposal_email(
                seeker_email=info['seeker_email'],
                seeker_name=info['seeker_name'],
                employer_name=info['employer_name'],
                job_title=info['title'],
                proposed_time=proposed_time
            )
        except Exception as e:
            print(f"Warning: Failed to send proposal email: {e}")
            
        return {"message": "Interview proposed successfully"}
    except Exception as e:
        db.rollback()
        return {"error": str(e)}
    finally:
        cursor.close()


def get_seeker_interviews(db, seeker_id):
    cursor = db.cursor(dictionary=True)
    query = """
    SELECT i.id as interview_id, i.proposed_time, i.status, i.created_at,
           j.title as job_title, e.username as employer_name, e.email as employer_email
    FROM interviews i
    JOIN applications a ON i.application_id = a.id
    JOIN jobs j ON a.job_id = j.id
    JOIN users e ON i.employer_id = e.id
    WHERE i.seeker_id = %s
    ORDER BY i.proposed_time ASC
    """
    cursor.execute(query, (seeker_id,))
    interviews = cursor.fetchall()
    cursor.close()
    
    # Format datetimes for JSON serialization
    for inv in interviews:
        inv['proposed_time'] = inv['proposed_time'].isoformat()
        inv['created_at'] = inv['created_at'].isoformat()
    return interviews


def respond_interview(db, seeker_id, interview_id, new_status):
    if new_status not in ['accepted', 'declined']:
        return {"error": "Invalid status update"}
        
    cursor = db.cursor(dictionary=True)
    try:
        # Verify ownership and get info for emails
        verify_query = """
        SELECT i.proposed_time, j.title, e.username AS employer_name, e.email AS employer_email,
               s.username AS seeker_name, s.email AS seeker_email
        FROM interviews i
        JOIN users e ON i.employer_id = e.id
        JOIN users s ON i.seeker_id = s.id
        JOIN applications a ON i.application_id = a.id
        JOIN jobs j ON a.job_id = j.id
        WHERE i.id = %s AND i.seeker_id = %s AND i.status = 'pending'
        """
        cursor.execute(verify_query, (interview_id, seeker_id))
        info = cursor.fetchone()
        
        if not info:
            return {"error": "Interview not found, already responded to, or unauthorized"}
            
        # Update DB
        cursor.execute("UPDATE interviews SET status = %s WHERE id = %s", (new_status, interview_id))
        db.commit()
        
        # Send emails
        try:
            if new_status == 'accepted':
                send_interview_confirmation_email(
                    seeker_email=info['seeker_email'],
                    seeker_name=info['seeker_name'],
                    employer_email=info['employer_email'],
                    employer_name=info['employer_name'],
                    job_title=info['title'],
                    interview_time=info['proposed_time']
                )
            else:
                send_interview_declined_email(
                    employer_email=info['employer_email'],
                    employer_name=info['employer_name'],
                    seeker_name=info['seeker_name'],
                    job_title=info['title'],
                    interview_time=info['proposed_time']
                )
        except Exception as e:
            print(f"Warning: Failed to send response emails: {e}")
            
        return {"message": f"Interview {new_status} successfully"}
    except Exception as e:
        db.rollback()
        return {"error": str(e)}
    finally:
        cursor.close()
