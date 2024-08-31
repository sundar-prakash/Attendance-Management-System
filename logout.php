<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = array();

// If the session was started with cookies, delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: login.php');
exit();
?>
