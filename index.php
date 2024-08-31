<!-- index.php -->
<?php

$pageTitle = 'Welcome to Attendance System';
include 'template/header.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // If user is admin, redirect to admin panel
    if ($_SESSION['is_admin']) {
        header('Location: admin_panel.php');
        exit();
    }
    // If regular user, redirect to dashboard
    else {
        header('Location: dashboard.php');
        exit();
    }
}
?>

<h1 class="text-3xl font-bold mb-6">Welcome to the Attendance System</h1>
<p class="mb-4">Please log in to access your dashboard or the admin panel.</p>
<a href="login.php" class="bg-red-400 hover:bg-[#f00000]  text-white font-bold py-2 px-4 rounded">
    Login
</a>

<?php include 'template/footer.php'; ?>