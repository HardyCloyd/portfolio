<?php
// ===========================
// Contact Form Handler with PHPMailer
// ===========================

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Set JSON response header
header('Content-Type: application/json');

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate input data
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');
    
    // Validation
    $errors = array();
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['message'] = implode(', ', $errors);
        echo json_encode($response);
        exit;
    }
    
    // ===========================
    // PHPMailer Configuration
    // ===========================
    
    try {
        $mail = new PHPMailer(true);
        
        // Enable verbose debug output (comment out in production)
        // $mail->SMTPDebug = 2;
        // $mail->Debugoutput = 'html';
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nagnalhardycloyd0@gmail.com';  // Your Gmail address
        $mail->Password   = 'sbprezpqrdbkcncu';              // App Password without spaces
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('nagnalhardycloyd0@gmail.com', 'Portfolio Contact Form');
        $mail->addAddress('nagnalhardycloyd0@gmail.com', 'Hardy Cloyd Nagnal');
        $mail->addReplyTo($email, $name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = "Portfolio Contact: " . $subject;
        
        // HTML email body
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #3498db; color: white; padding: 20px; text-align: center; }
                .content { background: #f8f9fa; padding: 20px; margin-top: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #2c3e50; }
                .value { margin-top: 5px; }
                .footer { margin-top: 20px; padding: 15px; background: #ecf0f1; font-size: 12px; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Portfolio Contact Message</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <div class='label'>From:</div>
                        <div class='value'>" . htmlspecialchars($name) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Email:</div>
                        <div class='value'>" . htmlspecialchars($email) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Subject:</div>
                        <div class='value'>" . htmlspecialchars($subject) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Message:</div>
                        <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                </div>
                <div class='footer'>
                    Sent from your portfolio contact form on " . date('F j, Y \a\t g:i A') . "
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Plain text version
        $mail->AltBody = "You have received a new message from your portfolio contact form.\n\n"
                       . "Name: " . $name . "\n"
                       . "Email: " . $email . "\n"
                       . "Subject: " . $subject . "\n\n"
                       . "Message:\n" . $message . "\n";
        
        // Send email
        $mail->send();
        
        $response['success'] = true;
        $response['message'] = "Thank you for your message! I will get back to you soon.";
        
        // Log the message
        log_message($name, $email, $subject, $message);
        
    } catch (Exception $e) {
        // TEMPORARY: Show detailed error for debugging (remove in production)
        $response['message'] = "Error: " . $mail->ErrorInfo;
        
        // Log error for debugging
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        
        // Still log the message even if email fails
        log_message($name, $email, $subject, $message);
    }
    
} else {
    $response['message'] = "Invalid request method";
}

echo json_encode($response);

// ===========================
// Helper Functions
// ===========================

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function log_message($name, $email, $subject, $message) {
    $log_file = 'contact_messages.txt';
    $log_entry = "\n\n" . str_repeat("=", 50) . "\n";
    $log_entry .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $log_entry .= "Name: " . $name . "\n";
    $log_entry .= "Email: " . $email . "\n";
    $log_entry .= "Subject: " . $subject . "\n";
    $log_entry .= "Message: " . $message . "\n";
    $log_entry .= str_repeat("=", 50);
    
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// ===========================
// Alternative: Database Storage
// ===========================
/*
function store_in_database($name, $email, $subject, $message) {
    // Database configuration
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";
    
    try {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        // Execute
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        
        return true;
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

// SQL to create the table:
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read', 'replied') DEFAULT 'new'
);
*/
?>
