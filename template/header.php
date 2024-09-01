<?php
session_start();
date_default_timezone_set('Asia/Kolkata'); 
$timeout_duration = 3600; // 1 hour

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Last activity was more than 1 hour ago
    session_unset();     // Unset all session variables
    session_destroy();   // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Attendance System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex justify-between">
                    <div class="flex space-x-7">
                        <div>
                            <a href="index.php" class="flex items-center py-4 px-2">
                                <span class="font-semibold text-gray-500 text-lg">Attendance System</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <?php if($_SESSION['is_admin']): ?>
                                <a href="admin_panel.php" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-[#f00000]  hover:text-white transition duration-300">Admin Panel</a>
                            <?php endif; ?>
                            <a href="dashboard.php" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-[#f00000]  hover:text-white transition duration-300">Dashboard</a>
                            <a href="logout.php" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-[#f00000]  hover:text-white transition duration-300">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-[#f00000]  hover:text-white transition duration-300">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="max-w-6xl mx-auto mt-8 px-4">
