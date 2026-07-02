import mysql.connector

def update_db():
    print("Connecting to database...")
    db = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",      
        database="job_portal"
    )
    cursor = db.cursor()

    try:
        # Add the new skills column to the users table
        print("Checking users table for skills column...")
        cursor.execute("SHOW COLUMNS FROM users LIKE 'skills'")
        if not cursor.fetchone():
            print("Adding 'skills' column to users table...")
            cursor.execute("ALTER TABLE users ADD COLUMN skills TEXT NULL")
            print("Successfully added 'skills' column to users table.")
        else:
            print("'skills' column already exists in users table.")
            
        db.commit()
    except Exception as e:
        print(f"Error updating database: {e}")
        db.rollback()
    finally:
        cursor.close()
        db.close()
        print("Database connection closed.")

if __name__ == "__main__":
    update_db()
