import os

cmd = 'mysql -u root job_portal -e "ALTER TABLE applications ADD COLUMN resume_path VARCHAR(255) DEFAULT NULL;"'
os.system(cmd)
print("Added resume_path column to applications table.")
