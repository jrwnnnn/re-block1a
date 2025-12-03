<?php
$email_error = $password_error = $secretKey_error = ""; // Initialize error message variables so that PHP doesn't throw an undefined variable error
$has_error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $secretKey = trim($_POST['secretKey']);

    // Validate password strength and match
    if ($password !== $confirm_password) {
        $password_error = " - Passwords do not match.";
        $has_error = true;
    } elseif (strlen($password) < 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[0-9]/', $password)) {
        $password_error = " - Password must be at least 8 characters, include uppercase, lowercase, and a number.";
        $has_error = true;
    }

    // Check if email already exists
    $sql = query("SELECT id FROM users WHERE email = ?", [$email], "s");
    if ($sql) {
        $email_error = " - Email is already registered.";
        $has_error = true;
    }

    // Retrieve player information using secret key
    $playerData = query("SELECT uuid, username FROM auth WHERE secret = ?", [$secretKey], "s");
    if (!$playerData) {
        $secretKey_error = " - Invalid secret key.";
        $has_error = true;
    }

    if (!$has_error) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $create_user = query("INSERT INTO users (username, email, password, uuid) VALUES (?, ?, ?, ?)", [$playerData['username'], $email, $hashed_password, $playerData['uuid']], "ssss");
        
        if ($create_user) {
            $_SESSION['username'] = $playerData['username'];
            $_SESSION['email'] = $email;
            $_SESSION['uuid'] = $playerData['uuid'];
            
            $sql = query("DELETE FROM auth WHERE secret = ?", [$secretKey], "s");

            header('Location: ../index.php');
            exit();
        } else {
            $password_error = "Signup failed. Please try again.";
        }
    }
}
?>