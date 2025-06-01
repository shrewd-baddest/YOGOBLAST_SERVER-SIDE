<?php
session_set_cookie_params([
    'lifetime' => 1800, // 30 minutes
    'path' => '/',
    'domain' => 'localhost', // Or your actual domain in production
    'secure' => true, // Must be true when SameSite=None
    'httponly' => true,
    'samesite' => 'None'
]);

session_start();

 if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
     session_unset();     
    session_destroy();  
    echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
        exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update activity time
    ?>