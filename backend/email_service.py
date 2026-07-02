import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import os

# Set this to a real password to actually send emails.
import os
SMTP_APP_PASSWORD = os.environ.get("SMTP_APP_PASSWORD")
SYSTEM_EMAIL = os.environ.get("SYSTEM_EMAIL")

def send_approval_email(seeker_email, seeker_name, employer_email, employer_name, job_title):
    """
    Drafts and sends an approval email to the job seeker.
    """
    system_email = SYSTEM_EMAIL
    
    msg = MIMEMultipart("alternative")
    msg["Subject"] = f"Application Update: You're Approved for {job_title}!"
    msg["From"] = system_email
    msg["To"] = seeker_email
    
    # Create the HTML version of the email
    html = f"""\
    <html>
      <body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h2 style="color: #4f46e5; margin-bottom: 20px;">Congratulations, {seeker_name}!</h2>
            <p>We have fantastic news for you.</p>
            <p><strong>{employer_name}</strong> has reviewed your application for the <strong>{job_title}</strong> position and would like to move forward with your candidacy.</p>
            
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #111;">Next Steps</h3>
                <p style="margin-bottom: 0;">Please contact the recruiter directly at <strong><a href="mailto:{employer_email}">{employer_email}</a></strong> to discuss the next phases of the process, which may include scheduling an interview.</p>
            </div>
            
            <p>We wish you the best of luck!</p>
            <br>
            <p style="font-size: 0.9em; color: #666;">Best regards,<br>The Nexus Jobs Team</p>
        </div>
      </body>
    </html>
    """
    
    part = MIMEText(html, "html")
    msg.attach(part)
    
    if SMTP_APP_PASSWORD:
        try:
            # Connect to Gmail SMTP Server
            server = smtplib.SMTP('smtp.gmail.com', 587)
            server.starttls()
            server.login(system_email, SMTP_APP_PASSWORD)
            server.sendmail(system_email, seeker_email, msg.as_string())
            server.quit()
            print(f"Successfully sent approval email to {seeker_email}")
        except Exception as e:
            print(f"[Email Error] Failed to send email to {seeker_email}: {e}")
    else:
        print("[Email Service] No SMTP password configured, email not sent.")


def send_interview_proposal_email(seeker_email, seeker_name, employer_name, job_title, proposed_time):
    """Sends an email to the seeker proposing an interview time."""
    msg = MIMEMultipart("alternative")
    msg["Subject"] = f"Interview Proposal: {job_title} at {employer_name}"
    msg["From"] = SYSTEM_EMAIL
    msg["To"] = seeker_email
    
    formatted_time = proposed_time.strftime("%A, %B %d, %Y at %I:%M %p") if hasattr(proposed_time, 'strftime') else proposed_time
    
    html = f"""\
    <html>
      <body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h2 style="color: #4f46e5; margin-bottom: 20px;">Interview Invitation!</h2>
            <p>Hi <strong>{seeker_name}</strong>,</p>
            <p>Good news! <strong>{employer_name}</strong> would like to schedule an interview with you for the <strong>{job_title}</strong> position.</p>
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #4f46e5;">
                <p style="margin: 0;"><strong>Proposed Time:</strong> {formatted_time}</p>
            </div>
            <p>Please log in to your Nexus Jobs Seeker Dashboard to <strong>Accept</strong> or <strong>Decline</strong> this proposal.</p>
            <br>
            <p style="font-size: 0.9em; color: #666;">Best regards,<br>The Nexus Jobs Team</p>
        </div>
      </body>
    </html>
    """
    msg.attach(MIMEText(html, "html"))
    _send_email_helper(seeker_email, msg)


def send_interview_confirmation_email(seeker_email, seeker_name, employer_email, employer_name, job_title, interview_time):
    """Sends a confirmation to BOTH parties when an interview is accepted."""
    formatted_time = interview_time.strftime("%A, %B %d, %Y at %I:%M %p") if hasattr(interview_time, 'strftime') else interview_time
    
    # Send to Seeker
    msg_seeker = MIMEMultipart("alternative")
    msg_seeker["Subject"] = f"Interview Confirmed: {job_title} at {employer_name}"
    msg_seeker["From"] = SYSTEM_EMAIL
    msg_seeker["To"] = seeker_email
    html_seeker = f"""\
    <html>
      <body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h2 style="color: #10B981; margin-bottom: 20px;">Interview Confirmed!</h2>
            <p>Hi <strong>{seeker_name}</strong>,</p>
            <p>You have successfully accepted the interview for the <strong>{job_title}</strong> position.</p>
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0;"><strong>Confirmed Time:</strong> {formatted_time}</p>
                <p style="margin: 0;"><strong>With:</strong> {employer_name} (<a href="mailto:{employer_email}">{employer_email}</a>)</p>
            </div>
            <p>Good luck!</p><br>
            <p style="font-size: 0.9em; color: #666;">Best regards,<br>The Nexus Jobs Team</p>
        </div>
      </body>
    </html>
    """
    msg_seeker.attach(MIMEText(html_seeker, "html"))
    _send_email_helper(seeker_email, msg_seeker)
    
    # Send to Employer
    msg_emp = MIMEMultipart("alternative")
    msg_emp["Subject"] = f"Interview Confirmed: {seeker_name} for {job_title}"
    msg_emp["From"] = SYSTEM_EMAIL
    msg_emp["To"] = employer_email
    html_emp = f"""\
    <html>
      <body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h2 style="color: #10B981; margin-bottom: 20px;">Interview Accepted</h2>
            <p>Hi <strong>{employer_name}</strong>,</p>
            <p><strong>{seeker_name}</strong> has confirmed their availability for the interview for the <strong>{job_title}</strong> position.</p>
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0;"><strong>Confirmed Time:</strong> {formatted_time}</p>
                <p style="margin: 0;"><strong>Applicant Email:</strong> <a href="mailto:{seeker_email}">{seeker_email}</a></p>
            </div>
            <p>Please use this contact information to send them meeting links or location details.</p><br>
            <p style="font-size: 0.9em; color: #666;">Best regards,<br>The Nexus Jobs Team</p>
        </div>
      </body>
    </html>
    """
    msg_emp.attach(MIMEText(html_emp, "html"))
    _send_email_helper(employer_email, msg_emp)


def send_interview_declined_email(employer_email, employer_name, seeker_name, job_title, interview_time):
    """Sends a notification to the employer that the seeker declined."""
    formatted_time = interview_time.strftime("%A, %B %d, %Y at %I:%M %p") if hasattr(interview_time, 'strftime') else interview_time
    
    msg = MIMEMultipart("alternative")
    msg["Subject"] = f"Interview Declined: {seeker_name} for {job_title}"
    msg["From"] = SYSTEM_EMAIL
    msg["To"] = employer_email
    
    html = f"""\
    <html>
      <body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h2 style="color: #F59E0B; margin-bottom: 20px;">Interview Declined</h2>
            <p>Hi <strong>{employer_name}</strong>,</p>
            <p>Unfortunately, <strong>{seeker_name}</strong> has declined the interview proposal for the <strong>{job_title}</strong> position originally scheduled for {formatted_time}.</p>
            <p>You can propose a new time from your Employer Dashboard or proceed with other candidates.</p><br>
            <p style="font-size: 0.9em; color: #666;">Best regards,<br>The Nexus Jobs Team</p>
        </div>
      </body>
    </html>
    """
    msg.attach(MIMEText(html, "html"))
    _send_email_helper(employer_email, msg)


def _send_email_helper(to_email, msg):
    """Helper function to execute SMTP sequence"""
    if not SMTP_APP_PASSWORD:
        return
    try:
        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        server.login(SYSTEM_EMAIL, SMTP_APP_PASSWORD)
        server.sendmail(SYSTEM_EMAIL, to_email, msg.as_string())
        server.quit()
        print(f"Successfully sent interview email to {to_email}")
    except Exception as e:
        print(f"[Email Error] Failed to send email to {to_email}: {e}")
