import os
from werkzeug.security import generate_password_hash

hash_val = generate_password_hash('admin123')
cmd = f'mysql -u root job_portal -e "UPDATE users SET password_hash = \'{hash_val}\' WHERE username = \'admin\';"'
os.system(cmd)
print("Updated admin password to admin123")
