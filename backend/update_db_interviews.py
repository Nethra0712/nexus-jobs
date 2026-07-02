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
        print("Creating 'interviews' table if it doesn't exist...")
        create_table_query = """
        CREATE TABLE IF NOT EXISTS interviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            application_id INT NOT NULL,
            employer_id INT NOT NULL,
            seeker_id INT NOT NULL,
            proposed_time DATETIME NOT NULL,
            status ENUM('pending', 'accepted', 'declined') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
            FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (seeker_id) REFERENCES users(id) ON DELETE CASCADE
        )
        """
        cursor.execute(create_table_query)
        print("Successfully created/verified 'interviews' table.")
            
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
