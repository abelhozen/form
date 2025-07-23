<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize all input data
    $name = htmlspecialchars(trim($_POST['full_name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $role = htmlspecialchars(trim($_POST['role']));
    
    // User Experience
    $satisfaction = htmlspecialchars(trim($_POST['satisfaction']));
    $ease_of_use = htmlspecialchars(trim($_POST['ease_of_use']));
    $performance = htmlspecialchars(trim($_POST['performance']));
    
    // System Coverage
    $modules_used = htmlspecialchars(trim($_POST['modules_used']));
    $working_well = htmlspecialchars(trim($_POST['working_well']));
    $missing_features = htmlspecialchars(trim($_POST['missing_features']));
    
    // Challenges
    $technical_issues = htmlspecialchars(trim($_POST['technical_issues']));
    $workflow_problems = htmlspecialchars(trim($_POST['workflow_problems']));
    $data_issues = htmlspecialchars(trim($_POST['data_issues']));
    
    // Recommendations
    $priority_improvements = htmlspecialchars(trim($_POST['priority_improvements']));
    $new_features = htmlspecialchars(trim($_POST['new_features']));
    $training_needs = htmlspecialchars(trim($_POST['training_needs']));
    
    // Role-specific fields
    $role_specific = [];
    if ($role === 'Receptionist') {
        $role_specific['appointments'] = htmlspecialchars(trim($_POST['receptionist_appointments'] ?? ''));
        $role_specific['registration'] = htmlspecialchars(trim($_POST['receptionist_registration'] ?? ''));
    } elseif ($role === 'Doctor') {
        $role_specific['records'] = htmlspecialchars(trim($_POST['doctor_records'] ?? ''));
        $role_specific['prescriptions'] = htmlspecialchars(trim($_POST['doctor_prescriptions'] ?? ''));
    } elseif ($role === 'Nurse') {
        $role_specific['monitoring'] = htmlspecialchars(trim($_POST['nurse_monitoring'] ?? ''));
    } elseif ($role === 'Lab Technician') {
        $role_specific['results'] = htmlspecialchars(trim($_POST['lab_results'] ?? ''));
    }
    $role_specific_json = json_encode($role_specific);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO feedbacks (
        name, email, role, 
        satisfaction, ease_of_use, performance,
        modules_used, working_well, missing_features,
        technical_issues, workflow_problems, data_issues,
        priority_improvements, new_features, training_needs,
        role_specific
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssssssssssss", 
        $name, $email, $role,
        $satisfaction, $ease_of_use, $performance,
        $modules_used, $working_well, $missing_features,
        $technical_issues, $workflow_problems, $data_issues,
        $priority_improvements, $new_features, $training_needs,
        $role_specific_json
    );

    if ($stmt->execute()) {
        // Success message
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Feedback Submitted</title>
            <style>
                body { font-family: Arial; background: #e8f4f8; padding: 20px; text-align: center; }
                .success-box { 
                    background: #2ecc71; 
                    color: white; 
                    padding: 40px; 
                    border-radius: 10px; 
                    max-width: 600px; 
                    margin: 50px auto;
                    box-shadow: 0 0 25px rgba(0,0,0,0.1);
                }
                .success-box h1 { margin-top: 0; }
                .btn { 
                    display: inline-block; 
                    background: #005b96; 
                    color: white; 
                    padding: 12px 25px; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    margin-top: 25px;
                    font-weight: bold;
                    transition: background 0.3s;
                }
                .btn:hover {
                    background: #003d66;
                }
            </style>
        </head>
        <body>
            <div class="success-box">
                <h1>✅ Feedback Submitted Successfully!</h1>
                <p>Thank you for helping us improve our hospital management system. Your input is valuable.</p>
                <p>We will review your feedback and work on improvements.</p>
                <a href="index.html" class="btn">Return to Feedback Form</a>
            </div>
        </body>
        </html>';
    } else {
        echo '<div style="color: red; text-align: center; margin-top: 50px; background: #ffecec; padding: 30px; border-radius: 10px; max-width: 600px; margin-left: auto; margin-right: auto;">
                <h1>❌ Error Submitting Feedback</h1>
                <p>There was an error processing your feedback. Please try again later.</p>
                <p>If the problem persists, please contact IT support with this error message:</p>
                <p><strong>' . $stmt->error . '</strong></p>
                <a href="index.html" style="color: #005b96; font-weight: bold;">Go Back to Form</a>
              </div>';
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.html");
    exit();
}
?>