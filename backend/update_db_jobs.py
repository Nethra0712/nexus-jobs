import mysql.connector

# Database Configuration
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'job_portal'
}

def update_db():
    try:
        connection = mysql.connector.connect(**db_config)
        cursor = connection.cursor()
        
        print("Checking for required columns in jobs table...")
        
        # Add job_type
        try:
            cursor.execute("ALTER TABLE jobs ADD COLUMN job_type VARCHAR(50) DEFAULT 'Full-time'")
            print("Added job_type column.")
        except mysql.connector.Error as err:
            # 1060 is "Duplicate column name"
            if err.errno == 1060:
                print("- job_type already exists.")
            else:
                raise err
                
        # Add industry
        try:
            cursor.execute("ALTER TABLE jobs ADD COLUMN industry VARCHAR(100) DEFAULT 'Technology'")
            print("Added industry column.")
        except mysql.connector.Error as err:
            if err.errno == 1060:
                print("- industry already exists.")
            else:
                raise err
                
        # Add salary
        try:
            cursor.execute("ALTER TABLE jobs ADD COLUMN salary DECIMAL(10,2) DEFAULT NULL")
            print("Added salary column.")
        except mysql.connector.Error as err:
            if err.errno == 1060:
                print("- salary already exists.")
            else:
                raise err
        
        connection.commit()
        print("Jobs table update complete.")
        
    except mysql.connector.Error as err:
        print(f"Error: {err}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            cursor.close()
            connection.close()

if __name__ == "__main__":
    update_db()
