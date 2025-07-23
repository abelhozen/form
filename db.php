<?php
// Database configuration
$host = "localhost";
$user = "root"; // Replace with your database username
$pass = "";     // Replace with your database password
$dbname = "hospital_system_feedback";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div style='color: white; background: #e74c3c; padding: 30px; border-radius: 10px; max-width: 600px; margin: 50px auto; text-align: center;'>
            <h1>Database Connection Failed</h1>
            <p><strong>Error:</strong> " . $conn->connect_error . "</p>
            <p>Please check your database configuration in db.php</p>
            <p>Ensure the database server is running and the credentials are correct.</p>
         </div>");
}

// Set charset to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");

// Create database and table if they don't exist
$check_table = $conn->query("SELECT 1 FROM feedbacks LIMIT 1");
if ($check_table === FALSE) {
    // Table doesn't exist, create it
    $create_table = $conn->query("
        CREATE TABLE IF NOT EXISTS feedbacks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            role VARCHAR(50) NOT NULL,
            
            -- User Experience
            satisfaction VARCHAR(50),
            ease_of_use TEXT,
            performance TEXT,
            
            -- System Coverage
            modules_used TEXT,
            working_well TEXT,
            missing_features TEXT,
            
            -- Challenges
            technical_issues TEXT,
            workflow_problems TEXT,
            data_issues TEXT,
            
            -- Recommendations
            priority_improvements TEXT,
            new_features TEXT,
            training_needs TEXT,
            
            -- Role-specific (stored as JSON)
            role_specific JSON,
            
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            INDEX idx_role (role),
            INDEX idx_submitted_at (submitted_at),
            INDEX idx_satisfaction (satisfaction)
        )
    ");
    
    if ($create_table === FALSE) {
        die("<div style='color: white; background: #e74c3c; padding: 30px; border-radius: 10px; max-width: 600px; margin: 50px auto; text-align: center;'>
                <h1>Database Setup Error</h1>
                <p>Failed to create the feedbacks table.</p>
                <p><strong>Error:</strong> " . $conn->error . "</p>
             </div>");
    }
}
?>