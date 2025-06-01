session_set_cookie_params([
    'lifetime' => 1800, // 30 minutes
    'path' => '/',
    'domain' => 'localhost', // Or your actual domain in production
    'secure' => true, // Must be true when SameSite=None
    'httponly' => true,
    'samesite' => 'None'
]);

session_start();

// Optionally, regenerate session if user is active and session is near expiry
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // Last request was more than 30 minutes ago
    session_unset();     // Unset $_SESSION
    session_destroy();   // Destroy session data
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update activity time
echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
    exit;