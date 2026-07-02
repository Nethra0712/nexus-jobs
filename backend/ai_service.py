import time
import random

def generate_cover_letter(seeker_name, skills, job_title, job_description, employer_name):
    """
    Simulates calling an AI language model API (like OpenAI/Claude) to generate
    a tailored cover letter based on the user's skills and the specific job.
    """
    
    # Simulate network/API latency for realism
    time.sleep(1.5)
    
    # Process inputs to handle potential empty values gracefully
    name = seeker_name if seeker_name else "Hiring Manager"
    clean_skills = skills if skills else "general professional skills"
    
    prompt_result = f"""Dear {employer_name} Hiring Team,

I am writing to express my strong interest in the {job_title} position. With my background and strong proficiency in {clean_skills}, I am confident in my ability to make an immediate impact on your team.

Having reviewed the job description, I was particularly drawn to the opportunity to contribute to your goals. My previous experiences have equipped me with the exact technical and soft skills you are looking for, allowing me to deliver high-quality results efficiently. I am a fast learner, highly adaptable, and passionate about the work.

I would welcome the opportunity to discuss how my specific experiences align with the needs of your company. Thank you for your time and consideration.

Sincerely,
{name}"""

    return prompt_result

def generate_chat_response(message):
    """
    Simulates calling an AI language model to generate a chat response
    for career advice, resume tips, interview preparation, and job searching.
    """
    time.sleep(1) # Simulate AI processing time
    
    msg_lower = message.lower()
    
    if "interview" in msg_lower:
         return "Here are 3 tips for your interview:<br>1. Research the company thoroughly.<br>2. Practice the STAR method for behavioral questions.<br>3. Prepare a few insightful questions to ask the interviewer."
    elif ("resume" in msg_lower) or ("cv" in msg_lower):
         return "To improve your resume:<br>1. Use a clean, professional format.<br>2. Highlight achievements rather than just duties (use metrics).<br>3. Tailor your skills to match the job description."
    elif ("career" in msg_lower) or ("advice" in msg_lower):
         return "For career growth, we recommend continuous learning. Keep your Nexus profile updated, network with industry professionals, and consider upskilling through online courses."
    elif ("job" in msg_lower) or ("search" in msg_lower):
         return "When searching for a job, consistency is key. Set up daily job alerts, tailor your cover letters for each application, and don't hesitate to reach out directly to recruiters on Nexus Jobs."
    else:
         return "I'm your Nexus AI Career Coach! Feel free to ask me for interview preparation tips, resume advice, job searching strategies, or general career guidance."
