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
        print("Creating 'saved_jobs' table if it doesn't exist...")
        create_table_query = """
        CREATE TABLE IF NOT EXISTS saved_jobs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            seeker_id INT NOT NULL,
            job_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (seeker_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
            UNIQUE KEY unique_save (seeker_id, job_id)
        )
        """
        cursor.execute(create_table_query)
        print("Successfully created/verified 'saved_jobs' table.")
            
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
