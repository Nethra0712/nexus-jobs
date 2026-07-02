# Nexus Jobs – Job Portal

A full-stack job portal that connects job seekers and employers. Employers can post jobs and manage applicants; seekers can browse jobs, apply with skill-matching, save listings, and get AI-generated cover letters. Includes an admin dashboard for platform-wide stats and moderation.

## Features

- **Authentication** – registration/login for seekers and employers, with hashed passwords (Werkzeug)
- **Job listings** – employers can post, view, and delete job postings
- **Applications** – seekers apply with a resume upload; skill-match score is calculated automatically against job requirements
- **Saved jobs** – seekers can bookmark jobs to review later
- **Interviews** – employers can propose interviews; seekers can accept/decline
- **AI Career Coach** – simulated AI chatbot and cover-letter generator (interview tips, resume advice, career guidance)
- **Email notifications** – automated approval emails to seekers via SMTP
- **Admin dashboard** – KPI stats, user/job/payment overviews, filterable by month/year

## Tech Stack

- **Frontend:** PHP, HTML/CSS, vanilla JavaScript
- **Backend:** Python (Flask), Flask-CORS
- **Database:** MySQL
- **Auth:** Werkzeug password hashing
- **Email:** smtplib (Gmail SMTP)

## Project Structure

```
Job-Portal/
├── backend/          # Flask API (app.py, auth.py, jobs.py, interviews.py, ai_service.py, email_service.py)
│   └── uploads/resumes/   # Uploaded resume files (not committed)
├── database/
│   └── schema.sql    # MySQL schema
└── frontend/          # PHP pages (index, dashboards, auth, static pages) + CSS
```

## Getting Started

### Prerequisites
- Python 3.11+
- MySQL (e.g. via XAMPP)
- PHP 7.4+ with a local server (e.g. XAMPP/Apache) to serve the `frontend/` folder

### 1. Clone the repo
```bash
git clone https://github.com/<your-username>/<your-repo>.git
cd <your-repo>
```

### 2. Set up the database
```bash
mysql -u root -p < database/schema.sql
```

### 3. Set up the backend
```bash
cd backend
python -m venv venv
source venv/bin/activate   # Windows: venv\Scripts\activate
pip install -r requirements.txt
```

Create a `.env` file in `backend/` (see `.env.example`) with your own values:
```
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=job_portal
SMTP_APP_PASSWORD=your_gmail_app_password
SYSTEM_EMAIL=your_email@gmail.com
```

Run the API:
```bash
flask run
# or: python app.py
```

### 4. Set up the frontend
Serve the `frontend/` folder with a PHP-capable server (e.g. place it in XAMPP's `htdocs`, or run):
```bash
php -S localhost:8000 -t frontend
```

## Environment Variables & Secrets

This project uses a MySQL connection and a Gmail SMTP app password. **Do not commit real credentials.** Store them in a `.env` file (already excluded via `.gitignore`) and load them with `python-dotenv` instead of hardcoding them in `db_config` / `email_service.py`.

## Notes

- The AI Career Coach and cover-letter generator currently return simulated responses; swap in a real LLM API call (e.g. OpenAI or Anthropic) in `ai_service.py` if desired.
- Sample/test resume PDFs under `backend/uploads/resumes/` are excluded from version control.

## License

This project is licensed under the MIT License.
