from werkzeug.security import generate_password_hash, check_password_hash
import re

def create_user(db, username, email, password, role):
    cursor = db.cursor(dictionary=True)
    
    # Check if user exists
    cursor.execute("SELECT * FROM users WHERE username = %s OR email = %s", (username, email))
    if cursor.fetchone():
        cursor.close()
        return {"error": "Username or email already exists"}

    password_hash = generate_password_hash(password)
    
    query = "INSERT INTO users (username, email, password_hash, role) VALUES (%s, %s, %s, %s)"
    cursor.execute(query, (username, email, password_hash, role))
    db.commit()
    user_id = cursor.lastrowid
    cursor.close()
    
    return {"message": "User created successfully", "user_id": user_id, "role": role}

def login_user(db, username, password):
    cursor = db.cursor(dictionary=True)
    cursor.execute("SELECT * FROM users WHERE username = %s", (username,))
    user = cursor.fetchone()
    cursor.close()

    if user and check_password_hash(user['password_hash'], password):
        return {
            "message": "Login successful",
            "user_id": user["id"],
            "username": user["username"],
            "email": user["email"],
            "role": user["role"],
            "skills": user.get("skills", "")
        }
    return {"error": "Invalid username or password"}


def update_user_profile(db, user_id, new_username, new_email, new_password_hash=None, new_skills=None):
    cursor = db.cursor(dictionary=True)
    
    # Check if new username or email already exists for a DIFFERENT user
    cursor.execute("SELECT id FROM users WHERE (username = %s OR email = %s) AND id != %s", (new_username, new_email, user_id))
    if cursor.fetchone():
        cursor.close()
        return {"error": "Username or email is already taken by another user"}
        
    if new_password_hash:
        query = "UPDATE users SET username = %s, email = %s, password_hash = %s, skills = %s WHERE id = %s"
        cursor.execute(query, (new_username, new_email, new_password_hash, new_skills, user_id))
    else:
        query = "UPDATE users SET username = %s, email = %s, skills = %s WHERE id = %s"
        cursor.execute(query, (new_username, new_email, new_skills, user_id))
        
    db.commit()
    cursor.close()
    
    return {"message": "Profile updated successfully"}
