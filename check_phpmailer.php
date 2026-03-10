<?php
echo "<h2>PHPMailer File Check</h2>";

// Check if files exist
$files = [
    'vendor/autoload.php',
    'vendor/phpmailer/phpmailer/PHPMailer.php',
    'vendor/phpmailer/phpmailer/SMTP.php',
    'vendor/phpmailer/phpmailer/Exception.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ Found: $file<br>";
    } else {
        echo "✗ Missing: $file<br>";
    }
}

echo "<br><h3>Testing Autoloader...</h3>";

require 'vendor/autoload.php';
echo "✓ Autoloader loaded<br>";

try {
    $test = new \PHPMailer\PHPMailer\PHPMailer();
    echo "✓ <strong>PHPMailer class loaded successfully!</strong><br>";
    echo "PHPMailer Version: " . $test::VERSION . "<br>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
?>
