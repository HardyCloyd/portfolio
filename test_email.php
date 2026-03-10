<?php
// Test PHPMailer Installation

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

echo "<h2>PHPMailer Test</h2>";

try {
    $mail = new PHPMailer(true);
    echo "✓ PHPMailer loaded successfully<br>";
    
    // Test SMTP connection
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nagnalhardycloyd0@gmail.com';
    $mail->Password = 'sbprezpqrdbkcncu';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPDebug = 2; // Enable verbose debug output
    
    echo "<br><strong>Testing SMTP connection...</strong><br>";
    
    $mail->setFrom('nagnalhardycloyd0@gmail.com', 'Test');
    $mail->addAddress('nagnalhardycloyd0@gmail.com');
    $mail->Subject = 'PHPMailer Test';
    $mail->Body = 'This is a test email';
    
    $mail->send();
    echo "<br><br><strong style='color: green;'>✓ Email sent successfully!</strong>";
    
} catch (Exception $e) {
    echo "<br><br><strong style='color: red;'>✗ Error: " . $mail->ErrorInfo . "</strong>";
    echo "<br><br>Exception message: " . $e->getMessage();
}
?>
