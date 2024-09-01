# Attendance Management System (AMS)

## Short Description

Attendance Management System (AMS) is a web-based application designed to help organizations efficiently manage employee attendance. The system allows administrators to add users, generate attendance reports, and manage user roles. Users can check in and check out, and view their attendance records. The AMS is built using PHP and MySQL, with a user-friendly interface and responsive design.

## Features

- **User Management**: Administrators can add, update, and delete users.
- **Role Management**: Assign roles to users (Admin or User).
- **Attendance Tracking**: Users can check in and check out to record their attendance.
- **Geolocation Restriction**: Users can only check in if they are within 500 meters of the office location. 
- **Automatic Logout**: Users are automatically logged out after 1 hour of inactivity for security purposes.
- **Report Generation**: Generate and download attendance reports in Excel format.
- **Responsive Design**: Mobile-friendly interface for ease of use on any device.
- **Password Reset**: Secure password reset functionality for users.

## How to Use

1. **Admin Panel**:
   - Log in as an administrator to access the admin panel.
   - Add new users by filling out the "Add New User" form.
   - Generate attendance reports by selecting a date range and clicking "Generate Report."
   - View and manage the user list, including updating or deleting users.

2. **User Actions**:
   - Users can log in to their accounts to check in and check out for the day.
   - Users can only check in if they are within 500 meters of the office location set in the configuration.
   - View their attendance history from their dashboard.

## How to Configure

1. **Database Configuration**:
   - Create a MySQL database named `attendance_system`.
   - Import the provided `schema.sql` file to set up the necessary tables.
   - Update the database configuration in `/includes/config.php`:
     ```php
     <?php
     define('DB_HOST', 'localhost');  // Database host
     define('DB_NAME', 'attendance_system');  // Database name
     define('DB_USER', 'root');  // Database username
     define('DB_PASS', '');  // Database password

     // SMTP settings for email
     define('SMTP_HOST', 'smtp.example.com');
     define('SMTP_USER', 'example@example.com');
     define('SMTP_PASS', 'example'); // SMTP password
     define('SMTP_PORT', 587);
     define('SMTP_SECURE', 'tls'); // or 'ssl'
     define('SMTP_FROM_EMAIL', 'example@example.com');
     define('SMTP_FROM_NAME', 'example');

     // Geolocation settings
     define('OFFICE_LATITUDE', 'your_office_latitude'); // Replace with your office latitude
     define('OFFICE_LONGITUDE', 'your_office_longitude'); // Replace with your office longitude
     define('CHECKIN_RADIUS', 500); // Radius in meters within which check-in is allowed

     //url config
     define('URL', 'http://localhost/ams'); 
     ?>
     ```

2. **Web Server Configuration**:
   - Ensure you have a web server like Apache or Nginx running.
   - Make sure PHP and MySQL are installed and configured.
   - Place the project files in the web server's root directory.

3. **Environment Configuration**:
   - Ensure the correct timezone is set in your PHP configuration to match your location:
     ```php
     date_default_timezone_set('Asia/Kolkata');
     ```

4. **Geolocation Configuration**:
   - Update `OFFICE_LATITUDE` and `OFFICE_LONGITUDE` in `/includes/config.php` with your office's latitude and longitude to enforce the geolocation-based check-in restriction.

## How to Run

1. **Local Setup**:
   - Start your local web server (e.g., XAMPP, WAMP).
   - Place the project folder in the `htdocs` (for XAMPP) or `www` (for WAMP) directory.
   - Open your web browser and navigate to `http://localhost/your-project-folder-name`.

2. **Production Setup**:
   - Upload all project files to your web hosting server.
   - Make sure to update `/includes/config.php` with your production database credentials, SMTP settings, and geolocation settings.
   - Access your website through your domain name.

## Additional Notes

- **Security**: Ensure your `config.php` file is not accessible to the public. Add it to `.gitignore` to prevent it from being pushed to any public repositories.
- **Dependencies**: This project uses PHPMailer for sending emails. Ensure that the PHPMailer library is included in your project and properly configured in `config.php`.
- **Cron Jobs**: If you want to set up automatic tasks, consider using cron jobs for periodic checks or backups.
- **Default Credentials**: After importing `schema.sql`, the default administrator username is `admin` and the password is `123`. Please change these credentials upon initial login for security purposes.

## Getting Help

If you encounter any issues or have any questions, please feel free to contact the project maintainer at GitHub.
