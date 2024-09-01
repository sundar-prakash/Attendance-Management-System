<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'attendance_system');
define('DB_USER', 'root');
define('DB_PASS', ''); // Change to your database password

// SMTP configuration
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USER', 'example@example.com');
define('SMTP_PASS', 'example'); // SMTP password
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // or 'ssl'
define('SMTP_FROM_EMAIL', 'example@example.com');
define('SMTP_FROM_NAME', 'example');

// location config
define('OFFICE_LATITUDE', 'your-office-lat');
define('OFFICE_LONGITUDE', 'your-office-long');
define('CHECKIN_RADIUS', 500); 

//url config
define('URL', 'http://localhost/ams'); 
?>
