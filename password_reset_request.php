<?php
$pageTitle = 'Reset Password - Attendance System';
include 'template/header.php';
include 'includes/db.php';
include_once 'includes/config.php';
require 'includes/PHPMailer/PHPMailerAutoload.php'; // Adjust this path if needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    date_default_timezone_set('Asia/Kolkata'); // Correct timezone

    // Check if the username exists in the database and get the associated email
    $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    if ($email) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

        // Insert the token and email into the database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expiry);
        $stmt->execute();
        $stmt->close();

        // Send the reset email
        $resetLink = "http://ams.zingbizz.com/reset_password.php?token=$token";
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = SMTP_HOST; // SMTP server
$mail->SMTPAuth = true;
$mail->Username = SMTP_USER; // SMTP username
$mail->Password = SMTP_PASS; // SMTP password
$mail->SMTPSecure = SMTP_SECURE; // 'tls' or 'ssl'
$mail->Port = SMTP_PORT; // Port number

$mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
     
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Please click the following link to reset your password: $resetLink";

        if ($mail->send()) {
            // Hide part of the email address
            $hiddenEmail = preg_replace('/(.{2}).*(@.*)/', '$1****$2', $email);
            $success = "A password reset link has been sent to $hiddenEmail.";
        } else {
            $error = "Failed to send reset email. Please try again.";
        }
    } else {
        $error = "Username not found.";
    }
}
?>

<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Reset Password</h1>
        <?php if (isset($success)): ?>
            <p class="text-green-500 mb-4"><?php echo htmlspecialchars($success); ?></p>
        <?php elseif (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="password_reset_request.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Send Reset Link</button>
        </form>
    </div>
</div>

<?php include 'template/footer.php'; ?>
